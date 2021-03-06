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
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<title>信息绑定</title>
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
<form id="pinfo" action="<?php echo base_url("index.php/member/center/bindBailiMember");?>" method="post">
<div class="ui_normal_list ui_border">
    <div class="item">
        <tt>姓名</tt>
        <input name="name" type="text" placeholder="请输入姓名" value="">
    </div>
	<div class="item">
    	<tt>手机号码</tt>
    	<input name="telephone" type="tel" placeholder="请输入手机号码" value="">
    </div>
	<div class="item">
    	<tt>短信验证</tt>
    	<input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码">
        <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="绑 定">
</form>
<script>
$(document).ready(function(){
	$("#sms").click(function() {
        if($("input[name='telephone']").val().length==0) {
            alert("请输入手机号码!");
            return false;
        }
	    var tel = $("input[name='telephone']").val();
		$.get("<?php echo site_url("member/center/usersendsms");?>", {telephone:tel},
		    function(data){
		        alert(data);
		});
        $(this).attr('disabled',"true");
		$(this).addClass("ui_disable_btn");

		return false;
	});
	
   $("#sub").click(function(){
       if($("input[name='telephone']").val().length==0) {
           alert("请输入手机号码!");
           return false;
       }

       if($("input[name='name']").val().length==0) {
           alert("请输入姓名!");
           return false;
       }

       if($("input[name='telephone']").val().length!=11) {
           alert("手机号码必须为11位!");
           return false;
       }
       
       if($("input[name='sms']").val().length==0) {
           alert("请输入短信验证码!");
           return false;
       }
       
       var sms = $("input[name='sms']").val();
       $.get("<?php echo site_url("member/center/smsvalid");?>", {sms:sms},
      	   function(data){
      		  if(data==true) {
          		  $("#pinfo").submit();
      		  }else{
          		  alert("验证码不正确!");
      		  }
       });
   });
});
</script>
</body>
</html>
