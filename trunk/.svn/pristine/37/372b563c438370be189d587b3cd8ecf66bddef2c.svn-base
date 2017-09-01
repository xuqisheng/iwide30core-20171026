<title>我的消息</title>
</head>
<style>
.ui_btn_list .item{background-size:auto 0.7rem}
</style>
<body>
<div class="fixed_header">
	<div class="webkitbox bg_d8">
    	<div class="iscur">系统消息</div>
    	<div>常见问题</div>
    </div>
</div>

<div class="ui_btn_list ui_border" style="margin-top:13%">
	<?php if(empty($msgs)):?>
	<p style="text-align: center">没有消息记录...</p>
	<?php else: foreach($msgs as $msg):?>
	<a href="<?php echo site_url('distribute/distribute/msg_det');?>?id=<?php echo $inter_id?>&mid=<?php echo $msg->nid?>" class="item">
    	<p class="h2">酒店发放收益</p>
    	<p class="h4 co_aaa"><?php echo $msg->create_time?></p>
    	<p><?php echo $msg->title?></p>
    	<p><?php echo $msg->sub_title?></p>
    </a>
    <?php endforeach;endif;?>
</div>

</body>
<script>
$('.fixed_header .webkitbox div').click(function(){
	$(this).addClass('iscur').siblings().removeClass('iscur');
});
</script>
</html>