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
<title>产生的间夜总数</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
    <script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
    <script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
    <script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
</head>
<style>
.table thead th:nth-child(1),.table td:nth-child(1),
.table thead th:nth-child(3),.table td:nth-child(3),
.table thead th:nth-child(4),.table td:nth-child(4){width:13%}
.table thead th:nth-child(2),.table td:nth-child(2){width:20%}
.table thead th:nth-child(5),.table td:nth-child(5){width:40%}
</style>
<script>
$(function(){
	$('table thead tr').width($('table').width());
	$('table thead tr').css('top',$('.topfixed').outerHeight());
	$('table').css("margin-top",$('table thead tr').height()+'px');
	var _tmph = $(window).height();
	var _tableh= $('.topfixed').outerHeight()+ $('.bottomfixed').outerHeight();
	$('.table').css('min-height',_tmph -_tableh-8);
});
</script>
<body style="background:#eee;">
<div head class="bg_fff pad3 topfixed" style="border-bottom:7px solid #eee">
    <em class="ico iconfont bg_main">&#xe602;</em><?php if(isset($club_info['club_name'])){echo $club_info['club_name'];}?>
</div>
<div body style="padding:44px 8px">
    <div class="center table bg_fff bdradius overflow">
        <table class="_w h20">
            <thead>
                <tr class="bg_main h24 _w webkitbox">
                    <th><em class="iconfont">&#xe606;</em>姓名</th>
                    <th><em class="iconfont">&#xe603;</em>入离日期</th>
                    <th>间夜</th>
                    <th><em class="iconfont">&#xe602;</em>金额</th>
                    <th><em class="iconfont">&#xe607;</em>酒店-房型</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($orders as $arr){
            ?>
                <tr>
                    <td class="clip_str"><span><?php echo $arr['name'];?></span>**</td>
                    <td><p><?php echo $arr['startdate'];?></p><p><?php echo $arr['enddate'];?></p></td>
                    <td><?php echo $arr['night'];?></td>
                    <td><?php echo $arr['iprice'];?></td>
                    <td><p><?php echo $hotels[$arr['hotel_id']]['name'];?></p><p><?php echo $arr['roomname'];?></p></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div foot class=" center bottomfixed">
    <div class="webkitbox">
        <a href="?type=D&cid=<?php echo $_GET['cid'];?>" class="pad3 bg_555">今天<tt class="color_main">(<?php echo $count['day'];?>)</tt></a>
        <a href="?type=W&cid=<?php echo $_GET['cid'];?>" class="pad3 bg_555 bd_lr">本周<tt class="color_main">(<?php echo $count['week'];?>)</tt></a>
        <a href="?type=M&cid=<?php echo $_GET['cid'];?>" class="pad3 bg_555">本月<tt class="color_main">(<?php echo $count['month'];?>)</tt></a>
        <a href="?type=A&cid=<?php echo $_GET['cid'];?>" class="pad3 bg_555 bd_left">总间夜<tt class="color_main">(<?php echo $count['all'];?>)</tt></a>
    </div>
</div>


<div class="ui_none" style="display:none"><div>没有收益记录~</div></div> 

</body>
</html>
