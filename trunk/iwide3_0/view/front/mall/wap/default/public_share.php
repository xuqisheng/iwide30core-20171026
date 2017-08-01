<script>
wx.ready(function(){
	wx.onMenuShareTimeline({
		title:'<?php echo $share['title']?>',
		link: '<?php echo $share['link']?>',
		imgUrl: '<?php echo $share['imgUrl']?>',
		success: function () {
			
		},
		cancel: function () { 
		}
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $share['title']?>',
	    desc: '<?php echo $share['desc']?>',
	    link: '<?php echo $share['link']?>', 
	    imgUrl: '<?php echo $share['imgUrl']?>',
	    type: '<?php echo isset($share["type"])? $share["type"]: ''; ?>', 
	    dataUrl: '<?php echo isset($share["dataUrl"])? $share["dataUrl"]: ''; ?>',
	    success: function () { 
	    },
	    cancel: function () { 
	       
	    }
	});
});
</script>
