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
<?php echo referurl('js','viewport.js',2,$media_path) ?>
<?php echo referurl('js','jquery.js',2,$media_path) ?>
<?php echo referurl('js','sha1.js',2,$media_path) ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php echo referurl('css','global.css',2,$media_path) ?>
<?php echo referurl('css','page.css?v=6',2,$media_path) ?>
<?php echo referurl('css','mycss.css?v=6',2,$media_path) ?>
<title>报名成功</title>
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
                <p class="tim">11月25日我们上海见</p>
                <p class="draw"><img src="/public/chat/public/img/draw.png"/></p>
                <div style="margin-top:6%; padding-bottom:2%; font-weight:bold;">请领取卡券，作为参会凭证</div>
                <button id="addcard1" class="btn" name="addcard" type="button"><span><?php if(!$signaturecard){echo '已经领取';} else {echo '领取卡券';}?></span></button>	
            </div>
            <a href="/index.php/chat/fapi?iad=<?php echo $addinfo['cid'];?>" class="btn" style="margin-right:3em"><span>继续购买</span></a>	
            <a href="/index.php/chat/fapi/order?iad=<?php echo $addinfo['cid'];?>" class="btn"><span>我的订单</span></a>	
        </div>	
    </div>	
</div>
<script>
var appid='<?php echo $hoteldata['appid'];?>',timestamp='<?php echo $ntime;?>',ticket='<?php echo $ticket;?>',cardid='<?php echo $card['cardid'];?>',signaturecard='<?php echo $signaturecard;?>',nonceStr = 'qingfeng',url = location.href;
var signature = hex_sha1('jsapi_ticket='+ticket+'&noncestr='+nonceStr+'&timestamp='+timestamp+'&url='+url);
var cid = '<?php echo $card['id'];?>',paying='<?php echo $paying;?>',code='<?php echo $card['ucode'];?>';

if(paying){
    top.location.href='/index.php/chat/fapi/addresult?iad=<?php echo $card['infoid'];?>';
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
		$.get('/index.php/chat/fapi/getcard',{cid:cid},function(d){});
		signaturecard = '';
		$("#addcard1 span").html('已经领取');
	  }
	});
}
</script>
</body>
</html>