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
    <meta name="viewport" content="width=320,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <script src="<?php echo get_cdn_url('public/soma/scripts/jquery.js');?>"></script>
    <script src="<?php echo get_cdn_url('public/soma/scripts/ui_control.js'). config_item('css_debug');?>"></script>
	<script src="<?php echo get_cdn_url('public/soma/scripts/alert.js');?>"></script>
    <!-- 
    <script src="<?php echo get_cdn_url('public/soma/scripts/lazyload.js');?>"></script>
     -->
    <link href="<?php echo get_cdn_url('public/soma/styles/global.css'). config_item('css_debug');?>" rel="stylesheet">
    <link href="<?php echo get_cdn_url('public/soma/styles/default.css'). config_item('css_debug');?>" rel="stylesheet">
    <link href="<?php echo get_cdn_url('public/soma/styles/theme.css'). config_item('css_debug');?>" rel="stylesheet"> 
    <?php if(isset($langDir) && $langDir == 'english' ): ?>
        <link href="<?php echo get_cdn_url('public/soma/styles/en_v1.css');?>" rel="stylesheet">
    <?php endif; ?>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title><?php echo $title;?></title>
     <?php echo $statistics_js;?>
</head>

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

