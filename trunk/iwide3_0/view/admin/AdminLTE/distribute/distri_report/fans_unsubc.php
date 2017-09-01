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
<style>
<!--
form>div{margin:.5rem auto;}
-->
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
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>门店粉丝取消情况汇总
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
                	<?php echo form_open('distribute/distri_report/hotel_unsubc_fans/','class="form-inline"')?>
                	<div class="form-group">
                		<label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_name?>">
                	</div>
                	<div class="form-group">
                		<label>分销号</label> <input type="text" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_no?>">
                	</div>
                	<div class="form-group">
                		<label>分销员所属酒店</label> <select name="hotel_id" class="form-control input-sm"><option value="ALL"> --全部-- </option><?php foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"><?php echo $val?></option><?php endforeach;?></select>
                		
                	</div>
                	<div class="form-group">
                		<label>绩效时间</label>
                		<input type="datetime" name="btime" class="form-control input-sm" placeholder="起始时间" aria-controls="data-grid" value="<?php echo $btime?>">
                		<input type="datetime" name="etime" class="form-control input-sm" placeholder="结束时间" aria-controls="data-grid" value="<?php echo $etime?>">
                	</div>
                	<div class="btn-group">
                		<button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                	</div>
                	</form>
                	</div>
                </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                    		<tr role="row">
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="所属酒店: activate to sort column ascending">所属酒店</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="微信粉丝名: activate to sort column ascending">微信粉丝名</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="微信会员号: activate to sort column ascending">微信会员号</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝取消时间: activate to sort column ascending">粉丝取消时间</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="所属分销员姓名: activate to sort column ascending">所属分销员姓名</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="所属分销员分销号: activate to sort column ascending">所属分销员分销号</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="粉丝绩效规则: activate to sort column ascending">粉丝绩效规则</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员绩效: activate to sort column ascending">分销员绩效</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="绩效发放时间: activate to sort column ascending">绩效发放时间</th>
                              	<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放状态: activate to sort column ascending">发放状态</th>
		                    </tr>
                    <tfoot></tfoot>
                    <tbody>
                    <?php foreach ($res as $item):?>
                    	<tr>
                    		<td><?php echo isset($hotels[$item->hotel_id]) ? $hotels[$item->hotel_id] : '--'?></td>
                    		<td><?=$item->nickname?></td>
                    		<td><?=$item->mem_card_no?></td>
                    		<td><?=$item->unsubcribe_time?></td>
                    		<td><?=$item->staff_name?></td>
                    		<td><?=$item->saler?></td>
                    		<td>粉丝关注奖励固定金额</td>
                    		<td><?=$item->grade_total?></td>
                    		<td><?=$item->send_time?></td>
                    		<td><?php echo $item->status == 2 ? '已发放' : '未发放'?></td>
                    	</tr>
                    <?php endforeach;?>
                    </tbody>
                  </table>
                  
                  <div class="row">
	                  <div class="col-sm-5">
	                  	<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/distri_report/ext_hotel_unsubc_fans/".$saler_no.'_'.$saler_name.'_'.$hotel_id.'_'.$btime.'_'.$etime)?>">导出</a></div>
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

var url_extra= [
//'http://iwide.cn/',
];
var baseStr = "";
$("input[name=btime]").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});
$("input[name=etime]").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
});

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
