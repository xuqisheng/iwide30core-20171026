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
<meta name="viewport" content="width=320,user-scalable=0">
<title>圣火令</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<style>
body,html{width:100%; height:100%; background:#fff;}
</style>
<body>

<script src="<?php echo base_url('public/club/scripts/canvas.js');?>"></script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<!--- 二维码不能跨域 否则不能生成海报  --->
<div class="haibao">
	<div class="center color_fff" style="padding-top:11%;font-size:1.2em"><?php if(isset($club_name)&&!empty($club_name)){echo $club_name;}else{echo '-';}?></div>
    <div class="color_888" style="width:70%; margin:6% auto; text-align:center;font-size:0.9em">诚邀您的加盟，您将成为酒店尊贵尊享协议，您的单位成员及贵宾在协议有效期间内可享受诸多优惠及便利。</div>
	<div class="center" style="padding:2% 0 4% 0">
        <div style="width:32%; display:inline-block"><div class="squareimg"><img src="data:image/jpg;base64,<?php echo $qrcode_url;?>" /></div></div>
    </div>
    <div class="center pad3" style="font-size:0.8em;"><span style="border-bottom:1px solid #555;">扫描二维码，登记信息，享受专属优惠价</span></div>
    <div class="center pad3" style="font-size:1.2em; margin-top:12px">
        <p style=" line-height:1;">优惠房型：所有房型</p>
        <p>优惠价格：<?php echo $price_code[$arr_price_code[0]]['price_name'];if(count($arr_price_code)>1){echo '...';}?></p>
    </div>
    <div class="center pad3" style="font-size:0.8em;">有效期：<?php if(isset($valid_time)&&!empty($valid_time)){echo $valid_time;}else{echo '-';}?></div>
    <div class="center absolute color_main" style="bottom:10%;width:100%;"><?php if(isset($public_name)&&!empty($public_name)){echo $public_name.'，';}?>欢迎您的入住！</div>
    <div class="center absolute color_E4E4E4" style="bottom:1.5%;width:100%;">Powered by 金房卡</div>
</div>
<script>
$(function(){
	var _w = $(window).width();
	var rate = 640/1008;
	var _h = _w/rate;
	$('.haibao').height(_h);
	pageloading('生成海报中',0.8);

	var fail = window.setTimeout(function(){
		removeload();
		$.MsgBox.Alert('生成失败, 请稍候刷新页面重试!');
	},10000);
	var clone=$('.haibao').clone();
	var _w = $('.haibao').width()*2;
	var _h = $('.haibao').height()*2;
	$('body').append(clone).css('overflow','hidden');
	$('meta').attr('content','width=320,user-scalable=0,initial-scale=1');
	clone.width(_w);
	clone.height(_h);
	clone.css('font-size','1.8rem');
	/*生成海报代码*/

	window.setTimeout(function(){ //延迟0.3s生成...
		html2canvas(clone.get(0), {
			allowTaint: true,
			taintTest: false,
			onrendered: function(canvas) {
				removeload();
				canvas.id = "mycanvas";
				var dataUrl = canvas.toDataURL();
				var newImg = document.createElement("img");
				newImg.src =  dataUrl;
				$('.haibao').html(newImg);
				clone.remove();
				$('body').removeAttr('style');
				//$.MsgBox.Alert('你的专属海报,记得长按保存图片哦~');
				window.clearTimeout(fail);
			}
		});
	},300);

})



wx.config({
    debug: false,// 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。移动端会通过弹窗来提示相关信息。如果分享信息配置不正确的话，可以开了看对应报错信息
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [//需要使用的JS接口列表,分享默认这几个，如果有其他的功能比如图片上传之类的，需要添加对应api进来
        'checkJsApi',
        'onMenuShareTimeline',//
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo'
    ]
});


window.share_config = {
    "share": {
        "imgUrl": "<?php echo base_url('public/club/images/share.png');?>",//分享图，默认当相对路径处理，所以使用绝对路径的的话，“http://”协议前缀必须在。
        "desc" : "享受更低的订房折扣",//摘要,如果分享到朋友圈的话，不显示摘要。
        "title" : '嘿亲，你的专属优惠价~',//分享卡片标题
        "link": window.location.href,//分享出去后的链接，这里可以将链接设置为另一个页面。
        "success":function(){//分享成功后的回调函数
        },
        'cancel': function () {
            // 用户取消分享后执行的回调函数
        }
    }
};
wx.ready(function () {
    wx.onMenuShareAppMessage(share_config.share);//分享给好友
    wx.onMenuShareTimeline(share_config.share);//分享到朋友圈
    wx.onMenuShareQQ(share_config.share);//分享给手机QQ
});
</script>
</body>
</html>
