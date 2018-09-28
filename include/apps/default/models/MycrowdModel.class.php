<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：IndexModel.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTOUCH 我的众筹
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class MycrowdModel extends BaseModel {

	/**
     * 获取推荐众筹     
     */
	function recom_list(){	
		$now = time();	
		$sql = 'SELECT goods_id, cat_id, goods_name, goods_img, sum_price, start_time, end_time '.'FROM '
		. $this->pre . 'crowd_goods ' . "WHERE is_verify = 1 AND start_time <= '$now' AND end_time >= '$now' and recommend = 1 order by sort_order DESC ";
        $res = $this->query($sql);
		
		$goods = array();
        foreach ($res AS $key => $row) {
            $goods[$key]['id'] = $row['goods_id'];
            $goods[$key]['goods_name'] = $row['goods_name'];
			$goods[$key]['time'] = floor(($row['end_time']-$row['start_time'])/86400);
            $goods[$key]['like_num'] = $row['like_num'];
            $goods[$key]['buy_num'] = model('Crowdfunding')->crowd_buy_num($row['goods_id']);
			$goods[$key]['start_time'] =floor((time()-$row['start_time'])/86400);
			$goods[$key]['sum_price'] = $row['sum_price'];
            $goods[$key]['total_price'] = model('Crowdfunding')->crowd_buy_price($row['goods_id']);
            $goods[$key]['goods_img'] = $row['goods_img'];
            $goods[$key]['url'] = url('Crowdfunding/goods_info', array('id' => $row['goods_id']));
			$goods[$key]['bar'] = $goods[$key]['total_price']*100/$row['sum_price'];
			$goods[$key]['bar'] = round($goods[$key]['bar'],1); //计算百分比
			$goods[$key]['min_price'] = $this->plan_min_price($row['goods_id']); //获取方案最低价格
        }
        return $goods;
	}
	
	
	
	/**
     * 获取关注众筹列表    
     */
	function like_list($user_id = 0, $type= 0){	

		switch($type){
            case 1:
            $where = " ";                  //全部
            break;
            case 2:
            $where = " AND g.status < 1";  //进行中
            break;
            case 3:
            $where = " AND g.status = 1";  //已成功
            break;
            case 4:
            $where = " AND g.status = 2";  //已失败
            break;
        }
		
		$now = time();	
		$sql = 'SELECT g.goods_id, g.cat_id, g.goods_name, g.goods_img, g.sum_price, g.start_time, g.end_time,g.status  '.'FROM '
		. $this->pre . 'crowd_goods as g left join ' . $this->pre  ."crowd_like as cl" . " on g.goods_id=cl.goods_id " . " WHERE g.is_verify = 1 $where AND g.start_time <= '$now' AND g.end_time >= '$now' and cl.user_id = '$user_id'  order by g.sort_order DESC ";

        $res = $this->query($sql);
		$goods = array();
        foreach ($res AS $key => $row) {
            $goods[$key]['id'] = $row['goods_id'];
            $goods[$key]['goods_name'] = $row['goods_name'];
			$goods[$key]['time'] = floor(($row['end_time']-$row['start_time'])/86400);
            $goods[$key]['buy_num'] = model('Crowdfunding')->crowd_buy_num($row['goods_id']);
			$goods[$key]['start_time'] =floor((time()-$row['start_time'])/86400);
			$goods[$key]['sum_price'] = $row['sum_price'];
            $goods[$key]['total_price'] = model('Crowdfunding')->crowd_buy_price($row['goods_id']);
            $goods[$key]['goods_img'] = $row['goods_img'];
            $goods[$key]['url'] = url('Crowdfunding/goods_info', array('id' => $row['goods_id']));
			$goods[$key]['bar'] = $goods[$key]['total_price']*100/$row['sum_price'];
			$goods[$key]['bar'] = round($goods[$key]['bar'],1); //计算百分比
			$goods[$key]['min_price'] = $this->plan_min_price($row['goods_id']); //获取方案最低价格
			$goods[$key]['status'] = $row['status'];
        }
        return $goods;
	}
	
	
	/**
     * 我支持的众筹列表     
     */
	function crowd_buy_list($user_id = 0, $type= 0){	

		switch($type){
            case 1:
            $where = " ";                  //全部
            break;
            case 2:
            $where = " AND g.status < 1";  //进行中
            break;
            case 3:
            $where = " AND g.status = 1";  //已成功
            break;
            case 4:
            $where = " AND g.status = 2";  //已失败
            break;
        }

		$sql = "SELECT distinct g.goods_id, g.cat_id, g.goods_name, g.goods_img, g.sum_price, g.start_time,g.end_time,g.status  FROM ". $this->pre ."crowd_order_info as o left join  ". $this->pre ."crowd_goods as g on o.goods_id = g.goods_id". " WHERE o.user_id = '$user_id' $where and  o.extension_code = 'crowd_buy' ";
		
        $res = $this->query($sql);
		$goods = array();
        foreach ($res AS $key => $row) {
            $goods[$key]['id'] = $row['goods_id'];
            $goods[$key]['goods_name'] = $row['goods_name'];
			$goods[$key]['time'] = floor(($row['end_time']-$row['start_time'])/86400);
            $goods[$key]['like_num'] = $row['like_num'];
            $goods[$key]['buy_num'] = model('Crowdfunding')->crowd_buy_num($row['goods_id']);
			$goods[$key]['start_time'] =floor((time()-$row['start_time'])/86400);
			$goods[$key]['sum_price'] = $row['sum_price'];
            $goods[$key]['total_price'] = model('Crowdfunding')->crowd_buy_price($row['goods_id']);
            $goods[$key]['goods_img'] = $row['goods_img'];
            $goods[$key]['url'] = url('Crowdfunding/goods_info', array('id' => $row['goods_id']));
			$goods[$key]['bar'] = $goods[$key]['total_price']*100/$row['sum_price'];
			$goods[$key]['bar'] = round($goods[$key]['bar'],1); //计算百分比
			$goods[$key]['min_price'] = $this->plan_min_price($row['goods_id']); //获取方案最低价格
			$goods[$key]['status'] = $row['status'];
        }
        return $goods;
	}
	
	/**
     * 获取详情    
     */
	function crowd_user_orders($user_id, $pay = 1, $num = 10, $start = 0){		
		/* 取得订单列表 */

        $arr = array();
        if ($pay == 1) {
			 // 全部订单
            $pay = '';
        } elseif($pay == 2) {
            // 未付款 但不包含已取消、无效、退货订单的订单
            $pay = 'and pay_status = ' . PS_UNPAYED . ' and order_status not in(' . OS_CANCELED . ','. OS_INVALID .','. OS_RETURNED .')';
        }elseif($pay == 3) {
            // //代发货
            $pay = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_UNSHIPPED  ;
        }elseif($pay == 4) {
            // //待收货
            $pay = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_SHIPPED  ;
        }else{
			// 已完结
            $pay = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_RECEIVED  ;
        }

        $sql = "SELECT order_id, order_sn, shipping_id, order_status, shipping_status, pay_status, add_time,order_amount, " .
                "(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount) AS total_fee " .
                " FROM " . $this->pre .
                "crowd_order_info WHERE user_id = '$user_id' and extension_code = 'crowd_buy' " . $pay . " ORDER BY add_time DESC LIMIT $start , $num";
        $res = M()->query($sql);
        foreach ($res as $key => $value) {
            if ($value['order_status'] == OS_UNCONFIRMED) {
                $value['handler'] = "<a href=\"" . url('mycrowd/cancel_order', array('order_id' => $value['order_id'])) . "\"class=\" btn-default \" onclick=\"if (!confirm('" . L('confirm_cancel') . "')) return false;\">" .L('cancel') . "</a>";
            } else if ($value['order_status'] == OS_SPLITED) {
                /* 对配送状态的处理 */
                if ($value['shipping_status'] == SS_SHIPPED) {
                    @$value['handler'] = "<a href=\"" . url('mycrowd/affirm_received', array('order_id' => $value['order_id'])) . "\" onclick=\"if (!confirm('" . L('confirm_received') . "')) return false;\">" . L('received') . "</a>";
                } elseif ($value['shipping_status'] == SS_RECEIVED) {
                    @$value['handler'] = '<span style="color:red">' . L('ss_received') . '</span>';
                } else {
                    if ($value['pay_status'] == PS_UNPAYED) {
                        @$value['handler'] = "<a href=\"" . url('mycrowd/cancel_order', array('order_id' => $value['order_id'])) . "\">" . L('pay_money') . "</a>";
                    } else {
                        @$value['handler'] = "<a href=\"" . url('mycrowd/cancel_order', array('order_id' => $value['order_id'])) . "\">" . L('view_order') . "</a>";
                    }
                }
            } else {
                //$value['handler'] = '<span>' . L('os.' . $value['order_status']) . '</span>';
				$value['handler'] = "<a href=\"" . '#' . "\"class=\" btn-default \">" . L('os.' . $value['order_status']) . "</a>";
            }

            $value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];
            $value['status'] = L('os.' . $value['order_status']) . ',' . L('ps.' . $value['pay_status']) . ',' . L('ss.' . $value['shipping_status']);
			
			// 订单详情
			$order = model('Mycrowd')->get_order_detail($value['order_id'], $user_id);

			// 订单信息
			$sql = "SELECT og.goods_name,og.goods_id,og.goods_number,og.goods_price,cp.name FROM ". $this->pre ."crowd_order_info as og left join  ". $this->pre ."crowd_plan as cp on og.cp_id = cp.cp_id " . " WHERE og.order_id = '".$value['order_id']."' ";
			$res = $this->row($sql);
			
			// 验证是否评论
			if ($_SESSION ['user_id']) {
				$where['user_id'] = $_SESSION ['user_id'];
				$where['order_id'] = $value['order_id'];
				$rs = $this->model->table('crowd_comment')->where($where)->count();
				if ($rs > 0) {
					$rs = 1;
				}else{
					$rs = '';
				}
			}
			

            $arr[] = array(
                'order_id' => $value['order_id'],
                'order_sn' => $value['order_sn'],
                'img' => $this->order_thumb($value['order_id']),
				'order_time' => date('Y-m-d H:i:s',$value['add_time']),
                'status' => $value['status'],
                'shipping_id' => $value['shipping_id'],
                'total_fee' => price_format($value['total_fee'], false),
                //'url' => url('user/order_detail', array('order_id' => $value['order_id'])),
                'goods_count' => model('Users')->get_order_goods_count($value['order_id']),
                'handler' => $value['handler'],
				'order_status' => $value['order_status'],        //订单状态
				'pay_status' => $value['pay_status'],            //支付状态
				'shipping_status' => $value['shipping_status'],  //配送状态
				'is_comment' => $rs,                             //验证是否评论
				'goods_name' => $res['goods_name'],
				'goods_number' => $res['goods_number'],
				'goods_price' => $res['goods_price'],
				'goods_id' => $res['goods_id'],
				'name' => $res['name'],
				'pay_online' => $order['pay_online'],            //支付按钮
				'url' => url('Crowdfunding/goods_info', array('id' => $res['goods_id']))
            );

        }

        return $arr;
	}
	
	/**
    * 获取订单商品的数量
    * @param type $order_id
    * @return type
    */
    function crowd_orders_num($user_id = 0, $status = 0) {
		switch($status){
            case 1:
            $where = " ";       //全部订单
            break;
            case 2:
            $where = 'and pay_status = ' . PS_UNPAYED . ' and order_status not in(' . OS_CANCELED . ','. OS_INVALID .','. OS_RETURNED .')';  		//待支付订单
            break;
            case 3:
            $where = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_UNSHIPPED  ;		//代发货
            break;
            case 4:
            $where = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_SHIPPED  ;	    //待收货
            break;
			case 5:
            $where = 'and pay_status = ' . PS_PAYED . ' and shipping_status ='. SS_RECEIVED  ;		//已完结
            break;
        }

		$sql = "SELECT count(order_id) as num  FROM ". $this->pre ."crowd_order_info "." WHERE user_id = '".$user_id."' $where  AND extension_code = 'crowd_buy' ";
        $res = $this->row($sql);
        return $res['num'];
    }
	
	
	
	/**
    * 获取订单商品的缩略图
    * @param type $order_id
    * @return type
    */
    function order_thumb($order_id) {

        $arr = $this->row("SELECT g.goods_img FROM " . $this->model->pre . "crowd_order_info as og left join " . $this->model->pre . "crowd_goods g on og.goods_id = g.goods_id WHERE og.order_id = " . $order_id . " limit 1");		
        return $arr['goods_img'];
    }
	
	/**
     * 获取方案最低价格     
     */
	function plan_min_price($goods_id = 0){		
		$sql = 'SELECT min(shop_price) as price '.'FROM '
		. $this->model->pre . 'crowd_plan ' . "WHERE status = 1 and goods_id = '$goods_id'  ";
        $res = $this->row($sql);
        return $res['price'];
	}
	
	 /**
     *  获取指订单的详情
     *
     * @access  public
     * @param   int         $order_id       订单ID
     * @param   int         $user_id        用户ID
     *
     * @return   arr        $order          订单所有信息的数组
     */
    function get_order_detail($order_id, $user_id = 0) {

        $order_id = intval($order_id);
        if ($order_id <= 0) {
            ECTouch::err()->add(L('invalid_order_id'));

            return false;
        }
        $order = model('Mycrowd')->order_info($order_id);
        //检查订单是否属于该用户
        if ($user_id > 0 && $user_id != $order['user_id']) {
            ECTouch::err()->add(L('no_priv'));

            return false;
        }

        /* 对发货号处理 */
        if (!empty($order['invoice_no'])) {
            $sql = "SELECT shipping_code FROM " . $this->pre . "shipping WHERE shipping_id = '$order[shipping_id]'";
            $res = $this->row($sql);
            $shipping_code = $res['shipping_code'];
            $plugin = ADDONS_PATH . 'shipping/' . $shipping_code . '.php';
            if (file_exists($plugin)) {
                include_once($plugin);
                $shipping = new $shipping_code;
                $order_tracking = $shipping->query($order['invoice_no']);
                $order['order_tracking'] = ($order_tracking == $order['invoice_no']) ? 0:1;
            }
        }

        /* 只有未确认才允许用户修改订单地址 */
        if ($order['order_status'] == OS_UNCONFIRMED) {
            $order['allow_update_address'] = 1; //允许修改收货地址
        } else {
            $order['allow_update_address'] = 0;
        }

        /* 获取订单中实体商品数量 */
        $order['exist_real_goods'] = model('Order')->exist_real_goods($order_id);

        /* 如果是未付款状态，生成支付按钮 */
        if ($order['pay_status'] == PS_UNPAYED && ($order['order_status'] == OS_UNCONFIRMED || $order['order_status'] == OS_CONFIRMED)) {
            /*
             * 在线支付按钮
             */
            //支付方式信息
            $payment_info = array();
            $payment_info = Model('Order')->payment_info($order['pay_id']);
            // 只保留显示手机版支付方式
            if(!file_exists(ROOT_PATH . 'plugins/payment/'.$payment_info['pay_code'].'.php')){
                $payment_info = false;
            }

            //无效支付方式
            if ($payment_info === false || substr($payment_info['pay_code'], 0 , 4) == 'pay_') {
                $order['pay_online'] = '';
            } else {
                //取得支付信息，生成支付代码
                $payment = unserialize_config($payment_info['pay_config']);

                //获取需要支付的log_id
                $order['log_id'] = model('ClipsBase')->get_paylog_id($order['order_id'], $pay_type = PAY_ORDER);
                $order['user_name'] = $_SESSION['user_name'];
                $order['pay_desc'] = $payment_info['pay_desc'];

                /* 调用相应的支付方式文件 */
                include_once(ROOT_PATH . 'plugins/payment/' . $payment_info['pay_code'] . '.php');

                /* 取得在线支付方式的支付按钮 */
                $pay_obj = new $payment_info['pay_code'];
                $order['pay_online'] = $pay_obj->get_code($order, $payment);
            }
        } else {
            $order['pay_online'] = '';
        }

        /* 无配送时的处理 */
        $order['shipping_id'] == -1 and $order['shipping_name'] = L('shipping_not_need');

        /* 其他信息初始化 */
        $order['how_oos_name'] = $order['how_oos'];
        $order['how_surplus_name'] = $order['how_surplus'];

        /* 虚拟商品付款后处理 */
        if ($order['pay_status'] != PS_UNPAYED) {
            /* 取得已发货的虚拟商品信息 */
            $virtual_goods = model('OrderBase')->get_virtual_goods($order_id, true);
            $virtual_card = array();
            foreach ($virtual_goods AS $code => $goods_list) {
                /* 只处理虚拟卡 */
                if ($code == 'virtual_card') {
                    foreach ($goods_list as $goods) {
                        if ($info = model('OrderBase')->virtual_card_result($order['order_sn'], $goods)) {
                            $virtual_card[] = array('goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info);
                        }
                    }
                }
                /* 处理超值礼包里面的虚拟卡 */
                if ($code == 'package_buy') {
                    foreach ($goods_list as $goods) {
                        $sql = 'SELECT g.goods_id FROM ' . $this->pre . 'package_goods AS pg, ' . $this->pre . 'goods AS g ' .
                                "WHERE pg.goods_id = g.goods_id AND pg.package_id = '" . $goods['goods_id'] . "' AND extension_code = 'virtual_card'";
                        $vcard_arr = $this->query($sql);

                        foreach ($vcard_arr AS $val) {
                            if ($info = model('OrderBase')->virtual_card_result($order['order_sn'], $val)) {
                                $virtual_card[] = array('goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info);
                            }
                        }
                    }
                }
            }
            $var_card = deleteRepeat($virtual_card);
            ECTouch::view()->assign('virtual_card', $var_card);
        }

        /* 确认时间 支付时间 发货时间 */
		$order['add_time'] =  date('Y-m-d H:i:s',$order['add_time']);
        if ($order['confirm_time'] > 0 && ($order['order_status'] == OS_CONFIRMED || $order['order_status'] == OS_SPLITED || $order['order_status'] == OS_SPLITING_PART)) {
            //$order['confirm_time'] = sprintf(L('confirm_time'), local_date(C('time_format'), $order['confirm_time']));
			$order['confirm_time'] = sprintf(L('confirm_time'), date('Y-m-d H:i:s',$order['confirm_time']));
        } else {
            $order['confirm_time'] = '';
        }
        if ($order['pay_time'] > 0 && $order['pay_status'] != PS_UNPAYED) {
            //$order['pay_time'] = local_date(C('time_format'), $order['pay_time']);
			$order['pay_time'] = date('Y-m-d H:i:s',$order['pay_time']);
        } else {
            $order['pay_time'] = '';
        }
        if ($order['shipping_time'] > 0 && in_array($order['shipping_status'], array(SS_SHIPPED, SS_RECEIVED))) {
            //$order['shipping_time'] = sprintf(L('shipping_time'), local_date(C('time_format'), $order['shipping_time']));
			$order['shipping_time'] = sprintf(L('shipping_time'), date('Y-m-d H:i:s',$order['shipping_time']));
        } else {
            $order['shipping_time'] = '';
        }
		
		 // 订单 支付 配送 状态语言项
        /* $order['order_status'] = L('os.' . $order['order_status']);
        $order['pay_status'] = L('ps.' . $order['pay_status']);
        $order['shipping_status'] = L('ss.' . $order['shipping_status']); */

        return $order;
    }
	
	/**
     * 获取订单商品详情
     */
    public function order_goods($order_id = 0) {
		$sql = "SELECT g.goods_name,g.goods_id,g.goods_img,g.sum_price,og.goods_number,og.user_id,og.goods_price,cp.name FROM ". $this->pre ."crowd_order_info as og left join  ". $this->pre ."crowd_plan as cp on og.cp_id = cp.cp_id  left join " . $this->pre . "crowd_goods as g on og.goods_id = g.goods_id" . " WHERE og.order_id = '".$order_id."' ";
        $row = $this->row($sql);
        if ($row !== false) {
            $row['goods_id'] = $row['goods_id'];
            $row['goods_name'] = $row['goods_name'];
            $row['buy_num'] = model('Crowdfunding')->crowd_buy_num($row['goods_id']);
			$row['time'] = floor(($row['end_time']-$row['start_time'])/86400);
			$row['start_time'] =floor((time()-$row['start_time'])/86400);				
			//$row['shiping_time'] = local_date(C('time_format'), $row['shiping_time']);
			$row['shiping_time'] =  $row['shiping_time'];
			$row['sum_price'] = $row['sum_price'];
			$row['total_price'] =model('Crowdfunding')->crowd_buy_price($row['goods_id']);
            $row['goods_img'] = $row['goods_img'];
            $row['url'] = url('Crowdfunding/goods_info', array('id' => $row['goods_id']));
			$row['bar'] = $row['total_price']*100/$row['sum_price'];
			$row['bar'] = round($row['bar'],1); //计算百分比
			$wechat_user = $this->model->table('wechat_user')->where("ect_uid=".$row['user_id'])->field('nickname,headimgurl')->find();
			if(!empty($wechat_user)){
				$row['user_name'] =  $wechat_user['nickname'];
				$row['avatar'] = $wechat_user['headimgurl'];
			}else{
				$row['user_name'] =  $_SESSION['user_name'];
				$row['avatar'] = '';				
			}

            return $row;
        } else {
            return false;
        }
	}
	
	 /**
     * 取得订单信息
     * @param   int     $order_id   订单id（如果order_id > 0 就按id查，否则按sn查）
     * @param   string  $order_sn   订单号
     * @return  array   订单信息（金额都有相应格式化的字段，前缀是formated_）
     */
    function order_info($order_id, $order_sn = '') {
        /* 计算订单各种费用之和的语句 */
        $total_fee = " (goods_amount - discount + tax + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee) AS total_fee ";
        $order_id = intval($order_id);
        if ($order_id > 0) {
            $sql = "SELECT *, " . $total_fee . " FROM " . $this->pre .
                    "crowd_order_info WHERE order_id = '$order_id'";
        } else {
            $sql = "SELECT *, " . $total_fee . "  FROM " . $this->pre .
                    "crowd_order_info WHERE order_sn = '$order_sn'";
        }
        $order = $this->row($sql);

        /* 格式化金额字段 */
        if ($order) {
            $order['formated_goods_amount'] = price_format($order['goods_amount'], false);
            $order['formated_discount'] = price_format($order['discount'], false);
            $order['formated_tax'] = price_format($order['tax'], false);
            $order['formated_shipping_fee'] = price_format($order['shipping_fee'], false);
            $order['formated_insure_fee'] = price_format($order['insure_fee'], false);
            $order['formated_pay_fee'] = price_format($order['pay_fee'], false);
            $order['formated_pack_fee'] = price_format($order['pack_fee'], false);
            $order['formated_card_fee'] = price_format($order['card_fee'], false);
            $order['formated_total_fee'] = price_format($order['total_fee'], false);
            $order['formated_money_paid'] = price_format($order['money_paid'], false);
            $order['formated_bonus'] = price_format($order['bonus'], false);
            $order['formated_integral_money'] = price_format($order['integral_money'], false);
            $order['formated_surplus'] = price_format($order['surplus'], false);
            $order['formated_order_amount'] = price_format(abs($order['order_amount']), false);
			$order['formated_add_time'] = date('Y-m-d H:i:s',$order['add_time']);;
        }

        return $order;
    }
	
	
	 /**
     * 取消一个用户订单
     *
     * @access  public
     * @param   int         $order_id       订单ID
     * @param   int         $user_id        用户ID
     *
     * @return void
     */
    function cancel_order($order_id, $user_id = 0) {
        /* 查询订单信息，检查状态 */
        $sql = "SELECT user_id, order_id, order_sn , surplus , integral , bonus_id, order_status, shipping_status, pay_status FROM " . $this->pre . "crowd_order_info WHERE order_id = '$order_id'";
        $order = $this->row($sql);
        if (empty($order)) {
            ECTouch::err()->add(L('order_exist'));
            return false;
        }

        // 如果用户ID大于0，检查订单是否属于该用户
        if ($user_id > 0 && $order['user_id'] != $user_id) {
            ECTouch::err()->add(L('no_priv'));

            return false;
        }

        // 订单状态只能是“未确认”或“已确认”
        if ($order['order_status'] != OS_UNCONFIRMED && $order['order_status'] != OS_CONFIRMED) {
            ECTouch::err()->add(L('current_os_not_unconfirmed'));

            return false;
        }

        //订单一旦确认，不允许用户取消
        if ($order['order_status'] == OS_CONFIRMED) {
            ECTouch::err()->add(L('current_os_already_confirmed'));

            return false;
        }

        // 发货状态只能是“未发货”
        if ($order['shipping_status'] != SS_UNSHIPPED) {
            ECTouch::err()->add(L('current_ss_not_cancel'));

            return false;
        }

        // 如果付款状态是“已付款”、“付款中”，不允许取消，要取消和商家联系
        if ($order['pay_status'] != PS_UNPAYED) {
            ECTouch::err()->add(L('current_ps_not_cancel'));

            return false;
        }

        //将用户订单设置为取消
        $sql = "UPDATE " . $this->pre . "crowd_order_info SET order_status = '" . OS_CANCELED . "' WHERE order_id = '$order_id'";
        if ($this->query($sql)) {
            /* 记录log */
            model('OrderBase')->order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, L('buyer_cancel'), 'buyer');
            /* 退货用户余额、积分、红包 */
            if ($order['user_id'] > 0 && $order['surplus'] > 0) {
                $change_desc = sprintf(L('return_surplus_on_cancel'), $order['order_sn']);
                model('ClipsBase')->log_account_change($order['user_id'], $order['surplus'], 0, 0, 0, $change_desc);
            }
            if ($order['user_id'] > 0 && $order['integral'] > 0) {
                $change_desc = sprintf(L('return_integral_on_cancel'), $order['order_sn']);
                model('ClipsBase')->log_account_change($order['user_id'], 0, 0, 0, $order['integral'], $change_desc);
            }
            if ($order['user_id'] > 0 && $order['bonus_id'] > 0) {
                model('Order')->change_user_bonus($order['bonus_id'], $order['order_id'], false);
            }

            /* 如果使用库存，且下订单时减库存，则增加库存 */
           /*  if (C('use_storage') == '1' && C('stock_dec_time') == SDT_PLACE) {
                model('Order')->change_order_goods_storage($order['order_id'], false, 1);
            } */

            /* 修改订单 */
            $arr = array(
                'bonus_id' => 0,
                'bonus' => 0,
                'integral' => 0,
                'integral_money' => 0,
                'surplus' => 0
            );
            model('Mycrowd')->update_order($order['order_id'], $arr);

            return true;
        } else {
            die(M()->errorMsg());
        }
    }
	
	 /**
     * 修改订单
     * @param   int     $order_id   订单id
     * @param   array   $order      key => value
     * @return  bool
     */
    function update_order($order_id, $order) {
        $this->table = 'crowd_order_info';
        $condition['order_id'] = $order_id;
        
        $res = $this->query('DESC ' . $this->pre . $this->table);
        
        while ($row = mysqli_fetch_row($res)) {
            $field_names[] = $row[0];
        }
        foreach ($field_names as $value) {
            if (array_key_exists($value, $order) == true) {
                $order_info[$value] = $order[$value];
            }
        }
        return $this->update($condition, $order_info);
    }
	
	
	 /**
     * 确认一个用户订单
     *
     * @access  public
     * @param   int         $order_id       订单ID
     * @param   int         $user_id        用户ID
     *
     * @return  bool        $bool
     */
    function affirm_received($order_id, $user_id = 0) {
        /* 查询订单信息，检查状态 */
        $sql = "SELECT user_id, order_sn , order_status, shipping_status, pay_status FROM " . $this->pre . "crowd_order_info WHERE order_id = '$order_id'";

        $order = $this->row($sql);

        // 如果用户ID大于 0 。检查订单是否属于该用户
        if ($user_id > 0 && $order['user_id'] != $user_id) {
            ECTouch::err()->add(L('no_priv'));

            return false;
        }
        /* 检查订单 */ elseif ($order['shipping_status'] == SS_RECEIVED) {
            ECTouch::err()->add(L('order_already_received'));

            return false;
        } elseif ($order['shipping_status'] != SS_SHIPPED) {
            ECTouch::err()->add(L('order_invalid'));

            return false;
        }
        /* 修改订单发货状态为“确认收货” */ else {
            $sql = "UPDATE " . $this->pre . "crowd_order_info SET shipping_status = '" . SS_RECEIVED . "' WHERE order_id = '$order_id'";
            if ($this->query($sql)) {
                /* 记录日志 */
                model('OrderBase')->order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], '', L('buyer'));

                return true;
            } else {
                die(M()->errorMsg());
            }
        }
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
