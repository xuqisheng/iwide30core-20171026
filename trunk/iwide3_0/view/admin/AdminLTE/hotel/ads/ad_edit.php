<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
<!--
@font-face {font-family: 'iconfont';
    /*src: url('iconfont.eot');  IE9*/
    /*src: url('iconfont.eot?#iefix') format('embedded-opentype'),  IE6-IE8 */
    src: url('<?php echo base_url('public/fonts/iconfont.woff')?>') format('woff'), /* chrome、firefox */
    url('<?php echo base_url('public/fonts/iconfont.ttf')?>') format('truetype') /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
    /*url('iconfont.svg#svgFontName') format('svg');  iOS 4.1- */
}
.iconfont{font-family: "iconfont";
font-style: normal;
font-size: 1.4em;
vertical-align: middle;
display: inline-block;}
-->
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
<!-- Horizontal Form -->
<div class="box box-info"><!--

    <div class="tabbable "> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-list-alt"></i> 基本信息 </a></li>
        </ul>
<style>
img{max-width:400px}
#upfiles-button{ text-align:center; margin-top:15px; background:#F90; color:#fff;}
</style>
<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/ads/ad_edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('aid'=>$list['id']) ); ?>
                <div class="box-body">
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">广告标题</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="ad_title" id="ad_title" placeholder="广告标题" value="<?php echo $list['ad_title']; ?>">
						</div>
					</div>
                    <div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">广告链接</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="link" id="link" placeholder="广告链接" value="<?php echo $list['ad_link']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">描述</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="des" id="des" placeholder="描述" value="<?php echo $list['des']; ?>">
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-8">
							<select class="form-control" name="status" id="status">
							<?php foreach($status_des as $code=>$des){?>
							<option value="<?php echo $code;?>" <?php if($code==$list['status']){?>selected<?php }?>><?php echo $des;?></option>
							<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group  has-feedback">
						<label class="col-sm-2 control-label">图标</label>
						<?php foreach ($services as $service):?>
						<label class="checkbox-inline">
							<input type="radio" name="ser" value="<?php echo htmlspecialchars ($service->image_url)?>" <?php if($service->image_url == $list['ad_img']): echo ' checked';endif; ?>><em class="iconfont" title="<?php echo $service->info?>"><?php echo $service->image_url?></em>
						</label>
						<?php endforeach;?>
						<label class="checkbox-inline">
							<input id="default" type="radio" name="ser" value=""><em class="iconfont" title="自定义">自定义</em>
						</label>
					</div>
					<div class="form-group  has-feedback hideimg">
						<label class="col-sm-2 control-label">图片路径</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="img" id="img" placeholder="图片路径" value="<?php echo htmlspecialchars ($list['ad_img']); ?>">
						</div>
					</div>
					<div class="form-group  has-feedback hideimg">
						<label class="col-sm-2 control-label">图片</label>
						<div class="col-sm-8" id='sight'>
							<img src="<?php echo $list['ad_img']; ?>" />
						</div>
					</div>
					</div>
                    <!-- /.box-footer -->
                </div>
                        <div class="col-sm-2" style="margin-top:40px">
                            <button type="submit" class="btn btn-info">保存</button>
                        </div>
	<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

        </section><!-- /.content -->
</div>
<!-- /.box -->

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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script>
$(function () {
	$(".wysihtml5").wysihtml5();
	<?php $timestamp = time();?>
		$('#img').parent().append('<input type="file" value="上传图片" id="upfiles">');
		$('#upfiles').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
	    		$('#img').val(res.url);
	    		$('#sight').html('<img  src="'+res.url+'" />');
	    	}
		});
		var img_type= $('input[name=ser]:checked').val();
		if(!img_type){
			$("#default").attr("checked",'checked'); 
		}else{
			$('.hideimg').hide();
		}
		$('input[name=ser]').click(function(){
			if($(this).val()){
				$('.hideimg').hide();
			}else{
				$('#img').val('');
				$('#sight').find('img').attr('src',"");
				$('.hideimg').show();
			}
		});
		$('.uploadify-button-text').html('选择图片')
});
</script>
</body>
</html>
