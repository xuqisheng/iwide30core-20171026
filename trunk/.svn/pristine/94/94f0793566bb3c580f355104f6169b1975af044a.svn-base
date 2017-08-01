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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
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


.fixed_box{padding:1% 0;width:450px;top:13%;}
.tile{padding:0 2%;font-size:12px;}
.h_btn_list{text-align:center;}
.tile{padding:0 2%;}
.display_flex >div{width:490px;}
#coupons_table1 tr{border-top:1px solid #d7e0f1;border-bottom:1px solid #d7e0f1;}
#coupons_table1 thead{color:#333;}
#coupons_table1 table td{font-size:12px;}
#coupons_table1_paginate a{padding:3px 6px;font-size:10px;}
#coupons_table1_filter{font-size:12px;}
#coupons_table1_filter input{margin-left:-25px;width:125px;height:23px;}
#coupons_table1_length{display:block;font-size:12px;margin-top:2px;margin-left:7px;}
.input_checkboxs label{margin-bottom:0px;}



</style>
<div class="containers_fixed">
    <div class="fixed_box bg_fff b_radius_6" style="position:relative;padding-top:0px;overflow:hidden;display:block;width: 600px">
        <form>
            <div class="member_tabel bg_f8f9fb center" style="padding:10px 0;">
<!--                <div class="">编辑成员信息 <img class="close_img" style="float:right;margin-right:10px;" src="--><?php //echo base_url(FD_PUBLIC) ?><!--/js/img/close2.png" /></div>-->
                <div class="">编辑成员信息</div>
            </div>
            <div class="hotel_tabel" style="">
                <table id="coupons_table1" class="table-condensed no-footer" style="width:100%;">
                    <thead class="form_thead">
                    <tr class="form_title" style="border-bottom:0px;">
                        <th>名称</th>
                        <th class="fixed_box_name">懒人101</th>
                        <input type='hidden' id="saler_qrcode_id" value="">
                    </tr>
                    </thead>
                    <tbody class="dataTables_wrapper form-inline dt-bootstrap">
                    <tr class="form_con" style="border-top:0px;">
                        <td>社群客数量</td>
                        <td><input type="text" id='edit_amount'></td>
                    </tr>
                    <tr class="form_con">
                        <td>可用价格</td>
                        <td class="available_price">
                            <div class="w_450 t_a_l m_auto" id='club_price_code'>
                                <div class="input_checkboxs"><input type="checkbox" id="7" name="room_ids"><label for="7" style="width:auto;">协议价a</label></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="form_con">
                        <td style="width: 20%">无需审核</td>
                        <td class="examine"  style="width: 80%">
                            <div class="w_450 t_a_l m_auto" id='club_auth_price_code'>
<!--                                <div class="input_checkboxs"><input type="checkbox" id="4" name="room_ids"><label for="4" style="width:auto;">协议价a</label></div>-->
                            </div>
                        </td>
                    </tr>
                    <tr class="form_con">
                        <td>商城</td>
                        <td class="available_soma_price">
                            <div class="w_450 t_a_l m_auto" id='soma_code'>
<!--                                <div class="input_checkboxs"><input type="checkbox" id="soma_club" name="soma_club"><label for="soma_club" style="width:auto;">协议价a</label></div>-->
                            </div>
                        </td>
                    </tr>
                    <tr class="form_con">
                        <td >粉丝归属</td>
                        <td class="fans_txt">
                            <div class="w_300 t_a_l m_auto">
                                <span class="input_radios">
                                    <input class="" type="radio" id="star_2" name="edit_is_grade" value="1">
                                    <label for="star_2">开</label>
                                </span>
                                <span class="input_radios">
                                    <input class="" type="radio" id="star_1" name="edit_is_grade" value="0">
                                    <label for="star_1">关</label>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr class="form_con">
                        <td>状  态</td>
                        <td  class="switch_txt">
                            <div class="w_300 t_a_l m_auto">
                                <span class="input_radios">
                                    <input class="" type="radio" id="star_3" name="edit_status" value="1">
                                    <label for="star_3">有效</label>
                                </span>
                                <span class="input_radios">
                                    <input class="" type="radio" id="star_4" name="edit_status" value="2">
                                    <label for="star_4">失效</label>
                                </span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="clearfix m_t_10 center" >
                <div class="confirms color_fff bg_ff9900 center template_btn" style="border:0px;">保存</div>
                <div class="color_fff bg_ff9900 center template_btn" id="cancel_save" style="border:0px;margin-left: 50px;cursor:pointer">取消</div>
            </div>
        </form>
    </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_xs">
        <div class="content-wrapper">
            <div class="banner p_0_20 bg_fff color_333">
               社群客/销售员列表
            </div>
            <div class="contents m_t_10 bg_fff">
        <!-- <div class="head_cont contents_list bg_fff">
        </div> -->
        <div class="box-body">
          <table id="coupons_table" class="table-striped table-condensed dataTable no-footer new_tabels color_333" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                <th>分销号</th>
                <th>姓名</th>
                <th>已开通／总数</th>
                <th>可用价格</th>
                <th>商城</th>
                <th>间夜数</th>
                <th>绩效金额</th>
                <th>粉丝归属</th>
                <th>状态</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap color_555">
            <?php if(!empty($club_staffs)){ foreach($club_staffs as $staff){ ?>
              <tr class="form_con">
                <td><?php echo $staff['qrcode_id'];?></td>
                <td class="table_name"><?php echo $staff['name'];?></td>
                <td class="total"> <?php echo $staff['amount'];?>/<?php echo $staff['limited_amount'];?></td>
                <td>
                    <?php if(!empty($staff['club_price_code'])){
                                $array_price_code = explode(',',$staff['club_price_code']);
                                foreach($array_price_code as $club_price_code){ if(isset($price_code[$club_price_code])){ ?>
                                    <span class="nowraps blocks h_24"><?php echo $price_code[$club_price_code]['price_name'];?> </span>
                    <?php }}}?>
                </td>
                <td>
                  <?php if(!empty($staff['soma_code'])){
                      $array_soma_code = explode(',',$staff['soma_code']);
                      foreach($array_soma_code as $arr_soma_code){ if(isset($soma_code[$arr_soma_code])){ ?>
                          <span class="nowraps blocks h_24"><?php echo $soma_code[$arr_soma_code]->name;?> </span>
                      <?php }}}?>
                </td>
                <td><?php if(isset($club_orders['staff_count'][$staff['qrcode_id']]))echo $club_orders['staff_count'][$staff['qrcode_id']];else echo 0;?></td>
                <td><?php if(isset($club_grade[$staff['qrcode_id']]['grade_total']))echo $club_grade[$staff['qrcode_id']]['grade_total'];else echo 0;?></td>
                <td class="fans_btn"><?php if($staff['is_grade']==1)echo '开';elseif($staff['is_grade']==0) echo '关';?></td>
                <td class="switch_btn"><?php if($staff['status']==0)echo '申请中';elseif($staff['status']==1) echo '有效';elseif($staff['status']==2) echo '失效';?></td>
                <td class="">
                  <a class="edits color_fff border_1_ff9900 bg_ff9900 template_btn nowraps m_l_10 m_r_10 looks_hotel" href="javascript:;" saler="<?php echo $staff['qrcode_id'];?>">编辑</a>
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
  var bool1=true;
  var bool2=true;
  $('.looks_hotel').click(function(){
      var qrcode_id = $(this).attr('saler');
      $.post('<?php echo site_url('club/club/get_staff_info')?>',{
          saler:qrcode_id,
          <?php echo $csrf_token?>:'<?php echo $csrf_value?>'
      },
      function(data){
          var html_price_code = '';
          var html_auth_price_code = '';
          var html_soma_code = '';
//          console.log(data.price_code);
          $(".fixed_box_name").html(data.name);
          $("#edit_amount").val(data.limited_amount);
          $("#saler_qrcode_id").val(qrcode_id);
          if(data.club_price_code==undefined){
              data.club_price_code = '';
//              alert('该销售员没有可用的价格代码');return;
          }
          var arr_price_code = data.club_price_code.split(',');
          var arr_auth_price_code = data.auth_price_code.split(',');
          var arr_soma_code = data.soma_code.split(',');

          $.each(data.price_code,function(k,v){
              html_price_code += '<div class="input_checkboxs" style="float: left;width: 45%"><input type="checkbox" ';
              $.each(arr_price_code,function(key,code){
                  if(k==code){
                      html_price_code += 'checked ';
                  }
              })
              html_price_code += 'id='+k+' name="edit_price_code[]" value='+k+'><label for='+k+' style="width:auto;"' ;
              html_price_code +=  '>'+v.price_name+'</label></div>';
          });
          $('#club_price_code').html(html_price_code);

          if(arr_price_code!=''){
              $.each(arr_price_code,function(k,v){
                  if(data.price_code[v] !=undefined){
                      html_auth_price_code += '<div class="input_checkboxs"><input type="checkbox" ';
                      $.each(arr_auth_price_code,function(key,code){
                          if(v==code){
                              html_auth_price_code += 'checked ';
                          }
                      })
                      html_auth_price_code += 'id=auth_'+v+' name="edit_auth_price_code[]" value='+v+'><label for=auth_'+v+' style="width:auto;"' ;
                      html_auth_price_code +=  '>'+data.price_code[v].price_name+'</label></div>';
                  }
              });
              $('#club_auth_price_code').html(html_auth_price_code);
          }


          $.each(data.all_soma_code,function(k,v){
              html_soma_code += '<div class="input_checkboxs" style="float: left;width: 45%"><input type="checkbox" ';
              $.each(arr_soma_code,function(key,code){
                  if(k==code){
                      html_soma_code += 'checked ';
                  }
              })
              html_soma_code += 'id=soma_'+k+' name="edit_soma_code[]" value='+k+'><label for=soma_'+k+' style="width:auto;"' ;
              html_soma_code +=  '>'+v.name+'</label></div>';
          });
          $('#soma_code').html(html_soma_code);

//          var namr=$(this).parents('tr').find('.table_name').html();
          var fans_btn=$(this).parents('tr').find('.fans_btn').html();
          if(data.is_grade==1){
              bool1=true;
          }else if(data.is_grade==0){
              bool1=false;
          }
          if(data.status==1){
               bool2=true;
          }else if(data.status==2){
               bool2=false;
          }
          var switch_btn=$(this).parents('tr').find('.switch_btn').html();
          if(bool1){
              $(".fans_txt .input_radios:nth-of-type(1)").find('input').prop('checked',true);
              $(".fans_txt .input_radios:nth-of-type(2)").find('input').prop('checked',false);
          }else{
              $(".fans_txt .input_radios:nth-of-type(1)").find('input').prop('checked',false);
              $(".fans_txt .input_radios:nth-of-type(2)").find('input').prop('checked',true);
          }
          if(bool2){
              $(".switch_txt .input_radios:nth-of-type(1)").find('input').prop('checked',true);
              $(".switch_txt .input_radios:nth-of-type(2)").find('input').prop('checked',false);
          }else{
              $(".switch_txt .input_radios:nth-of-type(1)").find('input').prop('checked',false);
              $(".switch_txt .input_radios:nth-of-type(2)").find('input').prop('checked',true);
          }
//          $(".fixed_box_name").html(namr);
          $(".containers_fixed").fadeIn();
      },'json');
  })

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
  var This;
  $('.adjustment').click(function(){
    This=$(this);
    if(This.html()=='退款'){
        $('.containers_fixed').show();
        var str=This.parents('tr').find('.order_number').html();
        $('.f_b_con').html('退款确认:订单'+str+'确认退款后自动将订单实际支付退还给用户且不可撤回！');
    }

  })

  $('.confirms').click(function(){
      var json_dat={};
      var auth_price_codes=[];
      var club_price_codes=[];
      var soma_codes=[];
        $('#club_auth_price_code input:checked').each(function(index, el) {
            auth_price_codes.push($(this).val());
        });
        $('#club_price_code input:checked').each(function(index, el) {
            club_price_codes.push($(this).val());
        });
        $('#soma_code input:checked').each(function(index, el) {
            soma_codes.push($(this).val());
        });
        json_dat.limited_amount=$('#edit_amount').val();
        json_dat.auth_price_code=auth_price_codes;
        json_dat.club_price_code=club_price_codes;
          json_dat.soma_codes=soma_codes;
        json_dat.status=$('input[name=edit_status]:checked').val();
        json_dat.is_grade=$('input[name=edit_is_grade]:checked').val();
        json_dat.saler=$("#saler_qrcode_id").val();
        json_dat.<?php echo $csrf_token?>='<?php echo $csrf_value?>';
        var json_str=JSON.stringify(json_dat);
      $.getJSON('<?php echo site_url('club/club/edit_post')?>',{
              'data':json_str,
          <?php echo $csrf_token?>:'<?php echo $csrf_value?>'
          },
      function(data){
          if(data.status==1){
              window.location.reload();
          }else{
              alert(data.message);
          }
      })
    $('.containers_fixed').hide();
  })


  $('#cancel_save').click(function(){
    $('.containers_fixed').hide();
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
</script>
</body>
</html>
