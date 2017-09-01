<?php include 'header.php'?>
<?php echo referurl('js','submit_order.js',1,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<style>
.checkin:after{content:"入住"}
.checkout:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.morning:before{content:"次日"}
.dawn:before{content:"凌晨"}
.input_item>*{display:block}
</style>
<input type="hidden" id="order_sub_url" name="order_sub_url" value="<?php echo Hotel_base::inst()->get_url("SAVEORDER");?>" />
<input type="hidden" id="startdate" name="startdate" value="<?php echo $startdate;?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo $enddate;?>" />
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel_id;?>" />
<input type="hidden" id="price_codes" name="price_codes" value='<?php echo $price_codes;?>' />
<input type="hidden" id="price_type" name="price_type" value='<?php echo $price_type;?>' />
<input type="hidden" id="prevend" name="prevend" value='0' />
<input type="hidden" id="datas" name="datas" value='<?php echo $source_data;?>' />
<input type="hidden" id="pay_type" name="pay_type" value="<?php echo  empty($pay_ways)?'':$pay_ways[0]->pay_type;?>" />
<header class="pad3">
	<div class="h30"><?php echo $hotel['name']?></div>
    <div class="h24"><?php echo date("m月d日",strtotime($startdate));?></div>
    <div class="h24 color_888 martop">房型:<?php foreach($room_list as $rl)echo $rl['name'].' ';?>(<?php echo $first_state['price_name'];?>)</div>
	<div class="h24 color_888"><?php if(!empty($first_room['room_info']['imgs']['hotel_room_service']))foreach($first_room['room_info']['imgs']['hotel_room_service'] as $hs)echo $hs['info'].' ';?></div>
</header>

<!-- 预订 -->
<div class="list_style bd">
	<div class="arrow input_item room_count" >
    	<span>房间数</span>
        <select class="num h28" id="roomnum" rid="<?php echo $first_room['room_info']['room_id'];?>">
        <?php for($i=1;$i<=$first_state['least_num'];$i++){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?>间</option>
        	<?php }?>
        </select>
    </div>
	<div class=" input_item room_num" <?php if(empty($hotel_config['ROOM_NO_SELECT'])||$hotel_config['ROOM_NO_SELECT']==0){?>style="display:none;"<?php }?>>
    	<span>房间号</span>
        <span class="num">前台分配(<tt>1</tt>间)</span>
    </div>
	<div class="input_item">
    	<span>入住人</span>
        <div><input type="text" id='name' name='name' required class="person" placeholder="请输入姓名" value="<?php echo empty($last_order)?'':$last_order['name']?>" /></div>
    </div>
	<div class="input_item">
    	<span>手机号</span>
        <div><input type="tel" id='tel' name='tel' required class="phone" placeholder="请输入手机号" value="<?php echo empty($last_order)?'':$last_order['tel']?>" /></div>
    </div>
	<div class="arrow input_item room_count" >
    	<span>到店时间</span>
        <select name="intime" id="intime" class="h28" style="width:75%">
        <?php foreach ($selecttime as $key => $value) {?>
        	<option value="<?php echo $value?>" <?php if($key == 0) echo 'selected';?>><?php echo $value.'前';?></option>
        <?php } ?>
        </select>
    </div>
</div>
<!-- 以上为预订部分 -->
<!-- 续住 -->
<div class="list_style bd" style="display:none">
	<div class="arrow input_item">
    	<span>续住时间</span>
        <span id='checkdate'>
            <span class="checkin" id='checkin'>1月1日</span>
            <span class="checkout" id='checkout'>1月1日</span>
            <span class="checkin_time color_main">1</span>
        </span>
    </div>
	<div class="input_item">
    	<span>续住房型</span>
        <span>高级双人房</span>
    </div>
	<div class="input_item">
    	<span>续住房号</span>
        <span>8899</span>
    </div>
</div>

<!-- 以上为 续住 部分 -->
<div class="list_style bd martop">
   <?php if(!empty($member->mem_id)&&!empty($point_consum_rate)&&empty($first_state['bonus_condition']['no_part_bonus'])){?>
	<div class="input_item">
    	<span><?php echo $point_name;?>抵用</span>
        <div style="width:100%;display:inline"><input max="<?php echo $member->bonus;?>" type="tel" id='bonus' name='bonus' 
       <?php if (!empty($point_consum_set['max_use'])){?>    
    	placeholder="最多可用<?php echo $point_consum_set['max_use'];?><?php echo $point_name;?>，最多可抵<?php echo $point_consum_set['max_use']*$point_consum_rate; ?>元" 
    	<?php }else{?>
        placeholder="共<?php echo $member->bonus;?><?php echo $point_name;?>，最多可抵<?php echo $member->bonus*$point_consum_rate; ?>元"
        <?php }?> /></div>
    </div>
   <?php } else{?>
	<div class="input_item" style="display:none">
    	<span><?php echo $point_name;?>抵用</span>
        <div style="width:100%;display:inline"><input type="tel" id='bonus' name='bonus' placeholder="" /></div>
    </div>
    <?php }?>
   	<?php if(empty($first_state['condition']['no_coupon'])&&empty($first_state['coupon_condition']['no_coupon'])) {?>
	<div class="input_item arrow usevote">
    	<span>优惠券</span>
		<span id="coupon_i"><?php echo empty($first_state['coupon_condition']['couprel_info'])?'选择优惠券':$first_state['coupon_condition']['couprel_info']['title']; ?></span>
    </div>
    <?php }?>
</div>
<div class="martop pad3 webkitbox input_item bg_fff bd" style="-webkit-box-align:baseline; padding-right:0">
    <span>支付方式</span>
    <div class="pay_list">
    <?php if(!empty($pay_ways)) foreach($pay_ways as $k=>$pw){?>
        <div pay_type='<?php echo $pw->pay_type;?>' pname="<?php echo $pw->pay_name;?>" pfavour="<?php echo $pw->favour;?>" class="pay_way <?php if($k==0){?>ischeck<?php $first_pay_favour=$pw->favour;}?> <?php if (!empty($pw->disable))echo 'disable'?>">
            <span class="color_main"><em class="iconfont">&#x4f;</em></span>
            <span><?php echo $pw->pay_name;?><?php echo $pw->des;?> <?php if($pw->pay_type=='point') { $valid_point=1; }?></span>
        </div>
        <?php }?>
        <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
        <div id='bonus_pay_way' pay_type='bonus' class="pay_way <?php if($point_exchange['can_exchange']==0){?>disable<?php }?>">
            <span class="color_main"><em class="iconfont">&#x4f;</em></span>
            <span><?php echo $point_name;?>兑换(<?php echo $point_exchange['point_need'];?>/<?php echo $member->bonus;?>)</span>
        </div>
        <?php }?>
        <?php if(empty($valid_point)){?>
           	<div id="point_pay_way" style="display: none" pay_type="point" class="pay_way">
           	<span class="color_main"><em class="iconfont">&#x4f;</em></span><span><?php echo $point_name;?>支付</span>
           	</div>
        <?php }?>
    </div>
</div>
<div class="list_style bd martop" id='consume_code' <?php if($pay_ways[0]->pay_type!='balance'||$banlance_code==0){?>style="display:none"<?php }?>>
	<div class="input_item">
    	<span>消费密码</span>
        <div><input type="password" id='consume_pwd' placeholder="请输入消费密码"/></div>
    </div>
</div>
<?php if(!empty($hotel['book_policy'])){?>
<div class="pad3"> 
	<div class="h30">温馨提示</div>
    <div class="color_888 h22"><?php echo nl2br($hotel['book_policy']);?></div>
</div>
<?php }?>
<div style="padding-top:15%">
    <div class="foot_fixed">
    	<div class="color_minor pad3 bd_top_img">
        	合计 <?php if (!isset($total_point)){?>
        	<span id="total_price" class="y h36"><?php echo $total_price-$first_pay_favour;?></span>
        	<?php }else{?>
        	<span id="total_point" class="h36"><?php echo $total_point;?> <?php echo $point_name;?></span>
        	<span id="total_price" class="y h36" style="display: none"><?php echo $total_price-$first_pay_favour;?></span>
        	<?php }?>
        	<?php if (!isset($total_point)){?>
				<?php if($total_oprice>$total_price){?>
	        	<del id="total_oprice" class="y color_888 h22"><?php echo $total_oprice;?></del><?php }?>
        	<?php }?>
        </div>
        <span class="bg_main center pad10 submit_btn">提交订单</span>
    </div>
</div>


<div class="ui_pull chooseroom_pull bg_fff" style="display:none">
    <div>
        <div class="default color_main pad10 bd_bottom">前台分配(<tt>1</tt>间)</div>
        <?php foreach($rooms as $rm){?>
        <div class="scroll" rid="<?php echo $rm['room_info']['room_id'];?>">
            <div class="room_name bg_E4E4E4 center pad10"><?php echo $rm['room_info']['name'];?>(<span><?php echo count($rm['room_info']['number_realtime']);?></span>间)</div>
            <?php foreach($rm['room_info']['number_realtime'] as $rnd){?>
            <div class="roomid pad10 bd_bottom" rno="<?php echo $rnd['num_id'];?>" rnn="<?php echo $rnd['room_no'];?>" style="display:block"><span><?php echo $rnd['room_no'];?></span><?php if(!empty($rnd['des'])){?>(<?php echo $rnd['des'];?>)<?php }?></div>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <div class="sure_btn bottomfixed bg_main center pad10 h32">确定</div>
</div>


<div class="ui_pull bg_F8F8F8 vote_pull " style="display:none">
    <div tips class="pad3 bg_fff">
        <div class="h30">温馨提示</div>
        <div class="h22">
	        <?php if(!empty($coupon_tips)){ ?><p><?php echo $coupon_tips; ?></p><?php }else{ ?>
				<p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
				<p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
	        <?php } ?>
        </div>
    </div>
    <ul class="votelist scroll bg_F8F8F8" id="votelist"></ul>
    <div footbtn class="bg_main">确定</div>
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
var part_bonus_set={};
<?php if (isset($point_consum_set)){?>
part_bonus_set=JSON.parse('<?php echo json_encode($point_consum_set);?>');
<?php }?>
var point_pay_set={};
<?php if (isset($point_pay_set)){?>
point_pay_set=JSON.parse('<?php echo json_encode($point_pay_set);?>');
<?php }?>
var bonus_condition={};
<?php if (isset($first_state['bonus_condition'])){?>
bonus_condition=JSON.parse('<?php echo json_encode($first_state['bonus_condition']);?>');
<?php }?>
var no_part_bonus=<?php echo empty($first_state['bonus_condition']['no_part_bonus'])?0:$first_state['bonus_condition']['no_part_bonus'];?>;
var has_point_pay=<?php echo empty($has_point_pay)?0:1;?>;
var click_check = false;
var point_name='<?php echo empty($point_name)?'积分':$point_name;?>';
var pay_favour=<?php echo empty($first_pay_favour)?0:$first_pay_favour;?>;
total_favour+=pay_favour*1;
$(function(){
	<?php if(empty($first_state['coupon_condition']['couprel_info'])){ ?>
	$('.usevote').click(function(){
		if($(this).hasClass('disable')){
			return false;
		}
		pageloading();
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_USABLE_COUPON");?>',{
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
				$.each(data.cards,function(i,n){
					temp+='<li onclick="choose_coupon(this,'+bool+')"';
					if(coupons[n.code]!=undefined){
						temp+=' class="ischeck"';
						if(n.hotel_use_num_type=='room_nights' && bool)
							max_room_night_use--;
						else if(n.hotel_use_num_type=='order' && bool)
							max_order_use--;
						else if(!bool)max_coupon_use--;
					}
					temp+=' code='+n.code+' amount="'+n.reduce_cost+'" card_type="'+n.ci_id+'"';
					if(bool)temp+=' max_use_num="'+n.hotel_max_use_num+'" use_num_type="'+n.hotel_use_num_type+'"';
					temp+='><div checkbox class="color_main"><em class="iconfont">&#x4f;</em></div><div class="ui_vote"><p class="bordertop_img"></p><div class="vote_con">';
					temp+='<p rebate class="color_main">'+n.reduce_cost+'元</p>';
					temp+='<p><b>'+n.title+'</b></p>';
					temp+='<p class="color_888">'+n.brand_name;
					if(n.is_wxcard==1)temp+='--已添加到卡包';
					temp+='</p></div><div class="val_date bd_top">';
					temp+='<p class="color_key"><!--还有4天过期--></p>';
					temp+='<p class="color_888">有效期至';
					if(bool)temp+=getLocalTime(n.date_info_end_timestamp);
					else temp+=n.valid_date;
					temp+='</p></div></div></li>';
				});
			}
			else{
               temp+='<li class="ischeck" ><div class="ui_vote" style="width:90%"><p class="bordertop_img"></p><div class="vote_con"><p class="votename" style="text-align: center;">暂无可用优惠券哦</p></div></div></li>';
			}
			$('#votelist').html(temp);
			removeload();
			toshow($('.vote_pull'));
			var _h=$(window).height()-$('.vote_pull [tips]').outerHeight()-$('.vote_pull [footbtn]').outerHeight();
			$('#votelist').height(_h-10);
		},'json');
	});
	<?php } ?>
	$('.vote_pull [footbtn]').click(function(){
		if(coupon_amount>0)
			$('#coupon_i').html('已选￥'+coupon_amount);
		else
			$('#coupon_i').html('选择优惠券');
		toclose();
		getBonusSet();
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
		getBonusSet();
		getPointpaySet();
		 <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
		 	var need_point=<?php echo $point_exchange['point_need'];?>*tmpval;
		 	$('#bonus_pay_way').find('span').html(point_name+"兑换("+need_point+"/<?php echo $member->bonus;?>)");
		 	if(need_point><?php echo $member->bonus;?>){
		 		$('#bonus_pay_way').addClass('disable');
		 		$('#bonus_pay_way').parent().find('li:first-child').click();
			}else{
				$('#bonus_pay_way').removeClass('disable');
			}
		 <?php }?>
		
	});
})
function choose_coupon(obj,bool){
	if ( $(obj).hasClass('ischeck')){
		$(obj).removeClass('ischeck');
		if(coupons[$(obj).attr('code')]!=undefined){
			delete(coupons[$(obj).attr('code')]);
			if(getJsonObjLength(coupons)==0)use_flag='';
			coupon_amount-=$(obj).attr('amount')*1;
			total_favour-=$(obj).attr('amount')*1;
			if($(obj).attr('use_num_type')=='room_nights' && bool)
				max_room_night_use++;
			else if($(obj).attr('use_num_type')=='order' && bool)
				max_order_use++;
			else if(!bool)max_coupon_use++;
		}
	}
	else{
		if(bool){
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
		}else{
			if(max_coupon_use>0)
				max_coupon_use--;
			else return;
		}
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

function getBonusSet(){
	if(no_part_bonus==0){
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_POINT_SET");?>',{
			datas:JSON.stringify(roomnums),
			start:$('#startdate').val(),
			end:$('#enddate').val(),
			h:$('#hotel_id').val(),
			total_price:$('#total_price').html(),
			price_code:$('#price_codes').val(),
			paytype:$('#pay_type').val()
		},function(data){
			data=JSON.parse(data);
			$("#bonus").val('');
			if(data.s==1&&data.consum_rate>0){
				if(part_bonus_set!=data.part_set){
					part_bonus_set={}
					part_bonus_set=data.part_set;
					$("#bonus").parent().parent().show();
					if(part_bonus_set['max_use']!=undefined)
						$("#bonus").attr('placeholder',"最多可用"+part_bonus_set['max_use']+point_name+"，最多可抵"+part_bonus_set['max_use']*data.consum_rate+"元");
					else{
						var my_bonus=<?php echo empty($member->bonus)?0:$member->bonus;?>;
						var ex_money=my_bonus*data.consum_rate;
						$("#bonus").attr('placeholder',"共"+my_bonus+point_name+"，最多可抵"+ex_money+"元");
					}
						
				}
			}else{
				$("#bonus").parent().parent().hide();
				if($("#bonus").val()!='')
					$.MsgBox.Alert(data.errmsg);
			}
			if($('[pay_type="point"]').hasClass('ischeck')){
				$("#bonus").parent().parent().hide();
			}
		});
	}
}
function getPointpaySet(){
	if(has_point_pay==1){
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_POINTPAY_SET");?>',{
			datas:JSON.stringify(roomnums),
			start:$('#startdate').val(),
			end:$('#enddate').val(),
			h:$('#hotel_id').val(),
			total_price:$('#total_price').html(),
			price_code:$('#price_codes').val(),
			paytype:$('#pay_type').val()
		},function(data){
			if(data.can_exchange==1){
				$('[pay_type="point"]').removeClass('disable');
				$('[pay_type="point"]').attr('able',1);
				$('[pay_type="point"]').show();
				$('[pay_type="point"] span:last').html($('[pay_type="point"]').attr('pname')+'('+data.des+')');
				if($('[pay_type="point"]').hasClass('ischeck')){
					$("#bonus").val('');
					$("#bonus").parent().parent().hide();
				}
				$('#total_point').html(data.point_need+' '+point_name);
			}else{
				$('[pay_type="point"]').addClass('disable');
				$('[pay_type="point"]').attr('able',0);
// 				$.MsgBox.Alert(data.errmsg);
				$('.pay_way').eq(0).trigger('click');
				if(data.point_need!=undefined)
					$('#total_point').html(data.point_need+' '+point_name);
			}
		},'json');
	}
}

$("#bonus").change(function(){
//	$('.pay_way').eq(0).trigger('click');
})
</script>
</html>
