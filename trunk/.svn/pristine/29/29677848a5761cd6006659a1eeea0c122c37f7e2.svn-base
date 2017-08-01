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

#pages
{
    height: 40px;
    text-align: right;
    font-family: \u5b8b\u4f53,Arial;
    display: inline-block;
    padding-left: 0;
    margin: 20px 0;
    border-radius: 4px;
    font-size: 14px;
}

#pages span {
    float: left;
    display: inline;
    margin: 1px 4px;
    display: block;
    border-radius: 3px;

}

#pages span.span {
    color: #666;
    padding: 6px;
    background-color: #FFFFFF;
}
#pages span.nolink {
    color: #666;
    border: 1px solid #e3e3e3;
    padding: 6px;
    background-color: #FFFFFF;
}
#pages span.current {
    color: #fff;
    background: #ffac59;
    border: 1px solid #e6e6e6;
    padding: 11px 13px;
    font-size: 14px;
    display: inline;
}

#pages a {
    float: left;
    display: inline;
    padding: 11px 13px;
    border: 1px solid #e6e6e6;
    border-right: none;
    background: #f6f6f6;
    color: #666666;
    font-family: \u5b8b\u4f53,Arial;
    font-size: 14px;
    cursor: pointer;

}
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
    <!-- Main content -->
    <section class="content">
    	<form class="bd bg_fff pad10">
        	<div class="marbtm">
            	<span>分组名称</span>
                <span class="input"><input name="group_name" value="<?php echo $param['group_name'];?>"/></span>
            	<span style="margin-left:25px;">创建时间</span>
                <span class="input"><input class="datetime" name="start_time" value="<?php echo $param['start_time'];?>"/></span>
                <span class="input"><input class="datetime" name="end_time" value="<?php echo $param['end_time'];?>"/></span>
            </div>
        	<div class="marbtm">
            	<span>核定来源</span>
                <span class="input">
                    <select name="source" id="source">
                        <option value="">下拉选择</option>
                        <option value="1">订房</option>
                        <option value="2">商城</option>
                    </select>
                </span>
            	<span style="margin-left:25px;">分组状态</span>
                <span class="input">
                    <select name="type" id="type">
                        <option value="">下拉选择</option>
                        <option value="1">手动</option>
                        <option value="2">自动</option>
                    </select>
                </span>
            </div>
        	<button class="bg_main btn maright" style="color:#fff">筛选</button>
            <!-- <a class="bg_main btn maright" href="<?php echo site_url('distribute/distribute_group/group_detail?type=1')?>">手动分组管理</a>-->
             <a class="btn-default btn maright" href="<?php echo site_url('distribute/distribute_group/group_add/?type=1')?>">新增手动分组</a>
            <!-- <a class="bg_main btn maright" href="<?php echo site_url('distribute/distribute_group/group_detail?type=2')?>">自动分组管理</a>-->
             <a class="btn-default btn maright" href="<?php echo site_url('distribute/distribute_group/group_add/?type=2')?>">新增自动分组</a>
        </form>
            <?php echo $this->session->show_put_msg(); ?>
            <!--
              <div class="box-header">
                <h3 class="box-title">Data Table With Full Features</h3>
              </div><!-- /.box-header -->
            <!--<div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <?php /*echo form_open('distribute/distri_report/exnew_fas/','class="form-inline"')*/?>

                  <div class="form-group">
                    <label>关注时间 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="begin_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php /*echo empty($posts['begin_time']) ? '' : $posts['begin_time']*/?>">
                    <label>至 </label>
                    <input class="form_datetime form-control input-sm" data-date-format="yyyymmdd" type="text" name="end_time" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php /*echo empty($posts['end_time']) ? '' : $posts['end_time']*/?>">
                  </div>
                  <div class="form-group">
                    <label>粉丝编号 </label><input type="text" name="fans_id" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php /*echo empty($posts['fans_id']) ? '' : $posts['fans_id']*/?>">
                  </div>
                  <div class="form-group">
                    <label>分销员 </label><input type="text" name="saler_name" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php /*echo empty($posts['saler_name']) ? '' : $posts['saler_name']*/?>">
                  </div>
                  <div class="form-group">
                    <label>分销号</label> <input type="datetime" name="saler_no" class="form-control input-sm" placeholder="" aria-controls="data-grid" value="<?php /*echo empty($posts['saler_no']) ? '' : $posts['saler_no']*/?>">
                  </div>

                  <div class="btn-group">
                    <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search"><i class="fa fa-search"></i>&nbsp;检索</button>
                  </div>
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;设置</button>
                  </div>
                </div>
                </form>
              </div>
            </div>-->
            <table id="data-grid" class="table table-striped table-condensed martop">
              <thead>
              <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
               -->
              <tr role="row">
                <?php $index = 0;
                $fields = array();
                foreach ($nav_confs as $key=>$item){
                    ?>   <th width="10%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label="<?php echo $item['name'];?>: activate to sort column ascending"><?php echo $item['name'];?></th>
                    <?php $index ++;
                    $fields[] = $key;

                }?>
              </tr>
              </thead>
                <tbody>
                <?php
                $status_arr = array('1'=>'手动','2'=>'自动');
                foreach ( $data as $item2 ):?>
                    <tr>
                        <td><?=$item2['group_id']?></td>
                        <td><?=$item2['group_name']?></td>
                        <td><?=date('Y-m-d H:i',$item2['create_time'])?></td>
                        <td><?=date('Y-m-d',$item2['start_time'])?></td>
                        <td><?=date('Y-m-d',$item2['end_time'])?></td>
                        <td><?=$item2['type']==1?'手动':'自动'?></td>
                        <?php if($type == 2){?>
                            <td><?=$source_arr[$item2['source']]?></td>
                        <?php }?>
                        <td><?=$item2['member_count']?></td>
                        <td><?=$status_arr[$item2['type']]?></td>

                        <td><?='<a href="'.site_url('distribute/distribute_group/group_edit?ids=').$item2['group_id'].'">编辑</a>'?>
                            <?php if($type == 2){?>||
                            <?='<a href="'.site_url('distribute/distribute_group/group_check?ids=').$item2['group_id'].'">查看</a>'?></td>
                    <?php }?>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>

            <div class="row">
              <div class="col-sm-5">
              </div>
              <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                  <ul class="pagination"><?php echo $pagehtml;?></ul>
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
    $('#type').val('<?php echo $param['type'];?>');
    $('#source').val('<?php echo $param['source'];?>');


$(".datetime").datepicker({format: 'yyyy-mm-dd',language: "ZH-CN"});
function delete_com(group_id){
  var a=confirm("确定要删除吗？");
  if(a && (typeof group_id != 'undefined')){
    $.get('<?php echo site_url("distribute/distribute_group/ajax_delete")?>' , {ids:group_id},function(res){

      if(res == 'success'){
        window.location.reload();
      }else{
        alert('失败');
      }
    });
  }
}
  <?php

  ?>
  var buttons = $('<div class="btn-group"></div>');

  var grid_sort= [[ , "" ]];

  <?php /* 有更多的按钮，URL在此定义，与上面button顺序匹配 */ ?>
  var url_extra= [
//'http://iwide.cn/',
  ];
  var baseStr = "";
  $(".form_datetime").datepicker({format: 'yyyymmdd'});
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
        alert(res);
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
