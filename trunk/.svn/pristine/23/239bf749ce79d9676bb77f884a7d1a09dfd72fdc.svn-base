<?php if(!empty($result)){ foreach($result as $r){?>
	<div onclick="go_hotel('<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$r->hotel_id)).$exe_param;?>')" tmp="<?php echo $r->hotel_id?>" class="webkitbox justify item">
    	<?php if( isset($r->is_new_open)&&$r->is_new_open == 1){?>
    	<div class="new_store">新开业</div>
    	<?php }?>
        <div class="img"><div class="squareimg"><img src="<?php echo $r->intro_img;?>" /></div></div>
        <div class="info">
            <div class="name"><?php echo $r->name;?></div>
            <?php if(!empty($r->comment_data)){?>
                <div class="h20"><?php echo $r->comment_data['comment_score'].'分/'.$r->comment_data['comment_count'].'条点评';?></div>
            <?php }?>
            
           	<?php if(!empty($r->short_intro)){?>
            <div class="h20 txtclip"><?php echo $r->short_intro;?></div>
			<?php }?>
            
            <?php if(!empty($r->characters)){?>
            <div class="h20 color_link txtclip" style="max-width:15em">
			<?php echo $r->characters;?></div><?php }?>


            <?php if(!empty($r->address)){?>
            <div class="h20"><?php echo $r->address;?></div>
			<?php }?>
            
            <?php if(isset($r->distance)){ if(!isset($landmark)){ ?>
            <div class="h20 color_999">距您<span class="color_main"><?php echo round($r->distance,1);?></span>公里</div>
            <?php }else{  ?>
            <div class="h20 color_999">距<span class="color_main"><?php echo $landmark.round($r->distance,1);?></span>公里</div>
            <?php  }}?>
        </div>
        <div class="price color_888" style="font-size:10px">
        <?php if(!empty($r->lowest)){?>
            <div class="qi">
                <span class="color_main y"></span>
                <span class="color_main h36" id="lowest_p_<?php echo $r->hotel_id;?>"><?php echo $inter_id=='a476756979'?intval($r->lowest):$r->lowest;?></span>
            </div>
         <?php }else{?>
            <div id="lowest_p_<?php echo $r->hotel_id;?>">暂无价格</div>
         <?php }?>
        </div>
    </div>
    <?php }?>
    <?php }?>