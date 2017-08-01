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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>详情
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
                          <form class="form-inline" method='get' action='<?php echo site_url('roomservice/statistic/index')?>'>

                          <div class="form-group">
                              <label>订单时间 </label>
                              <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="start_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($filter['start_time']) ?'' : $filter['start_time']?>">
                              <label>至 </label>
                              <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($filter['end_time']) ? '' : $filter['end_time']?>">
                          </div>
                          <div class="form-group">
                              <label>场景 </label>
                              <select name='type' class="form-control input-sm">
                                  <option value="">--全部--</option>
                                  <option value="1" <?php echo isset($filter['type'])&&$filter['type']==1?'selected':''?>>--房间--</option>
                                  <option value="2" <?php echo isset($filter['type'])&&$filter['type']==2?'selected':''?>>--堂食--</option>
                                  <option value="3" <?php echo isset($filter['type'])&&$filter['type']==3?'selected':''?>>--外卖--</option>
                                  <option value="4" <?php echo isset($filter['type'])&&$filter['type']==4?'selected':''?>>--即时核销--</option>
                                  </select>
                          </div>

                          <div class="btn-group">
                              <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                          </div>
                              <div class="btn-group">
                                  <button type="submit" class="btn btn-sm bg-green" name="export" value="1" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;导出</button>
                              </div>

                          </form>
                      </div>
                  </div>
              </div>

            <table id="data-grid" class="table table-bordered table-striped table-condensed">
              <thead>
              <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
               -->
              <tr role="row">
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">inter_id</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">酒店公众号名称</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">房间数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">交易人数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">交易订单数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">成功人数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">成功订单数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">实际收入</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">交易成功率</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">复购率</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">待接单订单</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">待配送订单</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">已配送订单</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">已完成订单</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">已取消订单</th>


              </tr>
              <tfoot></tfoot>
              <tbody>
              <?php
              if(!empty($res)){
              foreach ( $res as $ii=>$item2 ):?>
                <tr>
                  <td><?=$item2['inter_id']?></td>
                  <td><?=isset($publics[$item2['inter_id']])?$publics[$item2['inter_id']]:'--'?></td>
                    <td><?=$item2['room_num']?></td>
                  <td><?=$item2['all_mem_count']?></td>
                  <td><?=$item2['all_orders_count']?></td>
                  <td><?=$item2['success_mem_count']?></td>
                  <td><?=$item2['success_order_count']?></td>
                  <td><?=$item2['income_money']?></td>
                  <td><?=$item2['success_order_rate']?></td>
                    <td><?=$item2['fu_success_order_rate']?></td>
                    <td><?=$item2['wait_accept']?></td>
                    <td><?=$item2['wait_send']?></td>
                    <td><?=$item2['sending']?></td>
                    <td><?=$item2['finish']?></td>
                    <td><?=$item2['cancel']?></td>
                </tr>
              <?php endforeach;?>
              <?php }?>
              </tbody>
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
  //计算选了几个
  $(function(){
        $('#jump_url').click(function(){
            var i = $('#type_id option:selected').val();
            if(i==-1){
                location.href='<?php echo site_url('roomservice/statistic/index')?>'
            }else{
                location.href='<?php echo site_url('roomservice/statistic/index')?>'+'?type='+i;
            }
        });
  });


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
      $.getJSON('<?php echo site_url("distribute/distri_report/get_cofigs?ctyp=dist_fans_sale")?>',function(data){
        if(data != null){
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
        }


      });

    })});
  $('#set_btn_save').click(function(){

  });
  $(document).ready(function() {
    <?php
    // $num= (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
    // if( count($result['data'])<$num)
    // 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs.php';
    // else
    // 	require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'gridjs_ajax.php';
    ?>
  });
</script>
</body>
</html>
