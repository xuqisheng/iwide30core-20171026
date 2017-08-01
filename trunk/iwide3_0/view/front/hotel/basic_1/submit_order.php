<?php include 'header.php'?>
<?php echo referurl('js','submit_order.js?v='.time(),1,$media_path) ?>
<?php echo referurl('css','submit_order.css',1,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('js','date.js',1,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<style>
.checkin .date:after{content:"入住"}
.checkout .date:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.n:before{content:""}
.morning:before{content:"次日"}
.dawn:before{content:"凌晨"}
</style>
<input type="hidden" id="startdate" name="startdate" value="<?php echo $startdate;?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo $enddate;?>" />
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel_id;?>" />
<input type="hidden" id="price_codes" name="price_codes" value='<?php echo $price_codes;?>' />
<input type="hidden" id="price_type" name="price_type" value='<?php echo $price_type;?>' />
<input type="hidden" id="prevend" name="prevend" value='0' />
<input type="hidden" id="datas" name="datas" value='<?php echo $source_data;?>' />
<input type="hidden" id="pay_type" name="pay_type" value="<?php echo  empty($pay_ways)?'':$pay_ways[0]->pay_type;?>" />
<header class="order_intro">
	<div class="hotelname"><?php echo $hotel['name']?></div>
    <div class="datetime"><?php echo date("m月d日",strtotime($startdate));?><?php if(empty($athour)){?>-<?php echo date("m月d日",strtotime($enddate));?>  共<?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?>晚<?php }?></div>
    <div class="room_type">房型:<?php foreach($room_list as $rl)echo $rl['name'].' ';?>(<?php echo $first_state['price_name'];?>)</div>
	<div class="sever"><?php if(!empty($first_room['room_info']['imgs']['hotel_room_service']))foreach($first_room['room_info']['imgs']['hotel_room_service'] as $hs)echo $hs['info'].' ';?></div>
</header>

<!-- 预订 -->
<div class="form_list">
	<div class="item ui_btn_block room_count" >
    	<span>房间数</span>
        <select class="num big" id="roomnum" rid="<?php echo $first_room['room_info']['room_id'];?>">
        <?php for($i=1;$i<=$first_state['least_num'];$i++){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?>间</option>
        	<?php }?>
        </select>
    </div>
	<div class="item ui_btn_block room_num" <?php if(empty($hotel_config['ROOM_NO_SELECT'])||$hotel_config['ROOM_NO_SELECT']==0){?>style="display:none;"<?php }?>>
    	<span>房间号</span>
        <span class="num">前台分配(<tt>1</tt>间)</span>
    </div>
	<div class="item">
    	<span>入住人</span>
        <input type="text" id='name' name='name' required class="person" placeholder="请输入姓名" value="<?php echo empty($last_order)?'':$last_order['name']?>" />
    </div>
	<div class="item">
    	<span>手机号</span>
        <input type="tel" id='tel' name='tel' required class="phone" placeholder="请输入手机号" value="<?php echo empty($last_order)?'':$last_order['tel']?>" />
    </div>
</div>
<!-- 以上为预订部分 -->
<?php if(!empty($athour)){?>
<!-- 钟点房/时租房 -->
<div class="form_list">
<?php if(!empty($first_state['condition']['book_times'])){?>
	<div class="item ui_btn_block">
    	<span>入住时间</span>
        <span class="addhour_date"><?php echo date('H:00',strtotime($first_state['condition']['book_times'][0]));?></span>
    </div>
     <?php }?>
     <?php if(!empty($add_time_service['add_times'])){?>
	<div class="item ui_btn_block">
    	<span>加时服务</span>
        <span class="addhour_server">请选择加时服务</span>
    </div>
    <?php }?>
    <?php if(!empty($first_state['condition']['last_time'])){?>
	<div class="item">
    	<span>离店时间</span>
        <span class="addhour_leave"><?php echo date('H:00',strtotime($first_state['condition']['last_time']));?></span>
    </div>
     <?php }?>
</div>
<?php if(!empty($first_state['condition']['book_times'])){?>
<div class="ui_pull addhour_date_pull" style="display:none">
    <div class="pulltitle">请选择入住时间</div> 
    <ul>
        <?php foreach ($first_state['condition']['book_times'] as $bt){?>
        <li><?php if( date('H',strtotime($bt))==0)echo '24:00';else echo date('H:00',strtotime($bt));?></li>
        <?php }?> 
    </div>
</div>
<?php }?>
<?php if(!empty($add_time_service['add_times'])){?>
<div class="ui_pull addhour_server_pull" style="display:none">
    <div class="pulltitle">请选择加时服务</div>
    <ul service_id='<?php echo $add_time_service['service_id']?>' onclick='add_service_info($(this))'>
        <li><tt></tt>不加时</li>
         <?php foreach ($add_time_service['add_times'] as $at){?>
        <li>+<tt><?php echo $at;?></tt>小时<dt><?php echo $at*$add_time_service['service_price'];?></dt>元</li>
         <?php }?>
    </ul>
</div>
 <?php }?>
 
<script>
	var str_a = $('.addhour_date').html().split(':');
	var str_b = $('.addhour_leave').html().split(':');
	var str_tmp =  $('.addhour_date_pull li').eq($('.addhour_date_pull li').length-1).html().split(':');
	if ( parseInt(str_b[0]) < parseInt(str_a[0]) )  str_b[0]=parseInt(str_b[0])+24;
	var _min = parseInt(str_b[0])-parseInt(str_a[0]);
	var _max = parseInt(str_tmp[0])+_min;
	
	function r(s){
		if ( s>24 ){
			s=s-24;$('.addhour_leave').addClass('morning');
			if( s<6)$('.addhour_leave').addClass('dawn');
		}
		else{$('.addhour_leave').removeClass('dawn morning');}
		if( s<10){s='0'+s;}
		return s+':00';
	}
	function L(str){
	}	
	function M(num){
		var b=parseInt($('.addhour_date').html().split(':')[0]);
		return  parseInt(num)+b+_min;
	}
	
	$('.addhour_date_pull li').click(function(){  //选择入住时间
		var b = parseInt($(this).html().split(':')[0]);
		var s = b+_min;
		$('.addhour_server').html('不加时');
		$('.addhour_leave').html(r(s));
		$('.addhour_date').html($(this).html());
		toclose();
	})
	$('.addhour_server_pull li').click(function(){  //选择加时服务
		var s = M($('tt',this).html() || '0');
		if (s >_max){
			if ($('tt',this).html()=='')
				$('.addhour_server').html($(this).html());
			return;
		}
		$('.addhour_leave').html(r(s));
		$('.addhour_server').html($(this).html());
		toclose();
	})
	$('.addhour_date').parent().click(function(){
		toshow($('.addhour_date_pull'));
	})
	$('.addhour_server').parent().click(function(){
		toshow($('.addhour_server_pull'));
		var tmp;
		for ( var i=0; i< $('.addhour_server_pull li').length;i++){
			tmp = $('.addhour_server_pull li').eq(i).find('tt').html();
			if ( tmp == '') tmp=0;else tmp = M(tmp);
			if( tmp >_max) $('.addhour_server_pull li').eq(i).css('color','#999');
			else $('.addhour_server_pull li').eq(i).css('color','#555')
		}
	})
</script>
<!-- 以上为 钟点房/时租房 部分 -->
<?php }?>
<!-- 续住 -->
<div class="form_list" style="display: none">
	<div class="item ui_btn_block ">
    	<span>续住时间</span>
        <div class="checkdate" id='checkdate'>
            <span class="checkin" id='checkin'><span class="date">1月1日</span></span>
            <span class="checkout" id='checkout'><span class="date">1月1日</span></span>
            <span class="checkin_time ui_color">1</span>
        </div>
    </div>
	<div class="item">
    	<span>续住房型</span>
        <span>高级双人房</span>
    </div>
	<div class="item ui_btn_block">
    	<span>续住房号</span>
        <span>8899</span>
    </div>
</div>

<!-- 以上为 续住 部分 -->
<div class="form_list">
	<div class="item" style="display: none">
    	<span>连住优惠</span>
        <span class="pay_way ischeck"><em class="ui_ico ui_ico13"></em><span>连住3天抵减300元</span></span>
    </div>
   <?php if(!empty($member->mem_id)&&!empty($point_consum_rate)){?>
	<div class="item">
    	<span>积分抵用</span>
        <input max="<?php echo $member->bonus;?>" type="tel" id='bonus' name='bonus' placeholder="共<?php echo $member->bonus;?>积分，最多可抵<?php echo $member->bonus*$point_consum_rate; ?>元" />
    </div>
    <?php } ?>
   <?php if(empty($first_state['condition']['no_coupon'])) {?>
	<div class="item ui_btn_block usevote" abled='1'>
    	<span>优惠券</span>
		<span id="coupon_i"><?php echo empty($first_state['coupon_condition']['couprel_info'])?'选择优惠券':$first_state['coupon_condition']['couprel_info']['title']; ?></span>
    </div>
    <?php }?>
</div>
<div class="form_list">
	<div class="item pay_list">
    	<span>支付方式</span>
        <ul><?php if(!empty($pay_ways)) foreach($pay_ways as $k=>$pw){?>
			<li pay_type='<?php echo $pw->pay_type;?>' abled='1' class="pay_way <?php if($k==0){?>ischeck<?php }?>"><em class="ui_ico ui_ico13"></em><span><?php echo $pw->pay_name;?><?php if($pw->pay_type=='balance') { ?>(<?php echo $member->balance;?>元)<?php }else if($pw->pay_type=='point') {?>(<?php if ($inter_id=='a421641095')echo $member->bonus.'/'.$member->bonus/10;else echo $member->bonus.'/'.$member->bonus/100;?>)<?php }?></span></li>            <?php }?>
            <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
            <li id='bonus_pay_way' pay_type='bonus' <?php if($point_exchange['can_exchange']==0){?>style='color:gray;' abled='0'<?php }else{?>abled='1'<?php }?> class="pay_way"><em class="ui_ico ui_ico13"></em><span>积分兑换(<?php echo $point_exchange['point_need'];?>/<?php echo $member->bonus;?>)</span></li>
            <?php }?>
        </ul>
    </div>
</div>
<div class="form_list" id='consume_code' <?php if($pay_ways[0]->pay_type!='balance'||$banlance_code==0){?>style="display:none"<?php }?>>
	<div class="item">
    	<span>消费密码</span>
        <input type="password" id='consume_pwd' placeholder="请输入消费密码"/>
    </div>
</div>
<?php if(!empty($hotel['book_policy'])){?>
<div class="notic">
	<div class="title">温馨提示</div>
    <div class="content">
    	<p><?php echo nl2br($hotel['book_policy']);?></p>
    </div>
</div>
<?php }?>
<div style="padding-top:15%">
    <div class="footfixed">
    	<div class="total ui_color">合计 <span id="total_price" class="ui_price"><?php echo $total_price;?></span>
    	<?php if($total_oprice>$total_price){?> <del id="total_oprice" class="ui_price"><?php echo $total_oprice;?></del><?php }?></div>
        <span class="submit_btn">提交订单</span>
    </div>
</div>


<div class="ui_pull chooseroom_pull" style="display:none">
    <div class="list">
        <div class="default ischoose">前台分配(<tt>1</tt>间)</div>
        <?php foreach($rooms as $rm){?>
        <div class="item" rid="<?php echo $rm['room_info']['room_id'];?>">
            <div class="room_name"><?php echo $rm['room_info']['name'];?>(<span><?php echo count($rm['room_info']['number_realtime']);?></span>间)</div>
            <?php foreach($rm['room_info']['number_realtime'] as $rnd){?>
            <div class="roomid" rno="<?php echo $rnd['num_id'];?>" rnn="<?php echo $rnd['room_no'];?>"><span><?php echo $rnd['room_no'];?></span><?php if(!empty($rnd['des'])){?>(<?php echo $rnd['des'];?>)<?php }?></div>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <div class="sure_btn">确定</div>
</div>


<div class="ui_pull vote_pull" style="display:none">
	<div class="list">
        <div class="notic">
            <div class="title">温馨提示</div>
            <div class="content">
	            <?php if(!empty($coupon_tips)){ ?><p><?php echo $coupon_tips; ?></p><?php }else{ ?>
					<p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
					<p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
	            <?php } ?>
            </div>
        </div>
        <ul class="votelist" id="votelist"></ul>
    </div>
    <div class="footbtn" style="border-top-color:#fff !important;">确定</div>
</div>
</body>
<script>
var csrf_name='<?php echo $csrf_token;?>';
var csrf_value='<?php echo $csrf_value;?>';
var roomnos={}; 
var coupons={}; 
var add_services={};
var roomnums=JSON.parse($('#datas').val()); 
var total_price=<?php echo $total_price;?>;
var real_price=total_price;
var total_oprice=<?php echo $total_oprice;?>;
var total_favour=0;
var coupon_amount=0;
var max_room_night_use=0;
var max_order_use=0;
var max_coupon_use=0;
var room_night_use=0;
var order_use=0;
var paytype_counts=0;
var use_flag='';
var banlance_code=<?php echo $banlance_code?>;
$(function(){
	<?php if(empty($first_state['coupon_condition']['couprel_info'])){ ?>
	ajax_coupon();

	$('.usevote').click(function(){
		if($(this).attr('abled')==0){
			return false;
		}
		pageloading();
		$.post('/index.php/hotel/hotel/return_usable_coupon?id=<?php echo $inter_id;?>',{
			datas:JSON.stringify(roomnums),
			start:$('#startdate').val(),
			end:$('#enddate').val(),
			h:$('#hotel_id').val(),
			total:total_price*$('#roomnum').val(),
			price_code:$('#price_codes').val(),
			paytype:$('#pay_type').val()
		},function(data){
			temp='';
			if(data.cards!=''){
				if(data.vid==undefined||data.vid==0){
					max_room_night_use=data.count.max_room_night_use;
					max_order_use=data.count.max_order_use;
					$.each(data.cards,function(i,n){
						temp+='<li onclick="choose_coupon(this)" ';
						if(coupons[n.code]!=undefined){
							temp+=' class="ischeck"';
							if(n.hotel_use_num_type=='room_nights')
								max_room_night_use--;
							else if(n.hotel_use_num_type=='order')
								max_order_use--;
						}
						temp+=' code='+n.code+' amount="'+n.reduce_cost+'" max_use_num="'+n.hotel_max_use_num+'" card_type="'+n.ci_id+'" use_num_type="'+n.hotel_use_num_type+'"><div class="checkbox"><em class="ui_ico ui_ico13"></em></div><div class="ui_vote"><p class="bordertop_img"></p><div class="vote_con">';
						temp+='<p class="ui_price ui_color">'+n.reduce_cost+'元</p>';
						temp+='<p><b>'+n.title+'</b></p>';
						temp+='<p class="ui_gray">'+n.brand_name;
						if(n.is_wxcard==1)temp+='--已添加到卡包';
						temp+='</p></div><div class="val_date">';
						temp+='<p class="ui_red"><!--还有4天过期--></p>';
						temp+='<p class="ui_gray">有效期至'+getLocalTime(n.date_info_end_timestamp)+'</p></div></div></li>';
					});
				}else{
					max_coupon_use=data.count.num;
					if(data.count.effects!=undefined&&data.count.effects.paytype_counts!=undefined){
						paytype_counts=data.count.effects.paytype_counts;
					}
					$.each(data.cards,function(i,n){
						temp+='<li onclick="choose_coupon_v(this)" ';
						if(coupons[n.code]!=undefined){
							temp+=' class="ischeck"';
							max_coupon_use--;
						}
						temp+=' code='+n.code+' amount="'+n.reduce_cost+'" card_type="'+n.ci_id+'" ><div class="checkbox"><em class="ui_ico ui_ico13"></em></div><div class="ui_vote"><p class="bordertop_img"></p><div class="vote_con">';
						temp+='<p class="ui_price ui_color">'+n.reduce_cost+'元</p>';
						temp+='<p><b>'+n.title+'</b></p>';
						temp+='<p class="ui_gray">'+n.brand_name;
						if(n.is_wxcard==1)temp+='--已添加到卡包';
						temp+='</p></div><div class="val_date">';
						temp+='<p class="ui_red"><!--还有4天过期--></p>';
						temp+='<p class="ui_gray">有效期至'+n.valid_date+'</p></div></div></li>';
					});
				}
			}
			else{
               temp+='<li class="ischeck" ><div class="ui_vote" style="width:90%"><p class="bordertop_img"></p><div class="vote_con"><p class="votename" style="text-align: center;">暂无可用优惠券哦</p></div></div></li>';
			}
			$('#votelist').html(temp);
			$('.page_loading').remove();
		},'json');
		toshow($('.vote_pull'));
	});
	<?php } ?>
	$('.vote_pull .footbtn').click(function(){
		if(coupon_amount>0)
			$('#coupon_i').html('已选￥'+coupon_amount);
		else
			$('#coupon_i').html('选择优惠券');
		toclose();
	});
	$('.room_count .num').change(function(){
		var tmpval=$(this).val();
		real_price=total_price*tmpval;
		$('#total_price').html((total_price*tmpval).toFixed(2));
		$('#total_oprice').html(total_oprice*tmpval);
		roomnos={};
		rid=$(this).attr('rid');
		roomnums[rid]=tmpval;
		$('.room_num .num').html('前台分配(<tt>'+tmpval+'</tt>间)');
		$('.default tt').html(tmpval);
		$('.default').trigger('click');

		$('#coupon_i').html('选择优惠券');
		total_favour-=coupon_amount;
		coupon_amount=0;
		coupons={}; 
		$('#total_price').html((real_price-total_favour).toFixed(2));

		 <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
		 	var need_point=<?php echo $point_exchange['point_need'];?>*tmpval;
		 	$('#bonus_pay_way').find('span').html("积分兑换("+need_point+"/<?php echo $member->bonus;?>)");
		 	if(need_point><?php echo $member->bonus;?>){
		 		$('#bonus_pay_way').css('color','gray');
		 		$('#bonus_pay_way').attr('abled','0');
		 		$('#bonus_pay_way').parent().find('li:first-child').click();
			}else{
				$('#bonus_pay_way').removeAttr('style');
				$('#bonus_pay_way').attr('abled','1');
			}
		 <?php }?>
		ajax_coupon();
	});
})
function choose_coupon(obj){
	if ( $(obj).hasClass('ischeck')){
		$(obj).removeClass('ischeck');
		if(coupons[$(obj).attr('code')]!=undefined){
			delete(coupons[$(obj).attr('code')]);
			if(getJsonObjLength(coupons)==0)use_flag='';
			coupon_amount-=$(obj).attr('amount')*1;
			total_favour-=$(obj).attr('amount')*1;
			if($(obj).attr('use_num_type')=='room_nights')
				max_room_night_use++;
			else if($(obj).attr('use_num_type')=='order')
				max_order_use++;
		}
	}
	else{
		if(!use_flag)
			use_flag=$(obj).attr('use_num_type');
		if(use_flag!=use_flag)return;
		if($(obj).attr('use_num_type')=='room_nights'){
			if(max_room_night_use>0)
				max_room_night_use--;
			else return;
		}
		else if($(obj).attr('use_num_type')=='order'){
			if(max_order_use>0)
				max_order_use--;
			else return;
		}
		$(obj).addClass('ischeck');
		coupons[$(obj).attr('code')]=$(obj).attr('amount');
		coupon_amount+=$(obj).attr('amount')*1;
		total_favour+=$(obj).attr('amount')*1;
	}
	$('#total_price').html((real_price-total_favour).toFixed(2));
}
function choose_coupon_v(obj){
	if ( $(obj).hasClass('ischeck')){
		$(obj).removeClass('ischeck');
		if(coupons[$(obj).attr('code')]!=undefined){
			delete(coupons[$(obj).attr('code')]);
			if(getJsonObjLength(coupons)==0)use_flag='';
			coupon_amount-=$(obj).attr('amount')*1;
			total_favour-=$(obj).attr('amount')*1;
			max_coupon_use++;
		}
	}
	else{
		if(max_coupon_use>0)
			max_coupon_use--;
		else return;
		$(obj).addClass('ischeck');
		coupons[$(obj).attr('code')]=$(obj).attr('amount');
		coupon_amount+=$(obj).attr('amount')*1;
		total_favour+=$(obj).attr('amount')*1;
	}
	$('#total_price').html((real_price-total_favour).toFixed(2));
}
function getJsonObjLength(jsonObj) {
        var Length = 0;
        for (var item in jsonObj) {
            Length++;
        }
        return Length;
}
function getLocalTime(nS) {     
    return new Date(parseInt(nS) * 1000).toLocaleString().substr(0,10)
}  
function add_service_info(obj){
	
}

//自动选择优惠券
function ajax_coupon(){
	<?php if(empty($first_state['coupon_condition']['couprel_info'])){ ?>
	$.post('/index.php/hotel/hotel/return_usable_coupon?id=<?php echo $inter_id;?>',{
		datas:JSON.stringify(roomnums),
		start:$('#startdate').val(),
		end:$('#enddate').val(),
		h:$('#hotel_id').val(),
		total:total_price*$('#roomnum').val(),
		price_code:$('#price_codes').val(),
		paytype:$('#pay_type').val()
	},function(data){
		if(data.selected!=''){
			var bool = false;
			if(data.vid==undefined||data.vid==0){ bool=true;}

			if(bool){
				max_room_night_use=data.count.max_room_night_use;
				max_order_use=data.count.max_order_use;
			}else{
				max_coupon_use=data.count.num;
				if(data.count.effects!=undefined&&data.count.effects.paytype_counts!=undefined){
					paytype_counts=data.count.effects.paytype_counts;
				}
			}
			coupons={};
			$.each(data.selected,function(i,n){
				coupons[n.code]=n.reduce_cost;
				coupon_amount+=parseFloat(n.reduce_cost);
				total_favour+=parseFloat(n.reduce_cost);

				if(n.hotel_use_num_type=='room_nights' && bool)
					max_room_night_use--;
				else if(n.hotel_use_num_type=='order' && bool)
					max_order_use--;
				else if(!bool)max_coupon_use--;
			});

			$('#total_price').html((real_price-total_favour).toFixed(2));

			if(coupon_amount>0)
				$('#coupon_i').html('已选￥'+coupon_amount);
			else
				$('#coupon_i').html('选择优惠券');
//			getBonusSet();
		}
	},'json');
	<?php }else{ ?>
//	getBonusSet();
	<?php } ?>
}
</script>
</html>
