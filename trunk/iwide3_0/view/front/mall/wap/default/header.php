<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url('public/mall/default/script/viewport.js')?>"></script>
<script src="<?php echo base_url('public/mall/default/script/jquery.js')?>"></script>
<script src="<?php echo base_url('public/mall/default/script/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/mall/default/style/global.css')?>" rel="stylesheet">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"]?>',
    timestamp: <?php echo $signPackage["timestamp"]?>,
    nonceStr: '<?php echo $signPackage["nonceStr"]?>',
    signature: '<?php echo $signPackage["signature"]?>',
    jsApiList: [
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'hideMenuItems',
		'showMenuItems'
    ]
});
wx.ready(function(){
	wx.hideMenuItems({
		menuList: [
			"menuItem:jsDebug",
			"menuItem:editTag",
			"menuItem:delete",
			"menuItem:copyUrl",
			"menuItem:originPage",
			"menuItem:readMode",
			"menuItem:openWithQQBrowser",
			"menuItem:openWithSafari"
		]
	});
});
</script>