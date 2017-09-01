<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src="/public/chat/public/wall/js/jquery.js"></script>
<script src="/public/chat/public/wall/js/ui_control.js"></script>
<script type="text/javascript" src="/public/chat/public/qqface/jquery.qqFace.js"></script>
<link href="/public/chat/public/wall/css/global.css" rel="stylesheet">
<title><?php echo $wall['title'];?></title>
</head>
<style>
body,html{height:100%; background:url(/public/chat/public/wall/images/bg.jpg) #356ba6 no-repeat left bottom; background-size:auto 100%; font-family:"黑体",'微软雅黑'; background-attachment:fixed}
.right{width:350px; background:rgba(0,0,0,0.4); height:100%; position:fixed;right:0;text-align:center;}
.right .logo{ width:140px; height:140px; background:#fff; display:inline-block; overflow:hidden; line-height:220px;border-radius:50%; margin-bottom:10px; margin-top:30px;}
.right .saoma img{width:55%; padding-top:20px;}
.chat_content_box{ padding:30px; overflow:hidden}
.chat_content_box ._left{ float:left}
.chat_content_box .user_name{ color:#fff;text-indent:20px;margin-top:5px; font-size:28px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis; width:7em;}
.chat_content_box .user_chat{border-radius:5px; padding:20px 20px; background:#ffffff; display:inline-block; position:relative; text-align:justify;word-break:break-all; margin:10px 17px; max-width:60%;}
.chat_content_box .user_chat div{font-size:36px;}
.chat_content_box .ui_tip{ width:13px; height:20px; position:absolute; top:15px; background-repeat: no-repeat; background-size:100% 100%;right:100%;background-image:url(/public/chat/public/wall/images/ico01.png)}
.chat_content_box:nth-child(2n) .user_chat{ background-color:#dcd4fe;}
.chat_content_box:nth-child(2n) .ui_tip{background-image:url(/public/chat/public/wall/images/ico02.png);}
.chat_content_box .user_chat img{ max-width:100%; width:auto; font-size:0}
.chat_content_box .user_chat img.face_img{ width:30px;}

.chat_content_box .user_img{margin-right:2%;width:100px; height:100px; border-radius:50%; overflow:hidden; border:2px solid #fff;}
.chat_content_box .user_img img{width:100px; height:100px;}
</style>
<body>
<div class="page">
	<div class="right">
    	<div class="logo"><img src="<?php echo $wall['logo'];?>"></div>
        <div style="color:#fff;font-size:20px;"><?php echo $wall['title'];?></div>
        <div style="color:#fdd826;font-size:28px; margin:50px 0 10px 0">扫码参与</div>
        <div style="color:#fdd826;font-size:60px;">微信上墙</div>
    	<div class="saoma"><img src="<?php echo $wall['qrcode'];?>"></div>
    </div>	
</div>
<script>
var hid=0,id=0;
function showmsg(uimg,uname,msg,ai){
    ai = parseInt(ai);
	if(ai>id){
	    id = ai;
	}
	else {
	    return false;
	}
	msg = replace_em(msg);
	h = '<div class="chat_content_box"><div class="_left"><div class="user_img"><img src="'+uimg+'" /></div></div><div class="user_name">'+uname+'</div>';
	h = h+'<div class="user_chat"><div class="ui_tip"></div><div>'+msg+'</div></div></div>';
    
	$('.page').append(h);
	scrollBottom();
}

setInterval(function(){
	$.get('',{act:'on',hid:hid},function(d){
		if(d!=''){
			for(var i=0;i<d.length;i++){
				showmsg(d[i].headimgurl,d[i].nickname,d[i].msg,d[i].id);
				hid = d[i].id;
			}
		}
	},'json');
},300);
</script>
</body>
</html>
