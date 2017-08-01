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
    <!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>-->
	<!-- /.box-header -->

				<div class="tabbable "> <!-- Only required for left/right tabs -->
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
						<?php if($model->has_inter_id()): ?>
						<li><a href="#tab4" data-toggle="tab"><i class="fa fa-wechat"></i> 扫码授权 </a></li>
						<?php else: ?>
						<li><a href="#tab4" data-toggle="tab" style="color:gray;"><i class="fa fa-wechat"></i> 扫码授权 </a></li>
						<?php endif; ?>
					</ul>
<!-- form start -->
<?php echo form_open( EA_const_url::inst()->get_url('*/*/profile_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'form-edit-id') ); ?>

					<div class="tab-content">
						<div class="tab-pane active" id="tab1">

							<div class="box-body">
								<?php if($model->m_get($pk)): ?>
								<div class="form-group ">
									<label for="el_inter_id" class="col-sm-2 control-label">所属公众号</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " id="el_inter_id" disabled value="<?php 
										echo $fields_config['inter_id']['select'][$model->m_get('inter_id')] ?>" >
									</div>
								</div>
								<?php endif; ?>
								<?php 
									$keys= array('nickname','head_pic','email');
									if($check_data==FALSE){
										foreach($keys as $k)
											echo EA_block_admin::inst()->render_from_element($k, $fields_config[$k], $model); 
									} else {
										foreach($keys as $k)
											echo EA_block_admin::inst()->render_from_element($k, $fields_config[$k], $model, FALSE);
									}
								?>
								<div class="form-group has-feedback">
									<label for="el_password_cur" class="col-sm-2 control-label">当前密码</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="password_cur" id="el_password_cur" placeholder="当前密码" disabled value="******" >
									</div>
								</div>
								<div class="form-group has-feedback">
									<label for="el_password" class="col-sm-2 control-label">登陆密码</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="password" id="el_password" placeholder="登陆密码" disabled value="******" >
									</div>
								</div>
								<div class="form-group has-feedback">
									<label for="el_password" class="col-sm-2 control-label">确认密码</label>
									<div class="col-sm-8">
										<input type="password" class="form-control" name="password_cf" id="el_password_cf" placeholder="确认密码" disabled value="******">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<div class="checkbox">
											<label><input type="checkbox" id="checkbox_change_password" /> 修改密码？ </label>
										</div>
									</div>
								</div>
							</div>

							<!-- /.box-body -->
							<div class="box-footer ">
								<div class="col-sm-4 col-sm-offset-4">
									<button type="submit" class="btn btn-info pull-right">保存</button>
								</div>
							</div>
							<!-- /.box-footer -->

						</div><!-- /#tab1 -->

						<div class="tab-pane" id="tab4">
<?php 
/* Footer Block @see footer.php */
if($model->has_inter_id()):
	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'adminuser'. DS. 'openid.php';
else:
	echo '<div class="box-body text-center" >该账号无对应的公众号，无法进行授权。</div>';
endif;
?>
						</div><!-- /#tab4-->

					</div><!-- /.tab-content -->
<?php echo form_close() ?>

				</div><!-- /.tabbable -->
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
<script>
$('#checkbox_change_password').bind('click', function(){
	var chk= $('#checkbox_change_password');
	if(chk.prop('checked')==true){
		$('#el_password').prop('disabled', '');
		$('#el_password').prop('required','required');
		$('#el_password').val('');
		$('#el_password_cf').prop('disabled', '');
		$('#el_password_cf').prop('required','required');
		$('#el_password_cf').val('');
		$('#el_password_cur').prop('disabled', '');
		$('#el_password_cur').prop('required','required');
		$('#el_password_cur').val('');
	}else {
		$('#el_password').prop('disabled', 'disabled');
		$('#el_password').prop('required','');
		$('#el_password').val('******');
		$('#el_password_cf').prop('disabled', 'disabled');
		$('#el_password_cf').prop('required','');
		$('#el_password_cf').val('******');
		$('#el_password_cur').prop('disabled', 'disabled');
		$('#el_password_cur').prop('required','');
		$('#el_password_cur').val('******');
	}
});
<?php if(isset($redirect)&& $redirect): ?>
var t=setTimeout( function(){window.location="<?php echo EA_const_url::inst()->get_logout_admin(); ?>";},2000);
<?php endif; ?>
</script>
</body>
</html>
