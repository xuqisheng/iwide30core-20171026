<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<style>
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
<style>
</style>

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
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
               支付礼包规则列表
            </div>
            <div class="contents">
        <div class="head_cont contents_list bg_fff">
            <form id="form" action="<?php echo site_url('okpay/package/grid');?>" class="form-inline" method="get">
          <div class="j_head">
            <div>
              <span>所属酒店</span>
              <span class="input-group w_200" style="position:relative;display:inline-flex;" id="drowdown">
                <input placeholder="选择或输入关键字" type="text" style="color:#92a0ae;" class="form-control w_200 moba"
                       id="search_hotel" value="<?php echo $hotels[$posts['hotel_id']]?>" >
                  <input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $posts['hotel_id'];?>"/>
                <ul class="drow_list">
                    <li value="0">全部</li>
                    <?php
                    foreach ($hotels as $key=>$val):?>
                    <li value="<?php echo $key?>"><?php echo $val?></li>
                    <?php endforeach;?>
                </ul>
              </span>
            </div>
            <div>
              <span>所属场景</span>
              <span>
                <select class="w_90" name="type_id">
                    <option value="-1"<?php if(empty($posts['type_id'])):echo ' selected';endif;?>>-- 全部 --</option>
                    <?php
                    foreach ($types as $key=>$val):?><option value="<?php echo $key?>"<?php if(isset($posts['type_id']) && $key == $posts['type_id']):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?></select>
                </select>
              </span>
            </div>
            <div>
              <span>赠送时间</span>
              <span class="t_time"><input name="start_time" type="text" id="datepicker" class="moba" value="<?php echo empty($posts['start_time']) ? '' : $posts['start_time']?>"></span>
                <font>至</font>
                <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="moba" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>"></span>
            </div>

          </div>
        <div class="j_head">
            <div>
                <span>礼包状态</span>
                <span>
                    <select class="w_90" name="status">
                        <option value="-1">-- 全部 --</option>
                        <option value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' selected ':''?>>启用</option>
                        <option value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' selected ':''?>>不启用</option>
                    </select>
                </span>
            </div>
        </div>
          <div  class="h_btn_list" style="">
            <div id="dosubmit" style="cursor: pointer;" class="actives">筛选</div>
            <div><a href="<?php echo site_url('/okpay/package/add')?>">新增规则</a></div>
          </div>
            </form>
        </div>
        <div class="box-body">
          <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                <th>礼包名称</th>
                <th>赠送份数</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>所属酒店</th>
                <th>所属场景</th>
                <th>礼包状态</th>
                <th>创建时间</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
            <?php if(!empty($res)){
            foreach($res as $k=>$v){
            ?>
              <tr class="form_con">
                <td><?php echo $v['package_name']?></td>
                <td><?php echo $v['count']?></td>
                <td>
                    <span><?php echo isset($v['start_time'])?$v['start_time']:'--'?></span>
                </td>
                <td>
                    <span><?php echo isset($v['end_time'])?$v['end_time']:'--'?></span>
                </td>
                <td><?php echo isset($hotels[$v['hotel_id']])?$hotels[$v['hotel_id']]:'--'?></td>
                <td class="order_number"><?php echo isset($types[$v['type_id']])?$types[$v['type_id']]:'--'?></td>
                <td><?php echo $v['status']==1?'启用':'未启用'?></td>
                <td>
                    <span><?php echo isset($v['create_time'])?$v['create_time']:'--'?></span>
                </td>
                <td>
                    <a class="color_F99E12" href="<?php echo site_url('okpay/package/edit?ids='.$v['id'])?>">编辑</a></td>
              </tr>
            <?php }}?>


            </tbody>
          </table>

            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?=$total?>条</div>
                </div>
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                        <ul class="pagination"><?php echo $pagehtml?></ul>
                    </div>
                </div>
            </div><!-- /.box-body -->
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
  $('.drow_list li').click(function(){
        $('#search_hotel').val($(this).text());
        $('#hotel_id').val($(this).attr('value'));
        $(this).addClass('cur').siblings().removeClass('cur');
    });
    /*
  $('#coupons_table').DataTable({
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
        //"info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
        "infoEmpty": "",
        "infoFiltered": "(从 _MAX_ 条记录中过滤)",
        "paginate": {
          "sNext": "下一页",
          "sPrevious": "上一页",
        }
      }
  });
  */
  // var This;
  // $('.adjustment').click(function(){
  //   This=$(this);
  //   if(This.html()=='退款'){
  //       $('.fixed_box').show();
  //       var str=This.parents('tr').find('.order_number').html();
  //       $('.f_b_con').html('退款确认:订单'+str+'确认退款后自动将订单实际支付退还给用户且不可撤回！');
  //   }
    
  // })

  // $('.confirms').click(function(){
  //   $('.fixed_box').hide();
  //   This.html('已退款');
  //   This.removeClass('color_F99E12').addClass('color_9b9b9b');
  // })
  // $('.cancel').click(function(){
  //   $('.fixed_box').hide();
  // })


  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })
  $('.news').click(function(){
      $('.j_toshow').animate({"right":"0px"},800);
  });
  $('.close_btn').click(function(){
      $('.j_toshow').animate({"right":"-330px"},800);
  });
  var tips=$('#tips');
  $('.btn_o').click(function(){
    //console.log( decodeURIComponent($(".form").serialize(),true));
    start=$('.t_time').find('input[name="start_t"]').val().replace(/-/g,'');
    end=$('.t_time').find('input[name="end_t"]').val().replace(/-/g,'');
    if(start!=''&&start!=undefined){
      if(isNaN(start)){
        tips.html('开始日期错误');
        setout(tips);
        return false;
      }
      if(end!=''&&end!=undefined){
        if(isNaN(end)||end<start){
          tips.html('结束日期错误或大于开始日期');
          setout(tips);
          return false;
        }
      }
    }
  })
})
<!--杰 2016/8/30-->
function setout(obj){
  setTimeout(function(){
    obj.fadeOut();  
  },2000) 
}


    $('#dosubmit').click(function(){
        $('#form').submit();
    });
</script>
</body>
</html>
