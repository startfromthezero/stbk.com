<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>后台管理系统</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="keywords" content="后台管理系统">
	<meta name="description" content="致力于提供通用版本后台管理解决方案">
	<link rel="stylesheet" href="/layui/css/layui.css">
	<link rel="stylesheet" href="/view/global.css">
	<script src="http://stbk.admin.com/layui/layui.all.js"></script>
</head>
<body>
<div class="larry-grid layui-anim layui-anim-upbit larryTheme-A ">
	<div class="larry-personal">
		<div class="layui-tab">
			<blockquote class="layui-elem-quote mylog-info-tit">
				<div class="layui-inline">
					<form class="layui-form" id="linksSearchForm">
						<div class="layui-input-inline" style="width:145px;">
							<input type="text" name="searchContent" value="" placeholder="请输入关键字" class="layui-input search_input">
						</div>
						<a class="layui-btn userSearchList_btn" lay-submit="" lay-filter="userSearchFilter"><i class="layui-icon larry-icon larry-chaxun7"></i>查询</a>
					</form>
				</div>

				<div class="layui-inline">
					<a class="layui-btn layui-btn-normal userAdd_btn"> <i class="layui-icon larry-icon larry-xinzeng1"></i>添加链接</a>
				</div>
				<div class="layui-inline">
					<a class="layui-btn layui-btn-normal excelUserExport_btn" style="background-color:#5FB878"> <i class="layui-icon larry-icon larry-danye"></i>批量删除</a>
				</div>
			</blockquote>
			<div class="larry-separate"></div>
			<div class="layui-tab-item layui-show" style="padding: 10px 15px;">
				<table class="layui-table" lay-data="{height:670, url:'/links/links/showList', page:true, limit:10,loading:true}" lay-filter="demo">
					<thead>
					<tr>
						<th lay-data="{field:'url_name', width:100, sort: true, fixed: true}">网站名称</th>
						<th lay-data="{field:'url', width:200}">网站地址</th>
						<th lay-data="{field:'email', width:200, sort: true}">站长邮箱</th>
						<th lay-data="{field:'time_added', width:200}">添加时间</th>
						<th lay-data="{field:'show_site', width:160}">展示位置</th>
						<th lay-data="{fixed: 'right', width:150, align:'center', toolbar: '#barDemo'}">操作</th>
					</tr>
					</thead>
				</table>
			</div>
			<script type="text/html" id="barDemo">
               <a class="layui-btn layui-btn-mini" lay-event="edit">编辑</a>
			   <a class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
			</script>
		</div>
	</div>
</div>
<script type="text/javascript">
	layui.use(['laypage', 'layer', 'form', 'table', 'common', 'element'], function ()
	{
		var laypage = layui.laypage, layer = layui.layer, table = layui.table, carousel = layui.carousel, element = layui.element; //元素操作
		//监听工具条
		table.on('tool(demo)', function (obj)
		{ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
			var data = obj.data //获得当前行数据
					, layEvent = obj.event; //获得 lay-event 对应的值
			if (layEvent === 'detail')
			{
				console.log(obj);
				layer.msg('查看操作');
			}
			else if (layEvent === 'del')
			{
				layer.confirm('真的删除行么', function (index)
				{
					obj.del(); //删除对应行（tr）的DOM结构
					layer.close(index);
					//向服务端发送删除指令
				});
			}
			else if (layEvent === 'edit')
			{
				layer.msg('编辑操作');
			}
		});
	});
</script>
</body>
</html>