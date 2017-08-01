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
		<h3 class="box-title">充值固定金额</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
		    <?php foreach($charge_amount as $amount=>$additon_amount) {?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>充值金额:</label>
			        <input type="text" class="form-control" name="amount[]" value="<?php echo $amount;?>" placeholder="充值金额">
			    </div>
			    <div class="col-xs-3">
			        <label>额外赠送金额:</label>
			        <input type="text" class="form-control" name="addition_amount[]" value="<?php echo $additon_amount;?>" placeholder="额外赠送金额">
			    </div>
		    </div>
		    <?php } ?>
		    <div class="row">
		        <div class="col-xs-2">
			        <label>充值金额:</label>
			        <input type="text" class="form-control" name="amount[]" placeholder="充值金额">
			    </div>
			    <div class="col-xs-3">
			        <label>额外赠送金额:</label>
			        <input type="text" class="form-control" name="addition_amount[]" value="0" placeholder="额外赠送金额">
			    </div>
		    </div>
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
	<?php echo form_close() ?>
</div>
<!-- /.box -->

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
