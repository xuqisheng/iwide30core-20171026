<?php if(!empty($result)){ foreach($result as $r){?>
	<a onclick="go_hotel('<?php echo site_url('hotel/hotel/index?id=').$inter_id.'&h='.$r->hotel_id;?>')" tmp="<?php echo $r->hotel_id?>" href="javascript:void(0);" class="item">
    	<?php if( isset($r->is_new_open)&&$r->is_new_open == 1){?>
    	<div class="new_store">新开业</div>
    	<?php }?>
        <div class="ui_img_auto_cut"><img src="<?php echo $r->intro_img?>" /></div>
        <div class="allin_box">
            <div class="name"><span class="txtclip"><?php echo $r->name;?></span><?php if( isset($r->is_tuan)&&$r->is_tuan == 1){?><img src="/public/hotel/su8/images/tag01.jpg"><?php }?></div>
            <div class="coupon">
            <?php if(!empty($r->lowest)){?>
            	<div id="lowest_p_<?php echo $r->hotel_id;?>" class="ui_price"><b><?php echo $r->lowest;?></b></div>
            	<div class="h6 tag">
                <?php if( isset($r->is_balance_pay)&&$r->is_balance_pay == 1){?><img src="/public/hotel/su8/images/tag02.png"><?php }?>
                </div>
            	<?php }else{?>
            	<div id="lowest_p_<?php echo $r->hotel_id;?>" class="h6 ui_color_gray">暂无价格</div>
            	<?php }?>
                <div class="h6 ui_color_gray"><?php if(isset($r->distance)){?>距离<span><?php echo $r->distance;?></span>Km<?php }?></div>
            </div>
            <?php if(!empty($r->comment_data)){?>
            <div class="ever"><span class="ui_color"><?php if(isset($r->comment_data['good_rate'])&&$r->comment_data['good_rate']!='-1'){ ?><?php echo $r->comment_data['good_rate'];?>%好评</span>/<?php }?>
<?php if(isset($r->comment_data['comment_count'])){?><span class="ui_color_gray"><?php echo $r->comment_data['comment_count'];?>条评论</span><?php }?></div>
            <?php }?>
            <div class="sever" style="padding-bottom:1.5%">
            	<?php if(!empty($r->service)){ foreach ($r->service as $rs) {?> <em class="iconfont"><?php echo $rs['image_url'];?></em><?php }}?>	
                <em>&nbsp;</em>
            </div>
           <?php if(!empty($r->search_icons)){?>
            <div class="h6 tag">
            	<?php foreach ($r->search_icons as $icon){?>
              	  <img src="<?php echo $icon;?>" />
                <?php }?>
            </div>
            <?php }?>
        </div>
    </a>
    <?php }?>
    <?php }?>