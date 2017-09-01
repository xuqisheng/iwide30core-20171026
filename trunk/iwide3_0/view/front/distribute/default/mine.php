<link href="<?php echo base_url('public/distribute/default/styles/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/sales_center.css')?>" rel="stylesheet">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" ></script>

<title>全员营销</title>
</head>
<style>
.pullhaibao{display:none; background:#2d31320;}
.pullhaibao >div{position:relative;overflow:auto;height:100%; font-size:0}
.pullhaibao .haibao_user,.pullhaibao .haibao_erweima,.pullhaibao .bg_erweima,.saveimg{position:absolute}
.pullhaibao .haibao_user{ left:0;top:46.5%; width:94%; padding:0 3%;}
.pullhaibao .haibao_user > p{width:2rem; height:2rem; float:left; margin-right:3%; background:#fff; font-size:0}
.pullhaibao .haibao_user > p img,.pullhaibao >div> img{ min-height:100%}
.pullhaibao .haibao_user div{ background:#026eb3; height:1.5rem; padding-top:0.5rem}
.pullhaibao .haibao_erweima{top:92%;left:0; text-align:center; width:100%;}
.pullhaibao .haibao_erweima img{width:46.875%;}
.pullhaibao .bg_erweima{width:100%; height:100%; opacity:0;left:0; bottom:0;}
.saveimg{padding:2% 4%; color:#fff; background:rgba(0,0,0,0.5); z-index:9999; right:3%; bottom:3%; opacity:0.9}
</style>
<body>
<div class="head">
	<div class="padding">
        <div class="user_img img_auto_cut"><img src="<?php echo $saler_details['headimgurl'];?>" /></div>	
        <div class="user_name"><?php echo $saler_details['name']?></div>
        <div class="viplv_black">分销号:  <?php echo $saler_details['id']?></div>
    </div>
    <div class="mask">
    	<div class="lis">
        	<a href="<?php echo site_url('distribute/distribute/incomes')?>?id=<?php echo $inter_id?>">
                <div><?php echo empty($saler_details['total_fee'])?0:$saler_details['total_fee']?></div>
                <div class="f_txt">未提取收益</div>
            </a>
        </div>
    	<div class="lis">
        	<a href="<?php echo site_url('distribute/distribute/my_fans')?>?id=<?php echo $inter_id?>">
                <div><?php echo $saler_details['fans_count']?></div>
                <div class="f_txt">粉丝</div>
            </a>
        </div>
    </div>
</div>
<div class="ui_btn_list ui_border">
	<a href="<?php echo site_url('distribute/distribute/incomes')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico1"></em>
    	<tt>我的收益</tt>
    </a>
	<a href="<?php echo site_url('distribute/distribute/my_fans')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico2"></em>
    	<tt>我的粉丝</tt>
    </a>
    <a href="<?php echo site_url('distribute/distribute/ranking')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico5"></em>
    	<tt>琅琊榜</tt>
    </a>
</div>
<div class="ui_btn_list ui_border">
	<a class="item my_erwen">
    	<em class="ui_ico ui_ico3"></em>
    	<tt>分销二维码 </tt>
    </a>
	<!-- <a href="" class="item">
    	<em class="ui_ico ui_ico4"></em>
    	<tt>我的消息</tt>
    	<span>有1条新消息</span>
    </a>
	<a href="" class="item">
    	<em class="ui_ico ui_ico5"></em>
    	<tt>分销排行榜</tt>
    </a> -->
</div>
<?php if($this->input->get('id') == 'a452223043' || $this->input->get('id') == 'a450939254' ||$this->input->get('id') == 'a445223616' ||$this->input->get('id') == 'a449675133' ||$this->input->get('id') == 'a463105262'):?>
<div class="ui_btn_list ui_border">
    <a class="item _haibao">
        <em class="ui_ico ui_ico3"></em>
        <tt>生成二维码海报 </tt>
    </a>
</div>
<?php endif;?>
<div class="pull pullhaibao">
	<div>
        <img src="" >
    	<canvas id="canvas"></canvas>
    </div>
</div>
<div class="pull pullerwei" style="display:none">
	<div class="box">
        <div class="pullclose">&times;</div>
        <div class="pulltitle">
        	<!--p>酒店名</p-->
            <h1>员工分销二维码</h1>
        </div>
        <img src="<?php echo base_url('public/distribute/default/images/border_bg.png')?>" />
        <div class="er_log">
            <img src="<?php echo $saler_details['url'];?>" />
            <p>使用时向顾客出示</p>
        </div>
    </div>
</div>
</body>
<script>
function getPixelRatio(context) {
	var backingStore = context.backingStorePixelRatio ||
	context.webkitBackingStorePixelRatio ||
	context.mozBackingStorePixelRatio ||
	context.msBackingStorePixelRatio ||
	context.oBackingStorePixelRatio ||
	context.backingStorePixelRatio || 1;
	
	return (window.devicePixelRatio || 1) / backingStore;
}
$(function(){
	var _time,_tmpurl,a,b;
	var isdown=false;
	var _count=0;
	var _rate = 640/1008;
	var _w=$(window).width();
	var _h=_w/_rate;
	if ( _h <$(window).height()) _h=$(window).height();
	var bgimg=new Image;
	var userimg=new Image;
	var erweima=new Image;
	var canvas=$("canvas").get(0);
	var _r =1.5;// getPixelRatio(canvas.getContext("2d"));
	_w=_w*_r;
	_h=_h*_r;
	//用户图片宽,坐标;
	var u_w=_w*120/640; 
	var u_x=_w*30/640;
	var u_y=_h*470/1008;
	//二维码宽高;
	var e_w=_w*300/640;
	var e_x=_w*170/640;
	var e_y=_h*630/1008;
	//方形宽;
	var r_w=_w*460/640;
	var r_x=_w*150/640;
	//文字坐标;
	var t_s= parseInt($('.user_name').css('font-size'))*_r;
	var t_x=_w*170/640;
	var t_y=_h*465/1008+(u_w+t_s)/2;
	$.get("<?php echo site_url('distribute/distribute/file2base64')?>?id=<?php echo $inter_id?>",{'url':'<?php echo $saler_details['headimgurl'];?>'},function(data){
		userimg.src="data:image/jpeg;base64,"+data;
		a='complete';
	});
	$.get('<?php echo site_url('distribute/distribute/file2base64')?>?id=<?php echo $inter_id?>',{'url':'<?php echo $saler_details['url'];?>'},function(data){
		erweima.src="data:image/jpeg;base64,"+data;
		b='complete';
	});
	$('.my_erwen').click(function(){
		toshow($('.pullerwei'));
		reset_time('我的二维码海报');
	});
	$('.pullclose').click(toclose);
	$('._haibao').on('click',function(){
		if ( isdown) return;
		bgimg.src='';
		<?php if($this->input->get('id') == 'a452223043' || $this->input->get('id') == 'a450939254'):?>
		bgimg.src="<?php echo base_url('public/distribute/default/images/a450939254.jpg')?>";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a445223616'):?>
		bgimg.src="<?php echo base_url('public/distribute/default/images/a445223616.jpg')?>";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a449675133'):?>
		bgimg.src="<?php echo base_url('public/distribute/default/images/a449675133.jpg')?>";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a463105262'):?>
		bgimg.src="<?php echo base_url('public/distribute/default/images/a463105262.jpg')?>";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a457946152'):?>
		bgimg.src="<?php echo base_url('public/distribute/default/images/a457946152.jpg')?>";
		<?php endif;?>
		if(bgimg.src==''){ isdown=true; alert('暂未添加此功能');return false;}
		if ( a!='complete'|| b!='complete' || !bgimg.complete ){ cutdown('生成');}
		else{
			if ($('.pullhaibao img').attr('src')=='') cutdown('生成');
			else toshow($('.pullhaibao'));
		}
	});
	
	function creat(){
		isdown=true;
		var cxt=canvas.getContext("2d");
		$('#canvas').attr('width',_w);
		$('#canvas').attr('height',_h);
		cxt.drawImage(bgimg,0,0,_w,_h);
		cxt.drawImage(userimg,u_x,u_y,u_w,u_w);
		cxt.drawImage(erweima,e_x,e_y,e_w,e_w);
		var txt='';
		<?php if($this->input->get('id') == 'a452223043' || $this->input->get('id') == 'a450939254'):?>
		txt='莫林 · 美梦开始的地方';
		cxt.fillStyle = "#026eb3";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a445223616'):?>
		txt='扫码可享订房优惠/乐购商场/粉丝互动';
		cxt.fillStyle = "#f7a23d";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a449675133'):?>
		txt='扫码可提供优惠, 快捷的管家服务。';
		cxt.fillStyle = "#883d39";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a463105262'):?>  //亲的客栈
		txt='扫码关注，畅享优惠';
		cxt.fillStyle = "#cf3f5a";
		<?php endif;?>
		<?php if($this->input->get('id') == 'a457946152'):?>  //隐居
		txt='扫码关注，畅享优惠';
		cxt.fillStyle = "#313b78";
		<?php endif;?>
		cxt.fillRect(r_x,u_y,r_w,u_w);
		cxt.fillStyle = "#ffffff";
		cxt.font=t_s+'px Helvetica Neue,sans-serif';
		cxt.fillText(txt,t_x,t_y);
		_tmpurl=canvas.toDataURL().replace('data:image/png;base64,','');
		$.post("<?php echo site_url('distribute/distribute/base64_2file')?>?id=<?php echo $inter_id?>",{
			'url':_tmpurl,
			'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
			'qid':'<?php echo $saler_details['id']?>'
		},function(data){
			if (data=='error') {alert('下载失败,请刷新页面重试~');reset_time( '二维码海报生成失败!');return;}
			_count=0;
			cutdown('下载');
			var tmpimg = new Image;
			tmpimg.onload=function(){
				reset_time( '我的二维码海报!');
				isdown=false;
				$('#canvas').hide();
				$('.pullhaibao img').attr('src',tmpimg.src);
				toshow($('.pullhaibao')); 
			};
   			tmpimg.src=data;
		});
	}
	function cutdown(str){
		window.clearInterval(_time);
		$('._haibao tt').html('正在'+str+'二维码海报,请稍候('+_count+'s)');
		_time=window.setInterval(function(){
			_count++;
			$('._haibao tt').html('正在'+str+'二维码海报,请稍候('+_count+'s)');
			if( a=='complete'&& b=='complete' && bgimg.complete){
				if (!isdown) creat();
			}
			if (_count>180){
				_count=0;
				isdown=false;
				reset_time( str+'二维码海报');
			}
		},1000);
	}
	function reset_time(str){
		window.clearInterval(_time);
		$('._haibao tt').html(str);
	}

})

</script>
</html>