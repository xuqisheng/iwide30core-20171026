<?php include 'header.php'?>

<header style="padding-top:37px">
	<div class="headfixed webkitbox center pad3 bg_fff bd_bottom">
    	<a href="<?php echo Hotel_base::inst()->get_url("MYORDER",array('hl'=>0))?>" class="<?php if($handled===0){?>color_main<?php }?>">未完结订单</a>
    	<a href="<?php echo Hotel_base::inst()->get_url("MYORDER")?>" class="bd_left <?php if($handled===null){?>color_main<?php }?>">所有订单</a>
    </div>
</header>
<div class="allorder_list pad3">
<?php if(!empty($orders)){foreach($orders as $o){ ?>
	<div class="item">
    	<div class="webkitbox justify h24">
        	<div><em class="iconfont color_main">&#x2f;</em> <?php echo $o['hname'];?></div>
            <div class="<?php if(0){echo 'color_link';}?>"><?php echo $o['status_des'];?></div>
        </div>
        <div class="content h20 bd_top martop">
        	<div class="h24" style="padding:10px 0"><?php if(isset($o['first_detail']['roomname'])){echo $o['first_detail']['roomname'];}?> - <?php echo $o['hname'];?></div>
            <div class="color_main h36 y" style="float:right"><?php echo $o['price'];?></div>
            <div class="color_888">入住时间：<?php echo date('Y.m.d',strtotime($o['startdate']));?></div>
			<div class="color_888">最晚到店时间：<?php echo $o['holdtime'];?></div>
        </div>
        <div class="webkitbox justify h20 martop overflow">
        	<?php if($o['status']==3&&!empty($o['can_comment'])){ ?><div><a href="<?php echo Hotel_base::inst()->get_url("TO_COMMENT",array('oid'=>$o['id']))?>" class="btn_void xs color_888">评价</a></div> <?php }?>
        	<div><a href="<?php echo Hotel_base::inst()->get_url("ORDERDETAIL",array('oid'=>$o['id']))?>" class="btn_void xs color_888">订单详情</a></div>
        </div>
    </div>
<?php }}?>
</div>
</body>
<script>
$(function(){
	$('.headfixed div').click(function(){
		$(this).addClass('color_main').siblings().removeClass('color_main');
		//location.replace("");
	})
});
</script>
</html>
