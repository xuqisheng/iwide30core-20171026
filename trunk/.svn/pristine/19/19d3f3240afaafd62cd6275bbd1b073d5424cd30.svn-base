<body class="bg_f6f"> 
<div class="j_box">
<?php foreach($items as $v): ?>
    <label class="j_checkbox">
        <input type="checkbox" />
    <?php if( $v['openid'] == $openid ) : ?>
        <?php $aiid = $v['item_id']; ?>
        <?php if( $nowtime > $expirtime ) : ?>
            <div class="j_Presented f_right m_r_4 color_555">已过期</div>
        <?php elseif( $v['can_mail'] == $can_mail ) : ?>
        <div class="j_banr j_c_label j_c_label_bg">
        <?php else : ?>
            <div class="j_Presented f_right m_r_4 color_555">不支持邮寄</div>
        <?php endif; ?>
    <?php else : ?>
        <div class="j_banr j_c_label">
            <div class="j_Presented f_right m_r_4 color_555">已赠送</div>
    <?php endif; ?>
            <div class="j_con_ba clearfix p_3 l_h_15">
                <div class="j_c_imgs f_left m_r_2"><img src="<?php echo $v['face_img']; ?>" /></div>
                <div class="txtclip f_s_11"><b class="f_weigh_no"><?php echo $v['name']; ?> </b></div>
                <div class="txtclip color_555"><?php echo $v['hotel_name']; ?> </div>
                <div class="c_fc9 h3">有效期至：<?php echo substr($v['expiration_date'],0,10); ?> </div>
            </div>
        </div>
    </label>
<?php endforeach; ?>
<!-- <label class="j_checkbox">
    <input type="checkbox" />
    <div class="j_banr j_c_label j_c_label_bg">
        <div class="j_con_ba clearfix p_3">
            <div class="j_c_imgs f_left m_r_2"><img src="images/img-8.jpg" /></div>
            <div class="j_c_product txtclip">岭南集团端午粽子 </div>
            <div class="j_c_name txtclip color_555">中国大酒店 </div>
            <div class="c_fc9 y h2">118X4</div>
        </div>
    </div>
</label> 
<label class="j_checkbox">
    <div class="j_banr j_c_label">
        <div class="j_Presented f_right m_r_4 color_555">已赠送</div>
        <div class="j_con_ba clearfix p_3">
            <div class="j_c_imgs f_left m_r_2"><img src="images/img-8.jpg" /></div>
            <div class="j_c_product txtclip">岭南集团端午粽子 </div>
            <div class="j_c_name txtclip color_555">中国大酒店 </div>
            <div class="c_fc9 y h2">118X4</div>
        </div>
    </div>
</label>-->
</div>
<div style="height:3rem;">
	<div class="j_fixed">确认选中</div>
</div>
<script>
//j_delivery.html
var o_label=$('label');
var bool=false;
$(".j_fixed").click(function(){
    var number=0;
    for(var i=0;i<o_label.length;i++){
        if(o_label.eq(i).find('input').is(':checked')){
            number++;
            bool=true;
        }
    }
    
    var address_url = "<?php echo Soma_const_url::inst()->get_url('*/consumer/shipping_address_info'); ?>"
                        +'?id='+"<?php echo $inter_id; ?>"
                        +'&aiid='+<?php echo $aiid; ?>
                        +'&bsn=package&num='+number;
    // alert(address_url);return ;
    if(bool){
        // var $number = number;
		window.location.href=address_url;
	}else{
		$.MsgBox.Confirm('请选择礼品',null,null,'好的','取消')	
	}
})
</script>
</body>
</html>

