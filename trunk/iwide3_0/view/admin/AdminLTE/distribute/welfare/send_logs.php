<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/qrcode.js"></script>
    <style type="text/css">
    	#qrcode>img{margin: 0 auto;text-align: center;}
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
				<h1><?php echo $breadcrumb_array['action']; ?>
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
                <style>
                	#search_form .form-group{margin:.3em auto;}
                </style>
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
                	<?php echo form_open('distribute/welfare/send_logs/',array('class'=>"form-inline",'id'=>'search_form'))?>
	                	<div class="form-group">
							<label>操作日期</label>
							<input type="text" name="btime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['btime']) ? $posts['btime'] : ''?>">
							<label>至</label>
							<input type="text" name="etime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['etime']) ? $posts['etime'] : ''?>">
						</div>
	                	<div class="form-group">
							<label>分销员</label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['saler_name']) ? $posts['saler_name'] : ''?>">
						</div>
	                	<div class="form-group">
							<label>分销号</label><input type="text" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['saler_no']) ? $posts['saler_no'] : ''?>">
						</div>
	                	<div class="form-group">
							<label>发放状态</label>
							<select name="status" class="form-control input-sm">
								<option value=""> -- 全部 -- </option> 
								<option value="1"<?php if($posts['status'] == 1):echo ' selected';endif;?>>成功</option> 
								<option value="2"<?php if($posts['status'] == 2):echo ' selected';endif;?>>失败</option> 
								<option value="3"<?php if($posts['status'] == 3):echo ' selected';endif;?>>异常</option> 
							</select>
						</div>
	                	<div class="form-group">
							<label>所属酒店</label><input type="text" name="hotel" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['hotel']) ? $posts['hotel'] : ''?>">
						</div>
	                	<div class="form-group">
							<label>所属部门</label>
							<select name="dept" class="form-control input-sm">
								<option value=""> -- 全部 -- </option>
								<?php foreach($depts as $dept):?><option<?php if($posts['dept'] == $dept->master_dept):echo ' selected';endif;?>><?php echo $dept->master_dept?></option><?php endforeach;?>
							</select>
						</div>
						<div class="btn-group">
							<button type="submit" class="btn btn-default" id="grid-btn-search"> <i class="fa fa-search"></i>&nbsp;查询</button>
						</div>
						<div class="btn-group">
							<a class="btn btn-default" href="<?php echo site_url('distribute/welfare/ext_send_logs/'.$posts['btime'].'_'.$posts['etime'].'_'.$posts['saler_name'].'_'.$posts['saler_no'].'_'.$posts['status'].'_'.$posts['hotel'].'_'.$posts['dept'])?>">&nbsp;导出</a>
						</div>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">&nbsp;</div>
					</div>
					<table id="data-grid"
						class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>分销员</th>
								<th>分销号</th>
								<th>所属酒店</th>
								<th>所属部门</th>
								<th>分销状态</th>
								<th>发放时间</th>
								<th>福利标题</th>
								<th>发放金额</th>
								<th>发放状态</th>
								<th>发放商户单号</th>
								<th>发放来源</th>
								<th>备注</th>
							</tr>
						</thead>
						<tbody><?php foreach ($res as $item):?>
                    	<tr>
							<td><?php echo $item->name?></td>
							<td><?php echo $item->saler?></td>
							<td><?php echo $item->hotel_name?></td>
							<td><?php echo $item->master_dept?></td>
							<td><?php echo isset($saler_stat[$item->saler_status]) ? $saler_stat[$item->saler_status] : '--'?></td>
							<td><?php echo $item->send_time?></td>
							<td><?php echo $item->title?></td>
							<td><?php echo $item->amount?></td>
							<td><?php echo isset($send_stat[$item->status]) ? $send_stat[$item->status] : '--'?></td>
							<td><?php echo $item->out_trade_no?></td>
							<td><?php echo $item->typ == 1 ? '酒店发放' : '金房卡发放'?></td>
							<td><?php echo empty($item->rec_content) ? $item->remark : simplexml_load_string($item->rec_content)->return_msg?></td>
						</tr><?php endforeach;?> 
                    </tbody> 
								</table>
								<div class="row"> 
									<div class="col-sm-5">
										<div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite" total_amount="<?=$total?>">共<?=$total?>条</div>
									</div>
									<div class="col-sm-7">
										<div class="dataTables_paginate paging_simple_numbers"
											id="data-grid_paginate">
											<ul class="pagination"><?php echo $pagination?></ul>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div>
	<!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>

</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- page script -->
	<script>
var url_extra= [
//'http://iwide.cn/',
];

$(document).ready(function() {
	$('#sendModal').on('show.bs.modal',function (event) {
		var _this = $(event.relatedTarget);
		$('#send_to').html(_this.attr('to'));
		$('#title').html(_this.attr('title'));
		$('#amount').html(_this.attr('amount') + '/人');
		$('#amounts').html(_this.attr('amounts'));
	});
    $(".datetime").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>
