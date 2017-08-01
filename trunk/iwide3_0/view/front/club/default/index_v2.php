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
<title>社群帮</title>
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
    	<div><em class="iconfont" style="background:#aa89bd">&#xE606;</em>新建帮派</div>
        <tt>可新增<?php echo $left;?>个</tt>
    </a>
    <?php }else{ ?>
	<div class="bdradius disable">
    	<div><em class="iconfont" style="background:#aa89bd">&#xE606;</em>新建帮派</div>
        <tt>已添加</tt>
    </div>
    <?php }?>
	<a class="bdradius" href="club_list">
    	<div><em class="iconfont" style="background:#00a0e9">&#xE609;</em>我的帮派</div>
        <tt><?php echo $amount?$amount:'0';?>个</tt>
    </a>

</div>
<div class="pad3 h22 color_888 martop">
	<p>活动细则：</p>
	<p>1、活动时间2016.12.05-2016.12.20</p>
	<p>2、活动期间，按照成为帮主并发展10位帮成员的时间先后，取前1000名给予终身铂金会员礼遇。（说明：与注册成为帮主时间先后无关。）如未满足条件，帮主亦可享受住房金卡折扣，有效期为一年。</p>
	<p>3、帮主通过分享“帮主令”，最多可邀50名微信(朋友圈)好友享住房金卡折扣。有效期一年。</p>
	<p>4、帮主和帮成员享用住房折扣之前，须在帮主令中长按二维码，激活信息，方可享用折扣。</p>
    <p>5、终身铂金卡的使用说明：</p>
    <p>1）非银座旅行家会员直接给予铂金会员资格，届时会有短信发送提醒。已经是银座旅行家的会员给予升级为终身铂金会员资格。（注：无实体卡）</p>
    <p>2）铂金会员可在银座酒店ＡＰＰ＼银座旅行家微信＼银座酒店官网使用。金卡折扣用户只能在微信订房使用。</p>
    <p>6、符合领取资格后，一个工作日后可在会员中心查看会员级别，即可判别中奖与否。</p>
</div>
</body>
</html>
