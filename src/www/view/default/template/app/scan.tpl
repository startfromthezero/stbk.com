<?php echo $page_header; ?>
<style>
#card-search{position:relative;}
#history{position:absolute;top:70px;left:25px;border-radius:4px;width:86%;background-color:#FFF;display:none;box-shadow:0 0 1px 1px rgba(0, 0, 0, 0.1);z-index:1;}
.history-item{position:relative;height:35px;line-height:35px;padding-left:38px;color:#333333;background:url('/view/default/image/input_sq.png') no-repeat 5px -123px;background-size:auto 200px;width:95%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;}
.search-history-clear{height:35px;line-height:35px;border-top:1px solid #dddddd;color:#666;overflow:hidden;}
#clear-history{text-align:center;margin-right:57px;border-right:1px solid #ddd;}
#down-button{float:right;width:57px;height:40px;background:url('/view/default/image/down_new.png') no-repeat center center;background-size:21px 7px;position:relative;top:-40px;}
#clear-button{width:30px;height:30px;position:absolute;right:20px;top:13px;background:url('/view/default/image/input_sq.png') no-repeat;background-size:auto 250px;display:none;}
</style>
<div class="body" style="background-color:#ebebeb;margin:0 auto;height:100%;">
	<div class="iccid_conten">
		<div style="overflow: hidden;" id="card-search">
			<input type='text' placeholder='<?php echo $text_input_iccid; ?>' name='iccid' id='iccid' />
			<div id="clear-button"></div>
		</div>
		<div id="history">
			<div id="history-card"></div>
			<div class="search-history-clear">
				<div id="clear-history"><?php echo $text_clear_iccid; ?></div>
				<div id="down-button" onclick="$('#history').hide()"></div>
			</div>
		</div>
		<div id="error-card">
			<p><?php echo $error_no_iccid; ?></p>
			<p><?php echo $error_iccid_digit; ?></p>
			<p><?php echo $error_company_iccid; ?></p>
		</div>
		<div class="buttons" onclick="query()">
			<a class="button iccid_look"><?php echo $button_query; ?></a>
		</div>
		<?php if ($isweixin) { ?>
		<div class="buttons iccid_mt" id="scan">
			<a class="button iccid_saoma"><?php echo $button_scan; ?></a>
		</div>
		<?php } ?>
	</div>
	<p  style="color:#93918f;margin-top:20px;font-weight: bold;text-align:center;}"><?php echo $text_notify; ?></p>
	<div class="iccid_sweep_card"><img src="/img/app/iccid_sweep_card.png" /></div>
	<p style="color:#93918f;margin:20px 0px;font-weight: bold;text-align:center;}"><?php echo $text_button_scan; ?></p>
</div>
<script type="text/javascript">
function query()
{
	var iccid = $("input[name='iccid']").val();
	if (!iccid)
	{
		$.alert('<?php echo $error_iccid; ?>');
		return false;
	}

	$.post('/app/main/scan', {'iccid': iccid}, function (result)
	{
		if (result == 'ok')
		{
			addHistory(iccid);
			location = '/app/main/info';
		}
		else
		{
			$('#error-card').css('display', 'block');
		}
	});
}

$('#card-search').on('click', function ()
{
	var stxt = $('#iccid').val();
	if (stxt != '')
	{
		$('#history').hide();
	}
	else
	{
		loadHistory();
	}
});

//触发获取焦点事件
$('#iccid').on('input', function ()
{
	if ($(this).val())
	{
		$('#history').hide();
		$("#clear-button").show();
	}
	else
	{
		$("#clear-button").hide();
		loadHistory();
	}
});

//清除输入的内容
$("#clear-button").on('click', function ()
{
	$('#iccid').val('');
	$(this).hide();
});

//清除全部历史记录
$('#clear-history').on('click', function ()
{
	$.confirm('<?php echo $text_clear_confirm; ?>', '<?php echo $text_yes; ?>', '<?php echo $text_no; ?>', function ()
	{
		localStorage.removeItem('iccidHistory');
		$('#history').hide();
	});
});

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
		historyHtml += "<div class='history-item' onclick=\"goInfo('" + historyArr[i] + "')\">" + historyArr[i] + '</div>';
	});
	$('#history-card').html(historyHtml);
	$('#history').show();
}

//选择历史记录到输入框
function goInfo(iccid)
{
	$('#iccid').val(iccid);
	$('#history').hide();
	$("#clear-button").show();
}

//存储历史记录ICCID
function addHistory(iccid)
{
	var historyData = localStorage.getItem('iccidHistory');
	var historyArr = historyData ? JSON.parse(historyData) : [];

	//判断该iccid是否已记录
	if ($.inArray(iccid, historyArr) > -1)
	{
		historyArr.splice($.inArray(iccid, historyArr), 1);
	}

	//历史记录最多为10条
	if (historyArr.length == 10)
	{
		historyArr.pop();
	}

	historyArr.unshift(iccid);
	localStorage.setItem('iccidHistory', JSON.stringify(historyArr));
}
</script>
<?php if ($isweixin) { ?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
wx.config({
	debug    : false,
	appId    : '<?php echo $jsapi['appid']; ?>',
	timestamp: '<?php echo $jsapi['timestamp']; ?>',
	nonceStr : '<?php echo $jsapi['noncestr']; ?>',
	signature: '<?php echo $jsapi['signature']; ?>',
	jsApiList: ['scanQRCode']
});

/**
 * 扫一扫获取ICCID
 */
$('#scan').on('click', function ()
{
	wx.scanQRCode({
		needResult: 1,
		scanType  : ["barCode"],
		success   : function (res)
		{
			var iccid = String(res.resultStr).substr(9, 19);
			$("input[name='iccid']").val(iccid);
			$(".ICCID_look").click();
		}
	});
});
</script>
<?php } ?>
<?php echo $page_footer; ?>