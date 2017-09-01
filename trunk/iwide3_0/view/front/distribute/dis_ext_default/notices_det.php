<title><?php echo $msg->title?> - 我的消息</title>
</head>
<style>
body,html{background:#fff}
.webkitbox{padding-bottom:3%; text-align:left}
.webkitbox>*:first-child{max-width:5em;}
.new{float:none; padding:0.8% 0; text-align:center; margin-right:2%}
.tmp{padding-top:5%; margin-top:10%;; text-align:justify}
.ui_foot_fixed_btn{background:#f8f8f8;}
.ui_foot_fixed_btn > *{display:inline-block; border-radius:1rem; background:#ff7200; padding:2% 8%; color:#fff; border:1px solid #e09832;}
</style>
<body>
<div class="pad3">
	<div class="webkitbox">
    	<p class="new h5"><?php echo empty($msg_typs[$msg->msg_typ]) ? '常见问题' : $msg_typs[$msg->msg_typ]?></p>
        <div><?php if($msg->msg_typ == 0):?>
        	<p>酒店给您发放了昨天的收益</p><?php else:?>
        	<p><?php echo $msg->title?></p>
        	<?php endif;?>
            	<p class="h4 co_aaa"><?php echo $msg->create_time?></p>
            </div>
    </div>
    <?php echo $msg->content?>
</div>
<?php if($msg->msg_typ == 0):?>
<div class="ui_foot_fixed_btn">
	<a href="<?php echo site_url('distribute/dis_v1/incomes')?>?id=<?php echo $inter_id?>&pn=<?php echo $msg->remark?>">发放清单</a>
</div>
<?php endif;?>
</body>
</html>