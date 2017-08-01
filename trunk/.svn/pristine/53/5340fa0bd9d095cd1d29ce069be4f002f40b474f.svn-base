<link href="<?php echo base_url('public/distribute/default/styles/apply_complish.css')?>" rel="stylesheet">
<title>分销申请</title>
</head>
<body><?php if($status == 'complete'):?>
<div class="box">
	<div class="b_lo">
		<div class="gon"><img src="<?php echo base_url('public/distribute/default/images/sta_2.png')?>"/></div>
        <p class="success">等待酒店审核</p>
    </div>
    <div class="d_txt">
    	<p>等待酒店审核通过</p>
        <p>申请状态可以在公众号“会员中心”查看</p> 
    </div>
    <a href="<?php echo site_url('hotel/hotel/index')?>?id=<?php echo $inter_id?>"><div class="f_shop">逛逛订房 </div></a>
</div>
<?php elseif ($status == 'processing'):?>
<div class="box">
	<div class="b_lo">
		<div class="gon"><img src="<?php echo base_url('public/distribute/default/images/sta_1.png')?>"/></div>
        <p class="examine">审核中...</p>
    </div>
    <div class="d_txt">
    	<p>等待酒店审核通过</p>
        <p>申请状态可以在公众号“会员中心”查看</p> 
    </div>
    <a href="<?php echo site_url('hotel/hotel/index')?>?id=<?php echo $inter_id?>"><div class="f_shop">逛逛订房 </div></a>
</div>
<?php elseif ($status == 'faild'):?>
<div class="box">
	<div class="b_lo">
		<div class="gon"><img src="<?php echo base_url('public/distribute/default/images/sta_3.png')?>"/></div>
        <p class="fail">审核失败</p>
    </div>
    <div class="d_txt">
    	<p>审核不通过</p>  
        <p>请<a href="<?php echo site_url('distribute/distribute/edit')?>?id=<?php echo $inter_id?>">重新填写资料申请</a></p> 
    </div>
    <a href="<?php echo site_url('hotel/hotel/index')?>?id=<?php echo $inter_id?>"><div class="f_shop">逛逛订房 </div></a>
</div>
<?php endif;?>
</body>
</html>