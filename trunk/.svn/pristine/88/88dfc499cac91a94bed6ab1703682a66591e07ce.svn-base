<link href="<?php echo base_url('public/mall/multi/style/pay.css')?>" rel="stylesheet">
<title>支付</title>
</head>
<body>
<div class="p_head">
	<div class="head_con">
    	<div class="h_l"><img src="<?php echo $details['gs_logo']?>"/></div>
    	<div class="h_r">
        	<p class="h_name"><?php echo $details['gs_name']?></p>
        	<p class="txt_details"><?php echo $details['gs_desc']?></p>
        	<p><font>¥<?php echo $details['gs_wx_price']?></font></p>
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<div class="con_list">
	<div class="show_lis">
    	<p>购买数量<font><?php echo $details['nums']?></font></p>
    	<p>支付方式<font>微信支付</font></p>
    	<p>邮资<font><?php if( isset($topic['shipping_desc']) && $topic['shipping_desc'] ):
    	       echo $topic['shipping_desc'];
    	    else: ?>全国包邮；港澳台、新疆、西藏除外<?php endif; ?></font></p>
    </div>
</div>
					<!--
					<form action="" method="post" id="">
					<div class="code">
						<span>优惠码 </span>
						<span><input type="text" placeholder="请输入优惠码"> <tt class="disable">使用</tt></span>
					</div>
					</form>
					-->
<div class="fix">
	<span class="money">合计 <font>¥<?php echo $details['gs_wx_price']*$details['nums']?></font></span>
	<span class="fast_pay" onclick="suborder()">立即支付</span>
</div>
</body>
</html>
<script type="text/javascript">
    function suborder(){
        $.post("<?php echo site_url('mall/wap/new_order')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'gid':<?php echo $details['gs_id']?>,'nums':<?php echo $details['nums']?>},function(data){
            if(data.errmsg == 'ok'){
                window.location.href="<?php echo site_url('wxpay/mall_pay')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>"+'&oid='+data.oid;
            	// window.location.href="<?php echo site_url('mall/wap/pay_success')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>"+'&oid='+data.oid;
            }else{
                alert('下单失败,请重试！');
            }
        },'json');
    }
function _post(){
	$('').submit();
}

$('.code input').bind('input propertychange', function() {
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
</script>