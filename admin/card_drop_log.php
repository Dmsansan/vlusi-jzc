<?php

/**
 * 代金卡消费记录管理程序文件
*/

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/includes/init.php');

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("card_drop_log"), $db, 'id', 'id');
//$image = new cls_image();

/*------------------------------------------------------ */
//-- 代金卡消费记录列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('card_drop_log');

    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      $_LANG['18_card_drop_log']);
    
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $cards_list = get_card_drop_loglist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('card_drop_log_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('card_drop_log');

    $cards_list = get_card_drop_loglist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('card_drop_log_list.htm'), '',
        array('filter' => $cards_list['filter'], 'page_count' => $cards_list['page_count']));
}

/*------------------------------------------------------ */
//-- 用户充值代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('card_drop_log');

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

    $sql = "INSERT INTO ".$ecs->table('card_drop_log')."(amount_list, amount_number, amount_password, amount_status, amount_count, type_id, expry_date, add_date) ".
                    "VALUES ('$_POST[amount_list]', '$amount_number', '$amount_password', '$_POST[amount_status]', '$count[card_count]', '$id' ,'$_POST[expry_date]', '$add_time')";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'card_drop_log.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'card_drop_log.php?act=list';

    admin_log($_POST['amount_number'],'add','card_drop_log');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 批量删除生卡消费记录
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_remove')
{
    admin_priv('card_drop_log');
    if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_select_card_drop_log'], 1);
    }

    $count = 0;
    
    foreach ($_POST['checkboxes'] AS $key => $id)
    {
        $arr = explode('-', $id);
        if ($exc->drop($arr[0]))
        {
            admin_log($id,'remove','card_drop_log');
            $count++;
        }
    }

    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'card_drop_log.php?act=list');
    sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
}

/*------------------------------------------------------ */
//-- 删除代金卡消费记录
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('card_drop_log');

    $id = intval($_GET['id']);
    
     if ($exc->drop($id))
    {
        admin_log($id,'remove','article');
        clear_cache_files();
    }

    $url = 'card_drop_log.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
    
}
/* 获得生卡消费记录列表 */
function get_card_drop_loglist()
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
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'ac.id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if (!empty($filter['keyword']))
        {
            $where = " AND ac.card_number LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }

        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('card_drop_log'). ' AS ac '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取文章数据 */
        $sql = 'SELECT ac.id,ac.user_name,ac.card_number,ac.card_password,ac.card_count,ac.drop_date,ct.card_name as card_type '.
               'FROM ' .$GLOBALS['ecs']->table('card_drop_log'). ' AS ac '.
               'LEFT JOIN '.$GLOBALS['ecs']->table('card_type').' AS ct ON ac.card_type = ct.id '.
               'WHERE 1 ' .              
               $where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
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