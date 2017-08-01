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
<title>接触绑定储值卡</title>
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
<form id="bindcard" action="<?php echo base_url("index.php/bgyhotel/bindcard/dounbind");?>" method="post">
<div class="ui_normal_list ui_border">
    <div class="item">
    	<tt>凤凰卡卡号</tt>
    	<input type="tel" value="<?php echo $code;?>" readonly="readonly">
    </div>
    <div class="item">
    	<tt>凤凰卡密码</tt>
    	<input name="password" type="tel" placeholder="请输入凤凰卡密码">
    </div>
</div>
<input type="hidden" name="id" value="<?php echo $id;?>" />
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){
   $("#sub").click(function(){
       if($("input[name='password']").val().length==0) {
           alert("请输入凤凰卡密码!");
           return false;
       }
       $.post("<?php echo site_url("bgyhotel/bindcard/dounbind");?>", {password:$("input[name='password']").val(),id:<?php echo $id;?>},
          	function(data){
    	        if(confirm(data)) {
    	        	location.href = "<?php echo site_url("bgyhotel/bindcard");?>";
    	        }
       });
   });
});
</script>
</body>
<script src="<?php echo base_url("public/js/button_change.js");?>"></script>
</html>
