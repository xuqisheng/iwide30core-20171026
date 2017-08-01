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
<title>会员卡注册</title>
<link rel="stylesheet" href="<?php echo base_url("public/member/styles/global.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
<link rel="stylesheet" href="<?php echo base_url("public/member/junting/styles/junting.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/js/jquery-1.11.0.min.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
</head>
<body>

<div style="padding:50px 10px 25px 10px;" class="center">
	<img style="width:auto; height:70px" src="<?php echo base_url("public/member/junting/icon/logo.png");?>">
    <p class="pad3">加入君亭四季会</p>
</div>
<form id="loginSave" action="<?php echo base_url("index.php/membervip/reg/savereg");?>" method="post" >
	<input type="hidden" name="smstype" value="0" />
<div class="list_style_1 bd_bottom">
    <input type="hidden" name='junting_card' value='<?php echo isset($_GET['junting_card']) ?  $_GET['junting_card'] : '' ;?>'>
	<?php if($login_config['name']['show']){ ?>
	<div class="input_item">
    	<div><?php echo $login_config['name']['name']; ?></div>
        <div><input pattern="<?php echo $login_config['name']['regular']; ?>" placeholder="<?php echo $login_config['name']['note']; ?>" type="<?php echo $login_config['name']['type']; ?>" name="name"></div>
    </div>
    <?php }?>
	<?php if($login_config['sex']['show']){ ?>
	<div class="input_item">
    	<div><?php echo $login_config['sex']['name']; ?></div>
        <div><select name="sex" >
                <option value="1" >男</option>
                <option value="2" >女</option>
            </select></div>
    </div>
    <?php }?>
	<?php if($login_config['phone']['show']){ ?>
	<div class="input_item">
    	<div><?php echo $login_config['phone']['name']; ?></div>
				<div> <input class="weui_input" type="<?php echo $login_config['phone']['type']; ?>" name="phone" pattern="<?php echo $login_config['phone']['regular']; ?>"  placeholder="<?php echo $login_config['phone']['note']; ?>" />
 </div>
    </div>
    <?php }?>
    <?php if($login_config['password']['show']){ ?>  
	<div class="input_item">
    	<div><?php echo $login_config['password']['name']; ?></div>
        <div><input class="weui_input" type="<?php echo $login_config['password']['type']; ?>" pattern="<?php echo $login_config['password']['regular']; ?>"  placeholder="<?php echo $login_config['password']['note']; ?>" name="password"/>
</div>
    </div>
    <?php }?>
	<?php if($login_config['email']['show']){ ?>
	<div class="input_item">
    	<div><?php echo $login_config['email']['name']; ?></div>
        <div><input type="<?php echo $login_config['email']['type']; ?>" pattern="<?php echo $login_config['email']['regular']; ?>"  placeholder="<?php echo $login_config['email']['note']; ?>" name="email"></div>
    </div>
    <?php }?>
	<?php if($login_config['idno']['show']){ ?>
	<div class="input_item">
    	<div><?php echo $login_config['idno']['name']; ?></div>
        <div><input type="<?php echo $login_config['idno']['type']; ?>" pattern="<?php echo $login_config['idno']['regular']; ?>"  placeholder="<?php echo $login_config['idno']['note']; ?>" name="idno"></div>
    </div>
    <?php }?>
	<?php if($login_config['phonesms']['show']){  ?>
	<div class="input_item justify phonesms">
    	<div>验证码</div>
        <div style="box-flex: 1;-webkit-box-flex: 1;"><input type="<?php echo $login_config['phonesms']['type']; ?>" pattern="<?php echo $login_config['phonesms']['regular']; ?>" placeholder="<?php echo $login_config['phonesms']['note']; ?>"  name="phonesms"></div>
        <div><span data-val='0' style="padding:2px 6px; font-size:10px; line-height:1.5" class="btn_main xs bdradius h22 smsSend">获取验证码</span></div>
    </div>
    <?php }?>
</div>
<div class="pad3" style="margin-top:25px">
	 <button type="button" class="bg_main pad12 bdradius" style="width:100%"  id="reg">注册</button>
</div>
</form>
<script type="text/javascript">
        var inter_id = "<?php echo $inter_id;?>";
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
                                if(result.err=='2019'){
                                    new AlertBox({content:result.msg,type:'confirm',site:'topmid',okVal:'登录',cancelVal:'关闭',dourl:"<?php echo base_url('index.php/membervip/reg');?>",ok:function () {
                                        window.location.href="<?php echo base_url('index.php/membervip/login');?>";
                                    },cancel:function () {
                                        location.reload();
                                    }}).show();
                            	  
                                    }
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
            $('#reg').click(function(){
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

                            if(name == 'idno' && !value) {
                                _null = true; _msg = '请输入证件号码!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'company_name' && !value) {
                                _null = true; _msg = '请输入公司名称!';inputobj=$("input[name='"+name+"']");break;
                            }

                            if(name == 'employee_id' && !value) {
                                _null = true; _msg = '请输入员工号码!';inputobj=$("input[name='"+name+"']");break;
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
                        loadtip = new AlertBox({content:'注册中',type:'loading',site:'topmid'}).show();
                    },
                    success: function(result){
                        if(loadtip) loadtip.closedLoading();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        if(result.err>1){
                            new AlertBox({content:result.msg,type:'info',site:'topmid'}).show();
                        }else if(result.err=='0'){
                            if(inter_id=='a421641095' && result.is_package=='1'){
                                handle_send_tmp();
                            }
                            var locat_url="<?php echo base_url('index.php/membervip/center');?>";
                            new AlertBox({content:result.msg,type:'tip',site:'bottom',dourl:locat_url,time:100}).show();
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

            $(document).on('click','.company-employee',function (e) {
                e.preventDefault();
                var obj=$('.is-member_type'),show=obj.data("show"),text=$(this).find('a').text();
                if(obj.length>0 && show==0){
                    obj.show();
                    obj.data("show",1);
                    $(this).find('a').text(text.replace('是', '不是'));
                    obj.find("input[type='text']").val('');
                    obj.find('input').prop('disabled',false);
                    $(this).find('a').removeClass('weui_btn_plain_primary');
                    $(this).find('a').addClass('weui_btn_warn');
                }else{
                    obj.hide();
                    obj.data("show",0);
                    $(this).find('a').text(text.replace('不是', '是'));
                    obj.find("input[type='text']").val('');
                    obj.find('input').prop('disabled',true);
                    $(this).find('a').removeClass('weui_btn_warn');
                    $(this).find('a').addClass('weui_btn_plain_primary');
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

