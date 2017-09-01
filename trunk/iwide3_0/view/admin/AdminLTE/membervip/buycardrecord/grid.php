<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
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
    .expot span{display: inline-block;margin: 0 10px;}
    .expot input{width:30%;display: inline-block;}
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
//print_r($fields_config);die;
	    foreach ($fields_config as $k=> $v):
		     ?><th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?> ><?php echo $v['label'];?></th><?php 
	    endforeach; ?></tr></thead>
                    
                    <tfoot><tr><?php 
//print_r($fields_config);die;
	    foreach ($fields_config as $k=> $v):
		     ?><th><?php echo $v['label'];?></th><?php 
		endforeach; ?></tr></thead>
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
<script>
<?php 
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

$buttions= '';	//button之间不能有字符空格，用php组装输出
$buttions.='<button type="button" data-action="'.EA_const_url::inst()->get_url('*/memberexport/export').'" class="btn bg-green btn-sm export"><i class="fa fa-tasks">导出</i></button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');
var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];


$(document).ready(function() {
<?php 
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
if( count($result['data'])<$num) 
	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
else 
	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
?>


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
