
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<title>收益记录</title>
</head>
<body>
<div class="headr">
	<div><a class="col" href="">收益记录</a></div>
	<div><a href="<?php echo site_url('distribute/distribute/drw_logs')?>?id=<?php echo $inter_id?>">提现记录</a></div>
</div>
<div class="content">
    <?php if(!empty($logs)){ ?>
    <?php foreach ($logs as $log):?>
        <div class="box">
            <div class="b_titl">分销收益</div>
            <?php if($log->grade_table == 'iwide_hotels_order'):?>
            <div class="h_nane">
                <font>¥<?php echo $log->iprice?></font>
                <p class="txtclip"><?php echo $log->roomname?>－<?php echo $log->name?></p>
            </div>
            <div class="n_t_con">
                <font>¥<?php echo $log->grade_total?></font>
                <p>购买粉丝：<?php echo $log->nickname?></p>
                <p>购买时间：<?php echo $log->grade_time?></p>
            </div>
            <?php elseif($log->grade_table == 'iwide_fans_sub_log'):?>
            <div class="h_nane">
                <p class="txtclip"><?php echo $log->roomname?></p>
            </div>
            <div class="n_t_con">
                <font>¥<?php echo $log->grade_total?></font>
                <p>关注粉丝：<?php echo $log->nickname?></p>
                <p>关注时间：<?php echo $log->grade_time?></p>
            </div>
            <?php endif;?>
            <div class="li_btns">
                <!-- <span>您可获得一张5折优惠券！</span>
                <a href="">领取卡券</a>
                <a href="">查看商品</a> -->
            </div>
        </div>
    <?php endforeach;
    } else{?>
        <div style="text-align: center">暂无记录</div>
    <?php } ?>
</div>
</body>
</html>