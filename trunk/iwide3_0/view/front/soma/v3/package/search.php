<body>
<link href="<?php echo base_url('public/soma/v1/v1.css'). config_item('css_debug');?>" rel="stylesheet">
<link href="<?php echo base_url('public/soma/v3/v3.css'). config_item('css_debug');?>" rel="stylesheet">
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

<div class="tp_list">
    <?php foreach($packages as $k=>$v){?>
    <?php
        // 是否显示¥符号
        $show_y_flag = true;
        if($v['type'] == MY_Model_Soma::PRODUCT_TYPE_POINT)
        {
            $show_y_flag = false;
        }
    ?>
        <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>" class="item bg_fff">
            <div class="img">
                <img src="<?php echo $v['face_img'];?>"/>

                <?php if(isset($v['killsec'])){ //有秒杀 ?>
                    <div class="j_label color_main h24">秒杀</div>
                <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                    <div class="j_label color_main h24">拼团</div>
                <?php } elseif(isset($v['auto_rule'])){ //有活动 ?>
                    <div class="j_label color_main h24">满减</div>
                <?php } ?>
            </div>
            <p><?php echo $v['name'];?></p>

            <div class="item_foot webkitbox justify pad3">
                <div class="h20">
                <?php if($v['can_gift']== $packageModel::CAN_T){ ?><span class="btn_void xs color_888">可赠好友</span><?php } ?>
                <?php if($v['can_reserve']== $packageModel::CAN_F){ ?><span class="btn_void xs color_888">不需预约</span><?php } ?>
                </div>
                <div class="color_main h34">
                	<?php if($show_y_flag): ?><tt class="h20 y"><?php else: ?><tt class="h20"><?php endif; ?></tt>
                <?php if(isset($v['killsec'])){ /*有秒杀*/echo $v['killsec']['killsec_price'];?>
                <?php } elseif(isset($v['groupon'])){ /*有拼团*/ echo $v['groupon']['group_price'].' | '.$v['groupon']['group_count'].'人团';?>
                <?php } else{ echo $v['price_package']?>
                <?php } ?>
                </div>
            </div>
        </a>
    <?php } ?>
</div>

<?php
        if(empty($packages)){
?>

<div class="ui_none" onClick="history.back(-1)"><div>此分类暂未添加<span style="color:blue;">(返回上一级)</span></div></div>


<?php
        }
?>

</body>
</html>