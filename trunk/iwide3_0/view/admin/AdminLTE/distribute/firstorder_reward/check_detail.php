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
        <!-- Main content -->
<section class="content">
    <?php echo $this->session->show_put_msg(); ?>
    <!--
      <div class="box-header">
        <h3 class="box-title">Data Table With Full Features</h3>
      </div><!-- /.box-header -->
	<?php echo form_open(site_url('distribute/firstorder_reward/check').'?ids='.$ids,'class=""')?>
        <div class="marbtm">
            <span class="input">
            <select name="hotel_id">
                <option value="-1">全部酒店</option>
                <?php if(!empty($hotels)){
                    foreach($hotels as $k=>$v){
                ?>
                <option value="<?php echo $k?>" <?php echo isset($posts['hotel_id'])&&$posts['hotel_id']==$k?' selected ':''?>><?php echo $v?></option>
                <?php }}?>
            </select></span>
            <span style="margin-left:25px;">分销号</span>
            <span class="input"><input type="text" name="saler" aria-controls="data-grid" value="<?php echo empty($posts['saler']) ? '' : $posts['saler']?>"></span>
            <span style="margin-left:25px;">核定时间</span>
            <span class="input"><input data-date-format="yyyy-mm-dd" type="text" class="datetime" name="start_time" aria-controls="data-grid" value="<?php echo empty($posts['start_time']) ? '' : $posts['start_time']?>"></span>
            <span>-</span>
            <span class="input"><input data-date-format="yyyy-mm-dd" type="text" class="datetime" name="end_time" aria-controls="data-grid" value="<?php echo empty($posts['end_time']) ? '' : $posts['end_time']?>"></span>
            <button type="submit" class="btn btn-sm bg-green" id="grid-btn-search">检索</button>
            <button type="submit" class="btn btn-sm bg-orange" name="export" value="1">导出</button>
        </div>
    <?php echo form_close();?>


    <table id="data-grid" class="table martop table-striped table-condensed">
        <thead>
        <!-- 订单号	粉丝微信名	微信会员号	关注时间	所属分销员姓名	所属分销员分销号	所属酒店	所属酒店分组	粉丝绩效规则	分销员绩效	绩效发放时间	发放状态
         -->
        <tr role="row">
         <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">订单号</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">商品名称</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">订单酒店</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">实付金额</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">绩效金额</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">核定时间</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分销员</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">分销号</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">所属酒店</th>
            <th width="5%" class="sorting" tabindex="0" aria-controls="data-grid" rowspan="1" colspan="1" aria-label=": activate to sort column ascending">发放时间</th>

        </tr>
        </thead>
        <tbody>
       <?php if(!empty($res)){
           foreach($res as $k=>$v){
       ?>
        <tr>
            <td><?php echo isset($v['order_id'])?$v['order_id']:''?></td>
            <td><?php echo isset($v['product'])?$v['product']:''?></td>
            <td><?php echo isset($v['hotel_name'])?$v['hotel_name']:''?></td>
            <td><?php echo isset($v['order_amount'])?$v['order_amount']:''?></td>
            <td><?php echo isset($v['grade_total'])?$v['grade_total']:''?></td>
            <td><?php echo isset($v['grade_time'])?$v['grade_time']:''?></td>
            <td><?php echo isset($v['staff_name'])?$v['staff_name']:''?></td>
            <td><?php echo isset($v['saler'])?$v['saler']:''?></td>
            <td><?php echo isset($v['hotel_id'])?$v['hotel_id']:''?></td>
            <td><?php echo isset($v['send_time'])?$v['send_time']:''?></td>

        </tr>
        <?php }}?>
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
