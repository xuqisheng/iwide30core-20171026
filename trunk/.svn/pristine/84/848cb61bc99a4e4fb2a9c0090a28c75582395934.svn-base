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
<meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">

<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<?php include 'wxheader.php' ?>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<title>二维码</title>
<script type="text/javascript">  
    $(function() {  
        setInterval("GetTime()", 1000);  
    });  
  
    function GetTime() {
        var mon, day, now, hour, $min, ampm, time, str, tz, end, beg, sec;  
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
        $min = now.getMinutes();  
        sec = now.getSeconds();  
        if (hour < 10) {  
            hour = "0" + hour;  
        }  
        if ($min < 10) {  
            $min = "0" + $min;  
        }  
        if (sec < 10) {  
            sec = "0" + sec;  
        }  
        $("#Timer").html("<nobr>" + hour + ":" + $min + ":" + sec + "</nobr>");
    }  
</script>
</head>
<style>
*{ font-size:20px;}
body{background:none}
html{width:100%;height:100%;background:rgba(0,0,0,0.6);}
.fixe{ width:80%; margin:auto; position:relative; padding-top:30%;}
.fixe .er_log{display:block;position:relative;margin:auto;text-align:center; background:#fff; color:#000; text-align:center; border-radius:10px 10px 0 0; padding-top:12%; padding-bottom:10%; font-size:20px;}
.back{display:block;position:absolute; top:10%; right:5%; color:#999;}
.fixe .er_log h1{padding-top:5%;}
.erwen_b{margin:auto; font-size:0;}
</style>
<body>

<div class="fixe">
	<div class="er_log">
    	<div class="back" onClick="history.back(-1)">&times;</div>
    	<div>卡号： <?php if(isset($centerinfo['inter_id']) && $centerinfo['inter_id']=='a421641095' && isset($centerinfo['membership_number'])):echo $centerinfo['membership_number'];else: echo isset($centerinfo['id_card_no'])?$centerinfo['id_card_no']:'无';endif;?></div>
        <div id="Timer">00:00:00</div>
    </div>
    <div class="erwen_b">
        <img src="<?php echo base_url("public/member/public/images/fen_bg_1.png");?>">
        <?php if(isset($centerinfo['inter_id']) &&  $centerinfo['inter_id']=='a421641095'):?>
            <img src="<?php echo isset($centerinfo['membership_number'])?base_url("index.php/membervip/center/qrcodecon?data=MEM").$centerinfo['membership_number']:0;?>">
        <?php else:?>
            <img src="<?php echo isset($centerinfo['id_card_no'])?base_url("index.php/membervip/center/qrcodecon?data=").$centerinfo['id_card_no']:0;?>">
        <?php endif;?>
    </div>
</div>
</body>
</html>