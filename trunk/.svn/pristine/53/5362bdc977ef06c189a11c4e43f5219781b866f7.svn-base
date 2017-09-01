

<link href="<?php echo base_url('public/soma/mooncake_v1/mooncake.css'). config_item('css_debug');?>" rel="stylesheet">
<style>
body,html{background:#fff !important}
</style>
<div class="pageloading"><p class="isload">正在加载</p></div>
<div class="pay_status block later">
	<p><em class="iconfont">&nbsp;</em></p>
    <p>还有谁领到了礼物</p>
</div>

<div class=" foot_btn">
	<a style="color:#fff;" href="<?php echo Soma_const_url::inst()->get_url('*/package/index', array( 'id'=>$inter_id ) ); ?>">我也要购买</a>
</div>
<div class="center pad10 h26">
	<span class="color_fff" style="background:#434343; border-radius:1rem; display:inline-block; padding:3px 8px;">送出礼物：<?php echo $item['name'];?></span>
</div>

<?php if( $gift_data['is_p2p'] == $gift_model::GIFT_TYPE_P2P ):?>
<div class="receive_list" style="padding-top:15px">
    <ul class="list_style bd">
        <li class="webkitbox">
            <div class="img"><div class="squareimg"><img src="<?php echo $gift_data['openid_received_headimg'];?>"></div></div>
            <div>
                <p><?php echo $gift_data['openid_received_nickname'];?></p>
                <p><?php echo $gift_data['update_time'];?></p>
            </div>
            <div class="txt_r">收到<?php echo $gift_data['per_give'];?>份</div>
        </li>
    </ul>
</div>
<?php endif;?>
<?php if( $gift_data['is_p2p'] == $gift_model::GIFT_TYPE_GROUP ):?>
	<div class="receive_list" style="padding-top:15px">
	    <ul class="list_style bd">
			<?php foreach( $receive_list as $k=>$v ):?>
			    <li class="webkitbox">
			        <div class="img"><div class="squareimg"><img src="<?php echo $v['openid_headimg'];?>"></div></div>
			        <div>
			            <p><?php echo $v['openid_nickname'];?></p>
			            <p><?php echo $v['get_time'];?></p>
			        </div>
			        <div class="txt_r">收到<?php echo $v['get_qty'];?>份</div>
			    </li>
			<?php endforeach;?>
	    </ul>
	</div>
<?php endif;?>
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
    
</body>
</html> 
