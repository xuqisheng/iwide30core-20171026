
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<title></title>
</head>
<body>
<ul class="ui_tab">
	<li class="cur">全部订单</li>
	<li>待处理订单</li>
	<li>已处理订单</li>
</ul>

<div class="page">
<?php 
//待处理订单
foreach($orders as $order){
	//if( isset($order['items'][0]) && $order['items'][0]['status'] == $items_model::STATUS_DEFAULT 
	//	&& $order['items'][0]['openid'] == $order['items'][0]['get_openid'] //目前归属人==原始人购买人
	if( in_array($order['status'], array($orders_model::STATUS_PROCESSING, $orders_model::STATUS_DEFAULT ) ) 
	) {
?>
	<div class="orderlist">
		<div class="ordertitle bg_white">
			<span class="float_r"><?php echo $order['order_time']?></span>
			<span>订单号：<?php echo $order['out_trade_no']?></span>
		</div>
		<a class="content" href="<?php echo site_url('mall/wap/order_details/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" style="display:block">
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
			<?php 
			if( $order['status']==$orders_model::STATUS_PROCESSING ) echo '处理中'; 
			else if( in_array($order['order_id'], $my_gift_order2) || $order['items'][0]['openid']!= $order['items'][0]['get_openid'] )  echo '已处理'; 
			else echo '待处理';
			//print_r( $my_gift_order2 );echo $order['order_id'];
			?>
			</span>
			<span style="padding-right:3%;">共<?php echo $count?>件商品  合计￥<?php echo $order['total_fee']?>  (含邮寄费)</span>
			<div class="showdetail">
				<a href="<?php echo site_url('mall/wap/order_details/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">查看详情</a>
			</div>
		</div>
	</div><?php 
	} 
} ?>
</div>

<div class="page">
<?php 
//已处理订单
foreach($orders as $order){
//	if( isset($order['items'][0]) && $order['items'][0]['status']!= $items_model::STATUS_DEFAULT  
/* //目前归属人 !=原始人购买人，默认前提这些主订单的openid是本人openid */
//		|| $order['items'][0]['openid'] != $order['items'][0]['get_openid'] 
/* //本人openid曾经出现在赠送日志的赠送人当中，针对a送给b，b送给c，c送给d，如何在b，c中显示该订单 */
//		|| in_array($my_openid, $my_gift_order) 
	if( in_array($order['status'], array( $orders_model::STATUS_COMPLETE) ) 
		|| in_array($my_openid, $my_gift_order1) 
	) {
?>
	<div class="orderlist">
		<div class="ordertitle bg_white">
			<span class="float_r"><?php echo $order['order_time']?></span>
			<span>订单号：<?php echo $order['out_trade_no']?></span>
		</div>
		<a class="content" href="<?php echo site_url('mall/wap/order_details/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" style="display:block">
		
		<?php $preid=-1;$count=0; foreach ($order['items'] as $item){if($item['gs_id'] != $preid){$count+=$item['nums'];$preid=$item['gs_id']; ?>
			<div class="item">
				<div class="itemimg"><img src="<?php echo $item['gs_logo']?>"></div>
				<div class="hotelname"><?php echo $item['gs_name']?></div>
				<div class="desc gray"><?php echo $item['gs_desc']?></div>
				<div style="margin-top:3%">
					<span class="ui_price color"><?php echo $item['promote_price']?></span>
					<span class="count gray"><?php echo $item['nums']?></span>
				</div>
			</div><?php }}?>
		</a>
		<div class="orderfoot bg_white">
			<span class="color float">已处理</span>
			<span style="padding-right:3%;">共<?php echo $count?>件商品  合计￥<?php echo $order['total_fee']?>  (含邮寄费)</span>
			<div class="showdetail">
				<a href="<?php echo site_url('mall/wap/order_details/'.$order['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>">查看详情</a>
			</div>
		</div>
	</div><?php 
	} 
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
