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

          <section class="content-header">
              <form name="searchForm" action='' method="get">
                  <input name="searchAll"><input style="margin-left:10px" type="submit" value="搜索">
              </form>
          </section>
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

                  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:#fff;width:280px;height:340px;margin:200px auto;">
                      <div class="modal-header" style="text-align: center">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          <h4 >二维码</h4>
                      </div>
                      <div class="modal-body" style="margin:10px 0 15px 15px ;text-align:center;">
                          <img id="qrcode-img" src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/img/loading.gif" />
                      </div>
                  </div>
              <!--
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                      <thead>
                      <tr>
                          <?php foreach ($fields_config as $k=> $v):?>
                              <th <?php if(isset($v['grid_width'])) echo 'width="'. $v['grid_width']. '"'; ?>>
                                  <?php echo $v['label'];?>
                              </th>
                          <?php endforeach; ?>
                      </tr>
                      </thead>

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
$default_sort= $model::default_sort_field();
$sort_index= $model->field_index_in_grid($default_sort['field']);
$sort_direct= $default_sort['sort'];

//$sort_index= $model->field_index_in_grid('cp_id');
//$sort_direct= 'DESC';

$buttions= '';	//button之间不能有字符空格，用php组装输出
//$buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;新增</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" style="margin-left:5px"  id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" style="margin-left:5px"  id="open-fans"><i class="fa fa-edit"></i>&nbsp;开通粉丝</button>';
$buttions.= '<button type="button" class="btn btn-sm disabled" style="margin-left:5px"  id="close-fans"><i class="fa fa-edit"></i>&nbsp;关闭粉丝</button>';
/*$buttions.= '<button type="button" class="btn btn-sm bg-green" style="margin-left:5px" id="grid-btn-batch-audit1" title="开通粉丝归属"><i class="fa fa-edit"></i>&nbsp;全部开通</button>';
$buttions.= '<button type="button" class="btn btn-sm bg-green" style="margin-left:5px" id="grid-btn-batch-audit2" title="关闭粉丝归属"><i class="fa fa-edit"></i>&nbsp;全部关闭</button>';*/

//$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';
/*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
//$buttions.= '<button type="button" class="btn btn-default" id="grid-btn-extra-0"><i class="fa fa-trash"></i>&nbsp;导出</button>';
if(isset($js_filter_btn)) $buttions.= $js_filter_btn;
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];

var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var open_fans= '<?php echo EA_const_url::inst()->get_url("*/*/turn_staff_grade?operate=1"); ?>';		//跟button对应
var close_fans= '<?php echo EA_const_url::inst()->get_url("*/*/turn_staff_grade?operate=0"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
    '<?php echo EA_const_url::inst()->get_url("*/prices/qrcode_front"); ?>'
];
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>



$(document).ready(function() {
<?php
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;

//如 view/mall/gridjs.php 存在，则会覆盖 view/privilege/gridjs.php，个性化的部分请拷贝到模块内修改
if( count($result['data'])<$num){
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS. 'gridjs.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';

} else {
    if( $module ) $m= $module;
    else $m= 'privilege';
    $gridjs= VIEWPATH. $tpl .DS . $m. DS. 'gridjs_ajax.php';
    if( file_exists($gridjs) )
        require_once $gridjs;
    else
        require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
}
?>

<?php if(isset($js_filter)) echo $js_filter; ?>

    $('#grid-btn-batch-audit1').click(function(){
        $.getJSON('<?php echo site_url('club/club/club_grade_turn?grade=1')?>',function(data){
            if(data.errmsg == 'ok'){
                alert('开启成功');
                window.location.reload();
            }else if(data.errmsg == 'same'){
                alert('现在已经开启');
            }else{
                alert('开启失败');
            }
        });
    });


    $('#grid-btn-batch-audit2').click(function(){
        $.getJSON('<?php echo site_url('club/club/club_grade_turn?grade=0')?>',function(data){
            if(data.errmsg == 'ok'){
                alert('关闭成功');
                window.location.reload();
            }else if(data.errmsg == 'same'){
                alert('现在已经关闭');
            }else{
                alert('关闭失败');
            }
        });
    });

});
</script>
</body>
</html>
