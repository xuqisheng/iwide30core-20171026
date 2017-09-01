<?php /** 订单状态页面 **/ ?>
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/choosebtn.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/bill.css')?>" rel="stylesheet">
<title>订单状态</title>
</head>
<body>

<?php $first_item= current($order_items); ?>

<?php if($mail_type==1): ?>
    <?php if($first_item['status'] == $item_model::STATUS_SHIPPING ): ?>
    <!-- 邮寄样式为mailing  赠送样式为 sending -->
    <div class="mailing" style="padding-bottom:4%;">
        <div class="bg_orange mailstatus">已发货</div>
    <?php elseif($first_item['status'] == $item_model::STATUS_SHIP_PRE ): ?>
    <div class="mailing" style="padding-bottom:4%;">
        <div class="bg_orange mailstatus">待发货</div>
    <?php endif; ?>
    
<?php elseif($mail_type==3): ?>
    <?php if($first_item['status'] == $item_model::STATUS_GIFTING ): ?>
    <div class="sending" style="padding-bottom:4%;">
        <div class="bg_orange mailstatus">赠送中</div>
    <?php else: //elseif($first_item['status'] == $item_model::STATUS_DEFAULT 
    		//&& $first_item['ge_openid'] == $openid 
    		//&& $first_item['gstatus']==1 ):
    ?>
    <div class="sending" style="padding-bottom:4%;">
        <div class="bg_orange mailstatus">已赠送</div>
    </div>
    <div class="orderlist">
        <div class="erweima ui_border bg_white overflow">
           <div class="gray normal">订单已赠送好友<?php 
if(isset($gift_logs[0]['gt_openid'])){
    $gt_openid= $gift_logs[0]['gt_openid'];
    $fans_info= $publics_model->get_fans_info( $gt_openid );
    echo $fans_info['nickname'];
}
?>
           </div>
        </div>
        <div class="notic gray">
        小提示：您可以在<a href="<?php echo site_url('mall/wap/my_orders/'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="blue">“我的订单”</a>中查看此单详情；

<?php endif; ?>
		</div>
    
<?php endif; ?>
    </div>
    

<?php //收货信息===============
if( ($first_item['status'] == $item_model::STATUS_SHIPPING || $first_item['status'] ==	$item_model::STATUS_SHIP_PRE ) 	&& $first_item['openid'] == $openid):
?>
	<!-- 未邮寄和已邮寄显示这块 -->
		<div class="ui_list bg_white">
			<div class="item itemico1">
				<div class="float_r"><?php echo $address['phone'] ?></div>
				<div>收货人：<?php echo $address['contact'] ?></div>
				<div>收货地址：<?php echo $address['country']. $address['province']. $address['city']. $address['region']. $address['address'] ?></div>
			</div>
	<?php if( !empty($first_item['trans_company']) ): ?>
			<div class="item itemico2">
				<div>发货快递：<?php echo $first_item['trans_company'] ?></div>
				<div>快递单号：<?php echo $first_item['trans_no'] ?></div>
			</div>
	<?php elseif( empty($first_item['trans_company']) ):?>
			<div class="item itemico2">
				<div style="padding:0.65rem 0">等待发货</div>
			</div>
	<?php endif; ?>
		</div>
	<!-- end -->
	
	<?php if( empty($first_item['trans_company']) ):?>
	<!-- 未邮寄显示这块 -->
		<div class="notic gray">工作人员将尽快为您安排邮寄，届时您可收到发货短信提醒，您也可在<a href="<?php echo site_url('mall/wap/my_orders'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}" ?>" class="blue">我的订单</a>中查看邮寄状态哦！</div>
	<!-- end -->
	<?php endif;?>
	
	<?php //暂时关闭单独邮寄发票入口
	if( false ): ?>
        <div class="ui_list bg_white">
            <a href="<?php echo site_url('mall/wap/bill/'.$oid). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>" class="item itemico0 bill">
                <tt>不需要</tt>
                <div>发票开具</div>
            </a>	
        </div>
	<?php endif; ?>

<?php endif; ?>

<?php //产品清单，如果为赠送中 ===============
if( $first_item['status'] == $item_model::STATUS_GIFTING ):
?>
	<div class="orderlist">
    	<div class="ordertitle bg_white">
        	<span class="float_r"><?php echo $order_infos['order_time'] ?></span>
        	<span>订单号：<?php echo $order_infos['out_trade_no']?></span>
        </div>
        <div class="content"><?php $total_qty= $total_pay=0; foreach ($order_items as $v): ?>
            <div class="item">
				<div class="itemimg"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>"></div>
				<div class="hotelname"><?php echo $v['gs_name'] ?></div>
				<div class="desc gray"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
				<div style="margin-top:3%">
					<span class="ui_price color"><?php echo $v['promote_price'] ?></span>
					<span class="count gray"><?php echo $v['qty'] ?></span>
				</div>
            </div><?php $total_qty+= $v['qty']; $total_pay+= $v['promote_price']; endforeach; ?>
         </div>
    	<div class="orderfoot bg_white">
        	<span> 共<?php echo $total_qty ?> 件商品&nbsp; 合计 ¥<em><?php echo $total_pay ?></em>&nbsp;
				(含邮寄费 ¥<?php echo $order_infos['shipping_fee']?>)
			</span>
            &nbsp;&nbsp;
        </div>
    </div>

<?php  //产品清单，如果为邮寄申请/邮寄中===============
elseif( $first_item['status'] == $item_model::STATUS_SHIP_PRE || $first_item['status'] == $item_model::STATUS_SHIPPING ):
?>
	<div class="orderlist">
    	<div class="ordertitle bg_white">
        	<span class="float_r"><?php echo $order_infos['order_time'] ?></span>
        	<span>订单号：<?php echo $order_infos['out_trade_no']?></span>
        </div>
        <div class="content"><?php $total_qty= $total_pay=0; foreach ($order_items as $v): 
				if($v['can_mail']==EA_base::STATUS_FALSE_) continue; ?>
            <div class="item">
				<div class="itemimg"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>"></div>
				<div class="hotelname"><?php echo $v['gs_name'] ?></div>
				<div class="desc gray"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
				<div style="margin-top:3%">
					<span class="ui_price color"><?php echo $v['promote_price'] ?></span>
					<span class="count gray"><?php echo $v['qty'] ?></span>
				</div>
            </div><?php $total_qty+= $v['qty']; $total_pay+= $v['promote_price']; endforeach; ?>
         </div>
    	<div class="orderfoot bg_white">
        	<span> 共<?php echo $total_qty ?> 件商品&nbsp; 合计 ¥<em><?php echo $total_pay ?></em>&nbsp;
				(含邮寄费 ¥<?php echo $order_infos['shipping_fee']?>)
			</span>
            &nbsp;&nbsp;
        </div>
    </div>
<?php endif;?>



<?php 
if( false //$first_item['ge_openid'] == $openid //<2为未领取
	&& $first_item['gstatus'] < $item_model::STATUS_GIFTED  ):
?>
<!-- 赠送中显示这块 -->
	<div class="bg_white receive">
    	<div class="title">领取情况(共<?php echo $total?>份，已领取<?php echo $get_nums?>份)：</div>
        <?php if($get_nums > 0):?><div class="content"><?php foreach($order_items as $item):?>
            <div class="item">
                <div class="itemimg"><img src="<?php echo $item['gs_logo']?>" /></div>
            	<div class="float_r"><?php echo $item['get_time']?></div>
                <div><?php echo $item['nickname']?></div>
            	<div class="float_r"><?php echo $item['nums']?>份</div>
                <div><?php echo $item['gs_name'] ?></div>
            </div><?php endforeach;?>
         </div><?php endif;?>
         <?php if($total > $get_nums):?><div class="title blue center" onclick="recy()">收回未领取心意</div><?php endif;?>
    </div>
<!-- end -->
<?php endif;?>

</div>
</body>
</html>
<script>
function recy(){
	$.getJSON("<?php echo site_url('mall/wap/recy_share/'.$share_code)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",function(data){
		if(data.errmsg == 'ok'){
			alert('心意已经收回');
			window.location.href="<?php echo site_url('mall/wap/mail_order/'.$order_infos['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>";

		} else {
			alert('心意收回失败，请重试...');
		}
	});
}
</script>