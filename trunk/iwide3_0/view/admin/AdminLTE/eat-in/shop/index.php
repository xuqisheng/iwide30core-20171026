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
    <style>
        @font-face {
            font-family: 'iconfont';
            src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot');
            src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.woff') format('woff'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.ttf') format('truetype'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.svg#iconfont') format('svg');
        }
        .iconfont{
            font-family:"iconfont" !important;
            font-size:16px;font-style:normal;
            -webkit-font-smoothing: antialiased;
            -webkit-text-stroke-width: 0.2px;
            -moz-osx-font-smoothing: grayscale;
        }
        .over_x{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;-webkit-overflow-scrolling:touch;overflow-scrolling:touch;}
        .clearfix:after{content:"" ;display:block;height:0;clear:both;visibility:hidden;}
        .bg_fff{background:#fff;}
        .color_fff{color:#fff;}
        .bg_ff0000{background:#ff0000;}
        .bg_f8f9fb{background:#f8f9fb;}
        .bg_ff9900{background:#ff9900;}
        .bg_f8f9fb{background:#f8f9fb;}
        .bg_fe6464{background:#fe6464;}
        .bg_eee{background:#EEEEEE}
        .color_72afd2{color:#72afd2;}
        .color_ff9900{color:#ff9900;}
        .color_F99E12{color:#F99E12;}
        a{color:#92a0ae;}

        .border_1{border:1px solid #d7e0f1;}
        .f_r{float:right;}
        .p_0_20{padding:0 20px;}
        .w_90{width:90px;}
        .w_200{width:200px;}
        .p_r_30{padding-right:30px;}
        .m_t_10{margin-top:10px;}
        .p_0_30_0_10{padding:0 30px 0 10px;}
        .b_b_1{border-bottom:1px solid #d7e0f1;}
        .b_t_1{border-top:1px solid #d7e0f1;}
        .p_t_10{padding-top:10px;}
        .p_b_10{padding-bottom:10px;}

        .contents{padding:10px 0px 20px 20px;}
        .contents_list{display:table;width:100%;border-bottom:1px solid #d7e0f1;margin-bottom:10px;}
        .head_cont{padding:20px 0 20px 10px;}
        .head_cont >div{margin-bottom:10px;cursor:pointer;}
        .head_cont >div:last-child{margin-bottom:0px;}
        .j_head >div{display:inline-block;}
        .j_head >div:nth-of-type(1){width:307px;}
        .j_head >div:nth-of-type(2){width:526px;}
        .j_head >div:nth-of-type(3){width:255px;}
        .j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
        .head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
        .h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
        .h_btn_list> div:last-child{margin-right:0px;}
        .classification{height:30px;line-height:30px;border-top:1px solid #d7e0f1;border-right:1px solid #d7e0f1;border-left:1px solid #d7e0f1;width:300px;}
        .classification >div{text-align:center;height:30px;border-right:1px solid #d7e0f1;}
        .classification >div:last-child{border-right:none;}
        .classification .add_active{background:#ecf0f5;border-bottom:1px solid #ecf0f5;}
        .fomr_term{height:30px;line-height:30px;}
        .classification >div,.all_open_order{cursor:pointer;}
        .all_open_order{margin-right:10px;margin-top:5px;}
        .template >div{text-align:center;}
        .template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
        .template_span{display:inline-block;margin-top:2px;}
        .template_btn{padding:1px 8px;border-radius:3px;}
        .form_con,.form_title{height:30px;line-height:30px;}
        .form_con >td,.form_title >th{text-align:center;font-weight:normal;}
        .form_thead >tr,.containers >tr{padding:8px 0;}
        .form_title >th:nth-of-type(2),.form_con >td:nth-of-type(2){flex:1.5;}
        .form_title >th:nth-of-type(7),.form_con >td:nth-of-type(7){flex:2.9;}
        .form_title >th:nth-of-type(6),.form_con >td:nth-of-type(6){flex:2.9;}
        .containers >tr:nth-child(even){background:#F8F8F8 !important;}
        .containers >tr:nth-child(odd){background:#fff !important;}
        .form_con >td{padding-right:20px !important;}
        .box-body{padding:0px;overflow:hidden;}
        #coupons_table_length{display:none;}


        .drow_list{display:none;position:absolute;width:100%;top:100%;left:0;background:#fff;border:1px solid #e4e4e4;padding:0;overflow:auto;z-index:999}
        .drow_list li{height:35px;padding-left:15px;line-height:35px;list-style:none; cursor:pointer}
        .drow_list li:hover{background:#f1f1f1}
        .drow_list li.cur{background:#ff9900;color:#fff}
        #drowdown:hover .drow_list{display:block}
    </style>
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

<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">店铺列表</div>
            <div class="contents">
                <div class="hottel_name ">
                        <?php echo $this->session->show_put_msg(); ?>
                    <div class="input_txt">
                        <form id="" action="<?php echo site_url('eat-in/shop/index');?>" class="form-inline" method="get">
                        关键词：<input type="text" name="wd" class="form-control input-sm" placeholder="店铺名" aria-controls="data-grid" value="<?php echo empty($posts['wd']) ? '' : $posts['wd']?>">
                        <input type="submit" class="btn bg_ff9900 color_fff" value="检索"/>&nbsp;&nbsp;&nbsp;
                         <a href="<?php echo site_url('eat-in/shop/add')?>" class="btn btn-sm bg-green">新增店铺</a>
                        </form>
                    </div>
                </div>

                <div class="box-body">
                    <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
                        <thead class="bg_f8f9fb form_thead">
                        <tr class="bg_f8f9fb form_title">
                                <th>店铺编号</th>
                                <th>店铺名称</th>
                                <th>所属酒店</th>
                                <th>售卖方式</th>
                                <!--<th>商品数量</th>-->
                                <th>优惠方式</th>
                                <th>操作</th>
                            </tr>
                            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
                           <?php if(!empty($res)){
                               foreach($res as $k=>$v){
                           ?>
                             <tr class=" form_con">
                                <td><?php echo $v['shop_id']?></td>
                                <td><?php echo $v['shop_name']?></td>
                                <td><?php echo isset($hotels[$v['hotel_id']])?$hotels[$v['hotel_id']]:'--'?></td>
                                <td><?php echo isset($sale_type[$v['sale_type']])?$sale_type[$v['sale_type']]:'--'?></td>
                                <!--<td><?php /*echo $v['goods_num']*/?></td>-->
                                <td><?php echo isset($discount_type[$v['discount_type']])?$discount_type[$v['discount_type']]:'--'?></td>
                                <td><a class="color_72afd2" href="<?php echo site_url('eat-in/shop/edit?ids='.$v['shop_id'])?>">编辑</a>
                                    <?php if($v['status'] == 1){?>
                                    <a class="color_72afd2" onclick="change_staus('<?php echo $v['shop_id']?>',0)" >停止</a>
                                    <?php }else{?>
                                       <a class="color_72afd2" onclick="change_staus('<?php echo $v['shop_id']?>',1)">运行</a>
                                    <?php }?>
                                 </td>
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
                    </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.content -->
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
    function change_staus(id,status){
        if(!confirm("确定更改")){
            return false;
        }
        $.post('<?php echo site_url('eat-in/shop/change_status')?>',{
            'shop_id':id,
            'status':status,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(res){
            if(res.errcode == 0){
                alert(res.msg);
                location.reload();
            }else{
                alert(res.msg);
            }
        },'json');
    }
</script>
</body>
</html>
