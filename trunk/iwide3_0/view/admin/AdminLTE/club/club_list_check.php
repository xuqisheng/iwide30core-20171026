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
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate12.css">
<link type="text/css" rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/need/laydate.css">
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
.fixed_box{padding:1% 0;width:304px;}
.tile{padding:0 2%;font-size:12px;}
.h_btn_list{text-align:center;}
.tile{padding:0 2%;}
#coupons_table_wrapper,#coupons_table1_wrapper{margin-bottom:8%;}
#coupons_table_wrapper .row:nth-of-type(2),#coupons_table1_wrapper .row:nth-of-type(2){margin-bottom:10px;min-height:162px;}
#coupons_table tr,#coupons_table1 tr{border-top:1px solid #d7e0f1;border-bottom:1px solid #d7e0f1;}
#coupons_table thead,#coupons_table1 thead{color:#333;}
#coupons_table table td,#coupons_table1 table td{font-size:12px;}
#coupons_table_paginate a,#coupons_table1_paginate a{padding:3px 6px;font-size:10px;}
</style>


<div class="containers_fixed">
  <div class="fixed_box bg_fff b_radius_6" style="overflow:hidden;display:block;width:450px">
    <div class="member_tabel">
      <div class="tile"><?php echo $list['club_name'].' 成员列表('.count($customer).')';?></div>
      <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;font-size:12px;">
        <thead class="bg_f8f9fb form_thead">
          <tr class="bg_f8f9fb form_title" style="font-size:14px;">
<!--            <th>会员号</th>-->
            <th>姓名</th>
            <th>手机号</th>
            <th>状态</th>
          </tr>
        </thead>
        <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
        <?php if(!empty($customer)){ foreach($customer as $c_member) { ?>
          <tr class="form_con" customer="<?php echo $c_member['customer_id'];?>">
<!--            <td>jfk123457</td>-->
            <td><?php echo $c_member['name'];?></td>
            <td><?php echo $c_member['tel'];?></td>
              <td>
                  <ib>
                      <input  class="customer_s" name="auth_<?php echo $c_member['customer_id'];?>" customer_id="<?php echo $c_member['customer_id'];?>" id="auth_t_<?php echo $c_member['customer_id'];?>" value="1" type="radio" <?php if($c_member['status']==1)echo "checked";?>>
                      <ib>有效</ib>
                  </ib>
                  <ib>
                      <input  class="customer_s" name="auth_<?php echo $c_member['customer_id'];?>" customer_id="<?php echo $c_member['customer_id'];?>" id="auth_f_<?php echo $c_member['customer_id'];?>" value="2" type="radio" <?php if($c_member['status']==2)echo "checked";?>>
                      <ib>无效</ib>
                  </ib>
              </td>
          </tr>
        <?php }}?>
        </tbody>
      </table>
    </div>
    <div class="hotel_tabel" style="display:none;">
    <div class="tile"><?php if(!empty($list['hotel_id']) || $list['hotel_id']!=0){$club_hotels = explode(',',$list['hotel_id']);echo $list['club_name'].' 门店列表('.count($club_hotels).')';}else{echo $list['club_name'].' 门店列表(0)';}?></div>
      <table id="coupons_table1" class="table-striped table-condensed dataTable no-footer" style="width:100%;font-size:12px;">
        <thead class="bg_f8f9fb form_thead" style="font-size:14px;">
          <tr class="bg_f8f9fb form_title">
            <th>序号</th>
            <th>酒店名称</th>
          </tr>
        </thead>
        <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
        <?php if(!empty($list['hotel_id']) || $list['hotel_id']!=0){
            $club_hotels = explode(',',$list['hotel_id']);
            foreach($club_hotels as $club_hotel){ ?>
          <tr class="form_con">
            <td><?php echo $club_hotel;?></td>
            <td><?php if(isset($hotels[$club_hotel]['name']))echo $hotels[$club_hotel]['name'];?></td>
          </tr>
        <?php }} ?>
        </tbody>
      </table>
    </div>
    <div class="clearfix center" style="">
      <div class="cancel color_ff9900 template_btn" style="border:1px solid #ff9900;">关闭</div>
    </div>
  </div>
</div>
<div class="over_xs">
    <div class="content-wrapper" style="min-height: 775px;">
        <div class="banner bg_fff p_0_20 color_333">社群客/社群客列表/查看 </div>
        <div class="contents">
            <div class="contents_list bg_fff box_list">
              <div class="d_i_b">
                <div class="w_200 t_a_r m_r_20">社群名：</div>
                <div class="color_333"><?php echo $list['club_name'];?></div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">总人数：</div>
                <div class="color_333"><?php echo $list['limited_amount'];?></div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">已加入人数：</div>
                <div>
                  <span class="color_333"><?php echo $list['amount'];?></span>
                  <a class="looks color_ff9900 border_1_ff9900 template_btn m_15 member_list looks " href="javascript:;">查看成员</a>
                  <a class="looks color_ff9900 border_1_ff9900 template_btn m_15 looks " href="<?php echo base_url('index.php/club/club_list/ext_club_customer?ids=').$list['club_id'];?>">导出成员</a>
                </div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">可用价格：</div>
                <div class="color_333">
                    <?php if(!empty($list['price_code'])){
                                foreach($list['price_code'] as $price_code){
                                    if(isset($price_codes[$price_code])){
                                        echo $price_codes[$price_code]['price_name'].'&nbsp';
                                    }else{
                                        echo "价格代码已失效";
                                    }
                                }
                            }else{
                                echo "没有可用价格代码";
                            }
                    ?>
                </div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">适用门店：</div>
                <div>
                    <?php if(empty($list['hotel_id']) || $list['hotel_id']==0){ ?>
                          <span class="color_333">全部门店适用</span>
                    <?php }else{ ?>
                          <span class="color_333">部门门店适用</span>
                          <a class="looks color_ff9900 border_1_ff9900 template_btn m_15 looks looks_hotel" href="javascript:;">查看门店</a>
                    <?php }?>
                </div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">有效期：</div>
                <div class="color_333"><?php echo $list['valid_times'][0].'至'.$list['valid_times'][1];?></div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">销售员：</div>
                <div>
                  <span class="color_333"><?php echo $list['staff_name'];?>-<?php echo $list['qrcode_id'];?></span>
                  <span class="f_s_10 color_888">（数据统计社群客报表里可以查看订单明细）</span>
                </div>
              </div>
              <div class=" d_i_b">
                <div class="w_200 t_a_r m_r_20">产生间夜数：</div>
                <div class="color_333"><?php echo $roomnight;?>间夜</div>
              </div>
              <div class="d_i_b clearfix">
                <div class="w_200 t_a_r float_left m_r_20">社群客二维码：</div>
                <div>
                  <div class="to_codes"><img src="<?php echo $qrcode_url;?>"></div>
                </div>
              </div>
            </div>
            <div class="" style="padding:15px;text-align:center;">
                <a class="b_btn color_fff bg_ff9900 b_radius_6" href="<?php echo site_url("club/club_list/index");?>" style="display:inline-block;">关 闭</a>
            </div>
        </div>
    </div>
</div>
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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>

<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->


<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
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
<script type="text/javascript">
$(function(){
    $('#coupons_table').DataTable({
        "aLengthMenu": [3,10,15,20],
        "iDisplayLength": 3,
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

    $('#coupons_table1').DataTable({
        "aLengthMenu": [3,10,15,20],
        "iDisplayLength": 3,
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
    $('#coupons_table_info,#coupons_table1_info').parent().css("display","none");
    $('#coupons_table_paginate,#coupons_table1_paginate').parent().css({"width":"100%","text-align":"right","font-size":"10","padding-right":"23px"});
    $('.member_list').click(function(){
      $(".member_tabel").show();
      $(".hotel_tabel").hide();
      $(".containers_fixed").fadeIn();
    })
    $('.cancel').click(function(){
      $(".containers_fixed").fadeOut();
    })
    $('.looks_hotel').click(function(){
        $(".hotel_tabel").show();
        $(".member_tabel").hide();
        $(".containers_fixed").fadeIn();
    })
});
function check_status(status){
    if(status == 0){
        $('#coupon_use_count').attr('disabled',true);
        $('#coupon_ids').attr('disabled',true);
    }else if(status == 1){
        $('#coupon_use_count').attr('disabled',false);
        $('#coupon_ids').attr('disabled',false);
    }
}
$('#search_hotel').bind('input propertychange',function(){
    var val=$(this).val();
    if(val!=''|| val!=undefined){
        for(var i=0;i<$('.drow_list li').length;i++){
            if ( $('.drow_list li').eq(i).html().indexOf(val)>=0)
                $('.drow_list li').eq(i).show();
            else
                $('.drow_list li').eq(i).hide();
        }
    }else{
        $('.drow_list li').show();
    }
});
function select_click(obj){
    $('#search_hotel').val($(obj).text());
    $("input[name='msgsaler']").val($(obj).val());
    $(obj).addClass('cur').siblings().removeClass('cur');
}


$(".customer_s").click(function(){
    if($(this).attr('checked')==undefined){
        if(($(this).val())==1){
           $("#auth_f_"+$(this).attr('customer_id')).attr('checked',false);
        }else{
            $("#auth_t_"+$(this).attr('customer_id')).attr('checked',false);
        }
        $(this).attr('checked',true);
//        console.log($(this).attr('customer_id'));
//        console.log($(this).val());
        $.get('<?php echo site_url('club/club_list/change_club_customer')?>',{
            club_id:<?php echo $list['club_id'];?>,
            customer_id:$(this).attr('customer_id'),
            status:$(this).val()
        },function(data){

        },'json');
    }
})

</script>

</body>
</html>
