<body>
<div class="pageloading"><p class="isload"></p></div>
<div class="bg pr" id="index">

    <a class="header pa">
        <img src="http://www.jfk.com/public/soma/mooncake4/images/1.png" alt="">
    </a>

    <div class="logo"></div>

    <div class="pr img-wrap">
        <div class="img-container pr">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" name ="测试1" discount="123" original="999" href="http://www.baidu.com">
                      <img src="http://www.jfk.com/public/soma/mooncake4/images/1.png">
                    </div>
                    <div class="swiper-slide" name ="测试2" discount="456" original="666" href="http://www.qq.com">
                      <img src="http://www.jfk.com/public/soma/mooncake4/images/1.png">
                    </div>
                    <div class="swiper-slide" name ="测试3" discount="789" original="888" href="http://www.hahah.com">
                        <img src="http://www.jfk.com/public/soma/mooncake4/images/1.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="arrow pa left-arrow"></div>
        <div class="arrow pa right-arrow"></div>
    </div>

    <div class="ta-c name-wrap">
        <div class="shop-name pr" id="name">
            大三元吉祥月饼
        </div>
    </div>

    <div class="price-wrap ta-c">
        <div class="price">
            <span class="symbol">￥</span>
            <span class="discount" id="discount">145</span>
            <span class="original" id="original">￥347</span>
        </div>
    </div>


      <a href="" class="button" id="href"></a>

</div>

</body>

<script src="<?php echo get_cdn_url('public/soma/mooncake4/js/index.js');?>"></script>
<script>
    var package_obj= {
        'appId': '<?php echo $wx_config["appId"]?>',
        'timestamp': <?php echo $wx_config["timestamp"]?>,
        'nonceStr': '<?php echo $wx_config["nonceStr"]?>',
        'signature': '<?php echo $wx_config["signature"]?>'
    }
    /*下列字符不能删除，用作替换之用*/
    //[<sign_update_code>]
    wx.config({
        debug: false,
        appId: package_obj.appId,
        timestamp: package_obj.timestamp,
        nonceStr: package_obj.nonceStr,
        signature: package_obj.signature,
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
    });
    wx.ready(function(){

        <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

        <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

        <?php if( $js_share_config ): ?>
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

        // wx.getLocation({
        //     success: function (res) {
        //         get_package_nearby(res.latitude,res.longitude);
        //     },
        //     cancel: function (res) {
        //         $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置');
        //     }
        // });
    });
</script>
</html>
