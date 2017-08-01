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
<script src="/public/chat/public/scripts/viewport.js"></script>
<script src="/public/chat/public/scripts/jquery.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/public/chat/public/js/sha1.js"></script>

<link href="/public/chat/public/style/page.css?v=6" rel="stylesheet">
<link href="/public/chat/public/style/mycss.css?v=6" rel="stylesheet">
</head>
<body>
<Br><Br><Br>
<div align="center" style="width:600px; line-height:50px; margin:0 auto; background:#fff;">
    <label>查询code</label>
    <br>
	cardid:
    <input type="text" name="ccardid"> 
    code: 
    <input type="text" name="ccode">
      <input type="submit" name="csub" value="提交">
	  <div style="clear:both;word-wrap:break-word; padding:0 30px" class="cclear">&nbsp;</div>
</div>
<Br><Br><Br>
<div align="center" style="width:600px; line-height:50px; margin:0 auto;background:#fff;">
    <label>核销card<br>
    cardid: 
    <input type="text" name="dcardid"> 
    code:
    <input type="text" name="dcode">

      <input type="submit" name="dsub" value="提交">
  </label>
</div>
<script>
$('input[name=csub]').click(function(){
    $.get('/index.php/chat/hoteladmin/comsume',{'ccardid':$('input[name=ccardid]').val(),'ccode':$('input[name=ccode]').val()},function(d){
	    if(d){
		    $('input[name=dcardid]').val($('input[name=ccardid]').val());
			$('input[name=dcode]').val($('input[name=ccode]').val());
			$('.cclear').html(d);
		}
		else {
		    alert('查询失败');
		}
	});
});

$('input[name=dsub]').click(function(){
    if(confirm('确定要核销这张卡券吗？')){
		$.get('/index.php/chat/hoteladmin/comsume',{'dcardid':$('input[name=dcardid]').val(),'dcode':$('input[name=dcode]').val()},function(d){
			alert(d);
		});
	}
});
</script>
</body>
</html>