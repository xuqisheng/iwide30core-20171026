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
<title>实体卡验证</title>
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
<form id="loginSave" action="<?php echo base_url("index.php/membervip/junting/validate");?>" method="post" >
	<input type="hidden" name="smstype" value="0" />
<div class="list_style_1 bd_bottom">
	<div class="input_item">
    	<div>会员卡号</div>
        <div><input pattern="^[1-9]\d*$" placeholder="请输入会员卡号" type="" name="card_num"></div>
    </div>
</div>
<div class="pad3" style="margin-top:25px">
	 <button type="button" class="bg_main pad12 bdradius" style="width:100%"  id="reg">提交</button>
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
                            if(name == 'card_num' && !value) {
                                _null = true; _msg = '请输入会员卡号!';inputobj=$("input[name='"+name+"']");break;
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
                        loadtip = new AlertBox({content:'正在处理，请稍候',type:'loading',site:'topmid'}).show();
                    },
                    success: function(result){
                        if(loadtip) loadtip.closedLoading();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        if(result.err=='40003'){
                            new AlertBox({content:result.msg,type:'confirm',site:'topmid',okVal:'确定',cancelVal:'关闭',dourl:"<?php echo base_url('index.php/membervip/reg');?>"
                            ).show();
                        }
                        else if(result.err=='0'){
                      	  new AlertBox({content:result.msg,type:'confirm',site:'topmid',okVal:'确定',cancelVal:'关闭',dourl:"<?php echo base_url('index.php/membervip/center');?>",
                        		ok:function () {	location.href = "<?php echo base_url('index.php/membervip/');?>"+'/'+result.url;}
                    		
                             }
                              ).show();
                            
                            var locat_url="<?php echo base_url('index.php/membervip/');?>"+'/'+result.url;
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

