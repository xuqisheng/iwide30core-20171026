<title><?php echo $msg->title?> - 我的消息</title>
</head>
<style>
body,html{background:#fff}
.webkitbox{padding-bottom:3%; text-align:left}
.webkitbox>*:first-child{max-width:5em;}
.new{float:none; padding:0.8% 0; text-align:center; margin-right:2%}
.tmp{padding-top:5%; margin-top:10%;; text-align:justify}
.ui_foot_fixed_btn{background:#f8f8f8;}
.ui_foot_fixed_btn > *{display:inline-block; border-radius:1rem; background:#fdb954; padding:2% 8%; color:#fff; border:1px solid #e09832;}
</style>
<body>
<div class="pad3">
	<div class="webkitbox">
    	<p class="new h5"><?php echo $msg_typs[$msg->msg_typ]?></p>
        <div>
        	<p>集团酒店给您发放了昨天的收益</p>
            	<p class="h4 co_aaa"><?php echo $msg->create_time?></p>
            </div>
    </div>
    <?php echo $msg->content?>
</div>
<?php if($msg->msg_typ == 0):?>
<div class="ui_foot_fixed_btn">
	<a href="">发放清单</a>
</div>
<?php endif;?>
</body>
</html>