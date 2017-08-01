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
          <h1><?php echo $breadcrumb_array['action']; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        <!-- Main content -->
        <section class="content" style="position:relative;">
            <style>
                .content-header{display: block;}
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
              <div class="box">
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
$sort_index= $module->field_index_in_grid($default_sort['field'],7);
$sort_direct= $default_sort['sort'];
$data_num= count($result['data']);
$get_param = !empty($_GET)?$_GET:array();
?>
var buttons = '';

var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
var dataSet= <?php echo json_encode($result['data']); ?>;
var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/index",$get_param); ?>';
var url_ajax_a= '<?php echo EA_const_url::inst()->get_url("*/*/index"); ?>';
<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//    '<?php //echo EA_const_url::inst()->get_url("*/*/audit"); ?>//'
];
</script>
<?php
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'adminoptlog'. DS .'indexjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'adminoptlog'. DS. 'indexjs_ajax.php';
?>
<script type="text/javascript">
    var Jobj = {};


    Wind.use("artDialog",function (){
        $(document).on('click','.memberexport',function () {
            var div1=$("<div/>",{id:'expot'}),div2=$("<div/>",{class:'expot'}).appendTo(div1),space='&nbsp;';
            $("<span/>").html('注册时间').appendTo(div2),$("<input/>",{type:'text',name:'start_time',autocomplete:'off',class:'form-control input-sm date-export1'}).appendTo(div2),$("<span/>").html('至').appendTo(div2),$("<input/>",{type:'text',name:'end_time',autocomplete:'off',class:'form-control input-sm date-export1'}).appendTo(div2);

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
                    var sd = new Date(start_time+' 00:00:00'),st=sd.getTime()/1000; //开始时间的时间戳
                    var ed = new Date(end_time+' 24:00:00'),et=ed.getTime()/1000; //结束时间的时间戳
                    var cd = et-st,fm=parseInt((cd/(60*60*24)));  //得出选择时间的间隔天数
                    if(cd<=0){
                        art.dialog({title:'提示',content:'结束时间不能小于开始时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }
                    sd.setDate(32); //设置超出月份范围的数字得出超出的天数,以得出准确的月份总天数
                    var v=sd.getDate(),c=32-v;
                    if(fm>c){
                        art.dialog({title:'提示',content:'时间间隔不能大于一个月',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }
                    art.dialog({content:'正在导出...',lock:true,opacity:0.1,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:6});
                    window.location= url_action+'?tp=3&fn=会员列表资料&bt='+start_time+'&et='+end_time;
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

    /*=================调整储值star===================*/
    var form1 = $("#EditBalanceInfo"),postUrl = $('#EditBalanceInfo').attr('action');
    form1.submit(function(){
        $.post( postUrl ,
            form1.serialize(),
            function(result,status){
                if(result['err']>=1){
                    alert(result['msg']);
                }else{
//                    alert(result['msg']);
                    $('.j_close').click();
//                    location.reload();
                    var value = Jobj.html();
                    var v2 = result.count;
                    if(result.mark=='1')
                        v2 = parseInt(value) + parseInt(v2);
                    else
                        v2 = parseInt(value) - parseInt(v2);
                    Jobj.html(v2.toFixed(2));
                }
            },'json');
        return false;
    });

    //按键触发
    $('.adjustment').keyup(function (event) {
        var keycode = event.which;
        switch (keycode){
            case 13:
                form1.submit();
                break;
            case 27:
                $('.j_close').click();
                break;
        }
    });
    $('.fixed .preservation').click(function(){
        form1.submit();
//	   $('.fixed,.face_film').fadeOut();
    });
    /*=================调整储值end===================*/

    /*=================调整积分star===================*/
    var form = $("#EditCreditInfo"),postUrl1 = $('#EditCreditInfo').attr('action');
    form.submit(function(){
        $.post( postUrl1 ,
            form.serialize(),
            function(result,status){
                if(result['err']>=1){
                    alert(result['msg']);
                }else{
//                    alert(result['msg']);
                    $('.j_close').click();
//                    location.reload();
                    var value = Jobj.html();
                    var v2 = result.count;
                    if(result.mark=='1')
                        v2 = parseInt(v2) + parseInt(value);
                    else
                        v2 = parseInt(value) - parseInt(v2);
                    Jobj.html(v2);
                }
            },'json');
        return false;
    });

    $('.fixed2 .preservation').click(function(){
        form.submit();
//		$('.fixed2,.face_film').fadeOut();
    });
    /*=================调整积分end===================*/

    $('.integral').keyup(function (event) {
        var keycode = event.which;
        switch (keycode){
            case 13:
                form.submit();
                break;
            case 27:
                $('.j_close').click();
                break;
        }
    });

    $(document).on('click','.adjustment',function(){
        Jobj = $(this).parent().parent().find('td').eq(7);
        var value = $(this).parent().parent().find('td').eq(7).html();
        $("input[name='balanceValue']").val(parseInt(value));
        $('.memberId').html( $(this).attr('dataid') );
        $('.memberCardNo').html( $(this).attr('attrno') );
        $('.memberName').html( $(this).attr('attrname') );
        $('.memberInfoId').val( $(this).attr('dataid') );
        $('.face_film,.fixed').fadeIn();
    });

    $(document).on('click','.integral',function(){
        Jobj = $(this).parent().parent().find('td').eq(6);
        var value = $(this).parent().parent().find('td').eq(6).html();

        $("input[name='creditValue']").val(parseInt(value));
        $('.memberId').html( $(this).attr('dataid') );
        $('.memberCardNo').html( $(this).attr('attrno') );
        $('.memberName').html( $(this).attr('attrname') );
        $('.memberInfoId').val( $(this).attr('dataid') );
        $('.face_film,.fixed2').fadeIn();
    });

    $(document).on('click','.j_close',function(){
        form[0].reset();form1[0].reset();
        $('.fixed,.fixed2,.face_film').fadeOut();
    });

    $(document).on('click','.synchronize',function (e) {
        e.preventDefault();
        var url = $(this).data('action'),obj=$(this),onthis = this,content = '确定要同步吗?';
        Wind.use("art_dialog",function (){
//        seajs.use([GV.JS__ROOT+'artDialog/src/dialog'], function (dialog){
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
                    var text = $.trim(obj.find('i').text());
                    obj.prop('disabled', true).addClass('disabled').find('i').text(text+'中...');
                    $.ajax({
                        url:url,
                        type:'get',
                        dataType:'json',
                        timeout:6000,
                        success: function (data) {
                            dthis.close();
                            if(data.status==1 && data.message=='ok'){
                                var d = dialog({
                                    content: '成功同步'+data.data+'条数据',quickClose:true
                                });
                                d.show(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else if(data.status==1 && data.message=='complete'){
                                var d = dialog({
                                    content: '没有需要同步的用户',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else if(data.status==1 && data.message=='null'){
                                var d = dialog({
                                    content: '默认等级没有配置',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }else{
                                var d = dialog({
                                    content: data.message?data.message:'请求失败,请刷新试试!',quickClose:true
                                });
                                d.showModal(onthis);
                                setTimeout(function () {
                                    d.close().remove();
                                }, 3000);
                            }
                            var text = obj.find('i').text();
                            obj.prop('disabled',false).removeClass('disabled').find('i').text(text.replace('中...', ''));
                        },
                        error: function (data) {
                            var d = dialog({
                                content: '请求异常,请刷新页面试试!',quickClose:true
                            });
                            d.showModal(onthis);
                            setTimeout(function () {
                                d.close().remove();
                            }, 3000);
                            var text = $.trim(obj.find('i').text());
                            obj.prop('disabled',false).removeClass('disabled').find('i').text(text.replace('中...', ''));
                        }
                    });
                },
                padding: 5,
                quickClose: true,
                cancelValue: '关闭'
            }).show(onthis);
        });
    });
</script>
</body>
</html>
