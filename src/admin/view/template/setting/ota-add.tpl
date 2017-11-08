<?php echo $page_header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/payment.png" /> <?php echo $heading_title; ?></h1>
			<div class="buttons" id="ctrl-div">
				<?php if ($mpermission) { ?><a onclick="$('#form').submit();" class="button btn-green"><?php echo $button_save; ?></a><?php } ?>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button btn-darkblue"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<table class="form">
					<tr>
						<td><span class="required">*</span><?php echo $entry_org_name; ?></td>
						<td>
						<select name="org_id">
							<?php foreach ($users as $user) { ?>
							<option value="<?php echo $user['org_id']; ?>"<?php if ($user['org_id'] == $org_id) { ?> selected="selected"<?php } ?>><?php echo $user['firstname']; ?></option>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_org_num; ?></td>
						<td><input type="text" name="org_code" value="<?php echo $org_code; ?>" /><?php if ($error_org_code) { ?><span class="error"><?php echo $error_org_code; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_hard_code; ?></td>
						<td><input type="text" name="hard_code" value="<?php echo $hard_code; ?>" /><?php if ($error_hard_code) { ?><span class="error"><?php echo $error_hard_code; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_soft_code; ?></td>
						<td><input type="text" name="soft_code" value="<?php echo $soft_code; ?>" /><?php if ($error_soft_code) { ?><span class="error"><?php echo $error_soft_code; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_fit_versions; ?><span class="help"><?php echo $note_what_versions; ?></span></td>
						<td><input type="text" name="soft_for" value="<?php echo $soft_for; ?>" /><?php if ($error_soft_for) { ?><span class="error"><?php echo $error_soft_for; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_version_depict; ?></td>
						<td><textarea name="soft_desc" rows="5" cols="90"><?php echo $soft_desc; ?></textarea><?php if ($error_soft_desc) { ?><span class="error"><?php echo $error_soft_desc; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><?php echo $entry_mandatory_upgrade; ?></td>
						<td><input type="checkbox" name="is_forced" value="1" <?php echo $is_forced == 1 ? " checked" : ''; ?> /><?php echo $entry_must_upgrade; ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_release_time; ?></td>
						<td><input type="text" name="time_publish" value="<?php echo $time_publish; ?>" id="timePublish" readonly /></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_package_url; ?></td>
						<td><input type="text" name="pack_url" value="<?php echo $pack_url; ?>" /><?php if ($error_pack_url) { ?><span class="error"><?php echo $error_pack_url; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_package_md5; ?></td>
						<td><input type="text" name="pack_md5" value="<?php echo $pack_md5; ?>" /><?php if ($error_pack_md5) { ?><span class="error"><?php echo $error_pack_md5; ?></span><?php } ?></td>
					</tr>
					<tr>
						<td><span class="required">*</span><?php echo $entry_package_size; ?></td>
						<td><input type="text" name="pack_size" value="<?php echo $pack_size; ?>" /><?php if ($error_pack_size) { ?><span class="error"><?php echo $error_pack_size; ?></span><?php } ?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$('#timePublish').datepicker({dateFormat: 'yy-mm-dd'});
</script>
<?php echo $page_footer; ?>