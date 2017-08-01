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
            <?php if(!empty($salers)){ foreach($salers as $saler){ ?>
              <tr class="form_con">
                <td class="f_name"><?php echo $saler['name'];?></td>
                <td class="f_number"> <?php echo $saler['qrcode_id'];?></td>
                <td>
                  <div class="input_radios">
                      <input class="" type="radio" id="saler_<?php echo $saler['qrcode_id'];?>"  <?php if($saler['qrcode_id']==$list['id'])echo 'checked'; ?> name="cat_img_12" value="<?php echo $saler['qrcode_id'];?>">
                      <label for="saler_<?php echo $saler['qrcode_id'];?>">选择</label>
                  </div>
                </td>
              </tr>
            <?php }}?>
        </tbody>
      </table>
        <div class="clearfix center" style="">
            <div class="confirm bg_ff9900 color_fff template_btn m_r_60" style="border:0px;">确认</div>
            <div class="cancel color_ff9900 border_1_ff9900 template_btn">取消</div>
        </div>
    </div>
    <div class="hotel_tabel" style="display:none;"><!-- 
      <input type="hidden" class="hotel_id" name="hotel_id" value="" /> -->
      <table id="coupons_table1" class="table-striped table-condensed dataTable no-footer color_555" style="width:100%;font-size:12px;">
        <thead class="bg_f8f9fb form_thead" style="font-size:14px;">
          <tr class="bg_f8f9fb form_title">
            <th>酒店id</th>
            <th>酒店名称</th>
            <th>选择</th>
          </tr>
        </thead>
        <tbody class="containers dataTables_wrapper form-inline dt-bootstrap hotel_data">
        <?php if(!empty($hotels)){ foreach($hotels as $key=>$hotel){?>
              <tr class="form_con">
                <td><?php echo $hotel['hotel_id'];?></td>
                <td><?php echo $hotel['name'];?></td>
                <td>
                  <div class="input_checkboxs"><input type="checkbox" id="hotel_<?php echo $hotel['hotel_id'];?>" <?php if(in_array($hotel['hotel_id'],$array_hotel_ids))echo 'checked';?> value="<?php echo $hotel['hotel_id'];?>"><label for="hotel_<?php echo $hotel['hotel_id'];?>" style="width:auto;">选择</label></div>
                </td>
              </tr>
        <?php }}?>
        </tbody>
      </table>
        <div class="clearfix center" style="">
            <div class="confirm1 bg_ff9900 color_fff template_btn m_r_60" style="border:0px;">确认</div>
            <div class="cancel color_ff9900 border_1_ff9900 template_btn">取消</div>
        </div>
    </div>
  </div>
</div>
<div class="over_xs">
    <div class="content-wrapper">
        <div class="banner bg_fff p_0_20 color_333">社群客/社群客列表/编辑</div>
        <div class="" style="padding:20px;">
        <form role="form" method='post' id='form1' action='<?php echo site_url('club/Club_list/save_club');?>'>
            <div class="">
              <div class="display_flex m_b_20 bg_fff b_radius_6">
                <div class="b_r_1">
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">基本信息</span></div>
                  <div class="p_30_35 color_555">
                    <div class="m_b_15">
                      <span class="w_88">名称</span>
                      <span><input type="text" name="club_name" placeholder="<?php echo $list['club_name'];?>" value="<?php echo $list['club_name'];?>"/></span>
                    </div>
                    <div class="m_b_15">
                      <span class="w_88">社群客数量</span>
                      <span><input type="number"  name="limited_amount" placeholder="<?php echo $list['limited_amount'];?>" value="<?php echo $list['limited_amount'];?>" /></span>
                    </div>
                    <div class="m_b_15">
                        <input type="hidden" name="club_id" value="<?php echo $list['club_id'];?>" />
                        <input type="hidden" name="saler_id" id="saler_id" value="<?php echo $list['id'];?>" />
                        <input type="hidden" name="n_name" id="n_name" value="<?php echo $list['staff_name'];?>" />
                      <span class="w_88">销售员</span>
                      <span class="con_name"><?php echo $list['id'];?>-<?php echo $list['staff_name'];?></span>
                      <a class="looks color_ff9900 border_1_ff9900 template_btn m_15 looks member_list" href="javascript:;">去修改</a>
                    </div>
                      <div class="m_b_15">
                          <span class="w_88">有效期</span>
                          <span><input type="text" id="datepicker" class="start_range" name="start_range"  placeholder="<?php echo date("Y-m-d",strtotime($list['valid_times'][0]));?>" value="<?php echo date("Y-m-d",strtotime($list['valid_times'][0]));?>" /></span>-
                          <span><input type="text" id="datepicker2"  class='end_range' name="end_range"  placeholder="<?php echo date("Y-m-d",strtotime($list['valid_times'][1]));?>" value="<?php echo date("Y-m-d",strtotime($list['valid_times'][1]));?>" /></span>
                      </div>
                      <div class="m_b_15">
                          <span class="w_88">备注</span>
                          <span><input type="text"  name="remark" placeholder="<?php echo $list['remark'];?>" value="<?php echo $list['remark'];?>" /></span>
                      </div>
                  </div>
                </div>
                <div>
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">可用价格</span></div>
                  <div id="rooms" class="input_checkbox p_l_4 p_30_35 color_555 rooms">
                  <input class="price_ids" type="hidden" name="price_code" value=""/>
                      <?php if(!empty($price_codes)){ foreach($price_codes as $key=>$code){ ?>
                        <div><input type="checkbox" <?php if(in_array($key,$list['price_code']))echo 'checked';?> id="price_<?php echo $key;?>"  value="<?php echo $key;?>" ><label for="price_<?php echo $key;?>"><?php echo $code['price_name'];?></label></div>
                      <?php }}else{ echo "没有可用的价格代码";}?>
                  </div>
                </div>
                  <div>
                      <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">商城</span></div>
                      <div id="soma" class="input_checkbox p_l_4 p_30_35 color_555 soma">
                          <input class="soma_code" type="hidden" name="soma_code" value=""/>
                          <?php if(!empty($usable_soma_codes)){ foreach($usable_soma_codes as $key=>$code){ ?>
                              <div><input type="checkbox" <?php if(in_array($key,$list['soma_code']))echo 'checked';?> id="soma_<?php echo $key;?>"  value="<?php echo $key;?>" ><label for="soma_<?php echo $key;?>"><?php echo $code->name;?></label></div>
                          <?php }}else{ echo "没有可用的价格代码";}?>
                      </div>
                  </div>
              </div>
              <div class="display_flex m_b_20 bg_fff b_radius_6">
                <div class="b_r_1">
                  <div class="b_b_1 p_10_25"><span class="b_l_3 color_333">适用门店</span></div>
                  <div class="p_30_35 color_555">
                    <div class="input_radio">
                        <div>
                            <input type="radio" id="star_26" name="hotel_ids" <?php if(empty($list['hotel_id']) || $list['hotel_id']==0)echo 'checked';?> value="all">
                            <label for="star_26">全部门店</label>
                        </div>
                        <div>
                            <input class="rule_input" type="radio" id="star_37" name="hotel_ids" <?php if(!empty($list['hotel_id']) && $list['hotel_id']!=0)echo 'checked';?> value="">
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
                            <input type="radio" id="star_27" name="status" checked="" value="1" <?php if($list['status']==1)echo 'checked';?>>
                            <label for="star_27">有效</label>
                        </div>
                        <div>
                            <input class="" type="radio" id="star_38" name="status" value="2" <?php if($list['status']==2)echo 'checked';?>>
                            <label for="star_38">失效</label>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="center" style="padding:15px;">
                <a class="b_btn b_radius_6 color_fff bg_ff9900 edits" onclick="sub()" style="display:inline-block;">保 存</a>
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
    var input_radiosid=<?php echo $list['id'];?>;
    var b_data=$('#coupons_table').DataTable({
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
          },
          "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ){
            var boole=false;
            $('#coupons_table input').each(function(index, el) {
              if(input_radiosid==$(this).val()){
                  boole=true;
                  return 
          }
            });
            if(!boole){
              $('#coupons_table input').prop('checked',false);
        }
          }
        }
    });

    var b_data1=$('#coupons_table1').DataTable({
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
    $('#coupons_table_length').html($('.con_name').html());
    $('#coupons_table1_length').html('添加门店');
    $('#coupons_table').on('click','.input_radios',function(){
        input_radiosid=$(this).find('input').val();
        str1=$(this).parents(".form_con").find('.f_name').html();
        str2=$(this).parents(".form_con").find('.f_number').html();
        $('#coupons_table_length').html('修改销售员 '+str2+'-'+str1+'');
    })
    $('.member_list').click(function(){
        $(".member_tabel").show();
        $(".hotel_tabel").hide();
        $(".containers_fixed").fadeIn();
    })
    $('.cancel').click(function(){
//        str1='';
//        str2='';
        $(".containers_fixed").fadeOut();
    })
    $('.confirm').click(function(){
        if(str2=='' || str1==''){
            str2 = $("#saler_id").val();
            str1 = $("#n_name").val();
        }
        $(".containers_fixed").fadeOut();
        $('#saler_id').val(str2);
        $('.con_name').html(str2+'-'+str1);
    })
    $('.confirm1').click(function(){
        $(".containers_fixed").fadeOut();
        $('.rule_input').val(hotel_arr.toString());
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
var hotel_arr=JSON.parse('<?php echo $json_hotel_id;?>');
$('#coupons_table1 .input_checkboxs').on('click','input[type="checkbox"]',function(){
    var CheckID=$(this).val();
    if($(this).is(":checked")){
      if(hotel_arr.indexOf(CheckID)<0){
        hotel_arr.push(CheckID);
      }
    }else{
      if(hotel_arr.indexOf(CheckID)>=0){
        hotel_arr.splice(hotel_arr.indexOf(CheckID),1);
      }
    }
})
function sub(){

    if($('.start_range').val() > $('.end_range').val()){
        alert('开始日期不能大于结束日期');
        return;
    }
	var price_code=[];
	$('.rooms div input:checked').each(function(index, el) {
        price_code.push($(this).val());
	});
	$('.price_ids').val(price_code);

    var soma_code=[];
    $('.soma div input:checked').each(function(index, el) {
        soma_code.push($(this).val());
    });
    $('.soma_code').val(soma_code);

    $.post('<?php echo site_url('club/club_list/save_club')?>',
        {
            datas:JSON.stringify($('#form1').serializeArray()),
    <?php echo $csrf_token?>:'<?php echo $csrf_value?>'
    },function(data){
        alert(data.message);
        if(data.status==1){
            location.reload();
            add_tag=1;
        }
        submit_tag=0;
    },'json');
}


</script>

</body>
</html>
