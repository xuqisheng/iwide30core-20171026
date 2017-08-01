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
                  <a class="btn btn-sm bg-blue" href="<?php echo site_url('distribute/saler_report/fans_data')?>">时间分布</a>
                      <a class="btn btn-sm bg-green" href="<?php echo site_url('distribute/saler_report/transform')?>">转化情况</a>
                          <a class="btn btn-sm bg-blue" href="<?php echo site_url('distribute/saler_report/saler_picture')?>">分销员画像</a>
          </div>
      </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>转化情况详情
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
                  <?php echo form_open('distribute/saler_report/transform','class="form-inline"')?>

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

<table>
    <tr><td><div id="id1"></div></td><td><div id="id2"></div></td></tr>
    <tr><td><div id="id3"></div></td><td><div id="id4"></div></td></tr>
</table>

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
/*
  $('.loading').hide();
*/
  data = '<?php echo $res?>';//console.log(data);
  data = JSON.parse(data);
  var dev_fans_count = parseInt('<?php echo $dev_fans_count?>');
  var sale_fans_count = parseInt('<?php echo $sale_fans_count?>');
  var fans_from_saler = parseInt('<?php echo $fans_from_saler?>');
  var sale_fans_from_saler = parseInt('<?php echo $sale_fans_from_saler?>');
  var fans_from_sence = parseInt('<?php echo $fans_from_sence?>');
  var sale_fans_from_sence = parseInt('<?php echo $sale_fans_from_sence?>');


  function get_highchars(id,text,data1,data2){
      $('#'+id).highcharts({
          chart: {
              plotBackgroundColor: null,
              plotBorderWidth: null,
              plotShadow: false
          },
          title: {
              text: text
          },
          tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: true,
                      format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                      style: {
                          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                      }
                  }
              }
          },
          series: [{
              type: 'pie',
              name: 'rate',
              data: [
                  ['无交易',(data1 - data2)],
                  ['有交易',data2]
              ]
          }]
      });
  }
  $(function(){
      get_highchars('id1','粉丝转化率',dev_fans_count,sale_fans_count);
      get_highchars('id2','员工发展转化率',fans_from_saler,sale_fans_from_saler);
      get_highchars('id3','场景发展转化率',fans_from_sence,sale_fans_from_sence);
      $('#id4').highcharts({
          chart: {
              plotBackgroundColor: null,
              plotBorderWidth: null,
              plotShadow: false
          },
          title: {
              text: '粉丝交易转化时间'
          },
          tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: false
                  },
                  showInLegend: true
              }
          },
          series: [{
              type: 'pie',
              name: 'rate',
              data: [
                  ['15分钟以内', data['time']['one']  ],
                  ['一个小时以内',data['time']['two'] ],
                  ['12小时以内',data['time']['three'] ],
                  ['一天以内',data['time']['four'] ],
                  ['1周以内', data['time']['five'] ],
                  ['1月以内',data['time']['six'] ],
                  ['1月以上', data['time']['seven']  ]
              ]
          }]
      });

  });



</script>
</body>
</html>
