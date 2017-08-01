
<link href="<?php echo base_url('public/mall/multi/style/global.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/address.css')?>" rel="stylesheet">
<title>我的地址</title>
</head>
<body>
<div class="ui_btn_list ui_border"><?php foreach($addresses as $address):?> 
	<a href="<?php echo site_url('mall/wap/mail_order/'.$order_id) ?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&naid=<?php echo $address['id']?>" class="item address">
    	<tt><?php echo $address['contact']?></tt>
	    <tt><?php echo $address['phone']?></tt>
    	<div><?php echo $address['province'].$address['city'].$address['region'].$address['address']?></div>
    </a><?php endforeach;?>
	<a href="<?php echo site_url('mall/wap/address_edit/'.$order_id)?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" class="item">
    	<em class="ui_ico ui_ico7"></em>
    	<tt>添加地址</tt>
    </a>
</div>
</body>
</html>
