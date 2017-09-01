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
			    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
			        <ul class="nav nav-tabs">
			            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
			            <li class=""><a href="#tab2" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> 购买清单 </a></li>
			        </ul>

			<!-- form start -->
				<?php if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ) 
						$params= array('referer'=> urlencode($_SERVER['HTTP_REFERER']) );
					else $params= array();
				echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post', $params ), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
			        <div class="tab-content">
			            <div class="tab-pane active" id="tab1">
							<div class="box-body">
								<?php foreach ($fields_config as $k=>$v): ?>
									<?php 
									if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
									else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
									// if( $k == 'order_id' ){
					    //             	echo '<div class="form-group">
									// 			<label for="el_nickname" class="col-sm-2 control-label">退款人</label>
									// 			<div class="col-sm-8">
									// 				<input class="form-control selectpicker show-tick" data-live-search="true" name="nickname" id="el_nickname" value="'.$nickname.'" disabled="true" />
									// 			</div>
									// 		</div>';
					    //             }
									?>
								<?php endforeach; ?>
							</div>
							<!-- /.box-body -->
							
							<div class="box-footer ">
								<div class="col-sm-12 col-sm-offset-4">
									<?php echo $button_str; ?>
								</div>
							</div>
							<!-- /.box-footer -->
			            </div><!-- /#tab1-->
			        <?php echo form_close() ?>

			            <div class="tab-pane" id="tab2">
							<div class="box-body">
								<!-- 购买清单 -->
								<div class=" col-sm-12 " >
									<table class="table table-striped table-hover">
										<thead>
											<tr role="row">
												<?php foreach($grid_field as $v): ?>
												<th role="row">
													<?php echo $v; ?>
												</th>
												<?php endforeach; ?>
											</tr>
										</thead>
										<tbody>
											<tr>
											<?php foreach($items as $v): ?>
											<tr>
												<?php foreach($grid_field as $sk=> $sv): ?>
													<td>
														<?php echo (isset($v[$sk]))? $v[$sk]: '-'; ?>
													</td>
												<?php endforeach; ?>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div><!-- /.box-body -->
			            </div><!-- /#tab2-->

			        </div><!-- tab-content -->

			    </div><!-- tabbable -->
			</div><!-- /.box -->
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
