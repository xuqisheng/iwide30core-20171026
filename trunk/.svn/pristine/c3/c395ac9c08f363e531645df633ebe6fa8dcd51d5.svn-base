<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.fr.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>列表
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php echo form_open('booking/booking/index/','class="form-inline"')?>
                                    <div class="form-group">
                                        <label>状态 </label>
                                        <select name='status' class="form-control input-sm">
                                            <option value=""<?php if(empty($posts['status'])):echo ' selected';endif;?>>-- 全部 --</option>
                                            <?php foreach ($status_arr as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['status']) && $key == $posts['status']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>
                                    </div>
                                    <div class="form-group">
                                        <label>餐厅 </label>
                                        <select name='shop_id' class="form-control input-sm">
                                            <option value=""<?php if(empty($posts['shop_id'])):echo ' selected';endif;?>>-- 全部 --</option>
                                            <?php foreach ($shop_arr as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['shop_id']) && $key == $posts['shop_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>
                                    </div>
                                    <div class="form-group">
                                        <label>用餐时间 </label>
                                        <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="start_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['start_time']) ? date('Y-m-d') : $posts['start_time']?>">
                                        <label>至 </label>
                                        <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? date('Y-m-d') : $posts['end_time']?>">
                                    </div>


                                    <div class="form-group">
                                        <label>关键字 </label>
                                        <input type="text" name="wd" class="form-control input-sm" placeholder="用户名/电话" aria-controls="data-grid" value="<?php echo empty($posts['wd']) ? '' : $posts['wd']?>">
                                    </div>

                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                                    </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead>
                            <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
                             -->
                            <tr role="row">
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">用餐时间</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">预约用户</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">用户电话</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">预定人数</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">预定餐厅</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">提交时间</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">备注</th>

                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="操作">操作</th>

                            </tr>
                            </thead>
                            <tfoot></tfoot>
                            <tbody>
                            <?php
                            foreach ($res as $item ):?>
                                <tr>
                                    <td><?=$item['book_time']?></td>
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['phone']?></td>
                                    <td><?=$item['num']?></td>
                                    <td><?=$item['shop_name']?></td>
                                    <td><?=$item['add_time']?></td>
                                    <td><?=$item['note']?></td>

                                    <td>
                                        <?php if($item['status']==1){?>
                                        <div class="btn btn-default bg-green" onclick=change_status('<?php echo $item['id']?>',2)>已用餐</div>
                                        <div class="btn btn-default bg-green" onclick=change_status('<?php echo $item['id']?>',4)>取消</div>
                                    <?php }elseif($item['status']==3){?>
                                        <div >用户取消</div>
                                        <?php }elseif($item['status']==4){?>
                                        <div >酒店取消</div>
                                        <?php }elseif($item['status']==2){?>
                                        <div  >已用餐</div>
                                    <?php }?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条<!--<a class="btn btn-sm bg-green" href="javascript:void(0);" name="export">导出</a>--></div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                    <ul class="pagination"><?php echo $pagination; ?></ul>
                                </div>
                            </div>
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
    function change_status(id,status){
        $.post('<?php echo site_url('booking/booking/change_status')?>',{
            'id':id,
            'status':status,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(res){
            if(res.errcode==0){
                window.location.reload();
            }else{
                alert(res.msg);
                return false;
            }
        },'json');
    }
   /* $('#grid-btn-minus').click(function(){
        if(isNaN(selected)){
            alert("请选择需要退款的订单");
        }else{
            if(confirm("确定要执行退款操作吗？")){
                $.getJSON('<?php echo site_url('okpay/orders/refund')?>?id='+selected,
                    {},
                    function(data){
                        if(data.status == 1){
                            alert(data.message);
                        }else{
                            alert(data.message);
                        }
                    },'json');
            }
        }
    });*/
    <?php
    // $sort_index= $model->field_index_in_grid($default_sort['field']);
    // $sort_direct= $default_sort['sort'];

    // $buttions= '';	//button之间不能有字符空格，用php组装输出
    // $buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;发放绩效</button>';
    // /*$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
    // $buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';*/
    // /*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
    // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=1">员工</a>';
    // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=2">酒店</a>';
    // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=3">金房卡</a>';
    // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=4">集团</a>';
    ?>
    var buttons = $('<div class="btn-group"></div>');

    var grid_sort= [[ , "" ]];

    <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
    var url_extra= [
//'http://iwide.cn/',
    ];
    var baseStr = "";
    //$(".form_datetime").datepicker({format: 'yyyymmdd'});
    $(".form_datetime").datetimepicker({format:'yyyy-mm-dd',
        //startDate:new Date(2013,12,01),
        //endDate:'+1',//结束时间，在这时间之后都不可选
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        minView:2,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        language:'zh-CN'});
    $('#grid-btn-set').click(function(){
        $('#setModal').on('show.bs.modal', function (event) {
// 	  modal.find('.modal-body input').val(recipient)
            var str = $('#setting_form').html();
            if(baseStr != ""){
                str = baseStr;
            }else{
                baseStr = str;
            }
            $.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_fans_sale")?>',function(data){
                if(data != null){
                    $.each(data,function(k,v){
                        str += '<div class="checkbox"><label><input type="checkbox" name="' + k + '"';
                        if(v.must == 1){
                            str += ' disabled checked ';
                        }else if(v.choose == 1){
                            str += ' checked ';
                        }
                        str += '>' + v.name + '</label></div>';
                    });
                    $('#setting_form').html(str);
                }


            });

        })});
    $('#set_btn_save').click(function(){
        $.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_fans_sale")?>',$("#setting_form").serialize(),function(data){
            if(data == 'success'){
                window.location.reload();
            }else{
                alert('保存失败');
            }
        });
    });
    $(document).ready(function() {
        <?php
        // $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
        // if( count($result['data'])<$num)
        // 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
        // else
        // 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
        ?>
    });
</script>
</body>
</html>
