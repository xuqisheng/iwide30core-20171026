<?php require_once('header.php');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/hotel/public/styles/receipt.css');?>">
<div class="list_style martop bd_bottom add_new_list">
	<a class="input_item webkitbox" href="<?php echo site_url('hotel/hotel/bookroom'); ?>">
    	<div>发票需求</div>
        <div>不需要发票</div>
    </a>
</div>

<div class="list_style bd_bottom add_new_list martop">
    <?php if(isset($list)){ foreach($list as $arr){?>
        <a href="<?php echo site_url('hotel/hotel/bookroom?eid=').$arr['invoice_id']; ?>">
            <div>
                <p class="h24 color_C3C3C3"><?php if($arr['type']==1){ echo '普通发票';}else{ echo '增值税发票';}?></p>
                <p><?php echo $arr['title'];?></p>
            </div>
        </a>
    <?php }} ?>

    <a class="h26" href="<?php echo site_url('/hotel/invoice/edit_invoice'); ?>">
    	<span class="icon"><img src="<?php echo base_url('public/hotel/public/images/add.png');?>"></span>新增发票信息
   	</a>
</div>
</body>
</html>
