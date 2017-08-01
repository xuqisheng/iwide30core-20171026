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
$revt= $model->m_get('reserve_date');
$current_status= $model->m_get('status');
if( in_array($current_status, $model->can_shipped_status() ) ){
    $can_shipped= TRUE;
} else 
    $can_shipped= FALSE;
?>

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
			<?php if( FALSE ): //发货后显示物流信息 ?> 
            <li><a href="#tab2" data-toggle="tab"><i class="fa fa-link"></i> 物流跟踪信息</a></li> 
			<?php endif; ?>
            <li><a href="#tab3" data-toggle="tab"><i class="fa fa-ambulance"></i> 配送商品  </a></li>
        </ul>



<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="box-body">
					<?php foreach ($fields_config as $k=>$v): ?>
						<?php if($k=='distributor'): ?>
    					<div class='form-group '>
    					   <label for='el_distributor' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
    					   <div class='col-sm-8'>
            					<select <?php echo ($can_shipped)? ' required ': ' disabled ';?> class='form-control selectpicker show-tick' data-live-search='true' name='distributor' id='el_distributor'>
            					   <?php echo $model->get_distributor_select_html($model->m_get('distributor'), FALSE); ?>
            					</select>
        					</div>
                        </div>
						<?php 
		                elseif($k=='tracking_no'): ?>
    					<div class='form-group '>
    					   <label for='el_tracking_no' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
    					   <div class='col-sm-8'>
            					<input <?php echo ($can_shipped)? ' required ': ' disabled ';?> type="text" class="form-control" name="tracking_no" id="el_tracking_no" placeholder="<?php echo $v['label']; ?>" value="<?php echo $model->m_get($k); ?>">
        					</div>
                        </div>
                        <?php 
		                elseif($k=='remark'): ?>
    					<div class='form-group '>
    					   <label for='el_remark' class='col-sm-2 control-label'><?php echo $v['label']; ?></label>
    					   <div class='col-sm-8'>
            					<input type="text" class="form-control" name="remark" id="el_remark" placeholder="<?php echo $v['label']; ?>" value="<?php echo $model->m_get($k); ?>">
        					</div>
                        </div>
						<?php 
						elseif( in_array($k, array('remote_ip','post_time','post_admin',) ) && $can_shipped ): continue;
		                elseif($check_data==FALSE): echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
		                else: echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
		                endif;
		                ?>
					<?php endforeach; ?>
					
				</div>
				<!-- /.box-body -->
				<div class="box-footer ">
				    <div class="col-sm-3 col-sm-offset-2">
					<?php if( $revt && strtotime($revt)> time() ): ?>
						<button type="submit" disabled class="btn btn-info pull-right">未到预约时间</button>
					<?php else: ?>
    				    <?php if($can_shipped): ?>
    				    	<input type="hidden" name="consumer_id" value="<?php echo $model->m_get('consumer_id'); ?>" />
						    <button type="submit" class="btn btn-info pull-left">发货</button>
    				    <?php //else: // 20160818 luguihong 地址备注修改：old=>发货后才能编辑，new=>邮寄申请才能编辑 ?>
						    <button type="button" class="btn pull-right btn-info" style="display: none" id="remark_smt_btn">保存备注</button>
					    <?php endif; ?>
					<?php endif; ?>
					</div>
					<span style="color:red;line-height:35px;height:35px;">* 客户地址信息不允许修改，如有调整请记录在”地址备注“。</span>
				</div>
				<!-- /.box-footer -->
			</div>


		<div class="tab-pane" id="tab2">
			<div class="box-body">
			    <!-- 
			    <iframe frameborder=0 width="100%" height="500" marginheight=0 marginwidth=0 scrolling=no src="http://www.kuaidi100.com/"></iframe>
			     -->
			</div>
			<!-- /.box-body -->
		</div><!-- /#tab2-->
			

		<div class="tab-pane" id="tab3">
			<div class="box-body">
				<div class=" col-sm-12 " >
				<?php 
				if($current_inter_id): 
				    $list_field= array('face_img','name','compose','consumer_qty','price_package','expiration_date','status',); ?>
					<table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php foreach ($list_field as $v):
                            if( isset($item_h[$v]) ) echo "<th>{$item_h[$v]}</th>";
                    endforeach; ?></tr></thead>
                    <tbody><?php foreach ($item_i as $k=>$v): ?><tr>
                        <?php foreach ($list_field as $sv):
                            if( isset($v[$sv]) ) echo "<td>{$v[$sv]}</td>";
                        endforeach; ?></tr>
                    <?php endforeach; ?></tbody>
                    </table>
                <?php 
                else:
                    echo '<div style="color:gray;">请用商户账号登陆。</div>';
                endif;
                ?>
			    </div>
			</div>
			<!-- /.box-body -->
		</div><!-- /#tab3-->
			

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

var tmp = $('#el_remark').val();
$('#el_remark').bind('input propertoty',function(){
	$('#remark_smt_btn').show();
	if(tmp==$(this).val()) $('#remark_smt_btn').hide();
});

$('#remark_smt_btn').click(function(){
	var c_id= <?php echo $model->m_get($pk) ?>;
	var c_remark= $('#el_remark').val();
	$.post("<?php echo Soma_const_url::inst()->get_url('*/*/remark'); ?>?id="+ c_id, {'remark':c_remark, 'inter_id':'<?php echo $model->inter_id; ?>',
		'<?php echo config_item('csrf_token_name') ?>':'<?php echo $this->security->get_csrf_hash() ?>' },
    	function(data){
    		if(data.status == 1){
    			$('#remark_smt_btn').hide();
    			tmp=$(this).val();
    		} else {
    			alert(data.message);
    		}
    },'json');
});

</script>
</body>
</html>
