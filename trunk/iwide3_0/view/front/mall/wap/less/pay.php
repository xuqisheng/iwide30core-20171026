<link href="<?php echo base_url('public/mall/multi/style/pay.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<title>支付确认</title>
</head>
<body><?php 
/** 立即购买/单品情况 **/
if(isset($details)):?>
<div class="p_head">
	<div class="head_con">
    	<div class="h_l"><img src="<?php echo $details['gs_logo']?>"/></div>
    	<div class="h_r">
        	<p class="h_name"><?php echo $details['gs_name']?></p>
        	<p class="txt_details"><?php echo $details['gs_desc']?></p>
        	<p><font>¥<?php echo $amount= $details['gs_wx_price']?>×<?php echo $details['nums']?></font></p>
        </div>
        <div style="clear:both"></div>
    </div>
</div><?php
//运费计算
		$shipping_amount = 0;
		if( isset($topic['freeship_level']) && isset($topic['shipment_fee']) ){
			if($amount<$topic['freeship_level']){
				$shipping_amount= $topic['shipment_fee'];
			}
		}
		$amount += $shipping_amount;
?>
<div class="con_list">
	<div class="show_lis">
    	<p>支付方式<font>微信支付</font></p>
    	<p>邮资<font><?php 
		    if($shipping_amount>0): echo '￥'. $shipping_amount;
			elseif( isset($topic['shipping_desc']) && $topic['shipping_desc'] ):
    	       echo $topic['shipping_desc'];
    	    else: ?>全国包邮；港澳台、新疆、西藏除外<?php endif; 
		?></font></p>
    </div>
</div>
<div class="fix">
	<span class="money">合计 <font>¥<?php echo number_format($amount*$details['nums'], 2); ?></font></span>
	<span class="fast_pay" onclick="suborder()">立即支付</span>
</div>
<script type="text/javascript">
    function suborder(){
        $.post("<?php echo site_url('mall/wap/new_order')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'gid':<?php echo $details['gs_id']?>,'nums':<?php echo $details['nums']?>},function(data){
            if(data.errmsg == 'ok'){
                window.location.href="<?php echo site_url('wxpay/mall_pay')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"+'&oid='+data.oid;
            	// window.location.href="<?php echo site_url('mall/wap/pay_success')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>"+'&oid='+data.oid;
            }else{
                alert('很抱歉，下单失败，请核对选购商品是否有足够库存');
            }
        },'json');
    }
</script><?php 


/** 多商品情况 **/
elseif(isset($products)):?>
<div class="p_head"><?php $amount=0; foreach ($products as $product):?>
    <div class="head_con">
        <div class="h_l"><img src="<?php echo $product['gs_logo']?>"/></div>
        <div class="h_r">
            <p class="h_name"><?php echo $product['gs_name']?></p>
            <p class="txt_details"><?php echo $product['gs_desc']?></p>
            <p><font>¥<?php echo $product['gs_wx_price']?>×<?php echo $pjson[$product['gs_id']]?></font>
				<?php if($product['can_mail']==EA_base::STATUS_TRUE_) echo '<tt>邮寄</tt>'; 
					if($product['can_pickup']==EA_base::STATUS_TRUE_) echo '<tt>自提</tt>';
					if($product['can_gift']==EA_base::STATUS_TRUE_) echo '<tt>赠送</tt>'; ?>
			</p>
        </div>
        <div style="clear:both"></div>
    </div><?php $amount+=$product['gs_wx_price']*$pjson[$product['gs_id']];endforeach; ?>
<?php
//运费计算
			$shipping_amount = 0;
	        if( isset($topic['freeship_level']) && isset($topic['shipment_fee']) ){
	            if($amount<$topic['freeship_level']){
	                $shipping_amount= $topic['shipment_fee'];
	            }
	        }
	        $amount += $shipping_amount;
	?>
</div>
<div class="con_list">
    <div class="show_lis">
        <p>支付方式<font>微信支付</font></p>
        <p>邮资<font><?php 
		    if($shipping_amount>0): echo '￥'. $shipping_amount;
			elseif( isset($topic['shipping_desc']) && $topic['shipping_desc'] ):
    	       echo $topic['shipping_desc'];
    	    else: ?>全国包邮；港澳台、新疆、西藏除外<?php endif; 
		?></font></p>
    </div>
</div>
<div class="fix">
    <span class="money">合计 <font>¥<?php echo number_format($amount, 2); ?></font></span>
	<?php if( isset($is_ext_order) && $is_ext_order): ?>
    <span class="fast_pay" onclick="toshow($('.pull'));">确认支付</span>
	<?php else: ?>
    <span class="fast_pay" onclick="suborder()">立即支付</span>
	<?php endif; ?> 
</div>
<script type="text/javascript">
    function suborder(){
        $.post("<?php echo site_url('mall/wap/cart_order')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'sps':<?php echo json_encode($pjson)?>},function(data){
            if(data.errmsg == 'ok'){
                window.location.href="<?php echo site_url('wxpay/mall_pay')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"+'&oid='+data.oid;
                // window.location.href="<?php echo site_url('mall/wap/pay_success')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>"+'&oid='+data.oid;
            }else{
                alert('很抱歉，下单失败，请核对选购商品是否有足够库存');
            }
        },'json');
    }
</script>
<?php endif;?>
					<!--
					<form action="" method="post" id="">
					<div class="code">
						<div class="tips"><em></em><dd></dd></div>
						<span>优惠码 </span>
						<span><input type="text" placeholder="请输入优惠码" value="555"><i>(-5元)</i> <tt class="disable">使用</tt></span>
					</div>
					</form>
					-->

<?php //如果同时包含2类订单则显示下面内容
$all_can_gift= TRUE;
if( isset($is_ext_order) && $is_ext_order): ?>
	<div class="notic">
		<tt class="color">提示：</tt>由于本订单中包含多个不同属性商品（可邮寄，可自提，可赠送），因此购买后将自动拆分为多个订单(<tt class="blue">点击查看拆单效果</tt>)，若您不想拆单，可返回单件购买。
	</div>

	<div class="pull" style="display:none;">
		<div class="order_box">
        	<div class="pullclose" onClick="toclose();">&times;</div>
			<div class="pull_title">支付后您的订单将拆分为</div>
			<div class="order_spilt_list">
				<div class="item">
					<div class="item_title"><span>订单1</span>( 可邮寄配送 )</div>
					<?php foreach($item_mail as $k=>$v): ?>
					<div class="p_head"><div class="head_con">
						<div class="h_l"><img src="<?php echo $v['gs_logo']?>"/></div>
						<div class="h_r">
							<p class="h_name txtclip"><?php echo $v['gs_name']?></p>
							<p class="txt_details txtclip"><?php echo $v['gs_desc']?></p>
							<p><font>¥<?php echo $v['gs_wx_price'] ?>×<?php echo $v['qty'] ?></font>
								<?php if($v['can_pickup']==EA_base::STATUS_TRUE_) echo '<tt>自提</tt>';
									if($v['can_gift']==EA_base::STATUS_TRUE_) {
										echo '<tt>赠送</tt>';
									} else $all_can_gift= FALSE; ?>
							</p>
						</div>
					</div></div>
					<?php endforeach; ?>
				</div>
			
				<div class="item">
					<div class="item_title"><span>订单2</span>( 可到店自提 )</div>
					<?php foreach($item_other as $k=>$v): ?>
					<div class="p_head"><div class="head_con">
						<div class="h_l"><img src="<?php echo $v['gs_logo']?>"/></div>
						<div class="h_r">
							<p class="h_name txtclip"><?php echo $v['gs_name']?></p>
							<p class="txt_details txtclip"><?php echo $v['gs_desc']?></p>
							<p><font>¥<?php echo $v['gs_wx_price'] ?>×<?php echo $v['qty'] ?></font>
								<?php if($v['can_gift']==EA_base::STATUS_TRUE_) {
										echo '<tt>赠送</tt>';
									} else $all_can_gift= FALSE; ?>
							</p>
						</div>
					</div></div>
					<?php endforeach; ?>
				</div>
			
				<div class="item">
					<div class="item_title">
					<?php if( $all_can_gift ): ?><span>该订单可以赠送朋友</span>
					<?php else: ?><span>该订单包含不可赠送商品，如需赠送朋友请重新挑选。</span>
					<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="foot_btn">
				<div onClick="suborder()">同意, 前往支付</div>
				<div onClick="toclose(); history.back(-1);">返回购物车</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<div style="padding-top:12%"></div>
</body>

<script>
function _post(){
	if( true){
	}
	else{
		$('.tips').show();
		$('.tips dd').html('优惠码无效, 请检查是否存在优惠码或者输入有误')
	}
}
$('.code input').bind('input propertychange', function() {
	$('.code i').html('');
	$('.tips').hide();
	$('.code button').unbind('click');
	var val=$(this).val();
	if( val ==''){
		$('.code tt').addClass('disable');
	}
	else{
		$('.code tt').removeClass('disable');
		$('.code tt').bind('click',_post);
	}
});  
$('.notic').click(function(){
	toshow($('.pull'));
})
</script>
</html>