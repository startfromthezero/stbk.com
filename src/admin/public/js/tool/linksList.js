layui.config({
	base : "js/"
}).use(['form','layer','jquery','laypage'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		$ = layui.jquery;

	//加载页面数据
	var linksData = '';
	$.ajax({
		url : "/tool/links/getList",
		type : "get",
		dataType : "json",
		success : function(data){
			linksData = data;
			linksList();
		}
	})

	//查询
	$(".search_btn").click(function(){
		var newArray = [];
		if($(".search_input").val() != ''){
			var index = layer.msg('查询中，请稍候',{icon: 16,time:false,shade:0.8});
            setTimeout(function(){
            	$.ajax({
					url : "/tool/links/getList",
					type : "get",
					dataType : "json",
					success : function(data){
						linksData = data;
						for(var i=0;i<linksData.length;i++){
							var linksStr = linksData[i];
							var selectStr = $(".search_input").val();
		            		function changeStr(data){
		            			var dataStr = '';
		            			var showNum = data.split(eval("/"+selectStr+"/ig")).length - 1;
		            			if(showNum > 1){
									for (var j=0;j<showNum;j++) {
		            					dataStr += data.split(eval("/"+selectStr+"/ig"))[j] + "<i style='color:#03c339;font-weight:bold;'>" + selectStr + "</i>";
		            				}
		            				dataStr += data.split(eval("/"+selectStr+"/ig"))[showNum];
		            				return dataStr;
		            			}else{
		            				dataStr = data.split(eval("/"+selectStr+"/ig"))[0] + "<i style='color:#03c339;font-weight:bold;'>" + selectStr + "</i>" + data.split(eval("/"+selectStr+"/ig"))[1];
		            				return dataStr;
		            			}
		            		}
		            		//网站名称
		            		if(linksStr.links_name.indexOf(selectStr) > -1){
			            		linksStr["links_name"] = changeStr(linksStr.links_name);
		            		}
		            		//网站地址
		            		if(linksStr.links_url.indexOf(selectStr) > -1){
			            		linksStr["links_url"] = changeStr(linksStr.links_url);
		            		}
		            		//
		            		if(linksStr.show_site.indexOf(selectStr) > -1){
			            		linksStr["show_site"] = changeStr(linksStr.show_site);
		            		}
		            		if(linksStr.links_name.indexOf(selectStr)>-1 || linksStr.links_url.indexOf(selectStr)>-1 || linksStr.show_site.indexOf(selectStr)>-1){
		            			newArray.push(linksStr);
		            		}
		            	}
		            	linksData = newArray;
		            	linksList(linksData);
					}
				})
            	
                layer.close(index);
            },2000);
		}else{
			layer.msg("请输入需要查询的内容");
		}
	})

	//添加友情链接
	$(".linksAdd_btn").click(function(){
		var index = layui.layer.open({
			title : "添加友情链接",
			type : 2,
			content : "/tool/links/oper",
			success : function(layero, index){
				setTimeout(function(){
					layui.layer.tips('点击此处返回友链列表', '.layui-layer-setwin .layui-layer-close', {
						tips: 3
					});
				},500)
			}
		})
		//改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
		$(window).resize(function(){
			layui.layer.full(index);
		})
		layui.layer.full(index);
	})

	//批量删除
	$(".batchDel").click(function(){
		var $checkbox = $('.links_list tbody input[type="checkbox"][name="checked"]');
		var $checked = $('.links_list tbody input[type="checkbox"][name="checked"]:checked');
		if($checkbox.is(":checked")){
			layer.confirm('确定删除选中的信息？',{icon:3, title:'提示信息'},function(index){
				var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
	            setTimeout(function(){
	            	//删除数据
	            	for(var j=0;j<$checked.length;j++){
	            		for(var i=0;i<linksData.length;i++){
							if(linksData[i].links_id == $checked.eq(j).parents("tr").find(".links_del").attr("del-id")){
								linksData.splice(i,1);
								linksList(linksData);
							}
						}
	            	}
	            	$('.links_list thead input[type="checkbox"]').prop("checked",false);
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
		data.elem.checked;
		if(childChecked.length == child.length){
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
		}else{
			$(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
		}
		form.render('checkbox');
	})
 
	//操作
	$("body").on("click",".links_edit",function(){  //编辑
		var index = layui.layer.open({
			title  : "编辑友情链接",
			type   : 2,
			content: "/tool/links/oper?links_id="+ $(this).attr("edit-id"),
			success: function (layero, index)
			{
				setTimeout(function ()
				{
					layui.layer.tips('点击此处返回友链列表', '.layui-layer-setwin .layui-layer-close', {
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

	$("body").on("click",".links_del",function(){  //删除
		var _this = $(this);
		layer.confirm('确定删除此信息？', {icon: 3, title: '提示信息'}, function (index)
		{
			$.ajax({
				url     : "/tool/links/delLinks",
				type    : "post",
				dataType: "json",
				data    : {links_id: _this.attr("del-id")},
				success : function (data)
				{
					layer.msg(data.msg);
					location.reload();
				}
			});
			layer.close(index);
		});
	})

	function linksList(that){
		//渲染数据
		function renderDate(data,curr){
			var dataHtml = '';
			if(!that){
				currData = linksData.concat().splice(curr*nums-nums, nums);
			}else{
				currData = that.concat().splice(curr*nums-nums, nums);
			}
			if(currData.length != 0){
				for(var i=0;i<currData.length;i++){
					dataHtml += '<tr>'
			    	+'<td><input type="checkbox" name="checked" lay-skin="primary" lay-filter="choose"></td>'
			    	+'<td align="left">'+currData[i].links_name+'</td>'
			    	+'<td><a style="color:#1E9FFF;" target="_blank" href="'+currData[i].links_url+'">'+currData[i].links_url+'</a></td>'
			    	+'<td>'+currData[i].master_email+'</td>'
			    	+'<td>'+currData[i].time_added+'</td>'
			    	+'<td>'+currData[i].show_site+'</td>'
			    	+'<td>'
					+  '<a class="layui-btn layui-btn-mini links_edit" edit-id="' + data[i].links_id + '"><i class="iconfont icon-edit"></i> 编辑</a>'
					+  '<a class="layui-btn layui-btn-danger layui-btn-mini links_del" del-id="'+data[i].links_id+'"><i class="layui-icon">&#xe640;</i> 删除</a>'
			        +'</td>'
			    	+'</tr>';
				}
			}else{
				dataHtml = '<tr><td colspan="7">暂无数据</td></tr>';
			}
		    return dataHtml;
		}

		//分页
		var nums = 13; //每页出现的数据量
		if(that){
			linksData = that;
		}
		laypage.render({
			elem : "page",
			count: linksData.length,
			limit: nums,
			jump : function(obj){
				$(".links_content").html(renderDate(linksData,obj.curr));
				$('.links_list thead input[type="checkbox"]').prop("checked",false);
		    	form.render();
			}
		})
	}
})
