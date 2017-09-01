<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/report/public/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>public/report/public/echarts/echarts.js"></script>
<script type="text/javascript">
require.config({
paths: {
	echarts: '<?php echo base_url() ?>public/report/public/echarts/build/dist'
}
});
</script>
<style type="text/css">
#data-grid-select td{padding:2rem;}
#data-grid thead th{height:2rem;}
#data-grid tbody td{text-align:center; font-size:1.5rem; font-weight:bolder}
#data-grid1 thead th{height:2rem}
#data-grid1 tbody td{height:30rem;} 
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
echo $block_left;
?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height:973px;">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>统计信息            <small></small>
  </h1>
  <ol class="breadcrumb"></ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
	<div class="col-xs-12">
	  <div class="box">
					  <!-- 
		<div class="box-header">
		  <h3 class="box-title">Data Table With Full Features</h3>
		</div><!-- /.box-header -->
		<div class="box-body">	
		  <div id="data-grid_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="data-grid_length"></div></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
		  
		  <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" colspan="5" aria-controls="data-grid" rowspan="1" aria-label="">
				<div style="text-indent:1rem">今日实时（最新更新时间<?php echo date('Y-m-d H:i:s');?>）含关注时，绩效金额大于订单金额<span style="float:right;cursor:pointer;color:#0000FF" onClick="location.href=''"><!--历史数据--></span></div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#FFF">
				<!--<td>4334
				<br>可分销商品
				</td>-->
				<td><?php echo $all_order[0]['count'];?>
				<br>订单总数
				</td>
				<td><?php echo $all_order_grade_yes[0]['count'];?>
				<br>绩效订单
				</td>
				<td><?php echo $all_order[0]['count']-$all_order_grade_yes[0]['count'];?>
				<br>无绩效订单
				</td>
				<td><?php echo $all_order_grade_sum[0]['sum'];?>
				<br>绩效金额
				</td>
				<td><?php echo $all_order_sum[0]['sum'];?>
				<br>订单金额
				</td>
			  </tr>
			</tbody>
		  </table>
		  <br>
		  <table id="data-grid1" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">
				<div style="text-indent:1rem">实时订单</div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#FFF">
				<td>
				<div id="main_line" style="height:100%;width:100%"></div>
				</td>
			  </tr>
			</tbody>
		  </table>
		  <br>
		  <table id="data-grid2" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" colspan="9" aria-controls="data-grid" rowspan="1" aria-label="">
				<div style="text-indent:1rem">分销商品排行（按商品销售金额）<span style="float:right;cursor:pointer;color:#0000FF" onClick="location.href='old';"></span></div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#ccc">
				<td>
				排名
				</td>
				<td>
				商品名称
				</td>
				<td>
				价格
				</td>
				<td>
				已售
				</td>
				<td>
				分销人数
				</td>
				<td>
				绩效金额
				</td>
				<td>
				分销转化率
				</td>
				<td>
				订单金额
				</td>
			  </tr>
			  <?php $i=0;foreach($dis_amount_count as $v){$i+=1; ?>
			  <tr role="row" style="background:#FFF">
			  	<td>
				<?php echo $i;?>
				</td>
				<td>
				<?php echo $v['product'];?>
				</td>
				<td>
				<?php echo $v['grade_amount'];?>
				</td>
				<td>
				<?php echo $v['count'];?>
				</td>
				<td>
				<?php echo $v['count_saler'][0]['count'];?>
				</td>
				<td>
				<?php echo $v['all_grade_total'];?>
				</td>
				<td>
				<?php 
				if($v['all_grade_total']>$v['all_order_amount']){
					echo intval($v['all_order_amount']*100/$v['all_grade_total']);
				}
				else {
					echo intval($v['all_grade_total']*100/$v['all_order_amount']);
				} 
				?> %
				</td>
				<td>
				<?php echo $v['all_order_amount'];?>
				</td>
			  </tr>
			  <?php } ?>
			</tbody>
		  </table>		
		  
		  <br>
		  <table id="data-grid2" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" colspan="9" aria-controls="data-grid" rowspan="1" aria-label="">
				<div style="text-indent:1rem">热销商品排行（按商品销量）<span style="float:right;cursor:pointer;color:#0000FF" onClick="location.href='old';"></span></div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#ccc">
				<td>
				排名
				</td>
				<td>
				商品名称
				</td>
				<td>
				价格
				</td>
				<td>
				已售
				</td>
				<td>
				分销人数
				</td>
				<td>
				绩效金额
				</td>
				<td>
				分销转化率
				</td>
				<td>
				订单金额
				</td>
			  </tr>
			  <?php $i=0;foreach($dis_amount_count as $v){$i+=1; ?>
			  <tr role="row" style="background:#FFF">
			  	<td>
				<?php echo $i;?>
				</td>
				<td>
				<?php echo $v['product'];?>
				</td>
				<td>
				<?php echo $v['grade_amount'];?>
				</td>
				<td>
				<?php echo $v['count'];?>
				</td>
				<td>
				<?php echo $v['count_saler'][0]['count'];?>
				</td>
				<td>
				<?php echo $v['all_grade_total'];?>
				</td>
				<td>
				<?php 
				if($v['all_grade_total']>$v['all_order_amount']){
					echo intval($v['all_order_amount']*10000/$v['all_grade_total'])/100;
				}
				else {
					echo intval($v['all_grade_total']*10000/$v['all_order_amount'])/100;
				} 
				?> %
				</td>
				<td>
				<?php echo $v['all_order_amount'];?>
				</td>
			  </tr>
			  <?php } ?>
			</tbody>
		  </table>	  
		  
			<div id="data-grid_processing" class="dataTables_processing" style="display: none;">Processing...</div>
			</div></div>
			
			<div class="row">
				<div class="col-sm-5">
					<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite"></div>
				</div>
				<div class="col-sm-7">
					<div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate"></div>
				</div>
			</div>
			</div>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div><!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>


<script type="text/javascript">
require(
	[
		'echarts',
		'echarts/chart/line'
	],
	function (ec) {
		// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main_line')); 		
		var option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['订单数']
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
					data : ["0:00","1:00","2:00","3:00","4:00","5:00","6:00","7:00","8:00","9:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"]			
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:'订单数',
					type:'line',
					stack: '总量',
					data:<?php echo json_encode($order_time);?>	
				}
			]
		};
		myChart.setOption(option); 
	}
);
</script>

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;新增</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
if(isset($js_filter_btn)) $buttions.= $js_filter_btn;
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

//var dataSet= <?php //echo json_encode($result['data']); ?>;
//var columnSet= <?php //echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
<?php 
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;

//如 view/mall/gridjs.php 存在，则会覆盖 view/privilege/gridjs.php，个性化的部分请拷贝到模块内修改
?>
});
</script>
</body>
</html>
