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
<title>地址编辑</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:5em;}
-->
</style>
<body>
<form action="<?php echo base_url("index.php/member/center/saveaddress")."?memid=".$memid;?>" method="post">
<div class="ui_normal_list ui_border">
	<div class="item">
    	<tt>收货人</tt>
    	<input name="consignee" type="text" placeholder="姓名" value="<?php if(isset($address)) echo $address->consignee;?>">
    </div>
	<div class="item">
    	<tt>手机号码</tt>
    	<input name="telephone" type="tel" placeholder="11位手机号" value="<?php if(isset($address)) echo $address->telephone;?>">
    </div>
	<div class="item">
    	<tt>详细地址</tt>
    	<input name="address" type="text" placeholder="街道门牌信息" value="<?php if(isset($address)) echo $address->address;?>">
    </div>
</div>
<input name="address_id" type="hidden" value="<?php if(isset($address)) echo $address->address_id;?>" />
<input class="ui_foot_btn disable" type="submit" value="保存">
</form>
</body>
</html>