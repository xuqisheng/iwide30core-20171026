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
font-size: 1.8em;
vertical-align: middle;
display: inline-block;}
-->
@font-face {
  font-family: 'iconfont';
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot');
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.woff') format('woff'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.ttf') format('truetype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.svg#iconfont') format('svg');
}
.iconfont{
  font-family:"iconfont" !important;
  font-size:16px;font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.over_x{width:100%;overflow-x:auto;}
.bg_fff{background:#fff;}
.bg_3f51b5{background:#3f51b5;}
.bg_ff503f{background:#ff503f;}
.bg_4caf50{background:#4caf50;}
.clearfix:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
.display_none{display:none !important;}
.m_b_20{margin-bottom:20px;}
.float_left{float:left;}
.content-wrapper{color:#7e8e9f;}
.p_0_20{padding:0 20px;}
textarea{border:1px solid #d7e0f1;}
.banner{height:50px;width:100%;line-height:50px;border-bottom:1px solid #d7e0f1;}
.contents{padding:10px 20px 20px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.hotel_star >div:nth-of-type(2) >div,.con_right >div >div{display:inline-block;}
.con_left{width:150px;text-align:center;border-right:1px solid #d7e0f1;display:table-cell;vertical-align:middle;}
.con_right{padding:20px 0 20px 0px;}
.con_right>div{margin-bottom:12px;}
.con_right >div >div:nth-of-type(1){width:115px;height:30px;line-height:30px;text-align:center;}
.input_txt{line-height:30px;}
.input_txt >input{height:30px;line-height:30px;border:1px solid #d7e0f1;width:450px;text-indent:3px;}
.input_txt >select{height:30px;line-height:30px;display:inline-block;border:1px solid #d7e0f1;background:#fff;margin-right:20px;padding:0 8px;}
.input_radio >div{margin-right:10px;}
.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio1.png) no-repeat center left;background-size:22%;width:90px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio2.png) no-repeat center left;background-size:20%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg2.png) no-repeat center left;background-size:15%;}


.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
.add_img_box_q:hover > .img_close_q{display:block !important;cursor:pointer;}
.add_img_box_d:hover > .img_close_d{display:block !important;cursor:pointer;}
.uploadify{float: left;}

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
			<?php 
	echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array('pro_id'=>$this->input->get('ids') ) ); 
	?>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
					<input type="hidden" name="inter_id" value="<?php echo $this->session->get_admin_inter_id ();?>">
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">名称</div>
							<div class="input_txt"><input type="text" name="name" value="<?php if(!empty($row['name'])) echo $row['name'];?>"/></div>
						</div>
						<div class="address">
							<div class="">一句话简介</div>
							<div class="input_txt"><input type="text" name="short_intro" value="<?php if(!empty($row['short_intro'])) echo $row['short_intro'];?>"/></div>
						</div>
						<div class="address">
							<div class="">作者</div>
							<div class="input_txt"><input type="text" name="author" value="<?php if(!empty($row['author'])) echo $row['author'];?>"/></div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_ff503f"></span>内容详情</div>
					<div class="con_right">
						<div class="hottel_name clearfix">
							<div class="float_left">内容简介</div>
							<div class="input_txt">
								<textarea class="introduce" name="intro" maxlength="150"><?php if(!empty($row['intro'])) echo $row['intro'];?></textarea>
							</div>
						</div>
						<div class="jingwei clearfix">
							<div class="float_left">缩略图</div>
							<input type="hidden" class="form-control " name="intro_img" id="el_intro_img" placeholder="缩略图" value="<?php if(!empty($row['intro_img'])) echo $row['intro_img'];?>">
							<div class="input_txt file_img_list" style="padding-left:4px;">
								<?php if(!empty($row['intro_img'])){?>
									<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?php echo $row['intro_img'];?>"/>
										<div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
									</div>
								<?php }?>
								<label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
							</div>
						</div>
						<div class="jingwei clearfix">
							<div class="float_left">二维码</div>
							<input type="hidden" class="form-control " name="qrcode_img" id="el_intro_img_q" placeholder="二维码" value="<?php if(!empty($row['qrcode_img'])) echo $row['qrcode_img'];?>">
							<div class="input_txt file_img_list_q" style="padding-left:4px;">
								<?php if(!empty($row['qrcode_img'])){?>
									<div class="add_img_box_q" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?php echo $row['qrcode_img'];?>"/>
										<div class="img_close_q" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
									</div>
								<?php }?>
								<label id="file_q" class="add_img"><input class="display_none file_img" type="file" /></label>
							</div>
						</div>
						<div class="jingwei clearfix">
							<div class="float_left">详情图</div>
							<input type="hidden" class="form-control " name="detail_img" id="el_intro_img_d" placeholder="详情图" value='<?php if(!empty($row['detail_img'])) echo $row['detail_img'];?>'>
							<div class="input_txt file_img_list_d" style="padding-left:4px;">
							<?php if(!empty($row['detail_img'])) $detail_img = json_decode($row['detail_img'],true);?>
								<?php if(!empty($detail_img)){foreach($detail_img as $img){?>
									<div class="add_img_box_d" style="float:left;width:77px;height:100px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:100px;overflow:hidden;" src="<?php echo $img;?>"/>
										<div class="img_close_d" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
									</div>
								<?php }}?>
								<label id="file_d" class="add_img"><input class="display_none file_img" type="file" /></label>
							</div>
						</div>
					</div>
				</div>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_4caf50"></span>状态&排序</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">推荐值</div>
							<div class="input_txt"><input type="text" name="recommend" value="<?php if(!empty($row['recommend'])) echo $row['recommend'];?>" /></div>
						</div>
						<div class="jingwei">
							<div class="">状态</div>
							<div class="input_txt" >
								<select style="width:450px;" name="status">
									<?php $status = array('1'=>'有效','2'=>'无效');?>
										<option <?php if(!empty($row['status']) && $row['status']==1) echo 'selected';?> value="1">有效</option>
										<option <?php if(!empty($row['status']) && $row['status']==2) echo 'selected';?> value="2">无效</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;">
					<button type="submit" class="fom_btn">保存</button>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
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
			'file_post_name': 'imgFile',
			'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
			'height':77,
			'width':77,
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

		$('#file_q').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			//'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
			'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
			'height':77,
			'width':77,
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#el_intro_img_q').val(res.url);
        		$('.add_img_box_q').remove();
	             $(".file_img_list_q").prepend($('<div class="add_img_box_q" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+res.url+'"/><div class="img_close_q" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
		        $('.add_img_box_q').delegate('.img_close_q','click',function(){
					$(this).parent().remove();
					$("#el_intro_img_q").val('');
				})
        	}
		});
        $('.add_img_box_q').delegate('.img_close_q','click',function(){
			$(this).parent().remove();
			$("#el_intro_img_q").val('');
		})

		$('#file_d').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			//'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
			'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
			'height':77,
			'width':77,
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
			    if($("#el_intro_img_d").val()==''){
			    	var detail_img = new Array();
			    }else{
			    	var detail_img = $.parseJSON($("#el_intro_img_d").val());
			    }
			    detail_img.push(res.url);
			    detail_img = JSON.stringify(detail_img); 

        		$('#el_intro_img_d').val(detail_img);
	             $(".file_img_list_d").prepend($('<div class="add_img_box_d" style="float:left;width:77px;height:100px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:100px;overflow:hidden;" src="'+res.url+'"/><div class="img_close_d" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
                 $('.add_img_box_d').delegate('.img_close_d','click',function(){
                 	var thisurl = $(this).prev().attr('src');
         			$(this).parent().remove();
         			var detail_img = $.parseJSON($("#el_intro_img_d").val());
         			for(var k=0;k<detail_img.length;k++){
         				if(detail_img[k] == thisurl){
         			    	detail_img.splice(k,1);
         				}
         			}
         			detail_img = JSON.stringify(detail_img); 
         			$("#el_intro_img_d").val(detail_img);
         		})
		        
        	}
		});
        $('.add_img_box_d').delegate('.img_close_d','click',function(){
        	var thisurl = $(this).prev().attr('src');
			$(this).parent().remove();
			var detail_img = $.parseJSON($("#el_intro_img_d").val());
			for(var k=0;k<detail_img.length;k++){
				if(detail_img[k] == thisurl){
			    	detail_img.splice(k,1);
				}
			}
			detail_img = JSON.stringify(detail_img); 
			$("#el_intro_img_d").val(detail_img);
		})
	});
</script>