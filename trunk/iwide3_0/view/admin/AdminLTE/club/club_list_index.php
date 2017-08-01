<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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
<style>
#coupons_table_length{display:block;}
.classification_n{margin-bottom:0px;}
.classification_n >div{width:auto;padding:0 40px;}
</style>
<div class="fixed_box bg_fff" id="batch_club">
    <div class="tile">社群客处理</div>
    <div class="f_b_con">审核通过该社群客？</div>
    <div class="h_btn_list clearfix" style="">
        <div class="confirm_batch" style="cursor: pointer">确认</div>
        <div class="cancel_batch f_r" style="cursor: pointer">取消</div>
    </div>
</div>

<div class="fixed_box bg_fff" id = "all_batch">
    <div class="tile">社群客处理</div>
    <div class="f_b_con">批量审核通过该社群客？</div>
    <div class="h_btn_list clearfix" style="">
        <div class="all_confirm_batch" style="cursor: pointer">确认</div>
        <div class="all_cancel_batch f_r" style="cursor: pointer">取消</div>
    </div>
</div>

<div style="color:#92a0ae;">
    <div class="over_xs">
        <div class="content-wrapper">
            <div class="banner p_0_20 bg_fff color_333">
               社群客/社群客列表
            </div>
            <div class="contents_list bg_fff">
              <div class="p_r_30 classification_n b_b_1 bg_fff" sytle="padding-left:20px;">
                <div class="add_active" id="club_count" count='<?php echo $amount['total'];?>'><a href="javascript:;"> 全部社群客（<?php echo $amount['total'];?>）</a></div>
                <div class="" id="apply_count" count='<?php echo $amount['apply'];?>'><a href="javascript:;"> 待审核社群客（<?php echo $amount['apply'];?>）</a></div>
              </div>
            </div>
            <div class="contents m_t_10 bg_fff">
              <div class="box-body">
                <table id="coupons_table" class="table-striped table-condensed dataTable no-footer new_tabels" style="width:100%;">
                  <thead class="bg_f8f9fb form_thead color_333">
                    <tr class="bg_f8f9fb form_title">
                      <th>编号</th>
                      <th>社群客名称</th>
                      <th>已开通／总数</th>
                      <th>可用价格</th>
                      <th>商城</th>
                      <th>适用门店</th>
                      <th>销售员／分销号</th>
                      <th>有效期</th>
                      <th>状态</th>
                      <th>间夜</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody class="containers dataTables_wrapper form-inline dt-bootstrap color_555">
                  <?php if(!empty($list)){ foreach($list as $clubList){ ?>
                    <tr class="form_con" id="club_<?php echo $clubList['club_id'];?>">
                      <td><?php echo $clubList['club_id'];?></td>
                      <td><?php echo $clubList['club_name'];?></td>
                      <td> <?php echo $clubList['amount'];?>/<?php echo $clubList['limited_amount'];?></td>
                      <td>
                          <?php if(!empty($clubList['price_code'])){
                                  $temp_code = explode(',',$clubList['price_code']);
                                  foreach($temp_code as $arr_temp_code){
                          ?>
                                <span class="nowraps blocks h_24"><?php if(isset($price_code[$arr_temp_code]['price_name']))echo $price_code[$arr_temp_code]['price_name'];?> </span>
                          <?php }}?>
                      </td>
                      <td>
                        <?php if(!empty($clubList['soma_code'])){
                            $temp_code = explode(',',$clubList['soma_code']);
                            foreach($temp_code as $arr_temp_code){
                                ?>
                                <span class="nowraps blocks h_24"><?php if(isset($all_soma_code[$arr_temp_code]->name))echo $all_soma_code[$arr_temp_code]->name;?> </span>
                            <?php }}?>
                      </td>
                      <td><?php if($clubList['hotel_id']==0 || empty($clubList['hotel_id']))echo '全部酒店';else echo '部分酒店';?></td>
                      <td><?php if(isset($staff[$clubList['id']]))echo $staff[$clubList['id']];else echo '';echo '-'.$clubList['id'];?></td>
                      <td><?php echo $clubList['valid_time'];?></td>
                        <td><?php echo $status[$clubList['status']];?></td>
                        <td><?php if(isset($c_nights['count'][$clubList['club_id']]))echo $c_nights['count'][$clubList['club_id']];else echo 0;?></td>
                      <td class="operations">
                        <a class="edits color_fff border_1_ff9900 bg_ff9900 template_btn m_l_4 m_r_4 m_b_5 m_t_3" href="<?php echo site_url('club/club_list/club_edit?ids=').$clubList['club_id'];?>">编辑</a>
                        <a class="looks color_ff9900 border_1_ff9900 template_btn m_l_4 m_r_4 m_b_5 m_t_3" href="<?php echo site_url('club/club_list/check?ids=').$clubList['club_id'];?>">查看</a>
                      </td>
                    </tr>
                  <?php }}?>
                  </tbody>
                </table>
              </div>
              <div class="box-body" style="display:none;">
                <table id="coupons_table1" class="table-striped table-condensed dataTable no-footer new_tabels" style="width:100%;">
                    <?php if(!empty($a_list)){ ?>
                        <a class="edits color_fff border_1_ff9900 bg_ff9900 template_btn m_l_4 m_r_4 nowraps  m_b_5 m_t_3"  style="float:left;cursor: pointer;margin-right: 40%" onclick=all_batch()>一键审核</a>
                    <?php }else{ ?>
                        <a class="edits color_fff border_1_ff9900 bg_ff9900 template_btn m_l_4 m_r_4 nowraps  m_b_5 m_t_3"  style="float:left;cursor: pointer;margin-right: 40%;background-color: #EEEED1;border:none ">一键审核</a>
                    <?php }?>
                  <thead class="bg_f8f9fb form_thead color_333">
                    <tr class="bg_f8f9fb form_title">
                      <th>编号</th>
                      <th>社群客名称</th>
                      <th>已开通／总数</th>
                      <th>可用价格</th>
                      <th>商城</th>
                      <th>适用门店</th>
                      <th>销售员／分销号</th>
                      <th>有效期</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody class="containers dataTables_wrapper form-inline dt-bootstrap color_555">
                      <?php if(!empty($a_list)){ foreach($a_list as $a_clubList){ ?>
                          <tr class="form_con" id="club_<?php echo $a_clubList['club_id'];?>">
                              <td><?php echo $a_clubList['club_id'];?></td>
                              <td><?php echo $a_clubList['club_name'];?></td>
                              <td> <?php echo $a_clubList['amount'];?>/<?php echo $a_clubList['limited_amount'];?></td>
                              <td>
                                  <?php if(!empty($a_clubList['price_code'])){
                                      $temp2_code = explode(',',$a_clubList['price_code']);
                                      foreach($temp2_code as $arr2_temp_code){
                                          ?>
                                          <span class="nowraps blocks h_24"><?php echo $price_code[$arr2_temp_code]['price_name'];?> </span>
                                      <?php }}?>
                              </td>
                              <td>
                                  <?php if(!empty($clubList['soma_code'])){
                                      $temp_code = explode(',',$clubList['soma_code']);
                                      foreach($temp_code as $arr_temp_code){
                                          ?>
                                          <span class="nowraps blocks h_24"><?php if(isset($all_soma_code[$arr_temp_code]->name))echo $all_soma_code[$arr_temp_code]->name;?> </span>
                                      <?php }}?>
                              </td>
                              <td><?php if($a_clubList['hotel_id']==0 || empty($a_clubList['hotel_id']))echo '全部酒店';else echo '部分酒店';?></td>
                              <td><?php if(isset($staff[$a_clubList['id']]))echo $staff[$a_clubList['id']];else echo '';echo '-'.$a_clubList['id'];?></td>
                              <td><?php echo $a_clubList['valid_time'];?></td>
                              <td class="operations">
                                  <a class="color_ff9900 border_1_ff9900 template_btn m_l_4 m_r_4 nowraps  m_b_5 m_t_3" href="<?php echo site_url('club/club_list/club_edit?ids=').$a_clubList['club_id'];?>">编辑资料</a>
                                  <a class="color_fff border_1_ff9900 bg_ff9900 template_btn nowraps m_l_4 m_r_4  m_b_5 m_t_3"  style="cursor: pointer" onclick=confirm_batch(<?php echo $a_clubList['club_id'];?>)>审核通过</a>
                              </td>
                          </tr>
                      <?php }}?>
                  </tbody>
                </table>
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
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/layDate.js"></script>
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
  $('.classification_n >div').click(function(){
    var Index=$(this).index();
    $(this).addClass('add_active').siblings().removeClass('add_active');
    $('.contents >div').eq(Index).show().siblings().hide();

  })
  $('.drow_list li').click(function(){
        $('#search_hotel').val($(this).text());
        $(this).addClass('cur').siblings().removeClass('cur');
  });
  $('.select_input input').bind('input propertychange',function(){
    var _this = $(this).parent().find('li');
    var val = $(this).val();
    if(val==''){
      _this.show();
    }else{
      _this.each(function(){
        if($(this).html().indexOf(val)>=0){
          $(this).show()
        }else{
          $(this).hide();
        }
      });
    }
  });

// $('#grid-btn-set').click(function(){
// $('#setModal').on('show.bs.modal', function (event) {
// //    modal.find('.modal-body input').val(recipient)
//   var str = '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name ();?>" value="<?php echo $this->security->get_csrf_hash ();?>" style="display:none;">';
//   $.getJSON('<?php echo site_url("hotel/hotel_report/get_cofigs?ctyp=ORDERS_BY_ROOMNIGHT")?>',function(data){
//     $.each(data,function(k,v){
//       str += '<div class="checkbox"><label><input type="checkbox" name="' + k + '"';
//       if(v.must == 1){
//         str += ' disabled checked ';
//       }else if(v.choose == 1){
//         str += ' checked ';
//       }
//       str += '>' + v.name + '</label></div>';
//     });
//     $('#setting_form').html(str);
//   });

// })});
  $('#coupons_table').DataTable({
      "aLengthMenu": [20,50,100,200],
      "iDisplayLength": 20,
      "bProcessing": true,
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "searching": true,
      "language": {
        "sSearch": "搜索",
        "lengthMenu": "每页显示 _MENU_ 条记录",
        "zeroRecords": "找不到任何记录. ",
        "info": "",
        "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });

  $('#coupons_table1').DataTable({
      "aLengthMenu": [8,50,100,200],
      "iDisplayLength": 8,
      "bProcessing": true,
      "paging": true,
      "lengthChange": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "searching": true,
      "language": {
        "sSearch": "搜索",
        "lengthMenu": "每页显示 _MENU_ 条记录",
        "zeroRecords": "找不到任何记录. ",
        "info": "",
        "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });
  var This;
  $('.adjustment').click(function(){
    This=$(this);
    if(This.html()=='退款'){
        $('.fixed_box').show();
        var str=This.parents('tr').find('.order_number').html();
        $('.f_b_con').html('退款确认:订单'+str+'确认退款后自动将订单实际支付退还给用户且不可撤回！');
    }

  })

  $('.confirms').click(function(){
    $('.fixed_box').hide();
    This.html('已退款');
    This.removeClass('color_F99E12').addClass('color_9b9b9b');
  })
  $('.cancel').click(function(){
    $('.fixed_box').hide();
  })


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

function confirm_batch(club_id){
    $('#batch_club').fadeIn();
    $('.confirm_batch').val(club_id);
}

$('.cancel_batch').click(function(){
    $('#batch_club').fadeOut();
})

$('.confirm_batch').click(function(){

     var club_id = $(this).val();

    $.get('<?php echo site_url('club/club_list/club_batch_post');?>',{
        club_id:club_id
    },function(data){
        if(data.status==0){
            $('#club_'+club_id).css('display','none');
            var count_total = parseInt($('#club_count').attr('count')) + 1;
            var count_apply = parseInt($('#apply_count').attr('count')) - 1;
            $('#club_count').attr('count',count_total);
            $('#apply_count').attr('count',count_apply);
            $('#club_count a').html('全部社群客（'+ count_total + ')');
            $('#apply_count a').html('待审核社群客（'+ count_apply + ')');
            $('#batch_club').fadeOut();
            alert(data.message);
        }else{
            alert(data.message);
        }
    },'json');

})

function batch_club(club_id){

        $.get('<?php echo site_url('club/club_list/club_batch_post');?>',{
            club_id:club_id
        },function(data){
            if(data){
                alert(data.message);
                location.reload();
            }
        },'json');
    $('#myModal').modal('hide');
}


function all_batch(){
    $('#all_batch').fadeIn();
}

$('.all_cancel_batch').click(function(){
    $('#all_batch').fadeOut();
})

$('.all_confirm_batch').click(function(){
    $('#all_batch').fadeOut();
    ensure_batch_all();
})

function ensure_batch_all(){

    $.get('<?php echo site_url('club/club_list/club_batch_auth');?>',{
        club_id:1
    },function(data){
        if(data){
            alert(data.message);
            location.reload();
        }
    },'json');
    $('#myModal').modal('hide');
}


</script>
</body>
</html>
