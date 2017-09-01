<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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
.table{display:table;margin-bottom:0px;}
.table >div{display: table-cell;width:137px;}
.contents{padding:10px 0px 85px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont >div{margin-bottom:10px;cursor:pointer;}
.head_cont >div:last-child{margin-bottom:0px;}
.h_btn_list .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> div:last-child{margin-right:0px;}
.f_r_con >div,.j_head >div{display:inline-block;}
.f_r_con >div{margin-right:30px;}
.classification{height:30px;line-height:30px;}
.classification >div{width:70px;display:inline-block;text-align:center;height:30px;}
.classification .add_active{border-bottom:3px solid #ff9900;}
.fomr_term{height:30px;line-height:30px;}
.classification >div,.all_open_order{cursor:pointer;}
.template >div{text-align:center;}
.template >div:nth-of-type(1){width:250px;text-align:left;padding-left:10px;}
.template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
.template_span{display:inline-block;margin-top:2px;}
.template_btn{padding:1px 8px;border-radius:3px;}
.temp_con >div >span{line-height:1.7;}
.room{width:52px;display:inline-block;}
.con_list > div:nth-child(odd){background:#fafcfb;}
.con_list{display:none;}
</style>
<?php
  // $filter = $this->input->get(null, true);
  // $base_page = array('page_num' => 1, 'page_size' => 20);
?>
<div class="fixed_box bg_fff">
  <div class="tile">订单修改确认</div>
  <div class="f_b_con">确认要将当前订单状态修改为“入住”状态？</div>
  <div class="h_btn_list clearfix" style="">
    <div class="actives confirms">确认</div>
    <div class="cancel f_r">取消</div>
  </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;">
            <div class="banner bg_fff p_0_20">订单管理</div>
            <div class="contents">
              <form action="<?php echo Soma_const_url::inst()->get_url('*/*/*' ); ?>" class="form" method='get' id="this_form" >
                <div class="head_cont contents_list bg_fff">
                <div class="j_head">
                 <!--  <div>
                    <span>酒店名称</span>
                    <span class="input-group w_200 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                      <input placeholder="选择或输入关键字" type="text" style="color:#92a0ae;" class="form-control w_200 moba" id="search_hotel">
                      <ul class="drow_list silde_layer">
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
                      <span>订单号</span>
                      <span><input class="w_200" name="order_id" type="text" value="<?php echo isset($filter['order_id']) ? $filter['order_id'] : ''?>"></span>
                  </div>
                  <div>
                      <span>商品名称</span>
                      <span><input class="w_200" name="pname" type="text" value="<?php echo isset($filter['pname']) ? $filter['pname'] : ''?>"></span>
                  </div>
                  <div>
                      <span>购买人</span>
                      <span><input class="w_200" name="contact" type="text" value="<?php echo isset($filter['contact']) ? $filter['contact'] : ''?>"></span>
                  </div>
                  <div>
                      <span>联系电话</span>
                      <span><input class="w_200" name="mobile" type="text" value="<?php echo isset($filter['mobile']) ? $filter['mobile'] : ''?>"></span>
                  </div>
                  <div>
                      <span>实付金额</span>
                      <span><input class="w_200" name="real_grand_total" type="text" value="<?php echo isset($filter['real_grand_total']) ? $filter['real_grand_total'] : ''?>"></span>
                  </div>
                  <div>
                    <span>购买方式</span>
                    <span>
                        <select id="settlement_select" class="w_130" name="settlement">
                          <option value>所有购买方式</option>
                          <?php foreach($settleLabel as $k => $v): ?>
                            <option value="<?php echo $k ?>" <?php if(isset($filter['settlement']) && $filter['settlement'] == $k): ?> selected <?php endif; ?>><?php echo $v; ?></option>
                          <?php endforeach; ?>
                        </select>
                    </span>
                  </div>
                  <div>
                      <span>订单状态</span>
                      <span>
                          <select id="status_select" class="w_130" name="status">
                            <option value>所有订单状态</option>
                            <?php foreach($statusLabel as $k => $v): ?>
                              <option value="<?php echo $k ?>" <?php if(isset($filter['status']) && $filter['status'] == $k): ?> selected <?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                          </select>
                      </span>
                  </div>
                  <div>
                    <span>消费状态</span>
                    <span>
                      <select class="w_90" name="consume_status">
                          <option value>全部</option>
                          <?php foreach($consumeLabel as $k => $v): ?>
                            <option value="<?php echo $k ?>" <?php if(isset($filter['consume_status']) && $filter['consume_status'] == $k): ?> selected <?php endif; ?>><?php echo $v; ?></option>
                          <?php endforeach; ?>
                      </select>
                    </span>
                  </div>
                  <div>
                      <span>退款状态</span>
                      <span>
                          <select class="w_90" name="refund_status">
                              <option value>全部</option>
                              <?php foreach($refundLabel as $k => $v): ?>
                                <option value="<?php echo $k ?>" <?php if(isset($filter['refund_status']) && $filter['refund_status'] == $k): ?> selected <?php endif; ?>><?php echo $v; ?></option>
                              <?php endforeach; ?>
                          </select>
                      </span>
                  </div>
                  <div>
                    <span>下单时间</span>
                    <span class="t_time"><input name="create_start_time" type="text" id="datepicker" class="datepicker moba" value="<?php echo isset($filter['create_start_time']) ? $filter['create_start_time'] : ''; ?>"></span>
                    <font>至</font>
                    <span class="t_time"><input name="create_end_time" type="text" id="datepicker2" class="datepicker moba" value="<?php echo isset($filter['create_end_time']) ? $filter['create_end_time'] : ''; ?>"></span>
                  </div>
                </div>
                <div class="h_btn_list" style="">
                  <div class="actives" id="search_btn">筛选</div>
                  <div class="" id='export_btn'>导出</div>
                </div>
              </div>
              </form>
              <div class="" style="font-size:13px;">
                <div class="bg_f8f9fb table fomr_term template">
                  <div>酒店&商品信息</div>
                  <div>实付金额&购买数量</div>
                  <div>订单折扣</div>
                  <div>客户信息</div>
                  <div>购买方式</div>
                  <div>订单状态</div>
                  <div>支付时间</div>
                </div>
              </div>

              <input type='hidden' id='current_page' />
              <input type='hidden' id='show_per_page' />
              <div id="content">
                <?php foreach($orderList['data'] as $order): ?>
                <div class="border_1 m_t_10 bg_fff">
                  <div class="bg_f8f9fb fomr_term p_0_30_0_10 b_b_1">
                    <a href="<?php echo Soma_const_url::inst()->get_url('*/*/edit', array('ids' => $order['order_id'])); ?>" class="f_r color_F99E12">订单详情</a>
                    <div>订单号：<?php echo $order['order_id']; ?></div>
                  </div>
                  <div class="table temp_con template p_t_10 p_b_10">
                    <div class="clearfix">
                      <img class="template_img" src="<?php echo $order['items'][0]['face_img']; ?>">
                      <span class="template_span"><?php echo $order['inter_name']; ?></span><br>
                      <span><?php echo $order['items'][0]['name']; ?></span>
                    </div>
                    <div>
                      <span><?php echo $order['real_grand_total']; ?></span><br>
                      <span><?php echo $order['items'][0]['pay_qty']; ?>份</span>
                    </div>
                    <div>
                      <span><?php echo $order['discount']; ?></span><br>
                      <!-- <span>600</span> -->
                    </div>
                    <div>
                      <span><?php echo isset($order['contact']) ? $order['contact'] : ''; ?></span><br>
                      <span><?php echo isset($order['mobile']) ? $order['mobile'] : ''; ?></span>
                    </div>
                    <div>
                      <span><?php echo $order['settlement']; ?></span><br>
                      <span>&nbsp;</span>
                    </div>
                    <div>
                      <span><?php echo $order['consume_status']; ?></span><br>
                      <span class="color_ff9900"><?php echo $order['status']; ?></span>
                    </div>
                    <div>
                      <?php $pay_time_arr = explode(' ', $order['payment_time'])?>
                      <span class="color_72afd2"><?php echo isset($pay_time_arr[0]) ? $pay_time_arr[0] : ''; ?></span><br>
                      <span class="color_72afd2 open_order"><?php echo isset($pay_time_arr[1]) ? $pay_time_arr[1] : ''; ?></span>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="pages">
                <div id="Pagination">
                  <div class="pagination">
                    <?php
                      // 每页显示5条数据
                      $page_size = $base_page['page_size'];
                      // 总页数
                      $total_page = ceil($orderList['total'] / $page_size);
                      // 当前页，从get参数获取
                      $current_page = isset($filter['page_num']) ? intval($filter['page_num']) : $base_page['page_num'];
                      if($current_page <= 0) { $current_page = $base_page['page_num']; }

                      // 当前链接
                      $params = $filter;
                      unset($params['page_num']);
                      unset($params['page_size']);
                      $current_url = Soma_const_url::inst()->get_url('*/*/*', $params);
                      if(strpos($current_url, "?") === false) {
                        $current_url .= '?';
                      } else {
                        $current_url .= '&';
                      }

                      // 第一页链接
                      $first_page = $current_url . 'page_num=' . 1 . '&page_size=' . $page_size;

                      $pre_page = $pre_two_page = $nxt_page = $nxt_two_page = null;
                      // 上两页链接
                      if(($current_page - 1) > 1) {
                        $pre_two_page = $current_url . 'page_num=' . ($current_page - 2) . '&page_size=' . $page_size;
                      }
                      // 上一页链接
                      if($current_page > 1) {
                        $pre_page = $current_url . 'page_num=' . ($current_page - 1) . '&page_size=' . $page_size;
                      }
                      // 下一页链接
                      if($current_page < $total_page) {
                        $nxt_page = $current_url . 'page_num=' . ($current_page + 1) . '&page_size=' . $page_size;
                      }
                      // 下两页页链接
                      if(($current_page + 1) < $total_page) {
                        $nxt_two_page = $current_url . 'page_num=' . ($current_page + 2) . '&page_size=' . $page_size;
                      }
                      // 最后一页链接
                      $last_page = $current_url . 'page_num=' . $total_page . '&page_size=' . $page_size;

                    ?>
                    
                    <?php if($current_page > 1): ?>
                      <a href="<?php echo $pre_page; ?>">&lt;</a>
                    <?php endif; ?>
                    <?php if($current_page > 3): ?>
                      <a href="<?php echo $first_page; ?>">1</a>
                      <a>...</a>
                    <?php endif; ?>
                    <?php if($pre_two_page != null): ?>
                      <a href="<?php echo $pre_two_page; ?>"><?php echo intval($current_page - 2); ?></a>
                    <?php endif; ?>
                    <?php if($pre_page != null): ?>
                      <a href="<?php echo $pre_page; ?>"><?php echo intval($current_page - 1); ?></a>
                    <?php endif; ?>
                    <a class="number current" href="#"><?php echo intval($current_page); ?></a>
                    <?php if($nxt_page != null): ?>
                      <a href="<?php echo $nxt_page; ?>"><?php echo intval($current_page + 1); ?></a>
                    <?php endif; ?>
                    <?php if($nxt_two_page != null): ?>
                      <a href="<?php echo $nxt_two_page; ?>"><?php echo intval($current_page + 2); ?></a>
                    <?php endif; ?>
                    <?php if(($current_page + 2) < $total_page): ?>
                      <a>...</a>
                      <a href="<?php echo $last_page; ?>"><?php echo intval($total_page); ?></a>
                    <?php endif; ?>
                    <?php if($current_page < $total_page): ?>
                      <a href="<?php echo $nxt_page; ?>">&gt;</a>
                    <?php endif; ?>
                  </div>
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
?>
<!--日历调用开始-->
<!-- <script src="<?php echo base_url(FD_PUBLIC);?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/layDate.js"></script>
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

    $('#export_btn').click(function(){
      window.location= "<?php echo Soma_const_url::inst()->get_url('*/*/export_order_list', $filter); ?>";
    });

  $('#search_btn').click(function(){
    // var status= $('#status_select').val();
    // var settlement= $('#settlement_select').val();
    // var url= "<?php echo Soma_const_url::inst()->get_url('*/*/*', array('page_num' => 1, 'page_size' => 5)); ?>";
    // var start = $('#datepicker').val();
    //   var end = $('#datepicker2').val();
    // var p = '';
    //   if( status != '' ){
    //     p += '&status='+ status;
    //   }
    // if( settlement != '' ){
    //     p += '&settlement='+ settlement;
    // }
    // if( start != '' ){
    //     p += '&start='+ start;
    // }
    // if( end != '' ){
    //     p += '&end='+ end;
    // }
    // window.location= url+= p;
    $('#this_form').submit();
  });

  // var show_per_page = 5; 
  // var number_of_items = $('#content').children().size();
  // var number_of_pages = Math.ceil(number_of_items/show_per_page);
  // $('#current_page').val(0);
  // $('#show_per_page').val(show_per_page);
  // var navigation_html = '<a class="previous_link" href="javascript:previous();">上一页</a>';
  // var current_link = 0;
  // while(number_of_pages > current_link){
  //   navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link +')" longdesc="' + current_link +'">'+ (current_link + 1) +'</a>';
  //   current_link++;
  // }
  // navigation_html += '<a class="next_link" href="javascript:next();">下一页</a>';
  // $('#page_navigation').html(navigation_html);
  // $('#page_navigation .page_link:first').addClass('active_page');
  // $('#content').children().css('display', 'none');
  // $('#content').children().slice(0, show_per_page).css('display', 'block');
  
  var bool=true;
  var obj=null;
    $('.drow_list li').click(function(){
        $('#search_hotel').val($(this).text());
        $('#search_hotel_h').val($(this).val());
        $(this).addClass('cur').siblings().removeClass('cur');
    });
  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })
  $('.open_order').click(function(){
    $(this).parent().parent().next().slideToggle();
  })
  $('.all_open_order').click(function(){
    $('.con_list').slideToggle();
  })
  <!--日历调用-->
  // $('.datepicker').datepicker({
  //  dateFormat: "yymmdd"
  // });
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

function previous(){
  new_page = parseInt($('#current_page').val()) - 1;
  if($('.active_page').prev('.page_link').length==true){
    go_to_page(new_page);
  }
}
function next(){
  new_page = parseInt($('#current_page').val()) + 1;
  //if there is an item after the current active link run the function
  if($('.active_page').next('.page_link').length==true){
    go_to_page(new_page);
  }
}
function go_to_page(page_num){
  var show_per_page = parseInt($('#show_per_page').val());
  start_from = page_num * show_per_page;
  end_on = start_from + show_per_page;
  $('#content').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
  $('.page_link[longdesc=' + page_num +']').addClass('active_page').siblings('.active_page').removeClass('active_page');
  $('#current_page').val(page_num);
}


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
function slidesub(id){
  chose="[name='"+'weborder'+id+"']";
  if($(chose).css("display")=='table-row'){
    $(chose).css("display",'none');
  }
  else{
    $(chose).css("display",'table-row');
  }
}
$('#grid-btn-set').click(function(){
  var str = '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name ();?>" value="<?php echo $this->security->get_csrf_hash ();?>" style="display:none;">';
  $.getJSON('<?php echo site_url("hotel/orders/get_cofigs?ctyp=ORDERS_STATUS_HOTEL")?>',function(data){
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
  });

});
$('#set_btn_save').click(function(){
  $.post('<?php echo site_url("hotel/orders/save_cofigs?ctyp=ORDERS_STATUS_HOTEL")?>',$("#setting_form").serialize(),function(data){
    if(data == 'success'){
      window.location.reload();
    }else{
      alert('保存失败');
    }
  });
});
<!--改变订单状态-->
function change_status(obj){
  var sid = $(obj).attr('sid');
  var orderid = $(obj).attr('oid');
  var item_id = '';
  if($(obj).attr('iid')){
    item_id = $(obj).attr('iid')
  }
  if(orderid){
    $.get('<?php echo site_url('hotel_2/orders/update_order_status');?>',{
      oid:orderid,
      status:sid,
      item_id:item_id
    },function(data){
      $('.fixed_box').fadeOut();
      if(data==1){
        alert('修改成功');
        location.reload();
      }else{
        alert('修改失败');
      }
    });
  }
}

</script>
</body>
</html>
