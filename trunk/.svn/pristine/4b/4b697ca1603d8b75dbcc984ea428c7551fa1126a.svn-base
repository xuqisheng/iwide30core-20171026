<body style="padding-bottom:3%">
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


<div class="notic_banner color_888 bd bg_fff h24">
    <!-- <span>小提示：此张套票使用后，您的订单将不能退款</span> -->
    <span>小提示：使用后，您的订单将不能退款</span>
</div>

<div class="order_list bd martop">
    <a href="<?php echo Soma_const_url::inst()->get_url('*/package/package_detail', array('pid'=>$item['product_id'], 'id'=>$inter_id, 'bsn'=>'package' ) ); ?>" class="item bg_fff">
        <div class="img"><img src="<?php echo $item['face_img']; ?>" /></div>
        <p class="txtclip"><?php echo $item['name']; ?></p>
        <p class="txtclip"><?php echo $item['hotel_name']; ?></p>
    </a>
</div>

<?php $compose = show_compose($item['compose']); if( !empty( $compose ) ): ?>
<div class="bg_fff bd martop block">
    <p>商品内容</p>
    <p class="bd_top" style="margin-left:10%;padding-left:0"><?php echo $compose; ?></p>
</div>
<?php endif;?>

<div class="bg_fff bd martop block">
    <!-- <p class="bd_bottom" >电话预定</p> -->
    <p class="bd_bottom" >到店用券</p>
    <ul class="step_style webkitbox color_main h24">
        <li><em>1</em><p>抵达<br>酒店</p></li>
        <li><em>2</em><p>询问<br>前台</p></li>
        <li><em>3</em><p>提供劵码<br>或二维码</p></li>
        <li><em>4</em><p>领取<br>月饼</p></li>
    </ul>
</div>

<?php if( isset($is_consumer) && $is_consumer ): ?>
    <div class="bg_fff bd martop block">
        <p class="bd_bottom" style="margin-left:10%; padding-left:0">劵码 <?php echo $item['qrcode']; ?></p>
        <p class="center color_888">
            <img src="<?php echo $item['qrcode_url']; ?>" style="width:32%"/>
            <br>该二维码已经消费
        </p>
    </div>

     <!-- 推荐位  -->
    <div id="load_page_block" ></div>
    <script>
    $.ajax({
        url : "<?php echo Soma_const_url::inst()->get_url('*/package/page_block_ajax', array('u'=>implode('_', $uri), 'id'=>$inter_id ) ); ?>",
        dataType : "html",
        cache: false,
        success : function(data){ $('#load_page_block').html(data); }
    });
    </script>
     <!-- 推荐位  -->
<?php elseif( isset( $is_booking ) && $is_booking ): ?>
    <div class="bg_fff bd martop block">
        <p>方法一</p>
        <p class="center bd_bottom">
            <img src="<?php echo $item['qrcode_url']; ?>" style="width:50%"/>
            <br>请向门店服务员出示此二维码
        </p> 
        <p class="color_888" style="margin-left:10%; padding-left:0">劵码： <?php echo $item['qrcode']; ?></p>
    </div>
<?php elseif( isset( $is_expire ) && !$is_expire ): ?>

    <div class="bg_fff bd martop block">
        <p>方法一</p>
        <p class="center bd_bottom h24">
            <img src="<?php echo $item['qrcode_url']; ?>" style="width:50%"/>
            <br>请向门店服务员出示此二维码
        </p> 
        <p class="color_888" style="margin-left:10%; padding-left:0">劵码：<?php echo $item['qrcode']; ?></p>
    </div>
    <?php if( isset( $item['qrcode'] ) && $item['qrcode'] ): ?>
    <div class="bg_fff bd martop block">
        <p>方法二</p>
        <p class="bd" style="margin-left:10%">如不支持券码和二维码扫描，请客服人员输入核销码</p>
        <form style="margin-left:10%" id="myForm" method="post">请工作人员输入<br><br>
            <input type="hidden" id="consumer_code" name="code" value="<?php echo $item['qrcode']; ?>" placeholder="输入核销码" maxlength="12" class="pad3" style="width:92%"/>
            <input type="text" id="consumer_captcha" name="captcha" placeholder="输入验证码" maxlength="6" class="pad3" style="width:92%"/>
            <div class="foot_btn" style="padding:7% 2% 3% 0;text-align:right">
                <button style="width:auto; background:#999;">确定核销</button>
            </div>
        </form>
    </div>
    <?php endif;?>

    <script>
        // $('#consumer_code').focus(function(){
        $('#consumer_captcha').focus(function(){
            window.setTimeout(function(){
                $(document).scrollTop($(document).height());
            },200);
        });
        $('#myForm button').click(function(){
            var code =$('#consumer_code').val()
            var captcha = $('#consumer_captcha').val();
            // if (code=='') return;
            if (captcha=='') return false;
            //alert($("#myForm").attr('action'));
            // var is_true = isNaN(code);
            // var is_true = /^\d{12}$/.test(  code  );
            var is_true = /^\d{6}$/.test(  captcha  );
            if( !is_true ){
                alert('请输入6位纯数字');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo Soma_const_url::inst()->get_url('*/consumer/self_consumer', array('id'=>$inter_id, 'aiid'=>$item['item_id'], 'bsn'=>'package' ) ); ?>",
                data: {code:code,captcha:captcha},
                dataType: "json",
                success:function( json ){
                    if( json['status'] == 1 ){
                        window.location.href=json['url'];
                    }else{
                        alert( json['message'] );
                        return false;
                    }
                }
            });

            return false;
        });
    </script>
<?php else: ?>
    已过期（<?php echo $item['expiration_date']; ?>）
<?php endif; ?>

<!-------- 推荐位 --------->
<div id="load_page_block" ></div>
<script>
$.ajax({
    url : "<?php echo Soma_const_url::inst()->get_url('*/package/page_block_ajax', array('u'=>implode('_', $uri), 'id'=>$inter_id ) ); ?>",
    dataType : "html",
    cache: false,
    success : function(data){ $('#load_page_block').html(data); }
});
</script>
<!-------- 推荐位 --------->
    
</body>
</html>