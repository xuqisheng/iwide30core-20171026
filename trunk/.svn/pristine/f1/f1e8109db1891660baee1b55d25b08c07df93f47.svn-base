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
<script src="/static/scripts/viewport.js"></script>
<script src="/static/scripts/jquery.js"></script>
<script src="/static/scripts/jquery.touchwipe.min.js"></script>
<script src="/static/scripts/common.js?v=1"></script>
<script src="/static/scripts/myjs.js"></script>
<link href="/static/style/global.css" rel="stylesheet">
<link href="/static/style/animate.css?v=1" rel="stylesheet">
<link href="/static/style/page.css?v=2" rel="stylesheet">
<link href="/static/style/mycss.css" rel="stylesheet">
<title>《微信力量》签名版，主编谢晓萍亲自签名，限量发售！</title>
<meta name="description" content="专属于你的《微信力量》，限时抢购！" />
<div id='wx_pic' style='margin:0 auto;display:none;'>
<img src='http://wxsw.chat.iwide.cn/static/images/485132858627203326.jpg' />
</div>
<script type="text/javascript">			
function phptojs(s){
    if(s){return s.replace(/\\x{(.*?)}/g, '\\u$1');}
}
			
function inputcheck(){
    var err = 0;times = 0;
    $('input').each(function(){
	    key = $(this).attr('key')?$(this).attr('key'):'';
		tip = $(this).attr('tip')?$(this).attr('tip'):'';
		ept = $(this).attr('ept')?$(this).attr('ept'):'';
		value = $(this).val();
		times += 1;
		if(value==''){
			if(parseInt(ept)==0){
			    alert(tip+',不能为空.');
				err +=1;
				return false;
			}
		}
		else {
		    if(key){
				var re = eval('/'+phptojs(key)+'/i');
				if(!re.test(value)){
					alert(tip);
					err +=1;
					$(this).focus();
					return false;
				}
			}
		}
	});
	if(err==0){return true;}
	return false;
}
</script>
</head>
<style>
body,html{overflow:auto;-webkit-overflow-scrolling:touch;}
.ac_title{margin-bottom:6%}
.ac_title b{ font-size:1rem;}
.ac_list{ display: block;}
.ac_list ul{ width:90%;}
.ac_list ul li{ text-align:justify; font-size:0.6rem; font-weight:normal !important;}
a.btn{position:absolute; top:3%; right:4%; width:5em}
a.btn span{padding:0.3em 0; font-size:0.55rem;}
</style>
<body>

<div class="share_bg content center">
    <form id="form1" name="form1" onSubmit="return inputcheck();" method="post" target="_top" action="" style="height:100%;">
    <div class="for" style="min-height:65%">
			<div class="ac_title"><b>邮寄地址</b></div>
			<?php 
				foreach($forminput as $v){ 
			    	if($v['isempty']==0 && $v['isshow']==1){
				 		echo '<input type="text" value="'.$subinfo[$v['id']].'" placeholder="'.$v['iname'].'" key="'.$v['fieldmatch'].'" tip="'.$v['errinfo'].'" ept="'.$v['isempty'].'" name="id'.$v['id'].'">';
					}
					else {
						echo '<input type="hidden" value="'.$subinfo[$v['id']].'" placeholder="'.$v['iname'].'" key="'.$v['fieldmatch'].'" tip="'.$v['errinfo'].'" ept="'.$v['isempty'].'" name="id'.$v['id'].'">';
					}
				} 
			?>        
    </div>
	温馨提示：邮寄地址港澳台除外。
	<input name="submit" type="hidden" value="1" /><input name="iid" type="hidden" value="<?php echo $iid;?>" />
	<input name="card_id" type="hidden" value="<?php echo $card_id;?>" />
	<input name="ucode" type="hidden" value="<?php echo $ucode;?>" />
	<div class="floor" style="position:fixed;width:100%; bottom:0"><input class="s_btn" type="submit" name="submit1" value="提交邮寄"></div>
	</form>	
</div>
</body>
</html>