<script type="text/javascript">
	layui.config({
		base: "http://yangxiaobing.org/static/js/"
	}).use(['form', 'table', 'layer', 'common'], function ()
	{
		var $ = layui.$,
				form = layui.form,
				table = layui.table,
				layer = layui.layer,
				common = layui.common;
		/**用户表格加载*/
		table.render({
			elem  : '#userTableList',
			url   : '/user/ajax_user_list.do',
			id    : 'userTableId',
			method: 'post',
			height: 'full-140',
			skin  : 'row',
			even  : 'true',
			size  : 'sm',
			cols  : [
				[
					{checkbox: true, fixed: 'left',},
					{field: 'userLoginName', title: '登陆账号', width: 120},
					{field: 'userName', title: '用户姓名', width: 100},
					{field: 'userStatus', title: '用户状态', width: 90, templet: '#userStatusTpl'},
					{field: 'roleNames', title: '拥有角色', width: 150},
					{field: 'creator', title: '创建人', width: 120},
					{field: 'createTime', title: '创建时间', width: 150},
					{field: 'modifier', title: '修改人', width: 120},
					{field: 'updateTime', title: '修改时间', width: 150},
					{fixed: 'right', title: '操作', align: 'center', width: 195, toolbar: '#userBar'}

				]
			],
			page  : true,
			limit : 10//默认显示10条
		});

		/**查询*/
		$(".userSearchList_btn").click(function ()
		{
			//监听提交
			form.on('submit(userSearchFilter)', function (data)
			{
				table.reload('userTableId', {
					where : {
						searchTerm   : data.field.searchTerm,
						searchContent: data.field.searchContent
					},
					height: 'full-140'
				});

			});

		});

		/**新增用户*/
		$(".userAdd_btn").click(function ()
		{
			var url = "/user/user_add.do";
			common.cmsLayOpen('新增用户', url, '550px', '265px');
		});

		/**导出用户信息*/
		$(".excelUserExport_btn").click(function ()
		{
			var url = '/user/excel_users_export.do';
			$("#userSearchForm").attr("action", url);
			$("#userSearchForm").submit();
		});
		/**批量失效*/
		$(".userBatchFail_btn").click(function ()
		{

			//表格行操作
			var checkStatus = table.checkStatus('userTableId');
			if (checkStatus.data.length == 0)
			{
				common.cmsLayErrorMsg("请选择要失效的用户信息");
			}
			else
			{
				var isCreateBy = false;
				var userStatus = false;
				var currentUserName = '155';
				var userIds = [];

				$(checkStatus.data).each(function (index, item)
				{
					userIds.push(item.userId);
					//不能失效当前登录用户
					if (currentUserName != item.userId)
					{
						isCreateBy = true;
					}
					else
					{
						isCreateBy = false;
						return false;
					}
					//用户已失效
					if (item.userStatus == 0)
					{
						userStatus = true;
					}
					else
					{
						userStatus = false;
						return false;
					}

				});

				if (isCreateBy == false)
				{
					common.cmsLayErrorMsg("当前登录用户不能被失效,请重新选择");

					return false;
				}
				if (userStatus == false)
				{
					common.cmsLayErrorMsg("当前选择的用户已失效");
					return false;
				}

				var url = "/user/ajax_user_batch_fail.do";
				var param = {userIds: userIds};
				common.ajaxCmsConfirm('系统提示', '确定失效当前用户，并解除与角色绑定关系吗?', url, param);

			}

		});

		/**监听工具条*/
		table.on('tool(userTableId)', function (obj)
		{
			var data = obj.data; //获得当前行数据
			var layEvent = obj.event; //获得 lay-event 对应的值

			//修改用户
			if (layEvent === 'user_edit')
			{
				var userId = data.userId;
				var url = "/user/user_update.do?userId=" + userId;
				common.cmsLayOpen('编辑用户', url, '550px', '265px');

				//分配角色
			}
			else if (layEvent === 'user_grant')
			{
				var userId = data.userId;
				var userStatus = data.userStatus;
				if (userStatus == 1)
				{
					common.cmsLayErrorMsg("当前用户已失效,不能被分配角色");
					return false;
				}
				var url = "/user/user_grant.do?userId=" + userId;
				common.cmsLayOpen('分配角色', url, '500px', '440px');

				//用户失效
			}
			else if (layEvent === 'user_fail')
			{
				var userId = data.userId;
				var userStatus = data.userStatus;
				var currentUserId = '155';
				/*当前登录用户的ID*/
				if (userStatus == 1)
				{
					common.cmsLayErrorMsg("当前用户已失效");
					return false;
				}
				if (userId == currentUserId)
				{
					common.cmsLayErrorMsg("当前登陆用户不能被失效");
					return false;
				}

				var url = "/user/ajax_user_fail.do";
				var param = {userId: userId};
				common.ajaxCmsConfirm('系统提示', '确定失效用户，并解除与角色绑定关系吗?', url, param);

			}
		});

	});
</script><!-- 用户状态tpl-->
<script type="text/html" id="userStatusTpl">
	{{#if(d.userStatus == 0){}} <span class="label label-success ">0-有效</span>    {{# } else if(d.userStatus == 1){ }}  <span class="label label-danger ">1-失效</span>    {{# } else { }}    {{d.userStatus}}    {{# }  }}
</script>

<!--工具条 -->
<script type="text/html" id="userBar">
	<div class="layui-btn-group">
		<a class="layui-btn layui-btn-mini user_edit" lay-event="user_edit"><i class="layui-icon larry-icon larry-bianji2"></i> 编辑</a>
	</div>
</script>
</body></html>