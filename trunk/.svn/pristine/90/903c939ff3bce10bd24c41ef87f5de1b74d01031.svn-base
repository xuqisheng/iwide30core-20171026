<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport"
	content="width=320,initial-scale=1,user-scalable=0">
<link
	href="<?php echo base_url("public/member/phase2/styles/global.css");?>"
	rel="stylesheet">
<link
	href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>"
	rel="stylesheet">
<script
	src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
<script
	src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
<script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
<script
	src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title>绑定储值卡</title>
</head>
<body>
	<form id="cardSave"
		action="<?php echo base_url("index.php/membervip/Giftcards/save");?>"
		method="post">
		<div class='list_style bd_bottom'>
			<div class="input_item  code">
				<div>储值卡号</div>
				<div>
					<input pattern="^[A-Za-z0-9]+$" placeholder="请输入储值卡号" name="code"
						data-name="code" />
				</div>
			</div>
			<div class="input_item  password">
				<div>卡号密码</div>
				<div>
					<input pattern="^[A-Za-z0-9]+$" placeholder="请输入密码" name="password"
						type="password" data-name="code" />
				</div>
			</div>
		</div>
		<div class="sign_btn bg_main">保存</div>
				<div class=''>
		        <p style="padding: 5% 3%; color: #999;">温馨提示 :<br>尊敬的用户，凤凰礼卡现已正式开通线上支付功能，为了您的资金及账户安全，即日起，每次仅可绑定一张凤凰礼卡消费和支付。</p>
		    </div>
		<script>
$(function(){
    $('.sign_btn').click(function(){
        var form = $("#cardSave"),form_url=form.attr("action"),btn = $(this),loadtip=null;
        postUrl = form.attr("action");
        form.ajaxSubmit({
            url:form_url,
            dataType:'json',
            timeout:20000,
//                clearForm:true,
//                resetForm:true,
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
                    $.MsgBox.Alert(result.msg,function(){window.location.href="<?php  echo base_url("index.php/membervip/Giftcards/")?>";});
                }
            },
            error:function () {
                removeload();
                $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
            }
        });
    });

    $("input").change(function(){
        var regular = new RegExp($(this).attr('pattern'));
        var inputValue = $(this).val();
        var inputName = $(this).attr('name');
        if(!regular.test(inputValue)){
            $("."+inputName+"").addClass('warn');
            $(".sign_btn").addClass('disable');
        }else{
            $("."+inputName+"").removeClass('warn');
            $(".sign_btn").removeClass('disable');
        }
    });
    
    $("input").focusout(function(){
        var regular = new RegExp($(this).attr('pattern'));
        var inputValue = $(this).val();
        var inputName = $(this).attr('name');
        if(!regular.test(inputValue)){
            $("."+inputName+"").addClass('warn');
            $(".sign_btn").addClass('disable');
        }else{
            $("."+inputName+"").removeClass('warn');
            $(".sign_btn").removeClass('disable');
        }
    });
})
</script>
	</form>
</body>