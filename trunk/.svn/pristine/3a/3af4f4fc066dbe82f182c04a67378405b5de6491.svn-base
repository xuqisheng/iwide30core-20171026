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
<div class="box box-info"><!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	 /.box-header -->


    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
			<?php if($model->m_get($pk)): ?> 
            <!--
            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-link"></i> 关联皮肤 </a></li> 
			-->
			<?php endif; ?>
        </ul>



<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">

<?php 
echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data',), array($pk=>$model->m_get($pk) ) ); ?>

				<div class="box-body">
					<?php 
		$show_fields= array('parent_id','cat_name','inter_id','hotel_id','cat_sort','cat_keyword','cat_desc','cat_img',);
					foreach ($show_fields as $v ): ?>
						<?php 
						if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($v, $fields_config[$v], $model); 
						else echo EA_block_admin::inst()->render_from_element($v, $fields_config[$v], $model, FALSE); 
						?>
					<?php endforeach; ?>
					<div class="form-group ">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-8"><a href="javascript:void();" id="toggle_upload">要自己上传图标？</a></div>
					</div>
					<div class="form-group ">
						<label for="el_cat_img_" class="col-sm-2 control-label">分类图标</label>
						<div class="col-sm-8" id="el_cat_img_">
						<?php foreach ($imgs as $v ): 
						$checked=''; 
						if( $model->m_get('cat_img')== $model->url_cat_img($v) ) $checked= ' checked '; 
						else if( $this->input->post('cat_img_')== $model->url_cat_img($v) )  $checked= ' checked '; 
						?>
							<label class="radio-inline" style="padding:5px;margin:5px 10px;">
								<input type="radio" name="cat_img_" <?php echo $checked ?> value="<?php echo $model->url_cat_img($v); ?>" required >
								<img src="<?php echo file_site_url(). $model->url_cat_img($v); ?>" height="50" />
							</label>
						<?php endforeach; ?>
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
			</div>


		<div class="tab-pane" id="tab2">
			<div class="box-body">ssss
			</div>
			<!-- /.box-body -->
		</div><!-- /#tab3-->


<?php echo form_close() ?>
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
<?php if( !$model->m_get('cat_img') || preg_match('/.*common.*/i', $model->m_get('cat_img')) ): ?>
	$('#el_cat_img').parent().parent().hide();
<?php else: ?>
	$('#el_cat_img_').parent().hide();
	$.each($('#el_cat_img_ input'), function(i,e){
		$(e).prop('required','');
	});
<?php endif; ?>
$('#toggle_upload').click(function(){
	$.each($('#el_cat_img_ input'), function(i,e){
		$(e).prop('checked', false);
		$(e).prop('required','');
	});
	$('#el_cat_img').parent().parent().toggle();
	$('#el_cat_img_').parent().toggle();
});
</script>
</body>
</html>
