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
    <!--
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>-->
	<!-- /.box-header -->

    <div class="tabbable " id="top_tabs"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class=""><a href="#tab2" id="el_tab2" data-toggle="tab"><i class="fa fa-image"></i> 焦点图 </a></li>
<?php //if($model->m_get('page_theme') && $model->m_get('page_theme')==$model::THEME_LESS ): ?>
            <li class=""><a href="#tab3" id="el_tab3" data-toggle="tab"><i class="fa fa-cube"></i> 商品 </a></li>
<?php //elseif($model->m_get('page_theme') && $model->m_get('page_theme')==$model::THEME_MULTI ): ?>
            <li class=""><a href="#tab4" id="el_tab4" data-toggle="tab"><i class="fa fa-cubes"></i> 分类 </a></li>
<?php //endif; ?>
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        </ul>

<!-- form start -->
	<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data','id'=>'post-from' ), array($pk=>$model->m_get($pk) ) ); ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
				<div class="box-body">
					<?php if($model->m_get('identity')): ?>

<!-- 二维码弹层 -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:#fff;width:280px;height:340px;margin:220px auto;"> 
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 >请用微信扫一扫</h4>
  </div>
  <div class="modal-body" style="margin:10px 0 15px 15px ;text-align:center;">
    <img id="qrcode-img" src="<?php echo EA_const_url::inst()->get_url("*/*/qrcode_front"). '?ids='. $model->m_get($pk); ?>" />
  </div> 
</div>
						<div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>手机访问专题：</strong> 
<code><?php echo EA_const_url::inst()->get_front_url($model->m_get('inter_id'), 'mall/wap/topic', array('id'=> $model->m_get('inter_id'), 't'=>$model->m_get('identity')));  ?> </code>
&nbsp; 或 &nbsp;<a data-toggle="modal" data-target="#myModal" href="#" >打开</a><code>二维码</code>，用微信扫一扫
							<!-- 或用微信"扫一扫"&nbsp; <img src="{$qrcode}" /> -->
                        </div>
					<?php endif; ?>
					<?php foreach ($fields_config as $k=>$v): ?>
						<?php 
						if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
						else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
						?>
					<?php endforeach; ?>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-4">
							<button type="submit" class="btn btn-info pull-right">保存</button>
						</div>
					</div>
					<!-- /.box-footer -->
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab1-->
        
            <div class="tab-pane" id="tab2">
				<div class="box-body">
					<div class=" col-sm-7 col-sm-offset-2" >
						<br/>
						<select multiple class="form-control" id='el_adv_ids' name="adv_ids[]" style="min-height:500px;">
<?php 
if($model->m_get($pk)){
	$result= $model->get_topic_link('advs');
	$result= $model->array_to_hash($result, 'name', 'id');
	$select_= array_keys($result);
} else {
	$select_= array();
}
foreach($advs as $sk=> $sv):
	if( in_array($sk, $select_) ) $selected= ' selected ';
	else $selected= '';
?>
						  <option value="<?php echo $sk; ?>" <?php echo $selected ?> ><?php echo '('. $sk. ') : '. $sv; ?></option>
<?php endforeach; ?>
						</select>
						<p style="color:gray;line-height:35px;height:35px;">请按住 “ctrdl+鼠标左键” 进行多选选择</p>
					</div>
					<div class=" col-sm-2" style="padding-left:0px;" >
						<br/>
<?php $base_url= EA_const_url::inst()->get_url('*/advs/edit', array('ids'=> '') ); foreach($advs as $sk=> $sv): ?>
						<p style="margin:2px 0;"><a href="<?php echo $base_url. $sk; ?> " target="_blank"><i class="fa fa-hand-o-right"></i> 编辑焦点图（<?php echo $sk; ?>） </a></p>
<?php endforeach; ?>
						<br/>
					</div>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-3">
							<button type="submit" class="btn btn-info pull-right">保存</button>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab2-->
            
            <div class="tab-pane" id="tab3">
				<div class="box-body">
					<div class=" col-sm-7 col-sm-offset-2" >
						<br/>
						<select multiple class="form-control" id='el_good_ids' name="good_ids[]" style="min-height:500px;">
<?php 
if($model->m_get($pk)){
	$result= $model->get_topic_link('goods');
	$result= $model->array_to_hash($result, 'gs_name', 'gs_id');
	$select_= array_keys($result);
} else {
	$select_= array();
}
foreach($goods as $sk=> $sv): 
	if( in_array($sk, $select_) ) $selected= ' selected ';
	else $selected= '';
?>
						  <option value="<?php echo $sk; ?>" <?php echo $selected ?> ><?php echo '('. $sk. ') : '. $sv; ?></option>
<?php endforeach; ?>
						</select>
						<p style="color:gray;line-height:35px;height:35px;">请按住 “ctrl+鼠标左键” 进行多选选择</p>
					</div>
					<div class=" col-sm-2" style="padding-left:0px;" >
						<br/>
<?php $base_url= EA_const_url::inst()->get_url('*/goods/edit', array('ids'=> '') ); foreach($goods as $sk=> $sv): ?>
						<p style="margin:1px 0;"><a href="<?php echo $base_url. $sk; ?> " target="_blank"><i class="fa fa-hand-o-right"></i> 编辑商品（<?php echo $sk; ?>） </a></p>
<?php endforeach; ?>
						<br/>
					</div>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-3">
							<button type="submit" class="btn btn-info pull-right">保存</button>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab3-->
            
            <div class="tab-pane" id="tab4">
				<div class="box-body">
					<div class=" col-sm-7 col-sm-offset-2" >
						<br/>
						<select multiple class="form-control" id='el_category_ids' name="category_ids[]" style="min-height:500px;">
<?php 
if($model->m_get($pk)){
	$result= $model->get_topic_link('category');
	$result= $model->array_to_hash($result, 'cat_name', 'cat_id');
	$select_= array_keys($result);
} else {
	$select_= array();
}
foreach($category as $sk=> $sv): 
	if( in_array($sk, $select_) ) $selected= ' selected ';
	else $selected= '';
?>
						  <option value="<?php echo $sk; ?>" <?php echo $selected ?> ><?php echo '('. $sk. ') : '. $sv; ?></option>
<?php endforeach; ?>
						</select>
						<p style="color:gray;line-height:35px;height:35px;">请按住 “ctrl+鼠标左键” 进行多选选择</p>
					</div>
					<div class=" col-sm-2" style="padding-left:0px;" >
						<br/>
<?php $base_url= EA_const_url::inst()->get_url('*/category/edit', array('ids'=> '') ); foreach($category as $sk=> $sv): ?>
						<p style="margin:1px 0;"><a href="<?php echo $base_url. $sk; ?> " target="_blank"><i class="fa fa-hand-o-right"></i> 编辑分类（<?php echo $sk; ?>） </a></p>
<?php endforeach; ?>
						<br/>
					</div>
					<div class="box-footer ">
						<div class="col-sm-4 col-sm-offset-3">
							<button type="submit" class="btn btn-info pull-right">保存</button>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
            </div><!-- /#tab4-->
            
        </div><!-- /.tab-content -->

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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
<script>
$(function () {
//CKEDITOR.editorConfig({
//	'filebrowserUploadUrl': "actions/ckeditorUpload"
//});
CKEDITOR.replace('el_gs_detail');
});

$('#post-from').submit(function(i){
	if( $('#el_adv_ids').val()==null){
		$('#top_tabs a[href="#tab2"]').tab('show');
		alert('请至少选择1个焦点图');
		return false;
	}
	<?php //if($model->m_get('page_theme') && $model->m_get('page_theme')==$model::THEME_LESS ): ?>
	if( 
		($('#el_page_theme').val()=='<?php echo $model::THEME_DEFAULT ?>' && $('#el_good_ids').val()==null)
		|| ($('#el_page_theme').val()=='<?php echo $model::THEME_LESS ?>' && $('#el_good_ids').val()==null)
	){
		$('#top_tabs a[href="#tab3"]').tab('show');
		alert('为保障显示效果，请至少选择1个显示商品');
		return false;
	}
	return true;
	<?php //elseif($model->m_get('page_theme') && $model->m_get('page_theme')==$model::THEME_MULTI ): ?>
	if( $('#el_page_theme').val()=='<?php echo $model::THEME_MULTI?>' && $('#el_category_ids').val()==null){
		$('#top_tabs a[href="#tab4"]').tab('show');
		alert('为保障显示效果，请至少选择1个显示分类');
		return false;
	}
	return true;
	<?php //endif; ?>
});
function display_topic_color(theme){
	if( theme=='<?php echo $model::THEME_DEFAULT; ?>'){
		$('#el_theme_color').parent().parent().parent().show();
		$('#el_theme_image').parent().parent().show();
	} else {
		$('#el_theme_color').parent().parent().parent().hide();
		$('#el_theme_image').parent().parent().hide();
	}
}
$('#el_page_theme').change(function(){
	display_topic_color(this.value);
});

<?php if($page_theme=$this->input->post('page_theme')): ?>
	display_topic_color('<?php echo $page_theme; ?>');
<?php else: ?>
	display_topic_color('<?php echo $model->m_get("page_theme"); ?>');
<?php endif; ?>
</script>

</body>
</html>
