var $;
layui.config({
	base : "js/"
}).use(['form','layer','jquery'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		laypage = layui.laypage;
		$ = layui.jquery;

 	var addUserArray = [],addUser,msg = '用户添加成功！';
 	form.on("submit(addUser)",function(data){
 		//是否添加过信息
	 	// if(window.sessionStorage.getItem("addUser")){
	 	// 	addUserArray = JSON.parse(window.sessionStorage.getItem("addUser"));
	 	// }
		addUser = {
			username     : $(".userName").val(),
			email        : $(".userEmail").val(),
			tel          : $(".userTel").val(),
			sex          : $(".userSex input[type='radio']:checked").val(),
			user_group_id: $(".userGrade").val(),
			password     : $.md5($(this).val()),
			status       : $(".userStatus").val()
		}
		$(this)
		if ($(".userId").val() != undefined)
		{
			addUser.user_id = $(".userId").val();
			msg = '用户编辑成功！';
		}
		//弹出loading
		var index = layer.msg('数据提交中，请稍候', {icon: 16, time: false, shade: 0.8});
		$.ajax({
			url     : "/user/user/addUser",
			type    : "post",
			dataType: "json",
			data    : addUser,
			success : function (data) {
				//console.log(data);
				layer.close(index);
				layer.msg(msg);
				layer.closeAll("iframe");
				//刷新父页面
				parent.location.reload();
				return false;
			}
		})
 	})
})

//格式化时间
function formatTime(_time){
    var year = _time.getFullYear();
    var month = _time.getMonth()+1<10 ? "0"+(_time.getMonth()+1) : _time.getMonth()+1;
    var day = _time.getDate()<10 ? "0"+_time.getDate() : _time.getDate();
    var hour = _time.getHours()<10 ? "0"+_time.getHours() : _time.getHours();
    var minute = _time.getMinutes()<10 ? "0"+_time.getMinutes() : _time.getMinutes();
    return year+"-"+month+"-"+day+" "+hour+":"+minute;
}
