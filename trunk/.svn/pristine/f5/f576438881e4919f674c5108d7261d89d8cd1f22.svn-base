
<?php include 'header.php' ?>
<?php echo referurl('css','calendar.css',1,$media_path) ?>
<?php echo referurl('js','calendar.js',1,$media_path) ?>
<?php echo referurl('js','iscroll.js',1,$media_path) ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ggmZIrqw5hOjnXwT7ypK0aIoZXrn4yfS"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<input type="hidden" id="ajax_src" value="<?php echo Hotel_base::inst()->get_url("AJAX_HOTEL_LIST");?>">
<input type="hidden" id="startdate" name="startdate" value='<?php echo date('Y/m/d',strtotime($startdate));?>' />
<input type="hidden" id="enddate" name="enddate" value='<?php echo date('Y/m/d',strtotime($enddate));?>' />
<input type="hidden" id="city" name="city" value="<?php echo $city; ?>" />
<input type="hidden" id="city_val" name="city" value="<?php echo $city; ?>" />
<input type="hidden" id="area" name="area" value="<?php echo $area; ?>" />
<input type="hidden" id="off" name="off" value='0' />
<input type="hidden" id="num" name="num" value='20' />
<input type="hidden" id="nearby" name="nearby" value='<?php echo $nearby;?>' />
<input type="hidden" id="latitude" name="latitude" value='' />
<input type="hidden" id="longitude" name="longitude" value='' />
<input type="hidden" id="sort_type" name="sort_type" value='distance' />
<input type="hidden" id="city" name="city" value='' />
<input type="hidden" id="ec" name="ec" value='<?php echo $extra_condition;?>' />
<input type="hidden" id="first_local" name="first_local" value='0' />
<div class="gradient_bg search_results_wrapper" id="j_window">
    <div>
        <div class="main_tab h30 center search_results_choose" >
            <p class="main_tab_choose flexgrow search_results_sort"  sort-down='price_up'  sort-up='price_down' style="width: 33.75px;">
                价格
                <em class="h26"></em>
                <span class="shadow_b"></span>
            </p>
            <span class="main_tab_line"></span> 
            <p class="main_tab_choose flexgrow search_results_sort"  sort-down='comment_score'  sort-up='comment_score'>
                评分
                <span class="shadow_b"></span>
            </p>
            <span class="main_tab_line"></span>
            <p class="main_tab_choose flexgrow search_results_sort" id="j-distance"  sort-down='distance'  sort-up='distance'>
                距离
                <span class="shadow_b"></span>
            </p>
            <span class="main_tab_line"></span> 
            <p id="j_screen" class="main_tab_choose flexgrow" url="<?php echo Hotel_base::inst()->get_url("AJAX_CITY_FILTER")?>">筛选 <span class="iconfont color2">&#xE014;</span></p>
            <span class="main_tab_line"></span>
            <div class="main_tab_choose flexgrow" id="checkdate">
                <div class="inblock color3 h24 pad_r20">
                    <div><span class="color3 h24">入住</span> <span class="color2 checkin h28"></span></div>
                    <div><span class="color3 h24">离店</span> <span class="color2 checkout h28"></span></div>   
                </div>
                <div class="main_tab_xiala iconfont color2">&#xE014;</div> 
            </div>
        </div>
    </div>
    <div class="results_wrapper loading_wrap hotel_list">
        
    </div>
    <div class="blankpage" style="display:none;">
        <p class="iconfont">&#xe055;</p>
        <p class="color3 center h28"><a href="<?php echo $index_url;?>">暂无酒店~</a></p>
    </div>
<?php include 'footer.php' ?>
</div>
<section class="city_wrapper bg_090909" style="display:none;" id="serach_whole">
    <div class="h20 layer_bg whole_eject_content pad_t80 pad_b40 pad_lr30 border_radius scroll">
        <!-- <em id="j_close" class="close iconfont color6">&#xE000;</em> -->
        <div class="webkitbox bd_bottom pad_b30 pad_l40">
            <em class="h30 color2 iconfont mar_r20 city_search_ico">&#xE006;</em> 
            <input id="city_search_content" class="h30 flexgrow color1" type="text" placeholder="搜索区域/地址/酒店名/路名等">
            <em id="city_search_clear" class="iconfont h18 mar_l20" style="display:none;">&#xE000;</em>
        </div>
        <div id="search_region">
            
        </div>
        <div id="city_search_result" style="display:none;" class="city_content mar_t60">

        </div>
        <div id="city_no_search_result"  style="display:none;" class="city_content">
            <div class="center city_no_search_word color3">
                <em class="iconfont inblock" style="font-size:32px;">&#xE006;</em>
                <p class="h24 mar_b10">没有找到搜索结果~</p>
                <p class="h24">换个条件试试</p>
            </div>
<!--             <div class="mar_t60">
                <p class="h24 color3 mar_b40">为你推荐</p>
                <div class="color3 h30 city_result_rows"><em class="iconfont h26 mar_r30">&#xE026;</em><p>火车站</p></div>
                <div class="color3 h30 city_result_rows"><em class="iconfont h26 mar_r30">&#xE026;</em><p>广州北火车站店</p></div>
                <div class="color3 h30 city_result_rows"><em class="iconfont h26 mar_r30">&#xE026;</em><p>广州南火车站店</p></div>
            </div> -->
        </div>
    </div>
</section>
</body>
<?php echo referurl('js','search_results.js',1,$media_path) ?>
<?php echo referurl('js','search_public.js',1,$media_path) ?>
</html>