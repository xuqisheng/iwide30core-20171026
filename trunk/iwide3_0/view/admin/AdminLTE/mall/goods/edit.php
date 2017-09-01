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

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li id="top_tabs_1" class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
			<?php if($model->m_get($pk)): ?>
            <li id="top_tabs_2"><a href="#tab2" data-toggle="tab"><i class="fa fa-image"></i> 产品相册 </a></li>
			<!--
            <li id="top_tabs_3"><a href="#tab3" data-toggle="tab"><i class="fa fa-link"></i> 关联分类 </a></li>
            <li id="top_tabs_4"><a href="#tab4" data-toggle="tab"><i class="fa fa-link"></i> 关联皮肤 </a></li>
			-->
			<?php endif; ?>
            <li id="top_tabs_5"><a href="#tab5" data-toggle="tab"><i class="fa fa-money"></i> 关联卡券 </a></li>
        </ul>

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk) ) ); ?>
                <div class="box-body">
                    <?php foreach ($fields_config as $k=>$v): ?>
                        <?php 
                        if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                        else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                        ?>
                    <?php endforeach; ?>

                    <div class="form-group ">
                        <label for="el_gs_detail" class="col-sm-2 control-label">详细介绍</label>
                        <div class="col-sm-8">
                            <textarea class="form-control wysihtml5" name="gs_detail" id="el_gs_detail" placeholder="描述" rows="10" cols="80"><?php echo $detail_field; ?></textarea>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
							<input type="hidden" id="el_wx_card_id_" name="wx_card_id_" value="<?php echo $model->m_get('wx_card_id'); ?>" />
                            <button type="submit" class="btn btn-info pull-right">保存</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
    <?php if($model->m_get($pk)): ?>
            <div class="tab-pane" id="tab2">
                <div class="box-body">
			<?php echo form_open( EA_const_url::inst()->get_url('*/*/edit_focus'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>
					<br/>
					<div class="form-group ">
						<label for="el_gallery" class="col-sm-2 control-label">上传图片</label>
						<div class="col-sm-8">
							<input type="file" class="form-control " name="gallery" id="el_gallery" value="">
							<span class="input-group-addon">图片大小必须< 1MB</span>
						</div>
					</div>
					<div class="form-group ">
						<label for="el_gry_desc" class="col-sm-2 control-label">图片描述</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="gry_desc" id="el_gry_desc" value="">
						</div>
					</div>
					<br/>

<?php if( count($gallery)>0 ): ?>
					<div id="myCarousel" class="carousel slide col-sm-8 col-sm-offset-2 ">
						<ol class="carousel-indicators">
						<?php $k=0; foreach($gallery as $v): ?>
							<li data-target="#myCarousel" data-slide-to="<?php echo $k ?>" class="<?php echo ($k==0)? 'active': '';?> "></li>
						<?php $k++; endforeach; ?>
						</ol>
						<!-- Carousel items -->
						<div class="carousel-inner">
						<?php $k=0; foreach($gallery as $v): ?>
							<div class="item <?php echo ($k==0)? 'active': '';?>">
								<img src="<?php echo $v['gry_url'] ?>" />
								<div class="carousel-caption"><p><?php echo $v['gry_desc'] ?></p></div>
							</div>
						<?php $k++; endforeach; ?>
						</div>
						<!-- Carousel nav -->
						<a class="carousel-control left" href="#myCarousel" data-slide="prev"><i class="fa fa-chevron-left"></i>&nbsp;</a>
						<a class="carousel-control right" href="#myCarousel" data-slide="next"><i class="fa fa-chevron-right"></i>&nbsp;</a>
					</div>
					<div class="carousel slide col-sm-2">
						<?php $k=1; foreach($gallery as $v): ?>
							<div class="checkbox"><input type="checkbox" name="del_gallery[]" value="<?php echo $v['gry_id'] ?>" /> 删除第<?php echo $k ?>张？</div>
						<?php $k++; endforeach; ?>
						<br/><br/><button type="submit" class="btn btn-info" >保存</button>
					</div>
<?php else: ?>
                <div class="box-footer ">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button type="submit" class="btn btn-info pull-right">保存</button>
                    </div>
                </div>
<?php endif; ?>

	<?php echo form_close() ?>
                </div>
                <!-- /.box-body -->
            </div><!-- /#tab2-->
	<?php endif; ?>
            
<?php
$loading= base_url(FD_PUBLIC). '/'. $tpl. '/dist/img/loading.gif'; 
?>
            <div class="tab-pane " id="tab5">
                <div class="box-body">
                    <div class="form-group " id="cardlist_selector">
						<div style="text-align:center;width:100%;padding-top:10px;"><img src="<?php echo $loading ?>" width="50"/>
						&nbsp;&nbsp;正在读取微信接口数据，请耐心等候...</div>
					</div>
					<br/><br/><br/><br/>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box-body -->
            </div><!-- /#tab5-->

            <div class="tab-pane" id="tab3">
                <div class="box-body">
                </div>
                <!-- /.box-body -->
            </div><!-- /#tab3-->

            <div class="tab-pane" id="tab4">
                <div class="box-body">
                </div>
                <!-- /.box-body -->
            </div><!-- /#tab4-->
        </div><!-- /.tab-content -->

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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script>
<?php 
//FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
$floder= $model->m_get('inter_id')? $model->m_get('inter_id'): 'inter_id';
$subpath= $floder. '|mall|goods|gs_detail'; //基准路径定位在 /public/media/ 下
$params= array(
	't'=>'images',
	'p'=>$subpath,
	'token'=>'test'	//再完善校验机制
);
?>
$(function () {
	CKEDITOR.replace('el_gs_detail',{
	  //filebrowserBrowseUrl:'../ckfinder/ckfinder.html',
      //filebrowserImageBrowseUrl:'<?php echo EA_const_url::inst()->get_front_url($model->m_get('inter_id'), 'basic/upload/browse', $params, 'file' ); ?>',
      //filebrowserFlashBrowseUrl:
      
	  //filebrowserUploadUrl:'../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
      filebrowserImageUploadUrl:'<?php echo EA_const_url::inst()->get_url('basic/upload/do_upload', $params); ?>'
      //filebrowserFlashUploadUrl:
	});
	//$(".wysihtml5").wysihtml5();
});
$('#el_gs_unit').blur(function(){
	$(this).val($(this).val().replace(/[~'!?<>@#$%^&*()-+_=:\d]/g, ""));
});

<?php 
$token_name= config_item('csrf_token_name');
$token_val= $this->security->get_csrf_hash();
$ajax_cardlist= EA_const_url::inst()->get_url('*/*/ajax_cardlist');
$ajax_cardsave= EA_const_url::inst()->get_url('*/*/ajax_cardsave');
?>
var cardoffset= 0;
var cardcount= 50;
var is_sending = false;
var cardstatus= ["CARD_STATUS_VERIFY_OK","CARD_STATUS_DISPATCH"];
$('#top_tabs a[href="#tab5"]').on('shown.bs.tab', function (e) {
	$('#cardlist_selector').html('<div style="text-align:center;width:100%;padding-top:10px;"><img src="<?php echo $loading ?>" width="50"/>&nbsp;&nbsp;正在读取微信接口数据，请耐心等候...</div>');
	if(is_sending==true){
		alert('数据发送中，请等待！');
	} else {
		is_sending= true;
	}
	$.post("<?php echo $ajax_cardlist ?>", {"offset":cardoffset,"count":cardcount,"status_list":cardstatus,"<?php echo $token_name ?>": "<?php echo $token_val ?>"}, function(obj){
		is_sending = false;

		if(obj.status==1 ){
			var radio_html= '<div class="form-group" style="text-align:center;color:red;margin:10px auto;" >点选后自动保存</div>';
			var ccardId= '<?php echo $model->m_get('wx_card_id') ?>';
			$.each(obj.data, function (i,v) {
				if(ccardId==i) {
					var slt= ' checked="checked" ';
					var slt_color= ' style="font-weight:bold;color:red;" ';
				} else {
					var slt= '';
					var slt_color= '';
				}
				radio_html+= '<div class="col-sm-4" '+ slt_color+ '>&nbsp; <i class="fa fa-money">&nbsp;</i> <input type="radio" name="wx_card_id" value="'+ i+ '" '+ slt+ ' />'+ v + '</div>';
			} );
			$('#cardlist_selector').html(radio_html);
			
			$('#cardlist_selector input').each(function(i,e){
				$(e).bind('click', function(ev){
					cardipt_save(e);
				});
			});

		} else {
			//alert(obj.message);
			$('#cardlist_selector').html(obj.message);
		}
	},'json');
})
function cardipt_save(ipt){
	var card_id= ipt.value;
	var gs_id= "<?php echo $model->m_get('gs_id') ?>";
	$.post("<?php echo $ajax_cardsave ?>", {"gs_id":gs_id,"card_id":card_id,"<?php echo $token_name ?>": "<?php echo $token_val ?>"}, function(obj){
		if(obj.status==1 ){
			$('#el_wx_card_id').val(card_id);
			$('#el_wx_card_id_').val(card_id);
			alert(obj.message);
		} else {
			alert(obj.message);
		}
	},'json');
}

function display_gs_tabs(v){
	if( v=='<?php echo $model::GS_TYPE_2; ?>'){
		$('#top_tabs_5').show();
		$('#el_wx_card_id').parents('.form-group').show();
		$('#el_card_use_type').parents('.form-group').show();
		
	} else {
		$('#top_tabs_5').hide();
		$('#el_wx_card_id').parents('.form-group').hide();
		$('#el_card_use_type').parents('.form-group').hide();
	}
}
$('#el_is_virtual').change(function(){
	display_gs_tabs(this.value);
});

<?php if($is_virtual= $this->input->post('el_is_virtual')): ?>
	display_gs_tabs('<?php echo $is_virtual; ?>');
<?php else: ?>
	display_gs_tabs('<?php echo $model->m_get("is_virtual"); ?>');
<?php endif; ?>

<?php if($t= $this->input->get('tab')) echo "$('". '#top_tabs a[href="#tab'. $t. '"]'. "').tab('show');"; ?>
</script>
</body>
</html>
