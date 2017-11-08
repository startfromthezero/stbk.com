<?php echo $page_header; ?>
<style type="text/css">
#history{position:absolute;background-color: #FFF;z-index：10;display:none;width:94%;border:1px solid #DDD;top:55px;border-bottom:none; border-radius:0 0 2px 2px;-webkit-box-shadow:0 1px 3px rgba(0, 0, 0, 0.2);box-shadow:0 1px 3px rgba(0, 0, 0, 0.2);}
.history-item{border-bottom:1px solid #DDD;text-align:left;font-size:14px;word-break:break-all; padding:5px 7px;}
.htab{display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:flex;text-align:center;line-height:40px;border-bottom:1px solid #C0C0C0;}
a{color:#ccc}
.htab a{-webkit-box-flex:1;-moz-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;padding:10px;font-weight:600;}
.selected{border-bottom:2px solid #FF8000;color:#000;}
</style>
<div id="tabs" class="htab">
	<a href="#tab-year"><?php echo $text_pack_year; ?></a>
	<a href="#tab-month"><?php echo $text_pack_month; ?></a>
</div>
<table class="list">
	<tr>
		<td><?php echo $text_iccid; ?></td>
		<td style="text-align:center;padding:20px 5px;position:relative;">
			<?php if (!empty($iccid)) { ?>
			<?php echo $iccid; ?>
			<input type="hidden" name="iccid" value="<?php echo $iccid;?>" />
			<?php } else { ?>
			<input type="number" name="iccid" style="width:100%;" />
			<div id="history"></div>
			<?php } ?>
		</td>
		<td>
			<?php if (empty($iccid) && $isweixin) { ?>
			<button class="button btn-blue" id="scan"><?php echo $button_scan; ?></button>
			<?php } ?>
		</td>
	</tr>
</table>
<div id="tab-year" class="body">
	<?php if (!empty($items)) { ?>
	<table class="list">
		<?php foreach($items as $item) { ?>
		<?php if($item['allot_month'] == 1) { ?>
		<tr>
			<td><?php echo modules_funs::gpgsFormat($item['gprs_amount']); ?><br/><span class="help"><?php echo $item['gprs_memo']; ?></span></td>
			<td style="text-align:right;" onclick="orderPay(<?php echo $item['pack_id'];?>)"><span class="price-btn"><?php echo $this->currency->format($item['gprs_price']); ?></span></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
	<?php } else { ?>
	<div class="noresult"><?php echo $error_no_pack; ?></div>
	<?php } ?>
	<div id="resDiv"></div>
</div>
<div id="tab-month" class="body">
	<?php if (!empty($items)) { ?>
	<table class="list">
		<?php foreach($items as $item) { ?>
		<?php if($item['allot_month'] != 1) {	 ?>
		<tr>
			<td>
				<?php echo modules_funs::gpgsFormat($item['gprs_amount']),$arr_pack_time[$item['allot_month']]; ?>
				（每月<?php echo modules_funs::gpgsFormat($item['allot_value']); ?>）<br/>
				<span class="help"><?php echo $item['gprs_memo']; ?></span>
			</td>
			<td style="text-align:right;" onclick="orderPay(<?php echo $item['pack_id'];?>)"><span class="price-btn"><?php echo $this->currency->format($item['gprs_price']); ?></span></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</table>
	<?php } else { ?>
	<div class="noresult"><?php echo $error_no_pack; ?></div>
	<?php } ?>
	<div id="resDiv"></div>
</div>
<script type="text/javascript" src="/js/winpop.min.js"></script>
<script type="text/javascript">
$("input[name='iccid']").on('input', function ()
{
	if ($(this).val())
	{
		loadHistory()
	}
	else
	{
		$("#history").hide();
	}
});

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
		historyHtml += '<div class="history-item" onclick="selectHistory(this)">' + historyArr[i] + '</div>';
	});
	$('#history').html(historyHtml);
	$("#history").show();
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
$.fn.easyTabs = function ()
{
	var selector = this;
	this.each(function ()
	{
		var obj = $(this);
		$(obj.attr('href')).hide();
		$(obj).click(function ()
		{
			$(selector).removeClass('selected');
			$(selector).each(function (i, element)
			{
				$($(element).attr('href')).hide();
			});

			$(this).addClass('selected');
			$($(this).attr('href')).fadeIn();
			return false;
		});
	});

	$(this).show();
	$(this).first().click();
};
$('#tabs a').easyTabs();
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
			$("input[name='iccid']").attr('readonly', true);
		}
	});
});

/**
 * 微信支付
 */
function orderPay(pack_id)
{
	var iccid = $("input[name='iccid']").val();
	if (!iccid)
	{
		$.alert('<?php echo $error_iccid; ?>');
		return false;
	}

	$.post('/app/wxpay/order', {'iccid': iccid, 'pack_id': pack_id}, function (result)
	{
		if (result != '')
		{
			addHistory(iccid);
			WeixinJSBridge.invoke('getBrandWCPayRequest', JSON.parse(result), function (res)
			{
				if (res.err_msg == "get_brand_wcpay_request:ok")
				{
					$('#resDiv').html('<span class="success center"><?php echo $text_pay_success; ?></span>');
					setTimeout(function ()
					{
						$('#resDiv').html('<span class="success center" style="margin-left:auto"><?php echo $text_pay_success; ?></span>');
					}, 2000);
				}
				else
				{
					$('#resDiv').html('<span class="error center"><?php echo $error_pay; ?></span>');
				}
			});
		}
		else
		{
			$('#resDiv').html('<span class="error center"><?php echo $error_order; ?></span>');
		}
	});
}
</script>
<?php } else{ ?>
<script type="text/javascript">
/**
 * 支付宝支付
 */
function orderPay(pack_id)
{
	var iccid = $("input[name='iccid']").val();
	if (!iccid)
	{
		$.alert('<?php echo $error_iccid; ?>');
		return false;
	}
	location = '/app/alipay/order?iccid=' + iccid + '&pack_id=' + pack_id;
}
</script>
<?php } ?>
<?php echo $page_footer; ?>