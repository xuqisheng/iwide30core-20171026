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
<script src="<?php echo base_url("public/member/public/js/jquery.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/ui_control.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/imgscroll.js");?>"></script>
<script src="<?php echo base_url("public/member/public/js/jquery.touchwipe.min.js");?>"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<link href="<?php echo base_url("public/member/public/css/global.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/ui_style.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/imgscroll.css");?>" rel="stylesheet">
<link href="<?php echo base_url("public/member/public/css/store.css");?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
<title>购卡列表</title>
<style>
    html{
            font-size: 12px !important;
            font-family: PingFang SC Light, 微软雅黑;
        }
        .w100 {
            width: 100%
        }
        .absolute {
            position: absolute;
        }
        body {
            width: 100%;
            height: 100%;
            position: absolute;
            margin: 0;
            font-size: 1rem;
            overflow: hidden;
        }
        .right {
            float: right
        }
        .full {
            width: 100%;
            height: 100%
        }
        .fixed {
    position: fixed
}
.none {
    display: none
}
.relative {
    position: relative
}
        ib {
    display: inline-block;
    vertical-align: middle;
    word-break: break-all;
}
        flex {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
.center {
    text-align: center;
}
        [around] {
    justify-content: space-around;
}
        [between]{
            justify-content: space-between;
        }
        [nowrap] {
    flex-wrap: nowrap;
}
    .tc{
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: RGBA(0,0,0,0.8);
            top: 0px;
            left: 0px;
        }
        .tc_body{
            width: 81.25%;
            margin: auto;
            background-color: white;
            border-radius: 5px;
            line-height: 1.5;
            text-indent: 2rem;
        }
        .tc_body>div{
            padding: 1.25rem 1.5rem;
        }
    .tc_body>div>div{
        margin-top: 1rem;
    }
        .tc_body>div>div:nth-child(1){
            text-align: center;
            font-size: 1.25rem;
            text-indent:0rem;
            font-weight: bold;
        }
        .tc_body>div>div:nth-child(2){
            margin-top: 1.08rem;
            text-indent:0rem;
        }
        .tc_body>div>div:last-child{
            text-indent: 0rem;
        }
        .tc_body>div>flex{
            margin-top: 1rem;
            text-indent: 0rem;
        }
        .tc_body>div>flex>ib{
            width: 8.5rem;
            height: 2.91rem;
            text-align: center;
            line-height: 2.91rem;
            border-radius: 2px;
        }
        .tc_body>div>flex>ib:nth-child(1){
            border: 1px #b5b5b5 solid;
            color: #b5b5b5;
            background-color: white;
        }
        .tc_body>div>flex>ib:nth-child(2){
            border: 1px transparent solid;
            color: white;
            background-color: #ff9900;
        }
    .tc_body span{
        color: #ff9900;
    }
    input[type = "checkbox"]{
        -webkit-appearance:  !important;
        vertical-align: middle;
        margin-right: 0.5rem !important;
    }
    input[type="checkbox"] {
	margin: 0px;
	position: relative;
	cursor: pointer;
	width: 1rem;
	height: 1rem;
	vertical-align: middle;
}

input[type="checkbox"]:before {
	-webkit-transition: all 0.3s ease-in-out;
	-moz-transition: all 0.3s ease-in-out;
	transition: all 0.3s ease-in-out;
	content: "";
	position: absolute;
	left: 0;
	z-index: 1;
	width: 1rem;
	height: 1rem;
	border: 1px solid #d9dfe9;
}

input[type="checkbox"]:checked:before {
	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	transform: rotate(-45deg);
	height: .5rem;
	border-color: #ff9900;
	border-top-style: none;
	border-right-style: none;
}

input[type="checkbox"]:after {
	content: "";
	position: absolute;
	width: 1rem;
	height: 1rem;
	background: #fff;
	cursor: pointer;
}
body,html{background:#f8f8f8}
.cards_list .item{border-color:#316f4d; background:none}
.goods_name,.cards_list .item .goods_desc,.ui_price{color:#316f4d;}
.ui_price{
    font-size: 1.4rem !important;
}
.goods_name{
    font-size: 1.4rem !important;
}
.goods_desc{
    font-size: 1rem !important;
}
</style>
<body>
<div class="page">
    <div class="cards_list">
        <?php foreach($card_list as $card) {?>
            <a href="<?php echo base_url("index.php/membervip/depositcard/info?cardId=".$card['deposit_card_id']);?>" class="item">
                <!--div class="ui_img_auto_cut">
                <img src="<?php echo $card['logo_url'];?>">
                <div class="number">88888888</div>
            </div-->
                <div class="ui_price"><?php echo $card['money'];?></div>
                <div class="goods_name"><?php echo $card['title'];?></div>
                <div class="goods_desc"><?php echo $card['brand_name'];?></div>
            </a>
        <?php } ?>
    </div>
    <!-- <div style="padding-top:15%;">
    <a class="ui_foot_btn" style="position: fixed;  bottom: 0;  width: 100%; border-radius:0; margin:0;"  href="<?php echo site_url("member/corder/orderlist");?>">我的订单</a>
</div> -->
</div>
<flex class="tc" <?php  if(isset($hide) && $hide) echo 'style="display:none";' ; ?> id="auth_tips">
    <div class="tc_body">
        <div>
            <div>会员卡泛分销绩效</div>
            <div>亲爱的会员朋友：</div>
            <div>小雅偷偷告诉您，雅斯特新的会员权益2.0版，不但会员福利升级而且还有会员泛分销资格！</div>
            <div>（一）入住返币：会员入住雅斯特，获房费消费<span>2%</span>金额的雅币。</div>
            <div>（二）福利同享：金卡和钻卡会员把客房优惠分享给您的朋友，同享微信订房金卡或钻卡优惠折扣。</div>
            <div>（三）售卡奖励：通过微信分享链接给朋友购买会员卡，如朋友购卡成功，第二天，小雅将把奖励发放到您的微信零钱包，可取现；售卖钻卡<span>100</span>元奖励，金卡<span>60</span>元奖励，银卡<span>10</span>元奖励。</div>
            <div>（四）客房奖励：金卡和钻卡会员推荐朋友入住雅斯特，如微信预付且成功入住，获<span>10</span>元微信零钱奖励可取现。</div>
            <flex between style="margin-top:1.2rem;">
<!--                <ib class="back cancel_auth">我先想想</ib>-->
                <ib class="ok authorize">我知道了</ib>
            </flex>
<!--            <div><input type="checkbox" name="auth_tips"><ib>不要再提示我</ib></div>-->
        </div>
    </div>
</flex>
</body>

<script>

    wx.config({
        debug: false,
        appId: '<?php echo $wx_config["appId"]?>',
        timestamp: <?php echo $wx_config["timestamp"]?>,
        nonceStr: '<?php echo $wx_config["nonceStr"]?>',
        signature: '<?php echo $wx_config["signature"]?>',
        jsApiList: [<?php echo (isset($wx_config['js_api_list']) && !empty($wx_config['js_api_list']))?$wx_config['js_api_list'].",":'' ; ?>'getLocation','openLocation']
    });
    wx.ready(function(){
        <?php if( isset($js_menu_hide) && $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
        <?php if( isset($js_menu_show) && $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

        <?php if( isset($js_share_config) && $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo $js_share_config["title"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {},
            cancel: function () {}
        });

        <?php endif; ?>
    });

    $(document).ready(function () {

        $('.authorize').on('click',function (e) {
            /*
            var check_flag = $("input[name='auth_tips']")[0].checked;
            var notice = 0;
            if(check_flag){
                notice = 1;
            }
            var datas = {"status":'authorize'};
            $.ajax({
                url:"<#?php echo base_url("index.php/membervip/depositcard/update_distribution_stats?id=".$inter_id);?>",
                data:datas,
                type:'POST',
                dataType:'json',
                timeout:6000,
                success: function (data) {
                    console.log(data);
                },
                error: function () {
//                    $('.page_loading').hide();
//                    ajaxload=false;
//                    alert("请求失败");
                }
            });
            */
            $('#auth_tips').hide();
        });
/*
        $(document).on('click','.cancel_auth',function (e) {

            var check_flag = $("input[name='auth_tips']")[0].checked;
            var notice = 0;
            if(check_flag){
                notice = 1;
            }
            if(notice == 0){
                $('.tc').hide();
                return;
            }

            var datas = {"status":'cancel_auth','notice':notice};
            $.ajax({
                url:"<#?php echo base_url("index.php/membervip/depositcard/update_distribution_stats?id=".$inter_id);?>",
                data:datas,
                type:'POST',
                dataType:'json',
                timeout:6000,
                success: function (data) {
                    console.log(data);
                },
                error: function () {
//                    $('.page_loading').hide();
//                    ajaxload=false;
//                    alert("请求失败");
                }
            });
            $('.tc').hide();
        })
*/

    })




</script>

</html>