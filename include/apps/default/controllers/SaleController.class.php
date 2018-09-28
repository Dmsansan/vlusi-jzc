<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：SaleController.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTouch用户中心
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class SaleController extends CommonController {

    protected $user_id;
    protected $action;
    protected $back_act = '';
    private $drp = null;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        // 属性赋值
        $this->user_id = $_SESSION['user_id'];
        $this->action = ACTION_NAME;
        // 分销商信息
        $this->drp = model('Sale')->get_drp($this->user_id);

        $this->check_login();
        // 用户信息
        $info = model('ClipsBase')->get_user_default($this->user_id);

        // 如果是显示页面，对页面进行相应赋值
        assign_template();
        $this->assign('action', $this->action);
        $this->assign('info', $info);

        $this->assign('user_id',session('user_id'));
    }


    /**
     * 会员中心欢迎页
     */
    public function index() {
        $shop = $this->model->table('drp_shop')->where(array('user_id'=>$_SESSION['user_id']))->field('id, create_time,shop_name')->find();
        $shop['time'] = local_date('Y-m-d H:i:s',$shop['create_time']);
        $shop['shop_name'] = $shop['shop_name'];
        $this->assign('sale', $shop);
        // 总销售额
        $sale_money = model('Sale')->get_sale_money_total();
        $this->assign('sale_money_order',$sale_money ? $sale_money : '0.00');
        // 佣金总额
        $sale_money = model('Sale')->saleMoney();
        $this->assign('sale_money',$sale_money ? $sale_money : '0.00');
        // 今日收入
        $sale_money_today = model('Sale')->saleMoney_today();
        $this->assign('sale_money_today',$sale_money_today ? $sale_money_today : '0.00');
        $this->assign('custom',$this->custom);
        $this->assign('title', L('sale'));
        $this->display('sale.dwt');
    }

    /**
     * 我的店铺
     */
    public function shop_config(){
        if(IS_POST){
            $data = $_POST['data'];
            $data = I('data');
            if (empty($data['shop_name'])){
                show_message(L('shop_name_empty'));
            }
            if (empty($data['real_name'])){
                show_message(L('real_name_empty'));
            }
            if (empty($data['shop_mobile'])){
                show_message(L('shop_mobile_empty'));
            }
            if(!empty($_FILES['shop_img']['name'])){
                $result = $this->uploadImage();
                if ($result['error'] > 0) {
                    show_message($result['message']);
                }

                $data['shop_img'] = $result['message']['shop_img']['savename'];
            }
            $where['user_id'] = $_SESSION['user_id'];
            $this->model->table('drp_shop')->data($data)->where($where)->update();
            show_message(L('success'),$this->custom.'中心',url('sale/index'));
        }
        $drp_info = $this->model->table('drp_shop')->field('shop_name,real_name,shop_mobile,shop_img,shop_qq')->where('user_id='.session('user_id'))->select();
        $this->assign('drp_info',$drp_info['0']);
        $this->assign('title', L('shop_config'));
        $this->display('sale_shop_config.dwt');
    }

    /**
     * 我的商品
     */
    public function my_goods(){
        if(IS_POST){
            $cateArr = I('cate');
            $cat_id = '';
            if($cateArr){
                foreach($cateArr as $key=>$val){
                    $cat_id.=$val.',';
                }
            }else{
                show_message(L('sale_cate_not_empty'));
            }
            $data['cat_id'] = $cat_id;
            $where['user_id'] = $_SESSION['user_id'];
            $this->model->table('drp_shop')->data($data)->where($where)->update();
            show_message(L('success'),$this->custom.'中心',url('sale/index'));
        }
        $cat_id = $this->model->table('drp_shop')->field("cat_id")->where(array("user_id"=>$_SESSION['user_id']))->getOne();
        $catArr = explode(',',$cat_id);
        if($catArr){
            unset($catArr[(count($catArr)-1)]);
        }
        $category = $this->model->table('category')->field("cat_id,cat_name")->where(array("parent_id"=>0,"is_show"=>1))->order('sort_order ASC, cat_id ASC')->select();
        if($category){
            foreach($category as $key=>$val){
                $category[$key]['profit1'] = $this->model->table('drp_profit')->field("profit1")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit1'] = $category[$key]['profit1'] ? $category[$key]['profit1'] : 0;
                $category[$key]['profit2'] = $this->model->table('drp_profit')->field("profit2")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit2'] = $category[$key]['profit2'] ? $category[$key]['profit2'] : 0;
                $category[$key]['profit3'] = $this->model->table('drp_profit')->field("profit3")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit3'] = $category[$key]['profit3'] ? $category[$key]['profit3'] : 0;
                if(in_array($val['cat_id'],$catArr)){
                    $category[$key]['is_select'] = 1;
                }
            }
        }
        $this->assign('custom',$this->custom);
        $this->assign('category',$category);
        $this->assign('title', '我的商品');
        $this->display('sale_my_goods.dwt');
    }

    /**
     * 佣金管理
     */
    public function account_detail() {
        $this->assign('key',I('key'));
        // 获取剩余余额
        $surplus_amount = model('Sale')->saleMoney($this->user_id);
        if (empty($surplus_amount)) {
            $surplus_amount = 0;
        }

        $size = I(C('page_size'), 10);
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $where = 'o.user_id = ' . $this->user_id;
        $sql = "select COUNT(*) as count from {pre}drp_log as d right join {pre}order_info as o on d.order_id = o.order_id  where $where";
        $count = $this->model->query($sql);
        $count = $count['0']['count'];
        $this->pageLimit(url('sale/account_detail'), $size);
        $this->assign('pager', $this->pageShow($count));
        $account_detail = model('Sale')->get_sale_log($this->user_id, $size, ($page-1)*$size);
        $this->assign('title', L('add_surplus_log'));
        $this->assign('surplus_amount', price_format($surplus_amount, false));
        $this->assign('account_log', $account_detail);
        $dwt = $account_detail ? 'sale_account_detail.dwt' : 'sale_show_message.dwt';
        $this->display($dwt);
    }

    /**
     *  会员申请提现
     */
    public function account_raply(){
        $bank = $this->model->table('drp_shop')->where(array('user_id'=>$_SESSION['user_id']))->field('bank')->find();
        $bank_info = array();
        if($bank['bank']){
            $bank_info = $this->model->table('drp_bank')->where("id=".$bank['bank'])->select();
        }
        $this->assign('bank_info',$bank_info['0']);
        // 获取剩余余额
        $surplus_amount = $this->model->table('drp_shop')->where('user_id='.$this->user_id)->field('money')->getOne();
        if (empty($surplus_amount)) {
            $surplus_amount = 0;
        }
        $this->assign('surplus_amount', price_format($surplus_amount, false));
        $txxz =  $this->model->getRow("select value from {pre}drp_config where keyword='txxz'");
        $this->assign('txxz',$txxz['value']);
        $this->assign('title', L('label_user_surplus'));
        $this->display('sale_account_raply.dwt');
    }

    /**
     *  对会员佣金申请的处理
     */
    public function act_account()
    {
        $bank_id = I('bank');
        $bank = model('Sale')->get_bank_info($bank_id);
        if(!$bank){
            show_message('请选择提现的银行卡');
        }
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        if (!is_numeric($amount)){
            show_message(L('amount_gt_zero'));
        }elseif ($amount <= 0)
        {
            show_message(L('amount_gt_zero'));
        }
        $txxz =  $this->model->getRow("select value from {pre}drp_config where keyword='txxz'");
        if($txxz['value'] > $_POST['amount']){
            show_message('提现金额必须大于'.$txxz['value'].'元');
        }

        /* 判断是否有足够的余额的进行退款的操作 */
        $sur_amount =  $this->model->table('drp_shop')->where('user_id='.$this->user_id)->field('money')->getOne();
        if ($amount > $sur_amount)
        {
            show_message('佣金金额不足', L('back_page_up'), '', 'info');
        }
        /* 插入帐户变动记录 */
        $account_log = array(
            'user_id'       => $this->user_id,
            'user_money'    => '-'.$amount,
            'change_time'   => gmtime(),
            'change_desc'   => L('drp_log_desc'),
            'change_type'   => DRP_WITHDRAW,
            'bank_info'     => "银行所在地：".$bank['bank_region']."  开户银行：".$bank['bank_name']."  开户姓名：".$bank['bank_user_name']."  帐号：".$bank['bank_card'],
            'status'        => 0
        );

        $this->model->table('drp_log')
            ->data($account_log)
            ->insert();

        /* 更新用户信息 */
        $sql = "UPDATE {pre}drp_shop" .
            " SET money = money - ('$amount')" .
            " WHERE user_id = '$this->user_id' LIMIT 1";
        $this->model->query($sql);
		
		// 推送消息
        $message_status = M()->table('drp_config')->field('value')->where('keyword = "msg_open"')->getOne();
        if (method_exists('WechatController', 'send_message') && $message_status=='open') {
			 // 模版信息设置
			// 获取openid 和 微信昵称
            $userInfo = M()->table('wechat_user')->field('openid,nickname')->where('ect_uid = ' . $this->user_id)->find();		
            $data['openid'] = $userInfo['openid'];  
			$data['open_id'] = 'OPENTM400075274';
            $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].url('sale/account_detail');
            $data['first'] = $this->custom . '结款通知';  // 简介
			$data['keyword1'] = $amount;  // 结款金额
            $data['keyword2'] = $bank['bank_card'];  // 银行卡号
            if($data['openid']){
               sendTemplateMessage($data);
            }			
		}
		
        $content = L('surplus_appl_submit');
        show_message($content, L('back_account_log'), url('sale/account_detail'), 'info');
    }

    /**
     * 我的佣金
     */
    public function my_commission(){


        $info = $this->model->getRow("SELECT * FROM " .$this->model->pre ."drp_config WHERE id = '3'");
        $this->assign('info',$info);

        $saleMoney_surplus =  model('Sale')->saleMoney_surplus();
        $this->assign('saleMoney_surplus',$saleMoney_surplus);
        $saleMoney =  model('Sale')->saleMoney();
        $this->assign('saleMoney',$saleMoney);
        // 未分成销售佣金
        $sale_money = model('Sale')->get_shop_sale_money($this->user_id);
        $this->assign('sale_no_money',$sale_money['profit']);
        $this->assign('sale_no_money1',$sale_money['profit1']);
        $this->assign('sale_no_money2',$sale_money['profit2']);
        $this->assign('sale_no_money_num',$sale_money['profit_num']);

        // 已分成销售佣金
        $sale_money = model('Sale')->get_shop_sale_money($this->user_id,1);
        $this->assign('sale_money',$sale_money['profit']);
        $this->assign('sale_money1',$sale_money['profit1']);
        $this->assign('sale_money2',$sale_money['profit2']);
        $this->assign('sale_money_num',$sale_money['profit_num']);
        $this->assign('title','我的佣金');
        $this->display('sale_my_commission.dwt');
    }

    /**
     * 推广二维码
     */
    public function spread(){
        $id = I('u') ? I('u') : $this->user_id;
        if(!isset($_GET['u'])){
            redirect(url('sale/spread',array('u'=>$id)));
        }  
		$this->check_open($id);
        // 创建目录
        $filename  = ROOT_PATH.'data/attached/drp';
        if(!file_exists($filename)){
            mkdir($filename);
        }
        $bg_img = ROOT_PATH.'data/attached/drp/tg-bg.png';//背景图
        $ew_img = ROOT_PATH.'data/attached/drp/tg-ewm-'.$id.'.png';//二维码
        $dp_img = ROOT_PATH.'data/attached/drp/tg-dp-'.$id.'.png';//店铺二维码
        $wx_img = ROOT_PATH.'data/attached/drp/tg-wx-'.$id.'.png';//微信头像
        $dp_img_size = filesize($dp_img);
        $ew_img_size = filesize($ew_img);
        if(!file_exists($dp_img) || empty($dp_img_size) || !file_exists($ew_img) || empty($ew_img_size)){
            if(!file_exists($ew_img) || empty($ew_img_size)){
                $b = call_user_func(array('WechatController', 'rec_qrcode'), $_SESSION['user_name'],$id);
                $b = preg_replace('/https/','http',$b,1);
                logResult('Local:1');
                if(empty($b)){
                    $b = call_user_func(array('WechatController', 'rec_qrcode'), $_SESSION['user_name'],$id,0,'',true);
                    $b = preg_replace('/https/','http',$b,1);
                    logResult('Local:2');
                    //logResult(var_export($_SESSION, true));
                }
                if(empty($b)){
                    $drp_id = M()->table('drp_shop')->field('id')->where("user_id=".$id)->getOne();
                    // 二维码
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?u='.$id.'&drp_id='.$drp_id;
                    // 纠错级别：L、M、Q、H
                    $errorCorrectionLevel = 'M';
                    // 点的大小：1到10
                    $matrixPointSize = 13;
                    @QRcode::png($url, $ew_img, $errorCorrectionLevel, $matrixPointSize, 2);
                    logResult('Local:3');
                }else{
                    $img = Http::doGet($b);
                    file_put_contents($ew_img,$img);
                }
                Image::thumb($ew_img, $ew_img,'','330','330'); // 将图片重新设置大小
            }
            // 获取微信头像
            if(class_exists('WechatController')){
                if (method_exists('WechatController', 'get_avatar')) {
                    $info = call_user_func(array('WechatController', 'get_avatar'), $id);
                }
            }
            if($info['avatar']){
                $info['avatar']=preg_replace('/https/','http',$info['avatar'],1);
                $thumb = Http::doGet($info['avatar']);
                file_put_contents($wx_img,$thumb);

                Image::thumb($wx_img, $wx_img,'','100','100'); // 将图片重新设置大小
            }

            // 生成海报图片
            $img = file_get_contents($bg_img);
            file_put_contents($dp_img,$img);
            chmod(ROOT_PATH.$dp_img, 0777);

            // 添加二维码水印
            if(file_get_contents($ew_img)){
                Image::water($dp_img,$ew_img,12);
            }

            // 添加微信头像水印
            if($info['avatar']){
                Image::water($dp_img,$wx_img,13);
            }
        }
		// 查询推广用户信息
        $shopuser = $this->model->table('users')->where(array('user_id'=> $id))->find();
        if(empty($shopuser)){
            redirect(url('sale/index'));
        }else{
            $info = array(
                'name' => $shopuser['user_name'],
                'avatar' => ''
            );
        }
        // 查询微信用户信息
        $wechatuser = $this->model->table('wechat_user')->where(array('ect_uid'=>$id))->find();
        if(!empty($wechatuser)){
            $info = array(
                'name' => $wechatuser['nickname'],
                'avatar' => $wechatuser['headimgurl']
            );
        }
        // 销售二维码
        $this->assign('mobile_qr', 'data/attached/drp/tg-dp-'.$id.'.png');
        $this->assign('info', $info);
        $this->assign('title',L('spread'));
        $this->display('sale_spread.dwt');
    }

    /**
     * 店铺二维码
     */
    public function store(){
        $id = I('u') ? I('u') : $this->user_id;
        if(!isset($_GET['u'])){
            redirect(url('sale/store',array('u' => $id)));
        }
		$this->check_open($id);
        $filename  = ROOT_PATH.'data/attached/drp';
        if(!file_exists($filename)){
            mkdir($filename);
        }

        $bg_img = ROOT_PATH.'data/attached/drp/dp-bg.png';//背景图
        $ew_img = ROOT_PATH.'data/attached/drp/dp-ewm-'.$id.'.png';//二维码
        $dp_img = ROOT_PATH.'data/attached/drp/dp-dp-'.$id.'.png';//店铺二维码
        $wx_img = ROOT_PATH.'data/attached/drp/dp-wx-'.$id.'.png';//微信头像
        $filesize = filesize($dp_img);
        if(!file_exists($dp_img) || empty($filesize) || !file_exists($ew_img)){
            if(!file_exists($ew_img)){
                $drp_id = M()->table('drp_shop')->field('id')->where("user_id=".$id)->getOne();
                // 二维码
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?c=store&u='.$id.'&drp_id='.$drp_id;
                // 纠错级别：L、M、Q、H
                $errorCorrectionLevel = 'M';
                // 点的大小：1到10
                $matrixPointSize = 13;
                @QRcode::png($url, $ew_img, $errorCorrectionLevel, $matrixPointSize, 2);
            }

            // 获取微信头像

            $info = model('ClipsBase')->get_user_default($id);
            if(class_exists('WechatController')){
                if (method_exists('WechatController', 'get_avatar')) {
                    $info = call_user_func(array('WechatController', 'get_avatar'), $id);
                }
            }
            if($info['avatar']){
                $info['avatar']=preg_replace('/https/','http',$info['avatar'],1);
                $thumb = Http::doGet($info['avatar']);
                file_put_contents($wx_img,$thumb);
                Image::thumb($wx_img, $wx_img,'','100','100'); // 将图片重新设置大小
            }

            // 生成海报图片
            $img = file_get_contents($bg_img);
            file_put_contents($dp_img,$img);
            chmod(ROOT_PATH.$dp_img, 0777);
            // 添加二维码水印
            Image::water($dp_img,$ew_img,10);
            // 添加微信头像水印
            if($info['avatar']) {
                Image::water($dp_img, $wx_img, 11);
            }
        }
        $this->assign('mobile_qr', 'data/attached/drp/dp-dp-'.$id.'.png');
        $this->assign('title',L('store'));
        $this->display('sale_store.dwt');
    }
    /**
     * 本店订单
     */
    public function order_list(){
        $user_id = I('user_id') > 0 ? I('user_id') : $_SESSION['user_id'];
        $drp_id = $this->model->table('drp_shop')->field('id')->where("user_id=".$user_id)->getOne();
        if(!$drp_id){
            show_message('此用户尚未开店，无法查看订单');
        }
        $size = I(C('page_size'), 5);
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $where = 'd.drp_id = '.$drp_id;
        $sql = "select count(*) as count from {pre}drp_order_info as d right join {pre}order_info as o on d.order_id=o.order_id where d.drp_id=$drp_id";
        $count = $this->model->getRow($sql);
        $count = $count['count'] ? $count['count'] : 0;
        $this->pageLimit(url('sale/order_list'), $size);
        $this->assign('pager', $this->pageShow($count));
        $orders = model('Sale')->get_sale_orders($where ,  $size, ($page-1)*$size,$user_id);

        if($orders){
            foreach($orders as $key=>$val){
                foreach($val['goods'] as $k=>$v){
                    $orders[$key]['goods'][$k]['profit'] = model('Sale')->get_drp_order_profit($val['order_id'],$v['goods_id']);
                }
            }
        }
        $this->assign('orders_list', $orders);
        $this->assign('title', L('order_list'));
        $dwt = $orders ? 'sale_order_list.dwt' : 'sale_show_message.dwt';
        $this->display($dwt);
    }
    /**
     * 订单详情
     */
    public function goods_detai(){
        $order_id = I('order_id') > 0 ? I('order_id') :'';
        $where = 'd.order_id = '.$order_id;
        $user_id = I('user_id') > 0 ? I('user_id') : $_SESSION['user_id'];
        $orders = model('Sale')->get_sale_goods_detai($where, $user_id);
        if($orders){
            foreach($orders as $key=>$val){
                foreach($val['goods'] as $k=>$v){
                    $orders[$key]['goods'][$k]['profit'] = model('Sale')->get_drp_order_profit($val['order_id'],$v['goods_id']);
                    $orders[$key]['goods'][$k]['money'] = price_format($v['touch_sale']/100*$orders[$key]['goods'][$k]['profit']*$v['goods_number'],false);
                    $orders[$key]['goods'][$k]['touch_sale'] = price_format($v['touch_sale'],false);
                }
            }
        }
		$this->assign('title', '订单详情');
        $this->assign('orders_list', $orders);
        $this->display('sale_goods_detail.dwt');
    }
    public function my_shop_info(){

        // 总销售额 
        $sale_money = model('Sale')->get_sale_money_total();
        $this->assign('money',$sale_money ? $sale_money : '0.00');
		
        // 一级分店数
        $sql = "select count(*) count from {pre}users as u JOIN {pre}drp_shop d ON  u.user_id=d.user_id WHERE u.parent_id = ".$_SESSION['user_id'];
        $shop_count = $this->model->getRow($sql);
        $this->assign('shop_count', $shop_count['count'] ? $shop_count['count'] : 0);

        // 我的会员数
        $user_count = M()->table('users')->where("parent_id=".$_SESSION['user_id'])->count();
        $this->assign('user_count', $user_count ? $user_count : 0);

        // 店铺订单数
        $sql = "select count(*) as count from {pre}drp_order_info as d right join {pre}order_info as o on d.order_id=o.order_id  where d.drp_id = ".$this->drp['drp_id'];
        $order_count = $this->model->getRow($sql);
        $this->assign('order_count', $order_count['count'] ? $order_count['count'] : 0);

        $this->assign('title', L('my_shop_info'));
        $this->display('sale_my_shop_info.dwt');
    }

    /**
     * 我的分店
     */
    public function my_shop_list(){
        $key = I('key') ? I('key') : '1';
        $list = model('Sale')->get_shop_list($key);
        $this->assign('list', $list);
        $this->assign('custom',$this->custom);
        $this->assign('title', L('my_shop_list'.$key));
        $dwt = $list ? 'sale_my_shop_list.dwt' : 'sale_show_message.dwt';
        $this->display($dwt);
    }



    /**
     * 微店设置
     */
    public function sale_set(){
		$buy_money = $this->model->table('drp_config')->field("value")->where(array("keyword"=>'buy_money'))->getOne();//是否开启		
		if($buy_money == open){
			$buy = $this->model->table('drp_config')->field("value")->where(array("keyword"=>'buy'))->getOne();//设置金额
			$sql ="select sum(goods_amount) as money from {pre}order_info where pay_status= 2 and user_id = ".$_SESSION['user_id'] ;
			$money = $this->model->getRow($sql);
			if($money['money'] >= $buy){
				$info = $this->model->table('drp_shop')->where(array("user_id"=>$_SESSION['user_id']))->select();
				if($info){
					if($info['0']['cat_id']==''){
						redirect(url('sale/sale_set_category'));
					}
					else{
						redirect(url('sale/index'));
					}
				}				
			}else{
				show_message('您的累计消费金额未达到开店要求，再接再厉','返回商城',url('user/index'));				
			}
		}else{
			$info = $this->model->table('drp_shop')->where(array("user_id"=>$_SESSION['user_id']))->select();
			if($info){
				if($info['0']['cat_id']==''){
					redirect(url('sale/sale_set_category'));
				}
				else{
					redirect(url('sale/index'));
				}
			}			
		}
        if (IS_POST){
            $data = I('data');
            if (empty($data['shop_name'])){
                show_message(L('shop_name_empty'));
            }
            if (empty($data['real_name'])){
                show_message(L('real_name_empty'));
            }
            if (empty($data['shop_mobile'])){
                show_message(L('shop_mobile_empty'));
            }
            // if (empty($data['shop_qq'])){
            //     show_message(L('shop_qq_empty'));
            // }
            $data['shop_name'] = $data['shop_name'];
            $data['user_id'] = $_SESSION['user_id'];
            $data['create_time'] = gmtime();
            $_SESSION['enable_drp_shop'] = $data;
            redirect(url('sale/sale_set_category'));
        }
        $this->assign('title',L('sale_set'));
        $this->display('sale_set.dwt');
    }

    /**
     * 设置分销商品的分类
     */
    public function sale_set_category(){
        // if($this->model->table('drp_shop')->where(array("user_id"=>$_SESSION['user_id'],"open"=>1,'cat_id'=>''))->count() > 0){
        //     redirect(url('sale/index'));
        // }
        if(IS_POST){
            $cateArr = I('cate');
            $cat_id = '';
            if($cateArr){
                foreach($cateArr as $key=>$val){
                    $cat_id.=$val.',';
                }
            }else{
                show_message(L('sale_cate_not_empty'));
            }
            $_SESSION['enable_drp_shop']['cat_id'] = $cat_id;
            $where['user_id'] = $_SESSION['user_id'];
            // $this->model->table('drp_shop')->data($data)->where($where)->update();
            redirect(url('sale/sale_set_end'));
        }
        $apply = $this->model->table('drp_config')->field("value")->where(array("keyword"=>'apply'))->getOne();
        $this->assign('apply',$apply);
        $category = $this->model->table('category')->field("cat_id,cat_name")->where(array("parent_id"=>0,"is_show"=>1))->order('sort_order ASC, cat_id ASC')->select();
        if($category){
            foreach($category as $key=>$val){
                $category[$key]['profit1'] = $this->model->table('drp_profit')->field("profit1")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit1'] = $category[$key]['profit1'] ? $category[$key]['profit1'] : 0;
                $category[$key]['profit2'] = $this->model->table('drp_profit')->field("profit2")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit2'] = $category[$key]['profit2'] ? $category[$key]['profit2'] : 0;
                $category[$key]['profit3'] = $this->model->table('drp_profit')->field("profit3")->where(array("cate_id"=>$val['cat_id']))->getOne();
                $category[$key]['profit3'] = $category[$key]['profit3'] ? $category[$key]['profit3'] : 0;
            }
        }
        $this->assign('custom',$this->custom);
        $this->assign('category',$category);
        $this->assign('title',L('sale_set_category'));
        $this->display('sale_set_category.dwt');
    }

    /*
     *  设置完成
     */
    public function sale_set_end(){
        // 是否选择商品
        if($this->model->table('drp_shop')->where(array("user_id"=>$_SESSION['user_id'],'cat_id'=>''))->count() > 0){
            redirect(url('sale/sale_set_category'));
        }
        // 设置为分销商
        $audit = $this->model->table('drp_config')->field("value")->where(array("keyword"=>'audit'))->getOne();
        $_SESSION['enable_drp_shop']['create_time'] = gmtime();
        $_SESSION['enable_drp_shop']['audit'] = ($audit == 'open') ? 0:1;
        $_SESSION['enable_drp_shop']['open'] = ($audit == 'open') ? 0:1;
        $where['user_id'] = $_SESSION['user_id'];
        $this->model->table('drp_shop')->data($_SESSION['enable_drp_shop'])->insert();

        $novice = $this->model->table('drp_config')->field("value")->where(array("keyword"=>'novice'))->getOne();
        $this->assign('novice',$novice);
        // 设置分销商店铺地址
        $drp_id = M()->table('drp_shop')->field('id')->where("user_id=".$_SESSION['user_id'])->getOne();
        $this->assign('drp_id', $drp_id);
        $this->assign('sale_url','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?u='.$_SESSION['user_id'].'&drp_id='.$drp_id);
		
		// 推送消息
        $message_status = M()->table('drp_config')->field('value')->where('keyword = "msg_open"')->getOne();
        if (method_exists('WechatController', 'send_message') && $message_status=='open') {
			 // 模版信息设置
			// 获取openid 和 微信昵称
            $userInfo = M()->table('wechat_user')->field('openid,nickname')->where('ect_uid = ' . $_SESSION['user_id'])->find();
			$drp_shop = M()->table('drp_shop')->field('shop_name,shop_mobile,create_time')->where('id = ' . $drp_id)->find();
            $data['openid'] = $userInfo['openid'];  
			$data['open_id'] = 'OPENTM207126233';
            $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].url('sale/index',array('order_id'=>$new_order_id));
            $data['first'] = $this->customs.'申请成功提醒';  // 简介
			$data['keyword1'] = $drp_shop['shop_name'];  // 分销商名称
            $data['keyword2'] = $drp_shop['shop_mobile'];  // 分销商电话
            $data['keyword3'] = local_date('Y-m-d H:i:s',($drp_shop ['create_time'])); // 申请时间		
            if($data['openid']){
               sendTemplateMessage($data);
            }			
		}
		
        $this->assign('title',L('sale_set_category'));
        $this->display('sale_set_end.dwt');
    }

    /**
     * 未登录验证
     */
    private function check_login() {
        // 是否登陆
        if(empty($this->user_id)){
            $url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            redirect(url('user/login', array('referer' => urlencode($url)) ));
            exit();
        }
        $shareArr = array(
            'store',
            'spread',
            'apply'
        );
        if(!in_array($this->action,$shareArr)){
            $deny = array(
                'sale_set',
                'sale_set_category',
                'sale_set_end',
            );
            // 分销状态
            $drp_status = model('Sale')->get_drp_status($this->user_id);
            // 已经是分销商
            if($drp_status && in_array($this->action, $deny)){
                redirect(url('sale/index'));
                exit();
            }
            // 不是分销商
            if(!$drp_status && !in_array($this->action,$deny)){
                redirect(url('sale/sale_set'));
                exit;
            }

            // 增加判断
            $examine = $this->model->table('drp_config')->field('value')->where('keyword = "examine"')->getOne();
            if($examine == 'open' && $this->action !='apply'){
                $is_apply = $this->model->table('drp_apply')->field('apply')->where('user_id ='.session('user_id'))->getOne();
                if(!$drp_status && $is_apply != 2 ){
                    redirect(url('sale/apply'));
                }
            }
        }
    }

    /**
     * 我的会员
     */
    public function user_list()
    {
        $key = I('key') ? I('key') : 'wfk';
        $info = model('Sale')->get_sale_info($key);

        $this->assign('info',$info);
        $this->assign('title',L('user_list_'.$key));
        $dwt = $info ? 'sale_user_list.dwt' : 'sale_show_message.dwt';
        $this->display($dwt);
    }

    /**
     * 我的会员
     */
    public function my_user_list(){
        $key = I('key') ? I('key') : '1';
        $list = model('Sale')->get_user_list();
        $this->assign('list',$list);
        $this->assign('title',L('my_user_list'.$key));

        $dwt = $list ? 'sale_my_user_list.dwt' : 'sale_show_message.dwt';
        $this->display($dwt);
    }


    /**
     * 添加银行卡
     */
    public function add_bank(){
        if(IS_POST){
            $data = I('data');
            if(empty($data['bank_name'])){
                show_message('请输入银行名称，如：建设银行等');
            }
            if(empty($data['bank_card'])){
                show_message('请输入帐号');
            }
            if(empty($data['bank_region'])){
                show_message('请输入开户所在地');
            }
            if(empty($data['bank_user_name'])){
                show_message('请输开户名');
            }
            $data['user_id'] = $this->user_id;
            $this->model->table('drp_bank')
                ->data($data)
                ->insert();
            redirect(url('sale/select_bank'));
        }

        $this->assign('title', '添加银行卡');
        $this->display('sale_add_bank.dwt');
    }

    public function select_bank(){
        if(IS_POST){
            $bank = I('bank') ? I('bank') : 0;
            if($bank==0){
                show_message('请选择银行卡');
            }
            $data['bank'] = $bank;
            $this->model->table('drp_shop')->data($data)->where("user_id=".$this->user_id)->update();
            redirect(url('sale/account_raply'));

        }
        $list = $this->model->table('drp_bank')->where("user_id=".$this->user_id)->select();
        $this->assign('list',$list);
        $this->assign('title','选择默认银行卡');
        $this->display('sale_select_bank.dwt');
    }
    /**
     * 修改银行卡
     */
    public function edit_bank(){
        if(IS_POST){
            $data = I('data');
            if(empty($data['bank_name'])){
                show_message('请输入银行名称，如：建设银行等');
            }
            if(empty($data['bank_card'])){
                show_message('请输入帐号');
            }
            if(empty($data['bank_region'])){
                show_message('请输入开户所在地');
            }
            if(empty($data['bank_user_name'])){
                show_message('请输开户名');
            }
            $this->model->table('drp_bank')
                ->data($data)->where(array('id'=>$data['id']))
                ->update();
            redirect(url('sale/select_bank'));
        }
        $id = I('id') ? I('id') : 0;
        $bank_info = $this->model->table('drp_bank')->where(array("id"=>$id))->find();
        $this->assign('title', '修改银行卡');
        $this->assign('bank', $bank_info);
        $this->display('sale_edit_bank.dwt');
    }
    /**
     * 删除银行卡
     */
    public function del_bank(){
        $id = I('id') ? I('id') : 0;
        if($id==0){
            show_message('请选择要删除的银行卡号');
        }
        $this->model->table('drp_bank')->where("id=".$id)->delete();
        redirect(url('sale/select_bank'));
    }

    /**
     * 店铺详情
     * @throws Exception
     */
    public function shop_detail(){
        $id = I('id') ? I('id') : $this->user_id;
        $info = M()->table('drp_shop')->where("user_id=".$id)->find();
        $info['time'] = local_date('Y-m-d H:i:s',$info['create_time']);
        $info['shop_name'] = C('shop_name').$info['shop_name'];
        $this->assign('shop_info', $info);

        $shop_user = model('Sale')->get_drp($id);
        $this->assign('shop_user', $shop_user);
        // 总销售额
        $money = model('Sale')->get_sale_money_total($id);
        $this->assign('money', $money ? $money : '0.00');
        // 一级分店数
        $sql = "select count(*) count from {pre}users as u JOIN {pre}drp_shop d ON  u.user_id=d.user_id WHERE u.parent_id = ".$id;
        $shop_count = $this->model->getRow($sql);
        $this->assign('shop_count', $shop_count['count'] ? $shop_count['count'] : 0);

        // 我的会员数
        $user_count = M()->table('users')->where("parent_id=".$id)->count();
        $this->assign('user_count', $user_count ? $user_count : 0);

        // 店铺订单数
        $order_count = M()->table('drp_order_info')->where("drp_id=".$info['id'])->count();;
        $this->assign('order_count', $order_count ? $order_count : 0);
        $this->assign('custom',$this->custom);
        $this->assign('title', '店铺详情');
        $this->display('sale_shop_detail.dwt');
    }

    /**
     * 分销商排行榜
     */
    public function ranking_list(){
        $size = 5;
//        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $sql = "select COUNT(*) as count from {pre}drp_shop";
        $count = $this->model->query($sql);
        $count = $count['0']['count'];
        $this->pageLimit(url('sale/ranking_list'), $size);
        $this->assign('pager', $this->pageShow($count));

        $sql = "select *, (select sum(user_money) from {pre}drp_log WHERE user_money > 0 and status=1 and  {pre}drp_log.user_id= {pre}drp_shop.user_id) as sum_money from {pre}drp_shop order by sum_money desc, user_id asc limit  ".$size;
        $list = $this->model->query($sql);
        if($list){
            foreach($list as $key=>$val){
                $list[$key]['sum_money'] = $val['sum_money'] ? $val['sum_money'] : 0.00;
                $list[$key]['shop_name'] = C('shop_name').$val['shop_name'];
                $list[$key]['user_img'] = $this->wechat_iheadimgurl($val['user_id']);
            }
        }
        $this->assign('list', $list);
        $this->assign('title', L('ranking_list'));
        //获取我的排名
        $pm = null;
        $sql = "select user_id, (select sum(user_money) from {pre}drp_log WHERE user_money > 0 and status=1 and  {pre}drp_log.user_id= {pre}drp_shop.user_id) as sum_money from {pre}drp_shop order by sum_money desc, user_id asc limit 50000";

        $list = $this->model->query($sql);

        foreach ($list as $key => $val) {
            if ($val['user_id'] == I('u')) {
                $pm = $key+1;
                break;
            }
        }
        //
        $this->assign('pm', $pm);
        $this->display('sale_ranking_list.dwt');
    }

    public function wechat_iheadimgurl($user_id){
        $sql = "select headimgurl from {pre}wechat_user where ect_uid = '$user_id'";
        $res = $this->model->query($sql);
        return $res[0]['headimgurl'];
    }


    /**
     * 销售订单详情
     */
    public function order_detail() {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

        if ($order_id == 0) {
            ECTouch::err()->show(L('back_home_lnk'), './');
            exit();
        }
        $where = 'd.order_id = '.$order_id;
        $orders = model('Sale')->get_sale_orders($where,1,0,0);
        if($orders){
            foreach($orders as $key=>$val){
                foreach($val['goods'] as $k=>$v){
                    $orders[$key]['goods'][$k]['profit'] = model('Sale')->get_drp_profit($v['goods_id']);
                    $orders[$key]['goods'][$k]['profit_money'] = $v['touch_sale']*$orders[$key]['goods'][$k]['profit']['profit1'] /100;
                    $orders[$key]['sum']+=$orders[$key]['goods'][$k]['profit_money']*$v['goods_number'];
                }
            }
        }
        $this->assign('orders_list', $orders);
        $this->assign('title', L('order_detail'));
        $this->display('sale_order_detail.dwt');
    }

    /**
     * 上传图片
     * @return multitype:number type
     */
    public function uploadImage(){
        $upload = new UploadFile();
        //设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,jpeg,gif,png,bmp');
        //设置附件上传目录
        $upload->savePath = './data/attached/drp_logo/';
        // 是否生成缩略图
        $upload->thumb = false;
        //缩略图大小
        $upload->thumbMaxWidth = 500;
        $upload->thumbMaxHeight = 500;
        if (!$upload->upload($key)) {
            //捕获上传异常
            return array('error' => 1, 'message' => $upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            return array('error' => 0, 'message' => $upload->getUploadFileInfo());
        }
    }

    /**
     * 分销商审核中
     */
    public function examine(){
        $this->assign('title',$this->customs.'审核');
        $this->display('sale_examine.dwt');
    }

    /**
     * 购买成为分销商
     */
    public function apply(){

        $apply_info = $this->model->table('drp_apply')->where("user_id=".session('user_id'))->find();
        $price = $this->model->table('drp_config')->where("keyword='money'")->field('value')->getOne();
		$examine = $this->model->table('drp_config')->field('value')->where('keyword = "examine"')->getOne();
		if($examine != 'open'){
            redirect(url('sale/sale_set'));exit;
        }

        if($apply_info['apply'] == 2){
            redirect(url('sale/index'));exit;
        }
        if($apply_info){
            if($apply_info['amount'] != $price){
                $data['amount'] = $price;
                $where['user_id'] = session('user_id');
                $this->model->table('drp_apply')->data($data)->where($where)->update();
            }
            $apply_id = $apply_info['id'];
        }else{
            unset($apply_info);
            // 生成支付记录
            $apply_info['apply'] = 1;
            $apply_info['user_id'] = session('user_id');
            $apply_info['time'] = gmtime();
            $apply_info['amount'] = $price;

            $this->model->table('drp_apply')
                ->data($apply_info)
                ->insert();

            $apply_id = $this->model->insert_id();
        }
        /* 取得支付信息，生成支付代码 */
        if ($apply_info ['amount'] > 0) {

            $sql = "SELECT * FROM {pre}payment WHERE pay_code = 'wxpay' AND enabled = 1";
            $payment = $this->model->getRow($sql);
            if(!$payment ['pay_code']){
                show_message('因商家暂未开通微信支付，此功能暂未开发');exit;
            }
            include_once (ROOT_PATH . 'plugins/payment/' . $payment ['pay_code'] . '.php');

            $pay_obj = new $payment ['pay_code'] ();
            //补全支付信息
            $apply_info['order_amount'] = $apply_info ['amount'];
            $apply_info['order_sn'] = get_order_sn();
            $apply_info['log_id'] = $apply_id;

            $pay_online = $pay_obj->get_code($apply_info, unserialize_config($payment ['pay_config']));

            $this->assign('pay_online',$pay_online);

        }

        $headimgurl = $this->model->table('wechat_user')->field('headimgurl')->where('ect_uid = '.session('user_id'))->getOne();
        $this->assign('headimgurl',$headimgurl);

        $money = $this->model->table('drp_config')->field('value')->where('keyword = "money"')->getOne();
        $this->assign('money',$money);
        $this->assign('title',$this->custom.'申请');
        $this->display('sale_apply.dwt');
    }
	/**
     * 获取二维码，验证是否开店
     */
	public function check_open($id){
		$condition['user_id'] = $id;
        $shop_info = $this->model->table('drp_shop')->where($condition)->find();
		if(empty($shop_info)){
			show_message('您还未开店，请先前往开店','前去开店',url('sale/sale_set'));			
		}
		
	}
}
