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

<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) );
?>
<div class="tabbable "> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li id="tab_header1" class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        <li id="tab_header2" <?php echo $tr_slt != '' && $tr_slt != NULL && $model->m_get('scope')== Soma_base::STATUS_FALSE ? '' : 'style="display:none;"'; ?>><a href="#tab2" data-toggle="tab"><i class="fa fa-link"></i> 选择商品</a></li> 
    </ul>
	<div class="tab-content">
        <div class="tab-pane active" id="tab1">
			<div class="box-body">
            <?php foreach ($fields_config as $k=>$v): ?>
                <?php if($k == 'bonus_size'): ?>
<div class="form-group " style="display: block;">
    <label for="el_bonus_size" class="col-sm-2 control-label"><abbr title="1元获取x积分">积分比例: <br/>(按等级配置)</abbr></label>
    <div class="col-sm-8" id="el_bonus_size">
        <?php foreach ($bonus_data as $lk=> $lv): ?>
        <div class="col-sm-12">
            <div class="col-sm-3"><label for="" ><?php echo $lv['name']; ?></label></div>
            <div class="col-sm-3"><input class="form-control" value="<?php echo $lv['size']; ?>" name="bonus_size[<?php echo $lk; ?>]" type="text" placeholder="1元获取x积分"></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
                <?php elseif ($check_data == FALSE): ?>
                    <?php echo EA_block_admin::inst()->render_from_element($k, $v, $model); ?>
                <?php else: ?>
                    <?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); ?>
                <?php endif;?>
			<?php endforeach; ?>
			<div class="form-group  has-feedback">
            	<label for="el_name" class="col-sm-2 control-label">适用商品</label>
            	<div class="col-sm-8">
            		<label class="col-sm-4" onclick="$('#tab_header2').show();">
            		<input type="radio" required <?php echo $tr_slt != '' && $tr_slt != NULL && $model->m_get('scope')== Soma_base::STATUS_FALSE ? 'checked="checked"' : ''; ?> name="p_type" id="select_product" value="ids"  ><abbr title="下单时仅对所选择的商品计算">选择商品 </abbr></label>
            		<label class="col-sm-4" onclick="$('#tab_header2').hide();">
            		<input type="radio" required <?php echo $model->m_get('scope')== Soma_base::STATUS_TRUE ? ' checked="checked" ': '';
            		?> name="p_type" id="allUse" value="all_use" > <abbr title="">选择全部商品</abbr> </label>
            	</div>
            </div>
            <div class="form-group">
				<input type="hidden" name=product_ids id="el_product_ids" value="<?php echo $model->m_get('product_ids'); ?>">
            </div>
			</div>
		</div>
		<div class="tab-pane" id="tab2" style="padding-top: 20px;">

			<link href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
			<link href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css" rel="stylesheet">
			<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
			<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
			<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
			<div class="col-sm-12">
				<table id="data-grid" class="table table-bordered table-striped table-condensed">
				    <thead><tr role="row">
				    	<th></th>
				    	<th>编号</th>
				    	<th>商品名</th>
				    	<th>目前价格</th>
				    </tr></thead>
				</table>
			</div>
			
		</div>
	</div>
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

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<script type="text/javascript">
<?php 
$click_event = <<<EOF
    var selected_ = selected.join();
    var id = parseInt(this.id);
    var index = $.inArray(id, selected);
    if ( index===-1 ){
    	$(this).addClass('selected');
        $(this).addClass('bg-gray');
        selected.push( id );
    } else {
    	$(this).removeClass('selected');
        $(this).removeClass('bg-gray');
        selected.splice( index, 1 );
    }
    $('#el_product_ids').val(selected);
EOF
;
?>
var dataSet=<?php echo json_encode($grid_data); ?>;
var grid_sort= [[ 1, "asc" ]];
$(document).ready(function() {
	<?php require_once VIEWPATH. $tpl .DS .'soma'. DS. 'sales_point' . DS . 'gridjs_lite.php'; ?>
	$('#el_product_ids').val(selected);
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
