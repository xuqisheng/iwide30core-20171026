<?php include 'header.php'?>
<?php echo referurl('css','tab_list.css',2,$media_path) ?>
<header>
	<div class="headfixed ui_tab">
    	<div class="all_order <?php if($handled===null){?>cur<?php }?>">所有订单</div>
    	<div class="stay_in <?php if($handled===0){?>cur<?php }?>">未完结订单</div>
    </div>
</header>
<div class="allorder_list">
<?php if(!empty($orders)){foreach($orders as $o){?>
	<a class="item" href="orderdetail?id=<?php echo $inter_id;?>&oid=<?php echo $o['id'];?>">
    	<div class="title">
        	<em class="iconfont ui_color">&#x2f;</em>
            <span><?php echo $o['hname'];?></span>
            <span><?php  echo $o['status_des'];?></span>
        </div>
        <div class="content">
        	<div class="middle name"><?php echo $o['first_detail']['roomname'];?>-<?php echo $o['hname'];?></div>
            <div class="ui_color ui_price float_r"><?php echo $o['price'];?></div>
            <div class="normal ui_color_gray">入住时间：<?php echo date('Y.m.d',strtotime($o['startdate']));?></div>
			<div class="normal ui_color_gray">最晚到店时间：<?php echo $o['holdtime'];?></div>
        </div>
        <div class="normal foot">
        	<span class=" ui_color_gray">订单详情</span>
        </div>
    </a>
    <?php }}else{?>
    <?php }?>
</div>
</body>
<script>
$(function(){
	
	$('.ui_tab div').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		if ( $(this).index()==1){
			location.replace("<?php echo site_url('hotel/hotel/myorder').'?id='.$inter_id.'&hl=0'?>");
		}
		else if ( $(this).index()==0){
			location.replace("<?php echo site_url('hotel/hotel/myorder').'?id='.$inter_id?>");
		}
	})
});
</script>
</html>
