
	var index      = 0;
	var _scrlindex = 0;
	var length     = 0;
	var height     = 0;
	var _top       = 0;
	var direction  = 0;
	var _scroll;
	var scrollpage = function(dir){
		if(!dir){
		    if(index>= length-1){
				window.location.href='http://iwide.chat.iwide.cn/index.php/fapi?id=15';
				return false;
			}
		}else{	
		    if(index<= 0) return false; 
		}
		$('.next').show();
		 _scrlindex = 0;
		direction  = dir ? 1 : -1;
		_top	  += height * direction ; 
		$('.page').eq(index).stop().animate({top:_top},500,function(){$('.fire').hide()});
		$('.page').eq(index-direction).stop().animate({top:_top},500).scrollTop(0);
		//alert(length+' '+height+' '+_top);
		index     -= direction;
		//if(index==1) isfirst = true;
	}
	var tranform = function(dir,_e){
	//	if(!dir){
//		    if(index>= length-1) return false;
//		}else{	
//		    if(index<= 0) return false; 
//		}
		//alert($('.page').eq(index).get(0).scrollHeight+' '+height)
		
		direction  = dir ? 1 : -1;
		var _this =$('.page').eq(index);
		var tmp = _this.get(0).scrollHeight-height-3;
		var sctop =_this.scrollTop();
		if (tmp>=0){
			if((sctop>=tmp && !dir)||( sctop<=0 && dir)){ 
				_e.preventDefault();
				scrollpage(dir);
				return;
			}
		}
		
	}

$(function(){
	
	//会议下拉
	$('.for .invoice').click(function(){
		$('.for .hid').slideToggle();
	})
	$('.startbtn').click(function(e){
		//$(this).hide();
		//$(this).siblings('.footlogo').slideUp();
		$('.fire').show();
		scrollpage(false);
	})
	
	$('.btn').on('touchstart',function(){
		$(this).addClass('hover');
	});
	$('.btn').on('touchend',function(){
		$(this).removeClass('hover');
	});
	$('.page').touchwipe({
		wipeDown   : function(_e){ //上滑
			tranform(false,_e) ;
		},  
		wipeUp     : function(_e){ //下滑
			tranform(true,_e) ;
		}, 
		min_move_x : 10,
		min_move_y : 10,
		preventDefaultEvents: true
	});
	$('.nextimg').click(function(e){
		scrollpage(false);
	})
});

window.onload=function(){
	$('.page_loading').hide();
	$('.page_loading').siblings().fadeIn();
	length     = $('.page').length;
	height     = $('.index_page').height();
	_top       = 0-$('.page').eq(index).offset().top;
	//$('.section').height(length * height)
//	$('.page').height(height)
		console.warn(_top);
/*	var isfirst=true;
	$('.slongpage').on('touchmove',function(e){
		if($(this).scrollTop()>=$(this).get(0).scrollHeight-height-3){
			//alert($(this).get(0).scrollHeight-height+' '+$(this).scrollTop());
			if (!isfirst){
				$('.longpage').scrollTop($(this).get(0).scrollHeight/4);
				scrollpage(false);
			}
			isfirst = false;
		}
	})
	$('.longpage').scroll(function(e){
		e.preventDefault();
	})*/
//	
//	$('.singlepage').click(function(e){
//		scrollpage(false);
//	})
}

