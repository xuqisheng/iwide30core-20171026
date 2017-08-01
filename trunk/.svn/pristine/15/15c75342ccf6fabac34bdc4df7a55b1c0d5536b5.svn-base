<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/kindeditor/lang/zh_CN.js"></script>
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
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php
	$ids = $model->m_get($pk);
	$ids = intval($ids);//整型安全过滤，防注入。	
	
	if($ids){
		$this_main_data = $model->get_main_data($ids);
		$publics = $this->publics_model->get_public_by_id($this_main_data[0]['inter_id']);
	}
	
	
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
			<?php if($ids){ ?>
			  <div class="form-group  has-feedback">
				<label for="el_gs_nums" class="col-sm-2 control-label">手机端</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control " disabled="disabled" value="http://<?php echo $publics['domain'];?>/index.php/chat/bottle/mainbottle?id=<?php echo $publics['inter_id'];?>&chatid=<?php echo $ids;?>">
				</div>
			  </div>
			  <?php } ?>
			  
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
</div>
<!-- /.box -->
<div style="display:none"><textarea name="qfformcontent" class="form-control " style="height:300px"></textarea></div>
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
var editor1;
KindEditor.ready(function(K) {
	editor1 = K.create('textarea[name="qfformcontent"]', {
		cssPath : '<?php echo base_url(FD_PUBLIC) ?>/kindeditor/plugins/code/prettify.css',
		uploadJson : '<?php echo base_url() ?>index.php/basic/uploadftp/do_upload',
		fileManagerJson : '<?php echo base_url() ?>index.php/basic/uploadftp/listfiles',
		allowFileManager : true,
		minWidth : '100%',
		autoHeightMode : true,
		width : '100%',
		afterCreate : function() {
			$(window).on('resize', function() {
				if (editor1) editor1.resize('100%', null);
			});
		}
	});qfkeupload(editor1);
});
KindEditor.ready(function(K) {
	var editor = K.editor({
		allowFileManager : true
	});
});
</script>
</body>
</html>
