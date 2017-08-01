<?php 
    $bg_img = (isset($themeConfig['cat_bg']) && !empty($themeConfig['cat_bg'])) ? $themeConfig['cat_bg'] : base_url('public/soma/mooncake_v1/theme1/bg2.jpg');
?>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $wx_config["appId"]?>',
    timestamp: <?php echo $wx_config["timestamp"]?>,
    nonceStr: '<?php echo $wx_config["nonceStr"]?>',
    signature: '<?php echo $wx_config["signature"]?>',
    jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
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
    	    success: function () {},
    	    cancel: function () {}
    	});
<?php endif; ?>
});
</script>
<body>
<div class="pageloading" style="background:rgba(0,0,0,0.5)"><p class="isload" style="margin-top:150px">正在加载</p></div>
<!-- 以上为head -->

<!-- 显示分销号start -->
    <div class="distribute_btn" style="display:none">
        <span><img src="<?php echo base_url('public/soma/images/distributeimg.jpg');?>" /></span>
    </div>
    <div class="ui_pull distribute" style="display:none" >
        <div class="pullbox center bg_fff">
            <div class="pullclose bg_999" onClick="toclose()">&times;</div>
            <div class="pullimg"><div class="squareimg"><img src="<?php echo base_url('public/soma/images/distributeimg.jpg');?>" /></div></div>
            <div>分销号:<span id="distribute_id"></span></div>
            <div>姓　名:<span id="distribute_name"></span></div>
            <div class="bg_999 pullbtn h26" onClick="toclose()">取消</div>
            <a class="bg_main pullbtn h26" id="distribute_url" href="">进入分销</a>
        </div>
    </div>
    <script>
        $('.distribute_btn').click(function(){toshow($('.distribute'));});

        //异步查询分销员号
        function get_saler(){
            var saler = "<?php echo $this->input->get('saler');?>";
            var url = "<?php echo Soma_const_url::inst()->get_url('*/package/get_saler_id_by_ajax',array( 'id'=> $this->inter_id) );?>";
            $.ajax({
                url: url,
                type: 'post',
                data: {saler:saler},
                dataType: 'json',
                success:function( json ){
                    if( json.status == 1 ){
                        if(json.jump_url== 1){
                        	window.location="<?php 
                            	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                            	    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                            	echo "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        	?>&saler="+ json.sid;
                        }
                        if(json.show_button== 1){
                        	//alert( json.sid + json.name );
                            $("#distribute_id").html(json.sid);
                            $("#distribute_name").html(json.name);
                            $("#distribute_url").attr('href',json.url);
                            $(".distribute_btn").show();
                        }
                    }
                }
            });
        }
        // get_saler();
    </script>
<!-- 显示分销号end -->
<div class="goods_list" style="background-image:url(<?php echo $bg_img; ?>)">

    <?php
    $mooncakeArr = array();
    foreach($products as $k=>$v){
    ?>
    <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>">
        <span class="itemborder"></span>
        <div class="squareimg">
        	<img src="<?php echo $v['face_img'];?>"/>
		<?php if(isset($v['killsec'])){ //有秒杀 ?>
            <div class="labels color_main h24">秒杀</div>
        <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
            <div class="labels color_main h24">拼团</div>
        <?php } elseif(isset($v['auto_rule'])){ //有活动 ?>
            <div class="labels color_main h24">满减</div>
        <?php }?> 
        </div>
        <p class="txtclip color_000 h28"><?php echo $v['name'];?></p>
        <?php if(isset($v['killsec'])){ //有秒杀 ?>
            <p class="h28 pad3"><span class="color_main">秒杀价</span>&nbsp;<span class="btn_main y bdradius"><?php echo $v['killsec']['killsec_price'];?></span></p>
        <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
            <p class="h28 pad3"><span class="color_main">拼团价</span>&nbsp;<span class="btn_main y bdradius"><?php echo $v['groupon']['group_price'];?></span></p>
        <?php } else{ ?>
            <p class="h28 pad3"><span class="color_main">售价</span>&nbsp;<span class="btn_main y bdradius"><?php echo $v['price_package'];?></span></p>
        <?php } ?>
    </a>
    <?php
    }
    ?>
<!--    <a href="pay.html">-->
<!--        <span class="itemborder"></span>-->
<!--        <div class="img"><img src="--><?php //echo base_url('public/soma/mooncake_v1/images/eg3.jpg');?><!--"/></div>-->
<!--        <p class="txtclip color_000 h28">金房卡月饼一号1234</p>-->
<!--        <p class="h28"><span class="color_main">售价</span>&nbsp;<span class="btn_main y bdradius">399.00</span></p>-->
<!--    </a>-->
<!--    <a href="pay.html">-->
<!--        <span class="itemborder"></span>-->
<!--        <div class="img"><img src="--><?php //echo base_url('public/soma/mooncake_v1/images/eg3.jpg');?><!--"/></div>-->
<!--        <p class="txtclip color_000 h28">金房卡月饼一号1234</p>-->
<!--        <p class="h28"><span class="color_main">售价</span>&nbsp;<span class="btn_main y bdradius">399.00</span></p>-->
<!--    </a>-->
</div>
<script>
    $(function(){
        window.setTimeout(removeload,200);
    });
</script>


</body>
</html>