<?php include 'header.php'?>
<?php echo referurl('css','submit_results.css',1,$media_path) ?>
<header class="order_detail">
	<div class="order_status">提交成功，等待酒店审核...</div>
    <ul>
    	<li><span>订单编号</span><span><?php echo $order['orderid'];?></span></li>
        <li><span>订单总价</span><span class="ui_price"><?php echo $order['price']?></span></li>
        <li><span>支付类型</span><span><?php echo $status_des['PAY_WAY'][$order['paytype']];?></span></li>
        <li><span>订单状态</span><span><?php echo $status_des['HOTEL_ORDER_STATUS'][$order['status']];?></span></li>
            <li><span>保留时间</span><span><?php echo $order['holdtime'];?></span></li>
    </ul>
</header>
<div class="order_person">
	<div class="block_title">订单会通知以下客户</div>
    <div class="block_content">
        <span><?php echo $order['name'];?></span>
        <span><?php echo $order['tel'];?></span>
    </div>
</div>
<div class="book_intro">
	<div class="block_title">预订信息</div>
    <div class="block_content">
    	<div class="hotelname"><?php echo $order['hname']?></div>
        <ul>
            <li><span>入住房型</span><span><?php echo $order['roomname'];?></span></li>
            <li><span>入住时间</span><span><?php echo date('m月d日',strtotime($order['startdate']))?></span></li>
            <li><span>离店时间</span><span><?php echo date('m月d日',strtotime($order['enddate']))?></span></li>
            <li><span>房间数</span><span><?php echo $order['roomnums']?></span></li>
        </ul>
        <?php if(!empty($order['book_policy'])){?>
        <div class="notic">
            <div class="title">温馨提示</div>
            <div class="content">
               <p><?php echo nl2br($order['book_policy']);?></p>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<div class="footfixed">
	<a href="order_detail.html" class="footbtn">订单详情</a>
</div>
</body>
</html>
