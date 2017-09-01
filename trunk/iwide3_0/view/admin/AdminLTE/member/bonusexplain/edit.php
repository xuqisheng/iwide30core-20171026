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
		<h3 class="box-title">积分说明</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <div class="row">
		        <div class="col-xs-12">
			        <label>标题:</label>
			        <input type="text" name="bonus_rule_des_title_1" value="<?php if(isset($bonus_rule_des_title_1)) echo $bonus_rule_des_title_1;?>">
			    </div>
		    </div>
		    <div class="row">
		        <div class="col-xs-12">
			        <textarea name="bonus_rule_des_content_1" class="form-control" rows=8 cols=100><?php if(isset($bonus_rule_des_content_1)) echo $bonus_rule_des_content_1;?></textarea>
			    </div>
		    </div>
		    &nbsp;&nbsp;
		    <div class="row">
		        <div class="col-xs-12">
			        <label>标题:</label>
			        <input type="text" name="bonus_rule_des_title_2" value="<?php if(isset($bonus_rule_des_title_2)) echo $bonus_rule_des_title_2;?>">
			    </div>
		    </div>
		    <div class="row">
		        <div class="col-xs-12">
			        <textarea name="bonus_rule_des_content_2" class="form-control" rows=8 cols=100><?php if(isset($bonus_rule_des_content_2)) echo $bonus_rule_des_content_2;?></textarea>
			    </div>
		    </div>
		    &nbsp;&nbsp;
		    <div class="row">
		        <div class="col-xs-12">
			        <label>标题:</label>
			        <input type="text" name="bonus_rule_des_title_3" value="<?php if(isset($bonus_rule_des_title_3)) echo $bonus_rule_des_title_3;?>">
			    </div>
		    </div>
		    <div class="row">
		        <div class="col-xs-12">
			        <textarea name="bonus_rule_des_content_3" class="form-control" rows=8 cols=100><?php if(isset($bonus_rule_des_content_3)) echo $bonus_rule_des_content_3;?></textarea>
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
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
