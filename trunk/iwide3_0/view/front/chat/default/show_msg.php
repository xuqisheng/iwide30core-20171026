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
var f_id = '<?php echo $finfo['fid'];?>';var u_me = '<?php echo $userinfo['openid'];?>';
var fnickname='<?php echo $finfo['nickname'];?>';var flogo='<?php echo $finfo['logo'];?>';var nickname='<?php echo $userinfo['nickname'];?>';var logo='<?php echo $userinfo['logo'];?>';
var ma = '',mi = '';
var timestamp = 0;

function showmsg(p,l,n,m,lo,ai){
    pos=p=='l'?'_left':'_right';
	
	s=p=='l'?'ui_style_default':'ui_style_green';
	
	m = replace_em(m);
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

    html = '<div class="chat_item '+pos+'"><div class="user_img"><img src="'+l+'" /></div><div class="user_name">'+n+'</div>';
	
    html = html+'<div class="user_chat '+s+'"><div class="ui_tip"></div><div>'+m+'</div></div></div>';
	
    if(lo=='max'){
	
	    $('.chat_content_box').append(html);
		
	}
	else {
	
	    $('.chat_content_box').prepend(html);
	
	}
	
	document.getElementById("msg").rows=1;
	$('.red_packget').parents('.user_chat').addClass('ui_style_red');
	scrollBottom();
	preimg();
}

$(function(){
    $.get('/index.php/chat/msg/m',{iad:f_id},function(d){
	    for(var i=d.length-1;i>=0;i--){
			if(d[i].openid==u_me){
			    p='r';l=logo;n=nickname;
			}
			else
			{
			    p='l';l=flogo;n=fnickname;
			}
			m=d[i].msg;ai=d[i].id;
			showmsg(p,l,n,m,'max',ai);
		}
	},'json');
	
	$('.send_btn').click(function(){
	    var msg = $('#msg').val();
		if(!msg){return false;}
		$.get('',{msg:msg,fid:f_id,submit:1},function(d){
		    if(d==1){}
			else{showmsg('r',logo,nickname,'发送失败','max','');}
		});
		$('#msg').val('');				
		showmsg('r',logo,nickname,msg,'max','');
	});
	
	setInterval(function(){
	    $.get('/index.php/chat/msg/m',{iad:f_id,ma:ma},function(d){
		    for(var i=d.length-1;i>=0;i--){
			    if(d[i].openid!=u_me){
					p='l';l=flogo;n=fnickname;
					m=d[i].msg;ai=d[i].id;
					showmsg(p,l,n,m,'max',ai);
				}
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
	showmsg('r',logo,nickname,msg,'max','');
	$(window.frames["uploads"].document).find("form").submit();
}

function qfupload(d){
	if(d){
	    var msg = '<img src="'+d.file_url+'" class="preimg" />';
		var upimg = d.file_url;
		$.get('',{msg:msg,fid:f_id,submit:1},function(d){
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
