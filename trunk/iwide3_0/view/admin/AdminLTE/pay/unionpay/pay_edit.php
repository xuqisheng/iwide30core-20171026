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

					<div class="tab-content">
						<div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('pay/unionpay/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' )); ?>
              			    <div class="box-body">
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">主商户号</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " name="mch_id"
											id="mch_id" placeholder="<?php echo (!empty($list['mch_id']))? $list['mch_id']: '主商户号'; ?>"
											value="" >
									</div>
								</div>
							</div>
							<div class="box-body">
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">证书密码</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " name="pwd"
											id="pwd" placeholder="<?php echo (!empty($list['pwd']))? $list['pwd']: '证书密码(6位数字)'; ?>"
											value="" >
									</div>
								</div>
							</div>
              			    <div class="box-body">
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">支付证书</label>
									<div class="col-sm-8">
										<input type="file" class="form-control " name="certs" id="certs" />
									</div>
								</div>
								<i>（注：如只是上传证书而没有修改支付商户信息，直接点击上传证书按钮上传证书，上传成功后不必点击保存）</i>
							</div>
							 <div class="box-footer ">
		                        <div class="col-sm-4 col-sm-offset-1 pull-right">
		                            <button type="submit" class="btn btn-info">保存</button>&nbsp;
		                            <a href='<?php echo site_url('pay/pay/ways');?>' class="btn btn-info">返回</a>
		                        </div>
		                    </div>
							<!-- /.box-footer -->
			<?php echo form_close()?>
						</div>
                <!-- /.box-body -->

					</div>
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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
	<link rel="stylesheet"
		href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script>
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
});
</script>
</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
// 		$('#el_logo').parent().append('<input type="file" value="上传图片" id="upfiles">');
		$('#certs').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			'uploader' : '<?php echo site_url('pay/unionpay/cert_upload_url') ?>',
			'file_post_name': 'imgFile',
			'buttonText': '上传证书',
			'fileTypeExts': '*.pem;*.cer;*.pfx',
			'onSWFReady': function(){
				$('.uploadify-button-text').addClass('btn btn-info'); 
			},
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
				var msgType = 'alert-success';
				var msg     = '上传成功';
			    if(res.errormsg != 'ok'){
					msgType = 'alert-danger';
					msg     = res.errormsg;
				}
			    if($('.content > div > .alert').length > 0){
			    	$('.content > div > .alert').remove();
			    	var alertStr = '<div class="alert ' + msgType + ' alert-dismissible">';
				    alertStr += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
				    alertStr += '<h4><i class="icon fa fa-ban"></i> ' + msg + '</h4></div>';
				    $('.content > div').prepend(alertStr);
				}else{
				    var alertStr = '<div class="alert ' + msgType + ' alert-dismissible">';
				    alertStr += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
				    alertStr += '<h4><i class="icon fa fa-ban"></i> ' + msg + '</h4></div>';
				    $('.content > div').prepend(alertStr);
				}
        	}
		});
	});
	
</script>