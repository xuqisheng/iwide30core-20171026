<?php require_once 'header.php';?>
<?php echo referurl('css','activity.css',2,$media_path) ?>
<body>
<div class="activity_list">
	<div class="activity_item">
    	<div class="bg_img ui_img_auto_cut"><img src="<?php echo referurl('img','bg03.jpg',2,$media_path) ?>"></div>
        <div class="absolute">
        	<div class="activity_box">
                <div class="act_title"><?php echo $active['title'];?></div>
                <div class="act_local"><?php echo $active['address'];?></div>
                <div class="overflow">
                    <div class="act_time"><?php echo $active['starttime'];?></div>
                    <div class="act_type"><?php echo $active['tag'];?></div>
                </div>
                <div class="act_count"><?php echo $active['totalnum'];?>人已报名</div>
                <div class="act_person">
                    <span class="ui_male"><?php echo $active['male'];?>人</span>
                    <span class="ui_female"><?php echo $active['female'];?>人</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="activity_detail">
        <p>活动介绍</p>
        <p>【活动时间】<?php echo $active['starttime'];?> <?php echo $active['endtime'];?></p>
        <p>【活动地点】<?php echo $active['address'];?></p>
        <p>【活动内容】<?php echo $active['purpose'];?></p>
        <p>【活动路线】<?php echo $active['lines'];?></p>
        <p>【注意事项】</p>
        <p><?php echo $active['notes'];?></p>
    </div>
    <div class="person_list">
    	<a href="/index.php/chat/talk/signuplist?iad=<?php echo $active['id'];?>" class="person_list_title">
        	<span>报名列表</span>
            <span><?php echo $active['totalnum'];?>人</span>
        </a>
    	<div class="ui_list">
		    <?php foreach($signup as $v){ ?>
        	<div class="ui_item">
            	<div class="user_img ui_img_auto_cut"><img src="<?php echo $v['logo'];?>"></div>
                <a href="/index.php/chat/talk/makefri?uid=<?php echo $v['id'];?>" class="ui_btn float_r">发送消息</a>
                <div class="user_name"><?php echo $v['uname'];?>(<?php echo $v['anickname'];?>)</div>
                <div class="ui_time"><?php echo date('m-d H:i',$v['signuptime']);?></div>
            </div>
			<?php } ?>
        </div>
    </div>
</div>
<div style="padding-top:15%">
	<div class="foot_btn" onClick="location.href='/index.php/chat/talk/signup?iad=<?php echo $active['id'];?>'">马上报名</div>
</div>

<script>
 document.addEventListener('load',img_auto_cut,false);
</script>