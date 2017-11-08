layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit','upload'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		layedit = layui.layedit,
		$ = layui.jquery,
		upload = layui.upload;

	//创建一个编辑器
 	var editIndex = layedit.build('news_content');
 	var addNewsArray = [],addNews;
 	form.on("submit(addNews)",function(data){
 		//是否添加过信息
	 	if(window.sessionStorage.getItem("addNews")){
	 		addNewsArray = JSON.parse(window.sessionStorage.getItem("addNews"));
	 	}
		//显示、审核状态
		var newsCommend = data.field.tuijian == "on" ? "1" : "0",
			newsStatus = data.field.shenhe == "on" ? "1" : "0",
			isShow = data.field.show == "on" ? "1" : "0";

		addNews = {
			news_name    : $(".newsName").val(),
			news_look    : $(".newsLook").val(),
			news_author  : $(".newsAuthor").val(),
			img_url      : $("#articleCoverSrc").val(),
			type_id      : $(".newstype").val(),
			is_show      : isShow,
			news_status  : newsStatus,
			news_commend : newsCommend,
			news_content : layedit.getContent(editIndex),
			news_keyword : $(".newsKeyword").val(),
			news_synopsis: $(".newsSynopsis").val(),
		}
		if ($(".newsId").val() != undefined){
			addNews.news_id = $(".newsId").val();
		}

		//弹出loading
		var index = layer.msg('数据提交中，请稍候', {icon: 16, time: false, shade: 0.8});
		$.ajax({
			url     : "/content/news/addNews",
			type    : "post",
			dataType: "json",
			data    : addNews,
			success : function (data)
			{
				layer.close(index);
				layer.msg("文章添加成功！");
				layer.closeAll("iframe");
				//刷新父页面
				parent.location.reload();
				return false;
			}
		})
 	});

	//普通图片上传
	var uploadInst = upload.render({
		elem    : '#img-upload'
		, url   : "/common/img_upload"
		, data  : {'_hash': $('#img-upload').attr('hash')}
		, before: function (obj) {
			//预读本地文件示例，不支持ie8
			obj.preview(function (index, file, result) {
				$('#articleCoverImg').attr('src', result); //图片链接（base64）
			});
		}
		, done  : function (res) {
			console.log(res);
			//如果上传失败
			if (res.r == 0)
			{
				console.log(res);
				$('#articleCoverImg').attr('src', res.url);
				$("#articleCoverSrc").val(res.url);
				return layer.msg('上传成功');
			}
			//上传成功
		}
		, error : function () {

		}
	});
});
