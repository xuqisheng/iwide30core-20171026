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
$tab2_header=FALSE;
$tab4_header=FALSE;
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
    <!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>-->
	<!-- /.box-header -->

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li class=""><a href="#tab2" data-toggle="tab"><i class="fa fa-ticket"></i> 优惠信息 </a></li>
            <li class=""><a href="#tab4" data-toggle="tab"><i class="fa fa-user"></i> 客户信息 </a></li>
            <li class=""><a href="#tab3" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> 购买清单 </a></li>
            <?php if(count($self_gift)>0 || count($other_gift)>0 ): ?>
            <li class=""><a href="#tab5" data-toggle="tab"><i class="fa fa-gift"></i> 赠送清单 </a></li>
            <?php endif; ?>
            <!--li class=""><a href="#tab6" data-toggle="tab"><i class="fa fa-ticket"></i> 消费清单 </a></li-->
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
							if($k == 'master_oid')
							{
								continue;
							}
							// grand_total 在 discount 之后显示
							if($k == 'grand_total') {
								$grand_total_k = $k;
								$grand_total_v = $v;
								continue;
							}
						?>

						<?php 
						if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
						else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
						?>
						
						<?php
							// grand_total 在 discount 之后显示
							if($k == 'discount') {
								if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($grand_total_k, $grand_total_v, $model); 
								else echo EA_block_admin::inst()->render_from_element($grand_total_k, $grand_total_v, $model, FALSE); 
							}
						?>

                        <?php if($k == 'hotel_id'):?>
                            <div class="form-group ">
                                <label for="el_gift_status" class="col-sm-2 control-label">价格配置</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control " name="scope_product_link_id" id="el_scope_product_link_id" value="<?php echo $scopeName;?>" disabled="">
                                </div>
                            </div>
                        <?php endif;?>

						<?php if($k == 'settlement'): ?>
							<?php // 订单类型 主订单号放在购买方式后面显示 ?>
							<?php if($model->m_get('master_oid') == '0'
                            || $model->m_get('master_oid') == '' || $model->m_get('master_oid') == null): ?>
								<?php 
									// 主订单、默认订单保持原样
									continue;
								?>
							<?php else: ?>
								<div class="form-group ">
			                        <label for="el_gift_status" class="col-sm-2 control-label">订单类型</label>
			                        <div class="col-sm-8">
			                        	<input type="text" class="form-control " name="order_type" id="el_order_type" placeholder="订单类型" value="组合购买子商品订单" disabled="">
			                        </div>
			                    </div>
			                    <div class="form-group ">
			                        <label for="el_gift_status" class="col-sm-2 control-label">套餐订单</label>
			                        <div class="col-sm-8">
			                            <div class="btn-group bootstrap-select disabled form-control show-tick">
			                                <a class="form-control " href="<?php echo Soma_const_url::inst()->get_url('*/*/*', array('ids' => $model->m_get('master_oid'))); ?>"><?php echo $model->m_get('master_oid'); ?></a>
			                            </div>
			                        </div>
			                    </div>
			                    <?php continue; ?>
							<?php endif; ?>
						<?php endif; ?>

					<?php endforeach; ?>

<!--                    <div class="form-group ">-->
<!--                        <label for="el_gift_status" class="col-sm-2 control-label">赠送状态</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            <div class="btn-group bootstrap-select disabled form-control show-tick">-->
<!--                                --><?php //echo "未赠送:{$not_gift}; ","待发送:{$pending}; ","赠送中:{$gifting}; ","已赠送:{$received};";?>
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

					<!-- /.box-body -->
					<div class="form-group ">
						<label for="el_gift_status" class="col-sm-2 control-label">备注</label>
						<div class="col-sm-8">
							<div class="btn-group bootstrap-select disabled form-control show-tick" style="padding: 2px 0 0 13px;">
								<span><?php echo $remark; ?></span>
								<span><button type="button" class="btn btn-info editRemark">编辑</button></span>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
									<h4 class="modal-title" id="myModalLabel">编辑备注</h4>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<textarea name="remark" class="form-control" id="remarkVal"><?php echo $remark; ?></textarea>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>关闭</button>
									<button type="button" class="btn btn-primary" data-dismiss="modal" id="btn_submit"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>保存</button>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-4">
							<!--<button type="submit" class="btn btn-info pull-right">保存</button>-->
						</div>
					</div>
				</div>
				<!-- /.box-body -->

            </div><!-- /#tab1-->

            <div class="tab-pane" id="tab2">
				<div class="box-body">
					<div class=" col-sm-12 " >
    					<table id="data-grid" class="table table-bordered table-striped table-condensed">
                        <thead><tr><?php //unset($discount_fields_config['order_id']); //去掉某些字段表头
    	    foreach ($discount_fields_config as $k=> $v): 
    		     ?><th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> ><?php echo $v['label'];?></th><?php 
    	    endforeach; ?></tr></thead>
                        </table>
				    </div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab2-->
            
            <div class="tab-pane" id="tab3">
				<div class="box-body">
					<!-- 购买清单 -->
					<div class=" col-sm-12 " >
						<table class="table table-striped table-hover">
							<tbody>
								<?php foreach($items as $k=> $v): ?>
									<?php foreach($items_grid_field as $sk=> $sv): ?>
        								<tr>
    										<td><?php echo $sv; ?></td>
    										<td><?php if( $k == $sk ) echo $v[$sk]; else echo '-'; ?></td>
        								</tr>
									<?php endforeach; ?>
								<?php endforeach; ?>
								<?php if(!empty($combine_usage)): ?>
									<tr>
										<td>套餐详情</td>
										<td>
											<?php $cnt = 1; ?>
											<?php foreach($combine_usage as $usage): ?>
												<div><?php echo "子商品{$cnt}:{$usage['name']}&nbsp;&nbsp;&nbsp;&nbsp;获得数量:{$usage['get_qty']}&nbsp;&nbsp;&nbsp;&nbsp;未使用数量:{$usage['now_qty']}&nbsp;&nbsp;&nbsp;&nbsp;订单号:{$usage['child_oid']}"; ?></div>
												<?php $cnt++; ?>
											<?php endforeach; ?>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab3-->
            
            <div class="tab-pane" id="tab4">
				<div class="box-body">
<div class='form-group has-feedback'>
	<label for='el_headimgurl' class='col-sm-2 control-label'>微信头像</label>
	<div class='col-sm-8' style="text-align:center;">
		<img src="<?php echo $fans['headimgurl'] ?>" width="80" height="80" >
	</div>
</div>
<div class='form-group has-feedback'>
	<label for='el_nickname' class='col-sm-2 control-label'>昵称(微信个人资料)</label>
	<div class='col-sm-8'>
		<input type='text' class='form-control ' name='nickname' id='el_nickname' value='<?php 
			echo empty($fans['nickname'])? '未获得授权信息': $fans['nickname']; ?>'  disabled >
	</div>
</div>
<div class='form-group has-feedback'>
	<label for=' ' class='col-sm-2 control-label'>性别(微信个人资料)</label>
	<div class='col-sm-8'>
		<input type='text' class='form-control ' name='sex' id='el_sex' value='<?php 
			if(isset($fans['sex'])) echo $fans['sex']==1? '男': '女'; ?>'  disabled >
	</div>
</div>
<div class='form-group has-feedback'>
	<label for=' ' class='col-sm-2 control-label'>地区(微信个人资料)</label>
	<div class='col-sm-8'>
		<input type='text' class='form-control ' name='province' id='el_province' value='<?php 
			echo empty($fans['province'])? '未获得授权信息': $fans['province'] ?>'  disabled >
	</div>
</div>
<div class='form-group has-feedback'>
	<label for='el_nickname' class='col-sm-2 control-label'>购买联系人</label>
	<div class='col-sm-8'>
		<input type='text' class='form-control ' name='name' id='el_name' value='<?php 
			echo empty($fans['name'])? '客户未填写': $fans['name']; ?>'  disabled >
	</div>
</div>
<div class='form-group has-feedback'>
	<label for='el_nickname' class='col-sm-2 control-label'>购买联系电话</label>
	<div class='col-sm-8'>
		<input type='text' class='form-control ' name='mobile' id='el_mobile' value='<?php 
			echo empty($fans['mobile'])? '客户未填写': $fans['mobile']; ?>'  disabled >
	</div>
</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab4-->
            
            <div class="tab-pane" id="tab5">
				<div class="box-body">
				
				
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab5-->
            
            <div class="tab-pane" id="tab6">
				<div class="box-body">
				
				
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab6-->
            
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

<!--    datatable js start  -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 
$sort_index= $discount_model->field_index_in_grid($discount_default_sort['field']);
$sort_direct= $discount_default_sort['sort'];
$buttions= '';	//button之间不能有字符空格，用php组装输出
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');
var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
var dataSet= <?php echo json_encode($discount['data']); ?>;
var columnSet= <?php echo json_encode( $discount_model->get_column_config($fields_config) ); ?>;
$(document).ready(function() {
<?php 
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_lite.php';
/**    datatable js start  **/
?>

<?php if($t= $this->input->get('tab')) echo "$('". '#top_tabs a[href="#tab'. $t. '"]'. "').tab('show');"; ?>

	/**
	 * 备注点击事件
	 */
	$(".editRemark").click(function(){
		$("#myModal").modal();
	});

	/**
	 * 提交备注
	 */
	$("#btn_submit").click(function(){
		var url = '<?php echo Soma_const_url::inst()->get_url('*/*/ajax_edit_remark'); ?>';
		var soid = '<?php echo $order_id; ?>';
		var remark = $("#remarkVal").val();
		if( remark == '' ){
			alert( '请输入备注信息！' );
			return false;
		}
		$.ajax({
			type: 'post',
			url: url,
			data: {soid: soid, remark: remark, <?php echo $this->security->get_csrf_token_name()?>: '<?php echo $this->security->get_csrf_hash() ?>'},
			dataType: 'json',
			success: function(e){
				console.log(e);
				if(e.status != 1){
					alert(e.message);
					return false;
				}

				alert('编辑成功');
				location.reload();
			}
		});
	});
});
</script>
</body>
</html>
