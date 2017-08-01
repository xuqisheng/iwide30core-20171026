<!doctype html>
<html class="w_h_100">
<head>
    <meta charset="utf-8">
    <title><?=!empty($title)?$title:'扫码核销'?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes" >
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="ML-Config" content="fullscreen=yes,preventMove=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?=base_url("public/member/public/css/jie_h.css");?>" />
    <link href="<?php echo base_url("public/member/phase2/styles/global.css");?>" rel="stylesheet">
    <script src="<?php echo base_url('public/soma/scripts/jquery.js');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/ui_control.js');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/imgscroll.js');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="<?php echo base_url('public/member/scripts/alert.js');?>"></script>
    <style>
    </style>
</head>
<body class="w_h_100">
<?php if($type == 1):?>
<div class="flex column box_content center">
    <div class="sao_icon margin_bottom_20 flex centers" id="logo_div">
        <img class="" src="<?=base_url("public/member/public");?>/images/icon16.png" alt="" style="width:2.1rem;height:2.1rem;">
    </div>
    <p class="center content_txt color_555 width_65 color_333">点击上方图标，开始核销</p>
</div>
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

        function call_qrcode(){
            wx.scanQRCode({
                needResult: 1,
                scanType: ["qrCode","barCode"],
                success: function (res) {
                    var result = res.resultStr;
                    pageloading('核销中，请稍后……');
                    $.post('<?php echo $callback; ?>', {'code':result}, function(res){
                        removeload();
                        $.MsgBox.Alert( res['msg'] );
                    }, 'json');
                }
            });
            $("title").html("扫码核销");
        }
        $('#logo_div').click(function(){ call_qrcode(); });
    </script>
<?php else:?>
    <div class="flex column box_content center" style="padding: 15rem 0;">
        <p class="center content_txt color_555 width_65 color_999"><?=!empty($message)?$message:'您没有权限进行此操作！'?></p>
        <p class="center content_txt color_555 width_65 color_999"><tt>5</tt>秒后关闭</p>
    </div>
    <script type="text/javascript">
        window.onload=function(){
            window.setTimeout(function(){
                wx.closeWindow();
            },5000);
        }
    </script>
<?php endif;?>
</body>
</html>
