layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','laydate'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage,
		layedit = layui.layedit,
		laydate = layui.laydate,
		$ = layui.jquery;

	//创建一个编辑器
 	var editIndex = layedit.build('links_content');
 	var addLinksArray = [],addLinks;
 	form.on("submit(addLinks)",function(data){
 		//是否添加过信息
	 	if(window.sessionStorage.getItem("addLinks")){
	 		addLinksArray = JSON.parse(window.sessionStorage.getItem("addLinks"));
	 	}
	 	//显示、审核状态
 		var homePage = data.field.homePage=="on" ? "1" : "0",
 			subPage = data.field.subPage=="on" ? "1" : "0";
 			showAddress = homePage + ',' + subPage;

		addLinks = {
			links_name  : $(".linksName").val(),
			links_url   : $(".linksUrl").val(),
			links_desc  : $(".linksDesc").text(),
			master_email: $(".masterEmail").val(),
			show_site   : showAddress
		}
		if($(".linksId").val() != undefined){
			addLinks.links_id = $(".linksId").val();
		}

		//弹出loading
		var index = layer.msg('数据提交中，请稍候', {icon: 16, time: false, shade: 0.8});
		$.ajax({
			url     : "/tool/links/addLinks",
			type    : "post",
			dataType: "json",
			data    : addLinks,
			success : function (data)
			{
				console.log(data);
				layer.close(index);
				layer.msg("链接添加成功！");
				layer.closeAll("iframe");
				//刷新父页面
				parent.location.reload();
				return false;
			}
		})
 	})
})
