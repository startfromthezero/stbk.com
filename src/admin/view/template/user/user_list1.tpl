
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($warning) { ?><div class="warning"><?php echo $warning; ?></div><?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/user.png" /> <?php echo $heading_title; ?></h1>
			<div class="buttons" id="ctrl-div">
				<?php if ($mpermission && $this->user->getGroupId() <= 1) { ?>
				<a href="<?php echo $insert; ?>" class="button btn-green"><?php echo $button_insert; ?></a>
				<a onclick="$('form').submit();" class="button btn-yellow"><?php echo $button_delete; ?></a>
				<?php } ?>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="list">
					<thead>
						<tr>
							<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
							<td class="left"><?php if ($sort == 'username') { ?>
								<a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?>
									<i class="<?php echo strtolower($order); ?>"></i>
								</a>
								<?php } else { ?>
								<a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?>
									<i class="harrow"></i>
								</a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'user_group_id') { ?>
								<a href="<?php echo $sort_group; ?>"><?php echo $column_group; ?>
									<i class="<?php echo strtolower($order); ?>"></i>
								</a>
								<?php } else { ?>
								<a href="<?php echo $sort_group; ?>"><?php echo $column_group; ?>
									<i class="harrow"></i>
								</a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'org_id') { ?>
								<a href="<?php echo $sort_org; ?>"><?php echo $column_org; ?>
									<i class="<?php echo strtolower($order); ?>"></i>
								</a>
								<?php } else { ?>
								<a href="<?php echo $sort_org; ?>"><?php echo $column_org; ?>
									<i class="harrow"></i>
								</a>
								<?php } ?></td>
							<td class="left"><?php if ($sort == 'date_added') { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?>
									<i class="<?php echo strtolower($order); ?>"></i>
								</a>
								<?php } else { ?>
								<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?>
									<i class="harrow"></i>
								</a>
								<?php } ?></td>
							<td class="center"><?php echo $column_last_ip; ?></td>
							<td class="center">
								<?php if ($sort == 'status') { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $text_manage; ?>
									<i class="<?php echo strtolower($order); ?>"></i>
								</a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $text_manage; ?>
									<i class="harrow"></i>
								</a>
								<?php } ?>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($users) { ?>
						<?php foreach ($users as $user) { ?>
						<tr>
							<td style="text-align: center;"><?php if ($user['selected']) { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
								<?php } else { ?>
								<input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
								<?php } ?>
							</td>
							<td class="left"><?php echo $user['username']; ?></td>
							<td class="left"><?php echo isset($groups[$user['user_group_id']]) ? $groups[$user['user_group_id']] : ''; ?></td>
							<td class="left"><?php echo isset($orgs[$user['org_id']]) ? $orgs[$user['org_id']] : ''; ?></td>
							<td class="left"><?php echo $user['date_added']; ?></td>
							<td class="center"><?php echo $user['last_ip']; ?></td>
							<td class="center" width="15%">
								<?php foreach ($user['action'] as $action) { ?>
								<a class="button btn-blue" href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
								<?php } ?>
								<?php if($this->user->getGroupId() <= 1) { if($user['status']) { ?>
								<a class="button btn-red" onclick="userStop(this,<?php echo $user['user_id']; ?>,0)"><?php echo $text_disabled; ?></a>
								<?php } else { ?>
								<a class="button btn-green" onclick="userStop(this,<?php echo $user['user_id']; ?>,1)"><?php echo $text_enabled; ?></a>
								<?php } } ?>
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
			</form>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript">
function userStop(obj, user_id, status)
{
	if (status)
	{
		var confirm_text = '<?php echo $text_confirm_start; ?>';
		var onclick_fun = "userStop(this," + user_id + ",0)";
	}
	else
	{
		var confirm_text = '<?php echo $text_confirm_stop; ?>？';
		var onclick_fun = "userStop(this," + user_id + ",1)";
	}

	if (!confirm(confirm_text))
	{
		return;
	}

	$.post('/user/user/stop', {'user_id': user_id, 'status': status}, function (res)
	{
		if (res != 'ok')
		{
			alert(res);
		}
		else
		{
			if (status)
			{
				$(obj).text('<?php echo $text_disabled; ?>');
				$(obj).removeClass('btn-green');
				$(obj).addClass('btn-red');
			}
			else
			{
				$(obj).text('<?php echo $text_enabled; ?>');
				$(obj).removeClass('btn-red');
				$(obj).addClass('btn-green');
			}
			$(obj).attr('onclick', onclick_fun);
		}
	});
}
</script>
<?php echo $page_footer; ?>