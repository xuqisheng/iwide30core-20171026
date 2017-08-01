
<?php include 'header.php' ?>

<?php echo referurl('css','calendar.css',1,$media_path) ?>
<?php echo referurl('js','calendar.js',1,$media_path) ?>
<?php echo referurl('css','swiper.css',1,$media_path) ?>
<?php echo referurl('js','swiper.js',1,$media_path) ?>
<script type="text/javascript">
to_locate();
wx.ready(function(){
  to_locate();
});
  function to_locate(){
        wx.getLocation({
            success: function (res) {
                latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                locate_city(latitude,longitude);
            }
        });
    }
    function locate_city(lati,logi){
        geocoder = new qq.maps.Geocoder();
        var lat = parseFloat(lati);
        var lng = parseFloat(logi);
        var latLng = new qq.maps.LatLng(lat, lng);
        geocoder.getAddress(latLng);
        geocoder.setComplete(function(result) {
            // get_near(lati,logi);
        });
    }

$(".j_whole_show always ").on("click",function(){
    $("#always_book").show();
});

$(".j_whole_show collect").on("click",function(){
    $("#my_collect").show();
});

</script>
<style>
<?php if (sizeof($pubimgs) == 1) { ?>
.search_bg .search_top_img{border-radius: 0px;}
.search_img_wrapper{padding:0 0 0 0;}
<?php } ?>
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<div class="search_bg">
	<div class="center swiper-container search_img_wrapper">
       <div class="swiper-wrapper">
            <?php $i = 0; foreach ($pubimgs as $pi){?>
              <div class="swiper-slide search_top_img">
                  <a class="slideson" href="<?php echo Hotel_base::inst()->get_url($pi['link'],'',TRUE);?>">
                      <div class="squareimg">
                         <img src="<?php echo $pi['image_url'];?>" />
                      </div>
                  </a>
                  <p class="search_top_word"><span class="main_color1 h32"><?php  $i++; echo $i; ?></span><span class="h22">/<?php echo sizeof($pubimgs); ?></span></p>
              </div>
            <?php }?>
       </div>
    </div>
    <form action="<?php echo Hotel_base::inst()->get_url("SRESULT")?>" method="post" id="index_search">
      <input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
      <input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
      <input type="hidden" id="off" name="off" value='0' />
      <input type="hidden" id="num" name="num" value='20' />
      <input type="hidden" id="latitude" name="latitude" value='' />
      <input type="hidden" id="longitude" name="longitude" value='' />
      <input type="hidden" id="sort_type" name="sort_type" value='distance' />
      <input type="hidden" id="city" name="city" value='<?php if($first_city!='全部')echo $first_city;?>' />
      <input type="hidden" id="area" name="area" value='' />
      <input type="hidden" id="ec" name="ec" value='[]' />
      <input type="hidden" id="first_local" name="first_local" value='0' />
      <div class="searchLayer mar_b80">
          <label class="bd_bottom webkitbox pad_lr10 h28 flexjustify" style="padding-bottom:7px;">
              <div id="search_click">
                    <span class="iconfont">&#xe006;</span>
                    <input name="keyword" class="color3 h30" style="margin-left:-1.5px;" placeholder="搜索城市/关键字/位置" disabled value="<?php echo $first_city; ?>" id="city_val">
              </div>
              <a class="nearby" href="javascript:;">
              	<span class="main_tab_line"></span>
              	<span class="iconfont main_color1 pad_lr20 h48">&#xe003;</span>
              </a>
          </label>
          <div class="flex center checkdate h22 mar_t40 mar_b60" id="checkdate">
              <span class="mar_r10 iconfont main_color1 h36" >&#xe014;</span>
              <div class="checkin">
                  <div class="color3 date-title">入&nbsp;&nbsp;住</div>
                  <div class="iconfont main_color1 day"></div>
                  <div class="date h24"></div>
              </div>
              <span class="main_line mar_t20"></span>
              <div class="checkout">
                  <div class="color3 date-title">离&nbsp;&nbsp;店</div>
                  <div class="iconfont main_color1 day"></div>
                  <div class="date h24"></div>
              </div>
              <span class="mar_l10 iconfont main_color1 h38">&#xe014;</span>
          </div>
          <a class="iconfont button mar_b80 spacing" id="search_hotel" href="javascript:;">查询酒店</a>
          <div class="h24 center pad15 histroy" id="old_search" style="display:none;">
              <div class="color4 h28">最近搜索</div>
          </div>
      </div>
    </form>
	<div class="webkitbox others center h28 boxflex pad_lr30">
    <?php foreach($homepage_set['menu'] as $v){ ?>
        <a class="layer_bg color2 <?php if($v['code'] != 'athour' && $v['code'] != 'order' && $v['code'] != 'ticket') echo 'j_whole_show';?> <?php echo $v['code'];?>"
            <?php if($v['code'] == 'athour') echo 'href="'.Hotel_base::inst()->get_url("SEARCH",array('type'=>'athour'));?>
            <?php if($v['code'] == 'order') echo 'href="'.Hotel_base::inst()->get_url("MYORDER").'"';?>
            <?php if($v['code'] == 'ticket') echo 'href="'.Hotel_base::inst()->get_url("SEARCH",array('type'=>'ticket')).'"';?>>
            <div class="img"><?php if(!empty($special_theme)&&$special_theme=='spring'){?><p class="squareimg"><img src="<?php echo base_url('public/hotel/public/images/spring').'/'.$v['code'].'.png';?>"></p><?php }else{?><em class="iconfont <?php echo $v['code'];?>"></em><?php }?></div>
            <div class="txtclip h28 mar_b     "><?php echo $v['desc'];?></div>
            <div class="txtclip h24 color3"><?php echo $v['menu_name'];?></div>
        </a>

    <?php }?>
    </div>
    <?php include 'footer.php' ?>

</div>
<div class="gradient_bg city_wrapper scroll">
      <div class="city_search webkitbox bd_bottom color3">
        <em class="h32 color2 iconfont inblock" style="margin: 5px 6px 0 0">&#xE006;</em> 
        <input id="city_search_content" url="<?php echo Hotel_base::inst()->get_url("AJAX_HOTEL_SEARCH")?>" class="h38 flexgrow color1" type="text" placeholder="关键字 / 位置 / 名称" >
          <em id="city_search_clear" class="iconfont h18 mar_l20" style="display:none;">&#xE000;</em>
      </div>
      <div id="city_content_wrapper">
          <div class="city_content">
              <a class="nearby" href="javascript:;">
                <div class="webkitbox bd_bottom nearby_sarch_wrap color3">
                  <em class="h40 main_color1 iconfont city_light_ico inblock" style="width:1.1rem;margin-right:6px">&#xE025;</em> 
                  <span class="h32 main_color1 mar_r20">附近</span>
                  <input id="nearby_sarch" class="h26 flexgrow color3" type="text" value="附近有什么，去看看" disabled>
                </div>
              </a>
              <?php if(!empty($last_orders)){ ?>
              <div class="mar_t80">
                  <p class="h24 color3 mar_b40">历史搜索</p>
                  <div class="city_search_history h30 color2">
                    <?php foreach($last_orders as $lo){?>
                        <div class="hotel_search"><?php echo str_replace(array('市','区','县'),'',$lo['hcity']);?></div>
                    <?php }?>
                  </div>
              </div>
              <?php }?>
              <?php if(!empty($hot_city)){ ?>
              <div class="mar_t80">
                  <p class="h24 color3 mar_b40">热门搜索</p>
                   <div class="city_search_history h30 color2">
                        <?php foreach($hot_city as $hc){?>
                            <div class="hotel_search"><?php echo str_replace(array('市','区','县'),'',$hc);?></div>
                        <?php }?>
                  </div>
              </div>
              <?php }?>
              <div class="city_list_wrap mar_t40 color2 h28">
                    <div>
                      <p class="hotel_search" city="" area="" style="border-bottom: 0.5px solid #363636;">全部</p>
                    </div>
                  <?php $let=array(); foreach($citys as $ck=>$cs){ $let[]=$ck;?>
                    <div data-letter="<?php echo $ck;?>">
                        <p><?php echo $ck;?></p>
                        <?php foreach($cs as $c){ ?>
                        <?php if(isset($c['area'])){ ?>
                            <p class="hotel_search" city="<?php echo $c['city'];?>" area="<?php echo $c['area'];?>">
                                <?php
                                    if(strlen($c['area'])>6){
                                        echo str_replace(array('市','区','县'),'',$c['area']).'('.str_replace(array('市','区','县'),'',$c['city']).')';
                                    }else{
                                        echo $c['area'].'('.str_replace(array('市','区','县'),'',$c['city']).')';
                                    }
                                ?>
                            </p>
                        <?php }else{  ?>
                            <p class="hotel_search"><?php echo str_replace(array('市','区','县'),'',$c['city']); ?></p>
                        <?php }}?>
                    </div>
                  <?php }?>
              </div>
          </div>
          <div class="city_letter_list h22 color2">
            <?php foreach($let as $l){?>
                <div><?php echo $l;?></div>
            <?php }?>
          </div>
      </div>
      <div id="city_search_result" style="display:none;" class="city_content">
      </div>
      <div id="city_no_search_result" class="city_search_result"  style="display:none;" class="city_content">
          <div class="center city_no_search_word color3">
              <em class="iconfont h60 mar_b40 inblock">&#xE006;</em>
              <p class="h24 mar_b10">没有找到搜索结果~</p>
              <p class="h24">换个条件试试</p>
          </div>
<!--           <div class="mar_t60">
              <p class="h24 color3 mar_b40">为你推荐</p>
              <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>火车站</p></div>
              <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>广州北火车站店</p></div>
              <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>广州南火车站店</p></div>
          </div> -->
      </div>
</div>

<!--<section class="whole_eject"  style="display:none;" id="whole_content">
    <div class="whole_eject_small bg_282828 pad_lr60 pad_b60" style="padding-top:2.312rem;">
        <em class="close iconfont color6">&#xE000;</em>
        <div class="center">
            <p class="color1 h32">内容标题</p>
            <div class="mar_t40">
                <p class="color1 h32">内容</p>
            </div>
            <div class="iconfont button spacing h32  mar_t80">确定</div>
        </div>
    </div>
</section>-->

<section class="always_book whole_eject"  style="display:none;" id="always_book">
    <div class="whole_eject_small bg_282828 pad_lr60 pad_b60" style="padding-top:2.312rem;">
        <em class="close iconfont color6">&#xE000;</em>
        <div class="center">
            <p class="color1 h32">常住酒店</p>
            <div class="mar_t40">
                <?php if(!empty($last_orders)){ ?>
                <?php foreach($last_orders as $lo){?>
                <p class="color1 h32"><a href="<?php echo Hotel_base::inst()->get_url("INDEX",array('h'=>$lo['hotel_id']))?>" onclick="stop_bubble()"><?php echo $lo['hname']; ?></a></p>
                <?php }} else {?>
                <p class="color1 h32">无</p>
                <?php }?>
            </div>
            <div class="iconfont button spacing h32  mar_t80">确定</div>
        </div>
    </div>
</section>

<section class="my_collect whole_eject"  style="display:none;" id="my_collect">
    <div class="whole_eject_small bg_282828 pad_lr60 pad_b60" style="padding-top:2.312rem;">
        <em class="close iconfont color6">&#xE000;</em>
        <div class="center">
            <p class="color1 h32">我的收藏</p>
            <div class="mar_t40">
                <?php if(!empty($hotel_collection)) {?>
                    <?php foreach($hotel_collection as $hc){ ?>
                        <p class="color1 h32"><a href="<?php echo Hotel_base::inst()->get_url($hc['mark_link'],array(),TRUE);?>" onclick="stop_bubble()"><?php echo $hc['mark_title'];?></a></p>
                    <?php }?>
                <?php }else{?>
                    <li>无</li>
                <?php }?>
            </div>
            <div class="iconfont button spacing h32  mar_t80">确定</div>
        </div>
    </div>
</section>

</body>
<?php echo referurl('js','search.js',1,$media_path) ?>
<?php echo referurl('js','search_public.js',1,$media_path) ?>
</script>
</html>