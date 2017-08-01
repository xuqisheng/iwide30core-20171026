<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url("public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/css/ui_pull.css");?>" rel="stylesheet">
<title>资料审核</title>
</head>
<style>
.ui_normal_list .item tt{ display:inline-block; width:6em;}
</style>
<body>
<div style="padding:3%; padding-bottom:0; color:#999999; font-size:0.5rem;">根据国家现行政策规定，购买超过1000元以上的储值卡需要进行实名登记，给您带来的不便敬请谅解！</div>
<form id="checkinform" action="<?php echo base_url("index.php/bgyhotel/checkin/save");?>" enctype="multipart/form-data" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>姓名</tt>
    	<input name="name" type="text" value="<?php if(isset($info)) echo $info->name;?>" placeholder="请输入您的姓名">
    </div>
	<div class="item">
    	<tt>手机号码</tt>
    	<input name="telephone" type="tel" value="<?php if(isset($info)) echo $info->telephone;?>" placeholder="请输入手机号码">
    </div>
	<div class="item">
    	<tt>短信验证</tt>
    	<input name="sms" type="tel" style="width:43%" placeholder="请输入短信验证码">
        <button id="sms" class="ui_normal_btn">发送验证码</button>  
    </div>
    <div class="item">
    	<tt>身份证号</tt>
    	<input name="identity_card" value="<?php if(isset($info)) echo $info->identity_card;?>" type="tel" placeholder="请输入身份证号">
    </div>
</div>
<!-- <div class="ui_btn_list ui_border"> -->
<!-- 	<a href="" class="item"> -->
<!--     	<tt>开具发票</tt> -->
<!--     	<span>选择开具发票的酒店</span> -->
<!--     </a> -->
<!-- </div> -->
<div class='ui_upload_img ui_border'>
	<div class="item">
        <p>添加身份证<span class="ui_gray">点击上传身份正反面照片</span></p>
        <div class="fileInputContainer div_idcard_front" style="padding:10px 0;width:48%;display:inline-block">
			<img src="<?php echo base_url("public/uploads/upload.jpg");?>" />
		</div><input name="idcard_front" style="display:none" onChange="dopic(this,'.div_idcard_front')" class="fileInput" type="file" />
		<div class="fileInputContainer div_idcard_reverse"  style="width:48%;display:inline-block">
			<img src="<?php echo base_url("public/uploads/upload.jpg");?>" />
		</div><input name="idcard_reverse" style="display:none" onChange="dopic(this,'.div_idcard_reverse')" class="fileInput" type="file" />
    </div>
	<div class="item">
        <p>您的照片<span class="ui_gray">点击上传您的免冠正面照片</span></p>
        <div class="fileInputContainer div_personal_picture"  style="width:48%;display:inline-block">
			<img src="<?php echo base_url("public/uploads/upload.jpg");?>" />
		</div><input name="personal_picture" style="display:none" onChange="dopic(this,'.div_personal_picture')" class="fileInput" type="file" />
    </div>
</div>
<div style="padding-bottom:3%">
<input name="ci_id" type="hidden" value="<?php if(isset($ci_id)) echo $ci_id;?>" />
<input name="num" type="hidden" value="<?php if(isset($num)) echo $num;?>" />
<input name="saler" type="hidden" value="<?php if(isset($saler)) echo $saler;?>" />
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</div>
</form>
<script>
$(document).ready(function(){
    $('.div_idcard_front').click(function(){
	     $("input[name='idcard_front']").click();
	});
    
	$('.div_idcard_reverse').click(function(){
	     $("input[name='idcard_reverse']").click();
	});
	
	$('.div_personal_picture').click(function(){
	     $("input[name='personal_picture']").click();
	});
	
	$("#sms").click(function() {
		if($("input[name='telephone']").val().length==0) {
            alert("请输入手机号码!");
            return false;
        }
	    var tel = $("input[name='telephone']").val();
		$.get("<?php echo site_url("member/center/sendsms");?>", {telephone:tel},
		    function(data){
		});
        $(this).attr('disabled',"true");
		$(this).addClass("ui_disable_btn");

		return false;
	});
	
   $("#sub").click(function(){
       if($("input[name='name']").val().length==0) {
           alert("请填写姓名!");
           return false;
       }
       if($("input[name='telephone']").val().length==0) {
           alert("请输入手机号码!");
           return false;
       }
       if($("input[name='sms']").val().length==0) {
           alert("请输入短信验证码!");
           return false;
       }
       if($("input[name='identity_card']").val().length==0) {
           alert("请输入身份证号!");
           return false;
       }
       
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data){
      		  if(data) {
          		  $("#checkinform").submit();
      		  } else {
          		  alert("验证码不正确!");
      		  }
       });
   });
});
</script>
</body>
<script src="<?php echo base_url("public/js/button_change.js");?>"></script>
</html>
