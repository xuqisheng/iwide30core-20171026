<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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
<style>
</style>

<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1090px;" >
            <div class="banner bg_fff p_0_20">
               <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
        <div class="head_cont">
          <div  class="h_btn_list" style="text-align:right;padding-right:8px;">
            <div><a href="<?php echo site_url('hotel/thematic/edit');?>">新增活动</a></div>
          </div>
        </div>
        <div class="box-body">
          <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                <th>活动ID</th>
                <th>活动类型</th>
                <th>活动名称</th>
                <th>开始/结束时间</th>
                <th>链接</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
            <?php foreach ($list as  $row) {?>
              <tr class="form_con">
                <td><?php echo $row['id'];?></td>
                <td><?php if($row['pre_days']>0) echo '提前预定'.$row['pre_days'].'天';?><?php if($row['min_days']>0 && $row['pre_days']>0) echo ',';?><?php if($row['min_days']>0) echo '连住'.$row['min_days'].'天';?></td>
                <td><?php echo $row['act_name'];?></td>
                <td>
                    <span><?php echo $row['start_time'].' - '. $row['end_time'];?></span>
                </td>
                <td><?php echo EA_const_url::inst()->get_front_url($this->inter_id, 'hotel/hotel/thematic_index?id='.$this->inter_id.'&tc_id='.$row['id']);?></td>
                <td><a class="color_F99E12" href="<?php echo site_url('hotel/thematic/edit?tid=').$row['id'];?>">修改</a></td>
              </tr>
            <?php }?>
            </tbody>
          </table>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
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
        $(this).addClass('cur').siblings().removeClass('cur');
    });
  $('#coupons_table').DataTable({
        "aLengthMenu": [8,50,100,200],
      "iDisplayLength": 20,
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
var orderid='';
function show_detail(obj){
  $('#status_detail').html('');
  $('#myModalLabel').html('单号：');
  var temp='';
  orderid='';
  $.get('<?php echo site_url('hotel/orders/order_status')?>',{
    oid:$(obj).attr('oid'),
    hotel:$(obj).attr('h')
  },function(data){
    orderid=data.order.orderid;
    if(data.after!=''){
      temp+='<select id="after_status">';
      $.each(data.after,function(i,n){
        if(i!=4)
          temp+='<option value="'+i+'">'+n+'</option>';   
      });
      temp+='</select>';
    }else{
      temp+=data.order.status_des;
      orderid='';
    }
    $('#status_detail').html(temp);
    $('#myModalLabel').append(data.order.orderid);
  },'json');
}
function change_status(){
  if(orderid){
    $.get('<?php echo site_url('hotel/orders/update_order_status');?>',{
      oid:orderid,
      status:$('#after_status').val()
    },function(data){
      if(data==1){
        alert('修改成功');
        location.reload();
      }else{
        alert('修改失败');
      }
    });
  }
  $('#myModal').modal('hide');
}
</script>
</body>
</html>
