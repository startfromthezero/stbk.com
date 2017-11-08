<!DOCTYPE html>
<html dir="ltr" lang="cn">
<head>
	<meta charset="UTF-8" />
	<title>权限设置</title>
	<base href="http://stbk.admin.com" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="stylesheet" type="text/css" href="http://stbk.admin.com/view/style.css" />
	<link rel="stylesheet" type="text/css" href="http://stbk.admin.com/view/autofit_admin_mobile.css" />
	<link rel="stylesheet" type="text/css" href="http://stbk.admin.com/js/css/gallery.css" />
	<link type="text/css" href="http://stbk.admin.com/js/css/jquery-ui.min.css" rel="stylesheet" />
	<script type="text/javascript" src="http://stbk.admin.com/js/jquery.min.js"></script>
	<script type="text/javascript" src="http://stbk.admin.com/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://stbk.admin.com/js/gallery.js"></script>
	<script type="text/javascript" src="http://stbk.admin.com/js/jquery.freezeheader.js"></script>
	<!--[if lt IE 9]>
	<script src="/js/html5shiv.js"></script>
	<script src="/js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<link rel="stylesheet" type="text/css" href="/view/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/view/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/view/nanoscroller.css" />
<link rel="stylesheet" type="text/css" href="/view/theme_styles.css" />
<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
<link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all" />
<link rel="stylesheet" href="/public/css/user.css" media="all" />
<fieldset class="layui-elem-field">
	<legend>权限管理</legend>
	<div class="layui-field-box">
		<div id="content">
			<div class="box">
				<div class="heading">
					<h1><img src="/view/image/user-group.png" /> <?php echo $heading_title; ?></h1>
					<div class="buttons" id="ctrl-div">
						<?php if ($mpermission) { ?><a onclick="$('#form').submit();"
													   class="button btn-green"><?php echo $button_save; ?></a><?php } ?>
						<a onclick="location = '<?php echo $cancel; ?>';"
						   class="button btn-darkblue"><?php echo $button_cancel; ?></a>
					</div>
				</div>
				<div class="content">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
						<table class="form">
							<tr>
								<td><span class="required">*</span> <?php echo $entry_name; ?></td>
								<td><input type="text" name="name" value="<?php echo $name; ?>" />
									<?php if ($error_name) { ?>
									<span class="error"><?php echo $error_name; ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_access; ?></td>
								<td>
									<div class="scrollbox">
										<?php foreach ($permissions as $permission) { ?>
										<div>
											<?php if (in_array($permission, $access)) { ?>
											<input type="checkbox" name="permission[access][]"
												   value="<?php echo $permission; ?>"
												   checked="checked" /> <?php echo isset($permissions_data[$permission]) ? $permissions_data[$permission] : $permission; ?>
											<?php } else { ?>
											<input type="checkbox" name="permission[access][]"
												   value="<?php echo $permission; ?>" /> <?php echo isset($permissions_data[$permission]) ? $permissions_data[$permission] : $permission; ?>
											<?php } ?>
										</div>
										<?php } ?>
									</div>
									<div style="text-align:right;margin:8px 0 0;">
										<a onclick="$(this).parent().parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>
										/
										<a onclick="$(this).parent().parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
									</div>
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_modify; ?></td>
								<td>
									<div class="scrollbox">
										<?php foreach ($permissions as $permission) { ?>
										<div>
											<?php if (in_array($permission, $modify)) { ?>
											<input type="checkbox" name="permission[modify][]"
												   value="<?php echo $permission; ?>"
												   checked="checked" /> <?php echo isset($permissions_data[$permission]) ? $permissions_data[$permission] : $permission; ?>
											<?php } else { ?>
											<input type="checkbox" name="permission[modify][]"
												   value="<?php echo $permission; ?>" /> <?php echo isset($permissions_data[$permission]) ? $permissions_data[$permission] : $permission; ?>
											<?php } ?>
										</div>
										<?php } ?>
									</div>
									<div style="text-align:right;margin:8px 0 0;">
										<a onclick="$(this).parent().parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a>
										/
										<a onclick="$(this).parent().parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</fieldset>
<?php echo $page_footer; ?>