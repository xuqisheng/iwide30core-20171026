<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
          <h1><?php echo $breadcrumb_array['action']; ?>
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
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead><tr><?php 
	    foreach ($fields_config as $k=> $v):
		     ?><th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> ><?php echo $v['label'];?></th><?php 
	    endforeach; ?></tr></thead>
                    
                  </table>
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
<?php 
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
//$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;新增</button>';
$buttions.= '<button type="button" class="btn btn-default btn-sm disabled" id="grid-btn-edit"><i class="fa fa-ambulance"></i>&nbsp;发货</button>';

$buttions.= $js_filter_btn;

//$buttions.= '<button type="button" class="btn btn-default disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
//var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/orders/edit"); ?>';		//跟button对应
//var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*", $this->input->get('filter') ); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
$(document).ready(function() {
/***** gridjs_ajax.php start *****/

var selected= [];
$("#data-grid").DataTable({
	"aLengthMenu": [20,50,100,200],
	"iDisplayLength": 20,
	"bProcessing": true,
	"paging": true,
	"lengthChange": true,
	"ordering": false,
	"order": grid_sort,
	"info": true,
	"autoWidth": false,
	"language": {
		"sSearch": "搜索",
		"lengthMenu": "每页显示 _MENU_ 条记录",
		"zeroRecords": "找不到任何记录. ",
		"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
		"infoEmpty": "",
		"infoFiltered": "(从 _MAX_ 条记录中过滤)",
		"paginate": {
			"sNext": "下一页",
			"sPrevious": "上一页",
		}
	},
	"processing": true,
	"serverSide": true,
	"ajax": {
		"type": 'POST',
		"url": url_ajax,
		"data": {<?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>' }
	},
	"rowCallback": function(row, data ) {
		if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
			$(row).addClass('bg-gray');
		}
	},
	"columns": columnSet,
	//"data": dataSet,
	"searching": false
});

$("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append( buttons );

$('#data-grid tbody').on('click', 'tr', function(){
	var id = this.id;
	var index = $.inArray(id, selected);
	if ( index===-1 ) selected.push( id );
	else selected.splice( index, 1 );

	$(this).toggleClass('bg-gray');
	if(selected.length==1){
		$('#grid-btn-edit').removeClass('disabled').bind('click', selected, function(ev){
			window.location= url_edit+ '?ids='+ ev.data;
		});
		$('#grid-btn-del').addClass('disabled').unbind();	//先清除原有绑定事件
		$('#grid-btn-del').removeClass('disabled').bind('click', selected, function(ev){
			if(confirm('您确定要删除这些数据吗？') ){
				window.location= url_delete+ '?ids='+ ev.data;
			}
		});
		$('#grid-btn-edit').addClass('bg-green');
		$('#grid-btn-del').addClass('bg-red');
		$('button.single-btn').each(function(i,e){$(this).removeClass('disabled').addClass('bg-green');});
		$('#grid-btn-del').attr('title', '批量操作').attr('data-placement','top').tooltip('show');
		
	} else if(selected.length>0){
		$('#grid-btn-edit').addClass('disabled').removeClass('bg-green').unbind();
		$('button.single-btn').each(function(i,e){$(this).addClass('disabled').removeClass('bg-green');});
		//删除事件只需绑定一次，否则删除按钮会多次弹出
	} else {
		$('#grid-btn-edit').addClass('disabled').removeClass('bg-green').unbind();
		$('#grid-btn-del').addClass('disabled').removeClass('bg-red').unbind();
		$('#grid-btn-del').tooltip('hide');
		$('button.single-btn').each(function(i,e){$(this).addClass('disabled').removeClass('bg-green');});
	}
});
/***** gridjs_ajax.php start *****/

<?php echo $js_filter; ?>

<?php if( isset($fields_config) && count($fields_config) > config_item('grid_wide_columns') ): ?>
$("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
<?php endif; ?>

});
</script>
</body>
</html>
