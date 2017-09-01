<?php require_once 'header.php';?>
<?php echo referurl('css','activity.css',2,$media_path) ?>
<body>
    <div class="person_list">
    	<div class="ui_list">
		    <?php if(empty($signup)){echo '<div class="ui_item" onClick="history.go(-1)" style="text-align:center;height:150px; line-height:150px">暂无人报名，快去报个名吧！</div>';} ?>
		    <?php foreach($signup as $v){ ?>
        	<div class="ui_item">
            	<div class="user_img ui_img_auto_cut"><img src="<?php echo $v['logo'];?>"></div>
                <a href="/index.php/chat/talk/makefri?uid=<?php echo $v['id'];?>" class="ui_btn float_r">发送消息</a>
                <div class="user_name"><?php echo $v['anickname'];?></div>
                <div class="ui_time"><?php echo date('m-d H:i',$v['signuptime']);?></div>
            </div>
			<?php } ?>
        </div>
    </div>

<script>
img_auto_cut();

</script>