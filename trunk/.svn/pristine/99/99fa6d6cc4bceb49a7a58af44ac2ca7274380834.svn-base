<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IWIDE 3.0 Admin Panel</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/iCheck/square/blue.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
        <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
    <![endif]-->
</head>

<?php $remember= $this->session->flashdata('remember_account'); ?>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="http://www.iwide.cn"><b><?php echo CORP_NAME; ?></b>&nbsp;&nbsp;v<?php echo VERSION; ?></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">

        <?php if( $msg= $this->session->flashdata('error_msg') ): ?>
            <div class=" callout callout-danger"><i class="icon fa fa-warning"></i> <?php echo $msg ?></div>
        <?php else: ?>
            <p class="login-box-msg">请输入下面账号信息</p>
        <?php endif; ?>

        <form action="<?php echo $form_action ?>" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="username" class="form-control" placeholder="帐号" value="<?php echo ($remember)? $remember: ''; ?>" >
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label> <input type="checkbox" name="remember_account" <?php if($remember) echo 'checked'; ?> > 记住帐号 </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="captcha" value="<?php echo rand('100000', '999999')?>">
                    <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-sign-in"></i> 登录</button>
                </div><!-- /.col -->
            </div>
        </form>
        <div class="social-auth-links text-center">
<!-- 
        <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> 微信同步登录</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> QQ同步登录</a>
 -->
            <div class="pull-right hidden-xs">
                <b>Version</b> <?php echo VERSION; ?>
            </div>
        </div>
        <a href="http://www.iwide.cn" target="_blank" >关于<?php echo CORP_NAME; ?></a><br>
   
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
    
    
<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/iCheck/icheck.min.js"></script>
<script>
$(function () {
    $('.icheck input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
</script>
</body>
</html>
