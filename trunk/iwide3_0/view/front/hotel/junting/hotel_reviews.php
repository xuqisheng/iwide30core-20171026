<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<style>
.unit:after{content:"分";/*单位填充*/}  
</style>
<div class="list_style_1">
	<div class="boxflex">
        <div><div class="circle_percent bg_main"> 
            <div class="imgpercent_left"><div></div></div>
            <div class="imgpercent_right"><div></div></div>
            <div class="curpercent unit h22"><?php echo $t_t['comment_score'];?></div> 
        </div></div>
        <div>
        	<p>卫生 <span class="color_main unit"><?php if(isset($t_t['clean_score'])){echo $t_t['clean_score'];}else{ echo 0;}?></span></p>
            <p>网络 <span class="color_main unit"><?php if(isset($t_t['net_score'])){echo $t_t['net_score'];}else{ echo 0;}?></span></p>
        </div>
        <div>
        	<p>设施 <span class="color_main unit"><?php if(isset($t_t['facilities_score'])){echo $t_t['facilities_score'];}else{ echo 0;}?></span></p>
            <p>服务 <span class="color_main unit"><?php if(isset($t_t['service_score'])){echo $t_t['service_score'];}else{ echo 0;}?></span></p>
        </div>
    </div>
    <div style="padding-bottom:0">
    	<div class="h22 sum_tag">
        	<span class="btn_void">全部 (<?php echo $t_t['comment_count'];?>)</span>
        	<span class="btn_void">有图评价 (<?php if(isset($t_t['image_count'])){echo $t_t['image_count'];}else{ echo 0;}?>)</span>
            <?php
                if(isset($t_t['keyword'])){
                    foreach($t_t['keyword'] as $arr){
                        if($arr['count']!=0){
            ?>
                        <span class="btn_void"><?php echo $arr['keyword'].'('.$arr['count'].')';?></span>
            <?php
                        }
                    }
                }
            ?>
<!--        	<span class="btn_void">网络信号好 (1)</span>-->
<!--        	<span class="btn_void">客房干净 (1)</span>-->
<!--        	<span class="btn_void">位置优越</span>-->
        </div>
    </div>
</div>
<!--div class="middle hide">来自<?php echo $t_t['score_count'];?>人的打分</div-->

<section class="apply_list">
	<?php if(!empty($comments)){foreach($comments as $c){if(!empty($c['content']) && isset($c['type']) && $c['type']=='user'){?>
    <div class="bd_top">
        <div class="webkitbox justify">
            <div class="img"><div class="squareimg"><?php if(!empty($c['headimgurl'])){?><img src="<?php echo $c['headimgurl'];?>"><?php }?></div></div>
            <div>
                <p><?php echo $c['nickname'];?></p>
                <p class="color_888 h22"><?php echo date('Y-m-d H:i',$c['comment_time']);?></p>
            </div>
            <div>
				<?php if(!empty($c['score'])){?>
                <p class="color_main unit"><?php echo $c['score'];?></p>
                <?php }?>
            	<p class="color_888 h22"><?php if(!empty($c['order_info']['room_name'])) echo $c['order_info']['room_name'];?></p>
            </div>
        </div>
        <div class="discuss">
            <p class="martop" style="word-break:break-all"><?php echo $c['content'];?></p>
            <div class="addimg martop">
                <?php
                    if(isset($c['images']) && !empty($c['images'])){
                        foreach($c['images'] as $c_arr){
                            ?>
                            <div><img style="width:100%;" src="<?php echo $c_arr;?>"></div>
                        <?php
                        }
                    }
                ?>
<!--                <div><img src="/public/hotel/public/images/egimg/eg02.png"></div>-->
<!--                <div><img src="/public/hotel/public/images/egimg/eg02.png"></div>-->
            </div>
        </div>
        <?php if(isset($c['feedback_content'])){  ?>
            <div class="bg_F3F4F8 pad3 h22 martop" style="text-align:justify;word-break:break-all"><b>酒店回复：</b><?php echo $c['feedback_content'];?></div>
        <?php }?>
    </div>
    <?php }}}else{?>
	<div class="ui_none middle">
    	<div>暂无评论~<a href="<?php echo site_url('hotel/hotel/index').'?id='.$inter_id; ?>" class=" color_main">快来下单体验吧！</a></div>
    </div>
    <?php }?>
</section>
</body>
<script>
$(function(){	
	var num = <?php echo $t_t['comment_score']/5*100;?> * 3.6;  //  $t_t['comment_score']/5*100  当前百分比
	if (num<=180) { $('.imgpercent_right div').css('transform', "rotate(" + num + "deg)"); } 
	else { 
		$('.imgpercent_right div').css('transform', "rotate(180deg)"); 
		$('.imgpercent_left div').css('transform', "rotate(" + (num - 180) + "deg)"); 
	};
	
})
</script>
</html>
