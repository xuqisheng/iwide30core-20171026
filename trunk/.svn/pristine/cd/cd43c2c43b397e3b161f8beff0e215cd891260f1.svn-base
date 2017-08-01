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
<title>社群客</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<body>

<div style="font-size:0;"><img src="<?php echo base_url('public/club/images/bg_02.jpg');?>"></div>
<?php if(!isset($st) || $st!=1){ ?>
<div class="statustep webkitbox center">
	<div>
    	<span class="bg_555 h24">1</span>
        <p class="h22">添加</p>
    </div>
    <div>
    	<span class="bg_main h24">2</span><hr>
        <p class="h22 color_main">审核</p>
    </div>
    <div>
    	<span class="bg_555 h24">3</span><hr>
        <p class="h22">生成圣火令</p>
    </div>
</div>
<?php }?>
<div class="color_main center" style="margin:15px 0"><p class="h32">提交成功!</p><div><?php if(!isset($st) || $st!=1){ ?>请等待管理员审核<?php }?></div></div>
<div class="pad12 center">
	<a class="btn_main submitbtn" href="<?php echo site_url('club/club/club_list?id=').$this->inter_id;?>">查看全部</a>
</div>

<div class="center pad15">
    <?php if(isset($left)&&$left!=0){?>
	<p>您还可以添加<span class="color_main"><?php echo $left;?></span>个社群客户<br><a href="<?php echo site_url('club/club/add_club?id=').$this->inter_id;?>" class="color_main">点此继续添加</a></p>
    <?php }else{ ?>
    <p>您已添加完<span class="color_main"><?php if(isset($limited_amount))echo $limited_amount;?></span>个社群客户<br>如需继续添加，请联系管理员</p>
    <?php }?>
</div>

</body>
</html>
