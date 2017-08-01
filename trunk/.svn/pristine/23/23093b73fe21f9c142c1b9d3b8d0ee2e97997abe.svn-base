<link href="<?php echo base_url('public/distribute/default/styles/ui.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/distribute/default/scripts/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/my_fans.css')?>" rel="stylesheet">
<title>我的粉丝</title>
</head>
<body>
<div class="f_box">
    <?php if(!empty($saler_details)){
        foreach ($saler_details as $fans):?>
            <a href="<?php echo site_url('distribute/distribute/fans_blogs')?>?id=<?php echo $inter_id?>&fid=<?php echo $fans->fid?>" style="color:#555"><div class="fans clearfix">
                    <div class="nan_img img_auto_cut"><img src="<?php echo $fans->headimgurl?>"/></div>
                    <div class="nan_txt">
                        <p class="use"><?php echo $fans->nickname?></p>
                        <p class="con">购买商品数：<font><?php echo empty($fans->total)?0:$fans->total?></font></p>
                        <p class="con">产生的收益：<font>¥<?php echo empty($fans->total_fee)?0:$fans->total_fee?></font></p>
                    </div>
                </div></a>
        <?php endforeach;
    }else{?>
        <div style="text-align: center;padding:2%;">暂无记录</div>
    <?php
    }
    ?>
</div>
</body>
</html>