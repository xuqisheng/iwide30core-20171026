<?php /** 订单处理页面 **/ ?>
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/choosebtn.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/bill.css')?>" rel="stylesheet">
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"]?>',
    timestamp: <?php echo $signPackage["timestamp"]?>,
    nonceStr: '<?php echo $signPackage["nonceStr"]?>',
    signature: '<?php echo $signPackage["signature"]?>',
    jsApiList: [
		'onMenuShareTimeline','onMenuShareAppMessage'
    ]
  });
  wx.ready(function(){
  wx.hideMenuItems({
		menuList: [
			"menuItem:favorite"<?php if(!$can_gift) echo ',"menuItem:share:appMessage","menuItem:share:timeline"'; ?>
		]
	});
  	wx.onMenuShareTimeline({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>',
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    success: function () {
			$.getJSON("<?php echo site_url('mall/wap/save_share').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>",{'c':'<?php echo $guid?>','o':'<?php echo $oid?>','i':'<?php echo $itemc?>','t':0},function(){
				window.location.href="<?php echo site_url('mall/wap/order_status/'.$order_info['order_id']).'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>";
            });
	    },
	    cancel: function () {}
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $share["title"]?>',
	    desc: '<?php echo $share["desc"]?>',
	    link: '<?php echo $share["link"]?>', 
	    imgUrl: '<?php echo $share["imgUrl"]?>',
	    type: '<?php echo $share["type"]?>', 
	    dataUrl: '<?php echo $share["dataUrl"]?>',
	    success: function () { 
	    	$.getJSON("<?php echo site_url('mall/wap/save_share').'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>",{'c':'<?php echo $guid?>','o':'<?php echo $oid?>','i':'<?php echo $itemc?>','t':0},function(){
			     window.location.href="<?php echo site_url('mall/wap/order_status/'.$order_info['order_id']).'?id='.$inter_id.'&t='.$topic['identity'].'&saler='.$saler?>";
            });
	    },
	    cancel: function () {}
	});
});
</script>
<title>订单处理</title>
<style>
.footfixed>a,.page{ display:none}
.footfixed>a.cur,.page.cur{ display:block}
.pullhelp p{ padding:3%; line-height:1.5}
</style>
</head>
<body>
<ul class="ui_tab">
	<?php if($mail_type==1): //对于可邮寄的子订单   ?>
		<li val="" class="cur" >邮寄</li>
		<li val="<?php if(!$can_gift) echo 'disable'; ?>" >送给朋友</li>
		<li val="<?php if(!$can_pickup) echo 'disable'; ?>" >门店自提</li>

	<?php else:  //不可邮寄的子订单  ?>
		<li val="disable" >邮寄</li>
		<li val="<?php if(!$can_gift) echo 'disable'; ?>" >送给朋友</li>
		<li val="" class="cur" >门店自提</li>
	<?php endif; ?>
</ul>

<form method="post" id="form_id" action="<?php echo site_url('mall/wap/order_status/'.$oid). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}&mail=1" ?>">
<div class="content">
	<?php $total_qty=0; foreach ($order_items as $v): $owner_openid=$v['get_openid']; ?> 
	<div class="item">
		<div class="itemimg"><img src="<?php echo $goods[$v['gs_id']]['gs_logo'] ?>"></div>
		<div class="hotelname"><?php echo $v['gs_name'] ?></div>
		<div class="desc gray"><?php echo $goods[$v['gs_id']]['gs_desc'] ?></div>
		<?php if( isset($v['consume_start']) && strtotime($v['consume_start'])> time() ): ?>
			<div class="get_item disable" style="display:none">还没到领取时间~</div>

		<?php elseif( isset($v['consume_end']) && strtotime($v['consume_end'])< time() ): ?>
			<div class="get_item disable" style="display:none">已超过领取期限</div>

		<?php elseif( isset($v['status']) && in_array( $v['status'], $items_model->can_consume_status() ) ): ?>
			<div class="get_item" style="display:none" onClick="get_item(this)">
            	扫码领取此商品
                <!-- 细单二维码弹层（多个） -->
                <div class="pull pullitem" style="display:none" onClick="toclose();window.clearInterval(check_time);write_off()">
                    <div class="box">
                    	<div style="font-size:0">
                        <div class="pullclose">&times;</div>
                        <div class="name"><?php echo $v['gs_name'] ?></div>
                        <div class="itemcount">商品数量：<?php echo $v['qty'] ?></div>
						<?php if( isset($v['consume_end'])&& $v['consume_end'] ): ?>
							<div class="val_date">有效期 <?php echo substr($v['consume_start'],0,10). ' 至 '. substr($v['consume_end'],0,10); ?></div>
						<?php endif; ?>
                        <div class="val_date">订单号  <?php echo $order_info['out_trade_no']; ?></div>
						</div>
                        <img src="<?php echo base_url('public/mall/multi/images/border_img.png')?>" style="margin:-2px 0" />
                        <div class="code">
							<img src="<?php echo site_url('mall/wap/item_consume_qrcode/'). "?id=$inter_id&code=". $v['consume_code']; ?>" />
							<div class="name"><?php echo $orders_model->qr_order_no_splite($order_info['out_trade_no'], $v['id']); ?></div>
							<div>请向店员出示此二维码，或报读以上字串</div>
						</div>
                    </div>
                </div>
            </div>
		<?php else: ?>
			<div class="get_item disable" style="display:none">领取时间<?php echo $v['consume_time']; ?></div>
		<?php endif; ?>
		<div style="margin-top:3%">
			<span class="ui_price color"><?php echo $v['promote_price'] ?></span>
			<span class="count gray"><?php echo $v['qty'] ?></span>
		</div>
    </div>
	<?php endforeach; ?>

    <div class="page cur">
     	<div class="borderimg"></div>
        <?php if(empty($address)):?><a href="<?php echo site_url('mall/wap/address_edit/'.$oid)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="rowsbtn">
            <div class="address">您还未登记邮寄信息，马上登记一个吧</div>
        </a><?php else:?>
        <a href="<?php echo site_url('mall/wap/addresses/'.$oid)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="rowsbtn">
            <span class="username">姓名：<?php echo $address['contact']?></span>
            <span class="telphone"><?php echo $address['phone']?></span>
            <div class="address">地址：<?php echo $address['province'].$address['city'].$address['region'].$address['address']?></div>
        </a><?php endif;?>
        <div class="help blue">使用说明</div>

<?php if( $openid== $owner_openid && isset($order_info['is_invoice']) && $order_info['is_invoice']==EA_base::STATUS_FALSE ): ?>
        <div class="bill rowsbtn">
            <span>开具发票</span>
            <tt>不需要</tt>
        </div>
<?php endif; ?>

    </div>
	<?php $myorder_url= site_url('mall/wap/my_orders'). "?id=$inter_id&t=". $topic['identity']. "&saler=$saler"; ?>
    <div class="page">
        <div class="notic gray">注：目前系统仅支持<b>整个订单所有商品</b>赠送给朋友的方式，请留意。送给朋友后，您的这个订单里的所有商品都将送给这个朋友；您可以到<a href="<?php echo $myorder_url; ?>" class="blue">“个人中心”</a>-<a href="<?php echo $myorder_url; ?>" class="blue">“我的订单”</a>中查看订单状态</div>
        <div class="help blue">使用说明</div>
    </div>
    <div class="page">
		<?php if($can_pickup): ?>
        <div class="notic gray">小提示：您可以在<a href="<?php echo $myorder_url ?>" class="blue">“我的订单”</a>中查看此单详情；
			<br/>　　　　领取某个商品，请点击“扫码领取此商品”；<!--<br/>一次性领取所有商品，请扫描最下方的二维码；-->
		</div>
        <div class="help blue">使用说明</div>
        <div class="saoma">
<?php if(false): ?>
            <div>该商品已经核销</div>
            <img src="<?php echo (isset($order_info['qrcode_url']))? $order_info['qrcode_url']: $qrcode ?>" />
            <div>请向门店服务员出示此二维码</div>
<?php endif; ?>
        </div>
		<?php endif; ?>
    </div>
</div>
<div class="footfixed">
	<?php if($can_mail): ?>
		<a href="javascript:;" onclick="save_mail()" class="surebtn bg_orange cur">确认邮寄</a>
	<?php else: ?>
		<a href="javascript:;" class="stroebtn bg_orange" style="background:#999">不支持邮寄</a>
	<?php endif; ?>

	<?php if($can_gift): ?>
		<a href="javascript:;" class="sendbtn bg_orange">赠送给好友</a>
	<?php else: ?>
		<a href="javascript:;" class="stroebtn bg_orange" style="background:#999">不支持赠送好友</a>
	<?php endif; ?>

	<?php if($can_pickup): ?>
		<a href="javascript:;" class="stroebtn bg_orange" >可到商家门店自提</a>
	<?php else: ?>
		<a href="javascript:;" class="stroebtn bg_orange" style="background:#999">此订单不支持自提</a>
	<?php endif; ?>
	<!--<a href="<?php echo site_url('mall/wap/stores')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="stroebtn bg_orange">此商品不支持门店自提</a>-->
</div>

<div class="pull pullshare" style="text-align:right; display:none">
    <div style="padding-right:3%">
        <img src="<?php echo base_url('public/mall/multi/images/ico/arrow.png')?>" style="width:10%"/>
    </div>
    <p style="padding-right:12%; font-size:0.8rem;">点击并发送给朋友</p>
</div>

<div class="pull pullhelp" style=" background:#f8f8f8; color:#000; display:none">
    <h2 style="font-size:1.6rem;text-align:center;margin-top:5%;">使用说明</h2>
        <p>购买流程说明：</p>
        <p>购买：点击&ldquo;立即预定&rdquo;后，进入到商品详情界面；点击&ldquo;立即购买&rdquo;，界面后弹出购买数量编辑弹层，添加商品数量，点击立即购买，进入支付界面完成支付后，即可进行自己邮寄、&ldquo;送给朋友&rdquo;、&ldquo;自提&rdquo;等操作；</p>
        <p>赠送流程说明：</p>
        <p>此商品在购买成功后，可以将其赠送给自己的好友；</p>
        <p>在&ldquo;订单处理&rdquo;界面，点击&ldquo;送给朋友&rdquo; ，进入到此界面后，点击确定，即可通过手机分享给自己的朋友；</p>
        <p>自提流程说明：</p>
        <p>在购买完成后，可以选择&ldquo;门店自提&rdquo;，在查看&ldquo;附近门店&rdquo;后，可以去离自己最近的门店向店员出示&ldquo;二维码&rdquo;，以领取商品；</p>
</div>
<div class="pull pullbill" style=" background:#f8f8f8; color:#000; display:none">
	<div class="whileblock choosebill">
    	<div class="choosebtn"><i></i></div>
        <span>是否需要开发票</span>
    </div>
    <div class="billtitle" style=" display:none">
    	<div style="padding:3%;">发票抬头</div>
        <input type="text" name="inv_title" style="width:100%;" class="whileblock" placeholder="点击输入发票抬头信息">
    </div>
    <div class="billnotic">温馨提示:<br>发票金额为现金支付金额（扣除抵用券、红包立减、返现金额等。）</div>
    <div class="footfixed"><div class="surebtn bg_orange">确定</div></div>
</div>



<input type="hidden" id="needbill" name="inv_need" value='2'><!--"是否需要发票1:需要;2:不需要;"-->
</form>
</body>
</html>
<script>
$(function(){
	$('.ui_tab li').on('click',function(){
		var _index = $(this).index();
		if ($(this).attr('val')== 'disable'){
			alert('不支持'+$(this).html()+'哦~');
			return;
		}
		$(this).addClass('cur');
		$(this).siblings().removeClass('cur');
		if( !_index ) $('.borderimg').show();
		else  $('.borderimg').hide();
		if( _index == 2 )$('.get_item').show();
		else $('.get_item').hide();
		$('.page').eq(_index).addClass('cur');
		$('.page').eq(_index).siblings('.page').removeClass('cur');
		$('.footfixed a').eq(_index).addClass('cur');
		$('.footfixed a').eq(_index).siblings().removeClass('cur');
	});
	for ( var i=0; i<$('.ui_tab li').length; i++){
		if ($('.ui_tab li').eq(i).attr('val') != 'disable' && i!=1){
			$('.ui_tab li').eq(i).trigger('click');
			break;
		}
	}
	$('.sendbtn').click(function(){
		toshow($('.pullshare'));
		//window.setTimeout(toclose,1800);
	})
	$('.help').click(function(){
		toshow($('.pullhelp'));
	})
	$('.bill').click(function(){
		toshow($('.pullbill'));
		if($(this).find('tt').html() != '不需要'){
			$('.choosebtn').addClass('ischoose');
			$('.billtitle').show();			
		}
	})
	$('.choosebill').click(function(){
		if ( $('.choosebtn').hasClass('ischoose') ){
			$('.choosebtn').removeClass('ischoose');
			$('.billtitle').hide();
		}
		else{
			$('.choosebtn').addClass('ischoose');
			$('.billtitle').show();
		}
	})
	$('.pullbill .surebtn').click(function(){  //发票弹层 的 确定 按钮
		toclose();
		if ( !$('.choosebtn').hasClass('ischoose') || $('.billtitle input').val()==''){
			$('.bill tt').html('不需要');
			$('#needbill').val(2);	//发票的hidden
			return;
		}
		$('.bill tt').html('已填写');
		$('#needbill').val(1); //发票的hidden
	})
})
    function save_mail(){
		<?php if(!empty($address)){?>
        $.post("<?php echo site_url('mall/wap/save_mail_order'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>",{
			'oid':<?php echo $oid?>,
			'aid':<?php echo $address['id']?>
		},function(data){
            if(data.errmsg == 'ok'){
				$('#form_id').submit();
//window.location.href="<?php echo site_url('mall/wap/order_status/'.$oid). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>";
            }else{
                alert('保存邮寄信息失败，请稍后再重试！');
            }
        },'json');
		<?php }else{ ?>
			alert('请先选择地址');
        <?php } ?>
    }
	var check_time;
	function checking(){
		window.clearInterval(check_time);
		check_time = window.setInterval(write_off,5000);
	}
	function write_off(){
		$.post("<?php echo site_url('mall/wap/item_consume_check'). "?id={$inter_id}&t={$topic['identity']}&saler={$saler}&f={$fans_id}" ?>",{
				'oid':<?php echo $oid; ?>,
				's':'<?php echo $checkjson; ?>'
			},function(data){
				if(data.status == 2){
					alert('您的部分商品已被成功核销！');
					location.reload();
				}
		},'json');
	}
	function get_item(obj){
		toshow($(obj).find('.pullitem'));	
		checking();
	}
</script>
