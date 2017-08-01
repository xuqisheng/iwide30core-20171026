<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!-- viewport 后面加上 minimal-ui 在safri 体现效果 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <!-- 隐藏状态栏/设置状态栏颜色 -->
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <!-- uc强制竖屏 -->
  <meta name="screen-orientation" content="portrait">
  <!-- UC强制全屏 -->
  <meta name="full-screen" content="yes">
  <!-- UC应用模式 -->
  <meta name="browsermode" content="application">
  <!-- QQ强制竖屏 -->
  <meta name="x5-orientation" content="portrait">
  <!-- QQ强制全屏 -->
  <meta name="x5-fullscreen" content="true">
  <!-- QQ应用模式 -->
  <meta name="x5-page-mode" content="app">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui">
  <title>登录</title>
</head>
<link rel="stylesheet" href="<?php echo refer_res('app.css','public/user') ?>">
<body>
  <div id="app">
  </div>
  <div id="scriptArea" data-page-id="login"></div>
</body>
<script type=text/javascript src="<?php echo refer_res('manifest.js','public/user') ?>"></script>
<script type=text/javascript src="<?php echo refer_res('app.js','public/user') ?>"></script>


</html>
