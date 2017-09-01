<!doctype html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">

<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>

<link href="<?php echo base_url('public/soma/styles/global.css');?>" rel="stylesheet">

<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<script src="<?php echo base_url('public/soma/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('public/member/super8/css/activate_card.css'); ?>">

<title>速8酒店 - 激活会员卡</title>
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
</head>
<body>
<div class="wrap-outer">
	<div class="wrap">
		<img src="<?php echo base_url('public/member/super8/images/logo-115.png'); ?>" class="logo">
		<h1 class="page-name">激活会员卡</h1>
		<div class="feedback-wrap">
			<img src="<?php echo base_url('public/member/super8/images/icon-wrong.png'); ?>" class="icon-feedback"><br>
			<?php echo $mess; ?>
		</div>
		<div class="btn-wrap btn-wrap-two">
			<a href="<?php echo site_url('member/account/activecard'); ?>" class="btn">重新激活</a><!--重新激活返回会员卡激活信息页（自动记录会员手机号与姓名）-->
			<a href="<?php echo site_url('member/center'); ?>" class="btn btn-dust">放弃激活</a><!--放弃激活退回微信首页-->
		</div>
	</div>
</div>
</body>
</html>