
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'openLocation',
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });
function tonavigate(lati,longi,hname,addr)
{
	wx.openLocation({
		latitude: lati,
		longitude: longi,
		name: hname,
		address: addr,
		scale: 15,
		infoUrl: ''
	});
}
</script>
