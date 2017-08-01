<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url("public/member/version4.0/js/lk/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/version4.0/js/lk/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/version4.0/js/lk/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/version4.0/js/lk/imgscroll.js");?>"></script>
<script src="<?php echo base_url("public/member/version4.0/js/lk/jquery.touchwipe.min.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/version4.0/css/lk/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/version4.0/css/lk/store.css");?>" rel="stylesheet">
<title>我的储值卡</title>
<style>
body,html{background:#f8f8f8}
.cards_list .item .ui_img_auto_cut {height:auto}
.cards_list .item .ui_img_auto_cut img{ position:static}
.ui_foot_btn{ margin:0; width:100%; border-radius:0}
</style>
<body>
<script>

</script>
<div class="page" style="padding-bottom:15%">
    <div class="cards_list">
        <a href="<?php echo base_url("index.php/membervip/giftcards/qrcode");?>" class="item">
            <div class="ui_img_auto_cut"><img src="http://7n.cdn.iwide.cn/public/uploads/201707/qf041827165184.jpg"></div>
            <div class="goods_name">卡号:<?php echo $code;?></div>
            <div class="goods_name"><?php echo $balance;?></div>
            <div class="goods_desc">碧桂园凤凰酒店</div>
        </a>
    </div>
<div style="position: fixed;  bottom: 0;  width: 100%;">
    <p style="padding: 1% 2%;color: #999;margin-bottom: 20px;" >温馨提示 :<br>尊敬的用户，凤凰礼卡现已正式开通线上支付功能，为了您的资金及账户安全，即日起，每次仅可绑定一张凤凰礼卡消费和支付。</p>
    <a class="ui_foot_btn"  href="<?php echo base_url("index.php/membervip/giftcards/unbind");?>">重新绑定储值卡</a>
</div>

</body>
</html>