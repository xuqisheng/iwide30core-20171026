<?php include 'header.php'?>
<style>
.img{margin-right:10px;width:80px; max-width:80px; min-width:80px}
</style>
<div class="webkitbox pad3 bg_fff">
    <div>
        <div class="h30"><?php echo $order['hname']?></div>
        <div class="color_888 h22"><?php echo $order['haddress'];?></div>
    </div>
    <div onclick="tonavigate(<?php echo $order['latitude'];?>,<?php echo $order['longitude'];?>,'<?php echo $order['hname'];?>','<?php echo $order['haddress'];?>')" class="pad3" style="display:inline-block"><em class="iconfont">&#x36;</em> 导航</div>
</div>
<div class="webkitbox h24 pad3 bg_fff bd martop overflow" style="padding-top:0; padding-bottom:0">
    <div class="img">
        <div class="squareimg "><img src="<?php echo base_url('public\hotel\public\images\egimg\eg01.png')?>"></div>
    </div>
    <div>
        <div class="h36 color_000"><?php echo $order['first_detail']['roomname'];?></div>
        <div class="color_888"><?php echo $order['first_detail']['price_code_name'];?>  数量：<?php echo $order['roomnums'];?> 张</div>
        <div class="color_main webkitbox justify">
			<span><?php echo date('Y.m.d',strtotime($order['startdate']));?></span>
            <span><?php echo $order['status_des'];?></span>
        </div>
    </div>
</div>
<div class="pad3 bg_fff martop bd_top">用户信息</div>
<div class="list_style bd">
	<div class="input_item">
    	<span>姓名</span>
        <div><?php echo $order['name'];?></div>
    </div>
	<div class="input_item">
    	<span>手机号</span>
        <div><?php echo $order['tel'];?></div>
    </div>
</div>

<section class="pad3 bg_fff h24 color_555 martop">
	<div class="h30">订单信息</div>
    <div class="webkitbox martop input_item">
        <div class="input_item color_999">下单时间</div>
        <div><?php echo date('m.d H:i',$order['order_time']);?></div>
    </div>
    <div class="webkitbox martop input_item">
        <div class="input_item color_999">订单编号</div>
        <div><?php echo $order['show_orderid'];?></div>
    </div>
	<div class="webkitbox martop input_item">
        <div class="input_item color_999">支付类型</div>
        <div><?php echo $status_des['PAY_WAY'][$order['paytype']];?></div>
    </div>
	<div class="webkitbox martop input_item">
        <div class="input_item color_999">数量</div>
        <div><?php echo $order['roomnums'];?>张</div>
    </div>
	<div class="webkitbox martop input_item">
        <div class="input_item color_999">优惠券</div>
        <div>-<?php echo $order['coupon_favour'];?></div>
    </div>
	<div class="webkitbox martop input_item">
        <div class="input_item color_999">积分</div>
        <div>-<?php echo $order['point_favour'];?></div>
    </div>
<?php if($order['paytype']=='weixin'){?>
    <div class="webkitbox martop input_item">
        <div class="input_item color_999">支付状态</div>
        <div><?php if($order['paid']==1){?>已支付<?php }else{?>未支付<?php }?></div>
    </div>
<?php }?>
    <div class="webkitbox martop input_item">
        <div class="input_item color_999">订单状态</div>
        <div><?php echo $order['status_des'];?></div>
    </div>
    <?php if(!empty($not_same)){ foreach($order['order_details'] as $k=>$od) {?>
    <div class="webkitbox martop input_item">
        <div class="input_item  color_999">订单<?php echo ($k+1);?></div>
        <div><?php echo $status_des['HOTEL_ORDER_STATUS_TICKET'][$od['istatus']];?></div>
    </div>
    <?php }}?>
    <div class="webkitbox martop input_item">
        <div class="input_item color_999">实付金额</div>
        <div  class="y color_main"><?php echo $order['price'];?></div>
    </div>
</section>
<div class="pad3 bg_fff martop bd">
	<div>温馨提示</div>
	<div class="h22 color_888"><?php echo $hotel['book_policy'];?></div>
</div>
<div style="padding-top:4rem">
    <div class="bottomfixed webkitbox bg_fff bdtop center" style="z-index:999">
    <?php if($can_cancel==1){?>
    	<div class="pad10" onclick="cancel_order()">取消订单</div><!-- 在线支付不可取消 无取消订单按钮 -->
    	<?php }else if($can_comment==1){?>
		<a class="pad10" href='<?php echo Hotel_base::inst()->get_url("TO_COMMENT",array('oid'=>$order['id']))?>'>评价</a>
		<?php }?>
		<?php if($re_pay==1){?>
        <?php if($order['paytype']=='weifutong'){?>
         <a id="pay" href="<?php echo site_url('wftpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main" style="padding:4px;">
            支付订单
            <div class="h20" timeout="<?php echo $timeout;?>">00:00</div>
         </a>
        <?php }elseif($order['paytype']=='lakala'){?>
         <a id="pay" href="<?php echo site_url('lakalapay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main" style="padding:4px;">
            支付订单
            <div class="h20" timeout="<?php echo $timeout;?>">00:00</div>
         </a>
        <?php }else{?>
		 <a id="pay" href="<?php echo site_url('wxpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main" style="padding:4px;">
         	支付订单
            <div class="h20" timeout="<?php echo $timeout;?>">00:00</div>
         </a>
         <?php }?>
		<?php }else{?>
        <a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$order['hotel_id'],'type'=>$order['price_type']))?>" class="pad10 bg_main">再次预订</a>
        <?php }?>
    </div>
</div>
</body>
<script>
function cancel_order(){
	if(confirm('您确定要取消吗？')){
		pageloading('请稍候');
		$.get('<?php echo Hotel_base::inst()->get_url("CANCEL_MAIN_ORDER");?>',{
			oid:'<?php echo $order['orderid']?>'
		},function(data){
			removeload();
			if(data.s==1){
				$.MsgBox.Alert(data.errmsg,function(){
					location.reload();
				});
			}
			else{
				$.MsgBox.Alert(data.errmsg);
			}
			
		},'json')
	}
}
$(function(){
	$('[timeout]').each(function() {
		if($(this).attr('timeout')=='')return;
		try{
			var $this = $(this);
			var time=parseInt( $this.attr('timeout')); //剩余秒数
			if(isNaN(time))return;
			var tmp=window.setInterval(function(){
				var theTime = parseInt(time--);// 秒
				if(time<=0){
					$this.html('支付超时');
                    $('#pay').attr('href','javascript:void(0);');
					window.clearInterval(tmp);
					return;
				}
				var theTime1 = 0;// 分
				var theTime2 = 0;// 小时
				if(theTime > 60) {
					theTime1 = parseInt(theTime/60);
					theTime = parseInt(theTime%60);
					if(theTime1 > 60) {
						theTime2 = parseInt(theTime1/60);
						theTime1 = parseInt(theTime1%60);
					}
				}
				var result = parseInt(theTime);
				if(theTime1 > 0) {
				result = parseInt(theTime1)+":"+result;
				}
				if(theTime2 > 0) {
				result = parseInt(theTime2)+":"+result;
				}
				$this.html('支付倒计时 '+ result);
			},1000);
		}catch(e){
			$.MsgBox.Alert(e);
		}
    });
})
</script>
</html>
