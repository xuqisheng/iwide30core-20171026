<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC;?>/js/",
        JS__ROOT:"<?php echo base_url(FD_PUBLIC);?>/js/"
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC);?>/js/wind.js"></script>
<style type="text/css">
    html, body{min-width: 100%;}
    div.dataTables_filter label{text-align:center;width: 50%;}
    .search-wd {  width: 80% !important;  }
    .expot{width: 100%;margin-bottom: 10px;}
    .expot span{display: inline-block;}
    .expot input{width:30%;display: inline-block;}
    table.table-bordered th:last-child, table.table-bordered td:last-child{text-align: center;vertical-align: middle;}
    table.table-bordered td:last-child>.btn-sm{padding:2px 6px;margin: 0 2px;}
    #expot{ display: none;}
    #selects_membeb{display:inline-block;width:auto;vertical-align:middle;margin-right:25px;}
    .table-striped>tbody>tr:nth-of-type(odd) {  background-color: #ffffff;  }
    .color_F99E12 {  margin: 0 5px;  }
    #data-grid_wrapper >.row:first-child{background:#fff;padding:10px;}
    table.dataTable th{background: #f8f9fb;}
    .h_btn_list{display: inline-block}
</style>
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
    $sort_index= $module->field_index_in_grid($default_sort['field'],11);
    $sort_direct = $default_sort['sort'];
    $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    $buttions= '<div class="actives export" id="subbtn" data-action="'.EA_const_url::inst()->get_url('*/memberexport/export').'">导出</div>';	//button之间不能有字符空格，用php组装输出
    $get_param = !empty($_GET)?$_GET:array();
    ?>
    var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
    var dataSet= <?php echo json_encode($result['data']); ?>;
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var buttons = $('<div class="h_btn_list"><?php echo $buttions; ?></div>');
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
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercredit'. DS .'indexjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercredit'. DS. 'indexjs_ajax.php';
?>
<script type="text/javascript">
$(function () {
    Wind.use("artDialog",function (){
        $(document).on('click','.export',function () {
            var div1=$("<div/>",{id:'expot'}),div2=$("<div/>",{class:'expot'}).appendTo(div1),space='&nbsp;';
            $("<span/>").html('购卡时间').appendTo(div2),$("<input/>",{type:'text',name:'start_time',autocomplete:'off',class:'form-control input-sm date-export1'}).appendTo(div2),$("<span/>").html('至').appendTo(div2),$("<input/>",{type:'text',name:'end_time',autocomplete:'off',class:'form-control input-sm date-export1'}).appendTo(div2);

            var onthis = this,url_action=$(this).data('action');
            var content = div1.html();
            var pid = $(this).parent().parent().attr("id");
            art.dialog({
                title:'信息提示',
                width:'27%',
                content:content,
                ok:function (){
                    var start_time = $("input[name='start_time']").val(),end_time = $("input[name='end_time']").val();
                    if(!start_time || start_time==''){
                        art.dialog({title:'提示',content:'请选择开始时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }

                    if(!end_time || end_time==''){
                        art.dialog({title:'提示',content:'请选择结束时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }
                    art.dialog({content:'正在导出...',lock:true,opacity:0.1,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:6});
                    window.location= url_action+'?tp=5&fn=购卡记录报表&bt='+start_time+'&et='+end_time;
                },
                cancel:true,
                follow:onthis,
                okVal:'导出',
                cancelVal:'取消'
            });

            $('.date-export1').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });
            $('.date-export2').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });

        });
    });
});
</script>
</body>
</html>
