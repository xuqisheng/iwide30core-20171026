<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css">
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
        JS__ROOT:"<?php echo base_url(FD_PUBLIC) ?>/js/"
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/lib/sea.js"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php
$inter_id= $this->session->get_admin_inter_id();
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
          <?php echo $this->session->show_put_msg(); ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <label><span>开始时间</span><input type="text" name="start_time" autocomplete="off" class="form-control input-sm date" placeholder="<?php echo date('Y-m-01');?>" /></label>
                    <label><span>结束时间</span><input type="text" name="end_time" autocomplete="off" class="form-control input-sm date" placeholder="<?php echo date('Y-m-t');?>" /></label>
                    <button type="button" class="btn btn-default btn-sm bg-green" id="grid-btn-export"><i class="fa fa-tasks"></i>导出</button>
                    <em>注：选择时间段不能大于一个月</em>
                </div>
                <div class="box-body">
                  <table id="data-grid" class="table table-bordered table-striped table-condensed table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($fields_config as $k=> $v):?>
                            <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> >
                                <?php echo $v['label'];?>
                            </th>
                            <?php endforeach; ?>
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
<script type="text/javascript">
<?php
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];
/*<div class="dataTables_filter"><label>搜索<input type="search" class="form-control input-sm" placeholder="" aria-controls="data-grid"></label></div>*/
$buttions= '<label style="width: 100%"><span>累计消息推送人数:'.$result['send_total'].'人</span>';
$buttions.= '<span style="padding-left: 15px;">累计注册人数:'.$reg_count.'人</span>';	//button之间不能有字符空格，用php组装输出
$buttions.= '<span style="padding-left: 15px;">数据统计截止至:'.$endtime.'</span></label>';	//button之间不能有字符空格，用php组装输出

/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
?>
var buttons = $('<div class="btn-group" style="display: block; margin-top: 10px;"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_bind= '<?php echo EA_const_url::inst()->get_url("*/*/unbinding"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];
</script>
<?php
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'member'.DS.'memberlist'. DS. 'bindjs.php';
else
    require_once VIEWPATH. $tpl .DS .'member'.DS.'memberlist'. DS. 'bindjs_ajax.php';
?>

<script type="text/javascript">
    $(':input[name=start_time]').datetimepicker({
        format:'Y-m-d',
        lang:'ch',
        timepicker:false,
        scrollInput:false
    });
    $(':input[name=end_time]').datetimepicker({
        format:'Y-m-d',
        lang:'ch',
        timepicker:false,
        scrollInput:false
    });

    $(document).on('click','#grid-btn-export',function () {
        var onthis = this;
        seajs.use([GV.JS__ROOT+'artDialog/src/dialog'], function (dialog){
            var _st=$("input[name=start_time]").val(),_et=$("input[name=end_time]").val();
            if(!_st) {
                var d = dialog({
                    content: '请选择开始时间',btnStyle:'ui-dialog-mini',padding: 10,quickClose:true
                });
                d.show(onthis);
                setTimeout(function () {
                    d.close().remove();
                }, 3000);
                return false;
            }

            if(!_et) {
                var d = dialog({
                    content: '请选择结束时间',btnStyle:'ui-dialog-mini',padding: 10,quickClose:true
                });
                d.show(onthis);
                setTimeout(function () {
                    d.close().remove();
                }, 3000);
                return false;
            }
            var st=_st+' 00:00:00',et=_et+' 23:59:59';
            var st2 = Date.parse(new Date(st)),et2 = Date.parse(new Date(et));
            st2 = st2 / 1000;
            et2 = et2 / 1000;
            var url = "<?php echo EA_const_url::inst()->get_url('*/*/bind_export');?>";
            url=url+'?st='+st2+'&et='+et2;
            window.location.href=url;
        });
    });
</script>
</body>
</html>
