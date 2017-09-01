<?php require_once('header.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/hotel/public/styles/receipt.css');?>">
<!-- 提交成功 -->

<div class="center ui_success h36" style="height:auto"><div style="color:#7bb928;padding-bottom:20px; ">预约成功</div></div>
<?php if(isset($type) && $type==1){ ?>
<div class="center h26" style="padding-bottom:20px;">
	<p>等待工作人员确认信息</p>
</div>
<?php }else{ ?>
<div class="center h26" style="padding-bottom:20px;">
    <p>等待工作人员确认信息并开具发票</p>
    <p>请留意您的微信消息</p>
</div>
<?php }?>
<div class="center h26 hide" style="color:#008ffe;" >
	关闭页面 <span class="icon"><img src="<?php echo base_url('public/hotel/publick/images/link.png');?>"></span>
</div>
</body>
</html>
