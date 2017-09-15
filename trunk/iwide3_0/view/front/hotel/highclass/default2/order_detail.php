<?php include 'header.php'?>
<header>
	<?php if($re_pay==1&&$timeout!==0){ ?>
	<style>#paytime:before{ content:"支付剩余时间："}</style>
    <input type="hidden" id="rest" value="<?php echo $timeout?>">
	<div id="paytime" class="pad10 bg_F8F8F8 center color_key h24">00:00</div> 
    <script>
	$(function(){
		$.extend({getnum:function(num){if(num<10)return '0'+num;else return num;}});
		var rest= parseInt($('#rest').val());
		var timer = function(){
			$("#rest").val(rest);
			if( rest<=0){
				var Link = $('<a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$order['hotel_id']));?>" class="pad10 bg_main">重新预订</a>');
				$('#paytime').remove();
				$('.bottomfixed').html(Link);
				return;
			}
			var m = $.getnum(parseInt(rest/60));
			var s = $.getnum(parseInt(rest%60));
			$('#paytime').html( m+":"+s);
			rest--;
			window.setTimeout(timer,1000);
		}
		timer();
        $('.checkcanpay').click(function(){
            pageloading();
            var url = $(this).attr('redirect');
            $.get('<?php echo Hotel_base::inst()->get_url("CHECK_ORDER_CANPAY");?>',{
                oid:'<?php echo $order['orderid']?>'
            },function(data){
                removeload();
                if(data.s==0){
                    $.MsgBox.Alert(data.errmsg,function(){
                        location.reload();
                    });
                }
                else{
                    window.location.href = url;
                }
                
            },'json')
        });
	});
	</script>
	<?php } ?>
	<?php if($order['handled']==0){?>
    <div class="pad3 bg_fff">
        <div class="h30">订单提交成功</div>
        <div class="color_main h22">酒店已收到您的订单，请您在预订时间内抵达酒店出示您的证件办理入住手续。</div>
    </div>
    <?php }?>
    <?php if(!empty($order_sequence)){?>
    <ul class="pad10 bg_fff webkitbox h24 center bd_top bookstatus">
    	<li class="statuslist">
            <div class="bg_main"><hr class="color_main" /></div>
            <div class="pad2">提交订单</div>
            <div class="h18 color_888"><?php echo date('m.d H:i',$order['order_time']);?></div>
        </li>
        <?php if(!empty($order_sequence['before'])){foreach($order_sequence['before'] as $sb){?>
    	<li class="statuslist">
            <div class="bg_main"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $sb;?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
        <?php }}?>
        <li class="statuslist">
            <div class="bg_main active"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $order_sequence['cur'];?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
           <?php if(!empty($order_sequence['after'])){foreach($order_sequence['after'] as $sa){?>
  		<li class="statuslist">
            <div class="bg_main not"><hr class="color_main" /></div>
            <div class="pad2"><?php echo $sa;?></div>
            <div class="h18 color_888">&nbsp;</div>
        </li>
        <?php }}?>
    </ul>
    <?php }?>
</header>
<section class="bg_fff martop center">
	<div class="pad10 bd_bottom h24 color_888">
        <div class="h32 color_555"><?php echo $order['hname']?></div>
        <div class="martop"><?php echo $order['first_detail']['roomname'];?>-<?php echo $order['first_detail']['price_code_name'];?> <?php if(!empty($order['first_detail']['breakfast_nums'])){ ?>【<?php echo $order['first_detail']['breakfast_nums']; ?>】<?php } ?></div>
        <div><?php if(!empty($first_room['imgs']['hotel_room_service'])) foreach($first_room['imgs']['hotel_room_service'] as $hs){ ?><?php echo $hs['info']; ?>&nbsp;<?php }?></div>
    </div>
    <div class="pad3"  style="width:90%;margin:auto">
    	<div class="webkitbox pad3">
            <div><div class="h20 color_888">入住</div><div><?php echo date('m月d日',strtotime($order['startdate']));?></div></div>
            <div class="bd_bottom h24" style="width:5rem;border-color:#555">共<?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚/<?php echo $order['roomnums'];?>间</div>
            <div><div class="h20 color_888">离店</div><div><?php echo date('m月d日',strtotime($order['enddate']));?></div></div>
        </div>
		<?php if($order['price_type'] != 'athour'){?>
        <div class="h24 pad8">房间保留至<?php echo $order['holdtime'];?></div>
        <?php }?>
        <div class="h24 color_888">地址：<?php echo $order['haddress'];?></div>
    </div>
    <div class="webkitbox bd_top pad3 h24">
        <div onclick="tonavigate(<?php echo $order['latitude'];?>,<?php echo $order['longitude'];?>,'<?php echo $order['hname'];?>','<?php echo $order['haddress'];?>')" class="pad2 color_main">路线导航</div>
        <?php if (!empty($hotel['arounds'])){?>
       		<a href="<?php echo Hotel_base::inst()->get_url("AROUNDS",array('h'=>$order['hotel_id']));?>" class="pad2 bd_left color_main">酒店周边</a>
        <?php }else{?>
        	<a href="http://cps.dianping.com/mm/weixin/home?showwxpaytitle=1&utm_source=card" class="pad2 bd_left color_main">酒店周边</a>
        <?php }?>

        <a href="tel:<?php echo $order['htel'];?>" class="pad2 bd_left color_main">联系酒店</a>

        <?php if($order['status']==2 || ($inter_id=='a491796658' && $order['status']==1)){ if($order['is_invoice']==1){ ?>
            <a href="<?php echo Hotel_base::inst()->get_url("CHECK_OUT",array('oid'=>$order['id']));?>" class="pad2 bd_left color_main"><?php if($inter_id=='a492669988'){ echo '开具发票';}else{echo '预约退房';}?></a>
        <?php }elseif($order['is_invoice']==2){ ?>
            <div class="pad2 bd_left color_main">预约退房成功</div>
        <?php }}?>
    </div>
</section>

<section class="pad3 bg_fff h20 color_888 martop">
	<div class="h30 color_555">订单信息</div>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单编号</div>
        <div><?php echo $order['show_orderid'];?></div>
    </div>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单总价</div>
        <div  class="y color_main"><?php echo ($order['price'] + $order['point_favour'] + $order['coupon_favour'] + $order['wxpay_favour']);?></div>
    </div>
    <?php if($order['point_used_amount']!=0){ ?>
    <div class="webkitbox martop input_item">
        <div class="input_item">使用积分数</div>
        <div  class="color_main"><?php echo round($order['point_used_amount']);?></div>
    </div>
    <?php }?>
    <?php if($order['point_favour']!=0){ ?>
    <div class="webkitbox martop input_item">
        <div class="input_item">积分抵用</div>
        <div  class="y color_main"><?php echo $order['point_favour'];?></div>
    </div>
    <?php }?>
    <?php if($order['coupon_favour']!=0){ ?>
    <div class="webkitbox martop input_item">
        <div class="input_item">使用优惠券</div>
        <div  class="y color_main"><?php echo $order['coupon_favour'];?></div>
    </div>
    <?php }?>
    <?php if($order['wxpay_favour']!=0){ ?>
    <div class="webkitbox martop input_item">
        <div class="input_item">支付立减</div>
        <div  class="y color_main"><?php echo $order['wxpay_favour'];?></div>
    </div>
    <?php }?>
    <div class="webkitbox martop input_item">
        <div class="input_item">实付金额</div>
        <div  class="y color_main"><?php echo $order['paytype']=='point'?0:$order['price'];?></div>
    </div>
    <div class="webkitbox martop">
        <div class="input_item" style="min-width: 5rem; display: inline-block;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</div>
        <div><?php if($order['customer_remark'] == ""){ echo "无";}else{echo $order['customer_remark'];}?></div>
    </div>
<?php if(isset($hotel['invoice']) && $hotel['invoice']==2 ) { ?>
    <div class="webkitbox martop input_item">
        <div class="input_item" style="-webkit-box-align: baseline;">发票信息</div>
        <div>
    	<?php if(isset($invoice_info)){ ?>
            <div><?php if($invoice_info->type==1){ echo '普通发票';}else{echo '增值税发票';}?></div>
            <div class="txtclip"><?php echo $invoice_info->title;?></div>
    	<?php }else{ ?>
            <div class="color_link">未开票</div>
    	<?php }?>
        </div>
    </div>
<?php }?>
	<div class="webkitbox martop input_item">
        <div class="input_item">支付类型</div>
        <div><?php echo $pay_ways[$order['paytype']]->pay_name;?></div>
    </div>
<?php if($order['paytype']=='weixin'){?>
    <div class="webkitbox martop input_item">
        <div class="input_item">支付状态</div>
        <div><?php if($order['paid']==1){?>已支付<?php }else{?>未支付<?php }?></div>
    </div>
<?php }?>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单状态</div>
        <div><?php echo $order['status_des'];?></div>
    </div>
	<?php if(!empty($extra_info['breakfast_nums_arr'])){ ?>
		<div class="webkitbox martop input_item">
			<div class="input_item">早餐数</div>
			<div>
				<?php foreach($extra_info['breakfast_nums_arr'] as $k=> $v){ ?>
					<p><?php echo date('Y-m-d',strtotime($k)); ?>：<?php echo $v; ?></p>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
    <?php if(!empty($not_same) || !empty($states['show_item'])){ foreach($order['order_details'] as $k=>$od) {?>
    <div class="webkitbox martop input_item">
        <div class="input_item">订单<?php echo ($k+1);?><</div>
        <div>
            <?php echo $status_des['HOTEL_ORDER_STATUS'][$od['istatus']];?>
            
            <a href="javascript:;" onclick='check_self_continue("<?php echo $od["sub_id"]?>")' class="color_link"><?php if (!empty($states['item_state'][$od['sub_id']]['can_continue'])){$self_continue=1;?>
            	(房间号:<?php if (!empty($od['room_no'])){?> <?php echo empty($od['room_no'])?'':$od['room_no'];?><?php };echo ','.date('m-d',strtotime($od['enddate'])).'离店';?>) 续住</a>
            <?php }?>
        </div>
    </div>
    <?php }}?>
</section>
<div style="padding-top:4rem">
    <div class="bottomfixed webkitbox bg_fff bdtop center">
    <?php if($can_cancel==1){?>
    	<div class="pad10" onclick="cancel_order()">取消订单</div><!-- 在线支付不可取消 无取消订单按钮 -->
    	<?php }else if($can_comment==1){?>
		<a class="pad10" href='<?php echo Hotel_base::inst()->get_url("TO_COMMENT",array('oid'=>$order['id']));?>'>评价</a>
		<?php }?>
		<?php if($re_pay==1){?>
            <?php if($order['paytype']=='weifutong'){?>
              <a href="javascript:;" redirect="<?php echo site_url('wftpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main checkcanpay">立即支付</a>
            <?php }elseif($order['paytype']=='lakala' || $order['paytype']=='lakala_y'){?>
              <a href="javascript:;" redirect="<?php echo site_url('lakalapay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main checkcanpay">立即支付</a>
            <?php }elseif($order['paytype']=='unionpay'){?>
              <a href="javascript:;" redirect="<?php echo site_url('unionpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main checkcanpay">立即支付</a>
            <?php }else{?>
              <a href="javascript:;" redirect="<?php echo site_url('wxpay/hotel_order').'?id='.$inter_id.'&orderid='.$order['orderid'];?>" class="pad10  bg_main checkcanpay">立即支付</a>
            <?php }?>
		<?php }else{?>
        <a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$order['hotel_id'],'type'=>$order['price_type']));?>" class="pad10 bg_main">再次预订</a>
        <?php }?>
    </div>
</div>
</body>
<script>
function cancel_order(){
	var _msg;
	<?php if ($inter_id=='a472731996'){?>
	_msg='确认取消该订单吗？如预付房费退订需提前一天，退款在15个工作日内完成，收取20%手续费，详询客服';
	<?php }else{?>
	_msg='您确定要取消吗？';
	<?php }?>
	$.MsgBox.Alert(_msg,function(){
		pageloading();
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
			
		},'json');
	});
	
}

function checkout_tips(){
    <?php if(isset($retreat_time->end) && isset($retreat_time->start)){ ?>
        var checkout_start = '<?php echo (intval(substr($retreat_time->start,0,2)));?>';
        var checkout_end = '<?php echo (intval(substr($retreat_time->end,0,2)));?>';
//        alert('预约退房时间为'+checkout_start+':00～'+checkout_end+':00哦');
    location.href = "<?php echo Hotel_base::inst()->get_url("PROCESSING",array('h'=>$hotel['hotel_id']));?>"+'&s='+checkout_start+':00&e='+checkout_end+":00";
    <?php }?>
    return;
}

<?php if(!empty($self_continue)){?>
function check_self_continue(item_id){
	pageloading();
	$.post('<?php echo Hotel_base::inst()->get_url('CHECK_SELF_CONTINUE')?>',
	{
		orderid:'<?php echo $order['orderid']?>',
		item_id:item_id
	},function(data){
		removeload();
		if(data.s==1){
			location.href=data.pay_link;
		}else{
			$.MsgBox.Alert(data.errmsg,function(){
				location.reload();
			});
		}
	},'json');
}
<?php }?>
</script>
</html>
