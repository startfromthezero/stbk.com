<?php echo $page_header; ?>
<div id="content">
	<?php if ($error_logs) { ?>
	<div class="warning"><?php echo $error_logs; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="/view/image/home.png" /> <?php echo $heading_title; ?></h1>
		</div>
		<div class="content">
			<div class="latest">
				<table class="list">
					<thead>
					<tr>
						<td width="8.3%"><?php echo $text_org_name; ?></td>
						<td>
							<?php if ($sort == 'card_count') { ?>
							<a href="<?php echo $sort_card_count; ?>"><?php echo $text_sell_num; ?>
								<i class="<?php echo strtolower($order); ?>"></i>
							</a>
							<?php } else { ?>
							<a href="<?php echo $sort_card_count; ?>"><?php echo $text_sell_num; ?>
								<i class="harrow"></i>
							</a>
							<?php } ?>
						</td>
						<td><?php echo $text_stop_num; ?></td>
						<td><?php echo $text_renewal_num; ?></td>
						<td><?php echo $text_rate; ?></td>
						<td>
							<?php if ($sort == 'activated') { ?>
							<a href="<?php echo $sort_activated; ?>"><?php echo $text_act_num; ?>
								<i class="<?php echo strtolower($order); ?>"></i>
							</a>
							<?php } else { ?>
							<a href="<?php echo $sort_activated; ?>"><?php echo $text_act_num; ?>
								<i class="harrow"></i>
							</a>
							<?php } ?>
						</td>
						<td><?php echo $text_act_rate; ?></td>
						<td>
							<?php if ($sort == 'nonactivated') { ?>
							<a href="<?php echo $sort_nonactivated; ?>"><?php echo $text_not_act_num; ?>
								<i class="<?php echo strtolower($order); ?>"></i>
							</a>
							<?php } else { ?>
							<a href="<?php echo $sort_nonactivated; ?>"><?php echo $text_not_act_num; ?>
								<i class="harrow"></i>
							</a>
							<?php } ?>
						</td>
						<td><?php echo $text_not_act_rate; ?></td>
						<td><?php echo $text_paid_log; ?></td>
						<td><?php echo $text_nopaid_log; ?></td>
						<td><?php echo $text_paid_total; ?></td>
						<td><?php echo $text_nopaid_total; ?></td>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($card_used) && !empty($renewal)) { ?>
					<?php foreach ($card_used as $k =>$v) { ?>
					<tr>
						<td><?php echo isset($orgs[$k]) ? $orgs[$k] : ''; ?></td>
						<td><a href="/gprs/card?orgId=<?php echo $k; ?>"><?php echo $v['card_count'];$total['card_count'] += $v['card_count'];  ?></a></td>
						<td><a href="/gprs/halt?orgId=<?php echo $k; ?>"><?php echo $v['unicom_stop'];$total['unicom_stop']+=$v['unicom_stop']; ?></a></td>
						<td><a href="/gprs/sell2pay?org_id=<?php echo $k; ?>"><?php echo $renewal[$k] = isset($renewal[$k]) ? $renewal[$k] : 0;$total['renewal']+=$renewal[$k]; ?></a></td>
						<td><?php echo (round((isset($renewal[$k]) ? $renewal[$k] : 0)/$v['card_count']*100,2)).'%'; ?></td>
						<td><a href="/gprs/card_used?org_id=<?php echo $k; ?>"><?php echo $v['activated'];$total['activated']+=$v['activated'];  ?></a></td>
						<td><?php echo round($v['activated']/$v['card_count']*100,2).'%'; ?></td>
						<td><?php echo $v['nonactivated'];$total['nonactivated']+=$v['nonactivated']; ?></td>
						<td><?php echo round($v['nonactivated']/$v['card_count']*100,2).'%'; ?></td>
						<td><a href="/gprs/pay_report?org_id=<?php echo $k; ?>"><?php echo $items[$k]['paid_amount'] = isset($items[$k]['paid_amount']) ? $items[$k]['paid_amount'] : 0;$total['paid_amount']+= $items[$k]['paid_amount']; ?></a></td>
						<td><?php echo $items[$k]['nopaid_amount'] = isset($items[$k]['nopaid_amount']) ? $items[$k]['nopaid_amount'] : 0;$total['nopaid_amount']+= $items[$k]['nopaid_amount']; ?></a></td>
						<td><?php echo $this->currency->format($items[$k]['paid_total'] = isset($items[$k]['paid_total']) ? $items[$k]['paid_total'] : 0);$total['paid_total']+=
							$items[$k]['paid_total']; ?></td>
						<td><?php echo $this->currency->format($items[$k]['nopaid_total'] = isset($items[$k]['nopaid_total']) ? $items[$k]['nopaid_total'] : 0);$total['nopaid_total']+=
							$items[$k]['nopaid_total']; ?></td>
					</tr>
					<?php } ?>
					<tr style="color:#F00;font-weight:bold;">
						<td><?php echo $text_total; ?></td>
						<td><?php echo $total['card_count']; ?></td>
						<td><?php echo $total['unicom_stop']; ?></td>
						<td><?php echo $total['renewal']; ?></td>
						<td><?php echo (round($total['renewal']/$total['card_count']*100,2)).'%'; ?></td>
						<td><?php echo $total['activated']; ?></td>
						<td><?php echo round($total['activated']/$total['card_count']*100,2).'%'; ?></td>
						<td><?php echo $total['nonactivated']; ?></td>
						<td><?php echo round($total['nonactivated']/$total['card_count']*100,2).'%'; ?></td>
						<td><?php echo $total['paid_amount']; ?></td>
						<td><?php echo $total['nopaid_amount']; ?></td>
						<td><?php echo $this->currency->format($total['paid_total']); ?></td>
						<td><?php echo $this->currency->format($total['nopaid_total']); ?></td>
					</tr>
					<?php } else { ?>
					<tr>
						<td class="center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="chart" id="main1" style='min-height:300px;width:33%;display:inline-block;padding:10px;'></div>
			<div class="chart" id="main2" style="min-height:300px;width:33%;display:inline-block;padding:10px;"></div>
			<div class="chart" id="main3" style="min-height:300px;width:33%;display:inline-block;padding:10px;"></div>
		</div>
	</div>
</div>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script type="text/javascript">
function fitHeigt(offsetheight)
{
	if (offsetheight > 5)
	{
		var height = 300 + (offsetheight - 5) * 30 + 'px';
		$('.chart').css('height', height);
	}
}
fitHeigt('<?php echo $count; ?>');
// 路径配置
require.config({
	paths: {
		echarts: 'http://echarts.baidu.com/build/dist'
	}
});

// 使用
require(
[
	'echarts',
	'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
],
drawCharts
);
function drawCharts(ec)
{
	funDraw1(ec);
	funDraw2(ec);
	funDraw3(ec);
}
function funDraw1(ec)
{
			// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main1'));

		var zrColor = require('zrender/tool/color');
		var colorList = <?php echo $color; ?>;
		var itemStyle = {
			normal: {
				color: function (params)
				{
					if (params.dataIndex < 0)
					{
						// for legend
						return zrColor.lift(
								colorList[colorList.length - 1], params.seriesIndex * 0.1
						);
					}
					else
					{
						// for bar
						return zrColor.lift(
								colorList[params.dataIndex], params.seriesIndex * 0.1
						);
					}
				}
			}
		};
		var option = {
			title     : {
				text   : '<?php echo $text_org_paylog; ?>',
				x	   : 'center'
			},
			tooltip   : {
				trigger        : 'axis',
				backgroundColor: 'rgba(255,255,255,0.7)',
				axisPointer    : {
					type: 'shadow'
				},
				formatter      : function (params)
				{
					// for text color
					var color = '#FF0000';
					var res = '<div style="color:' + color + '">';
					res += '<strong>' + params[0].name + '</strong>'
					for (var i = 0, l = params.length; i < l; i++)
					{
						res += '<br/>' + params[i].seriesName + ' : ' + params[i].value
					}
					res += '</div>';
					return res;
				}
			},
			toolbox   : {
				show   : true,
				orient : 'vertical',
				y      : 'center',
				feature: {
					mark       : {show: true},
					dataView   : {show: true, readOnly: false},
					restore    : {show: true},
					saveAsImage: {show: true}
				}
			},
			calculable: true,
			grid      : {
				y : 80,
				y2: 40,
				x2: 40
			},
			xAxis     : [
				{
					type: 'value',
					name: '次数'
				}
			],
			yAxis     : [
				{
					type: 'category',
                    data: <?php echo $org; ?>
				}
			],
			series    : [
				{
					name     : '充值次数',
					type     : 'bar',
					itemStyle: itemStyle,
					data     : <?php echo $data; ?>,
				}
			]
		};
		myChart.setOption(option,true);
}
function funDraw2(ec)
{
			// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main2'));

		var zrColor = require('zrender/tool/color');
		var colorList = <?php echo $color; ?>;
		var itemStyle = {
			normal: {
				color: function (params)
				{
					if (params.dataIndex < 0)
					{
						// for legend
						return zrColor.lift(
								colorList[colorList.length - 1], params.seriesIndex * 0.1
						);
					}
					else
					{
						// for bar
						return zrColor.lift(
								colorList[params.dataIndex], params.seriesIndex * 0.1
						);
					}
				}
			}
		};
		var option = {
			title     : {
				text   : '<?php echo $text_org_gprs; ?>',
				x	   : 'center'
			},
			tooltip   : {
				trigger        : 'axis',
				backgroundColor: 'rgba(255,255,255,0.7)',
				axisPointer    : {
					type: 'shadow'
				},
				formatter      : function (params)
				{
					// for text color
					var color = '#FF0000';
					var res = '<div style="color:' + color + '">';
					res += '<strong>' + params[0].name + '</strong>'
					for (var i = 0, l = params.length; i < l; i++)
					{
						res += '<br/>' + params[i].seriesName + ' : ' + gpgsFormat(params[i].value)
					}
					res += '</div>';
					return res;
				}
			},
			toolbox   : {
				show   : true,
				orient : 'vertical',
				y      : 'center',
				feature: {
					mark       : {show: true},
					dataView   : {show: true, readOnly: false},
					restore    : {show: true},
					saveAsImage: {show: true}
				}
			},
			calculable: true,
			grid      : {
				y : 80,
				y2: 40,
				x2: 40
			},
			xAxis     : [
				{
					type: 'value',
					name: '流量'
				}
			],
			yAxis     : [
				{
					type: 'category',
					data: <?php echo $org; ?>
				}
			],
			series    : [
				{
					name     : '总流量',
					type     : 'bar',
					itemStyle: itemStyle,
					data     : <?php echo $data1; ?>
				}
			]
		};
		myChart.setOption(option,true);
}

function funDraw3(ec)
{
			// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main3'));

		var zrColor = require('zrender/tool/color');
		var colorList = <?php echo $color; ?>;
		var itemStyle = {
			normal: {
				color: function (params)
				{
					if (params.dataIndex < 0)
					{
						// for legend
						return zrColor.lift(
								colorList[colorList.length - 1], params.seriesIndex * 0.1
						);
					}
					else
					{
						// for bar
						return zrColor.lift(
								colorList[params.dataIndex], params.seriesIndex * 0.1
						);
					}
				}
			}
		};
		var option = {
			title     : {
				text   : '<?php echo $text_org_price; ?>',
				x: 'center'
			},
			tooltip   : {
				trigger        : 'axis',
				backgroundColor: 'rgba(255,255,255,0.7)',
				axisPointer    : {
					type: 'shadow'
				},
				formatter      : function (params)
				{
					// for text color
					var color = '#FF0000';
					var res = '<div style="color:' + color + '">';
					res += '<strong>' + params[0].name + '</strong>'
					for (var i = 0, l = params.length; i < l; i++)
					{
						res += '<br/>' + params[i].seriesName + ' : ' + params[i].value+'元'
					}
					res += '</div>';
					return res;
				}
			},
			toolbox   : {
				show   : true,
				orient : 'vertical',
				y      : 'center',
				feature: {
					mark       : {show: true},
					dataView   : {show: true, readOnly: false},
					restore    : {show: true},
					saveAsImage: {show: true}
				}
			},
			calculable: true,
			grid      : {
				y : 80,
				y2: 40,
				x2: 40
			},
			xAxis     : [
				{
					type: 'value',
					name: '金额',
				}
			],
			yAxis     : [
				{
					type: 'category',
					data: <?php echo $org; ?>
				}
			],
			series    : [
				{
					name     : '总金额',
					type     : 'bar',
					itemStyle: itemStyle,
					data     : <?php echo $data2; ?>
				}
			]
		};
		myChart.setOption(option,true);
}
</script>
<?php echo $page_footer; ?>