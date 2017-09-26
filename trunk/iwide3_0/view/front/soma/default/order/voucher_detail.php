<div class="order_list bg_fff" style="margin-bottom:8px">
    <a href="<?php echo Soma_const_url::inst()->get_package_detail(array('id'=>$this->inter_id,'pid'=>$item['product_id']));?>">
        <div class="item_header pad3 webkitbox">
        <?php if(isset($order_id) && $order_id != ''): ?>
            <p>订单编号：<?php echo $order_id; ?></p>
        <?php else: ?>
            <p>礼物编号：<?php echo $gift_id; ?></p>
        <?php endif; ?>
            <p class="txt_r"><?php echo $detail['status']; ?></p>
        </div>
        <div class="item bd">
            <div class="img"><img class="lazy" src="<?php echo get_cdn_url('public/soma/images/default2.jpg'); ?>" data-original="<?php echo $item['face_img'];?>" /></div>
            <p class="txtclip h30"><?php echo $item['name'] ?></p>
            <p class="txtclip color_555"><?php echo $voucher_detail['name']; ?></p>
            <p class="txtclip color_main"><span class="h24">惊喜价</span><span class="y h30"><?php echo $item['price_package']?></span></p>
            <?php if( $item['qty'] == 0): ?>
                <p class="txt_r h20 color_888">已放入卡包</p>
            <?php endif;?>
        </div>
    </a>
</div>
<div class="color_fff martop pad3" style="background:#ff4062;">礼包内容    <?php if($voucher_detail['credit'] > 0) { echo "积分：" . $voucher_detail['credit'] . "积分"; } ?>  <?php if($voucher_detail['deposit'] > 0) { echo "{$show_name}：{$voucher_detail['deposit']}元"; } ?></div>
<?php if( $item['qty'] == 0 && ($voucher_detail['deposit'] > 0 || $voucher_detail['credit'] > 0) ):?><div class="center martop pad30"><a class="color_link" style="text-decoration:underline" ><?php echo $show_name;  ?>、积分已存入个人帐户</a></div><?php endif;?>
<?php 
    if( isset( $voucher_detail['card'] ) && count( $voucher_detail['card'] ) > 0 ):
        // var_dump( $voucher_detail['card'] );die;
        foreach ($voucher_detail['card'] as $k => $v):
            for( $i=0; $i<$v['number']; $i++ ):
?>
            <div class="coupon2">
            	<div class="item">
                	<div class="webkitbox itemhead">
                    	<div class="icon"><img class="lazy" src="<?php echo get_cdn_url('public/soma/images/default2.jpg')?>" data-original="<?php echo $v['logo_url']?>" /></div>
                        <div>
                        	<div><?php echo $v['title'];?></div>
                            <div class="h20 color_888">使用说明：<?php echo $v['description'];?></div>
                        </div>
                    </div>
                	<div class="webkitbox justify color_888 h20 itemfoot">
                        <!-- <div>使用时间：<span style="font-family:arial;"><?php //echo date( 'Y-m-d H:i:s', $v['time_start'] );?>-<?php //echo date( 'Y-m-d H:i:s', $v['time_end'] );?></span></div> -->
                    	<div>使用时间：<span style="font-family:arial;"><?php echo date( 'Y/m/d', $v['use_time_start'] );?> -
                            <?php 
                                if( $v['use_time_end_model'] == 'g' ) echo date( 'Y/m/d', $v['use_time_end'] );
                                elseif( $v['use_time_end_model'] == 'y' ) echo date( 'Y/m/d', $v['use_time_start']+$v['use_time_end_day']*24*60*60 );
                            ?>
                            </span></div>
                        <div><a href="<?php echo $v['header_url'];?>" class="btn">立即使用</a></div>
                    </div>
                </div>
            	<!-- <div class="item">
                	<div class="webkitbox itemhead">
                    	<div class="icon"><img class="lazy" src="images/default2.jpg" data-original="images/eg5-2.jpg" /></div>
                        <div>
                        	<div>四季酒店8折自助餐劵</div>
                            <div class="h20 color_888">使用说明：酒店内各房型通用</div>
                        </div>
                    </div>
                	<div class="webkitbox justify color_888 h20 itemfoot">
                    	<div>使用时间：<span style="font-family:arial;">2016.11.31-2016.12.31</span></div>
                        <div><a href="" class="btn">立即使用</a></div>
                    </div>
                </div> -->
            </div>
<?php 
            endfor;
        endforeach;
    endif;
?>

</body>

</html>
