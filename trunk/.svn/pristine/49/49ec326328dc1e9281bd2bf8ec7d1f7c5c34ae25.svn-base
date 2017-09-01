<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC);?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css'>
<script src='<?php echo base_url(FD_PUBLIC);?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js'></script>
<script src='<?php echo base_url(FD_PUBLIC);?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js'></script>
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

<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/batch_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data',), array($pk=>$model->m_get($pk) ) ); 
$revt= $model->m_get('reserve_date');
$current_status= $model->m_get('status');
if( in_array($current_status, $model->can_shipped_status() ) ){
    $can_shipped= TRUE;
} else 
    $can_shipped= FALSE;
?>

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 批量发货 </a></li>
        </ul>



<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="box-body">
					<div class='form-group '>
					   <label for='el_distributor' class='col-sm-2 control-label'>选择快递商：</label>
					   <div class='col-sm-6 inline'>
        					<select class="form-control selectpicker show-tick" data-live-search="true" name='distributor' id='el_distributor'>
        					   <?php echo $model->get_distributor_select_html($model->m_get('distributor'), FALSE); ?>
        					</select>
    					</div>
                    </div>
                    <div class="form-group">
						<label for="exampleInputFile" class='col-sm-2 control-label'>上传文件：</label>
						<div class='col-sm-6 inline'>
        					<input type="file" name="batch" id="exampleInputFile">
                            <span style="color: red;">请上传csv格式的文件</span>
                        </div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer ">
				    <div class="col-sm-1 col-sm-offset-3">
					    <button type="submit" class="btn btn-info pull-right">发货</button>
					</div>
				</div>
				<!-- /.box-footer -->
			</div>

            <div class="tab-pane active" id="tab2">
                <div class="box-body">
                    <?php if(isset( $fail_data ) && count($fail_data)>0): ?>
                        <div class="form-group">
                            <label for='el_distributor' class='col-sm-2 control-label'>邮寄发货失败列表：</label>
                            <div class="col-sm-9 " >
                                <table id="data-grid" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <th>邮寄ID</th>
                                        <th>失败原因</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fail_data as $k=>$v): ?>
                                        <tr>
                                            <td><?php echo $v['shipping_id'];?></td>
                                            <td><?php echo $v['message'];?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>


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
<script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
var slt_dist= $('#el_distributor').val();
$('#el_distributor').change(function(){
	$.cookie('soma_default_distributor', slt_dist, { expires: 7 });
});
var old_dist= $.cookie('soma_default_distributor');
if( slt_dist=='' && old_dist!=null) {
	$('#el_distributor').val(old_dist);
}
</script>
</body>
</html>
