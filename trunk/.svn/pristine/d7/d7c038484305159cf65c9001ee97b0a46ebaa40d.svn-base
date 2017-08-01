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
            <h1>场景分组列表
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


                                    <div class="form-group">
                                        <?php echo form_open('okpay/typesgroup/add/','class="form-inline"')?>
                                        <div class="modal fade" id="setModal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">新增场景分组</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group form-control">
                                                            <label >分组名称:</label>

                                                       </div>
                                                        <input class="form-control" type="text" id="new_group" name="groupname" />
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-default"  value="新增"/>
                                                        <!--  <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>-->
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->
                                        </form>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm bg-green" id="grid-btn-set" data-toggle="modal" data-target="#setModal" ><i class="fa"></i>&nbsp;新增分组</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>



                        <table id="data-grid" class="table table-bordered table-striped table-condensed">
                            <thead>
                            <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
                             -->
                            <tr role="row">
                             <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分组id</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分组名称</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">使用酒店数</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">关联场景数</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">成交订单数</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">成交总额</th>
                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">渠道占比</th>

                                <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">操作</th>
                            </tr>
                            <tfoot></tfoot>
                            <th></th>
                            <tbody>
                           <?php if(!empty($res)){
                               foreach($res as $k=>$v){
                           ?>
                            <tr>
                                <td><?php echo $v['id']?></td>
                                <td><?php echo $v['name']?></td>
                                <td><?php echo $v['hotel_count']?></td>
                                <td><?php echo $v['type_count']?></td>
                                <td><?php echo $v['order_count']?></td>
                                <td><?php echo $v['trade_money']?></td>
                                <td><?php echo $v['rate']?></td>
                                <td><a class="btn btn-default bg-green" href="<?php echo site_url('okpay/typesgroup/detail?id='.$v['id'])?>">查看</a></td>
                            </tr>
                            <?php }}?>
                        </table>

                        <!--<div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共<?/*=$total*/?>条</div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                    <ul class="pagination"><?php /*echo $pagination*/?></ul>
                                </div>
                            </div>
                        </div>--><!-- /.box-body -->
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



        })});

   // $('#btn_con').
    $(document).ready(function() {

    });
</script>
</body>
</html>
