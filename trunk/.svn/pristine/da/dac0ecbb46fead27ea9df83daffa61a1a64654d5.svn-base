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
<?php $pk= $model->table_primary_key();?>
<!-- Horizontal Form -->
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">查询到的信息</h3>
	</div>
	<!-- /.box-header -->
<!-- form start -->
	<?php 
	echo form_open( Soma_const_url::inst()->get_url('*/*/exchange_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <!-- <?php echo $btn_search; ?> -->
			<!-- 核销清单 -->
			<div class=" col-sm-12 " >
				<table class="table table-striped table-hover">
					<thead>
						<tr role="row">
							<?php foreach($grid_field as $v): ?>
							<th role="row">
								<?php echo $v; ?>
							</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php foreach($grid_field as $k=> $v): ?>
							<td>
								<?php foreach($items as $sk=> $sv): ?>
									<?php if( $k == $sk ) echo $sv; ?>
								<?php endforeach; ?>
							</td>
						<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 col-sm-offset-4">
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <input type="hidden" name="code" value="<?php echo $code; ?>" />
                <input type="hidden" name="cid" value="<?php echo $code_id; ?>" />
                <input type="hidden" name="tid" value="<?php echo $template_id; ?>" />
                <?php echo $button_str; ?>
            </div>
		</div>
		<!-- /.box-footer -->
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
<script>
	$(function(){
		$("#button").click(function(){
			var is_true = '';
			is_true = confirm("你确认要进行该操作吗？");
			if( !is_true ){
				return false;
			}
		});
	});
</script>
</body>
</html>
