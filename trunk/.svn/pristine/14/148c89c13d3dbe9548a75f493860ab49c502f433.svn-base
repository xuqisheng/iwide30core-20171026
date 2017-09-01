<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
   
	<?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>

	<style>
		.weui_cell_money{
			position:absolute; 
			right:5px; 
			top: 14px; 
			margin-right: 15px;
		}
		.weui_cell_money_yellow{
			color: #fe7a1f;
		}
		.weui_cell_money_black{
			color: #000000;
		}
		.logo{
			width:80px; 
			height:80px; 
			border-radius:50%; 
			overflow:hidden;
		}
		.logo_line_height{
			line-height: 1;
		}
	</style>
</head>
<body ontouchstart>
    <div class="hd">
        <h1 class="page_title logo_line_height">
        	<style>
        		.weui_icon_msg:before{
        			font-size: 70px;
        		}
        	</style>
        	<img src="<?php echo $hotel['intro_img']; ?>"  class="logo"/>
        	<br />
        	<span style="font-size: 18pt; color: #000; line-height: 0.2em;" ><?php echo $hotel['name']; ?></span>
        	<br />
        </h1>
        
    </div>
    <div class="bd spacing" style="padding:0px; text-align:center; color:#ccc; margin-top: 60px;">
         
            暂无支付记录
            
    </div>
    <div class="weui_btn_area" style="margin-bottom: 40px; margin-top: 120px;">
        <a class="weui_btn weui_btn_default" href="javascript:history.go(-1);" id="showTooltips">返回</a>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
	<?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>