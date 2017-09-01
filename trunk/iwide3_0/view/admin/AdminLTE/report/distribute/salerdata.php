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
		<div style="">
			<form name="form1" method="get" action="">
	  分销号
	  <input type="text" name="saler" value="<?php echo $condition['saler'];?>">
	  
	  姓名
	  <input type="text" name="name" value="<?php echo $condition['name'];?>">
	  <!--
	  通过时间
      <input id="qingfeng1" name="timedown" readonly="1" type="text" value="2016-02-17" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'qingfeng2\')||\'2020-10-01\'}'})" style="width:100px" /> 
      <input id="qingfeng2" name="timeup" readonly="1" type="text" value="2016-03-17" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'qingfeng1\')}',maxDate:'2020-10-01'})" style="width:100px" />-->
	  
	  性别
	  <select name="sex">
	  	<option value="">所有</option>
	    <option value="1">男</option>
		<option value="2">女</option>
	  </select>
	  
	  <Br>
	  所属酒店
	  <input type="text" name="hotel_name" value="<?php echo $condition['hotel_name'];?>">
	  
	  所属部门
	  <input type="text" name="master_dept" value="<?php echo $condition['master_dept'];?>">
	  
	  累积绩效
	  <input type="text" name="income" value="<?php //echo $condition['income'];?>">
	  <Br>
	 <!--粉丝数量
	  <select name="grade_table">
	  	<option value="">所有</option>
	  </select>-->
	  
	  电话
	  <input type="text" name="cellphone" value="<?php echo $condition['cellphone'];?>">
	  
	  <input type="submit" name="Submit" value="提交">
	  
				</form>
				</div>
		  <div id="data-grid_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="data-grid_length"></div></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
		  
		  <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead><tr role="row">
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">分销号</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">姓名</th>
				
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">性别</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">电话</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">所属酒店</th>
				
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">所属部门</th>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">累积绩效</th>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">待发放绩效</th>
			
				</tr>
			</thead>			
		  <tbody>
		  <?php foreach($saler_data['result'] as $v){ ?>
		  <tr role="row" class="odd">
			<td><?php echo $v['qrcode_id'];?></td>
			<td><?php echo $v['name'];?></td>
			
			<td><?php echo $v['sex'];?></td>
			<td><?php echo $v['cellphone'];?></td>
			<td><?php echo $v['hotel_name'];?></td>
			
			<td><?php echo $v['master_dept'];?></td>
			<td><?php //echo $v['income'];?></td>
			<td><?php //echo $v['income'];?></td>

		  </tr>
		  <?php } ?>
		  </tbody>
		  </table>
		
		  
			<div id="data-grid_processing" class="dataTables_processing" style="display: none;">Processing...</div>
			</div></div>
			
			<div class="row">
				<div class="col-sm-5">
					<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite"><?php echo $saler_data['qfpage']['html'];?>
			<br><br>
			<form name="qfexport" method="post" action="" style="display:none">
			  <input type="hidden" name="export" value="1" />
			  <input type="hidden" name="<?php //echo $csrf['name'];?>" value="<?php //echo $csrf['hash'];?>" />
			  <input type="submit" name="Submit" value="导出数据" /> 导出大量数据时由于花费时间比较长，请耐心等待！
			</form>
			</div>
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
