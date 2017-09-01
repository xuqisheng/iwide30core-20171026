<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/ionicons.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/dist/css/skins/_all-skins.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<style type="text/css">
	.derived{float:right;border:1px solid #999;padding:4px 10px;border-radius:4px;margin-right:2%;}
	.numbe_list{padding:10px 30px;}
	.table-striped>tbody>tr>td,.table-striped>thead>tr>th,.center{text-align:center;}
	.search{width:300px;margin:0 10px;}
	.detailes{color:rgba(191,41,239,1.00);text-decoration:underline;}
	/*.j_fliex{position:fixed;top:0px;left:0px;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;}*/
</style>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="j_fliex">
    <div class="box box-info" style="width:100%;background:#fff;border: none;">
        <div class="box-body pad">
            <div class="row">
            	<div class="loadings" style="display:none;">加载中。。。</div>
                <div class="col-xs-12 containers">
                    <div class="box">
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <?php foreach ($fields_config as $k=> $v):?>
                                        <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
                                            <?php echo $v['label'];?>
                                        </th>
                                    <?php endforeach;?>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <?php foreach ($fields_config as $k=> $v):?>
                                        <th><?php echo $v['label'];?></th>
                                    <?php endforeach;?>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script>
var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var selected = [];
$(function() {
	$('#example2').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
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
        "rowCallback": function(row, data ) {
            if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                $(row).addClass('bg-gray');
            }
        },
        "columns": columnSet,
        "data": dataSet,
        "searching": true
	});
});
</script>
</body>
</html>
