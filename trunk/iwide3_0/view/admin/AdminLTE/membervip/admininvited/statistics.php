<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
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
	.derived{float:right;border:1px solid #999;padding:4px 10px;border-radius:4px;margin-right:2%;}
	.numbe_list{padding:10px 30px;}
	.table-striped>tbody>tr>td,.table-striped>thead>tr>th,.center{text-align:center;}
	.search{width:300px;margin:0 10px;}
	.detailes{color:rgba(191,41,239,1.00);text-decoration:underline;}
	.j_fliex{position:fixed;top:0px;left:0px;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;}
    .j_fliex_2{position:fixed;top:0px;left:0px;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:9999;}

    .expot{width: 100%;margin-bottom: 10px;}
    .expot span{display: inline-block;}
    .expot input[type='text']{width:30%;display: inline-block;}
    .expot input[type='radio']{width:7%;display: inline-block;}
    table.table-bordered th:last-child, table.table-bordered td:last-child{text-align: center;vertical-align: middle;}
    table.table-bordered td:last-child>.btn-sm{padding:2px 6px;margin: 0 2px;}
    #expot{ display: none;}
    .expot-control{margin-top: 10px;}
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
          <h1><small></small></h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
                <?php echo $this->session->show_put_msg(); ?>
            <!-- Horizontal Form -->
            <div class="box box-info">
                <?php echo form_open(EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-inline')); ?>
                <div class="box-body">
                    <div id="expot">
                        <div class="expot">
                            <div class="expot-control">
                                <input type="radio" checked class="export_mode" name="export_mode" value="1" />
                                <span>导出被邀请报表</span>
                                <input type="text" checked name="start_time" autocomplete="off" class="form-control input-sm date-export1" />
                            </div>

                            <div class="expot-control">
                                <input type="radio" class="export_mode" name="export_mode" value="2" />
                                <span>邀请好友分析报表</span>
                            </div>
                        </div>
                    </div>

                    <div class="box-header with-border" style="padding-left:0px;width: 100%;float: left;">
                        <h3 class="box-title">邀请好友统计</h3>
                    </div>
                    <div class="control-group" style="float: left;width: 100%;">
                    	<div class="contents">已有<?=$invite_count?>位会员行使了会员权益</div>
                        <div class="contents">共成功邀请<?=$result['total']?>人次<?php if(!empty($group_lvls)):?>；其中<?=$group_lvls?><?php endif;?></div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                        <section class="content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <table id="data-grid" class="table table-bordered table-striped table-condensed table-hover">
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
    $sort_index= $module->field_index_in_grid($default_sort['field'],5);
    $sort_direct= $default_sort['sort'];
    $data_num= count($result['data']);
    $buttions= '';	//button之间不能有字符空格，用php组装输出

    $buttions.='<a href="'.EA_const_url::inst()->get_url('*/*').'" class="btn btn-primary btn-sm" target="_blank">权益设置</a>';

    $buttions.='<a href="'.EA_const_url::inst()->get_url('*/*/viewconf').'" class="btn btn-success btn-sm" target="_blank">显示设置</a>';

    $buttions.='<button type="button" data-action="'.EA_const_url::inst()->get_url('*/memberexport/export').'" data-action2="'.EA_const_url::inst()->get_url('*/memberexport/export_invite').'" class="btn btn-info btn-sm" id="grid-btn-export">导出</a>';

    $inputs='';
    $st = !empty($get['st'])?$get['st']:'';
    $inputs.='<input type="text" style="width: 32%;" name="stime" autocomplete="off" class="form-control input-sm date-export1" value="'.$st.'" />';
    $inputs.='<span>至</span>';
    $et = !empty($get['et'])?$get['et']:'';
    $inputs.='<input type="text" style="width: 32%;" name="etime" autocomplete="off" class="form-control input-sm date-export2" value="'.$et.'" />';

    $k = !empty($get['k'])?$get['k']:'';
    $sinputs='<input type="search" class="form-control input-sm" name="keyword" placeholder="请输入姓名、卡号..." aria-controls="data-grid" value="'.$k.'" />';

    $sbuttions='<button type="search" data-action="'.EA_const_url::inst()->get_url('*/*/*').'" class="btn btn-default btn-sm search-sub">查询</button>';

    $selects = '<option value="">全部</option>';
    foreach ($member_lvl as $vo){
        $selected = "";
        if(isset($get['lvl']) && $get['lvl']==$vo['member_lvl_id']) $selected = "selected";
        if($vo['is_default']=='f') $selects .= '<option value="'.$vo['member_lvl_id'].'" '.$selected.'>'.$vo['lvl_name'].'</option>';
    }
    $get_param = !empty($get)?$get:array();
    ?>
    var buttons = $('<div class="btn-group"><?php echo $buttions; ?></div>');
    var selects = '<label style="margin-left: 10px;">邀请资格: <select name="slvl_id" aria-controls="data-selects" class="form-control input-sm data-selects"><?php echo $selects;?></select></label>';
    var inputs = $('<label style="width:33%">邀请时间：<?=$inputs?></label>');
    var sinputs = $('<label><?=$sinputs?></label>');
    var sbuttions = $('<label style="margin-left:10px"><?=$sbuttions?></label>');

    var grid_sort= [[ <?php echo $sort_index ?>, "<?php echo $sort_direct ?>" ]];
    var dataSet= <?php echo json_encode($result['data']); ?>;
    var columnSet= <?php echo json_encode( $model->get_column_config($fields_config) ); ?>;
    var url_add= '<?php echo EA_const_url::inst()->get_url("*/*/add"); ?>';			//跟button对应
    var url_edit= '<?php echo EA_const_url::inst()->get_url("*/*/edit"); ?>';		//跟button对应
    var url_delete= '<?php echo EA_const_url::inst()->get_url("*/*/delete"); ?>';	//跟button对应
    var url_ajax= '<?php echo EA_const_url::inst()->get_url("*/*/*"); ?>';
    var url_ajax_a= '<?php echo EA_const_url::inst()->get_url("*/*/index"); ?>';
    <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
    var url_extra= [
//    '<?php //echo EA_const_url::inst()->get_url("*/*/audit"); ?>//'
    ];
</script>
<?php
if( floatval($result['total'])<$num)
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'admininvited'. DS .'statisticsjs.php';
else
    require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'admininvited'. DS. 'statisticsjs_ajax.php';
?>
<script type="text/javascript">

    var Jobj = {};
    $(document).on('click','.search-sub',function (e) {
        e.preventDefault();
        var st = $("input[name='stime']").val(),
            et = $("input[name='etime']").val(),
            k = $("input[name='keyword']").val(),
            lvl = $("select[name='slvl_id']").val();
        window.location= url_ajax+'?st='+st+'&et='+et+'&lvl='+lvl+'&k='+k;
    });

    Wind.use("artDialog",function (){
        $(document).on('click','#grid-btn-export',function () {
            var onthis = this,url_action=$(this).data('action'),url_action2=$(this).data('action2');
            var content = $("#expot").html();
            $("#expot").find('.expot').remove();
            var cid = $(this).data("cid");
            art.dialog({
                title:'信息提示',
                width:'27%',
                left:'30%',
                content:content,
                ok:function (){
                    var export_mode=1;
                    $("input[name='export_mode']").each(function (index,item) {
                        if(item.checked===true) export_mode=item.value;
                    });

                    if(export_mode=='1'){
                        var start_time = $("input[name='start_time']").val(),end_time = $("input[name='end_time']").val();
                    }

                    if((!start_time || start_time=='') && export_mode=='1'){
                        art.dialog({title:'提示',content:'请选择时间',lock:true,opacity:0.01,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:3});
                        return false;
                    }

                    art.dialog({content:'正在导出...',lock:true,opacity:0.1,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:6});
                    //                        window.location.href = url_action+'?bt='+start_time+'&et='+end_time+'&tp=4';
                    if(export_mode=='1')
                        window.open(url_action+'?bt='+start_time+'&et='+end_time+'&tp=4');
                    else
                        window.open(url_action2);
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
                format:'Y-m',
                lang:'ch',
                timepicker:false,
                scrollInput:false
            });
        });
    });

    $(document).on('click','.export_mode',function () {
        $(this).parent().parent().find("input[type='text']").prop('disabled',true);
        $(this).parent().find("input[type='text']").prop('disabled',false);
    });
</script>
</body>
</html>
