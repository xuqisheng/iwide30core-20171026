<!doctype html>
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
<meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
<script src="<?php echo base_url('public/mall/multi/script/jquery.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/imgscroll.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/classify.css')?>" rel="stylesheet">
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
<title><?php echo $title?></title>
</head>
<style>
header{text-align:center; margin-bottom:6%}
.rankimg{ max-width:580px; margin:auto; width:93.75%; min-width:300px}
.rankimg li{ padding-bottom:3%}
</style>
<body>

<div class="page_loading"><p class="isload">正在加载</p></div>

<header class="headers"> 
  <div class="headerslide">
      <a class="slideson imgshow relative"><img src="<?php echo base_url('public/mall/multi/images/eg (1).png'); ?>" /></a>
      <a class="slideson imgshow relative"><img src="<?php echo base_url('public/mall/multi/images/eg (2).png'); ?>" /></a>
      <a class="slideson imgshow relative"><img src="<?php echo base_url('public/mall/multi/images/eg (3).png'); ?>" /></a>
  </div>
</header>

<ul class="rankimg">
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (1).png'); ?>" /></a></li>
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (2).png'); ?>" /></a></li>
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (3).png'); ?>" /></a></li>
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (4).png'); ?>" /></a></li>
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (5).png'); ?>" /></a></li>
    <li><a href="" ><img src="<?php echo base_url('public/mall/multi/images/eg (6).png'); ?>" /></a></li>
</ul>
<div class="l_b_btn radius">
	<a href="<?php echo site_url('mall/wap/cart'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="car"></a>
	<a href="<?php echo site_url('mall/wap/my_orders'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="person"></a>
</div>
<div style="padding-top:15%"></div>
</body>
<script>
//轮播图片比例
imgrate=640/160;  
</script>
</html>
