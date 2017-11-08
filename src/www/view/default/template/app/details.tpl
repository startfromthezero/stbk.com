<?php echo $page_header; ?>
<div class="body" style="background-color:#ebebeb;margin:0 auto;height:100%;">
	<ul class="details-title">
		<li>使用日期</li>
		<li>使用时间(分钟)</li>
		<li>用量(MB)</li>
	</ul>
	<table class="list details-list">
		<tr>
			<td>2016-06-30</td>
			<td>5</td>
			<td>10</td>
		</tr>
		<tr>
			<td>2016-06-30</td>
			<td>5</td>
			<td>10</td>
		</tr>
		<tr>
			<td>2016-06-30</td>
			<td>5</td>
			<td>10</td>
		</tr>
		<tr>
			<td>2016-06-30</td>
			<td>5</td>
			<td>10</td>
		</tr>
	</table>
	<div class="noresult" style="color:#413f3e;">没有用量详细数据</div>
</div>




<script type="text/javascript">
var $page = 1, $canLoad = true;
var $loading = '<div id="loading" class="wait center"><?php echo $text_loading; ?><img src="/img/app/loading.gif" /></div>';
$(window).scroll(function ()
{
	var scrollTop = $(this).scrollTop();//滚动条距离顶部的高度
	var scrollHeight = $(document).height();//当前页面的总高度
	var windowHeight = $(this).height();//当前可视的页面高度

	if ($canLoad && scrollTop + windowHeight >= scrollHeight)
	{
		$canLoad = false;
		$('#resDiv').append($loading);
		$.post('/app/main/paylog', {'page': ++$page, 'ajax': 1}, function (result)
		{
			$('#loading').remove();
			if (result != '')
			{
				$canLoad = true;
				$('#resDiv').append(result);
			}
		});
	}
});
</script>
<?php echo $page_footer; ?>