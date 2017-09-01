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
    var GV = {
        JS_ROOT:"<?php echo base_url(FD_PUBLIC) ?>/js/"
    };
</script>
<style type="text/css">
    div.dataTables_filter label{text-align:center;}
    .box-body .expot{width: 35%;margin-bottom: 10px;}
    .box-body .expot span{display: inline-block;}
    .box-body .expot input{width:20%;display: inline-block;}
    table.table-bordered th:last-child, table.table-bordered td:last-child{text-align: center;vertical-align: middle;}
    table.table-bordered td:last-child>.btn-sm{padding:2px 6px;margin: 0 2px;}
    .expot{width: 100%;margin-bottom: 10px;}
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
          <h1>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>

        <!-- Main content -->
        <section class="content" style="position:relative;">
            <style>
                .fixed,.fixed2{position:fixed;top:8%;left:1.5%;width:100%;height:100%;padding:3%;z-index:821;}
                .bg_fff{background:rgba(255,255,255,1)}
                .j_box{border:2px solid #00c0ef;}
                .j_footer{padding-bottom:30px;}
                .face_film{position:fixed;height:100%;width:100%;top:0px;left:0px;background:rgba(0,0,0,0.5);z-index:820;}
                .dataTables_filter label{width: 50%;}
                .search-wd{width: 80% !important;}
            </style>

          <div class="row">
            <div class="col-xs-12">
              <div class="box box-info">
                  <div class="box-header with-border">
                      <h3 class="box-title"><?php echo $breadcrumb_array['action']; ?></h3>
                  </div>
                <?php echo $this->session->show_put_msg(); ?>
              <!--
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
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
$sort_index= $module->field_index_in_grid($default_sort['field'],6);
$sort_direct= $default_sort['sort'];
$data_num= $result['total'];
$buttions= '';	//button之间不能有字符空格，用php组装输出
if($data_num == 0)
    $buttions.= '<button type="button" class="btn btn-default disabled" target="qr"><i class="fa fa-edit"></i><span>审核<span></button>';
else
    $buttions.= '<button type="button" class="btn btn-default btn-sm bg-green pull-left btn-member-audit" data-check="1" data-premsg="不选择将全部审核通过,确定操作吗?" data-msg="确定审核通过吗" id="grid-btn-extra-0" target="qr"><i class="fa fa-edit"></i><span>审核通过<span></button>&nbsp;<button type="button" class="btn btn-default btn-sm pull-left btn-member-audit" data-check="0" data-premsg="不选择将全部审核不通过,确定操作吗?" data-msg="确定审核不通过吗" id="grid-btn-extra-1" target="qr"><i class="fa fa-edit"></i><span>审核不通过<span></button>';

$get_param = !empty($_GET)?$_GET:array();
?>
var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/index",$get_param); ?>';
var url_audit= '<?php echo EA_const_url::inst()->get_url("*/*/member_audit"); ?>';
var url_member_level= '<?php echo EA_const_url::inst()->get_url("*/*/member_level"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//    '<?php //echo EA_const_url::inst()->get_url("*/*/audit"); ?>//'
];
</script>
<?php
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'ownerusers'. DS .'gridjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'ownerusers'. DS. 'gridjs_ajax.php';
?>
<script type="text/javascript">
    var Jobj = {};
    seajs.use([GV.JS_ROOT+'artDialog/src/dialog'], function (dialog){
        $(document).on('click','.btn-member-audit',function (e) {
            e.preventDefault();
            var obj=$(this),audit=obj.data("check"),premsg=obj.data("premsg"),msg=obj.data("msg"),onthis = this,content = selected.length==0?premsg:msg;
            dialog({
                title:false,
                content:content,
                align:'right',
                btnStyle:'ui-dialog-mini',
                okValue: '确定',
                cancel: function () {
                    this.close();
                    onthis.focus(); //关闭时让触发弹窗的元素获取焦点
                    return true;
                },
                ok: function () {
                    var dthis = this;
                    for(var i in selected){
                        upgrade_member_lvl(dthis,onthis,obj,dialog,selected[i],audit,i);
//                        var cou = parseInt(i) + 1;
//                        if(cou==selected.length) {
//                            setTimeout(function () {
//                                window.location.reload();
//                            }, 3000);
//                        }
                    }
                },
                padding: 10,
                quickClose: true,
                cancelValue: '关闭'
            }).show(onthis);
        });
    });
    
    function upgrade_member_lvl(dthis,onthis,obj,dialog,member_info_id,audit,k) {
        dthis.close().remove();
        var text = $.trim(obj.find('span').text());
        if(k==0) obj.prop('disabled', true).addClass('disabled').find('span').text(text+'中...');
        $.get(url_audit,{member_info_id:member_info_id,audit:audit},function(data){
            dthis.close();
            if(data.status==1 && data.message=='ok'){
                $(onthis).parent().parent().parent().append("<p style='color:#18BF0E;'>"+data.data+"</p>");
                setTimeout(function () {
                    $(onthis).parent().parent().parent().find('p').fadeOut('normal', function () {
                        $(onthis).parent().parent().parent().find('p').remove();
                    });
                }, 5000);
            }else if(data.status==1 && data.message=='null'){
                $(onthis).parent().parent().parent().append("<p style='color:#18BF0E;'>没有需要审核的用户</p>");
                setTimeout(function () {
                    $(onthis).parent().parent().parent().find('p').fadeOut('normal', function () {
                        $(onthis).parent().parent().parent().find('p').remove();
                    });
                }, 5000);
            }else{
                var infomsg = data.message?data.message:'审核失败,请刷新页面试试';
                $(onthis).parent().parent().parent().append("<p style='color:red;'>"+infomsg+"</p>");
                setTimeout(function () {
                    $(onthis).parent().parent().parent().find('p').fadeOut('normal', function () {
                        $(onthis).parent().parent().parent().find('p').remove();
                    });
                }, 5000);
            }
            var text = obj.find('span').text();
            obj.prop('disabled',false).removeClass('disabled').find('span').text(text.replace('中...', ''));
        });
    }

    function member_level(data) {
        if(typeof data == 'object'){
            $.ajax({
                url:url_member_level,
                type:'get',
                data:{data:data},
                dataType:'json',
                timeout:6000,
                success: function (result) {
                    console.log(result);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    }
</script>
</body>
</html>
