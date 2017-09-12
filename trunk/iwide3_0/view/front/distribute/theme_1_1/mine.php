<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" ></script>
<title>全员营销</title>
<style>
.pullhaibao{display:none; background:#000;}
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
</head>
<body>
<div class="head">
	<a href="<?php echo site_url('distribute/dis_v1/incomes')?>?id=<?php echo $inter_id?>" class="income">
    	<div><span>总收益</span><span><?php echo $total_amount?></span></div>
    	<div><span>今日收益</span><span><?php echo $today_amount?></span></div>
    	<div><span>昨日收益</span><span><?php echo $yestoday_amount?></span></div>
    </a>
	<div class="padding overflow">
        <div class="user_img"><img src="<?php echo $saler_details['headimgurl'];?>" /></div>	
        <div class="user_name"><?php echo $saler_details['name']?><span class="h3">&nbsp;No.<?php echo $saler_details['id']?></span></div>
        <div class="viplv_black"><?php echo $saler_details['hotel_name']?></div>
    </div>
</div>
<div class="ui_btn_list ui_border">
	<a href="<?php echo site_url('distribute/dis_v1/incomes')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico4"></em>
    	<tt>我的收益(<?php echo $total_amount?>)</tt>
    </a>
	<a href="<?php echo site_url('distribute/dis_v1/my_fans')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico5"></em>
    	<tt>我的粉丝(<?php echo $saler_details['fans_count']?>)</tt>
    	<!-- <span class="new">+1</span> -->
    </a>
</div>

<!-- <?php echo $inter_id; ?>  -->
<div class="ui_btn_list ui_border">
<?php if($inter_id =="a464177542"){ ?>
	<div class="item tmp_act">
    	<em class="ui_ico ui_ico12"></em>
    	<tt style="color:#e22e3b;">悬赏令第一期</tt>
    </div>
<?php }?>
	<a href="<?php echo site_url('distribute/dis_v1/ranking')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico8"></em>
    	<tt>琅琊榜</tt>
    </a>
</div>
<div class="ui_btn_list ui_border">
	<?php $inter_id_default_array = array('a'); //不需要二维码的公众号ID 
	  if(in_array($inter_id,$inter_id_default_array)) {$inter_id_not_default=false;}
	  else{  $inter_id_not_default=true; }
	  if($inter_id_not_default) :?>
	<a class="item my_erwen" >
    	<em class="ui_ico ui_ico6"></em>
        <?php if($inter_id == 'a492669988'){ ?>
    	   <tt>会员引入二维码</tt>
        <?php } else { ?>
           <tt>我的二维码</tt>
        <?php } ?>
    </a>
    <?php endif;?>
    <?php if($inter_id == 'a450089706'):?>
	<a class="item my_erwen" >
    	<em class="ui_ico ui_ico6"></em>
    	<tt>怡亚通二维码</tt>
    </a>
	<?php endif;?>
    <?php $inter_id_taopiao_array = array('a468914243','a426755343','a465353793','a469617014','a475115706','a466585224','a476935671','a466585224','a474876699','a459234737','a479437128','a472114616','a476843906','a478659714','a449675133','a457946152','a479866040','a473817945','a474876399','a434597274','a451027797','a468983720','a434361763','a476352963','a466508403','a489646425','a468919145','a490611297','a489633563','a492152200','a493691946','a493966098','a429262688','a495077577','a484533415','a496887680','a469174492','a492755178','a493201849','a494688016','a488035568','a488035363','a469608397','a495258040','a487849419','a497580480','a494820079','a495893551','a493195389','a497864944','a495782075','a484202338','a495090452','a499242328','a499067795','a492669988','a489744049','a498719056','a490321436','a483687344','a498449733','a484533415','a491543237','a500347318','a450941565','a457498861','a492152200','a501048349','a500119114','a487585689','a468291359','a496398382','a501121376','a502439398','a499666469','a467792456','a471253048','a500304280','a500372181','a499241310','a490782373','a477051781','a484191907','a467780350','a498646866','a476756979','a466161567','a493717254','a503541435');
if(in_array($inter_id,$inter_id_taopiao_array)) {$inter_id_in_taopiao=true;}
else{  $inter_id_in_taopiao=false; }
if($inter_id_in_taopiao) :?>
	<a class="item _taopiao">
    	<em class="ui_ico ui_ico6"></em>
        <?php if($inter_id == 'a492669988'){ ?>
           <tt>商城营销二维码</tt>
        <?php } else { ?>
           <tt>套票二维码</tt>
        <?php } ?>
    </a>
    <?php endif;?>
    <?php $inter_id_haibao_array = array('a452223043','a445223616','a449675133','a450939254','a457946152','a463105262','a464177542','a455510007','a468209719','a469428180','a483582961','a488251814');
if(in_array($inter_id,$inter_id_haibao_array)) {$inter_id_in_haibao=true;}
else{  $inter_id_in_haibao=false;}

 $inter_id_haibao_array2 = array('a469428180','a483582961'); //a469428180 测试号*文字分行的海报*
if(in_array($inter_id,$inter_id_haibao_array2)) {$inter_id_in_haibao2=true;}
else{  $inter_id_in_haibao2=false; }
if($inter_id_in_haibao) :?>
	<a class="item _haibao">
    	<em class="ui_ico ui_ico6"></em>
    	<tt>生成二维码海报 </tt>
    </a>
    <?php endif;?>
    <?php if($inter_id == 'a421641095' || $inter_id == 'a460705829' || $inter_id == 'a463803935' || $inter_id == 'a445223616' || $inter_id == 'a459234737' || $inter_id == 'a441624001'):?>
	<!--a class="item my_erwen_mall" >
    	<em class="ui_ico ui_ico6"></em>
    	<tt>粽子分销二维码</tt>
    </a--><?php endif;?>
<?php
/* 月饼ID 
-酒店名称-公众号-ID	-
书香		    a449675133	东莞银丰		a466404906	珠海万豪 		a459130158	 
白云国际 		a450941565	白天鹅宾馆	a467780350	东莞南华国际	a464937572	
爱群		    a462262181	揭阳迎宾馆	a460084509	百花山庄		a466585224	
广州大厦		a429674577	广州流花宾馆	a434597274	广州宾馆		a434361763	
鑫源月饼		a468917762 	中山雅居乐酒店	a467082989	广州酒家		a468917629	
东莞华通城	a468303168	武汉明德酒店	a466586409	三英		    a452839067  	
希尔顿逸林	a462948435	隐居 		a457946152	深圳格兰云天	a466161567	
广骏		    a467353624	丽柏国际		a459234737	碧桂园		a421641095	
岭南		    a426755343	云盟		    a445223616 	远洲		    a440577876	
街町		    a441624001	玛丽蒂姆酒店  a466508403	东方嘉伯酒店	a469007639		
逸柏		    a441098524	柏丽		    a450682197	中秋月饼说    a466733989
金融城国际	a460022674  中南海怡   	a469592678	广州白云宾馆	a469440657
威尼斯睿途酒店	a469763135	香格里拉大酒店	a469617014	万达文华月饼  a467615037  
测试号		a467261753	东方宾馆		a462353539	沃顿国际大酒店	a470377478	
大三元		a471243792	山水			a470152677	领好奕墅酒店	a453287659
万佳集团		a463383106  格兰云天大酒店	a471253048	翔丰国际酒店	a469514149
空港佰翔花园	a470823776  清沐酒店		a464919542	
*/
$inter_id_tmp_array = array(
'a449675133','a466404906','a459130158','a450941565','a467780350','a464937572','a462262181','a460084509','a466585224','a429674577','a434597274','a434361763','a468917762','a467082989','a468917629','a468303168','a466586409','a452839067','a462948435','a457946152','a466161567','a467353624','a459234737','a421641095','a426755343','a445223616','a440577876','a441624001','a466508403','a441098524','a450682197','a469007639','a466733989','a460022674','a469592678','a469440657','a469763135','a469617014','a467615037','a467261753','a462353539','a470377478','a471243792','a470152677','a453287659','a463383106','a471253048','a469514149','a470823776','a464919542');
	if(in_array($inter_id,$inter_id_tmp_array)) {
		$inter_id_in_array =false;
	}
	else{  $inter_id_in_array=false; }
?>
	<?php if( $inter_id_in_array ): ?>
    <a class="item _mooncake" >
    	<em class="ui_ico ui_ico6"></em>
    	<tt>月饼二维码海报</tt>
    </a>
    <?php endif; ?>
    <?php 
    /* 
        奖励商品列表 
        -酒店名称-公众号-ID -
        a429262688 演示号
        a489736105 三亚国光豪生酒店
        a501035260  海口希尔顿酒店
        a494407322  三亚凤凰岛度假酒店
        a494576637 三亚香格里拉度假酒店
        a502333740 三亚亚龙湾美高梅度假酒店
        a502852632  琼海博鳌亚洲湾度假酒店
    */
        $soma_array = array('a429262688','a489736105','a501035260','a494407322','a494576637','a502333740','a502852632'); 
        if(in_array($inter_id,$soma_array)) {$soma_in_array=true;}
        else{$soma_in_array=false;}
    ?>
    <a href="<?php echo site_url('soma/package/distribute_products')?>?id=<?php echo $inter_id?>" class="item">
		<em class="ui_ico ui_ico13"></em>
		<tt>商城奖励商品列表</tt>
	</a>
	<a href="<?php echo site_url('soma/GiftDelivery/gift_list')?>?id=<?php echo $inter_id.'&saler_id='.$saler_details['id'].'&saler_name='.$saler_details['name'].'&inter_id='.$inter_id; ?> " class="item">
		<em class="ui_ico ui_ico13"></em>
		<tt>礼包派送</tt>
	</a>
</div>

<?php  /*会员部分*/ ?>
<?php if(isset($wechat_card_qrcode) && !empty($wechat_card_qrcode)){?>
<div class="ui_btn_list ui_border">
    <a class="item my_wechat_card_code" >
        <em class="ui_ico ui_ico6"></em>
        <tt>微信会员卡二维码</tt>
    </a>
</div>
<?php } ?>

<?php if((isset($deposit_card_show) && $deposit_card_show ) || (isset($balance_show) && $balance_show ) ){ ?>
<div class="ui_btn_list ui_border">
    <?php if(isset($deposit_card_show) && $deposit_card_show ) { ?>
    <a class="item my_depositcard_code" >
        <em class="ui_ico ui_ico6"></em>
        <tt>购买会员卡二维码</tt>
    </a>
    <?php } ?>
    <?php if(isset($balance_show) && $balance_show ){ ?>
    <a class="item my_balance_code" >
        <em class="ui_ico ui_ico6"></em>
        <tt>会员储值二维码</tt>
    </a>
    <?php } ?>
</div>
<?php } ?>
<?php  /*end 会员部分*/ ?>


<div class="ui_btn_list ui_border">
	<a href="<?php echo site_url('distribute/dis_v1/msgs')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico7"></em>
    	<tt>我的消息</tt>
    	<?php if($new_msg_count > 0):?>
    	<span class="ui_red">有<?php echo $new_msg_count;?>条新消息</span>
    	<?php endif;?>
    </a>
</div>
<div class="pull pullerwei my_code" style="display:none" onClick="toclose()">
	<div class="box">
        <div class="bg_fff">
            <div class="pullclose h1 ui_gray">&times;</div>
            <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>
            
            <div class="h padding">扫码关注&nbsp;&nbsp;优惠多多</div>
        </div>
   		<div class="border_hr"></div>
        <div class="bg_fff er_log">
            <img src="<?php echo $saler_details['url'];?>" />
            <p><?php echo $saler_details['name']?>&nbsp;No.<?php echo $saler_details['id']?></p>
            
        	<p class="ui_gray h1" style="padding:2rem;">快捷入住&nbsp;&nbsp;管家服务</p>
        </div>
    </div>
</div>
<?php if(isset($wechat_card_qrcode) && !empty($wechat_card_qrcode)){?>
<div class="pull pullerwei my_wechat_card_qrc" style="display:none" onClick="toclose()">
    <div class="box">
        <div class="bg_fff">
            <div class="pullclose h1 ui_gray">&times;</div>
            <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>

            <div class="h padding">扫码领取会员卡</div>
        </div>
        <div class="border_hr"></div>
        <div class="bg_fff er_log">
            <img src="<?php echo $wechat_card_qrcode;?>" />
        </div>
    </div>
</div>
<?php } ?>
<?php if(isset($deposit_card_show) && $deposit_card_show ) { ?>
    <div class="pull pullerwei my_depositcard_qrc" style="display:none" onClick="toclose()">
        <div class="box">
            <div class="bg_fff">
                <div class="pullclose h1 ui_gray">&times;</div>
                <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>

                <div class="h padding">扫码购买会员卡</div>
            </div>
            <div class="border_hr"></div>
            <div class="bg_fff er_log">
                <img src="<?php echo $deposit_card_url;?>" />
            </div>
        </div>
    </div>
<?php } ?>
<?php if(isset($balance_show) && $balance_show ) { ?>
    <div class="pull pullerwei my_balance_qrc" style="display:none" onClick="toclose()">
        <div class="box">
            <div class="bg_fff">
                <div class="pullclose h1 ui_gray">&times;</div>
                <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>

                <div class="h padding">扫码购买储值</div>
            </div>
            <div class="border_hr"></div>
            <div class="bg_fff er_log">
                <img src="<?php echo $balance_url;?>" />
            </div>
        </div>
    </div>
<?php } ?>

<?php if($inter_id == 'a421641095' || $inter_id == 'a460705829' || $inter_id == 'a463803935' || $inter_id == 'a445223616' || $inter_id == 'a459234737' || $inter_id == 'a441624001'):?>
<div class="pull pullerwei mall_code" style="display:none" onClick="toclose()">
	<div class="box">
        <div class="bg_fff">
            <div class="pullclose h1 ui_gray">&times;</div>
            <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>
            <div class="h padding">扫码关注&nbsp;&nbsp;优惠多多</div>
        </div>
   		<div class="border_hr"></div>
        <div class="bg_fff er_log">
            <img src="<?php echo $mall_qrcode;?>" />
        	<p><?php echo $saler_details['name']?>&nbsp;No.<?php echo $saler_details['id']?></p>
        	<p class="ui_gray h1" style="padding:2rem;">快捷入住&nbsp;&nbsp;管家服务</p>
        </div>
    </div>
</div>
<?php endif;?>

<?php if( $inter_id_in_taopiao ): ?>
<div class="pull pullerwei my_taopiao" style="display:none" onClick="toclose()">
	<div class="box">
        <div class="bg_fff">
            <div class="pullclose h1 ui_gray">&times;</div>
            <div class="ui_gray h1 padding" style="padding-bottom:1%"><?php echo $publics['name']?></div>
            <div class="h padding">扫码购买&nbsp;&nbsp;方便快捷</div>
        </div>
   		<div class="border_hr"></div>
        <div class="bg_fff er_log">
            <img src="<?php echo site_url('distribute/soma_api/show_saler_qrcode');?>?id=<?php echo $inter_id;?>" />
        	<p><?php echo $saler_details['name']?>&nbsp;No.<?php echo $saler_details['id']?></p>
        	<p class="ui_gray h1" style="padding:2rem;">优惠多多&nbsp;&nbsp;惊喜连连</p>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="pull pullhaibao"><div><img src="" ></div></div>
<div class="pull moon_haibao" style="display:none"><div><img src="" ></div></div>
<div style="display:none"><canvas id="canvas"></canvas></div>

<?php if($inter_id == 'a464177542'):?>
<div class="pull tmp_act_pull" onClick="toclose()" style="display:none; font-size:0"><img src="<?php echo base_url('public/distribute/default/images/tmp_act.jpg')?>" style="min-height:100%"></div>
<?php endif;?>
</body>
</html>
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
var _time,_time2,a,b,c;
var bgimg=new Image;
var userimg=new Image;
var erweima=new Image;
var moonbg=new Image;
var moonerweima=new Image;
var getbase64url ="<?php echo site_url('distribute/dis_v12/file2base64')?>?id=<?php echo $inter_id;?>";
var getimgeurl="<?php echo site_url('distribute/dis_v12/base64_2file')?>?id=<?php echo $inter_id;?>";

bgimg.src="<?php if($inter_id_in_haibao){echo base_url('public/distribute/default/images/'.$inter_id.'.jpg');}?>";

<?php if( $inter_id_in_array ): ?>
moonbg.src="<?php echo base_url('public/distribute/default/images/mooncake_bg.jpg')?>";
<?php endif; ?>
$(function(){
	$('.tmp_act').click(function(){
		toshow($('.tmp_act_pull'));
	});
	var canvas=$('#canvas').get(0);
	var isdown=false;
	var isfirst=true;
	var _count =0
	var _count2=0;
	var _rate = 640/1008;
	var _w=$(window).width();
	var _h=_w/_rate;
	if ( _h <$(window).height()) _h=$(window).height();
	var _r = 1.4;// getPixelRatio(canvas.getContext("2d"));
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
	var t_s= parseInt($("body").css('font-size'))*_r*1.3;
	var t_x=_w*170/640;
	var t_y= _h*465/1008+(u_w+t_s)/2;
	$('.my_erwen').click(function(){
		toshow($('.my_code'));
		reset_time('我的二维码海报');
	});

    /*会员部分*/
    $('.my_wechat_card_code').click(function(){
        toshow($('.my_wechat_card_qrc'));
        reset_time('我的二维码海报');
    });
    <?php if(isset($deposit_card_show) && $deposit_card_show ) { ?>
    $('.my_depositcard_code').click(function(){
        toshow($('.my_depositcard_qrc'));
        reset_time('我的二维码海报');
    });
    <?php } ?>
    <?php if(isset($balance_show) && $balance_show ) { ?>
    $('.my_balance_code').click(function(){
        toshow($('.my_balance_qrc'));
        reset_time('我的二维码海报');
    });
    <?php } ?>

    /*end 会员部分*/

	<?php if($inter_id == 'a421641095' || $inter_id == 'a460705829' || $inter_id == 'a463803935'):?>
	$('.my_erwen_mall').click(function(){
		toshow($('.mall_code'));
		reset_time('我的二维码海报');
	});
	<?php endif;?>
	$('._taopiao').click(function(){
		toshow($('.my_taopiao'));
		reset_time('我的二维码海报');
	});
	$('.pullclose').click(toclose);
	
	$('._haibao').on('click',function(){
		u_w=_w*120/640; 
		u_x=_w*30/640;
		u_y=_h*470/1008;
		<?php if($inter_id== 'a457946152'||$inter_id== 'a488251814'):?>
		e_w=_w*150/640;
		e_x=_w*240/640;
		e_y=_h*245/1008;
		<?php elseif($inter_id== 'a464177542'):?>
		e_w=_w*300/640;
		e_x=_w*170/640;
		e_y=_h*400/1008;
		<?php elseif($inter_id== 'a468209719'):?>
		e_w=_w*165/640;
		e_x=_w*240/640;
		e_y=_h*532/1008;
		<?php endif;?>
		if ( isdown) return;
		if(bgimg.src==''){  alert('暂未添加此功能');return false;}
		if ( isFinish() && bgimg.complete ){ 
			if ($('.pullhaibao img').attr('src')=='') cutdown('生成');
			else toshow($('.pullhaibao'));
		}
		else{cutdown('生成');}
	});
	$('._haibao').one('click',function(){
		getUserimg();
		$.get(getbase64url,{'url':'<?php echo $saler_details['url'];?>'},function(data){
			erweima.src="data:image/jpeg;base64,"+data;
			b='complete';
		},'text');
	});
	$('._mooncake').on('click',function(){
	    u_w=_w*112/640; 
	    u_x=_w*268/640;
	    u_y=_h*245/1008;
		e_w=_w*306/640;
		e_x=_w*175/640;
		e_y=_h*592/1008;
		if( isdown) return;
		if( moonbg.src==''){ alert('暂未添加此功能');return false;}
		if ( isFinish2() && moonbg.complete ){ 
			if ($('.moon_haibao img').attr('src')=='') cutdown('生成',2);
			else toshow($('.moon_haibao'));
		}
		else{cutdown('生成',2);}
	});
	$('._mooncake').one('click',function(){
		getUserimg();
		var _url="<?php echo site_url('distribute/soma_api/mk_saler_qrcode');?>?id=<?php echo $inter_id;?>";
		moonerweima.src=_url;
	});
	function isFinish(){
		if( a=='complete' && b=='complete' )return true;
		else return false;
	}
	function isFinish2(){
		if( a=='complete' && moonerweima.complete )return true;
		else return false;
	}
	function getUserimg(){
		if(userimg.src=='')
			$.get(getbase64url,{'url':'<?php echo $saler_details['headimgurl'];?>'},function(data){
				userimg.src="data:image/jpeg;base64,"+data;
				a='complete';
			},'text');
	}
	function creat(bgimg,erweima,str,bool){ /*bool为true不生成头像文字*/
		isdown=true;
		var cxt=canvas.getContext("2d");
		$('#canvas').attr('width',_w);
		$('#canvas').attr('height',_h);
		cxt.drawImage(bgimg,0,0,_w,_h);
		cxt.drawImage(erweima,e_x,e_y,e_w,e_w);
		if(!bool){
			cxt.drawImage(userimg,u_x,u_y,u_w,u_w);
			if(str!='.moon_haibao'){
			var txt='扫码可提供优惠,快捷的管家服务';
			<?php if($inter_id  == 'a452223043'):?>
			txt='莫林 · 美梦开始的地方';
			<?php elseif($inter_id  == 'a445223616'):?> //云盟
			txt='扫码可享订房优惠/乐购商场/粉丝互动';
			cxt.fillStyle = "#026eb3";
			cxt.fillStyle = "#f7a23d";
			<?php elseif($inter_id  == 'a449675133'):?> //书香
			<?php elseif($inter_id  == 'a464177542'):?>  //锦江
			<?php elseif($inter_id  == 'a463105262'):?>  //亲的客栈
			txt='扫我扫我扫我，优惠折扣一起来！';
			cxt.fillStyle = "#990000";
			<?php elseif($inter_id  == 'a455510007'):?>  //速8
			cxt.fillStyle = "#d84840";
			<?php elseif($inter_id  == 'a469428180'||$inter_id  == 'a483582961'):?> //a469428180测试号 a483582961拉萨
			txt = '官方微信预订更优惠';
			var txt2 ='客房供氧';
			cxt.fillStyle = "#104278";
			<?php endif;?>
			cxt.fillRect(r_x,u_y,r_w,u_w);
			cxt.fillStyle = "#ffffff";
			cxt.font=t_s+'px Helvetica Neue,sans-serif';
			<?php if( $inter_id_in_haibao2 ): ?>
			t_y=_h*470/1008+t_s*1.4+(u_w-t_s*3.5)/2;
			var t_y2=t_y+t_s*1.5;
			cxt.fillText(txt,t_x,t_y);
			cxt.fillText(txt2,t_x,t_y2);
			<?php else:?>
			cxt.fillText(txt,t_x,t_y);
			<?php endif; ?>
			}
		}
		$(str+' img').attr('src',canvas.toDataURL('image/jpeg',0.6));
		reset_time( '我的二维码海报');
		toshow($(str)); 
		_count=0;
		_count2=0;
		isdown=false;
	}
	function cutdown(str,_i){
		reset_time('我的二维码海报');
		if(_i==undefined){
			var tmp_this = '._haibao';
			$(tmp_this+' tt').html('正在'+str+'二维码海报,请稍候('+_count+'s)');
			_time=window.setInterval(function(){
				_count++;
				$(tmp_this+' tt').html('正在'+str+'二维码海报,请稍候('+_count+'s)');
				if( isFinish()  && bgimg.complete){
					if (!isdown) creat(bgimg,erweima,'.pullhaibao' <?php if($inter_id == 'a457946152'|| $inter_id=='a468209719' || $inter_id  == 'a464177542'||$inter_id== 'a488251814'){echo ',true';}?>);
				}
				if (_count>180){
					_count=0;
					isdown=false;
					reset_time(str+'二维码海报');
				}
			},1000);
		}else{
			var tmp_this = '._mooncake';
			$(tmp_this+' tt').html('正在'+str+'二维码海报,请稍候('+_count2+'s)');
			_time2=window.setInterval(function(){
				_count2++;
				$(tmp_this+' tt').html('正在'+str+'二维码海报,请稍候('+_count2+'s)');
				if(isFinish2() && moonbg.complete){
					if (!isdown) creat(moonbg,moonerweima,'.moon_haibao');
				}
				if (_count2>180){
					_count2=0;
					isdown=false;
					reset_time(str+'二维码海报');
				}
			},1000);
		}
	}
	function reset_time(str){
		window.clearInterval(_time);
		window.clearInterval(_time2);
		$('._haibao tt').html(str);
		$('._mooncake tt').html('月饼二维码海报');
	}
})

</script>