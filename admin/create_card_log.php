<?php

/**
 * 生卡记录管理程序文件
*/

define('IN_ECTOUCH', true);

require(dirname(__FILE__) . '/includes/init.php');

/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("create_card_log"), $db, 'id', 'id');
//$image = new cls_image();

/*------------------------------------------------------ */
//-- 代金卡列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('create_card_log');

    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      $_LANG['18_create_card_log']);
    
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $cards_list = get_create_card_loglist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('create_card_log_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('create_card_log');

    $cards_list = get_create_card_loglist();

    $smarty->assign('cards_list',    $cards_list['arr']);
    $smarty->assign('filter',        $cards_list['filter']);
    $smarty->assign('record_count',  $cards_list['record_count']);
    $smarty->assign('page_count',    $cards_list['page_count']);

    $sort_flag  = sort_flag($cards_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('create_card_log_list.htm'), '',
        array('filter' => $cards_list['filter'], 'page_count' => $cards_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('create_card_log');

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
    $smarty->assign('ur_here',     $_LANG['create_card_log_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_create_card_log_list'], 'href' => 'create_card_log.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('create_card_log_info.htm');
}

/*------------------------------------------------------ */
//-- 批量添加代金卡
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'group_add')
{
    /* 权限判断 */
    admin_priv('create_card_log');

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
    $smarty->assign('ur_here',     $_LANG['create_card_log_group_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_create_card_log_list'], 'href' => 'create_card_log.php?act=list'));
    $smarty->assign('form_action', 'group_insert');

    assign_query_info();
    $smarty->display('create_card_log_group_add.htm');
}

/*------------------------------------------------------ */
//-- 添加商品
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('create_card_log');

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

    $sql = "INSERT INTO ".$ecs->table('create_card_log')."(amount_list, amount_number, amount_password, amount_status, amount_count, type_id, expry_date, add_date) ".
                    "VALUES ('$_POST[amount_list]', '$amount_number', '$amount_password', '$_POST[amount_status]', '$count[card_count]', '$id' ,'$_POST[expry_date]', '$add_time')";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'create_card_log.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'create_card_log.php?act=list';

    admin_log($_POST['amount_number'],'add','create_card_log');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 批量添加商品
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'group_insert')
{
    /* 权限判断 */
    admin_priv('create_card_log');
    $amount_num = $_POST['amount_num'];//生卡数量
    for($j = 0; $j<$amount_num; $j++){
           //自动生成代金卡号
            $amount_number =  create_amount_number();
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

            $sql = "INSERT INTO ".$ecs->table('create_card_log')."(amount_list, amount_number, amount_password, amount_status, amount_count, type_id, expry_date, add_date) ".
                    "VALUES ('$_POST[amount_list]', '$amount_number', '$amount_password', '$_POST[amount_status]', '$count[card_count]', '$id' ,'$_POST[expry_date]', '$add_time')";
            $db->query($sql);
            $amount_password = '';
    }
 

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'create_card_log.php?act=group_add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'create_card_log.php?act=list';

    admin_log($_POST['amount_number'],'add','create_card_log');

    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}
/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('create_card_log');

    /* 取商品数据 */
    $sql = "SELECT ac.amount_id,ac.amount_list,ac.amount_number,ac.amount_password,ac.amount_status,ac.type_id, ac.amount_count,ac.expry_date".
           " FROM " . $ecs->table('create_card_log') . " AS ac ".
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
    $smarty->assign('ur_here',     $_LANG['create_card_log_add']);
    $smarty->assign('action_link', array('text' => $_LANG['16_create_card_log_list'], 'href' => 'create_card_log.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');

    assign_query_info();
    $smarty->display('create_card_log_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($_REQUEST['act'] =='update')
{
    /* 权限判断 */
    admin_priv('create_card_log');

    /*查找类型金额*/
    $id = $_POST['amount_count'];
    $sql_type = "SELECT cc.card_count".
           " FROM " . $ecs->table('card_type') . " AS cc ".
           " WHERE cc.id='$id'";
    $count = $db->GetRow($sql_type);

    if ($exc->edit_create_card_log("amount_status='$_POST[amount_status]', amount_count='$count[card_count]', type_id= '$id',expry_date='$_POST[expry_date]' ", $_POST['amount_id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'create_card_log.php?act=list&' . list_link_postfix();

        admin_log($_POST['amount_id'], 'edit', 'create_card_log');

        clear_cache_files();
        sys_msg($_LANG['articleedit_succeed'], 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑使用积分值
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_exchange_integral')
{
    check_authz_json('exchange_goods');

    $id                = intval($_POST['id']);
    $exchange_integral = floatval($_POST['val']);

    /* 检查文章标题是否重复 */
    if ($exchange_integral < 0 || $exchange_integral == 0 && $_POST['val'] != "$goods_price")
    {
        make_json_error($_LANG['exchange_integral_invalid']);
    }
    else
    {
        if ($exc->edit("exchange_integral = '$exchange_integral'", $id))
        {
            clear_cache_files();
            admin_log($id, 'edit', 'exchange_goods');
            make_json_result(stripslashes($exchange_integral));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否兑换
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_exchange')
{
    check_authz_json('exchange_goods');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_exchange = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 切换是否兑换
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_hot')
{
    check_authz_json('exchange_goods');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_hot = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 批量删除商品
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_remove')
{
    admin_priv('create_card_log');
    if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_select_create_card_log'], 1);
    }

    $count = 0;
    foreach ($_POST['checkboxes'] AS $key => $id)
    {
        if ($exc->drop_create_card_log($id))
        {
            admin_log($id,'remove','create_card_log');
            $count++;
        }
    }

    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'create_card_log.php?act=list');
    sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
}

/*------------------------------------------------------ */
//-- 删除代金卡
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('create_card_log');

    $id = intval($_GET['id']);

    if ($exc->drop_create_card_log($id))
    {
        admin_log($id,'remove','article');
        clear_cache_files();
    }

    $url = 'create_card_log.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 搜索商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_goods')
{
    // include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $filters = $json->decode($_GET['JSON']);

    $arr = get_goods_list($filters);

    make_json_result($arr);
}

/* 获得生卡记录列表 */
function get_create_card_loglist()
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
            $where = " AND ac.amount_list LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }

        /* 文章总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('create_card_log'). ' AS ac '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取文章数据 */
        $sql = 'SELECT ac.id,ac.amount_list,ac.card_number,ac.amount_number,ac.create_date,ct.card_name as card_type '.
               'FROM ' .$GLOBALS['ecs']->table('create_card_log'). ' AS ac '.
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
        //计算已经使用的生卡数量
        $amount_str = trim($rows['amount_number']);//生卡记录生成的代金卡卡号

        $amount_str = "'".$amount_str."'";
        $amount_str = str_replace(",","','",$amount_str);

        $num_sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('amount_card'). ' AS ac '.
               'WHERE 1 AND ac.amount_number IN ('.$amount_str.
               ') AND ac.use_status=1';
        $rows['card_used'] = $GLOBALS['db']->getOne($num_sql);
        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}
?>