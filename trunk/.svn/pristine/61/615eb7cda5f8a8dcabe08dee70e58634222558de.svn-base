<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/report/public/DatePicker/WdatePicker.js"></script>
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
$status = array('1'=>'未发放','2'=>'已发放','3'=>'发放失败');
$grade_table = array('iwide_fans_sub_log'=>'按粉丝关注','iwide_hotels_order'=>'按酒店订单','iwide_shp_orders'=>'按商城订单');
//$saler = array('-1'=>'-1');
?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" style="min-height:973px;">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>信息列表            <small></small>
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
			<form name="form1" method="get" action=""> 条件：
	  绩效时间
      <input id="qingfeng1" name="timedown" readonly="1" type="text" value="<?php echo $condition['timedown'];?>" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'qingfeng2\')||\'2020-10-01\'}'})" style="width:100px" /> 
      <input id="qingfeng2" name="timeup" readonly="1" type="text" value="<?php echo $condition['timeup'];?>" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'qingfeng1\')}',maxDate:'2020-10-01'})" style="width:100px" />
	  
	  支付状态
	  <select name="paystatus">
	  	<option value="">所有</option>
	  <?php foreach($status as $k=>$v){
	  	echo '<option value="'.$k.'">'.$v.'</option>';
	  } ?>
      </select>
	  
	  分销类型
	  <select name="grade_table">
	  	<option value="">所有</option>
	  <?php foreach($grade_table as $k=>$v){
	  	echo '<option value="'.$k.'">'.$v.'</option>';
	  } ?>
      </select>
	  
	  <!--
	  saler
	  <select name="saler">
	  	<option value="">所有</option>
	  <?php foreach($saler as $k=>$v){
	  	echo '<option value="'.$k.'">'.$v.'</option>';
	  } ?>
      </select>-->
	  
	  ID
	  <input type="text" name="staff_id" value="<?php echo $condition['staff_id'];?>">
	  
	  分销号
	  <input type="text" name="saler" value="<?php echo $condition['saler'];?>">
	  
	  分销员
	  <input type="text" name="staff_name" value="<?php echo $condition['staff_name'];?>">
	  
	  酒店名
	  <input type="text" name="hotel_name" value="<?php echo $condition['hotel_name'];?>">
	  
	  <input type="submit" name="Submit" value="提交">
	  
				</form>
				</div>
				
		  <div id="data-grid_wrapper" class="dataTables_wrapper form-inline dt-bootstrap"><div class="row"><div class="col-sm-6"><div class="dataTables_length" id="data-grid_length"></div></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
		  <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable" role="grid" aria-describedby="data-grid_info">
			<thead><tr role="row">
				<?php foreach($attribute_items as $k=>$v){echo '';}/*此方法暂停使用功能不能满足*/?>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">ID</th>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">分销号</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">酒店名</th>
				
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">分销员</th>
                <th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">部门</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">电话</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">产品</th>
				
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">订单金额</th>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">绩效金额</th>
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">计算金额</th>
				
				<th width="5%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">状态</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">订单号1</th>
                <th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">订单号2</th>
				<th width="10%" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="">时间</th></tr>
			</thead>
			<tfoot><tr>
				<th rowspan="1" colspan="1">ID</th>
				<th rowspan="1" colspan="1">分销号</th>
				<th rowspan="1" colspan="1">酒店名</th>
				
				<th rowspan="1" colspan="1">分销员</th>
                <th rowspan="1" colspan="1">部门</th>
				<th rowspan="1" colspan="1">电话</th>				
				<th rowspan="1" colspan="1">产品</th>
				
				<th rowspan="1" colspan="1">订单金额</th>
				<th rowspan="1" colspan="1">绩效金额</th>
				<th rowspan="1" colspan="1">计算金额</th>
				
				<th rowspan="1" colspan="1">状态</th>
				<th rowspan="1" colspan="1">订单号1</th>
                <th rowspan="1" colspan="1">订单号2</th>
				<th rowspan="1" colspan="1">时间</th>
				</tr>
			</tfoot>
			
		  <tbody>
		  <?php foreach($datalist as $dl){ ?><tr id="28" role="row" class="odd">
			
			<td class="sorting_1"><?php echo $dl['id'];?></td>
			<td><?php echo $dl['saler'];?></td>
			<td><?php echo $dl['hotel_name'];?></td>
			
			<td><?php echo $dl['staff_name'];?></td>
            <td><?php
                        if($dl['saler']!=-1 && !empty($staff_info[$dl['saler']])){
                            echo $staff_info[$dl['saler']];
                        }
                   ?>
            </td>
			<td><?php echo $dl['cellphone'];?></td>
			<td><?php echo $dl['product'];?></td>
			
			<td><?php echo $dl['order_amount'];?></td>
			<td><?php echo $dl['grade_total'];?></td>
			<td><?php echo $dl['grade_amount'];?></td>
			
			<td><?php echo $status[$dl['status']];?></td>
			<td><?php echo $dl['order_id'];?></td>
              <td><?php echo $orderId_list[$dl['order_id']];?></td>
			<td><?php echo $dl['grade_time'];?></td>
			
			</tr>
		  <?php } ?>
			</tbody>
		  </table>
			
			<div id="data-grid_processing" class="dataTables_processing" style="display: none;">Processing...</div>
			</div></div><div class="row"><div class="col-sm-5">
			
			<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite"><?php echo $qfpage['item'];?>
			<br>结算     
			订单总数：<?php echo $count;?>      
			绩效总额：<?php echo $sum_grade_total;?>     
			<form name="qfexport" method="post" action="">
			  <input type="hidden" name="export" value="1" />
			  <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
			  <input type="submit" name="Submit" value="导出数据" /> 导出大量数据时由于花费时间比较长，请耐心等待！
			</form>
			</div>
			
			</div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate"><?php echo $qfpage['html'];?></div></div></div></div>
		</div><!-- /.box-body -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
/*$.get('/index.php/report/api',{},function(c){$('form').append('<input name="'+c.csrf.name+'" type="hidden" value="'+c.csrf.hash+'" />');},'json');*/
</script>
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
var paystatus = '<?php echo $condition['paystatus'];?>';
var grade_table = '<?php echo $condition['grade_table'];?>';
var saler = '<?php echo $condition['saler'];?>';

$('select[name=paystatus]').val(paystatus);
$('select[name=grade_table]').val(grade_table);
$('select[name=saler]').val(saler);

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
