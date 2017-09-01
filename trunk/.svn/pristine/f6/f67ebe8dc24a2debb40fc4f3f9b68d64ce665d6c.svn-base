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
<!-- <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/raphael-min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/morris/morris.min.js"></script> -->
<!-- <script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script> -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/highcharts.js"></script>
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

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>平台粉丝数据统计
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

                </div>

                  <table id="data-grid" class="table table-bordered table-striped table-condensed">
                    <thead>

                        <tr role="row">
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="时间: activate to sort column ascending" style="text-align: center;">时间</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="平台总粉丝数: activate to sort column ascending" style="text-align: center;">平台总粉丝数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="新增关注数: activate to sort column ascending" style="text-align: center;">新增关注数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="取消关注数: activate to sort column ascending" style="text-align: center;">取消关注数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="净增关注数: activate to sort column ascending" style="text-align: center;">净增关注数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销关注数: activate to sort column ascending" style="text-align: center;">分销关注数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="累计关注数: activate to sort column ascending" style="text-align: center;">累计关注数</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="分销粉丝占比: activate to sort column ascending" style="text-align: center;">分销粉丝占比</th>
                          <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="在线门店总数: activate to sort column ascending" style="text-align: center;">在线门店总数</th>

                        </tr>


                    <tr>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;">昨天</th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['all_fans_count'])?$res1['all_fans_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['new_sub_count'])?$res1['new_sub_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['new_unsub_count'])?$res1['new_unsub_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['new_add_count'])?$res1['new_add_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['saler_nums'])?$res1['saler_nums']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['all_sub_count'])?$res1['all_sub_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['saler_rate'])?(number_format($res1['saler_rate'],4,'.','')*100).'%':''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($res1['all_publics'])?$res1['all_publics']:''?></th>
                    </tr>
                    <tr>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;">环比</th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['all_fans_count'])&&!empty($rate['all_fans_count'])?$rate['all_fans_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['new_sub_count'])&&!empty($rate['new_sub_count'])?$rate['new_sub_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['new_unsub_count'])&&!empty($rate['new_unsub_count'])?$rate['new_unsub_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['new_add_count'])&&!empty($rate['new_add_count'])?$rate['new_add_count']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['saler_nums'])&&!empty($rate['saler_nums'])?$rate['saler_nums']:''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['all_sub_count'])&&!empty($rate['all_sub_count']) ? $rate['all_sub_count'] : ''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['saler_rate'])&&!empty($rate['saler_rate'])? $rate['saler_rate'] : ''?></th>
                        <th width="8%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1"  style="text-align: center;"><?php echo isset($rate['all_publics'])&&!empty($rate['all_publics']) ? $rate['all_publics']:''?></th>

                    </tr>
                      <tr>

                      </tr>
                    </tbody>

                  </table>
                  <div style="padding: 5px;">
                      <ul id="myTab" class="nav nav-tabs">
                          <li class="active"><a href="#home" onclick="get_highchart(1);" data-toggle="tab">
                                  昨天</a>
                          </li>
                          <li><a href="#ios" onclick="get_highchart(7);" data-toggle="tab">最近7天</a></li>
                          <li><a href="#ios" onclick="get_highchart(30);" data-toggle="tab">最近30天</a></li>
                      </ul>
                      <div id="myTabContent" class="tab-content">

                      </div>
                  </div>
                  <!-- 统计表 -->
                  <div id="purchase-total"></div>
                  <!-- end-->
                  <div style="font-size:16px;float: right;">
                      <a href="<?php echo  site_url('distribute/follower_report/detail')?>">查看更多</a>
                  </div>
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <div class="loading" style="position:fixed; top:45%; text-align:center; z-index:9999999; width:100%;display: none;">
        <span style="padding:10px 20px; border:1px solid #e4e4e4; background:#fff;">数据正在加载..</span>

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

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- page script -->
<script>


var buttons = $('<div class="btn-group"></div>');

var grid_sort= [[ , "" ]];

<?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
var url_extra= [
//'http://iwide.cn/',
];

$(".form_datetime").datepicker({
  format: 'yyyymm', 
  startDate:new Date(2013,12,01),
  endDate:'+1',//结束时间，在这时间之后都不可选
  weekStart: 1, 
  autoclose: true,
  startView: 2, 
  maxViewMode: 1,
  minViewMode:1,
  forceParse: false, 
  language: 'zh-CN',
});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("hotel/hotel_report/save_cofigs?ctyp=ORDERS_BY_ROOMNIGHT")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});
$(document).ready(function() {
    get_highchart(1);
});

    function get_highchart(check_date){


  $('.loading').show();
  $.get('<?php echo site_url("distribute/follower_report/ajax_get_data")?>',
    {
      'check_date':check_date,
    //  'time_type':time_type,
    //  'month_start':month_start,
    //  'month_end':month_end
    },function(data){
       //console.log(data);
      $('.loading').hide();
      data = JSON.parse(data); //console.log(data);
      var date_list = data[0]['date'];//横坐标
      var new_sub = data[0]['new_sub'];//新增关注
      var new_unsub = data[0]['new_unsub'];//取消关注
      var new_add = data[0]['new_add'];//净增

     /* for(x in data){
        categories.push(data[x]['date']);
        usercounts.push(parseInt(data[x]['user_count']));
        two.push(data[x]['u2']);
        three.push(data[x]['u3']);
        five.push(data[x]['u5']);
        ten.push(data[x]['u10']);

      }*/
      //$('#res').html(html);
     //  console.log(new_sub);
     //  console.log(new_unsub);
      // console.log(new_add);
      // console.log(three);
      // console.log(five);
      // console.log(ten);
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
              name: '新增关注数',
              data: new_sub
          }, {
              name: '取消关注数',
              data: new_unsub
          }, {
              name: '净增关注数',
              data: new_add
          }]
          });

  });
}
//});
</script>
</body>
</html>
