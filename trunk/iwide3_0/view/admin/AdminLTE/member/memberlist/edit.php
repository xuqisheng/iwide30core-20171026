<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">查看会员信息</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal')); ?>
		<div class="box-body">
            <div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">会员ID:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->mem_id;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">姓名:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->name;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">性别:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php if(isset($meminfo->sex) && $meminfo->sex==1) { echo '男'; } else {echo '女';};?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">会员级别:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php if(isset($levels[$meminfo->level])) { echo $levels[$meminfo->level]; } else {echo '普通会员';};?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">手机:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->telephone;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">余额:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->balance;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">积分:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->bonus;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">身份证:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->identity_card;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">邮件地址:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->email;?>" disabled>
				</div>
			</div>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">关联卡号:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="<?php echo $meminfo->membership_number;?>" disabled>
				</div>
			</div>
			<?php if($meminfo->is_login == 1){ ?>
			<div class="form-group ">
				<label for="el_module" class="col-sm-2 control-label">解绑会员登录:</label>
				<div class="col-sm-8">
					<a href="<?php echo EA_const_url::inst()->get_url("*/*/unbinding?ids=$meminfo->mem_id"); ?>">
						<button type="button" class="btn btn-default"><i class="fa fa-close"></i>&nbsp;解绑</button>
					</a>
				</div>
			</div>
			<?php }?>
		</div>
	<?php echo form_close() ?>
</div>

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
</div><!-- ./wrapper -->
<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
</body>
</html>
