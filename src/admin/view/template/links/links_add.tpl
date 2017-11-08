<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>链接添加--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
</head>
<body class="childrenBody">
<form class="layui-form" style="width:80%;">
	<?php if(isset($links_id)){ ?>
	<input class="linksId" type="hidden" value="<?php echo $links_id; ?>"/>
	<?php } ?>
	<div class="layui-form-item">
		<label class="layui-form-label">网站名称</label>
		<div class="layui-input-block">
			<input type="text" class="layui-input linksName" lay-verify="required" placeholder="请输入网站名称" value="<?php echo $links_name; ?>">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">网站地址</label>
		<div class="layui-input-block">
			<input type="tel" class="layui-input linksUrl" lay-verify="required|url" placeholder="请输入网站地址" value="<?php echo $links_url; ?>">
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">展示位置</label>
			<div class="layui-input-block">
				<input type="checkbox" name="homePage" class="homePage" title="首页" <?php if($homePage == 1){ ?> checked <?php } ?>>
				<input type="checkbox" name="subPage" class="subPage" title="子页"<?php if($subPage == 1){ ?>checked <?php } ?>>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">发布时间</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input linksTime" lay-verify="date" value="<?php echo $time_added; ?>" onclick="layui.laydate.render({elem:this})">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">站长邮箱</label>
		<div class="layui-input-block">
			<input type="text" class="layui-input masterEmail" lay-verify="email" placeholder="请输入站长邮箱" value="<?php echo $master_email; ?>">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">站点描述</label>
		<div class="layui-input-block">
			<textarea placeholder="请输入站点描述" class="layui-textarea linksDesc"><?php echo $links_desc; ?></textarea>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="" lay-filter="addLinks">立即提交</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		</div>
	</div>
</form>
<script type="text/javascript" src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/js/links/linksAdd.js"></script>
</body>
</html>