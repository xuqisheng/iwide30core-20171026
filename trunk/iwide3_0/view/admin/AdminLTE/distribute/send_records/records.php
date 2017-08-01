<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
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
          <h1>发放记录
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
                <div class="box-body">
                	<div class="row">
	                	<div class="col-md-12">
	                		<?php echo form_open(site_url('distribute/send_records/records/'),'class="form-inline"')?>
	                			<div class="form-group">
	                				<label>发放编号</label>
	                				<input type="text" name="order_id" class="form-control" value="<?php echo $order_no?>" />
	                			</div>
	                			<div class="form-group">
	                				<label>发放时间</label>
	                				<input type="text" name="from_date" class="form-control" value="<?php echo $from?>" />
	                				<label>至</label>
	                				<input type="text" name="to_date" class="form-control" value="<?php echo $to?>" />
	                			</div>
	                			<!-- <div class="form-group">
	                				<label>发放状态</label>
	                				<select name="status" class="form-control">
	                					<option value="1">成功</option>
	                					<option value="2">失败</option>
	                					<option value="3">部分成功</option>
	                				</select>
	                			</div> -->
	                			<div class="form-group">
	                			<input type="submit" class="btn btn-default" name="btn_search" value="查询" />
	                			</div>
	                			<div class="form-group">
	                			<a href="<?php echo site_url('distribute/send_records/ext_records/'.$order_no.'_'.$from.'_'.$to);?>" class="btn btn-default">导出当前</a>
	                			</div>
	                		</form>
	                	</div>
                	</div>
                	<div class="row">
	                	<div class="col-md-12">&nbsp;</div>
	                </div>
                	<div class="row">
	                	<div class="col-md-12">
		                	<table id="data-grid" class="table table-bordered table-striped table-condensed">
		                    <thead>
		                    	<tr role="row">
		                    		<th width="10%" class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="发放编号: activate to sort column ascending">发放编号</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放时间: activate to sort column ascending">发放时间</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="核定截止: activate to sort column ascending">核定截止</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放人数: activate to sort column ascending">发放人数</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放笔数: activate to sort column ascending">发放笔数</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放总金额: activate to sort column ascending">发放总金额</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放状态: activate to sort column ascending">发放状态</th>
		                    		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作: activate to sort column ascending">操作</th>
		                    		</thead>
		                    
		                    <tfoot></tfoot>
		                    <tbody><?php foreach ($res as $msg):?>
		                    	<tr>
		                    		<td><?=$msg->batch_no?></td>
		                    		<td><?=$msg->send_time?></td>
		                    		<td><?=date('Y-m-d 23:59:59',strtotime('-1 day',strtotime($msg->send_time)))?></td>
		                    		<td><?=$msg->times?></td>
		                    		<td><?=$msg->times?></td>
		                    		<td><?php echo $msg->amount/100?></td>
		                    		<td><?php if($msg->sts == 1 && $msg->status == 1):echo '成功';elseif($msg->sts == 1 && $msg->status == 2):echo '全部失败';else: echo '部分失败';endif;?></td>
		                    		<td><a href="<?php echo site_url('distribute/send_records/sub_records?bn='.$msg->batch_no)?>">详情</a></td>
		                    	</tr><?php endforeach;?>
		                    </tbody>
		                  </table>
	                  </div>
                  </div>
                  <div class="row">
                  <div class="col-sm-5">
                  <!-- <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">当前显示第1 / 1页，记录从 1 到 4 ，共 4 条</div> -->
                  </div>
                  <div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate"><ul class="pagination"><?php echo $pagination?></ul></div></div></div>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

$(document).ready(function() {
	$("input[name=from_date]").datepicker({format: 'yyyy-mm-dd'});
	$("input[name=to_date]").datepicker({format: 'yyyy-mm-dd'});
});
</script>
</body>
</html>
