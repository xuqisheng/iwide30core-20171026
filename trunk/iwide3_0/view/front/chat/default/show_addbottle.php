<?php require_once 'header.php';?>
<?php echo referurl('css','add_bottle.css',2,$media_path) ?>
<script type="text/javascript" src="/public/chat/public/qqface/jquery.qqFace.js"></script>
<script type="text/javascript">
$(function(){
	$('.face_btn').qqFace({
		assign:'message',
		path:'/public/chat/public/qqface/face/'
	});
});
</script>
<body>
<iframe id="submitform" name="submitform" src="" style="display:none"></iframe>
<form id="sendmsg" name="sendmsg" method="post" target="submitform" action="">
<iframe id="uploads" name="uploads" src="/index.php/chat/upload/" style="display:none"></iframe>
<iframe id="uploadico" name="uploadico" src="/index.php/chat/uploadico/?w=200&h=200" style="display:none"></iframe>
<div class="poster_edit">
    <div class="poster_edit_box">
        <div class="poster_edit_input"><textarea name="message" id="message" maxlength="200" rows="4"></textarea></div>
        <div class="img_add_box ui_img_auto_cut"></div>
    </div>
    <div class="poster_foot_btn">
		<input type="button" value="发送" class="send_btn" /><input type="hidden" name="dosubmit" value="1" />
        <div class="ui_btn face_btn"><img src="<?php echo referurl('img','ico/btn03.png',2,$media_path) ?>" /></div>
        <div class="ui_btn un_name_btn"><img src="<?php echo referurl('img','ico/btn05.png',2,$media_path) ?>" /></div>
    </div>
</div>
<div class="user_diy" style="display:none">
	<div class="user_diy_name">设置我的社交昵称：<input type="text" name="nickname" value="<?php echo $userinfo['nickname'];?>"></div>
	<div class="float">设置我的社交头像：</div>
    <div class="user_diy_img ui_img_auto_cut"><img src="/public/chat/public/attachment/default.jpg" /></div><!--点击修改-->
	<input name="newlogo" id="newlogo" type="hidden" value="<?php echo $userinfo['logo'];?>" />
</div>
</form>

<script type="text/javascript">
var imgtotal=0;
var timestamp = 0;

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
	$('.img_add_box').append('<img src="<?php echo referurl('img','ico/loading.gif',2,$media_path) ?>" id="t'+timestamp+'" />');
	$('.img_add_box').unbind();
	$('.img_add_box').removeClass('img_add_box');
	$(window.frames["uploads"].document).find("form").submit();
}

function qfupload(d){
	if(d){
	    var upimg = d.file_url;
	    $('.poster_edit_box').append('<input name="uploadfile[]" value="'+upimg+'" type="hidden" />');
		$('#t'+timestamp).attr('src',upimg);
		$('img').load(function(){img_auto_cut();});
		if(imgtotal<2){
		    $('.poster_edit_box').append('<div class="img_add_box ui_img_auto_cut"></div>');
		    addimgbox();
		}
	    imgtotal += 1;
	}
	else{alert('上传失败！');}
}
function addimgbox(){
	$('.img_add_box').click(function(){
		if(imgtotal>=3){}
		else {
			$(window.frames["uploads"].document).find("form input").click();
		}
	});
}
addimgbox();


function qfuploadico(d){
	if(d){
		$('#newlogo').val(d.file_url);
		$('.user_diy_img img').attr('src',d.file_url);
		img_auto_cut($('.user_diy'));
	}
	else{alert('上传失败！');}
}
function addicobox(){
	$('.user_diy_img').click(function(){
	    $(window.frames["uploadico"].document).find("form input").click();
	});
}
addicobox();

$('.send_btn').click(function(){
    $('#sendmsg').submit();
});
$('.un_name_btn').click(function(){
    $('.user_diy').show();
	$('.user_diy_img img').attr('src','<?php if($userinfo['logo']){echo $userinfo['logo'];} else {echo '/public/chat/public/attachment/default.jpg';} ?>');
	img_auto_cut($('.user_diy'));
});
$('.face_btn').on('click',function(){
    $('.user_diy').hide();
});
$('.poster_edit_box').on('click',function(){
    $('.user_diy').hide();
});
</script>