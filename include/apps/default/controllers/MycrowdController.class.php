<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：IndexController.class.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTouch我的众筹控制器
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class MycrowdController extends CommonController {
	
	protected $user_id;
    protected $action;
    protected $back_act = '';
	
	 /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();		
		$this->user_id = $_SESSION['user_id'];
		$this->action = ACTION_NAME;
		// 验证登录
        $this->check_login();
		
        // 用户信息
        $info = model('ClipsBase')->get_user_default($this->user_id);
        // 显示第三方API的头像
        if(isset($_SESSION['avatar'])){
            $info['avatar'] = $_SESSION['avatar'];
        }

		// 如果是显示页面，对页面进行相应赋值
        assign_template();

		$this->assign('info', $info);
		$this->assign('action', $this->action);
    }
	
    /**
     * 众筹项目列表信息
     */
    public function index() {
		
        $recommend = model('Mycrowd')->recom_list();//获取推荐众筹
		$this->assign('recommend', $recommend);
        $this->display('crowd/raise_user.html');
    }
	
	/**
     * 关注众筹项目列表信息
     */
    public function crowd_like() {
		$this->type = I('request.type') ? intval(I('request.type')) : 1 ;
        $like = model('Mycrowd')->like_list($this->user_id, $this->type);//获取关注众筹
		$this->assign('like', $like);
		$this->assign('type', $this->type);
        $this->display('crowd/raise_follow.html');
    }
	
	/**
     * 我的支持众筹项目列表信息
     */
    public function crowd_buy() {
		$this->type = I('request.type') ? intval(I('request.type')) : 1 ;
        $buy_list = model('Mycrowd')->crowd_buy_list($this->user_id, $this->type);//获取我的支持众筹项目
		$this->assign('buy_list', $buy_list);
		$this->assign('type', $this->type);
        $this->display('crowd/raise_support.html');
    }
    
	
	/**
     * 关于众筹
     */
    public function crowd_articlecat() {
        $sql = 'SELECT cat_id, cat_name' .
            ' FROM ' .$this->model->pre. 'article_cat ' .
            ' WHERE cat_type = 1 AND parent_id = 0' .
            ' ORDER BY sort_order ASC';
        $data = $this->model->query($sql);
        foreach($data as $key=>$vo){
            $data[$key]['url'] = url('crowd_art_list', array('id'=>$vo['cat_id']));
        }
        $this->assign('data', $data); //文章分类树
        $this->display('crowd/raise_help.html');
    }
	
	
	/**
     * 关余众筹详细order_list
     */
    public function crowd_art_list() {
		//$id = I('request.id') ? intval(I('request.id')) : 0 ;
        $sql = 'SELECT title, 	description' .
            ' FROM ' .$this->model->pre. 'crowd_article ' .
            " WHERE is_open = 1 " ;
        $data = $this->model->query($sql);
        $this->assign('data', $data);
        $this->display('crowd/raise_problem.html');
    }
	
	
	/**
     * 众筹订单
     */
    public function crowd_order() {
		
		$this->status = I('request.status') ? intval(I('request.status')) : 1 ;
		$pay = $this->status;
        $size = I(C('page_size'),10);
        $count = model('Mycrowd')->crowd_orders_num($this->user_id, $this->status);//获取订单数量
        $filter['page'] = '{page}';
        $offset = $this->pageLimit(url('crowd_order', $filter), $size);
        $offset_page = explode(',', $offset);
        $orders = model('Mycrowd')->crowd_user_orders($this->user_id, $pay, $offset_page[1], $offset_page[0]);
        $this->assign('pay', $pay);
        $this->assign('title', L('order_list_lnk'));
        $this->assign('pager', $this->pageShow($count));
		$this->assign('status', $this->status);
        $this->assign('orders_list', $orders);
		
		
        $this->display('crowd/raise_order.html');
    }
	
	/**
     * 众筹订单详情
     */
    public function crowd_order_detail() {
		$order_id = I('request.order_id') ? intval(I('request.order_id')) : 0 ;
		// 订单详情
		$order = model('Mycrowd')->get_order_detail($order_id, $this->user_id);
		$goods_list = model('Mycrowd')->order_goods($order['order_id']);//获取订单商品详情
		//dump($order);
		$this->assign('goods', $goods_list);
		$this->assign('order', $order);
		
        $this->display('crowd/raise_order_detail.html');
    }
	
	
	/**
     * 获取订单商品的评论
    */
    public function crowd_comment() {
		
		 if (IS_POST) {
            $data = array(
                'user_id' => $this->user_id,
                'user_name' => $_SESSION['user_name'],
                'content' => I('post.content'),
				'add_time' => time(),
                'order_id' => I('post.order_id', 0),
				'goods_id' => I('post.goods_id', 0)
                
            );

            $this->model->table('crowd_comment')
                        ->data($data)
                        ->insert();
            crowd_show_message('评论成功等待管理员审核', '返回', url('crowd_order'), 'info');

        }
		
		$order_id = I('request.order_id') ? intval(I('request.order_id')) : 0 ;
		$goods_id = I('request.id') ? intval(I('request.id')) : 0 ;
		$sql = "SELECT cg.goods_img, cg.goods_name FROM " . $this->model->pre . "order_info as o left join " . $this->model->pre . "order_goods as g on o.order_id = g.order_id left join ". $this->model->pre ."crowd_goods as cg on g.goods_id = cg.goods_id " ." WHERE o.order_id = '$order_id' and  o.extension_code = 'crowd_buy'  limit 1";		
		$order = $this->model->query($sql);
		foreach ($order AS $key => $row) {           
            $order[$key]['goods_img'] = 'data/attached/crowdimage/'.$row['goods_img'];
            
		}
		$this->assign('order_id', $order_id);
		$this->assign('goods_id', $goods_id);
		$this->assign('order', $order);
	
		$this->display('crowd/raise_user_evaluation_info.html');
    }
	

	
	/**
    * 取消订单
    */
    public function cancel_order() {
        $order_id = I('get.order_id', 0, 'intval');

        if (model('Mycrowd')->cancel_order($order_id, $this->user_id)) {
            $url = url('crowd_order');
            ecs_header("Location: $url\n");
            exit();
        } else {
            ECTouch::err()->show(L('order_list_lnk'), url('crowd_order'));
        }
    }
	
	
	
	/**
     * 确认收货
     */
    public function affirm_received() {
        $order_id = I('get.order_id', 0, 'intval');
        if (model('Mycrowd')->affirm_received($order_id, $this->user_id)) {
            ecs_header("Location: " . url('crowd_order') . "\n");
            exit();
        } else {
            ECTouch::err()->show(L('order_list_lnk'), url('crowd_order'));
        }
    }
	
	 /**
     * 订单跟踪
     */
    public function order_tracking() {
        $order_id = I('get.order_id', 0);
        $ajax = I('get.ajax', 0);
        $where['user_id'] = $this->user_id;
        $where['order_id'] = $order_id;
        $orders = $this->model->table('crowd_order_info')->field('order_id, order_sn, invoice_no, shipping_name, shipping_id')->where($where)->find();

        // 生成快递100查询接口链接
        $shipping = get_shipping_object($orders['shipping_id']);
        // 接口模式
        $query_link = $shipping->query($orders['invoice_no']);
        $get_content = Http::doGet($query_link);
        $get_content_data = json_decode($get_content, 1);
        if($get_content_data['status'] != '200'){
            // 跳转模式
            $query_link = $shipping->third_party($orders['invoice_no']);
            if($query_link){
                header('Location: '.$query_link);
                exit();
            }
        }
        $this->assign('title', L('order_tracking'));
        $this->assign('trackinfo', $get_content);
        $this->display('user_order_tracking.dwt');
    }
	
	
	/**
    * 验证登录
    */	
	private function check_login() {
        // 是否登录
        if(empty($this->user_id)){
            $url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            redirect(url('user/login', array('referer' => urlencode($url)) ));
            exit();
        }
	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
   

}
