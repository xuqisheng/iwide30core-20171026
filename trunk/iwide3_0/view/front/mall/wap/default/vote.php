<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<title>收到的礼物</title>
<link href="<?php echo base_url('public/mall/multi/style/new_add_vote.css')?>" rel="stylesheet">
</head>
<body>
<div class="head">
	<div class="main">
        <!-- <div class="tosend hide">赠送</div> -->
		<div class="hotel">
			<img src="<?php echo $hotel['intro_img']?>"/>
			<?php echo $hotel['name']; ?>
		</div>
        <div class="moon_name">
		<?php if( count($orders['items'])>1 ): $num=0;
		foreach($orders['items'] as $v ):
			$num+= $v['nums'];
		endforeach;
		?>
			<?php echo $orders['items'][0]['gs_name']. '等商品 '. $num. ' 份' ?>
		<?php else: ?>
			<?php echo $orders['items'][0]['gs_name']. $orders['items'][0]['nums']. $orders['items'][0]['gs_unit'] ?>
		<?php endif; ?>
		</div>
        <div>来自<?php echo $details['nickname']?></div>
    </div>
</div>

<div class="erweima">
	<img src="<?php if($orders['qrcode_url']) echo $orders['qrcode_url']; else echo $qrcode; ?>" />
	<div><?php echo $orders['out_trade_no']?></div>
    <div>向店员出示二维码</div>
</div>
<div class="btn_list border">
	<!-- <div class="help item">使用说明</div>
	<a href="<?php echo site_url('mall/wap/stores/')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>" class="item">适用门店</a> -->
	<a href="<?php echo site_url('mall/wap/mail_order/'.$orders['order_id'])?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="item">立即使用<span>领取或送朋友</span></a>
</div>


<div class="btn_list border">
	<a href="<?php echo site_url('mall/wap/index/')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>" class="item">我也要送</a>
</div>

<div class="pull pullhelp" style=" background:#f8f8f8; color:#000;display:none">
    <p>使用说明</p>
</div>
</body>
<script>
$(function(){
	$('.help').click(function(){
		toshow($('.pullhelp'));
	})
})
</script>
</html>
