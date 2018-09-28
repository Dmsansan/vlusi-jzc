<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: liubo $
 * $Id: affiliate.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECTOUCH', true);
require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 分成管理页
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 获取分类列表 */
    $cate_list = $db->getAll("SELECT c.cat_name, c.cat_id, p.profit_id, p.cate_id, p.profit1, p.profit2, p.profit3 FROM " . $ecs->table("category") . "as c left join " . $ecs->table('drp_profit') . " as p on c.cat_id=p.cate_id WHERE parent_id= 0 and is_show = 1 ORDER BY c.sort_order ASC, c.cat_id ASC");

    foreach($cate_list as $key=>$val){
        $cate_list[$key]['profit1'] = $val['profit1'] > 0 ? $val['profit1'] : 0;
        $cate_list[$key]['profit2'] = $val['profit2'] > 0 ? $val['profit2'] : 0;
        $cate_list[$key]['profit3'] = $val['profit3'] > 0 ? $val['profit3'] : 0;
    }
    $smarty->assign('list', $cate_list);
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('ur_here', $_LANG['drp_profit']);
    $smarty->display('drp_cate_list.htm');
}
/*------------------------------------------------------ */
//-- 设置分销利润
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    if($_POST){
        $cate_id = $_POST['cate_id'] ? $_POST['cate_id'] : 0;
        if($cate_id == 0){
            ecs_header("Location: drp.php?act=list\n");
            exit;
        }
        $data = $_POST['data'];
        foreach($data as $key=>$val){
            if(!is_numeric($val) || $val < 0 || $val > 100){
                $data[$key] = 0;
            }
        }
        if($db->getRow("SELECT profit_id FROM" . $ecs->table('drp_profit') . " WHERE  cate_id=$cate_id")){
            $db->autoExecute($ecs->table('drp_profit'), $data, 'UPDATE', "cate_id = '$cate_id'");
        }else{
            $data['cate_id'] = $cate_id;
            $db->autoExecute($ecs->table('drp_profit'), $data, 'INSERT');
        }
        ecs_header("Location: drp.php?act=list\n");
        exit;
    }
    $id = $_GET['id'] ? $_GET['id'] : 0;
    if($id == 0){
        ecs_header("Location: drp.php?act=list\n");
        exit;
    }
    /* 获取分类列表 */
    $cate_list = $db->getRow("SELECT c.cat_name, c.cat_id, p.profit_id, p.cate_id, p.profit1, p.profit2, p.profit3 FROM " . $ecs->table("category") . "as c left join " . $ecs->table('drp_profit') . " as p on c.cat_id=p.cate_id WHERE parent_id= 0 and is_show = 1 and cat_id=$id");


    $cate_list['profit1'] = $cate_list['profit1'] > 0 ? $cate_list['profit1'] : 0;
    $cate_list['profit2'] = $cate_list['profit2'] > 0 ? $cate_list['profit2'] : 0;
    $cate_list['profit3'] = $cate_list['profit3'] > 0 ? $cate_list['profit3'] : 0;

    $smarty->assign('list', $cate_list);
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('ur_here', $_LANG['drp_profit']);
    $smarty->display('drp_cate_edit.htm');
}


/*------------------------------------------------------ */
//-- 分销设置
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'config')
{
    if($_POST){
        $data = $_POST['data'];
        if($data){
            foreach($data as $key=>$val){
                unset($dat);
                $dat['value']=$val;
                $db->autoExecute($ecs->table('drp_config'), $dat, 'UPDATE', "keyword = '$key'");
            }
        }

        ecs_header("Location: drp.php?act=config\n");
        exit;
    }
    $info = $db->getAll("SELECT * FROM " . $ecs->table("drp_config"));
    foreach($info as $key=>$val){
        // radio
        if($val['type'] == 'radio'){
            $info[$key]['centent'] = explode(',',$val['centent']);
        }
    }

    $smarty->assign('info', $info);
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('ur_here', $_LANG['config']);
    $smarty->display('drp_config.htm');
}

/*------------------------------------------------------ */
//-- 分销商管理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'users')
{
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
	if($_POST){
		$username = $_POST['username'] ? $_POST['username'] : '';
		$user_name = $_POST['user_name'] ? $_POST['user_name'] : '';
		$shop_mobile = $_POST['shop_mobile'] ? $_POST['shop_mobile'] : '';
		$drp_name = $_POST['drp_name'] ? $_POST['drp_name'] : '';		
	}
	
    $list = get_user_list(1,$username,$user_name, $shop_mobile, $drp_name);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('keyword', 'novice');
    $smarty->assign('etime', date("Y-m-d H:i:s"));
    $smarty->assign('stime', date("Y-m-d H:i:s",time()-86400*7));
    $smarty->assign('ur_here', $_LANG['drp_profit']);
    $smarty->display('drp_users.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $list = get_user_list(1,$username,$user_name, $shop_mobile, $drp_name);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_users.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*-
/*------------------------------------------------------ */
//-- 分销商审核管理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'users_audit')
{
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
	if($_POST){
		$username = $_POST['username'] ? $_POST['username'] : '';
		$user_name = $_POST['user_name'] ? $_POST['user_name'] : '';
		$shop_mobile = $_POST['shop_mobile'] ? $_POST['shop_mobile'] : '';
		$drp_name = $_POST['drp_name'] ? $_POST['drp_name'] : '';		
	}

    $list = get_user_list(0,$username,$user_name, $shop_mobile, $drp_name);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('keyword', 'novice');
    $smarty->assign('etime', date("Y-m-d H:i:s"));
    $smarty->assign('stime', date("Y-m-d H:i:s",time()-86400*7));
    $smarty->assign('ur_here', $_LANG['drp_profit']);
    $smarty->display('drp_users_audit.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'users_audit_query')
{
    $list = get_user_list(0,$username,$user_name, $shop_mobile, $drp_name);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_users_audit.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 修改店铺状态
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'user_change')
{
    $id = $_GET['id'] ? $_GET['id'] : 0;
    if($id == 0){
        ecs_header("Location: drp.php?act=users\n");
        exit;
    }
    $open = $_GET['open'] > 0 ? 0 : 1;
    $data['open']=$open;
    $db->autoExecute($ecs->table('drp_shop'), $data, 'UPDATE', "id = '$id'");
    ecs_header("Location: drp.php?act=users\n");
    exit;
}
/*------------------------------------------------------ */
//-- 编辑店铺审核信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'user_audit_edit')
{
    // 修改店铺信息
    if($_POST){
        $id = $_POST['id'] ? $_POST['id'] : 0;
        if($id == 0){
            sys_msg($_LANG['sale_cate_not_empty']);
        }
        $data = $_POST['data'];
        $cat_id = '';
        if($data['cat_id']){
            foreach($data['cat_id'] as $key=>$val){
                $cat_id.=$val.',';
            }
        }else{
            sys_msg($_LANG['sale_cate_not_empty']);
        }
        $data['cat_id'] = $cat_id;
        $db->autoExecute($ecs->table('drp_shop'), $data, 'UPDATE', "id = '$id'");
        ecs_header("Location: drp.php?act=users_audit\n");
        exit;
    }
    $id = $_GET['id'] ? $_GET['id'] : 0;
    if($id == 0){
        ecs_header("Location: drp.php?act=users\n");
        exit;
    }
    // 获取店铺信息
    $info = $db->getRow("SELECT d.id,d.shop_name,d.real_name,d.shop_mobile,d.shop_qq,d.user_id,d.cat_id,d.open,d.audit,u.user_name FROM " . $ecs->table("drp_shop") . " as d join " . $ecs->table("users") . " as u on d.user_id=u.user_id where d.id = $id");
    $smarty->assign('info', $info);

    $catArr = explode(',',$info['cat_id']);
    if($catArr){
        unset($catArr[(count($catArr)-1)]);
    }
    // 获取所有一级分类
    $category = $db->getAll("select cat_id,cat_name from " . $ecs->table("category") . " where parent_id =0");
    if($category){
        foreach($category as $key=>$val){
            if(in_array($val['cat_id'],$catArr)){
                $category[$key]['is_select'] = 1;
            }
        }
    }
    $smarty->assign('category', $category);
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('keyword', 'novice');
    $smarty->assign('ur_here', $_LANG['drp_user_edit']);
    $smarty->display('drp_users_edit.htm');
}
/*------------------------------------------------------ */
//-- 编辑店铺信息
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'user_edit')
{
    // 修改店铺信息
    if($_POST){
        $id = $_POST['id'] ? $_POST['id'] : 0;
        if($id == 0){
            sys_msg($_LANG['sale_cate_not_empty']);
        }
        $data = $_POST['data'];
        $cat_id = '';
        if($data['cat_id']){
            foreach($data['cat_id'] as $key=>$val){
                $cat_id.=$val.',';
            }
        }else{
            sys_msg($_LANG['sale_cate_not_empty']);
        }
        $data['cat_id'] = $cat_id;
        $db->autoExecute($ecs->table('drp_shop'), $data, 'UPDATE', "id = '$id'");
        ecs_header("Location: drp.php?act=users\n");
        exit;
    }
    $id = $_GET['id'] ? $_GET['id'] : 0;
    if($id == 0){
        ecs_header("Location: drp.php?act=users\n");
        exit;
    }
    // 获取店铺信息
    $info = $db->getRow("SELECT d.id,d.shop_name,d.real_name,d.shop_mobile,d.shop_qq,d.user_id,d.cat_id,d.open,d.audit,u.user_name FROM " . $ecs->table("drp_shop") . " as d join " . $ecs->table("users") . " as u on d.user_id=u.user_id where d.id = $id");
    $smarty->assign('info', $info);

    $catArr = explode(',',$info['cat_id']);
    if($catArr){
        unset($catArr[(count($catArr)-1)]);
    }
    // 获取所有一级分类
    $category = $db->getAll("select cat_id,cat_name from " . $ecs->table("category") . " where parent_id = 0 and is_show = 1 ORDER BY sort_order ASC, cat_id ASC");
    if($category){
        foreach($category as $key=>$val){
            if(in_array($val['cat_id'],$catArr)){
                $category[$key]['is_select'] = 1;
            }
        }
    }
    $smarty->assign('category', $category);
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('keyword', 'novice');
    $smarty->assign('ur_here', $_LANG['drp_user_edit']);
    $smarty->display('drp_users_edit.htm');
}

/*------------------------------------------------------ */
//-- 查看店铺订单
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'user_order')
{
    // 获取用户id
    $user_id = $_GET['id'] ? $_GET['id'] : 0;
    if($user_id == 0){

        sys_msg($_LANG['empty_id']);
    }
	$_SESSION['user_id'] = $user_id;
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('user_id',$user_id);
    $list = get_user_order_list($user_id);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('ur_here', $_LANG['drp_user_edit']);
    $smarty->display('drp_user_order.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'user_order_list')
{
	$user_id = $_SESSION['user_id'];
    $list = get_user_order_list($user_id);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_user_order.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 查看店铺订单
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'user_log')
{
    // 获取用户id
    $user_id = $_GET['id'] ? $_GET['id'] : 0;
    if($user_id == 0){

        sys_msg($_LANG['empty_id']);
    }
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $smarty->assign('user_id',$user_id);
    $list = get_user_log_list($user_id);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('ur_here', $_LANG['drp_user_edit']);
    $smarty->display('drp_user_log.htm');
}

/*------------------------------------------------------ */
//-- 佣金管理
/*------------------------------------------------------ */
if($_REQUEST['act'] == 'drp_log'){
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $list = get_drp_log();
    $smarty->assign('etime', date("Y-m-d H:i:s"));
    $smarty->assign('stime', date("Y-m-d H:i:s",time()-86400*7));    
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);
    $smarty->display('drp_log.htm');
}
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drp_log_query')
{
    $list = get_drp_log();
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_log.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
/*------------------------------------------------------ */
//-- 佣金提现管理功能
/*------------------------------------------------------ */
if($_REQUEST['act'] == 'drp_refer'){
    if(IS_GET){
        $id =$_GET['id'];
        $money = $db->getRow("SELECT user_money,user_id,status FROM".$ecs->table("drp_log")."WHERE log_id =".$id);
        if(intval($money['status']) == DRP_NOT_MANAGE){
            $age['status'] = DRP_MANAGE;
            $db->autoExecute($ecs->table('drp_log'), $age, 'UPDATE', "log_id =".$id);
            $links[0]['text'] = $GLOBALS['_LANG']['go_back'];
            $links[0]['href'] = 'drp.php?act=drp_log';
            sys_msg($_LANG['withdraw_ok'],'0',$links);
        }else{
            $links[0]['href'] = 'drp.php?act=drp_log';
            sys_msg($_LANG['The_extracted'],'1',$links);
        }
    }
}
/*------------------------------------------------------ */
//-- 佣金提现删除
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'order_delete'){
    if(IS_GET){
        $id=$_GET['id'];
        $money = $db->getRow("SELECT status,user_id FROM ".$ecs->table("drp_log")." WHERE log_id =".$id);
        if(intval($money['status']) == DRP_MANAGE){
            $sql = "DELETE FROM " . $ecs->table('drp_log') .
                " WHERE user_id = ".$money['user_id'] ." and log_id = $id";
            $delete = $db->query($sql);
            if($delete == true){
                $links[0]['href'] = 'drp.php?act=drp_log';
                sys_msg($_LANG['delete_Success'],'',$links);
            }
        }
    }
}

/*------------------------------------------------------ */
//-- 订单列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'order_list')
{
    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }
    $is_separate = $_GET['is_separate'] ? $_GET['is_separate'] : 0;
    $smarty->assign('is_separate', $is_separate);
	if($_POST){
		$order_sn = $_POST['order_sn'] ? $_POST['order_sn'] : '';
	}
    $list = get_order_list($is_separate,$order_sn);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('ur_here', $_LANG['drp_profit']);
    $smarty->display('drp_order_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'order_list_query')
{
    $is_separate = $_POST['is_separate'] ? $_POST['is_separate'] : 0;
    $smarty->assign('is_separate', $is_separate);
    $list = get_order_list($is_separate,$order_id);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_order_list.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}
/*------------------------------------------------------ */
//-- 分销分成
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'separate')
{
    include_once(BASE_PATH . 'helpers/order_helper.php');
    $oid = (int)$_REQUEST['oid'];
    $row = $db->getRow("SELECT order_id,shop_separate  FROM " . $GLOBALS['ecs']->table('drp_order_info').
        " WHERE order_id = '$oid'");

    if ($row['shop_separate'] == 0)
    {

        $log = $db->getAll("SELECT *  FROM " . $GLOBALS['ecs']->table('drp_log').
            " WHERE order_id = '$oid'");

        if($log){
            foreach($log as $key=>$val){
                drp_log_change($val['user_id'], $val['user_money'], $val['pay_points']);
            }
        }
        $sql = "UPDATE " . $GLOBALS['ecs']->table('drp_order_info') .
            " SET shop_separate = 1" .
            " WHERE order_id = $oid LIMIT 1";
        $db->query($sql);


        $sql = "UPDATE " . $GLOBALS['ecs']->table('drp_log') .
            " SET status = 1" .
            " WHERE order_id = $oid ";
        $db->query($sql);

    }
    $links[] = array('text' => $_LANG['affiliate_ck'], 'href' => 'drp.php?act=order_list');
    sys_msg($_LANG['edit_ok'], 0 ,$links);
}

/*------------------------------------------------------ */
//-- 分销商排行榜
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'ranking')
{

    assign_query_info();
    if (empty($_REQUEST['is_ajax']))
    {
        $smarty->assign('full_page', 1);
    }

    $time = $_REQUEST['time'];
    $smarty->assign('time',         $time);
    $list = get_user_ranking($time);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $smarty->assign('ur_here', $_LANG['ranking']);
    $smarty->display('drp_ranking.htm');

}

/*------------------------------------------------------ */
//-- 分销商排行榜
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'ranking_query')
{
    $time = $_POST['time'] ? $_POST['time'] : 0;
    $smarty->assign('time', $time);
    $list = get_user_ranking($time);
    $smarty->assign('list',         $list['list']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    make_json_result($smarty->fetch('drp_ranking.htm'), '',array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/**
 * 取得分销商列表
 * @param   int     $user_id    用户id
 * @param   string  $account_type   帐户类型：空表示所有帐户，user_money表示可用资金，
 *                  frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
 * @return  array
 */
function get_user_list($type ,$username,$user_name, $shop_mobile, $drp_name)
{
    
    /* 初始化分页参数 */
    $filter = array(

    );
	$conditioin = ' where `audit` = "'.$type.'" '; // 是否审核
	
    $where = 'where audit = "'.$type.'"';

	if ($user_name != '') {
            $where .= " and d.real_name like '%$user_name%'  ";
        } 
	if ($shop_mobile != '') {
            $where .= "and d.shop_mobile like '%$shop_mobile%' ";
        } 
	if ($username != '') {
            $where .= "and u.user_name like '%$username%' ";
        } 
	if ($drp_name != '') {
            $where .= "and d.shop_name like '%$drp_name%' ";
        }

    
    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('drp_shop') . " as d left join " . $GLOBALS['ecs']->table('users') ." as u on  d.user_id=u.user_id".
        " $where ";
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);
    /* 查询记录 */
    $sql = "SELECT *, u.user_name FROM " . $GLOBALS['ecs']->table('drp_shop') . " as d left join " . $GLOBALS['ecs']->table('users') ." as u on  d.user_id=u.user_id".
        " $where ORDER BY id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['create_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['create_time']);
        /* $row['user_name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$row['user_id']); */
        $arr[] = $row;
    }
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 取得分销商订单
 * @param   int     $user_id    用户id
 * @param   string  $account_type   帐户类型：空表示所有帐户，user_money表示可用资金，
 *                  frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
 * @return  array
 */
function get_user_order_list($user_id)
{
    /* 初始化分页参数 */
    $filter = array(
        'id'=>$user_id,
    );
    $sql = "SELECT id FROM " . $GLOBALS['ecs']->table('drp_shop'). " WHERE user_id = $user_id ";
    $drp_id = $GLOBALS['db']->getOne($sql);
    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('drp_order_info'). " WHERE drp_id = $drp_id ";
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);

    /* 查询记录 */
    $sql = "SELECT o.* FROM " .  $GLOBALS['ecs']->table('order_info'). " as o join ".$GLOBALS['ecs']->table('drp_order_info')." as d on d.order_id=o.order_id WHERE d.drp_id = $drp_id " .
        " ORDER BY order_id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
        $row['user_name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$row['user_id']);
        $row['parent_name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$row['parent_id']);
        $arr[] = $row;
    }
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


/**
 * 取得分销商佣金
 * @param   int     $user_id    用户id
 * @param   string  $account_type   帐户类型：空表示所有帐户，user_money表示可用资金，
 *                  frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
 * @return  array
 */
function get_user_log_list($user_id)
{
    /* 初始化分页参数 */
    $filter = array(
        'id'=>$user_id,
    );
    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('drp_log'). " WHERE user_id = $user_id  and user_money > 0";
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);

    /* 查询记录 */
    $sql = "SELECT * FROM " .  $GLOBALS['ecs']->table('drp_log'). " WHERE user_id = $user_id  and user_money > 0 " .
        " ORDER BY log_id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['change_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['change_time']);
        $arr[] = $row;
    }
 
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


/**
 * 取得订单
 * @param   int     $user_id    用户id
 * @param   string  $account_type   帐户类型：空表示所有帐户，user_money表示可用资金，
 *                  frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
 * @return  array
 */
function get_order_list($is_separate,$order_sn)
{
    /* 初始化分页参数 */
    $filter = array(
        'is_separate'=>$is_separate,
    );
	if ($order_sn != '') {
		$where .= " and o.order_sn like '%$order_sn%'  ";
    } 
    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_info'). " as o join ".$GLOBALS['ecs']->table('drp_order_info')." as d on d.order_id=o.order_id WHERE d.drp_id > 0 $where and  d.shop_separate = ".$is_separate;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);
    /* 查询记录 */
    $sql = "SELECT o.*,d.drp_id as drp FROM " .  $GLOBALS['ecs']->table('order_info'). " as o join ".$GLOBALS['ecs']->table('drp_order_info')." as d on d.order_id=o.order_id WHERE d.drp_id > 0 and  d.shop_separate = $is_separate $where" .
        " ORDER BY order_id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    // 获取分销天数
    $fxts = $GLOBALS['db']->getOne("select value from " . $GLOBALS['ecs']->table('drp_config') ." where keyword = 'fxts'");
    $fxts = $fxts*3600*24;
    $nowTime = gmtime();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if($row['pay_time'] > 0 && ($row['pay_time']+$fxts) <= $nowTime){
            $row['separate'] = 1;
        }
        $row['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
        $row['user_name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$row['user_id']);
        $row['parent_name'] = $GLOBALS['db']->getOne("select shop_name from ".$GLOBALS['ecs']->table('drp_shop') ." where id = ".$row['drp']);
        $log = $GLOBALS['db']->getAll("select user_id,change_desc from ".$GLOBALS['ecs']->table('drp_log') ." where order_id = ".$row['order_id']);
        if($log){
            foreach($log as $key=>$val){
                $log[$key]['name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$val['user_id']);
            }
        }
        $row['log'] = $log;
        $arr[] = $row;
    }
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 获取佣金提现记录
 */
function get_drp_log(){
    /* 初始化分页参数 */
    $filter = array(
    );

    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('drp_log'). " WHERE change_type = ".DRP_WITHDRAW;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);
    /* 查询记录 */
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('drp_log'). " WHERE change_type = ".DRP_WITHDRAW .
        " ORDER BY change_time DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    while ($row = $GLOBALS['db']->fetchRow($res)){
        $row['log_id'] = $row['log_id'];
        $row['change_time'] = date("Y-m-d H:i:s",$row['change_time']);
        $row['user_money'] = $row['user_money'];
        $row['user_name'] = $GLOBALS['db']->getOne("SELECT user_name FROM".$GLOBALS['ecs']->table("users")."WHERE user_id =".$row['user_id']);
        $row['shop_name'] = $GLOBALS['db']->getOne("SELECT shop_name FROM".$GLOBALS['ecs']->table('drp_shop')."WHERE user_id =".$row['user_id']);
        $row['status_show'] = $row['status'] == DRP_NOT_MANAGE ? '未支付' : '已支付';
        $row['status'] = $row['status'];
        $arr[] = $row;
        
    }
   
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}


/**
 * 获取分销商排行
 * @return array
 */
function get_user_ranking($time)
{
    /* 初始化分页参数 */
    $filter = array(
        'time'=>$time,
    );

    /* 查询记录总数，计算分页数 */
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('drp_shop') . ' where `audit` = "1" ';
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);
    $filter = page_and_size($filter);

    $ext = '';
    if ($time == '1') {// 一年
        $ext = "AND l.change_time >'" . local_strtotime('-1 years') . "'";
    } elseif ($time == '2') {// 半年
        $ext = "AND l.change_time > '" . local_strtotime('-6 months') . "'";
    } elseif ($time == '3') {// 一个月
        $ext = " AND l.change_time > '" . local_strtotime(' - 1 months') . "'";
    }

    /* 查询记录 */
    $sql = "SELECT s1.* ,(select sum(l.user_money) from ". $GLOBALS['ecs']->table('drp_log')." as l join " . $GLOBALS['ecs']->table('drp_shop') ." as s on l.user_id=s.user_id where s.user_id=s1.user_id and l.user_money > 0 and l.status=1 ".$ext.") as sale_money ,(select sum(o.goods_amount) from ". $GLOBALS['ecs']->table('order_info')." as o left join " . $GLOBALS['ecs']->table('drp_log') ." as l on o.order_id = l.order_id " . 'left join' . $GLOBALS['ecs']->table('drp_order_info') ." as d on o.order_id = d.order_id where d.drp_id = s1.id and o.pay_status=2 ".$ext.") as sales_volume FROM " . $GLOBALS['ecs']->table('drp_shop') ." as s1 where s1.`audit` = '1' ORDER BY sale_money DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['create_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['create_time']);
        $row['user_name'] = $GLOBALS['db']->getOne("select user_name from ".$GLOBALS['ecs']->table('users') ." where user_id = ".$row['user_id']);
        $row['sale_money'] = $row['sale_money'] ? $row['sale_money'] : 0;
        $row['sales_volume'] = $row['sales_volume'] ? $row['sales_volume'] : 0;
        $arr[] = $row;
    }
    return array('list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 获取佣金比例
 * @param $goods_id
 */
function get_drp_profit($goods_id=0){
    if($goods_id == 0 ){
        return false;
    }
    $id = $GLOBALS['db']->getOne("select cat_id from ".$GLOBALS['ecs']->table('goods') ." where goods_id = ".$goods_id);
    $id = get_goods_cat($id);
    $profit = $GLOBALS['db']->getRow("select * from ".$GLOBALS['ecs']->table('drp_profit') ." where cate_id = ".$id);
    return $profit ? $profit : false;
}

function get_goods_cat($id){
    $parent_id = $GLOBALS['db']->getOne("select parent_id from ".$GLOBALS['ecs']->table('category') ." where cat_id = ".$id);
    if($parent_id==0){
        return $id;
    }else{
        $id = get_goods_cat($parent_id);
        return $id;
    }
}

/**
 * 记录帐户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   int     $pay_points     消费积分变动
 * @param   string  $change_desc    变动说明
 * @return  void
 */
function drp_log_change($user_id, $user_money = 0, $pay_points = 0)
{
    /* 更新用户信息 */
    $sql = "UPDATE " . $GLOBALS['ecs']->table('drp_shop') .
        " SET money = money + ('$user_money')" .
        " WHERE user_id = '$user_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
}



//导出分销商
if($_REQUEST['act'] == 'export'){
   if(!empty($_POST['start_time']) || !empty($_POST['end_time'])){
        $condition = ($_GET['from'] = 'users_audit') ? ' AND `audit` = "0"' : ' AND `audit` = "1"';
        $start_time =strtotime($_POST['start_time']);
        $end_time =strtotime($_POST['end_time']);
        $sql = "SELECT *  FROM " . $ecs->table('drp_shop') .
                " WHERE `create_time` >= '".$start_time."' AND `create_time` <= '".$end_time."'  ORDER BY id DESC ";
        $list = $db->getAll($sql);
        include_once (ROOT_PATH . 'include/vendor/PHPExcel.php');
         //创建处理对象实例
        $objPhpExcel = new PHPExcel();
        $objPhpExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
        //设置表格的宽度  手动
        $objPhpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPhpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPhpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $objPhpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        //设置标题
        $rowVal = array(0=>'编号',1=>'店铺名', 2=>'真实姓名', 3=>'手机号码',4=>'开店时间', 5=>'店铺是否审核（1为已审核，0为未审核）', 6=>'店铺状态（1为开启，0为关闭）',7=>'QQ号');
        foreach ($rowVal as $k=>$r){
            $objPhpExcel->getActiveSheet()->getStyleByColumnAndRow($k,1)->getFont()->setBold(true);//字体加粗
            $objPhpExcel->getActiveSheet()->getStyleByColumnAndRow($k,1)->getAlignment(); //->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//文字居中
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($k,1,$r);
        }
        //设置当前的sheet索引 用于后续内容操作
        $objPhpExcel->setActiveSheetIndex(0);
        $objActSheet=$objPhpExcel->getActiveSheet();
        //设置当前活动的sheet的名称
        $title="分销商信息";
        $objActSheet->setTitle($title);
        //设置单元格内容
        foreach($list as $k => $v)
        {
            $num = $k+2;
            $objPhpExcel->setActiveSheetIndex(0)
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.$num, $v['id'])
            ->setCellValue('B'.$num, $v['shop_name'])
            ->setCellValue('C'.$num, $v['real_name'])
            ->setCellValue('D'.$num, $v['shop_mobile'])
            ->setCellValue('E'.$num, date("Y-m-d H:i:s",$v['create_time']))
            ->setCellValue('F'.$num, $v['audit'])
            ->setCellValue('G'.$num, $v['open'])
            ->setCellValue('H'.$num, $v['shop_qq'])
            ;
        }
        $name = date('Y-m-d'); //设置文件名
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding:utf-8");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-e xcel');
        header('Content-Disposition: attachment;filename="'.$title.'_'.urlencode($name).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');
        $objWriter->save('php://output');
   }
    
}

//佣金体现导出
if($_REQUEST['act'] == 'drplogexport'){
   if(!empty($_POST['start_time']) ||!empty($_POST['end_time'])){
       $start_time =strtotime($_POST['start_time']);
       $end_time =strtotime($_POST['end_time']);
       $sql="select dl.*,ds.shop_name,u.user_name  FROM ".$ecs->table('drp_log').
          " as dl left join ".$ecs->table('drp_shop').
          " as ds on dl.user_id=ds.user_id left join ".
          $ecs->table('users').
          " as u on dl.user_id=u.user_id ".
          " WHERE dl.change_time >="
          .$start_time.
          " AND dl.change_time <="
          .$end_time.
          " ORDER BY dl.log_id DESC " ;
        $list = $db->getAll($sql); 
        include_once (ROOT_PATH . 'include/vendor/PHPExcel.php');
         //创建处理对象实例
        $objPhpExcel = new PHPExcel();
        $objPhpExcel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);//设置单元格宽度
        //设置表格的宽度  手动
        $objPhpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPhpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPhpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        //设置标题
        $rowVal = array(0=>'编号',1=>'店铺名', 2=>'会员名称', 3=>'操作日期', 4=>'提现金额', 5=>'提现信息', 6=>'佣金状态(1为已支付,0为未支付)');
        foreach ($rowVal as $k=>$r){
            $objPhpExcel->getActiveSheet()->getStyleByColumnAndRow($k,1)->getFont()->setBold(true);//字体加粗
            $objPhpExcel->getActiveSheet()->getStyleByColumnAndRow($k,1)->getAlignment(); //->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//文字居中
            $objPhpExcel->getActiveSheet()->setCellValueByColumnAndRow($k,1,$r);
        }
        //设置当前的sheet索引 用于后续内容操作
        $objPhpExcel->setActiveSheetIndex(0);
        $objActSheet=$objPhpExcel->getActiveSheet();
        //设置当前活动的sheet的名称
        $title="佣金提现管理";
        $objActSheet->setTitle($title);
        //设置单元格内容
        foreach($list as $k => $v)
        {
            $num = $k+2;
            $objPhpExcel->setActiveSheetIndex(0)
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.$num, $v['log_id'])
            ->setCellValue('B'.$num, $v['shop_name'])
            ->setCellValue('C'.$num, $v['user_name'])
            ->setCellValue('D'.$num, date("Y-m-d H:i:s",$v['change_time']))
            ->setCellValue('E'.$num, $v['user_money'])
            ->setCellValue('F'.$num, $v['bank_info'])
            ->setCellValue('G'.$num, $v['status'])
           
            ;
        }
       
        $name = date('Y-m-d'); //设置文件名
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding:utf-8");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-e xcel');
        header('Content-Disposition: attachment;filename="'.$title.'_'.urlencode($name).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');
        $objWriter->save('php://output');
   }
}


        



?>