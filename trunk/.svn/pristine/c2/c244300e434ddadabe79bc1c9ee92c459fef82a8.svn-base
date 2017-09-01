
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/deposit.css')?>" rel="stylesheet">
<title>提现记录</title>
</head>
<body>
<div class="headr">
	<div><a href="<?php echo site_url('distribute/distribute/incomes')?>?id=<?php echo $inter_id?>">收益记录</a></div>
	<div><a class="col" href="">提现记录</a></div>
</div>

<div class="content" style="display:none">
	<div class="c_lis" style="padding:3%">
    	<font class="price">¥</font>
    	<p class="promp">异常</p>
    	<p class="balance">余额 ¥</p>
    	<p class="tmie"></p>
    </div>
</div>

<div class="content" style="display:block"><?php
    if(!empty($logs)){
    foreach ($logs as $log):?>
	<div class="c_lis">
    	<font class="price">¥<?php echo $log->amount?></font>
    	<p class="promp">提现－<?php if($log->status == 1):?>审核中<?php elseif($log->status == 2):?>已成功<?php elseif ($log->status == 3):?>失败<?php else:?>异常<?php endif;?></p>
    	<p class="balance">余额 ¥<?php echo $log->balance?></p>
    	<p class="tmie"><?php echo $log->order_time?></p>
    </div><?php endforeach;
    }else{?>
        <div style="text-align: center">暂无记录</div>
    <?php } ?>
</div>
<div class="floot" style="padding-top:14%;display:none;">
	<div class="fix">
        <a href="<?php echo site_url('wap/distribute/to_pocket')?>?id=<?php echo $inter_id?>"><font class="f_pric">马上提现</font></a>
    	<p><font>¥<?php echo $my_info['total_fee']?></font><span>(总收益¥<?php echo empty($total_fee)?0:$total_fee;?>)</span></p>
    </div>
</div>
</body>
</html>