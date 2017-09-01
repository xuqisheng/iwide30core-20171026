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
<title>修改会员卡密码</title>
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
<form id="modpwd" action="<?php echo base_url("index.php/member/account/modpwdsave");?>" method="post">
<div class="ui_normal_list ui_border">
    <div class="item">
    	<tt>原密码</tt>
    	<input name="oldpassword" type="password" placeholder="请输入原密码">
    </div>
    <div class="item">
    	<tt>新密码</tt>
    	<input name="password" type="password" placeholder="请输入密码">
    </div>
    <div class="item">
    	<tt>会员卡号</tt>
    	<input name="uid" type="text" placeholder="请输入用户卡号">
    </div>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<div id="show_message" style="display:none"><?php if(isset($message)) echo $message;?></div>
<script>
$(document).ready(function(){
	if($("#show_message").html().length) {
		alert($("#show_message").html());
	}
})
</script>
<script>
$(document).ready(function(){
	
   $("#sub").click(function(){
       if($("input[name='oldpassword']").val().length==0) {
           alert("请输入原密码!");
           return false;
       }
       if($("input[name='password']").val().length==0) {
           alert("请输入密码!");
           return false;
       }
       if($("input[name='uid']").val().length==0) {
           alert("请输入用户卡号!");
           return false;
       }
       
       
       $("#modpwd").submit();
   });
});
</script>
</body>
</html>
