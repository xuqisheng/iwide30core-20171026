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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">

<style>

    .fixed_box_remark{position:fixed;top:30%;left:48%;z-index:9999;border:1px solid #d7e0f1;border-radius:5px;padding:1% 2%;display:none;}
    .tile{font-size:15px;text-align:center;margin-bottom:4%;}
    .f_b_con{font-size:13px;margin-bottom:8%;width:245px;}
    .f_b_con span:first-child{display:inline-block;width:80px;text-align:right;margin-right:5px;}

    .pagination{margin-top:0px;margin-bottom:0px;}
    .btn_list_r span{margin-right:10px;}
    .btn_list_r span:last-child{margin-right:0px;}
    .f_b_con i{right:8px;top:1px;font-style:normal;}

    .f_con >div{display:inline-block;}
    .menoy_input{
        margin: auto;
        height: 68px;
        line-height: 68px;
        font-size: 21px;
        text-align: center;
        /* padding: 0 5px; */
    }

    .confirms_remark{cursor: pointer}
    .cancel_remark{cursor: pointer}
</style>
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

    <!--
<div class="fixed_box bg_fff w_330">
  <div class="tile"></div>
  <div class="f_b_con">
    退款确认:订单12346579确认退款后自动将订单实际支付退还给用户且不可撤回！
  </div>
  <div class="h_btn_list clearfix" style="">
    <div class="actives confirms">保存</div>
    <div class="cancel f_r">取消</div>
  </div>
</div>
-->
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
               订单流水
            </div>
            <div class="contents">
          <?php echo form_open('okpay/orders/grid/','class="form-inline"')?>
          <div class="head_cont contents_list bg_fff">
          <div class="j_head">
            <div>
              <span>酒店名称</span>
              <span class="input-group w_200 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                <input placeholder="选择或输入关键字" type="text" value="<?php echo empty($posts['hotel_name']) ? '' : urldecode($posts['hotel_name'])?>" name="hotel_name" style="color:#92a0ae;" class="w_200 moba" id="search_hotel" >
                <ul class="drow_list silde_layer">
                    <?php if(!empty($hotels)){
                        foreach($hotels as $k=>$v){
                    ?>
                      <li value="<?php echo $k?>"><?php echo $v?></li>
                    <?php }}?>
                </ul>
              </span>
            </div>
            <div>
              <span>订单时间</span>
              <span class="t_time"><input name="begin_time" type="text" id="datepicker" class="moba" value="<?php echo empty($posts['pay_begin_time']) ? date('Y-m-d H:i') : $posts['pay_begin_time']?>"></span>
                <font>至</font>
                <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="moba" value="<?php echo empty($posts['pay_end_time']) ? date('Y-m-d H:i') : $posts['pay_end_time']?>"></span>
            </div>
            <div>
              <span>交易场景</span>
              <span>
                <select class="w_90" name="pay_type">
                    <option value="" <?php if(empty($posts['pay_type'])):echo ' selected';endif;?>>-- 全部 --</option>
                    <?php foreach ($ground_types as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['pay_type']) && $key == $posts['pay_type']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>

              </span>
            </div>
          </div>
        <div class="j_head">
            <div>
                <span>订单号</span>
                <span><input class="w_200" name="out_trade_no" type="text" value="<?php echo empty($posts['out_trade_no']) ? '' : $posts['out_trade_no']?>"/></span>
            </div>
            <div>
                <span>订单状态</span>
                <span>
                    <select class="w_90" name="pay_status">
                        <option value=""<?php if(empty($posts['pay_status'])):echo ' selected';endif;?>>-- 全部 --</option>
                        <?php foreach ($pay_status_list as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['pay_status']) && $key == $posts['pay_status']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>

                </span>
            </div>
        </div>
          <div  class="h_btn_list" style="">
            <button type="submit" class="actives">&nbsp;检索</button>
            <button type="submit" name="export"  class="actives" value="1">&nbsp;导出</button>
          </div>
        </div>
                </form>
        <div class="box-body">
          <table  class="no-footer" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                  <?php

                  foreach ($confs as $key=>$item){
                      if($item == 'update_time'){continue;}
                      ?>
                      <th width="6%" class="sorting" tabindex="0" rowspan="1" colspan="1" ><?php echo $all_keys[$item];?></th>

                      <?php
                  }
                  ?>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
            <?php
            foreach ($res as $item ):?>
              <tr class="form_con">
<!--                  <td>--><?//=$item['id']?><!--</td>-->
                  <td><?=$item['hotel_name']?></td>
                  <td><?=$item['pay_type_desc']?></td>
                  <td><?=$item['nickname']?></td>
                  <td><?=$item['money']?></td>
                  <td><?=$item['no_sale_money']?></td>
                  <td><?=$item['discount_money']?></td>
<!--                  <td>--><?//=$item['sale']?><!--</td>-->
                  <td><?php
                      echo empty($item['create_time']) ? '--' : date("Y-m-d H:i:s",$item['create_time']);
                      ?></td>
                 <!-- <td><?php
/*                      echo empty($item['update_time']) ? '--' : date("Y-m-d H:i:s",$item['update_time']);
                      */?></td>-->
                  <td><?php
                      echo empty($item['pay_time']) ? '--' : date("Y-m-d H:i:s",$item['pay_time']);
                      ?></td>
                  <td><?php echo $item['out_trade_no']?></td>
                  <td><?php echo formatMoney(($item['pay_money']*100 - $item['refund_money']*100)/100)?></td>
                  <td><?php echo $item['refund_money']?></td>
                  <td>
                      <?php
                      echo isset($paytype[$item['pay_way']])?$paytype[$item['pay_way']]:'';

                      echo isset($pay_status_list[$item['pay_status']])? '--'. $pay_status_list[$item['pay_status']]:'';

                      /*if(intval($item['pay_status']) == 1){
                          echo "未支付";
                      }elseif(intval($item['pay_status']) == 3){
                          echo "已支付";
                      }elseif(intval($item['pay_status']) == 4){
                          echo "已退款";
                      }elseif(intval($item['pay_status']) == 0){
                          echo "已取消";
                      }*/
                      ?>
                  </td>
                  <!--
                  <td>
                      <?php
                      echo isset($paytype[$item['pay_way']])?$paytype[$item['pay_way']]:'';
                      /*if(intval($item['pay_way']) == 1){
                          echo "微信";
                      }elseif(intval($item['pay_way']) == 2){
                          echo "余额";
                      }elseif(intval($item['pay_way']) == 3){
                          echo "扫码设备";
                      } elseif(intval($item['pay_way']) == 11){
                          echo "威富通";
                      }*/
                      ?>
                  </td>
                  -->
                  <td>
                      <?php echo $item['remark']?>
                  </td>
                  <td>
                      <a style="color: #3c8dbc;" href="<?php echo site_url('okpay/orders/edit?ids='.$item['id'])?>">查看订单</a>
                      <?php //if(intval($item['pay_status'])==3){//已经支付才可以退款?>
                      <?php if($has_refund_acl && intval($item['pay_status'])==3){?>
                          | <a style="color: red;cursor: pointer" onclick="to_refund(<?php echo $item['id'].",'".$item['pay_money']."','".$item['out_trade_no']."'"?>)">退款</a>
                      <?php }elseif($has_refund_acl){?>
                          | <span>退款</span>
                      <?php }//}?>
                        |
                      <a style="color: #3c8dbc;" href="javascript:void(0)" data ='<?php echo $item['id'];?>' content="<?php echo $item['remark'];?>" class="remark">备注</a>
                  </td>
               <!-- <td>
                    <span>2016.10.10</span><br>
                    <span>16:00:00</span>
                </td>
                <td>
                    <span>2016.10.10</span><br>
                    <span>16:00:00</span>
                </td>
                <td class="order_number">12346987</td>
                <td>已支付</td>
                <td>微信支付</td>
                <td><a class="color_F99E12 adjustment" href="javascript:;">退款</a></td>-->
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
        </div>
      </div>
        </div>
    </div>
</div>

<!--弹出窗效果-->

<div class="fixed_box bg_fff">
    <div class="tile">退款</div>
    <div class="con_1">
        <div class="f_b_con center">
            <input class="menoy_input" id="menoy_input" type="text" placeholder="请输入退款金额" />
        </div>
        <div class="f_b_con">当前订单最多可退<b id="show_pay_money" style="color: red">30元</b></div>
    </div>
    <div class="h_btn_list clearfix center" style="">
        <div class="actives confirms">确认退款</div>
        <div class="cancel">取消</div>
        <input type="hidden" id="o_id" value=""/>
        <input type="hidden" id="o_sn" value=""/>
        <input type="hidden" id="o_money" value=""/>
    </div>
</div>

<div class="fixed_box_remark bg_fff">
    <div class="tile">备注</div>
    <div class="con_1">
        <div class="f_b_con center">
            <textarea id="remark_info" style="height: 120px;width: 100%;padding: 5px 10px;color: #999">请输入订单备注</textarea>
        </div>
    </div>
    <div class="h_btn_list clearfix center" style="">
        <div class="actives confirms_remark">保存</div>
        <div class="cancel_remark">取消</div>
        <input type="hidden" id="remark_order_id" value=""/>
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
  var This;
  $('.adjustment').click(function(){
    This=$(this);
    if(This.html()=='退款'){
        $('.fixed_box').show();
        var str=This.parents('tr').find('.order_number').html();
        $('.f_b_con').html('退款确认:订单'+str+'确认退款后自动将订单实际支付退还给用户且不可撤回！');
    }

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
//<!--杰 2016/8/30-->
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

//备注

$('.confirms_remark').click(function()
{
    //隐藏层
    var data = {};
    data.remark = $('#remark_info').val();
    data.id = $('#remark_order_id').val();

    if (data.remark == '请输入订单备注')
    {
        alert('请输入备注信息');
        return false;
    }

    data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
    $.ajax({
        dataType:"json",
        type:'post',
        url:"<?php echo site_url('/okpay/orders/save_remark')?>",
        data:data,
        success: function(res)
        {
            alert(res.msg);
            if (res.status == 1)
            {
               $('.fixed_box_remark').hide();
                window.location.reload();
            }
        },
        complete: function()
        {
           // _this.html('提交');
        }
    })


    //This.removeClass('color_F99E12').addClass('color_9b9b9b');
})
$('.cancel_remark').click(function(){
    $('.fixed_box_remark').hide();
    $('#remark_info').val('请输入订单备注');
})

$('.remark').click(function(){
    $('.fixed_box_remark').show();
    $('#remark_order_id').val($(this).attr('data'));

    var remark_info = $(this).attr('content');
    if (remark_info != '')
    {
        $('#remark_info').val(remark_info);
    }
})


$("#remark_info").focus(function()
{
    var remark_info = $('#remark_info').val();
    if (remark_info == '请输入订单备注')
    {
        $('#remark_info').val('');
    }
});

//退款

$('.confirms').click(function(){

    var o_money = $('#o_money').val();
    var menoy_input = $('#menoy_input').val();
    var o_id = $('#o_id').val();
    var o_sn = $('#o_sn').val();

    if (menoy_input == '')
    {
        alert('请输入退款金额');
        return false;
    }

    var preg = /^([\d]{0,10}|0)(\.[\d]{1,2})?$/;
    if(!preg.test(menoy_input))
    {
        alert("请输入正确的退款金额");
        return false;
    }

    if ((o_money *100) < (menoy_input *100))
    {
        alert('最多可退'+o_money+'元');
        return false;
    }

    if( (typeof o_id == 'undefined') || (typeof o_money == 'undefined') || (typeof o_sn == 'undefined'))
    {
        alert('data error!');
        return false;
    }
    else
    {
        var a=confirm("退款确认:订单"+o_sn+"确认退款后自动将订单实际支付退还给用户且不可撤回！");
        if(a)
        {
            var data = {};
            data.money = menoy_input;
            data.id = o_id;
            data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
                dataType:"json",
                type:'post',
                url:"<?php echo site_url('okpay/orders/refund')?>",
                data:data,
                success: function(res)
                {
                    if(data.status == 1)
                    {
                        alert(res.message);
                    }
                    else
                    {
                        alert(res.message);
                    }
                },
                complete: function()
                {
                    // _this.html('提交');
                }
            })
        }
    }

    $('.fixed_box').hide();
    //This.html('已退款');
    //This.removeClass('color_F99E12').addClass('color_9b9b9b');

})
$('.cancel').click(function(){
    $('.fixed_box').hide();
})


function to_refund(o_id,o_money,o_sn)
{
    $('#show_pay_money').html(o_money+'元');
    $('#o_money').val(o_money);
    $('#o_id').val(o_id);
    $('#o_sn').val(o_sn);
    $('.fixed_box').show();  //支付输入
}
</script>
</body>
</html>
