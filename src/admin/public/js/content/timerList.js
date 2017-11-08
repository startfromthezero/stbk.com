layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;

	//加载页面数据
	var timerData = '';
	var url = '/content/timer/getList';
	$.ajax({
		url     : url,
		type    : "get",
		dataType: "json",
		success : function (data)
		{
			timerData = data;
			timerList();
		}
	})

	//查询
	$(".search_btn").click(function(){
		var newArray = [];
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
				for (var i = 0; i < timerData.length; i++)
				{
					var timerStr = timerData[i];
					var selectStr = $(".search_input").val();

					function changeStr(data)
					{
						var dataStr = '';
						var showNum = data.split(eval("/" + selectStr + "/ig")).length - 1;
						if (showNum > 1)
						{
							for (var j = 0; j < showNum; j++)
							{
								dataStr += data.split(eval("/" + selectStr + "/ig"))[j] + "<i style='color:#03c339;font-weight:bold;'>" + selectStr + "</i>";
							}
							dataStr += data.split(eval("/" + selectStr + "/ig"))[showNum];
							return dataStr;
						}
						else
						{
							dataStr = data.split(eval("/" + selectStr + "/ig"))[0] + "<i style='color:#03c339;font-weight:bold;'>" + selectStr + "</i>" + data.split(eval("/" + selectStr + "/ig"))[1];
							return dataStr;
						}
					}

					//时光轴内容
					if (timerStr.content.indexOf(selectStr) > -1)
					{
						timerStr["content"] = changeStr(timerStr.content);
					}
					//时光轴时间
					if (timerStr.time.indexOf(selectStr) > -1)
					{
						timerStr["time"] = changeStr(timerStr.time);
					}
					if (timerStr.content.indexOf(selectStr) > -1 || timerStr.time.indexOf(selectStr) > -1)
					{
						newArray.push(timerStr);
					}
				}
				timerData = newArray;
				timerList(timerData);
            	
                layer.close(index);
            },2000);
		}else{
			layer.msg("请输入需要查询的内容");
		}
	})

	//添加时光轴
	//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
	$(window).one("resize",function(){
		$(".timerAdd_btn").click(function(){
			var index = layui.layer.open({
				title : "添加时光轴",
				type : 2,
				content : "/content/timer/oper",
				success : function(layero, index){
					setTimeout(function(){
						layui.layer.tips('点击此处返回时光轴列表', '.layui-layer-setwin .layui-layer-close', {
							tips: 3
						});
					},500)
				}
			})			
			layui.layer.full(index);
		})
	}).resize();


	//批量删除
	$(".batchDel").click(function(){
		var $checkbox = $('.timer_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.timer_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
				var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
	            setTimeout(function(){
	            	//删除数据
	            	for(var j=0;j<$checked.length;j++){
	            		for(var i=0;i<timerData.length;i++){
							if(timerData[i].timerId == $checked.eq(j).parents("tr").find(".timer_del").attr("del-id")){
								timerData.splice(i,1);
								timerList(timerData);
							}
						}
	            	}
	            	$('.timer_list thead input[type="checkbox"]').prop("checked",false);
	            	form.render();
	                layer.close(index);
					layer.msg("删除成功");
	            },2000);
	        })
		}else{
			layer.msg("请选择需要删除的时光轴");
		}
	})

	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render('checkbox');
	});

	//通过判断时光轴是否全部选中来确定全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked')
		if(childChecked.length == child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	})

	//操作
	$("body").on("click", ".timer_edit", function ()
	{  //编辑
		var index = layui.layer.open({
			title  : "编辑时光轴",
			type   : 2,
			content: "/content/timer/oper?timer_id=" + $(this).attr("edit-id"),
			success: function (layero, index)
			{
				setTimeout(function ()
				{
					layui.layer.tips('点击此处返回时光轴列表', '.layui-layer-setwin .layui-layer-close', {
						tips: 3
					});
				}, 500)
			}
		})
		//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
		$(window).resize(function ()
		{
			layui.layer.full(index);
		})
		layui.layer.full(index);
	})
	
	$("body").on("click",".timer_del",function(){  //删除
		var _this = $(this);
		layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
			//_this.parents("tr").remove();
			$.ajax({
				url     : '/content/timer/del',
				type    : "get",
				dataType: "json",
				data   : {'timer_id': _this.attr('del-id')},
				success : function (res)
				{
					if(res.r == 0){
						layer.msg("删除成功");
					}else{
						layer.msg("删除失败");
					}
					parent.location.reload();
					return false;
				}
			})
			layer.close(index);
		});
	})

	function timerList(that){
		//渲染数据
		function renderDate(data,curr){
			var dataHtml = '';
			if(!that){
				currData = timerData.concat().splice(curr*nums-nums, nums);
			}else{
				currData = that.concat().splice(curr*nums-nums, nums);
			}
			if(currData.length != 0){
				for(var i=0;i<currData.length;i++){
					dataHtml += '<tr>'
			    	+'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
			    	+'<td>'+currData[i].time_added+'</td>'
			    	+'<td>'+currData[i].content+'</td>'
			    	+'<td>'
					+  '<a class="layui-btn layui-btn-mini timer_edit" edit-id ="' + data[i].timer_id + '"><i class="iconfont icon-edit"></i> 编辑</a>'
					+  '<a class="layui-btn layui-btn-danger layui-btn-mini timer_del" del-id="'+data[i].timer_id+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
			        +'</td>'
			    	+'</tr>';
				}
			}else{
				dataHtml = '<tr><td colspan="8">暂无数据</td></tr>';
			}
		    return dataHtml;
		}

		//分页
		var nums = 13; //每页出现的数据量
		if(that){
			timerData = that;
		}
		//var limit = eval('(' + timerData + ')').length;
		laypage.render({
			elem : "page",
			count: timerData.length,
			limit: nums,
			jump : function(obj){
				$(".timer_content").html(renderDate(timerData,obj.curr));
				$('.timer_list thead input[type="checkbox"]').prop("checked",false);
		    	form.render();
			}
		})
	}
})
