<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC).'/'.refer_res('app.css') ?>">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php
    /* 顶部导航 */
    echo $block_top;
  ?>
  <?php
    /* 左栏菜单 */
    echo $block_left;
  ?>
  <div class="content-wrapper">
    <div id="app"></div>
  </div>
  <?php
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
</div>
<div id="scriptArea" data-page-id="depositStatements"></div>
<script type=text/javascript src="<?php echo base_url(FD_PUBLIC).'/'.refer_res('manifest.js') ?>"></script>
<script type=text/javascript src="<?php echo base_url(FD_PUBLIC).'/'.refer_res('vendor.js') ?>"></script>
<script type=text/javascript src="<?php echo base_url(FD_PUBLIC).'/'.refer_res('app.js') ?>"></script>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
</body>
</html>