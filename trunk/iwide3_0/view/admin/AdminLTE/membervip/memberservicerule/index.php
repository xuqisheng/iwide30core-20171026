<!-- DataTables -->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<style type="text/css">
    html, body{min-width: 100%;}
    table.dataTable{width: 98.8%;}
    .table-striped>tbody>tr:nth-of-type(odd) {  background-color: #ffffff;  }
    .color_F99E12 {  margin: 0 5px;  }
    #data-grid_wrapper >.row:first-child{background:#fff;padding:10px;width: 99.8%;}
    .h_btn_list{display: inline-block}
    .h_btn_list> a.actives {
        display: inline-block;
        width: 100px;
        border: 1px solid #d7e0f1;
        text-align: center;
        padding: 6px 0px;
        border-radius: 5px;
        margin-right: 8px;
    }
    div.dataTables_paginate ul.pagination{margin: 2px 2%;}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">显示设置</h4>
            </div>
            <div class="modal-body">
                <div id='cfg_items'>
                    <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>

                    </form></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="wrapper">

    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>
    <div style="color:#92a0ae;">
        <div class="over_x">
            <div class="content-wrapper">
                <div class="banner bg_fff p_0_20">
                    <?=$breadcrumb_array['action']?>
                </div>
                <div class="contents">
                    <?=$this->session->show_put_msg()?>
                    <div class="box-body" style="margin-top: 18px;">
                        <table id="data-grid" class="table-bordered table-striped table-condensed dataTable no-footer">
                            <thead class="bg_f8f9fb form_thead">
                            <tr class="bg_f8f9fb form_title">
                                <?php foreach ($fields_config as $k=> $v):?>
                                    <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> >
                                        <?php echo $v['label'];?>
                                    </th>
                                <?php endforeach;?>
                            </tr>
                            </thead>
                            <tfoot class="bg_f8f9fb form_thead">
                            <tr class="bg_f8f9fb form_title">
                                <?php foreach ($fields_config as $k=> $v):?>
                                    <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> >
                                        <?php echo $v['label'];?>
                                    </th>
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
     
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<!--日历调用结束-->
<script type="text/javascript">
;!function(){
  laydate({
     elem: '#datepicker'
  })
  laydate({
     elem: '#datepicker2'
  })
}();
</script>
<script type="text/javascript">
    <?php
    $sort_index= $module->field_index_in_grid($default_sort['field'],3);
    $sort_direct= $default_sort['sort'];
    $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    $buttions= '<a class="actives" href="'.EA_const_url::inst()->get_url("*/*/add").'"><div class="actives pointer" id="subbtn">新增规则</div></a>';	//button之间不能有字符空格，用php组装输出
    $buttions = '';
    $get_param = !empty($_GET)?$_GET:array();
    ?>
    var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
    var dataSet= <?php echo json_encode($result['data']); ?>;
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var buttons = $('<div class="h_btn_list" style=""><?php echo $buttions; ?></div>');
    var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
    var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
    var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
    var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/index",$get_param); ?>';
    var url_extra= [];
</script>
<?php if($result['total']<$num):?>
    <script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<?php else:?>
    <script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.member.min.js"></script>
<?php endif;?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<?php
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'memberservicerule'. DS .'indexjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'memberservicerule'. DS. 'indexjs_ajax.php';
?>
</body>
</html>
