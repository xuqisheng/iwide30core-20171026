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
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
          <?php //echo form_open('distribute/distribute_group/group_index?type='.$type,'id="setting_form"')?>

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
      <h1>会员列表
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

            <div class="box-body form-inline">
              <div class="row">
                <div class="col-sm-12">

                  <div class="form-group">
                    <label> 分组ID :</label><?php echo empty($group_info['group_id']) ? '' : $group_info['group_id']?>
                  </div>
                  <div class="form-group">
                    <label> 名称 :</label><?php echo empty($group_info['group_name']) ? '' : $group_info['group_name']?>
                  </div>
                  <div class="form-group">
                    <label> 创建时间 :</label><?php echo empty($group_info['create_time']) ? '' : date('Y-m-d',$group_info['create_time'])?>
                  </div>
                  <div class="form-group">
                    <label> 有效期始 :</label><?php echo empty($group_info['start_time']) ? '' : date('Y-m-d',$group_info['start_time'])?>
                  </div>
                  <div class="form-group">
                    <label> 有效期止 :</label><?php echo empty($group_info['end_time']) ? '' : date('Y-m-d',$group_info['end_time'])?>
                  </div>
                  <div class="form-group">
                    <label> 核定周期 :</label><?php echo isset($group_info['check_date'])&&$group_info['check_date']==1 ? '周' : '月'?>
                  </div>
                  <div class="form-group">
                    <label> 核定来源 :</label><?php echo isset($group_info['source'])&&$group_info['source']==1 ? '订房' : '商城'?>
                  </div>
                  <div class="form-group">
                    <label> 核定方式 :</label><?php echo isset($group_info['check_type'])&&$group_info['check_type']==1 ? '间夜' : (isset($group_info['check_type'])&&$group_info['check_type']==2?'订单':'交易额')?>
                  </div>
                  <div class="form-group">
                    <label> 核定数量 :</label><?php echo isset($group_info['check_count'])? $group_info['check_count'] : 0?>
                  </div>
                  <div class="form-group">
                    <label> 当前组内成员 :</label><?php echo isset($group_info['member_count'])? $group_info['member_count'] : 0?>
                  </div>
                  <div class="form-group">
                    <label> 历史组内成员 :</label><?php echo isset($group_info['his_member_count'])? $group_info['his_member_count'] : 0?>
                  </div>
                  <div class="form-group">
                    <label> 分组状态 :</label><?php echo isset($group_info['status'])&&$group_info['status']==1? '有效' : '无效'?>
                  </div>
                  <div class="form-group">
                    <label> 当前周期 :</label><?php echo isset($week_num)? $week_num :0?>
                  </div>

                <!--  <div class="btn-group">
                    <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                    <button type="button" class="btn btn-sm bg-green" onclick="location.replace(location.href);" ><i class=""></i>&nbsp;重置</button>
                  </div>-->

                </div>
              </div>
            </div>

            <table id="data-grid" class="table table-bordered table-striped table-condensed">
              <thead>
              <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
               -->
              <tr role="row">
                <?php $index = 0;
                $fields = array();
                foreach ($nav_confs as $key=>$item){
                    ?>   <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="<?php echo $item['name'];?>: activate to sort column ascending"><?php echo $item['name'];?></th>
                    <?php $index ++;
                    $fields[] = $key;

                }?>
              </tr>
              <tfoot></tfoot>
              <th></th>
              <tbody>
              <?php
              foreach ( $res as $item2 ):?>
                <tr>
                  <td><?=$item2['week_num']?></td>
                  <td><?=$item2['saler_name']?></td>
                  <td><?=$item2['saler_id']?></td>
                  <td><?=$group_info['check_count']?></td>
                  <td><?=date('Y-m-d',$item2['complete_time'])?></td>
                  <td><?=$item2['complete_count']?></td>
                  <td><?=$item2['total_income']?></td>
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

  <?php
  // $sort_index= $model->field_index_in_grid($default_sort['field']);
  // $sort_direct= $default_sort['sort'];

  // $buttions= '';	//button之间不能有字符空格，用php组装输出
  // $buttions.= '<button type="button" class="btn btn-sm bg-green" id="grid-btn-add"><i class="fa fa-plus"></i>&nbsp;发放绩效</button>';
  // /*$buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-edit"><i class="fa fa-edit"></i>&nbsp;编辑</button>';
  // $buttions.= '<button type="button" class="btn btn-sm disabled" id="grid-btn-del"><i class="fa fa-trash"></i>&nbsp;删除</button>';*/
  // /*有更多的按钮，URL在此定义，id依次编号 id="grid-btn-extra0-1-2-...*/
  // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=1">员工</a>';
  // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=2">酒店</a>';
  // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=3">金房卡</a>';
  // $buttions.= '<a class="btn btn-default" id="grid-btn-extra-0" href="'.site_url('distribute/distribute/index').'?t=4">集团</a>';
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
    $.post('<?php echo site_url("distribute/distri_report/save_cofigs?ctyp=dist_fans_sale")?>',$("#setting_form").serialize(),function(data){
      if(data == 'success'){
        window.location.reload();
      }else{
        alert('保存失败');
      }
    });
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
