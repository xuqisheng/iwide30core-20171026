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
    <title>重置密码</title>
</head>
<body>
<div class="gradient_bg padding_35">
	<section class="padding_0_15">
		<form class="form_list font_14" id="resetSave" action="<?php echo base_url("index.php/membervip/resetpassword/saveresetpassword");?>" method="post">
			<input type="hidden" name="smstype" value="4" />
            <?php if($login_config['account']['show']){ ?>
                <div class="flex form_item bd_bottom padding_18">
                    <div class="margin_right_42 width_120">
                        <div class="flex between">
                            <span class="block">账</span>
                            <span class="block">号</span>
                        </div>
                    </div>
                    <div class="flex_1"><input class="font_14" type="<?php echo $login_config['account']['type']; ?>" name="account" placeholder="请输入账号"></div>
                </div>
            <?php }?>
            <?php if($login_config['name']['show']){ ?>
                <div class="flex form_item bd_bottom padding_18">
                    <div class="margin_right_42 width_120">
                        <div class="flex between">
                            <span class="block">名</span>
                            <span class="block">字</span>
                        </div>
                    </div>
                    <div class="flex_1"><input class="font_14" type="<?php echo $login_config['name']['type']; ?>" name="name" placeholder="请输入名字"></div>
                </div>
            <?php }?>
            <?php if($login_config['email']['show']){ ?>
                <div class="flex form_item bd_bottom padding_18">
                    <div class="margin_right_42 width_120">
                        <div class="flex between">
                            <span class="block">邮</span>
                            <span class="block">箱</span>
                        </div>
                    </div>
                    <div class="flex_1"><input class="font_14" type="<?php echo $login_config['email']['type']; ?>" name="email" placeholder="请输入邮箱"></div>
                </div>
            <?php }?>
            <?php if($login_config['phone']['show']){ ?>
			<div class="flex form_item bd_bottom padding_18">
				<div class="margin_right_42 width_120">
					<div class="flex between">
						<span class="block">手</span>
						<span class="block">机</span>
						<span class="block">号</span>
						<span class="block">码</span>
					</div>
				</div>
				<div class="flex_1"><input class="font_14" type="<?php echo $login_config['phone']['type']; ?>" name="phone" pattern="<?php echo $login_config['phone']['regular']; ?>" placeholder="请输入手机号码"></div>
			</div>
            <?php }?>
            <?php if($login_config['phonesms']['show']){  ?>
			<div class="flex form_item bd_bottom padding_18">
				<div class="margin_right_42 width_120 block">
					<div class="flex between">
						<span class="block">验</span>
						<span class="block">证</span>
						<span class="block">码</span>
					</div>
				</div>
				<div class="flex_1"><input class="font_14" type="<?php echo $login_config['phonesms']['type']; ?>" pattern="<?php echo $login_config['phonesms']['regular']; ?>" name="phonesms" placeholder="请输入手机验证码"></div>
				<div class="relative verification"><a href="javascript:;" data-val='0' style="width:auto;" class="smsSend weui_btn border_1_808080 weui_btn_plain_default">获取验证码</a></div>
			</div>
            <?php }?>
            <?php if($login_config['password']['show']){ ?>
            <div class="flex form_item bd_bottom padding_18">
				<div class="margin_right_42 width_120">
					<div class="flex between">
						<span class="block">新</span>
						<span class="block">密</span>
						<span class="block">码</span>
					</div>
				</div>
				<div class="flex_1"><input class="font_14" type="<?php echo $login_config['password']['type']; ?>" name="password" placeholder="请输入6-12位登陆密码"></div>
			</div>
            <?php }?>
            <div class="margin_top_35 font_17">
				<a class="block width_85 center padding_15 auto iconfont entry_btn weui_btn_primary">&#xe608;&ensp;&#xe60a;</a>
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
        var postUrl;
        /*60S等待发送短信 START*/
        //发送短息
        var countdown = 60;
        $('.smsSend').click(function(){
            var timestr = $('.smsSend').attr('data-val');
            if(timestr==0){
                var tel=$("input[name='phone']").val(),phonesms=$("input[name='phonesms']").val(),smstype=$("input[name='smstype']").val();
                var regular = new RegExp($("input[name='phone']").attr('pattern'));
                if(!tel || tel==''){
                    new AlertBox({content:'请输入手机号码',type:'tip',site:'bottom'}).show();return false;
                }else if(!regular.test(tel)){
                    new AlertBox({content:'请输入正确的手机号码',type:'tip',site:'bottom'}).show();return false;
                }
                //请求发送验证码
                var postUrl = "<?php echo base_url("index.php/membervip/sendsms");?>";
                var datas = {phone:tel,phonesms:phonesms,smstype:smstype};
                $.ajax({
                    url:postUrl,
                    type:'POST',
                    data:datas,
                    dataType:'json',
                    timeout:6000,
                    success: function (result) {
                        if(result.err=='0'){
                            new AlertBox({content:'短信已发送,请注意查收!',type:'tip',site:'bottom'}).show();
                        }else if(result.err != '0' && result.msg !='' && result.msg != undefined){
                            new AlertBox({content:result.msg,type:'tip',site:'bottom'}).show();
                        }
                    },
                    error: function () {
                        new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                    }
                });
                Timeing();
            }else{
                new AlertBox({content:'请在'+countdown+'秒后点击获取',type:'tip',site:'mid'}).show();
            }
            $('.smsSend').attr('data-val',1);
        });
        function Timeing(){
            if (countdown == 0) {
                $('.smsSend').html('获取验证码');
                countdown = 60;
                $('.smsSend').attr('data-val',0);
            } else {
                $('.smsSend').html("重新获取" + countdown + "S");
                countdown--;
                setTimeout(function() {
                    Timeing();
                },1000)
            }
        }
        /*60S等待发送短信 END*/

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
            var form = $("#resetSave"),form_url=form.attr("action"),btn = $(this),loadtip=null;
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
                        if(name == 'account' && !value) {
                            _null = true; _msg = '请输入帐号!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'name' && !value) {
                            _null = true; _msg = '请输入真实姓名!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'email' && !value) {
                            _null = true; _msg = '请输入邮箱!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'phone' && !value) {
                            _null = true; _msg = '请输入手机号码!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'password' && !value) {
                            _null = true; _msg = '请输入密码!';inputobj=$("input[name='"+name+"']");break;
                        }

                        if(name == 'phonesms' && !value) {
                            _null = true; _msg = '请输入手机验证码!';inputobj=$("input[name='"+name+"']");break;
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
                    if(!result){
                        new AlertBox({content:'网络异常,请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                    }
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