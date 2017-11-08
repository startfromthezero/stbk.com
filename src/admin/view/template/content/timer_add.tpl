<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>时光轴添加--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
</head>
<body class="childrenBody">
	<form class="layui-form">
		<?php if(isset($timer_id)){ ?>
		<input class="timer_id" type="hidden" name="timer_id" value="<?php echo $timer_id; ?>" />
		<?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">时光轴内容</label>
			<div class="layui-input-block">
				<textarea class="layui-textarea layui-hide timerContent" name="content" lay-verify="content" id="timer_content"><?php echo $content; ?></textarea>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="addTimer">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
	<script type="text/javascript" src="/public/layui/layui.js"></script>
	<script type="text/javascript" src="/public/js/content/timerAdd.js"></script>
</body>
</html>