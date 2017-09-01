<?php if(!empty($comments)){foreach($comments as $c){
//if(!empty($c['content'])){?>
    <div class="content">
        <div class="user">
            <span class="normal ui_color_gray" style="float:right"><?php echo date('Y-m-d',$c['comment_time']);?></span>
            <div class="big"> <?php if(!empty($c['member_level'])){?><?php echo $c['member_level'];?><?php }?>
            <?php echo $c['nickname'];?></div>
       		<div class="normal" style="float:right"></div>
            <div style="font-size:11px">
            <?php if(!empty($c['score'])){?>
            <em class="iconfont ui_color_orange" style="font-size:10px"><?php for($i=0;$i<$c['score'];$i++){echo '&#x3a;';}?></em>
<?php echo $c['score']*100/5;?>%<?php }?>
            </div>
        </div>
        <div class="discuss">
            <p class="middle"><?php echo $c['content'];?></p>
        </div>
    </div><?php //}?><?php }}?>