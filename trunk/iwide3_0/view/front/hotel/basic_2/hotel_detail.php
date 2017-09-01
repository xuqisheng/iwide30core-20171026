<?php include 'header.php'?>
<?php echo referurl('css','hotel_detail.css',1,$media_path) ?>
<header class="list_style">
	<div class="item">
        <div class="middle"><?php echo $hotel['name']?></div>
        <?php if(!empty($hotel['star'])&&$hotel['star']!=99){?>
        <div class="normal ui_color_gray" id='star'><?php echo $hotel['star'];?></div>
        <?php }?>
    </div>
    <div class="item middle"><?php echo $hotel['short_intro'];?></div>
    <div class="item">
        <div class="middle">酒店设施</div>
        <ul class="sever">
        <?php if(!empty($hotel['imgs']['hotel_service'])) foreach($hotel['imgs']['hotel_service'] as $hs){?>
            <li><em class="iconfont"><?php echo $hs['image_url'];?></em> <span class="normal"><?php echo $hs['info'];?></span></li>
            <?php }?>
        </ul>
    </div>
</header>
<div class="list_style">
    <div class="item hotel_intro">
    	<div class="middle">酒店介绍</div>
        <div class="normal content"><?php echo nl2br($hotel['intro'])?></div>
    </div>
</div>
<div class="list_style">
    <a class="item ui_btn_block" href="<?php if (!empty($hotel['arounds'])){?><?php echo site_url('hotel/hotel/arounds').'?id='.$inter_id.'&h='.$hotel['hotel_id']; }else{?>http://cps.dianping.com/mm/weixin/home?showwxpaytitle=1&utm_source=card<?php }?>"><span class="middle">酒店周边</span></a>
    <a class="item middle" href="tel:<?php echo $hotel['tel']?>">酒店电话：<?php echo $hotel['tel']?></a>
</div>
</body>
<script type="text/javascript">
var star={'1':'一','2':'二','3':'三','4':'四','5':'五','6':'六','7':'七','8':'八','9':'九',};
$(function() {
	s=star[$('#star').html()];
	if(s!=undefined&&s!=99)
		$('#star').html(s+'星级');
});
</script>
</html>
