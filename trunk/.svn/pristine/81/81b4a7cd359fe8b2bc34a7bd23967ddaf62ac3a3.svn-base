<?php require_once 'header.php';?>
<?php echo referurl('js','get_bottle.js',2,$media_path) ?>
<?php echo referurl('css','ui_bottle_page.css',2,$media_path) ?>
<?php echo referurl('css','animate.css',2,$media_path) ?>
<?php echo referurl('css','fly.css',2,$media_path) ?>
<body>
<div class="page_loading">
	<div>
    	<p><img src="<?php echo referurl('img','ico/loading.gif',2,$media_path) ?>" /></p>
    	<p>正在加载</p>
    </div>
</div>

<div class="page" style="display:none">
	<div class="fly_animate">
    	<img src="<?php echo referurl('img','bird.gif',2,$media_path) ?>" class="fly1" />  
    	<img src="<?php echo referurl('img','bird.gif',2,$media_path) ?>" class="fly2" /> 
    	<img src="<?php echo referurl('img','bird.gif',2,$media_path) ?>" class="fly3"/> 
    	<img src="<?php echo referurl('img','bird.gif',2,$media_path) ?>" class="fly4"/> 
    	<img src="<?php echo referurl('img','bird.gif',2,$media_path) ?>" class="fly5"/> 
    </div>
	<div class="foot_btn">
		<div class="trapezoid">
        	<a href="/index.php/chat/bottle/addbottle?id=<?php echo $inter_id;?>" class="throw_btn">
            	<p><img src="<?php echo referurl('img','img01.png',2,$media_path) ?>" /></p>
                <p>丢酒瓶</p>
                <em class="hide"></em>
            </a>
        	<div class="get_btn">
            	<p><img src="<?php echo referurl('img','img02.png',2,$media_path) ?>" /></p>
                <p>捞酒瓶</p>
                
            </div>
            <a href="/index.php/chat/bottle/bottle?id=<?php echo $inter_id;?>" class="mine_btn">
            	<p><img src="<?php echo referurl('img','img03.png',2,$media_path) ?>" /></p>
                <p>我的酒瓶</p>
                <em style="display:none"></em>
            </a>
        </div>
    </div>
    <!-- 捞瓶子弹层  -->
    <div class="pull pull_get_bottle" style="display:none">
    	<a onClick="" class="bgimg_box">
        </a>
        <div></div>
    </div>
    
	<img src="<?php echo referurl('img','img04.png',2,$media_path) ?>" class="_throw" style="display:none; width:auto; height:50%; position:absolute; top:30%;">
</div>
</body>
<script type="text/javascript">
online();
function throw_bottole(){
	window.setTimeout(function(){
		$('._throw').show();
		$('._throw').addClass('fadeout');
		$('._throw').fadeOut('2000',function(){
			$('._throw').removeClass('fadeout');
		})
	},1000);
}
if(getcookie('qf_rebottleok')){
   delcookie('qf_rebottleok');
   throw_bottole();
}
if(getcookie('qf_addbottleok')){
   delcookie('qf_addbottleok');
   throw_bottole();
}
$('.reback_btn').click(function(){
    setcookie('qf_rebottleok','1',3600);
});
</script>
</p>
