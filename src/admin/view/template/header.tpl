<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<?php if ($description) { ?><meta name="description" content="<?php echo $description; ?>" /><?php } ?>
<?php if ($keywords) { ?><meta name="keywords" content="<?php echo $keywords; ?>" /><?php } ?>
<?php foreach ($links as $link) { ?><link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" /><?php } ?>
<link rel="stylesheet" type="text/css" href="/view/style.css" />
<link rel="stylesheet" type="text/css" href="/view/autofit_admin_mobile.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="shortcut icon" href="/view/image/favicon.ico">
<link rel="stylesheet" href="/layui/css/layui.css">
<link rel="stylesheet" href="/view/login.css">
<link rel="stylesheet" href="/layui/css/layui.css">
<link rel="stylesheet" href="/view/global.css">
<link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_9h680jcse4620529.css">
<link rel="stylesheet" href="http://yangxiaobing.org/static/css/main.css">
<link rel="stylesheet" href="http://yangxiaobing.org/static/css/backstage.css">
<link rel="stylesheet" type="text/css" href="/js/css/gallery.css" />
<link type="text/css" href="/js/css/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/gallery.js"></script>
<script type="text/javascript" src="/js/jquery.freezeheader.js"></script>
<script type="text/javascript" src="/layui/layui.js"></script>
<?php foreach ($scripts as $script) { ?><script type="text/javascript" src="<?php echo $script; ?>"></script><?php } ?>
<!--[if lt IE 9]>
<script src="/js/html5shiv.js"></script>
<script src="/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="main_body larryTheme-A">
<?php if ($logged) { ?>
<link rel="stylesheet" type="text/css" href="/view/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="/view/nanoscroller.css" />
<link rel="stylesheet" type="text/css" href="/view/theme_styles.css" />
<div class="layui-layout layui-layout-admin">
	<div class="layui-header">
		<div class="layui-logo">layui 后台布局</div>
		<!-- 头部区域（可配合layui已有的水平导航） -->
		<ul class="layui-nav layui-layout-left">
			<li class="layui-nav-item"><a href="">控制台</a></li>
			<li class="layui-nav-item"><a href="">商品管理</a></li>
			<li class="layui-nav-item"><a href="">用户</a></li>
			<li class="layui-nav-item">
				<a href="javascript:;">其它系统</a>
				<dl class="layui-nav-child">
					<dd><a href="">邮件管理</a></dd>
					<dd><a href="">消息管理</a></dd>
					<dd><a href="">授权管理</a></dd>
				</dl>
			</li>
		</ul>
		<ul class="layui-nav layui-layout-right">
			<li class="layui-nav-item">
				<a href="javascript:;">
					<img src="http://t.cn/RCzsdCq" class="layui-nav-img">
					贤心
				</a>
				<dl class="layui-nav-child">
					<dd><a href="">基本资料</a></dd>
					<dd><a href="<?php echo $profile; ?>">安全设置</a></dd>
				</dl>
			</li>
			<li class="layui-nav-item"><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
		</ul>
	</div>
	<div class="layui-side layui-bg-black">
		<div class="layui-side-scroll">
			<!-- 左侧导航区域（可配合layui已有的垂直导航） -->
			<ul class="layui-nav layui-nav-tree" lay-filter="test" id="menus">
				<li class="layui-nav-item"><a href="/<?php echo $home; ?>"><?php echo $text_dashboard; ?></a></li>
				<?php foreach ($menus as $mk => $mv) { ?>
				<li class="layui-nav-item" id="<?php echo $mk; ?>">
					<a class="" href="javascript:;"><?php echo ${"text_{$mk}"}; ?></a>
					<?php foreach ($mv as $mk1 => $mv1) { ?>
					<?php if (!is_array($mv1)) { ?>
					<dl class="layui-nav-child">
						<dd><a href="/<?php echo ${$mv1}; ?>"><?php echo ${"text_{$mv1}"}; ?></a></dd>
					</dl>
					<?php } else { ?>
					<dl class="layui-nav-child">
						<?php foreach ($mv1 as $mk2 => $mv2) { ?>
						<dd><a href="/<?php echo ${$mv2}; ?>"><?php echo ${"text_{$mv2}"}; ?></a></dd>
						<?php } ?>
					</dl>
					<?php } ?>
					<?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="layui-body layui-form" id="larry-body">
		<div class="layui-tab marg0" id="larry-tab" lay-filter="bodyTab">
			<! -- 选项卡-->
			<ul class="layui-tab-title top_tab" id="top_tabs">
				<li class="layui-this" lay-id=""><i class="larry-icon larry-houtaishouye"></i> <cite>后台首页</cite></li>
			</ul>
			<div class="larry-title-box" style="height: 41px;">
				<div class="go-left key-press pressKey" id="titleLeft" title="滚动至最右侧"><i
							class="larry-icon larry-weibiaoti6-copy"></i></div>
				<div class="title-right" id="titleRbox">
					<div class="go-right key-press pressKey" id="titleRight" title="滚动至最左侧"><i
								class="larry-icon larry-right"></i></div>
					<div class="refresh key-press" id="refresh_iframe"><i
								class="larry-icon larry-shuaxin2"></i><cite>刷新</cite></div>

					<div class="often key-press">
						<ul class="layui-nav posr">
							<li class="layui-nav-item posb">
								<a class="top"><i class="larry-icon larry-caozuo"></i><cite>常用操作</cite><span
											class="layui-nav-more"></span></a>
								<dl class="layui-nav-child">
									<dd>
										<a href="javascript:;" class="closeCurrent"><i
													class="larry-icon larry-guanbidangqianye"></i>关闭当前选项卡</a>
									</dd>
									<dd>
										<a href="javascript:;" class="closeOther"><i
													class="larry-icon larry-guanbiqita"></i>关闭其他选项卡</a>
									</dd>
									<dd>
										<a href="javascript:;" class="closeAll"><i
													class="larry-icon larry-guanbiquanbufenzu"></i>关闭全部选项卡</a>
									</dd>
								</dl>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="layui-tab-content clildFrame" style="height:793px;">
				<div class="layui-tab-item layui-show layui-anim layui-anim-upbit">
					<iframe src="/user/user" data-id="0" name="ifr_0" id="ifr_0"></iframe>
				</div>
			</div>
		</div>
	</div>
	<div class="layui-footer footer layui-larry-foot">

		<div class="layui-main">
			<p>Copyright 2017 © yangxiaobing,873559947@qq.com(推荐使用IE9+,Firefox、Chrome 浏览器访问)</p>
		</div>
	</div>
</div>
<script type="text/javascript">
	//JavaScript代码区域
	layui.use('element', function ()
	{
		var element = layui.element;

	});

$.datepicker.regional['zh-CN'] = {
	closeText         : '关闭',
	prevText          : '<上月',
	nextText          : '下月>',
	currentText       : '今天',
	monthNames        : [
		'一月', '二月', '三月', '四月', '五月', '六月',
		'七月', '八月', '九月', '十月', '十一月', '十二月'
	],
	monthNamesShort   : [
		'一', '二', '三', '四', '五', '六',
		'七', '八', '九', '十', '十一', '十二'
	],
	dayNames          : ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
	dayNamesShort     : ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
	dayNamesMin       : ['日', '一', '二', '三', '四', '五', '六'],
	weekHeader        : '周',
	dateFormat        : 'yy-mm-dd',
	firstDay          : 0,
	isRTL             : false,
	showMonthAfterYear: true,
	yearSuffix        : '年'
};
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
	if (part[1]) {url += '/' + part[1];}
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
			if (!confirm('<?php echo $text_confirm; ?>')) {return false;}
		}
	});

	// Confirm Uninstall
	$('a').click(function ()
	{
		if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1)
		{
			if (!confirm('<?php echo $text_confirm; ?>')) {return false;}
		}
	});

	// Operation Button Scroll
	$(window).scroll(function ()
	{
		if ($(window).scrollTop() > 140)
		{
			$("#ctrl-div").addClass('fixed');
		}
		else
		{
			$("#ctrl-div").removeClass('fixed');
		}

		if ($(document).height() > $(window).height())
		{
			$('#content-wrapper').css('min-height', $(document).height() - 51);
		}
	});

	$('#content-wrapper').css('min-height', $(window).height() - 51);
	$(window).resize(function() {$('#content-wrapper').css('min-height', $(window).height() - 51);});
	$(".list").freezeHeader();
});

function tipBox(tip, tit, icn, cExec)
{
	icn = icn || 'error';
	tit = tit || 'HECart Notification';
	cExec = cExec || function () {};
	if (String(tip).indexOf('<') == -1)
	{
		tip = '<p style="padding:10px;">' + tip + '</p>';
	}
	$.fn.tboxy({title: tit, value: tip, icon: icn, time: 6000, closeExec: cExec});
}

$.fn.easyTabs = function ()
{
	var selector = this;
	this.each(function ()
	{
		var obj = $(this);
		$(obj.attr('href')).hide();
		$(obj).click(function ()
		{
			$(selector).removeClass('selected');
			$(selector).each(function (i, element)
			{
				$($(element).attr('href')).hide();
			});

			$(this).addClass('selected');
			$($(this).attr('href')).fadeIn();
			return false;
		});
	});

	$(this).show();
	$(this).first().click();
};

function gpgsFormat(gprs)
{
	var gb = 1024;
	var tb = 1024 * 1024;

	if (Math.abs(gprs) < gb)
	{
		gprs = gprs + 'M';
	}
	else if (Math.abs(gprs) >= gb && Math.abs(gprs) < tb)
	{
		gprs = (gprs / gb).toFixed(2) + 'G';
	}
	else if (Math.abs(gprs) >= tb)
	{
		gprs = (gprs / tb).toFixed(2) + 'T';
	}
	return gprs;
}
</script>
<div id="content-wrapper">
<?php } ?>
