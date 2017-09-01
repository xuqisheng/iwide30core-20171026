<?php require_once 'header.php';?>
<?php echo referurl('css','ui_chat_box.css',2,$media_path) ?>
<script type="text/javascript" src="/public/chat/public/qqface/jquery.qqFace.js"></script>
<script type="text/javascript">
$(function(){
	$('.face_btn').qqFace({
		assign:'msg',
		path:'/public/chat/public/qqface/face/'
	});
});
var userinfo=<?php echo json_encode($userinfo);?>;
var bottle=<?php echo json_encode($bottle);?>;
var fromuser=<?php echo json_encode($fromuser);?>;
var chat=<?php echo json_encode($chat);?>;
</script>
<body>
<iframe id="uploads" name="uploads" src="/index.php/chat/upload/" style="display:none"></iframe>
<div class="chat_content_box"></div>

<div class="reply_box">
	<div class="reply_box_content">
        <div class="ui_btn send_btn">发送</div>
        <div class="ui_btn face_btn"><img src="<?php echo referurl('img','ico/btn03.png',2,$media_path) ?>" /></div>
        <div class="reply_input"><textarea id="msg" maxlength="100" rows="1" oninput="changerow(this)"></textarea></div>
        <div class="ui_btn other_btn"><img src="<?php echo referurl('img','ico/btn04.png',2,$media_path) ?>" /></div>
    </div>
    <div class="reply_btn_hidden">
    	<div class="ui_btn camera_btn"><img src="<?php echo referurl('img','ico/btn01.png',2,$media_path) ?>" />照片</div>
    	<div class="ui_btn camera_btn"><img src="<?php echo referurl('img','ico/btn02.png',2,$media_path) ?>" />拍照</div>
    </div>
</div>
<div style="padding-top:18%"></div>
<script type="text/javascript">
var ma = '',mi = '';
var timestamp = 0;

function showmsg(uimg,uname,pos,male,msg,ai){
    ai=parseInt(ai);
	ma=parseInt(ma);
	mi=parseInt(mi);
	
	if(ai){
	    if(ma){
			if(ai>ma){
				ma = ai;
			}
		}
		else{
		    ma = ai;
		}
		if(mi){
			if(ai<mi){
				mi = ai;
			}
		}
		else{
		    mi = ai;
		}
	}

    if(pos=='l'){
	    style = 'default';
	}
	else{
	    style = 'green';
	}
    pos = pos=='l'?'_left':'_right';
	male = male=='1'?'ui_male':'ui_female';
	msg = replace_em(msg);
	tohtml = '<div class="chat_item '+pos+'"><div class="user_img"><img src="'+uimg+'" /></div>';
	tohtml = tohtml+'<div class="user_name '+male+'">'+uname+'</div>';
	tohtml = tohtml+'<div class="user_chat ui_style_'+style+'"><div class="ui_tip"></div><div>'+msg+'</div></div></div>';
	$('.chat_content_box').append(tohtml);
	
	document.getElementById("msg").rows=1;
	scrollBottom();
	preimg();
}
$(function(){
	scrollBottom();
	var _scroll;
	$('textarea').click(function(){
		window.clearTimeout(_scroll);
		_scroll=window.setTimeout(function(){
			$(document).scrollTop($(document).height());
		},300);
	})	
    $('.send_btn').click(function(){
	    var msg = $('#msg').val();
		if(!msg){return false;}
		$.get('',{msg:msg,mbid:bottle.id,submit:1},function(d){
		    if(d!=1){
			    showmsg(userinfo.logo,userinfo.nickname,'r',userinfo.sex,'发送失败','');
			}
		});
		$('#msg').val('');
		showmsg(userinfo.logo,userinfo.nickname,'r',userinfo.sex,msg,'');
	});
	
	if(chat){
	    for(var i=0;i<chat.length;i++){
		    if(chat[i].openid==userinfo.openid){
			    tlogo = userinfo.logo;
				tnickname = userinfo.nickname;
				tpos = 'r';
				tsex = userinfo.sex;
			}
			else {
			    tlogo = fromuser.logo;
				tnickname = fromuser.nickname;
				tpos = 'l';
				tsex = fromuser.sex;
			}
			tmsg = chat[i].msg;
			showmsg(tlogo,tnickname,tpos,tsex,tmsg,chat[i].id);
		}
	}
	
	if(bottle.status>1){$.get('',{isread:bottle.id},function(d){});}
    
	setInterval(function(){
	    $.get('/index.php/chat/msg/msg',{mbid:bottle.id,mid:ma},function(d){
		    if(d!=''){
			    for(var i=0;i<d.length;i++){
					mid = d[i].id;
					showmsg(fromuser.logo,fromuser.nickname,'l',fromuser.sex,d[i].msg,mid);
				}
				$.get('',{isread:bottle.id},function(d){});
			}
		},'json');
		return false;
	},2000);
	
});

function qfupready(d){
	var isIE = /msie/i.test(navigator.userAgent) && !window.opera;       
    var fileSize = 0;        
    if (isIE && !d.files) {    
      var filePath = d.value;    
      var fileSystem = new ActiveXObject("Scripting.FileSystemObject");       
      var file = fileSystem.GetFile (filePath);    
      fileSize = file.Size;
	  type = file.Type;
    } else {   
      fileSize = d.files[0].size;   
	  type = d.files[0].type; 
    }
	if(!fileSize){return false;}
    var size = fileSize / 1024;   
    if(size>3072){ 
      alert("上传文件不能超过3M！");return false;
    }
	if(type.indexOf('image')!=0){
	  alert("请上传图片格式的文件");return false;
	}

    timestamp = Date.parse(new Date());
	var msg = '<img src="<?php echo referurl('img','ico/loading.gif',2,$media_path) ?>" id="t'+timestamp+'" class="preimg" />';
	showmsg(userinfo.logo,userinfo.nickname,'r',userinfo.sex,msg,'');
	$(window.frames["uploads"].document).find("form").submit();
}

function qfupload(d){
	if(d){
	    var msg = '<img src="'+d.file_url+'" class="preimg" />';
		var upimg = d.file_url;
		$.get('',{msg:msg,mbid:bottle.id,submit:1},function(d){
		    if(d==1){
			    $('#t'+timestamp).attr('src',upimg);
				scrollBottom();
				preimg();
			}
		});
	}
	else{alert('上传失败！');}
}
function addimgbox(){
	$('.other_btn').click(function(){
        $(window.frames["uploads"].document).find("form input").click();
	});
}
addimgbox();

</script>
