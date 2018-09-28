<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：FlowControoller.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：购物流程控制器
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class MY_FlowController extends FlowController {

    /**
     *  提交订单
     */
    public function done() {
        /* 取得购物类型 */
        $flow_type = isset($_SESSION ['flow_type']) ? intval($_SESSION ['flow_type']) : CART_GENERAL_GOODS;
        /* 检查购物车中是否有商品 */
        $condition = " session_id = '" . SESS_ID . "' " . "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
        $count = $this->model->table('cart')->field('COUNT(*)')->where($condition)->getOne();
        if ($count == 0) {
            show_message(L('no_goods_in_cart'), '', '', 'warning');
        }
        /* 如果使用库存，且下订单时减库存，则减少库存 */
        if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
            $cart_goods_stock = model('Order')->get_cart_goods();
            $_cart_goods_stock = array();
            foreach ($cart_goods_stock ['goods_list'] as $value) {
                $_cart_goods_stock [$value ['rec_id']] = $value ['goods_number'];
            }
            model('Flow')->flow_cart_stock($_cart_goods_stock);
            unset($cart_goods_stock, $_cart_goods_stock);
        }
        // 检查用户是否已经登录 如果用户已经登录了则检查是否有默认的收货地址 如果没有登录则跳转到登录和注册页面
        if (empty($_SESSION ['direct_shopping']) && $_SESSION ['user_id'] == 0) {
            /* 用户没有登录且没有选定匿名购物，转向到登录页面 */
            ecs_header("Location: " . url('user/login') . "\n");
        }
        // 获取收货人信息
        $consignee = model('Order')->get_consignee($_SESSION ['user_id']);
        /* 检查收货人信息是否完整 */
        if (!model('Order')->check_consignee_info($consignee, $flow_type)) {
            /* 如果不完整则转向到收货人信息填写界面 */
            ecs_header("Location: " . url('flow/consignee') . "\n");
        }

        // 处理接收信息
        $how_oos = I('post.how_oos', 0);
        $card_message = I('post.card_message', '');
        $inv_type = I('post.inv_type', '');
        $inv_payee = I('post.inv_payee', '');
        $inv_content = I('post.inv_content', '');
        $postscript = I('post.postscript', '');
        $oos = L('oos.' . $how_oos);
        // 订单信息
        $order = array(
            'shipping_id' => I('post.shipping'),
            'pay_id' => I('post.payment'), // 付款方式
            'pack_id' => I('post.pack', 0),
            'card_id' => isset($_POST ['card']) ? intval($_POST ['card']) : 0,
            'card_message' => trim($_POST ['card_message']),
            'surplus' => isset($_POST ['surplus']) ? floatval($_POST ['surplus']) : 0.00,
            'integral' => isset($_POST ['integral']) ? intval($_POST ['integral']) : 0,
            'bonus_id' => isset($_POST ['bonus']) ? intval($_POST ['bonus']) : 0,
            'need_inv' => empty($_POST ['need_inv']) ? 0 : 1,
            'inv_type' => $_POST ['inv_type'],
            'inv_payee' => trim($_POST ['inv_payee']),
            'inv_content' => $_POST['inv_content'],
            'postscript' => trim($_POST ['postscript']),
            'how_oos' => isset($oos) ? addslashes("$oos") : '',
            'need_insure' => isset($_POST ['need_insure']) ? intval($_POST ['need_insure']) : 0,
            'user_id' => $_SESSION ['user_id'],
            'add_time' => gmtime(),
            'order_status' => OS_UNCONFIRMED,
            'shipping_status' => SS_UNSHIPPED,
            'pay_status' => PS_UNPAYED,
            'agency_id' => model('Order')->get_agency_by_regions(array(
                $consignee ['country'],
                $consignee ['province'],
                $consignee ['city'],
                $consignee ['district']
            ))
        );
        /* 扩展信息 */
        if (isset($_SESSION ['flow_type']) && intval($_SESSION ['flow_type']) != CART_GENERAL_GOODS) {
            $order ['extension_code'] = $_SESSION ['extension_code'];
            $order ['extension_id'] = $_SESSION ['extension_id'];
        } else {
            $order ['extension_code'] = '';
            $order ['extension_id'] = 0;
        }
        /* 检查积分余额是否合法 */
        $user_id = $_SESSION ['user_id'];
        if ($user_id > 0) {

            $user_info = model('Order')->user_info($user_id);
            $order ['surplus'] = min($order ['surplus'], $user_info ['user_money'] + $user_info ['credit_line']);
            if ($order ['surplus'] < 0) {
                $order ['surplus'] = 0;
            }

            // 查询用户有多少积分
            $flow_points = model('Flow')->flow_available_points(); // 该订单允许使用的积分
            $user_points = $user_info ['pay_points']; // 用户的积分总数

            $order ['integral'] = min($order ['integral'], $user_points, $flow_points);
            if ($order ['integral'] < 0) {
                $order ['integral'] = 0;
            }
        } else {
            $order ['surplus'] = 0;
            $order ['integral'] = 0;
        }

        /* 检查红包是否存在 */
        if ($order ['bonus_id'] > 0) {
            $bonus = model('Order')->bonus_info($order ['bonus_id']);
            if (empty($bonus) || $bonus ['user_id'] != $user_id || $bonus ['order_id'] > 0 || $bonus ['min_goods_amount'] > model('Order')->cart_amount(true, $flow_type)) {
                $order ['bonus_id'] = 0;
            }
        } elseif (isset($_POST ['bonus_sn'])) {
            $bonus_sn = trim($_POST ['bonus_sn']);
            $bonus = model('Order')->bonus_info(0, $bonus_sn);
            $now = gmtime();
            if (empty($bonus) || $bonus ['user_id'] > 0 || $bonus ['order_id'] > 0 || $bonus ['min_goods_amount'] > model('Order')->cart_amount(true, $flow_type) || $now > $bonus ['use_end_date']) {

            } else {
                if ($user_id > 0) {
                    $sql = "UPDATE " . $this->model->pre . "user_bonus SET user_id = '$user_id' WHERE bonus_id = '$bonus[bonus_id]' LIMIT 1";
                    $this->model->query($sql);
                }
                $order ['bonus_id'] = $bonus ['bonus_id'];
                $order ['bonus_sn'] = $bonus_sn;
            }
        }

        /* 订单中的商品 */
        $cart_goods = model('Order')->cart_goods($flow_type);
        if (empty($cart_goods)) {
            show_message(L('no_goods_in_cart'), L('back_home'), './', 'warning');
        }

        /* 检查商品总额是否达到最低限购金额 */
        if ($flow_type == CART_GENERAL_GOODS && model('Order')->cart_amount(true, CART_GENERAL_GOODS) < C('min_goods_amount')) {
            show_message(sprintf(L('goods_amount_not_enough'), price_format(C('min_goods_amount'), false)));
        }

        /* 收货人信息 */
        foreach ($consignee as $key => $value) {
            $order [$key] = addslashes($value);
        }

        /* 判断是不是实体商品 */
        foreach ($cart_goods as $val) {
            /* 统计实体商品的个数 */
            if ($val ['is_real']) {
                $is_real_good = 1;
            }
        }
        if (isset($is_real_good)) {
            $res = $this->model->table('shipping')->field('shipping_id')->where("shipping_id=" . $order ['shipping_id'] . " AND enabled =1")->getOne();
            if (!$res) {
                show_message(L('flow_no_shipping'));
            }
        }
        /* 订单中的总额 */
        $total = model('Users')->order_fee($order, $cart_goods, $consignee);
        $order ['bonus'] = $total ['bonus'];
        $order ['goods_amount'] = $total ['goods_price'];
        $order ['discount'] = $total ['discount'];
        $order ['surplus'] = $total ['surplus'];
        $order ['tax'] = $total ['tax'];

        // 购物车中的商品能享受红包支付的总额
        $discount_amout = model('Order')->compute_discount_amount();
        // 红包和积分最多能支付的金额为商品总额
        $temp_amout = $order ['goods_amount'] - $discount_amout;
        if ($temp_amout <= 0) {
            $order ['bonus_id'] = 0;
        }

        /* 配送方式 */
        if ($order ['shipping_id'] > 0) {
            $shipping = model('Shipping')->shipping_info($order ['shipping_id']);
            $order ['shipping_name'] = addslashes($shipping ['shipping_name']);
        }
        $order ['shipping_fee'] = $total ['shipping_fee'];
        $order ['insure_fee'] = $total ['shipping_insure'];

        /* 支付方式 */
        if ($order ['pay_id'] > 0) {
            $payment = model('Order')->payment_info($order ['pay_id']);
            $order ['pay_name'] = addslashes($payment ['pay_name']);
        }

        $order ['pay_fee'] = $total ['pay_fee'];
        $order ['cod_fee'] = $total ['cod_fee'];

        /* 商品包装 */
        if ($order ['pack_id'] > 0) {
            $pack = model('Order')->pack_info($order ['pack_id']);
            $order ['pack_name'] = addslashes($pack ['pack_name']);
        }
        $order ['pack_fee'] = $total ['pack_fee'];

        /* 祝福贺卡 */
        if ($order ['card_id'] > 0) {
            $card = model('Order')->card_info($order ['card_id']);
            $order ['card_name'] = addslashes($card ['card_name']);
        }
        $order ['card_fee'] = $total ['card_fee'];
        $order ['order_amount'] = number_format($total ['amount'], 2, '.', '');

        /* 如果全部使用余额支付，检查余额是否足够 */
        if ($payment ['pay_code'] == 'balance' && $order ['order_amount'] > 0) {
            if ($order ['surplus'] > 0) {    // 余额支付里如果输入了一个金额
                $order ['order_amount'] = $order ['order_amount'] + $order ['surplus'];
                $order ['surplus'] = 0;
            }
            if ($order ['order_amount'] > ($user_info ['user_money'] + $user_info ['credit_line'])) {
                show_message(L('balance_not_enough'));
            } else {
                $order ['surplus'] = $order ['order_amount'];
                $order ['order_amount'] = 0;
            }
        }

        /* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
        if ($order ['order_amount'] <= 0) {
            $order ['order_status'] = OS_CONFIRMED;
            $order ['confirm_time'] = gmtime();
            $order ['pay_status'] = PS_PAYED;
            $order ['pay_time'] = gmtime();
            $order ['order_amount'] = 0;
        }

        $order ['integral_money'] = $total ['integral_money'];
        $order ['integral'] = $total ['integral'];

        if ($order ['extension_code'] == 'exchange_goods') {
            $order ['integral_money'] = 0;
            $order ['integral'] = $total ['exchange_integral'];
        }

        $order ['from_ad'] = !empty($_SESSION ['from_ad']) ? $_SESSION ['from_ad'] : '0';
        $order ['referer'] = !empty($_SESSION ['referer']) ? addslashes($_SESSION ['referer']). 'Touch' : 'Touch';

        /* 记录扩展信息 */
        if ($flow_type != CART_GENERAL_GOODS) {
            $order ['extension_code'] = $_SESSION ['extension_code'];
            $order ['extension_id'] = $_SESSION ['extension_id'];
        }

        $parent_id = M()->table('users')->field('parent_id')->where("user_id=".$_SESSION['user_id'])->getOne();
        $order ['parent_id'] = $parent_id;
        $order ['drp_id'] = $_SESSION['drp_shop']['drp_id'] ? $_SESSION['drp_shop']['drp_id'] : 0;;

        /* 插入订单表 */
        $error_no = 0;
        do {
            $order ['order_sn'] = get_order_sn(); // 获取新订单号
            $new_order = model('Common')->filter_field('order_info', $order);
            $this->model->table('order_info')->data($new_order)->insert();
            $error_no = M()->errno();

            if ($error_no > 0 && $error_no != 1062) {
                die(M()->errorMsg());
            }
        } while ($error_no == 1062); // 如果是订单号重复则重新提交数据
        $new_order_id = M()->insert_id();
        $order ['order_id'] = $new_order_id;

        /* 插入订单商品 */
        $sql = "INSERT INTO " . $this->model->pre . "order_goods( " . "order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, " . "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id ) " . " SELECT '$new_order_id', goods_id, goods_name, goods_sn, product_id, goods_number, market_price, " . "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id " . " FROM " . $this->model->pre . "cart WHERE session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";
        $this->model->query($sql);

        // 更新佣金信息
        model('Sale')->update_order_sale($new_order_id);

        /* 修改拍卖活动状态 */
        if ($order ['extension_code'] == 'auction') {
            $sql = "UPDATE " . $this->model->pre . "goods_activity SET is_finished='2' WHERE act_id=" . $order ['extension_id'];
            $this->model->query($sql);
        }

        /* 处理余额、积分、红包 */
        if ($order ['user_id'] > 0 && $order ['surplus'] > 0) {
            model('ClipsBase')->log_account_change($order ['user_id'], $order ['surplus'] * (- 1), 0, 0, 0, sprintf(L('pay_order'), $order ['order_sn']));
        }
        if ($order ['user_id'] > 0 && $order ['integral'] > 0) {
            model('ClipsBase')->log_account_change($order ['user_id'], 0, 0, 0, $order ['integral'] * (- 1), sprintf(L('pay_order'), $order ['order_sn']));
        }

        if ($order ['bonus_id'] > 0 && $temp_amout > 0) {
            model('Order')->use_bonus($order ['bonus_id'], $new_order_id);
        }

        /* 如果使用库存，且下订单时减库存，则减少库存 */
        if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
            model('Order')->change_order_goods_storage($order ['order_id'], true, SDT_PLACE);
        }

        /* 给商家发邮件 */
        /* 增加是否给客服发送邮件选项 */
        if (C('send_service_email') && C('service_email') != '') {
            $tpl = model('Base')->get_mail_template('remind_of_new_order');
            $this->assign('order', $order);
            $this->assign('goods_list', $cart_goods);
            $this->assign('shop_name', C('shop_name'));
            $this->assign('send_date', date(C('time_format')));
            $content = ECTouch::$view->fetch('str:' . $tpl ['template_content']);
            send_mail(C('shop_name'), C('service_email'), $tpl ['template_subject'], $content, $tpl ['is_html']);
        }

        /* 如果需要，发短信 */
        if (C('sms_order_placed') == '1' && C('sms_shop_mobile') != '') {
            $sms = new EcsSms();
            $msg = $order ['pay_status'] == PS_UNPAYED ? L('order_placed_sms') : L('order_placed_sms') . '[' . L('sms_paid') . ']';
            $sms->send(C('sms_shop_mobile'), sprintf($msg, $order ['consignee'], $order ['mobile']), '', 13, 1);
        }
        /* 如果需要，微信通知 by wanglu */
        if (method_exists('WechatController', 'snsapi_base') && is_wechat_browser()) {
            $order_url = __HOST__ . url('user/order_detail', array('order_id' => $order ['order_id']));
            $order_url = urlencode(base64_encode($order_url));
            send_wechat_message('order_remind', '', $order['order_sn'] . L('order_effective'), $order_url, $order['order_sn']);
        }

        // 推送消息
        $message_status = M()->table('drp_config')->field('value')->where('keyword = "msg_open"')->getOne();
        if (method_exists('WechatController', 'send_message') && $order['pay_status'] == PS_PAYED && $message_status=='open') {

            // 模版信息设置
            $data['openid'] = '';
            $data['open_id'] = 'OPENTM206547887';
            $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].url('sale/order_detail',array('order_id'=>$new_order_id));
            $data['first'] = '下线会员卖出商品';  // 简介
            $data['keyword1'] = $order ['order_sn'];  // 订单编号
            $data['keyword2'] = $this->model->table('order_goods')->field('goods_name')->where("order_id ='".$new_order_id."'")->getOne();  // 商品名称
            $data['keyword3'] = local_date('Y-m-d H:i:s',($order ['add_time'])); // 下单时间
            $data['keyword4'] = price_format($order ['goods_amount']);  // 下单金额
            $data['keyword5'] = '';  // 分销商名称

            // 获取订单所属店铺信息
            $drp_id = M()->table('drp_order_info')->field('drp_id')->where('order_id = ' . $new_order_id)->getOne();
            if($drp_id){
                // 本店用户id
                $user_id = M()->table('drp_shop')->field('user_id')->where('id = ' . $drp_id)->getOne();
                if($user_id){
                    // 获取openid 和 微信昵称
                    $userInfo = M()->table('wechat_user')->field('openid,nickname')->where('ect_uid = ' . $user_id)->find();
                    $data['openid'] = $userInfo['openid'];
                    $data['keyword5'] = $userInfo['nickname'];
                    if($data['openid']){
                        sendTemplateMessage($data);
                    }
                    // 一级用户id
                    $parent_id1 = M()->table('users')->field('parent_id')->where('user_id = ' . $user_id)->getOne();
                    if($parent_id1){
                        // 获取openid 和 微信昵称
                        $userInfo = M()->table('wechat_user')->field('openid,nickname')->where('ect_uid = ' . $parent_id1)->find();
                        $data['openid'] = $userInfo['openid'];
                        $data['keyword5'] = $userInfo['nickname'];
                        if($data['openid']){
                            sendTemplateMessage($data);
                        }
                        // 二级用户id
                        $parent_id2 = M()->table('users')->field('parent_id')->where('user_id = ' . $parent_id1)->getOne();
                        if($parent_id2) {
                            // 获取openid 和 微信昵称
                            $userInfo = M()->table('wechat_user')->field('openid,nickname')->where('ect_uid = ' . $parent_id2)->find();
                            $data['openid'] = $userInfo['openid'];
                            $data['keyword5'] = $userInfo['nickname'];
                            if($data['openid']){
                                sendTemplateMessage($data);
                            }
                        }
                    }
                }


            }
         }
        /* 如果订单金额为0 处理虚拟卡 */
        if ($order ['order_amount'] <= 0) {
            $sql = "SELECT goods_id, goods_name, goods_number AS num FROM " . $this->model->pre . "cart WHERE is_real = 0 AND extension_code = 'virtual_card'" . " AND session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";
            $res = $this->model->query($sql);

            $virtual_goods = array();
            foreach ($res as $row) {
                $virtual_goods ['virtual_card'] [] = array(
                    'goods_id' => $row ['goods_id'],
                    'goods_name' => $row ['goods_name'],
                    'num' => $row ['num']
                );
            }

            if ($virtual_goods and $flow_type != CART_GROUP_BUY_GOODS) {
                /* 虚拟卡发货 */
                if (model('OrderBase')->virtual_goods_ship($virtual_goods, $msg, $order ['order_sn'], true)) {
                    /* 如果没有实体商品，修改发货状态，送积分和红包 */
                    $count = $this->model->table('order_goods')->field('COUNT(*)')->where("order_id = '$order[order_id]' " . " AND is_real = 1")->getOne();
                    if ($count <= 0) {
                        /* 修改订单状态 */
                        model('Users')->update_order($order ['order_id'], array(
                            'shipping_status' => SS_SHIPPED,
                            'shipping_time' => gmtime()
                        ));

                        /* 如果订单用户不为空，计算积分，并发给用户；发红包 */
                        if ($order ['user_id'] > 0) {
                            /* 取得用户信息 */
                            $user = model('Order')->user_info($order ['user_id']);

                            /* 计算并发放积分 */
                            $integral = model('Order')->integral_to_give($order);
                            model('ClipsBase')->log_account_change($order ['user_id'], 0, 0, intval($integral ['rank_points']), intval($integral ['custom_points']), sprintf(L('order_gift_integral'), $order ['order_sn']));

                            /* 发放红包 */
                            model('Order')->send_order_bonus($order ['order_id']);
                        }
                    }
                }
            }
        }

        // 销量
        model('Flow')->add_touch_goods($flow_type, $order ['extension_code']);
        /* 清空购物车 */
        model('Order')->clear_cart($flow_type);
        /* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
        clear_all_files();

        /* 插入支付日志 */
        $order ['log_id'] = model('ClipsBase')->insert_pay_log($new_order_id, $order ['order_amount'], PAY_ORDER);

        /* 取得支付信息，生成支付代码 */
        if ($order ['order_amount'] > 0) {
            $payment = model('Order')->payment_info($order ['pay_id']);

            include_once (ROOT_PATH . 'plugins/payment/' . $payment ['pay_code'] . '.php');

            $pay_obj = new $payment ['pay_code'] ();
            $pay_online = $pay_obj->get_code($order, unserialize_config($payment ['pay_config']));

            $order ['pay_desc'] = $payment ['pay_desc'];

            $this->assign('pay_online', $pay_online);
        }
        if (!empty($order ['shipping_name'])) {
            $order ['shipping_name'] = trim(stripcslashes($order ['shipping_name']));
        }
        // 如果是银行汇款或货到付款 则显示支付描述
        if ($payment['pay_code'] == 'bank' || $payment['pay_code'] == 'cod'){
            if (empty($order ['pay_name'])) {
                $order ['pay_name'] = trim(stripcslashes($payment ['pay_name']));
            }
            $this->assign('pay_desc',$order['pay_desc']);
        }
        // 货到付款不显示
        if ($payment ['pay_code'] != 'balance') {
            /* 生成订单后，修改支付，配送方式 */

            // 支付方式
            $payment_list = model('Order')->available_payment_list(0);
            if (isset($payment_list)) {
                foreach ($payment_list as $key => $payment) {

                    /* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
                    if ($payment ['pay_code'] == 'yeepayszx' && $total ['amount'] > 300) {
                        unset($payment_list [$key]);
                    }
                    // 过滤掉当前的支付方式
                    if ($payment ['pay_id'] == $order ['pay_id']) {
                        unset($payment_list [$key]);
                    }
                    /* 如果有余额支付 */
                    if ($payment ['pay_code'] == 'balance') {
                        /* 如果未登录，不显示 */
                        if ($_SESSION ['user_id'] == 0) {
                            unset($payment_list [$key]);
                        } else {
                            if ($_SESSION ['flow_order'] ['pay_id'] == $payment ['pay_id']) {
                                $this->assign('disable_surplus', 1);
                            }
                        }
                    }
                    // 如果不是微信浏览器访问并且不是微信会员 则不显示微信支付
                    if ($payment ['pay_code'] == 'wxpay' && !is_wechat_browser() && empty($_SESSION['openid'])) {
                        unset($payment_list [$key]);
                    }
                    // 兼容过滤ecjia支付方式
                    if (substr($payment['pay_code'], 0 , 4) == 'pay_') {
                        unset($payment_list[$key]);
                    }
                }
            }
            $this->assign('payment_list', $payment_list);
            $this->assign('pay_code', 'no_balance');
        }



        /* 订单信息 */
        $this->assign('order', $order);
        $this->assign('total', $total);
        $this->assign('goods_list', $cart_goods);
        $this->assign('order_submit_back', sprintf(L('order_submit_back'), L('back_home'), L('goto_user_center'))); // 返回提示

        user_uc_call('add_feed', array($order ['order_id'], BUY_GOODS)); // 推送feed到uc
        unset($_SESSION ['flow_consignee']); // 清除session中保存的收货人信息
        unset($_SESSION ['flow_order']);
        unset($_SESSION ['direct_shopping']);

        $this->assign('currency_format', C('currency_format'));
        $this->assign('integral_scale', C('integral_scale'));
        $this->assign('step', ACTION_NAME);

        $this->assign('title', L('order_submit'));

        $this->display('flow.dwt');
    }


}
