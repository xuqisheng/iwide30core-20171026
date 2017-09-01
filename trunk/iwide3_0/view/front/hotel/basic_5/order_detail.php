<?php include 'header.php'?>
<?php echo referurl('css','order_detail.css',1,$media_path) ?>
<header class="realtime_status">
	<?php if($order['handled']==0){?>
	<div class="title">下单成功</div>
    <div class="ui_color">酒店已收到您的订单。</div>
    <?php }?>
    <?php if(!empty($order_sequence)){?>
    <ul class="cur_status">
    	<li class="iscured">
            <div class="circle"><em></em></div>
            <div class="cur_status_txt">提交订单</div>
            <div class="cur_status_time"><?php echo date('m.d H:i',$order['order_time']);?></div>
        </li>
        <?php if(!empty($order_sequence['before'])){foreach($order_sequence['before'] as $sb){?>
    	<li class="iscured iscur">
            <div class="circle"><em><hr></em></div>
            <div class="cur_status_txt"><?php echo $sb;?></div>
            <div class="cur_status_time">&nbsp;</div>
        </li>
        <?php }}?>
        <li class="iscured iscur">
            <div class="circle"><em><hr></em></div>
            <div class="cur_status_txt"><?php echo $order_sequence['cur'];?></div>
            <div class="cur_status_time">&nbsp;</div>
        </li>
           <?php if(!empty($order_sequence['after'])){foreach($order_sequence['after'] as $sa){?>
  		<li>
            <div class="circle"><em><hr></em></div>
            <div class="cur_status_txt"><?php echo $sa;?></div>
            <div class="cur_status_time">&nbsp;</div>
        </li>
        <?php }}?>
    </ul>
    <?php }?>
</header>
<section class="all_message">
	<div class="big"><?php echo $order['hname']?></div>
    <div class="normal ui_color_gray"><?php echo $order['first_detail']['roomname'];?>-<?php echo $order['first_detail']['price_code_name'];?></div>
	<div class="normal ui_color_gray"><?php if(!empty($first_room['imgs']['hotel_room_service'])) foreach($first_room['imgs']['hotel_room_service'] as $hs){ ?><?php echo $hs['info']; ?>&nbsp;<?php }?></div>
    <ul class="datetime">
    	<li><p class="normal">入住</p><p class="middle"><?php echo date('m月d日',strtotime($order['startdate']));?></p></li>
        <li class="normal count">共<?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚/<?php echo $order['roomnums'];?>间</li>
        <li><p class="normal">离店</p><p class="middle"><?php echo date('m月d日',strtotime($order['enddate']));?></p></li>
        <div class="normal" style="margin-top:2%; margin-bottom:1%;">房间保留至<?php echo $order['holdtime'];?></div>
        <div class="normal ui_color_gray ">地址：<?php echo $order['haddress'];?></div>
    </ul>
    <ul class="otherway">
<!--     	<li><a href="#" class="normal ui_color">打车前往</a></li> -->
        <li><a href="#" onclick="tonavigate(<?php echo $order['latitude'];?>,<?php echo $order['longitude'];?>,'<?php echo $order['hname'];?>','<?php echo $order['haddress'];?>')" class="normal ui_color">路线导航</a></li>
        <li><a href="http://cps.dianping.com/mm/weixin/home?showwxpaytitle=1&utm_source=card" class="normal ui_color">酒店周边</a></li>
        <li><a href="tel:<?php echo $order['htel'];?>" class="normal ui_color">联系酒店</a></li>
    </ul>
</section>

<section class="order_message">
	<div class="order_status">订单信息</div>
    <ul>
    	<li><span>订单编号</span><span><?php echo $order['show_orderid'];?></span></li>
        <li><span>订单总价</span><span class="ui_price ui_color"><?php echo $order['price'];?></span></li>
        <li><span>支付类型</span><span><?php echo $status_des['PAY_WAY'][$order['paytype']];?></span></li>
        <?php if($order['paytype']=='weixin'){?>
        <li><span>支付状态</span><span><?php if($order['paid']==1){?>已支付<?php }else{?>未支付<?php }?></span></li>
        <?php }?>
        <li><span>订单状态</span><span><?php echo $order['status_des'];?></span></li>
    
    <?php if(!empty($not_same)){ foreach($order['order_details'] as $k=>$od) {?>
    	<li><span>--订单<?php echo ($k+1);?></span><span><?php echo $status_des['HOTEL_ORDER_STATUS'][$od['istatus']];?></span></li>
    <?php }}?>
    </ul>
</section>
<div style="padding-top:15%">
    <div class="footfixed">
    <?php if($can_cancel==1){?>
    	<div class="cancelbtn" onclick="cancel_order()">取消订单</div><!-- 在线支付不可取消 无取消订单按钮 -->
    	<?php }else if($can_comment==1){?>
		<div class="cancelbtn" onclick="location.href='<?php echo site_url('hotel/hotel/to_comment'); ?>?id=<?php echo $inter_id; ?>&oid=<?php echo $order['id']; ?>'">评价</div>
		<?php }?>
		<?php if($re_pay==1){?>
		 <a href="<?php echo site_url('wxpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="submit_btn">支付订单</a>
		<?php }else{?>
        <a href="<?php echo site_url('hotel/hotel/index'); ?>?id=<?php echo $inter_id?>&h=<?php echo $order['hotel_id']?>" class="submit_btn">再次预订</a>
        <?php }?>
    </div>
</div>
</body>
<script>
function cancel_order(){
	if(confirm('您确定要取消吗？')){
		pageloading('请稍候');
		$.get('/index.php/hotel/hotel/cancel_main_order?id=<?php echo $inter_id;?>',{
			oid:'<?php echo $order['orderid']?>'
		},function(data){
			$('.page_loading').remove();
			if(data.s==1){
				$.MsgBox.Alert('通知',data.errmsg,function(){
					location.reload();
				});
			}
			else{
				$.MsgBox.Alert('通知',data.errmsg);
			}
			
		},'json')
	}
}
</script>
</html>
