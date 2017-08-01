<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
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
        <!-- Main content -->
        <section class="content">

<?php echo $this->session->show_put_msg(); ?>
<!-- Horizontal Form -->
<div class="box box-info">

    <div class="tabbable "> <!-- Only required for left/right tabs -->

<!-- form start -->

        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
			<?php echo form_open( site_url('hotel/orders/hotel_order_exp'), array('id'=>'code_form','class'=>'form-inline','enctype'=>'multipart/form-data' ) ); ?>
                <div class="box-body">
					<div class="form-group  has-feedback">
						<label class="control-label">下单时间</label>
						<input type="text" name="bb" value='' class="form_datetime form-control" data-date-format="yyyy-mm-dd" />
						<label class="control-label">至</label>
						<input type="text" name="be" value='' class="form_datetime form-control" data-date-format="yyyy-mm-dd" />
					</div>
					<div class="form-group  has-feedback">&nbsp;
					</div>
                </div>
                    <div class="box-footer ">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-info pull-right">导出</button>
                        </div>
                    </div>
                    <!-- /.box-footer -->
				<?php echo form_close() ?>
                <!-- /.box-body -->

            </div><!-- /#tab1-->
            
        </div><!-- /.tab-content -->

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
<script>
$(".form_datetime").datepicker({format: 'yyyy-mm-dd 00:00:00'});
$(function () {
});
</script>
</body>
</html>
