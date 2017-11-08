<?php echo $page_header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/setting.png" /> <?php echo $heading_title; ?></h1>
			<div class="buttons" id="ctrl-div">
				<?php if ($mpermission) { ?><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><?php } ?>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_general; ?></a>
				<a href="#tab-store"><?php echo $tab_store; ?></a>
				<a href="#tab-local"><?php echo $tab_local; ?></a>
				<a href="#tab-server"><?php echo $tab_server; ?></a>
			</div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_domain; ?></td>
							<td><input type="text" name="config_domain" value="<?php echo $config_domain; ?>" size="40" />
								<?php if ($error_domain) { ?>
								<span class="error"><?php echo $error_domain; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_url; ?></td>
							<td><input type="text" name="config_url" value="<?php echo $config_url; ?>" size="40" />
								<?php if ($error_url) { ?>
								<span class="error"><?php echo $error_url; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_ssl; ?></td>
							<td><input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" size="40" /></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_name; ?></td>
							<td><input type="text" name="config_name" value="<?php echo $config_name; ?>" size="40" />
								<?php if ($error_name) { ?>
								<span class="error"><?php echo $error_name; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_owner; ?></td>
							<td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" size="40" />
								<?php if ($error_owner) { ?>
								<span class="error"><?php echo $error_owner; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_address; ?></td>
							<td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
								<?php if ($error_address) { ?>
								<span class="error"><?php echo $error_address; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_email; ?></td>
							<td><input type="text" name="config_email" value="<?php echo $config_email; ?>" size="40" />
								<?php if ($error_email) { ?>
								<span class="error"><?php echo $error_email; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
							<td><input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
								<?php if ($error_telephone) { ?>
								<span class="error"><?php echo $error_telephone; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_fax; ?></td>
							<td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
						</tr>
					</table>
				</div>
				<div id="tab-store">
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_title; ?></td>
							<td><input type="text" name="config_title" value="<?php echo $config_title; ?>" />
								<?php if ($error_title) { ?>
								<span class="error"><?php echo $error_title; ?></span>
								<?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_meta_description; ?></td>
							<td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea></td>
						</tr>
						<tr>
							<td><?php echo $entry_template; ?></td>
							<td><select name="config_template" onchange="$('#template').load('/setting/store/template?template=' + encodeURIComponent(this.value));">
									<?php foreach ($templates as $template) { ?>
									<?php if ($template == $config_template) { ?>
									<option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
									<?php } else { ?>
									<option value="<?php echo $template; ?>"><?php echo $template; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
						</tr>
						<tr>
							<td></td>
							<td id="template"></td>
						</tr>
					</table>
				</div>
				<div id="tab-local">
					<table class="form">
						<tr>
							<td><?php echo $entry_language; ?></td>
							<td><select name="config_language">
									<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_language) { ?>
									<option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_currency; ?></td>
							<td><select name="config_currency">
									<?php foreach ($currencies as $currency) { ?>
									<?php if ($currency['code'] == $config_currency) { ?>
									<option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
						</tr>
					</table>
				</div>
			
				<div id="tab-server">
					<table class="form">
						<tr>
							<td><?php echo $entry_use_ssl; ?></td>
							<td><?php if ($config_use_ssl) { ?>
								<input type="radio" name="config_use_ssl" value="1" checked="checked" />
								<?php echo $text_yes; ?>
								<input type="radio" name="config_use_ssl" value="0" />
								<?php echo $text_no; ?>
								<?php } else { ?>
								<input type="radio" name="config_use_ssl" value="1" />
								<?php echo $text_yes; ?>
								<input type="radio" name="config_use_ssl" value="0" checked="checked" />
								<?php echo $text_no; ?>
								<?php } ?></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
$('#template').load('/setting/store/template?template=' + encodeURIComponent($('select[name=\'config_template\']').val()));
//--></script>

<script type="text/javascript" src="/js/address.js"></script>
<script type="text/javascript"><!--
var $text_select = '<?php echo $text_select; ?>';
var $text_none = '<?php echo $text_none; ?>';

$('select[name=\'config_country_id\']').bind('change', function(){loadCounty(this.value, '<?php echo $config_zone_id; ?>', 'config_country_id', 'config_zone_id', 'city', 'postcode-required')});
$('select[name=\'config_country_id\']').trigger('change');

$('#tabs a').easyTabs();
//--></script>
<?php echo $page_footer; ?>