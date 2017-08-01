<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" />
<style>
 textarea{width:50%;}
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
.submit-foot{
  background-color: rgb(190, 190, 190);
  bottom: 0;
  width:100%;
  position: fixed;
  z-index:99;
}
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
				<div class="box box-info">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab"><i
								class="fa fa-list-alt"></i> 基本信息 </a></li>
					</ul>
					<div class='des_tip'>
					<a href="javascript:void(0)" onclick="$('.des_div').toggle();" >订单代码</a>
					
					<div class='des_div'>
						<p>点击将代码填入字段内容中，模板消息中就会显示相应的内容</p>
						<?php if(!empty($order_params)){ foreach($order_params as $param=>$des){ if(!empty($des)){?>
						<p onclick='fill_para("<?php echo $param; ?>")'><span><?php echo $param;?></span><?php echo '-'.$des;?></p>
						<?php }}}?>
					</div>
					</div>
					<!-- form start -->
					<?php echo form_open( site_url('appointment/tmmsg/edit_post'), array('id'=>'submitform','class'=>'form-horizontal','enctype'=>'multipart/form-data' ) ); ?>
					<input id='content_data' name='content_data' type='hidden' value='' />
					<div class="tab-content">
						<div class="tab-pane active">
                			<div class="box-body">
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">模板类型</label>
									<div class="col-sm-8">
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
										<a href='javascript:void(0)' onclick='location.replace(location.href+"?tid="+$("#tid").val()+"&def=1");'>使用默认设置</a>
										<?php }?>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">模板ID</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " placeholder="微信模板消息ID" name="temp_id" value="<?php echo $list['temp_id']; ?>" />
										<?php if(!empty($is_default)){?>
										<p>订单通知默认设置的模板ID请填入行业"酒店旅游/酒店",标题为"酒店订单修改通知",编号"OPENTM205226373"的模板ID</p>
										<p>送券通知默认设置的模板ID请填入行业"餐饮/餐饮",标题为"优惠券领取成功通知",编号"OPENTM201048309"的模板ID</p>
										<p>若不在此行业或无法使用此模板，请根据实际情况选择。</p>
										<?php }?>
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">消息链接</label>
									<div class="col-sm-8">
										<select class="form-control " name="url_type" id="url_type">
											<option value=''>--无链接--</option>
											<?php if(!empty($url_type)) foreach($url_type as $code=>$des){?>
											<option value='<?php echo $code;?>' <?php if($list['url_type']==$code) echo 'selected';?>><?php echo $des;?></option>
											<?php }?>
										</select>
										<input type="text" class="form-control " placeholder="使用其他url" name="url" value="<?php echo $list['url']; ?>" />
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">状态</label>
									<div class="col-sm-8">
										<select class="form-control " name="status" id="status">
											<?php if(!empty($status_des)) foreach($status_des as $code=>$des){?>
											<option value='<?php echo $code;?>' <?php if($list['status']==$code) echo 'selected';?>><?php echo $des;?></option>
											<?php }?>
										</select>
									</div>
								</div>
								<div class="form-group  has-feedback" style="display: none">
									<label class="col-sm-2 control-label">标题文字颜色</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " placeholder="html颜色代码，如#000000" name="top_color" value="<?php echo $list['top_color']; ?>" />
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label">默认文本颜色</label>
									<div class="col-sm-8">
										<input type="text" class="form-control " placeholder="html颜色代码，如#000000" name="text_color" value="<?php echo $list['text_color']; ?>" />
									</div>
								</div>
								<div class="form-group  has-feedback">
									<label class="col-sm-2 control-label" style="color: #7B7B7B">内容配置</label>
									<hr />
								</div>
								<?php if(!empty($list['content'])){ foreach($list['content'] as $k=>$lc) {?>
								<div key='key'>
									<a href="javascript:void(0);" onclick="dele(this)">删除</a>
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">模板字段名</label>
										<div class="col-sm-8">
											<input type="text" class="form-control " name="key_value" placeholder='模板消息详细内容里的参数字段名，如first、keyword1'
												value="<?php echo $k; ?>" />
										</div>
									</div>
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">字段文本颜色</label>
										<div class="col-sm-8">
											<input type="text" class="form-control " name="color" placeholder="html颜色代码，如#000000,可不填"
												value="<?php if(!empty($lc['color'])) echo $lc['color'];?>" />
										</div>
									</div>
									<div class="form-group  has-feedback">
										<label class="col-sm-2 control-label">字段内容</label>
										<div class="col-sm-8">
											<?php foreach($content_des as $cdk=>$cd){?>
											<div flag='content'>
												<input name='type' value='<?php echo $cdk;?>'
													type='checkbox' <?php if(!empty($lc[$cdk])){?>
													checked='checked' <?php }?> /><?php echo $cd;?>
												<textarea name='value' onclick='focus_this(this);' placeholder='<?php echo $cd;?>情况时发送给用户的内容'><?php if(!empty($lc[$cdk]))echo $lc[$cdk];?></textarea>
											</div>
											<br />
											<?php }?>
										</div>
									</div>
								<hr />
								</div>
								<?php }}?>
							</div>

							
							<!-- /.box-footer -->
						</div>
                <!-- /.box-body -->
					</div>
					<!-- /#tab1-->
					<?php echo form_close() ?>
					<div class="box-footer submit-foot">
						<div class="col-sm-4 col-sm-offset-2">
							<button type="button" onclick="sub_content()" class="btn btn-info pull-right">保存</button>
							<button type="button" onclick="add_content()" class="btn btn-info pull-left" >增加一个字段</button>
							<button type="button" onclick="undele()" class="btn btn-info pull-left" >撤销删除</button>
						</div>
					</div>
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
	<!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
var data={};
var focus_obj='';
var ele=new Array();
$(function () {
	$('.submit-foot').css('bottom',($('.main-footer').outerHeight()));
	$(".wysihtml5").wysihtml5();
});
function sub_content(){
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
					data[key_word][content_key.val()]=$(pn).find("[name='value']").val();
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
function add_content(){
	temp='<div key="key"><a href="javascript:void(0);" onclick="dele(this)">删除</a><div class="form-group  has-feedback"><label class="col-sm-2 control-label">模板字段名</label>';
	temp+='<div class="col-sm-8"><input type="text" class="form-control " name="key_value" placeholder="模板消息详细内容里的参数字段名" ';
	temp+='value="" /></div></div><div class="form-group  has-feedback"><label class="col-sm-2 control-label">字段文本颜色</label>';
	temp+='<div class="col-sm-8"><input type="text" class="form-control " name="color" placeholder="html颜色代码，如#000000,可不填" value="" /></div></div>';
	temp+='<div class="form-group  has-feedback"><label class="col-sm-2 control-label">字段内容</label><div class="col-sm-8">';
	<?php foreach($content_des as $cdk=>$cd){?>
	temp+="<div flag='content'><input name='type' value='<?php echo $cdk;?>' type='checkbox' /><?php echo $cd;?>";
	temp+="<textarea name='value' onclick='focus_this(this);' placeholder='<?php echo $cd;?>情况时发送给用户的内容'></textarea></div><br />";
		<?php }?>
	temp+='</div></div><hr /></div>';
	$('.box-body').append(temp);
	$(document).scrollTop($(document).height());
}
</script>
</body>
</html>
