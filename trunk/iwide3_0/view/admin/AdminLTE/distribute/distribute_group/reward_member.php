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

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
.btn{min-width:100px}
.marbtm{margin-bottom:12px;}
._list span{display:inline-block;width:24%;}
table a{color:#39C}
</style>
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
    <section class="content">
		<?php echo $this->session->show_put_msg(); ?>
        <!--
        <div class="box-header">
        <h3 class="box-title">Data Table With Full Features</h3>
        </div><!-- /.box-header -->
        <div class="bd bg_fff pad10 _list">
        	<div class="marbtm">
            	<span>奖励ID：<?php echo empty($reward_info['reward_id']) ? '' : $reward_info['reward_id']?></span>      
            	<span>名称：<?php echo empty($reward_info['reward_name']) ? '' : $reward_info['reward_name']?></span>
                <span>创建时间：<?php echo empty($reward_info['add_time']) ? '' : $reward_info['add_time']?></span>
                <span>核定来源：<?php echo isset($reward_info['source'])&&$reward_info['source']==1?'订房':'商城'?></span>
            </div> 
        	<div class="marbtm">
            	<span>有效期始：<?php echo empty($reward_info['start_time']) ? '' : $reward_info['start_time']?></span>
                <span>有效期止：<?php echo empty($reward_info['end_time']) ? '' : $reward_info['end_time']?></span>
                <span>名额上限：<?php echo empty($reward_info['limit_count']) ? '' : $reward_info['limit_count']?></span>
                <span>已奖人数：<?php echo isset($reward_info['reward_count'])? $reward_info['reward_count'] : 0?></span>
            </div>
        </div>
            <table id="data-grid" class="table martop table-striped table-condensed">
              <thead>
              <tr role="row">
                 <th>奖励序号</th>
                 <th>分销员</th>
                 <th>分销号</th>
                 <th>当前核定数量</th>
                 <th>绩效金额</th>
                 <th>进入分组时间</th>
                 <th>绩效发放时间</th>
              </tr>
              </thead>
              <tbody>
              <?php
              if(!empty($res)){
              foreach ( $res as $k=>$item2 ):?>
                <tr>
                  <td><?=$item2['reward_id'].'-'.($k+1)?></td>
                  <td><?=$item2['saler_name']?></td>
                    <td><?=$item2['saler_id']?></td>
                  <td><?=$group_info['check_count']?></td>
                    <td><?=$item2['grade_total']?></td>
                  <td><?=date('Y-m-d',$item2['create_time'])?></td>
                  <td><?=empty($item2['send_time'])?'--':$item2['send_time']?></td>
                </tr>
              <?php endforeach;}?>
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
