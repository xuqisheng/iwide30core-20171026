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
<title>卡券中心</title>
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
.cards_list .item{border-color:#fe9402; background:none}
.goods_name,.cards_list .item .goods_desc,.ui_price{color:#fe9402;}
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
            <div class="ui_price"><?php echo $card['money'];?></div>
            <div class="goods_name"><?php echo $card['title'];?></div>
            <div class="goods_desc"><?php echo $card['brand_name'];?></div>
        </a>
        <?php } ?>
    </div>
</div>
</html>