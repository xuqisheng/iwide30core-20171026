<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="/public/chat/public/shake/viewport.js"></script>
<script src="/public/chat/public/shake/jquery.js"></script>
<script>
window.onerror=doerror;   
function doerror(){   
	arglen=arguments.length;   
	var errorMsg="参数个数："+arglen+"个";   
	for(var i=0;i<arglen;i++){   
		errorMsg+="\n参数"+(i+1)+"："+arguments[i];   
	}
	alert(errorMsg);
	window.onerror=null;   
	return true;   
}  
</script>
<script src="/public/chat/public/shake/shake.js"></script>
<link href="/public/chat/public/shake/global.css" rel="stylesheet">
<title>标题</title>
</head>
<style>
body,html{ background:#2f3034; color:#9b9a9a; height:100%;}
.page{ height:90%; position:relative;}
.top,.bottom{   background-position: bottom center;background-repeat:no-repeat; background-size:50%; height:45%}
.top{background-image:url(/public/chat/public/shake/bg1.jpg)}
.bottom{ background-position: top center;background-image:url(/public/chat/public/shake/bg2.jpg)}
.center .border{background:url(/public/chat/public/shake/bg3.png) repeat-x;height:0.2rem; background-size:auto 100%;}
.count{ position:absolute; bottom:5%; width:100%; text-align:center;}
.imgbox{font-size:0;}
.count span{ background:#37393a; padding:3% 5%; border:1px solid #404145; border-radius:0.3rem; display:inline-block}
.foot{ text-align:center}
</style>
<body>
<div class="page">
    <div class="top"></div>
    <div class="center" style="display:none">
    	<div class="border"></div>
        <div class="imgbox"><img src="<?php if($shake['baner']){echo $shake['baner'];} else {echo '/public/chat/public/shake/bg03.jpg';} ?>"></div>
    	<div class="border"></div>        
    </div>
    <div class="bottom"></div>
    <div class="count" style="display:block"><span>活动暂未开始</span></div>
</div>
<div class="foot"><?php echo $shake['title'];?></div>
<audio src="/public/chat/public/shake/1.mp3"></audio>
</body>
<script>
var openid = '<?php echo $openid;?>';
var allcount = 0;
var newcount = 0;
var timestamp = 0;
var canstart = 0;
var istoshowing=0;
function toshow(){
	var audio = $('audio')[0];
	var _page_h = $('.page').height();
	var _h  = _page_h/3;
	$('.top').height(_h);
	$('.bottom').height(_h);
	$('.center').show();
	audio.play(); 
	window.setTimeout(function(){
		toclose();
	},1000);
}
function toclose(){
	var _page_h = $('.page').height();
	var _h  = _page_h/2;
	$('.center').hide();
	$('.top').height(_h);
	$('.bottom').height(_h);
	istoshowing=0;
}
function count(){
    if(allcount>5){
		//$('.count').find('span').html('您总共摇了'+allcount+'次');//////////功能暂停一下
		$('.count').stop().fadeIn();
	}
}
$('.foot').click(function(){
	count();
})

var isshowstart = 0;
window.setInterval(function(){
	$.get(location.href,{act:'s'},function(d){
	    if(d){
		    if(d.start==0){
				canstart = 0;
			}
			else {
			    if(isshowstart==0){
				    $('.count').find('span').html('活动开始了快摇一摇吧');
				}
			    canstart = 1;
			}
		}
	},'json');
},3000);


window.onload = function() {
    var myShakeEvent = new Shake({
        threshold: 10,
		timeout: 300
    });

    myShakeEvent.start();

    window.addEventListener('shake', shakeEventDidOccur, false);

    function shakeEventDidOccur () {
	    if(canstart==1){
            isshowstart = isshowstart+1;
			if(isshowstart>8){
			    isshowstart=3;
			}
			
			if(istoshowing==0){
			    istoshowing=1;
			    toshow();
			}
			
			newtimestamp = parseInt(Date.parse(new Date())/1000);
			newcount = newcount + 1;
			
			if(newtimestamp - timestamp>2){
				$.get(location.href,{act:'m','openid':openid,'newcount':newcount},function(d){
					if(d){
						allcount = parseInt(d.dotimes);
					}
				},'json');
				timestamp = newtimestamp;
				newcount = 0;
			}
			
			allcount = allcount+1;
			count();		
		}
		else {
		    if(isshowstart>1){
		        $('.count').find('span').html('活动已经停止');
				setTimeout(function(){$('.count').find('span').html('活动暂未开始');},10000);
				isshowstart = 0;
			}
		}

    }
};

</script>
</html>
