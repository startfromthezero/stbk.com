<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>错误日志--layui后台管理模板</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="/public/layui/css/layui.css" media="all" />
	<link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all" />
	<link rel="stylesheet" href="/public/css/news.css" media="all" />
</head>
<body class="childrenBody">
<blockquote class="layui-elem-quote" style="padding: 10px;" id="layerDemo">
	 <span style="display: inline-block;height: 30px;line-height: 30px;">错误日志</span><button class="layui-btn layui-btn-small layui-btn-normal"  data-method="confirmTrans" target="_blank" style="float:right;">清除日志</button>
	<div style="clear: right;"></div>
</blockquote>
<?php if(!empty($log)){ ?>
<blockquote class="layui-elem-quote layui-quote-nm"><pre><?php echo $log; ?></pre></blockquote>
<?php } ?>
<script type="text/javascript" src="/public/layui/layui.js"></script>
<script>
layui.use('layer', function(){ //独立版的layer无需执行这一句
  var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句
  //触发事件
  var active = {
    confirmTrans: function(){
	$.ajax({
		url : "/other/error_log/clear",
		type : "get",
		dataType : "json",
		success : function(res){
			if(res.r == 0){
			      layer.msg('你已清除了所有日志', {
			        time: 2000, //2s后自动关闭
			      });
				location.reload();
			}
		}
	})
    }
  };
  $('#layerDemo .layui-btn').on('click', function(){
    var othis = $(this), method = othis.data('method');
    active[method] ? active[method].call(this, othis) : '';
  });
});
</script>
</body>
</html>