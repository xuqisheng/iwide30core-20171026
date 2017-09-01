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
<script src="/public/chat/public/wall/js/viewport.js"></script>
<script src="/public/chat/public/wall/js/jquery.js"></script>
<script src="/public/chat/public/wall/js/ui_control.js"></script>
<script type="text/javascript" src="/public/chat/public/qqface/jquery.qqFace.js"></script>

<link href="/public/chat/public/wall/css/global.css" rel="stylesheet">
<link href="/public/chat/public/wall/css/ui.css" rel="stylesheet">
<title>微信公众墙</title>
</head>
<link href="/public/chat/public/wall/css/ui_chat_box.css" rel="stylesheet">
<body>
<div class="chat_content_box"><div style="text-align:center; height:50px; line-height:50px">请您开始发言吧！</div></div>
<div class="reply_box">
	<div class="reply_box_content">
        <div class="ui_btn send_btn">发送</div>
        <div class="ui_btn face_btn"><img src="/public/chat/public/wall/images/ico/btn03.png" /></div>
        <div class="reply_input"><textarea id="msg" maxlength="100" rows="1" oninput="changerow(this)"></textarea></div>
    </div>
</div>
<div style="padding-top:18%"></div>

<script>
var userinfo=<?php echo json_encode($fans);?>;
var chat=<?php echo json_encode($chat);?>;


function showmsg(uimg,uname,male,msg){
	male = male=='1'?'ui_male':'ui_female';
	msg = replace_em(msg);
	tohtml = '<div class="chat_item _left"><div class="user_img"><img src="'+uimg+'" /></div>';
	tohtml = tohtml+'<div class="user_name '+male+'">'+uname+'</div>';
	tohtml = tohtml+'<div class="user_chat ui_style_default"><div class="ui_tip"></div><div>'+msg+'</div></div></div>';
	$('.chat_content_box').append(tohtml);
	
	document.getElementById("msg").rows=1;
	scrollBottom();

}

if(chat){
	for(var i=0;i<chat.length;i++){
		tmsg = chat[i].msg;
		showmsg(userinfo.headimgurl,userinfo.nickname,userinfo.sex,tmsg);
	}
}


$(function(){
    $('.send_btn').click(function(){
	    var msg = $('#msg').val();
		if(!msg){return false;}
		$.get('',{msg:msg,submit:1},function(d){
		    if(d!=1){
			    showmsg(userinfo.headimgurl,userinfo.nickname,userinfo.sex,msg+' <span style="color:#FF0000">发送失败</span>');
			}
		});
		$('#msg').val('');
		showmsg(userinfo.headimgurl,userinfo.nickname,userinfo.sex,msg);
	});
	
	$(function(){
		$('.face_btn').qqFace({
			assign:'msg',
			path:'/public/chat/public/qqface/face/'
		});
	});
	
});

</script>
</body>
</html>
