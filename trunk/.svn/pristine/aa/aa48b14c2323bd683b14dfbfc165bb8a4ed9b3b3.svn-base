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
<title>领取优惠券</title>
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
    <img src="<?php echo $card_info['logo_url'];?>"/>
    <div class="p_ab"></div>
</div>
<?php if($card_info){ ?>
<div class="content">
    <div class="con_name">
        <div class="na_img img_auto_cut"><img src="<?php echo $public['logo'];?>"></div>
        <div class="na_text">
            <p class="na_title">Hi~我是<?php echo $public['name'];?></p>
            <p class="con_text">送你一张券！</p>
            <div class="aworr"><img src="<?php echo base_url("public/member/public/images/aworr.png");?>"/></div>
        </div>
        <div style="clear:both"></div>
    </div>

        <div class="coupon">
            <div class="cou_con">
                <p class="money">
                    <?php if($card_info['card_type']==1){ ?>
                        <?php echo $card_info['reduce_cost']; ?>元
                    <?php }elseif($card_info['card_type']==2){ ?>
                        <?php echo $card_info['discount']; ?>折
                    <?php }elseif($card_info['card_type']==3){ ?>
                        兑换券
                    <?php }elseif($card_info['card_type']==4){ ?>
                        <?php echo $card_info['money']; ?>元
                    <?php }else{ ?>

                    <?php }?>
                </p>
                <p class="cou_titl"><?php echo $card_info['title']; ?></p>
                <p class="cou_text"><?php echo $card_info['brand_name']; ?></p>
                <p class="cou_time">领取时间:<br/><?php echo date('Y-m-d',$card_info['time_start']) ?>至<?php echo date('Y-m-d',$card_info['time_end']) ?></p>
                <p>1张</p>
                <div class="bg_1"></div>
            </div>
        </div>

</div>
<div class="flooter">
        <?php if(isset($card_info['frequency']) && $card_info['frequency']>$gain_count ){ ?>
            <a class="fl_btn gain_card" href="javascript:getcard();">立即领取</a>
            <a class="fl_btn look_card" style="display:none;" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php }else{ ?>
            <a class="fl_btn gain_card" style="display:none;" href="javascript:getcard();">立即领取</a>
            <a class="fl_btn look_card" href="<?php echo site_url("membervip/card");?>">立即查看</a>
        <?php } ?>
</div>
<?php } ?>

<div class="page_loading" style="display:none"><div>请稍候...</div></div>
<script>
var ajaxload = false;
    function getcard(){
		if(ajaxload)return;
		$('.page_loading').show();
        $.post("<?php echo base_url('index.php/membervip/card/addcard');?>",
            { "card_rule_id":" <?php echo $card_info['card_rule_id']; ?>"},
        function(Result){
			$('.page_loading').hide();
			ajaxload=false;
            if(Result['err']>0){
                alert(Result['msg']);
            }else{
                alert('领取成功');
                $('.gain_card').hide();
                $('.look_card').show();
            }
        }, "json");
    }
</script>
</body>
</html>
