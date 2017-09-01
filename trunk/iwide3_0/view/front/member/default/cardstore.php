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
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/imgscroll.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.touchwipe.min.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/store.css");?>" rel="stylesheet">
<title>卡券中心</title>
<style>
body,html{background:#f8f8f8}
</style>
<body>
<div class="page">
    <div class="cards_list">
        <?php foreach($cards as $card) {?>
        <a href="<?php echo base_url("index.php/member/cardstore/detail?ci_id=".$card->ci_id."&saler=".$saler);?>" class="item">
            <div class="ui_img_auto_cut">
            	<img src="<?php echo $card->logo_url;?>">
                <div class="number">8888888888</div>
            </div>
            <div class="ui_price"><?php echo $card->reduce_cost;?></div>
            <div class="goods_name"><?php echo $card->title;?></div>
            <div class="goods_desc"><?php echo $card->brand_name?></div>
        </a>
        <?php } ?>
    </div>
<div style="padding-top:15%;"> 
    <a class="ui_foot_btn" style="position: fixed;  bottom: 0;  width: 100%; border-radius:0; margin:0;"  href="<?php echo site_url("member/corder/orderlist");?>">我的订单</a>
</div>
</div>
</body>
</html>