
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
wx.ready(function(){
wx.onMenuShareTimeline({
    title: '<?php echo $share['title'];?>', // 分享标题
    link: '<?php echo $share['link'];?>', // 分享链接
    imgUrl: '<?php echo $share['imgUrl'];?>', // 分享图标
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
});
wx.onMenuShareAppMessage({
    title: '<?php echo $share['title'];?>', // 分享标题
    desc: '<?php echo $share['desc'];?>', // 分享描述
    link: '<?php echo $share['link'];?>', // 分享链接
    imgUrl: '<?php echo $share['imgUrl'];?>', // 分享图标
    type: '', // 分享类型,music、video或link，不填默认为link
    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
});
});
function tonavigate(lati,longi,hname,addr) {
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
</head>