<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, user-scalable=no">
<title>正在授权</title></head>
<?php $to = isset($_GET['to'])?$_GET['to']:'';?>
<script type="text/javascript">
location.href = 'http://<?php echo $authurl;?>/index.php/wxdata_trans/userinfo_auth?appid=<?php echo $appid;?>&re_url='+encodeURIComponent('http://'+document.domain+'/index.php/wxdata/iwidecode?to='+encodeURIComponent(encodeURIComponent('<?php echo $to;?>')));
</script>