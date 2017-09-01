<?php require_once 'header.php';?>
<?php echo referurl('css','get_bottle.css',2,$media_path) ?>
<body>
<div class="page">
    <div class="bottle_detail">
        <div class="user_img"><img src="<?php echo $getbottle['flogo'];?>" /></div>
        <div class="user_name <?php if($getbottle['fsex']==1){echo 'ui_male';} else {echo 'ui_female';} ?>"><?php echo $getbottle['fnickname'];?></div>
        <div class="user_local"><?php echo $fromuser['province'];?> <?php echo $fromuser['city'];?></div>
        <div class="user_said_detail">
            <p><?php echo qqface($getbottle['msg']);?></p>
            <p>
				<?php 				
				foreach($upload as $v){
				?>
                <div class="ui_img_auto_cut ui_square_h"><img src="<?php echo $v['src'];?>" class="preimg" /></div>
				<?php } ?>
            </p>
        </div>
        <div class="foot_btn">
            <a href="/index.php/chat/bottle/chat?iad=<?php echo $getbottle['id'];?>">回复TA</a>
		    <a class="reback_btn" href="/index.php/chat/bottle/getbottle?act=reback&iad=<?php echo $getbottle['bid'];?>">扔回酒店</a>
        </div>
    </div>
</div>

<script type="text/javascript">
img_auto_cut();
var nowgetbottle = '<?php echo $getbottle['id'];?>';

if(getcookie('qf_isgetbottle') == nowgetbottle){
   location.href = '/index.php/chat/bottle/mainbottle';
}
else {
   setcookie('qf_isgetbottle',nowgetbottle,3600);
}
preimg();
</script>




