<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>后台管理系统</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="keywords" content="后台管理系统">
	<meta name="description" content="致力于提供通用版本后台管理解决方案">
	<link rel="stylesheet" href="/layui/css/layui.css">
	<link rel="stylesheet" href="/view/global.css">
	<link rel="stylesheet" href="/js/css/news.css">
	<script src="/layui/layui.js"></script>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
</head>
<body class="childrenBody">
<blockquote class="layui-elem-quote news_search">
	<div class="layui-inline">
		<div class="layui-input-inline">
			<input type="text" value="" placeholder="请输入关键字" class="layui-input search_input">
		</div>
		<a class="layui-btn search_btn">查询</a>
	</div>
	<div class="layui-inline">
		<a class="layui-btn linksAdd_btn" style="background-color:#5FB878">添加链接</a>
	</div>
	<div class="layui-inline">
		<a class="layui-btn layui-btn-danger batchDel">批量删除</a>
	</div>
	<div class="layui-inline">
		<div class="layui-form-mid layui-word-aux">本页面刷新后除新添加的链接外所有操作无效，关闭页面所有数据重置</div>
	</div>
</blockquote>
<div class="layui-form links_list">
	<table class="layui-table">
		<colgroup>
			<col width="50">
			<col width="30%">
			<col>
			<col>
			<col>
			<col>
			<col>
			<col width="13%">
		</colgroup>
		<thead>
		<tr>
			<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose">
				<div class="layui-unselect layui-form-checkbox" lay-skin="primary"><i class="layui-icon"></i></div>
			</th>
			<th style="text-align:left;">网站名称</th>
			<th>网站地址</th>
			<th>站长邮箱</th>
			<th>添加时间</th>
			<th>展示位置</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody class="links_content">
		</tbody>
	</table>
</div>
<div id="page"></div>
<script type="text/javascript" src="/layui/layui.js"></script>
<script type="text/javascript" src="http://stbk.admin.com/js/links/linksList.js"></script>
</body>
</html>