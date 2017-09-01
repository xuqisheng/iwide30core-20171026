
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/withsraw.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/distribute/default/scripts/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/distribute/default/styles/my_fans.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/ui.css')?>" rel="stylesheet">
<title>粉丝购买记录</title>
</head>
<body>
<div class="f_box">
    <div class="fans clearfix">
        <div class="nan_img img_auto_cut"><img src="<?php echo $fans_details['fans_info']['headimgurl']?>"/></div>
        <div class="nan_txt">
            <p class="use"><?php echo $fans_details['fans_info']['nickname']?></p>
            <p class="con">购买商品数：<font><?php echo $fans_details['total']?></font></p>
            <p class="con">产生的收益：<font>¥<?php echo $fans_details['total_fee']?></font></p>
        </div>
    </div>
</div>
<div class="content"><?php foreach ($logs_details as $log):?>
        <div class="box">
        <div class="b_titl">分销收益</div>
        <div class="h_nane">
            <font>¥<?php echo $log->order_amount?></font>
            <p class="txtclip"><?php echo $log->product?>－<?php echo $log->hotel_name?></p>
        </div>
        <div class="n_t_con">
            <font>¥<?php echo $log->grade_total?></font>
            <p>购买粉丝：<?php echo $log->nickname?></p>
            <p>购买时间：<?php echo $log->grade_time?></p>
        </div>
        <div class="li_btns">
            <!-- 	<span>您可获得一张5折优惠券！</span>
                <a href="">领取卡券</a>
                <a href="">查看商品</a> -->
        </div>
        </div><?php endforeach;?>
</div>
</body>
</html>