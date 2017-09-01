<title></title>
<link href="<?php echo base_url('public/distribute/theme_1_1/styles/play_consular.css')?>" rel="stylesheet" />
</head>
<body>
<div class="heade">
	<div class="logo"><img src="<?php echo base_url('public/distribute/theme_1_1/images/iconfont.png')?>"/></div>
    <p class="num">员工礼券</p>
</div>
<div class="content">
	<div class="con_name">
    	<div class="h_na"><img src="<?php echo base_url('public/distribute/theme_1_1/images/name.png')?>"/></div>
        <div class="h_txt">
        	<p class="tit">Hi~我是小金，</p>
            <p class="h_con">鼓励劳动精神，特此奉上<?php echo $public_info['name'].$package->name?>1份。</p>
            <div class="arr"><img src="<?php echo base_url('public/distribute/theme_1_1/images/aworr2.png')?>"/></div>
        </div>
        <p style="clear:both"></p>
    </div>
    <div class="coupon">
    	<div class="cou_con">
            <div>
            	<p class="cou_titl"><?php echo $package->name?></p>
            </div>
            <font><?php echo $package->credit?>元</font>
            <div class="bg_1"></div>
        </div>
    </div>
    <p>使用方法请领取后点击卡券使用须知</p>
</div>
<div class="floot">
	<?php if(isset($extendtions['package']['used'])):?><a><div class="btn">已经领取</div></a><?php else:?><a><div class="btn" id="btn_get">立即领取</div></a><?php endif;?>
</div>
</body>
</html>
<?php if(!isset($extendtions['package']['used'])):?>
<script>
$(document).ready(function(){
	$('#btn_get').parent().on('click',function(e){
		e.preventDefault();
		$.getJSON('<?php echo site_url('distribute/dis_v1/do_get_p')?>?id=<?php echo $inter_id?>&mid=<?php echo $this->input->get('mid')?>',function(data){
			if(data.errmsg == undefined || data.errmsg != 'ok'){
				alert(data.errmsg);
			}else{
				$('#btn_get').unbind('click');
				$('#btn_get').html('已经领取');
				alert('领取成功');
			}
		});
	});
});
</script>
<?php endif;?>