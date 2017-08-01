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
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/mycss.css");?>" rel="stylesheet">
<title>领取卡券</title>
</head>
<style>
<!--
.ui_normal_list .item tt{ display:inline-block; width:6em;}
-->
</style>
<body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signpackage["appId"];?>',
    timestamp:<?php echo $signpackage["timestamp"];?>,
    nonceStr:'<?php echo $signpackage["nonceStr"];?>',
    signature:'<?php echo $signpackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu'
     ]
   });
   wx.ready(function () {
	   wx.hideOptionMenu();
   });
</script>
<div class="nav">
    <img src="<?php echo base_url("public/member/public/images/655696019181591823.jpg");?>"/>
    <div class="p_ab"></div>
</div>
<div class="content">
    <div class="con_name">
    	<div class="na_img img_auto_cut"><img src="<?php echo $public['logo'];?>"/></div>
        <div class="na_text">
        	<p class="na_title">Hi~我是<?php echo $public['name'];?></p>
            <p class="con_text">送你一张券！</p>
            <div class="aworr"><img src="<?php echo base_url("public/member/public/images/aworr.png");?>"/></div>
        </div>
        <div style="clear:both"></div>
    </div>
    <?php foreach($rule_card as $ci_id=>$cinfo) {?>
	    <div class="coupon">
	    	<div class="cou_con">
	        	<?php if(isset($card[$ci_id]->reduce_cost) && !empty($card[$ci_id]->reduce_cost)) {?>
	                <p class="money"><?php echo intval($card[$ci_id]->reduce_cost);?>元</p>
	            <?php } ?>
	        	<p class="cou_titl"><?php echo $card[$ci_id]->title;?></p>
	        	<p class="cou_text">仅限<?php echo $public['name'];?>使用</p>
	        	<p class="cou_time">有效期至<?php echo date('Y年m月d日',$card[$ci_id]->date_info_end_timestamp);?></p>
	        	<p><?php echo $cinfo['quantity']?>张</p>
	            <div class="bg_1"></div>
	        </div>
	    </div>
    <?php } ?>
</div>
<div class="flooter">
	<?php if(isset($yet)) {?>
        <a class="fl_btn" href="<?php echo site_url("member/crecord/cards");?>">立即查看</a>
    <?php } else { ?>
        <a class="fl_btn" href="javascript:getcard();">立即领取</a>
    <?php } ?>
</div>
<script>
<?php if(isset($message) && $message!='') echo 'showmessage();' ?>
function showmessage()
{
  alert( "<?php if(isset($message) && $message!='') echo $message;  ?>" );
}

function getcard()
{
	$.post("<?php echo site_url("member/pgetcard/addCard");?>", {rid:"<?php echo $rule_id;?>"},
      	   function(data){
	   	       data= eval("("+data+")");
      		   alert(data.errmsg);
      		   if(data.code==0) {
      		       $(".fl_btn").html("<a href=<?php echo site_url('member/crecord/cards');?>>立即查看</a>");
      		   }
       });
}
</script>
</body>
</html>
