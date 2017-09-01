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
<div class="modal fade" id="sendModal">
			<div class="modal-dialog">
				<div class="modal-content" style="min-width: 400px;width:30%;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
							aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">福利详情</h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">发放对象:</div>
								<div class="col-md-9" id="send_to"></div>
							</div>
							<div class="row">
								<div class="col-md-3">福利标题:</div>
								<div class="col-md-9" id="title"></div>
							</div>
							<div class="row">
								<div class="col-md-3">福利金额:</div>
								<div class="col-md-3" id="amount"></div>
							</div>
							<div class="row">
								<div class="col-md-3">福利总额:</div>
								<div class="col-md-9" id="amounts"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="setModelConfirm" data-dismiss="modal">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
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
                	<?php echo form_open('distribute/welfare/logs/',array('class'=>"form-inline",'id'=>'search_form'))?>
	                	<div class="form-group">
							<label>操作日期</label>
							<input type="text" name="btime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo $btime?>">
							<label>至</label>
							<input type="text" name="etime" class="form-control input-sm datetime" placeholder="" aria-controls="data-grid" value="<?php echo $etime?>">
						</div>
	                	<div class="form-group">
							<label>操作员</label><input type="text" name="admin" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $admin?>">
						</div>
						<div class="btn-group">
							<button type="submit" class="btn btn-sm" id="grid-btn-search"> <i class="fa fa-search"></i>&nbsp;查询</button>
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
								<th>操作账号</th>
								<th>操作时间</th>
								<th>操作IP</th>
								<th>操作类型</th>
								<th>操作对象</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody><?php foreach ($res as $item):?>
                    	<tr>
											<td><?php echo $item->admin_username?></td>
											<td><?php echo $item->create_time?></td>
											<td><?php echo $item->remote_ip?></td>
											<td>发放福利</td>
											<?php $to = $item->persons > 1 ? '选定'.$item->persons.'人' : $item->name.' - No.'.$item->saler;?>
											<td><?php echo $to?></td>
											<td><a href="" data-toggle="modal" data-target="#sendModal" sid="<?php echo $item->id?>" to="<?= $to?>" title="<?= $item->title?>" amount="<?= $item->amount?>" amounts="<?= $item->amounts?>">查看</a></td>
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
