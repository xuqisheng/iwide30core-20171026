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
<div class="group_head center color_000">
	<div class="img"><div class="squareimg"><img src="<?php if(isset($imgs)&&!empty($imgs)){echo $imgs;}?>" /></div></div>
    <div>
    	<p><em class="iconfont">&#xE600;</em><?php if(isset($name)&&!empty($name)){echo $name;}?></p>
        <p><em>&#xE60f;</em><?php if(isset($hotel_name)&&!empty($hotel_name)){echo $hotel_name;}?></p>
        <p><em>&#xE607;</em><?php if(isset($qrcode_id)&&!empty($qrcode_id)){echo 'No.'.$qrcode_id;}?></p>
    </div>
</div>
<div class="list_style_3">
    <?php if($left!=0){ ?>
	<a class="bdradius" href="add_club">
    	<div><em class="iconfont" style="background:#aa89bd">&#xE606;</em>新增社群客</div>
        <tt>可新增<?php echo $left;?>个</tt>
    </a>
    <?php }else{ ?>
	<div class="bdradius disable">
    	<div><em class="iconfont" style="background:#aa89bd">&#xE606;</em>新增社群客</div>
        <tt>已满</tt>
    </div>
    <?php }?>
	<a class="bdradius" href="club_list">
    	<div><em class="iconfont" style="background:#00a0e9">&#xE609;</em>社群客列表</div>
        <tt><?php echo $amount?$amount:'0';?>个</tt>
    </a>
	<a class="bdradius" href="income_list">
    	<div><em class="iconfont" style="background:#ea68a2">&#xE60d;</em>收益记录</div>
    </a>
    <?php if($inter_id !='a487173166' || $inter_id !='a472731996'){ ?>
        <a class="bdradius" href="ranking">
            <div><em class="iconfont" style="background:#fe9402">&#xE607;</em>社群客琅琊榜</div>
        </a>
    <?php }?>
	<a class="bdradius" href="qa_help">
    	<div><em class="iconfont" style="background:#eb6100">&#xE60f;</em>帮助</div>
    </a>
</div>
</body>
</html>
