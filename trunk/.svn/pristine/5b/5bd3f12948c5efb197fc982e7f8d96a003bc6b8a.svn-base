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
#selects_membeb{display:inline-block;width:auto;vertical-align:middle;margin-right:25px;}
#coupons_table_wrapper >.row:first-child{background:#fff;padding:10px;}
.fixed_box{position:fixed;top:30%;left:48%;z-index:9999;border:1px solid #d7e0f1;border-radius:5px;padding:1% 2%;display:none;}
.tile{font-size:15px;text-align:center;margin-bottom:4%;}
.f_b_con{font-size:13px;margin-bottom:8%;width:245px;}
.f_b_con span:first-child{display:inline-block;width:80px;text-align:right;margin-right:5px;}
.pointer,.delivery,.confirms,.cancel{cursor:pointer;}
.pagination{margin-top:0px;margin-bottom:0px;}
.btn_list_r span{margin-right:10px;}
.btn_list_r span:last-child{margin-right:0px;}
.f_b_con i{right:8px;top:1px;font-style:normal;}
#coupons_table_length,.display_none{display:none !important;}
.f_con >div{display:inline-block;}
.menoy_input{
    margin: auto;
    height: 68px;
    line-height: 68px;
    font-size: 21px;
    text-align: center;
    /* padding: 0 5px; */
}
.height_auto{height:auto;}
.modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000;}
.r_btn_lists a{margin-right:10px;}
.r_btn_lists a:last-child{margin-right:0px;}
.h_btn_list> button,.h_btn_list> a{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;background:#ff9900;color:#fff}
</style>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:#fff;width:420px;height:480px;margin:100px auto;">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h4 >请用微信扫一扫</h4>
    </div>
  <div class="modal-body" style="margin:10px 0 15px 15px ;text-align:center;">
    <div>
      <div style="margin-bottom: 20px;">
        <input class="height_auto" type="radio" name="ck_qrcode" checked="checked" value="1"/>通用支付&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="height_auto" type="radio" name="ck_qrcode" value="2"/>分销支付
        <input type="hidden" id="hid_pay_type" value="" />
      </div>
      <div id="div_sale_input" style="margin-bottom: 20px; display:none;">
        分销号：<input type="text" id="ipt_sale" style="width:60px; line-height: 24px; padding-bottom: 2px;" />&nbsp;&nbsp;&nbsp;&nbsp;<button id="btn_make_qrcode" class="btn btn-sm bg-green"><i class="fa fa-qrcode"></i>二维码</button>
      </div>
    </div>
    <div id="no_sale_code_img">
        <img id="img_no_code_qrcode" src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/img/loading.gif" />
    </div>
    <div id="has_sale_code_img" style="display:none;">
        说明：该二维码是和员工的分销号关联着，顾客由此二维码支付成功后，订单流水中将会记录对应的分销号
    </div>
  </div> 
</div>
<div class="fixed_box bg_fff">
  <div class="tile">收款-前台</div>
  <div class="con_1">
    <div class="f_b_con center">
      <input class="menoy_input" type="text" placeholder="请输入用户需支付金额" />
    </div>
    <div class="f_b_con">在收款框输入用户需支付的金额(优惠后的金额)并点击"下一步"按钮</div>
  </div>
  <div class="con_2 display_none">
    <div class="center color_F9c359 f_s_27 receivables_money">
      收款金额：229
    </div>
    <div class="state_1">
      <div class="f_b_con center f_con">
        <div><i class="iconfont f_s_50 color_50a3ba">&#xe610;</i></div>
        <div class="cen_left">
          <p>等待用户支付</p>
            <p>检测扫描码：<input type="text" name="pay_code" id="pay_code"/></p>
          <p id="down_count"><span class="color_ec898a times_t" style="width:auto;">60s</span>内未支付自动关闭</p>
        </div>
      </div>
      <div class="f_b_con">请提示用户打开"钱包-付款"页面并将二维码对准扫描枪</div>
    </div>
    <div class="state_2 display_none">
      <div class="f_b_con center f_con">
        <div><i class="iconfont f_s_50 color_7BB928">&#xe611;</i></div>
        <div class="cen_left">
          <p class="color_F9c359">用户支付成功</p>
          <p><span class="" style="width:auto;">订单号:</span><span id="show_order_sn">234</span></p>
        </div>
      </div>
      <div class="f_b_con">已完成收款，你可点击“查看订单”核查该笔收款订单</div>
    </div>
    <div class="state_3 display_none">
      <div class="f_b_con center f_con">
        <div><i class="iconfont f_s_50 color_C55200">&#xe616;</i></div>
        <div class="cen_left">
          <p class="color_E19C5B">用户支付超时</p>
          <p>系统已自动关闭订单</p>
        </div>
      </div>
      <div class="f_b_con">用户支付超时，系统已自动关闭订单，您可重新拉起收款</div>
    </div>
    <div class="state_4 display_none">
      <div class="f_b_con center f_con">
        <div><i class="iconfont f_s_50 color_7BB928">&#xe603;</i></div>
        <div class="cen_left">
          <p class="color_F9c359">用户支付失败</p>
          <p><span class="" style="width:auto;">失败原因:</span>支付失败</p>
        </div>
      </div>
      <div class="f_b_con">支付超时或者异常，系统已关闭订单，请重新下单</div>
    </div>
  </div>


  <div class="h_btn_list clearfix center" style="">
    <div class="actives confirms">下一步</div>
    <div class="cancel">取消</div>
    <div class="see_btn display_none pointer"><a href="" id="check_order">查看订单</a></div>
    <div class="cancel_2 display_none pointer">关闭</div>
  </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1050px;" >
            <div class="banner bg_fff p_0_20">
                场景管理
            </div>
            <div class="contents">
                <?php echo form_open('okpay/types/grid/','class="form-inline"')?>
                <div class="head_cont contents_list bg_fff">
                    <div class="j_head">
                        <div>
                            <span>场景名称</span>
              <span class="input-group w_200 select_input" style="position:relative;display:inline-flex;" id="drowdown">
                <input placeholder="选择或输入关键字" type="text" value="<?php echo empty($posts['name']) ? '' : urldecode($posts['name'])?>" name="name" style="color:#92a0ae;" class="w_200 moba" id="search_hotel" >
              </span>
                        </div>
                        <div>
                            <span>酒店</span>
              <span>
                <select class="w_120" name="hotel_id">
                    <option value="-1">--全部--</option>
                    <?php if(!empty($hotels)){
                            foreach($hotels as $k=>$v){
                    ?>
                    <option value="<?php echo $k?>" <?php echo $k==$posts['hotel_id']?'selected="selected"':''?>><?php echo $v?></option>
                    <?php }}?>
                </select>

              </span>
                        </div>
                        <div>
                            <span>状态</span>
              <span>
                <select class="w_120" name="status">
                    <option value="-1">--全部--</option>
                    <option value="1" <?php echo (isset($posts['status'])&&$posts['status']==1)?'selected="selected"':''?>>可用</option>
                    <option value="0" <?php echo (isset($posts['status'])&&$posts['status']==0)?'selected="selected"':''?>>不可用</option>
                </select>

              </span>
                        </div>
                    </div>
                    <div  class="h_btn_list" style="">
                        <button type="submit" class="active" >&nbsp;检索</button>
                        <a href="<?php echo site_url('okpay/types/add')?>" class="active">&nbsp;新增场景</a>
                    </div>
                </div>
                </form>
                <div class="box-body" style="margin-top: 18px;">
                  <table class="no-footer"  style="width:100%;">
                    <thead class="bg_f8f9fb form_thead">
                      <tr class="bg_f8f9fb form_title">
                          <th>编号</th>
                        <th>场景名称</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>公众号</th>
                        <th>状态</th>
                        <th>酒店</th>
                        <th>按钮名称</th>
                        <th>跳转地址</th>
                        <th>分组</th>
                          <th>操作</th>
                      </tr>
                    </thead>
                    <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
                    <?php if(!empty($res)){
                        foreach($res as $k=>$v){
                    ?>
                      <tr class=" form_con">
                        <td><?php echo $v['id']?></td>
                        <td><?php echo $v['name']?></td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$v['create_time'])?>
                        </td>
                        <td> <?php echo date('Y-m-d H:i:s',$v['update_time'])?></td>
                        <td><?php echo !empty($publics[$v['inter_id']])?$publics[$v['inter_id']]:''?>
                        </td>
                        <td><?php echo $v['status']==1?'可用':'不可用'?></td>
                          <td><?php echo !empty($hotels[$v['hotel_id']])?$hotels[$v['hotel_id']]:''?></td>
                        <td><?php echo $v['store_name']?></td>
                          <td><?php echo $v['store_url']?></td>
                          <td><?php echo isset($groups[$v['group_id']])?$groups[$v['group_id']]:''?></td>
                        <td class="r_btn_lists">
                          <a class="color_F99E12 receivables" onclick="get_money(<?php echo $v['id']?>)" href="javascript:;">收款</a>
                          <a  data-target="#myModal" data-toggle="modal" type="button" class="color_F99E12 qrcode_btn" onclick="show_qrcode(<?php echo $v['id']?>)" href="javascript:;">二维码</a>
                          <a class="color_F99E12" href="<?php echo site_url('okpay/types/edit?ids='.$v['id'])?>">编辑</a>
                        </td>
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

<script>
    var times;
    function show_qrcode(id){
        if(!id){
            return false;
        }
        $('#myModal div[class="modal-body"]').html('');
        $.getJSON('<?php echo site_url('okpay/types/check_type')?>?id='+id,{},function(data){
            if(parseInt(data.status) == 1){
                var html = '<div style="margin-bottom: 20px;"><input type="radio" name="ck_qrcode" checked="checked" value="1"/>通用支付&nbsp;&nbsp;&nbsp;&nbsp;';
                html += '<input type="radio" name="ck_qrcode" value="2"/>分销支付<input type="hidden" id="hid_pay_type" value="" /></div>';
                html += '<div id="div_sale_input" style="margin-bottom: 20px; display:none;">分销号：<input type="text" id="ipt_sale" style="width:60px; line-height: 24px; padding-bottom: 2px;" />&nbsp;&nbsp;&nbsp;&nbsp;<button id="btn_make_qrcode" class="btn btn-sm bg-green"><i class="fa fa-qrcode"></i>二维码</button>';
                html += '</div></div><div id="no_sale_code_img"><img id="img_no_code_qrcode" src="<?php echo base_url(FD_PUBLIC). "/". $tpl ?>/dist/img/loading.gif" /></div>';
                html += '<div id="has_sale_code_img" style="display:none;">说明：该二维码是和员工的分销号关联着，顾客由此二维码支付成功后，订单流水中将会记录对应的分销号</div>';

                $('#myModal div[class="modal-body"]').html(html);
                $("#hid_pay_type").val(id);
                $("#img_no_code_qrcode").attr("src","<?php echo EA_const_url::inst()->get_url("*/*/qrcode_front"); ?>?ck=1&ids="+id);
                $("input[name='ck_qrcode']").click(function(){
                    var ck_num = parseInt($(this).val());
                    if(1 == ck_num){
                        $("#div_sale_input").hide();
                        $("#no_sale_code_img").show();
                        $("#has_sale_code_img").hide();
                    }else{
                        $("#div_sale_input").show();
                        $("#no_sale_code_img").hide();
                        $("#has_sale_code_img").show();
                    }
                });
                $("#btn_make_qrcode").click(function(){
                    var sale_code = $.trim($("#ipt_sale").val());
                    if(sale_code != ""){
                        var pay_type = $("#hid_pay_type").val();
                        var base_url= '<?php echo EA_const_url::inst()->get_url("*/*/qrcode_front"); ?>?ck=2&ids=';
                        var img= '<img id="img_has_code_qrcode" src="'+ base_url+ pay_type+'&paycode='+sale_code+ '" />';
                        $("#has_sale_code_img").html(img);
                    }
                });
            }else{
                $('#myModal div[class="modal-body"]').html('<div class="alert alert-danger">当前场景不可用，无法继续生成二维码</div>');
            }
        },'json');

    }
$(function(){
$("input[name='ck_qrcode']").click(function(){
  var ck_num = parseInt($(this).val());
  if(1 == ck_num){
    $("#div_sale_input").hide();
    $("#no_sale_code_img").show();
    $("#has_sale_code_img").hide();
  }else{
    $("#div_sale_input").show();
    $("#no_sale_code_img").hide();
    $("#has_sale_code_img").show();
  }
});
$("#btn_make_qrcode").click(function(){
  var sale_code = $.trim($("#ipt_sale").val());
  if(sale_code != ""){
    var pay_type = $("#hid_pay_type").val();
    var base_url= '<?php echo EA_const_url::inst()->get_url("*/*/qrcode_front"); ?>?ck=2&ids=';
    var img= '<img id="img_has_code_qrcode" src="'+ base_url+ pay_type+'&paycode='+sale_code+ '" />';
    $("#has_sale_code_img").html(img);
  }
});

  $('.drow_list li').click(function(){
    $('#search_hotel').val($(this).text());
    $(this).addClass('cur').siblings().removeClass('cur');
  });
  $('.cancel,.cancel_2,.see_btn ').click(function(){
    $('.fixed_box').hide();
    $('.menoy_input').val('');
    $('.times_t').html('60s');
    clearInterval(times);
    $('.state_1,.con_1,.confirms,.cancel').show();
    $('.state_4,.state_3,.state_2,.con_2,.cancel_2,.see_btn').addClass('display_none');
    $('.confirms,#pay_code').unbind();
    $('#pay_code').val('');
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
});
function get_money(id){
    if(!id){
        return false;
    }
    ajax1(id);
}
function ajax1(id){
  $.getJSON('<?php echo site_url('okpay/types/check_type')?>?id='+id,{},function(data){
        if(parseInt(data.status) == 1){
            $('.fixed_box').show();  //支付输入
            var bools=true;
            $('.confirms').on('click',function(event){//console.log(234);return;
              console.log(2);
                if($('.menoy_input').val()!=''){
                    pay_money = $('.menoy_input').val();
                    if(isNaN(pay_money) || pay_money<0 || pay_money==''){
                        alert('输入金额有误');return false;
                    }
                    if(pay_money.length >= 2 && pay_money == "00"){
                        alert('输入金额有误');return false;
                    }
                    var point = pay_money.indexOf(".");
                    if(point > 0){
                        var little_num = pay_money.substring((point+1));
                        if(little_num.length > 2){
                            alert('输入金额有误');return false;
                        }
                    }
                    var zero = pay_money.substring(0,1);
                    if(zero == "0" && pay_money.length > 1 && point != 1){
                        alert('输入金额有误');return false;
                    }
                    //请求生成订单
                    url = '<?php echo site_url('okpay/types/create_ordor_in_type')?>';
                    ajax2(url,id)

                }
            })
        }else{
            alert('当前场景不可用!');
        }
    },'json');
}
function ajax2(url,id){
  $.post(url,
    {
      'type_id':parseInt(id),
      'pay_money':pay_money,
      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    },function(res){
      $('#down_count').html("<span class='color_ec898a times_t' style='width:auto;'>60s</span>内未支付自动关闭");
      var money=$('.menoy_input').val();
      $('.con_1').hide();
      $('.con_2').removeClass('display_none');
      $('.receivables_money').html('收款金额：'+money);
      $('.confirms').hide();
      $('#pay_code').focus();
      set_time(res.data.order_sn);
      ajax3(res);
  },'json');
}
function ajax3(res){
    $('#pay_code').change(function(){
        $('#down_count').html('等待付款中...');
        clearInterval(times);
        //alert('234234');return false;
        //ajax3(res);
        $.post('<?php echo site_url('okpay/types/okpay_pay')?>',{
            'inter_id':res.data.inter_id,
            'order_sn':res.data.order_sn,
            'auth_code':$('#pay_code').val(),
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(data){
            clearInterval(times);
            if(data.errcode != 0){
                alert(data.msg);
                $('.state_1').hide();
                clearInterval(times);
                $('.state_3').addClass('display_none');
                $('.state_4').removeClass('display_none');
                return ;
            }else{//成功
                // alert(data.msg);
                $('#show_order_sn').text(res.data.order_sn);
                $('#check_order').attr('href','<?php echo site_url("okpay/orders/edit?ids=")?>'+res.data.order_id);
                clearInterval(times);
                $('.state_1,.confirms,.cancel').hide();
                $('.state_2,.cancel_2,.see_btn').removeClass('display_none');
                return;
            }
        },'json');
    });

}
function set_time(order_sn){
    var munber=60;
    times=setInterval(function(){   //支付倒计时开始
        munber-=1;
        /*if(0){   //支付成功
            clearInterval(times);
            $('.state_1,.confirms,.cancel').hide();
            $('.state_2,.cancel_2,.see_btn').removeClass('display_none');
            return;
        }*/
        /*if(code==0){   //支付失败
         clearInterval(times);
         $('.state_1').hide();
         $('.state_4').removeClass('display_none');
         return;
         }*/
        if(munber==0){  //支付超时
            $.post('<?php echo site_url('okpay/types/update_order_status')?>',{
                'order_sn':order_sn,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(data){
                alert(data.msg);
            },'json');
            clearInterval(times);
            $('.state_1').hide();
            $('.state_3').removeClass('display_none');
        }
        $('.times_t').html(munber+'s');
    },1000);
}


</script>
</body>
</html>
