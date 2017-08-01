<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script src="/public/chat/public/shake/jquery.js"></script>
<link href="/public/chat/public/shake/global.css" rel="stylesheet">
<link href="/public/chat/public/shake/animate.css" rel="stylesheet">
<title>摇一摇活动</title>
</head>
<style>
*{font-family:'黑体','微软雅黑'}
body,html,.page,.bg{height:100%; overflow:hidden; background:#99e3e4;}
.startbtn{ position:absolute; top:40%; text-align:center; width:100%;}
.startbtn span,.btn{ background:#61af63; border-radius:7px; border:1px solid #acd598; color:#fff; padding:30px 60px; font-size:40px; cursor:pointer}
.startbtn span:hover{ background:#3b8c3d;color:#fff;}
.btn{ left:-35px; position:absolute; top:35%; z-index:999; font-size: 18px; padding: 15px 40px;}
.btn:hover{ background:#3b8c3d;color:#fff; left:-15px;}
.save{ top:30%;}
.restart{ top:45%;}
/*.clear{ top:45%;} .restart{ top:60%;}*/
.count{ font-size:600px; text-align:center;}
.bg{background:url(/public/chat/public/shake/bg04.jpg) no-repeat bottom right; background-size:auto 100%; position:absolute; width:100%;
	transition:bottom 600ms,top 1500ms,color 300ms,background-color 300ms;/* dong hua */
	 -moz-transition:bottom 600ms,top 1500ms,color 300ms,background-color 300ms; /* Firefox 4 */
	-webkit-transition:bottom 600ms,top 1500ms,color 300ms,background-color 300ms; /* Safari and Chrome */
	 -o-transition:bottom 600ms,top 1500ms,color 1500ms,background-color 300ms; /* Opera */}
.bg1{background-size:cover;}
.bg2{ background-image:url(/public/chat/public/shake/bg05.jpg); top:-100%;}
.bt3{top:-200%;}
.page ul{position:absolute; bottom:0; padding:0 5%;width:90%; height:100%;}
.page ul li{ width:10%; float:left; color:#fff; text-shadow:1px 1px 3px #000; text-align:center; height:100%; position:relative}
.page ul li .fly{background:url(/public/chat/public/shake/img01.png) no-repeat center center; width:83px; height:240px;position:relative; display:inline-block;top:80%;}
.page ul li:nth-child(2n) .fly{background-image:url(/public/chat/public/shake/img02.png)}
.page ul li:nth-child(3n) .fly{background-image:url(/public/chat/public/shake/img03.png)}
.page ul li:nth-child(4n) .fly{background-image:url(/public/chat/public/shake/img04.png)}
.page ul li:nth-child(5n) .fly{background-image:url(/public/chat/public/shake/img05.png)}
.page .ui_img_auto_cut{ position:absolute;width:27px; height:27px; top:92px; left:28px;}
.page .ui_img_auto_cut img{ width:100%; height:100%;}
.page .name{display:none; position:absolute; width:100%;}
.page .name p{font-size:18px}
.page .name img{ display:none; width:30px;}
.page .num{font-size:18px;  padding-top:220px;}
.page .fire{ background:url(/public/chat/public/shake/fire.png) no-repeat center; width:100%; height:45px; position:absolute; bottom:15px; display:none;}
.result{position:absolute; top:50px; width:100%; text-align:center;}
.result>div{ background:#f8f8f8; box-shadow:0px 0px 10px rgba(0,0,0,0.5); padding:20px 0; padding-right:20px; width:780px; display:inline-block; overflow:hidden}
.result .close{ font-size:25px; text-align:right; color:#000; color:#bebebe;}
.result ul li{width:210px; box-shadow:0px 0px 5px rgba(0,0,0,0.5); float:left; margin:20px 0 0 20px; padding:15px;}
.result ul li .ui_img_auto_cut{ float:left; width:88px; height:88px;}
.result ul li div{font-size:18px; margin-bottom:15px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
.result ul li div:first-child,.result ul li div:last-child{ margin-bottom:0px;}
.time{ color:#5c5c5c; position:absolute; top:1%; right:1%; font-size:48px; z-index:100;}
</style>
<body>
<div class="pull">
	<?php if($shake['nowtimes']>=$shake['dotimes']){echo '<div class="startbtn"><span>活动已经结束</span></div>';} else {echo '<div class="startbtn start"><span>开始</span></div>';} ?>
    <div class="count" style="display:none">3</div>
</div>
<div class="time"></div>
<div class="page">
    <div class="bg bg2"></div>
	<div class="bg bg1"></div>
    <ul>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    	<li>
        	<div class="fly">
        		<div class="ui_img_auto_cut"><img src=""></div>
                <div class="fire"></div>
            	<div class="num"></div>
                <div class="name">
                    <img src="/public/chat/public/shake/last.png" class="last">	
                    <p></p>
                </div>
            </div>
        </li>
    </ul>
</div>

<div class="show_btn" style="display:none">
    <button class="btn save">保存数据</button>
	<!--<button class="btn clear">清除数据</button>-->
	<button class="btn restart">重新开始</button>
</div>
<div class="result" style="display:none">
	<div>
        <div class="close">×</div>
        <ul>
            <li>
                <div class="ui_img_auto_cut"><img src="/public/chat/public/shake/bg03.jpg"></div>
                <div>第一名</div>
                <div>用户名</div>
                <div>111次</div>
            </li>
        </ul>
    </div>
</div>
</body>
<script>
$(function(){
	$('.show_btn').fadeIn();
	var _count = 2;
	var time;
	var down;//倒计时
	var flytime;
	var _h;
	var iii=30;
	var downresult='';
	function fly(_num,_index,rate){
		var percent = ((_h-_num)/ _h) *100;
		if ( percent > 90)
			$('.last').eq(_index).show();
		else
			$('.last').eq(_index).hide();
		if ( percent < 0)
			percent = 0;
		$('.fly').eq(_index).stop().css('top',percent+'%')
	}
	function showresult(){  // 显示结果
		window.clearInterval(time);
		window.clearInterval(flytime);
		window.clearInterval(data);
		var resulthtml = '';
		$.get('shake?iad=<?php echo $shake['id'];?>&id=<?php echo $shake['inter_id'];?>',{act:'g'},function(d){
		    if(d){
			    var userlist = '',userdotimes = '',userlevel = '';
			    for(var i=0;i<d.length;i++){
				    downresult = downresult+'用户ID：'+d[i].id+'	用户名：'+d[i].nickname+'		票数：'+d[i].num+"\r\n";
				    resulthtml = resulthtml+'<li><div class="ui_img_auto_cut"><img src="'+d[i].logo+'"></div><div>第'+(i+1)+'名</div><div>'+d[i].nickname+'</div><div>'+d[i].num+'次</div></li>';
					if(i<<?php echo $shake['people'];?>){
						userlist = userlist+','+d[i].id;
						userdotimes = userdotimes+','+d[i].num;
						userlevel = userlevel+','+(i+1);
					}
				}
				$.get('shake?iad=<?php echo $shake['id'];?>&id=<?php echo $shake['inter_id'];?>',{'act':'sendmsg','u':userlist,'t':userdotimes,'l':userlevel},function(){});
				$('.result ul').html(resulthtml);
			}
		},'json');
		$('.result').fadeIn();
	}
	function bgfly(){
		$('.bg1').animate({top:'100%'},1000,function(){
			$('.fly').css('top','100%');
			data=window.setInterval(filldata,1000)
		});
		flytime=window.setInterval(function(){
			$('.bg2').show().animate({'top':'100%'})
			window.setTimeout(function(){
				$('.bg2').hide().animate({'top':'-100%'})
			},1500)
		},2500);
	}
	function _down(){
	    if(iii<0){iii=0;}
		$('.time').html(iii);
		down=window.setInterval(function(){
			var i=parseInt($('.time').html())-1;
			if (i <0){
				window.clearInterval(down);
				showresult();
			}
			else
				$('.time').html(i)
		},1000);
	}
	function tostart(){
		$('.pull').fadeOut();
		window.setTimeout(function(){
			$('.fire').show();
			$('.name').show();
			$('.fly').addClass('shaking');
		},1000);
		_down();
		window.setTimeout(bgfly,1000);
		window.clearInterval(time);
	}
	$.get('shake?iad=<?php echo $shake['id'];?>&id=<?php echo $shake['inter_id'];?>',{act:'s'},function(d){
		if(d){
			if(d.start==0){
				//没开始时
			}
			else {
			    totime = parseInt(d.totime);
				ntime = parseInt(d.ntime);
				if(totime-ntime>0){
				   iii = totime-ntime;
				}
				tostart();
			}
		}
	},'json');
	$('.start').click(function(){
		$(this).fadeOut();
		$('.count').fadeIn();
		time=window.setInterval(function(){
			$('.count').html(_count--);
			if(_count==1){$.get('shake?iad=<?php echo $shake['id'];?>&id=<?php echo $shake['inter_id'];?>',{act:'d'},function(){});}
			if(!(_count+1)){
				tostart();
			}
		},1000);
	});
	function filldata(){
		$.get('shake?iad=<?php echo $shake['id'];?>&id=<?php echo $shake['inter_id'];?>',{act:'g'},function(d){
			_h=d[0].num;
			for(var i=0;i<d.length;i++){
				$('.fly').eq(i).find('img').attr('src',d[i].logo);
				$('.num').eq(i).html(d[i].num+'次');
				$('.name p').eq(i).html(d[i].nickname);
				fly(d[i].num,i);
			}
		},'json');
	}
	$('.close').click(function(){
		$('.result').fadeOut();
	});

	$('.restart').click(function(){
		$('.show_btn').fadeOut();
		$('.result').fadeOut();
		$('.pull').fadeIn();
		$('.fly').removeClass('shaking');
		$('.fire').hide();
		$('.fire').hide();
		iii=30;
		$('.fly').stop().css('top','80%')
		$('.time').html(iii);
		$('.bg1').css('top','0%')
		$('.pull .count').hide();
		$('.pull .start').show();
		_count = 3;
		$('.count').html(_count--);
	});

	$('.show_btn .save').click(function(){	
		export_raw('shakingresult.txt', downresult);
		downresult = '';
	});

	$('.show_btn .clear').click(function(){
		alert('数据成功归零!!！');
	});
	$('.show_btn .restart').click(function(){
		location.href = location.href;
	});
});
function fake_click(obj) {
	var ev = document.createEvent("MouseEvents");
	ev.initMouseEvent(
			"click", true, false, window, 0, 0, 0, 0, 0
			, false, false, false, false, 0, null
	);
	obj.dispatchEvent(ev);
}
function export_raw(name, data) {
	var urlObject = window.URL || window.webkitURL || window;
	var export_blob = new Blob([data]);
	var save_link = document.createElementNS("http://www.w3.org/1999/xhtml", "a");
	save_link.href = urlObject.createObjectURL(export_blob);
	save_link.download = name;
	fake_click(save_link);
}
</script>
</html>