<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
	<meta charset="UTF-8" />
	<title><?php echo '风之迷者管理后台'; ?></title>
	<base href="<?php echo $base; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<?php if ($description) { ?>
	<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<?php if ($keywords) { ?>
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<?php } ?>
	<?php foreach ($links as $link) { ?>
	<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>
	<?php foreach ($styles as $style) { ?>
	<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	<link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all" />
	<link rel="stylesheet" href="/public/layui/css/layui.css">
	<link rel="stylesheet" href="/public/css/main.css" media="all">
	<link type="text/css" href="/js/css/jquery-ui.min.css" rel="stylesheet" />
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
	<?php foreach ($scripts as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php } ?>
</head>
<body class="main_body larryTheme-A">
<?php if ($logged) { ?>
<div class="layui-layout layui-layout-admin">
	<!-- 顶部 -->
	<div class="layui-header header">
		<div class="layui-main">
			<a href="#" class="logo">风之迷者</a>
			<!-- 显示/隐藏菜单 -->
			<a href="javascript:;" class="iconfont hideMenu icon-menu1"></a>
			<!-- 顶部右侧菜单 -->
			<ul class="layui-nav top_menu">
				<li class="layui-nav-item purgeCache" id="purgeCache" pc>
					<a href="/common/home/flush"><i class="layui-icon" data-icon="&#x1002;">&#x1002;</i><cite>清除缓存</cite></a>
				</li>
				<li class="layui-nav-item showNotice" id="showNotice" pc>
					<a href="javascript:;"><i class="iconfont icon-gonggao"></i><cite>&#xe652;系统公告</cite></a>
				</li>
				<li class="layui-nav-item" mobile>
					<a href="javascript:;" class="mobileAddTab" data-url="page/user/changePwd.html"><i
								class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i><cite>设置</cite></a>
				</li>
				<li class="layui-nav-item" mobile>
					<a href="page/login/login.html" class="signOut"><i class="iconfont icon-loginout"></i> 退出</a>
				</li>
				<li class="layui-nav-item lockcms" pc>
					<a href="javascript:;"><i class="iconfont icon-lock1"></i><cite>锁屏</cite></a>
				</li>
				<li class="layui-nav-item" pc>
					<a href="javascript:;">
						<img src="/public/img/tongshao.jpg" class="layui-circle" width="35" height="35">
						<cite>请叫我通少</cite>
					</a>
					<dl class="layui-nav-child">
						<dd><a href="javascript:;" data-url="/user/user/info"><i class="iconfont icon-zhanghu" data-icon="icon-zhanghu"></i><cite>个人资料</cite></a></dd>
						<dd><a href="javascript:;" data-url="/user/user/changePwd"><i class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i><cite>修改密码</cite></a></dd>
						<dd><a href="javascript:;" class="changeSkin"><i class="iconfont icon-huanfu"></i><cite>更换皮肤</cite></a></dd>
						<dd><a href="<?php echo $logout; ?>" class="signOut"><i class="iconfont icon-loginout"></i><cite>退出</cite></a>
						</dd>
					</dl>
				</li>
			</ul>
		</div>
	</div>
	<div class="layui-side layui-bg-black">
		<div class="user-photo">
			<a class="img" title="我的头像"><img src="/public/img/tongshao.jpg"></a>
			<p>你好！<span class="userName">请叫我通少</span>, 欢迎登录</p>
		</div>
		<div class="navBar layui-side-scroll"></div>
		<!--
		<div class="layui-side-scroll">
			左侧导航区域（可配合layui已有的垂直导航）
			<ul class="layui-nav layui-nav-tree" lay-filter="test" id="menus">
				<?php foreach ($menus as $mk => $mv) { ?>
				<li class="layui-nav-item" id="<?php echo $mk; ?>">
					<a class="" href="javascript:;"><?php echo ${"text_{$mk}"}; ?></a>
					<?php foreach ($mv as $mk1 => $mv1) { ?>
					<dl class="layui-nav-child">
						<dd>
							<a href="javascript:;" class="site-demo-active" data-type="tabAdd" data-icon="/<?php echo ${$mv1}; ?>">
								<i class="layui-icon larry-icon larry-10103"></i>
								<cite><?php echo ${"text_{$mv1}"}; ?></cite>
							</a>
						</dd>
					</dl>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
		-->
	</div>
	<!-- 右侧内容 -->
	<div class="layui-body layui-form">
		<div class="layui-tab marg0" lay-filter="bodyTab" id="top_tabs_box">
			<ul class="layui-tab-title top_tab" id="top_tabs">
				<li class="layui-this" lay-id=""><i class="iconfont icon-computer"></i> <cite>后台首页</cite></li>
			</ul>
			<ul class="layui-nav closeBox">
				<li class="layui-nav-item">
					<a href="javascript:;"><i class="iconfont icon-caozuo"></i> 页面操作</a>
					<dl class="layui-nav-child">
						<dd><a href="javascript:;" class="refresh refreshThis"><i class="layui-icon">&#x1002;</i>
								刷新当前</a></dd>
						<dd><a href="javascript:;" class="closePageOther"><i class="iconfont icon-prohibit"></i>
								关闭其他</a></dd>
						<dd><a href="javascript:;" class="closePageAll"><i class="iconfont icon-guanbi"></i> 关闭全部</a>
						</dd>
					</dl>
				</li>
			</ul>
			<div class="layui-tab-content clildFrame">
				<div class="layui-tab-item layui-show">
					<iframe src="/index/index"></iframe>
				</div>
			</div>
		</div>
	</div>
	<!-- 底部 -->
	<div class="layui-footer footer layui-larry-foot">
		<div class="layui-main">
			<p>Copyright 2017 © yangxiaobing,873559947@qq.com(推荐使用IE9+,Firefox、Chrome 浏览器访问)</p>
		</div>
	</div>
	<!-- 移动导航 -->
	<div class="site-tree-mobile layui-hide"><i class="layui-icon">&#xe602;</i></div>
	<div class="site-mobile-shade"></div>
</div>
	<div class="layui-layer layui-layer-page steward layer-anim-02" id="layui-layer7" type="page" times="7" showtime="10000" contype="string" style="z-index: 19891021; width: 340px; height: 215px; top: 223px; left: 281px;"><div class="layui-layer-title" style="cursor: move;">管家提醒</div><div id="" class="layui-layer-content" style="height: 173px;"><p>目前有<span>42</span>条留言未回复<a href="/About#tabIndex=4" target="_blank">点击查看</a></p><div class="notnotice">不再提醒</div></div><span class="layui-layer-setwin"><a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;"></a></span></div>

<script type="text/javascript" src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/js/leftNav.js"></script>
<script type="text/javascript" src="/public/js/index.js"></script>
<script type="text/javascript">
	//JavaScript代码区域
	layui.use(['bodyTab', 'form', 'element', 'layer', 'jquery'], function ()
	{
		var form = layui.form,
				layer = layui.layer,
				element = layui.element;
		$ = layui.jquery;
		tab = layui.bodyTab({
			openTabNum: "50",  //最大可打开窗口数量
			url       : "json/navs.json" //获取菜单json地址
		});
		//触发事件
		var active = {
			tabAdd     : function ()
			{
				//新增一个Tab项
				element.tabAdd('bodyTab', {
					title    : $(this).text() //用于演示
					, content: '<iframe src="'+$(this).attr('data-icon')+'" data-id="0" name="ifr_0" id="ifr_0"></iframe>'
					, id     : new Date().getTime() //实际使用一般是规定好的id，这里以时间戳模拟下
				})
			}
			, tabDelete: function (othis)
			{
				//删除指定Tab项
				element.tabDelete('bodyTab', '44'); //删除：“商品管理”

				othis.addClass('layui-btn-disabled');
			}
			, tabChange: function ()
			{
				//切换到指定Tab项
				element.tabChange('bodyTab', '1'); //切换到：用户管理
			}
		};
		$('.site-demo-active').on('click', function ()
		{
			var othis = $(this), type = othis.data('type');
			active[type] ? active[type].call(this, othis) : '';
		});

		//Hash地址的定位
		var layid = location.hash.replace(/^#test=/, '');
		element.tabChange('test', layid);

		element.on('tab(test)', function (elem)
		{
			location.hash = 'test=' + $(this).attr('lay-id');
		});

		//公告层
		function showNotice()
		{
			layer.open({
				type    : 1,
				title   : "系统公告",
				closeBtn: false,
				area    : '310px',
				shade   : 0.8,
				id      : 'LAY_layuipro',
				btn     : ['火速围观'],
				moveType: 1,
				content : '<div style="padding:15px 20px; text-align:justify; line-height: 22px; text-indent:2em;border-bottom:1px solid #e2e2e2;"><p>最近偶然发现贤心大神的layui框架，瞬间被他的完美样式所吸引，虽然功能不算强大，但毕竟是一个刚刚出现的框架，后面会慢慢完善的。很早之前就想做一套后台模版，但是感觉bootstrop代码的冗余太大，不是非常喜欢，自己写又太累，所以一直闲置了下来。直到遇到了layui我才又燃起了制作一套后台模版的斗志。由于本人只是纯前端，所以页面只是单纯的实现了效果，没有做服务器端的一些处理，可能后期技术跟上了会更新的，如果有什么问题欢迎大家指导。谢谢大家。</p><p>在此特别感谢Beginner和Paco，他们写的框架给了我很好的启发和借鉴。希望有时间可以多多请教。</p></div>',
				success : function (layero)
				{
					var btn = layero.find('.layui-layer-btn');
					btn.css('text-align', 'center');
					btn.on("click", function ()
					{
						window.sessionStorage.setItem("showNotice", "true");
					})
					if ($(window).width() > 432)
					{  //如果页面宽度不足以显示顶部“系统公告”按钮，则不提示
						btn.on("click", function ()
						{
							layer.tips('系统公告躲在了这里', '#showNotice', {
								tips: 3
							});
						})
					}
				}
			});
		}

		//锁屏
		function lockPage()
		{
			layer.open({
				title   : false,
				type    : 1,
				content : '<div class="admin-header-lock" id="lock-box">' +
				'<div class="admin-header-lock-img"><img src="/public/img/tongshao.jpg"/></div>' +
				'<div class="admin-header-lock-name" id="lockUserName">请叫我通少</div>' +
				'<div class="input_btn">' +
				'<input type="password" class="admin-header-lock-input layui-input" autocomplete="off" placeholder="请输入密码解锁.." name="lockPwd" id="lockPwd" />' +
				'<button class="layui-btn" id="unlock">解锁</button>' +
				'</div>' +
				'<p>请输入“123456”，否则不会解锁成功哦！！！</p>' +
				'</div>',
				closeBtn: 0,
				shade   : 1
			})
			$(".admin-header-lock-input").focus();
			$('.layui-layer-shade').html('<video class="video-player" preload="auto" autoplay="autoplay" loop="loop" data-height="1080" data-width="1920" height="1080" width="1920" style="height: 974px; width: auto;"> <source src="http://www.zjsoar.com/layuicms/page/login/login.mp4" type="video/mp4"></video>');
		}

		$(".lockcms").on("click", function ()
		{
			window.sessionStorage.setItem("lockcms", true);
			lockPage();
		})
		// 判断是否显示锁屏
		if (window.sessionStorage.getItem("lockcms") == "true")
		{
			lockPage();
		}
		// 解锁
		$("body").on("click", "#unlock", function ()
		{
			if ($(this).siblings(".admin-header-lock-input").val() == '')
			{
				layer.msg("请输入解锁密码！");
				$(this).siblings(".admin-header-lock-input").focus();
			}
			else
			{
				if ($(this).siblings(".admin-header-lock-input").val() == "123456")
				{
					window.sessionStorage.setItem("lockcms", false);
					$(this).siblings(".admin-header-lock-input").val('');
					layer.closeAll("page");
				}
				else
				{
					layer.msg("密码错误，请重新输入！");
					$(this).siblings(".admin-header-lock-input").val('').focus();
				}
			}
		});
		$(document).on('keydown', function ()
		{
			if (event.keyCode == 13)
			{
				$("#unlock").click();
			}
		});

	});

	$.datepicker.setDefaults($.datepicker.regional['zh-CN']);

	function getURLVar(key)
	{
		var value = [];
		var query = String(document.location).split('?');
		if (query[1])
		{
			var part = query[1].split('&');
			for (i = 0; i < part.length; i++)
			{
				var data = part[i].split('=');
				if (data[0] && data[1])
				{
					value[data[0]] = data[1];
				}
			}
		}
		return (typeof value[key] != 'undefined') ? value[key] : '';
	}

	// Navigation Selected
	var route = getURLVar('route');
	if (!route)
	{
		var part = String(document.location).split('?');
		route = String(part[0]).replace($('base').attr('href'), '');
	}
	if (!route)
	{
		$('#menus #dashboard').addClass('open active');
	}
	else
	{
		var part = route.split('/'), url = part[0];
		if (part[1])
		{
			url += '/' + part[1];
		}
		$("#menus a[href='/" + url + "']").addClass('layui-this').parents('li[id]').addClass('layui-nav-itemed');
	}

	$(document).ready(function ()
	{
		// Confirm Delete
		$('#form').submit(function ()
		{
			if ($(this).attr('action').indexOf('delete', 1) != -1)
			{
				if ($(this).find(':checked').length <= 0)
				{
					alert('<?php echo $text_no_checked; ?>');
					return false;
				}
				if (!confirm('<?php echo $text_confirm; ?>'))
				{
					return false;
				}
			}
		});

		// Confirm Uninstall
		$('a').click(function ()
		{
			if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1)
			{
				if (!confirm('<?php echo $text_confirm; ?>'))
				{
					return false;
				}
			}
		});

	});
</script>
<?php } ?>
