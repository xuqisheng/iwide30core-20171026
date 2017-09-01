<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/js/artDialog/css/ui-dialog.css">
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.css'>
<script type="text/javascript" src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/date-timepicker/jquery.datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<style type="text/css">
    html, body{min-width: 100%;}
    div.dataTables_filter label{text-align:center;width: 65%;}
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
    .f_l{float: left;}
</style>
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

    <!--调整储值 START -->
    <div class="fixed_box bg_fff c-balance" style="width: 320px;">
        <div class="tile">余额调整</div>
        <div class="f_b_con">
            <span>会员ID：</span>
            <span id="balance-con-mid"></span>
        </div>
        <div class="f_b_con">
            <span>会员卡号：</span>
            <span id="balance-con-mnum"></span>
        </div>
        <div class="f_b_con">
            <span>会员姓名：</span>
            <span id="balance-con-name"></span>
        </div>
        <div class="f_b_con">
            <span>余额调整：</span>
            <span class="relative">
                <input class="balance" type="number" step="0.01" name="balance" placeholder="" />
                <i class="absolute">元</i>
            </span>
        </div>
        <div class=" f_b_con clearfix">
            <span class="f_l">调整备注：</span>
            <span><textarea name="balance_note"></textarea></span>
        </div>
        <div class="clearfix">
            <span>只保留小数点后两位数，正数为增加，负数为减少；</span>
        </div>
        <div class="h_btn_list clearfix" style="width: 100%;">
            <div class="actives confirms save-balance" data-type="1">保存</div>
            <div class="cancel f_r">取消</div>
        </div>
    </div>
    <!--调整储值 END -->

    <!--调整积分 START -->
    <div class="fixed_box bg_fff c-integral" style="width: 320px;">
        <div class="tile">积分调整</div>
        <div class="f_b_con">
            <span>会员ID：</span>
            <span id="integral-con-mid"></span>
        </div>
        <div class="f_b_con">
            <span>会员卡号：</span>
            <span id="integral-con-mnum"></span>
        </div>
        <div class="f_b_con">
            <span>会员姓名：</span>
            <span id="integral-con-name"></span>
        </div>
        <div class="f_b_con">
            <span>积分调整：</span>
            <span class="relative">
                <input class="integral" type="number" name="integral" placeholder="积分"  />
                <i class="absolute">积分</i>
            </span>
        </div>
        <div class="f_b_con clearfix">
            <span class="f_l">调整备注：</span>
            <span><textarea name="integral_note"></textarea></span>
        </div>
        <div class="clearfix">
            <span>正数为增加，负数为减少；</span>
        </div>
        <div class="h_btn_list clearfix" style="width: 100%;">
            <div class="actives confirms save-integral" data-type="2">保存</div>
            <div class="cancel f_r">取消</div>
        </div>
    </div>
    <!--调整积分 END -->

    <div style="color:#92a0ae;">
        <div class="over_x">
            <div class="content-wrapper">
                <div class="banner bg_fff p_0_20">
                    会员列表
                </div>
                <div class="contents">
                    <?=$this->session->show_put_msg()?>
                    <div class="box-body" style="margin-top: 18px;">
                        <table id="data-grid" class="table table-bordered table-striped table-condensed dataTable no-footer">
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
$num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
?>

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.member.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script type="text/javascript">
    <?php
    $sort_index= $model->field_index_in_grid($default_sort['field']);
    $sort_direct= $default_sort['sort'];
    $data_num= count($result['data']);
    $buttions= '<div class="actives synchronize" id="subbtn" data-action="'.EA_const_url::inst()->get_url('*/*/synchronize_member_lvl').'">同步等级</div>';	//button之间不能有字符空格，用php组装输出

    $buttions.='<div class="memberexport" id="subbtn" data-action="'.EA_const_url::inst()->get_url('*/memberexport/export').'">导出</div>';
    $selects = '<option value="">全部</option>';
    foreach ($member_lvl as $vo){
        $selected = "";
        if(isset($get['member_lvl']) && $get['member_lvl']==$vo['member_lvl_id']) $selected = "selected";
        $selects .= '<option value="'.$vo['member_lvl_id'].'" '.$selected.'>'.$vo['lvl_name'].'</option>';
    }
    $get_param = !empty($_GET)?$_GET:array();
    ?>
    var buttons = $('<div class="h_btn_list"><?php echo $buttions; ?></div>');
    var selects = '会员等级: <select name="selects_member_lvl_id" aria-controls="data-selects" class="form-control input-sm data-selects"><?php echo $selects;?></select>';

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
require_once VIEWPATH. $tpl .DS .'membervip'. DS. 'membermanage'. DS. 'indexjs_ajax.php';
?>
<script type="text/javascript">
    $(function(){

        var Jobj;
        $(document).on("click",".s-balance",function (e) {
            e.preventDefault();
            $('.c-integral').hide();
            $('.integral').val('');
            Jobj = $(this).parent().parent().find('td').eq(7);
            var mid=$(this).data("mid"),
                mnum=$(this).data("mnum"),
                name=$(this).data("name");
            $("#balance-con-mid").html(mid);
            $("#balance-con-mnum").html(mnum);
            $("#balance-con-name").html(name);
            $('.c-balance').show();
        });

        $(document).on("click",".s-integral",function (e) {
            e.preventDefault();
            $('.c-balance').hide();
            $('.balance').val('');
            Jobj = $(this).parent().parent().find('td').eq(6);
            var mid=$(this).data("mid"),
                mnum=$(this).data("mnum"),
                name=$(this).data("name");
            $("#integral-con-mid").html(mid);
            $("#integral-con-mnum").html(mnum);
            $("#integral-con-name").html(name);
            $('.c-integral').show();
        });

        $(document).on("click",".confirms",function (e){
            e.preventDefault();
            var type = $(this).data("type"),obj = $(this);
            var text = obj.text();
            obj.prop('disabled', true).addClass('disabled').text(text+'中...');
            var text = obj.text();
            if(type==1){
                var act_url = "<?=EA_const_url::inst()->get_url('*/*/edit_balance')?>";
                var mid=$("#balance-con-mid").html(),
                    mnum=$("#balance-con-mnum").html(),
                    name=$("#balance-con-name").html(name),
                    amount = $("input[name=balance]").val(),
                    note = $("textarea[name=balance_note]").val();
                if(isNaN(amount) || !amount) {
                    obj.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                    alert("请输入正确的储值");return false;
                }

                var datas = {member_info_id:mid,balanceamount:amount,note:note};
            }else{
                var act_url = "<?=EA_const_url::inst()->get_url('*/*/edit_credit')?>";
                var mid=$("#integral-con-mid").html(),
                    mnum=$("#integral-con-mnum").html(),
                    name=$("#integral-con-name").html(name),
                    amount = $("input[name=integral]").val(),
                    note = $("textarea[name=integral_note]").val();
                if(isNaN(amount) || !amount) {
                    obj.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                    alert("请输入正确的积分");return false;
                }
                var reg = /^([+-])?[1-9]?[0-9]*\.[0-9]*$/;
                if(reg.test(amount)){
                    obj.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                    alert("积分只能输入整数");return false;
                }

                var datas = {member_info_id:mid,creditamount:amount,note:note};
            }
            $.get(act_url,datas,function (data) {
                obj.prop('disabled', false).removeClass('disabled').text(text.replace('中...', ''));
                if(typeof (data.err)!='undefined' && data.err==0){
                    $('.fixed_box').hide();
                    $('.balance,.integral').val('');
                    var value = Jobj.html();
                    var v2 = data.count;
                    if(data.mark=='1'){
                        v2 = parseInt(v2) + parseInt(value);
                    }else{
                        v2 = parseInt(value) - parseInt(v2);
                    }
                    Jobj.html(v2);
                }else{
                    if(typeof (data.err)!='undefined'){
                        alert(data.msg);
                    }else{
                        alert('请求失败');
                    }
                }
            },'json');

        });

        $(document).on('click','.cancel',function (){
            $("#balance-con-mid").html('');
            $("#balance-con-mnum").html('');
            $("#balance-con-name").html('');
            $("#integral-con-mid").html('');
            $("#integral-con-mnum").html('');
            $("#integral-con-name").html('');
            $('.fixed_box').hide();
        });

        $(document).on('change','.data-selects',function () {
            var member_lvl = this.value;
            if(url_ajax.indexOf('member_lvl=')=='-1'){
                if(url_ajax.indexOf('?')=='-1')
                    window.location= url_ajax+'?member_lvl='+member_lvl;
                else
                    window.location= url_ajax+'&member_lvl='+member_lvl;
            }else{
                window.location= url_ajax_a+'?member_lvl='+member_lvl;
            }
        });

        Wind.use("artDialog","art_dialog",function (){
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



            $(document).on('click','.synchronize',function (e) {
                e.preventDefault();
                var url = $(this).data('action'),obj=$(this),onthis = this,content = '确定要同步吗?';

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
    });
</script>
</body>
</html>
