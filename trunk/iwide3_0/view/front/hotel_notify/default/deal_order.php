<?php include 'header.php' ?>
<style>
body{font-size: 12px;}
.deal-big-title{font-size: 24px;margin-bottom: 4px;}
.deal-wrong{color: #d54d76;}
.deal-sure{color: #316f4d;}
.deal-header{background-color: white;padding: 10px 0; text-align: center;color: #E5A15A;}
.deal-big-word{color: #cdcdd0}
.deal-rows{background-color: white;padding: 20px 0;margin-bottom: 2px;}
.deal-title{display: inline-block;width: 80px;text-align: right;margin-right: 10px;margin-bottom: 5px;}
.deal-word{display: inline-block;}
.deal-button-wrapper{text-align: center;margin-top: 15px;}
.deal-button-delete{padding: 3px 25px;margin: 0px 10px;border: 1px solid #e3e3e3;background-color: white;color: black;border-radius: 3px; }
.deal-button-sure{padding: 3px 25px;margin: 0px 10px;border: 1px solid #f29d4c;background-color: #f29d4c;color: white;border-radius: 3px; }
.deal-tips{padding: 15px 0 15px 10px;}
.show-more{text-align: right;margin-right: 15px;color: #4a90e2;}
.more-rows{margin-top: -20px;}
.j-hide{position: relative;z-index: 1}
.more-rows , .j-hide{display: none;}
.deal-rows-active .more-rows , .deal-rows-active .j-hide{display: block;}
.deal-rows-active .j-show{display:none;}
.shadow{background-color: rgba(0,0,0,.8);position: fixed;top: 0px;width: 100%;height: 100%;;z-index:9}
.shadow-wrapper{position: fixed;top: 0px;left: 0px;width: 100%;height: 100%;display: none;z-index:10}
.shadow-box{margin-top: 50%;z-index: 10;position: relative;text-align: center;}
.shadow-box > div{background: white;width: 80%;margin: 0 auto;padding-bottom: 30px;}
.shadow-title{background-color: black;padding: 5px 0;color: white;}
.shadow-word{margin-top: 20px;}
.pms_input{overflow: hidden; width: 180px; margin: 0 auto;margin-top: 10px;line-height: 35px;}
.pms_input input{border: 1px solid #e3e3e3; display: inline-block; width: 120px; margin-left: 5px;font-size: 12px;padding:5px 0 5px 5px}
.pms_input span{float: left;}
</style>
<body>
<div class="deal-wrapper">
	<div class="deal-header">
		<p class="deal-big-title"><?php echo $status_des[$order['status']]?></p>
		<p class="deal-big-word"><?php if(!empty($tips)) echo $tips; ?></p>
	</div>
	<div class="deal-rows">
		<p class="deal-words">
			<span class="deal-title">酒店名称 :</span>
			<span class="deal-word"><?php echo $order['hname'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">房型信息 :</span>
			<span class="deal-word"><?php echo $order['first_detail']['r_name'];?> - <?php echo $order['first_detail']['price_code_name'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">订单编号 :</span>
			<span class="deal-word"><?php if(empty($order['web_orderid'])){ echo $order['orderid']; }else{ echo $order['web_orderid'];} ?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">客户姓名 :</span>
			<span class="deal-word"><?php echo $order['name'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">联系方式 :</span>
			<span class="deal-word"><a style="color:blue;" href="tel:<?php echo $order['tel'];?>"><?php echo $order['tel'];?></a></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">入住时间 :</span>
			<span class="deal-word"><?php echo $order['startdate'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">离店时间 :</span>
			<span class="deal-word"><?php echo $order['enddate'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">房间数 :</span>
			<span class="deal-word"><?php echo $order['roomnums'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">订单金额 :</span>
			<span class="deal-word"><?php echo $order['real_price'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">用券金额 :</span>
			<span class="deal-word"><?php echo $order['coupon_favour'];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">支付方式 :</span>
			<span class="deal-word"><?php echo $pay_ways[$order['paytype']]->pay_name;?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">支付状态 :</span>
			<span class="deal-word"><?php echo $pay_des['HOTEL_ORDER_PAY_STATUS'][$order['paid']];?></span>
		</p>
		<p class="deal-words">
			<span class="deal-title">下单时间 :</span>
			<span class="deal-word"><?php echo $order['order_datetime'];?></span>
		</p>
		<?php if(!empty($order['mt_pms_orderid'])){?>
		<p class="deal-words">
			<span class="deal-title">pms单号 :</span>
			<span class="deal-word"><?php echo $order['mt_pms_orderid'];?></span>
		</p>
		<?php }?>
		<div class="deal-button-wrapper" style="<?php if($order['status']!=0) echo 'display:none;'; ?>" oid="<?php echo $order['orderid']?>">
			<button class="deal-button-delete j-del" status="5">取消</button>
			<button class="deal-button-sure j-sure" status="1">确认</button>
		</div>
	</div>
	<p class="deal-tips">还有<span id="nums" class="deal-wrong"><?php echo count($order_list); ?></span>个订单要处理>>>>></p>
	<?php foreach ($order_list as $k => $od) {?>
		<div class="deal-rows">
			<p class="deal-words">
				<span class="deal-title">酒店名称 :</span>
				<span class="deal-word"><?php echo $od['hname'];?></span>
			</p>
			<p class="deal-words">
				<span class="deal-title">房型信息 :</span>
				<span class="deal-word"><?php echo $od['first_detail']['r_name'];?> - <?php echo $od['first_detail']['price_code_name'];?></span>
			</p>
			<p class="deal-words">
				<span class="deal-title">订单编号 :</span>
				<span class="deal-word"><?php if(empty($od['web_orderid'])){ echo $od['orderid']; }else{ echo $od['web_orderid'];} ?></span>
			</p>
			<p class="deal-words">
				<span class="deal-title">客户姓名 :</span>
				<span class="deal-word"><?php echo $od['name'];?></span>
			</p>
			<p class="deal-words">
				<span class="deal-title">联系方式 :</span>
				<span class="deal-word"><a style="color: blue;" href="tel:<?php echo $od['tel'];?>"><?php echo $od['tel'];?></a></span>
			</p>
			
			<p class="deal-words show-more"><span class="j-show">展开详情</span><span class="j-hide">收起</span></p>
			<div class="more-rows">
				<p class="deal-words">
					<span class="deal-title">入住时间 :</span>
					<span class="deal-word"><?php echo $od['startdate'];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">离店时间 :</span>
					<span class="deal-word"><?php echo $od['enddate'];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">房间数 :</span>
					<span class="deal-word"><?php echo $od['roomnums'];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">订单金额 :</span>
					<span class="deal-word"><?php echo $od['real_price'];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">用券金额 :</span>
					<span class="deal-word"><?php echo $od['coupon_favour'];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">支付方式 :</span>
					<span class="deal-word"><?php echo $pay_ways[$od['paytype']]->pay_name;?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">支付状态 :</span>
					<span class="deal-word"><?php echo $pay_des['HOTEL_ORDER_PAY_STATUS'][$od['paid']];?></span>
				</p>
				<p class="deal-words">
					<span class="deal-title">下单时间 :</span>
					<span class="deal-word"><?php echo $od['order_datetime'];?></span>
				</p>	
				<div class="deal-button-wrapper"  oid="<?php echo $od['orderid']?>">
					<button class="deal-button-delete j-del" status="5">取消</button>
					<button class="deal-button-sure j-sure" status="1">确认</button>
				</div>		
			</div>
		</div>
	<?php }?>
	<div class="shadow-wrapper" id="suer_box">
		<div class="shadow"></div>
		<div class="shadow-box">
			<div>	
				<p class="shadow-title">处理订单</p>
				<p class="shadow-word">当前订单状态修改为: 确认</p>
				<div class="pms_input">
					<span>pms单号:</span>
					<input name="mt_orderid" placeholder="选填" type="text" oninput="value=value.replace(/[^\w\.\/]/ig,'')">
				</div>	
				<div class="deal-button-wrapper">
					<button class="deal-button-delete deal-button-no">否</button>
					<button class="deal-button-sure deal-button-yes">是</button>
				</div>
			</div>
		</div>
	</div>
	<div class="shadow-wrapper" id="alert_box">
		<div class="shadow"></div>
		<div class="shadow-box">
			<div>	
				<p class="shadow-title">消息</p>
				<p class="shadow-word"></p>
				<div class="deal-button-wrapper">
					<button class="deal-button-sure alert_close">确定</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
	var ret = false;
	var __this = '';
	var status_des = JSON.parse('<?php echo json_encode($status_des);?>');
	function deal_alert(text){
		$("#alert_box").find(".shadow-word").html(text);
		$("#alert_box").show()
	}
	$(".j-show").on("click",function(){
		$(this).parents(".deal-rows").addClass("deal-rows-active")
	})
	$(".j-hide").on("click",function(){
		$(this).parents(".deal-rows").removeClass("deal-rows-active")
	})
	$(".deal-button-no").on("click",function(){
		$("#suer_box").hide();
	})
	$(".alert_close").on("click",function(){
		$("#alert_box").hide();
	})
	$(".deal-button-yes").on("click",function(){
		if(ret){
			deal_alert('请稍等');
			return;
		}
		ret = true;
		var oid = __this.parents(".deal-button-wrapper").attr('oid');
		var status = __this.attr('status');
		var ext = '';
		var mt_orderid = '';
		if(status==1){
			mt_orderid = $('input[name=mt_orderid]').val();
			if(mt_orderid != ''){
				ext = '&mt_orderid='+mt_orderid;
			}
		}
		if(oid=="<?php echo $order['orderid'];?>"){
			ext += '&record=1';
        }
		$.ajax({
		    url:"<?php echo site_url('hotel_notify/hotel_notify/update_order_status');?>"+"?oid="+oid+"&status="+status+ext,
		    dataType: "json",
		    success:function(data){
		        if(data.status==0){
		            deal_alert(data.msg);
		            if(oid!="<?php echo $order['orderid'];?>"){
						__this.parents(".deal-rows").remove();
		            }else{
		            	if(mt_orderid != ''){
		            		__this.parents(".deal-button-wrapper").before('<p class="deal-words"><span class="deal-title">pms单号 :</span><span class="deal-word">'+mt_orderid+'</span></p>');
		            	}
		            	$('.deal-big-word').html(data.tips);
						__this.parents(".deal-button-wrapper").remove();
		            	$('.deal-big-title').html(status_des[status]);
		            }
					$('#nums').html($('#nums').html()-1);
		        }else{
		            deal_alert(data.msg);
		        }
	            ret = false;
		    },
		    error:function(){
		        deal_alert('提交失败');
		        ret = false;
		    }
		});
		$("#suer_box").hide();
	})
	$(".j-sure").on("click",function(){
		__this = $(this);
		$('.shadow-word').html('当前订单状态修改为: 确认');
		$('input[name=mt_orderid]').val('');
		$('.pms_input').show();
		$("#suer_box").show();
	})
	$(".j-del").on("click",function(){
		__this = $(this);
		$('.pms_input').hide();
		$('.shadow-word').html('当前订单状态修改为: 取消');
		$("#suer_box").show();
	})
</script>
</html>
