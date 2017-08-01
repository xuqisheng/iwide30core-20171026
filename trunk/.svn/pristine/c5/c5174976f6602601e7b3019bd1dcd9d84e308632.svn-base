<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<!-- 新版本后台 v.2.0.0 -->
<link type="text/css" href="<?php echo base_url(FD_PUBLIC) ?>/soma/css/tao.css" rel="stylesheet">
<style>
    .table_banner_top{
        padding: 15px 35px;
        background-color: #f6f6f6;
    }
    .page_each_number{
        background-color: white;
        width: 85px;
        height: 28px;
        line-height: 28px;
    }
    .search{
        width: 200px;
        height: 28px;
        line-height: 28px;
    }
    .search_btn{
        width: 75px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        background-color: #b69b69;
        color:white;
        cursor: pointer;
    }
    .add{
        width: 110px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        color:#b69b69;
        background-color: #f6f6f6;
        border-radius: 5px;
        border:1px solid #ededed;
        cursor: pointer;
    }
    .table{
        border-collapse:collapse;border-spacing:0;
    }
    .table th:last-child{
        width: 150px;
    }
    .table th{
        color: #808080;
        border-bottom: 1px solid #ccd6e1;
        padding: 15px 0px;
    }
    .table td{
        padding: 15px 0px;
        color: #333;
    }
    .table tr:nth-child(2n+1) td{
        background-color: #eff2f7;
    }
    .table ib{
        color: #b69b69;
        border:1px solid #b69b69;
        border-radius: 5px;
        height: 28px;
        line-height: 28px;
        width: 75px;
        cursor: pointer;
    }
    .table input{
        width: 100px;
        height: 30px;
        line-height: 30px;
        padding: 0px 5px;
        text-align: center;
        border:1px solid #e7e9eb;
    }
    .table tr>td:nth-child(7){
        width: 110px;
    }
    .table tr>td:nth-child(8){
        width: 80px;
    }
    .table td>div{
        margin: 5px 0px;
    }
    .page_info{
        color:#bfbfbf;
    }
    .page_info>span{
        color: #808080;
    }
    .ttable tr>td:nth-child(2n+1){
        text-align: right;
        width: 70px;
        color: #808080;
    }
    .ttable tr>td:nth-child(2n){
        text-align: left;
        padding: 10px 20px;
    }
</style>
<script src="<?php echo base_url(FD_PUBLIC) ?>/soma/js/jquery-1.7.2.min.js"></script>
<body class="hold-transition skin-blue sidebar-mini">
<?php
$combine_main_order = false;
if(!empty($combine_assets))
{
    $combine_main_order = true;
    $asset_items = $combine_assets;
}
?>
<div class="wrapper">

    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>

    <div class="content-wrapper">
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>
            <div class="land">
                <table class="w100 ttable">
                    <tr style="display: none;">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>订单号</td>
                        <td><?php echo $order_detail['order_id'];?></td>
                        <td>用户昵称</td>
                        <td><?php echo $order_detail['openid'];?></td>
                    </tr>
                    <tr>
                        <td>支付时间</td>
                        <td><?php echo $order_detail['payment_time'];?></td>
                        <td>优惠金额</td>
                        <td><?php echo $order_detail['discount'];?></td>
                    </tr>
                    <tr>
                        <td>实付总额</td>
                        <td><?php echo $order_detail['real_grand_total'];?></td>
                        <td>状态</td>
                        <td><?php echo $order_detail['status'];?></td>
                    </tr>
                    <tr>
                        <td>订单类型</td>
                        <td>
                            <?php if($combine_main_order): ?>
                                组合购买套餐订单
                            <?php elseif(!empty($order_detail['master_oid'])): ?>
                                组合购买套餐子订单
                            <?php else: ?>
                            <?php endif; ?>
                        </td>
                        <td>价格配置</td>
                        <td><?php echo $scopeName;?></td>
                    </tr>
                </table>
            </div>
            <div class="land">
                <div mt-15>
                    <table class="w100 table" center>
                        <tr>
                            <th>订单号</th>
                            <th>购买人</th>
                            <th>商品ID</th>
                            <th>商品名</th>
                            <th>剩余数量</th>
                            <th>过期时间</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <?php if( $asset_items ):?>
                            <?php foreach( $asset_items as $k=>$v ):?>
                                <tr>
                                    <td><?php echo $v['order_id'];?></td>
                                    <td><?php echo $order_detail['contact'];?></td>
                                    <td><?php echo $v['product_id'];?></td>
                                    <td><?php echo $v['name'];?></td>
                                    <td><?php echo $v['qty'];?></td>
                                    <td>
                                        <?php $expDate = explode(' ',$v['expiration_date']);?>
                                        <div><?php echo $expDate[0];?></div>
                                        <div><?php echo $expDate[1];?></div>
                                    </td>
                                    <?php
                                        echo form_open(
                                            Soma_const_url::inst()->get_url('*/*/batch_post'),
                                            array('class'=>'form-horizontal','id'=>'goForm'.$v['order_id'])
                                        );
                                    ?>
                                        <td><input type="text" name="num" placeholder="输入核销数量"></td>
                                        <td><ib onclick="add(<?php echo $v['order_id'];?>)">核销</ib></td>

                                        <input type="hidden" name="order_id" value="<?php echo $v['order_id'];?>">
                                    <?php echo form_close() ?>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </table>
                </div>
<!--                <flex between mt-15>-->
<!--                    <ib class="page_info">当前共筛选到<span>12</span>条／共<span>100</span>条数据</ib>-->
<!--                    <ib pagebtn>-->
<!--                        <ib pagebtn_gray><</ib>-->
<!--                        <ib pagebtn_gray ml-3>1</ib>-->
<!--                        <ib pagebtn_gray nowpage>2</ib>-->
<!--                        <ib pagebtn_gray>3</ib>-->
<!--                        <ib pagebtn_gray>4</ib>-->
<!--                        <ib pagebtn_gray>5</ib>-->
<!--                        <ib pagebtn_gray ml-3>></ib>-->
<!--                        <ib>第</ib>-->
<!--                        <ib pagebtn_gray><input value="2"></ib>-->
<!--                        <ib>页</ib>-->
<!--                        <ib pagebtn_gray>GO</ib>-->
<!--                    </ib>-->
<!--                </flex>-->
            </div>
        </section>
    </div><!-- /.content-wrapper -->
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
</body>
<script>
    function add(order_id) {
//        alert($("#goForm"+order_id).attr('action'));return;
        var is_true = '';
        var is_expire = "<?php echo $is_expire;?>";
        if( is_expire ){
            is_true = confirm("该订单已经过了有效期，是否要核销？");
        }else{
            is_true = confirm("你确认要进行该操作吗？");
        }
        if( !is_true ){
            return false;
        } else {
            $("#goForm"+order_id).submit();
        }
    }
</script>
</html>
