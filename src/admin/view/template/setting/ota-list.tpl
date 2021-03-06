<?php echo $page_header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a
				href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/payment.png" /> <?php echo $heading_title; ?></h1>
			<div class="buttons" id="ctrl-div">
				<a href="/setting/ota" class="button btn-darkblue"><?php echo $button_back; ?></a>
			</div>
		</div>
		<div class="content">
			<table class="list">
				<thead>
					<tr>
						<td><?php echo $text_version_num; ?></td>
						<td><?php echo $text_version_depict; ?></td>
						<td style="width:80px"><?php echo $text_force_update; ?></td>
						<td><?php echo $text_issue_time; ?></td>
						<td><?php echo $text_add_time; ?></td>
						<td><?php echo $text_manage; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($items) { ?>
					<?php foreach ($items as $item) { ?>
					<tr>
						<td><a href="<?php echo $item['pack_url']; ?>"><?php echo $item['soft_code']; ?></a></td>
						<td><pre style="max-height:50px;padding:0;margin:0;width:500px"><?php echo $item['soft_desc']; ?></pre></td>
						<td><?php echo $item['is_forced'] ? '<?php echo $text_yes; ?>' : '<?php echo $text_no; ?>'; ?></td>
						<td><?php echo $item['time_publish']; ?></td>
						<td><?php echo $item['time_added']; ?></td>
						<td>
							<?php if($item['is_valid']){ ?>
							<button class="button btn-red" onclick="otaValid(this,<?php echo $item['ota_id']; ?>,0)"><?php echo $text_set_void; ?></button>
							<?php }else{ ?>
							<button class="button btn-green" onclick="otaValid(this,<?php echo $item['ota_id']; ?>,1)"><?php echo $text_set_valid; ?></button>
							<?php }?>
						</td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function otaValid(obj, ota_id, is_valid)
	{
		if (is_valid)
		{
			var confirm_text = '确认设置该版本有效？';
			var onclick_fun = "otaValid(this," + ota_id + ",0)";
		}
		else
		{
			var confirm_text = '确认设置该版本无效？';
			var onclick_fun = "otaValid(this," + ota_id + ",1)";
		}
		if (!confirm(confirm_text))
		{
			return;
		}
		$.get('/setting/ota/otaValid', {'ota_id': ota_id, 'is_valid': is_valid}, function (res)
		{
			if (res != 'ok')
			{
				alert(res)
			}
			else
			{
				if (is_valid)
				{
					$(obj).text('设置无效');
					$(obj).removeClass('btn-green');
					$(obj).addClass('btn-red');
				}
				else
				{
					$(obj).text('设置有效');
					$(obj).removeClass('btn-red');
					$(obj).addClass('btn-green');
				}
				$(obj).attr('onclick', onclick_fun);
			}
		});
	}
</script>
<?php echo $page_footer; ?>