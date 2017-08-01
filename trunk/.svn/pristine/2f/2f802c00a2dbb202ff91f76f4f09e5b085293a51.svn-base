<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>

<title>激活社群帮</title>
</head>
<body>
<div class="statustep webkitbox center">
	<div>
    	<span class="bg_555 h24">1</span>
        <p class="h22">填写信息</p>
    </div>
    <div>
    	<span class="bg_main h24">2</span><hr>
        <p class="h22 color_main">激活</p>
    </div>
    <div>
    	<span class="bg_555 h24">3</span><hr>
        <p class="h22">享受专属价</p>
    </div>
</div>
<div class="center pad15 color_main">
<?php if(isset($code)&&$code!=0){?>
	<em class="iconfont color_key" style="font-size:60px">&#xE60b;</em>
	<div class="color_key">绑定失败, <?php echo $msg;?></div>
<?php }elseif(isset($code)&&$code==0){ ?>
	<em class="iconfont" style="font-size:60px">&#xE60b;</em>
	<div>绑定成功, 当前已加入<?php if(!empty($club_info['club_name'])){ echo $club_info['club_name']; } ?></div>
<?php } ?>
</div>

<div class="foot_btn martop">
	<a href="<?php if($is_multy==2){echo site_url('hotel/hotel/search');}else{echo site_url("hotel/hotel/index?id={$inter_id}");}?>" class="btn_main h28 submitbtn">马上订房立享专属折扣</a>
</div>


</body>
</html>
