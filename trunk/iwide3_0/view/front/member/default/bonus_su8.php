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
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/history.css");?>" rel="stylesheet">
<title>间夜点数</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
.ui_normal_list .item tt:first-child{ width:50%;}
.ui_normal_list .item tt:nth-child(2n) { text-align:right}
-->
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
<div class="ui_tab_btn">
	<div class="item cur">全部间夜点数</div>
	<div class="item">增加的间夜点数</div>
	<div class="item">消费的间夜点数</div>
</div>
<div class="ui_normal_list ui_border point">
    <?php foreach($bonus as $obj) {?>
	<div class="item">
    	<tt><?php echo $obj->note;?></tt>
    	<tt><?php echo $obj->bonus;?></tt>
    	<div><?php echo $obj->create_time;?></div>
    </div>
    <?php } ?>
</div>
<div class="ui_normal_list ui_border point" style="display:none;">
	<?php foreach($add_bonus as $obj) {?>
	<div class="item">
    	<tt><?php echo $obj->note;?></tt>
    	<tt><?php echo $obj->bonus;?></tt>
    	<div><?php echo $obj->create_time;?></div>
    </div>
    <?php } ?>
</div>
<div class="ui_normal_list ui_border point" style="display:none;">
	<?php foreach($reduce_bonus as $obj) {?>
	<div class="item">
    	<tt><?php echo $obj->note;?></tt>
    	<tt><?php echo $obj->bonus;?></tt>
    	<div><?php echo $obj->create_time;?></div>
    </div>
    <?php } ?>
</div>
<!--
<div style="padding-top:15%;">
	<a href="<?php echo base_url("index.php/wap/point/store")?>" class="ui_foot_fixed_btn">
    	<em class="ui_ico ui_ico3"></em>
        <div>积分商城</div>
    </a>
</div>
-->
</body>
<script>
$(function(){
	$('.ui_tab_btn .item').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		var _index=$(this).index();
		$('.ui_normal_list').eq(_index).show();
		$('.ui_normal_list').eq(_index).siblings('.ui_normal_list').hide();
	})
})
</script>
</html>
