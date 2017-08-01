<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'header.php';
?>
<body>
<table align="center" cellpadding="0" cellspacing="0" width="800" style="height:30px; border:#CCCCCC solid 1px; margin-bottom:10px">
  <tr>
    <td>&nbsp;&nbsp;<a href="/index.php/chat/superform/suform">返回上一页</a></td>
  </tr>
</table><form name="form1" method="get" action=""><input type="hidden" name="id" value="<?php echo isset($_GET['iad'])?$_GET['iad']:'';?>" />
<table class="showhotel" align="center" cellpadding="0" cellspacing="0" width="800">
  <tr><?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
    <td>选择时间 <input id="qingfeng1" name="timedown" type="text" value="" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'qingfeng2\')||\'2020-10-01\'}'})" style="width:70px" /> 
      <input id="qingfeng2" name="timeup" type="text" value="" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'qingfeng1\')}',maxDate:'2020-10-01'})" style="width:70px" />
      <input type="submit" name="Submit" value="提交"></td>
  </tr>
  <tr>
    <td><div style="width:800px; overflow:hidden"><div id="main" style="height:400px; width:910px; margin-left:-50px; margin-top:-40px; margin-bottom:-25px"></div></div>
<script type="text/javascript">
// 使用
require(
	[
		'echarts',
		'echarts/chart/line' //使用柱状图就加载bar模块，按需加载
	],
	function (ec) {
		// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main')); 		
		var option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['表单提交统计']
			},
			toolbox: {
				show : true,
				feature : {
					mark : {show: false},
					dataView : {show: false, readOnly: false},
					magicType : {show: false, type: ['line', 'bar', 'stack', 'tiled']},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : <?php echo $adddate;?>
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:'表单提交统计',
					type:'line',
					stack: '总量',
					data:<?php echo $count;?>
				}
			]
		};
		myChart.setOption(option); 
	}
);
</script>
</td>
  </tr>
  <tr>
    <td><div style="font-size:18px; margin-left:10px; padding:20px 0">表单提交统计 今日提交总数 <span style="color:#FF0000"><?php echo $counttoday;?></span> 昨日提交总数 <span style="color:#FF0000"><?php echo $countyesterday;?></span> 提交总计 <span style="color:#FF0000"><?php echo $countall;?></span> 次</div></td>
  </tr>
</table>
</form>
<table cellpadding="">
<tr>
<td style="width:250px"></td>
</tr>
</table>
</body>
</html>