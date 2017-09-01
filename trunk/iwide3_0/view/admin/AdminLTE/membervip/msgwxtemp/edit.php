<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC); ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.css">
<script src="<?php echo base_url(FD_PUBLIC); ?>/AdminLTE/plugins/colorpickersliders/tinycolor.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC); ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC); ?>/AdminLTE/colorpickersliders/bootstrap.colorpickersliders.nocielch.min.js"></script>
<style>
.des_div {   
  background-color: rgb(190, 190, 190);
  right: 0;
  position: fixed;
  z-index:99;
  padding: 2%;
  width: 15%;
   }
.des_tip{right: 0;
  right: 10%;
  position: fixed;
  z-index:99;
}
.des_tip a{color:black;}
</style>
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
	<div class="box-header with-border">
		<h3 class="box-title"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>信息</h3>
	</div>
<!-- form start -->
	<?php 
	echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <?php foreach ($fields_config as $k=>$v): ?>
				<?php 
                if($check_data==FALSE) echo EA_block_admin::inst()->render_from_element($k, $v, $model); 
                else echo EA_block_admin::inst()->render_from_element($k, $v, $model, FALSE); 
                ?>
			<?php endforeach; ?>
      <?php echo $content_first,$content_remark,$content_str; ?>
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
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <button type="button" class="btn btn-default" onclick="add_content()" id="addContent">添加一个模版内容</button>
                <!-- <button type="button" class="btn btn-default" onclick="del_content()" id="addContent">删除</button> -->
                <button type="submit" class="btn btn-info pull-right">保存</button>
            </div>
		</div>
		
		<!-- /.box-footer -->
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
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script>
var i="<?php echo $i; ?>";
function display_topic_color(theme){
	if( theme=='default'){
		$('#el_theme_color').parent().parent().parent().show();
		$('#el_theme_image').parent().parent().show();
	} else {
		$('#el_theme_color').parent().parent().parent().hide();
		$('#el_theme_image').parent().parent().hide();
	}
}
function add_content(){
	var content = '<div class="form-group del-'+i+'">';
    content += '<label for="el_content" class="col-sm-2 control-label">模版内容</label>';
    content += '<div class="col-sm-2">';
    content += '<select class="form-control " name="content['+i+'][key]" id="el_content_key">';
    content += '<?php echo $temp_option; ?>';
    content += '</select>';
    content += '</div>';
    content += '<div class="col-sm-4">';
	content += '<input type="text" class="form-control " name="content['+i+'][value]" id="el_content_value_" placeholder="内容" value="">';
	content += '</div>';
	content += ' <div class="col-sm-2">';
	content += '<div class=" input-group color">';
	content += '<input type="text" class="form-control cp-preventtouchkeyboardonshow el_theme_color" name="content['+i+'][color]" value="#000000" tabindex="-1" readonly="" style="color: rgb(0, 0, 0); background: rgb(238, 215, 0);">';
	content += '<span class="input-group-addon"><i class="fa fa-dashboard"></i></span>';
	content += '</div>';
	content += '</div><div class="col-sm-2"><button type="button" class="btn btn-default delContent" onclick="del_content('+i+')">删除</button></div>';
	content += '</div><script type="text/javascript">$(".el_theme_color").ColorPickerSliders({size: "sm", placement: "top", hsvpanel: true, previewformat:"hex"});';
	content += '<\/script>';
	$('#content').append(content);
	i++;
	// $(document).scrollTop($(document).height());
}

function del_content(i){
	$(".del-"+i).remove();
}
	
$('#el_page_theme').change(function(){
	display_topic_color(this.value);
});
$(".wysihtml5").wysihtml5();
</script>
<script type="text/javascript">
	$(".el_theme_color").ColorPickerSliders({
    	size: "sm", placement: "top", hsvpanel: true, previewformat:"hex"
    });
</script>
</body>
</html>
