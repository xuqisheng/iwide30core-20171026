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
<script src="/static/scripts/viewport.js"></script>
<script src="/static/scripts/jquery.js"></script>
<script src="/static/scripts/ui_control.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/static/js/sha1.js"></script>
<link href="/static/style/global.css" rel="stylesheet">
<title>快来领取《微信力量》签名码，手快有，手慢无！凭该码可购微信力量签名版</title>
<meta name="description" content="专属于你的《微信力量》，限时抢购！" />
<div id='wx_pic' style='margin:0 auto;display:none;'>
<img src='http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg' />
</div>
<script type="text/javascript">			
function phptojs(s){
    if(s){return s.replace(/\\x{(.*?)}/g, '\\u$1');}
}		
			
function inputcheck(){
    var err = 0;times = 0;
    $('input').each(function(){
	    key = $(this).attr('key')?$(this).attr('key'):'';
		tip = $(this).attr('tip')?$(this).attr('tip'):'';
		ept = $(this).attr('ept')?$(this).attr('ept'):'';
		value = $(this).val();
		times += 1;
		if(value==''){
			if(parseInt(ept)==0){
			    alert(tip+',不能为空.');
				err +=1;
				return false;
			}
		}
		else {
		    if(key){
				var re = eval('/'+phptojs(key)+'/i');
				if(!re.test(value)){
					alert(tip);
					err +=1;
					$(this).focus();
					return false;
				}
			}
		}
	});
	if(err==0){return true;}
	return false;
}
</script>
</head>
<style>

.invitation .box{ border-radius:0.3rem; width:90%; margin:15% auto; text-align:center; background:#fff; overflow:hidden; color:#555;}
.invitation .pullclose{ color:#cdcdcd; font-size:0.7rem; text-align:right; padding:4%;}
.invitation .title{color:#000; padding-top:5%}
.invitation .number{padding:6% 0; color:#f99e12; display:inline-block}
.invitation .number p{ font-size:0.9rem; display:table-cell; height:3rem; vertical-align:middle}
.invitation .number p.big{ font-size:1.2rem;}
.invitation .support{ color:#999; padding-bottom:4%; font-size:0.55rem;}
.invitation .footbtn>*{display:inline-block; width:40%; margin:5% 2%; padding:3% 0; border-radius:0.2rem; border:1px solid #a6a6a6;}
.invitation .footbtn .touse{color:#fff; background-color:#ff7200; border-color:#ff7200;}
</style>
<body>
	
<div class="invitation pull">
	<div class="box">
    	<div class="title">恭喜您获得签名码</div>
    	<div class="number">
        	<p><?php 
			$usecoupon = '';
			foreach($coupon as $v){$usecoupon=$v;echo $v;?><br><span class="isuse<?php echo $v;?>" style="display:none">您手慢了，此码已经使用！</span>
			<script>
			$.post('/index.php/api/iscoupon',{sid:'22',coupon:'<?php echo $v;?>'},function(d){
				if(d.code==0){
					$('.isuse<?php echo $v;?>').show();
				}
				else {
					$('.isuse<?php echo $v;?>').hide();
				}
			},'json');
			</script>
			<?php } ?></p>
        </div>
        <div class="footbtn">
        	<span class="tosent">送给朋友</span>
        	<a href="http://wxsw.chat.iwide.cn/index.php/fapi?id=22&couponid=<?php echo $usecoupon;?>" class="touse">立即使用</a>
        </div>
    	<div class="support">金房卡提供技术支持</div>        
    </div>
</div>

<div class="pull pull_sent" style="text-align:right; color:#fff; display:none;">
    <div style="padding-right:3%">
        <img src="/static/img/arrow.png" alt="" style="width:10%"/>
    </div>
    <p style="padding-right:12%; font-size:0.8rem;">
        点击并发送给朋友
    </p>
</div>
<script>
$(function(){
	if($('.invitation .number').find('br').length<=1)  // 如果只有一个码，则加大字体字号
		$('.invitation .number p').addClass('big');
	$('.tosent').click(function(){
		toshow($('.pull_sent'));
	})
	$('.pull_sent').click(toclose);
});

var appid='<?php echo $getapp['appid'];?>',timestamp='1449120630',ticket='<?php echo $ticket['api_ticket'];?>',nonceStr = 'qingfeng',url = location.href;
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
	    menuList: ["menuItem:share:appMessage","menuItem:share:timeline"]
	});
    wx.onMenuShareTimeline({
	    title: '快来领取《微信力量》签名码，手快有，手慢无！凭该码可购微信力量签名版',
		desc: '快来领取《微信力量》签名码，手快有，手慢无！凭该码可购微信力量签名版',
	    link: location.href,
	    imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
	    success: function () {

	    },
	    cancel: function () { 
	    }
	});
	wx.onMenuShareAppMessage({
	    title: '快来领取《微信力量》签名码，手快有，手慢无！凭该码可购微信力量签名版',
	    desc: '快来领取《微信力量》签名码，手快有，手慢无！凭该码可购微信力量签名版',
	    link: location.href, 
	    imgUrl: 'http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg',
	    success: function () {   },
	    cancel: function () { 
	       
	    }
	});
});
</script>
</body>
</html>