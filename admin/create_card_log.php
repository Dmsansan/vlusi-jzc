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
//-- 批量删除和导出生卡记录
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'operate')
{
    admin_priv('create_card_log');
    if(isset($_POST['batch_remove'])){
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
                {
                    sys_msg($_LANG['no_select_create_card_log'], 1);
                }

                $count = 0;
                foreach ($_POST['checkboxes'] AS $key => $id)
                {
                    $arr = explode('-', $id);
                    if($arr[1] != 0){
                         $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'create_card_log.php?act=list');
                         sys_msg(sprintf($_LANG['exit_card_log'], $count), 0, $lnk);
                         exit;
                    }
                }

                foreach ($_POST['checkboxes'] AS $key => $id)
                {
                    $arr = explode('-', $id);
                    if ($exc->drop($arr[0]))
                    {
                        admin_log($id,'remove','create_card_log');
                        $count++;
                    }
                }

                $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'create_card_log.php?act=list');
                sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
            }elseif(isset($_POST['export'])){
        
            if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
                {
                    sys_msg($_LANG['no_select_create_card_log'], 1);
                }
            /* 赋值公用信息 */
            $smarty->assign('shop_name',    $_CFG['shop_name']);
            $smarty->assign('shop_url',     $ecs->url());
            $smarty->assign('shop_address', $_CFG['shop_address']);
            $smarty->assign('service_phone',$_CFG['service_phone']);
            $smarty->assign('print_time',   local_date($_CFG['time_format']));
            $smarty->assign('action_user',  $_SESSION['admin_name']);

            $html = '';
            $order_sn_list = explode(',', $_POST['order_id']);
            include_once (ROOT_PATH . 'include/vendor/PHPExcel.php');
            include_once (ROOT_PATH . 'include/vendor/PHPExcel/IOFactory.php');
            //require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
            //require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
            $PHPExcel = new PHPExcel();
        
            //设置excel属性基本信息
            $PHPExcel->getProperties()->setCreator("Neo")
            ->setLastModifiedBy("Neo")
            ->setTitle("111")
            ->setSubject("生卡记录列表")
            ->setDescription("")
            ->setKeywords("生卡记录列表")
            ->setCategory("");
            $PHPExcel->setActiveSheetIndex(0);
            $PHPExcel->getActiveSheet()->setTitle("生卡记录列表");
            //填入表头主标题
            $PHPExcel->getActiveSheet()->setCellValue('A1', $_CFG['shop_name'].'生卡记录列表');
            //填入表头副标题
            $PHPExcel->getActiveSheet()->setCellValue('A2', '操作者：'.$_SESSION['admin_name'].' 导出日期：'.date('Y-m-d',time()).' 地址：'.$_CFG['shop_address'].' 电话：'.$_CFG['service_phone']);
            //合并表头单元格
            $PHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $PHPExcel->getActiveSheet()->mergeCells('A2:F2');
        
            //设置表头行高
            $PHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
            $PHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(20);
            $PHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(30);
            
            //设置表头字体
            $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('黑体');
            $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $PHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('宋体');
            $PHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
            $PHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
 
            //设置单元格边框
            $styleArray = array(  
                'borders' => array(  
                    'allborders' => array(  
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的  
                        'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框  
                        //'color' => array('argb' => 'FFFF0000'),  
                    ),  
                ),  
            );
        
            //表格宽度
            $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);//编号
            $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);//生卡批次
            $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);//卡片类型
            $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);//生卡数量
            $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);//已使用卡片数量
            $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);//生卡时间

            //表格标题
            $PHPExcel->getActiveSheet()->setCellValue('A3', '编号');
            $PHPExcel->getActiveSheet()->setCellValue('B3', '生卡批次');
            $PHPExcel->getActiveSheet()->setCellValue('C3', '卡片类型');
            $PHPExcel->getActiveSheet()->setCellValue('D3', '生卡数量');
            $PHPExcel->getActiveSheet()->setCellValue('E3', '已使用卡片数量');
            $PHPExcel->getActiveSheet()->setCellValue('F3', '生卡时间');
 
            $hang = 4;
            $shuliang = 0;
            foreach ($_POST['checkboxes'] AS $key => $id)
                {
                    $arr = explode('-', $id);
                    //生卡记录ID
                    $arr_id[] = $arr[0];
                }
            $cards_list = get_create_card_loglist();
            $create_card_log = $cards_list['arr'];//所有生卡记录
        for($i =0; $i<count($create_card_log); $i++){
            if(in_array($create_card_log[$i]['id'],$arr_id)){
                $arr_log[$i] = $create_card_log[$i];
            }
        }
        for($j=0;$j<count($arr_log);$j++){
            $shuliang = $shuliang+1;
        }
        for ($kk = $hang; $kk < ($hang + $shuliang); $kk++) {
            //合并单元格
            $PHPExcel->getActiveSheet()->mergeCells('A' . $hang . ':A' . $kk);
            $PHPExcel->getActiveSheet()->mergeCells('B' . $hang . ':B' . $kk);
            $PHPExcel->getActiveSheet()->mergeCells('C' . $hang . ':C' . $kk);
            $PHPExcel->getActiveSheet()->mergeCells('D' . $hang . ':D' . $kk);
            $PHPExcel->getActiveSheet()->mergeCells('E' . $hang . ':E' . $kk);
            $PHPExcel->getActiveSheet()->mergeCells('F' . $hang . ':F' . $kk);
        }
          foreach ($arr_log as $key => $log){
                 // $PHPExcel->getActiveSheet()->setCellValue('A' . ($hang), $order['order_sn']." ");//加个空格，防止时间戳被转换
                $PHPExcel->getActiveSheet()->setCellValue('A' . ($hang), $log['id']);
                $PHPExcel->getActiveSheet()->setCellValue('B' . ($hang), $log['amount_list']);
                $PHPExcel->getActiveSheet()->setCellValue('C' . ($hang), $log['card_type']);
                $PHPExcel->getActiveSheet()->setCellValue('D' . ($hang), $log['card_number']);
                $PHPExcel->getActiveSheet()->setCellValue('E' . ($hang), $log['card_used']);
                $PHPExcel->getActiveSheet()->setCellValue('F' . ($hang), $log['create_date']." ");

                $hang = $hang+$shuliang;
            }
            //设置单元格边框
            $PHPExcel->getActiveSheet()->getStyle('A1:F'.$hang)->applyFromArray($styleArray);
            //设置自动换行
            $PHPExcel->getActiveSheet()->getStyle('A4:F'.$hang)->getAlignment()->setWrapText(true);
            //设置字体大小
            $PHPExcel->getActiveSheet()->getStyle('A4:F'.$hang)->getFont()->setSize(12);
            //垂直居中
            $PHPExcel->getActiveSheet()->getStyle('A1:F'.$hang)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            //水平居中
            $PHPExcel->getActiveSheet()->getStyle('A1:F'.$hang)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="create_card_log_'.date('Y-m-d').'.xls"');
            header('Cache-Control: max-age=0');
            $Writer = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
            $Writer->save("php://output");
            exit;
    }

}

/*------------------------------------------------------ */
//-- 删除生卡记录
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('create_card_log');

    $id = intval($_GET['id']);
    
    $card_used = intval($_GET['used_card']);//代金卡已经使用的数量

    if($card_used != 0){//这批卡已经被使用不能删除生卡记录
        $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'create_card_log.php?act=list');
        sys_msg(sprintf($_LANG['exit_card_log'], $count), 0, $lnk);
    }else{
        if ($exc->drop($id))
        {
            admin_log($id,'remove','article');
            clear_cache_files();
            $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'create_card_log.php?act=list');
            sys_msg(sprintf($_LANG['drop_card_log_success'], $count), 0, $lnk);
        }
    }
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
        //批量删除生卡记录判断数据
        $rows['batch_remove'] = $rows['id']."-".$rows['card_used'];
        $arr[] = $rows;
    }
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}
?>