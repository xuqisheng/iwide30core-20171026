<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, user-scalable=no">
<title>后台登录页面</title>
<style type="text/css">
<!--
body {
	background-color: #003366;
	margin-top: 0px;
	font-size:12px
}
-->
</style></head>
<?php $to = isset($_GET['to'])?$_GET['to']:'';?>
<script language="JavaScript" type="text/javascript">
function checkform(obj){
	if (obj.name.value == "" ){
		alert("请输入正确的openid！");
		obj.name.focus();
		return false;
	}
}

function tourl(){
    location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo $appid;?>&redirect_uri='+encodeURIComponent('http://'+document.domain+'/index.php/chat/wxdata/getcode?to='+encodeURIComponent('<?php echo $to;?>'))+'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
}
</script>  
<body onload="javascript:document.all.name.focus();" style="background-color:#006699; padding:0px;">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="600" valign="middle">
			<table width="300" height="182" border="0" align="center" cellpadding="4" cellspacing="0" style="border:1px solid #1F89A8; padding:1px;">
				<form method="post" action="/index.php/chat/member/logincheck" name="frmlogin"  onsubmit="return checkform(this);">
				<tr>
						<td height="28" colspan="2" bgcolor="#003399"><span style="font-size:14px; font-weight:bold; color:#FFFFFF;">&nbsp;&nbsp;管理登录</span>&nbsp;</td>
				</tr>
				<tr>
						<td width="132" height="28" align="right" bgcolor="#EFEFEF">密码：</td>
						<td width="338" bgcolor="#EFEFEF"><input name="name" id="name" type="text"  style="width: 170px;"></td>
				</tr>
				<tr>
						<td height="28" bgcolor="#EFEFEF">&nbsp;</td>
						<td bgcolor="#EFEFEF"><input type="submit" name="submit" value="登 录" class="button" id="submit" /> <input type="reset" name="Reset" value="重 置" class="button" /></td>
				</tr>
				<tr>
				  <td height="28" bgcolor="#EFEFEF">&nbsp;</td>
				  <td bgcolor="#EFEFEF"><input type="button" onclick="tourl()" name="Submit" value=" 微 信 登 录 " /></td>
				  <?php if($csrf){echo '<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['hash'].'" />';}?>
				  </tr>
				</form>
			</table>
			<p align="center">版权所有 &copy; 2013-2015 信息驿站 All Rights Reserved.</p>
		</td>
	</tr>
</table>
</body>
</html>
