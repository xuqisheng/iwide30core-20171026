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
<link
	href="<?php echo base_url("public/member/phase2/styles/global.css");?>"
	rel="stylesheet">
<link
	href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>"
	rel="stylesheet">
<script src="<?php echo base_url("public/member/version4.0/js/lk/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/version4.0/js/lk/jquery.js");?>"></script>
<script
	src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
<script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
<script
	src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/version4.0/css/lk/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/ui_style.css");?>" rel="stylesheet">
<title>解除绑定储值卡</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
-->
</style>
<body>
<form id="unbindcard" action="<?php echo base_url("index.php/membervip/giftcards/dounbind");?>" method="post">
<div class="ui_normal_list ui_border">
    <div class="item">
    	<tt>凤凰卡卡号</tt>
    	<input type="tel" name='code' value="<?php echo $code;?>" readonly="readonly">
    </div>
    <div class="item">
    	<tt>凤凰卡密码</tt>
    	<input name="password" type="password" placeholder="请输入凤凰卡密码">
    </div>
</div>
<input id="sub" class="ui_foot_btn" type="button" value="保存">
</form>
<script>
$(document).ready(function(){
	  $('#sub').click(function(){
	        var form = $("#unbindcard"),form_url=form.attr("action"),btn = $(this),loadtip=null;
	        postUrl = form.attr("action");
	        form.ajaxSubmit({
	            url:form_url,
	            dataType:'json',
	            timeout:20000,
//	                clearForm:true,
//	                resetForm:true,
	            beforeSubmit: function(arr, $form, options){
	                /*验证提交数据*/
	                var _null = false, _msg = '',inputobj=false;
	                for(i in arr){
	                    var name = arr[i].name,value=$.trim(arr[i].value);
	                    if(name == 'code' && !value) {
	                        _null = true; _msg = '储值卡号';inputobj=$("input[name='"+name+"']");break;
	                    }
	                    if(name == 'password' && !value) {
	                        _null = true; _msg = '请输入密码!';inputobj=$("input[name='"+name+"']");break;
	                    }
	                }

	                if(_null === true) {
	                    $.MsgBox.Alert(_msg);
	                    new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
	                    $(inputobj).focus();
	                    return false;
	                }
	                /*end*/
	                pageloading();
	            },
	            success: function(result){
	                removeload();
	                 if(result.err>1){
	                    $.MsgBox.Alert(result.msg);
	                }else if(result.err=='0'){
	                     $.MsgBox.Alert(result.msg,function(){window.location.href="<?php  echo base_url("index.php/membervip/GiftCards/")?>";});
// 	               	 $.MsgBox.Alert(result.msg);
	                }
	            },
	            error:function () {
	                removeload();
	                $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
	            }
	        });
	    });
});
</script>
</body>
<script src="<?php echo base_url("public/member/version4.0/js/lk/button_change.js");?>"></script>
</html>