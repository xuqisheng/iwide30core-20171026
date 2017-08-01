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
<script src="/public/chat/public/scripts/viewport.js"></script>
<script src="/public/chat/public/scripts/jquery.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="/public/chat/public/style/global.css" rel="stylesheet">
<link href="/public/chat/public/style/page.css?v=2" rel="stylesheet">
<link href="/public/chat/public/style/mycss.css" rel="stylesheet">
<title>购买成功</title>
<style>
.btn{width:7em}
</style>
</head>
<body>
<div class="page" style="display:block;">
    <div class="share_bg content">
        <div class="app_box center">
            <div class="apply">
                <p class="title">您已报名成功</p>
                <p class="tim">活动时间：2016.1.1 09:30-12:30</p>
                <p class="draw"><img src="/public/chat/public/img/draw.png"/></p>
                <div style="margin-top:6%; padding-bottom:2%; font-weight:bold;">相门城墙文化休闲广场</div>
            </div>
            <a href="/index.php/chat/fapi?iad=<?php echo $addinfo['cid'];?>&id=<?php echo $this->inter_id;?>" class="btn" style="margin-right:3em"><span>继续购买</span></a>	
            <a href="/index.php/chat/fapi/signuptopay_order?iad=<?php echo $addinfo['cid'];?>&id=<?php echo $this->inter_id;?>" class="btn"><span>我的订单</span></a>	
        </div>	
    </div>	
</div>
<script>
/*
var appid='<?php echo $hoteldata['appid'];?>',timestamp='<?php echo $ntime;?>',ticket='<?php echo $ticket;?>',cardid='<?php echo $card['cardid'];?>',signaturecard='<?php echo $signaturecard;?>',nonceStr = 'qingfeng',url = location.href;
var signature = hex_sha1('jsapi_ticket='+ticket+'&noncestr='+nonceStr+'&timestamp='+timestamp+'&url='+url);
var cid = '<?php echo $card['id'];?>',paying='<?php echo $paying;?>',code='<?php echo $card['ucode'];?>';

if(paying){
    top.location.href='/index.php/fapi/addresult?id=<?php echo $card['infoid'];?>&id=<?php echo $this->inter_id;?>';
}

wx.config({
  debug: false,
  appId: appid,
  timestamp: timestamp,
  nonceStr: nonceStr,
  signature: signature,
  jsApiList: [
	'checkJsApi',
	'addCard',
	'chooseCard',
	'onMenuShareTimeline',
    'onMenuShareAppMessage',
	'openCard'
  ]
});

wx.ready(function(){
	wx.showMenuItems({
		menuList: ["menuItem:share:appMessage","menuItem:share:timeline"]
	});
	wx.onMenuShareTimeline({
		title: '《微信力量》签名版 ',
		desc: '《微信力量》签名版，主编谢晓萍亲自签名，限量发售！',
		link: 'http://wxsw.chat.iwide.cn/app/form/wxsw/',
		imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
		success: function () {
	
		},
		cancel: function () { 
		}
	});
	wx.onMenuShareAppMessage({
		title: '《微信力量》签名版 ',
		desc: '《微信力量》签名版，主编谢晓萍亲自签名，限量发售！',
		link: 'http://wxsw.chat.iwide.cn/app/form/wxsw/', 
		imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
		success: function () {   },
		cancel: function () { 
		   
		}
	});

});
	
$(function(){
    $("#addcard1").click(function(){
		toaddcard();
	});
});


function toaddcard(){
	if(!signaturecard){
		alert('已经领取');
		return false;
	}
	wx.addCard({
	  cardList: [
		{
		  cardId: cardid,
		  cardExt: '{"code":"'+code+'","timestamp": "'+timestamp+'", "signature":"'+signaturecard+'"}'
		}
	  ],
	  success: function (res) {
		$.post('/index.php/fapi/getcard',{cid:cid},function(d){});
		signaturecard = '';
		$("#addcard1 span").html('已经领取');
	  }
	});
}
*/
</script>
</body>
</html>