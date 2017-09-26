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
  <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,user-scalable=no">
  <title>维护通知</title>
  <style>
    *{
      margin: 0;
      padding: 0;
    }
    .maintain-icon {
      padding-top: 42px;
      text-align: center;
      padding-bottom: 16px;
      width: 53.125%;
      margin: 0 auto;
      position: relative;
    }
    .maintain-icon:before{
      content: '';
      position: absolute;
      bottom: 0;
      width: 100%;
      left: 0;
      height: 1px;
      -webkit-transform: scale(1, .5);
      transform: scale(1, .5);
      background-color: #c2c2c2;
    }
    .icon11{
      display: inline-block;
      width: 48.5294%;
      padding-bottom: 37.647%;
      background-size: 100%;
      background-repeat: no-repeat;
      background-image: url(data:image/png;base64,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n97nSGYuCIMZYvKEGltQ0lWbSLDJxzQCBmgD7vNZyyAamjmKKvwoqUh73tIel53tR9ACt7DdJX9rKy/Si6ahPtQgnCipQBK3867LqeObudq/y13uksJ7ujDJTJ6lPx25mPpUJ/5Mq0plr+280KOkItWKyBPnRZoqZ75XnZok0oYbGc50TU7j2TnS/7PMqcdE18ILp5ej3WSynlUsZxnLWMZyPuFDL20JD/hWHSUglPew0kd5FrO8UPYVfM0GtrHf64jfLWFJFiqQ4nLWfMnbf32ba0uOUlNxIThtD8MDSnugEf/2O1PbW5vom44a0F05OugYjzGX9oFPwmVoyxTXWhL+tgkRhzIXSAliGg7JpLFac0x06UZ5kNn0D8HZA9ThetJZwUEfCoS6tUmhRkkM/woY4Un2soSHAy5xV4yg8STRm1FM5SPW8B3fs4VMtpJNNtvYQQ672MUudpLtaDm+FlUOCOUIdmv69t62s5UtbGQl75PGLTQ2fCg7FYjnAurTkCRa04qWJNO+sLWllaO18dWdHNADXptLNH17b61pRgMSIu4g9sjd24mijKKMooyijKKMooy+oiijKKMooyijKANE6azzGkVZQpTXOcLBVllzRGCEoWzIXLaTWdiy2M44m888+x3+4eEQqYYa8gAAAABJRU5ErkJggg==)
    }
    .title{
      font-weight: normal;
      text-align: center;
      padding-top: .21875em;
      line-height: 4;
    }
    .tip{
      line-height: 1.41667;
      width: 82.5%;
      margin: 0 auto;
      padding-bottom: 4.7917em;
    }
    .btn-area {
      text-align: center;
    }
    .btn-area .btn222{
      text-decoration: none;
      width: 159px;
      height: 35px;
      line-height: 35px;
      display: inline-block;
    }
    .h32{
      font-size: 1rem;
    }
    .h24 {
      font-size: .75rem;
    }
    .h30 {
      font-size: .9375rem;
    }
    .color_main {
      color:#ff9900;
    }
    .bg_main {
      background-color: #ff9900;
    }
    .color_fff,a.color_fff{
      color: #fff;
    }
    .color_555{
      color: #555;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="maintain-icon">
      <i class="icon11"></i>
    </div>
    <h5 class="title h32 color_main">微信支付升级维护通知</h5>
    <div class="tip h24 color_555">亲爱的粉丝： 微信支付系统将于 0:00-1:00升级维护，升级期间暂时无法使用微信支付，对您造成的不便深感抱歉，感谢您的理解和配合!</div>
    <div class="btn-area">
      <a onClick="history.back(-1)" href="javascript:;" class="bg_main btn222 h30 color_fff">稍后支付</a>
    </div>
  </div>
</body>

</html>