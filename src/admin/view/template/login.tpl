<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>后台管理系统登陆</title>
	<link rel="stylesheet" href="/public/layui/css/layui.css">
	<link rel="stylesheet" href="/view/login.css">
	<script type="text/javascript" src="/public/layui/layui.js"></script>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<div class="layui-carousel video_mask" id="login_carousel">
	<div carousel-item>
		<div class="carousel_div1"></div>
		<div class="carousel_div2"></div>
		<div class="carousel_div3"></div>
	</div>
	<div class="login layui-anim layui-anim-up">
		<h1>CMS 后台管理系统</h1></p>
		<form class="layui-form" action="" method="post">
			<div class="layui-form-item">
				<input type="text" name="username" lay-verify="required" placeholder="请输入账号" autocomplete="off" value="" class="layui-input">
			</div>
			<div class="layui-form-item">
				<input type="password" name="password" value="<?php echo $password; ?>" salt="<?php echo $salt; ?>" lay-verify="required" placeholder="<?php echo $entry_password; ?>" autocomplete="off"  class="layui-input">
			</div>
			<?php if (isset($this->session->data['captcha'])) { ?>
			<div class="layui-form-item form_code">
				<input class="layui-input" name="captcha" placeholder="<?php echo $entry_captcha; ?>" lay-verify="required" type="text" autocomplete="off">
				<div class="code"><img src="/common/login/captcha" width="116" height="36"></div>
			</div>
			<?php } ?>
			<?php if ($redirect) { ?><input type="hidden" name="redirect" value="<?php echo $redirect; ?>" /><?php } ?>
			<a class="layui-btn login_btn" lay-submit="" lay-filter="login">登陆系统</a>
		</form>
	</div>
</div>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript"><!--
$('#form input').keydown(function (e)
{
	if (e.keyCode == 13) {$('#form').submit();}
});
$('#form input[name="username"]').focus();
//-->
</script>
</body>
</html>
<script>
	layui.config({
		base: "/js/"
	}).use(['form', 'layer', 'jquery', 'common', 'carousel'], function ()
	{
		var $ = layui.jquery,
				form = layui.form,
				common = layui.common,
				carousel = layui.carousel;

		/**背景图片轮播*/
		carousel.render({
			elem     : '#login_carousel',
			width    : '100%',
			height   : '100%',
			interval : 2000,
			arrow    : 'none',
			anim     : 'fade',
			indicator: 'none'
		});

		/**重新生成验证码*/
		function reqCaptcha()
		{
			var url = "/common/login/captcha?" + new Date().getTime()
			$('.code img').attr("src", url)
		}

		/**点击验证码重新生成*/
		$('.code img').on('click', function ()
		{
			reqCaptcha();
		});

		/**监听登陆提交*/
		form.on('submit(login)', function (data)
		{
			var salt = $("input[name='password']").attr('salt');
			var pwd = $.md5($("input[name='password']").val());
			data.field.password = $.md5($.md5($.md5(pwd.substring(0, 9)) + pwd) + salt);
			//弹出loading
			var loginLoading = top.layer.msg('登陆中，请稍候', {icon: 16, time: false, shade: 0.8});

			//登陆验证
			$.ajax({
				url    : '/common/login/loginCheck',
				type   : 'post',
				async  : false,
				data   : data.field,
				success: function (data)
				{
					var data = eval('(' + data + ')');
					if(data.r != 0){
						top.layer.close(loginLoading);
						common.cmsLayErrorMsg(data.msg);
						location.reload();
						return false;
					}else{
						window.location.href = data.url;
						top.layer.close(loginLoading);
						return false;
					}
				}
			});
		});
		//layer.alert('账号:user_system/123456 用户管理员<br>账号:user_readonly/123456 只读用户<br>原admin账号暂时回收');
	});
</script>