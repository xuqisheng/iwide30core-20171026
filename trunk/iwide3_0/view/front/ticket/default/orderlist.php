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
<title>订单列表</title>
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
</head>
<body>
<div class="pageloading"></div>
<page class="page h24">
    <!--
	<header>
    	<div class="center bg_fff flex flexjustify tablayer color_main bd_btm_img">
        	<a href="<?php echo site_url('roomservice/roomservice/orderlist?id='.$inter_id.'&type=1')?>" <?php if($type==1)echo 'class="iscur"'?>><tt>客房内</tt></a>
        	<a href="<?php echo site_url('roomservice/roomservice/orderlist?id='.$inter_id.'&type=2')?>" <?php if($type==2)echo 'class="iscur"'?>><tt>堂食</tt></a>
        	<a href="<?php echo site_url('roomservice/roomservice/orderlist?id='.$inter_id.'&type=3')?>" <?php if($type==3)echo 'class="iscur"'?>><tt>外卖</tt></a>
        </div>
    </header>
    -->
    <section class="scroll flexgrow orders" style="padding-bottom:10px">
        <?php if(!empty($orderlist)){
            foreach($orderlist as $k=>$v){
                ?>
        <div class="list_style_1 martop flexlist" url="<?php echo site_url('ticket/ticket/order_detail?id='.$v['inter_id'].'&hotel_id='.$v['hotel_id'].'&shop_id='.$v['shop_id'].'&oid='.$v['order_id']);?>">
        	<div class="flex flexjustify">
            	<a class="linkblock flex _width stopPropagation" href="<?php  echo site_url('ticket/ticket/index?id='.$v['inter_id'].'&hotel_id='.$v['hotel_id'].'&shop_id='.$v['shop_id'])?>">
                    <span class="img"><div class="squareimg"><img src="<?php echo $public['logo']?>"></div></span>
                    <span class="h26"><?php echo isset($shoplist[$v['shop_id']])?$shoplist[$v['shop_id']]:''?></span>
                </a>
                <?php if($v['order_status'] == $orderModel::OS_UNCONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_NOT &&$v['pay_way']!=3){?>
                <div>订单待付款</div>
                <?php }elseif($v['order_status'] == $orderModel::OS_UNCONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_YES &&$v['pay_way']!=3 || $v['pay_way']==3 && $v['order_status'] == $orderModel::OS_UNCONFIRMED){?>
                <div>订单待接单</div>
                <?php }elseif($v['order_status'] == $orderModel::OS_CONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_YES &&$v['pay_way']!=3||$v['pay_way']==3 && $v['order_status'] == $orderModel::OS_CONFIRMED ){?>
                    <div>订单待消费</div>
                <?php }elseif($v['order_status'] == $orderModel::OS_SHPPING){?>
                    <div>订单配送中</div>
                <?php }elseif($v['order_status'] == $orderModel::OS_FINISH ){?>
                    <div>订单已消费</div>
                <?php }elseif($v['order_status'] == $orderModel::OS_PER_CANCEL ||$v['order_status'] == $orderModel::OS_HOL_CANCEL||$v['order_status'] == $orderModel::OS_SYS_CANCEL){?>
                    <div>订单已取消</div>
                    <?php }?>
            </div>
            <div class="leftmargin flex flexjustify">
            	<div><?php echo $v['show_name']?></div>
				<div class="color_minor marleft">¥<?php echo $v['sub_total']?></div>
            </div>
            <div class="btnlayer">
                <?php if($v['order_status'] == $orderModel::OS_UNCONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_NOT &&$v['pay_way']!=3){?>
            		<a class="btn_void color_link stopPropagation" href="<?php echo $v['pay_url']?>">立即付款</a>
                <?php }elseif($v['order_status'] == $orderModel::OS_UNCONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_YES &&$v['pay_way']!=3 || $v['pay_way']==3 && $v['order_status'] == $orderModel::OS_UNCONFIRMED){?>
                   <!-- <div class="btn_void color_main stopPropagation" onclick="reminder('<?php echo $v['order_id']?>')">催单</div>-->
                    <a class="btn_void color_link stopPropagation" href="<?php echo $v['again_url']?>">再来一单</a>
                <?php }elseif($v['order_status'] == $orderModel::OS_CONFIRMED && $v['pay_status'] == $orderModel::IS_PAYMENT_YES &&$v['pay_way']!=3||$v['pay_way']==3 && $v['order_status'] == $orderModel::OS_CONFIRMED ){?>
                <!--<div class="btn_void color_main stopPropagation" onclick="reminder('<?php echo $v['order_id']?>')">催单</div>-->
                    <a class="btn_void color_link stopPropagation" href="<?php echo $v['again_url']?>">再来一单</a>
                <?php }elseif($v['order_status'] == $orderModel::OS_SHPPING){?>
                   <!-- <div class="btn_void color_main stopPropagation" onclick="reminder('<?php echo $v['order_id']?>')">催单</div>-->
                    <a class="btn_void color_link stopPropagation" href="<?php echo $v['again_url']?>">再来一单</a>
                <?php }elseif($v['order_status'] == $orderModel::OS_FINISH ){?>
                    <a class="btn_void color_link stopPropagation" href="<?php echo $v['again_url']?>">再来一单</a>
                <?php }elseif($v['order_status'] == $orderModel::OS_PER_CANCEL ||$v['order_status'] == $orderModel::OS_HOL_CANCEL||$v['order_status'] == $orderModel::OS_SYS_CANCEL){?>
                    <a class="btn_void color_link stopPropagation" href="<?php echo $v['again_url']?>">再来一单</a>
                 <?php }?>
            </div>
        </div>
        <?php }}?>
    </section>
    <footer></footer>
</page>
</body>
<script>
	$('.stopPropagation').click(function(e){
		e.stopPropagation()
	})
	$('[url]').click(function(){
		window.location.href=$(this).attr('url');
	})
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
            }else{
                alert(res.msg);
            }
        },'json');
    }
    function tips(){
        $.MsgBox.Alert('如需再来一单，请重新扫描二维码');
    }
</script>
</html>
