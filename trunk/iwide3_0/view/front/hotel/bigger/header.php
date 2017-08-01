<?php header('Cache-Control: public');?><!doctype html>
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
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<!-- 全局控制 -->
<?php echo referurl('css','global.css',1,$media_path) ?>
<?php echo referurl('css','service.css',1,$media_path) ?>
<?php echo referurl('css','tmp.css',1,$media_path) ?>


<?php echo referurl('js','zepto.js',1,$media_path) ?>
<?php echo referurl('js','vue.min.js',1,$media_path) ?>
<?php echo referurl('js','global.js',1,$media_path) ?>
<?php echo referurl('js','ui_control.js',1,$media_path) ?>
<!-- end -->
<title><?php echo $pagetitle;?></title>
<?php include 'wxheader.php' ?>
<script type='text/javascript'>
      var _vds = _vds || [];
      window._vds = _vds;
      (function(){
        _vds.push(['setAccountId', '9035a905d6d239a4']);
        (function() {
          var vds = document.createElement('script');
          vds.type='text/javascript';
          vds.async = true;
          vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(vds, s);
        })();
      })();
</script>
<script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
<body>
<div class="" a="load"></div>