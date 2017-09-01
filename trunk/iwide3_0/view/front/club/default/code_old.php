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
<title>社群客列表</title>
<script src="<?php echo base_url('public/club/scripts/jquery.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/ui_control.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/global.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/club/styles/group.css');?>">
</head>
<body>

<script src="<?php echo base_url('public/club/scripts/alert.js');?>"></script>
<script src="<?php echo base_url('public/club/scripts/canvas.js');?>"></script>

<?php
if(isset($code)&&$code==0){ ?>
<div style="text-align: center"><?php echo $info;?></div>
<?php }else{ ?>
    <!--- 二维码和头像不能跨域 否则不能生成海报  --->
    <div>
        <img id="haibao" src="" width="100%" height="100%">
    </div>
<?php }?>

<script>
$(function(){
//	var _w = $(window).width();
//	var rate = 640/1020;
//	var _h = _w/rate;
//	$('.haibao').height(_h);
//	pageloading('生成海报中',0.8);
//	var fail = window.setTimeout(function(){
//		removeload();
//		$.MsgBox.Alert('生成成功!');
//	},10000);
//	var clone=$('.haibao').clone();
//	var _w = $('.haibao').width()*2;
//	var _h = $('.haibao').height()*2;
//	$('body').append(clone).css('overflow','hidden');
//	$('meta').attr('content','width=320,user-scalable=0,initial-scale=1');
//	clone.width(_w);
//	clone.height(_h);
//	clone.css('font-size','2rem');
	/*生成海报代码*/
	window.setTimeout(function(){ //模拟延迟...
//		html2canvas(clone.get(0), {
//			allowTaint: true,
//			taintTest: false,
//			onrendered: function(canvas) {
//				removeload();
//				canvas.id = "mycanvas";
//				var dataUrl = canvas.toDataURL();
//                console.log(dataUrl);
//				var newImg = document.createElement("img");
//				newImg.src =  dataUrl;
//				$('.haibao').html(newImg);
//				clone.remove();
//				$('body').removeAttr('style');
//				$.MsgBox.Alert('生成成功,长按保存图片哦!~');
//				window.clearTimeout(fail);
//			}
//		});
<!--            $(".haibao").css({"background-image":"url(--><?php //echo $background_url;?><!--) ","background-size":"100%"})-->
            $("#haibao").attr("src","<?php if(isset($background_url)&&!empty($background_url)){ echo $background_url;} ?>");
	},1000);

})
</script>
</body>
</html>
