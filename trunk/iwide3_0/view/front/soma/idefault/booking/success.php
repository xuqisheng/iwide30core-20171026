
<body class="">
<div class="heads center">
    <i class="iconfont c_2ac833">&#xe61e;</i>
    <p class="h36">恭喜你预订成功</p>
</div>
<div class="bd list_style">
    <div class="input_item">
        <span>入住单号</span>
        <span><?php echo isset( $successInfo['show_orderid'] ) ? $successInfo['show_orderid']: '';?></span>
    </div>
</div>
<div class="bd list_style martop color_666">
    <div class="input_item">
        <span>入住时间 </span>
        <span><?php echo isset( $successInfo['startdate'] ) ? date( 'Y-m-d', strtotime( $successInfo['startdate'] ) ): '';?></span>
    </div>
    <div class="input_item">
        <span>离店时间 </span>
        <span><?php echo isset( $successInfo['enddate'] ) ? date( 'Y-m-d', strtotime( $successInfo['enddate'] ) ): '';?></span>
    </div>
    <div class="input_item">
        <span>预订酒店</span>
        <span><?php echo isset( $successInfo['hotel_name'] ) ? $successInfo['hotel_name']: '';?></span>
    </div>
    <div class="input_item">
        <span>预订房型</span>
        <span><?php echo isset( $successInfo['room_name'] ) ? $successInfo['room_name']: '';?></span>
    </div>
</div>
<div class="bd list_style martop color_666">
    <div class="input_item">
        <span>入住人 </span>
        <span><?php echo isset( $successInfo['name'] ) ? $successInfo['name']: '';?></span>
    </div>
    <div class="input_item">
        <span>入住人电话 </span>
        <span><?php echo isset( $successInfo['mobile'] ) ? $successInfo['mobile']: '';?></span>
    </div>
</div>
<div class="center webkitbox" style="padding:15px 0;">
    <div><a class="btn_main bdradius"  href="<?php echo site_url('hotel/hotel/myorder').'?id='.$id;?>">查看订单</a></div>
</div>
<!-- 推荐位  -->
<?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
<!-- <div class="border_top bg_fff relevant" style="display: none;">
    <div class="border_bottom relevant_t "><img class="" src="images/hot.png" />相关推荐</div>
    <div class="relevant_con">
        <a class="r_c_box c_8b8b8b h24 m_b_3">
            <div class="box_img"><img src="images/img1.jpg" /></div>
            <p class="box_txt">ULTIMATE CLASSICS</p>
            <p class="box_txt2"><span>惊喜价</span><i>|</i><span class="c_ff950d">¥399.00</span></p>
        </a>
        <a class="r_c_box c_8b8b8b h24 m_b_3">
            <div class="box_img"><img src="images/img2.jpg" /></div>
            <p class="box_txt">ULTIMATE CLASSICS</p>
            <p class="box_txt2"><span>惊喜价</span><i>|</i><span class="c_ff950d">¥399.00</span></p>
        </a>
        <a class="r_c_box c_8b8b8b h24 m_b_3">
            <div class="box_img"><img src="images/img3.jpg" /></div>
            <p class="box_txt">ULTIMATE CLASSICS</p>
            <p class="box_txt2"><span>惊喜价</span><i>|</i><span class="c_ff950d">¥399.00</span></p>
        </a>
        <a class="r_c_box c_8b8b8b h24 m_b_3">
            <div class="box_img"><img src="images/img4.jpg" /></div>
            <p class="box_txt">ULTIMATE CLASSICS</p>
            <p class="box_txt2"><span>惊喜价</span><i>|</i><span class="c_ff950d">¥399.00</span></p>
        </a>
    </div>
</div> -->
<script>
   // $(function(){
   //      var w=$('.box_img').width();
   //      $('.box_img').height(w);
   // }) 
</script>
</body>
</html>
