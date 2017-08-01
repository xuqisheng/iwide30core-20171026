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
<script>if(self!=top){top.location.href=location.href;}</script>
<?php echo referurl('js','viewport.js',2,$media_path) ?>
<?php echo referurl('js','jquery.js',2,$media_path) ?>
<?php echo referurl('js','jquery.touchwipe.min.js',2,$media_path) ?>
<?php echo referurl('js','common.js?v=1',2,$media_path) ?>
<?php echo referurl('js','myjs.js',2,$media_path) ?>
<?php echo referurl('js','sha1.js',2,$media_path) ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php echo referurl('css','global.css',2,$media_path) ?>
<?php echo referurl('css','animate.css?v=1',2,$media_path) ?>
<?php echo referurl('css','page.css?v=2',2,$media_path) ?>
<?php echo referurl('css','mycss.css',2,$media_path) ?>
<?php echo referurl('css','order.css',2,$media_path) ?>
<title>我的订单</title>
<script>var count = 0;</script>
</head>

<style>
body,html{overflow:auto;-webkit-overflow-scrolling:touch;}
.ac_title{margin-bottom:2%}
.ac_title b{ font-size:1rem;}
.btn{ width:5em}
.btn span{padding:0.3em 0; font-size:0.55rem;}
</style>
<body>
<div class="content">
	<div class="tic_count">门票共<b class="big">0</b>张</div>
	<div class="ac_title"><b>我的订单</b></div>
    <div class="orderlist">
        <!-- item -->
		<?php foreach($tiket as $v){ ?>
		<script>count += 1;</script>
    	<div class="item">
        	<div class="top_part">
            	<img src="/public/chat/public/img/ticket.png" />
            	<h1><?php echo $v['keyword'];?></h1>
            	<div class="orderid">订单号： <?php $tradeno = $v['iid'];echo $tradeno;?></div>
                <div class="ordertime">购买时间：<?php echo date('Y-m-d H:i',$v['addtime']);?></div>
            </div>
            <div class="borderimg">
                <div class="bottom_part">
                    <div class="btn cardso<?php echo $v['ciid'];?>"><span><a style="font-weight:bold !important;" onClick="getcard('<?php echo $v['cardid'];?>','<?php echo $ntime;?>','<?php echo $v['signaturecard'];?>','<?php echo $v['ciid'];?>','<?php echo $v['ucode'];?>')">查看卡券</a></span></div>
                    <div class="ispay">已付金额：<span class="price"><?php echo $v['payed'];?></span></div>
                    <div class="ac_time">上海杨浦区秦皇岛路32号玫瑰里<Br><?php echo date('Y-m-d',strtotime($v['starttime']));?>全天</div>
                </div>
            </div>
        </div>
		 <?php } ?>
        <!-- item -->
    </div>
</div>
<script>$('.tic_count b').html(count);</script>
<script>
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

//cardExt: '{"timestamp": "'+t+'", "signature":"'+s+'"}'
function getcard(c,t,s,id,code){
	wx.addCard({
	  cardList: [
		{
		  cardId: c,
		  cardExt: '{"code":"'+code+'","timestamp": "'+t+'", "signature":"'+s+'"}'
		}
	  ],
	  success: function (res) {
	   // $('.cardso'+id).html('已领取');
		$.get('/index.php/chat/fapi/getcard',{cid:id},function(d){});
		//location.href=location.href;
	  }
	});
}
	
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
	    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"]
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

</script>
</body>
</html>