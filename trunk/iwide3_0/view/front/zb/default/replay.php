<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no">
	<title>直播</title>
	<link type="text/css" href="/public/zb/css/tao.css" rel="stylesheet">
</head>

<body class="full">
	<flex class="bg full absolute" style="justify-content: center;">
		<video webkit-playsinline preload playsinline class="bofangqi absolute" x5-video-player-type="h5" style="top:0px;right:0px;width:100%;">
			<source src='<?php echo $channel['review_url']?>' type="application/vnd.apple.mpegurl" />
			<p class="warning">Your browser does not support HTML5 video.</p>
		</video>
	</flex>
	<div class="full hid">
		<img class="full" style="object-fit: cover;-webkit-object-fit:cover;" src='<?php echo $channel['head_img']?>'>
	</div>
	<div class="video_action" style="width: 5rem;margin: auto;position: absolute;margin-top: -2.5rem;margin-left: -2.5rem;top: 50%;left: 50%;">
			<img class="w100" src="/public/zb/img/video_action.png">
		</div>
	<div class="full absolute replay_btn" style="top: 0px;left: 0px;"></div>
</body>
	<script>
		var thevideo = document.querySelector(".bg>video");
		var rbtn = document.querySelector(".replay_btn");
		var hid = document.querySelector(".hid");
		var videoaction = document.querySelector(".video_action");
		document.addEventListener("touchmove",function(e){
			e.preventDefault();
		});
		rbtn.addEventListener("touchstart",function(){
			thevideo.play();
			hid.style.display = "none";
			videoaction.style.display = "none";
		});
	</script>
</html>
