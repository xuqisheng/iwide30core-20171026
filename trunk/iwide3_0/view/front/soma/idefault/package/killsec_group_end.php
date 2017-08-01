<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Pragma" content="no-cache">
    <title>抢尾房</title>
    <style>
        p {
            margin: 0;
            font-family: 微软雅黑
        }

        .w100 {
            width: 100%
        }

        .absolute {
            position: absolute
        }

        body {
            width: 100%;
            height: 100%;
            position: absolute;
            margin: 0;
            overflow: hidden;
            font-size: 14px;
            font-family: Heiti SC
        }

        .right {
            float: right
        }

        .full {
            width: 100%;
            height: 100%
        }

        .maxscreen {
            width: 100%
        }

        .left {
            float: left
        }

        .coverpage,
        .coverpage2 {
            width: 100%;
            height: 100%;
            background-color: #000;
            opacity: .8
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

        .left {
            float: left
        }

        .fullbg {
            background-size: 100% 100%
        }

        .main {
            transform-origin: top left;
            -webkit-transform-origin: top left;
            -o-transform-origin: top left;
            -moz-transform-origin: top left;
        }

        .full {
            height: 100%;
        }

        .center {
            text-align: center;
        }

        .black {
            background-color: black;
        }

        .shalou {
            top: 12%;
            width: 30%;
            left: 35%;
            position: absolute;
        }

        .down {
            bottom: 5%;
        }

        .btn {
            color: white;
            background-color: #1b2749;
            border-radius: 5px;
            border: 1px solid #153a76;
            padding: 10px 0px;
            position: relative;
            width: 40%;
            float: left;
            left: 30%;
            margin: 10px 0px 50px 0px;
            box-shadow: 0px 0px 13px #153a76;
            text-align: center;
        }

        ib {
            display: inline-block;
            vertical-align: middle;
        }

        img,
        input {
            vertical-align: middle;
        }

        .ic {
            width: 15%;
        }

        .red {
            color: #fefbc9;
            text-shadow: 0px 0px 13px red;
            font-size: 1.5em;
        }

        .blue {
            color: #5a99ff;
        }

        .bg {
            overflow: hidden;
        }

    </style>
</head>

<body>
<img src="<?php echo get_cdn_url('public/soma/images/kill_group/shalou.png'); ?>" class="hidden none">
<div class="bg full absolute bottom">
    <img src="<?php echo get_cdn_url('public/soma/images/kill_group/bg.jpg'); ?>" class="w100">
    <div class="relative shalou left center">
        <img src="<?php echo get_cdn_url('public/soma/images/kill_group/shalou.png'); ?>" class="shaloubg w100">
    </div>
    <div class="absolute w100 left down">
        <div class="w100 left center" style="margin-bottom:30px;">
            <ib>
                <ib class="red juli">活动已结束</ib>
            </ib>
        </div>
        <?php if(!empty($redirect_info)) :?>
            <div class="w100 left">
                <ib class="btn" ontouchstart=" goto('<?php echo $redirect_info['url'];?>') ">
                    <?php echo $redirect_info['name'];?>
                </ib>
            </div>
        <?php endif ?>
    </div>
</div>

<script>
    function goto(e){
        window.location = e;
    }
</script>

</body>

</html>
