<?php include 'header.php'?>
<header>
	<?php if($order['handled']==0){?>
    <div class="pad3 bg_fff">
        <div class="h30">订单提交成功</div>
        <div class="color_main h22">酒店已收到您的订单，请您在预订时间内抵达酒店出示您的证件办理入住手续。</div>
    </div>
    <?php }?>
    <?php if(!empty($order_sequence)){?>
    <ul class="pad10 bg_fff webkitbox h24 center bd_top bookstatus">
    	<li class="statuslist">
            <div class="bg_main"><hr class="color_main" /></div>
            <div class="pad2">提交订单</div>
            <div class="h18 color_888"><?php echo date('m.d H:i',$order['order_time']);?></div>
        </li>
        <?php if(!empty($order_sequence['before'])){foreach($order_sequence['before'] as $sb){?>
    	<li class="statuslist">
            <div class="bg_main"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $sb;?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
        <?php }}?>
        <li class="statuslist">
            <div class="bg_main active"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $order_sequence['cur'];?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
           <?php if(!empty($order_sequence['after'])){foreach($order_sequence['after'] as $sa){?>
  		<li class="statuslist">
            <div class="bg_main not"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $sa;?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
        <?php }}?>
    </ul>
    <?php }?>
</header>
<section class="bg_fff martop center">
	<div class="pad10 bd_bottom h24 color_888">
        <div class="h32 color_555"><?php echo $order['hname']?></div>
        <div class="martop"><?php echo $order['first_detail']['roomname'];?>-<?php echo $order['first_detail']['price_code_name'];?></div>
        <div><?php if(!empty($first_room['imgs']['hotel_room_service'])) foreach($first_room['imgs']['hotel_room_service'] as $hs){ ?><?php echo $hs['info']; ?>&nbsp;<?php }?></div>
    </div>
    <div class="pad3"  style="width:90%;margin:auto">
    	<div class="webkitbox pad3">
            <div><div class="h20 color_888">入住</div><div><?php echo date('m月d日',strtotime($order['startdate']));?></div></div>
            <div class="bd_bottom h24" style="width:5rem;border-color:#555">共<?php if($order['price_type'] != 'athour'){?><?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚/<?php }?><?php echo $order['roomnums'];?>晚</div>
            <div><div class="h20 color_888">离店</div><div><?php echo date('m月d日',strtotime($order['enddate']));?></div></div>
        </div>
	<?php if($order['price_type'] != 'athour'){?>
        	<div class="h24 pad8">房间保留至<?php echo $order['holdtime'];?></div>
	<?php }?>
        <div class="h24 color_888">地址：<?php echo $order['haddress'];?></div>
    </div>
    <div class="webkitbox bd_top pad3 h24">
        <a href="#" onclick="tonavigate(<?php echo $order['latitude'];?>,<?php echo $order['longitude'];?>,'<?php echo $order['hname'];?>','<?php echo $order['haddress'];?>')" class="pad2 color_main">路线导航</a>
        <?php if (!empty($hotel['arounds'])){?>
       		<a href="<?php echo site_url('hotel/hotel/arounds').'?id='.$inter_id.'&h='.$order['hotel_id'];?>" class="pad2 bd_left color_main">酒店周边</a>
        <?php }else{?>
        	<a href="http://cps.dianping.com/mm/weixin/home?showwxpaytitle=1&utm_source=card" class="pad2 bd_left color_main">酒店周边</a>
        <?php }?>
        <a href="tel:<?php echo $order['htel'];?>" class="pad2 bd_left color_main">联系酒店</a>
    </div>
</section>

<section class="pad3 bg_fff h20 color_888 martop">
	<div class="h30 color_555">订单信息</div>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单编号</div>
        <div><?php echo $order['show_orderid'];?></div>
    </div>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单总价</div>
        <div  class="y color_main"><?php echo $order['price'];?></div>
    </div>
	<div class="webkitbox martop input_item">
        <div class="input_item">支付类型</div>
        <div><?php echo $status_des['PAY_WAY'][$order['paytype']];?></div>
    </div>
<?php if($order['paytype']=='weixin'){?>
    <div class="webkitbox martop input_item">
        <div class="input_item">支付状态</div>
        <div><?php if($order['paid']==1){?>已支付<?php }else{?>未支付<?php }?></div>
    </div>
<?php }?>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单状态</div>
        <div><?php echo $order['status_des'];?></div>
    </div>
    <?php if(!empty($not_same)){ foreach($order['order_details'] as $k=>$od) {?>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单<?php echo ($k+1);?><</div>
        <div><?php echo $status_des['HOTEL_ORDER_STATUS'][$od['istatus']];?></div>
    </div>
    <?php }}?>
</section>
<div style="padding-top:4rem">
    <div class="bottomfixed webkitbox bg_fff bdtop center">
    <?php if($can_cancel==1){?>
    	<div class="pad10" onclick="cancel_order()">取消订单</div><!-- 在线支付不可取消 无取消订单按钮 -->
    	<?php }else if($can_comment==1){?>
		<a class="pad10" href='<?php echo site_url('hotel/hotel/to_comment'); ?>?id=<?php echo $inter_id; ?>&oid=<?php echo $order['id']; ?>'>评价</a>
		<?php }?>
		<?php if($re_pay==1){?>
		 <a href="<?php echo site_url('wxpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main">立即支付</a>
		<?php }else{?>
        <a href="<?php echo site_url('hotel/hotel/index'); ?>?id=<?php echo $inter_id?>&h=<?php echo $order['hotel_id']?>&type=<?php echo $order['price_type']?>" class="pad10 bg_main">再次预订</a>
        <?php }?>
    </div>
</div>
</body>
<script>
function cancel_order(){
	if(confirm('您确定要取消吗？')){
		pageloading();
		$.get('/index.php/hotel/hotel/cancel_main_order?id=<?php echo $inter_id;?>',{
			oid:'<?php echo $order['orderid']?>'
		},function(data){
			removeload();
			if(data.s==1){
				$.MsgBox.Alert(data.errmsg,function(){
					location.reload();
				});
			}
			else{
				$.MsgBox.Alert(data.errmsg);
			}
			
		},'json')
	}
}
</script>
</html>
