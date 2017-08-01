<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.css" type="text/css" />
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
			<h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
				<small></small>
			</h1>
			<ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
		</section>
		<!-- Main content -->
		<section class="content">

			<?php echo $this->session->show_put_msg(); ?>
			<!-- Horizontal Form -->
			<div class="box box-info">
				<!--

<div class="tabbable "> <!-- Only required for left/right tabs -->
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab"><i
								class="fa fa-list-alt"></i>基本信息 </a></li>
				</ul>

				<!-- form start -->
				<?php echo form_open( site_url('hotel/coupons/edit_tips'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'myform')); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
						<div class="box-body">
							<div class="form-group  has-feedback">
								<label class="col-sm-2 control-label">温馨提示</label>
								<div class="col-sm-8">
									<textarea name="param_value" rows="10" class="form-control" placeholder="温馨提示"><?php echo $param_value; ?></textarea>
								</div>
							</div>
						</div>
						<!-- /.box-footer -->
					</div>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-1 pull-right">
							<button type="button" id="save-form" class="btn btn-info">保存</button>&nbsp;
							<a href="javascript:;" onclick="document.getElementById('myform').reset();" class="btn btn-info">还原</a>
						</div>
					</div>
					<!-- /.box-body -->

				</div>
				<?php echo form_close()?>
				<!-- /#tab1-->

			</div>
			<!-- /.tab-content -->

		</section>
		<!-- /.content -->
	</div>
	<!-- /.box -->

</div>
<!-- /.content-wrapper -->

<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>
<?php

/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>
</div>
<!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<script type="text/javascript">
$(function(){
	$('#save-form').on('click',function(){
		$.ajax({
			url:$('#myform').attr('action'),
			data:$('#myform input[type="hidden"],#myform textarea'),
			dataType:'json',
			type:'POST',
			beforeSend:function(){
				$('#save-form').after('<i class="fa fa-circle-o-notch fa-spin waiting"></i>');
			},
			complete:function(){
				$('.waiting').remove();
			},
			success:function(json){
				if(json.status){
					alert(json.info);
					location.reload();
				}else{
					alert(json.errmsg);
				}
			}
		});
	});
});
</script>
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script
	src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
</body>
</html>