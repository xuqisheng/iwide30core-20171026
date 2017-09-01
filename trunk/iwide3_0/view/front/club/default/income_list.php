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
<title>收益记录</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<body>
<?php if(isset($list)){  ?>
<div class="income_list">
    <?php  foreach($list as $arr){
                if(($arr['info']+2678400)>$join_time){
    ?>
    <a class="color_555 whiteblock bdradius" href="club_orders?t=<?php echo $arr['info']?>">
        <div style="margin-bottom:8px;"><em class="iconfont bg_main">&#xE608;</em><?php echo $arr['name'];?></div>
        <div class="btn_main xs h26">查看明细</div>
        <div><em class="iconfont">&#xe602;</em>当月间夜：<?php echo $arr['count'];?></div>
    </a>
    <?php }}?>
</div>
<?php }else{ ?>

<div class="ui_none"><div>还没有收益哦</div></div>

<?php }?>
<!-----end---->
</body>
</html>
