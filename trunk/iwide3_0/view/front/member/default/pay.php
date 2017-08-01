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
<script src="<?php echo base_url("public/member/public/js/addcount.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.touchwipe.min.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/store.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/addcount.css");?>" rel="stylesheet">
<title>支付</title>
</head>
<style>
body,html{background:#f8f8f8}
.item .ui_price,.item .num,.total_num,.total_price{ float:right}
.item .num:before{content:"x "}
.total_price{font-size:1rem; padding:4% 0; margin-bottom:4%;}
.ui_gray{color:#999; font-size:0.55rem}
.total_price:before{content:"总计 ￥"; font-family:Arial; font-size:0.8rem;}
.selecthotel{width:12em; font-size:0.5rem; text-align:right; direction: rtl;}
</style>
<body>
<form id="aform" action="<?php echo $request_url;?>" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
        <div class="ui_price"><?php echo $order->amount;?></div>
        <div style="font-size:0.8rem;"><?php echo $product->title;?></div>
        <div class="num ui_gray"><?php echo $order->num;?></div>
        <div class="ui_gray"><?php echo $product->sub_title;?></div>
    </div>
</div>
<div style="padding:3% 4%">
    <div class="total_num ui_gray">共<?php echo $order->num;?>张</div>
    <div class="orid ui_gray">订单号:<?php echo $order->order_number;?></div>
    <div class="total_price"><?php echo $order->amount;?></div>
</div>
        <input type="hidden" name="openid" value="<?php echo $iwide_openid;?>" />
        <input type="hidden" name="out_trade_no" value="<?php echo $order->order_number;?>" />
        <input type="hidden" name="body" value="<?php echo $product->title;?>" />
        <input type="hidden" name="total_fee" value="<?php echo $order->amount;?>" />
        <input type="hidden" name="notify_url" value="<?php echo $notify_url;?>" />
        <input type="hidden" name="success_url" value="<?php echo $success_url;?>" />
        <input type="hidden" name="fail_url" value="<?php echo $fail_url;?>" />
        <input type="hidden" name="<?php echo $token_name;?>" value="<?php echo $token_value;?>" />
        <input type="hidden" name="type" value="card" />
        <input id="sub" class="ui_foot_btn" type="submit" value="马上支付">
</form>
</html>
