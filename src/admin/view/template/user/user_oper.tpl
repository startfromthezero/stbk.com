<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>会员添加--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
	<style type="text/css">
		.layui-form-item .layui-inline{ width:33.333%; float:left; margin-right:0; }
		@media(max-width:1240px){
			.layui-form-item .layui-inline{ width:100%; float:none; }
		}
	</style>
</head>
<body class="childrenBody">
	<form class="layui-form" style="width:80%;">
		<?php if(isset($user_id)){ ?>
		<input class="userId" type="hidden" value="<?php echo $user_id; ?>" />
		<?php } ?>
		<div class="layui-form-item">
			<label class="layui-form-label">登录名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input userName" lay-verify="required" placeholder="请输入登录名" value="<?php echo $username; ?>">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">邮箱</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input userEmail" lay-verify="email" placeholder="请输入邮箱" value="<?php echo $email; ?>">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">手机</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input userTel" lay-verify="phone" placeholder="请输入手机" value="<?php echo $tel; ?>">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
			    <label class="layui-form-label">性别</label>
			    <div class="layui-input-block userSex">
			      	<input type="radio" name="sex" value="1" title="男" <?php if($sex == 1){ ?> checked = "checked" <?php } ?> />
			      	<input type="radio" name="sex" value="2" title="女" <?php if($sex == 2){ ?> checked ="checked" <?php } ?>>
			      	<input type="radio" name="sex" value="0" title="保密"<?php if($sex == 0){ ?> checked ="checked" <?php } ?>>
			    </div>
		    </div>
		    <div class="layui-inline">
			    <label class="layui-form-label">角色等级</label>
				<div class="layui-input-block">
					<select name="userGrade" class="userGrade" lay-filter="userGrade">
						<?php foreach ($user_groups as $key=>$val) { ?>
						<option value="<?php echo $key; ?>" <?php if($key == $user_group_id){ ?> selected="selected" <?php } ?>><?php echo $val; ?></option>
						<?php } ?>
				    </select>
				</div>
		    </div>
			<div class="layui-inline">
				<div class="layui-form-item">
					<label class="layui-form-label">用户密码</label>
					<div class="layui-input-block">
						<input type="password" class="layui-input password" lay-verify="pass" placeholder="请输入密码">
					</div>
				</div>
			</div>
			<div class="layui-inline">
			    <label class="layui-form-label">用户状态</label>
				<div class="layui-input-block">
					<select name="userStatus" class="userStatus" lay-filter="userStatus">
						<option value="1" <?php if($status == 1) { ?> selected="selected" <?php } ?>>正常使用</option>
						<option value="0" <?php if($status == 0) { ?> selected="selected" <?php } ?>>限制用户</option>
				    </select>
				</div>
		    </div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="addUser">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.md5.js"></script>
	<script type="text/javascript" src="/public/layui/layui.js"></script>
	<script type="text/javascript" src="/public/js/user/addUser.js"></script>
</body>
</html>