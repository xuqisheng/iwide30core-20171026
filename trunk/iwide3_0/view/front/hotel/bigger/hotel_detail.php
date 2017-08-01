
<?php include 'header.php' ?>

<div class="gradient_bg wrapper hotel_details_wrap" style="padding-top:18.5px;">
	<div class="layer_bg border_radius mar_b60 clearfix">
        <div class="img_bg_1 border_radius webkitbox pad_t80 pad_b60 pad_l40 boxflex">
            <div class="pad_r20" style="width:calc(100% - 90px);">
                <p class="color1 h36 txtclip mar_b30"><?php echo $hotel['name'];?></p>
                <p class="color2 h26">
                    <em class="coordinate_ico iconfont mar_r10"></em>
                    <span><?php echo $hotel['address'];?></span>
                </p>
            </div>
            <div class="main_color1 bd_left getline" onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $hotel['address'];?>')">
                <em class="iconfont light_ico h48"></em>
                <p class="h26">导 航</p>
            </div>
        </div>
        <div class="order_information webkitbox flexjustify">
            <div class="h32 color1 flexgrow">酒店电话 : <?php echo $hotel['tel'];?></div>
            <div class="h32 color2 hotel_phone"><a href="tel:<?php echo $hotel['tel'];?>"><em class="main_color1 iconfont block h38">&#xE038;</em></a></div>
        </div>
    </div>
    <div class="pad_t20 pad_b20 bd_bottom">
        <p class="h24 color3 mar_b40">酒店设施</p>
        <div class="clearfix room_description_rows" style="margin:0px;">
            <?php if(!empty($hotel['imgs']['hotel_service'])){ foreach($hotel['imgs']['hotel_service'] as $service){ ?>
                <div class="float">
                    <em class="iconfont"><?php echo $service['image_url'];?></em><span class="h30"><?php echo $service['info'];?></span>
                </div>
            <?php }}?>
        </div>
    </div>
    <div class="pad_t60 pad_b40">
        <p class="h24 color3">酒店介绍</p>
        <p class="h32 paragraph lineheight17 color2 mar_t40"><?php echo $hotel['intro'];?></p>
    </div>
</div>
</body>
</html>