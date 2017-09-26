<body>
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

<div class="pageloading"><p class="isload">正在加载</p></div>
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
<a href="<?php echo Soma_const_url::inst ()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>" class="item color_555">
	<div class="img">
		<img class="lazy" src="<?php echo get_cdn_url('public/soma/images/default.jpg'); ?>"  data-original="<?php echo $v['face_img'];?>" />
		<div class="fn">
			<?php if($v['can_gift']== $packageModel::CAN_T){ ?><span>可赠好友</span><?php } ?>
			<?php if($v['can_reserve']== $packageModel::CAN_F){ ?><span>不需预约</span><?php } ?>
            <?php if($v['can_split_use']== $packageModel::CAN_T){ ?><span>分时可用</span><?php } ?>
		</div>
		<div class="tag absolute h3"><?php
			if(isset($v['killsec'])){
				?> <span>秒杀</span> <?php
			} elseif(isset($v['groupon'])){
				?> <span>拼团</span> <?php
			} ?>
		</div>
		<?php if(isset($v['killsec'])){?>
			<?php if($v['killsec']['killsec_time'] <= date('Y-m-d H:i:s',time()) ){ ?>
				<div class="absolute seckill h24">
					秒杀进行中
				</div>
			<?php }else{	
			?>
				<div class="absolute seckill h24">
				<?php 
				$_tmp_last_time=strtotime($v['killsec']['killsec_time'])-time(); 
				echo '倒计时:'.intval($_tmp_last_time/86400).'天'.intval($_tmp_last_time%86400/3600).'时'.intval($_tmp_last_time%3600/60).'分'; ?>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<p class="txtclip"><?php echo $v['name'];?></p>            
	<div class="foot">
		<p class="bg_minor tp_price">
			<?php if(isset($v['killsec'])){ //有秒杀 ?>
				<span>秒杀价</span>
				<span class="h36" style="width:6rem; padding:0 6px"><?php if($show_y_flag):?>¥<?php endif; ?><?php echo $v['killsec']['killsec_price'];?></span>
				<span class="bg_main">去秒杀<em class="iconfont">&#xe61b;</em></span>
			<?php } elseif(isset($v['groupon'])){ //有拼团 ?>
				<span><?php echo $v['groupon']['group_count'];?>人团</span>
				<span class="h36" style="width:6rem; padding:0 6px"><?php if($show_y_flag):?>¥<?php endif; ?><?php echo $v['groupon']['group_price'];?></span>
				<span class="bg_main">去开团<em class="iconfont">&#xe61b;</em></span>
			<?php } else{ ?>
				<span>惊喜价</span>
				<span class="h36" style="width:6rem;padding:0 6px"><?php if($show_y_flag):?>¥<?php endif; ?><?php echo $v['price_package']?></span>
				<span class="bg_main">去购买<em class="iconfont">&#xe61b;</em></span>
			<?php } ?>
			<!--
			<span>秒杀价</span>
			<span class="h1" style="width:6rem;">¥<?php echo $v['price_package']?></span>
			<span class="bg_main2">去秒杀<em class="iconfont">&#xe61b;</em></span>
	 -->
		</p>
		<p class="tp_local txtclip"><?php echo $v['product_city'];?></p>
	</div>
</a>
<?php } ?>
</div>
<?php if(empty($packages)){?>
<div class="ui_none" onClick="history.back(-1)">
	<div>此分类暂未添加<span class="color_link">返回上一级</span></div>
</div>
<?php }?>
</body>
</html>