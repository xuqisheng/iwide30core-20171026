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
		<h3 class="box-title">会员模式配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            <table class="table table-bordered table-striped table-condensed dataTable no-footer">
            <thead>
            	<tr>
            		<th>会员模式：</th>
            		<th>
                        <select name="value" >
                            <option value="" > --请选择--</option>
                            <option value="login" <?php if( isset($modelconfig['value']) && $modelconfig['value']=='login'){ ?> selected <?php } ?> > 会员卡登录模式</option>
                            <option value="perfect" <?php if( isset($modelconfig['value']) && $modelconfig['value']=='perfect'){ ?> selected <?php } ?> >会员完善资料模式</option>
                        </select>
                    </th>
            	</tr>
            </thead>
            <thead>
                <tr>
                    <th>同步模式：</th>
                    <th>
                        <select name="sync_config" >
                            <option value="" > --请选择--</option>
                            <option value="1" <?php if( isset($modelconfig['sync_config']) && $modelconfig['sync_config']=='1'){ ?> selected <?php } ?> >随时同步</option>
                            <option value="2" <?php if( isset($modelconfig['sync_config']) && $modelconfig['sync_config']=='2'){ ?> selected <?php } ?> >登录同步</option>
                            <option value="3" <?php if( isset($modelconfig['sync_config']) && $modelconfig['sync_config']=='3'){ ?> selected <?php } ?> >不同步</option>
                        </select>
                    </th>
                </tr>
            </thead>

            </table>
		</div>
		<div class="box-footer ">
            <?php if(!$modelconfig){ ?>
            <button type="submit" class="btn btn-primary">保存</button>
            <?php } ?>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div>
<!-- /.box -->

<!-- Horizontal Form -->
<!-- <div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">微信菜单配置</h3>
	</div>
	<?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
		<div class="box-body">
            
		</div>
		<div class="box-footer ">
            <button type="submit" class="btn btn-primary">保存</button>
		</div>
		<!-- /.box-footer -->
	<?php echo form_close() ?>
</div> -->
<!-- Horizontal Form -->

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
