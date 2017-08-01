<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/validate.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
<style type="text/css">
    .derived{float:right;border:1px solid #999;padding:4px 10px;border-radius:4px;margin-right:2%;}
    .numbe_list{padding:10px 30px;}
    .table-striped>tbody>tr>td,.table-striped>thead>tr>th,.center{text-align:center;}
    .search{width:300px;margin:0 10px;}
    .detailes{color:rgba(191,41,239,1.00);text-decoration:underline;}
    .j_fliex{position:fixed;top:0px;left:0px;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;}
    .j_fliex_2{position:fixed;top:0px;left:0px;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;}
</style>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php /* 顶部导航 */ echo $block_top; ?>

    <?php /* 左栏菜单 */ echo $block_left; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>活动统计<small></small></h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info">
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
                <div class="box-body">
                    <div class="row">
                        <section class="content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <table id="example1" class="table table-bordered table-striped table-condensed table-hover">
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
                        </section>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?php /* Footer Block @see footer.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php'; ?>
    <?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php'; ?>
</div><!-- ./wrapper -->
<div class="j_fliex" style="display:none;">
    <div class="box box-info" id="div-iframe" style="width:70%;height:50%;margin:12% auto;background:#fff;">
        <div class="box-header">
            <h3 class="box-title">获得明细
                <small></small>
            </h3>
            <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm btn_close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <iframe id="myiframe" src="" style="width:100%;height: 100%;border: navajowhite;"></iframe>
    </div>
</div>

<div class="j_fliex_2" style="display:none;">
    <div class="box box-info" id="div-iframe-2" style="width:70%;height:50%;margin:12% auto;background:#fff;">
        <div class="box-header">
            <h3 class="box-title">使用明细
                <small></small>
            </h3>
            <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm btn_close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <iframe id="myiframe-2" src="" style="width:100%;height: 100%;border: navajowhite;"></iframe>
    </div>
</div>
<?php /* Right Block @see right.php */ require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php'; ?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script>
    var dataSet= <?php echo json_encode($result['data']); ?>;
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var selected = [];
    $(function() {
        $("#example1").DataTable({
            "aLengthMenu": [20,50,100,200],
            "iDisplayLength": 20,
            "bProcessing": true,
            "paging": true,
            "lengthChange": true,
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

        Wind.use("artDialog",function () {
            $(document).on('click', '.send-rw', function (e) {
                e.preventDefault();
                var obj=this,actid=$(this).data('actid'),memid=$(this).data("memid"),openid=$(this).data("openid"),value=$(this).data("value");
                $.getJSON('<?php echo EA_const_url::inst()->get_url('*/*/send_reward')?>', {
                    actid: actid,
                    memid: memid,
                    openid:openid,
                    value:value
                }, function (data) {
                    if (data.status == 1) {
                        $(obj).remove();
                        art.dialog({title:'提示',fixed:true,icon:'succeed',content:data.message,ok:true,cancel:false,time:5});
                    } else {
                        art.dialog({title:'提示',fixed:true,icon:'warning',content:data.message,ok:true,cancel:false,time:5});
                    }
                });
            });
        });
    });
</script>
</body>
</html>
