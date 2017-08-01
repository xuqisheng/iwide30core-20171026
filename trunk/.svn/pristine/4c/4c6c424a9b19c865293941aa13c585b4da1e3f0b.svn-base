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
    <meta name="viewport" content="width=320,initial-scale=1,user-scalable=0">
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/green.css");?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<title><?php echo $inter_id=='a492669988'? '绑定':'登陆'?></title>
</head>
<body>

<form id="loginSave" action="<?php echo base_url("index.php/membervip/login/savelogin");?>" method="post" >
    <input type="hidden" name="smstype" value="2" />
    <div class="list_style bd_bottom">
        <?php if(!empty($login_config) && is_array($login_config)):?>
            <?php foreach ($login_config as $key=>$vo):?>
                <?php if($vo['show']=='1' && $key!='phonesms'):?>
                    <div class="input_item <?=$key?>">
                        <div><?=$vo['name'];?></div>
                        <div><input pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>" placeholder="<?=$vo['note'];?>" type="<?=$vo['type'];?>" name="<?=$key?>" data-name="<?php echo $vo['name'];?>" data-check="<?php echo $vo['check']; ?>" /></div>
                    </div>
                <?php endif;?>
                <?php if($vo['show']=='1' && $key=='phonesms'):?>
                    <div class="input_item relative <?=$key?>">
                        <div>验证码</div>
                        <div><input type="<?=$vo['type'];?>" pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>" placeholder="<?=$vo['note'];?>" name="<?=$key?>" data-name="<?php echo $vo['name'];?>" data-check="<?php echo $vo['check']; ?>" /></div>
                        <div><span data-val="0" class="smsSend send_out">获取验证码</span></div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <div class="sign_btn bg_main"><?php echo $inter_id=='a492669988'? '绑定':'登录'?></div>
    <div class="sign_list">
    <?php if($inter_id!='a486201893'&&$inter_id!='a492669988'){?>
        <a class="f_r" href="<?php echo base_url("index.php/membervip/resetpassword");?>">忘记密码?</a>
        <?php }?>
        <a href="<?php echo site_url("membervip/reg/index"."?redir=".$redir);?>">注册账户</a>
    </div>
</form>
<!--dialog end -->
<script type="text/javascript">
    //通用JS
    $(function(){
        var postUrl;
        //发送短息
        var countdown = 60;
        $('.smsSend').click(function(){
            var timestr = $('.smsSend').attr('data-val');
            if(timestr==0){
                var tel=$("input[name='phone']").val(),phonesms=$("input[name='phonesms']").val(),smstype=$("input[name='smstype']").val();
                var regular = new RegExp($("input[name='phone']").attr('pattern'));
                if(!tel || tel==''){
                    $.MsgBox.Alert('请输入手机号码');return false;
                }else if(!regular.test(tel)){
                    $.MsgBox.Alert('请输入正确的手机号码');return false;
                }
                $('.smsSend').addClass('C_b5b5b5');
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
                            $.MsgBox.Alert('短信已发送,请注意查收!');
                        }else if(result.err != '0' && result.msg !='' && result.msg != undefined){
                            $.MsgBox.Alert('result.msg');
                        }
                        $('.smsSend').removeClass('C_b5b5b5');
                        $('.smsSend').html('重新获取')
                    },
                    error: function () {
                        $.MsgBox.Alert('发送失败,请刷新重试或联系管理员!');
                    }
                });
                Timeing();
            }else{
                $.MsgBox.Alert('请在'+countdown+'秒后点击获取');
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

        //失去焦点判断
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
        /* 检测用户输入的是否合法 END */
        //提交JS
        /* 提交信息 START */
        $('.sign_btn').click(function(){
            var form = $("#loginSave"),form_url=form.attr("action"),btn = $(this),loadtip=null;
            postUrl = form.attr("action");
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
                            _null = true; _msg = '请输入登录帐号或邮箱!';inputobj=$("input[name='"+name+"']");break;
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
                    if(result.err == '2018'){
                   	 $.MsgBox.Confirm(result.msg,function(){window.location.href="<?php echo site_url('membervip/reg').'?redir='.$redir;?>"},'','注册','取消')
                        
                          return false;
						}
                    else if(result.err>1){
                        $.MsgBox.Alert(result.msg);
                    }else if(result.err=='0'){
                        $.MsgBox.Alert(result.msg,function(){window.location.href=<?php echo "'{$succ_url}'";?>;});
                    }
                },
                error:function () {
                    removeload();
                    $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
                }
            });
        });
        /* 提交信息 END */
    });
</script>
</body>
</html>
