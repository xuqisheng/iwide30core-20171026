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
  <title><?php echo $page_title ?></title>
</head>
<?php include 'wxheader.php' ?>

<?php if($_skin_theme == 'white'){ ?>
<link rel="stylesheet" href="<?php echo refer_res('light.css','public/user') ?>">
<?php } else { ?>
<link rel="stylesheet" href="<?php echo refer_res('dark.css','public/user') ?>">
<?php } ?>
<script type="text/javascript">
  window.jfkConfig = {
    interID: '<?php echo $inter_id;?>',
    wxShare: <?php echo json_encode($js_share_config);?>,
};
</script>
