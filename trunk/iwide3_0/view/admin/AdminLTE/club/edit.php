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
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/echarts-2.2.7/echarts.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/circle_canvas/percentageAnimation.js"></script>
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
#coupons_table2_length{display:none;}
.classification_n{margin-bottom:0px;}
.classification_n >div{width:auto;padding:0 40px;}
.pie > div{height:230px;width:284px;text-align:center;padding:20px;}
@media screen and (max-width: 1100px){
  .pie > div {
      width: 240px;
  }

}
</style>
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
        <form action="" id="setting_form" method="post" accept-charset="utf-8">
          <div class="checkbox"><label><input type="checkbox" name="event_time" disabled="" checked="">关注时间</label></div>
          <div class="checkbox"><label><input type="checkbox" name="sale" disabled="" checked="">分销号</label></div>
          <div class="checkbox"><label><input type="checkbox" name="name" disabled="" checked="">分销员</label></div>
          <div class="checkbox"><label><input type="checkbox" name="hotelname" disabled="" checked="">所属酒店</label></div>
          <div class="checkbox"><label><input type="checkbox" name="openid" disabled="" checked="">粉丝openid</label></div>
          <div class="checkbox"><label><input type="checkbox" name="nickname" disabled="" checked="">昵称</label></div>
          <div class="checkbox"><label><input type="checkbox" name="is_bind_mem_card_no" disabled="" checked="">是否绑定会员号</label></div>
          <div class="checkbox"><label><input type="checkbox" name="pms_card_no" disabled="" checked="">pms_会员号</label></div>
          <div class="checkbox"><label><input type="checkbox" name="mem_level" disabled="" checked="">会员级别</label></div>
          <div class="checkbox"><label><input type="checkbox" name="follow_status" disabled="" checked="">关注状态</label></div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
<div style="color:#92a0ae;">
    <div class="over_xs">
        <div class="content-wrapper">
            <div class="banner p_0_20 bg_fff color_333">
               社群客/社群客列表 
            </div>
            <div class="contents_list bg_fff">
              <div class="p_r_30 classification_n bg_fff" sytle="padding-left:20px;">
                <div class="add_active"><a href="javascript:;">  数据统计</a></div>
                <div class=""><a href="javascript:;"> 订单统计总表</a></div>
                <div class=""><a href="javascript:;"> 收益排行</a></div>
              </div>
            </div>
            <div class="contents m_t_10" >
              <div class="box-body">
                <div class="pie m_b_20 display_flex">
                    <div class="bg_fff border_1 b_radius_6 m_r_20">
                      <div class="radius1">
                        <p class="r_titles f_s_14 color_888">订单数</p>
                        <p class="r_numbers f_s_16 color_333">768940</p>
                      </div>
                      <canvas id="one" width="260" height="260" style="width:130px;height:130px;"></canvas>
                    </div>
                    <div class="bg_fff border_1 b_radius_6 m_r_20">
                      <div class="radius1">
                        <p class="r_titles f_s_14 color_888">间夜数</p>
                        <p class="r_numbers f_s_16 color_333">4553940</p>
                      </div>
                      <canvas id="two" width="260" height="260" style="width:130px;height:130px;"></canvas>
                    </div>
                    <div class="bg_fff border_1 b_radius_6">
                      <div class="radius1">
                        <p class="r_titles f_s_14 color_888">收入</p>
                        <p class="r_numbers f_s_16 color_333">3243870</p>
                      </div>
                      <canvas id="three" width="260" height="260" style="width:130px;height:130px;"></canvas>
                    </div>
                </div>
                <div class="bg_fff border_1 b_radius_6">
                    <div id="main" style="height:460px;padding:10px;margin:20px;"></div>
                </div>
              </div>
              <div class="box-body"  style="display:none;">
                <table class="new_tabels"  style="width:100%;">
                  <thead class="bg_f8f9fb form_thead color_333">
                    <tr class="bg_f8f9fb form_title">
                      <th class="b_r_1"></th>
                      <th>订单数</th>
                      <th>总占比</th>
                      <th>间夜数</th>
                      <th>总占比</th>
                      <th>收入</th>
                      <th>总占比</th>
                      <th>间夜／订单</th>
                      <th>平均房价</th>
                      <th>平均订单收入</th>
                      <th>环比增长</th>
                    </tr>
                  </thead>
                  <tbody class="containers dataTables_wrapper form-inline dt-bootstrap color_555 f_s_12">
                    <tr class="form_con">
                      <td class="b_r_1">2017年1月</td>
                      <td>1256</td>
                      <td>80%</td>
                      <td>888</td>
                      <td>42%</td>
                      <td>12351</td>
                      <td>50%</td>
                      <td class="">56%</td>
                      <td class="">56%</td>
                      <td class="">56%</td>
                      <td class="">56%</td>
                    </tr>
                    <tr class="form_con">
                      <td class="b_r_1"></td>
                      <td>总数</td>
                      <td>100%</td>
                      <td>总数</td>
                      <td>42%</td>
                      <td>总数</td>
                      <td>50%</td>
                      <td class="">平均值</td>
                      <td class="">平均值</td>
                      <td class="">5平均值6%</td>
                      <td class="">（本月-上月）/本月</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="box-body" style="display:none;">
                <table id="coupons_table2" class="table-striped table-condensed dataTable no-footer new_tabels" style="width:100%;">
                  <thead class="bg_f8f9fb form_thead  color_333">
                    <tr class="bg_f8f9fb form_title">
                      <th>排名</th>
                      <th>酒店名称</th>
                      <th>社群名</th>
                      <th>销售员</th>
                      <th>订单数</th>
                      <th>间夜数</th>
                      <th>平均房价</th>
                      <th>实际收益</th>
                      <th>绩效金额</th>
                    </tr>
                  </thead>
                  <tbody class="containers dataTables_wrapper form-inline dt-bootstrap  color_555">
                    <tr class="form_con">
                      <td>112</td>
                      <td>金房卡酒店</td>
                      <td>张总的朋友</td>
                      <td>懒人-03</td>
                      <td>587973</td>
                      <td>5879733</td>
                      <td>58797312</td>
                      <td class="">5879733123</td>
                      <td class="">¥78443</td>
                    </tr>
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
  $('#one').percentageAnimation({
      subtitle : '%',
      percentage: 0.8,
      numberFont : '32px Microsoft YaHei',
      coverColor : '#7385b8',
      subFont:'32px Microsoft YaHei',
      radius :'90',
      subColor:'#7385b8',
      lineWidth:24
  });
  $('#two').percentageAnimation({

      subtitle : '%',
      percentage: 0.6,
      numberFont : '32px Microsoft YaHei',
      coverColor : '#fa6a6a',
      subFont:'32px Microsoft YaHei',
      subColor:'#fa6a6a',
      radius :'90',
      lineWidth:24
  });
  $('#three').percentageAnimation({

      subtitle : '%',
      percentage: 0.4,
      numberFont : '32px Microsoft YaHei',
      coverColor : '#5dbde0',
      subFont:'32px Microsoft YaHei',
      subColor:'#5dbde0',
      radius :'90',
      lineWidth:24
  });
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
  $('#coupons_table2').DataTable({
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
        "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });
})
require.config({
    paths: {
        echarts:'http://test008.iwide.cn/public/AdminLTE/plugins/echarts-2.2.7'
    }
});
$(window).resize(function(){
  resize_canvas()
});
resize_canvas();
function resize_canvas(){
  require(
      [
          'echarts',
          'echarts/theme/macarons', 
          'echarts/chart/line',
          'echarts/chart/pie'
      ],
      function (ec,macarons){
          //--- 折柱 ---
          var myChart = ec.init(document.getElementById('main'),macarons);
          option = {
              title : {
                  text: '2017年2月与3月 社群客订单成交环比图',
                  subtext: '',
                  textStyle: {
                      fontSize: 14,
                      fontWeight: 'bolder',
                      color: '#333'          // 主标题文字颜色
                  }
              },
              tooltip : {
                  trigger: 'axis'
              },
              legend: {
                  data:['订单数','间夜数','收入']
              },
              calculable : true,
              xAxis : [
                  {
                      type : 'category',
                      boundaryGap : false,
                      data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                  }
              ],
              yAxis : [
                  {
                      type : 'value'
                  }
              ],
              series : [
                  {
                      name:'订单数',
                      type:'line',
                      smooth:true,
                      itemStyle: {normal: {areaStyle: {type: 'default'}}},
                      data:[10, 12, 21, 54, 260, 830, 710, 0, 10, 1710, 590, 820]
                  },
                  {
                      name:'间夜数',
                      type:'line',
                      smooth:true,
                      itemStyle: {normal: {areaStyle: {type: 'default'}}},
                      data:[23, 152, 89, 45, 260, 830, 610, 450, 480, 110, 900, 420]
                  },
                  {
                      name:'收入',
                      type:'line',
                      smooth:true,
                      itemStyle: {normal: {areaStyle: {type: 'default'}}},
                      data:[100, 212, 217, 543, 20, 83, 790, 170, 630, 170, 480, 620]
                  }
              ]
          };
          myChart.setOption(option);
      }
  );
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
