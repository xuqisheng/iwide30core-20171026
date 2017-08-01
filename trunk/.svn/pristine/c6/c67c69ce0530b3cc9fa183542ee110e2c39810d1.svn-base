<!DOCTYPE html>
<html lang="en">
<head>
<script src="<?php echo base_url("public/member/highclass/js/rem.js")?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
<!-- 全局控制 -->
    <link rel="stylesheet" href="<?php echo base_url("public/member/highclass/css/global.css")?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url("public/member/highclass/css/mycss.css")?>" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <script src="<?php echo base_url("public/member/highclass/js/jquery.js")?>"></script>
    <script src="<?php echo base_url("public/member/highclass/js/myjs.js")?>"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
<title>重置支付密码</title>
</head>
<body>
<div class="gradient_bg padding_35">
	<section class="padding_0_15 padding_top_15">
		<form class="form_list font_14" id="loginSave" action="<?php echo base_url("index.php/membervip/resetpassword/savesetbindpwd");?>" method="post">
			<input type="hidden" name="tel" value="" />
			<div>
				<p class="center font_19">设置储值支付密码</p>
			</div>

			<div class="layer_bg radius_3 padding_45 margin_top_45">
				<div class="padding_0_20">
					<div class="font_12 color3">请设置支付密码</div>
					<div class="post_num margin_top_22 relative" id="payPwd">
    					<input name="password" type="password" maxlength="6" readonlyunselectable="on" class="pwd_input absolute" id="pwd_input">
						<ul class="flex pwd_list_input">
							<li class="flex_1"><input type="password" readonly=""></li>
							<li class="flex_1"><input type="password" readonly=""></li>
							<li class="flex_1"><input type="password" readonly=""></li>
							<li class="flex_1"><input type="password" readonly=""></li>
							<li class="flex_1"><input type="password" readonly=""></li>
							<li class="flex_1"><input type="password" readonly=""></li>
						</ul>
					</div>
				</div>
				<div class="margin_top_35 padding_0_20 font_17">
					<a class="block center padding_15 iconfont entry_btn reset_pay weui_btn_primary">&#xe614;&ensp;&#xe60b;</a>
				</div>
			</div>
		</form>
	</section>
</div>
<script type="text/javascript">
    //通用JS
    $(function(){
        /* 等待加载 START */
        $('.vip_content').attr('style',"");
        $("#loadingToast").attr('style',"display:none;");
        /* 等待加载 END */

        /* 检测用户输入的是否合法 START */
        $("input").keyup(function(){
            var regular = new RegExp($(this).attr('pattern'));
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            if(!regular.test(inputValue)){
                $("."+inputName+"").addClass('weui_cell_warn');
                $(".weui_btn_primary").addClass('weui_btn_disabled');
            }else{
                $("."+inputName+"").removeClass('weui_cell_warn');
                $(".weui_btn_primary").removeClass('weui_btn_disabled');
            }
        });
        //失去焦点判断
        $("input").focusout(function(){
            var regular = new RegExp($(this).attr('pattern'));
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');
            if(!regular.test(inputValue)){
                $("."+inputName+"").addClass('weui_cell_warn');
                $(".weui_btn_primary").addClass('weui_btn_disabled');
            }else{
                $("."+inputName+"").removeClass('weui_cell_warn');
                $(".weui_btn_primary").removeClass('weui_btn_disabled');
            }
        });

        /* 检测用户输入的是否合法 END */
        //提交JS
        /* 提交信息 START */
        $('.weui_btn_primary').click(function(){
            var form = $("#loginSave"),form_url=form.attr("action"),btn = $(this),loadtip=null;
            form.ajaxSubmit({
                url:form_url,
                dataType:'json',
                timeout:20000,
//                    clearForm:true,
//                    resetForm:true,
                beforeSubmit: function(arr, $form, options){
                    /*验证提交数据*/
                    var _null = false, _msg = '',inputobj=false;
                    for(i in arr){
                        var name = arr[i].name,value=$.trim(arr[i].value);
                        if(name == 'phone' && !value) {
                            _null = true; _msg = '请输入手机号码!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'cardno' && !value) {
                            _null = true; _msg = '请输入储值卡号!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'password' && !value) {
                            _null = true; _msg = '请输入新密码!';inputobj=$("input[name='"+name+"']");break;
                        }
                    }

                    if(_null === true) {
                        new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
                        $(inputobj).focus();
                        return false;
                    }
                    /*end*/

                    var text = btn.text();
                    btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                    loadtip = new AlertBox({content:'提交中',type:'loading',site:'topmid'}).show();
                },
                success: function(result){
                    if(loadtip) loadtip.closedLoading();
                    var text = btn.text();
                    btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                    if(result.err>1){
                        new AlertBox({content:result.msg,type:'info',site:'topmid'}).show();
                    }else if(result.err=='0'){
                        var locat_url="<?php echo base_url('index.php/membervip/login');?>";
                        var lotime=result.msg=='密码重置成功'?100:5000;
                        new AlertBox({content:result.msg,type:'tip',site:'bottom',dourl:locat_url,time:lotime}).show();
                    }
                },
                error:function () {
                    if(loadtip) loadtip.closedLoading();
                    var text = btn.text();
                    btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                    new AlertBox({content:'网络异常,请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                }
            });
        });
        /* 提交信息 END */
    });

</script>
</body>
</html>