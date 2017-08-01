<!-- DataTables -->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet"
	href="<?php echo base_url(FD_PUBLIC) ?>/css/font-awesome.min.css">
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
				<h1>
					<small></small>
				</h1>
				<ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
			</section>
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
                <?php echo $this->session->show_put_msg(); ?>
                <div class="box-body">
								<form action="" method="get">
									<div class="row">
										<div class="col-xs-3">
											<div class="form-group">
												<label for="hotel">请选择酒店</label> <select id="hotel"
													name="hotel" onchange="get_rooms(this)"
													class="form-control">
						    <?php foreach ($hotels as $hotel):?><option
														value="<?=$hotel['hotel_id']?>"
														<?php if($hotel_id == $hotel['hotel_id']):?> selected
														<?php endif;?>><?=$hotel['name']?></option><?php endforeach;?>
						    </select>
											</div>
										</div>
										<div class="col-xs-3">
											<div class="form-group">
												<label for="room_id">请选择房型</label> <select id="room_id" name="room_id" onchange="get_codes(this)" class="form-control">
						  							<option value='0'>--不选择--</option>
						   						   <?php if(!empty($room_list)){ foreach($room_list as $rl){ ?>
										  			 <option value='<?php echo $rl['room_id'];?>'<?php if($room_id==$rl['room_id']) echo 'selected';?>><?php echo $rl['name'];?></option>
										  			 <?php }}?>
										   </select>
											</div>
										</div>

									</div>
									<div class="row">
										<div class="col-xs-3">
											<div class="form-group">
												<label for="room_img_type">房间图片类型</label> <select
													id="room_img_type" name="room_img_type"
													class="form-control">
						    <?php if(!empty($room_img_type)){ foreach($room_img_type as $code=>$des){ ?>
						   <option value='<?php echo $code;?>'
														<?php if($rty==$code) echo 'selected';?>><?php echo $des;?></option>
						   <?php }}?>
						    </select>
											</div>
										</div>

									</div>
									<div class="col-xs-3">
										<input type="submit" value="检索" class="btn btn-default">
									</div>
								</form>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->
			<div class="content">
				<div class="form-group  has-feedback">
					<label class="">&nbsp;</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="img_info"
							id="img_info" placeholder="图片介绍" />
					</div>
				</div>
				<div class="form-group  has-feedback">
					<label class="">&nbsp;</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="sort" id="sort"
							placeholder="排序，越大越前" />
					</div>
				</div>
				<div class="form-group  has-feedback">
					<label class="">&nbsp;</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="upload_gallery" placeholder='图片路径'
							id="upload_gallery"  />
					</div>
				</div>
				<div class="box-footer ">
					<div class="col-sm-4 col-sm-offset-2">
						<button type="button" onclick='save_gallery()'
							class="btn btn-info pull-right">保存图片</button>
					</div>
				</div>
			</div>
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
<script
		src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
		<script
		src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var uploaded=0;
<?php $timestamp = time();?>
$(function () {
	$('.submit-foot').css('bottom',($('.main-footer').outerHeight()));
	$(".wysihtml5").wysihtml5();
	$('#upload_gallery').parent().append('<input type="file" value="上传图片" id="upfiles">');
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
    		$('#upload_gallery').val(res.url);
    		uploaded=0;
    	}
	});
});
function get_rooms(obj){
	hotel_id = $(obj).val();
	fill_rooms(hotel_id);
}
function fill_rooms(hotel_id){
	hotel_id=hotel_id;
	var _html = '<option value="0">--选择房型--</option>';
	$('#room_id').html(_html);
	$.getJSON('<?php echo site_url('hotel/prices/room_types')?>',{'hid':hotel_id},function(datas){
		$.each(datas,function(k,v){
			_html += '<option value="' + v.room_id +'" ';
			_html+= '>' + v.name+ '</option>';
		});
		$('#room_id').html(_html);
	},'json');
}
</script>
</body>
</html>
