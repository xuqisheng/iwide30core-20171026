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
<?php $pk= $model->table_primary_key(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->get('ids') ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$this->input->get('ids') ) ); 
	?>
		<div class="box-body">
            <?php foreach ($fields_config as $k=>$v): ?>
				<?php 
                if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                ?>
			<?php endforeach; ?>
<!-- 
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" /> 选项
						</label>
					</div>
				</div>
			</div>
 -->
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="reset" class="btn btn-default">清除</button>
                <button type="submit" class="btn btn-info pull-right">保存</button>
            </div>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
	<div class="box-body">
		<div class="form-group  has-feedback">
			<label class="col-sm-2 control-label">上传公众号验证文件</label>
			<div class="col-sm-8">
				<input type="file" class="form-control " name="verifytxt" id="verifytxt" />
				<p id='tips' style="color:red"></p>
			<i>(文件会自动上传，不需要点保存)</i>
			</div>
		</div>
	</div>
	<div class="box-body">
		<div class="form-group  has-feedback">
			<div class="col-sm-8">
				<a href="<?php echo site_url('publics/publics/index')?>"><button type="button" class="btn btn-info pull-left">返回</button></a>
			</div>
		</div>
	</div>
</div>
<!-- /.box -->

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
</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#el_logo').parent().append('<input type="file" value="上传图片" id="upfiles">');
		$('#upfiles').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			//'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#el_logo').val(res.url);
        	}
		});
		
		$('#verifytxt').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			'uploader' : '<?php echo site_url('publics/publics/verifytxt_upload') ?>',
			'file_post_name': 'txt',
			'sizeLimit':'1KB',
			'buttonText': '上传',
			'fileTypeExts': '*.txt',
			'multi':false,
			'onSWFReady': function(){
				$('.uploadify-button-text').addClass('btn btn-info'); 
			},
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
				$("#tips").html(res.message);
        	}
		});
	});
</script>