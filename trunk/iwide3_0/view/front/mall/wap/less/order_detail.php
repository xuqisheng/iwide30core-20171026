
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/new_add_vote.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/bill.css')?>" rel="stylesheet">
<title>订单详情</title>
</head>
<style>
.border{border-top:0; margin-top:0;}
</style>
<body>
<div class="orderlist">
    <div class="ordertitle bg_white">
        <span class="float_r"><?php echo $order['order_time'] ?></span>
        <span>订单号：<?php echo $order['out_trade_no'] ?></span>
    </div>
    <div class="content"><?php $total_qty=0; foreach ($items_mail as $v): ?>
        <div class="item">
            <div class="itemimg"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>"></div>
            <div class="hotelname"><?php echo $v['gs_name'] ?></div>
            <div class="desc gray"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
            <div style="margin-top:3%">
                <span class="ui_price color"><?php echo $v['promote_price'] ?></span>
                <span class="count gray"><?php echo $v['qty'] ?></span>
            </div>
        </div><?php $total_qty+= $v['qty']; endforeach; ?>
	</div><!-- 可邮寄产品清单结束  -->
	
	<?php if( count($items_mail)>0 ): ?>
	<div class="btn_list border">
		<?php if( !$gift_status ): ?>
			<a class="item" href="<?php echo site_url('mall/wap/mail_order/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&mail=1&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">
			<?php if($can_mail): ?>待处理 (可邮寄<?php if($can_gift) echo '、赠送'; ?>)
			<?php elseif( $v['status']==$item_model::STATUS_SHIP_PRE ): ?>已处理 (申请邮寄)
			<?php else: ?>已处理 (商家发货)
			<?php endif; ?>
		<?php endif; ?>
		</a>
	</div>
	<?php endif; ?>

    <div class="content"><?php foreach ($items_other as $v){ ?>
        <div class="item">
            <div class="itemimg"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>"></div>
            <div class="hotelname"><?php echo $v['gs_name'] ?></div>
            <div class="desc gray"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
            <div style="margin-top:3%">
                <span class="ui_price color"><?php echo $v['promote_price'] ?></span>
                <span class="count gray"><?php echo $v['qty'] ?></span>
            </div>
        </div><?php $total_qty+= $v['qty']; }?>
	</div><!-- 不可邮寄产品清单结束  -->

	<?php if( count($items_other)>0 ): ?>
	<div class="btn_list border">
		<?php if( !$gift_status ): ?>
			<a class="item" href="<?php echo site_url('mall/wap/mail_order/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&mail=2&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">
			<?php if($can_pickup): ?>待处理 (可自提<?php if($can_gift) echo '、赠送'; ?>)<?php else: ?>已处理 (自提)<?php endif; ?>
			</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>

    <div class="orderfoot bg_white">
        <span>共<?php echo $total_qty ?>件商品  合计￥<?php echo $order['total_fee']?>  (含邮寄费)</span>&nbsp;&nbsp;
    </div>

</div>

<?php if($gift_status!=''): //显示当前的赠送状态：赠送中/已赠送； ?> 
<div class="erweima ui_border bg_white overflow">
   <div class="gray normal"><?php echo $gift_status ?></div>
</div>
<?php endif; ?>

</body>
</html>