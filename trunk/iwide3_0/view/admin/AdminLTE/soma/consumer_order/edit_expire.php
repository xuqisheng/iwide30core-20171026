<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.css'>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js'></script>
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
	echo form_open( Soma_const_url::inst()->get_url('*/*/expire_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>
		<div class="box-body">
            <!-- <?php echo $btn_search; ?> -->
			<!-- 核销清单 -->
			<div class=" col-sm-12 " >
				<table class="table table-striped table-hover">
					<thead>
						<tr role="row">
							<th>序号</th>
							<?php foreach($grid_field as $v): ?>
							<th role="row">
								<?php echo $v; ?>
							</th>
							<?php endforeach; ?>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($items as $k=>$v): ?>
							<tr>
								<td><?php echo $i;$i++;?></td>
								<?php foreach($grid_field as $sk=> $sv): ?>
									<td>
										<?php echo $v[$sk]; ?>
									</td>
								<?php endforeach; ?>
								<td>
									延期至：
									<input type="type" class="expireTime" name="new_time[]" value="" placeholder="时间不能少于当前有效期">
									<input type="hidden" name="old_time[]" value="<?php echo $v['expiration_date'];?>">
									<input type="hidden" name="pid[]" value="<?php echo $v['product_id'];?>">
									<!-- <input type="submit" name="" class="button" otime="<?php echo $v['expiration_date'];?>" pid="<?php echo $v['product_id'];?>" value="修改"> -->
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer ">
            <div class="col-sm-4 ">
                <!-- <input type="hidden" id="oldTime" name="old_time" value=""> -->
                <!-- <input type="hidden" id="newTime" name="new_time" value=""> -->
                <!-- <input type="hidden" id="pId" name="pid" value=""> -->
                <!-- <button type="reset" class="btn btn-default">清除</button> -->
                <input type="submit" id="button" class="btn btn-info" value="修改" />
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
			is_true = confirm("你确认要进行该操作吗？请仔细确认修改信息。");
			if( !is_true ){
				return false;
			}
		});
	});
</script>
<script type="text/javascript">
$(function(){
  $(".expireTime").datetimepicker({
  	format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
  });
})
</script>
</body>
</html>
