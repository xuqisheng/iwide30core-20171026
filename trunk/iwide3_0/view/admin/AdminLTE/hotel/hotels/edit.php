<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
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
<?php echo $this->session->show_put_msg(); ?>
<?php $pk= $model->table_primary_key(); ?>

		<div class="contents">
			<?php echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
					<input type="hidden" name="inter_id" value="<?php echo $this->session->get_admin_inter_id ();?>">
					<div class="con_right">
						<div class="hottel_name ">
							<div class="required">酒店名称</div>
							<div class="input_txt"><input placeholder="建议10个字内" required name="name" type="text" value="<?php if($model->has_data()) echo $model->m_get('name');?>" /></div>
						</div>
						<div class="region">
							<div class="required">酒店区域</div>
							<div class="input_txt">
								<select name='country' required>
									<option>中国</option>
								</select>
								<select id="sheng" name='province' required>
									<option>省份选择</option>
								</select>
								<select id='shi' name="city" required>
									<option>地市选择</option>
								</select>
								<select id='xian' name="area">
									<option>区县选择</option>
								</select>
							</div>
						</div>
						<div class="address">
							<div class="required">酒店地址</div>
							<div class="input_txt"><input placeholder="建议14个字内"  required type="text" name="address" value="<?php if($model->has_data()) echo $model->m_get('address');?>" /></div>
						</div>
						<div class="">
							<div class="required">电话</div>
							<div class="input_txt"><input required type="text" name="tel" value="<?php if($model->has_data()) echo $model->m_get('tel');?>" /></div>
						</div>
						<div class="jingwei">
							<div class="required">经纬信息</div>
							<div class="input_txt"><input required type="text" placeholder="输入格式：经度,纬度，如113.342081,23.137749" name="jingwei" value="<?php if($model->has_data()) echo $model->m_get('longitude').','.$model->m_get('latitude');?>" /></div>
						</div>
						<div class="hotel_star">
							<div class="required">酒店星级</div>
							<div class="input_txt input_radio">
								<?php if(!empty($fields_config['star']['select'])){?>
									<?php foreach ($fields_config['star']['select'] as $k => $v){?>
										<div>
											<input type="radio" id="star_<?php echo $k;?>" name="star" value="<?php echo $k;?>" <?php if(($model->has_data() && $model->m_get('star') == $k) ||(!$model->has_data()&&$k==0) ) echo 'checked';?>/>
											<label for="star_<?php echo $k;?>"><?php echo $v;?></label>
										</div>
									<?php }?>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span><font color="red">*</font>酒店介绍</div>
					<div class="con_right">
						<div class="address">
							<div class="float_left">简短描述</div>
							<div class="input_txt">
								<input placeholder="在酒店详情的酒店标题下显示，建议20字内。" class="" name="short_intro" value="<?php if($model->has_data()) echo $model->m_get('short_intro');?>" />
							</div>
						</div>
						<div class="hottel_name clearfix">
							<div class="float_left"><font color="red">*</font>酒店详情</div>
							<div class="input_txt">
								<textarea required placeholder="在酒店详情的酒店介绍里显示，建议200字内。" class="introduce" name="intro"><?php if($model->has_data()) echo $model->m_get('intro');?></textarea>
							</div>
						</div>
						<div class="address">
							<div class="required">预定说明</div>
							<div class="input_txt"><input placeholder="在酒店房型页的底部和提交订单页显示。" required type="text" name="book_policy" value="<?php if($model->has_data()) echo $model->m_get('book_policy');?>"/></div>
						</div>
						<div class="address">
							<div class="">酒店特色</div>
							<div class="input_txt"><input type="text" placeholder="在酒店列表页显示，建议10个字内。" name="characters" value="<?php if($model->has_data()) echo $model->m_get('characters');?>"/></div>
						</div>
						<div class="jingwei clearfix" style="height: 80px;">
							<div class="float_left required">介绍图片</div>
							<input required class="form-control hiddenimg" name="intro_img" id="el_intro_img" placeholder="酒店介绍图" value="<?php if($model->has_data()) echo $model->m_get('intro_img');?>">
							<div class="input_txt file_img_list" style="padding-left:4px;">
							<?php if($model->has_data() && $model->m_get('intro_img')){?>
								<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?php echo $model->m_get('intro_img');?>"/>
									<div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
								</div>
							<?php }?>
								<label id="file" class="add_img"><input id="uploadify" class="display_none file_img" type="file" /></label>
							</div>
							<div style="color: #DDD;opacity:1;">建议尺寸：200*200，不超过200KB</div>
						</div>
						<div class="hotel_star clearfix">
							<div class="float_left">酒店服务</div>
							<div class="input_txt input_checkbox">
								<?php foreach ($services as $service):?>
								<div>
									<input type="checkbox" id="<?php echo $service->info?>" name="ser[]" value="<?php echo htmlspecialchars ($service->image_url)?>" <?php if(in_array($service->image_url,$hotel_ser)): echo ' checked';endif; ?>/>
									<label class="iconfont" for="<?php echo $service->info?>" title="<?php echo $service->info?>"><?php echo $service->image_url?></label>
								</div>
								<?php endforeach;?>
							</div>
						</div>
						<div class="hotel_star clearfix">
							<div class="float_left">酒店周边</div>
		                    <div class="col-sm-8">
		                        <textarea class="form-control wysihtml5" name="arounds" id="arounds" placeholder="酒店周边" rows="10" cols="80"><?php echo $arounds; ?></textarea>
		                    </div>
			            </div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_4caf50"></span>其他</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">酒店排序</div>
							<div class="input_txt"><input placeholder="数字越大，排序越前" type="text" name="sort" value="<?php if($model->has_data()) echo $model->m_get('sort');?>"/></div>
						</div>
						<div class="jingwei">
							<div class="">酒店状态</div>
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
						<div class="jingwei">
							<div class="">发票服务</div>
							<div class="input_txt" >
								<select style="width:450px;" name="invoice">
									<?php if(!empty($fields_config['invoice']['select'])){?>
										<?php foreach ($fields_config['invoice']['select'] as $k => $v){?>
											<option <?php if($model->has_data() && $model->m_get('invoice') == $k) echo 'selected';?> value="<?php echo $k;?>"><?php echo $v;?></option>
										<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="jingwei">
							<div class="">退房时间范围</div>
		                    <div class="input_txt">
		                        <select  name="retreat_time[start]" placeholder="退房开始时间">
		                        	<option <?php if($retreat_time['start']=='0000') echo 'selected';?> value="0000">00:00</option>
		                        	<option <?php if($retreat_time['start']=='0100') echo 'selected';?> value="0100">01:00</option>
		                        	<option <?php if($retreat_time['start']=='0200') echo 'selected';?> value="0200">02:00</option>
		                        	<option <?php if($retreat_time['start']=='0300') echo 'selected';?> value="0300">03:00</option>
		                        	<option <?php if($retreat_time['start']=='0400') echo 'selected';?> value="0400">04:00</option>
		                        	<option <?php if($retreat_time['start']=='0500') echo 'selected';?> value="0500">05:00</option>
		                        	<option <?php if($retreat_time['start']=='0600') echo 'selected';?> value="0600">06:00</option>
		                        	<option <?php if($retreat_time['start']=='0700') echo 'selected';?> value="0700">07:00</option>
		                        	<option <?php if($retreat_time['start']=='0800') echo 'selected';?> value="0800">08:00</option>
		                        	<option <?php if($retreat_time['start']=='0900') echo 'selected';?> value="0900">09:00</option>
		                        	<option <?php if($retreat_time['start']=='1000') echo 'selected';?> value="1000">10:00</option>
		                        	<option <?php if($retreat_time['start']=='1100') echo 'selected';?> value="1100">11:00</option>
		                        	<option <?php if($retreat_time['start']=='1200') echo 'selected';?> value="1200">12:00</option>
		                        	<option <?php if($retreat_time['start']=='1300') echo 'selected';?> value="1300">13:00</option>
		                        	<option <?php if($retreat_time['start']=='1400') echo 'selected';?> value="1400">14:00</option>
		                        	<option <?php if($retreat_time['start']=='1500') echo 'selected';?> value="1500">15:00</option>
		                        	<option <?php if($retreat_time['start']=='1600') echo 'selected';?> value="1600">16:00</option>
		                        	<option <?php if($retreat_time['start']=='1700') echo 'selected';?> value="1700">17:00</option>
		                        	<option <?php if($retreat_time['start']=='1800') echo 'selected';?> value="1800">18:00</option>
		                        	<option <?php if($retreat_time['start']=='1900') echo 'selected';?> value="1900">19:00</option>
		                        	<option <?php if($retreat_time['start']=='2000') echo 'selected';?> value="2000">20:00</option>
		                        	<option <?php if($retreat_time['start']=='2100') echo 'selected';?> value="2100">21:00</option>
		                        	<option <?php if($retreat_time['start']=='2200') echo 'selected';?> value="2200">22:00</option>
		                        	<option <?php if($retreat_time['start']=='2300') echo 'selected';?> value="2300">23:00</option>
		                        </select>~
		                        <select  name="retreat_time[end]" placeholder="退房结束时间">
		                        	<option <?php if($retreat_time['end']=='0000') echo 'selected';?> value="0000">00:00</option>
		                        	<option <?php if($retreat_time['end']=='0100') echo 'selected';?> value="0100">01:00</option>
		                        	<option <?php if($retreat_time['end']=='0200') echo 'selected';?> value="0200">02:00</option>
		                        	<option <?php if($retreat_time['end']=='0300') echo 'selected';?> value="0300">03:00</option>
		                        	<option <?php if($retreat_time['end']=='0400') echo 'selected';?> value="0400">04:00</option>
		                        	<option <?php if($retreat_time['end']=='0500') echo 'selected';?> value="0500">05:00</option>
		                        	<option <?php if($retreat_time['end']=='0600') echo 'selected';?> value="0600">06:00</option>
		                        	<option <?php if($retreat_time['end']=='0700') echo 'selected';?> value="0700">07:00</option>
		                        	<option <?php if($retreat_time['end']=='0800') echo 'selected';?> value="0800">08:00</option>
		                        	<option <?php if($retreat_time['end']=='0900') echo 'selected';?> value="0900">09:00</option>
		                        	<option <?php if($retreat_time['end']=='1000') echo 'selected';?> value="1000">10:00</option>
		                        	<option <?php if($retreat_time['end']=='1100') echo 'selected';?> value="1100">11:00</option>
		                        	<option <?php if($retreat_time['end']=='1200') echo 'selected';?> value="1200">12:00</option>
		                        	<option <?php if($retreat_time['end']=='1300') echo 'selected';?> value="1300">13:00</option>
		                        	<option <?php if($retreat_time['end']=='1400') echo 'selected';?> value="1400">14:00</option>
		                        	<option <?php if($retreat_time['end']=='1500') echo 'selected';?> value="1500">15:00</option>
		                        	<option <?php if($retreat_time['end']=='1600') echo 'selected';?> value="1600">16:00</option>
		                        	<option <?php if($retreat_time['end']=='1700') echo 'selected';?> value="1700">17:00</option>
		                        	<option <?php if($retreat_time['end']=='1800') echo 'selected';?> value="1800">18:00</option>
		                        	<option <?php if($retreat_time['end']=='1900') echo 'selected';?> value="1900">19:00</option>
		                        	<option <?php if($retreat_time['end']=='2000') echo 'selected';?> value="2000">20:00</option>
		                        	<option <?php if($retreat_time['end']=='2100') echo 'selected';?> value="2100">21:00</option>
		                        	<option <?php if($retreat_time['end']=='2200') echo 'selected';?> value="2200">22:00</option>
		                        	<option <?php if($retreat_time['end']=='2300') echo 'selected';?> value="2300">23:00</option>
		                        </select>
		                    </div>
		                    <div style="color: #DDD;opacity:1;">用户能预约退房开发票的时间段。</div>
			            </div>
						<div class="hotel_star">
							<div class="">支持多入住人</div>
							<div class="input_txt input_radio">
								<div>
									<input type="radio" id="multiple_0" name="multiple_inner" value="0" <?php if(($model->has_data() && $model->m_get('multiple_inner') == 0) ||(!$model->has_data()) ) echo 'checked';?>/>
									<label for="multiple_0">不支持</label>
								</div>
								<div>
									<input type="radio" id="multiple_1" name="multiple_inner" value="1" <?php if($model->has_data() && $model->m_get('multiple_inner') == 1) echo 'checked';?>/>
									<label for="multiple_1">支持</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;">
					<button type="submit" class="fom_btn">保存酒店</button>
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
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
	<?php
		$floder= $this->session->get_admin_inter_id ()?$this->session->get_admin_inter_id (): 'kindeditor';
		$subpath= $floder. '|hotel|hotels|hotel_detail'; //基准路径定位在 /public/media/ 下
		$params= array(
			't'=>'images',
			'p'=>$subpath,
			'token'=>'test'	//再完善校验机制
		);
	?>
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
        	},
			'onUploadError': function () {
				alert('上传失败');
			}
		});
	        $('.add_img_box').delegate('.img_close','click',function(){
				$(this).parent().remove();
				$("#el_intro_img").val('');
			})
		//图片上传排版start
		 // $("#file >input").change(function(e){
   //           var file = this.files[0];
   //           var imageType = /image.*/;
   //           if(file.type.match(imageType)){
   //               var reader = new FileReader();
   //               reader.onload=function(){
   //                  $(".file_img_list").prepend($('<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+reader.result+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
			//         $('.add_img_box').delegate('.img_close','click',function(){
			// 			$(this).parent().remove();
			// 			$("#file >input").val('');
			// 		})
   //               }
   //              reader.readAsDataURL(file);
   //          }
   //      });

		//图片上传排版end
		//地区调用start
		var sheng = areaData.sheng;
		var $sheng = $('#sheng');
		var $shi = $('#shi');
		var $xian = $('#xian');
		var shiIndex = 0;
		var mysheng = "<?php echo $model->m_get('province');?>";
		var myshi = "<?php echo $model->m_get('city');?>";
        var myxian = "<?php echo $model->m_get('area');?>";
			for ( var i=0;i<sheng.length;i++ ){
				if(mysheng!='' && sheng[i].indexOf(mysheng)>=0) {
					var selected = 'selected';
					var selectedIndex = i;
				}else{
					var selected = '';
				}
				if(mysheng==undefined&&i==0){
					var selected = 'selected';
					var selectedIndex = i;
				}
				var $option = $('<option ' + selected + ' value='+ sheng[i] +'>'+ sheng[i] +'</option>');
				$sheng.append( $option );
			}
			if(selectedIndex >=0){
				var shi = areaData.shi['a_'+selectedIndex];
				$shi.html('<option value="0">地市选择</option>');
				// $xian.html('<option value="0">区县选择</option>');
				for (var i=0;i<shi.length;i++ ){
					if(myshi!='' && shi[i].indexOf(myshi)>=0) {
						var selected = 'selected';
                        var select_city = i;
					}else{
						var selected = '';
					}
					if(myshi==undefined&&i==0){
						var selected = 'selected';
					}
					var $option = $('<option ' + selected + ' value='+ shi[i] +'>'+ shi[i] +'</option>');
					$shi.append( $option );
				}
			}
            if(select_city >=0){
                var xian = areaData.xian['a_'+selectedIndex+'_'+select_city];
                $xian.html('<option value="0">区县选择</option>');
                for (var i=0;i<xian.length;i++ ){
                    if(myxian!='' && xian[i].indexOf(myxian)>=0) {
                        var selected = 'selected';
                    }else{
                        var selected = '';
                    }
                    if(myxian==undefined&&i==0){
                        var selected = 'selected';
                    }
                    var $option = $('<option ' + selected + ' value='+ xian[i] +'>'+ xian[i] +'</option>');
                    $xian.append( $option );
                }
            }
			$sheng.change(function(){
				shiIndex = this.selectedIndex - 1;
				if ( shiIndex < 0 ){

				}else{
					var shi = areaData.shi['a_'+shiIndex];
					$shi.html('<option value="0">地市选择</option>');
					// $xian.html('<option value="0">区县选择</option>');
					for (var i=0;i<shi.length;i++ ){
						if(shi[i].indexOf(myshi)>=0) {
							var selected = 'selected';
						}else{
							var selected = '';
						}
						var $option = $('<option ' + selected + ' value='+ shi[i] +'>'+ shi[i] +'</option>');
						$shi.append( $option );
					}
				}
			});
			$shi.change(function(){
                if(shiIndex<0){
                    var select_city = sheng.indexOf(mysheng);
                }else{
                    var select_city = shiIndex;
                }

				var index = this.selectedIndex - 1;
				if ( index < 0 ){

				}else{
                    if(select_city!=0){
                        var xian = areaData.xian['a_'+select_city+'_'+index];
                    }else{
                        var xian = areaData.xian['a_'+selectedIndex+'_'+index];
                    }
                    $xian.html('<option value="0">区县选择</option>');
                    for (var i=0;i<xian.length;i++ ){
                        var $option = $('<option value='+ xian[i] +'>'+ xian[i] +'</option>');
                        $xian.append( $option );
                    }
				}
			});
		//地区调用end
		var commonItems = [
		                   'undo', 'redo', '|','cut', 'copy', 'paste',
		                   'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
		                   'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		                   'superscript', 'clearhtml', 'quickformat',  '|',
		                   'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
		                   'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'image', 'multiimage',
		                   'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		                   'anchor', 'link', 'unlink'
		               ];
		KindEditor.ready(function(K) {
	        var editor1 = K.create('textarea[name="arounds"]', {
	            cssPath : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css',
	            uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
	            fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
	            allowFileManager : true,
	            resizeType : 1,
	            items : commonItems,
	            afterCreate : function() {
	                setTimeout(function(){
	                    $('.ke-container').css('width','');
	                },1)
	            }
	        });

	        prettyPrint();
	    });
	});
</script>