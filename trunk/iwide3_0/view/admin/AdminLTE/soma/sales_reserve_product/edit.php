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
	<div class="box-header with-border">
		<!-- <h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3> -->
		<h3 class="box-title">新增产品</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
<!--             <?php //foreach ($fields_config as $k=>$v): ?>
				<?php 
                //if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                //else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                ?>
			<?php //endforeach; ?> -->
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
 			<input type="hidden" name=product_ids id="el_product_ids" value="<?php echo $model->m_get('product_ids'); ?>">
			<!-- <div style="height:450px;">
				<div class="col-sm-12">
				    <div class="alert alert-success">
				        <table class="table ">
					        <thead><tr role="row center"> <th colspan="3">已选择商品</th> </tr></thead>
					        <tr role="row"> <th>#ID</th><th>商品名称</th><th>目前价格</th> </tr>
					        <tbody>
					        </tbody>
					    </table>
					</div>
				</div>
			</div> -->
			<link href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
			<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
			<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
			<div class="col-sm-12">
				<table id="data-grid" class="table table-bordered table-striped table-condensed">
				    <thead><tr role="row">
				    	<th>编号</th>
				    	<th>商品名</th>
				    	<th>封面图</th>
				    	<th>门店价</th>
				    	<th>组合价</th>
				    </tr></thead>
				</table>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <button type="submit" class="btn btn-info pull-right">保存</button>
            </div>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<script type="text/javascript">
<?php 
$click_event = <<<EOF
    var selected_ = selected.join();
    var id = parseInt(this.id);
    var index = $.inArray(id, selected);
    if ( index===-1 ){
        $(this).addClass('bg-gray');
        selected.push( id );
    } else {
        $(this).removeClass('bg-gray');
        selected.splice( index, 1 );
    }
    $('#el_product_ids').val(selected);
EOF
;
?>
var dataSet=<?php echo json_encode($grid_data); ?>;
var grid_sort= [[ 0, "asc" ]];
$(document).ready(function() {
	<?php require_once VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>
});

</script>

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
