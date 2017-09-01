<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<script src="<?php echo base_url('public/mall/multi/script/ui_control.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<title>和小伙伴分享一下心得</title>
<style>
</style>
</head>
<body>
<div class="content cur">
	<div class="item">
    	<div class="itemimg img_auto_cut"><img src="<?php echo $orders['items'][0]['gs_logo']?>" /></div>
        <div class="hotelname"><?php echo $orders['items'][0]['gs_name']?></div>
        <div class="desc gray"><?php echo $orders['items'][0]['gs_desc']?></div>
    </div>
    <div class="pin_txt">
    	<textarea name="gcontent" id="gcontent" rows="8">来和小伙伴分享一下心得吧～</textarea>
    </div>
    <div class="state">
    	<p>活动说明：</p> 
    	<p>1.大红色的机会 </p>
    	<p>2.大家看得见啊老师</p>
    </div>
</div>
<div class="footfixed">
	<a href="javascript:;" class="surebtn bg_orange cur" onclick="do_submit()">提交评价</a>
</div>
</body>
</html>
<script type="text/javascript">
<!--
function do_submit(){
	$.post("<?php echo site_url('mall/wap/do_save_comment')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>",{'oid':<?php echo $orders['order_id']?>,'gsid':<?php echo $orders['order_id']['items'][0]['gs_id']?>,'content':$('#gcontent').val()},function(datas){
		if(datas.errmsg == 'ok'){
			window.location.href="<?php echo site_url('mall/wap/comment/success')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>";
		}else{
			alert('提交失败了！');
		}
	},'json');
}
//-->
</script>
