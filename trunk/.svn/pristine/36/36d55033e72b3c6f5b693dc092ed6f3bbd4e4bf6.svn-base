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
		<ul class="steps-wrap steps-wrap-2">
			<li class="second current">2. 激活</li>
			<li>1. 填写个人信息</li>
		</ul>
		<div class="form-wrap" id="form-div">
			<div class="input-box"><input type="text" class="i-t-normal" name="card_no" placeholder="请输入您的会员卡号"></div>
			<div class="input-box"><input type="text" class="i-t-normal" name="card_verify" placeholder="请输入您的会员卡验证码"></div>
		</div>
		<div class="btn-wrap">
			<a href="javascript:;" id="btn-submit" class="btn">激活</a>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
$(function(){
	$('#btn-submit').on('click',function(){
		$.ajax({
			url:'<?php echo site_url("member/account/validate_card"); ?>',
			type:'POST',
			dataType:'json',
			data:$('#form-div input[type="text"]'),
			beforeSend:function(){
				pageloading('资料提交中...',0.2);
			},
			complete:function(){
				$('.pageloading').remove();
			},
			success:function(res){
				if(res.redirect){
					location.href=res.redirect;
				}
				if(res.errmsg){
					if(res.is_active){
						geneAbox(res.errmsg,res.route_to);
					}else{
						$.MsgBox.Alert(res.errmsg);
					}
				}
			}
		});
	});

	$(document).delegate('#btn_close,#mb_ico','click',function(){
		$("#mb_box,#mb_con").remove();
	});
});

function geneAbox(msg,href){
	$("#mb_box,#mb_con").remove();
	var _html = "";
	_html += '<div id="mb_box" class="h30"><div id="mb_con">';
	_html += '<!--div id="mb_title" class="color_main h36"> + title + </div-->';
	_html += '<div id="mb_msg" class="bd_bottom">' + msg + '</div><div class="webkitbox" id="mb_btnbox">';
	_html += '<div class="container-fluid"><div class="row">';
	_html += '<div class="col-sm-12" style="margin-bottom:8px;"><a class="blok-btn btn-danger" href="'+href+'">绑定会员卡</a></div>';
	_html += '<div class="col-sm-12"><button class="blok-btn btn-warning" id="btn_close">关闭</button></div>';
	_html += '</div></div>';
	_html += '</div></div></div>';
	$("body").append(_html);

	var tmp='<style>';
	tmp+="#mb_box{width:100%;height:100%;z-index:99999;position:fixed;background:rgba(0,0,0,0.4);top:0;left:0;}";
	tmp+="#mb_con{z-index:999999;width:320px;position:fixed;background:rgba(255,255,255,0.9);text-align:center;border-radius:10px;}";
	tmp+="#mb_title{ padding-top:15px;text-align:center;}";
	tmp+="#mb_msg{ padding:35px;text-align:center;line-height: 1.2;}";
	tmp+="#mb_btnbox{text-align: center;}";
	tmp+="#mb_btnbox>*{ padding:10px;}";
	$('body').before(tmp);
	var _widht = document.documentElement.clientWidth;  //屏幕宽
	var _height = document.documentElement.clientHeight; //屏幕高
	var boxWidth = $("#mb_con").width();
	var boxHeight = $("#mb_con").height();
	//让提示框居中
	$("#mb_con").css({ top: (_height - boxHeight) / 2.5 + "px", left: (_widht - boxWidth) / 2 + "px" });

}




</script>
</html>