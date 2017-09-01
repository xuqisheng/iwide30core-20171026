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
                  <?php echo form_open('distribute/follower_report/detail','class="form-inline"')?>

                  <div class="form-group">
                    <label>Inter_id </label><input type="text" name="inter_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['inter_id']) ? '' : $posts['inter_id']?>">
                  </div>
                  <div class="form-group">
                    <label>统计时间 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="start_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['start_time']) ? '' : $posts['start_time']?>">
                    <label>至 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyy-mm-dd" type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>">
                  </div>
                  <div class="form-group">
                    <div class="modal fade" id="setModal">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">选择</h4>
                          </div>
                          <div class="modal-body">
                            <ul class="form-control _select_hotel _point list-unstyled"  style="height:320px;overflow:auto;width:50%">
                              <li class="all_check"><input value='' type="checkbox" id="all_hotel" > <span>所有酒店公众号</span></li>
                              <?php foreach($public as $key=>$name) {?>
                                <li class="noe_check" >
                                  <input value='<?php echo $name['inter_id'];?>' <?php echo isset($select_hotel)&&in_array($name['inter_id'],$select_hotel)?' checked="checked" ':''?> name="hotel_public[]"  type="checkbox" >
                                  <span><?php echo $name['name'];?></span>
                                </li>
                              <?php } ?>
                            </ul>
                            <div id='cfg_items'>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_con">确定</button>
                          <!--  <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>-->
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <label>选择酒店: </label>

                    <div class="btn-group">
                      <button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;选择</button>
                    </div>
                  </div>
                  <div class="btn-group" id="show_result">

                </div>
                  <div class="btn-group">
                    <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>&nbsp;&nbsp;
                  </div>
                <div class="btn-group">
                  <button type="submit" class="btn btn-sm bg-green" name="export" value="1"><i class="fa fa-search"></i>&nbsp;导出</button>&nbsp;&nbsp;
                </div>
                </div>
                </form>
              </div>
            </div>

            <table id="data-grid" class="table table-bordered table-striped table-condensed">
              <thead>
              <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
               -->
              <tr role="row">
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">inter_id</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">酒店公众号名称</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">新增关注数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">取消关注数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">净增关注数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分销关注数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">累计关注数</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">新增关注数排名</th>


              </tr>
              <tfoot></tfoot>
              <th></th>
              <tbody>
              <?php
              foreach ( $res as $ii=>$item2 ):?>
                <tr>
                  <td><?=$item2['inter_id']?></td>
                  <td><?=$item2['public_name']?></td>
                  <td><?=$item2['new_sub_count']?></td>
                  <td><?=$item2['new_unsub_count']?></td>
                  <td><?=$item2['new_add_count']?></td>
                  <td><?=$item2['saler_nums']?></td>
                  <td><?=$item2['all_sub_count']?></td>
                  <td><?=$item2['new_sub_sort']?></td>

                </tr>
              <?php endforeach;?>
              </tbody>
            </table>

            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条</div>
              </div>
              <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                  <ul class="pagination"><?php echo $pagination?></ul>
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
  $(function(){
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
