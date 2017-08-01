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
            overflow: auto;
            font-family: 微软雅黑
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
            width: 30%;
            left: 35%;
            margin-bottom: 100px;
        }

        .down {
            bottom: 0px;
        }

        .btn {
            color: white;
            background-color: #ff9900;
            border-radius: 5px;
            padding: 7px 20px;
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
        .neirong{
            background-color: #f3f4f8;
            border-radius: 5px;
            margin-top: -20px;
        }
        .kuai{
            position: relative;
            width: 100%;
            background-color: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        flex {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        flex[between] {
            justify-content: space-between;
        }

        flex[around] {
            justify-content: space-around;
        }
        flex[center] {
            justify-content:center;
        }
        flex[end] {
            align-items: flex-end;
        }

        flex[nowrap] {
            flex-wrap: nowrap;
        }
        .logo{
            width: 6%;
            border-radius: 100%;
        }
        block{
            width: 100%;
            display: block;
        }
        .border-bottom{
            border-bottom: 1px solid #eeecea;
        }
        .module{
            padding: 10px;
            margin: 0px 10px;
        }
        .ff9900{
            color: #ff9900;
        }
        .discount{
            color: #b9b9b9;
            text-decoration:line-through;
            font-size: 10px !important;
            text-align: right;
        }
        .info{
            color: #b9b9b9;
            font-size: 10px !important;
        }
        *{
            font-size: 16px;
        }
        .tt{
            top: 50%;
            left: 32%;
            width: 39%;
            height: 15%;
            text-align: center;
            position: absolute;
        }
        .footer{
            bottom: 0px;
            width: 100%;
            height: 50px;
            line-height: 50px;
            text-align: center;
            background-color: #ff9900;
            color: white;
        }
        .gray{
            color : #b9b9b9 !important;
        }
        .qiangguang{
            width: 40px;
            padding: 0px 8px;
        }
        .body{
            width: 80%;
            position: absolute;
            left: 10%;
            top: 10%;
            background-color: white;
            border-radius: 5px;
        }
        .tixing,.sry{
            top: 0px;
            background-color: RGBA(0,0,0,0.5);
            position: fixed;
        }
        .back{
            color: #ff9900;
            border: 1px solid #ff9900;
            padding: 5px 15px;
            border-radius: 5px;
        }
        .sztx,.queding{
            color: white;
            border: 1px solid #ff9900;
            background-color: #ff9900;
            padding: 5px 15px;
            border-radius: 5px;
        }
        .border-bottom>flex>ib:nth-child(1){
            width: 60%;
        }
        .tanchuang{
            top: 0px;
            background-color: RGBA(0,0,0,0.5);
            position: fixed;
        }
        .body2{
            width: 80%;
            position: absolute;
            left: 10%;
            top: 30%;
            background-color: white;
            border-radius: 5px;
            opacity: 0.9;
        }
        .queding2{
            color: white;
            border: 1px solid #ff9900;
            background-color: #ff9900;
            padding: 5px 15px;
            border-radius: 5px;
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
            success: function () {

            },
            cancel: function () {}
        });
        <?php endif; ?>
    });

</script>

<div class="banner w100 relative">
    <img id="banner" src="<?php echo get_cdn_url('public/soma/images/kill_group/jukaishi.jpg'); ?>" class="w100">
    <ib class="tt red">12:00:00</ib>
</div>
<div class="neirong w100 relative" style="margin-bottom:55px;">
    <?php  foreach ($hotelProducts as $item): ?>
        <flex class="kuai">

            <block class="border-bottom module center">
                <img src="<?php echo $item['hotel']['intro_img']; ?>" class="logo">
                <ib><?php echo $item['hotel']['name']; ?></ib>
            </block>
            <?php  foreach ($item['products'] as $product): ?>
                <?php if(isset($product['kill'])) :?>
                    <input type="hidden" value="<?php echo $product['kill']['act_id']; ?>" class="act_id">
                    <block class="border-bottom module">
                        <flex class="w100" between>
                            <ib>
                                <div><?php echo $product['name'];?></div>
                                <div class="info"><?php echo $product['keyword'];?></div>
                            </ib>
                            <ib>
                                <ib>
                                    <div class="ff9900 <?php if($product['kill']['over'] == true ) :?>gray<?php endif ?>  ">
                                        <?php if( $times['show_time'] >  time() && time() < $times['kill_time'] ) :?>
                                            ¥<?php echo $product['kill']['killsec_price'];?>
                                        <?php else: ?>
                                            ¥<?php $stars = '*********'; echo ($product['kill']['killsec_price']>100 )? substr(intval($product['kill']['killsec_price']), 0, 1) . substr($stars, 0, strlen(intval($product['kill']['killsec_price']))-2). substr($product['kill']['killsec_price'], -1) : $product['kill']['killsec_price'];?>
                                        <?php endif ?>
                                    </div>
                                    <div class="discount">¥<?php echo $product['price_package'];?></div>
                                </ib>
                                <ib>
                                    <?php if($product['kill']['over'] == true) :?>
                                        <ib class="btn none">抢</ib>
                                        <img src="<?php echo get_cdn_url('public/soma/images/kill_group/none.png'); ?>" class="qiangguang">
                                    <?php else: ?>
                                        <ib class="btn" ontouchstart="qiang('<?php echo $product['product_id']; ?>', <?php echo $product['kill']['act_id']; ?>)">抢</ib>
                                    <?php endif ?>
                                </ib>
                            </ib>
                        </flex>
                    </block>
                <?php endif ?>
            <?php endforeach; ?>
        </flex>
    <?php endforeach; ?>
<!--    <flex class="kuai">-->
<!--        <block class="border-bottom module center">-->
<!--            <img src="../../public/soma/images/kill_group/logo.png" class="logo">-->
<!--            <ib>金房卡大酒店岗顶店</ib>-->
<!--        </block>-->
<!--        <block class="border-bottom module">-->
<!--            <flex class="w100" between>-->
<!--                <ib>-->
<!--                    <div>景观家庭豪华大床房</div>-->
<!--                    <div class="info">含早午餐 保留到24点</div>-->
<!--                </ib>-->
<!--                <ib>-->
<!--                    <ib>-->
<!--                        <div class="ff9900">¥255</div>-->
<!--                        <div class="discount">¥699</div>-->
<!--                    </ib>-->
<!--                    <ib>-->
<!--                        <ib class="btn">抢</ib>-->
<!--                    </ib>-->
<!--                </ib>-->
<!--            </flex>-->
<!--        </block>-->
<!--        <block class="border-bottom module">-->
<!--            <flex class="w100" between>-->
<!--                <ib>-->
<!--                    <div>海景超级无敌大床房</div>-->
<!--                    <div class="info">含早 保留到24点</div>-->
<!--                </ib>-->
<!--                <ib>-->
<!--                    <ib>-->
<!--                        <div class="ff9900 gray">¥388</div>-->
<!--                        <div class="discount">¥699</div>-->
<!--                    </ib>-->
<!--                    <ib>-->
<!--                        <ib class="btn none">抢</ib>-->
<!--                        <img src="../../public/soma/images/kill_group/none.png" class="qiangguang">-->
<!--                    </ib>-->
<!--                </ib>-->
<!--            </flex>-->
<!--        </block>-->
<!--    </flex>-->

</div>

<!--<div class="footer fixed">-->
<!--    <ib>-->
<!--        <img src="../../public/soma/images/kill_group/bear.png" style="width:20px;">-->
<!--        <ib>设置提醒</ib>-->
<!--    </ib>-->
<!--</div>-->

<div class="tixing full none">
    <div class="body">
        <div style="margin:15px;">
            <img src="<?php echo get_cdn_url('public/soma/images/kill_group/tixing.png'); ?>" class="w100">
            <div style="line-height:2">抢购还未开始，您可以设置提醒，开始前10分钟提醒您</div>
            <flex between style="margin-top:20px;">
                <ib class="back">算了下次</ib>
                <ib class="sztx"  id="">设置提醒</ib>
            </flex >
        </div>
    </div>
</div>
<div class="sry full none">
    <div class="body">
        <div style="margin:15px;">
            <img src="<?php echo get_cdn_url('public/soma/images/kill_group/qiangguang.png'); ?>" class="w100">
            <div style="line-height:2">很遗憾，今晚的特价房已经被抢光了，明晚再来吧！</div>
            <flex center style="margin-top:20px;">
                <ib class="queding">确定</ib>
            </flex >
        </div>
    </div>
</div>

<div class="tanchuang full none">
    <div class="body2">
        <div style="margin:30px;">
            <div style="line-height:2;text-align:center;" class="ts"></div>
            <flex center style="margin-top:20px;">
                <ib class="queding2">确定</ib>
            </flex >
        </div>
    </div>
</div>

<script src="<?php echo get_cdn_url('public/soma/js/Taoja_new.js'); ?>"></script>
<script>
    var juli = document.querySelector(".tt");
    var footer = document.querySelector(".footer");
    var tixing = document.querySelector(".tixing");
    var sry = document.querySelector(".sry");
    var back = document.querySelector(".back");
    var queding = document.querySelector(".queding");
    var sztx = document.querySelector(".sztx");

    var xxx = function(num){
        this.endTime = num;
        this.go();
    }
    xxx.prototype.go = function(){
        var nowtime = (new Date()).getTime();
        var cha = this.endTime - nowtime;
        if(cha <= 0 ){
            cha = 0;
        }
        var back = sjc("hh:mm:ss",cha);
        juli.innerHTML = back;
        setTimeout(function(){
            this.go();
        }.bind(this),1000);
    }
    var sjc = function(fmt,ts){
        var days = Math.floor(ts/(24*3600*1000));
        var leave1 = ts%(24*3600*1000);
        var hours = Math.floor(leave1/(3600*1000));
        var leave2=leave1%(3600*1000)        //计算小时数后剩余的毫秒数
        var minutes=Math.floor(leave2/(60*1000))
        //计算相差秒数
        var leave3=leave2%(60*1000)      //计算分钟数后剩余的毫秒数
        var seconds=Math.round(leave3/1000)
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

    var killTime = '<?php echo $times['kill_time'] * 1000; ?>';
    var endTime = '<?php echo $times['end_time'] * 1000 ; ?>';

    var nowtime = (new Date()).getTime();
    var banner = document.querySelector("#banner");

    if ( killTime < nowtime && nowtime < endTime) {
        banner.src = '<?php echo get_cdn_url('public/soma/images/kill_group/jujieshu.jpg'); ?>';
        new xxx(endTime);
    } else if( nowtime < killTime ) {
        new xxx(killTime);
    } else {
        xxx.endTime = '00:00:00';
    }

    var queding2 = document.querySelector(".queding2");
    var tanchuang = document.querySelector(".tanchuang");
    queding2.addEventListener("touchend",function(){
        tanchuang.style.display = "none";
    });
    function setts(str){
        var ts = document.querySelector(".ts");
        ts.innerHTML = str;
        tanchuang.style.display = "block";
    }

    function qiang(productID, actID){

        var nowTime = (new Date()).getTime();
        if ( killTime < nowTime && nowTime < endTime) {
            $http('<?php echo Soma_const_url::inst()->get_url('*/killsec/product', array('id'=>$group['inter_id'] )); ?>').post({"pid": productID}).then(function(json){
                var act_id = 0;
                if( json.status == 1 ){
                    act_id = json.act_id;
                    $http('<?php echo Soma_const_url::inst()->get_url('*/killsec/get_killsec_token_ajax', array('id'=>$group['inter_id'] )); ?>').post({"act_id": act_id}).then(function(json){
                        if( json.status == 1 ){
                            var token= json.data.token;
                            var instance_id= json.data.instance_id;

                            location.href='<?php echo Soma_const_url::inst()->get_url('*/killsec/package_pay',
                                    array('id'=>$group['inter_id'] )); ?>&instance_id='+ instance_id+ '&token='+ token + '&pid='+ productID + '&act_id='+ act_id;
                        } else if( json.status == 2 ){
                            setts( json.message );
                        }
                    }).catch(function(error) {
                        setts( '哎呦喂，被挤爆了，请稍后重试！' );
                    });

                } else if( json.status == 2 ){
                    setts( json.message );
                }
            }).catch(function(error) {
                setts( '哎呦喂，被挤爆了，请稍后重试！' );
            });
        } else if ( nowTime < killTime - 15 * 60 * 1000) {

            tixing.style.display = "block";
            sztx.id = actID;

        } else if ( nowTime > killTime - 15 * 60 * 1000) {
            setts('特价抢购即将开始');
        } else {
            console.log(4);
        }
    }

    back.addEventListener("touchend",function(){
        tixing.style.display = "none";
    });

    function meile(){
        sry.style.display = "block";
    }

    function goto(e){
        window.location = e;
    }

    sztx.addEventListener("touchstart",function(){
        var actID = sztx.id;
        $http('<?php echo Soma_const_url::inst()->get_url('*/killsec/subscribe_killsec_notice_ajax', array('id'=>$group['inter_id'] )); ?>').post({"act_id": actID}).then(function(json){
            tixing.style.display = "none";

            if( json.status == 1 ){
                setts( json.message );
            } else if( json.status == 2 ){
                setts( json.message );
            }
        });
    });

    new Tao()

</script>
</body>

</html>
