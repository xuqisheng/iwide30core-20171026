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
								<div class="panel panel-default">
								  <div class="panel-heading">新增收取交易通知授权</div>
								  <div class="panel-body">
								    <?php echo form_open('okpay/msgauth/cksaler',array('class'=>'form-inline','id'=>'search_form'))?>
							<div class="form-group">
								<label>分销号</label>
								<input type="text" class="form-control input-sm" name="saler_no" value="" />
							</div>
							<div class="form-group">
								<label>姓名</label>
								<input type="text" class="form-control input-sm" name="saler_name" value="" />
							</div>
							<div class="form-group">
								<a class="btn btn-default">检索</a>
							</div>
						</form>
					<div class="row">
						<div class="col-sm-12" id="search_res"></div>
					</div>
								  </div>
								</div>
					<div class="row">
						<div class="col-sm-12">&nbsp;</div>
					</div>
					<div class="panel panel-default">
  <div class="panel-heading">授权列表</div>
  <div class="panel-body">
    <table id="data-grid" class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>分销号</th>
								<th>姓名</th>
								<th>手机号</th>
								<th>所属酒店</th>
								<th>所属部门</th>
								<th>当前状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody><?php foreach ($res as $item):?>
                    	<tr>
											<td><?php echo $item->qrcode_id?></td>
											<td><?php echo $item->name?></td>
											<td><?php echo $item->cellphone?></td>
											<td><?php echo $item->hotel_name?></td>
											<td><?php echo $item->master_dept?></td>
											<td><?php echo $item->status == 1 ? '正常' : '停止'; ?></td>
											<td><?php if($item->status == 1):?><a href="" sid="<?php echo $item->qrcode_id?>" name="status" rs="2">停止授权</a><?php else:?><a href="" sid="<?php echo $item->qrcode_id?>" name="status" rs="1">开放授权</a><?php endif;?></td>
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
	$('#search_form .btn').on('click',function(){
		var _this = $(this);
		$.post($('#search_form').attr('action'),$('#search_form').serialize(),function(data){
			var tmpStr = '<table id="res-grid" class="table table-bordered table-striped table-condensed"><thead><th>分销号</th><th>姓名</th><th>电话</th><th>部门</th><th>酒店</th><th>操作</th></thead><tbody>';
			if(data.count > 0){
				$.each(data.res,function(k,v){
					tmpStr += '<tr>';
					tmpStr += '<td>' + v.qrcode_id + '</td>';
					tmpStr += '<td>' + v.name + '</td>';
					tmpStr += '<td>' + v.cellphone + '</td>';
					tmpStr += '<td>' + v.master_dept + '</td>';
					tmpStr += '<td>' + v.hotel_name + '</td>';
					tmpStr += '<td><a class="btn btn-default btn-sm" role="auth" rid="' + v.qrcode_id + '">授权</a></td>';
					tmpStr += '</tr>';
				});
			}else{
				tmpStr += '<td colspan="6">没有资料</td>';
			}
			tmpStr += '</tbody></table>';
			$('#search_res').html(tmpStr);
			$('a[role=auth]').on('click',function(){
				var _this = $(this);
				var tds = $('td',$(this).parent().parent());
				if(confirm('确认授权分销号为 ' + tds[0].innerText + ' 的 ' + tds[1].innerText + ' 收取交易通知吗?')){
					$.post('<?php echo site_url("okpay/msgauth/new_auth")?>',{'admin':_this.attr('rid'),'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','typ':<?php echo $this->uri->segment(3) == 'jfk_admins' ? 2 : 1;?>},function(data){
						alert(data.errmsg);
						location.reload();
					},'json');
				}
			});
		},'json');
	});

	$('a[name=status]').on('click',function(e){
		e.preventDefault();
		var _this = $(this);
		var tds = $('td',$(this).parent().parent());
		if(confirm('确认修改分销号为 ' + tds[0].innerText + ' 的 ' + tds[1].innerText + ' 授权状态吗?')){
			$.post('<?php echo site_url("okpay/msgauth/schange")?>',{'admin':_this.attr('sid'),'status':_this.attr('rs'),'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','typ':<?php echo $this->uri->segment(3) == 'jfk_admins' ? 2 : 1;?>},function(data){
				alert(data.errmsg);
				if(data.errcode == 'ok'){
					location.reload();
				}
			},'json');
		}
	});
	$('#select_all').click(function (){
		if($(this).attr('prop') == "false"){
			$("input[name='sid[]']").prop('checked',true);
			$('#select_all').attr('prop',"true");
		}else{
			$("input[name='sid[]']").prop('checked',false);
			$('#select_all').attr('prop',"false");
		}
	});
	$("input[name='sid[]']").click(function(){
		$('#select_all').attr('prop',"false");
	});
	$('input[name=amount]').on('change',function(){
		// var amount = parseFloat ($(this).val());
		$('#welfare_amount').html(parseFloat ($(this).val())*parseFloat ($(this).attr('rc')));
	});
	var qrcode = new QRCode(document.getElementById("qrcode"), {
        width : 105,//设置宽高
        height : 105
    });
    qrcode.makeCode("http://jfk.iwide.cn/index.php/s/w/a/000e2e4a-167b-11e6-b2a0-00163f000037");
});
	$('#sendModal').on('show.bs.model',function (event) {
// 		var total_amount = parseInt($('div[role="status"]').attr('total_amount'));
		var total = $("input[name='sid[]']:checked");
		if(total.length > 1){
			$('#send_to').html(total.val() + '-' + total.attr('rn'));
		}
		
	});
</script>
