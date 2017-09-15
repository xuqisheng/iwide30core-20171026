<?php if(!empty($result)){ foreach($result as $r){?>
<div class="results_wrapper_rows"  tmp="<?php echo $r->hotel_id?>"  onclick="go_hotel('<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$r->hotel_id)).$exe_param;?>')">
        <?php if(empty($r->intro_img) || $r->intro_img ==''){ ?>
        <span class="default_sresult_img"></span>
        <?php }else{ ?>
        <div class="img">
            <div class="squareimg">
                <img src="<?php echo $r->intro_img;?>" />
            </div>
        </div>
         <?php }?>
        <div class="imgtitle">
                    <div class="float results_address">
                        <div class="clearfix mar_b10">
                            <?php if(!empty($r->comment_data)){?>
                            <p class="results_score float mar_r10"><span class="hotel_list_letter"><?php echo $r->comment_data['comment_score']?></span></p>
                            <?php }?>
                            <p class="txtclip h30"><?php echo $r->name;?></p>
                        </div>
                        <div class="clearfix h24 color3 spacing1">
                            <?php if(isset($r->distance)){ if(!isset($landmark)){ ?>
                            <p class="float mar_r10">距您<?php echo round($r->distance,1);?>公里</p>
                            <p class="results_address_line float mar_r10"></p>
                            <?php }else{  ?>
                            <p class="float mar_r10">距<?php echo $landmark.round($r->distance,1);?>公里</p>
                            <p class="results_address_line float mar_r10"></p>
                            <?php  }}?>
                            <?php if(!empty($r->address)){?>
                            <p class="txtclip"><?php echo $r->address;?></p>
                            <?php }?>
                        </div>
                    </div>
                    <div class="floatr color1">
                        <?php if(!empty($r->lowest)){?>
                            <?php if($r->lowest>=0){?>
                        <span class="iconfont h42 hotel_list_ico">&#xFFE5;</span>
                        <span id="lowest_p_<?php echo $r->hotel_id;?>" class="iconfont h60 sresult_price"><?php echo intval($r->lowest);?></span>
                        <span class="h22 hotel_list_qi">起</span>
                         <?php }else{?>>
                            <span id="lowest_p_<?php echo $r->hotel_id;?>" class="h24">满房</span>
                         <?php }?>
                         <?php }else{?>
                            <span id="lowest_p_<?php echo $r->hotel_id;?>" class="h24">暂无价格</span>
                         <?php }?>
                    </div>
                </div>
        <!-- <div class="results_identification h26 iconfont">特&nbsp;惠</div> -->
</div>
<?php }?>
<?php }?>