<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="<?php echo base_url("public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/js/imgscroll.js");?>"></script>
<script src="<?php echo base_url("public/js/addcount.js");?>"></script>
<script src="<?php echo base_url("public/js/jquery.touchwipe.min.js");?>"></script>
<link href="<?php echo base_url("public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/store.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/css/addcount.css");?>" rel="stylesheet">
<title>二维码</title>
<script type="text/javascript">  
    $(function() {  
        setInterval("GetTime()", 1000);  
    });  
  
    function GetTime() {  
        var mon, day, now, hour, min, ampm, time, str, tz, end, beg, sec;  
        /*  
        mon = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",  
                "Sep", "Oct", "Nov", "Dec");  
        */  
        mon = new Array("一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月",  
                "九月", "十月", "十一月", "十二月");  
        /*  
        day = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");  
        */  
        day = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");  
        now = new Date();  
        hour = now.getHours();  
        min = now.getMinutes();  
        sec = now.getSeconds();  
        if (hour < 10) {  
            hour = "0" + hour;  
        }  
        if (min < 10) {  
            min = "0" + min;  
        }  
        if (sec < 10) {  
            sec = "0" + sec;  
        }  
        $("#Timer").html("<nobr>" + hour + ":" + min + ":" + sec + "</nobr>");  
  
    } 
 //+ day[now.getDay()] + ", " + mon[now.getMonth()] + " "  + now.getDate() + ", " + now.getFullYear() + " " 
</script>
</head>
<body>
<div class="headers"> 
  <div class="headerslide">
  	  <a class="slideson imgshow relative" href="#">
     	 <img src="<?php echo $detail->logo_url;?>">
      </a>
  </div><!-- headerslide---end--->
</div><!-- headers---end--->
<div class="detail">
	<div class="goods_name"><?php echo $detail->title;?></div>
	<div class="goods_dec"><?php echo $detail->sub_title;?>
	    <div style="float:right">
	        <a href="<?php echo site_url("bgyhotel/bindcard/resetpwd?id=".$card->gc_id);?>">修改密码</a>
	        <a href="<?php echo site_url("bgyhotel/bindcard/unbind?id=".$card->gc_id);?>">解除绑定</a>
	    </div>
	</div>
    <div class=" ui_border erweima">
    	<div>消费二维码</div>
    	<div>卡号： <?php echo $card->code;?></div>
         <div id="Timer"></div>
        <div class="saoma"><img src="<?php echo base_url("index.php/member/qrcodecon?data=").$card->code;?>"></div>
    </div>
    <div class="how_use">
    	<?php echo $detail->description;?>
    </div>
</div>
<div class="pull pull_erweima" style="display:none">
    <div class="erweima">
    	<div>消费二维码</div>
    	<div>请向酒店服务员出示此二维码进行消费</div>
    </div>
</div>
</body>

</html>