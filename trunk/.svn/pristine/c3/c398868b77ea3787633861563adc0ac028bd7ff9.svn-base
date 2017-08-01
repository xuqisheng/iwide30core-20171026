<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 日历 -->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<style>
    .nav_list_btn div{text-align: center;}
    .nav_list_btn  a{font-size: 14px;display: block;width: 100%}
    .nav_list_btn  a:hover{color: #b69b69}

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
        border: 1px solid #e3e3e3;
        display: block;


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
        background: #b2955e;
        border: 1px solid #e6e6e6;
        padding: 6px 10px;
        font-size: 14px;
        display: inline;
    }

    #pages a {
        float: left;
        display: inline;
        padding: 6px 13px;
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
<div class="wrapper">
<?php
/* 顶部导航 */
echo $block_top;
?>

<?php
/* 左栏菜单 */
echo $block_left;
?>
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/jie_h.css' />
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/j_loading.css' />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/pages/pagination.css" />
<script src="<?php echo base_url(FD_PUBLIC) ?>/pages/jquery.pagination.js"></script>
<div class="over_x">
    <div class="content-wrapper">
        <!-- <div class="banner bg_fff p_0_20">test1</div> -->
        <div class="contents card-info padding_20 font_12 color_333">
            <div class="bg_fff padding_20">
                <form>
                    <div class="flex between font_14 margin_bottom_30">
                        <div class="flex nav_list_btn">
                            <a href="<?php echo site_url('ticket/orders/order_list?order_status=1')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==1)echo 'actives'?>">待付款</div>
                            </a>
                            <a href="<?php echo site_url('ticket/orders/order_list?order_status=2')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==2)echo 'actives'?>">
                                    待确认
                                </div>
                            </a>
                            <a href="<?php echo site_url('ticket/orders/order_list?order_status=3')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==3)echo 'actives'?>">
                                    待核销
                                </div>
                            </a>
                            <a href="<?php echo site_url('ticket/orders/order_list?order_status=5')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==5)echo 'actives'?>">
                                   已核销
                                </div>
                            </a>
                            <a href="<?php echo site_url('ticket/orders/order_list?order_status=6')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==6)echo 'actives'?>">
                                    已取消
                                </div>
                            </a>
                            <a href="<?php echo site_url('ticket/orders/order_list')?>">
                                <div class="width_110 <?php if(isset($filter['order_status'])&&$filter['order_status']==99)echo 'actives'?>">
                                    所有状态
                                </div>
                            </a>
                        </div>
                        <div class="color_808080 btn border_bfbfbf_1 radius_3"><button type="submit" name="ext_order" value="1">导出</button></div>
                    </div>
                    <div class="bg_f6f6f6 flex secarch_container form_head margin_bottom_30">
                        <div class="margin_right_10">
                            <select name="shop_id" class="radius_3 bg_fff height_30 border_e6e6e6_1 width_130">
                                <option value="-1">所有店铺</option>
                                <?php if(!empty($shops)){
                                    foreach($shops as $k=>$v){
                                        ?>
                                    <option <?php echo isset($filter['shop_id']) && $filter['shop_id'] == $k ? 'selected="yes"':'';?> value="<?php echo $k?>"><?php echo $v?></option>
                                <?php }}?>
                            </select>
                        </div>
                        <div class="margin_right_10">
                            <span>
                                <select name="time_type" id="time_type" class="radius_3 bg_fff height_30 border_e6e6e6_1 width_130">
                                    <option <?php echo isset($filter['time_type']) && $filter['time_type'] == 1 ? 'selected="yes"':'';?> value="1">预约时间</option>
                                    <option <?php echo isset($filter['time_type']) && $filter['time_type'] == 2 ? 'selected="yes"':'';?> value="2">下单时间</option>
                                 </select>
                            </span>
                            <label class="time_type_1" style="display: <?php echo empty($filter['time_type']) || $filter['time_type'] == 1 ? 'initial':'none';?>">
                                <span class="relative">
                                    <input id="datepicker" name="book_start_time" value="<?php echo empty($filter['book_start_time']) ? '' : $filter['book_start_time']?>" class="datepicker width_130 radius_3 border_e6e6e6_1 height_30 text_indent_3 bg_fff" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer" >&#xe604;</i>
                                </span>
                                至
                                <span class="relative">
                                    <input id="datepicker2" name="book_end_time" value="<?php echo empty($filter['book_end_time']) ? '' : $filter['book_end_time']?>" class="datepicker width_130 radius_3 border_e6e6e6_1 height_30 text_indent_3 bg_fff" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer">&#xe604;</i>
                                </span>
                            </label>

                            <label class="time_type_2" style="display: <?php echo !empty($filter['time_type']) && $filter['time_type'] == 2 ? 'initial':'none';?>">
                                <span class="relative">
                                    <input id="datepicker3" name="start_time" value="<?php echo empty($filter['start_time']) ? '' : $filter['start_time']?>" class="datepicker width_130 radius_3 border_e6e6e6_1 height_30 text_indent_3 bg_fff" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer" >&#xe604;</i>
                                </span>
                                至
                                <span class="relative">
                                    <input id="datepicker4" name="end_time" value="<?php echo empty($filter['end_time']) ? '' : $filter['end_time']?>"class="datepicker width_130 radius_3 border_e6e6e6_1 height_30 text_indent_3 bg_fff" type="text" /><i class="absolute iconfonts color_bfbfbf date_ico pointer">&#xe604;</i>
                                </span>
                            </label>
                        </div>

                        <div class="bg_fff margin_right_10 relative">
                            <input class="secarch_input width_300 radius_3 height_28 text_indent_10" type="text" name="wd" value="<?php echo empty($filter['wd']) ? '' : $filter['wd']?>" placeholder="请输入订单号/手机号/联系人" />
                            <i class="absolute iconfonts color_bfbfbf secarch_ico pointer" >&#xe600;</i>
                        </div>
                        <div>
                            <input type="hidden" name="order_status" value="<?php echo isset($filter['order_status'])?$filter['order_status']:''?>"/>
                            <div class="btn font_14 bg_b69b69 radius_3 color_fff margin_right_10 secarch_btn"><button style="color: #fff;width: 100%" type="submit">查询</button></div>
                        </div>
                    </div>
                </form>
                <div class="border_bottom_ccd6e1_1 padding_b_10">
                    <table class="j_table_order" style="width:100%;">
                        <thead>
                            <tr class="border_bottom_ccd6e1_1">
                                <th>商品&规格</th>
                                <th>商品单价&份数</th>
                                <th>消费时间</th>
                                <th>应付金额</th>
                                <th>实付金额</th>
                                <th>订单状态</th>
                                <th class="max_width_130">用户信息</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <!---订单循环开始--->
                            <?php
                                if(!empty($res))
                                {
                                    foreach($res as $k => $list)
                                    {
                                        $rows = count($list['order_info']);
                                        ?>
                                        <tr class="table_order bg_f8fafc">
                                            <td class="order_number" colspan='7'>
                                                <span
                                                    class=" inline_block margin_right_15">订单号：<?php echo !empty($list['merge_info']['orderNO']) ?$list['merge_info']['orderNO'] : '--';?></span>
                                                <span><?php echo !empty($list['merge_info']['add_time']) ?$list['merge_info']['add_time'] : '--';?></span>
                                            </td>
                                        </tr>
                                        <?php
                                        if ($rows)
                                        {
                                            foreach ($list['order_info'] as $key=> $order)
                                            {
                                        ?>
                                        <tr>
                                            <td class="order_number" colspan='7'>
                                                <span
                                                    class=" inline_block margin_right_15">订单号：<?php echo $order['order_sn'];?></span>
                                                <span class="margin_right_15"><?php echo $order['add_time'];?></span>
                                                <span><?php echo $hotels[$order['hotel_id']] .'-'.$shops[$order['shop_id']];?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex">
                                                    <div class="j_img margin_right_10 radius_3 hiddens"
                                                         style="width:60px;height:60px;"><img
                                                            src="<?php echo !empty($order['order_goods'][0]['goods_img']) ? json_decode($order['order_goods'][0]['goods_img'], true)[0] : ''; ?>"
                                                            alt=""></div>
                                                    <div class="max_width_220 text_left">
                                                        <span><?php echo $order['order_goods'][0]['goods_name'];?></span><br>
                                                        <span><?php echo $order['order_goods'][0]['spec_name'];?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span>￥<?php echo $order['order_goods'][0]['goods_price'];?></span><br>
                                                <span><?php echo $order['order_goods'][0]['goods_num'];?>份</span>
                                            </td>
                                            <td><?php echo date('Y-m-d',strtotime($order['dissipate']));?></td>
                                            <td>
                                                <span>￥<?php echo $order['sub_total'];?></span><br>
                                                <span class="color_bfbfbf">优惠￥<?php echo $order['discount_money'];?></span>
                                            </td>
                                            <td>
                                                <span>￥ <?php echo $order['pay_money'];?></span><br>
                                                <span><?php echo $pay_type_arr[$order['pay_way']]; ?>-
                                                    <?php if ($order['pay_way'] != 3) { ?>
                                                        <span>
                                                        <?php if ($order['pay_status'] == 1) {
                                                        echo '已支付';
                                                        } elseif ($order['pay_status'] == 2) {
                                                        echo '未支付';
                                                        } elseif ($order['pay_status'] == 3) {
                                                        echo '已退款';
                                                        } ?>
                                                        </span><br>
                                                        <?php } ?>
                                                </span>
                                            </td>
                                            <td>

                                                <!--
                                                <p>待核销</p>
                                                <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10">核销</span>
                                                <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10">取消</span>
                                                -->

                                                <?php if ($order['order_status'] == $orderModel::OS_UNCONFIRMED
                                                    && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT && $order['pay_way'] != 3) {
                                                ?>
                                                    <p>待付款</p>
                                                    <!--
                                                    <button class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10"
                                                          onclick="change_status('<?php echo $order['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</button>-->
                                                <?php }
                                                    elseif ($order['order_status'] == $orderModel::OS_UNCONFIRMED && $order['pay_status'] == $orderModel::IS_PAYMENT_YES
                                                    && $order['pay_way'] != 3 || $order['pay_way'] == 3 && $order['order_status'] == $orderModel::OS_UNCONFIRMED)
                                                    {
                                                ?>
                                                    <p>待接单</p>
                                                    <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10"
                                                          onclick="change_status('<?php echo $order['order_id'] ?>' , '<?php echo $orderModel::OS_CONFIRMED ?>')">接单</span>
                                                    <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10"
                                                          onclick="change_status('<?php echo $order['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</span>
                                                <?php }
                                                    elseif ($order['order_status'] == $orderModel::OS_CONFIRMED && $order['pay_status'] == $orderModel::IS_PAYMENT_YES
                                                    && $order['pay_way'] != 3 || $order['pay_way'] == 3 && $order['order_status'] == $orderModel::OS_CONFIRMED)
                                                    {
                                                ?>
                                                    <p>待核销</p>
                                                    <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10"
                                                          onclick="change_status('<?php echo $order['order_id'] ?>' , '<?php echo $orderModel::OS_FINISH ?>')">核销</span>
                                                    <span class="color_b69b69 btn border_b69b69_1 radius_3 margin_bottom_10"
                                                          onclick="change_status('<?php echo $order['order_id'] ?>' , '<?php echo $orderModel::OS_HOL_CANCEL ?>')">取消</span>
                                                <?php }
                                                    else if ($order['order_status'] == $orderModel::OS_FINISH) {
                                                ?>
                                                    <p>已核销</p>
                                                <?php }
                                                    else if ($order['order_status'] == $orderModel::OS_PER_CANCEL || $order['order_status'] == $orderModel::OS_HOL_CANCEL
                                                        || $order['order_status'] == $orderModel::OS_SYS_CANCEL)
                                                    {
                                                ?>
                                                    <p>已取消</p>
                                                <?php } ?>

                                            </td>
                                            <?php
                                            if ($key == 0 )
                                            {
                                                ?>
                                                <td class="max_width_130" rowspan='<?php echo ($rows * 2) -1;?>'>
                                                    <div class="flex column">
                                                        <div><?php echo $list['merge_info']['consignee'];?></div>
                                                        <div class="margin_bottom_15"><?php echo $list['merge_info']['phone'];?></div>
                                                        <div><?php echo !empty($list['merge_info']['note']) ? '用户备注：'.$list['merge_info']['note'] : '';?></div>
                                                    </div>
                                                </td>
                                                <?php
                                            }
                                            ?>

                                        </tr>
                                        <?php
                                            }
                                        }
                                    }
                               }
                            ?>
                            <!---订单循环结束--->
                        </tbody>
                    </table>
                </div>
                <div class="pages flex between" style="width:100%;">
                    <div>当前共筛选到 <span class="color_9e9e9e"><?php echo $arr_page['total']?></span> 条／共 <span class="color_9e9e9e"><?php echo $arr_page['page_total']?></span> 页数据</div>
                    <div id="Pagination"><?php echo $pagehtml;?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p_fixed color_333">
    <div class="flex w_h_100">
        <div class="j_load_ico margin_auto">
            <div class="loader">
                <div class="loader-inner ball-triangle-path relative">
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
            </div>
            <div class="color_fff">&nbsp;  数据加载中。。。</div>
        </div>
    </div>
</div>
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
<script>
;!function(){
    laydate({
       elem: '#datepicker'
    })
    laydate({
       elem: '#datepicker2'
    })
    laydate({
        elem: '#datepicker3'
    })
    laydate({
        elem: '#datepicker4'
    })
}();
</script>
<script>
$('.p_fixed').hide();
$('.nav_list_btn >div').click(function(){
    var _index=$(this).index();
    $(this).addClass('actives').siblings().removeClass('actives');
    $('.nav_list_content >div').eq(_index).show().siblings().hide();
})

//分页
//$("#Pagination").pagination("10");

function change_status(order_id,status)
{
    if(order_id)
    {
        if(!window.confirm('确定要更改？'))
        {
            return false;
        }
        $.post('<?php echo site_url('ticket/orders/update_order_status');?>',
        {
            oid:order_id,
            status:status,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(data)
        {
            if(data.errcode==0)
            {
                alert(data.msg);
                location.reload();
            }
            else
            {
                alert(data.msg);
            }
        },'json');
    }
}



$('#time_type').change(function()
{
    var time_type_val = $(this).val();
    if (time_type_val == 1)
    {
        $('.time_type_1').show();
        $('.time_type_2').hide();
    }
    else
    {
        $('.time_type_2').show();
        $('.time_type_1').hide();
    }
})
</script>
</body>
</html>
