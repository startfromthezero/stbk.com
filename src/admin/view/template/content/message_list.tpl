<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>消息列表--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
	<link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all" />
	<link rel="stylesheet" href="/public/css/message.css" media="all" />
</head>
<body class="childrenBody">
<form class="layui-form">
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline selectMsg">
			<select name="msgColl" lay-filter="selectMsg">
				<option value="0">全部</option>
				<option value="1">已收藏</option>
			</select>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux">本页所有数据均为静态，刷新后所有操作无效</div>
		</div>
	</blockquote>
	<table class="layui-table msg_box" lay-skin="line">
		<colgroup>
			<col width="45%">
			<col width="25%">
			<col width="15%">
			<col>
		</colgroup>
		<tbody class="msgHtml"></tbody>
	</table>
</form>
<script type="text/javascript" src="/public/layui/layui.js"></script>
<script type="text/javascript" src="/public/js/content/message.js"></script>
</body>
</html>