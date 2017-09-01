<?php require_once 'header.php';?>
<?php echo referurl('css','activity.css',2,$media_path) ?>
<body>

    <div class="person_list">
    	<div class="ui_list">
		    <?php
			if(empty($myfriend)){
			    echo '<div class="ui_item" onClick="location.href=\'/index.php/chat/talk/\'" style="text-align:center;height:150px; line-height:150px">暂无消息，快去大厅找个小伙伴吧！</div>';
			}
			foreach($myfriend as $v){
			?>
        	<div class="ui_item" onClick="location.href='/index.php/chat/talk/msg?iad=<?php echo $v['fid'];?>'">
            	<div class="user_img"><img src="<?php echo $v['logo'];?>"></div>
                <a href="/index.php/chat/talk/msg?iad=<?php echo $v['fid'];?>" class="ui_btn float_r">发送消息</a>
                <div class="user_name"><?php echo $v['nickname'];?></div>
                <div class="ui_time"><?php echo date('m-d H:s',$v['uptime']);?></div>
            </div>
			<?php
			}
			?>
        </div>
	</div>
<script>
 window.addEventListener('load',function(){
	 img_auto_cut($('.person_list'));
 },false);
</script>