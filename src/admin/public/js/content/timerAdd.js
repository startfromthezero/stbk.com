layui.config({
	base : "js/"
}).use(['form','layer','jquery','layedit'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		layedit = layui.layedit,
		$ = layui.jquery;

	//创建一个编辑器
	var editIndex = layedit.build('timer_content');
 	var msg='时光轴添加成功！';
 	form.on("submit(addTimer)",function(data){
		if (data.field.timer_id != undefined){
			msg='时光轴编辑成功！';
		}
		data.field.content = layedit.getContent(editIndex);

		//弹出loading
		var index = layer.msg('数据提交中，请稍候', {icon: 16, time: false, shade: 0.8});
		$.ajax({
			url     : "/content/timer/addTimer",
			type    : "post",
			data    : data.field,
			dataType: "json",
			success : function (res)
			{
				layer.close(index);
				layer.msg(msg);
				layer.closeAll("iframe");
				//刷新父页面
				parent.location.reload();
			//	return false;
			},
			error   : function (err)
			{
				console.log(err);
			}
		})
 	});
});
