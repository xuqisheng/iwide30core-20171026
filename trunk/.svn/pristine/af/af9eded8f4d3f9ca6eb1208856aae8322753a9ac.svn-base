<title>粉丝购买记录</title>
</head>
<body>
<div class="user_list bg_fff">
    <a href="javascript:;" class="item">
        <div class="user_img"><img src="<?php echo $fans_details['headimgurl']?>" /></div>
        <div class="h1 txtclip" style="padding-right:3%"><?php echo $fans_details['nickname']?><span class="h3"><?php echo $hotels[$fans_details['hotel_id']]?></span></div>
        <div class="">产生收益：￥<?php echo $fans_details['amount']?></div>
        <div class="">发展时间：<?php echo $fans_details['event_time']?></div>
    </a>
</div>
</div>
<div class="_block h4 co_aaa">交易详情</div>
<?php if(empty($logs_details)):?>
<?php else: foreach ($logs_details as $log):?>

<div class="income_detail">
    <div class="_block bg_fff content_income">
        <div class="h3"><?php echo $grades_types[$log->grade_table]?>:<?php echo empty($hotels[$log->order_hotel]) ? '' : $hotels[$log->order_hotel]?>-<?php echo $log->product?></div>
        <div class="webkitbox">
            <p>
                <span class="co_aaa"><?php echo empty($log->order_time) ? '--' : date('Y.m.d H:i:s',strtotime($log->order_time))?></span>
                <?php if($log->grade_amount > 0):?><?php if($log->grade_amount > 0):?>
			<?php if($log->grade_table == 'iwide_hotels_order' && $log->status == 4):
        	echo '<span style="color:#417505">未核定-未离店</span>';
        	elseif ($log->status == 6):
        	echo '<span style="color:#417505">未核定</span>';
        	elseif ($log->status == 1 && !$deliver_config):
        	echo '<span style="color:#4A90E2">已核定(未发放)</span>';
        	elseif ($log->status == 1 && $deliver_config):
        	echo '<span style="color:#4A90E2">已核定(线下发放)</span>';
        	elseif ($log->status == 2):
        	echo '<span style="color:#f99e12">已发放</span>';
        	elseif ($log->status == 5):
        	echo '<span style="color:#f99e12">已核定(无绩效)</span>';
        	endif;endif;
        	?><?php endif;?>
            </p>
            <p style="text-align:right;">
                 <?php if($log->grade_amount > 0):?><span class="h0" <?php if($log->grade_table == 'iwide_hotels_order' && $log->status == 3): 
                echo 'style="color:#aaa"';
                elseif ($log->status == 3): echo 'style="color:#aaa"';
                elseif ($log->status == 2): echo 'style="color:#F5A623"';
                endif;?>>￥<?php echo $log->grade_total?></span><?php endif;?>
                <span class="ui_ico ui_ico10"></span>
            </p>
        </div>
    </div>
    <div class="_block h4 co_aaa" style="display:none">
        <div><?php echo $grades_types[$log->grade_table]?>:<?php echo empty($hotels[$log->order_hotel]) ? '' : $hotels[$log->order_hotel]?>-<?php echo $log->product?></div>
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
                <p>订单编号：<?php echo $log->order_id?></p>
                <p>交易粉丝：<?php echo '&nbsp;'.mb_substr($log->nickname, 0, 1). str_repeat('*', 2);?></p>
                <p>交易状态：<?php echo empty($o_sts[$log->grade_table][$log->order_status]) ? '' : $o_sts[$log->grade_table][$log->order_status]?></p>
            </div>
            <div class="marL3">
                <p>核定时间：<?php echo empty($log->grade_time)?'--':date('Y.m.d H:i:s',strtotime($log->grade_time))?></p>
                <p>交易类型：<?php echo $grades_types[$log->grade_table]?></p>
                <?php if($log->grade_amount > 0):?><p>交易金额：￥<?php echo $log->grade_amount?></p><?php endif;?>
            </div>
        </div>
            <?php endif;?>
    </div>
</div>
<?php endforeach; endif;?>
</body>
<script>

    $('.income_detail .content_income').click(function(){
        $(this).siblings().stop().slideToggle();
    })
</script>
</html>