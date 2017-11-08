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