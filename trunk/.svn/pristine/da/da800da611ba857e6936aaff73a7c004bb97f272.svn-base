<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
	<style>
<!--
.zero-clipboard{position: relative;display: block;}
.btn-clipboard{border-top-right-radius: 0;position: absolute;top: -1px;right: -1px;z-index: 10;display: block;padding: 5px 8px;font-size: 12px;color: #777;cursor: pointer;background-color: #fff;border: 1px solid #e1e1e8; border-radius: 0 4px 0 4px;}
#qjform .form-control{display:initial;}
#qjform .row{margin-top: 5px;}
#qjform .input-sm{padding: 5px 5px;}
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
				<h1>反馈信息
            <small></small>
				</h1>
				<ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
			</section>
			<!-- Main content -->
			<section class="content">
				<div class="box box-info">
				<div class="box-body">
                <?php echo $this->session->show_put_msg(); ?>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default">
								  <div class="panel-body">
								  <?php echo form_open('',array('id'=>'qjform','class'=>'form-horizontal'))?>
								<div class="row">
									<?php if(count($publics) > 1):?><div class="col-md-4">所属公众号：<select id="public_id" name="public_id" class="form-control input-sm"><option value="">全部</option><?php foreach ($publics as $k=>$v):?><option value="<?php echo $k?>"><?php echo $v?></option><?php endforeach;?></select></div><?php endif;?>
									<div class="col-md-4">所属酒店：<select name="hotel_id" id="hotel_id" class="form-control input-sm"><option value="">全部</option><?php foreach ($hotels as $k=>$v):?><option value="<?php echo $k?>"><?php echo $v?></option><?php endforeach;?></select></div>
									<div class="col-md-4">反馈人：<input type="text" name="name" id="name" class="form-control input-sm" value="" /></div>
								</div>
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-4">关键字：<input type="text" name="keywords" id="keywords" class="form-control input-sm" value="" /></div>
									<div class="col-lg-* col-md-* col-sm-*">反馈时间：
										<div class="input-group input-daterange">
										    <input type="text" class="form-control" name="time" id="time_begin" value="">
										    <span class="input-group-addon">至</span>
										    <input type="text" class="form-control" name="time" id="time_end" value="">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-2 col-md-3 col-sm-4"><a id="search" class="btn btn-primary">检索</a></div>
								</div>
								<?php echo form_close();?>
								  </div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
                <table id="all-grid" class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>反馈编号</th>
								<th>反馈内容</th>
								<th>反馈时间</th>
								<th>反馈人</th>
								<th>所属公众号</th>
								<th>所属门店</th>
								<th>分销号</th>
								<th>反馈次数</th>
							</tr>
						</thead>
								</table>
					</div></div>
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

<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- page script -->
	<script>
var buttons = $('<div class="btn-group"></div>');


<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
	var configs = {
			aLengthMenu: [5,10,20,50,100],
			iDisplayLength: 20,
			bProcessing: true,
			paging: true,
			bPaginate: true,
			bLengthChange: true,
			bInfo: true,
			lengthChange: true,
//	 		"ordering": true,
//	 		"order": grid_sort,
			info: true,
			autoWidth: false,
			sPaginationType:   "full_numbers",
			language: {
				"sSearch": "搜索",
				"lengthMenu": "每页显示 _MENU_ 条记录",
				"zeroRecords": "找不到任何记录. ",
				"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
				"infoEmpty": "",
				"infoFiltered": "(从 _MAX_ 条记录中过滤)",
				"paginate": {
					"sNext": "下一页",
					"sPrevious": "上一页",
					"sFirst": "首页",
					"sLast": "末页",
				}
			},
			processing: true,
			serverSide: true,
			recordsTotal: true,
			stateSave: true,
			ajax: {
				"type": 'POST',
				"url": "<?php echo site_url('distribute/feebacks/get_feebacks')?>",
				"data": {<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>','inter_id':'','hotel_id':$('#hotel_id').val(),'keywords':$('#keywords').val(),'saler':$('#name').val(),time_begin:$('#time_begin').val(),time_end:$('#time_end').val() }
			},
			columns: [
	            { "data": "id","searchable": true },
	            { "data": "content","searchable": true },
	            { "data": "create_time" },
	            { "data": "name","searchable": true },
	            { "data": "inter_id","searchable": true },
	            { "data": "hotel_id","searchable": true },
	            { "data": "saler","searchable": true },
	            { "data": "counts" }
	        ],
			searching: false
		};
$(document).ready(function() {
// 	$("input[name=time]").datepicker({format: 'yyyy-mm-dd',autoclose:true});
	$('.input-daterange input').each(function() {
	    $(this).datepicker({format: 'yyyy-mm-dd',autoclose:true});
	});
	var grid_table = $("#all-grid").DataTable(configs);
	//$('#all-grid_length').append('&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm bg-green" href="<?php echo site_url('distribute/feeback/ex_feebacks').'?sid='.$this->input->get('sid')?>&sts=' + configs.ajax.data.sts + '">导出</a>');
	$('#search').on('click',function(){
		configs.ajax.data.hotel_id=$('#hotel_id').val();
		configs.ajax.data.keywords=$('#keywords').val();
		configs.ajax.data.saler=$('#name').val();
		configs.ajax.data.time_begin=$('#time_begin').val();
		configs.ajax.data.time_end=$('#time_end').val();
		grid_table.clear();
		grid_table.destroy();
		grid_table = $("#all-grid").DataTable(configs);
	});
	<?php if(count($publics) > 1):?>
	$('#public_id').on('change',function(){
		var _this = $(this);
		$.getJSON('<?php echo site_url('distribute/feebacks/get_hotels')?>',{inter_id:_this.val()},function(data){
			var tempStr = '<option value="">全部</option>';
			$.each(data,function(k,v){
				tempStr += '<option value="'+k+'">'+v+'</option>';
			});
			$('#hotel_id').html(tempStr);
		});
	});	
<?php endif;?>
});
</script>
</body>
</html>
