<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('css','hotel_reviews.css',1,$media_path) ?>
<style>
.point:after{content:"分"}
</style>
<header>
	<div class="middle">来自<?php echo $t_t['score_count'];?>人的打分</div>
    <div class="sum_star">
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    </div>
    <div class="big"><span class="point ui_color"><?php echo $t_t['comment_score'];?></span>/5.0分</div>
    <div class="ui_tab" style="display: none">
    	<div class="cur">所有评价(<?php echo $t_t['comment_count'];?>份)</div>
    	<div>有图评价(6378份)</div>
    </div>
</header>
<section class="apply_list">
	<?php if(!empty($comments)){foreach($comments as $c){if(!empty($c['content'])){?>
    <div class="content">
        <div class="user">
            <div class="userimg"><img src="<?php echo $c['headimgurl'];?>"></div>
            <span class="username"><?php echo $c['nickname'];?></span>
            <span class="level" style="display:none">银牌会员</span>
            <?php if(!empty($c['score'])){?>
            <span class="point"><?php echo $c['score'];?></span>
            <?php }?>
        </div>
        <div class="discuss">
            <p class="middle"><?php echo $c['content'];?></p>
            <div class="more" style="display:none">共<span>0</span>张&gt;</div>
            <!--<div class="imgbox">
                <div class="ui_img_auto_cut"><img src="images/egimg/eg01.png"></div>
                <div class="ui_img_auto_cut"><img src="images/egimg/eg02.png"></div>
                <div class="ui_img_auto_cut"><img src="images/egimg/eg03.png"></div>
            </div>-->
        </div>
        <div class="ui_color_gray normal ui_martop"><?php if(!empty($c['order_info']['room_name'])) echo $c['order_info']['room_name'];?>&nbsp;<?php echo date('Y-m-d H:i',$c['comment_time']);?></div>
        <div class="cus_apply" style="display:none">
            <p class="middle ui_color">酒店回复：</p>
            <p class="middle ui_color_gray">十里铂金沙滩，沙质细腻洁白，脚踩在松软的沙滩上，非常舒服呢~ 小编和小伙伴们会秉承着“热情微笑，尽善尽美”的服务理念，给客人一个完美的假期！您有空，再回来，祝您天天好心情！</p>
        </div>
    </div>
    <?php }}}else{?>
	<div class="ui_none middle">
    	<div>暂无评论~<a href="<?php echo site_url('hotel/hotel/index').'?id='.$inter_id; ?>" class=" ui_color">快来下单体验吧！</a></div>
    </div>
    <?php }?>
</section>
<!--<div class="ui_pull" style="display:none"><ul><li><img src="images/egimg/eg02.png"></li><li><img src="images/egimg/eg02.png"></li></ul></div>-->
</body>
<script>
var _cur = 0;
var time ;
var _left= 0;
$(function(){
	var curstar =function(){
		var point=$('header .point').html()*1;
		for(var i=0; i<point;i++){
			$('.ui_star').eq(i).addClass('ui_star1');
			if ( i+1!= point & i+1>point) $('.ui_star').eq(i).addClass('ui_star2');
			
		}
	}();
	
	$('.ui_tab div').click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
		if ( $(this).index()==1){
			for (var i=0;i<$('.content').length; i++){
				if ( $('.content').eq(i).find('.ui_img_auto_cut').length==0){
					$('.content').eq(i).hide();
				}
			}
		}
		else
			$('.content').show();
	})
	var ismore = function(){
		var _this=$('.imgbox');
		for (var i=0; i<_this.length; i++){
			var _l=_this.eq(i).find('div').length;
			var _T=_this.eq(i).siblings('.more');
				_T.find('span').html(_l);
			if(_l>3) _T.show();		
		}
	}
	ismore();
	var fillimg = function(_this){
		var tmp='';
		if (_this.find('div').length)
			for ( var i=0; i<_this.find('div').length;i++)
				tmp +='<li>'+_this.find('div').eq(i).html()+'</li>';
		else
			tmp = '<li>'+_this.html()+'</li>';
		$('.ui_pull').find('ul').html(tmp);
	}
	var scrollimg = function(dir){
		window.clearInterval(time);
		var _this = $('.ui_pull').find('li');
		var _w    = $(window).width();
		direction = dir ? 1 : -1;
		if(!dir){
		    if(_cur>= _this.length-1) return;
		}else{	
		    if(_cur<=0) return; 
		}
		time = window.setInterval(function(){
			if ( (_left<=_cur*_w*direction && !dir) || (Math.abs(_left)<=_cur*_w && dir) ){
				window.clearInterval(time);
				//alert((_left<_cur*_w*direction && !dir));
				//alert (_left+' '+_cur*_w );
				return;
			}
			_left +=_w/10*direction;
			$('.ui_pull ul').css('transform','translateX('+_left+'px)');
		},20);
		_cur -= direction;
	}
	var showimg = function(){
		toshow($('.ui_pull'));
		$('.ui_pull ul').css('top',($(window).height()-$('.ui_pull ul').height())/2);
		_cur = 0;
		_left= 0;
	}
	$(".ui_pull ul").touchwipe({
		 wipeLeft: function() { scrollimg(false); }, //从右往左
		 wipeRight: function() { scrollimg(true); },
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
	$('.imgbox img').click(function(){
		fillimg($(this).parent());
		showimg();
	});
	$('.more').click(function(){
		fillimg($(this).siblings('.imgbox'));
		showimg();
	});
})
</script>
</html>
