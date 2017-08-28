<!doctype html>
<html class="w_h_100">
<head>
<meta charset="utf-8">
<title>申请核销权限</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
<link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url("public/member/public/css/jie_h.css");?>" />
<script src="<?php echo base_url("public/member/phase2/scripts/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/phase2/scripts/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/phase2/scripts/alert.js");?>"></script>
<?php include 'wxheader.php' ?>
<style>
</style>
</head>
<body class="w_h_100">
<?php if(empty($scanqr_auth) OR ($scanqr_auth['status'] != 1 && $scanqr_auth['status'] != 3)):?>
    <div id="apply-auth">
        <div class="flex column box_content center">
            <div class="sao_icon margin_bottom_20 flex centers">
                <img class="" src="<?=base_url("public/member/public");?>/images/icon16.png" alt="" style="width:2.1rem;height:2.1rem;">
            </div>
            <p class="center content_txt color_555 width_65 color_333">是否申请扫码核销权限？</p>
        </div>
        <div class="flex column">
            <a class="block color_fff bg_ff9900 radius_3 center width_82 padding_12_0 margin_bottom_12 sub_pay" href="javascirpt:void(0);">申请授权</a>
            <a class="block color_999 border_ccc_1 radius_3 center width_82 padding_12_0 close_window" href="javascirpt:void(0);">关闭页面</a>
        </div>
    </div>

    <div id="success-auth" class="none">
        <div class="flex column box_content center">
            <div class="sao_icon margin_bottom_20 flex centers">
                <img class="" src="<?=base_url("public/member/public");?>/images/icon16.png" alt="" style="width:2.1rem;height:2.1rem;">
            </div>
            <p class="center content_txt color_555 width_65 color_333 margin_bottom_20">您的扫码核销权限申请已受理， 管理员授权后即可使用</p>
            <p class="center content_txt color_555 width_65 color_999">如管理员未进行授权，您可关闭该页面， 不会影响您的授权申请。</p>
        </div>
        <div class="floot absolute" style="bottom:30px;left:0px;">
            <div class="flex column center">
                <p class="color_666 width_65 margin_bottom_15">使用提示：您可在会员中心查看 “<span class="color_ff9900 text_nowrap">扫码核销</span>” 功能，点击进入即可开始扫码核销</p>
                <p class="color_333 width_65">祝您使用愉快！</p>
            </div>
        </div>
    </div>
<?php elseif(!empty($scanqr_auth) && $scanqr_auth['status'] == 1):?>
    <div class="flex column box_content center">
        <div class="sao_icon margin_bottom_20 flex centers">
            <img class="" src="<?=base_url("public/member/public");?>/images/icon16.png" alt="" style="width:2.1rem;height:2.1rem;">
        </div>
        <p class="center content_txt color_555 width_65">恭喜您，您的授权申请已通过。</p>
    </div>
    <div class="floot absolute" style="bottom: 30%;left:0px;">
        <div class="flex column center">
            <p class="color_666 width_65 margin_bottom_15">使用提示：您可在会员中心查看 “<span class="color_ff9900 text_nowrap">扫码核销</span>” 功能，点击进入即可开始扫码核销</p>
            <p class="color_333 width_65">祝您使用愉快！</p>
        </div>
    </div>
<?php elseif(!empty($scanqr_auth) && $scanqr_auth['status'] == 3):?>
    <div class="flex column box_content center">
        <div class="sao_icon margin_bottom_20 flex centers">
            <img class="" src="<?=base_url("public/member/public");?>/images/icon16.png" alt="" style="width:2.1rem;height:2.1rem;">
        </div>
        <p class="center content_txt color_555 width_65 color_333 margin_bottom_20">您的扫码核销权限申请已受理， 管理员授权后即可使用</p>
        <p class="center content_txt color_555 width_65 color_999">如管理员未进行授权，您可关闭该页面， 不会影响您的授权申请。</p>
    </div>
    <div class="floot absolute" style="bottom:30px;left:0px;">
        <div class="flex column center">
            <p class="color_666 width_65 margin_bottom_15">使用提示：您可在会员中心查看 “<span class="color_ff9900 text_nowrap">扫码核销</span>” 功能，点击进入即可开始扫码核销</p>
            <p class="color_333 width_65">祝您使用愉快！</p>
        </div>
    </div>
<?php endif;?>
<script type="text/javascript">
    wx.config({
        debug: false,
        appId:'<?php echo $signpackage["appId"];?>',
        timestamp:<?php echo $signpackage["timestamp"];?>,
        nonceStr:'<?php echo $signpackage["nonceStr"];?>',
        signature:'<?php echo $signpackage["signature"];?>',
        jsApiList: [
            'scanQRCode',
            'closeWindow',
        ]
    });


    wx.ready(function(){
        $(".close_window").click(function (e) {
            wx.closeWindow(); //关闭页面
        });
    });

    var check_apply;
    <?php if(!empty($scanqr_auth) && ($scanqr_auth['status'] == 1 OR $scanqr_auth['status'] == 3)):?>
    check_apply = setInterval(sp_check_apply,3500); //设置一个定时器，获得定时器的ID
    <?php endif;?>

    /* 提交信息 START */
    $('.sub_pay').click(function(){
        var url = "<?=EA_const_url::inst()->get_url('*/*/apply');?>",obj = this;
        var rand_code = "<?=$rand_code?>";
        $.ajax({
            url:url,
            type:'POST',
            data:{rand_code:rand_code},
            dataType:'json',
            timeout:15000,
            beforeSend:function (XMLHttpRequest) {
                pageloading();
            },
            success: function (result) {
                if(result.status == 1){
                    $("#apply-auth").hide();
                    $("#success-auth").show();
                    if(check_apply){
                        clearInterval(check_apply);
                        check_apply = null;
                    }
                    check_apply = setInterval(sp_check_apply,3500); //设置一个定时器，获得定时器的ID
                }else {
                    $.MsgBox.Alert(result.message);
                }
            },
            error: function () {
                $.MsgBox.Alert('网络异常,请求失败,请刷新重试或联系管理员!');
            },
            complete:function (XMLHttpRequest, textStatus) {
                removeload();
            }
        });
    });
    /* 提交信息 END */

    function sp_check_apply() {
        var url = "<?=EA_const_url::inst()->get_url('*/*/check_apply')?>",fc = "<?=$rand_code?>";
        var rand_code = "<?=$rand_code?>";
        $.ajax({
            url:url,
            type:'POST',
            data:{rand_code:rand_code},
            dataType:'json',
            timeout:15000,
            success: function (result) {
                if(result.status == 1){
                    if(check_apply){
                        clearInterval(check_apply);
                        check_apply = null;
                    }
                    window.location.href=result.data;
                }
            }
        });
    }
</script>
</body>
</html>
