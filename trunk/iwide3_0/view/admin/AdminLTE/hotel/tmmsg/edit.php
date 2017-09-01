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


<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="over_x">
	<div class="content-wrapper" style="min-width:1050px;">
		<div class="banner bg_fff p_0_20"><?php echo $breadcrumb_html; ?></div>
		<div class="contents">
			<!-- form start -->
			<?php echo form_open( site_url('hotel/tmmsg/edit_post'), array('id'=>'submitform','class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
			<input id='content_data' name='content_data' type='hidden' value='' />
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
					<div class="con_right">
						<div class="address">
							<div class="">模版名称</div>
							<div class="input_txt">
								<?php if(!empty($list['temp_type'])){?>
								<span class="form-control " style='border: 0;'><?php echo $list['desc']?></span>
								<input type='hidden' id='tid' name='tid' value='<?php echo $list['temp_type'];?>' />
								<?php }else{?>
								<select class="form-control " name="tid"
												id="tid">
									<?php if(!empty($types)) foreach($types as $code=>$des){?>
									<option value='<?php echo $code;?>'><?php echo $des;?></option>
									<?php }?>
								</select>
								<?php }?>
							</div>
							<?php if(empty($list['temp_type'])){?>
								<a href='javascript:void(0)' onclick='location.replace(location.href+"?tid="+$("#tid").val()+"&def=1");'>使用默认设置</a>
							<?php }?>
						</div>
						<div class="jingwei">
							<div class="">模版ID</div>
							<div class="input_txt">
								<input name="temp_id" type="text" placeholder="微信模版消息ID" value="<?php echo $list['temp_id']; ?>" />
								<?php if(!empty($is_default)){?>
									<p>订单通知默认设置的模板ID请填入行业"酒店旅游/酒店",标题为"酒店订单修改通知",编号"OPENTM205226373"的模板ID</p>
									<p>送券通知默认设置的模板ID请填入行业"餐饮/餐饮",标题为"优惠券领取成功通知",编号"OPENTM201048309"的模板ID</p>
									<p>若不在此行业或无法使用此模板，请根据实际情况选择。</p>
								<?php }?>
							</div>
						</div>
						<div class="hotel_star">
							<div class="">引流页面</div>
							<div class="input_txt input_radio">
								
								<?php if(!empty($url_type)) foreach($url_type as $code=>$des){?>
								<div>
									<input type="radio" id="<?php echo $code;?>" name="url_type" value="<?php echo $code;?>" <?php if($list['url_type']==$code || ($code=='diy' && empty($list['url_type'])) ) echo 'checked';?>/>
									<label for="<?php echo $code;?>"><?php echo $des;?></label>
								</div>
								<?php }?>
							</div>
						</div>

						<div class="jingwei page_url" <?php if($list['url_type']=='diy'  || (empty($list['url_type']))){?> style='display: block;' <?php }?>>
							<div class="">页面url</div>
							<div class="input_txt"><input type="text" onclick='focus_this(this);' placeholder="输入引流页面的链接" name="url" value="<?php echo $list['url']; ?>"/></div>
						</div>
						<div class="jingwei" style="display: none;">
							<div>标题文字颜色</div>
							<div class="input_txt">
								<input type="text" placeholder="html颜色代码，如#000000" name="top_color" value="<?php echo $list['top_color']; ?>" />
							</div>
						</div>
						<div class="jingwei">
							<div class="">文字颜色</div>
							<div class="input_txt"><input type="text" placeholder="html颜色代码，如#000000" name="text_color" value="<?php echo $list['text_color']; ?>"/></div>
						</div>
						<div class="jingwei">
							<div class="">状态</div>
							<div class="input_txt" >
							<select style="width:450px;" name="status" id="status">
									<?php if(!empty($status_des)) foreach($status_des as $code=>$des){?>
									<option value='<?php echo $code;?>' <?php if($list['status']==$code) echo 'selected';?>><?php echo $des;?></option>
									<?php }?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<?php $num = 1;?>
				<?php if(!empty($list['content'])){ foreach($list['content'] as $k=>$lc) {?>
				<div class="contents_list bg_fff" key="key">
					<div class="con_left"><span class="block bg_3f51b5"></span>模版字段</div>
					<div class="con_right relative">
						<span class="deletes absolute">删除</span>
						<div class="jingwei">
							<div class="">字段名称</div>
							<div class="input_txt"><input type="text" name="key_value" placeholder="模版消息详情内容里的参数字段名" value="<?php echo $k; ?>"/></div>
						</div>
						<div class="jingwei">
							<div class="">文本颜色</div>
							<div class="input_txt"><input type="text" name="color" placeholder="html颜色代码，如#000000,可不填" value="<?php if(!empty($lc['color'])) echo $lc['color'];?>"/></div>
						</div>
						<div class="hotel_star clearfix">
							<div class="float_left">字段内容</div>
							<div class="input_txt input_checkbox" style="padding-left:4px;">
								<?php foreach($content_des as $cdk=>$cd){?>
								<div flag='content'>
								<?php if($cdk=='common'){$name = 'wf';}elseif($cdk=='pay_0'){$name = 'con_room';}elseif($cdk=='pay_1'){$name = 'shuttle';}?>
									<input class="<?php echo $name;?>" type="checkbox" id="<?php echo $name.$num;?>" name="type" value="<?php echo $cdk;?>" <?php if(!empty($lc[$cdk])){?>
										checked='checked' <?php }?>>
									<label for="<?php echo $name.$num;?>"><?php echo $cd;?></label>
								</div>
								<?php }?>
							</div>
						</div>
						<?php $kk = 1;?>
						<?php foreach($content_des as $cdk=>$cd){?>
							<div class="jingwei d_n jingwei<?php echo $kk;?>" <?php if(!empty($lc[$cdk])){?>
										style='display: block;' <?php }?>>
								<div class=""><?php echo $cd;?></div>
								<div class="input_txt"><input onclick='focus_this(this);' type="text" name="value" placeholder="<?php echo $cd;?>情况时发送给用户的内容" value="<?php if(!empty($lc[$cdk]))echo $lc[$cdk];?>"/></div>
							</div>
							<?php $kk++;?>
						<?php }?>
					</div>
				</div>
				<?php $num++;?>
				<?php }}?>
			<?php echo form_close() ?>
			<div class="bg_fff border_1 btns_list" style="padding:15px;text-align:center;">
				<button onclick="sub_content()" class="fom_btn">保存模版</button>
				<button onclick="undele()" class="fom_btn" >撤销删除</button>
				<button class="fom_btn actives news_add">新增字段</button>
			</div>
			<div class="fixed1 bg_fff border_1">
				<div class="fixed1_title">订单代码：点击将代码填入字段内容中，模板消息中就会显示相应的内容</div>
				<?php $number = 1?>
				<div class="d_table">
				<?php if(!empty($order_params)){ foreach($order_params as $param=>$des){ if(!empty($des)){?>
					<div onclick='fill_para("<?php echo $param; ?>")'><?php echo $param.'-'.$des;?></div>
					<?php if($number%5==0){echo '</div><div class="d_table">';}?>
					<?php $number++;?>
				<?php }}}?>
				</div>
			</div>
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
	var data={};
	var focus_obj='';
	var ele=new Array();
	<?php $timestamp = time();?>
	$(function(){
		$('#el_intro_img').parent().append('<input type="file" value="上传图片" id="upfiles">');
		$('#upfiles').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			//'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#el_intro_img').val(res.url);
        	}
		});
		$('.input_txt input').change(function(){
			if($('#diy:checked').val()){
				$('.page_url').show();
			}else{
				$('.page_url').hide();
			}
		})
		var number="<?php echo $num; ?>";
		$('.news_add').click(function(){
			var context=$('<div class="contents_list bg_fff" key="key"><div class="con_left"><span class="block bg_3f51b5"></span>模版字段'+number+'</div><div class="con_right relative"><span class="deletes absolute">删除</span><div class="jingwei"><div class="">字段名称</div><div class="input_txt"><input name="key_value" type="text" placeholder="模版消息详情内容里的参数字段名" /></div></div><div class="jingwei"><div class="">文本颜色</div><div class="input_txt"><input name="color" type="text" placeholder="html颜色代码，如#000000,可不填" /></div></div><div class="hotel_star clearfix"><div>字段内容</div><div class="input_txt input_checkbox" style="padding-left:4px;"><div flag="content"><input class="wf" type="checkbox" id="wf'+number+'" name="type" value="common"><label for="wf'+number+'">默认</label></div><div flag="content"><input class="con_room" type="checkbox" id="con_room'+number+'" name="type" value="pay_0"><label for="con_room'+number+'">未支付</label></div><div flag="content"><input class="shuttle" type="checkbox" id="shuttle'+number+'" name="type" value="pay_1"><label for="shuttle'+number+'">已支付</label></div></div></div><div class="jingwei d_n jingwei1"><div class="">默认内容</div><div class="input_txt"><input name="value" onclick="focus_this(this);" type="text" placeholder="默认情况下发送给用户的内容" /></div></div><div class="jingwei d_n jingwei2"><div class="">未支付内容</div><div class="input_txt"><input name="value" onclick="focus_this(this);" type="text" placeholder="未支付情况下以送给用户的内容" /></div></div><div class="jingwei d_n jingwei3"><div class="">已支付内容</div><div class="input_txt"><input name="value" onclick="focus_this(this);" type="text" placeholder="已支付情况下以送给用户的内容" /></div></div></div></div>')
				$('#submitform').append(context);
				number++;
		})

		$('#submitform').delegate('.deletes','click',function(){
			// $(this).parents(".contents_list").remove();
			t_p=$(this).parent().parent();
			t_p.attr('key','');
			t_p.hide();
			ele.push(t_p);
		})
		
		j_toChange('.wf','.jingwei1')
		j_toChange('.con_room','.jingwei2')
		j_toChange('.shuttle','.jingwei3')
		function j_toChange(obj,obj2){
			$('#submitform').delegate(obj,'change',function(){
				var _this=$(this);
				if(_this.parents('.contents_list').find(''+obj+':checked').val()){
					_this.parents('.contents_list').find(obj2).show();
				}else{
					_this.parents('.contents_list').find(obj2).hide();
				}
			});
		}
		// $('from').delegate('.wf','change',function(){
		// 	var _this=$(this);
		// 	console.log(_this.parents('.contents_list').find('.wf:checked').val());
		// 	if(_this.parents('.contents_list').find('.wf:checked').val()){
		// 		_this.parents('.contents_list').find('.jingwei1').show();
		// 	}else{
		// 		_this.parents('.contents_list').find('.jingwei1').hide();
		// 	}
		// });
		// $('from').delegate('.con_room','change',function(){
		// 	var _this=$(this);
		// 	if(_this.parents('.contents_list').find('.con_room:checked').val()){
		// 		_this.parents('.contents_list').find('.jingwei2').show();
		// 	}else{
		// 		_this.parents('.contents_list').find('.jingwei2').hide();
		// 	}
		// });
		// $('from').delegate('.shuttle','change',function(){
		// 	var _this=$(this);
		// 	if(_this.parents('.contents_list').find('.shuttle:checked').val()){
		// 		_this.parents('.contents_list').find('.jingwei3').show();
		// 	}else{
		// 		_this.parents('.contents_list').find('.jingwei3').hide();
		// 	}
		// });


			
		//图片上传排版start
		 $("#file >input").change(function(e){
             var file = this.files[0];
             var imageType = /image.*/;
             if(file.type.match(imageType)){
                 var reader = new FileReader();
                 reader.onload=function(){
                    $(".file_img_list").prepend($('<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+reader.result+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
			        $('.add_img_box').delegate('.img_close','click',function(){
						$(this).parent().remove();
						$("#file >input").val('');
					})
                 }
                reader.readAsDataURL(file);
            }
        });

		//图片上传排版end
	});
function undele(){
	if(ele.length>0){
		t_o=ele.pop();
		$(t_o).attr('key','key');
		$(t_o).show();
	}
}
function sub_content(){
	data={};
	ranges=$("[key='key']");
	var key_word='';
	var p_o='';
	$.each(ranges,function(i,n){
		key_word=$(n).find('input[name="key_value"]').val();
		if(key_word!=''){
			color=$(n).find('input[name="color"]').val();
			data[key_word]={};
			data[key_word]['color']=color;
			p_o=$(n).find("[flag='content']");
			check_key='';
			$.each(p_o,function(pi,pn){
				content_key=$(pn).find("[name='type']");
				if(content_key.is(":checked")==true)
					data[key_word][content_key.val()]=$(pn).parent().parent().parent().find('.jingwei'+($(pn).index()+1)).find("[name='value']").val();
			});
		}
	});
	json=JSON.stringify(data);
	$('#content_data').val(json);
	$('#submitform').submit();
}
function fill_para(para){
	if(focus_obj!=''){
		$(focus_obj).val($(focus_obj).val()+para);
	}
}
function focus_this(obj){
	focus_obj=obj;
}
</script>