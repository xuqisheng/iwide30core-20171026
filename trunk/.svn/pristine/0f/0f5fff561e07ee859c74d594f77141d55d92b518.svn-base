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
<title>充值</title>
</head>
<style>
body,html{background:#f8f8f8;}
.user_img{margin:5% auto;}
</style>
<body>
<div style="text-align:center">
    <div class="user_img"><a href="<?php if(isset($member)) {echo base_url("index.php/member/center/userinfo")."?id=".$member->mem_id;} else {echo base_url("index.php/member/center/userinfo");}?>"><img src="<?php echo base_url("public/uploads/userimg01.jpg");?>"></a></div>	
    <div><?php echo $info->name;?></div>
    <div>111111111111111</div>
    <div class="ui_price"><?php echo $info->balance;?></div>
</div>
<div class="ui_normal_list ui_border">
    <div class="item">
    	<tt>充值金额</tt>
        <span>
        <select name="fixed_balance">
            <option value="3000">3000元</option>
            <option value="5000">5000元</option>
            <option value="10000">10000元</option>
            <option value="20000">20000元</option>
            <option value="30000">30000元</option>
        </select>
        </span>
    </div>
</div>
    <input class="ui_foot_btn" type="submit" value="保存">
    <input type="hidden" name="ga_id" value="<?php echo $info->ga_id;?>" />
</body>
</html>