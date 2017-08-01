<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<style>
.submit-foot {
	background-color: rgb(190, 190, 190);
	bottom: 0;
	width: 100%;
	position: fixed;
	z-index: 99;
}
#file_upload_1-button{ text-align:center;position: relative; top:15px; background:#F90; color:#fff;font-family: 微软雅黑;padding: 3px 28px;font-weight: 400;}

</style>
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
					<!--

    <div class="tabbable "> <!-- Only required for left/right tabs -->
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab"><i
								class="fa fa-list-alt"></i> 基本信息 </a></li>
					</ul>

					<!-- form start -->

					<div class="tab-content">
						<div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/gallery/edit_post'), array('id'=>'subform','class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array('hotel_id'=>$hotel_id) ); ?>
             	<input type="hidden" id='datas' name='datas' /> <input
								type="hidden" id='gid' name='gid'
								value='<?php echo $type['id'];?>' /> <input type="hidden"
								id='deles' name='deles' />
							<div class="box-body">
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">相册名</label>
									<div class="col-sm-8">
										<span class="form-control " style='border: 0;'><?php echo $type['param_value'];?></span>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">图片介绍</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="img_info"
												id="img_info" placeholder="图片介绍" />
										</div>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">排序</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="sort" id="sort"
												placeholder="排序，越大越前" />
										</div>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">上传图片</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="upload_gallery"
												id="upload_gallery" placeholder="" />
											<label id="upfiles" class="add_img"></label>

										</div>
									</div>
								</div>
								<div class="box-footer ">
									<div class="col-sm-4 col-sm-offset-2">
										<button type="button" onclick='save_gallery()'
											class="btn btn-info pull-right">保存图片</button>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label" style="color: #7B7B7B">图片列表<a
										href='javascript:void(0);'
										onclick="$('#gallerys').find('img').hide();">(隐藏)</a></label>
									<hr />
								</div>

								<div id='gallerys'>
					<?php if(!empty($gallery)){?>
						<?php foreach($gallery as $img){?>
						<div key='key' img_id='<?php echo $img['id'];?>'
										style='border-bottom: 1px solid'>
										<a href="javascript:void(0);" onclick="dele(this)">删除</a>
										<div class="form-group  has-feedback">
											<label class="col-sm-2 control-label">图片设置/排序</label>
											<div class="col-sm-8">
												<input type="text" class="form-control " name="info"
													placeholder="图片介绍" value="<?php echo $img['info']; ?>" /> <input
													type="text" class="form-control " name="sort"
													placeholder="排序，越大越前" value="<?php echo $img['sort']; ?>" />
											</div>
										</div>
										<div class="form-group  has-feedback">
											<label class="col-sm-2 control-label">图片路径</label>
											<div class="col-sm-8">
												<a href="javascript:void(0);"
													onclick="$(this).parent().find('img').toggle();"><?php echo $img['image_url'];?></a>
												<img src='<?php echo $img['image_url'];?>' />
											</div>
										</div>
									</div>
						<?php }?>
					<?php }?>
						</div>

								<div class="box-footer submit-foot">
									<div class="col-sm-4 col-sm-offset-2">
										<button type="button" onclick="sub()"
											class="btn btn-info pull-right">全部保存</button>
										<button type="button" onclick="undele()"
											class="btn btn-info pull-left">撤销删除</button>
									</div>
								</div>
								<!-- /.box-footer -->
							</div>
						<?php echo form_close()?>
                <!-- /.box-body -->

						</div>
						<!-- /#tab1-->

					</div>
					<!-- /.tab-content -->
			
			</section>
			<!-- /.content -->
		</div>
		<!-- /.box -->

	</div>
	<!-- /.content-wrapper -->

<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>
<?php

/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>
</div>
	<!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
	<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" />
	<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
	<script
		src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
	<script>
var ele=new Array();
var uploaded=0;
<?php $timestamp = time();?>
$(function () {
	$('.submit-foot').css('bottom',($('.main-footer').outerHeight()));
	$(".wysihtml5").wysihtml5();
	// $('#upload_gallery').parent().append('<input type="file" value="上传图片" id="upfiles">');
	$('#upfiles').uploadify({
		'formData'     : {
			'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
		'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
		'fileObjName': 'imgFile',
		'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
	    'onUploadSuccess' : function(file, data, response) {
		    var res = $.parseJSON(data);
    		$('#upload_gallery').val(res.url);
    		uploaded=0;
    	}
	});
});
function undele(){
	if(ele.length>0){
		t_o=ele.pop();
		$(t_o).attr('key','key');
		$(t_o).show();
	}
}
function dele(obj){
	t_p=$(obj).parent();
	t_p.attr('key','');
	t_p.hide();
	ele.push(t_p);
}
function save_gallery(){
	img_url=$('#upload_gallery').val();
	if(img_url!=''&&uploaded==0){
		info=$('#img_info').val();
		sort=$('#sort').val();
		$.post('<?php echo site_url('hotel/gallery/add_gallery');?>',{
			'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
			image:img_url,
			info:info,
			sort:sort,
			hotel_id:<?php echo $hotel_id;?>,
			gid:$('#gid').val()
		},function(data){
			if(data.s>0){
				uploaded=1;
				temp='<div key="key" img_id="'+data.s+'" style="border-bottom:1px solid"><a href="javascript:void(0);" onclick="dele(this)">删除</a>';
				temp+='<div class="form-group  has-feedback"><label class="col-sm-2 control-label">图片设置</label><div class="col-sm-8">';
				temp+='<input type="text" class="form-control" name="info" placeholder="图片介绍" value="'+info+'" />';
				temp+='<input type="text" class="form-control " name="sort" placeholder="排序，越大越前" value="'+sort+'" /></div></div>';
				temp+='<div class="form-group  has-feedback"><label class="col-sm-2 control-label">图片路径</label><div class="col-sm-8">';			
				temp+='<a href="javascript:void(0);" onclick="'+"$(this).parent().find('img').toggle();"+'">'+img_url+'</a>';		
				temp+='<img  src="'+img_url+'" /></div></div></div>';
				$('#gallerys').append(temp);		
			}
			alert(data.errmsg);
		},'json');
	}
}
function sub(){
	ranges=$("[key='key']");
	var deles='';
	var data={};
	$.each(ranges,function(i,n){
		info=$(n).find('input[name="info"]').val();
		sort=$(n).find('input[name="sort"]').val();
		img_id=$(n).attr('img_id');
		data[img_id]={};
		data[img_id]['id']=img_id;
		data[img_id]['info']=info;
		data[img_id]['sort']=sort;
	});
	json=JSON.stringify(data);
	$('#datas').val(json);
	if(ele.length>0){
		$.each(ele,function(i,n){
			deles+=','+$(n).attr('img_id');
		});
		deles=deles.substring(1);
	}
	$('#deles').val(deles);
	$('#subform').submit();
}
</script>
</body>
</html>
