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
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<title>绑定储值卡</title>
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
<form id="bindcard" action="<?php echo site_url("member/bindcard/save");?>" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>储值卡号</tt>
    	<input name="code" type="text" placeholder="请输入储值卡号">
    </div>
    <div class="item">
    	<tt>卡号密码</tt>
    	<input name="password" type="tel" placeholder="请输入密码">
    </div>
</div>
<!-- <div class="ui_normal_list ui_border"> -->
<!-- 	<div class="item"> -->
<!--     	<tt>设置密码</tt> -->
<!--     	<input name="password" type="tel" placeholder="请输入新密码"> -->
<!--     </div> -->
<!-- 	<div class="item"> -->
<!--     	<tt>确认密码</tt> -->
<!--     	<input name="resetpassword" type="text" placeholder="请再次输入新密码"> -->
<!--     </div> -->
<!-- </div> -->
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){
	
   $("#sub").click(function(){
       if($("input[name='code']").val().length==0) {
           alert("请输入储值卡号!");
           return false;
       }
       if($("input[name='password']").val().length==0) {
           alert("请输入密码!");
           return false;
       }
       $("#bindcard").submit();
   });
});
</script>
</body>
<script src="<?php echo base_url("public/member/public/js/button_change.js");?>"></script>
</html>
