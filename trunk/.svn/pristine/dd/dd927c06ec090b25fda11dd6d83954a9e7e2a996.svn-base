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
<div class="box box-info"><!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
	 /.box-header -->

<?php 
echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data',), array($pk=>$model->m_get($pk) ) ); 
?>

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li id="tab_header1" <?php if( !$selectProducts ) echo 'class="active"' ;?>><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
            <li id="tab_header2" <?php if( $selectProducts ) echo 'class="active"' ;?>><a href="#tab2" data-toggle="tab"><i class="fa fa-link"></i> 选择商品</a></li> 
        </ul>

<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane <?php if( !$selectProducts ) echo 'active' ;?>" id="tab1">
				<div class="box-body">
					<?php foreach ($fields_config as $k=>$v): ?>
						<?php if($check_data==FALSE): echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
		                else: echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
		                endif;
		                
		                if($k=='reduce_cost'):
		                ?>
<div class="form-group " style="display: block;">
	<label for="el_bonus_size" class="col-sm-2 control-label"><abbr title="1积分等值x元">积分比例: <br/>(按等级配置)</abbr></label>
	<div class="col-sm-8" id="el_bonus_size">
	    <?php foreach ($bonus_data as $lk=> $lv): ?>
    	<div class="col-sm-12">
        	<div class="col-sm-3"><label for="" ><?php echo $lv['name']; ?></label></div>
    		<div class="col-sm-3"><input class="form-control" value="<?php echo $lv['size']; ?>" name="bonus_size[<?php echo $lk; ?>]" type="text" placeholder="1积分等值x元"></div>
    	</div>
	    <?php endforeach; ?>
	</div>
</div>
					<?php endif; endforeach; ?>
					<!-- 
					<div class="form-group has-feedback" id="el_point_modules">
                    	<label for="el_name" class="col-sm-2 control-label">积分适用模块</label>
                    	<div class="col-sm-8">
                    	    <?php 
                    	    $modules= explode(',', $model->m_get('modules'));
                    	    foreach ($model->get_point_module() as $k=>$v):
                    	    ?>
                    		<label class="col-sm-4" >
                    			<input type="checkbox" name="modules[]" <?php 
                    			if(in_array($k, $modules) ) echo ' checked="checked" '; 
                    			//if($k=='soma' ) echo ' required '; 
                    			?> value="<?php echo $k ?>" > <?php echo $v ?></label>
                    	    <?php endforeach; ?>
                    	</div>
                    </div>
                     -->
					<div class="form-group has-feedback">
                    	<label for="el_name" class="col-sm-2 control-label">适用商品</label>
                    	<div class="col-sm-8">
                    		<label class="col-sm-4" onclick="$('#tab_header2').show();">
                    			<input type="radio" required <?php echo $selectProducts? ' checked="checked" ': '';
                    		?> name="p_type" id="select_product" value="ids"  ><abbr title="下单时仅对所选择的商品计算">选择商品 </abbr></label>
                    		<label class="col-sm-4" onclick="$('#tab_header2').hide();">
                    			<input type="radio" required <?php echo $model->m_get('scope')== Soma_base::STATUS_TRUE ? ' checked="checked" ': '';
                    		?> name="p_type" id="allUse" value="all_use" > <abbr title="">选择全部商品</abbr> </label>
                    	</div>
                    </div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer ">
				    <div class="col-sm-1 col-sm-offset-2">
				    	<input type="hidden" name=card_id id="el_card_id" value="<?php echo $model->m_get('card_id'); ?>">
				        <input type="hidden" name=product_ids id="el_product_ids" value="<?php echo $model->m_get('product_ids'); ?>">
				        <button type="submit" <?php if($disabled) echo'disabled'; ?> class="btn btn-info pull-right save"><?php if($disabled){echo '管理员不能修改';}else{echo '保存';} ?></button>
					</div>
					<span style="color:red;line-height:35px;height:35px;" id="save_notice">规则类型执行顺序：<?php $rules= $model->get_rule_label(); krsort($rules);
					foreach ($rules as $k=>$v){ echo " <i class='fa fa-chevron-right'></i> {$v}"; }
					?></span>
				</div>
				<!-- /.box-footer -->
			</div>

		<div class="tab-pane <?php if( $selectProducts ) echo 'active' ;?>" id="tab2">
			<div class="box-body">
<link href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<?php 
$table_selected= '';
$table_header= '<tr role="row"> <th>#ID</th><th>商品名称</th><th>目前价格</th> </tr>'; 
$grid_data= array();
$product_ids = array();
foreach($products as $k=>$v){
    $grid_data[]= array( $v['product_id'], $v['name'], show_price_prefix($v['price_package'], '￥'), 'DT_RowId'=>$v['product_id']);
    $product_ids[$v['product_id']] = $v;
}
?>
				<div style="height:450px;">
					<div class="col-sm-12">
					    <div class="alert alert-success">
					        <table class="table ">
						        <thead><tr role="row center"> <th colspan="3">已选择商品</th> </tr></thead>
						        <tbody>
						        <?php echo $table_header; ?>
						        <!-- <?php echo $table_selected; ?> -->
						        <?php foreach( $selectProducts as $sk=>$sv ): ?>
							        <tr>
							        	<td><?php echo $sv['product_id']; ?></td>
							        	<td><?php echo $sv['name']; ?></td>
							        	<td><?php if( array_key_exists( $sv['product_id'], $product_ids ) ) echo $product_ids[$sv['product_id']]['price_package']; ?></td>
							        </tr>
						    	<?php endforeach; ?>
						        </tbody>
					        </table>
					    </div>
					</div>
					<div class="col-sm-12">
						<table id="data-grid" class="table table-bordered table-striped table-condensed">
						    <thead><?php echo $table_header; ?></thead>
						</table>
					</div>
				</div>

                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <!-- <button type="submit" <?php if($disabled) echo'disabled'; ?> class="btn btn-info pull-right save">保存</button> -->
                    </div>
                </div>
		</div><!-- /#tab2-->


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


<?php $tr_slt= $model->m_get('product_ids'); ?>; //selected out container.
/** gridjs start **/
<?php 
$click_event = <<<EOF
    var selected_ = selected.join();
    var id = parseInt(this.id);
    var index = $.inArray(id, selected);
    if ( index===-1 ){
        $(this).addClass('bg-gray');
        selected.push( id );
    } else {
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
	<?php require_once VIEWPATH. $tpl .DS .'soma'. DS. 'gridjs_lite.php'; ?>
});
/** gridjs end **/

<?php if( !$selectProducts ): ?>
    $('#tab_header2').hide();
<?php endif; ?>

$("#allUse").click(function(){
	$("#save_notice").html('<span style="color:red;">选择全部商品，将对以后添加的商品也会生效</span>');
});

$("#select_product").click(function(){
	$("#save_notice").html('<span style="color:red;">请到顶部选择商品</span>');
});


var hide_ids= ['el_bonus_size','el_limit_type','el_limit_percent','el_lease_percent','el_over_limit','el_lease_cost','el_reduce_cost'];  
function rule_type_change_triger(){
	var stv = $("#el_settlement").val();
	var rtv = $("#el_rule_type").val();
	for(var i=0;i<hide_ids.length;i++){
        $('#'+hide_ids[i]).parents('.form-group').hide();
    }
    $('#el_point_modules').hide();

	$('button[data-id=el_limit_type]').removeAttr('disabled');
	//$('#el_limit_type').removeAttr('disabled' );
	$('#el_limit_percent').removeAttr('disabled' );
	$('#el_can_use_coupon').removeAttr('disabled' );
	
    <?php foreach ($model->get_settle_label() as $k=> $v): ?>
    if(stv== '<?php echo $k;?>') var b_typ= '<?php echo $v;?>';
    <?php endforeach; ?>
		
	if( rtv == "<?php echo $model::RULE_TYPE_REDUCE;?>" ){
		$('#el_over_limit').parents('.form-group').show();
		$('#el_lease_cost').parents('.form-group').show();
		$('#el_reduce_cost').parents('.form-group').show();
		var title= b_typ+ '_满 '+ $('#el_lease_cost').val()+ ' 立减 '+ parseFloat($('#el_reduce_cost').val()).toFixed(2)+ '元现金';
		
	} else if( rtv == "<?php echo $model::RULE_TYPE_DISCOUNT;?>" ){
		$('#el_over_limit').parents('.form-group').show();
		$('#el_lease_cost').parents('.form-group').show();
		$('#el_reduce_cost').parents('.form-group').show();
		var title= b_typ+ '_满 '+ $('#el_lease_cost').val()+ ' 打 '+ parseFloat($('#el_reduce_cost').val()).toFixed(6)+ '折';
		
	} else if( rtv == "<?php echo $model::RULE_TYPE_POINT;?>" ){
		
		$('#el_limit_type').parents('.form-group').show();
	    $('#el_limit_type').val('<?php echo $model::LIMIT_TYPE_SCOPE; ?>');
		$('[data-id=el_limit_type] .filter-option').html('浮动比例');
		$('button[data-id=el_limit_type]').attr('disabled','');
		
		$('#el_limit_percent').parents('.form-group').show();
		$('#el_lease_percent').parents('.form-group').show();
		<?php if( isset($inter_id) ): ?>
		$('#el_bonus_size').parents('.form-group').show();
		<?php endif; ?>
	    $('#el_point_modules').show();
		var title= b_typ+ '_积分规则'+ '_占订单总额'+ $('#el_lease_percent').val() + '%~'+ $('#el_limit_percent').val()+ '%时适用';

	} else if( rtv == "<?php echo $model::RULE_TYPE_BALENCE;?>" ){
		
		$('#el_limit_type').parents('.form-group').show();
	    $('#el_limit_type').val('<?php echo $model::LIMIT_TYPE_FIXED; ?>');
		$('[data-id=el_limit_type] .filter-option').html('固定比例');
		$('button[data-id=el_limit_type]').attr('disabled','');
		
		$('#el_limit_percent').parents('.form-group').show();
		//$('#el_lease_percent').parents('.form-group').show();
	    //$('#el_point_modules').show();
		var ltv = $("#el_limit_type").val();
		if(ltv==<?php echo $model::LIMIT_TYPE_FIXED; ?>) ltv_l= '固定比例';
		else ltv_l= '浮动比例';
		var title= b_typ+ '_<?php $inter_id= $this->session->get_admin_inter_id(); echo $model->show_name;  ?>规则_ '+ ltv_l + '_'+ $('#el_limit_percent').val()+ '%';

		$('#el_limit_percent').val(100).attr('disabled', '');
		//$('#el_can_use_coupon').val(<?php echo $model::STATUS_CAN_NO; ?>).attr('disabled', '');
	}
	
	$('#el_rule_detail').val(title);
}
rule_type_change_triger();

$("#el_rule_type").change(function(){
	rule_type_change_triger();
});
$("#el_settlement").change(function(){
	rule_type_change_triger();
});
for(var i=0;i<hide_ids.length;i++){
    $('#'+hide_ids[i]).change(function(){
    	rule_type_change_triger();
    });
}

$(".save").click(function(){
	var val = $("#el_rule_type").val();
	if( val == "<?php echo $model::RULE_TYPE_REDUCE;?>" ){
		//如果选择了满减规则，则要判断满减标准和满减金额，满减金额暂时订为不能超过满减标准的30%
		var lease_cost = parseInt( $("#el_lease_cost").val() );
		var reduce_cost = parseInt(  $("#el_reduce_cost").val() );
		if( lease_cost*0.9 < reduce_cost ){
			alert( '满减金额不能超过满减标准的90%' );
			return false;
		}
	}
});
</script>
</body>
</html>
