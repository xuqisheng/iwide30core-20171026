<?php require_once 'header.php';?>
<?php echo referurl('css','activity.css',2,$media_path) ?>
<body>

<div class="activity_list">
    <?php if(!$active){ ?>
	<div class="ui_list">
        <div style="margin:10% 0;text-align:center"><a href="/index.php/chat/talk/" style="color:#333">暂无活动，点击返回大厅吧！</a></div>
	</div>
	<?php } ?>

    <?php foreach($active as $v){ ?>
	<div class="activity_item" onClick="location.href='/index.php/chat/talk/active?iad=<?php echo $v['id'];?>'">
    	<div class="bg_img ui_img_auto_cut"><img src="<?php echo referurl('img','bg03.jpg',2,$media_path) ?>"></div>
        <div class="absolute">
        	<div class="activity_box">
                <div class="act_title"><?php echo $v['title'];?></div>
                <div class="act_local"><?php echo $v['address'];?></div>
                <div class="overflow">
                    <div class="act_time"><?php echo $v['starttime'];?> <?php echo $v['endtime'];?></div>
                    <div class="act_type"><?php echo $v['tag'];?></div>
                </div>
                <div class="act_count"><?php echo $v['totalnum'];?>人已报名</div>
                <div class="act_person">
                    <span class="ui_male"><?php echo $v['male'];?>人</span>
                    <span class="ui_female"><?php echo $v['female'];?>人</span>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
img_auto_cut();
</script>