<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>会员卡更改密码</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/css/alert.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/js/jquery-1.11.0.min.js");?>"></script>
    <script src="<?php echo base_url(FD_PUBLIC)?>/js/ajaxForm.js"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    <script type="text/javascript" src="<?php echo base_url("public/member/version4.0/js/alert.js");?>"></script>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <div class="hd">
            <h3 class="page_title"></h3>
        </div>
        <!--FROM DATA START-->
        <form id="loginSave" action="<?php echo base_url("index.php/membervip/changepassword/saveupdatepassword");?>" method="post" >
            <input type="hidden" name="smstype" value="4" />
        <div class="bd spacing">
            <div class="weui_cells weui_cells_form">
                <?php if($password_config['phone']['show'] && 0){  ?>
                    <div class="weui_cell weui_vcode phone">
                        <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="<?php echo $password_config['phone']['type']; ?>" pattern="<?php echo $password_config['phone']['regular']; ?>" placeholder="<?php echo $password_config['phone']['note']; ?>"  name="phone" placeholder="请输入验证码"/>
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                        <div class="weui_cell_ft">
                            <a href="javascript:;" data-val='0' style="width:auto;" class="weui_btn  weui_btn_plain_default smsSend">获取验证码</a>
                        </div>
                    </div>
                <?php }?>
                <?php if($password_config['oldpassword']['show']){ ?>
                <div class="weui_cell oldpassword">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $password_config['oldpassword']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $password_config['oldpassword']['type']; ?>" name="oldpassword" pattern="<?php echo $password_config['oldpassword']['regular']; ?>"  placeholder="<?php echo $password_config['oldpassword']['note']; ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($password_config['newpassword']['show']){ ?>
                <div class="weui_cell newpassword">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $password_config['newpassword']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $password_config['newpassword']['type']; ?>" name="newpassword" pattern="<?php echo $password_config['newpassword']['regular']; ?>"  placeholder="<?php echo $password_config['newpassword']['note']; ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>

                    <div class="weui_cell newpassword_con">
                        <div class="weui_cell_hd"><label for='' class="weui_label">确认密码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="<?php echo $password_config['newpassword']['type']; ?>" name="newpassword_con" pattern="<?php echo $password_config['newpassword']['regular']; ?>"  placeholder="确认密码"/>
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>
                <?php }?>

            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary">提交</a>
            </div>
        </div>
        </form>

        <!--FROM DATA END-->
    </div>
    <!--BEGIN START-->
    <div id="toast" style="display:none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">已完成</p>
        </div>
    </div>
    <!--end END-->
    <!--Loading START-->
    <div id="loadingToast" class="weui_loading_toast" style="">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">努力加载中</p>
        </div>
    </div>
    <!--Loading END-->
    <!--dialog start -->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">操作提示</strong></div>
            <div class="weui_dialog_bd">当前账号或密码错误</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
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
                    var tel=$("input[name='phone']").val(),phone=$("input[name='phone']").val(),smstype=$("input[name='smstype']").val();
                    var regular = new RegExp($("input[name='phone']").attr('pattern'));
                    if(!tel || tel==''){
                        new AlertBox({content:'请输入手机号码',type:'tip',site:'bottom'}).show();return false;
                    }else if(!regular.test(tel)){
                        new AlertBox({content:'请输入正确的手机号码',type:'tip',site:'bottom'}).show();return false;
                    }
                    //请求发送验证码
                    var postUrl = "<?php echo base_url("index.php/membervip/sendsms");?>";
                    var datas = {phone:tel,phone:phone,smstype:smstype};
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

                            if(name == 'phone' && !value) {
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
                            var locat_url="<?php echo base_url('index.php/membervip/center');?>";
                            var lotime=result.msg=='密码修改成功'?100:5000;
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
