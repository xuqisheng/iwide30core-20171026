<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>会员资料</title>
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
        <form id="SaveMemberInfo" action="<?php echo base_url("index.php/membervip/perfectinfo/save");?>" method="post" >
            <input type="hidden" name="smstype" value="0" />
        <div class="bd spacing">
            <div class="weui_cells weui_cells_form">
                <?php if($modify_config['name']['show']){ ?>
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['name']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $modify_config['name']['type']; ?>" value="<?php if($centerinfo['name']!='微信用户'){echo $centerinfo['name'];} ?>" name="name" pattern="<?php echo $modify_config['name']['regular']; ?>"  placeholder="<?php echo $modify_config['name']['note']; ?>"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['phone']['show']){ ?>
                <div class="weui_cell phone">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['phone']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $modify_config['phone']['type']; ?>" value="<?php echo $centerinfo['cellphone'] ?>" name="phone" pattern="<?php echo $modify_config['phone']['regular']; ?>"  placeholder="<?php echo $modify_config['phone']['note']; ?>" />
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['email']['show']){ ?>
                <div class="weui_cell email">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['email']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $modify_config['email']['type']; ?>" value="<?php echo $centerinfo['email'] ?>" pattern="<?php echo $modify_config['email']['regular']; ?>"  placeholder="<?php echo $modify_config['email']['note']; ?>" name="email"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['sex']['show']){ ?>
                <div class="weui_cell sex">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['sex']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_input" name="sex" >
                            <option <?php if($centerinfo['sex']=="3" ||$centerinfo['sex']=="3"){ echo 'selected'; } ?> value="3" >请选择</option>
                            <option <?php if($centerinfo['sex']=="2"){ echo 'selected'; } ?> value="2" >女</option>
                            <option <?php if($centerinfo['sex']=="1"){ echo 'selected'; } ?> value="1" >男</option>
                        </select>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['birthday']['show']){ ?>
                <div class="weui_cell birthday">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['birthday']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="birthday" type="date" value="<?php echo date('Y-m-d',$centerinfo['birth']); ?>" pattern="<?php echo $modify_config['birthday']['regular']; ?>" >
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['idno']['show']){ ?>
                <div class="weui_cell idno">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $modify_config['idno']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $modify_config['idno']['type']; ?>" value="<?php echo $centerinfo['id_card_no'] ?>" pattern="<?php echo $modify_config['idno']['regular']; ?>"  placeholder="<?php echo $modify_config['idno']['note']; ?>" name="idno"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($modify_config['phonesms']['show']){  ?>
                    <div class="weui_cell weui_vcode phonesms">
                        <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="<?php echo $modify_config['phonesms']['type']; ?>" pattern="<?php echo $modify_config['phonesms']['regular']; ?>" placeholder="<?php echo $modify_config['phonesms']['note']; ?>"  name="phonesms" placeholder="请输入验证码"/>
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                        <div class="weui_cell_ft">
                            <a href="javascript:;" data-val='0' style="width:auto;" class="weui_btn  weui_btn_plain_default smsSend">获取验证码</a>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary">保存</a>
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
                            }
                        },
                        error: function () {
                            new AlertBox({content:'发送失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                        }
                    });
                    Timeing();
                }else{
                    new AlertBox({content:'请'+countdown+'秒后点击获取',type:'tip',site:'mid'}).show();
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
                            new AlertBox({content:_msg,type:'tip',site:'bottom',time:2000}).show();
                            $(inputobj).focus();
                            return false;
                        }
                        /*end*/

                        var text = btn.text();
                        btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                        loadtip = new AlertBox({content:'保存中',type:'loading',site:'topmid'}).show();
                    },
                    success: function(result){
                        if(loadtip) loadtip.closedLoading();
                        var text = btn.text();
                        btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                        if(result.err>1){
                            new AlertBox({content:result.msg,type:'info',site:'topmid'}).show();
                        }else if(result.err=='0'){
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
        });
        
    </script>
</body>
</html>
