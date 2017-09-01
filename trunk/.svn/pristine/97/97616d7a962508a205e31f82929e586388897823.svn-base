<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
<title>酒店小程序金选</title>
    <link href="<?php echo base_url("public/wxapp/styles/global.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("public/wxapp/styles/service.css"); ?>" rel="stylesheet">
    <script src="<?php echo base_url("public/wxapp/scripts/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("public/wxapp/scripts/ui_control.js"); ?>"></script>
    <script src="<?php echo base_url("public/wxapp/scripts/alert.js"); ?>"></script>
</head>
<body>
<div class="pageloading"></div>
<div class="ui_pull corelayer flex center" id="corelayer" onClick="toclose()" style="display:none">
    <div><img src="" /></div>
</div>
<page class="page">
    <header style="flex-shrink:0">
    	<div class="banner" style="font-size:0"><img src="<?php echo base_url("public/wxapp/images/banner.jpg"); ?>" /></div>
    	<div class="center bg_fff flex flexjustify tablayer color_main icontab bd_bottom" style="font-size:0">
        	<div class="icon01 iscur" type="default"><tt class="h24">推荐</tt></div>
        	<div class="icon02" type="hit"><tt class="h24">热门</tt></div>
        	<div class="icon03" type="time"><tt class="h24">最新</tt></div>
        	<div class="icon04 showsearch" type="all"><tt class="h24">全部</tt></div>
        </div>
    </header>
    <section class="bd_bottom flex mainboxs">
    	<div class="flexgrow bg_fff scroll">
    		<div class="list_style bd_bottom salelist"></div>
        </div>
	</section>
    <footer class="bg_fff hide">
    	<a href="" class="color_main center padding h24" style="display:block">
        	<img style="width:auto; height:15px; vertical-align:text-bottom" src="<?php echo base_url("public/wxapp/images/icon06.png"); ?>" />
            申请展示
        </a>
   </footer>
</page>
<page class="page topfixed _w" style="top:0; left:100%; z-index:444; background:#fff;">
    <header style="flex-shrink:0">
        <div style="padding:5px 10px 5px 0; background:#eeeeee; ">
            <div class="flex">
                <div class="padding color_main cancel h24">首页</div>
            	<div class="flexgrow"><input placeholder="输入关键字进行搜索" class="h24 _w center searchbox"></div>
            </div>
        </div>
    </header>
    <section class="flex mainboxs">
    	<div class="flexgrow bg_fff scroll">
    		<div class="list_style bd_bottom salelist"></div>
        </div>
	</section>
    <footer class="bg_fff hide">
    	<a href="" class="color_main center padding h24" style="display:block">
        	<img style="width:auto; height:15px; vertical-align:text-bottom" src="<?php echo base_url("public/wxapp/images/icon06.png"); ?>" />
            申请展示
        </a>
   </footer>
</page>
<page class="page topfixed _w" id="page_detail" pro_id="" style="top:100%; z-index:444; background:#fff;">
    <header></header>
    <section class="flex mainboxs">
    	<div class="flexgrow list_style bd_bottom salelist scroll">
            <div>
                <div class="img"><div class="squareimg"><img src="" /></div></div>
                <div class="flexgrow">
                    <div class="h26" name></div>
                    <div class="h22 color_999 pad" hit></div>
                    <div class="h22 color_666 txtclip" style="max-width:15em; line-height:1.3" short_intro></div>
                </div>
                <div class="h22 color_999" style="flex-shrink:0">作者：<span class="color_main" author></span></div>
            </div>
            <div class="flex scroll imgshow _w">
            </div>
            <div class="list_intro">
            	<div class="color_main" style="font-size:5px; margin-top:5px; margin-right:6px">●</div>
            	<div>
                	<div style="margin-bottom:3px">内容简介</div>
                 	<div class="h22 color_666" intro></div>
                </div>
                 
            </div>
        </div>
	</section>
    <footer class="title_main_bg" style="flex-shrink:0">
    	<div class="h26 bg_main center" onclick="showcore(event,this)" id="detailcore" qrcode_img="">立即体验</div>
    </footer>
</page>
<div class="model" pro_id="">
    <div class="img"><div class="squareimg"><img src="" /></div></div>
    <div class="flexgrow">
        <div class="h26" name></div>
        <div class="h22 color_999 pad" hit></div>
        <div class="h22 color_666 txtclip" style="max-width:15em; line-height:1.3" short_intro></div>
    </div>
    <div class="icon05 color_main h20" onclick="showcore(event,this)" qrcode_img="" style="flex-shrink:0">体验</div>
</div>

</body>
<script>
var defaultimg = "<?php echo base_url("public/wxapp/images/default2.jpg"); ?>";
var  startX ,startY,distanceX,distanceY;
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
	if($('.page').eq(0).find('.img').length>=10&&!$('.page').eq(0).is(":hidden")){
		if(distanceY<0){
			$('.banner').hide();
		}
		else{
			$('.banner').show();
		}
	}
	if(Math.abs(distanceX)<Math.abs(distanceY)&&distanceY>40&&$('#page_detail').offset().top<=10){
		$('#page_detail').css('top','100%');
	}
	if(Math.abs(distanceX)>Math.abs(distanceY)&&distanceX>50&&$('.page').eq(1).offset().left<=10){
		$('.page').eq(1).css('left','100%');
	}
})
$('#page_detail').on('touchstart',function(e){
	if(e.target.id!='detailcore'){
		if(Math.abs(distanceX)>10&&Math.abs(distanceX)<Math.abs(distanceY)){
			e.preventDefault();
		}
	}
});
function fillimg(dom,src){
	var img = new Image();
	img.onload=function(){dom.attr('src',this.src);}
	img.src=src;
}
$('.tablayer>*').click(function(){
	var  index = 0;
	var type = $(this).attr('type');
	if($(this).hasClass('showsearch')){
		index = 1;
		$('.page').eq(index).css('left','0');
	}else{
		$(this).addClass('iscur').siblings().removeClass('iscur');
	}
	pageloading('',0.5);
	pagedata({type:type},$('.page').eq(index).find('.salelist'));
	$('.banner').hide();
})
$('.cancel').click(function(){
	$('.page').eq(1).css('left','100%');
})
function showcore(e,_this){
	e.stopPropagation();
	$('#corelayer img').attr('src',defaultimg);
	fillimg( $('#corelayer img'),$(_this).attr('qrcode_img'));
	toshow($('#corelayer'))	;
	sum_hit($(_this).parents('[pro_id]').attr('pro_id'));
}
$('.mainboxs').on('click','.model',function(e){
	pageloading();
	var pro_id = $(this).attr('pro_id');
	$.ajax({
		async:true,
		url:'<?php echo base_url("index.php/wxapp/navigation/detail"); ?>',
		data:{pro_id:pro_id},
		dataType:"json",
		type:'GET',
		success: function(data){
			var _this =$('#page_detail');
			_this.css('top','0');
			if(data.status==1){
			var n = data.data;
			_this.attr('pro_id',n.pro_id);
			$('.imgshow').html('');
			if(n.detail_img!=undefined&& n.detail_img.length>0){
				$.each(n.detail_img,function(i,j){
					var dom=$('<div class="img"><div class="squareimg"><img src="" /></div></div>');
					$('.imgshow').append(dom);
					fillimg(dom.find('img'),j);
				})
			}
			fillimg($('.img img',_this).eq(0),n.intro_img);
			$('[qrcode_img]',_this).attr('qrcode_img',n.qrcode_img);
			$('[name]',_this).text(n.name);
			$('[hit]',_this).text(n.hit+'人体验');
			$('[short_intro]',_this).text(n.short_intro);
			$('[intro]',_this).text(n.intro);
			if(n.author!="")$('[author]',_this).text(n.author);
			else{$('[author]',_this).text('-');}
			}else{
				$.MsgBox.Confirm('网络貌似出了点小问题，请刷新再试~',winreload);
			}
		},
		complete:function(data){
			removeload();		
			if(data.readyState!=4||data.status!=200){
				$.MsgBox.Alert('服务器开小差，请稍后再试');
			}
		}
	});
});
$('.searchbox').on('input propertychange',function(){
	var _this = $('.page').eq(1).find('.salelist');
	var val = $(this).val();
	if( val==''){
		_this.find('.model').show();
	}else{
		_this.find('.model').each(function(){
			if( $(this).html().indexOf(val)>=0){$(this).show();}
			else{$(this).hide()}
		});
	}
});

pagedata({type:'default'},$('.page').eq(0).find('.salelist'));
function pagedata(data,_this){
	$.ajax({
		async:true,
		url:'<?php echo base_url("index.php/wxapp/navigation/search"); ?>',
		data:data,
		dataType:"json",
		type:'GET',
		success: function(data){
			if(data.status==1){
				_this.html('');
				$.each(data.data,function(i,n){
					var clone = $('.model').eq(0).clone();
					clone.attr('pro_id',n.pro_id);
					fillimg($('img',clone),n.intro_img);
					$('[qrcode_img]',clone).attr('qrcode_img',n.qrcode_img);
					$('[name]',clone).text(n.name);
					$('[hit]',clone).text(n.hit+'人体验');
					$('[short_intro]',clone).text(n.short_intro);
					_this.append(clone);
				});
			}else{
				$.MsgBox.Confirm('网络貌似出了点小问题，请刷新再试~',winreload);
			}
		},
		complete:function(data){
			removeload();		
			if(data.readyState!=4||data.status!=200){
				$.MsgBox.Alert('服务器开小差，请稍后再试');
			}
		}
	});
}
function sum_hit(pro_id){
	$.ajax({
		async:true,
		url:'<?php echo base_url("index.php/wxapp/navigation/ajax_hit"); ?>',
		data:{pro_id:pro_id},
		dataType:"json",
		type:'post',
		complete:function(){/*不做处理*/}
	});
}
</script>
</html>
