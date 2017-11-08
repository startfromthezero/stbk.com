<?php echo $page_header; ?>
<style>
#history{width:100%;
	list-style-type:decimal;
	height:calc(70vh - 40px);
	overflow-x:hidden;
	overflow-y:auto;
}
#history li{padding:10px;font-size:20px;}
#history li:nth-child(even){background:#EEE;}
</style>
<a href="/app/main/scan"><img src="/img/app/gprs-head.jpg" /></a>
<h2 style="padding:15px;font-size:20px">ICCID卡历史查询记录</h2>
<ul id="history"></ul>

<script type="text/javascript">

function selectHistory(obj)
{
	var iccid = $(obj).text();
	if (iccid)
	{
		$("input[name='iccid']").val(iccid);
		$("#history").hide();
	}
}

//加载搜索历史记录
function loadHistory()
{
	var historyData = localStorage.getItem('iccidHistory');
	if (!historyData)
	{
		return;
	}
	var historyArr = JSON.parse(historyData);
	var historyHtml = '';
	$.each(historyArr, function (i)
	{
		historyHtml += "<li onclick=\"goInfo('" + historyArr[i] + "')\">" + (i + 1) + '. ' + historyArr[i] + '</li>';
	});
	$('#history').html(historyHtml);
}
loadHistory();

	function goInfo(iccid)
	{
		self.location = '/app/main/info?iccid='+ iccid;
	}
</script>
<?php echo $page_footer; ?>