<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('css','search_result.css',1,$media_path) ?>
<?php echo referurl('css','tab_list.css',2,$media_path) ?>
<header  style='display: none;'>
	<div class="headfixed ui_tab" >
    	<div class="ofte_go cur" tag='2'>常住酒店</div>
    	<div class="my_like" tag='1'>我的收藏</div>
    </div>
</header>
<div class="hotel_list">
	<?php if(!empty($hotels)){foreach ($hotels as $h){?>
	<div class="item">
    	<a href="<?php echo site_url('hotel/hotel/index').'?h='.$h->hotel_id.'&id='.$inter_id; ?>" class="wipe">
            <div class="ui_img_auto_cut"><img src="<?php echo $h->intro_img;?>" /></div>
            <div class="allin_box">
                <div class="name"><?php echo $h->name;?></div>
                <div class="coupon">
                   <?php if (!empty($h->lowest)){?> <div class="ui_price"><?php echo $h->lowest;?></div><?php }?>
                    <div class="backvote" style='display: none;'></div>
                </div>
                <div class="ever"><span class="ui_color_gray"><?php if(isset($h->comment_data['comment_count'])){?><span class="ui_color_gray"><?php echo $h->comment_data['comment_count'];?>条评论</span><?php }?></span></div>
                <div class="sever">
                   <?php if(!empty($h->service)){ foreach ($h->service as $rs) {?> <em class="iconfont"><?php echo $rs['image_url'];?></em><?php }}?>
                </div>
                <div class="h6 tag"><?php if(!empty($h->search_icons)){?><?php foreach ($h->search_icons as $icon){?>
              	  <img src="<?php echo $icon;?>" />
                <?php }?><?php }?></div>
        	</div>
        </a>
        <div class="delete">删除常住</div>
    </div>
    <?php }}else{?>
    <div class="ui_none middle">
    	<div>暂无记录~<a href="<?php echo $index_url; ?>" class=" ui_color">再去逛逛吧！</a></div>
    </div>
    <?php }?>
</div>

</body>
<script>
$(function(){
	
	$('.ui_tab div').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		location.replace('<?php echo site_url('hotel/hotel/my_marks?id='.$inter_id.'&mt=');?>'+$(this).attr('tag'));
	})
	var wipeleft = function(_this){
		$(_this).siblings().css({'transform':'translateX(-6em)','line-height':$(_this).siblings().height()+'px'});
		$(_this).css({'transform':'translateX(-6em)'});
	}
	var wiperight= function(_this){
		$(_this).css({'transform':'translateX(0px)'});
		$(_this).siblings().css({'transform':'translateX(0px)'});
	}
	$(".wipe").touchwipe({
		 wipeLeft: function(_this) { wipeleft(_this);},
		 wipeRight: function(_this) { wiperight(_this); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
	
	$('.delete').on('click',function(){
		var r=confirm('删除后不可恢复，确定删除？');
		if( !r ) return false;
		$(this).parent().remove();
	})
});
</script>
</html>
