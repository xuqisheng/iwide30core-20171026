// JavaScript Document

$(document).ready(function(e) {
	$('.get_btn').click(function(){		//捞瓶子
		$('.bgimg_box').removeClass('bgimg_default');
		$('.bgimg_box').removeClass('fadein');
		$('.pull_get_bottle').removeClass('light');
		$('.pull_get_bottle div').html('正在努力的捞瓶子...');
		setTimeout(function(){			 
			$.get('/index.php/welcome/getbottle?act=getbottle',{},function(d){
				if(d.status==1){
					$('.pull_get_bottle').addClass('light');
					$('.bgimg_box').addClass('bgimg_default');
					$('.bgimg_box').addClass('fadein');
					$('.bgimg_box').attr('onClick','location.href="/index.php/welcome/getbottle?id='+d.id+'"');
					$('.pull_get_bottle div').html('捞到一个酒瓶，快打开看看里面都有什么～');
				}
				else {
					$('.pull_get_bottle div').html('很遗憾，您没有捞到酒瓶哦！');
					$('.bgimg_box').attr('onClick','history.go(-1)');
				}
			},'json');
        },1000);
		toshow($('.pull_get_bottle'));
		
	})//
//	$('.reback_btn').click(function(){    //扔回酒店
//		toshow($('.pull_get_bottle'));
//	})
});
