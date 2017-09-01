<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>


<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/highcharts.js"></script>
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="box-body">
          <div class="form-group">
              <a  class="btn btn-sm bg-blue" href="<?php echo site_url('distribute/saler_report/index')?>">业绩排名</a>
                  <a class="btn btn-sm bg-green" href="<?php echo site_url('distribute/saler_report/fans_data')?>">时间分布</a>
                      <a class="btn btn-sm bg-blue" href="<?php echo site_url('distribute/saler_report/transform')?>">转化情况</a>
                          <a class="btn btn-sm bg-blue" href="<?php echo site_url('distribute/saler_report/saler_picture')?>">分销员画像</a>
          </div>
      </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>时间分布详情
        <small></small>
      </h1>
      <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <?php echo $this->session->show_put_msg(); ?>
            <!--
              <div class="box-header">
                <h3 class="box-title">Data Table With Full Features</h3>
              </div><!-- /.box-header -->

            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <?php echo form_open('distribute/saler_report/fans_data','class="form-inline"')?>

                  <div class="form-group">
                    <label>Inter_id </label><input type="text" name="inter_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['inter_id']) ? '' : $posts['inter_id']?>">
                  </div>
                  <div class="form-group">
                    <label>统计时间 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="start_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['start_time']) ? '' : $posts['start_time']?>">
                    <label>至 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>">
                  </div>

                  <div class="btn-group" id="show_result">

                </div>
                  <div class="btn-group">
                    <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>&nbsp;&nbsp;
                  </div>

                </div>
                </form>
              </div>
            </div>



            <div id="purchase-total"></div>
            <!--<div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?/*=$total*/?>条</div>
              </div>
              <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                  <ul class="pagination"><?php /*echo $pagination*/?></ul>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>
  //计算选了几个
  /*$(function(){
    var num = 0;
    $(":checkbox").each(function () {
      if ($(this)[0].checked) {
        ++num;
      }
    });
    $("#show_result").html('已经选择'+num+'个');
  });
  $("#btn_con").click(function() {
    var num = 0;
    $(":checkbox").each(function () {
      if ($(this)[0].checked) {
        ++num;
      }
    });
    $("#show_result").html('已经选择'+num+'个');
  });
  //全选酒店
  $("#all_hotel").click(function(){
    if ($(this).prop("checked")) {
      $("input[name='hotel_public[]']").each(function() {
        $(this).prop("checked", true);
      });
    } else {
      $("input[name='hotel_public[]']").each(function() {
        $(this).prop("checked", false);
      });
    }
  });*/
  <?php

  ?>
  var buttons = $('<div class="btn-group"></div>');

  var grid_sort= [[ , "" ]];

  <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
  var url_extra= [
//'http://iwide.cn/',
  ];
  var baseStr = "";
  $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
  $('#grid-btn-set').click(function(){
    $('#setModal').on('show.bs.modal', function (event) {
// 	  modal.find('.modal-body input').val(recipient)
      var str = $('#setting_form').html();
      if(baseStr != ""){
        str = baseStr;
      }else{
        baseStr = str;
      }


    })});
  $('#set_btn_save').click(function(){

  });
  $(document).ready(function() {

  });
/*
  $('.loading').hide();
*/
  data = '<?php echo $res?>';//console.log(data);
  data = JSON.parse(data); console.log(data);
  var date_list = data['date'];//横坐标
  var dev_fans = data['dev'];//粉丝发展
  var sale_fans = data['sale'];//粉丝交易

  /* for(x in data){
   categories.push(data[x]['date']);
   usercounts.push(parseInt(data[x]['user_count']));
   two.push(data[x]['u2']);
   three.push(data[x]['u3']);
   five.push(data[x]['u5']);
   ten.push(data[x]['u10']);

   }*/
  //$('#res').html(html);
    console.log(date_list);
    console.log(dev_fans);
   console.log(sale_fans);
  // console.log(three);
  // console.log(five);
  // console.log(ten);
  $(function(){
    $('#purchase-total').highcharts({
      title: {
        text: '折线图',
        x: -20 //center
      },

      xAxis: {
        categories:date_list
      },
      yAxis: {
        title: {
          text: '数量'
        },
        plotLines: [{
          value: 0,
          width: 1,
          color: '#808080'
        }]
      },
      tooltip: {
        valueSuffix: '{value}人'
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
      },
      series: [{
        name: '粉丝发展',
        data: dev_fans
      }, {
        name: '粉丝交易',
        data: sale_fans
      }]
    });

  });


</script>
</body>
</html>
