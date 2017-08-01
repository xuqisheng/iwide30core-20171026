<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" ></script>
<title>全员营销</title>
<style>
.pullhaibao{display:none; background:#000;}
.pullhaibao >div{position:relative;overflow:auto;height:100%; font-size:0}
.pullhaibao .haibao_user,.pullhaibao .haibao_erweima,.pullhaibao .bg_erweima,.saveimg{position:absolute}
.pullhaibao .haibao_user{ left:0;top:46.5%; width:94%; padding:0 3%;}
.pullhaibao .haibao_user > p{width:2rem; height:2rem; float:left; margin-right:3%; background:#fff; font-size:0}
.pullhaibao .haibao_user > p img,.pullhaibao >div> img{ min-height:100%}
.pullhaibao .haibao_user div{ background:#026eb3; height:1.5rem; padding-top:0.5rem}
.pullhaibao .haibao_erweima{top:92%;left:0; text-align:center; width:100%;}
.pullhaibao .haibao_erweima img{width:46.875%;}
.pullhaibao .bg_erweima{width:100%; height:100%; opacity:0;left:0; bottom:0;}
.saveimg{padding:2% 4%; color:#fff; background:rgba(0,0,0,0.5); z-index:9999; right:3%; bottom:3%; opacity:0.9}
</style>
</head>
<body>
<div class="head">
	<a href="<?php echo site_url('distribute/dis_ext/incomes')?>?id=<?php echo $inter_id?>" class="income">
    	<div><span>总收益</span><span><?php echo $total_amount?></span></div>
    	<div><span>今日收益</span><span><?php echo $today_amount?></span></div>
    	<div><span>昨日收益</span><span><?php echo $yestoday_amount?></span></div>
    </a>
	<div class="padding overflow">
        <div class="user_img"><img src="<?php echo $saler_info['headimgurl'];?>" /></div>	
        <div class="user_name"><?php echo $saler_info['nickname']?><span class="h3">&nbsp;No.<?php echo $saler_info['fans_key']?></span></div>
        <div class="viplv_black"></div>
    </div>
</div>
<div class="ui_btn_list ui_border">
	<a href="<?php echo site_url('distribute/dis_ext/incomes')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico4"></em>
    	<tt>我的收益(<?php echo $total_amount?>)</tt>
    </a>
</div>
<div class="ui_btn_list ui_border">
	<a href="<?php echo site_url('distribute/dis_ext/msgs')?>?id=<?php echo $inter_id?>" class="item">
    	<em class="ui_ico ui_ico7"></em>
    	<tt>我的消息</tt>
    	<?php $new_msg_count=0; if($new_msg_count > 0):?>
    	<span class="ui_red">有<?php echo $new_msg_count;?>条新消息</span>
    	<?php endif;?>
    </a>
</div>
</body>
</html>