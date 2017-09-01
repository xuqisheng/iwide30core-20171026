<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>订房预订数据
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div class="row">
                	<div class="col-sm-12">
                	<?php echo form_open('hotel/hotel_report/booking_summary',array('class'=>'form-inline','id'=>'para_form'));?>
                	<div class="form-group">
                		<label>下单时间 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="btime" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($btime) ? '' : $btime?>">00:00
                		<label>至 </label>
                		<input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="etime" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($etime) ? '': $etime?>">23:59
                	</div>
                	<div class="btn-group">
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	</form>
                	</div>
                </div>
                <div>&nbsp;</div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="酒店名称: activate to sort column ascending">酒店名称</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="预订间数: activate to sort column ascending">预订间数</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="取消间数: activate to sort column ascending">取消间数</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="预付间数: activate to sort column ascending">预付间数</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="入住间数: activate to sort column ascending">入住间数</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="入住订单总额: activate to sort column ascending">入住订单总额</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="离店间数: activate to sort column ascending">离店间数</th>
             <th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="离店订单总额: activate to sort column ascending">离店订单总额</th>
                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php foreach ($res as $item):?>
	                    <tr>
		                    <td><?php echo $hotels[$item->hotel_id]?></td>
		                    <td><?=$item->total_count?></td>
		                    <td><?=$item->cancel_count?></td>
		                    <td><?=$item->prepay_count?></td>
		                    <td><?=$item->check_in_count?></td>
		                    <td><?=$item->check_in_amount?></td>
		                    <td><?=$item->check_out_count?></td>
		                    <td><?=$item->check_out_amount?></td>
	                    </tr>
	                    <?php endforeach;?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("hotel/hotel_report/ex_booking_summary/".$btime.'_'.$etime)?>">导出</a></div>
	                  </div>
	                  <div class="col-sm-7">
	                  	<div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
	                  		<ul class="pagination"><?php echo $pagination?></ul>
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


var buttons = $('<div class="btn-group"></div>');

var grid_sort= [[ , "" ]];

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];

$(".form_datetime").datepicker({format: 'yyyymmdd'});
$(document).ready(function() {
<?php 
// $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
// if( count($result['data'])<$num) 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
// else 
// 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>
});
</script>
</body>
</html>
