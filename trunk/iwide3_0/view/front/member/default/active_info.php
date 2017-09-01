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
		<ul class="steps-wrap">
			<li class="second">2. 激活</li>
			<li class="current">1. 填写个人信息</li>
		</ul>
		<div class="form-wrap" id="form-div">
			<div class="input-box"><input type="text" class="i-t-normal" name="customer" placeholder="请输入您的真实姓名"></div>
			<div class="input-box"><input type="tel" maxlength="11" name="telephone" class="i-t-normal" placeholder="请输入您的手机号码"></div>
			<div class="input-box">
				<input type="text" class="i-t-normal i-t-short" name="sms" placeholder="短信验证码">
				<!-- <button class="btn-check" type="button">获取验证码</button>-->
				<button class="btn-check" type="button" id="sms">获取验证码</button>
			</div>
		</div>
		<div class="btn-wrap">
			<a href="javascript:;" class="btn" id="btn-submit">提交</a>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
var time=60; //短信CD时间
var _time; //定时器
var _sent =false;
var cutdown = function(){
	_sent = true;
	$('#sms').html('剩余'+time+'s').addClass('disable');
	$('#sms').addClass('btn-check-grey');
	_time = window.setInterval(function(){
		time--;
		$('#sms').html('剩余'+time+'秒');
		if(time<=0){
			window.clearInterval(_time);
			$('#sms').html('重新发送').removeClass('disable');
			$('#sms').removeClass('btn-check-grey');
			_sent =false;
			time = 60;
		}
	},1000);
};

$(function(){
	$('#sms').on('click',function(){
		if(_sent)return;

		var getstr='';
		if($("input[name='telephone']").val().length==0) {
			$.MsgBox.Alert("请输入手机号码!");
			return false;
		}

		getstr += 'customer='+$("input[name='customer']").val();

		var tel = $('input[name=telephone]').val();

		if(tel.length !=11){
			$.MsgBox.Alert('请输入正确的手机号码');
			return false;
		}
		getstr += '&telephone='+tel+'&Forced=1';
		$.ajax({
			type      : "GET",
			dataType  : 'json',
			url       : "<?php echo site_url("member/center/sendsms");?>",
			data      : getstr,
			beforeSend: function(){
				$('#sms').html('发送中...');
			},
			complete  : function(){
				$('#sms').html('获取验证码');
			},
			success   : function(data){
				if(data.status == 1){
					cutdown();
					$.MsgBox.Alert(data.msg);
					$(this).attr('disabled', "true");
					$(this).addClass("ui_disable_btn");
				}else{
					$.MsgBox.Alert(data.msg);
				}
			}
		});
	});

	$('#btn-submit').on('click',function(){
		$.ajax({
			url:'<?php echo site_url("member/account/validate_sms"); ?>',
			type:'POST',
			dataType:'json',
			data:$('#form-div input[type="text"], #form-div input[type="tel"]'),
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
					$.MsgBox.Alert(res.errmsg);
				}
			}
		});
	});
});



</script>
</html>