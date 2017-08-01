<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('css','hotel_reviews.css',1,$media_path) ?>
<header>
	<!--div class="middle">来自<?php echo $t_t['score_count'];?>人的打分</div-->
	<div style="color:#ff950d; font-size:26px" id='point'><?php echo $t_t['good_rate'];?>%</div>
    <div class="sum_star">
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    	<em class="ui_star"></em>
    </div>
    <div class="big" style='display: none;'><span class="point ui_color"><?php echo $t_t['comment_score'];?></span>/5.0分</div>
    <div class="ui_tab" style='display: none;'>
    	<div class="cur">所有评价(<?php echo $t_t['comment_count'];?>份)</div>
    	<div>有图评价(6378份)</div>
    </div>
</header>
<input type="hidden" id="off" name="off" value='20' />
<input type="hidden" id="num" name="num" value='20' />
<section class="apply_list" id='apply_list'>
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
            <!--div class="more" style="display:none">共<span>0</span>张&gt;</div>
            <<div class="imgbox">
                <div class="ui_img_auto_cut"><img src="images/egimg/eg01.png"></div>
                <div class="ui_img_auto_cut"><img src="images/egimg/eg02.png"></div>
                <div class="ui_img_auto_cut"><img src="images/egimg/eg03.png"></div>
            </div>-->
        </div>
        <?php if (!empty($c['reply_content'])){?>
        <div class="cus_apply">
            <p class="middle ui_color">酒店回复：</p>
            <p class="middle ui_color_gray"><?php echo $c['reply_content'];?></p>
        </div>
        <?php }?>
    </div><?php //}?><?php }}else{?>
	<div class="ui_none middle">
    	<div>暂无评论~<a href="<?php echo $index_url; ?>" class=" ui_color">快来下单体验吧！</a></div>
    </div>
    <?php }?>
</section>
<!--<div class="ui_pull" style="display:none"><ul><li><img src="images/egimg/eg02.png"></li><li><img src="images/egimg/eg02.png"></li></ul></div>-->
</body>
<script>
var _cur = 0;
var time ;
var _left= 0;
	var isload=false;

	var showload =function(_str,haveico){
		if(_str==undefined)_str=' ';
		if(haveico==undefined)haveico=true;
		$('.ui_loadmore').remove();
		var tmp = "<div class='ui_loadmore'><span>";
		tmp +=_str+'</span>';
		if( haveico)tmp+="<em class='ui_ico ui_loading'></em>";
		tmp +="</div>";
		$('.apply_list').after(tmp);
	}  
function fill_comment(){
	var off=$('#off').val()*1;
	var num=$('#num').val()*1;
	$.get('<?php echo site_url('hotel/hotel/ajax_hotel_comments').'?id='.$inter_id.'&h='.$hotel_id;?>',{
		off:off,
		num:num
	},function(data){
		if(data.s==1){
			$('#apply_list').append(data.data);
			$('#off').val(off+$('#num').val()*1);
			$('.ui_loadmore').remove();
		}else{
			showload('无更多结果');
		}
		
		isload = false;
	},'json');
}
$(function(){
	var curstar =function(){
		var point=<?php echo $t_t['good_rate'];?>/20;
// 		var point=$('#point').html()*1;
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
	$(document).on('touchmove',function(e){
		if(($(document).height()-$(window).height())*0.4<=$(document).scrollTop()){;
			if (!isload){
				isload  = true;	
				fill_comment();
			}
			else{
				showload();
			}
		}
	})
})
</script>
</html>
