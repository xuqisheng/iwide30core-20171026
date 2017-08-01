<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
	                	<?php echo form_open(site_url('distribute/send_records/sub_records/').'?bn='.$batch_no.'&s='.$source,'class="form-inline"')?>
	                			<div class="form-group">
	                				<label>订单号</label>
	                				<input type="text" name="order_id" class="form-control" value="<?php echo $order_no?>" />
	                			</div>
	                			<div class="form-group">
	                				<label>分销号</label>
	                				<input type="text" name="saler_no" class="form-control" value="<?php echo $saler_no?>" />
	                			</div>
	                			<div class="form-group">
	                				<label>分销员</label>
	                				<input type="text" name="saler_name" class="form-control" value="<?php echo $saler_name?>" />
	                			</div>
	                			<div class="form-group">
	                			<input type="submit" class="btn btn-default" name="btn_search" value="查询" />
	                			</div>
	                			<div class="form-group">
	                			<a href="<?php echo site_url('distribute/send_records/ext_sub_records/'.$order_no.'_'.$saler_no.'_'.$saler_name.'_'.$msg_typ.'_'.$batch_no.'?s='.$source);?>" class="btn btn-default">导出当前</a>
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
		                    		<th class="sorting_desc" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-sort="descending" aria-label="订单号: activate to sort column ascending">订单号</th>
		<!--                     		<th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="子订单号: activate to sort column ascending">子订单号</th> -->
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="商品名称: activate to sort column ascending">商品名称</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="间夜: activate to sort column ascending">间夜</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="订单酒店: activate to sort column ascending">订单酒店</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="实际金额: activate to sort column ascending">实际金额</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="核定时间: activate to sort column ascending">核定时间</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="核发状态: activate to sort column ascending">核发状态</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销号: activate to sort column ascending">分销号</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销员: activate to sort column ascending">分销员</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="所属酒店: activate to sort column ascending">所属酒店</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="返佣金额: activate to sort column ascending">返佣金额</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放单号: activate to sort column ascending">发放单号</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="发放方式: activate to sort column ascending">发放方式</th>
		                    		<th class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="返佣金额: activate to sort column ascending">备注</th>
		                    		</thead>
		                    
		                    <tfoot></tfoot>
		                    <tbody><?php foreach ($res as $msg):?>
		                    	<tr>
		                    		<td><?=$msg->order_id;?></td>
		                    		<td><?=$msg->product?></td>
		                    		<td>
                                    <?php
                                        if ($msg->grade_table == 'iwide_hotels_order')
                                        {
                                            echo get_room_night($msg->startdate, $msg->enddate, 'ceil');
                                        }
                                        else
                                        {
                                            echo '--';
                                        }
                                    ?>
                                    </td>
		                    		<td><?php echo empty($hotels[$msg->hotel_id]) ? '' : $hotels[$msg->hotel_id];?></td>
		                    		<td><?php echo empty($msg->grade_amount) ? '--' : $msg->grade_amount?></td>
		                    		<td><?php echo $msg->grade_time?></td>
		                    		<td><?php if($msg->status == 2):
		        	echo '发放失败';
		        	elseif ($msg->status == 1): 
		        	echo '已发放';
		        	endif;
		        	?></td>
		                    		<td><?=$msg->saler?></td>
		                    		<td><?=$msg->staff_name?></td>
		                    		<td><?=isset($msg->hotel_name)?$msg->hotel_name:''?></td>
		                    		<td><?=$msg->grade_total?></td>
		                    		<td><?=$msg->partner_trade_no?></td>
		                    		<td><?=$msg->send_by == 2 ? '金房卡' : '酒店'?></td>
		                    		<td><?php echo $msg->status == 1 ? '--' : $msg->remark?></td>
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
});
</script>
</body>
</html>
