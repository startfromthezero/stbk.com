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
</head>
<body>
<div class="larry-grid layui-anim layui-anim-upbit larryTheme-A ">
	<div class="larry-personal">
		<div class="layui-tab">
			<blockquote class="layui-elem-quote mylog-info-tit">
				<div class="layui-inline">
					<form class="layui-form" id="userSearchForm">
						<div class="layui-input-inline" style="width:110px;">
							<select name="searchTerm">
								<option value="userLoginNameTerm">登陆账号</option>
								<option value="userNameTerm">用户姓名</option>
							</select>
							<div class="layui-unselect layui-form-select">
								<div class="layui-select-title"><input type="text" placeholder="请选择" value="登陆账号" readonly="" class="layui-input layui-unselect"><i class="layui-edge"></i></div>
								<dl class="layui-anim layui-anim-upbit">
									<dd lay-value="userLoginNameTerm" class="layui-this">登陆账号</dd>
									<dd lay-value="userNameTerm" class="">用户姓名</dd>
								</dl>
							</div>
						</div>
						<div class="layui-input-inline" style="width:145px;">
							<input type="text" name="searchContent" value="" placeholder="请输入关键字"
								   class="layui-input search_input">
						</div>
						<a class="layui-btn userSearchList_btn" lay-submit="" lay-filter="userSearchFilter"><i
									class="layui-icon larry-icon larry-chaxun7"></i>查询</a>
					</form>
				</div>

				<div class="layui-inline">
					<a class="layui-btn layui-btn-normal userAdd_btn"> <i class="layui-icon larry-icon larry-xinzeng1"></i>新增用户</a>
				</div>
				<div class="layui-inline">
					<a class="layui-btn layui-btn-normal excelUserExport_btn" style="background-color:#5FB878"> <i class="layui-icon larry-icon larry-danye"></i>导出</a>
				</div>
			</blockquote>
			<div class="larry-separate"></div>
			<div class="layui-tab-item layui-show" style="padding: 10px 15px;">
				<table class="layui-table" lay-data="{height:670, url:'/user/org/showList', page:true, limit:10,loading:true}" lay-filter="demo">
					<thead>
					<tr>
						<th lay-data="{field:'name', width:100, sort: true, fixed: true}">机构名称</th>
						<th lay-data="{field:'partner_id', width:200}">合作编号</th>
						<th lay-data="{field:'partner_key', width:200, sort: true}">合作密匙</th>
						<th lay-data="{field:'notify_url', width:200}">异步通知地址</th>
						<th lay-data="{field:'user_num', width:160}">可开账户数量</th>
						<th lay-data="{field:'email', width:150, sort: true}">负责人邮箱</th>
						<th lay-data="{field:'tel', width:150, sort: true}">负责人手机</th>
						<th lay-data="{field:'memo', width:150}">机构描述</th>
						<th lay-data="{fixed: 'right', width:150, align:'center', toolbar: '#barDemo'}">管理</th>
					</tr>
					</thead>
				</table>
			</div>
			<script type="text/html" id="barDemo">
				<a class="layui-btn layui-btn-primary layui-btn-mini" lay-event="detail">查看</a>                <a
						class="layui-btn layui-btn-mini" lay-event="edit">编辑</a>                <a
						class="layui-btn layui-btn-danger layui-btn-mini" lay-event="del">删除</a>
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