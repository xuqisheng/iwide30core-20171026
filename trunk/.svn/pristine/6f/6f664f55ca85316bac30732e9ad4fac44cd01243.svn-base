
<title>我的收益</title>
</head>
<body>
<div class="head_income_list">
<div class="wrap">
    <div class="head_income_item is_cur">
        <div class="income_item_title">核定收益</div>
        <div class="income_item_detail">
            <div class="webkitbox _1">
                <p>核定收益总额</p><p class="ui_price"><?php echo $grades?></p>
                <div class="co_aaa"><p>已发放收益</p><p class="ui_price"><?php echo $send?></p></div>
            </div>
            <div class="webkitbox _2">
                <p>核定收益笔数</p><p><?php echo $grades_times?></p>
                <div class="h4 co_aaa"><p>已发放笔数</p><p><?php echo $send_times?></p></div>
            </div>
        </div>
    </div>
    
</div><!-- wrap -->
</div>

<div class="_block h4 co_aaa">收益记录</div>
<?php if(empty($logs)):?>
<div class="_block bg_fff content_income">	
<?php if($g_typ == 'ALL'):?>您还没有收益记录！<?php endif;?>
<?php if($g_typ == 'OLD'):?>您暂无已发放收益！<?php endif;?>
<?php if($g_typ == 'NEW'):?>您暂无未发放收益！<?php endif;?>
<?php if($g_typ == 'PRE'):?>您暂无未核定收益！<?php endif;?>
</div>
<?php else :?>

<?php foreach ($logs as $log):?>

<div class="income_detail">
<div class="_block bg_fff content_income">
	<div class="h3 fill1"><?php echo $grades_types[$log->grade_table]?>:<?php echo empty($hotels[$log->order_hotel]) ? '' : $hotels[$log->order_hotel]?>-<?php echo $log->product?><?php if($log->grade_table == 'iwide_fans_sub_log'):echo '&nbsp;'.mb_substr($log->nickname, 0, 1). str_repeat('*', 2);endif;?></div>
    <div class="webkitbox">
    	<p>
        	<span class="co_aaa fill2"><?php echo empty($log->order_time) ? '--' : date('Y.m.d H:i:s',strtotime($log->order_time))?></span>
        	<span class="fill3" <?php if($log->grade_amount > 0):?>
			<?php if($log->grade_table == 'iwide_hotels_order' && $log->status == 4):
        	echo 'style="color:#417505">未核定-未离店';
        	elseif ($log->status == 6):
        	echo 'style="color:#417505">未核定';
        	elseif ($log->status == 1 && !$deliver_config):
        	echo 'style="color:#4A90E2">已核定(未发放)';
        	elseif ($log->status == 1 && $deliver_config):
        	echo 'style="color:#4A90E2">已核定(线下发放)';
        	elseif ($log->status == 2):
        	echo 'style="color:#f99e12">已发放';
        	elseif ($log->status == 5):
        	echo 'style="color:#f99e12">已核定(无绩效)';
        	endif;endif;
        	?>
            </span>
        </p>
        <p style="text-align:right;">
        	<?php if($log->grade_amount > 0):?><span class="h0 fill4" <?php if($log->grade_table == 'iwide_hotels_order' && $log->status == 3): 
			echo 'style="color:#aaa"';
			elseif ($log->status == 3): echo 'style="color:#aaa"';
			elseif ($log->status == 2): echo 'style="color:#f99e12"';
			endif;?>>￥<?php echo $log->grade_total?></span><?php endif;?>
            <span class="ui_ico ui_ico10"></span>
        </p>
    </div>
</div>

    <div class="_block h4 co_aaa" style="display:none">
        <div class="fill5"><?php echo $grades_types[$log->grade_table]?>:<?php echo empty($hotels[$log->order_hotel]) ? '' : $hotels[$log->order_hotel]?>-<?php echo $log->product?></div>
        <?php if($log->grade_table == 'iwide_fans_sub_log'):?>
        <div><p>关注粉丝：<?php echo '&nbsp;'.mb_substr($log->nickname, 0, 1). str_repeat('*', 2);?></p></div>
            <div class="webkitbox mar3">
            <div>
                <p>粉丝编号：<?php echo $log->order_id?></p>
                <p>交易类型：<?php echo $grades_types[$log->grade_table]?></p>
            </div>
            <div class="marL3">
                <p>核定时间：<?php echo empty($log->grade_time)?'--':date('Y.m.d H:i:s',strtotime($log->grade_time))?></p>
            </div>
            </div>
        <?php else:?> 
        <div class="webkitbox mar3">
            <div>
                <p>订单编号：<span class="fill6"><?php echo $log->order_id?></span></p>
                <p>交易粉丝：<span class="fill7"><?php echo mb_substr($log->nickname, 0, 1). str_repeat('*', 2);?></span></p>
                <p>交易状态：<?php echo empty($o_sts[$log->grade_table][$log->order_status]) ? '' : $o_sts[$log->grade_table][$log->order_status]?></p>
            </div>
            <div class="marL3">
                <p>核定时间：<span class="fill8"><?php echo empty($log->grade_time)?'--':date('Y.m.d H:i:s',strtotime($log->grade_time))?></span></p>
                <p>交易类型：<?php echo $grades_types[$log->grade_table]?></p>
                <p>交易金额：￥<span class="fill9"><?php echo $log->grade_amount?></span></p>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>
<?php endforeach; endif;?>

<div class="ajaxloading" style="display:none"></div>
</body>
<script>
$(function(){
	var _p_w =$(window).width();
	var _this =$('.head_income_item');
	_this.width(_p_w*0.72);
	var _c_w =_this.outerWidth();
	var _num =_this.length;
	var _left = _p_w -_c_w;
	_this.eq(_num-1).css('margin-right',_left/2+'px');
	if (_num ==1)
	_this.eq(_num-1).css('margin-left',_left/2+'px');
	
	var _s_l = $('.wrap').get(0).scrollWidth;
	$('.wrap').scrollLeft(_s_l);
	$('.wrap').on('touchend',function(e){
		var tmp_left =$(this).scrollLeft();
		if (tmp_left<=0) return;		
		var cur_index = Math.round(tmp_left/_c_w);
		_this.eq(cur_index).addClass('is_cur').siblings().removeClass('is_cur');
	});
	$('.income_detail .content_income').click(function(){
		$(this).siblings().stop().slideToggle();
	});
	
	var isload=false;
	function get_data(){
		//$.post( 'url',{},function(data){
		isload  = false;
		$('.ajaxloading').stop().hide();
		if($('.income_detail').length>0) return;
		var TmpDom = $('.income_detail').eq(0).clone(true);  //以第一个元素为模版进行复制并填充数据
		for (var i=0;i<20;i++){
			TmpDom.find('.fill1').html('长沙碧桂园凤凰酒店-高级大床房 2间2晚');
			TmpDom.find('.fill2').html('2016.04.22 12:00:25');
			
			TmpDom.find('.fill3').html('未核定-尚未离店');
			//核定状态的每个对应颜色
			TmpDom.find('.fill3').css('color','#417505');//未核定;
			TmpDom.find('.fill3').css('color','#4A90E2');//已核定;
			TmpDom.find('.fill3').css('color','#F5A623');//已发放;
			
			TmpDom.find('.fill4').html('5.00');  
			//收益金额不同状态下对应的颜色
			TmpDom.find('.fill4').css('color','#aaa');//未核定;
			TmpDom.find('.fill4').css('color','#F5A623');//已发放;
			TmpDom.find('.fill4').css('color','#000');//其他;
			
			TmpDom.find('.fill5').html('长沙碧桂园凤凰酒店-高级大床房 2间2晚');
			TmpDom.find('.fill6').html('qwer123456订单号');
			TmpDom.find('.fill7').html('粉丝名');
			TmpDom.find('.fill8').html('20160101(核定时间)');
			TmpDom.find('.fill9').html('5.00');
			$('.ajaxloading').before(TmpDom);
		}
		// },'json');
	}
	$(document).on('touchmove',function(){
		if(($(document).height()-$(window).height())*0.5<=$(document).scrollTop()){
			if (!isload){
				isload  = true;
				$('.ajaxloading').stop().show();
				get_data();
			}
		}
	})
})
</script>
</html>
