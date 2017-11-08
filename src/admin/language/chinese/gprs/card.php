<?php
// Heading
$_['heading_title'] = '流量卡管理';

// Text
$_['text_gprs_halt']     = '停号流量卡统计';
$_['text_card_iccid']    = 'ICCID卡号';
$_['text_card_sn']       = '上网卡号';
$_['text_bind_device']   = '绑定设备';
$_['text_gprs_month']    = '赠送流量';
$_['text_used_total']    = '累计使用流量';
$_['text_used_month']    = '当月使用流量';
$_['text_pay_total']     = '累记充值流量';
$_['text_pay_unused']    = '可用充值流量';
$_['text_max_unused']    = '剩余流量';
$_['text_see_details']   = '详情';
$_['text_action']        = '管理';
$_['text_operate']       = '操作';
$_['button_import']      = '导入';
$_['button_export']      = '导出';
$_['button_chart']       = '图表';
$_['text_card_sn']       = 'SN号：';
$_['text_card_name']     = '别名：';
$_['text_owner_name']    = '卡主姓名：';
$_['text_owner_gender']  = '卡主性别：';
$_['text_owner_cdi']     = '卡主身份证：';
$_['text_time_active']   = '添加时间：';
$_['text_time_paid']     = '上次充值时间：';
$_['text_time_stop']     = '上次停号时间：';
$_['text_more_detail']   = '详细信息';
$_['text_card_chart']    = '机构流量ICCID卡统计图表';
$_['text_confirm_start'] = '确认开启该流量卡？';
$_['text_confirm_stop']  = '确认停止该流量卡？';

$_['text_gprs_card_used'] = '流量卡使用统计';
$_['text_subtotal']       = '小计';
$_['text_total']          = '总计';
$_['text_org_name']       = '机构名称';
$_['text_sell_num']       = '售卡数量';
$_['text_not_act_num']    = '未激活数';
$_['text_not_act_rate']   = '未激活率';
$_['text_act_num']        = '已激活数';
$_['text_act_rate']       = '已激活率';
$_['text_gprs_total']     = '流量总计';
$_['text_gprs_used']      = '使用流量';
$_['text_used_rate']      = '使用流量率';
$_['text_gprs_activat']   = '流量卡激活统计';
$_['text_activat_case']   = '激活情况';
$_['text_used_case']      = '使用情况';

$_['text_gprs_unicom_stat'] = '联通流量卡统计';
$_['text_unicom_total']     = '使用总流量';
$_['text_unicom_month']     = '当月使用流量';

$_['text_my_pay']           = '充值详情列表';
$_['text_org_topup_detail'] = '机构充值明细';
$_['text_gprs_amount']      = '流量值';
$_['text_gprs_price']       = '价格';
$_['text_pay_memo']         = '备注';
$_['text_pay_method']       = '付款方式';
$_['text_transfer_id']      = '支付流水号';
$_['text_is_paid']          = '支付状态';
$_['text_time_paid']        = '付款时间';
$_['text_time_added']       = '充值时间';
$_['text_time_expire']      = '过期时间';
$_['text_time_last']        = '最后更新时间';
$_['text_time_out']         = '（已过期）';

$_['text_report']         = '充值总额统计';
$_['text_month_report']   = '充值月度统计';
$_['text_user_firstname'] = '机构名称';
$_['text_customer_count'] = '用户总数';
$_['text_pay_count']      = '充值次数';
$_['text_gprs_count']     = '充值总流量';
$_['text_money_count']    = '充值总金额';
$_['text_nopaid_total']   = '未付款金额';
$_['text_paid_count']     = '已付款次数';
$_['text_nopaid_count']   = '未付款次数';
$_['text_total']          = '总计';
$_['text_mdate']          = '月份';

$_['text_card_abnormal']     = '流量卡异常统计';
$_['text_difference']        = '流量差异值';
$_['text_yunovo_used_total'] = '平台使用总流量';
$_['text_unicom_used_total'] = '联通使用总流量';

// Error
$_['error_card']            = '修改失败，请重试！';
$_['error_export_selected'] = '请选择流量卡！';
$_['error_export_empty']    = '导出内容不能为空！';

$_['arr_pay_type'] = array(
	'1' => '微信支付',
	'2' => '支付宝',
	'3' => '充值卡',
	'4' => '银行转账',
);

$_['arr_date_status'] = array(
	'1' => '正常使用',
	'2' => '已过期的'
);

$_['arr_pay_status'] = array(
	'0' => '未付款',
	'1' => '已付款'
);

$_['arr_difference'] = array(
	'10'   => '>= 10M',
	'25'   => '>= 25M',
	'50'   => '>= 50M',
	'100'  => '>= 100M',
	'150'  => '>= 150M',
	'200'  => '>= 200M',
	'500'  => '>= 500M',
	'750'  => '>= 750M',
	'1024' => '>= 1G',
	'2048' => '>= 2G'
);

$_['arr_card_status'] = array(
	'0' => '正常使用',
	'1' => '停机保号'
);

$_['arr_color'] = array(
	'#C1232B',
	'#B5C334',
	'#FCCE10',
	'#E87C25',
	'#27727B',
	'#FE8463',
	'#9BCA63',
	'#FAD860',
	'#F3A43B',
	'#60C0DD',
	'#D7504B',
	'#C6E579',
	'#F4E001',
	'#F0805A',
	'#26C0C0',
	'#ff7f50',
	'#87cefa',
	'#da70d6',
	'#32cd32',
	'#6495ed',
	'#ff69b4',
	'#ba55d3',
	'#cd5c5c',
	'#ffa500',
	'#40e0d0',
	'#d575d0',
	'#e4fefe',
	'#337f7c',
	'#e5b827',
	'#7cb9e1',
	'#7987ba',
	'#575add'
);
?>