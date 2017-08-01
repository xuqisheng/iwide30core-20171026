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
<title>会员登录</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
.ui_foot_btn{border:none;background: #d40f20;}
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
<form id="pinfo" action="<?php echo base_url("index.php/member/account/save");?>" method="post">
<div class="ui_normal_list ui_border">
  <?php foreach ($fields as $key => $info){ ?>
    <?php if ($info['must']==1){ ?>

    <div class="item">
        <tt><?php echo $info['name'];?></tt>
        <input name="<?php echo $key; ?>"  <?php if($key=='password'){?> type="password" <?php }else{?> type="text"  <?php } ?> style="width:9.5rem" placeholder="请输入<?php echo $info['name'] ?>" />
    </div>

    <?php } ?>
  <?php } ?>
  <?php if(isset($fields['telephone']) && ($fields['telephone']['must']==1 && $fields['telephone']['check']==1)) {?>
    <div class="item">
      <tt>短信验证</tt>
      <input type="tel" name="sms" style="width:43%" placeholder="请输入短信验证码">
      <button id="sms" class="ui_normal_btn">发送验证码</button>
    </div>
  <?php } ?>
</div>
  <input id="sub" class="ui_foot_btn" type="button" value="登 录">
<tt>
  <p style="text-align:center">还没有账号？马上<a href="<?php echo base_url("index.php/member/account/register");?>" >注册</a></p>
  <p style="text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;<br/>忘记密码？请点击<a href="<?php echo base_url("index.php/member/account/resetpassword");?>" >找回密码</a></p>
  <p style="text-align:center"><br/>
    <?php if(isset($tishiMsg)) echo $tishiMsg; ?>
  </p>
</tt>
</form>
<script>
$(document).ready(function(){
   $("#sub").click(function(){
       if($("input[name='account']").val().length==0) {
           alert("请填写登录帐号!");
           return false;
       }
       if($("input[name='password']").val().length==0) {
           alert("请输入密码!");
           return false;
       }
       $("#pinfo").submit();

   });
});
</script>
</body>
</html>
