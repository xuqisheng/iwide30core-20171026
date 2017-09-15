<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<style>
.unit:after{content:"分";/*单位填充*/}  
</style>
<input type="hidden" id="off" name="off" value='<?php echo $nums;?>' />
<input type="hidden" id="num" name="num" value='<?php echo $nums;?>' />
<input type="hidden" id="hotel_id" name="hotel_id" value='<?php echo $hotel_id;?>' />
<div class="list_style_1">
	<div class="boxflex">
        <div><div class="circle_percent bg_main"> 
            <div class="imgpercent_left"><div></div></div>
            <div class="imgpercent_right"><div></div></div>
            <div class="curpercent unit h22"><?php echo $t_t['comment_score'];?></div> 
        </div></div>
        <div>
        	<p><?php if(isset($comment_config->clean_score)){ echo $comment_config->clean_score;}else{ echo "卫生";}?> <span class="color_main unit"><?php if(isset($t_t['clean_score'])){echo $t_t['clean_score'];}else{ echo 0;}?></span></p>
            <p><?php if(isset($comment_config->net_score)){ echo $comment_config->net_score;}else{ echo "网络";}?> <span class="color_main unit"><?php if(isset($t_t['net_score'])){echo $t_t['net_score'];}else{ echo 0;}?></span></p>
        </div>
        <div>
        	<p><?php if(isset($comment_config->facilities_score)){ echo $comment_config->facilities_score;}else{ echo "设施";}?> <span class="color_main unit"><?php if(isset($t_t['facilities_score'])){echo $t_t['facilities_score'];}else{ echo 0;}?></span></p>
            <p><?php if(isset($comment_config->service_score)){ echo $comment_config->service_score;}else{ echo "服务";}?> <span class="color_main unit"><?php if(isset($t_t['service_score'])){echo $t_t['service_score'];}else{ echo 0;}?></span></p>
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
    <div class="foot_fixed">
        <a class="bg_main center pad10 submit_btn" href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$_GET['h']))?>">马上订房</a>
    </div>
</div>
<!--div class="middle hide">来自<?php echo $t_t['score_count'];?>人的打分</div-->

<section class="apply_list">
	<?php if(!empty($comments)){foreach($comments as $c){if((!empty($c['content']) && isset($c['type']) && $c['type']=='user') && ($c['status']==1 || $c['openid']==$member->open_id)){?>
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
    	<div>暂无评论~<a href="<?php echo Hotel_base::inst()->get_url("INDEX")?>" class=" color_main">快来下单体验吧！</a></div>
    </div>
    <?php }?>
</section>
</body>
<script>
var loadtimes = 1;
var isload =false;
$(function(){	
	var num = <?php echo $t_t['comment_score']/5*100;?> * 3.6;  //  $t_t['comment_score']/5*100  当前百分比
	if (num<=180) { $('.imgpercent_right div').css('transform', "rotate(" + num + "deg)"); } 
	else { 
		$('.imgpercent_right div').css('transform', "rotate(180deg)"); 
		$('.imgpercent_left div').css('transform', "rotate(" + (num - 180) + "deg)"); 
	};
	
})


function fill_comments(){
    var num = $('#num').val();
    var off = $('#off').val();

    $.get('<?php echo site_url('hotel/Hotel/ajax_hotel_comments').'?id='.$inter_id;?>',{
        h:$('.hotel_id').val(),
        off:off,
        num:num
    },function(data){
//        console.log(data);
        var tmp='';
        if(data.s==1){
            $.each(data.data,function(ck,cc){
//                console.log(cc);
                 if(cc.content !=''){
                    tmp+='<div class="bd_top"><div class="webkitbox justify"><div class="img"><div class="squareimg">';
                    if(cc.headimgurl !=undefined && cc.headimgurl!=''){
                        tmp+='<img src="'+cc.headimgurl+'">';
                    }
                    tmp+='</div></div><div>';

                    if(cc.nickname!=''){
                        tmp+='<p>'+cc.nickname+'</p>';
                    }else{
                        tmp+='<p>微信用户</p>';
                    }

                    tmp+='<p class="color_888 h22">'+date(cc.comment_time)+'</p></div><div>';

                    if(cc.score !=undefined){
                        tmp+='<p class="color_main unit">'+cc.score+'</p>';
                    }

                    if(cc.order_info.room_name !=undefined){
                        tmp+='<p class="color_888 h22">'+cc.order_info.room_name+'</p>';
                    }else{
                        tmp+='<p class="color_888 h22"></p>';
                    }
                    tmp+='</div></div>';
                    tmp+='<div class="discuss"><p class="martop" style="word-break:break-all">'+cc.content+'</p>';

                    tmp+='<div class="addimg martop">';

                    if(cc.images !=undefined && cc.images!=''){
                        console.log(cc.images);
                        $.each(cc.images,function(ik,ci){
                            tmp+='<div><img style="width:100%;" src="'+ci+'"></div>';
                        })
                    }
                    tmp+='</div></div>';

                    if(cc.feedback_content !=undefined){
                        tmp+='<div class="bg_F3F4F8 pad3 h22 martop" style="text-align:justify;word-break:break-all"><b>酒店回复：</b>'+cc.feedback_content+'</div>';
                    }
                    tmp+='</div>';
                 }
            })
            $('#off').val(parseInt(off)+parseInt($('#num').val()));
            $('.apply_list').append(tmp);
        }
//        removeload();
        isload = false;
    },'json');


}


function date(time){

    var date = new Date(parseInt(time)*1000);
    Y = date.getFullYear() + '-';
    M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    D = (date.getDate() < 10 ? '0'+(date.getDate()) : date.getDate()) + ' ';
    h = (date.getHours() < 10 ? '0'+(date.getHours()) : date.getHours()) + ':';
    m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes());


    return (Y+M+D+h+m);

}

var  startX ,startY;
$(document).bind('touchstart',function(e){
    startX = e.originalEvent.changedTouches[0].pageX,
        startY = e.originalEvent.changedTouches[0].pageY;
});
$(document).on('touchmove',function(e){
    endX = e.originalEvent.changedTouches[0].pageX,
    endY = e.originalEvent.changedTouches[0].pageY;
    //获取滑动距离
    distanceX = endX-startX;
    distanceY = endY-startY;
    //判断滑动方向
    /*if(Math.abs(distanceX)>Math.abs(distanceY) && distanceX>0){
     //alert('往右滑动');
     }else if(Math.abs(distanceX)>Math.abs(distanceY) && distanceX<0){
     //alert('往左滑动');
     }else if(Math.abs(distanceX)<Math.abs(distanceY) && distanceY<0){
     //alert('往上滑动');
     }else if(Math.abs(distanceX)<Math.abs(distanceY) && distanceY>0){
     //alert('往下滑动');
     }
     */
    if(distanceY<0&&($(document).height()-$(window).height())*0.8<=$(document).scrollTop()){
        if (!isload){
//            e.preventDefault();
            fill_comments();
            //isfirst = true;
            isload  = true;
        }
        else{
//            showload();
            //isfirst = false;
        }
    }
})


</script>
</html>
