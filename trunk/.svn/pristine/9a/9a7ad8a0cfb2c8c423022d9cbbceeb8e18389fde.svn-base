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
<title>绑定会员卡</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
-->
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<form id="pinfo" action="<?php echo base_url("index.php/bgyhotel/bindcard/bindmcsave");?>" method="post">
<div class="ui_normal_list ui_border">
    <div class="item">
    	<tt>会员卡号</tt>
    	<input name="membership_number" type="text" placeholder="请输入卡号" value="<?php if(isset($member)) echo $member->membership_number;?>">
    </div>
	<div class="item">
    	<tt>手机号码</tt>
    	<input name="telephone" type="tel" placeholder="请输入手机号码" value="<?php if(isset($member)) echo $member->telephone;?>">
    </div>
	<div class="item">
    	<tt>短信验证</tt>
    	<input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码">
        <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){
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
       if($("input[name='membership_number']").val().length==0) {
           alert("请填写会员卡号!");
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
       
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data){
      		  if(data) {
          		  $("#pinfo").submit();
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
