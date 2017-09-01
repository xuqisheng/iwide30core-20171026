<!doctype html>
<html>
<head>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php echo referurl('js','sha1.js',2,$media_path) ?>
</head>
<body>
<script>
document.body.addEventListener('touchstart', function () { }); 
var appid='<?php echo $hoteldata['appid'];?>',timestamp='<?php echo $ntime;?>',ticket='<?php echo $ticket;?>',nonceStr = 'qingfeng',url = location.href;
var signature = hex_sha1('jsapi_ticket='+ticket+'&noncestr='+nonceStr+'&timestamp='+timestamp+'&url='+url);

wx.config({
  debug: false,
  appId: appid,
  timestamp: timestamp,
  nonceStr: nonceStr,
  signature: signature,
  jsApiList: [
	'checkJsApi',
	'onMenuShareTimeline',
	'onMenuShareAppMessage',
	'onMenuShareQQ',
	'onMenuShareWeibo',
	'onMenuShareQZone',
	'hideMenuItems',
	'showMenuItems',
	'hideAllNonBaseMenuItem',
	'showAllNonBaseMenuItem',
	'translateVoice',
	'startRecord',
	'stopRecord',
	'onVoiceRecordEnd',
	'playVoice',
	'onVoicePlayEnd',
	'pauseVoice',
	'stopVoice',
	'uploadVoice',
	'downloadVoice',
	'chooseImage',
	'previewImage',
	'uploadImage',
	'downloadImage',
	'getNetworkType',
	'openLocation',
	'getLocation',
	'hideOptionMenu',
	'showOptionMenu',
	'closeWindow',
	'scanQRCode',
	'chooseWXPay',
	'openProductSpecificView',
	'addCard',
	'chooseCard',
	'openCard'
  ]
});	
wx.ready(function(){
	wx.hideMenuItems({
		menuList: [
		"menuItem:share:facebook",
		"menuItem:share:QZone",
		"menuItem:jsDebug",
		"menuItem:editTag",
		"menuItem:delete",
		"menuItem:copyUrl",
		"menuItem:originPage",
		"menuItem:readMode",
		"menuItem:openWithQQBrowser",
		"menuItem:openWithSafari",
		"menuItem:share:email",
		"menuItem:share:brand"]
	});
	wx.showMenuItems({
	    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"] // 要显示的菜单项，所有menu项见附录3
	});
    wx.onMenuShareTimeline({
	    title: '邀请函：酒店第二次技术变革',
		desc: '酒店邦邀各路高手相聚，1场有格调的行业Patry。',
	    link: 'http://iwide.chat.iwide.cn/app/form/jiudianbang/',
	    imgUrl: 'http://chat.file.iwide.cn/uploads/attached/image/20151020/792147859522956387.jpg',
	    success: function () {

	    },
	    cancel: function () { 
	    }
	});
	wx.onMenuShareAppMessage({
	    title: '邀请函：酒店第二次技术变革',
	    desc: '酒店邦邀各路高手相聚，1场有格调的行业Patry。',
	    link: 'http://iwide.chat.iwide.cn/app/form/jiudianbang/', 
	    imgUrl: 'http://chat.file.iwide.cn/uploads/attached/image/20151020/792147859522956387.jpg',
	    success: function () {   },
	    cancel: function () { 
	       
	    }
	});
});

function test(){
 alert(5);
}
</script>
</body>
</html>