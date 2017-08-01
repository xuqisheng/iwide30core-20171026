
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<title></title>
</head>
<body>
<ul class="ui_tab">
	<li class="cur">所有礼品</li>
	<li>送出的礼品</li>
	<li>收到的礼品</li>
</ul>

<div class="page">
<?php 
//送出的礼品
foreach($orders as $order){
	if( isset($order['items'][0]) //&& $order['items'][0]['status'] == $items_model::STATUS_DEFAULT 
		//&& $order['items'][0]['openid'] == $order['items'][0]['get_openid'] //目前归属人==原始人购买人
		//&& ( $my_openid== $order['items'][0]['openid'] || $my_openid== $order['items'][0]['get_openid'] )
	) {
?>
	<div class="orderlist">
		<div class="ordertitle bg_white">
			<span class="float_r"><?php echo $order['order_time']?></span>
			<span>订单号：<?php echo $order['out_order_id']?></span>
		</div>
		<a class="content" href="<?php echo site_url('mall/wap/record/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" style="display:block">
		<?php $preid=-1;$count=0; foreach ($order['items'] as $item){
			if($item['gs_id'] != $preid){
				$count+=$item['nums'];	//累计显示多个一样单品
				$preid=$item['gs_id']; 
		?>
			<div class="item">
				<div class="itemimg"><img src="<?php echo $item['gs_logo']?>"></div>
				<div class="hotelname"><?php echo $item['gs_name']?></div>
				<div class="desc gray"><?php echo $item['gs_desc']?></div>
				<div style="margin-top:3%">
					<span class="ui_price color"><?php echo $item['promote_price']?></span>
					<span class="count gray"><?php echo $item['nums']?></span>
				</div>
			</div>
	<?php }} ?>
		</a>
		<div class="orderfoot bg_white">
			<span class="blue float"><a href="<?php echo site_url('mall/wap/mail_order/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"></a>
			<?php //echo ( $order['status']==$orders_model::STATUS_PROCESSING )? '处理中': '未处理'; ?>
			</span>
			<span style="padding-right:3%;">共<?php echo $count?>件商品  合计￥<?php echo $order['total_fee']?>  (含邮寄费)</span>
			<div class="showdetail">
				<?php if( $order['openid']== $my_openid): //对于自己 ?>
    				<?php  if( $order['status']==$orders_model::STATUS_PROCESSING && $order['items'][0]['openid']!= $order['items'][0]['get_openid']): echo '已赠送'; 
        			elseif( in_array($order['order_id'], $my_gift_order2) || $order['items'][0]['openid']!= $order['items'][0]['get_openid'] ):  echo '已被领取'; 
        			else: ?>
    				    <a href="<?php echo site_url('mall/wap/record/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">马上赠送</a>
				    <?php endif; ?>
				    
				<?php else: //对应收礼人   ?>
				    <?php if( $order['status']==$orders_model::STATUS_DEFAULT ): ?>
				        <a href="<?php echo site_url('mall/wap/opengift/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">未领取</a>
				    <?php else: ?>
				        <a href="<?php echo site_url('mall/wap/opengift/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">已领取</a>
				    <?php endif; ?>
			    <?php endif; ?>
			</div>
		</div>
	</div><?php 
	} 
} ?>
</div>

<div class="page">
<?php 
//收到的礼品
foreach($my_gift_order as $order){
?>
	<div class="orderlist">
		<div class="ordertitle bg_white">
			<span class="float_r"><?php echo $order['order_time']?></span>
			<span>订单号：<?php echo $order['out_order_id']?></span>
		</div>
		<a class="content" href="<?php echo site_url('mall/wap/opengift/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" style="display:block">
		
		<?php $preid=-1;$count=0; foreach ($order['items'] as $item){if($item['gs_id'] != $preid){$count+=$item['nums'];$preid=$item['gs_id']; ?>
			<div class="item">
				<div class="itemimg"><img src="<?php echo $goods[$item['gs_id']]['gs_logo']; ?>"></div>
				<div class="hotelname"><?php echo $item['gs_name']; ?></div>
				<div class="desc gray"><?php echo $goods[$item['gs_id']]['gs_desc']; ?></div>
				<div style="margin-top:3%">
					<span class="ui_price color"><?php echo $item['promote_price']?></span>
					<span class="count gray"><?php echo $item['nums']?></span>
				</div>
			</div><?php }}?>
		</a>
		<div class="orderfoot bg_white">
			<span class="color float"><php //echo '已处理'; ?></span>
			<span style="padding-right:3%;">共<?php echo $count?>件商品  合计￥<?php echo $order['total_fee']?>  (含邮寄费)</span>
			<div class="showdetail">
				<?php if( $order['openid']== $my_openid): //对于自己 ?>
    				<?php  if( $order['status']==$orders_model::STATUS_PROCESSING ): echo '已赠送'; 
        			elseif( in_array($order['order_id'], $my_gift_order2) ):  echo '已被领取'; 
        			else: ?>
    				    <a href="<?php echo site_url('mall/wap/record/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">马上赠送</a>
				    <?php endif; ?>
				    
				<?php else: //对应收礼人   ?>
				    <?php if( $order['status']==$orders_model::STATUS_DEFAULT ): ?>
				        <a href="<?php echo site_url('mall/wap/opengift/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">未领取</a>
				    <?php else: ?>
				        <a href="<?php echo site_url('mall/wap/opengift/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">已领取</a>
				    <?php endif; ?>
			    <?php endif; ?>
			</div>
		</div>
	</div><?php 
} ?>
</div>
</body>
<script>
$(function(){
	$('.ui_tab li').click(function(){
		var _index=$(this).index();
		$(this).addClass('cur');
		$(this).siblings().removeClass('cur');
		if( _index != 0 ){
			$('.page').eq(_index-1).show();
			$('.page').eq(_index-1).siblings('.page').hide();
		} else{
			$('.page').show();			
		}
	});
})
</script>
</html>
