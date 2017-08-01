<body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
<!--<script src="--><?php //echo base_url('public/soma/scripts/alert.js');?><!--"></script>-->
<!-- 腾讯地图加载异常<div class="page_loading"><p class="isload">正在加载</p></div> -->
<script>
<!--    wx.config({-->
<!--        debug: false,-->
<!--        appId: 'wxaea772c0c45af8e6',-->
<!--        timestamp: --><?php //echo time();?><!--,-->
<!--        nonceStr: '6gd0zkbzzu5zxe8vi2tcm1rv2tuw9njo',-->
<!--        signature: 'c5d9d0f781f9000733141c4df5ace1bc5715aadf',-->
<!--        jsApiList: ['openAddress']-->
<!--    });-->

    wx.config({
        debug: false,
        appId: 'wxf8b4f85f3a794e77',
        timestamp: <?php echo time();?>,
        nonceStr: 'Weyaol5f02cuNGcW',
        signature: 'a4d4a0fee3b988a6e9a3ece5504eb0f8cc77495e',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'onVoicePlayEnd',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });
</script>

</body>



