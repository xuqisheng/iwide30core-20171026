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
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/store.css");?>" rel="stylesheet">
<title>我的订单</title>
<style>
body,html{background:#f8f8f8}
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
<div class="page">
    <div class="order_list">
        <?php foreach($orders as $order) {?>
        <?php
            if($order->paid==0) {
                $order_link = site_url('member/corder/pay?co_id='.$order->co_id);
            } else {
                $order_link = "";
            }
        ?>
        <a href="<?php echo $order_link;?>" class="item">
        	<div class="cardtype">
    			<em class="ui_ico ui_ico4"></em>
                <span><?php echo $order->title;?></span>
                <span>
                    <?php if($order->paid==1) {echo "已支付";} elseif($order->paid==0) {echo "未支付";}?>
                </span>
            </div>
            <div class="goods_name"><?php echo $order->title;?></div>
            <div class="ui_price"><?php echo $order->amount;?></div>
            <div class="orderid">订单号：<?php echo $order->order_number;?></div>
			<div class="time">下单时间：<?php echo $order->create_time;?></div>
			<div class="time">礼卡单价：<?php echo $order->unit_price;?></div>
			<div class="time">礼卡数量：<?php echo $order->num;?></div>
        </a>
        <?php } ?>
        <?php if(count($orders)==0) echo "暂无订单";?>
    </div>
</body>
<script>
$(function(){
	img_auto_cut();
	$('.ispay').click(function(){
		return false;
	})
});
</script>
</html>