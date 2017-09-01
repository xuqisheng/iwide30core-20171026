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
		  
		  <table id="data-grid-select" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" aria-controls="data-grid" rowspan="1" aria-label="">
				<div style="text-indent:1rem">关键指标 <span style="float:right;cursor:pointer;color:#0000FF"></span></div>				</th>
			    <th width="5%" aria-controls="data-grid" rowspan="1" aria-label="">&nbsp;</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#FFF">
				<td>性别比例<Br>
				  男：女<!--：其它--><Br>
				  <?php echo $user_sex['sex1'][0]['count']+intval($user_sex['sex0'][0]['count']/2);?>：<?php echo $user_sex['sex2'][0]['count']+intval($user_sex['sex0'][0]['count']/2);?><!--：<?php echo $user_sex['sex0'][0]['count'];?>-->
				</td>
			    <td>新用户比例<Br>
			    新用户：老用户<br>
				<?php echo $user_old['old1'];?>：<?php echo $user_old['old2'];?>
				</td>
			  </tr>
			</tbody>
		  </table>
		  <br>
		  <table id="data-grid1" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">
				<div style="text-indent:1rem">性别比例</div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#FFF">
				<td>
				<div id="main" style="height:100%;width:100%"></div>
				</td>
			  </tr>
			</tbody>
		  </table>
		  
		  <br>
		  <table id="data-grid1" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead>
			  <tr role="row" class="odd" style="background-color:#f9f9f9;">
				<th width="5%" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">
				<div style="text-indent:1rem">新用户比例</div>
				</th>
			  </tr>
			</thead>
			<tbody>
			  <tr role="row" style="background:#FFF">
				<td>
				<div id="main2" style="height:100%;width:100%"></div>
				</td>
			  </tr>
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
// 使用
require(
	[
		'echarts',
		'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
	],
	function (ec) {
		// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main')); 		
		var option = {
			title : {
				text: '性别比例',
				subtext: 'iwide',
				x:'center'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			legend: {
				orient : 'vertical',
				x : 'left',
				data:["男","女"]			},
			toolbox: {
				show : true,
				feature : {
					mark : {show: false},
					dataView : {show: false, readOnly: false},
					magicType : {
						show: false, 
						type: ['pie', 'funnel'],
						option: {
							funnel: {
								x: '25%',
								width: '50%',
								funnelAlign: 'left',
								max: 1548
							}
						}
					},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			series : [
				{
					name:'访问来源',
					type:'pie',
					radius : '55%',
					center: ['50%', '60%'],
					data:[{"value":"<?php echo $user_sex['sex1'][0]['count']+intval($user_sex['sex0'][0]['count']/2);?>","name":"男"},{"value":"<?php echo $user_sex['sex2'][0]['count']+intval($user_sex['sex0'][0]['count']/2);?>","name":"女"}]				}
			]
		};					   
		myChart.setOption(option); 
	}
);

require(
	[
		'echarts',
		'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
	],
	function (ec) {
		// 基于准备好的dom，初始化echarts图表
		var myChart = ec.init(document.getElementById('main2')); 		
		var option = {
			title : {
				text: '新用户比例',
				subtext: 'iwide',
				x:'center'
			},
			tooltip : {
				trigger: 'item',
				formatter: "{a} <br/>{b} : {c} ({d}%)"
			},
			legend: {
				orient : 'vertical',
				x : 'left',
				data:["新用户","老用户"]			},
			toolbox: {
				show : true,
				feature : {
					mark : {show: false},
					dataView : {show: false, readOnly: false},
					magicType : {
						show: false, 
						type: ['pie', 'funnel'],
						option: {
							funnel: {
								x: '25%',
								width: '50%',
								funnelAlign: 'left',
								max: 1548
							}
						}
					},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
			calculable : true,
			series : [
				{
					name:'访问来源',
					type:'pie',
					radius : '55%',
					center: ['50%', '60%'],
					data:[{"value":"<?php echo $user_old['old1'];?>","name":"新用户"},{"value":"<?php echo $user_old['old2'];?>","name":"老用户"}]				}
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
