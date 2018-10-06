<?php

/**
 * 代金卡类型管理程序文件
*/

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/includes/init.php');

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("card_type"), $db, 'id', 'id');
//$image = new cls_image();

/*------------------------------------------------------ */
//-- 代金卡类型列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('card_type');

    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      $_LANG['17_card_type_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['card_type_add'], 'href' => 'card_type.php?act=add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $cards_list = get_card_typelist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('card_type_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('card_type');

    $cards_list = get_card_typelist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('card_type_list.htm'), '',
        array('filter' => $cards_list['filter'], 'page_count' => $cards_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加代金卡类型
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('card_type');

    $smarty->assign('ur_here',     $_LANG['card_type_add']);
    $smarty->assign('action_link', array('text' => $_LANG['17_card_type_list'], 'href' => 'card_type.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('card_type_info.htm');
}

/*------------------------------------------------------ */
//-- 添加代金卡类型
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('card_type');

    /*检查是否重复*/
    $is_only = $exc->is_only('card_name', $_POST['card_name'],0, " card_name='$_POST[card_name]'");

    if (!$is_only)
    {
        sys_msg($_LANG['card_exist'], 1);
    }

    $sql = "INSERT INTO ".$ecs->table('card_type')."(card_name,card_count) ".
            "VALUES ('$_POST[card_name]', '$_POST[card_count]')";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'card_type.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'card_type.php?act=list';

    admin_log($_POST['card_name'],'add','card_type');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('card_type');

    /* 取类型数据 */
    $sql = "SELECT ac.id,ac.card_name,ac.card_count".
           " FROM " . $ecs->table('card_type') . " AS ac ".
           " WHERE ac.id='$_REQUEST[id]'";
    $cards = $db->GetRow($sql);

    $smarty->assign('cards',       $cards);
    $smarty->assign('ur_here',     $_LANG['card_type_add']);
    $smarty->assign('action_link', array('text' => $_LANG['17_card_type_list'], 'href' => 'card_type.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('card_type_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('card_type');
    if ($exc->edit("card_name='$_POST[card_name]', card_count='$_POST[card_count]'", $_POST['id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'card_type.php?act=list&' . list_link_postfix();

        admin_log($_POST['id'], 'edit', 'card_type');

        clear_cache_files();
        sys_msg($_LANG['articleedit_succeed'], 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 批量删除代金卡类型
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_remove')
{
    admin_priv('card_type');
    if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_select_card_type'], 1);
    }

    $count = 0;
    foreach ($_POST['checkboxes'] AS $key => $id)
    {
        if ($exc->drop($id))
        {
            admin_log($id,'remove','card_type');
            $count++;
        }
    }

    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'card_type.php?act=list');
    sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
}

/*------------------------------------------------------ */
//-- 删除代金卡类型
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('card_type');

    $id = intval($_GET['id']);

    if ($exc->drop($id))
    {
        admin_log($id,'remove','article');
        clear_cache_files();
    }

    $url = 'card_type.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}
/* 获得代金卡类型列表 */
function get_card_typelist()
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
            $where = " AND ac.card_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }

        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('card_type'). ' AS ac '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取文章数据 */
        $sql = 'SELECT *'.
               'FROM ' .$GLOBALS['ecs']->table('card_type'). ' AS ac '.
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
        //生卡数量
        $create_card_count_sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('amount_card'). ' AS ac '.
            'WHERE 1 AND ac.type_id = '.$rows['id'];
        $rows['create_card_count'] =  $GLOBALS['db']->getOne($create_card_count_sql);

        $used_card_count_sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('amount_card'). ' AS ac '.
            'WHERE 1 AND ac.type_id = '.$rows['id'].' AND ac.use_status=1';
        $rows['used_card_count'] =  $GLOBALS['db']->getOne($used_card_count_sql);

        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}
?>