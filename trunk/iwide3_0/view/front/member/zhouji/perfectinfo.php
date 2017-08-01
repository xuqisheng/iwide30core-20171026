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
    <link href="<?php echo base_url("public/member/phase2/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css"); ?>" rel="stylesheet">
    <script src="<?php echo base_url("public/member/phase2/scripts/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js"); ?>"></script>
    <script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
	<script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <title>会员资料</title>
    </head>
<body>
<form id="SaveMemberInfo" action="<?php echo base_url("index.php/membervip/perfectinfo/save");?>" method="post" >
    <input type="hidden" name="smstype" value="0" />
    <div class="list_style bd_bottom">
        <div class="webkitbox justify">
            <div>头像</div>
            <div><img class="headportrait" src="<?php echo $info['headimgurl'];?>" /></div>
        </div>
    </div>
    <div class="list_style martop bd">
		<?php if($modify_config['name']['show']){ ?>
        <div class="webkitbox justify arrow name">
            <div><?php echo $modify_config['name']['name']; ?></div>
            <input type="<?php echo $modify_config['name']['type']; ?>" value="<?php if($centerinfo['name']!='微信用户'){echo $centerinfo['name'];} ?>" name="name" pattern="<?php echo $modify_config['name']['regular']; ?>"  placeholder="<?php echo $modify_config['name']['note']; ?>"/>
        </div>
        <?php }?>
        <?php if($modify_config['phone']['show']){ ?>
        <div class="webkitbox justify arrow phone">
            <div><?php echo $modify_config['phone']['name']; ?></div>
            <input type="<?php echo $modify_config['phone']['type']; ?>" value="<?php echo $centerinfo['cellphone'] ?>" name="phone" pattern="<?php echo $modify_config['phone']['regular']; ?>"  placeholder="<?php echo $modify_config['phone']['note']; ?>" />
        </div>
        <?php }?>
		<?php if($modify_config['email']['show']){ ?>
        <div class="webkitbox justify arrow email">
            <div><?php echo $modify_config['email']['name']; ?></div>
            <input type="<?php echo $modify_config['email']['type']; ?>" value="<?php echo $centerinfo['email'] ?>" pattern="<?php echo $modify_config['email']['regular']; ?>"  placeholder="<?php echo $modify_config['email']['note']; ?>" name="email"/>
        </div>
        <?php }?>    
		<?php if($modify_config['sex']['show']){ ?>
        <div class="webkitbox justify sex arrow sex">
            <div><?php echo $modify_config['sex']['name']; ?></div>
            <div><select class="h28" name="sex" style="padding-left:20%">
            <option <?php if($centerinfo['sex']=="3" ||$centerinfo['sex']=="3"){ echo 'selected'; } ?> value="3" >-</option>
            <option <?php if($centerinfo['sex']=="2"){ echo 'selected'; } ?> value="2" >女</option>
            <option <?php if($centerinfo['sex']=="1"){ echo 'selected'; } ?> value="1" >男</option>
            </select>
            </div>
        </div>
        <?php }?>
		<?php if($modify_config['birthday']['show']){ ?>
                <div class="webkitbox  justify birthday arrow">
                    <div class=""><?php echo $modify_config['birthday']['name']; ?></div>
                       <input name="birthday" class="weui_input diydate"  type="date"  type="text" value="<?php echo date('Y-m-d', $centerinfo['birth'] ); ?>" pattern="<?php echo $modify_config['birthday']['regular']; ?>"  />
                </div>
        <?php }?>
		<?php if($modify_config['idno']['show']){ ?>
        <div class="webkitbox justify idno arrow">
            <div><?php echo $modify_config['idno']['name']; ?></div>
            <input type="<?php echo $modify_config['idno']['type']; ?>" value="<?php echo $centerinfo['id_card_no'] ?>" pattern="<?php echo $modify_config['idno']['regular']; ?>"  placeholder="<?php echo $modify_config['idno']['note']; ?>" name="idno"/>
        </div>
        <?php }?>
		<?php if($modify_config['phonesms']['show']){  ?>
        <div class="webkitbox justify phonesms arrow">
            <div>验证码</div>
            <div><input type="<?php echo $modify_config['phonesms']['type']; ?>" pattern="<?php echo $modify_config['phonesms']['regular']; ?>" placeholder="<?php echo $modify_config['phonesms']['note']; ?>"  name="phonesms" /></div>
            <div><span class="smsSend send_out" data-val="0">获取验证码</span></div>
        </div>
        <?php }?>
        
    </div>
    <div class="sign_btn bg_main color_fff">保存</div>
<!--    <div class="sign_list center C_b5b5b5"><a class="" href="sgin_in.html">账户安全设置</a>-->
</form>
    <script>
        //通用JS
        $(function(){
            var postUrl;
            /*60S等待发送短信 START*/
            //发送短息
            var countdown = 60;
			$('.diydate').change(function(){
				$('input[name="birthday"]').val($(this).val());
			})
			
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
								$('.smsSend').removeClass("C_b5b5b5");
								$('.smsSend').html('重新获取')
                            }
                        },
                        error: function () {
                        	$.MsgBox.Alert('发送失败,请刷新重试或联系管理员!');
							$('.smsSend').removeClass("C_b5b5b5");
							$('.smsSend').html('重新获取')
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
                var form = $("#SaveMemberInfo"),form_url=form.attr("action"),btn = $(this),loadtip=null;
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
                            if(name == 'name' && !value) {
                                _null = true; _msg = '请输入真实姓名!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'phone' && !value) {
                                _null = true; _msg = '请输入手机号码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'email' && !value) {
                                _null = true; _msg = '请输入邮箱!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'idno' && !value) {
                                _null = true; _msg = '请输入证件号码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'phonesms' && !value) {
                                _null = true; _msg = '请输入手机验证码!';inputobj=$("input[name='"+name+"']");break;
                            }
                        }

                        if(_null === true) {
							$.MsgBox.Alert(_msg);
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
                            var locat_url="<?php echo base_url('index.php/membervip/center');?>";
                        	$.MsgBox.Confirm(result.msg,function(){window.location.href=locat_url;});
							$('#mb_btn_no').remove();
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
