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
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" type="text/css" href="<?php echo refer_res('app.css', 'SOMAGIFT') ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo refer_res('light.css', 'SOMAGIFT') ?>"/>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>礼包列表</title>
</head>
<body>
<div id="app">
</div>
<div id="scriptArea" data-page-id="packageList"></div>
<script>
    var jfkConfig = {
        wxConfig: <?php echo json_encode($wx_config); ?>,
        wxApiList: [<?php echo $js_api_list; ?>],
        wxMenuHide: [<?php echo $js_menu_hide; ?>],
        wxShare: <?php echo json_encode($js_share_config); ?>,
        interID: '<?php echo $inter_id;?>',
        token: <?php echo json_encode($token)?>
    }
</script>
<script type=text/javascript src="<?php echo refer_res('manifest.js', 'SOMAGIFT') ?>"></script>
<script type=text/javascript src="<?php echo refer_res('vendor.js', 'SOMAGIFT') ?>"></script>
<script type=text/javascript src="<?php echo refer_res('app.js', 'SOMAGIFT') ?>"></script>
</body>
</html>


