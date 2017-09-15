<?php include 'header.php'?>
<div class="list_style bd">
	<div style="display:block">
        <div><?php echo $hotel['name']?></div>
        <?php if(!empty($hotel['star'])&&$hotel['star']!=99){?>
        <div class="color_888 h22" id='star'><?php echo $hotel['star'];?></div>
        <?php }?>
    </div>
    <div><?php echo $hotel['short_intro'];?></div>
    <div style="display:block">
        <div>酒店设施</div>
        <div>
        <?php if(!empty($hotel['imgs']['hotel_service'])) foreach($hotel['imgs']['hotel_service'] as $hs){?>
            <span style="width:30%; display:inline-block"><em class="iconfont"><?php echo $hs['image_url'];?></em> <span class="h22"><?php echo $hs['info'];?></span></span>
            <?php }?>
        </div>
    </div>
</div>
<div class="whiteblock bd">
    <div>酒店介绍</div>
    <div class="hotel_intro h22"><?php echo nl2br($hotel['intro'])?></div>
</div>
<div class="list_style bd martop">
	<?php if (!empty($hotel['arounds'])){?>
   	 	<a href="<?php echo Hotel_base::inst()->get_url("AROUNDS",array('h'=>$hotel['hotel_id']))?>"><span>酒店周边</span></a>
    <?php }else{?>
    	<a href="http://cps.dianping.com/mm/weixin/home?showwxpaytitle=1&utm_source=card"><span>酒店周边</span></a>
    <?php }?>
	    <a href="tel:<?php echo $hotel['tel']?>">酒店电话：<?php echo $hotel['tel']?></a>
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
