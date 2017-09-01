<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
<!--<script src="--><?php //echo base_url(FD_PUBLIC) ?><!--/js/ajaxForm.js"></script>-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/lib/sea.js"></script>
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
    div.dataTables_filter label{text-align:center;width: 50%;}
    .search-wd {  width: 80% !important;  }
    .expot{width: 100%;margin-bottom: 10px;}
    .expot span{display: inline-block;}
    .expot input[type='text']{width:30%;display: inline-block;}
    .expot input[type='radio']{width:7%;display: inline-block;}
    table.table-bordered th:last-child, table.table-bordered td:last-child{text-align: center;vertical-align: middle;}
    table.table-bordered td:last-child>.btn-sm{padding:2px 6px;margin: 0 2px;}
    #expot{ display: none;}
    .expot-control{margin-top: 10px;}
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $breadcrumb_array['action'];?></h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content" style="position:relative;">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-info">
                    <?php echo $this->session->show_put_msg(); ?>
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $result['title'];?></h3>
                        <span class="box-title" style="font-size: 14px;margin-left: 15px;">是否可领取：<?php echo (isset($result['is_get']) && $result['is_get']=='1')?'是':'否';?></span>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-condensed dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>总领取数量</th>
                                    <th>已使用数量</th>
                                    <th>已过期数量</th>
                                    <th>已核销数量</th>
                                    <th>已转赠数量</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo isset($result['total'])?floatval($result['total']):0;?></td>
                                    <td><?php echo isset($result['use_num'])?$result['use_num']:0; ?></td>
                                    <td><?php echo isset($result['expire_num'])?$result['expire_num']:0; ?></td>
                                    <td><?php echo isset($result['useoff_num'])?$result['useoff_num']:0; ?></td>
                                    <td><?php echo isset($result['giving_num'])?$result['giving_num']:0; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title">优惠券列表</h3>
                    </div>
                <div class="box-body">
                    <div id="expot">
                        <div class="expot">
                            <div class="expot-control">
                                <input type="radio" checked class="time_mode" name="time_mode" value="1" />
                                <span>领取时间</span>
                                <input type="text" name="start_time" autocomplete="off" class="form-control input-sm date-export1" />
                                <span>至</span>
                                <input type="text" name="end_time" autocomplete="off" class="form-control input-sm date-export2" />
                            </div>

                            <div class="expot-control">
                                <input type="radio" class="time_mode" name="time_mode" value="2" />
                                <span>失效时间</span>
                                <input type="text" name="stime" disabled autocomplete="off" class="form-control input-sm date-export3" />
                                <span>至</span>
                                <input type="text" name="etime" disabled autocomplete="off" class="form-control input-sm date-export4" />
                            </div>
                        </div>
                    </div>
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <?php foreach ($fields_config as $k=> $v):?>
                            <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> >
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
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
    <div class="face_film" style="display:none;"></div>
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

$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
?>
<?php if($result['total']<$num):?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<?php else:?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.member.min.js"></script>
<?php endif;?>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script type="text/javascript">
<?php
$sort_index= $module->field_index_in_grid($default_sort['field'],4);
$sort_direct= $default_sort['sort'];
$data_num= count($result['data']);
$buttions= '<button type="button" class="btn btn-sm bg-green" data-action="'.EA_const_url::inst()->get_url('*/memberexport/export').'" data-cid="'.$get['card_id'].'" id="grid-btn-export"><i class="fa fa-tasks"></i>&nbsp;导出</button>';	//button之间不能有字符空格，用php组装输出
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/card_user_info/".$get['card_id']); ?>';
var url_ajax_a= '<?php echo EA_const_url::inst()->get_url("*/*/index"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//    '<?php //echo EA_const_url::inst()->get_url("*/*/audit"); ?>//'
];
</script>
<?php
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercard'. DS .'indexjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membercard'. DS. 'indexjs_ajax.php';
?>
<script type="text/javascript">
$(document).ready(function() {
    var Jobj = {};
    Wind.use("artDialog",function (){
        $(document).on('click','#grid-btn-export',function () {
            var onthis = this,url_action=$(this).data('action');
            var content = $("#expot").html();
            $("#expot").find('.expot').remove();
            var cid = $(this).data("cid");
            art.dialog({
                title:'信息提示',
                width:'27%',
                left:'30%',
                content:content,
                ok:function (){
                    var time_mode=0;
                    $("input[name='time_mode']").each(function (index,item) {
                        if(item.checked===true) time_mode=item.value;
                    });

                    if(time_mode=='1'){
                        var start_time = $("input[name='start_time']").val(),end_time = $("input[name='end_time']").val();
                    }else if(time_mode=='2'){
                        var start_time = $("input[name='stime']").val(),end_time = $("input[name='etime']").val();
                    }

                    if(!start_time || start_time==''){
                        art.dialog({title:'提示',content:'请选择开始时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }

                    if(!end_time || end_time==''){
                        art.dialog({title:'提示',content:'请选择结束时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }
                    var sd = new Date(start_time+' 00:00:00'),st=sd.getTime()/1000; //开始时间的时间戳
                    var ed = new Date(end_time+' 24:00:00'),et=ed.getTime()/1000; //结束时间的时间戳
                    var cd = et-st,fm=parseInt((cd/(60*60*24)));  //得出选择时间的间隔天数
                    if(cd<=0){
                        art.dialog({title:'提示',content:'结束时间不能小于开始时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }
                    sd.setDate(32); //设置超出月份范围的数字得出超出的天数,以得出准确的月份总天数
                    var v=sd.getDate(),c=32-v;
                    var c1=31,c2=30,tc=c1+c2+c;
                    if(fm>tc){
                        art.dialog({title:'提示',content:'时间间隔不能大于三个月',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }

                    art.dialog({content:'正在导出...',lock:true,opacity:0.1,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:6});

                    window.location= url_action+'?cid='+cid+'&bt='+start_time+'&et='+end_time+'&mode='+time_mode;
                },
                close:function () {
                    $("#expot").html(content);
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

            $('.date-export3').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });
            $('.date-export4').datetimepicker({
                format:'Y-m-d',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });
        });
    });

    $(document).on('click','.time_mode',function () {
        $(this).parent().parent().find("input[type='text']").prop('disabled',true);
        $(this).parent().find("input[type='text']").prop('disabled',false);
    });
});
</script>
</body>
</html>