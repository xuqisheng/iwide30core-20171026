
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/withsraw.css')?>" rel="stylesheet">
<title>提现</title>
</head>
<body>
<div class="box">
	<span class="t_pric">提现金额</span>
    <span><input type="text" placeholder="请输入转出金额" name="amount" id="amount" /></span>
</div>
<div class="prompt">
	<p>当前最高可提现金额为：¥<?php echo empty($saler_details['total_fee'])?0:$saler_details['total_fee']?> </p>
	<p>提现成功后可以在公众号中看到提示哦 ！</p>
</div>
<div><a href=""><div class="f_btn" onclick="do_post();">提交</div></a></div>
</body>
</html>
<script>
function do_post(){
	if(confirm('确定提交申请吗？')){
		$('.f_btn').html('正在提交...');
		var amount = $('#amount').val();
		$.ajax({
			url:"<?php echo site_url('wap/distribute/new_drw')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>",
			dataType:'json',
			data:{"amount":amount},
			type:'get',
			success:function(datas){
				if(datas.errmsg == 'ok'){
					alert('提交成功');
					window.location.href="<?php echo site_url('wap/distribute/drw_logs')?>?id=<?php echo $inter_id?>&saler=<?php echo $saler?>";
				}else if(datas.errmsg == 'faild:AMOUNT_LESS_THEN_ZERO'){
					$('.f_btn').html('提交');
					alert('输入的金额不合法');
				}else{
					$('.f_btn').html('提交');
					alert('提交失败');
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