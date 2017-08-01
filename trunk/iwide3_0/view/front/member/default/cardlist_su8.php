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
    <script src="<?php echo base_url("public/member/super8/js/viewport.js");?>"></script>
    <script src="<?php echo base_url("public/member/super8/js/jquery.js");?>"></script>
    <script src="<?php echo base_url("public/member/super8/js/ui_control.js");?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="<?php echo base_url("public/member/super8/js/jquery.touchwipe.min.js");?>"></script>
    <link href="<?php echo base_url("public/member/super8/css/global.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/super8/css/ui.css");?>" rel="stylesheet">
    <link href="<?php echo base_url("public/member/super8/css/coupons.css");?>" rel="stylesheet">
    <title>我的代金券</title>
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
<div class="vote_pull">
    <div class="notic">
        <div class="title">温馨提示</div>
        <div class="content">
            <p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
            <p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
        </div>
    </div>
<?php if(count($cards)==0) {?>
	
	<div style="padding-top:25%;text-align:center;" onClick="history.back(-1);">
    	<img src="<?php echo base_url("public/member/public/images/novote.png");?>" style="width:4rem">
		<div style="padding-bottom:1rem">没有更多可用劵了~<span style="color:#e60012;">点此返回</span></div>
	</div>
<?php } else { ?>
        <ul class="votelist">
        <?php foreach($cards as $card) {?>
                <li <?php if(time() >= $card->date_info_end_timestamp){ ?> class="timeout" <?php } ?> >
<!--                    <div class="checkbox"><em></em><input type="checkbox"></div>-->
                    <div class="ui_vote">
                        <p class="bordertop_img"></p>
                        <div class="vote_con">
                            <p class="ui_price ui_red"><?php echo $card->reduce_cost;?></p>
                            <p><b><?php echo $card->title;?></b></p>
                            <p class="ui_gray">仅限速8连锁酒店使用</p>
                        </div>
                        <div class="val_date">
                            <?php if(time() < $card->date_info_end_timestamp){ ?>
                                <p class="ui_red">还有<?php
                                    $nowDate = date("Y-m-d",time());
                                    $expireDate = date("Y-m-d",$card->date_info_end_timestamp);
                                    echo floor( (strtotime($expireDate)-strtotime($nowDate)) /(3600*24));
//                                    echo floor(($card->date_info_end_timestamp-time())/(3600*24));
                                    ?>天过期</p>
                            <?php } ?>
                            <p class="ui_gray">有效期至<?php echo date('Y年m月d日',$card->date_info_end_timestamp);?></p>
                        </div>
                    </div>
                </li>
<?php } ?>
        </ul>
<?php } ?>
</div>
<div style="padding-top:18%"><a class="footbtn" href="<?php echo base_url('/index.php/hotel/hotel')."/search?id=".$inter_id;?>">去订房</a></div>
</body>
</html>