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
<title>资料完善</title>
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
<form id="pinfo" action="<?php echo base_url("index.php/member/corder/saveinfo");?>" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>姓名</tt>
    	<input name="name" type="text" placeholder="请输入姓名" value="">
    </div>
	<div class="item">
    	<tt>手机</tt>
    	<input name="telephone" type="tel" placeholder="请输入手机号码" value="">
    </div>
    <div class="item">
    	<tt>身份证号</tt>
    	<input name="identity_card" type="text" placeholder="请输入身份证号码" value="">
    </div>
        <div class="item">
    	<tt>分销号</tt>
    	<input name="distribution_no" type="text" placeholder="选填项（请输入分销号）" value="">
    </div>
</div>
<input type="hidden" name="num" value="<?php echo $num;?>">
<input type="hidden" name="ci_id" value="<?php echo $ci_id;?>">
<input type="hidden" name="saler" value="<?php echo $saler;?>">
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){	
   $("#sub").click(function(){
       if($("input[name='name']").val().length==0) {
           alert("请填写姓名!");
           return false;
       }
       if($("input[name='telephone']").val().length==0) {
           alert("请输入手机号码!");
           return false;
       }
       if($("input[name='identity_card']").val().length==0) {
           alert("请输入身份证号!");
           return false;
       }
       $("#pinfo").submit();
   });
});
</script>
</body>
</html>
