<!-- DataTables -->
<link rel="stylesheet"
    href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
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
<div class="modal fade" id="setModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">修改审核状态</h4>
                    </div>
                    <div class="modal-body">
                        <div id='cfg_items'>
        <?php echo form_open('distribute/qrcodes/batch_auth','id="setting_form"')?>
            <div class="from-group">
                            <input type="hidden" name="saler" id="hsaler" value=""/>
                                <label>状态修改为：</label><select class="form-control input-sm" id="statOpt" name="status"></select>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" id="setModelConfirm">确定</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="modal fade" id="detailModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div id='cfg_items'></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn_auth_confirm">确定</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>分销人员管理
            <small></small>
                </h1>
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
                    <?php echo form_open('distribute/qrcodes/index/','class="form-inline"')?>
                        <div class="form-group">
                            <label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_name?>">
                        </div>
                        <div class="form-group">
                            <label>分销号</label> <input type="text" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo $saler_no?>">
                        </div>
                        <div class="form-group">
                            <label>所属酒店</label> <select class="selectpicker" data-live-search="true" name="hotel_id"><option value=""<?php if(empty($hotel_id)):echo ' selected';endif;?>>--全部--</option>
                            <?php foreach ($hotels as $hid=>$hname):?><option value="<?php echo $hid;?>"<?php if($hid == $hotel_id):echo ' selected';endif;?>><?php echo $hname;?></option><?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>所属部门</label> <select class="selectpicker" data-live-search="true" name="department"><option value="">--全部--</option>
                            <?php foreach ($depts as $k=>$v):if(!empty($v->master_dept)):?><option value="<?php echo $v->master_dept;?>"<?php if($deptment == $v->master_dept):echo ' selected';endif;?>><?php echo $v->master_dept;?></option><?php endif; endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>分销状态</label> 
                            <select class="form-control input-sm" name="status">
                                <option value=""<?php if(empty($status)):echo ' selected';endif;?>>--全部--</option>
                                <?php foreach ($status_arr as $s=>$v):?><option value="<?php echo $s;?>"<?php if($status==$s):echo ' selected';endif;?>><?php echo $v;?></option><?php endforeach;?>
                                </select>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"> <i class="fa fa-search"></i>&nbsp;检索</button>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-sm bg-green" href="javascript:;" id="btn_batch_auth" data-toggle="modal" data-target="#setModal">批量审核</a>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-sm bg-green" href="<?php echo site_url("distribute/qrcodes/ext_qrcodes/".$hotel_id.'_'.$saler_name.'_'.$saler_no.'_'.$cellphone.'_'.$deptment.'_'.$status)?>">导出</a>
                        </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">&nbsp;</div>
                    </div>
                    <table id="data-grid"
                        class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th style="width: 3em;color:blue;" id="select_all" prop="false">&nbsp;</th>
                                <th>姓名</th>
                                <th>分销号</th>
                                <th>手机号</th>
                                <th>所属酒店</th>
                                <th>所属部门</th>
                                <th>分销状态</th>
                                <th>总收益</th>
                                <th>未发收益</th>
                                <th>参与社群客</th>
                                <th>分销入口</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody><?php foreach ($res as $item):?>
                        <tr>
                                            <td><input type="checkbox" name="sid[]" value="<?php echo $item->id?>" rs="<?php echo $item->status?>" /></td>
                                            <td><?php if(!empty($item->qrcode_id)):?><a href="<?php echo site_url('distribute/qrcodes/saler_grades')?>?sid=<?php echo $item->qrcode_id;?>"><?php endif;?><?php echo $item->name?><?php if(!empty($item->qrcode_id)):?></a><?php endif;?></td>
                                            <td><?php echo $item->qrcode_id?></td>
                                            <td><?php echo $item->cellphone?></td>
                                            <td><?php echo isset($hotels[$item->hotel_id]) ? $hotels[$item->hotel_id] : '--'?></td>
                                            <td><?php echo $item->master_dept?></td>
                                            <td><a href="javascript:;" data-toggle="modal" data-target="#setModal" rel_status="<?php echo $item->status?>" rel_saler="<?php echo $item->id?>"><?php echo isset($status_arr[$item->status]) ? $status_arr[$item->status] : '异常'; ?></a></td>
                                            <td><?php echo isset($cls[$item->qrcode_id]) ? $cls[$item->qrcode_id]['grade_total'] : '0.00';?></td>
                                            <td><?php echo isset($cls[$item->qrcode_id]) ? $cls[$item->qrcode_id]['undeliver'] : '0.00';?></td>
                                            <td><?php echo $club_status[$item->is_club];?></td>
                                            <td><?php echo$distribute_hidden[$item->distribute_hidden];?></td>
                                            <td><a href="<?php echo site_url('distribute/qrcodes/edit')?>?ids=<?php echo $item->id?>" sid="<?php echo $item->id?>">编辑</a></td>
                                        </tr><?php endforeach;?>
                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="dataTables_info" id="data-grid_info" role="status"
                                            aria-live="polite">共<?=$total?>条</div>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="dataTables_paginate paging_simple_numbers"
                                            id="data-grid_paginate">
                                            <ul class="pagination"><?php echo $pagination?></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
<?php 
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>

</div>
    <!-- ./wrapper -->

<?php 
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>

<script
        src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script
        src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script
        src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- page script -->
        <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.css">
    <script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script>
var buttons = $('<div class="btn-group"></div>');


<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];

function makeAuthOption(curStatus){
    var optionStr = '';
    switch(curStatus){
        case 0:
            optionStr += '<option value="2">正常</option>';
            optionStr += '<option value="3">未通过</option>';
        break;
        case 1:
            optionStr += '<option value="2">正常</option>';
            optionStr += '<option value="3">未通过</option>';
        break;
        case 3:
            optionStr += '';
        break;
        case 2:
            optionStr += '<option value="4">停止绩效</option>';
        break;
        case 4:
            optionStr += '<option value="2">通过</option>';
        break;
    }
    $('#statOpt').html(optionStr);
}
function getDetails(saler){
    
}
$(document).ready(function() {
    var single  = true;
    $('#setModal').on('show.bs.modal', function (event) {
        if(event.relatedTarget.id == 'btn_batch_auth'){
            single = false;
            var idsObj = $("input[name='sid[]']:checked");
            if(idsObj.length < 1){
                alert('请先勾选分销员');
                event.preventDefault();
                return false;
            }
            var tmpStats = -1;
            idsObj.each(function(){
                if(tmpStats == -1)
                    tmpStats = parseInt($(this).attr('rs'));
                else if(tmpStats != -1 && tmpStats != $(this).attr('rs')){
                    alert('不能对不同状态的人员进行批量操作');
                    event.preventDefault();
                }
            });
            makeAuthOption(tmpStats);
        }else{
            single = true;
            makeAuthOption(parseInt($(event.relatedTarget).attr('rel_status')));
            $('#hsaler').val(parseInt($(event.relatedTarget).attr('rel_saler')));
        } 
    });
    $('#setModelConfirm').click(function(){
        if(confirm('确定要保存吗?')){
            if(single){
                $.post('<?php echo site_url("distribute/qrcodes/auth")?>',$("#setting_form").serialize(),function(data){
                    if(data.errmsg){
                        window.location.reload();
                    }else{
                        alert('保存失败');
                    }
                },'json');
            }else{
                var idsObj = $("input[name='sid[]']:checked");
                $.post('<?php echo site_url("distribute/qrcodes/batch_auth")?>',$("input[name='sid[]']:checked").serialize()+"&<?php echo $this->security->get_csrf_token_name ();?>=<?php echo $this->security->get_csrf_hash ();?>&status="+$('#statOpt').val(),function(data){
                    if(data.errmsg){
                    }else{
                        alert('成功' + data.success_count + '人，失败' + data.fail_count + '人');
                        window.location.reload();
                    }
                },'json');
            }
        }
    });
    // $('#select_all').click(function (){
    //  if($(this).attr('prop') == "false"){
    //      $("input[name='sid[]']").prop('checked',true);
    //      $('#select_all').attr('prop',"true");
    //  }else{
    //      $("input[name='sid[]']").prop('checked',false);
    //      $('#select_all').attr('prop',"false");
    //  }
    // });
    $("input[name='sid[]']").click(function(){
        // $('#select_all').attr('prop',"false");
        var _this = $(this);
        if(_this.attr('prop')){
            $("input[name='sid[]']:checked")
        }else if($("input[name='sid[]']:checked").length == 0){
            $("input[name='sid[]'][rs!="+_this.attr('rs')+"]").attr('disabled',false);
        }else if($("input[name='sid[]']:checked").length > 0){
            $("input[name='sid[]'][rs!="+_this.attr('rs')+"]").attr('disabled',true);
        }
    });
    $('#btn_batch_auth').click(function(){
    });
});
</script>
</body>
</html>
