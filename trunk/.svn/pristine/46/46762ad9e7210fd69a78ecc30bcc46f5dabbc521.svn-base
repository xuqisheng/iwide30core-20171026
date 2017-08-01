<!-- DataTables -->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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

    .fixed_box_remark{position:fixed;top:30%;left:48%;z-index:9999;border:1px solid #d7e0f1;border-radius:5px;padding:1% 2%;display:none;}
    .tile{font-size:15px;text-align:center;margin-bottom:4%;}
    .f_b_con{font-size:13px;margin-bottom:8%;width:245px;}
    .f_b_con span:first-child{display:inline-block;width:80px;text-align:right;margin-right:5px;}

    .pagination{margin-top:0px;margin-bottom:0px;}
    .btn_list_r span{margin-right:10px;}
    .btn_list_r span:last-child{margin-right:0px;}
    .f_b_con i{right:8px;top:1px;font-style:normal;}

    .f_con >div{display:inline-block;}

    .confirms_remark{cursor: pointer}
    .cancel_remark{cursor: pointer}

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
    .j_head >div:nth-of-type(1){width:307px;}
    .j_head >div:nth-of-type(2){width:526px;}
    .j_head >div:nth-of-type(3){width:255px;}
    .j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
    .table >div{text-align:center;}
    .table{display:table;margin-bottom:0px;}
    .table >div{display:table-cell;width:137px;vertical-align:middle;}
    .table >div:nth-of-type(1){width:350px;text-align:left;padding-left:10px;}
    .template_btn{padding:1px 8px;border-radius:3px;}
    .classifications{width:600px;}
    .classifications div{text-align:center;padding:6px 4px;width: 100px;}

    .temp_con >div >span{line-height:1.7;}
    .room{width:52px;display:inline-block;}
    .con_list > div:nth-child(odd){background:#fafcfb;}
    .con_list{display:none;}
    .add_active{border-bottom:3px solid #ff9900;}

    .colspans{margin-bottom:10px;}
    .colspans:last-child{margin-bottom:0}
    .cont_txt{word-wrap:break-word;width:260px;}
    .colspan{overflow:hidden; height:50px; margin-bottom:15px;}
    .colspan:last-child{margin-bottom:0}
    .blue{color:#2d87e2}
    .red{color:#e22e3b}
    .pages #Pagination .pagination .next{
        padding:0px;
    }

    .ref_table td{height: 28px;line-height: 28px;text-align: left;}
    .refund *{font-size: 14px;padding: 3px;}

    </style>

    <div style="color:#92a0ae;">
        <div class="over_x">
            <div class="content-wrapper" style="min-width:1130px; ">

                <div class="banner bg_fff p_0_20">
                    <?php echo '订单列表'; ?>
                </div>
                <div class="contents_list bg_fff" style="font-size:13px;margin-top:20px">
                    <div class="classifications display_flex">
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==1)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=1')?>">待付款</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==2)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=2')?>">待确认</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==3)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=3')?>">待配送</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==4)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=4')?>">配送中</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==5)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=5')?>">已完成</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==6)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index?order_status=6')?>">已取消</a></div>
                        <div <?php if(isset($filter['order_status'])&&$filter['order_status']==99)echo 'class="add_active"'?>><a href="<?php echo site_url('eat-in/orders/index')?>">所有状态</a></div>
                    </div>
                </div>
                <div style="padding: 10px;">
                    <form class="form" method='get' id="this_form" action='<?php echo site_url('eat-in/orders/index')?>'>
                        <input type="hidden" name="order_status" value="<?php echo isset($filter['order_status'])?$filter['order_status']:''?>"/>
                            <div class="j_head">
                                <div>
                                    <span>店铺</span>
                            <span>
                                <select  class="w_90" name="shop_id">
                                    <option value="-1">全部</option>
                                    <?php if(!empty($shops)){
                                        foreach($shops as $k=>$v){
                                    ?>
                                    <option <?php echo isset($filter['shop_id']) && $filter['shop_id'] == $k ? 'selected="yes"':'';?> value="<?php echo $k?>"><?php echo $v?></option>
                                    <?php }}?>
                                </select>
                            </span>
                                </div>
                                <div>
                                    <span>下单时间</span>

                                    <span class="t_time"><input name="start_time" type="text" id="datepicker"
                                                                class="datepicker moba form_datetime" value="<?php echo empty($filter['start_time']) ? '' : $filter['start_time']?>"></span>
                                    <font>至</font>
                                    <span class="t_time"><input name="end_time" type="text" id="datepicker2"
                                                                class="datepicker moba form_datetime" value="<?php echo empty($filter['end_time']) ? '' : $filter['end_time']?>"></span>
                                </div>
                                <div>
                                    <span>关键字</span>
                                    <span><input style="width: 170px;" type="text" name="wd" value="<?php echo empty($filter['wd']) ? '' : $filter['wd']?>" placeholder="订单号/收货人"/></span>
                                </div>
                                <input type="submit" class="bg_ff9900 color_fff" value="检索"/>
                                <input type="submit" name="export" class="bg_ff9900 color_fff" value="导出">
                            </div>

                    </form>
                    </div>
                    <div class="contents_list bg_fff" style="font-size:13px;">
                        <div class="bg_f8f9fb fomr_term table">
                            <div>商品&规格</div>
                            <div>商品单价&份数</div>
                            <div>订单店铺</div>
                            <div>用户信息</div>
                            <div>订单状态</div>
                            <div>达达状态</div>
                            <div>退款金额</div>
                            <div>应付金额</div>
                            <div>实付金额</div>
                        </div>
                    </div>
                    <?php if(!empty($res)){ foreach($res as $k => $list){?>
                        <div class="border_1 m_t_10 bg_fff">
                            <div class="bg_f8f9fb fomr_term p_0_30_0_10 b_b_1">
                                <div>
                                    <span>订单号：<?php echo $list['order_sn']?></span>
                                    <span><?php echo $list['add_time']?></span>
                                    <a style="float:right" class="blue" onclick="print_order('<?php echo $list['order_id']?>')">打印订单</a>
                                </div>
                            </div>
                            <?php if(!empty($list['order_goods'])) { ?>
                            <div class=" temp_con table p_t_10 p_b_10">
                            <div class="">
                                <?php foreach ($list['order_goods'] as $ok => $ov) { ?>
                                    <div class="clearfix colspans">
                                        <img class="template_img"
                                             src="<?php echo !empty($ov['goods_img']) ? json_decode($ov['goods_img'], true)[0] : ''; ?>">
                                        <div class="" style="float:left;">
                                            <span class="template_span cont_txt"><?php echo $ov['goods_name']; ?></span><br>
                                            <span><?php echo $ov['spec_name']; ?></span>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div>
                                <?php foreach ($list['order_goods'] as $ok => $ov) { ?>
                                    <div class="colspan" style="text-align: center">
                                        <div style="float: left;margin-right: 10px;">
                                            <span>¥<?php echo $ov['goods_price']; ?></span> <br>
                                            <span><?php echo $ov['goods_num'] ?>份</span>
                                            <?php
                                            if ($ov['refund_num'] > 0)
                                            {
                                            ?>
                                            <span style="color:#999">(已退<?php echo $ov['refund_num']; ?>份)<span>
                                             <?php
                                             }
                                             ?>
                                        </div>
                                        <?php
                                        if (($ov['goods_num'] > $ov['refund_num']) && ($list['pay_status'] == 1 && in_array($list['pay_way'],array(1,2))) && in_array(intval($list['order_status']), array(0, 5, 10))) {
                                            ?>
                                            <div style="float: left;width: 30px;text-align: center;">
                                                <a class="blue"
                                                   onclick='to_refund("<?php echo $list['order_id']; ?>","<?php echo $ov['item_id']; ?>","<?php echo $ov['goods_num'] - $ov['refund_num']; ?>","<?php echo $ov['goods_name']; ?>","<?php echo $ov['goods_price']; ?>")'
                                                   href="javascript:void(0)">退款</a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div style="clear: both"></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div>
                                <span><?php echo isset($shops[$ov['shop_id']]) ? $shops[$ov['shop_id']] : '' ?></span>
                            </div>
                            <div>
                                <?php if ($list['type'] == 3) { ?>
                                    <span><?php echo $list['consignee'] ?></span><br>
                                    <span><?php echo $list['phone'] ?></span><br>
                                <?php } ?>
                                <span><?php echo $list['address'] ?></span><br>
                                <span><?php echo !empty($list['note']) ? '用户备注：' . $list['note'] : '' ?></span><br>
                                <span
                                    id="shop_note"><?php echo !empty($list['shop_note']) ? '店铺备注：' . $list['shop_note'] : '' ?></span><br>

                                <?php if (!empty($list['remind_info'])) { ?>
                                    <span><?php echo '用户催单' . $list['remind_info']['remind_count'] . '次' ?></span><br>
                                    <span><?php echo $list['remind_info']['last_remind_time'] ?></span>
                                <?php } ?>
                                <a style="color: #3c8dbc;" href="javascript:void(0)"
                                   data='<?php echo $list['order_id']; ?>' content="<?php echo $list['shop_note']; ?>"
                                   class="remark">备注</a>
                            </div>
                            <div>
                                <?php if ($list['order_status'] == $orderModel::OS_UNCONFIRMED && $list['pay_status'] == $orderModel::IS_PAYMENT_NOT && $list['pay_way'] != 3) { ?>
                                    <span>待付款</span><br>
                                    <span class="red"
                                          onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</span>
                                <?php } elseif ($list['order_status'] == $orderModel::OS_UNCONFIRMED && $list['pay_status'] == $orderModel::IS_PAYMENT_YES && $list['pay_way'] != 3 || $list['pay_way'] == 3 && $list['order_status'] == $orderModel::OS_UNCONFIRMED) { ?>
                                    <span>待确认</span><br>
                                    <span class="blue"
                                          onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_CONFIRMED ?>')">接单</span> &nbsp;|&nbsp;
                                    <span class="red"
                                          onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</span>
                                <?php } elseif ($list['order_status'] == $orderModel::OS_CONFIRMED && $list['pay_status'] == $orderModel::IS_PAYMENT_YES && $list['pay_way'] != 3 || $list['pay_way'] == 3 && $list['order_status'] == $orderModel::OS_CONFIRMED) { ?>
                                    <span>准备中</span><br>
                                    <?php
                                    if ($list['shipping_type'] == 2 && $list['type'] == 3 && !in_array($list['dada_status'],array('0','5','7'),true))
                                    {
                                    ?>
                                        <span class="">配送</span> &nbsp;|&nbsp;
                                        <span class="">取消</span>

                                    <?php
                                    }else {
                                        ?>

                                        <span class="blue"
                                              onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_SHPPING ?>')">配送</span> &nbsp;|&nbsp;
                                        <span class="red"
                                              onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</span>
                                    <?php
                                    }
                                    ?>
                                <?php } elseif ($list['order_status'] == $orderModel::OS_SHPPING) { ?>
                                    <span>配送中</span><br>

                                <?php
                                if ($list['shipping_type'] == 2 && $list['type'] == 3 && !in_array($list['dada_status'],array('0','5','7'),true))
                                {
                                    ?>
                                    <span class="">确认送达</span>

                                    <?php
                                }else {
                                    ?>

                                    <span class="blue"
                                          onclick="change_status('<?php echo $list['order_id'] ?>' , '<?php echo $orderModel::OS_FINISH ?>')">确认送达</span>
                                    <?php
                                }
                                    ?>

                                <?php } elseif ($list['order_status'] == $orderModel::OS_FINISH) { ?>
                                    <span>已完成</span><br>
                                <?php } elseif ($list['order_status'] == $orderModel::OS_PER_CANCEL || $list['order_status'] == $orderModel::OS_HOL_CANCEL || $list['order_status'] == $orderModel::OS_SYS_CANCEL) { ?>
                                    <span>已取消</span><br>
                                <?php } ?>
                            </div>
                            <div>
                                <?php
                                    if ($list['type'] == 3 && $list['shipping_type'] == 1 && $list['order_status'] == $orderModel::OS_CONFIRMED)
                                    {
                                ?>
                                        <a class="blue send_dd" data="<?php echo $list['order_id'];?>" href="javascript:;">推送达达</a>
                                <?php
                                    }

                                // 达达配送状态
                                else if ($list['dada_status'] == 1) {
                                    ?>
                                    <span class="yellow">待接单</span> |
                                    <a class="blue cancel_dd" data="<?php echo $list['order_id'];?>" href="javascript:;">取消</a>
                                    <?php
                                } else if ($list['dada_status'] == 0) {
                                    echo '<span>--</span>';
                                } else if ($list['dada_status'] == 8) {
                                    echo '<span>已申请取消</span>';
                                } else if ($list['dada_status'] == 9) {
                                    echo '<span>指派单</span><br/>';
                                } else if ($list['dada_status'] == 2) {
                                    echo '<span class="green">待到店</span>';
                                } else if ($list['dada_status'] == 3) {
                                    echo '<span class="green">配送中</span>';
                                } else if ($list['dada_status'] == 4) {
                                    echo '<span class="green">已送达</span>';
                                } else if ($list['dada_status'] == 5) {
                                    ?>
                                    <span class="">已取消</span>

                                    <?php
                                    if ($list['order_status'] == 5)
                                    {
                                    ?>
                                        | <a class="blue resend_dd" data="<?php echo $list['order_id'];?>" href="javascript:;">重新发送</a>
                                    <?php
                                    }
                                    ?>

                                    <br/>
                                    <!--<span><?php echo $list['dada_cancel_reason'];?></span>-->
                                    <?php
                                }
                                else if ($list['dada_status'] == 7)
                                {
                                    ?>
                                    <span class="">未接单</span>
                                    <?php
                                    if ($list['order_status'] == 5)
                                    {
                                        ?>
                                        | <a class="blue resend_dd" data="<?php echo $list['order_id'];?>" href="javascript:;">重新发送</a>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                }
                                ?>
                            </div>
                            <div>
                                <span>¥<?php echo ' ' . $list['refund_money']; ?></span>
                            </div>
                                <div>
                                    <span>¥<?php echo ' ' . $list['sub_total']; ?></span><br>
                                    <?php
                                    if ($list['discount_money'] > 0)
                                    {
                                        ?>
                                        <span>优惠：¥<?php echo ' ' . $list['discount_money']; ?></span><br>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($list['shipping_cost'] > 0)
                                    {
                                        ?>
                                        <span>运费：¥<?php echo ' ' . $list['shipping_cost'];?></span><br>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($list['cover_charge'] > 0)
                                    {
                                        ?>
                                        <span>服务费：¥<?php echo ' ' . $list['cover_charge'];?></span><br>
                                        <?php
                                    }
                                    ?>
                                </div>
                            <div>
                            <!--<span><?php /*echo (isset($pay_type_arr[$order['pay_way']])?$pay_type_arr[$order['pay_way']]:'').''.$list['pay_money']*/ ?></span>-->                                    <?php if (isset($pay_type_arr[$list['pay_way']])) { ?>
                                <span><?php echo $pay_type_arr[$list['pay_way']]; ?></span><br>
                            <?php } ?>
                            <?php if ($list['pay_way'] != 3) { ?>
                                <span>
                                    <?php if ($list['pay_status'] == 1) {
                                        echo '已支付';
                                    } elseif ($list['pay_status'] == 2) {
                                        echo '未支付';
                                    } elseif ($list['pay_status'] == 3) {
                                        echo '已退款';
                                    } ?>
                                    </span><br>
                            <?php } ?>
                            <span>¥<?php echo ' ' . $list['pay_money']; ?></span><br>


                                </div>
                            </div>
                            <?php }?>

                        </div>
                    <?php }}?>
                    <div class="pages">
                        <div id="Pagination">
                            <div class="pagination">
                                <?php echo $pagination;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed_box bg_fff">
        <div class="tile">商品退款</div>
        <div class="con_1">
            <div class="f_b_con center">
                <table class="ref_table">
                    <tr>
                        <td>退款商品：</td>
                        <td align="left" id="goods_name">可乐哦</td>
                    </tr>
                    <tr>
                        <td>退款份数：</td>
                        <td align="left" class="refund">
                            <a class="minus" onclick="numDec()" href="javascript:void(0)"> - </a>
                            <span id="quantity">1</span>
                            <a class="plus" onclick="numAdd()" href="javascript:void(0)"> + </a>
                        </td>
                    </tr>
                    <tr>
                        <td>退款金额：</td>
                        <td align="left"><i id="refund_price">0</i>元</td>
                    </tr>

                </table>
            </div>
        </div>
        <div class="h_btn_list clearfix center" style="">
            <div class="actives">
                <button  style="list-style: none;background: none;border: none;" class="confirms_order">确认退款</button>
            </div>
            <div class="cancel">取消</div>
            <input type="hidden" id="o_id" value=""/>
            <input type="hidden" id="item_id" value=""/>
            <input type="hidden" id="goods_num" value=""/>
            <input type="hidden" id="goods_price" value=""/>
        </div>
    </div>

    <div class="fixed_box_remark bg_fff">
        <div class="tile">备注</div>
        <div class="con_1">
            <div class="f_b_con center">
                <textarea id="remark_info" style="height: 120px;width: 100%;padding: 5px 10px;color: #999">请输入订单备注</textarea>
            </div>
        </div>
        <div class="h_btn_list clearfix center" style="">
            <div class="actives confirms_remark">保存</div>
            <div class="cancel_remark">取消</div>
            <input type="hidden" id="remark_order_id" value=""/>
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
    <!--日历调用开始-->
    <!-- <script src="<?php echo base_url(FD_PUBLIC);?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<!--    <script src="<?php /*echo base_url(FD_PUBLIC).'/'.$tpl;*/?>/plugins/datatables/layDate.js"></script>
-->    <!--日历调用结束-->
    <script>
        /*;!function(){
            laydate({
                elem: '#datepicker'
            })
            laydate({
                elem: '#datepicker2'
            })
        }();*/

        //取消达达订单
        $('.cancel_dd').click(function()
        {
            if(!window.confirm('确定要更改？')){
                return false;
            }
            var data = {};
            data.oid = $(this).attr('data');
            data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
                dataType:"json",
                type:'post',
                url:"<?php echo site_url('/eat-in/orders/dadaCancelOrder')?>",
                data:data,
                success: function(res)
                {
                    alert(res.msg);
                    if (res.status == 1)
                    {
                        window.location.reload();
                    }
                },
                complete: function()
                {
                    // _this.html('提交');
                }
            });
        });

        //重新发送达达订单
        $('.resend_dd').click(function()
        {
            if(!window.confirm('确定要更改？')){
                return false;
            }
            var data = {};
            data.oid = $(this).attr('data');
            data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                dataType:"json",
                type:'post',
                url:"<?php echo site_url('/eat-in/orders/reSendOrderToDada')?>",
                data:data,
                success: function(res)
                {
                    alert(res.msg);
                    if (res.status == 1)
                    {
                        window.location.reload();
                    }
                },
                complete: function()
                {
                    // _this.html('提交');
                }
            });

        });


        //发送达达订单
        $('.send_dd').click(function()
        {
            if(!window.confirm('确定要更改？')){
                return false;
            }
            var data = {};
            data.oid = $(this).attr('data');
            data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';

            $.ajax({
                dataType:"json",
                type:'post',
                url:"<?php echo site_url('/eat-in/orders/send_dd')?>",
                data:data,
                success: function(res)
                {
                    alert(res.msg);
                    if (res.status == 1)
                    {
                        window.location.reload();
                    }
                },
                complete: function()
                {
                    // _this.html('提交');
                }
            });

        });

        //备注

        $('.confirms_remark').click(function()
        {
            //隐藏层
            var data = {};
            data.remark = $('#remark_info').val();
            data.id = $('#remark_order_id').val();

            if (data.remark == '请输入订单备注')
            {
                alert('请输入备注信息');
                return false;
            }

            data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajax({
                dataType:"json",
                type:'post',
                url:"<?php echo site_url('/eat-in/orders/save_remark')?>",
                data:data,
                success: function(res)
                {
                    alert(res.msg);
                    if (res.status == 1)
                    {
                        $('.fixed_box_remark').hide();
                        window.location.reload();
                    }
                },
                complete: function()
                {
                    // _this.html('提交');
                }
            })


            //This.removeClass('color_F99E12').addClass('color_9b9b9b');
        })
        $('.cancel_remark').click(function(){
            $('.fixed_box_remark').hide();
            $('#remark_info').val('请输入订单备注');
        })

        $('.remark').click(function(){

            $('.fixed_box_remark').show();
            $('#remark_order_id').val($(this).attr('data'));

            var remark_info = $(this).attr('content');
            if (remark_info != '')
            {
                $('#remark_info').val(remark_info);
            }
        });


        $("#remark_info").focus(function()
        {
            var remark_info = $('#remark_info').val();
            if (remark_info == '请输入订单备注')
            {
                $('#remark_info').val('');
            }
        });

        //备注结束



        /*商品数量+1*/
        function numAdd()
        {
            var quantity = $('#quantity').html();
            var num_add = parseInt(quantity) + 1;
            var goods_price = $('#goods_price').val();

            goods_price = goods_price * 100;
            if(quantity == "")
            {
                num_add = 1;
            }
            var goods_num = $('#goods_num').val();
            if(num_add > goods_num)
            {
                alert("退款份数不能大于"+goods_num);
            }
            else
            {
                $('#quantity').html(num_add);
                var Num = (goods_price * num_add)/100;
                $('#refund_price').html(Num.toFixed(2));
            }
        }

        /*商品数量-1*/
        function numDec()
        {
            var quantity = $('#quantity').html();
            var goods_price = $('#goods_price').val();
            var num_dec = parseInt(quantity) - 1;
            if(num_dec>0)
            {
                $('#quantity').html(num_dec);
                var Num=goods_price*num_dec;
                $('#refund_price').html(Num.toFixed(2));
            }
            else
            {
                alert("商品数量不能小于1");
            }
        }

        function to_refund(o_id,item_id,goods_num,goods_name,goods_price)
        {
            $('#o_id').val(o_id);
            $('#item_id').val(item_id);
            $('#goods_num').val(goods_num);
            $('#goods_name').html(goods_name);
            $('#goods_price').val(goods_price);

            $('#quantity').html(1);
            $('#refund_price').html(goods_price);

            $('.fixed_box').show();  //支付输入
        }

        $('.cancel').click(function()
        {
            $('.fixed_box').hide();
        })

        //退款

        $('.confirms_order').click(function()
        {
            var obj =$(this);
            var quantity    = $('#quantity').html();
            var o_id        = $('#o_id').val();
            var item_id     = $('#item_id').val();

            if( (typeof o_id == 'undefined') || (typeof item_id == 'undefined') || (typeof quantity == 'undefined'))
            {
                alert('data error!');
                return false;
            }
            var a=confirm("确定要退款么？");
            if(a)
            {
                var data = {};
                data.quantity = quantity;
                data.oid = o_id;
                data.item_id = item_id;
                data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
                $.ajax({
                    dataType:"json",
                    type:'post',
                    url:"<?php echo site_url('/eat-in/orders/refund_goods')?>",
                    data:data,
                    beforeSend: function()
                    {
                        obj.attr("disabled", true);
                    },
                    success: function(res)
                    {
                        alert(res.msg);
                        if (res.status == 1)
                        {
                            window.location.reload();
                        }
                    },
                    complete: function()
                    {
                        obj.removeAttr('disabled');
                        $('.fixed_box').hide();
                    }
                })
            }
        })


    </script>
    <script>
        $(function(){
            $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});//.datepicker({language: "zh-CN"});
            var bool=true;
            var obj=null;
            var dbool = true;
            $('.template_btn').click(function(){
                if(bool){
                    bool=false;
                    $('.fixed_box').fadeIn();
                    $('.f_b_con').html('确认要将当前订单状态修改为“'+$(this).html()+'”状态？');
                    obj = $(this);
                }
            });
            $('.cancel').click(function(){
                bool=true;
                $('.fixed_box').fadeOut();
            });
            $('.confirms').click(function(){
                if(dbool){
                    dbool = false;
                    if(obj.attr('iid')){
                        change_item_status(obj);
                    }else{
                        change_status(obj);
                    }
                }
            });
            $('.drow_list li').click(function(){
                $('#search_hotel').val($(this).text());
                $('#search_hotel_h').val($(this).val());
                $(this).addClass('cur').siblings().removeClass('cur');
            });


            $('.all_open_order').click(function(){
                $('.con_list').slideToggle();
            })
            // $('.datepicker').datepicker({
            //  dateFormat: "yymmdd"
            // });
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
        });
        function change_status(order_id,status){
            if(order_id){
                if(!window.confirm('确定要更改？')){
                    return false;
                }
                $.post('<?php echo site_url('eat-in/orders/update_order_status');?>',{
                    oid:order_id,
                    status:status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(data){
                    if(data.errcode==0){
                        alert(data.msg);
                        location.reload();
                    }else{
                        alert(data.msg);
                    }
                },'json');
            }
        }

        function print_order(order_id){
            if(order_id){
                if(!window.confirm('确定要打印？')){
                    return false;
                }
                $.post('<?php echo site_url('eat-in/orders/print_order');?>',{
                    oid:order_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },function(data){
                    if(data.errcode==0){
                        alert(data.msg);
                       // location.reload();
                    }else{
                        alert(data.msg);
                    }
                },'json');
            }
        }

    </script>
</body>
</html>
