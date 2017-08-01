<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<style>
.content-wrapper, .right-side, .main-footer{margin-left:0;}
</style>
<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
//echo $block_left;
?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header"><!-- 
          <h1>
            404 Error Page
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
      -->
        </section>
    <div style="height:100px;"></div>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red"> </h2>
            <div class="error-content">
                <h3><i class="fa fa-warning text-red"></i> 无权限访问！</h3>
				<p><?php //if(ENVIRONMENT=='development') 
				if($_SERVER['HTTP_HOST']== 'ta.iwide.cn') print_r($this->session->get_admin_actions()); ?></p>
                <p> 您无权进行此操作，您可以：<ol>
				  <li>联系系统管理员申请权限。</li>
                  <li><a href="<?php echo isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']: EA_const_url::inst()->get_default_admin(); ?>">返回 上一步</a></li>
                  <li><a href="<?php echo EA_const_url::inst()->get_default_admin(); ?>">返回 个人面板</a></li>
				  <li>或 <a href="<?php echo EA_const_url::inst()->get_logout_admin(); ?>" style="color:red;"> 退出登陆</a></li>
                </ol></p>
            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
      
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
//require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
      
</div><!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
<script>
$('header nav').html('<div class="pull-right" style="margin:10px 20px 0 0;"><a href="<?php echo EA_const_url::inst()->get_logout_admin(); ?>" class="btn btn-default bg-red"><i class="fa fa-sign-out"></i> 退出</a>');
</script>
</body>
</html>
