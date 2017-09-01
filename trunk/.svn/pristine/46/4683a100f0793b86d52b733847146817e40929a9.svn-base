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
		<h3 class="box-title">基本信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <?php foreach ($fields_config as $k=>$v): ?>
				<?php 
				if($model->m_get('status') == $model::STATUS_APPLY && in_array($k, $waitting_un_show)) continue;
				?>
				<?php if($k == 'distributor'):?>
					<div class='form-group '>
				   		<label for='<?php echo "el_" . $k; ?>' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
				   		<div class='col-sm-8'>
       						<select <?php echo ($model->m_get('status') == $model::STATUS_APPLY)?'':'disabled';?> class='form-control selectpicker show-tick' data-live-search='true' name='<?php echo $k; ?>' id='<?php echo "el_" . $k; ?>'>
        					   <?php echo $model->get_distributor_select_html(); ?>
        					</select>
   						</div>
                   	</div>
				<?php elseif ($k == 'tracking_no'):?>
					<div class="form-group  has-feedback">
						<label for="el_tracking_no" class="col-sm-2 control-label"><?php echo $v['label']; ?></label>
						<div class="col-sm-8"><input type="text" class="form-control " name="tracking_no" id="el_tracking_no" placeholder="<?php echo $v['label']; ?>" value="<?php echo $model->m_get($k); ?>" <?php echo ($model->m_get('status') == $model::STATUS_APPLY)?'':'disabled';?>></div>
					</div>
				<?php elseif($check_data==FALSE):?>
					<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model); ?>
                <?php else:?>
                	<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); ?>
                <?php endif; ?>
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
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <?php 
                	$btn_name = '提交备注';
                	if($model->m_get('status') == $model::STATUS_APPLY) {
                		$btn_name = '邮寄';
                	}
                ?>
                <button type="submit" class="btn btn-info pull-right"><?php echo $btn_name; ?></button>
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
</body>
</html>
