<?php require_once 'header.php';?>
<?php echo referurl('css','mine_bottle.css',2,$media_path) ?>
<body>

<div class="ui_list">
    <?php 
	if(empty($getbottle)){
	    echo '<div style="margin:10% 0;text-align:center"><a href="/index.php/chat/bottle/" style="color:#333">暂无酒瓶，快去捞一个酒瓶吧！</a></div>';
	}
	foreach($getbottle as $v){
	?>
    <div class="ui_item"<?php if($v['from']['openid']){?> onClick="location.href='/index.php/chat/bottle/chat?iad=<?php echo $v['id'];?>'"<?php  }?>>
    	<div class="user_img"><img src="<?php echo $v['from']['logo'];?>" /></div>
        <div class="ui_time float_r"<?php if($v['openid']==$userinfo['openid']){if($v['status']==3){echo ' style="color:#f00"';}} else {if($v['status']==5){echo ' style="color:#f00"';}}?>><?php echo date("m-d H:i",$v['editetime']);?></div>
        <div class="user_name <?php if($v['from']['sex']==1){echo 'ui_male';} else {echo 'ui_female';} ?>"><?php echo $v['from']['nickname']; ?></div>
        <div class="ui_content"><?php echo qqface($v['msg']);?></div>
    </div>
	<?php } ?>
</div>