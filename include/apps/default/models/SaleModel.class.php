<?php

/**
 * ECTouch Open Source Project
 * ============================================================================
 * Copyright (c) 2012-2014 http://ectouch.cn All rights reserved.
 * ----------------------------------------------------------------------------
 * 文件名称：SaleModel.php
 * ----------------------------------------------------------------------------
 * 功能描述：ECTouch 用户模型
 * ----------------------------------------------------------------------------
 * Licensed ( http://www.ectouch.cn/docs/license.txt )
 * ----------------------------------------------------------------------------
 */

/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class SaleModel extends BaseModel {
    /**
     * 获取店铺列表
     */
    function get_shop_list($key=1){
        $res = array();
        $sql = "select d.* from {pre}users as u JOIN {pre}drp_shop d ON  u.user_id=d.user_id WHERE u.parent_id = ".$_SESSION['user_id'] ." and open = 1";
        $list = M()->query($sql);
        if($key == 1){
            $res = $list;
        }else{
            if($list){
                $where = '';
                foreach ($list as $k => $val){
                    $where .= $val['user_id'].',';
                }
                $where = substr($where, 0, -1);
                $sql = "select d.* from {pre}users  as u JOIN {pre}drp_shop d ON  u.user_id=d.user_id WHERE u.parent_id in($where) and open = 1";
                $list2 = M()->query($sql);
                if($key == 2){
                    $res = $list2;
                }else{
                    if($list2){
                        $where = '';
                        foreach ($list2 as $k2 => $val){
                            $where .= $val['user_id'].',';
                        }
                        $where = substr($where, 0, -1);
                        $sql = "select d.* from {pre}users as u JOIN {pre}drp_shop d ON  u.user_id=d.user_id WHERE u.parent_id in($where) and open = 1";
                        $list3 = M()->query($sql);
                        if($key == 3){
                            $res = $list3;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
        foreach($res as $key => $val){
            $res[$key]['time'] = local_date('Y-m-d H:i:s',$val['create_time']);
            $res[$key]['shop_name'] = C('shop_name').$res[$key]['shop_name'];
        }
        return $res;
    }

    /**
     * 获取我的下线会员
     */
    function get_user_list(){
        $sql = "select reg_time,user_id,user_name from {pre}users  WHERE parent_id = ".$_SESSION['user_id'];
        $list = M()->query($sql);
        foreach($list as $key => $val){
            $list[$key]['time'] = local_date(C('date_format'), $val['reg_time']);;

            if(class_exists('WechatController')){
                if (method_exists('WechatController', 'get_avatar')) {
                    $wx_info = call_user_func(array('WechatController', 'get_avatar'), $val['user_id']);
                }
            }
            if ($wx_info) {
                $list[$key]['user_name'] = $wx_info['nickname'];
                $list[$key]['headimgurl'] = $wx_info['headimgurl'];
            } else {
                $list[$key]['user_name'] = $val['user_name'];
                $list[$key]['headimgurl'] = __PUBLIC__ . '/images/get_avatar.png';
            }
        }
        return $list;
    }

    /**
     * 查询销售总额
     */
    function get_sale_money_total($uid=0){
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $drp_id = $this->model->table('drp_shop')->where("user_id=".$uid)->field('id')->getOne();
        $goods_info =  M()->getRow("select sum(o.goods_amount) as money from {pre}order_info as o join {pre}drp_order_info as d on o.order_id = d.order_id  where o.pay_status='".PS_PAYED."' and d.drp_id = ".$drp_id);
        return $goods_info['money'];
    }
    /**
     * 查询分销商佣金
     * @access
     * @param   int     $user_id        会员ID
     * @return  int
     */
    function saleMoney($uid=0) {
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $money = M()->getRow("select sum(user_money) as money from {pre}drp_log where user_id = ".$uid ."  and status=1");
        $money = $money['money'];
        return $money ? $money : 0;

    }

    /**
     * 查询分销商当日佣金
     * @access
     * @param   int     $user_id        会员ID
     * @return  int
     */
    function saleMoney_today($uid=0) {
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $user_money =  M()->getRow("select sum(user_money) as money from {pre}drp_log where user_id = ".$uid ." and change_time > ".strtotime(local_date('Y-m-d'))." and user_money > 0 and status=1");
        return $user_money['money'];

    }

    /**
     * 查询分销商可提现佣金
     * @access
     * @param   int     $user_id        会员ID
     * @return  int
     */
    function saleMoney_surplus($uid=0) {
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $money = M()->getRow("select sum(money) as money from {pre}drp_shop where user_id = ".$uid);
        $money = $money['money'];
        return $money ? $money : 0;

    }

    /**
     * 查询会员账户明细
     * @access
     * @param   int     $user_id    会员ID
     * @param   int     $num        每页显示数量
     * @param   int     $start      开始显示的条数
     * @return  array
     */
    function get_sale_log($user_id, $num, $start) {
        // 获取余额记录
        $account_log = array();

        /* $sql = "SELECT d.* FROM  {pre}drp_log as d right join {pre}order_info as o on d.order_id = o.order_id WHERE d.user_id = " . $user_id .
            " ORDER BY log_id DESC limit " . $start . ',' . $num; */
		$sql = "SELECT d.* FROM  {pre}drp_log as d  WHERE d.user_id = " . $user_id .
            " ORDER BY log_id DESC limit " . $start . ',' . $num;
        $res = M()->query($sql);

        if (empty($res)) {
            return array();
            exit;
        }

        foreach ($res as $k => $v) {
            $res[$k]['change_time'] = local_date(C('date_format'), $v['change_time']);
            $res[$k]['type'] = $v['user_money'] > 0 ? L('account_inc') : L('account_dec');
            $res[$k]['user_money'] = price_format(abs($v['user_money']), false);
            $res[$k]['frozen_money'] = price_format(abs($v['frozen_money']), false);
            $res[$k]['rank_points'] = abs($v['rank_points']);
            $res[$k]['pay_points'] = abs($v['pay_points']);
            $res[$k]['short_change_desc'] = sub_str($v['change_desc'], 60);
            $res[$k]['amount'] = $v['user_money'];
            $res[$k]['change_type'] = $v['change_type'] == DRP_SEPARATE ? '佣金分成' : '佣金提现';
			if($v['order_id'] > 0){
				$res[$k]['order_status'] = $this->get_order_status($v['order_id']);				
			}
        }
        return $res;
    }
	//取得订单状态
	 function get_order_status($order_id) {
        /* 取得订单列表 */
        $arr = array();
        $sql = "SELECT order_status, shipping_status, pay_status " .               
                " FROM " . $this->pre . "order_info WHERE order_id = $order_id ". $pay  ;
        $res = M()->query($sql);
        foreach ($res as $key => $value) {          
			$value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];	
			$value['order_status'] = L('os.' . $value['order_status']) . ',' . L('ps.' . $value['pay_status']) . ',' . L('ss.' . $value['shipping_status']);
            $arr[] = array(               
                'order_status' => $value['order_status']
             );
        }
        return $arr;
    }
    /**
     *  获取用户的分销订单列表
     *
     * @access
     * @param   int         $user_id        用户ID号
     * @param   int         $pay            订单状态，0未付款，1全部，默认1
     * @param   int         $num            列表最大数量
     * @param   int         $start          列表起始位置
     * @return  array       $order_list     订单列表
     */
    function get_sale_orders($where, $num = 10, $start = 0 ,$user_id) {
        /* 取得订单列表 */
        $arr = array();
        $sql = "SELECT o.order_id, o.order_sn, o.user_id, o.shipping_id, o.order_status, o.shipping_status, o.pay_status, o.add_time, o.is_separate, " .
            "(o.goods_amount + o.shipping_fee + o.insure_fee + o.pay_fee + o.pack_fee + o.card_fee + o.tax - o.discount) AS total_fee, d.shop_separate " .
            " FROM {pre}order_info as o inner join {pre}drp_order_info as d on o.order_id=d.order_id " .
            " WHERE  " . $where . " ORDER BY add_time DESC LIMIT $start , $num";
        $res = M()->query($sql);
        if($res){
            foreach ($res as $key => $value) {


                $value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];
                $value['order_status'] = L('os.' . $value['order_status']) . ',' . L('ps.' . $value['pay_status']) . ',' . L('ss.' . $value['shipping_status']);
                $goods_list = $this->get_order_goods($value['order_id']);
                foreach ($goods_list as $key => $val) {
                    $goods_list[$key]['price'] = $val['goods_price'];
                    $goods_list[$key]['goods_price'] = price_format($val['goods_price'], false);
                    $goods_list[$key]['subtotal'] = price_format($value['total_fee'], false);
                    $goods_list[$key]['goods_number'] = $val['goods_number'];
                    $goods_list[$key]['touch_fencheng'] = $val['touch_fencheng'];
                    $goods_list[$key]['touch_sale'] = $val['touch_sale'];
                    $goods_list[$key]['goods_thumb'] = get_image_path($val['goods_id'],$val['goods_thumb']);
                }
                $nick_name = M()->table('wechat_user')->field('nickname')->where("ect_uid=".$value[user_id])->getOne();
                if(empty($nick_name)){
                    $nick_name = M()->table('users')->field('user_name')->where("user_id=".$value[user_id])->getOne();
                }
                $arr[] = array('order_id' => $value['order_id'],
                    'user_name' =>  $nick_name ,
                    'order_sn' => $value['order_sn'],
                    'img' => get_image_path(0, model('Order')->get_order_thumb($value['order_id'])),
                    'order_time' => local_date(C('time_format'), $value['add_time']),
                    'order_status' => $value['order_status'],
                    'shipping_id' => $value['shipping_id'],
                    'total_fee' => price_format($value['total_fee'], false),
                    'url' => url('user/order_detail', array('order_id' => $value['order_id'])),
                    'is_separate' => $value['shop_separate'] > 0 ? "<span style='font-weight:bold'>已分成</span>" : "<span style='color:red;font-weight:bold'>未分成</span>",
                    'goods'=>$goods_list,
                    'log' => $this->model->getRow("select * from {pre}drp_log where order_id='".$value[order_id]."' and user_id='".$user_id."'"),
					//table('drp_log')->where('order_id=90')->getRow(),
                );
            }
        }

        return $arr;
    }
	
	function get_sale_goods_detai($where, $user_id) {
        /* 取得订单列表 */
        $arr = array();
        $sql = "SELECT o.order_id, o.order_sn, o.user_id, o.shipping_id, o.order_status, o.shipping_status, o.pay_status, o.add_time, o.is_separate, " .
            "(o.goods_amount + o.shipping_fee + o.insure_fee + o.pay_fee + o.pack_fee + o.card_fee + o.tax - o.discount) AS total_fee, d.shop_separate " .
            " FROM {pre}order_info as o right join {pre}drp_order_info as d on o.order_id=d.order_id " .
            " WHERE  " . $where . " ORDER BY add_time DESC ";
        $res = M()->query($sql);
        if($res){
            foreach ($res as $key => $value) {

				
                $value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];
                $value['order_status'] = L('os.' . $value['order_status']) . ',' . L('ps.' . $value['pay_status']) . ',' . L('ss.' . $value['shipping_status']);
                $goods_list = $this->get_order_goods($value['order_id']);
                foreach ($goods_list as $key => $val) {
                    $goods_list[$key]['price'] = $val['goods_price'];
                    $goods_list[$key]['goods_price'] = price_format($val['goods_price'], false);
                    $goods_list[$key]['subtotal'] = price_format($value['total_fee'], false);
                    $goods_list[$key]['goods_number'] = $val['goods_number'];
                    $goods_list[$key]['touch_fencheng'] = $val['touch_fencheng'];
                    $goods_list[$key]['touch_sale'] = $val['touch_sale'];
                    $goods_list[$key]['goods_thumb'] = get_image_path($val['goods_id'],$val['goods_thumb']);
                }

                $arr[] = array('order_id' => $value['order_id'],
                    'user_name' => M()->table('users')->field('user_name')->where("user_id=".$value[user_id])->getOne(),
					'shop_mobile' => M()->table('drp_shop')->field('shop_mobile')->where("user_id=".$value[user_id])->getOne(),
                    'order_sn' => $value['order_sn'],
                    'img' => get_image_path(0, model('Order')->get_order_thumb($value['order_id'])),
                    'order_time' => local_date(C('time_format'), $value['add_time']),
                    'order_status' => $value['order_status'],
                    'shipping_id' => $value['shipping_id'],
                    'total_fee' => price_format($value['total_fee'], false),
                    'url' => url('user/order_detail', array('order_id' => $value['order_id'])),
                    'is_separate' => $value['shop_separate'] > 0 ? "<span style='font-weight:bold'>已分成</span>" : "<span style='color:red;font-weight:bold'>未分成</span>",
                    'goods'=>$goods_list,
                    'log' => $this->model->getRow("select * from {pre}drp_log where order_id='".$value[order_id]."' and user_id='".$user_id."'"),
					//table('drp_log')->where('order_id=90')->getRow(),
                );
            }
        }

        return $arr;
    }
    /**
     * 获取用户列表信息
     */
    function get_sale_info($key=false){
        if(!$key) return false;
        $res = array();
        if($key == 'wfk'){
            $sql = "SELECT user_id FROM {pre}order_info where user_id > 0 and pay_status = ".PS_UNPAYED." and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
            $user_list = M()->query($sql);
            if($user_list){
                foreach($user_list as $key=>$val){
                    $sql = "SELECT count(*) as count FROM {pre}order_info where user_id = ".$val['user_id']." and pay_status != ".PS_UNPAYED."and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
                    if(M()->getOne($sql) > 0){
                        unset($user_list[$key]);
                    }else{
                        $info = $this->get_drp($val['user_id']);
                        $user_list[$key] = $info;
                    }
                }
            }
            $res['count'] = count($user_list);
            $res['list'] = $user_list;
        }elseif($key == 'yfk'){
            $sql = "SELECT user_id FROM {pre}order_info where user_id > 0 and pay_status = ".PS_PAYED." and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
            $user_list =  M()->query($sql);
            if($user_list){
                foreach($user_list as $key=>$val){
                    $info = $this->get_drp($val['user_id']);
                    $user_list[$key] = $info;
                }
            }
            $res['count'] = count($user_list);
            $res['list'] = $user_list;
        }elseif($key == 'gz'){
            $sql = "SELECT user_id FROM {pre}users as u join {pre}wechat_user as w on u.user_id=w.ect_uid where u.user_id > 0 and w.subscribe = 1 and u.parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
            $user_list =  M()->query($sql);
            if($user_list){
                foreach($user_list as $key=>$val){
                    $user_list[$key] = $this->get_drp($val['user_id']);
                }
            }
            $res['count'] = count($user_list);
            $res['list'] = $user_list;
        }elseif($key == 'fk'){
            $drp_id = $this->model->table('drp_shop')->field("id")->where(array("user_id"=>$_SESSION['user_id']))->getOne();
            $user_list = $this->model->table('drp_visiter')->where(array("drp_id"=>$drp_id))->select();
            if($user_list){
                foreach($user_list as $key=>$val){
                    $user_list[$key] = $this->get_drp($val['user_id']);
                }
            }
            $res['count'] = count($user_list);
            $res['list'] = $user_list;
        }else{
            return false;
        }
        return $res['count'] > 0 ? $res : false;


    }


    /**
     * 获取用户一级下线数量
     * @return Ambigous <number, unknown>
     */
    function get_line_count($user_id=0){
        $user_id = $user_id > 0 ? $user_id : session('user_id');
        $count = M()->table('users')->field('COUNT(*)')->where("parent_id = ".$user_id)->getField();
        return $count > 0 ? $count : 0;
    }


    /**
     * 获取用户下线
     * @param int $num   每页显示数量
     * @param int $start 每页开始显示条数
     */
    function saleList($uid=0){
        $uid = $uid > 0 ? $uid : session("user_id");
        $sql = "SELECT * FROM {pre}users WHERE parent_id = " .$uid . " ORDER BY user_id DESC ";
        $res = $this->query($sql);

        if (empty($res)) {
            return array();
            exit;
        }

        foreach ($res as $k => $v) {
            $res[$k]['user_id']     =   $v['user_id'];
            $res[$k]['user_name']   =   $v['user_name'];
            $res[$k]['reg_time']    =   local_date('Y-m-d H:i:s', $v['reg_time']);
            $res[$k]['mobile_phone']    =    $v['mobile_phone'] ? substr_replace($v['mobile_phone'],'****',3,4) : '';

            if(class_exists('WechatController')){
                if (method_exists('WechatController', 'get_avatar')) {
                    $u_row = call_user_func(array('WechatController', 'get_avatar'), $v['user_id']);
                }
            }
            if ($u_row) {
                $res[$k]['username'] = $u_row['nickname'];
                $res[$k]['headimgurl'] = $u_row['headimgurl'];
            } else {
                $res[$k]['username'] = $v['username'];
                $res[$k]['headimgurl'] = __PUBLIC__ . '/images/get_avatar.png';
            }
        }

        return $res;
    }

    /**
     * 获取用户下线
     * @param int $num   每页显示数量
     * @param int $start 每页开始显示条数
     */
    function saleuser($uid=0){
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $sql = "SELECT * FROM {pre}users WHERE parent_id = " .$uid . " ORDER BY user_id DESC ";
        $res = M()->query($sql);
        if($res){
            foreach ($res as $k => $v) {
                $list[$k]['user_id']     =   $v['user_id'];
            }
            return $list;
        }else{
            return array();
        }
    }

    /**
     * 获取分销商信息
     * @access  public
     * @param   int         $user_id            用户ID
     * @return  array       $info               默认页面所需资料数组
     */
    public function get_drp($user_id=0,$is_drp=0) {
        if($is_drp != 0){
            $sql = "SELECT user_id FROM {pre}drp_shop WHERE id = '$user_id'";
            $row = $this->row($sql);
            $user_id = $row['user_id'];
            if(!$user_id){
                return array();exit;
            }
        }
        if($user_id == 0){
            return false;
        }
        $user_name = $this->model->table('users')->field("user_name")->where(array("user_id"=>$user_id))->getOne();
        $info = array();
        //新增获取用户头像，昵称
        $u_row = '';
        if(class_exists('WechatController')){
            if (method_exists('WechatController', 'get_avatar')) {
                $u_row = call_user_func(array('WechatController', 'get_avatar'), $user_id);
            }
        }
        if ($u_row) {
            $info['username'] = $u_row['nickname'];
            $info['headimgurl'] = $u_row['headimgurl'];
        } else {
            $info['username'] = $user_name;
            $info['headimgurl'] = __PUBLIC__ . '/images/get_avatar.png';
        }
        $sql = "SELECT * FROM " . $this->pre . "drp_shop WHERE user_id = '$user_id'";
        $row = $this->row($sql);
        $info['drp_id'] = $row['id'];
        $info['shop_name'] = C('shop_name').$row['shop_name'];
        $info['real_name'] = $row['real_name'];
        $info['open']      = $row['open'];
		$info['audit']      = $row['audit'];
        $info['cat_id']    = $row['cat_id'];
        $info['shop_mobile']    = $row['shop_mobile'];
        $info['shop_img']    = $row['shop_img'] ? './data/attached/drp_logo/'.$row['shop_img'] : '';
        $info['user_id']   = $user_id;
        $info['time']   = local_date(C('time_format'), $this->model->table('users')->field("reg_time")->where(array("user_id"=>$user_id))->getOne());
        return $info;
    }

    /**
     * 获取佣金比例
     * @param $goods_id
     */
    public function get_drp_profit($goods_id=0){
        if($goods_id == 0 ){
            return false;
        }
        $id = M()->table('goods')->field('cat_id')->where("goods_id=$goods_id")->getOne();
        $id = $this->get_goods_cat($id);
        $profit = M()->table('drp_profit')->where('cate_id='.$id)->select();
        return $profit['0'];
    }

    public function get_goods_cat($id){
        $parent_id = M()->table('category')->field('parent_id')->where("cat_id=$id")->getOne();
        if($parent_id==0){
            return $id;
        }else{
            $id = $this->get_goods_cat($parent_id);
            return $id;
        }
    }

    /**
     * 获取订单佣金比例
     * @param $goods_id
     */
    public function get_drp_order_profit($order_id=0,$goods_id=0){
        if($goods_id == 0 || $order_id == 0){
            return false;
        }
        $drp_id = $this->model->table('drp_order_info')->field('drp_id')->where("order_id=$order_id")->getOne();
        if($drp_id){
            $user_id = M()->table('drp_shop')->field('user_id')->where('id = ' . $drp_id)->getOne();
            if($user_id == session('user_id')){
                $key = 'profit1';
            }else{
                $user_id = $this->model->table('users')->field('parent_id')->where("user_id=$user_id")->getOne();
                if($user_id == session('user_id')){
                    $key = 'profit2';
                }else{
                    $user_id = $this->model->table('users')->field('parent_id')->where("user_id=$user_id")->getOne();
                    if($user_id == session('user_id')){
                        $key = 'profit3';
                    }
                }
            }
        }

        $id = M()->table('goods')->field('cat_id')->where("goods_id=$goods_id")->getOne();
        $id = $this->get_goods_cat($id);
        $profit = M()->table('drp_profit')->where('cate_id='.$id)->select();
        return $profit['0'][$key];
    }

    /**
     * 获取店铺销售总额
     */
    public function get_shop_sale_money($user_id=0,$separate=0){
        // 定义返回数组
        $data = array(
            'profit'=>0,
            'profit1'=>0,
            'profit2'=>0,
            'profit_num'=>0,
        );

        // 本店销售佣金
        $drp_id = M()->table('drp_shop')->field('id')->where("user_id=".$user_id)->getOne();
        // 本店订单
        $order_id = M()->table('drp_order_info')->field('order_id')->where('drp_id='.$drp_id ." and shop_separate=".$separate)->select();
        $where = "-1";
        foreach($order_id as $key=>$val){
            $where.=",".$val['order_id'];
        }
        // 本店利润
       $data['profit'] = M()->getRow("select sum(a.user_money) as money from {pre}drp_log as a left join {pre}order_info as o on a.order_id=o.order_id where a.order_id in(".$where.") and a.user_id = ".$user_id ." and a.user_money > 0 and o.pay_status = 2 ");
        $data['profit'] = $data['profit']['money'] ? $data['profit']['money'] : 0;

        //一级分店
        $sql = "select d.id from {pre}drp_shop as d JOIN {pre}users as u on d.user_id=u.user_id where u.parent_id=".$user_id." and open = 1";
        $drp_list = M()->query($sql);
        if($drp_list){
            $where_drp = '-1';
            foreach($drp_list as $key=>$val){
                $where_drp.=",".$val['id'];
            }
            // 一级订单
            $order_id = M()->table('drp_order_info')->field('order_id')->where('drp_id in('.$where_drp.') and shop_separate='.$separate)->select();
            if($order_id){
                $where = "-1";
                foreach($order_id as $key=>$val){
                    $where.=",".$val['order_id'];
                }
                // 一级分店利润
                $data['profit1'] = M()->getRow("select sum(a.user_money) as money from {pre}drp_log as a left join {pre}order_info as o on a.order_id=o.order_id where a.order_id in(".$where.") and a.user_id = ".$user_id ." and a.user_money > 0 and o.pay_status = 2 ");
                $data['profit1'] = $data['profit1']['money'] ? $data['profit1']['money'] : 0;

                //一级用户
                $sql = "select user_id from {pre}drp_shop where id in (".$where_drp.")";
                $user_list = M()->query($sql);

                $where_user = '-1';
                foreach($user_list as $key=>$val){
                    $where_user.=",".$val['user_id'];
                }
                //二级分店
                $sql = "select d.id from {pre}drp_shop as d JOIN {pre}users as u on d.user_id=u.user_id where u.parent_id in(".$where_user.") and open = 1";
                $drp_list = M()->query($sql);
                if($drp_list){
                    $where_drp = '-1';
                    foreach($drp_list as $key=>$val){
                        $where_drp.=",".$val['id'];
                    }
                    $order_id = M()->table('drp_order_info')->field('order_id')->where('drp_id in('.$where_drp.') and shop_separate='.$separate)->select();

                    if($order_id){
                        $where = "-1";
                        foreach($order_id as $key=>$val){
                            $where.=",".$val['order_id'];
                        }
                       $data['profit2'] = M()->getRow("select sum(a.user_money) as money from {pre}drp_log as a left join {pre}order_info as o on a.order_id=o.order_id where a.order_id in(".$where.") and a.user_id = ".$user_id ." and a.user_money > 0  and o.pay_status = 2 ");
                        $data['profit2'] = $data['profit2']['money'] ? $data['profit2']['money'] : 0;

                    }

                }
            }

        }
        foreach($data as $key=>$val){
            $data['profit_num']+=$val;
        }
        return $data;
    }

    /**
     * 获取分成佣金
     */
    public function get_user_separate_money($user_id=0,$separate=0){
        $user_id = $user_id > 0 ? $user_id : session('user_id');
        $affiliate = unserialize(C('affiliate'));
        empty($affiliate) && $affiliate = array();

        $separate_by = $affiliate['config']['separate_by'];

        // 获取一级订单
        $order_id = M()->table('order_info')->field('order_id')->where('parent_id='.$user_id ." and is_separate=".$separate)->select();
        $where = "0";
        foreach($order_id as $key=>$val){
            $where.=",".$val['order_id'];
        }
        $goods_list = M()->table('order_goods')->where('order_id in('.$where.')')->select();
        $money1=0;
        foreach($goods_list as $Key=>$val){
            $money1+= (float)$affiliate['item']['1']['level_money']*$val['touch_profit']/100;
        }
        // 二级
        $user_list = $this->saleuser($user_id);
        if($user_list){
            $where_user = '-1';
            foreach($user_list as $key=>$val){
                $where_user.=",".$val['user_id'];
            }
            $order_id = M()->table('order_info')->field('order_id')->where("parent_id in(".$where_user.") and is_separate=".$separate)->select();
            $where = "0";
            foreach($order_id as $key=>$val){
                $where.=",".$val['order_id'];
            }
            $goods_list = M()->table('order_goods')->where('order_id in('.$where.')')->select();
            $money2=0;
            foreach($goods_list as $Key=>$val){
                $money2+= (float)$affiliate['item']['2']['level_money']*$val['touch_profit']/100;
            }
            // 三级
            $user_list = M()->table('users')->field('user_id')->where("parent_id in(".$where_user.")")->select();
            if($user_list){
                $where_user = '-1';
                foreach($user_list as $key=>$val){
                    $where_user.=",".$val['user_id'];
                }
                $order_id = M()->table('order_info')->field('order_id')->where("parent_id in(".$where_user.") and is_separate=".$separate)->select();
                $where = "0";
                foreach($order_id as $key=>$val){
                    $where.=",".$val['order_id'];
                }
                $goods_list = M()->table('order_goods')->where('order_id in('.$where.')')->select();
                $money3=0;
                foreach($goods_list as $Key=>$val){
                    $money3+= (float)$affiliate['item']['3']['level_money']*$val['touch_profit']/100;
                }
            }else{
                $money3 = 0;
            }

        }else{
            $money2 =   0;
        }
        $data['money1'] = $money1 ? $money1 : 0;
        $data['money2'] = $money2 ? $money2 : 0;
        $data['money3'] = $money3 ? $money3 : 0;
        return $data;
    }


    /**
     * 根据银行卡id获取银行卡信息
     * @param $bank_id
     */
    public function get_bank_info($bank_id=0){
        if($bank_id==0){
            return false;
        }
        $bank_info = $this->model->table('drp_bank')->where("id=$bank_id")->select();
        return $bank_info['0'];
    }

    /**
     * 获取分销店铺是否开启
     * @param int $user_id
     */
    public function get_drp_status($user_id=0){
        if($user_id == 0){
            return false;
        }
        $status = $this->model->table('drp_shop')->where("user_id=".$user_id)->field('open')->getOne();
        if($status == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 添加访客信息
     * @param $drp_id
     */
    public function drp_visiter($drp_id){
        if($drp_id > 0 && session('user_id') > 0){
            if($this->model->table('drp_visiter')->where('drp_id = '.$drp_id .' and user_id='.session('user_id'))->count() == 0){
                $data['drp_id'] = $drp_id;
                $data['user_id'] = session('user_id');
                $data['visit_time'] = gmtime();
                $this->model->table('drp_visiter')->data($data)->insert();
            }
        }
    }

    /**
     * @param $order_id
     */
    public function update_order_sale($order_id){
        if($order_id > 0){
            $order_sn = $this->model->table('order_info')->where('order_id='.$order_id)->field('order_sn')->getOne();
            $goodsArr = $this->model->table('order_goods')->where('order_id='.$order_id)->field('goods_id,goods_number')->select();
            if($goodsArr){
                // 初始化分销商三级利润
                $sale_money = array(
                    'profit1'=>0,
                    'profit2'=>0,
                    'profit3'=>0,
                );
                foreach($goodsArr as $key=>$val){
                    $goods_sale = $this->model->table('drp_goods')->where('goods_id = '.$val['goods_id'])->field('touch_sale,touch_fencheng')->select();
                    if($goods_sale){
                        $data['touch_sale'] = $goods_sale['0']['touch_sale'];
                        $data['touch_fencheng'] = $goods_sale['0']['touch_fencheng'];
                        $data['goods_id'] = $val['goods_id'];
                        $data['order_id'] = $order_id;
                        $this->model->table('drp_order_goods')
                            ->data($data)
                            ->insert();
                        //  获取佣金比例
                        $profit = model('Sale')->get_drp_profit($data['goods_id']);
                        // 分销商三级利润
                        $sale_money['profit1']+= $data['touch_sale']/100*$profit['profit1']*$val['goods_number'];
                        $sale_money['profit2']+= $data['touch_sale']/100*$profit['profit2']*$val['goods_number'];
                        $sale_money['profit3']+= $data['touch_sale']/100*$profit['profit3']*$val['goods_number'];
                    }
                }
            }
            unset($data);
            $data['drp_id'] = $_SESSION['drp_shop']['drp_id'];
            $data['shop_separate'] = 0;
            $data['order_id'] = $order_id;
            $this->model->table('drp_order_info')
                ->data($data)
                ->insert();
        }

        // 获取订单所属店铺信息
        $drp_id = M()->table('drp_order_info')->field('drp_id')->where('order_id = ' . $order_id)->getOne();
        if($drp_id){
            // 本店用户id
            $user_id = M()->table('drp_shop')->field('user_id')->where('id = ' . $drp_id)->getOne();
            if($user_id){
                /* 插入帐户变动记录 */
                $account_log = array(
                    'user_id'       => $user_id,
                    'user_money'    => $sale_money['profit1'],
                    'change_time'   => gmtime(),
                    'change_desc'   => '订单分成，订单号：'.$order_sn.',分成金额：'.$sale_money['profit1'] ,
                    'order_id'      =>  $order_id,
                );

                $this->model->table('drp_log')
                    ->data($account_log)
                    ->insert();

                // 一级用户id
                $parent_id1 = M()->table('users')->field('parent_id')->where('user_id = ' . $user_id)->getOne();
                if($parent_id1){
                    /* 插入帐户变动记录 */
                    $account_log = array(
                        'user_id'       => $parent_id1,
                        'user_money'    => $sale_money['profit2'],
                        'change_time'   => gmtime(),
                        'change_desc'   => '订单分成，订单号：'.$order_sn.',分成金额：'.$sale_money['profit2'] ,
                        'order_id'      =>  $order_id,
                    );

                    $this->model->table('drp_log')
                        ->data($account_log)
                        ->insert();
                    // 二级用户id
                    $parent_id2 = M()->table('users')->field('parent_id')->where('user_id = ' . $parent_id1)->getOne();
                    if($parent_id2) {
                        /* 插入帐户变动记录 */
                        $account_log = array(
                            'user_id'       => $parent_id2,
                            'user_money'    => $sale_money['profit3'],
                            'change_time'   => gmtime(),
                            'change_desc'   => '订单分成，订单号：'.$order_sn.',分成金额：'.$sale_money['profit3'] ,
                            'order_id'      =>  $order_id,
                        );

                        $this->model->table('drp_log')
                            ->data($account_log)
                            ->insert();
                    }
                }
            }


        }

    }

    /**
     * @param int $order_id
     * 根据订单id获取订单商品
     */
    public function get_order_goods($order_id = 0){
        if($order_id > 0){
            $sql = "select og.rec_id,og.goods_id,og.goods_name,og.goods_number,og.goods_price, g.goods_thumb from {pre}order_goods as og join {pre}goods as g on og.goods_id = g.goods_id where og.order_id=$order_id";
            $goodsArr = $this->model->query($sql);
            if($goodsArr){
                foreach($goodsArr as $key=>$val){
                    $sql = "select * from {pre}drp_order_goods where order_id = $order_id and goods_id = $val[goods_id]";
                    $drp_goods = $this->model->getRow($sql);
                    $goodsArr[$key]['touch_sale'] = $drp_goods['touch_sale'];
                    $goodsArr[$key]['touch_fencheng'] = $drp_goods['touch_fencheng'];
                }
            }
            return $goodsArr;

        }
    }

}