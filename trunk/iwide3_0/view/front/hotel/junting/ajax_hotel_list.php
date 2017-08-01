<?php if(!empty($result)){ foreach($result as $r){?>
	<div onclick="go_hotel('<?php echo site_url('hotel/hotel/index?id=').$inter_id.'&h='.$r->hotel_id;?>')" tmp="<?php echo $r->hotel_id?>" style="background-image:url('<?php echo $r->intro_img;?>')">
    	<?php if( isset($r->is_new_open)&&$r->is_new_open == 1){?>
    	<div class="new_store">新开业</div>
    	<?php }?>
        <div class="mask">
            <div class="webkitbox justify">
                <div class="info"><?php echo $r->name;?></div>
                <?php if(!empty($r->lowest)){?>
                <div class="h20" style="display:inline-block"> ¥<span class="h28" id="lowest_p_<?php echo $r->hotel_id;?>"><?php echo $inter_id=='a476756979'?intval($r->lowest):$r->lowest;?></span>起</div>
                 <?php }else{?>
                <div id="lowest_p_<?php echo $r->hotel_id;?>">暂无价格</div>
                 <?php }?>
            </div>
            <div class="h22">
            <?php if (!empty($r->comment_data)){?>
            	<span star="<?php echo empty($r->comment_data['score_count'])?5:$r->comment_data['comment_score'];?>" class="star color_fff">
                	<em class="icon"></em><em class="icon"></em><em class="icon"></em><em class="icon"></em><em class="icon"></em>
                </span>
                <span style="margin-right:10px;"><?php echo empty($r->comment_data['score_count'])?'暂无评分':$r->comment_data['comment_score'];?></span>
                <?php }?>
                <?php if(isset($r->distance)){ ?><span><?php if(!isset($landmark)){echo round($r->distance,1);}else{ echo $landmark.round($r->distance,1);}?>公里</span><?php }?>
			
            </div>
        </div>
    </div>
    <?php }?>
    <?php }?>