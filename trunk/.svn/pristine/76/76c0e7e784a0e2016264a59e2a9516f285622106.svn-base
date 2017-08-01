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
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
<!-- 全局控制 -->
<link rel="stylesheet"
	href="<?php echo base_url("public/member/highclass/css/global.css")?> "
	type="text/css">
<link rel="stylesheet"
	href="<?php echo base_url("public/member/highclass/css/mycss.css")?>"
	type="text/css">
<script
	src="<?php echo base_url("public/member/highclass/js/jquery.js")?>"></script>
<script
	src="<?php echo base_url("public/member/highclass/js/myjs.js")?>"></script>
<title>个人信息</title>
</head>
<body>
	<div class="gradient_bg padding_35">
		<section class="padding_0_15">
			<form class="form_list font_14" id="SaveMemberInfo"
				action="<?php echo base_url("index.php/membervip/perfectinfo/save");?>"
				method="post">
		<?php if($modify_config['name']['show']){ ?>
			<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120"><?php echo $modify_config['name']['name']; ?></div>
					<div class="flex_1">
						<input class="font_14"
							type="<?php echo $modify_config['name']['type']; ?>"
							value="<?php if($centerinfo['name']!='微信用户'){echo $centerinfo['name'];} ?>"
							name="name"
							pattern="<?php echo $modify_config['name']['regular']; ?>"
							placeholder="<?php echo $modify_config['name']['note']; ?>" />
					</div>
				</div>
			<?php }?>
					<?php if($modify_config['sex']['show']){ ?>
			<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">性</span> <span class="block">别</span>
						</div>
					</div>
					<div class="flex_1 bg_arrow">
						<select class="font_15" name="sex">
							<option
								<?php if($centerinfo['sex']=="3" ||$centerinfo['sex']=="3"){ echo 'selected'; } ?>
								value="3">-</option>
							<option <?php if($centerinfo['sex']=="2"){ echo 'selected'; } ?>
								value="2">女</option>
							<option <?php if($centerinfo['sex']=="1"){ echo 'selected'; } ?>
								value="1">男</option>
						</select>
					</div>
				</div>
				<?php }?>
						<?php if($modify_config['birthday']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120"><?php echo $modify_config['birthday']['name']; ?></div>
					<div class="flex_1  bg_arrow">
						<input name="birthday" class="weui_input diydate" type="date"
							type="text"
							value="<?php echo date('Y-m-d', $centerinfo['birth'] ); ?>"
							pattern="<?php echo $modify_config['birthday']['regular']; ?>" />
					</div>
				</div>
				        <?php }?>
				        		<?php if($modify_config['idno']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120 block">身份证号</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['idno']['type']; ?>"
							value="<?php echo $centerinfo['id_card_no'] ?>"
							pattern="<?php echo $modify_config['idno']['regular']; ?>"
							placeholder="<?php echo $modify_config['idno']['note']; ?>"
							name="idno" />

					</div>
				</div>
				<?php }?>
						<?php if($modify_config['email']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">邮</span> <span class="block">箱</span> <span
								class="block">地</span> <span class="block">址</span>
						</div>
					</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['email']['type']; ?>"
							value="<?php echo $centerinfo['email'] ?>"
							pattern="<?php echo $modify_config['email']['regular']; ?>"
							placeholder="<?php echo $modify_config['email']['note']; ?>"
							name="email" />

					</div>
				</div>
				<?php }?>
				        <?php if($modify_config['phone']['show']){ ?>
				<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
							<span class="block">手</span> <span class="block">机</span> <span
								class="block">号</span> <span class="block">码</span>
						</div>
					</div>
					<div class="flex_1">
						<input type="<?php echo $modify_config['phone']['type']; ?>"
							value="<?php echo $centerinfo['cellphone'] ?>" name="phone"
							pattern="<?php echo $modify_config['phone']['regular']; ?>"
							placeholder="<?php echo $modify_config['phone']['note']; ?>" />

					</div>
				</div>
				<?php }?>
				<div class="margin_top_75 font_17">
					<a
						class="block width_85 center padding_15 auto iconfont entry_btn preservation">&#xe608;&ensp;&#xe60a;</a>
				<div class="center margin_top_30">
<!-- 					<a class="font_12 " href="index1.html"><span class="main_color1">退出登录</span></a> -->
				</div>
			</form>
		</section>
	</div>
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