// JavaScript Document

$(document).ready(function(e) {
	$('.get_btn').click(function(){		//捞瓶子
								 
		$('.bgimg_bottle_default div').html('正在努力的捞瓶子...');
		
		setTimeout(function(){			 
			$.get('/index.php/welcome/getbottle?act=getbottle',{},function(d){
				if(d.status==1){
					
					$('.bgimg_bottle_default').attr('onClick','location.href="/index.php/welcome/getbottle?id='+d.id+'"');
					
					$('.bgimg_bottle_default div').html('捞到一个酒瓶，快打开看看里面都有什么～');
					
				}
				else {
					
					$('.bgimg_bottle_default div').html('很遗憾，您没有捞到酒瓶哦！');
					
					$('.bgimg_bottle_default').attr('onClick','history.go(-1)');
					
				}
			},'json');
        },1000);
		toshow($('.pull_get_bottle'));
		
	})
	$('.reback_btn').click(function(){    //扔回酒店
		toshow($('.pull_get_bottle'));
	})
});
