<?php
    // 是否显示¥符号
    $show_y_flag = true;
    if($package['type'] == $packageModel::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }
?>
<body>
<div class="pageloading"><p class="isload"><?php echo $lang->line('loading');?></p></div>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>]
});
wx.ready(function(){
	
    <?php if( $js_share_config ): ?>
    wx.onMenuShareTimeline({
        title: '<?php echo $js_share_config["title"]?>',
        link: '<?php echo $js_share_config["link"]?>',
        imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
        success: function () {},
        cancel: function () {}
    });
    wx.onMenuShareAppMessage({
        title: '<?php echo $js_share_config["title"]?>',
        desc: '<?php echo $js_share_config["desc"]?>',
        link: '<?php echo $js_share_config["link"]?>',
        imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
        //type: '', //music|video|link(default)
        //dataUrl: '', //use in music|video
        success: function () {},
        cancel: function () {}
    });
    <?php endif; ?>
});
</script>

<div id="seckill-pay">

<div class="wrap">
<!--
<div class="whiteblock bd_bottom support_list" style="margin-top:0">

    <?php if($package['can_refund'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('after_buy_apply_refund');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('wechat_refund');?></tt>
        </span>
    <?php } ?>

    <?php if($package['can_gift'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('after_buy_donated');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('gift_a_friend');?></tt>
        </span>
    <?php } ?>

    <?php if($package['can_mail'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('goods_can_mail');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('deliver_to_home');?></tt>
        </span>
    <?php } ?>

    <?php if($package['can_pickup'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('goods_support_shop_or_self');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('collect_from_hotel');?></tt>
        </span>
    <?php } ?>
    <?php if($package['can_invoice'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('enter_invoice_header_tip');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('invoice');?></tt>
        </span>
    <?php } ?>
    <?php if($package['can_split_use'] == $packageModel::CAN_T){ ?>
        <span tips="<?php echo $lang->line('can_be_used_splitting');?>">
            <em class="iconfont color_main">&#xe61e;</em>
            <tt><?php echo $lang->line('multi_usage');?></tt>
        </span>
    <?php } ?>
</div>
-->


<div class="header pd-19 bg-white border-bottom support_list">



      <?php if($package['can_refund'] == $packageModel::CAN_T){ ?>
              <div class="item fl f20 c555 box" tips="<?php echo $lang->line('after_buy_apply_refund');?>">
                  <span class="icon"></span>
                  <span><?php echo $lang->line('wechat_refund');?></span>
              </div>
      <?php } ?>

        <?php if($package['can_gift'] == $packageModel::CAN_T){ ?>
            <div class="item fl f20 c555 box" tips="<?php echo $lang->line('after_buy_donated');?>">
                <span class="icon" ></span>
                <span><?php echo $lang->line('gift_a_friend');?></span>
            </div>
        <?php } ?>

          <?php if($package['can_mail'] == $packageModel::CAN_T){ ?>
              <div  class="item fl f20 c555 box" tips="<?php echo $lang->line('goods_can_mail');?>">
                 <span class="icon" ></span>
                  <span><?php echo $lang->line('deliver_to_home');?></span>
              </div>
          <?php } ?>

          <?php if($package['can_pickup'] == $packageModel::CAN_T){ ?>
              <div class="item fl f20 c555 box" tips="<?php echo $lang->line('goods_support_shop_or_self');?>">
                  <span class="icon"></span>
                  <span><?php echo $lang->line('collect_from_hotel');?></span>
              </div>
          <?php } ?>

          <?php if($package['can_invoice'] == $packageModel::CAN_T){ ?>
              <div class="item fl f20 c555 box" tips="<?php echo $lang->line('enter_invoice_header_tip');?>">
                  <span class="icon"></span>
                  <span><?php echo $lang->line('invoice');?></span>
              </div>
          <?php } ?>

          <?php if($package['can_split_use'] == $packageModel::CAN_T){ ?>
              <div class="item fl f20 c555 box" tips="<?php echo $lang->line('can_be_used_splitting');?>">
                  <span class="icon"></span>
                  <span><?php echo $lang->line('multi_usage');?></span>
              </div>
          <?php } ?>

</div>


<form onsubmit="return _submit()">
  <!--
    <div class="order_list bd_bottom martop">
        <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$package['product_id'],'id'=>$inter_id));?>" class="item bg_fff">
            <div class="item_left">
                <div class="img"><img src="<?php echo $package['face_img'];?>" /></div>
                <p class="txtclip"><?php echo $package['name'];?></p>
                <p class="txtclip"><?php echo $package['hotel_name'];?></p>
                <p class="txtclip color_main">秒杀价:<?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $killsec['killsec_price'];?></span></p>
            </div>
        </a>
    </div>
    -->

      <a class="goods-detail box"  href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$package['product_id'],'id'=>$inter_id));?>">
          <div class="goods-img">
              <img src="<?php echo $package['face_img'];?>" />
          </div>
          <div class="goods-info flex1">
              <p class="f26 c555 goods-name"><?php echo $package['name'];?></p>
              <p class="f24 c888">秒杀价：<?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $killsec['killsec_price'];?></p>
          </div>
      </a>


<?php 
$buy_limit= intval($killsec['killsec_permax'])<1? 1: intval($killsec['killsec_permax']);
if(isset($cache_hash['max_stock']) && $cache_hash['max_stock'] != $buy_limit ) $buy_limit =  $cache_hash['max_stock'];
$buy_default= 1; //默认买几个？
?>

<!--
<ul class="list_style_2 bd martop" id="select_num">

    <li class="arrow webkitbox justify">
        <span>
        <?php echo $lang->line('purchase_quantity');?>
        <tt class="btn_main btn_small"> <?php echo str_replace('[0]', $buy_limit, $lang->line('limit_num'));?></tt>
        </span>
        <input class="color_888" type="tel" value="<?php echo $buy_default; ?>" id="buy_num" readonly>
    </li>

    <li class="justify webkitbox">
        <span><?php echo $lang->line('purchase_validity');?></span>
        <span><span id="timeLeft"><?php echo $lang->line('remain');?></span></span>
    </li>

</ul> -->

<div class="number bg-white box pr">
      <div class="title f26 c555"><?php echo $lang->line('purchase_quantity');?></div>

      <div class="limit pr">
          <span class="bgff9900 f20"><?php echo str_replace('[0]', $buy_limit, $lang->line('limit_num'));?></span>
      </div>
      
      <!--
      <div class="num_control bd webkitbox" style="float:right">
              <div class="down_num bd_left">-</div>
              <div class="result_num bd_left"><input id="selece_num" value="<?php echo $buy_default; ?>" type="tel" min="1" max="<?php echo $buy_limit; ?>"></div>
              <div class="up_num bd_lr">+</div>
      </div> -->

      <div class="flex1">
          <div class="number-input">
              <div class="reduce pr fl pr"></div>
              <div class="fl f24 number-mask" style="height:24px;width: 52px;">
                   <input type="number" class="fl f24" value="<?php echo $buy_default; ?>"  id="buy_num" max="<?php echo $buy_limit; ?>">
              </div>
              <div class="increase fl pr"></div>
          </div>
      </div>
</div>

 <!-- 下单时间 -->
    <div class="order-time mt-16 bg-white box">
        <div class="title f26 c555"><?php echo $lang->line('purchase_validity');?></div>
        <div class="title ta-r cff9900 f22 flex1" id="timeLeft"><?php echo $lang->line('remain');?></div>
    </div>
 <!-- 下单时间 -->


 <div class="ui_pull area_pull" style="display:none">
    <div class="relative _w" style="height:100%;">
        <div class="area_box bg_fff absolute _w">
            <div class="webkitbox justify pad10" style="margin-top:0">
                <span><?php echo $lang->line('purchase_quantity');?></span>
                <span>
                    <div class="num_control bd webkitbox" style="float:right">
                        <div class="down_num bd_left">-</div>
                        <div class="result_num bd_left"><input id="selece_num" value="<?php echo $buy_default; ?>" type="tel" min="1" max="<?php echo $buy_limit; ?>"></div>
                        <div class="up_num bd_lr">+</div>
                    </div>
                </span>
            </div>
            <div class="sure_btn btn_main _w pad12"><?php echo $lang->line('confirm');?></div>
        </div>
    </div>
</div>


<!-- 填写订单 -->
<ul class="mt-16 bg-white">
    <li class="box border-bottom">
        <div class="title f26 c555">联<span class="em">系</span>人</div>
        <input type="text" class="f24 flex1" placeholder="<?php echo $lang->line('enter_a_contact');?>" value="<?php echo $customer_info['name']; ?>" id="name">
    </li>

    <li class="box">
        <div class="title f26 c555">手机号码</div>
        <input type="tel" class="f24 flex1" placeholder="<?php echo $lang->line('enter_phone_number');?>" maxlength="11" value="<?php echo $customer_info['mobile']; ?>" id="phone">
    </li>
</ul>
<!-- 填写订单 -->


<!--
<ul class="list_style_1 bd  martop">
    <li class="justify webkitbox">
        <span><?php echo $lang->line('contacts');?></span>
        <input type="text" id="name" name='name' placeholder="<?php echo $lang->line('enter_a_contact');?>" maxlength="20" value="<?php echo $customer_info['name']; ?>">
    </li>
    <li class="justify webkitbox">
        <span><?php echo $lang->line('contacts_phone_number');?></span>
        <input type="tel" id="phone" name="phone" placeholder="<?php echo $lang->line('enter_phone_number');?>" maxlength="11" value="<?php echo $customer_info['mobile']; ?>">
    </li>
</ul> -->

<input type="hidden" name="orderId">
<?php if($package['type'] != $packageModel::PRODUCT_TYPE_BALANCE): ?>

  <!-- <div class="pay bg_fff bd martop block">
      <div class="pay_means bd_bottom"><?php echo $lang->line('payment_method');?></div>
      <div class="pay_way list_style_1">
          <div class="item wxpay justify webkitbox">
              <span><?php echo $lang->line('wechat_pay');?></span>
              <span><input class="hide" checked type="radio"  name="pay_way" />
              <div class="radio"><div></div></div></span>
          </div>
      </div>
  </div> -->

  <ul class="mt-16 pay-way bg-white">
      <li class="box border-bottom">
          <div class="title f26 c555"><?php echo $lang->line('payment_method');?></div>
      </li>
      <li class="box">
          <div class="title f26 c555"><?php echo $lang->line('wechat_pay');?></div>
          <input class="hide" checked type="radio"  name="pay_way" />
          <div class="flex1 pr choice active">
              <span class="pa"></span>
          </div>
      </li>
   </ul>


<?php else: ?>

<!--- 修改 -->

<!-- <div class="pay bg_fff bd martop block">
    <div class="pay_means bd_bottom"><?php echo $lang->line('payment_method');?></div>
    <div class="pay_way list_style_1">
        <div class="balancepay item justify webkitbox">
            <span><?php echo $lang->line('stored_balance');?>(<?php echo isset($balance) ? $balance : 0; ?>)</span>
            <span><input class="hide" checked type="radio"  name="pay_way" />
            <div class="radio"><div></div></div></span>
        </div>
    </div>
</div> -->

 <ul class="mt-16 pay-way bg-white">
      <li class="box border-bottom">
          <div class="title f26 c555"><?php echo $lang->line('payment_method');?></div>
      </li>
      <li class="box">
          <div class="title f26 c555"><?php echo $lang->line('stored_balance');?>(<?php echo isset($balance) ? $balance : 0; ?>)</div>
          <input class="hide" checked type="radio"  name="pay_way" />
          <div class="flex1 pr choice active">
              <span class="pa"></span>
          </div>
      </li>
 </ul>


<?php if($show_balance_passwd == Soma_base::STATUS_TRUE): ?>
<ul class="list_style_1 bd  martop">
    <li class="justify webkitbox">
        <span>
            <?php echo $lang->line('store_password');?>
        </span>
        <input type="text" id="bpay_passwd" name="bpay_passwd" placeholder="<?php echo $lang->line('enter_password_first');?>" >
    </li>
</ul>
<?php endif; ?>
<?php endif; ?>
    <!--div class="color_888 martop pad3" id="how_sent">温馨提示：如果您是第一次购买或有疑惑，请<span class="color_main">点击此处</span></div-->
    <div class="foot_fixed">
        <div class="bg_fff foot_fixed_list bd_top">
            <div class="pad10" style="text-align:left">
                <?php echo $lang->line('real_pay');?>：
                <span class="<?php if($show_y_flag): ?>y<?php endif; ?> color_main" id="grandTotal">
                    <?php echo $killsec['killsec_price']*$buy_default;?>
                </span>
            </div>
            <?php if( $is_expire ): ?>
            	<button class="h30 bg_main pad10 bg_C3C3C3" type="button"><?php echo $lang->line('expired');?></button>
            <?php else: ?>
                <button class="h30 bg_main pad10" type="button" onClick="_submit()"><?php echo $lang->line('buy_now');?></button>
            <?php endif; ?>
        </div>
    </div>
</form>

<!-- 弹层 -->

<div class="ui_pull how_sent_pull" style="display:none">
    <div class="how_sent bg_fff scroll" style="max-height:70%">
        <div class="bg_main bdradius" style="width:2rem; height:0.5rem;margin:auto;"></div>
        <div class="h30" style="padding:0.5rem 0;"><?php echo $lang->line('tips');?></div>
        <div style="text-align:justify; line-height:1.5">
        	<p class="color_main"><?php echo $lang->line('buy_tip');?></p>
            <p><?php echo $lang->line('buy_tip_one');?></p>
            <p><img src="<?php echo base_url('public/soma/images/step1.jpg');?>"></p>
            <p><?php echo $lang->line('buy_tip_two');?></p>
            <p><img src="<?php echo base_url('public/soma/images/step2.jpg');?>"></p>
            <p><?php echo $lang->line('buy_tip_three');?> </p>
            <p><img src="<?php echo base_url('public/soma/images/step3.jpg');?>"></p>
            <p><?php echo $lang->line('buy_tip_four');?></p>
            <p><img src="<?php echo base_url('public/soma/images/step4.jpg');?>"></p>
        </div>
    </div>
    <div class="pull_close color_fff h36"><em class="iconfont">&#xe612;</em></div>
</div>

<div class="ui_pull" id="layout_tips" onclick="$(this).remove()">
    <div class="pullbox bg_fff">
        <div class="pulltitle h30"><?php echo $lang->line('get_qualification');?></div>
        <div style="text-align:justify"><?php echo $lang->line('pay_in_five_minute_tip');?></div>
        <div class="webkitbox center">
        	<div><div class="btn_main"><?php echo $lang->line('confirm')?></div></div>
        </div>
    </div>
</div>

<!-- <div style="padding-top:20%"></div> -->

  </div>
</div>
<script>
$(function(){
    $('#how_sent').click(function(){
        toshow($('.how_sent_pull'));
    })
    $('.how_sent_pull').click(toclose)

    $('.pay_way .item').click(function(){
        $(this).addClass('choose').siblings().removeClass('choose');
        $('input',this).get(0).checked=true;
    })

    $('#select_num').click(function(){
        toshow($('.area_pull'));
        $('.area_box').stop().animate({'bottom':0});
    });
    $('.area_box').click(function(e){e.stopPropagation();});
    $('.sure_btn').click(function(){
		var buy_num = parseInt($('#selece_num').val());
        $('#buy_num').val(buy_num);
        var tmp = buy_num*<?php echo $killsec['killsec_price'];?>;
        $('#grandTotal').html(tmp.toFixed(2));
        toclose();
    })
});
  
    $('.increase').on('click', function() {
        var select_num = $('#buy_num');
        var max = parseInt(select_num.attr('max'));
        var number = parseInt($('#buy_num').val());
        number = number + 1 ;
        if (number >= max) {
          number = max;
        }
        var tmp = number*<?php echo $killsec['killsec_price'];?>;
        $('#grandTotal').html(tmp.toFixed(2));
        select_num.val(number)
    });

    $('.reduce').on('click', function(){
        var select_num = $('#buy_num');
        var number = parseInt($('#buy_num').val());
        number = number - 1 ;
        if (number <= 1) {
          number = 1;
        }
        var tmp = number*<?php echo $killsec['killsec_price'];?>;
        $('#grandTotal').html(tmp.toFixed(2));
        select_num.val(number)
    });

    var _submit = function(){
        if( $('#name').val()=='' ){
            $.MsgBox.Confirm('<?php echo $lang->line('enter_a_contact');?>',null,null, '<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');return false;
        }
        if( $('#phone').val()=='' ){
            $.MsgBox.Confirm('<?php echo $lang->line('enter_phone_number');?>',null,null,'<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');return false;
        }
        if( !reg_phone.test($('#phone').val()) ){
            $.MsgBox.Confirm('<?php echo $lang->line('phone_number_wrong');?>',null,null,'<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');return false;
        }
        var payUrl = '<?php echo Soma_const_url::inst()->go_to_pay( array('id'=> $this->inter_id) ); ?>';
        var wftPayUrl = '<?php echo Soma_const_url::inst()->wft_go_to_pay( array('id'=> $this->inter_id ) );//订单生产请求地址?>';

        var bpay_passwd = $('#bpay_passwd').val();
        var pQty = parseInt($('#buy_num').val());
        if(isNaN(pQty)){
            $.MsgBox.Confirm('<?php echo $lang->line('number_incorrect');?>!',null,null,'<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
            return false;
        }

        <?php if($package['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?>
		if($('.balancepay').length){
			if($('.balancepay input').get(0).checked&& $('#grandTotal').html()*1><?php echo isset($balance) ? $balance : 0; ?>){
				$.MsgBox.Confirm('<?php echo $lang->line('balance_note_enough_tip');?>',function(){
					window.location.href="<?php echo $balance_url;?>";//充值链接
					// alert('未对接充值接口');
				},null,'<?php echo $lang->line('recharge_now');?>','<?php echo $lang->line('cancel');?>');
				return false;
			}
		}
        <?php endif; ?>

        pageloading('<?php echo $lang->line('generate_order');?>',0.2);
        $.ajax({
			async: true,
            type: 'POST',
			timeout : 10000,
            url: '<?php echo Soma_const_url::inst()->get_prepay_order_ajax();?>',
            data: {
                business: 'package',
                settlement: 'killsec',
                hotel_id: '<?php echo $package['hotel_id'];?>',
                qty : {<?php echo $_GET['pid'];?>:parseInt(pQty)},
                name : $('#name').val(),
                phone : $('#phone').val(),
                orderId : $('#orderId').val(),
                mcid: $('#mcid').val(),
                saler: '<?php echo isset($saler_self)? $saler_self: $saler; ?>',
                fans_saler: '<?php echo isset($fans_saler_self)? $fans_saler_self: $fans_saler; ?>',
                fans: '<?php echo $fans; ?>',
                product_id : <?php echo $killsec['product_id']; ?>,
                act_id  : <?php echo $killsec['act_id']; ?>,
                token : '<?php echo $_GET['token']; ?>',
                bpay_passwd: bpay_passwd,
                inid : '<?php echo $_GET['instance_id']; ?>'
            },
            success: function (data) {
                if (data.status == 1) {
                   if(data.step =='wxpay'){
                        location.href = payUrl+'&order_id='+data.data.orderId;
                    }else if(data.step == 'wft_pay'){
                        location.href = wftPayUrl+'&order_id='+data.data.orderId;
                    }else{
                        location.href = '<?php echo  Soma_const_url::inst()->get_payment_package_success(array('id'=>$inter_id));?>'+ "&order_id=" + data.data.orderId;
                    }
                } else if (data.status == 2) {
                    $('.pageloading').remove();
                    //失败
                    $.MsgBox.Confirm(data.message,null,null, '<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
                    return false;
                }
            },
			error: function() {
				//失败
				$('.pageloading').remove();
				$.MsgBox.Confirm('<?php echo $lang->line('order_overtime_try_again_tip');?>',function(){
					//if(e.status == 302||e.status == 307) {
						window.location.reload();
					//}		
				},function(){
						window.location.reload();
				},'<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
				return false;
			},
		　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
		　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
					$('.pageloading').remove();
					$.MsgBox.Confirm('<?php echo $lang->line('order_overtime_try_again_tip');?>',function(){
						//if(e.status == 302||e.status == 307) {
							window.location.reload();
						//}		
					},function(){
							window.location.reload();
					}, '<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
		　　　　}
		　　},
			timeout: function() {
				$('.pageloading').remove();
						//失败
				$.MsgBox.Confirm('<?php echo $lang->line('order_overtime_try_again_tip');?>',function(){
					//if(e.status == 302||e.status == 307) {
						window.location.reload();
					//}		
				},function(){
						window.location.reload();
				}, '<?php echo $lang->line('ok');?>', '<?php echo $lang->line('cancel');?>');
			},
            dataType: 'json'
        });
        return false;
    }

    var oTime = '<?php echo date('Y/m/d H:i:s',strtotime($cache_hash['create_at'])); ?>';
    var startFlag = false;
    /*秒杀倒计时*/
    function countdownTime(Time){
        var endTime=new Date(Time);
        var nowTime=new Date();
        var s_time=endTime-nowTime + 300000;
        var end_date=parseInt((s_time/1000)/86400);
        var end_hour=parseInt((s_time/1000)%86400/3600);
        var end_minute=parseInt((s_time/1000)%3600/60);
        var end_second=parseInt((s_time/1000)%60);
        return {
            j_date : end_date,
            j_hour : end_hour,
            j_minute : end_minute,
            j_second : end_second,
            j_rest  : s_time
        }
    }
    calcStrObj = countdownTime(oTime);
//    $('#timeLeft').html()
    var calcObj = $('#timeLeft');
    calcObj.time=setInterval(function(){
        if(calcStrObj.j_rest <= 0 ){
            $('#timeLeft').html('<?php echo $lang->line('time_out');?>');
            clearInterval(calcObj.time);
            return false;
        }
        calcStrObj = countdownTime(oTime);
        $('#timeLeft').html('<?php echo $lang->line('remain');?>  ' + calcStrObj.j_minute + ' <?php echo $lang->line('min'); ?> ' + calcStrObj.j_second +' <?php echo $lang->line('sec'); ?>');
    },1000)
</script>
</body>
</html>