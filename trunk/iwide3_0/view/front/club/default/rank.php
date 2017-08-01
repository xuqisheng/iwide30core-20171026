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
<title>社群客琅琊榜</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
</head>
<style>
.img{margin-right:5px; vertical-align:middle}
._tmp{max-width:60%;}
</style>
<body>
<div class="tabmenus webkitbox center bg_fff">
	<a href="ranking?type=day" class="<?php if(!isset($type)||$type=='day'){echo 'bg_main';}else{ echo 'bg_E4E4E4';}?>">日计</a>
	<a href="ranking?type=month" class="<?php if(isset($type)&&$type=='month'){echo 'bg_main';}else{ echo 'bg_E4E4E4';}?>">月计</a>
	<a href="ranking?type=all" class="<?php if(isset($type)&&$type=='all'){echo 'bg_main';}else{ echo 'bg_E4E4E4';}?>">总计</a>
</div>
<?php if($rank){  ?>
<div class="list_style_2" style="padding-top:36px">
    <?php if($my_rank){ ?>
	<div class="justify webkitbox bg_main">
        <div class="img"><div class="squareimg"><img src="<?php echo $my_rank['headimgurl']?>" /></div></div>
        <div class="_tmp">
            <div class="txtclip"><?php echo $my_rank['name']?></div>
            <div>
                <?php
                    if(!isset($type)||$type=='day'){echo '当日';}
                    if(isset($type)&&$type=='month'){echo '当月';}
                    if(isset($type)&&$type=='all'){echo '总';}?><?php echo '收益：￥'.$my_rank['grade'];
                ?>
            </div>
            <div>
                <?php
                if(!isset($type)||$type=='day'){echo '当日';}
                if(isset($type)&&$type=='month'){echo '当月';}
                if(isset($type)&&$type=='all'){echo '总';}?><?php echo '间夜：'.$my_rank['total'];
                ?>
            </div>
            <div><?php echo $my_rank['hotel_name']?></div>
        </div>
    	<div class="rank">第<tt><?php echo $my_rank['rank']+1; ?></tt>名</div>
    </div>
    <?php }?>
    <?php foreach($rank as $key=>$arr){ if($key<15){ ?>
	<div class="justify webkitbox">
        <div class="img"><div class="squareimg"><img src="<?php echo $arr['headimgurl'];?>" /></div></div>
        <div class="_tmp">
            <div class="txtclip"><?php echo $arr['name']?></div>
            <div>
                <?php
                    if(!isset($type)||$type=='day'){echo '当日';}
			        if(isset($type)&&$type=='month'){echo '当月';}
			        if(isset($type)&&$type=='all'){echo '总';}?><?php echo '收益：￥'.$arr['grade'];
                ?>
            </div>
            <div>
                <?php
                if(!isset($type)||$type=='day'){echo '当日';}
                if(isset($type)&&$type=='month'){echo '当月';}
                if(isset($type)&&$type=='all'){echo '总';}?><?php echo '间夜：'.$arr['total'];
                ?>
            </div>
            <div><?php echo $arr['hotel_name']?></div>
        </div>
    	<div class="rank">第<tt><?php echo $key+1; ?></tt>名</div>
    </div>
    <?php }} ?>
</div>

<?php }else{ ?>
<div class="ui_none"><div>暂无排名</div></div> 
<?php }?>
</body>
</html>