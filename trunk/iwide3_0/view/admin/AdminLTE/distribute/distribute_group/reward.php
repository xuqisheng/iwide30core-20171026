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
.input{display:inline-block}
.input select,.input input{width:163px}
.marbtm{margin-bottom:12px;}
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
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
      <h1>分销分组奖励规则
        <small></small>
      </h1>
      <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
    </section>-->
    <!-- Main content -->
    <section class="content">
	<?php echo $this->session->show_put_msg(); ?>
          <?php echo form_open('distribute/distribute_group/reward','class="bd bg_fff pad10"')?>
		  <div class="marbtm">
          	<span>奖励ID</span>
            <span class="input"><input type="text" name="reward_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php echo empty($posts['reward_id']) ? '' : $posts['reward_id']?>"></span>
            <span style="margin-left:25px;">创建时间</span>
            <span class="input"><input data-date-format="yyyy-mm-dd" class="datetime" type="text" name="start_time" aria-controls="data-grid" value="<?php echo empty($posts['start_time']) ? '' : $posts['start_time']?>"></span>
            <span>-</span>
            <span class="input"><input data-date-format="yyyy-mm-dd" class="datetime" type="text" name="end_time" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>"></span>
            <span style="margin-left:25px;">核定来源</span>
            <span class="input"><select name='source'>
              <option value="-1"<?php if(empty($posts['source'])):echo ' selected';endif;?>>全部来源</option>
              <?php
                $source_arr = array('1'=>'订房','2'=>'商城');
                foreach ($source_arr as $key=>$val):?><option value="<?php echo $key?>"<?php if(!empty($posts['source']) && $key == $posts['source']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select></span>
            <span style="margin-left:25px;">状态</span>
            <span class="input"><select name='status'>
              <option value="-1"<?php if(empty($posts['status'])):echo ' selected';endif;?>>全部状态</option>
              <?php
              $status_arr = array('1'=>'有效','0'=>'无效');
              foreach ($status_arr as $key=>$val):?><option value="<?php echo $key?>"<?php if(isset($posts['status']) && $key == $posts['status']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select></span>
          </div>


          <div>
            <button type="submit" class="btn bg-green maright" id="grid-btn-search">检索</button>
         <!-- <div class="btn-group">  <button type="submit" class="btn btn-sm bg-green" name="export" value="1" ><i class=""></i>&nbsp;导出当前</button>-->
         <a type="button" href="<?php echo site_url('distribute/distribute_group/reward_add')?>" class="btn bg-orange">新增</a>
         </div>

    <?php echo form_close();?>
            <table id="data-grid" class="table martop table-striped table-condensed">
              <thead>
              <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
               -->
              <tr role="row">
                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">奖励ID</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">奖励名称</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">创建时间</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">有效期始</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">有效期止</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">关联分组</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">核定来源</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">奖励规则</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">已奖人数</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分组状态</th>
                  <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">操作</th>
              </tr>
              <tfoot></tfoot>
              <th></th>
              <tbody>
              <?php
              if(!empty($res)){
              foreach ( $res as $item2 ):?>
                <tr>
                  <td><?=$item2['reward_id']?></td>
                  <td><?=$item2['reward_name']?></td>
                  <td><?=$item2['add_time']?></td>
                  <td><?=$item2['start_time']?></td>
                  <td><?=$item2['end_time']?></td>
                  <td><?=$item2['group_name']?></td>
                  <td><?=$item2['source']==1?'订房':'商城'?></td>
                  <td><?=$item2['reward']?></td>
                    <td><?=$item2['reward_count']?></td>
                    <td><?=$item2['status']==1?'启用':'不启用'?></td>
                  <td><?='<a href="'.site_url('distribute/distribute_group/reward_edit?ids=').$item2['reward_id'].'">编辑</a>'?>
                    <?='<a href="'.site_url('distribute/distribute_group/reward_check?ids=').$item2['reward_id'].'">查看</a>'?></td>
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
    $(".datetime").datepicker({format: 'yyyy-mm-dd',language: "ZH-CN"});

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
