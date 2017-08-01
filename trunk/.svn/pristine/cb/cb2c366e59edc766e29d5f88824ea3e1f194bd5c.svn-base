<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<style>
.con_right >div >div:nth-of-type(1){text-align:center;}
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
<div class="over_x">
	<div class="content-wrapper" style="min-width:1050px;">
		<div class="banner bg_fff p_0_20"><?php echo $breadcrumb_html; ?></div>
		<div class="contents">
		<?php echo $this->session->show_put_msg(); ?>
		<?php $pk= $model->table_primary_key(); ?>
			<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$this->input->get('ids') ) ); 
	?>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
					<input type="hidden" name="inter_id" value="<?php echo $this->session->get_admin_inter_id ();?>">
					<div class="con_right">
						<?php if(!$model->has_data()){?>
							<div class="hottel_name ">
								<div class="required">选择酒店</div>
								<div class="input_txt">
									<select style="width:450px;" name="hotel_id">
										<?php if(!empty($fields_config['hotel_id']['select'])){?>
											<?php foreach ($fields_config['hotel_id']['select'] as $k => $v){?>
												<option value="<?php echo $k;?>"><?php echo $v;?></option>
											<?php }?>	
										<?php }?>
									</select>
								</div>
							</div>
						<?php }?>
						<div class="hottel_name ">
							<div class="required">房型名称</div>
							<div class="input_txt"><input required placeholder="建议10字内" type="text" name="name" value="<?php if($model->has_data()) echo $model->m_get('name');?>"/></div>
						</div>
						<div class="address">
							<div class="required">面积</div>
							<div class="input_txt"><input type="text" required name="area" value="<?php if($model->has_data()) echo $model->m_get('area');?>"/></div>
						</div>
						<div class="jingwei">
							<div class="">床数</div>
							<div class="input_txt"><input type="text" name="bed_num" value="<?php if($model->has_data()) echo $model->m_get('bed_num');?>"/></div>
						</div>
						<div class="jingwei">
							<div class="">房型webID(非必填)</div>
							<div class="input_txt"><input type="text" name="webser_id" value="<?php if($model->has_data()) echo $model->m_get('webser_id');?>"/></div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span>房型介绍</div>
					<div class="con_right">
						<div class="hottel_name clearfix">
							<div class="float_left required">房型详情</div>
							<div class="input_txt">
								<textarea required placeholder="建议100字内" class="introduce" name="book_policy"><?php if($model->has_data()) echo $model->m_get('book_policy');?></textarea>
							</div>
						</div>
						<div class="jingwei">
							<div class="">简介</div>
							<div class="input_txt"><input type="text" placeholder="显示在房型名称下面，建议8字内" name="sub_des" value="<?php if($model->has_data()) echo $model->m_get('sub_des');?>"/></div>
						</div>
						<div class="jingwei clearfix">
							<div class="float_left required">介绍图片</div>
							<input required class="form-control hiddenimg" name="room_img" id="el_intro_img" placeholder="酒店介绍图" value="<?php if($model->has_data()) echo $model->m_get('room_img');?>">
							<div class="input_txt file_img_list" style="padding-left:4px;">
								<?php if($model->has_data() && $model->m_get('room_img')){?>
									<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?php echo $model->m_get('room_img');?>"/>
										<div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
									</div>
								<?php }?>
								<label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
							</div>
							<div style="color: #DDD;opacity:1;">建议尺寸：200*200，不超过200KB</div>
						</div>
						<div class="hotel_star clearfix">
							<div class="float_left">房型服务</div>
							<div class="input_txt input_checkbox">
								<?php foreach ($services as $service):?>
								<div>
									<input type="checkbox" id="<?php echo $service->info?>" name="ser[]" value="<?php echo htmlspecialchars ($service->image_url)?>" <?php if(in_array($service->image_url,$room_ser)): echo ' checked';endif; ?>/>
									<label class="iconfont" for="<?php echo $service->info?>" title="<?php echo $service->info?>"><?php echo $service->image_url?></label>
								</div>
								<?php endforeach;?>
							</div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_4caf50"></span>价格&排序</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="required">房量</div>
							<div class="input_txt"><input required type="text" name="nums" value="<?php if($model->has_data()) echo $model->m_get('nums');?>" /></div>
						</div>
						<div class="hottel_name">
							<div class="required">房型排序</div>
							<div class="input_txt"><input required type="text" placeholder="数字越大，排序越前" name="sort" value="<?php if($model->has_data()) echo $model->m_get('sort');?>" /></div>
						</div>
						<div class="jingwei">
							<div class="">房型状态</div>
							<div class="input_txt" >
								<select style="width:450px;" name="status">
									<?php if(!empty($fields_config['status']['select'])){?>
										<?php foreach ($fields_config['status']['select'] as $k => $v){?>
											<option <?php if($model->has_data() && $model->m_get('status') == $k) echo 'selected';?> value="<?php echo $k;?>"><?php echo $v;?></option>
										<?php }?>	
									<?php }?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;">
					<button type="submit" class="fom_btn">保存房型</button>
				</div>
			<?php echo form_close() ?>
		</div>
	</div>
</div>

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
</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#file').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			//'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'fileObjName': 'imgFile',
			'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
			'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
			'height':77,
			'width':77,
			'fileSizeLimit':'200', //限制文件大小
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#el_intro_img').val(res.url);
        		$('.add_img_box').remove();
	             $(".file_img_list").prepend($('<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+res.url+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
	            $('.add_img_box').delegate('.img_close','click',function(){
					$(this).parent().remove();
					$("#el_intro_img").val('');
				})
		        
        	}
		});
        $('.add_img_box').delegate('.img_close','click',function(){
			$(this).parent().remove();
			$("#el_intro_img").val('');
		})
	});
</script>