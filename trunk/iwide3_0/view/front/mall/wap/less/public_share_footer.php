<script>
wx.ready(function(){
	wx.onMenuShareTimeline({
		title: '<?php echo $share["desc"]?>',
		link: '<?php echo $share["link"]?>',
		imgUrl: '<?php echo $share["imgUrl"]?>',
		success: function(){	
		},
		cancel: function() {
		}
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>', 
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    type: '<?php echo $share["type"]?>', 
	    dataUrl: '<?php echo $share["dataUrl"]?>',
	    success: function(){
	    },
	    cancel: function(){
	    }
	});
});
</script>