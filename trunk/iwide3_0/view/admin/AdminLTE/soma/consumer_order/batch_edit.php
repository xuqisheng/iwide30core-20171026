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
<?php $pk= $model->table_primary_key(); $item_pk= $model->item_table_primary_key(); ?>

<?php 
	$combine_main_order = false;
	if(!empty($combine_assets))
	{
		$combine_main_order = true;
		$asset_items = $combine_assets;
	}
?>

<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">查询到的信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	//echo form_open( Soma_const_url::inst()->get_url('*/*/batch_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <!-- <?php echo $btn_search; ?> -->
			<!-- 核销清单 -->
			<div class=" col-sm-12 " >
				<table class="table table-striped table-hover">
					<thead>
						<tr role="row">
							<?php foreach($grid_field as $v): ?>
							<th role="row">
								<?php echo $v; ?>
							</th>
							<?php endforeach; ?>
							<th>订单类型</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php foreach($grid_field as $k=> $v): ?>
							<td>
								<?php echo $order_detail[$k]; ?>
							</td>
						<?php endforeach; ?>
							<td>
								<?php if($combine_main_order): ?>
									组合购买套餐订单
								<?php elseif(!empty($order_detail['master_oid'])): ?>
									组合购买套餐子订单
								<?php else: ?>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class=" col-sm-12 " >
			<hr style="border: 1px black solid;">
			</div>
			<div class=" col-sm-12 " >
				<table class="table table-striped table-hover">
					<thead>
						<tr role="row">
							<?php foreach($item_grid_field as $v): ?>
							<th role="row">
								<?php echo $v; ?>
							</th>
							<?php endforeach; ?>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $asset_items as $k=>$v ):?>
							<tr>
							<?php foreach($item_grid_field as $sk=> $sv): ?>
								<td>
									<?php echo $v[$sk]; ?>
								</td>
							<?php endforeach; ?>
								<td>
									<?php 
										echo form_open( Soma_const_url::inst()->get_url('*/*/batch_post'), array('class'=>'form-horizontal'), array('order_id'=>$v['order_id']) );
									?>
									<div class="group">
										<div class="col-sm-8 ">
											<input type="number" class="form-control " name="num" id="el_num" placeholder="请输入核销数量" min="0" max="<?php echo $v['qty'];?>">
										</div>
										<div class="col-sm-4 ">
							                <input type="submit" id="button" class="btn btn-info" value="提交" />
							            </div>
									</div>
									<?php echo form_close() ?>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
		<?php if(false): ?>
			<?php if( $asset_items_num > 0 ):?>
				<div class="group">
					<label for="el_order_notice" class="col-sm-2 control-label">请选择数量<!-- 1 --></label>
					<div class="col-sm-2 ">
		                <!-- <button type="reset" class="btn btn-default">清除</button> -->
		                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
		                <input type="number" class="form-control " name="num" id="el_num" placeholder="数量" value="0" min="0" max="<?php echo $asset_items_num;?>">
		            </div>
		            <div class="col-sm-4 ">
		                <!-- <button type="reset" class="btn btn-default">清除</button> -->
		                <input type="submit" id="button" class="btn btn-info" value="提交" />
		            </div>
				</div>
			<?php endif;?>
		<?php endif; ?>
		</div>
		<!-- /.box-footer -->
	<?php //echo form_close() ?>
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
	$(function(){
		$("#button").click(function(){
			var is_true = '';
			var is_expire = "<?php echo $is_expire;?>";
			if( is_expire ){
				is_true = confirm("该订单已经过了有效期，是否要核销？");
			}else{
				is_true = confirm("你确认要进行该操作吗？");
			}
			if( !is_true ){
				return false;
			}
		});
	});
</script>
</body>
</html>
