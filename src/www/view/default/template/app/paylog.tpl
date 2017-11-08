<?php echo $page_header; ?>
<div class="body">
	<table class="list">
		<tr height="60px">
			<td style="font-size:20px;"><?php echo $card_info['card_iccid']; ?></td>
			<td align="right">
				<div class="iccid-num" style="background:<?php if ($card_info['unicom_stop']) { ?>#FEB353<?php } else { ?> #2DAAF2<?php } ?>;">
					<?php echo $arr_card_status[$card_info['unicom_stop']]; ?>
				</div>
			</td>
		</tr>
		<tr height="100px">
			<td colspan="2">
				<div style="background:#41C0BE;height:30px;border-radius:5px;">
					<div id="plan" style="background:#FEB353;width:<?php echo (round($card_info['used_total']/($card_info['used_total']+$card_info['max_unused'])*100,2)).'%'; ?>;height:30px;border-radius:5px 0 0 5px;"></div>
				</div>
				<div style="margin:10px auto;width:100%">
					<div style="float:left;color:#FEB353"><?php echo $text_used_total,modules_funs::gpgsFormat($card_info['used_total']); ?></div>
					<div style="float:right;color:#41C0BE"><?php echo $text_max_unused,modules_funs::gpgsFormat($card_info['max_unused']); ?></div>
				</div>
			</td>
		</tr>
		<tr height="60px">
			<td><?php echo $text_total_gprs,modules_funs::gpgsFormat($card_info['used_total']+$card_info['max_unused']); ?><p><span class="help"><?php echo $text_not_zero_out; ?></span></p></td>
			<td><a class="btn-dkGreen" href="/app/main/scan"><?php echo $button_cut; ?></a></td>
		</tr>
	</table>
	<div class="buttons" style="margin:20px 10px;">
		<div class="center"><a class="button btn-orange" href="/app/main/topup"><?php echo $button_online_pay; ?></a></div>
	</div>
</div>

<div id="tabs" class="htabs">
	<a href="/app/main/info"><?php echo $text_my_card; ?></a>
	<a class="selected" href="/app/main/paylog"><?php echo $text_card_paylog; ?></a>
</div><br />
<?php if(!empty($items)) { ?>
<div id="resDiv">
	<?php foreach($items as $item) { ?>
	<div style="border-radius:10px;background:#F7F7F7;margin:10px;">
		<table class="list">
			<tr>
				<td><?php echo $text_gprs_amount; ?></td>
				<td><?php echo modules_funs::gpgsFormat($item['gprs_amount']); ?></td>
			</tr>
			<tr>
				<td><?php echo $text_gprs_price; ?></td>
				<td><?php echo $this->currency->format($item['gprs_price']); ?></td>
			</tr>
			<tr>
				<td><?php echo $text_value_type; ?></td>
				<td><?php echo $arr_pay_type[$item['pay_method']]; ?></td>
			</tr>
			<tr>
				<td><?php echo $text_time_paid; ?></td>
				<td><?php echo $item['time_paid']; ?></td>
			</tr>
		</table>
	</div>
	<?php } ?>
</div>
<?php } else { ?>
<div class="noresult" style="padding-top:25%"><?php echo $error_no_pay; ?></div>
<?php } ?>

<script type="text/javascript">
var $page = 1, $canLoad = true;
var $loading = '<div id="loading" class="wait center"><?php echo $text_loading; ?><img src="/img/app/loading.gif" /></div>';
$(window).scroll(function ()
{
	var scrollTop = $(this).scrollTop();//滚动条距离顶部的高度
	var scrollHeight = $(document).height();//当前页面的总高度
	var windowHeight = $(this).height();//当前可视的页面高度

	if ($canLoad && scrollTop + windowHeight >= scrollHeight)
	{
		$canLoad = false;
		$('#resDiv').append($loading);
		$.post('/app/main/paylog', {'page': ++$page, 'ajax': 1}, function (result)
		{
			$('#loading').remove();
			if (result != '')
			{
				$canLoad = true;
				$('#resDiv').append(result);
			}
		});
	}
});
</script>
<?php echo $page_footer; ?>