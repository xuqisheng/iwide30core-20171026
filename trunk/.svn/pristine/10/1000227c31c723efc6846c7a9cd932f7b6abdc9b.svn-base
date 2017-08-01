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

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">填写信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/*'), array('class'=>'form-horizontal','id'=>'form-edit-id'), 
	array('type'=>'model' ) ); ?>
		<div class="box-body">
             <div class="form-group ">
            	<label for="el_prefix" class="col-sm-2 control-label">数据库连接: </label>
            	<div class="col-sm-8">
                	<select class="form-control" name="db_resource" id="el_db_resource">
                    	<option value="iwide_rw" selected="selected">iwide_rw</option>
                    	<option value="iwide_soma" >iwide_soma</option>
                	</select>
            	</div>
             </div>
             <div class="form-group ">
            	<label for="el_prefix" class="col-sm-2 control-label">表前缀: </label>
            	<div class="col-sm-8">
            		<input type="text" class="form-control" name="prefix" id="el_prefix" placeholder="iwide_" value="<?php echo $prefix; ?>">
            	</div>
             </div>
             <div class="form-group ">
            	<label for="el_table" class="col-sm-2 control-label">表名: </label>
            	<div class="col-sm-8">
            		<input type="text" class="form-control" name="table" id="el_table" placeholder="soma_sales_order" value="<?php echo isset($table)? $table: ''; ?>">
            	</div>
             </div>
             <div class="form-group ">
            	<label for="el_parent" class="col-sm-2 control-label">继承父类: </label>
            	<div class="col-sm-8">
            		<input type="text" class="form-control" name="parent" id="el_parent" placeholder="MY_model_soma" value="<?php echo $parent; ?>">
            	</div>
             </div>
             <div class="form-group ">
            	<label for="el_path" class="col-sm-2 control-label">生成路径: </label>
            	<div class="col-sm-8">
            		<input type="text" class="form-control" name="path" id="el_path" placeholder="models/soma/" value="<?php echo $path; ?>">
            	</div>
             </div>
             <div class="form-group ">
            	<label for="el_class" class="col-sm-2 control-label">生成类名: </label>
            	<div class="col-sm-8">
            		<input type="text" class="form-control" name="class" id="el_class" placeholder="Sales_order" value="<?php echo isset($class)? $class: ''; ?>">
            	</div>
            </div>
            <div class="form-group ">
            	<label for="el_template" class="col-sm-2 control-label">模板文件: </label>
            	<div class="col-sm-8">
                	<select class="form-control" name="template" id="el_template">
                    	<option value="template/model.php" selected="selected">template/model.php</option>
                	</select>
            	</div>
            </div>
<?php if(isset($file)): ?>
			<div class="col-sm-10 " style="word-break:break-all;width:95%; overflow:auto;border:1px solid #afafaf;margin-left:20px;"><code><?php echo $file ?></code></div>
<?php endif; ?>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="submit" class="btn btn-default" id="gen_btn_id">生成</button>
                <button type="submit" class="btn btn-info pull-right">预览</button>
            </div>
		</div>
		<!-- /.box-footer -->
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
$('#gen_btn_id').bind('click', function(){
	$('#form-edit-id').prop('action', "<?php echo EA_const_url::inst()->get_url('*/*/save'); ?>");
});
$('#el_class').bind('focus', function(){
	var table= $('#el_table').val();
    if(table==''){
		alert('请输入数据表名称');
		$('#el_table').focus();
	}
});
</script>

</body>
</html>
