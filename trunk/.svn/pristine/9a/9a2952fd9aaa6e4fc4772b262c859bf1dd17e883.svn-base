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
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui_style.css");?>" rel="stylesheet">
<title>重置储值卡密码</title>
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
<form id="bindcard" action="<?php echo base_url("index.php/bgyhotel/bindcard/doresetpwd");?>" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>手机号码</tt>
    	<input name="telephone" type="tel" placeholder="请输入手机号码">
    </div>
	<div class="item">
    	<tt>短信验证</tt>
    	<input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码">
        <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div>
    <div class="item">
    	<tt>原密码</tt>
    	<input name="oldpassword" type="tel" placeholder="请输入原密码">
    </div>
    <div class="item">
    	<tt>新密码</tt>
    	<input name="password" type="tel" placeholder="请输入密码">
    </div>
    <div class="item">
    	<tt>确认新密码</tt>
    	<input name="resetpassword" type="tel" placeholder="请输入密码">
    </div>
</div>
<input type="hidden" name="id" value="<?php echo $id;?>" />
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
		$("input[name='telephone']").attr('readonly',"true");
        $(this).attr('disabled',"true");
		$(this).addClass("ui_disable_btn");

		return false;
	});
	
   $("#sub").click(function(){
       if($("input[name='telephone']").val().length==0) {
           alert("请输入手机号码!");
           return false;
       }
       if($("input[name='sms']").val().length==0) {
           alert("请输入短信验证码!");
           return false;
       }
       if($("input[name='oldpassword']").val().length==0) {
           alert("请输入原密码!");
           return false;
       }
       if($("input[name='password']").val().length==0) {
           alert("请输入密码!");
           return false;
       }
       if($("input[name='password']").val() != $("input[name='resetpassword']").val()) {
           alert("两次输入的密码不一致!");
           return false;
       }
       
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data){
      		  if(data) {
          		  $("#bindcard").submit();
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
