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
<title>地址</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
-->
</style>
<body>
<div class="ui_btn_list ui_border">
    <?php foreach($addresslist as $address) {?>
        <a href="<?php echo base_url("index.php/member/center/editaddress")."?memid=".$memid."&&address_id=".$address->address_id;?>" class="item address">
	    	<tt><?php echo $address->consignee;?></tt>
	    	<tt><?php echo $address->telephone;?></tt>
	    	<div><?php echo $address->address;?></div>
	    </a>
    <?php } ?>
	<a href="<?php echo base_url("index.php/member/center/editaddress")."?memid=".$memid;?>" class="item">
    	<em class="ui_ico ui_ico7"></em>
    	<tt>添加</tt>
    </a>
</div>
</body>
</html>
