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
<script src="<?php echo base_url("public/member/public/js/viewport.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.touchwipe.min.js");?>"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/cardlist.css");?>" rel="stylesheet">
<title>我的卡券</title>
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
    <ul class="votelist">
        <?php if(count($cards)==0) {?>
            <div class="ui_none middle"  style=" position:fixed;">
                <div>您还没有卡券~<span class=" ui_color" onClick="history.back(-1);">点此返回</span></div>
            </div>
        <?php } else { foreach($cards as $card) {?>
    	<li>
	        <a href="<?php  if(isset($card->href)) echo $card->href; else echo  base_url("index.php/member/crecord/carddetail?gc_id=".$card->gc_id);?>" class="ui_vote">
            	<p class="bordertop_img"></p>
                <div class="vote_con">
                	
                    <!--p class="limit"><img src="<?php echo $card->logo_url;?>"></p-->
                    <?php if(isset($card->coupon_type) && ($card->coupon_type =='number') && isset($card->reduce_cost) && !empty($card->reduce_cost)){ ?>

                        <p class="moneys"><?php echo ($card->reduce_cost);?>张</p>

                    <?php }elseif(isset($card->coupon_type) && ($card->coupon_type =='discount') && isset($card->reduce_cost) && !empty($card->reduce_cost)){ ?>

                        <p class="moneys"><?php echo ($card->reduce_cost)*10;?>折</p>

                    <?php }else if(isset($card->reduce_cost) && !empty($card->reduce_cost)) {?>
                    <p class="moneys"><?php echo intval($card->reduce_cost);?>元</p>
                    <?php } ?>
                    <!--p class="status"><?php if($card->status==0) echo "未激活";?></p-->
                    <p class="votename" style="font-size:0.8rem;"><?php echo $card->title;?></p>
                    <p class="votename" style=" opacity:0.6 ;padding-top:2%;">券码:<?php echo $card->code;?></p>
                    <!--p class="addto">添加至卡包</p>
                    <p class="isadd">已添加至卡包</p-->
                    <p class="val_date" style="font-size:0.55rem; color:#888; padding-top:2%;"><?php echo $card->brand_name?> - <?php echo date('Y年m月d日',$card->date_info_end_timestamp);?></p>
                    <p><?php if($card->status==7) echo '微信卡券';?></p>
                </div>         
            	<p class="borderbtom_img"></p>  
            </a>
        </li>
	        <?php } ?>
        <?php } ?>
    </ul>
</body>
</html>