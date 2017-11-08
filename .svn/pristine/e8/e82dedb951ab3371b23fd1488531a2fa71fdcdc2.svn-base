<?php echo $page_header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/user.png" /><?php echo $heading_title; ?></h1>
			<div class="buttons" id="ctrl-div">
				<a onclick="$('#forgotten').submit();" class="button"><?php echo $button_reset; ?></a>
				<a onclick="location='<?php echo $cancel; ?>'" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>

		<div class="content" style="text-align:center;padding:50px;">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
			<p><?php echo $text_email; ?></p><br/><br/>
			<?php echo $entry_email; ?> <input type="text" name="email" value="<?php echo $email; ?>" size="70"/>
		</form>
		</div>
	</div>
</div>
</body>
</html>