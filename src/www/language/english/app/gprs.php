<?php
//Heading
$_['heading_title'] = 'gprs card details';

//Text
$_['text_loading']     = 'Are being dealt with, please wait';
$_['text_pay_loading'] = 'Is under prepaid phone please wait';

$_['text_my_card']     = 'Gprs card info';
$_['text_card_paylog'] = 'Top up history';
$_['text_gprs_pay']    = 'Gprs card top up';

$_['text_gprs_amount'] = 'Gprs Pack';
$_['text_value_type']  = 'Top up Type';
$_['text_gprs_price']  = 'Gprs Price';
$_['text_time_paid']   = 'Top up Time';

$_['text_card_iccid']     = 'Card ICCID:';
$_['text_used_total']     = 'Used total:';
$_['text_pay_total']      = 'Top up total:';
$_['text_used_month']     = 'Month total:';
$_['text_max_unused']     = 'Available total:';
$_['text_last_time_paid'] = 'Last pay time:';
$_['text_card_status']    = 'Card status:';
$_['text_time_expire']    = 'Expiration time:';

$_['text_card_stop']   = 'Your gprs card has been stopped, please prepaid phone!';
$_['text_pay_body']    = 'Gprs top up %sM';
$_['text_pay_success'] = 'Top up success';
$_['text_live_month']  = '%s months';

$_['text_iccid']   = 'ICCID';
$_['text_gprs']    = 'Gprs';
$_['text_expire']  = 'Validity';
$_['text_price']   = 'Price';
$_['text_no_pack'] = 'Gprs pack error, please choose again';
$_['text_no_pay']  = 'System is busy, please again';

//Button
$_['button_online_pay'] = 'Online Top Up';
$_['button_scan_pay']   = 'Top up and qrcode';
$_['button_scan']       = 'Scan';

//Error
$_['error_no_card']    = 'No have card information';
$_['error_no_pay']     = 'No have card top up history';
$_['error_no_pack']    = 'No have package';
$_['error_iccid']      = 'Please fill out the ICCID';
$_['error_order']      = 'The ICCID does not exist';
$_['error_pay']        = 'Top up failure';
$_['error_no_czk']     = 'Top up CARDS error';
$_['error_czk_expire'] = 'Top up card has expired';
$_['error_czk_used']   = 'Top up CARDS have been used';

$_['arr_pay_type'] = array(
	'1' => 'wxpay',
	'2' => 'alipay',
	'3' => 'topup card',
	'4' => 'bank transfer',
);

$_['arr_card_status'] = array(
	'0' => 'Using',
	'1' => 'Pause'
);
?>