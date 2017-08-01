<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>会员卡登录</title>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
    
<style>
.weui_cell:before{width:0}
.weui_cells{padding-left:15px}
.weui_cells .weui_cell{padding-left:0; border-top:1px solid #e4e4e4;}
.weui_cells .weui_cell:first-child{border-top:0}
</style>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <!--FROM DATA START-->
        <form id="loginSave" action="<?php echo base_url("index.php/membervip/login/savelogin");?>" method="post" >
            <input type="hidden" name="smstype" value="2" />
        <div class="bd">
            <div class="weui_cells weui_cells_form">
                <?php if($login_config['account']['show']){ ?>
                <div class="weui_cell account">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['account']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['account']['type']; ?>" name="account" pattern="<?php echo $login_config['account']['regular']; ?>"  placeholder="<?php echo $login_config['account']['note']; ?>" />
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['phone']['show']){ ?>
                <div class="weui_cell phone">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['phone']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['phone']['type']; ?>" name="phone" pattern="<?php echo $login_config['phone']['regular']; ?>"  placeholder="<?php echo $login_config['phone']['note']; ?>" />
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['password']['show']){ ?>
                <div class="weui_cell password">
                    <div class="weui_cell_hd"><label for='' class="weui_label"><?php echo $login_config['password']['name']; ?></label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="<?php echo $login_config['password']['type']; ?>" pattern="<?php echo $login_config['password']['regular']; ?>"  placeholder="<?php echo $login_config['password']['note']; ?>" name="password"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <?php }?>
                <?php if($login_config['phonesms']['show']){  ?>
                    <div class="weui_cell weui_vcode phonesms">
                        <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="<?php echo $login_config['phonesms']['type']; ?>" pattern="<?php echo $login_config['phonesms']['regular']; ?>" placeholder="<?php echo $login_config['phonesms']['note']; ?>"  name="phonesms" />
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
            <div class="bd" style="width:62.5%; padding-top:20px;margin:auto;">
                <a href="javascript:;" class="weui_btn weui_btn_primary" style="background:#005bac">登录</a>
            </div>
        </div>
        <!-- <div class="weui_actionsheet_cell">
            <br/><span >还没有账号？<a href="#">立即注册</a></span>
            <br/><span >忘记密码？请点击<a href="#">找回密码</a></span>
        </div> -->
        </form>
        <div><br/>
            <div class="weui_cell" style="width: 65%;margin: auto; padding:0">
                <div class="weui_cell_bd ">
                    <a href="<?php echo base_url("index.php/membervip/reg");?>" style="margin-left:3px;">立即注册</a>
                </div>
                <div class="weui_cell_ft weui_cell_primary">
                    <a href="<?php echo base_url("index.php/membervip/resetpassword");?>">找回密码</a>
                </div>
            </div>
        </div>
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
                    //请求发送验证码
                    postUrl = "<?php echo base_url("index.php/membervip/sendsms");?>";
                    form.submit();  
                    Timeing();     
                }else{
                    $('.weui_dialog_bd').html('请'+countdown+'秒后点击获取');
                    $('#dialog2').show();
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
            var form = $("#loginSave");
            form.submit(function(){
                $.post( postUrl ,
                form.serialize(),
                function(result,status){
                    if(result['err']>1){
                        $('.weui_dialog_bd').html(result['msg']);
                        $('#dialog2').attr('style','');
                    }else{
                        $('.weui_dialog_bd').html(result['msg']);
                        $('#dialog2').attr('style','');
                        if(result['msg']=="登录成功"){
                            window.location.href="<?php echo base_url('index.php/membervip/center');?>"; 
                        }
                    }
                },'json');
                return false;
            });
            $('.weui_btn_primary').click(function(){
                postUrl = form.attr("action");
                form.submit();
            });
            /* 提交信息 END */
        });
        
    </script>
</body>
</html>
