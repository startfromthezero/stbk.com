layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;

	//加载页面数据
	var newsData = '';
	var url = '/content/news/getList';
	if ($(".top_tab li.layui-this cite", parent.document).text() == "待审核文章")
	{
		url += '?news_status=0';
	}
	$.ajax({
		url     : url,
		type    : "get",
		dataType: "json",
		success : function (data)
		{
			newsData = data;
			newsList();
		}
	})

	//查询
	$(".search_btn").click(function(){
		var newArray = [];
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
				for (var i = 0; i < newsData.length; i++)
				{
					var newsStr = newsData[i];
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

					//文章标题
					if (newsStr.news_name.indexOf(selectStr) > -1)
					{
						newsStr["news_name"] = changeStr(newsStr.news_name);
					}
					//发布人
					if (newsStr.news_author.indexOf(selectStr) > -1)
					{
						newsStr["news_author"] = changeStr(newsStr.news_author);
					}
					//审核状态
					if (newsStr.news_status.indexOf(selectStr) > -1)
					{
						newsStr["newsStatus"] = changeStr(newsStr.news_status);
					}
					//浏览权限
					if (newsStr.news_look.indexOf(selectStr) > -1)
					{
						newsStr["news_look"] = changeStr(newsStr.news_look);
					}
					//发布时间
					if (newsStr.time_added.indexOf(selectStr) > -1)
					{
						newsStr["time_added"] = changeStr(newsStr.time_added);
					}
					if (newsStr.news_name.indexOf(selectStr) > -1 || newsStr.news_author.indexOf(selectStr) > -1 || newsStr.news_status.indexOf(selectStr) > -1 || newsStr.news_look.indexOf(selectStr) > -1 || newsStr.time_added.indexOf(selectStr) > -1)
					{
						newArray.push(newsStr);
					}
				}
				newsData = newArray;
				newsList(newsData);
            	
                layer.close(index);
            },2000);
		}else{
			layer.msg("请输入需要查询的内容");
		}
	})

	//添加文章
	//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
	$(window).one("resize",function(){
		$(".newsAdd_btn").click(function(){
			var index = layui.layer.open({
				title : "添加文章",
				type : 2,
				content : "/content/news/oper",
				success : function(layero, index){
					setTimeout(function(){
						layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
							tips: 3
						});
					},500)
				}
			})			
			layui.layer.full(index);
		})
	}).resize();

	//推荐文章
	$(".recommend").click(function(){
		var $checkbox = $(".news_list").find('tbody input[type="checkbox"]:not([name="show"])');
		if($checkbox.is(":checked")){
			var index = layer.msg('推荐中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
                layer.close(index);
				layer.msg("推荐成功");
            },2000);
		}else{
			layer.msg("请选择需要推荐的文章");
		}
	})

	//审核文章
	$(".audit_btn").click(function(){
		var $checkbox = $('.news_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.news_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			var index = layer.msg('审核中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
            	for(var j=0;j<$checked.length;j++){
            		for(var i=0;i<newsData.length;i++){
						if(newsData[i].newsId == $checked.eq(j).parents("tr").find(".news_del").attr("data-id")){
							//修改列表中的文字
							$checked.eq(j).parents("tr").find("td:eq(3)").text("审核通过").removeAttr("style");
							//将选中状态删除
							$checked.eq(j).parents("tr").find('input[type="checkbox"][name="checked"]').prop("checked",false);
							form.render();
						}
					}
            	}
                layer.close(index);
				layer.msg("审核成功");
            },2000);
		}else{
			layer.msg("请选择需要审核的文章");
		}
	})

	//批量删除
	$(".batchDel").click(function(){
		var $checkbox = $('.news_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.news_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
				var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
	            setTimeout(function(){
	            	//删除数据
	            	for(var j=0;j<$checked.length;j++){
	            		for(var i=0;i<newsData.length;i++){
							if(newsData[i].newsId == $checked.eq(j).parents("tr").find(".news_del").attr("data-id")){
								newsData.splice(i,1);
								newsList(newsData);
							}
						}
	            	}
	            	$('.news_list thead input[type="checkbox"]').prop("checked",false);
	            	form.render();
	                layer.close(index);
					layer.msg("删除成功");
	            },2000);
	        })
		}else{
			layer.msg("请选择需要删除的文章");
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

	//通过判断文章是否全部选中来确定全选按钮是否选中
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

	//是否展示
	form.on('switch(isShow)', function(data){
		var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            layer.close(index);
			layer.msg("展示状态修改成功！");
        },2000);
	})

	//操作
	$("body").on("click", ".news_edit", function ()
	{  //编辑
		var index = layui.layer.open({
			title  : "编辑文章",
			type   : 2,
			content: "/content/news/oper?news_id=" + $(this).attr("edit-id"),
			success: function (layero, index)
			{
				setTimeout(function ()
				{
					layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
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

	$("body").on("click",".news_collect",function(){  //收藏.
		if($(this).text().indexOf("已收藏") > 0){
			layer.msg("取消收藏成功！");
			$(this).html("<i class='layui-icon'>&#xe600;</i> 收藏");
		}else{
			layer.msg("收藏成功！");
			$(this).html("<i class='iconfont icon-star'></i> 已收藏");
		}
	})

	$("body").on("click",".news_del",function(){  //删除
		var _this = $(this);
		layer.confirm('确定删除此信息？',{icon:3, title:'提示信息'},function(index){
			//_this.parents("tr").remove();
			for(var i=0;i<newsData.length;i++){
				if(newsData[i].newsId == _this.attr("data-id")){
					newsData.splice(i,1);
					newsList(newsData);
				}
			}
			layer.close(index);
		});
	})

	function newsList(that){
		//渲染数据
		function renderDate(data,curr){
			var dataHtml = '';
			if(!that){
				currData = newsData.concat().splice(curr*nums-nums, nums);
			}else{
				currData = that.concat().splice(curr*nums-nums, nums);
			}
			if(currData.length != 0){
				for(var i=0;i<currData.length;i++){
					dataHtml += '<tr>'
			    	+'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
			    	+'<td align="left">'+currData[i].news_name+'</td>'
			    	+'<td>'+currData[i].news_author+'</td>';
			    	if(currData[i].news_status == "待审核"){
			    		dataHtml += '<td style="color:#f00">'+currData[i].news_status+'</td>';
			    	}else{
			    		dataHtml += '<td>'+currData[i].news_status+'</td>';
			    	}
			    	dataHtml += '<td>'+currData[i].news_look+'</td>'
			    	+'<td><input type="checkbox" name="show" lay-skin="switch" lay-text="是|否" lay-filter="is_show"'+currData[i].is_show+'></td>'
			    	+'<td>'+currData[i].time_added+'</td>'
			    	+'<td>'
					+  '<a class="layui-btn layui-btn-mini news_edit" edit-id ="' + data[i].news_id + '"><i class="iconfont icon-edit"></i> 编辑</a>'
					+  '<a class="layui-btn layui-btn-normal layui-btn-mini news_collect"><i class="layui-icon">&#xe600;</i> 收藏</a>'
					+  '<a class="layui-btn layui-btn-danger layui-btn-mini news_del" data-id="'+data[i].news_id+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
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
			newsData = that;
		}
		//var limit = eval('(' + newsData + ')').length;
		laypage.render({
			elem : "page",
			count: newsData.length,
			limit: nums,
			jump : function(obj){
				$(".news_content").html(renderDate(newsData,obj.curr));
				$('.news_list thead input[type="checkbox"]').prop("checked",false);
		    	form.render();
			}
		})
	}
})
