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
    <meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <script src="<?php echo base_url('public/soma/scripts/jquery.js');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/ui_control.js'). config_item('css_debug');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/alert.js');?>"></script>
    <script src="<?php echo base_url('public/soma/scripts/lazyload.js');?>"></script>
    <link href="<?php echo base_url('public/soma/styles/global.css'). config_item('css_debug');?>" rel="stylesheet">
    <link href="<?php echo base_url('public/soma/styles/command.css'). config_item('css_debug');?>" rel="stylesheet">
    <link href="<?php echo base_url('public/soma/styles/theme.css'). config_item('css_debug');?>" rel="stylesheet">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title><?php echo $title;?></title>
</head>
    <script>
    $(function(){
		$("img.lazy").lazyload();  //惰性加载
	});
    </script>
<style>

        /*颜色控制*/

        /*主色*/
     <?php if(isset($main_color) && !empty($main_color)) { ?>
        .color_main,a.color_main,.btn_void, a.btn_void{color:<?php echo $main_color;?>;}
        .bg_main,a.bg_main,.btn_main,a.btn_main{background:<?php echo $main_color;?>;}
		.bd_main_color,.btn_void, a.btn_void{border-color:<?php echo $main_color;?> !important}
    <?php } ?>

        /*副色*/

    <?php if(isset($sub_color) && !empty($sub_color)) { ?>
        .color_minor,a.color_minor{color:<?php echo $sub_color;?>;}
        .bg_minor,a.bg_minor,.btn_minor,a.btn_minor{background:<?php echo $sub_color;?>;}
    <?php } ?>

</style>

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
