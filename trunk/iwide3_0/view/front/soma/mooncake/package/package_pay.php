
<div class="pageloading"><p class="isload" style="margin-top:150px"></p></div>


<input type="hidden" name="orderId" style="display:none"/>
<input type="hidden" name="mcid" style="display:none" id="mcid"/>
<!-- 以上为head -->
<div class="whiteblock bd_bottom support_list" style="margin-top:0">

    <?php if($package['can_refund'] == $packageModel::CAN_T){ ?>
        <span tips="购买后，您可以在订单中心直接申请退款，并原路退回"><em class="iconfont color_main">&#xe61e;</em><tt>微信退款</tt></span>
    <?php } ?>

    <?php if($package['can_gift'] == $packageModel::CAN_T){ ?>
        <span tips="该商品购买成功后，可微信转赠给好友，好友可继续使用"><em class="iconfont color_main">&#xe61e;</em><tt>赠送朋友</tt></span>
    <?php } ?>

    <?php if($package['can_mail'] == $packageModel::CAN_T){ ?>
        <span tips="这件商品，是可以邮寄的商品哟"><em class="iconfont color_main">&#xe61e;</em><tt>邮寄到家</tt></span>
    <?php } ?>

    <?php if($package['can_pickup'] == $packageModel::CAN_T){ ?>
        <span tips="此商品支持您到店使用／自提"><em class="iconfont color_main">&#xe61e;</em><tt>到店自提</tt></span>
    <?php } ?>
    <?php if($package['can_invoice'] == $packageModel::CAN_T){ ?>
        <span tips="此商品购买成功后，您可以提交发票信息开票"><em class="iconfont color_main">&#xe61e;</em><tt>开具发票</tt></span>
    <?php } ?>
</div>
<a class="goods webkitbox">
    <div class="goodsimg"><div class="squareimg"><img src="<?php echo $package['face_img'];?>" /></div></div>
    <div>
        <p><?php echo $package['name'];?></p>
        <p class="color_888">零售价：¥<?php echo $package['price_package'];?>/盒</p>
    </div>
</a>

<?php 
$buy_limit= $salesOrderModel::STOCK_LIMIT; 
if( !isset($buy_default)) $buy_default= 1; //默认买几个？
?>
<div class="list_style bd" id="select_num">
	<div class="arrow webkitbox justify">
        <span>购买数量</span>
        <span class="color_888" id="buy_num"><?php echo $buy_default ?></span>
    </div>
</div>

<div class="list_style_1 martop bd" id="ActivityReduce">
    <div class="webkitbox justify">
        <span>优惠活动&nbsp;<tt class="bg_main h20 pad2" id="ActivityName"></tt></span>
        <span id="ActivityTips"></span>
    </div>
</div>

<div class="list_style_1 martop bd" id="choose_coupon">
    <div class="webkitbox justify" >
        <?php if( isset($coupons) && count($coupons)>0 ){ ?>
            <span>优惠券&nbsp;<tt class="bg_main h20 pad2"><?php echo count($coupons); ?>张</tt></span>
            <span id="couponName">选择优惠券</span>
        <?php }else{?>
            <span>优惠券</span>
            <span>无</span>
<!--                <span>已使用100元优惠</span>-->
        <?php } ?>
    </div>
</div>

<div class="list_style martop bd grandTotal" id="userReduceObj" type="" quote=""  reduce_cost="100">
        <div class="webkitbox justify">
<!--            <span>可使用积分10000 抵¥100</span>-->
            <span id="userReduceTips">可使用储值</span>
            <span id="ra">
                <input class="hide" type="radio"  name="" />
                <div class="checkbox" style=" float:right;" ><div></div></div>
            </span>
        </div>
    </div>
<div class="list_style martop bd " id="passwordText">
    <div class="webkitbox justify">
        <span>支付密码</span>
        <input type="password" placeholder="请输入支付密码"  name="" id="userPassword" />
    </div>
</div>

<div class="list_style_1 martop bd">
    <div class="webkitbox justify">
        <span>购买人</span>
        <input type="text" placeholder="请填写您的姓名"  name="" id="name"   maxlength="5" value="<?php echo $customer_info['name']; ?>"/>
    </div>
    <div class="webkitbox justify">
        <span>购买电话</span>
        <input type="tel" placeholder="请填写您的电话"  name="" id="phone"   maxlength="11" value="<?php echo $customer_info['mobile']; ?>" />
    </div>
</div>
<!-- <div class="center pad3 color_link h24 martop">tips：购买后填写邮寄地址发货，也可以转赠朋友</div> -->
<div class="foot_padding">
    <div class="foot_fixed bd_top_img">
        <p class="h30 pad10">合计:
            <span class="y color_main" id="grandTotal"></span>
            <span class="h24" id="totalReduceCost" style="display: none">(已优惠<tt class="y"></tt>)</span>
        </p>
        <p id="pay" class="btn_main h30 pad10">立即购买</p>
    </div>
</div>

<div class="ui_pull area_pull" style="display:none" onClick="toclose();">
    <div class="relative _w" style="height:100%;">
        <div class="area_box bg_fff absolute _w">
            <div class="webkitbox justify pad10" style="margin-top:0">
                <span>购买数量</span>
                <span>
                    <div class="num_control bd webkitbox" style="float:right">
                        <div class="down_num bd_left">-</div>
                        <div class="result_num bd_left"><input id="selece_num" value="<?php echo $buy_default ?>" type="tel" min="1" max="<?php echo $buy_limit; ?>" ></div>
                        <div class="up_num bd_lr">+</div>
                    </div>
                </span>
            </div>
            <div class="sure_btn btn_main _w pad10">确认</div>
        </div>
    </div>
</div>




<div class="ui_pull coupon_pull scroll" style="display:none">
    <div class="pad3 bgcolor_fff border_bottom" id="RemindText" style="display: none">
        <p class="h2">温馨提示：</p>
        <!-- <p>由于微信支付限制（支付金额>0元），扣减后的订单总金额最小不能小于<b class="color_main">0.01</b>元。</p> -->
        <p>选择优惠券，并提交订单后，若支付失败或未支付，该券将不能再次使用</p>
    </div>
    <div class="coupon_select">

        <?php /*优惠券模板*/ ?>
        <!--商城代金券-->
        <div class="coupon_item template<?php echo $discountModel::TYPE_COUPON_DJ; //代金券?>" mcid="" style="display: none" >
            <input type="radio" name="coupon" style="display:none">
            <div class="b_radius"><div></div></div>
            <div class="coupon">
                <p class="y against coupon_price"></p>
                <p class="ticket couponTitle f_s_11"></p>
                <p class="fcolor couponSubTitle"></p>
                <div class="coupon_foot webkitbox border_top f_s_9" style="clear:both">
                    <p class="against expireDate"></p>
                    <p class="against txt_r scopeType"></p>
                </div>
            </div>
        </div>

        <!--商城折扣劵-->
        <div class="coupon_item template<?php echo $discountModel::TYPE_COUPON_ZK; //折扣券?>" mcid=""  style="display: none" >
            <input type="radio" name="coupon" style="display:none">
            <div class="b_radius"><div></div></div>
            <div class="coupon">
                <p class="zhe against coupon_price"></p>
                <p class="ticket couponTitle f_s_11"></p>
                <p class="fcolor couponSubTitle"></p>
                <div class="coupon_foot webkitbox border_top f_s_9" style="clear:both">
                    <p class="against expireDate"></p>
                    <p class="against txt_r scopeType"></p>
                </div>
            </div>
        </div>

        <!--商城兑换劵-->
        <div class="coupon_item template<?php echo $discountModel::TYPE_COUPON_DH; //兑换劵?>" mcid=""  style="display: none">
            <input type="radio" name="coupon" style="display:none">
            <div class="b_radius"><div></div></div>
            <div class="coupon">
                <p class="against coupon_price"></p>
                <p class="ticket couponTitle f_s_11"></p>
                <p class="fcolor couponSubTitle"></p>
                <div class="coupon_foot webkitbox border_top f_s_9" style="clear:both">
                    <p class="against expireDate"></p>
                    <p class="against txt_r scopeType"></p>
                </div>
            </div>
        </div>


        <form action="<?php echo current_url(); ?>" method="post" id="apply_coupon">
        </form>
    </div>
    <div class="ui_none" style="display: none" onclick="toclose()"><div>您还没有优惠券~<span class="color_link">点击返回</span></div></div>
</div>
<script>
_waitting =true; //等待AJAX返回
var singlePrice = parseFloat('<?php echo $package['price_package'] ?>').toFixed(2);
var originalTotal = parseFloat('<?php echo $package['price_package']*$buy_default ?>').toFixed(2);
var couponPrice = 0;
var totalReduce = 0; <?php /*总优惠价*/ ?>

var userReduce = 0; <?php /*用户储值或者积分扣减*/ ?>
var activityReduce = 0; <?php /*活动满减*/ ?>
var autoPick = false; <?php //true为进入自动选券?>
var couponItemText; <?php //优惠券初始文字?>
var mCid;
var can_use_coupon;

var payUrl = '<?php echo Soma_const_url::inst()->go_to_pay( $payParams );//订单生产请求地址?>';
var getDiscountUrl = '<?php echo Soma_const_url::inst()->get_url('soma/package/discount_rule_ajax'); //获取所有优惠信息?>';
var couponListUrl = '<?php echo Soma_const_url::inst()->get_url('soma/package/coupon_list_ajax'); //优惠券列表请求地址?>';

$(function(){
	/*支付订单生成*/
	$('#pay').click(function(){
			if( $('#name').val()=='' ){
				$.MsgBox.Alert('请输入联系人!');return false;
			}
			if( $('#phone').val()=='' ){
				$.MsgBox.Alert('请输入手机号码!');return false;
			}
			if( !reg_phone.test($('#phone').val()) ){
				$.MsgBox.Alert('手机号格式有误!');return false;
			}
			var assetType = $('#userReduceObj').attr('type') ;
			var assetQuote = $('#userReduceObj').attr('quote');
			var password = $('#userPassword').val();

			if( ! $('#userReduceObj').find('input[type=radio]').get(0).checked){
				assetType = '';
				assetQuote = '';
			}

			if(assetType == <?php echo $salesRuleModel::RULE_TYPE_BALENCE;?> ){
				if($('#userReduceObj').find('input[type=radio]').get(0).checked){
					if(can_use_coupon == <?php echo Soma_base::STATUS_FALSE;?>)$('#mcid').val('');
					if(password =='' || password == undefined){
						$.MsgBox.Alert('请输入支付密码!');return false;
					}
				}
			}
			pageloading();
			var _data ={
					business: 'package',
					settlement: 'default',
					qty : {<?php echo $_GET['pid'];?>:$('#buy_num').html()},
					name : $('#name').val(),
					phone : $('#phone').val(),
					orderId : $('#orderId').val(),
					mcid: $('#mcid').val(),
					saler: '<?php echo isset($saler_self)? $saler_self: $saler; ?>',
					hotel_id: '<?php echo $package['hotel_id'];?>',
					fans: '<?php echo $fans; ?>',
					product_id: <?php echo $_GET['pid'];?>,
					quote_type: assetType,
					quote: assetQuote, //assetQuote, //originalTotal- couponPrice- activityReduce,
					password: password
			}
//debug code
// for( var i in _data){
//     $('body').prepend(i+':'+_data[i]+'<br>');
// };
			$.ajax({
				type: 'POST',
				url: '<?php echo Soma_const_url::inst()->get_prepay_order_ajax($payParams);?>',
				data: _data,
			success: function(data){
			if(data.status == 1){
				if(data.step =='wxpay'){
					location.href = payUrl+'&order_id='+data.data.orderId;
				}else{
					location.href = data.success_url;
					// location.href = '<?php echo  Soma_const_url::inst()->get_payment_package_success(array('id'=>$inter_id));?>'+ "&order_id=" + data.data.orderId;
				}
			}else if(data.status == 2){
				removeload();
				//失败
				$.MsgBox.Alert(data.message);
				return false;
			}
		} ,
		dataType: 'json'
		});
	})


	$('#select_num').click(function(){
		toshow($('.area_pull'));
		$('.area_box').stop().animate({'bottom':0});
	});
	$('.sure_btn').click(function(){
		var buy_num = $('#selece_num').val();
		var $max = $('#selece_num').attr('max')? parseInt($('#selece_num').attr('max')) :999;
		if(isNaN(buy_num)){
			$.MsgBox.Alert('购买的数量必须为数字');
			return false;
		}
		if( buy_num > $max  ){
			$.MsgBox.Alert("商品数量有限，每人只能购买"+ $max +"件以下");
			return false;
		}
		originalTotal = buy_num * singlePrice;
		$('#buy_num').html(buy_num);
		getReduceInfo();
		toclose();
	})

	$('.area_box').click(function(e){e.stopPropagation();});
	//初始化加载
	couponItemText = $('#choose_coupon').html();
	getReduceInfo();
	$('.support_list span').click(function(){
		$.MsgBox.Alert($(this).attr('tips'));
		$('#left_btn').parent().remove();
	})
});

/*复位所有优惠信息*/
function resetAllReduceCost(){
	couponPrice = 0;  //优惠券金额
	activityReduce = 0; //活动优惠
	totalReduce = 0;  //总优惠重置0
	userReduce = 0;     //积分储值重置
	$('#mcid').val('');
	$('#choose_coupon').html(couponItemText);
//        $('.user_check_box').removeClass('choose');
	$('#userReduceObj').attr('type','');
	$('#userReduceObj').attr('quote','');
	$('#userReduceObj').attr('can_use_coupon',"");
	grandTotalCalcShow();
}

/*活动优惠填充*/
function activityReduceSet(actName,actTips,actReduceAmount){
	$('#ActivityReduce').show();
	$('#ActivityName').html(actName);
	$('#ActivityTips').html(actTips);
	activityReduce = parseFloat(actReduceAmount);
}


/*获取所有可用优惠*/
function getReduceInfo(){
	resetAllReduceCost();
	pageloading();
	$.ajax({
		type: 'POST',
		url: getDiscountUrl,
		data: {
			pid: '<?php echo $_GET['pid'];?>',
			qty: $('#buy_num').html(),
			stl:'default'
		},
		success: function(data){
			removeload();			
			can_use_coupon = data.data.asset.cal_rule.can_use_coupon; //储值积分 
			
			var tmp_can_use_coupon = data.data.activity.auto_rule.can_use_coupon;  //活动
			if( tmp_can_use_coupon !=undefined){
				if( tmp_can_use_coupon ==<?php echo Soma_base::STATUS_FALSE;?>){
					$('#choose_coupon').addClass('disable');
				}else{
					$('#choose_coupon').removeClass('disable');
				}
			}
			
			if(data.status == <?php echo Soma_base::STATUS_TRUE;?>){
				var discountData = data.data;

				if(discountData.activity.status == <?php echo Soma_base::STATUS_TRUE;?>){
					var activity = discountData.activity.auto_rule;
					activityReduceSet(activity.name,'已优惠¥'+ parseFloat(activity.reduce_cost).toFixed(2),activity.reduce_cost);
					activityReduce = activity.reduce_cost;
				}else{
					activityReduce = 0;
					$('#ActivityReduce').hide();
				}

				<?php //积分储值 ?>
				if(discountData.asset.status == <?php echo Soma_base::STATUS_TRUE;?>){
					var asset = discountData.asset.cal_rule;
					$('#userReduceObj').attr('type',asset.rule_type);
					$('#userReduceObj').attr('reduce_cost',asset.reduce_cost);
					$('#userReduceObj').attr('quote',asset.quote);
					$('#userReduceObj').attr('can_use_coupon',asset.can_use_coupon);
					$('#userReduceObj').show();

					if(asset.rule_type == <?php echo $salesRuleModel::RULE_TYPE_POINT;?>){
						$('#userReduceTips').html('积分 '+ asset.quote + '抵' + asset.reduce_cost);
						$('#passwordText').hide();
					}else if(asset.rule_type == <?php echo $salesRuleModel::RULE_TYPE_BALENCE;?>){
						$('#userReduceTips').html('可使用储值¥'+ asset.quote );
						$('#passwordText').show();
					}

//                    userReduce = asset.reduce_cost;
				}else{
					$('#userReduceObj').attr('type','');
					$('#userReduceObj').attr('quote','');
					$('#userReduceObj').attr('reduce_cost',0);
					$('#userReduceObj').hide();
					$('#passwordText').hide();
//                    userReduce = asset.reduce_cost;
				}
				grandTotalCalcShow();

			}else{ //没有返回值
				$.MsgBox.Alert(data.message);
				$('#userReduceObj').hide();
				$('#ActivityReduce').hide();
			}
		} ,
		dataType: 'json'
	});

}


/*储值积分*/
//用户储值或者积分扣减
$('#userReduceObj').click(function(){
	var currentCheck = $('#userReduceObj').find('input[type=radio]').get(0).checked;

	if($('#userReduceObj').find('input[type=radio]').get(0).checked){

		if( $('#userReduceObj').attr('can_use_coupon') == <?php echo Soma_base::STATUS_FALSE;?>){
			$('#choose_coupon').html(couponItemText);
			couponPrice = 0;
		}
		userReduce = parseFloat($(this).attr('reduce_cost'));
		//grandTotalCalcShow();
	}else{
		userReduce = 0;
		//grandTotalCalcShow();
	}
	var grandTotal = originalTotal - couponPrice - userReduce - activityReduce;
	if( grandTotal <= 0) grandTotal = 0;
	$('#grandTotal').html(grandTotal.toFixed(2));
});


/*优惠券*/
$('#choose_coupon').click(function(){
	if($(this).hasClass('disable')){
        $.MsgBox.Alert('当前活动不可使用优惠券~');
		return;
	}
	pageloading();
	$.ajax({
		type: 'POST',
		url: couponListUrl,
		data: {
			'<?php echo $_GET['pid'];?>':$('#buy_num').html()
		},
		success: function(data){
			removeload();
			if(data.status == <?php echo Soma_base::STATUS_FALSE;?>){
				$.MsgBox.Alert(data.message);
			}else{
				fillCouponContent(data.data)
			}
		} ,
		dataType: 'json'
	});
});

//优惠券填充
function fillCouponContent(data){
	$('#apply_coupon').html('');
	if(data.length <= 0){ //没有优惠券
		$('#RemindText').hide();
		$('.coupon_select').hide();
		$('.ui_none').show();
	}else{
		$('.ui_none').hide();
		$('#RemindText').show();
		$('.coupon_select').show();
		//循环输出
		for(var i = 0;i < data.length; i++) {
			coupon = data[i];
			if(coupon.card_type == <?php echo $discountModel::TYPE_COUPON_DH; //兑换券?>){  //
				CouponObj = $('.coupon_select>.template<?php echo $discountModel::TYPE_COUPON_DH; //兑换券?>').clone();
				$(CouponObj).find('.couponTitle').html(coupon.title);
				$(CouponObj).find('.coupon_price').html('兑');
				$(CouponObj).attr('cost',coupon.reduce_cost);

			}else if(coupon.card_type == <?php echo $discountModel::TYPE_COUPON_ZK; //折扣券?>){ //type2
				CouponObj = $('.coupon_select>.template<?php echo $discountModel::TYPE_COUPON_ZK; //折扣券?>').clone();
				$(CouponObj).find('.couponTitle').html(coupon.title);
				$(CouponObj).find('.coupon_price').html(coupon.discount);
				$(CouponObj).attr('cost',coupon.discount);

			}else{
				CouponObj = $('.coupon_select>.template<?php echo $discountModel::TYPE_COUPON_DJ; //代金券?>').clone();
				$(CouponObj).find('.couponTitle').html(coupon.title);
				$(CouponObj).find('.coupon_price').html(coupon.reduce_cost);
				$(CouponObj).attr('cost',coupon.reduce_cost);

			}

			<?php /*使用门槛*/?>
			if( parseFloat(coupon.least_cost) >= 0){
				$(CouponObj).find('.couponSubTitle').html('满'+coupon.least_cost+'元使用');
			}else{
				$(CouponObj).find('.couponSubTitle').html(coupon.sub_title);
			}

			$(CouponObj).css("display","-webkit-box");
			$(CouponObj).attr('cardType',coupon.card_type);
			$(CouponObj).attr('mcid',coupon.member_card_id);

			<?php /*主标题*/ ?>
			$(CouponObj).attr('name',coupon.title);

			<?php /*有效期*/?>
			$(CouponObj).find('.expireDate').html( coupon.expire_time );

			<?php /*适用商品*/?>
			$(CouponObj).find('.scopeType').html(coupon.scopeType);

			if(!coupon.usable){
				$(CouponObj).addClass('coupon_disable');
			}
			$('#apply_coupon').append(CouponObj);

		}
	}
	toshow($('.coupon_pull'));
	$('#apply_coupon .coupon_item').click(function(){
		if ( $(this).hasClass('coupon_disable')) return;
		$(this).addClass('choose').siblings().removeClass('choose');
		$('input',this).get(0).checked=true;
		choose_coupon(this);
	});
}
//选择完优惠券
function choose_coupon(obj){
	$('#mcid').val($(obj).attr('mcid'));
	$('#couponName').html($(obj).attr('name'));
	var couponType = $(obj).attr('cardType');
	var reduceCost = $(obj).attr('cost');
	//var can_use_coupon = $(obj).attr('can_use_coupon');
	if(couponType == <?php echo $discountModel::TYPE_COUPON_DH; //兑换券?>){
		couponPrice = singlePrice;
	}else if(couponType == <?php echo $discountModel::TYPE_COUPON_ZK; //折扣券?>){
		var reduceCost = $(obj).attr('cost');
		couponPrice = originalTotal *  (1 - reduceCost / 10);
	}else if(couponType == <?php echo $discountModel::TYPE_COUPON_DJ; //代金券?>){
		couponPrice = reduceCost;
	}
	//检测是否与储值积分冲突
	if(can_use_coupon == <?php echo Soma_base::STATUS_TRUE;?>){

	} else {
		userReduce = 0;
		$('#userReduceObj').find('input[type=radio]').get(0).checked = false;
	}
	toclose();
	grandTotalCalcShow();
}


//总价计算并显示
function grandTotalCalcShow(){
	var grandTotal = originalTotal - couponPrice - userReduce - activityReduce;
	showCalcTotalHtml(grandTotal);
}
/**总价显示*/
function showCalcTotalHtml(price){
	var total = 0;
	if( price <= 0){
		total = 0;
	}else{
		total = price;
	}
	$('#grandTotal').html(total.toFixed(2));
	$('#userReduceTips').html( '可使用储值¥'+total.toFixed(2) );
	reduce = originalTotal - total;
	if(reduce > 0){
		$('#totalReduceCost').show();
		$('#totalReduceCost tt').html( reduce.toFixed(2) );
	}else{
		$('#totalReduceCost').hide();
	}

}
</script>