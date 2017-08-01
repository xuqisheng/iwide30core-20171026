<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
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

<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
                <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
       
        <div class="contents_list" style="font-size:13px;">
          <a class="f_r all_open_order color_72afd2" href="/index.php/hotel/prices/code_edit">新增代码</a>
          <div class="classification display_flex bg_fff">
            <div value="有效" class="add_active">有效代码</div>
            <div value="无效">无效代码</div>
            <div value="效">所有代码</div>
          </div>
        </div>
        <div class="box-body">
          <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
            <thead class="bg_f8f9fb form_thead">
              <tr class="bg_f8f9fb form_title">
                <th>代码名称</th>
                <th>代码描述</th>
                <th>代码类型</th>
                <th>pms代码</th>
                <th>关联价格代码</th>
                <th>排序</th>
                <th>状态</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
              <?php if(!empty($list)){ foreach($list as $lt){ ?>
                <tr class=" form_con">
                  <td><?php echo $lt['price_name'];?></td>
                  <td><?php echo $lt['des'];?></td>
                  <td><?php echo $lt['type'];?></td>
                  <td><?php echo $lt['external_code'];?></td>
                  <td><?php echo $lt['related_name'];?></td>
                  <td><?php echo $lt['sort'];?></td>
                  <td><?php echo $lt['status'];?></td>
                  <td><a class="color_72afd2" href="/index.php/hotel/prices/code_edit?pcode=<?php echo $lt['price_code'];?>">编辑</a></td>
                </tr>
              <?php }}?>
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

<script>
$(function(){
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
      },
      "searching": true
  });
  var table = $('#coupons_table').DataTable();
  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
    table
          .columns( 6 )
          .search( $(this).attr('value') )
          .draw();
  })
  table
        .columns( 6 )
        .search( $('.add_active').attr('value') )
        .draw();

})

</script>
</body>
</html>
