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
<div class="box box-info">
	<!-- <div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>模板</h3>
	</div> -->
	<!-- /.box-header -->
<!-- form start -->
		
		<div class="tabbable ">
	        <ul class="nav nav-tabs">
	            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
	            <?php if($input_disabled): ?>
	            	<li><a href="#tab2" data-toggle="tab"><i class="fa fa-list-alt"></i> 批次导出  </a></li>
	            <?php endif; ?>
	        </ul>
	        <div class="tab-content">
	        	<div class="tab-pane active" id="tab1">
	        		<div class="box-body">
	        			<?php 
							echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) );
						?>
			        		<?php foreach ($fields_config as $k=>$v): ?>
			        			<?php if($k == 'product_id'): ?>
			        				<div class='form-group '>
									   <label for='el_<?php echo $k; ?>' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
									   <div class='col-sm-8 inline'>
				        					<select class="form-control selectpicker show-tick" data-live-search="true" name='<?php echo $k; ?>' id='el_<?php echo $k; ?>' <?php if($input_disabled): ?> disabled <?php endif; ?>>
				        					   <?php echo $model->get_product_select_html($product_list); ?>
				        					</select>
				    					</div>
				                    </div>
				                <?php elseif (!$input_disabled && in_array($k, array('create_time', 'update_time', 'op_user'))):?>
				                	<?php continue; ?>
			        			<?php elseif($check_data==FALSE): ?>
									<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model);?>
			        			<?php else: ?>
									<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE);?>
								<?php endif; ?>
							<?php endforeach; ?>
		
							<?php if($input_disabled): ?>
								<div class="form-group  has-feedback">
									<label for="el_produce_cnt" class="col-sm-2 control-label">生成数量</label>
									<div class="col-sm-8"><input type="text" class="form-control " name="produce_cnt" id="el_produce_cnt" placeholder="生成数量" value="" ></div>
								</div>
							<?php endif; ?>

							<div class="box-footer ">
					            <div class="col-sm-4 col-sm-offset-4">
					            	<?php if(!$input_disabled): ?>
					                	<button type="reset" class="btn btn-default">清除</button>
					                	<button type="submit" class="btn btn-info pull-right">保存</button>
					                <?php else: ?>
					                	<button id="produce_code" class="btn btn-info">生成券码</button>
					                <?php endif; ?>
					            </div>
							</div>
						
						<?php echo form_close() ?>

					</div>
	        	</div>
	        	<?php if($input_disabled): ?>
	        		<div class="tab-pane" id="tab2">
		        		<div class="box-body">
			        		<?php 
								echo form_open( EA_const_url::inst()->get_url('*/*/batch_export'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) );
							?>
			        			<div class='form-group '>
								   <label for='el_batch_no' class='col-sm-2 control-label'>选择批次</label>
								   <div class='col-sm-8 inline'>
			        					<select class="form-control selectpicker show-tick" data-live-search="true" name='batch_no' id='el_batch_no'>
			        					   <?php echo $model->get_batch_select_html($product_list); ?>
			        					</select>
			    					</div>
			                    </div>

			                    <div class="box-footer ">
						            <div class="col-sm-4 col-sm-offset-4">
						                <button id="batch_export" class="btn btn-info">导出</button>
						            </div>
								</div>
		                	<?php echo form_close() ?>
		        		</div>
		        	</div>
		        <?php endif; ?>
	        </div>
	    </div>

<!-- 		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
            	<?php if(!$input_disabled): ?>
                	<button type="reset" class="btn btn-default">清除</button>
                	<button type="submit" class="btn btn-info pull-right">保存</button>
                <?php else: ?>
                	<button id="produce_code" class="btn btn-info">生成券码</button>
                <?php endif; ?>
            </div>
		</div> -->
		<!-- /.box-footer -->
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
