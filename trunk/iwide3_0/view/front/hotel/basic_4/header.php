<?php 
header('Cache-Control: public');
?><!doctype html>
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
<meta name="viewport" content="user-scalable=no,width=320.1">
<!-- 全局控制 -->
<?php echo referurl('css','global.css',3,$media_path) ?>
<?php echo referurl('css','ui.css',3,$media_path) ?>
<?php echo referurl('css','ui_ico.css',2,$media_path) ?>
<?php echo referurl('css','ui_style.css',2,$media_path) ?>
<?php echo referurl('css','ui_pull.css',2,$media_path) ?>
<?php echo referurl('js','jquery.js',3,$media_path) ?>
<?php echo referurl('js','ui_control.js',3,$media_path) ?>
<?php echo referurl('js','alert.js',3,$media_path) ?>
<?php echo referurl('js','lazyload.js',3,$media_path) ?>
<?php include 'wxheader.php'?>
<?php echo $statistics_js;?>
<!-- end -->
<title><?php echo $pagetitle;?></title>
<style>
	body,html { font-size:5px !important;}

#ncalendar thead td:first-child, #ncalendar thead td:last-child, #ncalendar tbody td.span ,
.ui_tab .cur, .near p,.hotel_list .ui_price,.sure p,.vote_spread .ui_price,.ui_vote,.apply_list .level,.apply_list .point,
.ui_color,.room_list .ui_price,.pre_pay p:last-child,.form_list .pay_way .ui_price,.order_detail .ui_price
{color:#0072bf !important}
#ncalendar tbody td.current, #ncalendar tbody td.begin, #ncalendar tbody td.end ,
.ui_btn.isable,.vote_pull .ischeck .ui_ico,.realtime_status li.iscured em,.photo_class .cur,.pre_pay p:first-child,.now_pay p,.footbtn,.form_list .ischeck .ui_ico,.submit_btn,.chooseroom_pull .sure_btn,.vote_pull .ischeck .ui_ico
{background-color:#0072bf !important;}
.pre_pay,.now_pay,.no_pay,.guest_pull li,.form_list .ischeck .ui_ico,.footfixed .submit_btn,.vote_pull .ischeck .ui_ico,.realtime_status .iscured hr,.bdtom,.realtime_status .iscur .circle em
{ border-color:#0072bf;}
</style>
</head>
<body>
<div class="page_loading"><p class="isload">正在加载</p></div>
<script>
$(function(){
$("img.lazy").lazyload();  //惰性加载
});
</script>