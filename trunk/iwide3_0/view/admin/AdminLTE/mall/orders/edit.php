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
            <li class=""><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li class=""><a href="#tab7" data-toggle="tab"><i class="fa fa-user"></i> 客户信息 </a></li>
            <li class=""><a href="#tab3" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> 购买清单 </a></li>
            <li class="active"><a href="#tab2" data-toggle="tab"><i class="fa fa-ambulance"></i> 配送信息 </a></li>
            <li class=""><a href="#tab4" data-toggle="tab"><i class="fa fa-gg"></i> 核销信息 </a></li>
            <li class=""><a href="#tab5" data-toggle="tab"><i class="fa fa-gift"></i> 转赠记录 </a></li>
            <!--<li><a href="#tab3" data-toggle="tab"><i class="fa fa-ambulance"></i> 邮寄地址 </a></li>-->
        </ul>

<!-- form start -->
	<?php if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ) 
			$params= array('referer'=> urlencode($_SERVER['HTTP_REFERER']) );
		else $params= array();
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post', $params ), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
				<div class="box-body">
					<?php foreach ($fields_config as $k=>$v): ?>
						<?php 
						if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
						else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
						?>
					<?php endforeach; ?>
					<!-- /.box-body -->
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-4">
<?php if( $model->m_get('inter_id')=='a453956624' ): ?>
    <?php if( $model->m_get('transaction_id') && !$model->m_get('out_order_id') ): ?>
    	<button type="button" class="btn btn-info" onclick="javascript:sync_order();">同步订单</button>
    <?php elseif( ! $model->m_get('transaction_id') ): ?>
    	<button type="button" class="btn disabled" onclick="javascript:alert('订单未支付');">未支付</button>
    <?php else: ?>
    	<button type="button" class="btn disabled" onclick="javascript:alert('订单已同步过了');">已同步</button>
    <?php endif; ?>
<?php endif; ?>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

            </div><!-- /#tab1-->

            <div class="tab-pane active" id="tab2">
				<div class="box-body">
					<div class="alert alert-success hide" id="tab2_header">此订单中包含不可邮寄商品，下方只显示可邮寄部分商品，请点击“核销信息”查看全部商品</div>
					<div class=" col-sm-12 " >
						<table class="table table-striped table-hover">
						<thead><tr role="row">
							<?php 
							$grid_field= array('id','gs_name','price', 'trans_time','trans_no','trans_company','ex_order','status');
							$num_field= count($grid_field);
							foreach($grid_field as $v): 
							?>
								<th><?php echo $label_items[$v]; ?></th>
							<?php endforeach; ?><th>数量</th>
						</tr></thead>
						<tbody><tr>
							<?php 
							$echo_= $can_save_= FALSE; //前者代表是否显示总的单号录入，后者代表是否显示 save按钮
							$all_shipping = TRUE;		//代表是否所有的单号都已经发货
							//判断数组的key中是否包含_，如包含则含有拆单单品
							foreach(array_keys($items) as $search){
								if( strstr($search, "_")) $echo_= TRUE;	//代表有拆单的情况，暗含不显示下方输入框
							} ?>
							<?php foreach($items as $v): 
								if( $v['can_mail']==EA_base::STATUS_FALSE_) {
									$tab2_header= TRUE; //js显示顶部的提醒信息
									continue; //不能邮寄的商品跳过
								}
							?><tr>
								<?php foreach($grid_field as $sv): ?>
								<td ><?php 
									//特定字段显示中文名
									if($sv=='status')
										echo (isset($v[$sv]))? $item_status[$v[$sv]]: '-'; 
									elseif($sv=='ex_order')
										echo (isset($v[$sv]))? $item_ext[$v[$sv]]: '-'; 
									else 
										echo (isset($v[$sv]))? $v[$sv]: '-';
								?></td>
								<?php endforeach; ?><td><p class="text-center">
									<b><?php echo $v['num_item']; ?>件</b></p></td>
								</tr>
								
								<?php if( in_array($v['status'], array($item_model::STATUS_DEFAULT, $item_model::STATUS_GIFTED, $item_model::STATUS_SHIP_PRE ) ) ): 
								        $all_shipping = FALSE; $echo_= TRUE; 
								?>
									<tr><td>&nbsp;</td><td colspan="<?php $r1= round($num_field/3);  echo $r1 ?>"> 
<div class="input-group">
  <div class="input-group-addon">填写单号</div>
  <input type="text" class="form-control input-sm trans_no" name="trans_no[<?php echo str_replace(',','_',$v['id']); ?>][]" placeholder="">
  <div class="input-group-addon">快递公司</div>
  <input type="text" class="form-control input-sm trans_company" name="trans_company[<?php echo str_replace(',','_',$v['id']); ?>][]" placeholder="">
</div>
									</td>
									<td colspan="<?php echo count($grid_field)- 1 ?>" > 寄送地址：<?php echo isset($v['address'])? $v['address']: '-' ;?></td>
								</tr>
								<?php else: ?>
									<tr><td>&nbsp;</td><td>寄送地址：</td><td colspan="<?php echo $num_field-1 ?>">
									<?php echo isset($v['address'])? $v['address']: '-' ;?></td></tr>
								<?php endif; ?>
								
								<?php if($v['status']!= $item_model::STATUS_SHIPPING /*|| empty($v['trans_no'])*/ ) 
									$can_save_= TRUE; //有未发货的，标识显示保存按钮，进行数据提交 ?>
								<?php ?>
							<?php endforeach; ?>
						</tbody>
						</table>
					</div>
			<?php if($invoice): ?>
					<div class="box-footer">
						<div class="input-group col-sm-10" >
							<label for="el_topic_id" class="col-sm-2 control-label">发票信息：</label>
							<div class="input-group">
								<div class="input-group-addon bg-green">发票抬头</div>
								<input type="text" class="form-control input-sm col-sm-1" value="<?php echo $invoice['title']; ?>" disabled >
								<div class="input-group-addon bg-green">发票金额</div>
								<input type="text" class="form-control input-sm col-sm-1" value="￥<?php echo $invoice['grand_total']; ?>" disabled >
<input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
							</div>
						</div>
					</div>
			<?php endif; ?>

					<div class="box-footer ">
						<?php if( !$echo_ && !$all_shipping): ?>
<div class="input-group col-sm-10 col-sm-offset-1" >
  <div class="input-group-addon">填写单号</div>
  <input type="text" class="form-control input-sm trans_no" name="trans_no" placeholder="所填写信息将应用于上面所有寄送商品">
  <div class="input-group-addon">快递公司</div>
  <input type="text" class="form-control input-sm trans_company" name="trans_company" placeholder="所填写单号将应用于上面所有寄送商品">
</div>
<p>
<div class="input-group col-sm-10 col-sm-offset-1">
寄送地址：<b style="color:red;" ><?php echo isset($v['address'])? $v['address']: '-' ;?></b>
</div><div style="clear:both;"></div>
</p>
						<?php endif; ?>

						<?php if($can_save_== TRUE ): ?>
						<div class="col-sm-4 col-sm-offset-3">
							<?php if($model->m_get('pay_status')==$model::PAYMENT_T): ?>
							<button type="submit" class="btn btn-info pull-right">发货处理</button>
							<?php else: ?>
							<button type="submit" class="btn pull-right" disabled>订单未支付</button>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>

				</div>
				<!-- /.box-body -->
            </div><!-- /#tab2-->

            <div class="tab-pane" id="tab3">
				<div class="box-body">
					<!-- 购买清单 -->
					<div class=" col-sm-12 " >
						<table class="table table-striped table-hover">
							<thead><tr role="row">
								<?php 
								$grid_field= array('id','gs_name','price','promote_price','gs_unit','order_time','is_add_pack');
								$num_field= count($grid_field);
								foreach($grid_field as $v): 
								?>
									<th role="row"><?php echo $label_items[$v]; ?></th>
								<?php endforeach; ?><th>数量</th>
							</tr></thead>
							<tbody><tr>
								<?php foreach($items as $v): ?><tr>
								<?php foreach($grid_field as $sv): ?>
									<td><?php echo (isset($v[$sv]))? $v[$sv]: '-'; ?></td>
									<?php endforeach; ?><td><b><?php echo $v['num_item']; ?>件</b></td></tr>
									<?php ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab2-->

            <div class="tab-pane" id="tab4">
				<div class="box-body">
					<div class="alert alert-success hide" id="tab4_header">此订单中部分商品已经发货，或者提交发货申请，已经隐藏。</div>
					<!-- 核销信息 -->
					<div class=" col-sm-12 " >
						<table class="table table-striped table-hover">
							<thead><tr role="row">
								<?php 
								$grid_field= array('id','gs_name','price', 'consume_time','consumer','status');
								$num_field= count($grid_field);
								foreach($grid_field as $v): 
									if($v=='status'): 
								?>
									<th role="row"><?php echo $label_items[$v]; ?></th>
								<?php else: ?>
									<th role="row"><?php echo $label_items[$v]; ?></th>
								<?php endif; endforeach; ?><th>数量</th><th>核销码(20位)</th><th>处理</th>
							</tr></thead>
							<tbody>
								<?php foreach($items as $v): ?><tr role="row">
<?php if( $v['status']==$item_model::STATUS_SHIPPING || $v['status']==$item_model::STATUS_SHIP_PRE ) {
			$tab4_header= TRUE; //js显示顶部的提醒信息
			continue; //不能邮寄的商品跳过
} ?>
									<?php foreach($grid_field as $sv): 
										if($sv=='status'): 
									?>
											<td><?php echo (isset($item_status[$v[$sv]]))? show_status_color($item_status[$v[$sv]],'待处理','已发货'): $v[$sv]; ?></td>
									<?php else: ?>
											<td><?php 
											if( isset( $openid_array[$v[$sv]] ) ) echo $openid_array[$v[$sv]];
											else echo (isset($v[$sv]))? $v[$sv]: '-'; 
											?></td>
									<?php endif; 
									endforeach; ?>
									<td><b><?php echo $v['num_item']; ?>件</b></td>
<?php if( in_array($v['status'], $item_model->can_consume_status() ) ): ?>
									<td><input type="text" class="form-control input-sm" id="consume_code_<?php echo  strpos($v['id'],',')? strchr($v['id'],',',TRUE): $v['id']; ?>" /></td>
									<td>
									<?php if($model->m_get('pay_status')==$model::PAYMENT_T): ?>
<button type="button" class="btn btn-info" onclick="javascript:consume_item('<?php echo $v['id'] ?>');">核销</button>
<?php if($v['num_item']>1): ?>
<button type="button" class="btn btn-info" onclick="javascript:separate_item('<?php echo $v['id'] ?>');">拆单</button>
<?php endif; ?>
									<?php else: ?>
<button type="button" class="btn" disabled>订单未支付</button>
									<?php endif; ?>
									</td>
<?php else: ?>
									<td colspan='2' class="text-center">此商品已核销</td>
<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="col-sm-12 box-footer " >
						<?php if( FALSE ): //if($can_save_== TRUE): ?>
						<div class="col-sm-4 col-sm-offset-3">
							<?php if($model->m_get('pay_status')==$model::PAYMENT_T): ?>
							<button type="button" class="btn btn-success" id="qrcode-toggle"><i class="fa fa-qrcode"></i> 扫码核销</button>
							<?php endif; ?>
						</div><br/><br/>
						<div class="col-sm-12 alert-success hidden" id="qrcode-block">
							<p style="line-height:30px;height:30px;">转发此链接到微信打开：&nbsp;&nbsp;<?php echo front_site_url($model->inter_id). '/mall/handle/consume?id='. $model->inter_id; ?></p>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab4-->

            <div class="tab-pane" id="tab5">
				<div class="box-body">
					<!-- 转赠信息 -->
					<?php if($gifts): ?>
					<div class=" col-sm-12 " >
						<table class="table table-striped table-hover">
							<thead><tr role="row">
								<?php 
								$grid_field= array( 'order_items','ge_openid','ge_time','gt_openid','gt_time','status');
								$num_field= count($grid_field);
								foreach($grid_field as $v): 
								?>
									<th role="row"><?php echo $label_gifts[$v]; ?></th>
								<?php endforeach; ?>
							</tr></thead>
							<tbody><tr>
								<?php foreach($gifts as $v): ?><tr>
								<?php foreach($grid_field as $sv): ?>
									<td><?php 
									//赠送记录发现有点问题，未公开转换
									if( isset( $openid_array[$v[$sv]] ) ) echo $openid_array[$v[$sv]];
									else echo (isset($v[$sv]))? $v[$sv]: '-'; 
									//echo (isset($v[$sv]))? $v[$sv]: '-';
									?></td>
									<?php endforeach; ?></tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					
					<div class="col-sm-12 box-footer " >
						<table class="table table-striped table-hover">
							<thead><tr role="row">
								<?php 
								$grid_field= array('id','gs_name','gs_code','gs_unit','order_time','is_add_pack','status');
								$num_field= count($grid_field);
								foreach($grid_field as $v): 
								?>
									<th role="row"><?php echo $label_items[$v]; ?></th>
								<?php endforeach; ?>
							</tr></thead>
							<tbody><tr>
								<?php foreach($items as $v): ?><tr>
								<?php foreach($grid_field as $sv): ?>
									<td><?php echo (isset($v[$sv]))? $v[$sv]: '-'; ?></td>
									<?php endforeach; ?></tr>
									<?php ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<?php else: ?>
						<div style="color:gray;text-align:center;margin:20px auto;">还没任何赠送记录</div>
					<?php endif; ?>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab4-->

            <div class="tab-pane" id="tab7">
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
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab7-->

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
<script>
$('#qrcode-toggle').click(function(){
	$('#qrcode-block').toggleClass('hidden');
});
/**
$('.trans_no').each(function(i,e){
	$(e).keyup(function(){
		var val= this.value;
		$('.trans_no').each(function(si,se){$(se).val(val);});
	});
});
$('.trans_company').each(function(i,e){
	$(e).keyup(function(){
		var val= this.value;
		$('.trans_company').each(function(si,se){$(se).val(val);});
	});
});
*/
<?php if($tab2_header) echo '$("#tab2_header").removeClass("hide");'; ?>
<?php if($tab4_header) echo '$("#tab4_header").removeClass("hide");'; ?>
<?php if($t= $this->input->get('tab')) echo "$('". '#top_tabs a[href="#tab'. $t. '"]'. "').tab('show');"; ?>

function consume_item(id){
	var ipt= $('#consume_code_'+ id);
    re=new RegExp("-","g");
    var code= ipt.val().replace(re, '');
	if( confirm('请注意清点核销数量，您确定要核销该商品吗？') ){
		ipt.val( code );
		$.post("<?php echo EA_const_url::inst()->get_url('*/*/consume_handle'); ?>?id="+ id,{
				'<?php echo config_item('csrf_token_name') ?>':'<?php echo $this->security->get_csrf_hash() ?>',
				'inter_id':'<?php echo $model->inter_id; ?>',
				'order_id':'<?php echo $model->order_id; ?>',
				'code':ipt.val()
			},function(data){
				if(data.status == 1){
					alert('商品已被成功核销！');
					location.reload();
				} else {
					alert(data.message);
				}
		},'json');
	}
}
function sync_order(){
	$.post("<?php echo EA_const_url::inst()->get_url('*/*/sync_order'); ?>",{
			'<?php echo config_item('csrf_token_name') ?>':'<?php echo $this->security->get_csrf_hash() ?>',
			'inter_id':'<?php echo $model->inter_id; ?>',
			'order_id':'<?php echo $model->order_id; ?>' 
		},function(data){
			if(data.status == 1){
				alert('订单同步成功！');
				location.reload();
			} else {
				alert(data.message);
			}
	},'json');
}
function consume_force(id){
	var ipt= $('#consume_code_'+ id);
    re=new RegExp("-","g");
	ipt.val( ipt.val().replace(re, '') );
	if( confirm('此操作无法撤回，您确定要手动核销吗？') ){
		$.post("<?php echo EA_const_url::inst()->get_url('*/*/consume_force'); ?>?id="+ id,{
				'<?php echo config_item('csrf_token_name') ?>':'<?php echo $this->security->get_csrf_hash() ?>',
				'inter_id':'<?php echo $model->inter_id; ?>',
				'order_id':'<?php echo $model->order_id; ?>',
				'code':ipt.val()
			},function(data){
				if(data.status == 1){
					alert('商品已被成功核销！');
					location.reload();
				} else {
					alert(data.message);
				}
		},'json');
	}
}
function separate_item(id){
	if( confirm('商品分拆必须征得用户同意，分拆之后将需要逐个核销，您确定要操作吗？') ){
		$.post("<?php echo EA_const_url::inst()->get_url('*/*/separate_item'); ?>?id="+ id,{
				'<?php echo config_item('csrf_token_name') ?>':'<?php echo $this->security->get_csrf_hash() ?>',
				'inter_id':'<?php echo $model->inter_id; ?>',
				'order_id':'<?php echo $model->order_id; ?>',
				'id':id
			},function(data){
				alert(data.message);
				if(data.status == 1){
					location.reload();
				}
		},'json');
	}
}
</script>
</body>
</html>
