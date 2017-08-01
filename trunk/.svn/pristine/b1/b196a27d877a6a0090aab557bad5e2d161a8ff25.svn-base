<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>订单详情</title>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '9035a905d6d239a4']);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
    <?php include 'wxheader.php' ?>
</head>
<body>
<div class="pageloading"></div>
<style>
.btnlayer{justify-content: center;}
.btn_void{color:#9b9b9b}
</style>
<page class="page h26">
	<header>
    	<div class="padding center bg_fff">
            <div class="iconfont color_main" style="font-size:40px">&#XA6;</div>
            <?php if($order['order_status'] == $orderModel::OS_UNCONFIRMED && $order['pay_status'] == $orderModel::IS_PAYMENT_NOT &&$order['pay_way']!=3){?>
            <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单待付款 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <?php if($order['inter_id'] != 'a469543253' && $order['inter_id']!='a469428180'){?>
            <div class="color_555 h20">您的订单已提交</div>
                <?php }?>
            <div class="flex martop color_555 pad2 btnlayer">
                <a href="<?php echo $order['pay_url']?>" class="bg_link">立即付款</a>
                <a onclick="cancelorder('<?php echo $order['order_id']?>')" class="btn_void">取消订单</a>
            </div>
            <?php }elseif($order['order_status'] == $orderModel::OS_UNCONFIRMED && $order['pay_status'] == $orderModel::IS_PAYMENT_YES &&$order['pay_way']!=3 || $order['pay_way']==3 && $order['order_status'] == $orderModel::OS_UNCONFIRMED){?>
            <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单待接单 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <?php if($order['inter_id'] != 'a469543253' && $order['inter_id']!='a469428180'){?>
            <div class="color_555 h20">您的订单已提交</div>
                <?php }?>
            <div class="flex martop color_555 pad2 btnlayer">
                <?php if($order['pay_way']!=3){?>

                <?php }else{?>
                    <a href="<?php echo $order['again_url']?>" class="bg_link">再来一单</a>
                <?php }?>
                <a  onclick="cancelorder('<?php echo $order['order_id']?>')" class="btn_void">取消订单</a>
            </div>
            <?php }elseif($order['order_status'] == $orderModel::OS_CONFIRMED && $order['pay_status'] == $orderModel::IS_PAYMENT_YES &&$order['pay_way']!=3||$order['pay_way']==3 && $order['order_status'] == $orderModel::OS_CONFIRMED ){?>
            <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单待消费 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <?php if($order['inter_id'] != 'a469543253' && $order['inter_id']!='a469428180'){?>
            <div class="color_555 h20">您的订单已提交，请您准时到达现场核销</div>
                <?php }?>
            <div class="flex martop color_555 pad2 btnlayer">
                <!--
                <a  onclick="reminder('<?php echo $order['order_id']?>')" class="bg_main">催单</a>
                -->
                <a  href="<?php echo $order['again_url']?>" class="bg_link">再来一单</a>
               <!-- <a  onclick="cancelorder('<?php /*echo $order['order_id']*/?>')" class="btn_void">取消订单</a>-->
            </div>
            <?php }elseif($order['order_status'] == $orderModel::OS_SHPPING){?>
            <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单待消费 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <?php if($order['inter_id'] != 'a469543253' && $order['inter_id']!='a469428180'){?>
            <div class="color_555 h20">您的订单已提交</div>
                <?php }?>
            <div class="flex martop color_555 pad2 btnlayer">
                <a  onclick="reminder('<?php echo $order['order_id']?>')" class="bg_main">催单</a>
                <a href="<?php echo $order['again_url']?>" class="bg_link">再来一单</a>
<!--                <a  onclick="cancelorder('<?php /*echo $order['order_id']*/?>')" class="btn_void">取消订单</a>
-->            </div>
            <?php }elseif($order['order_status'] == $orderModel::OS_FINISH ){?>
            <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单已消费 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <div class="color_555 h20">您的订单已消费</div>
            <div class="flex martop color_555 pad2 btnlayer">
                <a href="<?php echo $order['again_url']?>" class="bg_link">再来一单</a>
            </div>
            <?php }elseif($order['order_status'] == $orderModel::OS_HOL_CANCEL ||$order['order_status'] == $orderModel::OS_PER_CANCEL||$order['order_status'] == $orderModel::OS_SYS_CANCEL){?>
                <a class="h36" href="<?php echo site_url('ticket/ticket/orderfollow?id='.$order['inter_id'].'&hotel_id='.$order['hotel_id'].'&shop_id='.$order['shop_id'].'&oid='.$order['order_id']);?>">订单已取消 <span class="iconfont h28" style="color:#9b9b9b;vertical-align:middle">&#xA2;</span></a>
            <div class="color_555 h20">您的订单已取消</div>
            <div class="flex martop color_555 pad2 btnlayer">
                <a href="<?php echo $order['again_url']?>" class="bg_link">再来一单</a>
            </div>
            <?php }?>
        </div>
    </header>
    <section class="scroll flexgrow" style="padding-bottom:10px">
        <div class="list_style_1 martop goods">
        	<div>订单商品</div>
            <?php if(!empty($order_goods)){
                        foreach($order_goods as $k=>$v){
            ?>
            <div class="item">
            	<div>
                	<p><?php echo $v['goods_name']?></p>
                    <p class="h20 color_555"><?php echo $v['spec_name']?></p>
                </div>
                <div>×<?php echo $v['goods_num']?></div>
				<div class=" color_minor">¥<?php echo $v['goods_price']?></div>
            </div>
            <?php }}?>


            <div class="justify webkitbox">
            	<div>消费时间</div>
                <div class="color_555 "><?php echo $order['dissipate']?></div>
            </div>

            <!--<div class="justify webkitbox">
            	<div>优惠券</div>
                <div class="color_minor">已优惠¥2.0</div>
            </div>-->
            <div class="color_555">
                <span>
                    订单<span class="y " style="margin-right:2px"><?php echo $order['row_total']?></span>
                    优惠<span class="y " style="margin-right:2px"><?php echo $order['discount_money']?></span>
                </span>
                <?php if($order['pay_status'] == $orderModel::IS_PAYMENT_YES){?>
                <span class="color_000">已支付<span class="y "><?php echo $order['pay_money']?></span></span>
                <?php }elseif($order['pay_status'] == $orderModel::IS_PAYMENT_NOT){?>
                <span class="color_000">待支付<span class="y "><?php echo $order['sub_total']?></span></span>
                <?php }?>
            </div>
        </div>
        <div class="list_style_1 martop">
        	<div>联系信息</div>

            <div class="justify webkitbox color_555">
            	<div>姓名</div>
                <div><?php echo $order['consignee']?></div>
            </div>
            <div class="justify webkitbox color_555">
                <div>电话</div>
                <div><?php echo $order['phone']?></div>
            </div>
        </div>
        <div class="list_style_1 martop">
        	<div>订单信息</div>
            <div class="justify webkitbox color_555">
            	<div>订单号</div>
                <div><?php echo $order['order_sn']?></div>
            </div>
            <div class="justify webkitbox color_555">
            	<div>支付方式</div>
                <div><?php echo isset($order['pay_way'])&&isset($orderModel->pay_way_array[$order['pay_way']])?(($order['pay_way']==4)?'微信':$orderModel->pay_way_array[$order['pay_way']]).'支付':'';//4=》威富通 显示 微信?>
                </div>

            </div>
            <div class="justify webkitbox color_555">
            	<div>下单时间</div>
                <div><?php echo $order['add_time']?></div>
            </div>
            <?php
            if (!empty($order['note'])) {

                ?>
                <div class="justify webkitbox color_555">
                    <div>需求</div>
                    <div><?php echo $order['note'] ?></div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="list_style_1 martop">
            <div>商家信息</div>

            <div class="justify webkitbox color_555">
                <div>商家地址</div>
                <div class="linkblock martop" onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $hotel['address'];?>')">
                    <?php echo $hotel['address']?>
                </div>
            </div>
            <div class="justify webkitbox color_555">
                <div>商家电话</div>
                <div><a href="tel:<?php echo $hotel['tel'] ? $hotel['tel'] : '--'?>"><?php echo $hotel['tel'] ? $hotel['tel'] : '--'?></a></div>
            </div>
        </div>
    </section>
    <footer></footer>
</page>
</body>
<script>
    function reminder(order_id){
        if(order_id == ''){
            return false;
        }
        $.post('<?php echo site_url('ticket/ticket/reminder');?>',{
            'oid':order_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(res){
            if(res.errcode == 0){
                alert(res.msg);
                window.location.reload();
            }else{
                alert(res.msg);
            }
        },'json');
    }
    //取消订单
    function cancelorder(order_id){
        if(order_id == ''){
            return false;
        }
        $.MsgBox.Alert('确认取消订单？',function(){
            $.post('<?php echo site_url('ticket/ticket/cancelOrder');?>',{
                'oid':order_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(res){
                if(res.errcode == 0){
                    alert(res.msg);
                    window.location.reload();
                }else{
                    alert(res.msg);
                }
            },'json');

        })
    }
    function tips(){
        $.MsgBox.Alert('如需再来一单，请重新扫描二维码');
    }
    $('#Maddress').click(function(){
        window.location.href = $(this).attr('href') 
        + '?latitude=<?php echo $hotel['latitude'];?>'
        + '&longitude=<?php echo $hotel['longitude'];?> '
        + '&name=<?php echo $hotel['name'];?> '
        + '&address=<?php echo $hotel['address'];?> ' 
    });

    // function tonavigate(lati,longi,hname,addr) {
    //     wx.openLocation({
    //         latitude: lati,
    //         longitude: longi,
    //         name: hname,
    //         address: addr,
    //         scale: 15,
    //         infoUrl: ''
    //     });
    // }

</script>
</html>
