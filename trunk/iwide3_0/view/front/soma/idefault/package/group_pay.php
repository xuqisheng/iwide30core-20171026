<?php 
    // 是否显示¥符号
    $show_y_flag = true;
    if($product['type'] == $packageModel::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }
?>
<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
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
	<?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

	<?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

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

<div class="whiteblock bd_bottom support_list" style="margin-top:0">

    <?php if($product['can_refund'] == $packageModel::CAN_T){ ?>
        <span tips="购买后，您可以在订单中心直接申请退款，并原路退回"><em class="iconfont color_main">&#xe61e;</em><tt>微信退款</tt></span>
    <?php } ?>

    <?php if($product['can_gift'] == $packageModel::CAN_T){ ?>
        <span tips="该商品购买成功后，可微信转赠给好友，好友可继续使用"><em class="iconfont color_main">&#xe61e;</em><tt>赠送朋友</tt></span>
    <?php } ?>

    <?php if($product['can_mail'] == $packageModel::CAN_T){ ?>
        <span tips="这件商品，是可以邮寄的商品哟"><em class="iconfont color_main">&#xe61e;</em><tt>邮寄到家</tt></span>
    <?php } ?>

    <?php if($product['can_pickup'] == $packageModel::CAN_T){ ?>
        <span tips="此商品支持您到店使用／自提"><em class="iconfont color_main">&#xe61e;</em><tt>到店自提</tt></span>
    <?php } ?>
    <?php if($product['can_invoice'] == $packageModel::CAN_T){ ?>
        <span tips="此商品购买成功后，您可以提交发票信息开票"><em class="iconfont color_main">&#xe61e;</em><tt>开具发票</tt></span>
    <?php } ?>
    <?php if($product['can_split_use'] == $packageModel::CAN_T){ ?>
        <span tips="此商品分时可用"><em class="iconfont color_main">&#xe61e;</em><tt>分时可用</tt></span>
    <?php } ?>
</div>
<form onSubmit="return _submit()">

    <div class="order_list bd_bottom martop">
<!--        product_id-->
        <a href="<?php echo Soma_const_url::inst ()->get_package_detail(array('pid'=>$groupon['product_id'],'id'=>$inter_id) );?>" class="item bg_fff">
            <div class="item_left">
                <div class="img"><img src="<?php echo $product['face_img'];?>" /></div>
                <p class="txtclip h30"><b><?php echo $groupon['product_name'];?></b></p>
                <?php if($inter_id != 'a455510007'){ //速8需要隐藏 ?>
                    <p class="txtclip"><?php echo $product['hotel_name'];?></p>
                <?php } ?>
                <p class="txtclip h30 color_main">组团价:<?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $groupon['group_price'];?></span></p>
            </div>
        </a>
    </div>
<ul class="list_style_1 bd  martop">
    <li class="justify webkitbox">
        <span>联系人</span>
        <input type="text" id="name" name='name' placeholder="请输入联系人" maxlength="20" value="<?php echo $customer_info['name']; ?>">
    </li>
    <li class="justify webkitbox">
        <span>联系电话</span>
        <input type="tel" id="phone" name="phone" placeholder="请输入手机号码" maxlength="11" value="<?php echo $customer_info['mobile']; ?>">
    </li>
</ul>
            <input type="hidden" name="orderId" />

    <div class="pay bg_fff bd martop block">
        <div class="pay_means bd_bottom">选择支付方式</div>
        <div class="pay_way list_style_1">
            <div class="item wxpay justify webkitbox choose">
                <span>微信支付</span>
                <span><input class="hide" checked type="radio"  name="pay_way" />
                <div class="b_radius"><div></div></div></span>
            </div>
<!--            <div class="item">-->
<!--                <input class="hide" type="radio"  name="pay_way" />-->
<!--                <div class="b_radius"><div></div></div>-->
<!--                <span>积分支付</span>-->
<!--            </div>-->
        </div>
    </div>

    <div class="martop bg_fff bd" style="padding-bottom:3%">
        <div class="h30 bd_bottom pad3">组团规则 </div>
        <div class="group_rule">
            <div class="active">
                <em></em>
                <p>选择心仪商品</p>
            </div>
            <div class="active cur">
                <em></em><hr>
                <p>支付开团或参团</p>
            </div>
            <div>
                <em></em><hr>
                <p>等待好友参团支付</p>
            </div>
            <div>
                <em></em><hr>
                <p>组团成功尽享优惠</p>
            </div>
        </div>
    </div>
    <!--div class="color_888 martop pad3" id="how_sent">温馨提示：如果您是第一次购买或有疑惑，请<span class="color_main">点击此处</span></div-->
    
   <div class="ui_pull how_sent_pull" style="display:none">
    <div class="how_sent bg_fff scroll" style="max-height:70%">
        <div class="bg_main bdradius" style="width:2rem; height:0.5rem;margin:auto;"></div>
        <div class="h30" style="padding:0.5rem 0;">温馨提示</div>
        <div style="text-align:justify; line-height:1.5">
        	<p class="color_main">＊亲，因为我们的商品可以自提或赠送，填写邮寄信息需购买成功哦！</p>
            <p>1  立即购买—支付成功—点击完成</p>
            <p><img src="<?php echo base_url('public/soma/images/step1.jpg');?>"></p>
            <p>2  全部订单—点击使用</p>
            <p><img src="<?php echo base_url('public/soma/images/step2.jpg');?>"></p>
            <p>3  订单明细 </p>
            <p><img src="<?php echo base_url('public/soma/images/step3.jpg');?>"></p>
            <p>4  邮寄—填写信息</p>
            <p><img src="<?php echo base_url('public/soma/images/step4.jpg');?>"></p>
        </div>
    </div>
    <div class="pull_close color_fff h0"><em class="iconfont">&#xe612;</em></div>
</div>

    <div class="foot_fixed">
        <div class="bg_fff foot_fixed_list bd_top">
            <div class="color_main txt_r pad10">实付款：<?php if($show_y_flag): ?><span class="y"><?php else: ?><span><?php endif; ?><?php echo $groupon['group_price'];?></span></div>
            <?php if( $is_expire ): ?>
                <button class="h30 bg_D3D3D3 pad10" type="button">立即购买</button>
            <?php else: ?>
                <button class="h30 bg_main pad10" type="submit">立即购买</button>
            <?php endif; ?>
        </div>
    </div>
</form>
<div style="padding-top:20%"></div>
<script>
    $('#how_sent').click(function(){
        toshow($('.how_sent_pull'));
    })
    $('.how_sent_pull .pull_close').click(toclose)

    $('.pay_way .item').click(function(){
        $(this).addClass('choose').siblings().removeClass('choose');
        $('input',this).get(0).checked=true;
    })


    var _submit = function(){

        if( $('#name').val()=='' ){
            $.MsgBox.Confirm('请输入联系人!',null,null,'好的','取消');return false;
        }
        if( $('#phone').val()=='' ){
            $.MsgBox.Confirm('请输入手机号码!',null,null,'好的','取消');return false;
        }
        if( !reg_phone.test($('#phone').val()) ){
            $.MsgBox.Confirm('手机号格式有误!',null,null,'好的','取消');return false;
        }

        var payUrl = '<?php echo Soma_const_url::inst()->go_to_pay( array('id'=> $this->inter_id ) );?>';
        var url="<?php echo Soma_const_url::inst()->get_prepay_order_ajax();?>";

        pageloading('订单生成中，请稍后',0.2);
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                business: 'package',
                settlement: 'groupon',
                name : $('#name').val(),
                phone :$('#phone').val(),
                orderId :$('#orderId').val(),
                hotel_id: '<?php echo $product['hotel_id'];?>',
                qty: {'<?php echo $groupon['product_id'];?>': 1},
                saler: '<?php echo isset($saler_self)? $saler_self: $saler; ?>',
                fans_saler: '<?php echo isset($fans_saler_self)? $fans_saler_self: $fans_saler; ?>',
                fans: '<?php echo $fans; ?>',
                product_id:<?php echo $groupon['product_id'];?>,
                act_id  : <?php echo $_GET['act_id'];?>,
                grid : '<?php echo $grid;?>',
                type : '<?php echo $type;?>'
            },
            success: function (data) {
//                $('.pageloading').remove();
                //close loading
                if (data.status == <?php echo Soma_base::STATUS_TRUE;?>) {
                   location.href = payUrl+'&order_id='+data.data.orderId;
                } else if (data.status == <?php echo Soma_base::STATUS_FALSE;?>) {
                    $('.pageloading').remove();
                    //失败
                    $.MsgBox.Confirm(data.message,null,null,'好的','取消');
                    return false;
                }
                else if (data.status == 3) {
                    $('.pageloading').remove();
                    $.MsgBox.loading(data.message, function(){
                        var timer = $(this).timer;
                        clearTimeout(timer);
                        $(this).timer = setTimeout(function(){
                            clearTimeout(timer);
                            location.href = data.success_url
                        }, 20000)
                    })
                }
            },
            dataType: 'json'
        });

        return false;
    }


</script>
</body>
</html>