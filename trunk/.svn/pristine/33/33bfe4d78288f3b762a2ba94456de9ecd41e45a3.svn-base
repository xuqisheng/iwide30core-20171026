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
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body hide">
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
		<?php if($asset_items):?>
		<div class="box-body">
			<!-- 购买清单 -->
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10" >
					<table class="table table-striped table-hover">
						<tbody>
							<?php foreach($items_grid_field as $k=> $v): ?>
								<tr>
									<td><?php echo $v,'：'; ?><?php echo $item[$k]; ?></td>
								</tr>
							<?php endforeach; ?>
							<?php if( $openids && $code_list && $code_item_ids ):?>
								<?php foreach( $openids as $k=>$v ):?>
									<tr>
										<td><?php echo $v,'券码列表：';?>
											<?php foreach( $code_list as $kk=>$vv ):?>
												<?php if( isset( $code_item_ids[$vv['asset_item_id']] ) && $code_item_ids[$vv['asset_item_id']] == $k ) echo $vv['code'],'; ';?>
											<?php endforeach;?>
										</td>
									</tr>
								<?php endforeach;?>
							<?php endif;?>
							<tr><td>备注：再次赠送出去的券码不会显示</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php else:?>
			<div class="box-body">
				<div class="form-group">
					<div class="col-sm-offset-5 col-sm-10" style="color: blue;" >没有更多信息</div>
				</div>
			</div>
		<?php endif;?>
		<!-- /.box-body -->
		<!-- <div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <button type="reset" class="btn btn-default">清除</button>
                <button type="submit" class="btn btn-info pull-right">保存</button>
            </div>
		</div> -->
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
