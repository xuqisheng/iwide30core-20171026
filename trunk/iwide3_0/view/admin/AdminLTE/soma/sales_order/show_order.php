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

<?php 
  $publics = isset($form['publics']) ? $form['publics'] : '';
  $ps_time = isset($form['ps_time']) ? $form['ps_time'] : '';
  $pe_time = isset($form['pe_time']) ? $form['pe_time'] : '';

  if($publics == '') { $publics = $this->session->get_admin_inter_id(); }
  if($ps_time == '' || $pe_time == '') {
    $ps_time = $pe_time = date('Y-m-d');
  }
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
                  <div>
                    <form action="<?php echo Soma_const_url::inst()->get_url('*/*/*' ); ?>" class="form_inline" method="get" accept-charset="utf-8">
                      <div class="form-group">
                        <div class="input-group col-md-3" style="margin-bottom: 5px;">
                          <div class="input-group-addon">公众号</div>
                          <input type="text" class="form-control" name="publics" value="<?php echo $publics; ?>" id="publics" placeholder="请输入公众号">
                        </div>
                        <div class="input-group col-md-3">
                          <div class="input-group-addon">支付时间</div>
                          <input type="text" class="form-control" name="ps_time" value="<?php echo $ps_time; ?>" id="ps_time" placeholder="请选择">
                          <div class="input-group-addon">至</div>
                          <input type="text" class="form-control" name="pe_time" value="<?php echo $pe_time; ?>" id="pe_time" placeholder="请选择">
                        </div>
                      </div>
                      <div class="button-group">
                        <button type="submit" class="btn btn-sm bg-green"><i class="fa fa-search"></i> 查询</button>
                        <button id="export_data" type="button" class="btn btn-sm bg-green"><i class="fa fa-download"></i> 导出</button>
                      </div>
                    </form>
                  </div>
                  <hr/>
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
		searching: false,
    } );

    $("#ps_time").datepicker({
  		format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left"
  	});	 

  	$("#pe_time").datepicker({
  		format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left"
  	});  

} );

$("#export_data").click(function(){
  window.location = "<?php echo $export_url; ?>";
});

/** gridjs end **/
 
</script>
</body>
</html>
