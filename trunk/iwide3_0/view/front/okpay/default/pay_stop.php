<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>快乐付</title>
    <?php echo referurl('css','weui.css',1,$media_path) ?>
	<?php echo referurl('css','okpay.css',1,$media_path) ?>
	<style>
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
        		/* .weui_icon_msg:before{
        			font-size: 70px;
        		} */
        	</style>
        	<img src="<?php echo $hotel['intro_img']; ?>"  class="logo"/>
        	<br />
        	<span style="font-size: 18pt; color:#000; line-height: 0.2em;" ><?php echo $hotel['name']; ?></span>
        	<br/>
        	<span style="font-size: 15pt; color:#000; line-height: 0.2em;" ><?php echo $pay_type_desc; ?></span>
        </h1>
    </div>
    <div class="bd spacing" style="padding:0px;">
    	<style>
    		.emwith{
    			width: 8em;
    		}
    		.txt-color{
    			color:#e4e4e4;
    		}
    		.txt-line-height{
    			line-height: 30px;
    		}
    		.txt-align{
    			vertical-align:middle;
    			margin:5px auto;
    			height:110px;
    			padding-top: 50px;
    		}
    	</style>

    	<div class="weui_panel txt-align" >
        	<h3 class="page_title logo_line_height" style="font-size:20pt; line-height:25pt;">
               二维码已失效<br/>
               无法进行支付
               </h3>
            </div>
        </div>
        
        
        <div class="weui_cells_title">
        	&nbsp;&nbsp;
        </div>
        
	    <div class="weui_btn_area" style="margin-bottom: 40px;">
	        <a class="weui_btn weui_btn_default" href="<?php echo site_url('okpay/okpay/pay_record') ?>" id="showTooltips2">查看支付历史</a>
	    </div>
    </div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>
    <div class="weui_btn_area">&nbsp;&nbsp;</div>

    <?php echo referurl('js','zepto.min.js',1,$media_path) ?>
    <script type="text/javascript">
    	
	
    </script>
    <?php echo referurl('js','hide_menu.js',1,$media_path) ?>
</body>
</html>