<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
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
<!-- Horizontal Form -->
<div class="box box-info">

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        </ul>
	</div>
<!-- form start -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('app/config/wxapp_save'), array('id'=>'form1','class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
                <div class="box-body">
                	<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label" style="color:#7B7B7B">公用配置</label>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">分享标题</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="common:share_config:share_title" id="share_title" placeholder="分享标题" 
							value="<?php echo isset($common_config['share_config']['share_title'])?$common_config['share_config']['share_title']:'';?>">
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">分享简介</label>
						<div class="col-sm-8">
							<input type="text" class="form-control " name="common:share_config:share_content" id="share_content" placeholder="分享简介" 
							value="<?php echo isset($common_config['share_config']['share_content'])?$common_config['share_config']['share_content']:'';?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">分享页面</label>
						<div class="col-sm-8">
							<select name='common:share_config:share_url' id='share_url' >
							<?php if(!empty($share_pages)){ foreach($share_pages as $code=>$des) {?>
							<option value="<?php echo $code;?>"
								<?php if(!empty($common_config['share_config']['share_url'])&&$common_config['share_config']['share_url']==$code) echo 'selected';?>><?php echo $des;}?></option>
							<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label" style="color:#7B7B7B">会员配置</label>
					</div>
					<div >
						<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">新版会员中心菜单</label>
							<div class="col-sm-8">
							<?php foreach($member_config['center_menu']['menus'] as $code=>$menu) {if (!empty($menu['des'])){?>
								<div>
									<input type='checkbox' name='membercenter_menus' value='<?php echo $code;?>' 
									<?php if(!empty($menu['checked'])){?>checked='checked'<?php }?> /> <?php echo $menu['des'];?> <a href='javascript:void(0);' onclick="up($(this))">上移</a>
								</div>
							<?php }}?>
							<input type="hidden" name="member:center_menu:menus" id="member_center_menu_menus" value="" />
							</div>
						</div>
						<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">会员模式</label>
							<div class="col-sm-8">
								<input type='radio' name='member:member_model:model' value='1' <?php if(!isset($member_config['member_model']['model'])||$member_config['member_model']['model']==1){?>checked='checked'<?php }?> />单体模式（用我们会员）
								<input type='radio' name='member:member_model:model' value='0' <?php if(isset($member_config['member_model']['model'])&&$member_config['member_model']['model']==0){?>checked='checked'<?php }?> />pms对接
							</div>
						</div>
					</div>
				</div>
                <!-- /.box-body -->
                 <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="button" onclick='sub();' class="btn btn-info pull-right">保存</button>
                             <label id='tips' style='color:red;'></label>
                        </div>
                    </div>
            <?php echo form_close() ?>
          </div><!-- /#tab1-->
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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var data={};
var submit_tag=0;
function sub(){
	var check=true;
	if(submit_tag==0){
		submit_tag=1;
		$('#tips').html('提交中');
		if(!check){
			submit_tag=0;
			return false;
		}
		ranges=$('input[name="membercenter_menus"]');
		var member_menu={};
		$.each(ranges,function(i,n){
			if($(n).is(":checked")==true){
				member_menu[$(n).val()]=true;
			}else{
				member_menu[$(n).val()]=false;
			}
		});
		$('#member_center_menu_menus').val(JSON.stringify(member_menu));
		$.post('<?php echo site_url('app/configs/wxapp_save')?>',
			{
				datas:JSON.stringify($('#form1').serializeArray()),
				<?php echo $csrf_token?>:'<?php echo $csrf_value?>'
			},function(data){
				$('#tips').html(data.message);
				submit_tag=0;
			},'json');
	}else{
		$('#tips').html('提交中，请勿重复提交');
	}
}
function up(obj){
	var p = $(obj).parent(); 
	var prev = p.prev(); 
	if (prev.length > 0) { 
		prev.insertAfter(p); 
	}
}
$(function () {
	//CKEDITOR.replace('el_gs_detail');
	$(".wysihtml5").wysihtml5();
	$('.date-pick').datepicker({
		dateFormat: "yymmdd"
	});
	$('.datepicker').datepicker();
});
</script>
</body>
</html>
