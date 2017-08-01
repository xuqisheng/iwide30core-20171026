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
        <div class="content-wrapper" style="min-width:1340px;" >
            <div class="banner bg_fff p_0_20">
               分销订单统计
            </div>
            <div class="contents">
        <?php if(!empty($saler_date_arr)){?>
          <div class="head_cont contents_list bg_fff">
            <div class="j_head">
             
              <div class="w_307" >
                  <span>可选日期</span>
                  <select name="saler_date" style="width:100px;">
                  <?php foreach ($saler_date_arr as $value) {?>
                    <option value="<?php echo $value['saler_date'];?>"><?php echo $value['saler_date'];?></option>
                  <?php }?>
                  </select>
              </div>
            </div>
            <div  class="h_btn_list" style="">
              <div class="actives search">筛选</div>
              <!-- <div id="export">导出</div> -->
            </div>
          </div>
        <?php }?>
        <div class="box-body">
          <div>
            
          </div>
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

<script>
$(function(){
  $('.search').on('click',function() {
    var url = "<?php echo site_url('hotel/hotel_report/get_saler_order_statistics');?>";
    var html ='<table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;"><thead class="bg_f8f9fb form_thead"><tr class="bg_f8f9fb form_title"><th>酒店名</th><th>总订单数</th><th>分销订单数</th><th>分销订单总额</th><th>分销订单占比</th><th>分销粉丝用户数</th><th>购买2次以上用户数</th></tr></thead><tbody class="containers dataTables_wrapper form-inline dt-bootstrap">';
    $.ajax({
        url: url,
        method: 'get',
        data: {
          saler_date: $("select[name='saler_date']").val()
        },
        success: function(datas){
          res = $.parseJSON(datas);
          if(res.status==0){
            if(!res.data){
              alert('暂无数据');
            }else{
              $.each(res.data, function(i, obj) {
                  html += '<tr class="form_con"><td>'+obj['name']+'</td><td>'+obj['all_order_num']+'</td><td>'+obj['saler_order_num']+'</td><td>'+obj['all_saler_total']+'</td><td>'+Math.round(obj['saler_order_num']*10000/obj['all_order_num']) / 100.00.toFixed(2)+'%'+'</td><td>'+obj['fans_num']+'</td><td>'+obj['fans_num_twice']+'</td></tr>';
              });
              html += '</tbody></table>';
              $('.box-body>div').html(html);
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
                    "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
                    "infoEmpty": "",
                    "infoFiltered": "(从 _MAX_ 条记录中过滤)",
                    "paginate": {
                      "sNext": "下一页",
                      "sPrevious": "上一页",
                    }
                  }
              });
            }
          }else{
            alert(res.msg);
          }
          return;
        }
    })
  })
})

</script>
</body>
</html>
