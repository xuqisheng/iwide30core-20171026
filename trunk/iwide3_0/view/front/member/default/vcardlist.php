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
<script src="<?php echo base_url("public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/js/imgscroll.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/js/jquery.touchwipe.min.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/store.css");?>" rel="stylesheet">
<title>我的储值卡</title>
<style>
body,html{background:#f8f8f8}
.cards_list .item .ui_img_auto_cut {height:auto}
.cards_list .item .ui_img_auto_cut img{ position:static}
.ui_foot_btn{ margin:0; width:100%; border-radius:0}
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
       'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<div class="page" style="padding-bottom:15%">
    <div class="cards_list">
        <?php foreach($cards as $card) {?>
        <!-- <a href="<?php echo site_url("member/bindcard/qrcode?id=".$card->gc_id);?>" class="item"> -->
            <div class="ui_img_auto_cut"><img src="<?php echo $card->logo_url;?>"></div>
            <div class="goods_name">卡号:<?php echo $card->code;?></div>
            <div class="goods_name"><?php if($card->status==0) {echo "未激活";} else {echo "余额:".$card->balance;}?></div>
            <div class="goods_name"><?php echo $card->title;?></div>
            <div class="goods_desc"><?php echo $card->brand_name?></div>
        <!-- </a> -->
        <?php } ?>
    </div>
<div style="position: fixed;  bottom: 0;  width: 100%;">
    <a class="ui_foot_btn"  href="<?php echo site_url("member/bindcard/bind");?>">绑定储值卡</a>
</div>
</body>
</html>