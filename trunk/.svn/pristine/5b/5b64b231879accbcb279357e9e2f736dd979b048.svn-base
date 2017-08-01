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
.fixed_box{padding:1% 0;width:450px;}
.tile{padding:0 2%;font-size:12px;}
.h_btn_list{text-align:center;}
.tile{padding:0 2%;}
.display_flex >div{width:490px;}
#coupons_table_wrapper,#coupons_table1_wrapper{margin-bottom:8%;}
#coupons_table_wrapper .row:nth-of-type(2),#coupons_table1_wrapper .row:nth-of-type(2){margin-bottom:10px;min-height:162px;}
#coupons_table tr,#coupons_table1 tr{border-top:1px solid #d7e0f1;border-bottom:1px solid #d7e0f1;}
#coupons_table thead,#coupons_table1 thead{color:#333;}
#coupons_table table td,#coupons_table1 table td{font-size:12px;}
#coupons_table_paginate a,#coupons_table1_paginate a{padding:3px 6px;font-size:10px;}
#coupons_table_filter,#coupons_table1_filter{font-size:12px;}
#coupons_table_filter input,#coupons_table1_filter input{margin-left:-25px;width:125px;height:23px;}
#coupons_table_length,#coupons_table1_length{display:block;font-size:12px;margin-top:2px;margin-left:7px;}
.input_checkboxs >input+label{background-size:33%;}
#coupons_table1_filter input,#coupons_table_filter input{background: #fff url(/public/js/img/seach.png) no-repeat center right;background-size:13%;background-position:94% center;}


.input_checkboxs >input:checked+label{background-size:33%;}
</style>
<?php echo $this->session->show_put_msg(); ?>
<div class="containers_fixed">
  <div class="fixed_box bg_fff b_radius_6" style="display:block;left:40%;">
    <div class="member_tabel">
      <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;font-size:12px;">
        <thead class="bg_f8f9fb form_thead" style="font-size:14px;">
          <tr class="bg_f8f9fb form_title color_333">
            <th>姓名</th>
            <th>分销号</th>
            <th>选择</th>
          </tr>
        </thead>
        <tbody class="containers dataTables_wrapper form-inline dt-bootstrap color_555">
          <tr class="form_con">
            <td class="f_name">哆啦A梦</td>
            <td class="f_number"> 248</td>
            <td>
              <div class="input_radios">
                  <input class="" type="radio" id="star_1" name="cat_img_12" value="">
                  <label for="star_1">选择</label>
              </div>
            </td>
          </tr>
          <tr class="form_con">
            <td class="f_name">A梦das </td>
            <td class="f_number"> 24s8</td>
            <td>
              <div class="input_radios">
                  <input class="" type="radio" id="star_2" name="cat_img_12" value="">
                  <label for="star_2">选择</label>
              </div>
            </td>
          </tr>
          <tr class="form_con">
            <td class="f_name">哆啦Aasd梦</td>
            <td class="f_number"> 2418</td>
            <td>
              <div class="input_radios">
                  <input class="" type="radio" id="star_3" name="cat_img_12" value="">
                  <label for="star_3">选择</label>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="hotel_tabel" style="display:none;">
      <table id="coupons_table1" class="table-striped table-condensed dataTable no-footer color_555" style="width:100%;font-size:12px;">
        <thead class="bg_f8f9fb form_thead" style="font-size:14px;">
          <tr class="bg_f8f9fb form_title">
            <th>酒店id</th>
            <th>酒店名称</th>
            <th>选择</th>
          </tr>
        </thead>
        <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
          <tr class="form_con">
            <td>23</td>
            <td>碧桂园酒店集团</td>
            <td>
              <div class="input_checkboxs"><input type="checkbox" id="4" name="room_ids"><label for="4" style="width:auto;">选择</label></div>
            </td>
          </tr>
          <tr class="form_con">
            <td>23</td>
            <td>碧桂园酒店集团</td>
            <td>
              <div class="input_checkboxs"><input type="checkbox" id="5" name="room_ids"><label for="5" style="width:auto;">选择</label></div>
            </td>
          </tr>
          <tr class="form_con">
            <td>23</td>
            <td>碧桂园酒店集团</td>
            <td>
              <div class="input_checkboxs"><input type="checkbox" id="6" name="room_ids"><label for="6" style="width:auto;">选择</label></div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="clearfix center" style="">
      <div class="confirm bg_ff9900 color_fff template_btn m_r_60" style="border:0px;">确认</div>
      <div class="cancel color_ff9900 border_1_ff9900 template_btn">取消</div>
    </div>
  </div>
</div>
<div class="over_xs">
    <div class="content-wrapper">
        <div class="banner bg_fff p_0_20 color_333">社群客/社群客列表/编辑</div>
        <div class="" style="padding:20px;">
          <form>
            <div class="">
              <div class="display_flex m_b_20 bg_fff b_radius_6">
                <div class="b_r_1">
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">基本信息</span></div>
                  <div class="p_30_35 color_555">
                    <div class="m_b_15">
                      <span class="w_88">名称</span>
                      <span><input type="text" placeholder="懒人的朋友" /></span>
                    </div>
                    <div class="m_b_15">
                      <span class="w_88">社群客数量</span>
                      <span><input type="number" placeholder="5" /></span>
                    </div>
                    <div class="m_b_15">
                      <span class="w_88">销售员</span>
                      <span class="con_name">懒人-123</span>
                      <a class="looks color_ff9900 border_1_ff9900 template_btn m_15 looks member_list" href="javascript:;">去修改</a>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">可用价格</span></div>
                  <div id="rooms" class="input_checkbox p_l_4 p_30_35 color_555">
                    <div><input type="checkbox" id="4" name="room_ids"><label for="4">协议价a</label></div>
                    <div><input type="checkbox" id="7" name="room_ids"><label for="7">协议价b</label></div>
                    <div><input type="checkbox" id="14919" name="room_ids"><label for="14919">协议价c</label></div>
                  </div>
                </div>
              </div>
              <div class="display_flex m_b_20 bg_fff b_radius_6">
                <div class="b_r_1">
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">适用门店</span></div>
                  <div class="p_30_35 color_555">
                    <div class="input_radio">
                        <div>
                            <input type="radio" id="star_26" name="cat_img" checked="">
                            <label for="star_26">全部门店</label>                                    
                        </div>
                        <div>
                            <input class="rule_input" type="radio" id="star_37" name="cat_img" value="">
                            <label for="star_37">指定门店</label>
                            <div class="rule_display" ><a class="looks color_ff9900 border_1_ff9900 template_btn m_15 looks looks_hotel" href="javascript:;" >去添加</a></div>
                        </div>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">状态</span></div>
                  <div id="rooms" class="input_checkbox p_l_4 p_30_35 color_555">
                    <div class="input_radio">
                        <div>
                            <input type="radio" id="star_27" name="cat_img_112" checked="">
                            <label for="star_27">有效</label>                                    
                        </div>
                        <div>
                            <input class="" type="radio" id="star_38" name="cat_img_112" value="">
                            <label for="star_38">失效</label>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="center" style="padding:15px;">
                <a class="b_btn b_radius_6 color_fff bg_ff9900 edits" href="" style="display:inline-block;">保 存</a>
            </div>
          </form>
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

<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
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
        "searching": true,
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
        "searching": true,
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
    var str1='';
    var str2='';
    $('#coupons_table_info,#coupons_table1_info').parent().css("display","none");
    $('#coupons_table_paginate,#coupons_table1_paginate').parent().css({"width":"100%","text-align":"right","font-size":"10","padding-right":"23px"});
    $('#coupons_table_filter,#coupons_table1_filter').parent().css({"text-align":"right","font-size":"10","padding-right":"23px"});
    $('#coupons_table_length').html('修改销售员 懒人-123');
    $('#coupons_table1_length').html('添加门店');
    $('#coupons_table .input_radios').click(function(){
        str1=$(this).parents(".form_con").find('.f_name').html();
        str2=$(this).parents(".form_con").find('.f_number').html();
        $('#coupons_table_length').html('修改销售员 '+str1+'-'+str2+'');
    })
    $('.member_list').click(function(){
      $(".member_tabel").show();
      $(".hotel_tabel").hide();
      $(".containers_fixed").fadeIn();
    })
    $('.cancel').click(function(){
        str1='';
        str2='';
      $(".containers_fixed").fadeOut();
    })
    $('.confirm').click(function(){
      $(".containers_fixed").fadeOut();
      $('.con_name').html(str1+'-'+str2);
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
// function select_click(obj){
//     $('#search_hotel').val($(obj).text());
//     $("input[name='msgsaler']").val($(obj).val());
//     $(obj).addClass('cur').siblings().removeClass('cur');
// }
// $("body").on('change','#el_hotel',function(){
//     $.post('/index.php/okpay/types/get_saler_info',{
//         'hotel_id':$('#el_hotel').val(),
//         '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
//     },function(data){
//         $('.drow_list').empty();
//         if(data.errcode==0){
//             for(i in data.res ){
//                 $('.drow_list').append('<li onclick="select_click(this)" value="'+data.res[i].qrcode_id+'">'+data.res[i].name+'</li>');
//             }
//         }
//         else{
//             alert('通知,'+data.msg);
//         }

//     },'json')
// });
</script>

</body>
</html>
