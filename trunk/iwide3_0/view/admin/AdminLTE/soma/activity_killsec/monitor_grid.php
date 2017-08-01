<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- Morris chart -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/raphael-min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.min.js"></script>

<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css' >
<script src='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>
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
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
              <!-- 
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="data-grid" class="table table-bordered table-striped table-condensed"></table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
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

<!--    datatable js start  -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>

var buttons = '<button class="btn btn-warning" onclick="del_lock(1)"><i class="fa fa-warning"></i> 删除实例锁</button>'
            + '&nbsp;&nbsp;&nbsp;'
            + '<button class="btn btn-warning" onclick="del_lock(2)"><i class="fa fa-warning"></i> 删除订单锁</button>';
<?php
    // 生成csrf表单数据
    $token_name = $this->security->get_csrf_token_name();
    $token = $this->security->get_csrf_hash();
?>

function del_lock(type) {
  $.ajax({  
      type: "POST",
      url: "<?php echo Soma_const_url::inst()->get_url('*/*/del_killsec_lock'); ?>", 
      data: {
        <?php echo $token_name; ?>: "<?php echo $token; ?>",
        type: type
      },
      dataType:'json',
      success: function(data)
      {
          alert(data.message);
      } 
  });
}

$(document).ready(function() {
    $('#data-grid').DataTable( {
    	aLengthMenu: [20,50,100,200],
		iDisplayLength: 20,
        data: <?php echo json_encode($data_set); ?>,
        columns: <?php echo json_encode($column_set); ?>,
        language: {
			sSearch: "搜索",
			lengthMenu: "每页_MENU_条记录",
			zeroRecords: "找不到任何记录. ",
			info: "第_PAGE_/_PAGES_页，从_START_到_END_ ，共_TOTAL_条",
			infoEmpty: "",
			infoFiltered: "(原_MAX_条)",
			paginate: {
				sNext: "下一页",
				sPrevious: "上一页",
			}
		},
		searching: true,
    } );

    $("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );

} );

/** gridjs end **/
 
</script>
</body>
</html>
