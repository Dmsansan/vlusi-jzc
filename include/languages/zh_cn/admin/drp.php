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

$_LANG['on'] = '开启';
$_LANG['off'] = '关闭';
$_LANG['drp_profit'] = '分销利润设置';
$_LANG['cate_name'] = '产品分类';
$_LANG['cate_id'] = '编号';
$_LANG['profit1'] = '本店销售佣金';
$_LANG['profit2'] = '一级分店佣金';
$_LANG['profit3'] = '二级分店佣金';
$_LANG['submit'] = '提交';
$_LANG['apply_wxts'] = '申请分销商时的温馨提示';
$_LANG['config'] = '分销设置';

$_LANG['user_name'] = '下单用户';
$_LANG['parent_name'] = '所属分销商';

$_LANG['order_id'] = '订单号';
$_LANG['affiliate_separate'] = '分成';
$_LANG['affiliate_cancel'] = '取消';
$_LANG['affiliate_rollback'] = '撤销';
$_LANG['log_info'] = '操作信息';
$_LANG['edit_ok'] = '操作成功';
$_LANG['edit_fail'] = '操作失败';
$_LANG['separate_info'] = '订单号 %s, 分成:金钱 %s 积分 %s';
$_LANG['separate_info2'] = '用户ID %s ( %s ), 分成:金钱 %s 积分 %s';
$_LANG['sch_order'] = '搜索订单号';

$_LANG['sch_stats']['name'] = '操作状态';
$_LANG['sch_stats']['info'] = '按操作状态查找:';
$_LANG['sch_stats']['all'] = '全部';
$_LANG['sch_stats'][0] = '等待处理';
$_LANG['sch_stats'][1] = '已分成';
$_LANG['sch_stats'][2] = '取消分成';
$_LANG['sch_stats'][3] = '已撤销';
$_LANG['order_stats']['name'] = '订单状态';
$_LANG['order_stats'][0] = '未确认';
$_LANG['order_stats'][1] = '已确认';
$_LANG['order_stats'][2] = '已取消';
$_LANG['order_stats'][3] = '无效';
$_LANG['order_stats'][4] = '退货';
$_LANG['js_languages']['cancel_confirm'] = '您确定要取消分成吗？此操作不能撤销。';
$_LANG['js_languages']['rollback_confirm'] = '您确定要撤销此次分成吗？';
$_LANG['js_languages']['separate_confirm'] = '您确定要分成吗？';
$_LANG['loginfo'][0] = '用户id:';
$_LANG['loginfo'][1] = '现金:';
$_LANG['loginfo'][2] = '积分:';
$_LANG['loginfo']['cancel'] = '分成被管理员取消！';

$_LANG['separate_type'] = '分成类型';
$_LANG['separate_by'][0] = '推荐注册分成';
$_LANG['separate_by'][1] = '推荐订单分成';
$_LANG['separate_by'][-1] = '推荐注册分成';
$_LANG['separate_by'][-2] = '推荐订单分成';

$_LANG['show_affiliate_orders'] = '此列表显示此用户推荐的订单信息。';
$_LANG['back_note'] = '返回会员编辑页面';

$_LANG['user']['user_name'] = '用户名';
$_LANG['user']['real_name'] = '真实姓名';
$_LANG['user']['money'] = '分销佣金';
$_LANG['user']['sale_money'] = '销售佣金';
$_LANG['user']['sales_volume'] = '销售额';
$_LANG['user']['shop_name'] = '店铺名';
$_LANG['user']['shop_mobile'] = '手机号';
$_LANG['user']['shop_qq'] = 'QQ号';
$_LANG['user']['audit'] = '审核状态';
$_LANG['user']['audit_true'] = '已审核';
$_LANG['user']['audit_false'] = '未审核';
$_LANG['user']['open'] = '店铺状态';
$_LANG['user']['open_true'] = '开启';
$_LANG['user']['open_false'] = '关闭';
$_LANG['user']['create_time'] = '开店时间';
$_LANG['handle'] = '操作';
$_LANG['edit'] = '编辑';
$_LANG['change'] = '您确认要修改店铺状态吗？';
$_LANG['drp_user_edit'] = '店铺信息编辑？';

// 佣金提现管理
$_LANG['drp_log']  = '佣金管理';
$_LANG['user_surplus'] = '预付款';
$_LANG['surplus_id'] = '编号';
$_LANG['user_id'] = '会员名称';
$_LANG['surplus_amount'] = '金额';
$_LANG['add_date'] = '操作日期';
$_LANG['pay_mothed'] = '支付方式';
$_LANG['process_type'] = '类型';
$_LANG['confirm_date'] = '到款日期';
$_LANG['surplus_notic'] = '管理员备注';
$_LANG['surplus_desc'] = '描述';
$_LANG['surplus_type'] = '操作类型';

$_LANG['no_user'] = '匿名购买';

$_LANG['surplus_type_0'] = '充值';
$_LANG['surplus_type_1'] = '提现';
$_LANG['admin_user'] = '操作员';

$_LANG['status'] = '到款状态';
$_LANG['confirm'] = '已完成';
$_LANG['unconfirm'] = '未确认';
$_LANG['cancel'] = '取消';

$_LANG['please_select'] = '请选择...';
$_LANG['surplus_info'] = '会员金额信息';
$_LANG['check'] = '到款审核';

$_LANG['money_type'] = '币种';
$_LANG['surplus_add'] = '添加申请';
$_LANG['surplus_edit'] = '编辑申请';
$_LANG['attradd_succed'] = '您此次操作已成功！';
$_LANG['username_not_exist'] = '您输入的会员名称不存在！';
$_LANG['cancel_surplus'] = '您确定要取消这条记录吗?';
$_LANG['surplus_amount_error'] = '要提现的金额超过了此会员的帐户余额，此操作将不可进行！';
$_LANG['edit_surplus_notic'] = '现在的状态已经是 已完成，如果您要修改，请先将之设置为 未确认';
$_LANG['back_list'] = '返回充值和提现申请';
$_LANG['continue_add'] = '继续添加申请';
$_LANG['commission_Status'] = '佣金说明';
$_LANG['withdrawals_info'] = '提现信息';
$_LANG['shop_name'] = '店铺名称';
$_LANG['withdraw'] = '提现';
$_LANG['withdraw_mode'] = '提现方式';
$_LANG['withdraw_gold'] = '提现金额';
$_LANG['withdraw_ok'] = '提现成功';
$_LANG['Lack_of_funds'] = '资金不足，无法提现';
$_LANG['commission_fettle'] = '佣金状态';
$_LANG['The_extracted'] = '对不起，佣金已经提取过了';
$_LANG['delete_Success'] = '佣金删除成功';
$_LANG['delete_search'] = '佣金搜索';





/* JS语言项 */
$_LANG['js_languages']['user_id_empty'] = '会员名称不能为空！';
$_LANG['js_languages']['deposit_amount_empty'] = '请输入充值的金额！';
$_LANG['js_languages']['pay_code_empty'] = '请选择支付方式';
$_LANG['js_languages']['deposit_amount_error'] = '请按正确的格式输入充值的金额！';
$_LANG['js_languages']['deposit_type_empty'] = '请填写类型！';
$_LANG['js_languages']['deposit_notic_empty'] = '请填写管理员备注！';
$_LANG['js_languages']['deposit_desc_empty'] = '请填写会员描述！';



$_LANG['cs'][OS_UNCONFIRMED] = '待确认';
$_LANG['cs'][CS_AWAIT_PAY] = '待付款';
$_LANG['cs'][CS_AWAIT_SHIP] = '待发货';
$_LANG['cs'][CS_FINISHED] = '已完成';
$_LANG['cs'][PS_PAYING] = '付款中';
$_LANG['cs'][OS_CANCELED] = '取消';
$_LANG['cs'][OS_INVALID] = '无效';
$_LANG['cs'][OS_RETURNED] = '退货';
$_LANG['cs'][OS_SHIPPED_PART] = '部分发货';

/* 订单状态 */
$_LANG['os'][OS_UNCONFIRMED] = '未确认';
$_LANG['os'][OS_CONFIRMED] = '已确认';
$_LANG['os'][OS_CANCELED] = '<font color="red"> 取消</font>';
$_LANG['os'][OS_INVALID] = '<font color="red">无效</font>';
$_LANG['os'][OS_RETURNED] = '<font color="red">退货</font>';
$_LANG['os'][OS_SPLITED] = '已分单';
$_LANG['os'][OS_SPLITING_PART] = '部分分单';

$_LANG['ss'][SS_UNSHIPPED] = '未发货';
$_LANG['ss'][SS_PREPARING] = '配货中';
$_LANG['ss'][SS_SHIPPED] = '已发货';
$_LANG['ss'][SS_RECEIVED] = '收货确认';
$_LANG['ss'][SS_SHIPPED_PART] = '已发货(部分商品)';
$_LANG['ss'][SS_SHIPPED_ING] = '发货中';

$_LANG['ps'][PS_UNPAYED] = '未付款';
$_LANG['ps'][PS_PAYING] = '付款中';
$_LANG['ps'][PS_PAYED] = '已付款';
$_LANG['download'] = '导出';
$_LANG['start_time'] = '起始时间';
$_LANG['end_time'] = '结束时间';

?>