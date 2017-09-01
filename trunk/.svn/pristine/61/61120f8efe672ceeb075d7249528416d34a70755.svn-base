<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no" />
    <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
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
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $wx_config["appId"]?>',
        timestamp: <?php echo $wx_config["timestamp"]?>,
        nonceStr: '<?php echo $wx_config["nonceStr"]?>',
        signature: '<?php echo $wx_config["signature"]?>',
        jsApiList: [<?php echo $js_api_list; ?>]
    });
    wx.ready(function(){

        var js_share_config = <?php echo json_encode($js_share_config);?>;
        console.log(js_share_config);

        <?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: js_share_config.title,
            link: js_share_config.link,
            imgUrl: js_share_config.imgUrl,
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: js_share_config.title,
            link: js_share_config.link,
            imgUrl: js_share_config.imgUrl,
            desc: js_share_config.desc,
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {},
            cancel: function () {}
        });
        <?php endif; ?>
    });

</script>

<img src="<?php echo get_cdn_url('public/soma/images/kill_group/shalou.png'); ?>" class="hidden none">
<div class="bg full absolute bottom">
    <img src="<?php echo get_cdn_url('public/soma/images/kill_group/bg.jpg'); ?>" class="w100">
    <div class="relative shalou left center">
        <img src="<?php echo get_cdn_url('public/soma/images/kill_group/shaloubg.png'); ?>" class="shaloubg absolute w100">
        <canvas class="djs w100" width="266px" height="271px"></canvas>
    </div>
    <div class="absolute w100 left down">
        <div class="w100 left center" style="margin-bottom:10px;">
            <ib class="blue" style="font-size:1.2em;">特价尚未开始</ib>
        </div>
        <div class="w100 left center" style="margin-bottom:40px;">
            <ib class="blue"><span class="red"><?php echo $show_time;?></span> 放房型 <span class="red"><?php echo $kill_time;?></span> 开抢当天特价房</ib>
        </div>
        <div class="w100 left center">
            <ib>
                <img src="<?php echo get_cdn_url('public/soma/images/kill_group/icon.png'); ?>" class="ic">
                <ib class="red juli">距离开抢：12:31:00</ib>
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
<script src="<?php echo get_cdn_url('public/soma/js/Taoja_new.js'); ?>"></script>
<script>
    document.addEventListener("touchstart", function(e) {
        e.preventDefault();
    });
    var tao = new Tao({
        load: "<?php echo get_cdn_url('public/soma/images/kill_group/tao.exe'); ?>",
        storage: "tao"
    });
    tao.result = function(e) {
        if (e.decode) {
            var arr = Gorender(e.decode);
            var video = tao.TaoVideo(arr, 50);
            video.result = function(e) {
                djs.clearRect(0, 0, 266, 271);
                djs.drawImage(e, 0, 0, 266, 271);
            }
            video.stop = function() {
                video.play();
            }
            video.play();
        }
    }
    var djs = document.querySelector(".djs").getContext("2d");
    var img = document.querySelector(".hidden");
    var juli = document.querySelector(".juli");
    img.onload = function() {
        djs.drawImage(img, 0, 0, 266, 271);
    }

    function Gorender(e) {
        var arr = [];
        e.forEach(function(a, b, c) {
            arr.push(a.url());
        });
        return arr;
    }
    var xxx = function(num) {
        this.endTime = num;
        this.go();
    }
    xxx.prototype.go = function() {
        var nowtime = (new Date()).getTime();
        var cha = this.endTime - nowtime;
        if (cha <= 0) {
            cha = 0;
        }
        var back = sjc("hh:mm:ss", cha);
        juli.innerHTML = "距离开抢：" + back;
        setTimeout(function() {
            this.go();
        }.bind(this), 1000);
    }
    var sjc = function(fmt, ts) {
        var days = Math.floor(ts / (24 * 3600 * 1000));
        var leave1 = ts % (24 * 3600 * 1000);
        var hours = Math.floor(leave1 / (3600 * 1000));
        var leave2 = leave1 % (3600 * 1000) //计算小时数后剩余的毫秒数
        var minutes = Math.floor(leave2 / (60 * 1000))
        //计算相差秒数
        var leave3 = leave2 % (60 * 1000) //计算分钟数后剩余的毫秒数
        var seconds = Math.round(leave3 / 1000)
        var o = {
            "d+": days, //日
            "h+": hours, //小时
            "m+": minutes, //分
            "s+": seconds, //秒
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (dateoj.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }

    function goto(e){
        window.location = e;
    }


    var killTime = <?php echo $times['kill_time'] * 1000;?>;
    var endTime = <?php echo $times['end_time'] * 1000;?>;
    if (new Date().getTime() > endTime) {
        killTime += 24*3600*1000;
        new xxx(killTime);
    } else {
        new xxx(killTime);
    }

</script>
</body>

</html>
