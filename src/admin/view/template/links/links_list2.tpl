<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>后台管理系统</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="keywords" content="后台管理系统">
	<meta name="description" content="致力于提供通用版本后台管理解决方案">
	<link rel="stylesheet" href="/layui/css/layui.css">
	<link rel="stylesheet" href="/view/global.css">
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
	<table class="layui-table" lay-data="{height:670, url:'/links/links/showList', page:true, limit:10,loading:true}" lay-filter="demo">
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
			<th lay-data="{checkbox:true, fixed: true}"></th>
			<th lay-data="{field:'url_name', width:100, sort: true, fixed: true}">网站名称</th>
			<th lay-data="{field:'url', width:200}">网站地址</th>
			<th lay-data="{field:'email', width:200, sort: true}">站长邮箱</th>
			<th lay-data="{field:'time_added', width:200}">添加时间</th>
			<th lay-data="{field:'show_site', width:160}">展示位置</th>
			<th lay-data="{fixed: 'right', width:150, align:'center', toolbar: '#barDemo'}">操作</th>
		</tr>
		</thead>
	</table>
	<script type="text/html" id="barDemo">
		<a class="layui-btn layui-btn-primary layui-btn-mini" lay-event="detail">查看</a>
		<a class="layui-btn layui-btn-mini" lay-event="edit">编辑</a>
		<a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
	</script>
</div>
<div id="page"></div>
<script type="text/javascript" src="/layui/layui.js"></script>

<div class="layui-layer-move"></div>
<script type="text/javascript">
	layui.use(['laypage', 'layer', 'form', 'table', 'common', 'element'], function ()
	{
		var laypage = layui.laypage, layer = layui.layer, table = layui.table, carousel = layui.carousel, element = layui.element; //元素操作
		//监听表格复选框选择
		table.on('checkbox(demo)', function (obj)
		{
			console.log(obj)
		});
		//监听工具条
		table.on('tool(demo)', function (obj)
		{
			var data = obj.data;
			if (obj.event === 'detail')
			{
				layer.msg('ID：' + data.id + ' 的查看操作');
			}
			else if (obj.event === 'del')
			{
				layer.confirm('真的删除行么', function (index)
				{
					obj.del();
					layer.close(index);
				});
			}
			else if (obj.event === 'edit')
			{
				layer.alert('编辑行：<br>' + JSON.stringify(data))
			}
		});

		var $ = layui.$, active = {
			getCheckData    : function ()
			{ //获取选中数据
				var checkStatus = table.checkStatus('idTest')
						, data = checkStatus.data;
				layer.alert(JSON.stringify(data));
			}
			, getCheckLength: function ()
			{ //获取选中数目
				var checkStatus = table.checkStatus('idTest')
						, data = checkStatus.data;
				layer.msg('选中了：' + data.length + ' 个');
			}
			, isAll         : function ()
			{ //验证是否全选
				var checkStatus = table.checkStatus('idTest');
				layer.msg(checkStatus.isAll ? '全选' : '未全选')
			}
		};

		$('.demoTable .layui-btn').on('click', function ()
		{
			var type = $(this).data('type');
			active[type] ? active[type].call(this) : '';
		});
	});
</script>
</body>
</html>