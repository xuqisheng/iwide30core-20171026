<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>绑定储值卡</title>
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
    <?php include 'wxheader.php' ?>
    <style type="text/css">
        .hd{padding: 0;}
    </style>
</head>
<body ontouchstart>
    <div class="vip_content" style="display:none;">
        <div class="hd">
            <h3 class="page_title"></h3>
        </div>
        <form id="bindcard" action="<?php echo base_url("index.php/membervip/Login/savebindcard");?>" method="post">
            <div class="bd spacing">
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell cardno">
                        <div class="weui_cell_hd"><label class="weui_label">储值卡号</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" pattern="^[A-Za-z0-9]{3,}$" name="cardno" placeholder="请输入储值卡号" />
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>

                    <div class="weui_cell password">
                        <div class="weui_cell_hd"><label class="weui_label">卡号密码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="text" pattern="^[A-Za-z0-9]{6,8}$" name="password" placeholder="请输入密码" />
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>
                </div>
                <div class="weui_cells_title"></div>
                <div class="bd spacing">
                    <a href="javascript:;" class="weui_btn weui_btn_primary">绑定</a>
                </div>

                <div class="bd spacing" style="margin-top: 15px;">
                    <a href="<?php echo base_url("index.php/membervip/resetpassword/resetbindpwd");?>" class="weui_btn weui_btn_plain_primary">找回密码</a>
                </div>
            </div>
        </form>
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
            <div class="weui_dialog_bd">当前储值卡号或密码错误</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary primary_jump ">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
<script>
$(document).ready(function(){
    /* 等待加载 START */
    $('.vip_content').attr('style',"");
    $("#loadingToast").attr('style',"display:none;");
    /* 等待加载 END */

    /* 提交信息 START */
    $('.weui_btn_primary').click(function(){
        var form = $("#bindcard"),form_url=form.attr("action"),btn = $(this),loadtip=null;
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
                    if(name == 'cardno' && !value) {
                        _null = true; _msg = '请输入储值卡号!';inputobj=$("input[name='"+name+"']");break;
                    }

                    if(name == 'password' && !value) {
                        _null = true; _msg = '请输入密码!';inputobj=$("input[name='"+name+"']");break;
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
                loadtip = new AlertBox({content:'绑定中',type:'loading',site:'topmid'}).show();
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
});
</script>
</body>
</html>
