<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?=!empty($page_title) ? $page_title : '我的海报';?></title>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
    <?php include 'wxheader.php' ?>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
        }

        img {
            vertical-align: top;
            max-width: 100%;
        }

        body {
            width: 100%;
            overflow: hidden;
        }
        
        @-webkit-keyframes jfk-rotate {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        50% {
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .jfk-popup {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 999999999;
        background: rgba(0, 0, 0, .85);
        margin: 0;
        padding: 0;
    }

    .jfk-popup .loader {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .ball-clip-rotate>.item {
        background: #b2945e;
        border-radius: 100%;
        margin: 2px;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        border: 2px solid #b2945e;
        border-bottom-color: transparent;
        height: 20px;
        width: 20px;
        background: transparent !important;
        display: inline-block;
        -webkit-animation: jfk-rotate 0.75s 0s linear infinite;
        animation: jfk-rotate 0.75s 0s linear infinite;
    }

    .loading-text {
        font-size: 13px;
        color: #b2945e;
        text-align:center;
    }
        
    .error {
        font-size: 13px;
        color: #b2945e;
        text-align: center;
        margin: 20% auto;
    }

    </style>
</head>
<body>
    
    <div class="jfk-popup" id="loading">
        <div class="loader">
            <div class="loader-inner ball-clip-rotate">
                <div class="item"></div>
            </div>
            <div class="loading-text">正在生成海报</div>
        </div>
    </div>

</body>
<script type="text/javascript" src="<?php echo base_url("public/member/scripts/jquery.js");?>"></script>
<script type="text/javascript">
// 呢称
var nickname = "<?=$nickname?>"; 
// 背景图
var bgUrl = "<?php echo base_url("public/member/images/bg.jpg?version=15032022288");?>";
var identity = "<?=$identity?>";
var identity2 = "<?=$identity2?>";
var level=  "<?=$lvl_name?>";
var identity_type = "<?=$identity_type?>";

function getQueryString (name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r !== null) {
        return decodeURIComponent(r[2]);
    } else {
        return null;
    }
}

// 二维码图片
var qrcodeUrl = "<?php echo base_url('/index.php/membervip/poster/posterqrcode?id=');?>";

qrcodeUrl =  qrcodeUrl + getQueryString('id');

 if (getQueryString('openid') !== null) {
    qrcodeUrl = qrcodeUrl + '&openid=' + getQueryString('openid');
}


var levelContent = {
    '白银': '<?php echo base_url("public/member/images/1.png?version=1503202788");?>',
    '黄金': '<?php echo base_url("public/member/images/2.png?version=1503202788");?>',
    '青铜': '<?php echo base_url("public/member/images/3.png?version=1503202788");?>',
    '王者': '<?php echo base_url("public/member/images/4.png?version=1503202788");?>'
}

function error () {
    $('body').html('<div class="error">生成海报失败,请刷新重试</div>');
}


// 宽高的比例
var rate = 750 / 1211;
// 图像的宽度
var canvasWidth = $(window).width();
// 图像的高度
var canvasHeight = parseInt(canvasWidth / rate);
// 如果计算的高度小于屏幕的高度，设置为屏幕的高度
canvasHeight = canvasHeight <= $(window).height() ? $(window).height() : canvasHeight;

var $loading = $('#loading');

// 背景图
var bgImg = new Image;
bgImg.src = bgUrl;


// 等级的logo
var levelImg = new Image;
levelImg.src = levelContent[level];

// 配置的宽高
var pixelRatio = 2;
var _height, _width;
_width = canvasWidth * pixelRatio;
_height = canvasHeight * pixelRatio;


// canvas 
var $canvas = $('<canvas id="canvas"  width = "' + _width + '"  height="' + _height + '"></canvas>');
$canvas.appendTo($('body'));
var canvas = $canvas.get(0);
var cxt = canvas.getContext('2d');


function loadImg(img, name) {
    var def = $.Deferred();
    $(img).on('load', function() {
        def.resolve('加载' + name + '图片成功');
    }).on('error', function() {
        def.reject('加载' + name + '图片失败');
        error();
    })
    return def.promise();
}


// 等待背景图 和 二维码加载完成， 合成海报
$.when(loadImg(bgImg, '背景'),  loadImg(levelImg, '等级')).then(function() {

    // 合成背景图
    cxt.drawImage(bgImg, 0, 0, _width, _height);

    // 等级的logo
    var levelWidth = _width * 85 / 750;
    var levelHeight = _width * 92 / 750;
    var levelX = _width * 177 / 750;
    var levelY = _height * 447 / 1211;
    cxt.drawImage(levelImg, levelX, levelY, levelWidth, levelHeight);


    // 合成呢称 
    var nickNameX = _width * 264 / 750;
    var nickNameY = _height * 482 / 1211;
    var nicknameFontSize = _width * 24 / 750;
    cxt.fillStyle = "#111111";
    cxt.font = nicknameFontSize + 'px Helvetica Neue,sans-serif';
    cxt.fillText('我是'+ nickname, nickNameX, nickNameY);

    // 合成文案的等级
    // 判断是否存在馅饼侠
    if ($.trim(identity2).length > 0) {
        var fontSize = _width * 24 / 750;
        cxt.font = fontSize + 'px Helvetica Neue,sans-serif';
        cxt.fillStyle = "#111111";
        cxt.fillText('我是', _width * 264 / 750, _height * 516 / 1211);
        cxt.fillStyle = "#e40e10";
        cxt.fillText(identity2, _width * 311 / 750, _height * 516 / 1211);

        if ($.trim(identity).length > 0) {
            cxt.fillStyle = "#111111";
            cxt.fillText('我是', _width * 398 / 750, _height * 516 / 1211);
            cxt.fillStyle = "#e40e10";
            cxt.fillText(level + identity, _width * 448 / 750, _height * 516 / 1211);
        }

    } else {
        if ($.trim(identity).length > 0) {
            var fontSize = _width * 24 / 750;
            cxt.font = fontSize + 'px Helvetica Neue,sans-serif';
            cxt.fillStyle = "#111111";
            cxt.fillText('我是', _width * 264 / 750, _height * 516 / 1211);
            cxt.fillStyle = "#e40e10";
            cxt.fillText(level + identity, _width * 311 / 750, _height * 516 / 1211);
         }
    }


   var imageUrl  = canvas.toDataURL();

   var imgContainer = $('<img src="'+imageUrl+'">');
   imgContainer.appendTo($('body'));
   $canvas.remove();

    imgContainer.on('load', function () {
        $loading.remove();
    });

    imgContainer.on('error', function () {
        error();
    });

});

<?php if(isset($wx_config) && !empty($wx_config)):?>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation','openLocation']
});

wx.ready(function(){
    <?php if(!empty($js_menu_hide)): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
    <?php if(!empty($js_menu_show)): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
    <?php if(!empty($js_share_config)): ?>
    wx.onMenuShareTimeline({
        title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'分享到朋友圈';?>',
        link: '<?php echo $js_share_config["link"];?>',
        imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
        success: function () {},
        cancel: function () {}
    });
    wx.onMenuShareAppMessage({
        title: '<?php echo !empty($js_share_config["title"])?$js_share_config["title"]:'发送给好友'?>',
        desc: '<?php echo $js_share_config["desc"];?>',
        link: '<?php echo $js_share_config["link"];?>',
        imgUrl: '<?php echo $js_share_config["imgUrl"];?>',
        success: function () {},
        cancel: function () {}
    });
    <?php endif; ?>
});

<?php endif;?>


</script>

</html>

