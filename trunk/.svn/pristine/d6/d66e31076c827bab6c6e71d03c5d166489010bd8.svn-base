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
    <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

    <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

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
<body>
<div class="pageloading"><p class="isload" style="margin-top:150px">正在加载</p></div>
<!-- 以上为head -->
<a class="goods webkitbox">
    <div class="goodsimg"><div class="squareimg">
    <!-- <img src="<?php echo base_url('public/soma/images');?>/eg4.jpg" /> -->
    <img src="<?php echo $product['face_img']; ?>"?>
    </div></div>
    <div>
        <p class="h30"><?php /*echo "asd";*/echo $product['name']; ?></p>
        <!-- <p class="color_888">零售价：¥188/盒</p> -->
    </div>
</a>
<!--
<div class="whiteblock webkitbox justify bd" style="margin-top:0" id="select_num">
    <span>购买数量</span>
    <span class="color_888" id="buy_num">100</span>
</div>
-->
<div class="list_style_1 bd">
    <div class="input_item">
        <span>购买数量</span>
        <input type="tel" required placeholder="请填写购买数量"  id="qty" name="qty" />
    </div>
</div>
<div class="pad10 color_555">客户信息</div>
<div class="list_style_1 bd">
    <div class="input_item">
        <span>联系人</span>
        <span><input type="text" required placeholder="请填写您的姓名"  id="customer_name" name="customer_name" /></span>
    </div>
    <div class="input_item">
        <span>联系电话</span>
        <span><input type="tel" required placeholder="请填写您的电话"  id="customer_tel" name="customer_tel" /></span>
    </div>
    <div class="input_item">
        <span>企业信息</span>
        <span><input type="text" placeholder="选填"  id="customer_com" name="customer_com" /></span>
    </div>
    <div class="input_item">
        <span>销售员</span>
        <span><input type="text" placeholder="选填"  id="salesman" name="salesman" /></span>
    </div>
</div>
<div class="center pad3 color_link h24 martop">tips：购买后填写邮寄地址发货，也可以转赠朋友</div>
<div class="foot_padding">
    <div class="foot_fixed">
        <p class="h30 pad10">合计:
            <span id="qty_total" class="color_main">0盒</span>
        </p>
        <p id="reserve_submit" class="btn_main h30 pad10 disable">预订下单</p>
    </div>
</div>
<script>
	$('input[required]').blur(function(){
		for( var i=0;i<$('input[required]').length;i++){
			if ($('input[required]').eq(i).val()==''||!reg_phone.test($('#customer_tel').val())){
				$('#reserve_submit').addClass('disable');
				return;
			}
		}
		$('#reserve_submit').removeClass('disable');
	});
    $('#qty').bind('input propertychange', function(){
		if (isNaN( $(this).val())) $(this).val('');
        var qty = $(this).val()?$(this).val():'0';
        $("#qty_total").html(qty + '盒');
    });

    $("#reserve_submit").click(function(){
		$('input[required]').each(function(){
			if($(this).val()=='') $.MsgBox.Confirm($(this).attr('placeholder'),null,null,'好的','取消');
		});
		if( !reg_phone.test($('#customer_tel').val())) $.MsgBox.Confirm('手机号码格式有误',null,null,'好的','取消');
		if($(this).hasClass('disable'))	return;
        var qty = $("#qty").val();
        var customer_name = $("#customer_name").val();
        var customer_tel = $("#customer_tel").val();
        var customer_com = $("#customer_com").val();
        var salesman = $('#salesman').val();
        var product_id = <?php echo $product['product_id']; ?>;
		$.MsgBox.Confirm('订单已提交,请稍候...',null,null,'好的','取消');
		$('#mb_btnbox').hide();
        $.ajax({
            url:"<?php echo Soma_const_url::inst()->get_url('*/*/reserve_post',array('id'=>$inter_id));?>",
            type:"post",
            dataType:"json",
            data:{
                product_id:product_id,
                qty:qty,
                customer_name:customer_name,
                customer_tel:customer_tel,
                customer_com:customer_com,
                salesman:salesman,
            },
            timeout:5000,
            error:function(){
                window.location.href = "<?php echo Soma_const_url::inst()->get_url('*/*/error_page');?>";
            },
            success:function(data){
                if(data.result) {
                    var url = "<?php echo Soma_const_url::inst()->get_url('*/*/reserve_order',array('id'=>$inter_id));?>" + "&reserve_id=" + data.reserve_id;
                    window.location.href = url;
                }
            }
        })
    });

</script>
</body>
</html>
