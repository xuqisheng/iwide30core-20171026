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
<script src="<?php echo base_url('public/distribute/theme_1_1/scripts/viewport.js')?>"></script>
<script src="<?php echo base_url('public/distribute/theme_1_1/scripts/jquery.js')?>"></script>
<script src="<?php echo base_url('public/distribute/theme_1_1/scripts/ui_control.js')?>"></script>
<script src="<?php echo base_url('public/distribute/default/scripts/lazyload.js')?>"></script> 
<link href="<?php echo base_url('public/distribute/theme_1_1/styles/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/theme_1_1/styles/comand.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/theme_1_1/styles/income.css')?>" rel="stylesheet">
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
		'hideMenuItems'
    ]
  });
wx.ready(function(){
	wx.hideMenuItems({
		menuList: [
		"menuItem:jsDebug",
		"menuItem:delete",
		"menuItem:copyUrl",
		"menuItem:originPage",
		"menuItem:openWithQQBrowser",
		"menuItem:openWithSafari"]
	});
	wx.onMenuShareTimeline({
		title: '<?php echo $share["title"]?>',
		link: '<?php echo $share["link"]?>',
		imgUrl: '<?php echo $share["imgUrl"]?>',
		success: function () {
		},
		cancel: function () { 
		}
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>', 
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    type: '<?php echo $share["type"]?>', 
	    dataUrl: '<?php echo $share["dataUrl"]?>',
	    success: function () { 
	    },
	    cancel: function () { 
		}
	});
});
wx.error(function(res){
//     alert('error:'+res.toString());
});
</script>