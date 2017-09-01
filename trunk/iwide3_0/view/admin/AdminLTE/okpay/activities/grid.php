<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script><link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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
<style>
    .h_btn_list> button,.h_btn_list> a{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;background:#ff9900;color:#fff}
</style>


<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
               折扣管理
            </div>
            <div class="contents">
                <?php echo form_open('okpay/activities/grid','class="form-inline"')?>
                <div class="head_cont contents_list bg_fff">
          <div class="j_head">
            <!-- <div>
              <span>酒店名称</span>
              <span class="input-group w_200" style="position:relative;display:inline-flex;" id="drowdown">
                <input placeholder="选择或输入关键字" type="text" style="color:#92a0ae;" class="form-control w_200 moba" id="search_hotel" >
                <ul class="drow_list">
                      <li value="">碧桂园凤凰大酒店</li>
                      <li value="">北京金茂万丽酒店</li>
                      <li value="">上海街町酒店</li>
                      <li value="">深圳威尼斯酒店</li>
                      <li value="">街町酒店广州测试店</li>
                      <li value="">广州金房卡大酒店</li>
                </ul>
              </span>
            </div> -->
              <div>
                  <span>关联酒店</span>
              <span>
                <select class="w_90" name="hotel_id">
                    <option value="-1"<?php if(empty($posts['hotel_id'])):echo ' selected';endif;?>>-- 全部 --</option>
                    <?php
                    foreach ($hotels as $key=>$val):?><option value="<?php echo $key?>"<?php if(isset($posts['hotel_id']) && $key == $posts['hotel_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>

                  </select>
              </span>
              </div>
            <div>
              <span>优惠时间</span>
              <span class="t_time"><input name="begin_time" type="text" id="datepicker" class="moba" value="<?php echo empty($posts['begin_time']) ? '' : $posts['begin_time']?>"></span>
                <font>至</font>
                <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="moba" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>"></span>
            </div>
            <div>
              <span>优惠状态</span>
              <span>
                <select class="w_90" name="status">
                     <option value="-1">-- 全部 --</option>
                     <option value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' selected ':''?>>启用</option>
                     <option value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' selected ':''?>>不启用</option>
                                            
                </select>
              </span>
            </div>
          </div>
        <div class="j_head">
            <div>
                <span>优惠方式</span>
                <span>
                    <select class="w_90" name="isfor">
                        <option value="-1">-- 全部 --</option>
                        <option value="1" <?php echo isset($posts['isfor'])&&$posts['isfor']==1?' selected ':''?>>每满减</option>
                        <option value="2" <?php echo isset($posts['isfor'])&&$posts['isfor']==2?' selected ':''?>>单满减</option>
                        <option value="3" <?php echo isset($posts['isfor'])&&$posts['isfor']==3?' selected ':''?>>随机减</option>             
                    </select>
                </span>
            </div>

            <div>
                <span>关联场景</span>
                <span>
                    <select class="w_90" name="type_id">
                        <option value="-1"<?php if(empty($posts['type_id'])):echo ' selected';endif;?>>-- 全部 --</option>
                        <?php
                        foreach ($types as $key=>$val):?><option value="<?php echo $key?>"<?php if(isset($posts['type_id']) && $key == $posts['type_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?>
                    </select>
                </span>
            </div>
            
        </div>
          <div  class="h_btn_list" style="">
             <button type="submit" class="actives">&nbsp;检索</button>
           <!-- <div>批量导出</div>-->
            <a href="<?php echo site_url('/okpay/activities/add')?>
">新增满减</a>
            <a href="<?php echo site_url('/okpay/activities/sj_add')?>
">新增随机减</a>
          </div>
        </div></form>
        <div class="box-body">
          <table class="no-footer" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                <th>优惠编号</th>
                <th>关联酒店</th>
                <th>关联场景</th>
                <th>优惠名称</th>
                <th>优惠方式</th>
                <th>优惠金额</th>
                <th>开始时间</th>
                <th>结束时间</th>
                  <th>创建时间</th>
                <th>优惠状态</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
            <?php if(!empty($res)){
            foreach($res as $k=>$v){
            ?>
              <tr class="form_con">
                  <td><?php echo $v['id']?></td>
                  <td><?php echo isset($hotels[$v['hotel_id']])?$hotels[$v['hotel_id']]:''?></td>
                  <td><?php echo isset($types[$v['type_id']])?$types[$v['type_id']]:''?></td>
                  <td><?php echo $v['title']?></td>
                  <td><?php echo $isfor[$v['isfor']]?></td>
                  <td><?php echo $v['isfor']==3?$v['minmax']:$v['discount_amount']?></td>
                  <td><?php echo empty($v['begin_time'])?'--':date('Y-m-d',$v['begin_time'])?></td>
                  <td><?php echo empty($v['end_time'])?'--':date('Y-m-d',$v['end_time'])?></td>
                  <td><?php echo empty($v['create_time'])?'--':date('Y-m-d',$v['create_time'])?></td>
                  <td><?php echo $v['status']==1?'启用':'未启用'?></td>
                  <?php if($v['isfor'] == 3){?>
                      <td><a class="btn btn-default bg-green" href="<?php echo site_url('okpay/activities/sj_edit?ids='.$v['id'])?>">编辑</a></td>
                  <?php }else{?>
                      <td><a class="btn btn-default bg-green" href="<?php echo site_url('okpay/activities/edit?ids='.$v['id'])?>">编辑</a></td>
                  <?php }?>
              </tr>
            <?php }}?>
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
?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<!--日历调用结束-->
<script>
;!function(){
  laydate({
     elem: '#datepicker'
  })
  laydate({
     elem: '#datepicker2'
  })
}();
</script>
<script>
$(function(){
  $('.drow_list li').click(function(){
        $('#search_hotel').val($(this).text());
        $(this).addClass('cur').siblings().removeClass('cur');
    });
  $('#coupons_table').DataTable({
        "aLengthMenu": [8,50,100,200],
      "iDisplayLength": 8,
      "bProcessing": true,
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "searching": false,
      "language": {
        "sSearch": "搜索",
        "lengthMenu": "每页显示 _MENU_ 条记录",
        "zeroRecords": "找不到任何记录. ",
        "info": "",
        //"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });
  // var This;
  // $('.adjustment').click(function(){
  //   This=$(this);
  //   if(This.html()=='退款'){
  //       $('.fixed_box').show();
  //       var str=This.parents('tr').find('.order_number').html();
  //       $('.f_b_con').html('退款确认:订单'+str+'确认退款后自动将订单实际支付退还给用户且不可撤回！');
  //   }
    
  // })

  // $('.confirms').click(function(){
  //   $('.fixed_box').hide();
  //   This.html('已退款');
  //   This.removeClass('color_F99E12').addClass('color_9b9b9b');
  // })
  // $('.cancel').click(function(){
  //   $('.fixed_box').hide();
  // })


  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })
  $('.news').click(function(){
      $('.j_toshow').animate({"right":"0px"},800);
  });
  $('.close_btn').click(function(){
      $('.j_toshow').animate({"right":"-330px"},800);
  });
  var tips=$('#tips');
  $('.btn_o').click(function(){
    //console.log( decodeURIComponent($(".form").serialize(),true));
    start=$('.t_time').find('input[name="start_t"]').val().replace(/-/g,'');
    end=$('.t_time').find('input[name="end_t"]').val().replace(/-/g,'');
    if(start!=''&&start!=undefined){
      if(isNaN(start)){
        tips.html('开始日期错误');
        setout(tips);
        return false;
      }
      if(end!=''&&end!=undefined){
        if(isNaN(end)||end<start){
          tips.html('结束日期错误或大于开始日期');
          setout(tips);
          return false;
        }
      }
    }
  })
})
<!--杰 2016/8/30-->
function setout(obj){
  setTimeout(function(){
    obj.fadeOut();  
  },2000) 
}
var orderid='';
function show_detail(obj){
  $('#status_detail').html('');
  $('#myModalLabel').html('单号：');
  var temp='';
  orderid='';
  $.get('<?php echo site_url('hotel/orders/order_status')?>',{
    oid:$(obj).attr('oid'),
    hotel:$(obj).attr('h')
  },function(data){
    orderid=data.order.orderid;
    if(data.after!=''){
      temp+='<select id="after_status">';
      $.each(data.after,function(i,n){
        if(i!=4)
          temp+='<option value="'+i+'">'+n+'</option>';   
      });
      temp+='</select>';
    }else{
      temp+=data.order.status_des;
      orderid='';
    }
    $('#status_detail').html(temp);
    $('#myModalLabel').append(data.order.orderid);
  },'json');
}
function change_status(){
  if(orderid){
    $.get('<?php echo site_url('hotel/orders/update_order_status');?>',{
      oid:orderid,
      status:$('#after_status').val()
    },function(data){
      if(data==1){
        alert('修改成功');
        location.reload();
      }else{
        alert('修改失败');
      }
    });
  }
  $('#myModal').modal('hide');
}
</script>
</body>
</html>
