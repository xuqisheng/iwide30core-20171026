<body>
<link href="<?php echo base_url('public/soma/v1/v1.css'). config_item('css_debug');?>" rel="stylesheet">
<div class="pageloading"><p class="isload">正在加载</p></div>
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
    	    //type: '', //music|video|link(default)
    	    //dataUrl: '', //use in music|video
    	    success: function () {},
    	    cancel: function () {}
    	});
<?php endif; ?>
});
</script>

<style>
body,html{ background:#fffaf4}
</style>
<?php if( isset( $staff ) && $staff ):?>
<div class="pull_btn"><img src="<?php echo get_cdn_url('public/soma/zongzi/pull_btn.png');?>"></div>
<div class="alert_cont">
    <div class="conter_box">
        <div style="width:40px;margin:auto;"><img src="<?php echo get_cdn_url('public/soma/zongzi/pull_btn.png');?>" /></div>
        <div class="conter_txt">
            <p><span>姓名:</span><span><?php echo $staff['saler_name'];?></span></p>
            <p><span>分销id:</span><span><?php echo $staff['saler_id'];?></span></p>
        </div>
        <div class="btns_list">
            <div class="cancel_btn">取消</div>
            <div class="reward_btn">赚奖励</div>
        </div>
    </div>
    <div class="share_box">
        <div><img src="<?php echo get_cdn_url('public/soma/zongzi/alter_txt.png');?>"></div>
    </div>
</div>
<?php endif;?>
<div class="squareimg" style="padding-bottom:51%; overflow:hidden">
	<img <?php if( $zongzi_cat_bg ):?>src="<?php echo $zongzi_cat_bg;?>"<?php else :?>src="<?php echo base_url('public/soma/zongzi/banner.jpg');?>"<?php endif;?>>
</div>
<div class="tp_list">
    <?php foreach($products as $k=>$v){?>
        <?php
            // 是否显示¥符号
            $show_y_flag = true;
            if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
            {
                $show_y_flag = false;
            }
        ?>
        <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>" class="item bg_fff">
            <div class="img" style="height: 193px;">
                <img src="<?php echo $v['face_img'];?>"/>

                <?php if(isset($v['killsec'])){ //有秒杀 ?>
                    <div class="j_label color_main f_s_12">秒杀</div>
                <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                    <div class="j_label color_main f_s_12">拼团</div>
                <?php } elseif(isset($v['auto_rule'])){ //有活动 ?>
                    <div class="j_label color_main f_s_12">满减</div>
                <?php } ?>
            </div>
            <p style="color:#583b30"><?php echo $v['name'];?></p>
            
            <?php if(isset($v['killsec'])){ //有秒杀 ?>
                <p class="item_foot">售价:<span class="h34"><?php echo $v['killsec']['killsec_price'];?></span><del class="y"><?php echo $v['price_market']?></del></p>
            <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                <p class="item_foot"><?php echo $v['groupon']['group_count'];?>人团:<span class="h34"><?php echo $v['groupon']['group_price'];?></span><del class="y"><?php echo $v['price_market']?></del></p>
            <?php } else{ ?>
                <p class="item_foot">售价:<span class="h34"><?php echo $v['price_package']?></span><del class="y"><?php echo $v['price_market']?></del></p>
            <?php } ?>
        </a>
    <?php } ?>
</div>

<?php
        if(empty($products)){
?>

<div class="ui_none" onClick="history.back(-1)"><div>此分类暂未添加<span style="color:blue;">(返回上一级)</span></div></div>


<?php
        }
?>


<script src="<?php echo get_cdn_url('public/soma/zongzi/jie.js');?>"></script>
<script>
    window.onload=function(){
        $('.img').each(function(index, element) {
            $(this).height($(this).width());
        });
    }
</script>
</body>
</html>