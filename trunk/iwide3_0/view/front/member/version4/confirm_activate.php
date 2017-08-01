<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>确认并激活</title>
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
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <style type="text/css">
        .company-employee{margin-top: 10px;}.is-member_type{display: none;}
        .hd{padding: 0;}
    </style>
</head>
<body ontouchstart>
<div class="vip_content" style="display:none;">
    <div class="hd">
        <h3 class="page_title"></h3>
    </div>
    <!--FROM DATA START-->
    <form id="doActivate" action="<?php echo base_url("index.php/membervip/wechatcard/do_activate?id=").$data['inter_id']."&openid=".$data['open_id'];?>" method="post" >
        <input type="hidden" name="card_id" value="<?php echo $card_id;?>" />
        <input type="hidden" name="card_code" value="<?php echo $card_code;?>" />
        <div class="bd spacing">
            <div class="weui_cells weui_cells_form">
                    <div class="weui_cell name">
                        <div class="weui_cell_hd"><label for='' class="weui_label">手机号码</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <?php echo $data['cellphone'];?>
                        </div>
                        <div class="weui_cell_ft">
                            <i class="weui_icon_warn"></i>
                        </div>
                    </div>
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">会员卡号</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <?php echo $data['membership_number'];?>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">当前等级</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <?php echo $data['lvl_name'];?>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <div class="weui_cell name">
                    <div class="weui_cell_hd"><label for='' class="weui_label">姓名</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <?php echo $data['name'];?>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary" id="doActivateBtn">确认并激活</a>
            </div>
            <div class="weui_cells_title"></div>
            <div class="bd spacing">
                <a href="javascript:;" class="weui_btn weui_btn_primary" id="changeUser">不是本人，换个账号激活</a>
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
            <a href="javascript:;" class="weui_btn_dialog primary primary_jump ">确定</a>
        </div>
    </div>
</div>
<!--dialog end -->
<script type="text/javascript">

    <?php if(isset($wx_config) && !empty($wx_config)):?>
    wx.config({
        debug: false,
        appId: '<?php echo $wx_config["appId"]?>',
        timestamp: <?php echo $wx_config["timestamp"]?>,
        nonceStr: '<?php echo $wx_config["nonceStr"]?>',
        signature: '<?php echo $wx_config["signature"]?>',
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation','hideAllNonBaseMenuItem']
    });
    wx.ready(function(){
        <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
        <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
        wx.hideAllNonBaseMenuItem();
    });
    <?php endif;?>


//通用JS
$(function(){
    /* 等待加载 START */
    $('.vip_content').attr('style',"");
    $("#loadingToast").attr('style',"display:none;");
    /* 等待加载 END */


    $('#doActivateBtn').click(function(){
        var form = $("#doActivate"),form_url=form.attr("action"),btn = $(this),loadtip=null;
        form.ajaxSubmit({
            url:form_url,
            dataType:'json',
            timeout:20000,
//                    clearForm:true,
//                    resetForm:true,
            beforeSubmit: function(arr, $form, options){
                /*验证提交数据*/
                var text = btn.text();
                btn.prop('disabled', true).addClass('weui_btn_disabled').text(text+'中...');
                loadtip = new AlertBox({content:'激活中，请稍等....',type:'loading',site:'topmid'}).show();
            },
            success: function(result){
                if(loadtip) loadtip.closedLoading();
                var text = btn.text();
                btn.prop('disabled',false).removeClass('weui_btn_disabled').text(text.replace('中...', ''));
                if(result.err>1){
                    new AlertBox({content:result.msg,type:'info',site:'topmid'}).show();
                }else if(result.errcode=='0' && result.errmsg =='ok'){
                    new AlertBox({content:'激活成功',type:'tip',site:'bottom',time:100}).show();
                    WeixinJSBridge.call('closeWindow');
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


    $('#changeUser').click(function(){
        var loadtip = new AlertBox({content:'正在退出',type:'loading',site:'topmid'}).show();
        $.post("<?php echo base_url("index.php/membervip/login/outlogin");?>",
            function(result){
                loadtip.closedLoading();
                if(!result) {
                    new AlertBox({content:'请求失败,请刷新重试或联系管理员!',type:'tip',site:'bottom'}).show();return false;
                }
                if(result.err>1){
                    new AlertBox({content:result['msg'],type:'info',site:'mid'}).show();
                }else if(result.err=='0'){
                    var locat_url="<?php echo base_url('index.php/membervip/wechatcard/login_activate?').$params;?>";
                    new AlertBox({content:result['msg'],type:'tip',site:'bottom',dourl:locat_url,time:100}).show();
                }
            }, "json");
    })

});
</script>
</body>
</html>
