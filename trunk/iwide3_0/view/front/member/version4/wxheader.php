
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
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
<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>
<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>
wx.onMenuShareTimeline({
    title: '<?php echo $js_share_config['title'];?>', // 分享标题
    link: '<?php echo $js_share_config['link'];?>', // 分享链接
    imgUrl: '<?php echo $js_share_config['imgUrl'];?>', // 分享图标
    success: function () { 
        // 用户确认分享后执行的回调函数
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
    }
});
wx.onMenuShareAppMessage({
    title: '<?php echo $js_share_config['title'];?>', // 分享标题
    desc: '<?php echo $js_share_config['desc'];?>', // 分享描述
    link: '<?php echo $js_share_config['link'];?>', // 分享链接
    imgUrl: '<?php echo $js_share_config['imgUrl'];?>', // 分享图标
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