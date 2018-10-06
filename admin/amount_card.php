<?php

/**
 * 代金卡管理程序文件
*/

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/includes/init.php');

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("amount_card"), $db, 'amount_id', 'amount_id');
//$image = new cls_image();

/*------------------------------------------------------ */
//-- 代金卡列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      $_LANG['16_amount_card_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['amount_card_add'], 'href' => 'amount_card.php?act=add'));
    $smarty->assign('action_link3',  array('text' => $_LANG['amount_card_group_add'], 'href' => 'amount_card.php?act=group_add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $cards_list = get_amount_cardlist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('amount_card_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('amount_card');

    $cards_list = get_amount_cardlist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('amount_card_list.htm'), '',
        array('filter' => $cards_list['filter'], 'page_count' => $cards_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /*初始化*/
    $cards = array();
    $cards['amount_status'] = 1;

    /*初始化金额选项*/
    $sql = "SELECT cc.id,cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           "ORDER BY cc.id DESC";
    $res = $db->query($sql);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }

    $smarty->assign('card_count',  $arr);//类型集合
    $smarty->assign('card_type_add_url', 'card_type.php?act=add');
    $smarty->assign('cards',       $cards);
    $smarty->assign('ur_here',     $_LANG['amount_card_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_amount_card_list'], 'href' => 'amount_card.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('amount_card_info.htm');
}

/*------------------------------------------------------ */
//-- 批量添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'group_add')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /*初始化*/
    $cards = array();
    $cards['amount_status'] = 1;

    /*初始化金额选项*/
    $sql = "SELECT cc.id,cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           "ORDER BY cc.id DESC";
    $res = $db->query($sql);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }

    $smarty->assign('card_count',  $arr);//类型集合
    $smarty->assign('card_type_add_url', 'card_type.php?act=add');
    $smarty->assign('cards',       $cards);
    $smarty->assign('ur_here',     $_LANG['amount_card_group_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_amount_card_list'], 'href' => 'amount_card.php?act=list'));
    $smarty->assign('form_action', 'group_insert');

    assign_query_info();
    $smarty->display('amount_card_group_add.htm');
}

/*------------------------------------------------------ */
//-- 添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /*检查是否重复*/
    $is_only = $exc->is_only('amount_number', $_POST['amount_number'],0, " amount_number='$_POST[amount_number]'");

    if (!$is_only)
    {
        sys_msg($_LANG['card_exist'], 1);
    }

    /*插入数据*/
    $add_time = date("Y-m-d H:i:s");
    if (empty($_POST['amount_status']))
    {
        $_POST['amount_status'] = 1;
    }
    /*查找类型金额*/
    $id = $_POST['amount_count'];
    $sql_type = "SELECT cc.card_count".
                " FROM " . $ecs->table('card_type') . " AS cc ".
                " WHERE cc.id='$id'";
    $count = $db->GetRow($sql_type);

    $sql = "INSERT INTO ".$ecs->table('amount_card')."(amount_list, amount_number, amount_password, amount_status, amount_count, type_id, expry_date, add_date) ".
                    "VALUES ('$_POST[amount_list]', '$amount_number', '$amount_password', '$_POST[amount_status]', '$count[card_count]', '$id' ,'$_POST[expry_date]', '$add_time')";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'amount_card.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'amount_card.php?act=list';

    admin_log($_POST['amount_number'],'add','amount_card');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 批量添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'group_insert')
{
    /* 权限判断 */
    admin_priv('amount_card');
    $amount_num = $_POST['amount_num'];//生卡数量
    for($j = 0; $j<$amount_num; $j++){
           //自动生成代金卡号
            $amount_number =  create_amount_number();
            $amount_number_str .= $amount_num.',';
            //自动生成密码
            for($i=0;$i<3;$i++){
                $amount_password .= create_amount_password(4)."-";
            }
            $amount_password .= create_amount_password(4);
            /*检查是否重复*/
            $is_only = $exc->is_only('amount_number', $amount_number,0, " amount_number='$amount_number'");

            if (!$is_only)
            {
                sys_msg($_LANG['card_exist'], 1);
            }

            /*插入数据*/
            $add_time = date("Y-m-d H:i:s");
            if (empty($_POST['amount_status']))
            {
                $_POST['amount_status'] = 1;
            }
            /*查找类型金额*/
            $id = $_POST['amount_count'];
            $sql_type = "SELECT cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           " WHERE cc.id='$id'";
            $count = $db->GetRow($sql_type);

            $sql = "INSERT INTO ".$ecs->table('amount_card')."(amount_list, amount_number, amount_password, amount_status, amount_count, type_id, expry_date, add_date) ".
                    "VALUES ('$_POST[amount_list]', '$amount_number', '$amount_password', '$_POST[amount_status]', '$count[card_count]', '$id' ,'$_POST[expry_date]', '$add_time')";
            $db->query($sql);
            $amount_password = '';
    }
 
    //添加生卡记录
    $create_card_list = $_POST['amount_list'];
    $create_card_type = $_POST['amount_count'];
    $create_card_number = $amount_num;
    $create_amount_number = $amount_number_str;

    $create_card_sql = "INSERT INTO ".$ecs->table('create_card_log')."(amount_list, card_type, card_number, amount_number, create_date) ".
                    "VALUES ('$create_card_list', '$create_card_type', '$create_card_number', '$create_amount_number', '$add_time')";
    $db->query($create_card_sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'amount_card.php?act=group_add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'amount_card.php?act=list';

    admin_log($_POST['amount_number'],'add','amount_card');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}
/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /* 取商品数据 */
    $sql = "SELECT ac.amount_id,ac.amount_list,ac.amount_number,ac.amount_password,ac.amount_status,ac.type_id, ac.amount_count,ac.expry_date".
           " FROM " . $ecs->table('amount_card') . " AS ac ".
           " WHERE ac.amount_id='$_REQUEST[id]'";
    $cards = $db->GetRow($sql);
    $cards['expry_date'] = date('Y-m-d',strtotime($cards['expry_date']));


    /*初始化金额选项*/
    $sql = "SELECT cc.id,cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           "ORDER BY cc.id DESC";
    $res = $db->query($sql);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }

    $smarty->assign('card_count',  $arr);//类型集合

    $smarty->assign('cards',       $cards);
    $smarty->assign('ur_here',     $_LANG['amount_card_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_amount_card_list'], 'href' => 'amount_card.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('amount_card_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('amount_card');

    /*查找类型金额*/
    $id = $_POST['amount_count'];
    $sql_type = "SELECT cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           " WHERE cc.id='$id'";
    $count = $db->GetRow($sql_type);

    if ($exc->edit_amount_card("amount_status='$_POST[amount_status]', amount_count='$count[card_count]', type_id= '$id',expry_date='$_POST[expry_date]' ", $_POST['amount_id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'amount_card.php?act=list&' . list_link_postfix();

        admin_log($_POST['amount_id'], 'edit', 'amount_card');

        clear_cache_files();
        sys_msg($_LANG['articleedit_succeed'], 0, $link);
    }
    else
    {
        die($db->error());
    }
}
/*------------------------------------------------------ */
//-- 批量删除代金卡
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_remove')
{
    admin_priv('amount_card');
    if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_select_amount_card'], 1);
    }

    $count = 0;
    foreach ($_POST['checkboxes'] AS $key => $id)
    {
        if ($exc->drop_amount_card($id))
        {
            admin_log($id,'remove','amount_card');
            $count++;
        }
    }

    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'amount_card.php?act=list');
    sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
}

/*------------------------------------------------------ */
//-- 删除代金卡
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('amount_card');

    $id = intval($_GET['id']);

    if ($exc->drop_amount_card($id))
    {
        admin_log($id,'remove','article');
        clear_cache_files();
    }

    $url = 'amount_card.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/* 获得代金卡列表 */
function get_amount_cardlist()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'ac.amount_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if (!empty($filter['keyword']))
        {
            $where = " AND ac.amount_number LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }

        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('amount_card'). ' AS ac '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取文章数据 */
        $sql = 'SELECT *'.
               'FROM ' .$GLOBALS['ecs']->table('amount_card'). ' AS ac '.
               'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $arr = array();
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}
?>