<!-- DataTables -->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate12.css">

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
</head>
<style>
    .weborder {
        background: #FFFFFF !important;
        display: none;
    }

    .morder {
        background: #FAFAFA !important;
    }

    .a_like {
        cursor: pointer;
        color: #72afd2;
    }

    .page {
        text-align: right;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 2em;
    }

    .page a {
        padding: 10px;
    }

    .current {
        color: #000000;
    }
</style>
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
    <div style="color:#92a0ae;">
        <div class="over_x">
            <div class="content-wrapper" style="min-width:1130px;" >
                <div class="banner bg_fff p_0_20">商品管理</div>
                <div class="contents">
                    <div class="hottel_name ">

                        <div class="input_txt">
                            <form action="<?php echo site_url('take-away/goods/index');?>" method="get">
                                <select   name="sale_status" class="w_450">
                                    <option value="1" <?php echo isset($filter['sale_status'])&&$filter['sale_status']==1?'selected="selected"':''?>>在售中</option>
                                    <option value="2" <?php echo isset($filter['sale_status'])&&$filter['sale_status']==2?'selected="selected"':''?>>已售罄</option>

                                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                            <select id="shop" onchange="change_group($(this).val())" name="shop_id" class="w_450">
                                <option value="">所有店铺</option>
                                <?php if(isset($shops)&&!empty($shops)){
                                    foreach($shops as $k=>$v){
                                    ?>
                                <option value="<?php echo $v['shop_id']?>" <?php echo $v['shop_id']==$filter['shop_id']?'selected="selected"':''?>><?php echo $v['shop_name']?></option>
                                <?php }}?>
                            </select>&nbsp;&nbsp;&nbsp;&nbsp;
                            <select id="group"  name="group_id" class="w_450">
                                <option value="">所有分组</option>
                                <?php if(isset($group)&&!empty($group)){
                                    foreach($group as $gk=>$gv){
                                        ?>
                                        <option value="<?php echo $gv['group_id']?>" <?php echo $gv['group_id']==$filter['group_id']?'selected="selected"':''?>><?php echo $gv['group_name']?></option>
                                    <?php }}?>
                            </select>&nbsp;&nbsp;&nbsp;&nbsp;
                            关键词：<input type="text" name="wd" value="<?php echo isset($filter['wd'])?$filter['wd']:''?>"/>
                            <input type="submit" class="bg_ff9900 color_fff" value="检索"/>&nbsp;&nbsp;&nbsp;
                             <a href="<?php echo site_url('take-away/goods/add')?>">新增</a>
                            </form>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
                            <thead class="bg_f8f9fb form_thead">
                                <tr class="bg_f8f9fb form_title">
                                    <th>商品图片</th>
                                    <th>商品名称</th>
                                    <th>商品分组</th>
                                    <th>价格</th>
                                    <th>所属店铺</th>
                                    <th>库存</th>
                                    <th>总销量</th>
                                    <th>创建时间</th>
                                    <th>展位推荐</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
                            <?php if(!empty($res)){?>
                                <?php foreach ($res as $r){?>
                                    <tr class=" form_con">
                                        <td><img width="80px" src="<?php echo !empty($r['goods_img'])?json_decode($r['goods_img'],true)[0]:'';?>"></td>
                                        <td><?php echo $r['goods_name'];?></td>
                                        <td><?php echo $r['group_name'];?></td>
                                        <td><?php echo $r['shop_price'] . (!empty($r['spec_list'])?' 起':'');?></td>
                                        <td><?php echo isset($shops[$r['shop_id']]['shop_name'])?$shops[$r['shop_id']]['shop_name']:'';?></td>
                                        <td><?php echo $r['stock'];?></td>
                                        <td><?php echo $r['sale_num'];?></td>
                                        <td><?php echo $r['add_time'];?></td>
                                        <td><?php echo $r['is_recommend']==1?'是':'否';?></td>
                                        <td><?php echo $r['group_sort_order'].'-'.$r['sort_order']?></td>
                                        <td><a class="color_72afd2" href="/index.php/take-away/goods/edit?ids=<?php echo $r['goods_id'];?>">编辑</a>

                                            <?php
                                            if($r['sale_now']==3)//不开售
                                            {
                                                if ($r['sale_status']==2)
                                                {
                                                    echo '开售';
                                                }
                                                else
                                                {
                                                    echo '售罄';
                                                }
                                                ?>
                                                <?php
                                            }
                                            else
                                            {
                                                if ($r['sale_status'] == 2) {
                                                    ?>

                                                    <a class="color_72afd2" onclick="sale_status('<?php echo $r['shop_id']?>','<?php echo $r['goods_id']?>',1)">开售</a>

                                                    <?php
                                                }else{
                                                    ?>
                                                    <a class="color_72afd2"
                                                       onclick="sale_status('<?php echo $r['shop_id'] ?>','<?php echo $r['goods_id'] ?>',2)">售罄</a>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                <?php }?>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
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
        function sale_status(shop_id,goods_id,status){
            if(!confirm("确定更改")){
                return false;
            }
            if(shop_id != '' && goods_id != ''){
                $.post('<?php echo site_url('take-away/goods/sale_status');?>',{
                    'shop_id':shop_id,
                    'goods_id':goods_id,
                    'status':status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(data){
                    if(data.errcode == 0){

                        alert(data.msg);
                        location.reload();
                    }else{
                        alert(data.msg);
                    }
                },'json');
            }
        }

        //根据店铺改变分组信息
        function change_group(shop_id){
            if(shop_id != '' ){
                $.post('<?php echo site_url('take-away/goods_group/ajax_get_group_info');?>',{
                    'shop_id':shop_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(res){
                    if(res.errcode == 0){
                        var html = '';
                        $('#group').html('');
                        html += '<option value="">所有分组</option>';
                        for(var i in res.data){
                            html += '<option value="'+res.data[i].group_id+'">'+res.data[i].group_name+'</option>';
                        }
                        $('#group').append(html);
                    }else{
                        alert(res.msg);
                    }
                },'json');
            }
        }
        $(function(){

        });

    </script>
</body>
</html>
