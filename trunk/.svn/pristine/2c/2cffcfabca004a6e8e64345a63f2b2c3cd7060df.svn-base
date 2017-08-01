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
	    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/phase2/styles/mycss.css");?>" rel="stylesheet">
<script
	src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<title>注册</title>
</head>
<body>
	<div class="gradient_bg padding_bottom_30">
		<section class="padding_0_15">
			<div class="flex between padding_60 font_12">
				<div class="margin_right_30 relative padding_left_35">
					<div class="line_left absolute top_971">
						<img
							src="<?php echo base_url("public/member/highclass/images/line_03.png")?>"
							alt="">
					</div>
					<div>
						<div class="font_30 iconfont txt_show3">&#xe612;&#xe613;&#xe61a;&#xe605;.</div>
						<div class="iconfont margin_top_8 center relative color3">可获得注册大礼包，享受更多会员优惠</div>
					</div>
				</div>
			</div>
			<form class="form_list font_14" id="loginSave"
				action="<?php echo base_url("index.php/membervip/reg/savereg");?>"
				method="post">
		    <?php if(!empty($login_config) && is_array($login_config)):?>
            <?php foreach ($login_config as $key=>$vo):?>
                <?php if($vo['show']=='1' && $key!='phonesms' && $key!='sex'&&$key!='birthday'):?>
                	<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
						<?=$vo['name'];?>
					</div>
					</div>
					<div class="flex_1">
						<input
							pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>"
							placeholder="<?=$vo['note'];?>" type="<?=$vo['type'];?>"
								class='<?=$key?>'   
							name="<?=$key?>" data-name="<?php echo $vo['name'];?>"
							data-check="<?php echo $vo['check']; ?>" />
					</div>

				</div>
                     <?php endif;?>
		                <?php if($vo['show']=='1' && $key=='sex'):?>
			<div class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120">
						<div class="flex between">
				<?php echo $vo['name']; ?>
					</div>
					</div>
					<div class="flex_1 bg_arrow bg_size1">
						<select class="weui_input select_sex" name="sex"><option value="1">男</option>
							<option value="2">女</option></select>
					</div>

				</div>
			                <?php endif;?>
			                                <?php if($vo['show']=='1' && $key=='phonesms'):?>
			                                			<div
					class="flex form_item bd_bottom padding_18">
					<div class="margin_right_42 width_120"></div>
					<div class="flex_1">
						<input type="<?=$vo['type'];?>"
							pattern="<?php if($vo['check']=='1'):?><?=$vo['regular'];?><?php endif;?>"
							placeholder="<?=$vo['note'];?>" name="<?=$key?>"
							class='<?=$key?>'   
							data-name="<?php echo $vo['name'];?>"
							data-check="<?php echo $vo['check']; ?>" />
					</div>
					<div class="relative border_1_808080 verification smsSend" data-val=0>获取验证码</div>
				</div>
			                                
			                                <?php endif;?>
			    <?php endforeach;?>
        <?php endif;?>
			<div class="margin_top_35 font_17">
					<div
						class="block width_85 center padding_15 auto iconfont entry_btn register">&#xe61a;&ensp;&#xe605;</div>
				</div>
				<div class="center margin_top_30">
					<a class="font_12 "
						href="<?php echo base_url('index.php/membervip/login')?>">已有账号？ <span
						class="main_color1">马上登录</span><em
						class="main_color1 iconfont font_12">&#xe61c;</em></a>
				</div>
			</form>
	
	</div>
	</section>
	</div>
	<script type="text/javascript">
    var inter_id = "<?php echo $inter_id;?>";
    //通用JS
    $(function(){
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
                $(".sign_btn").addClass('disabled');
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
                $("."+inputName+"").addClass('bg_ico_close');
                $(".sign_btn").addClass('disable');
            }else{
                $("."+inputName+"").removeClass('bg_ico_close');
                $(".sign_btn").removeClass('disable');
            }
        });
        /* 检测用户输入的是否合法 END */
        //提交JS
        /* 提交信息 START */
        $('.sign_btn').click(function(){
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
                        var name = arr[i].name,value=$.trim(arr[i].value),obj=$("input[name='"+name+"']"),check=obj.data("check");
                        var regular = new RegExp(obj.attr('pattern'));
                        if(!value) {
                            inputobj = obj;
                            var text_name = obj.data("name");
                            _msg='请输入'+text_name;
                            _null = true;break;
                        }else if(!regular.test(value) && check==1){
                            inputobj = obj;
                            var text_name = obj.data("name");
                            _msg=text_name+'不合法';
                            _null = true;break;
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
                    var text = btn.text();
                    btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                    if(result.err>1){
                        $.MsgBox.Alert(result.msg);
                    }else if(result.err=='0'){
                        if(inter_id=='a421641095' && result.is_package=='1'){
                            handle_send_tmp();
                        }
                        <?php if(isset($_GET['redir']) && !empty($_GET['redir'])){ ?>
                        var locat_url="<?php echo urldecode($_GET['redir']);?>";
                        <?php }else{?>
                        var locat_url="<?php echo base_url('index.php/membervip/center');?>";
                        <?php } ?>

                        $.MsgBox.Alert(result.msg,function(){window.location.href=locat_url;});
                    }
                },
                error:function () {
                    removeload();
                    $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
                }
            });
        });
        /* 提交信息 END */

        $(document).on('click','.company-employee',function (e) {
            e.preventDefault();
            var obj=$('.is-member_type'),show=obj.data("show"),text=$(this).find('a').text();
            if(obj.length>0 && show==0){
                obj.show();
                obj.data("show",1);
                $(this).find('a').text(text.replace('是', '不是'));
                obj.find("input[type='text']").val('');
                obj.find('input').prop('disabled',false);
                $(this).find('a').addClass('warn');
            }else{
                obj.hide();
                obj.data("show",0);
                $(this).find('a').text(text.replace('不是', '是'));
                obj.find("input[type='text']").val('');
                obj.find('input').prop('disabled',true);
                $(this).find('a').removeClass('warn');
            }
        });
    });

    function handle_send_tmp() {
        var name = $("input[name='name']").val(),post_url="<?php echo site_url('membervip/reg/send_tmp_msg');?>";
        $.ajax({
            url:post_url,
            type:'get',
            data:{name:name},
            dataType:'json',
            timeout:20000,
            success: function (result) {
                console.log(result);
            },
            error: function (XMLHttpRequest, textStatus) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
//                new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
            }
        });
    }
</script>
</body>
</html>