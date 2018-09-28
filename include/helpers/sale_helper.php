<?php

/**
 * 获取店铺列表
 */
function get_shop_list($key=1){
    $global = getInstance();
    $res = array();
    $sql = "select d.* from " . $global->ecs->table("users") . " as u JOIN " . $global->ecs->table("drp_shop") . " d ON  u.user_id=d.user_id WHERE u.parent_id = ".$_SESSION['user_id'] ." and apply_sale = 1";
    $list = $global->db->getAll($sql);
    if($key == 1){
        $res = $list;
    }else{
        if($list){
            $where = '';
            foreach ($list as $key=>$val){
                $where.=','.$val['user_id'];
            }
            $sql = "select d.* from " . $global->ecs->table("users") . " as u JOIN " . $global->ecs->table("drp_shop") . " d ON  u.user_id=d.user_id WHERE u.parent_id in($where) and apply_sale = 1";
            $list2 = $global->db->getAll($sql);
            if($key == 2){
                $res = $list2;
            }else{
                if($list2){
                    $where = '';
                    foreach ($list2 as $key=>$val){
                        $where.=','.$val['user_id'];
                    }
                    $sql = "select d.* from " . $global->ecs->table("users") . " as u JOIN " . $global->ecs->table("drp_shop") . " d ON  u.user_id=d.user_id WHERE u.parent_id in($where) and apply_sale = 1";
                    $list3 = $global->db->getAll($sql);
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
    foreach($res as $key=>$val){
       $res[$key]['time'] = date('Y-m-d',$val['time']);
    }
    return $res;
}

/**
 * 获取我的下线会员
 */
function get_user_list($key=1){
    $global = getInstance();
    $res = array();
    $sql = "select * from " . $global->ecs->table("users") . "  WHERE parent_id = ".$_SESSION['user_id'];
    $list = $global->db->getAll($sql);
    if($key == 1){
        $res = $list;
    }else{
        if($list){
            $where = '';
            foreach ($list as $key=>$val){
                $where.=','.$val['user_id'];
            }
            $sql = "select * from " . $global->ecs->table("users") . " WHERE parent_id in($where) ";
            $list2 = $global->db->getAll($sql);
            if($key == 2){
                $res = $list2;
            }else{
                if($list2){
                    $where = '';
                    foreach ($list2 as $key=>$val){
                        $where.=','.$val['user_id'];
                    }
                    $sql = "select * from " . $global->ecs->table("users") . " WHERE parent_id in($where)";
                    $list3 = $global->db->getAll($sql);
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
    foreach($res as $key=>$val){
        $res[$key]['time'] = date('Y-m-d',$val['time']);
    }
    return $res;
}

    /**
     * 查询销售总额
     */
    function get_sale_money_total($uid=0){
        $global = getInstance();
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $global->db->getOne("select sum(user_money) from " . $global->ecs->table('sale_log') . " where user_id = ".$uid ." and user_money > 0");
    }
    /**
     * 查询分销商佣金
     * @access
     * @param   int     $user_id        会员ID
     * @return  int
     */
    function saleMoney($uid=0) {
        $global = getInstance();
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        return $global->db->getOne("select sum(user_money) from " . $global->ecs->table('sale_log') . " where user_id = ".$uid);

    }

    /**
     * 查询分销商当日佣金
     * @access
     * @param   int     $user_id        会员ID
     * @return  int
     */
    function saleMoney_today($uid=0) {
        $global = getInstance();
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        return $global->db->getOne("select sum(user_money) from " . $global->ecs->table('sale_log') . " where user_id = ".$uid ." and change_time > ".strtotime(date('Y-m-d')));

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
        $global = getInstance();
        // 获取余额记录
        $account_log = array();
        
        $sql = 'SELECT * FROM ' . $global->ecs->table('sale_log') . " WHERE user_id = " . $user_id . ' AND user_money <> 0 ' .
        " ORDER BY log_id DESC limit " . $start . ',' . $num;
        $res = $global->db->getAll($sql);
        
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
        }
        
        return $res;
        
       
    }
    
    

    
    /**
     * 获取分销商id
     * @param int $parent_id
     */
     function get_parent_id($parent_id){
        $parent_id_ =  $this->model->table('users')->field('parent_id')->where('user_id = '.session('user_id'))->find();
        return $parent_id_['parent_id'] > 0 ? $parent_id_['parent_id'] : $parent_id;
        
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
        $global = getInstance();
        /* 取得订单列表 */
        $arr = array();        
        $sql = "SELECT order_id, order_sn, shipping_id, order_status, shipping_status, pay_status, add_time, is_separate, " .
            "(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount) AS total_fee " .
            " FROM " . $global->ecs->table('order_info') .
            " WHERE  " . $where . " ORDER BY add_time DESC LIMIT $start , $num";
        $res = $global->db->getAll($sql);
        foreach ($res as $key => $value) {
            $value['shipping_status'] = ($value['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $value['shipping_status'];
            $value['order_status'] = L('os.' . $value['order_status']) . ',' . L('ps.' . $value['pay_status']) . ',' . L('ss.' . $value['shipping_status']);
			$goods_list = order_goods($value['order_id']);
			foreach ($goods_list as $key => $val) {
				$goods_list[$key]['market_price'] = price_format($val['market_price'], false);
				$goods_list[$key]['goods_price'] = price_format($val['goods_price'], false);
				$goods_list[$key]['subtotal'] = price_format($val['subtotal'], false);
				$goods_list[$key]['tags'] = get_tags($val['goods_id']);
				$goods_list[$key]['goods_thumb'] = get_image_path($value['order_id'], $val['goods_thumb']);
			}
            $arr[] = array('order_id' => $value['order_id'],
                'order_sn' => $value['order_sn'],
                'img' => get_image_path(0, model('Order')->get_order_thumb($value['order_id'])),
                'order_time' => local_date(C('time_format'), $value['add_time']),
                'order_status' => $value['order_status'],
                'shipping_id' => $value['shipping_id'],
                'total_fee' => price_format($value['total_fee'], false),
                'url' => url('user/order_detail', array('order_id' => $value['order_id'])),
                'goods_count' => get_order_goods_count($value['order_id']),
                'is_separate' => $value['is_separate'] > 0 ? "<span style='font-weight:bold'>已分成</span>" : "<span style='color:red;font-weight:bold'>未分成</span>",
				'goods'=>$goods_list,
                );
        }
        return $arr;
    }

/**
 *  获取我的会员数量
 * @param $key
 */
function get_user_count($user_id = 0){
    $global = getInstance();
    $user_id = $user_id > 0 ? $user_id : $_SESSION['user_id'];
    $list = array(); // 用户一级下线
    $list2 = array(); // 用户二级下线
    $list3 = array(); // 用户三级下线
    // 获取用户一级下线
    $sql = "select user_id,parent_id from ". $global->ecs->table("users") ." where parent_id=".$user_id;
    $list = $global->db->getAll($sql);
    // 获取用户二级下线
    if($list){
        $where = '';
        foreach ($list as $key=>$val){
            $where.=','.$val['user_id'];
        }
        $sql = "select user_id,parent_id from ". $global->ecs->table("users") ." where parent_id in ($where)";
        $list2 = $global->db->getAll($sql);
        // 获取用户三级下线
        if($list2){
            $where = '';
            foreach ($list2 as $key=>$val){
                $where.=','.$val['user_id'];
            }
            $sql = "select user_id,parent_id from ". $global->ecs->table("users") ." where parent_id in ($where)";
            $list3 = $global->db->getAll($sql);
        }
    }
    $info['count'] = count($list)+count($list2)+count($list3);
    $info['list'] = $list;
    $info['list2'] = $list2;
    $info['list3'] = $list3;
    return $info;
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
    function get_grade_order($where) {
        $global = getInstance();
        /* 取得订单列表 */
        $arr = array();
        $sql = "SELECT order_id  FROM " . $global->ecs->table('order_info') . " WHERE  " . $where;
        $res = $global->db->getAll($sql);
        foreach ($res as $key => $value) {
            $arr[] = array('order_id' => $value['order_id']);
        }
        return $arr;
    }
    
    /**
     *  获取分销指订单的详情
     *
     * @access  
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
        $order = model('Order')->order_info($order_id);
    
       
    
        //检查订单是否属于该用户
        if ($user_id > 0 && $user_id != $order['parent_id']) {
            ECTouch::err()->add(L('no_priv'));
    
            return false;
        }
    
        /* 对发货号处理 */
        if (!empty($order['invoice_no'])) {
            $sql = "SELECT shipping_code FROM " . $this->pre . "shipping WHERE shipping_id = '$order[shipping_id]'";
            $res = $this->row($sql);
            $shipping_code = $res['shipping_code'];
            $plugin = ROOT_PATH . 'includes/modules/shipping/' . $shipping_code . '.php';
            if (file_exists($plugin)) {
                include_once($plugin);
                $shipping = new $shipping_code;
                $order['invoice_no'] = $shipping->query($order['invoice_no']);
            }
        }
    
       
        $order['allow_update_address'] = 0;
        
    
        /* 获取订单中实体商品数量 */
        $order['exist_real_goods'] = model('Order')->exist_real_goods($order_id);
    
    
        /* 无配送时的处理 */
        $order['shipping_id'] == -1 and $order['shipping_name'] = L('shipping_not_need');
    
        /* 其他信息初始化 */
        $order['how_oos_name'] = $order['how_oos'];
        $order['how_surplus_name'] = $order['how_surplus'];
    
    
        /* 确认时间 支付时间 发货时间 */
        if ($order['confirm_time'] > 0 && ($order['order_status'] == OS_CONFIRMED || $order['order_status'] == OS_SPLITED || $order['order_status'] == OS_SPLITING_PART)) {
            $order['confirm_time'] = sprintf(L('confirm_time'), local_date(C('time_format'), $order['confirm_time']));
        } else {
            $order['confirm_time'] = '';
        }
        if ($order['pay_time'] > 0 && $order['pay_status'] != PS_UNPAYED) {
            $order['pay_time'] = sprintf(L('pay_time'), local_date(C('time_format'), $order['pay_time']));
        } else {
            $order['pay_time'] = '';
        }
        if ($order['shipping_time'] > 0 && in_array($order['shipping_status'], array(SS_SHIPPED, SS_RECEIVED))) {
            $order['shipping_time'] = sprintf(L('shipping_time'), local_date(C('time_format'), $order['shipping_time']));
        } else {
            $order['shipping_time'] = '';
        }
    
        return $order;
    }
    
    // 根据id获取用户名

     function get_user_by_id($user_id){

        $sql = "SELECT user_name FROM " . $this->pre . "users WHERE user_id = '$user_id'";
        $info = $this->row($sql);
        return $info['user_name'] ? $info['user_name'] : '';
    }
    
	/**
     * 获取用户下线商品数量
     * @return Ambigous <number, unknown>
     */
     function get_sale_goods_count(){
        $global = getInstance();
		$sql =  "select order_id from " . $global->ecs->table("order_info") . " where parent_id=".$_SESSION['user_id'];
		$arr_order_id = $global->db->getAll($sql);
		if($arr_order_id){
			$order_id = '';
			foreach($arr_order_id as $key=>$val){
				$order_id.=$val['order_id'].',';
				}
				$order_id = substr($order_id,0,-1);
			$sql = "select sum(goods_number) as count from ".$global->ecs->table("order_goods")." where order_id in (".$order_id.")";
			$res = M()->query($sql);
			return $res['0']['count'] > 0 ? $res['0']['count'] : 0;
		}else{
			return 0;
		}
    }


/**
 * 获取用户列表信息
 */
function get_sale_info($key=false){
    if(!$key) return false;
    $global = getInstance();
    $res = array();
    if($key == 'wfk'){
        $sql = "SELECT user_id FROM ". $global->ecs->table('order_info')." where user_id > 0 and pay_status = ".PS_UNPAYED." and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
        $user_list = $global->db->getAll($sql);
        if($user_list){
            foreach($user_list as $key=>$val){
                $sql = "SELECT count(*) as count FROM ". $global->ecs->table('order_info')." where user_id = ".$val['user_id']." and pay_status != ".PS_UNPAYED."and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
                if($global->db->getOne($sql) > 0){
                    unset($user_list[$key]);
                }else{
                    $info = get_user_default($val['user_id']);
                    $user_list[$key]['user_name'] = $info['username'];
                    $user_list[$key]['time'] = $global->db->table('users')->field('time')->where("user_id = ".$val['user_id'])->getOne();
                }
            }
        }
        $res['count'] = count($user_list);
        $res['list'] = $user_list;
    }elseif($key == 'yfk'){
        $sql = "SELECT user_id FROM ". $global->ecs->table('order_info')." where user_id > 0 and pay_status = ".PS_PAYED." and parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
        $user_list = $global->db->getAll($sql);
        if($user_list){
            foreach($user_list as $key=>$val){
                $info = get_user_default($val['user_id']);
                $user_list[$key]['user_name'] = $info['username'];
                $user_list[$key]['time'] = $global->db->table('users')->field('time')->where("user_id = ".$val['user_id'])->getOne();
            }
        }
        $res['count'] = count($user_list);
        $res['list'] = $user_list;
    }elseif($key == 'gz'){
        $sql = "SELECT user_id FROM ". $global->ecs->table('users')." as u join ".$global->ecs->table("wechat_user")." as w on u.user_id=w.ect_uid where u.user_id > 0 and w.subscribe = 1 and u.parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
        $user_list = $global->db->getAll($sql);
        $res['count'] = count($user_list);
        $res['list'] = $user_list;
    }elseif($key == 'fk'){
        $sql = "SELECT user_id FROM ". $global->ecs->table('users')."  where parent_id = " . $_SESSION['user_id'] . " GROUP BY user_id";
        $user_list = $global->db->getAll($sql);
    }else{
        return false;
    }

    return $res;


}
    
    
    /**
     * 获取用户一级下线数量
     * @return Ambigous <number, unknown>
     */
     function get_line_count($user_id=0){
        $user_id = $user_id > 0 ? $user_id : session('user_id');
        $count = M()->table('users')->field('COUNT(*)')->where("parent_id = ".$user_id)->getOne();
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
            $res[$k]['reg_time']    =   date('Y-m-d H:i:s', ($v['reg_time']+8*3600));
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
                $res[$k]['headimgurl'] = ____ . '/images/get_avatar.png';
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
        $global = getInstance();
        $uid = $uid > 0 ? $uid : $_SESSION['user_id'];
        $sql = "SELECT * FROM ".$global->ecs->table('users')." WHERE parent_id = " .$uid . " ORDER BY user_id DESC ";
        $res = $global->db->getAll($sql);
         if($res){
             foreach ($res as $k => $v) {
                 $res[$k]['user_id']     =   $v['user_id'];
             }
             return $res;
         }else{
             return array();
         }
    }
    

    
    /**
     * 购买成为分销商
     */
     function sale_apply($status,$user_id){
        
    }
