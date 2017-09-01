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
	<!-- <div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div> -->
	<!-- /.box-header -->

<!-- form start -->
	<?php 
	
	$url = EA_const_url::inst()->get_url('*/*/comfirm');
	// $button_name = "确认";
	if($model->comfirmed_status != $model::STATUS_WAITTING) {
		// $button_name = '审核';
		$url = EA_const_url::inst()->get_url('*/*/review');
	}

	echo form_open( $url, array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
			<?php //foreach ($fields_config as $k=>$v): ?>
				<?php 
                //if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                //else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                ?>
			<?php //endforeach; ?>
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
		<div class="tabbable "> <!-- Only required for left/right tabs -->
	        <ul class="nav nav-tabs">
	            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
	            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-list-alt"></i> 订单确认  </a></li>
	            <?php if($model->comfirmed_status == $model::STATUS_SUCCESS): ?>
	            	<li><a href="#tab3" data-toggle="tab"><i class="fa fa-list-alt"></i> 财务审核  </a></li>
	        	<?php endif; ?>
	        </ul>
	        <div class="tab-content">
	        	<div class="tab-pane active" id="tab1">
	        		<div class="box-body">
	        			<?php foreach ($fields_config as $k => $v): ?>
							<?php
								// 订单状态尚未确认成功时，屏蔽空白信息
								if($model->comfirmed_status != $model::STATUS_SUCCESS && in_array($k, array('order_id','product_price','grand_total'))) {
									continue;
								}
							?>
							<?php if ($check_data==FALSE): ?>
        						<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model); ?>
		        			<?php else: ?>
		        				<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); ?>
		        			<?php endif; ?>
	        			<?php endforeach; ?>
	        		</div>
	        	</div>
	        	<div class="tab-pane" id="tab2">
	        		<div class="box-body">
	        			<?php foreach ($comfirm_fields as $k => $v): ?>

	        				<?php 
	        					// 屏蔽未确认时确认人，确认时间信息
	        					if($model->comfirmed_status == $model::STATUS_WAITTING
	        						&& in_array($k, array('comfirmed_time','comfirmed_user','reviewed_status','reviewed_note'))) {
	        						continue;
	        					}
	        				?>

							<?php if($k == 'comfirmed_status'):?>
								<div class='form-group '>
	    					   		<label for='<?php echo "el_" . $k; ?>' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
	    					   		<div class='col-sm-8'>
	            						<select <?php echo ($model->comfirmed_status == $model::STATUS_WAITTING)?'':'disabled';?> class='form-control selectpicker show-tick' data-live-search='true' name='<?php echo $k; ?>' id='<?php echo "el_" . $k; ?>'>
		            					   <?php echo $model->get_status_select_html($k); ?>
		            					</select>
	        						</div>
	                        	</div>
							<?php elseif($k == 'grand_total'): ?>
								<div class="form-group  has-feedback">
									<label for="el_grand_total" class="col-sm-2 control-label"><?php echo $v['label']; ?></label>
									<div class="col-sm-8"><input type="text" class="form-control " name="grand_total" id="el_grand_total" placeholder="<?php echo $v['label']; ?>" value="<?php echo $model->m_get($k); ?>" <?php echo ($model->comfirmed_status == $model::STATUS_WAITTING)?'':'disabled';?>></div>
								</div>
							<?php elseif ($k == 'salesman'): ?>
								<div class="form-group  has-feedback">
									<label for="el_salesman" class="col-sm-2 control-label"><?php echo $v['label']; ?></label>
									<div class="col-sm-8"><input type="text" class="form-control " name="salesman" id="el_salesman" placeholder="<?php echo $v['label']; ?>" value="<?php echo $model->m_get($k); ?>" <?php echo ($model->comfirmed_status == $model::STATUS_WAITTING)?'':'disabled';?>></div>
								</div>
							<?php elseif ($k == 'comfirmed_note'): ?>
								<div class="form-group  has-feedback">
									<label for="el_comfirmed_note" class="col-sm-2 control-label"><?php echo $v['label']; ?></label>
									<div class="col-sm-8">
										<textarea class="form-control" name="comfirmed_note" id="el_comfirmed_note" placeholder="填写范例：xxx公司 xxx客户 通过 xxx 方式付款 xxx 元，优惠 xxx 元" <?php echo ($model->comfirmed_status == $model::STATUS_WAITTING)?'':'disabled';?>><?php echo $model->m_get($k); ?></textarea>
									</div>
								</div>
							<?php elseif ($check_data==FALSE): ?>
								<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model); ?>
		        			<?php else: ?>
		        				<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); ?>
		        			<?php endif; ?>
	        			<?php endforeach; ?>
	        		</div>
	        	</div>
	        	<div class="tab-pane" id="tab3">
	        		<div class="box-body">
						<?php foreach ($review_fields as $k => $v): ?>
							<?php
								// 屏蔽未审核时审核人，审核时间信息,订单
	        					if($model->reviewed_status == $model::STATUS_WAITTING
	        						&& in_array($k, array('reviewed_time','reviewed_user'))) {
	        						continue;
	        					}
							?>
							<?php if($k == 'reviewed_status'):?>
								<div class='form-group '>
	    					   		<label for='<?php echo "el_" . $k; ?>' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
	    					   		<div class='col-sm-8'>
	            						<select <?php echo ($model->reviewed_status == $model::STATUS_WAITTING)?'':'disabled';?> class='form-control selectpicker show-tick' data-live-search='true' name='<?php echo $k; ?>' id='<?php echo "el_" . $k; ?>'>
		            					   <?php echo $model->get_status_select_html($k); ?>
		            					</select>
	        						</div>
	                        	</div>
							<?php elseif ($k == 'reviewed_note'): ?>
								<div class="form-group  has-feedback">
									<label for="el_reviewed_note" class="col-sm-2 control-label"><?php echo $v['label']; ?></label>
									<div class="col-sm-8"><textarea class="form-control" name="reviewed_note" id="el_reviewed_note" placeholder="填写规范：财务职员 XXX 确认已收到 XXX 款项" <?php echo ($model->reviewed_status == $model::STATUS_WAITTING)?'':'disabled';?>><?php echo $model->m_get($k); ?></textarea></div>
								</div>
							<?php elseif ($check_data==FALSE): ?>
								<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model); ?>
		        			<?php else: ?>
		        				<?php echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); ?>
		        			<?php endif; ?>
						<?php endforeach; ?>
	        		</div>
	        	</div>
	    	</div>
		</div>

		<!-- /.box-body -->
		<div class="box-footer ">
	        <div class="col-sm-4 col-sm-offset-4">
	                <!-- <button type="reset" class="btn btn-default">清除</button> -->
	            <?php if($model->reviewed_status == $model::STATUS_WAITTING): ?>
	                <button type="submit" class="btn btn-info">提交订单</button>
	        	<?php endif; ?>
	        </div>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<script type="text/javascript">
	$("#el_grand_total").change(function(){
        var grand_total = $(this).val();
        var price = grand_total / <?php echo $model->qty;?> ;
        $("#el_product_price").val(price.toFixed(2));
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
