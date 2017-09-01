
<link href="<?php echo base_url('media/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('media/distribute/default/styles/withsraw.css')?>" rel="stylesheet">
<title>提现</title>
</head>
<body>
<div class="box">
	<span class="t_pric">验证码</span>
    <span><input type="text" placeholder="请输入验证码" name="ckcode" id="ckcode" /></span>
</div>
<div class="prompt">
	<p>提现成功后可以在公众号中看到提示哦 ！</p>
</div>
<div><a href=""><div class="f_btn" onclick="do_post();">提交</div></a></div>
</body>
</html>
<script>
function do_post(){
	if(confirm('确定提交申请吗？')){
		$('.f_btn').html('正在提交...');
		var ckcode = $('#ckcode').val();
		$.ajax({
			url:"<?php echo site_url('wap/distribute/renew')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>",
			dataType:'json',
			data:{"cd":ckcode},
			type:'post',
			success:function(datas){
				if(datas.errmsg == 'ok'){
					alert('绑定成功');
					window.location.href="<?php echo site_url('wap/distribute/mine')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>";
				}else{
					$('.f_btn').html('提交');
					alert('绑定失败');
				}
			},
			error:function(){
				alert('error');
			}
		});
		//$.getJSON("<?php echo site_url('wap/distribute/new_drw')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>",{"amount":amount},function(datas){
			
// 		});
	}
}
</script>