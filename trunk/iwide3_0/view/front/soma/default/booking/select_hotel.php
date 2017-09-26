
<body>
<div class="pageloading"><p class="isload">正在加载</p></div>
<form action="<?php echo $search_url;?>" method="post" class="_w" style="position:fixed; z-index:999">
    <div class="search bg_fff bd_bottom">
        <div class="in_input"><em class="iconfont">&#xe628;</em><input type="text" id="searchContent" placeholder="输入酒店名和房型" name="search" value="<?php echo $search;?>" /><input type="hidden" name="is_search" value="1" /></div>
        <!-- <div class="confirm">确认</div> -->
        <input class="confirm" value="确认" type="submit" id="PostSubmit">
    </div>
</form>
<div style="padding-top:46px;"></div>
    <?php if( isset( $wx_booking_config ) && !empty( $wx_booking_config ) ): ?>
        <?php foreach( $wx_booking_config as $k=>$v ): ?>
            <?php if( isset( $v['room_ids'] ) && !empty( $v['room_ids'] ) && is_array( $v['room_ids'] ) ): ?>
                <?php foreach( $v['room_ids'] as $sk=>$sv ): ?>
                    <?php if( isset( $sv['price_codes'] ) && !empty( $sv['price_codes'] ) && is_array( $sv['price_codes'] ) ): ?>
                        <?php foreach( $sv['price_codes'] as $ssk=>$ssv ): ?>
<div class="bd webkitbox con_list martop bg_fff">
    <div class="img"><div class="squareimg">
    	<img src="<?php echo isset($sv['room_img'])&&$sv['room_img']!=''?$sv['room_img']:get_cdn_url('public/soma/images/default2.jpg');?>">
    </div></div>
    <div>
    	<div class="webkitbox">
        	<div>
                <p><?php echo isset( $v['name'] )?$v['name']:'';?></p>
                <p class="h26"><?php echo isset( $sv['name'] )?$sv['name']:'';?></p>
            </div>
    		<a class="btn_main bdradius h24" href="<?php echo Soma_const_url::inst()->get_url('*/booking/select_hotel_time', array('hid'=>$v['hotel_id'],'id'=>$inter_id,'aiid'=>$aiid,'aiidi'=>$aiidi,'oid'=>$oid,'bsn'=>$business,'rmid'=>isset($sv['room_id'])?$sv['room_id']:'','cdid'=>isset($ssv['price_code'])?$ssv['price_code']:'' ) ); ?>">订房</a> 
    </div>
        <!-- <p class="h26"><?php //echo isset( $ssv['price_name'] )?$ssv['price_name']:'';?></p> -->
        <p class="c_989898 h24"><i class="iconfont">&#xe606;</i><?php echo isset( $v['address'] )?$v['address']:'';?></p>
    </div>
</div>

                        <?php endforeach;?>
                    <?php endif;?>
                <?php endforeach;?>
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
<script>
    $("#PostSubmit").click(function(){
        if( $.trim($('#searchContent').val()) == '' ){
            return false;
        }
    });
</script>
</body>
</html>
