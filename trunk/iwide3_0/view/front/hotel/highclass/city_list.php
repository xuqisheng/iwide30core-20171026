
<?php include 'header.php' ?>
<?php echo referurl('css','swiper.css',1,$media_path) ?>
<?php echo referurl('js','swiper.js',1,$media_path) ?>
<div class="gradient_bg" style="padding: 0 1.375rem 0 0.937rem;">
    <div class="city_search webkitbox bd_bottom color3">
    	<em class="h34 color2 iconfont mar_r20 inblock" style="margin-top: 5px;">&#xE006;</em> 
    	<input id="city_search_content" class="h38 flexgrow color1" type="text" placeholder="搜索城市 / 关键字 / 位置">
        <em id="city_search_clear" class="iconfont h18 mar_l20" style="display:none;">&#xE000;</em>
    </div>
    <div id="city_content_wrapper">
        <div class="city_content">
            <a href="/index.php/hotel/hotel/search_results">
            	<div class="webkitbox bd_bottom nearby_sarch_wrap color3">
        	    	<em class="h40 main_color1 iconfont city_light_ico mar_r10 inblock" style="width:1.1rem;">&#xE025;</em> 
        	    	<span class="h32 main_color1 mar_r20">附近</span>
        	    	<input id="nearby_sarch" class="h24 flexgrow color3" type="text" value="附近有什么，去看看" disabled>
        	    </div>
            </a>
            <div class="mar_t80">
                <p class="h24 color3 mar_b40">历史搜索</p>
                <div class="city_search_history h30 color3">
                    <div>北京</div>
                    <div>上海</div>
                    <div>乌鲁木齐</div>
                    <div>齐齐哈尔</div>
                </div>
            </div>
            <div class="mar_t80">
                <p class="h24 color3 mar_b40">热门搜索</p>
                <div class="city_search_hot h32 color1 swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/beijing.jpg" alt="">
                            <p>北京</p>
                        </div>
                        <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/shanghai.jpg" alt="">
                            <p>上海</p>
                        </div>
                        <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/guangzhou.jpg" alt="">
                            <p>广州</p>
                        </div>
                         <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/chengdou.jpg" alt="">
                            <p>成都</p>
                        </div>
                        <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/beijing.jpg" alt="">
                            <p>北京</p>
                        </div>
                        <div class="swiper-slide">
                            <img src="/public/hotel/bigger/images/shanghai.jpg" alt="">
                            <p>上海</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="city_list_wrap mar_t40">
                <div data-letter="A" class="color2 h28">
                    <p class="">A</p>
                    <p>奥克兰</p>
                    <p>奥马鲁</p>
                    <p>澳大利亚</p>
                    <p>阿根廷</p>
                </div> 
                <div data-letter="B" class="color2 h28">
                    <p class="">B</p>
                    <p>北京</p>  
                    <p>北京</p>
                    <p>北京</p>
                    <p>北京</p>
                    <p>北京</p>
                    <p>北京</p>
                    <p>北京</p>
                </div>      
                <div data-letter="C" class="color2 h28">
                    <p class="">C</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                    <p>成都</p>
                </div>
                <div data-letter="D" class="color2 h28">
                    <p class="">D</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                    <p>丹东</p>
                </div>
                <div data-letter="S" class="color2 h28">
                    <p class="">S</p>
                    <p>上海</p>
                </div>
                <div data-letter="G" class="color2 h28">
                    <p class="">G</p>
                    <p>广州</p> 
                </div>
            </div>
        </div>
        <div class="city_letter_list h22 color2">
            <p>A</p>
            <p>B</p>
            <p>C</p>
            <p>D</p>
            <p>E</p>
            <p>F</p>
            <p>G</p>
            <p>H</p>
            <p>I</p>
            <p>J</p>
            <p>K</p>
            <p>L</p>
            <p>M</p>
            <p>N</p>
            <p>O</p>
            <p>P</p>
            <p>Q</p>
            <p>R</p>
            <p>S</p>
            <p>T</p>
            <p>U</p>
            <p>V</p>
            <p>W</p>
            <p>X</p>
            <p>Y</p>
            <p>Z</p>
        </div>
    </div>
    <div id="city_search_result" style="display:none;" class="city_content">
        <div class="mar_t60">
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p><a class="color3" href="/index.php/hotel/hotel/search_results">速8酒店广州<span class="color1">火车站</span>店</a></p></div>
        </div>
        <div class="mar_t60">
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE029;</em><p><span class="color1">火车站</span>市站商圈</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE029;</em><p>广州<span class="color1">火车站</span>商圈</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE029;</em><p>成都<span class="color1">火车站</span>商圈</p></div>
        </div>
        <div class="mar_t60">
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE027;</em><p><span class="color1">火车站</span>市站速7店</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE027;</em><p>希尔顿<span class="color1">火车站</span>店</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE027;</em><p>速8<span class="color1">火车站</span>店</p></div>
        </div>
        <div class="mar_t60">
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE024;</em><p><span class="color1">火车站</span></p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE024;</em><p>广州<span class="color1">北火车站</span>店</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE024;</em><p>广州<span class="color1">南火车站</span>店</p></div>
        </div>
    </div>
    <div id="city_no_search_result" class="city_search_result"  style="display:none;" class="city_content">
        <div class="center city_no_search_word color3">
            <em class="iconfont h60 mar_b40 inblock">&#xE006;</em>
            <p class="h24 mar_b10">没有找到搜索结果~</p>
            <p class="h24">换个条件试试</p>
        </div>
        <div class="mar_t60">
            <p class="h24 color3 mar_b40">为你推荐</p>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>火车站</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>广州北火车站店</p></div>
            <div class="color3 h32 city_result_rows"><em class="iconfont color3 h26 mar_r30">&#xE026;</em><p>广州南火车站店</p></div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $j_input = $("#city_search_content");
    $j_input.on("change, keyup",function(){
        $("#city_search_clear").show();
        if($j_input.val() == "火车站"){
            $("#city_content_wrapper").hide();
            $("#city_search_result").show();
            $("#city_no_search_result").hide();
        }else if($j_input.val() == ""){
            $("#city_content_wrapper").show();
            $("#city_search_result").hide();
            $("#city_no_search_result").hide();
            $("#city_search_clear").hide();
        }else{
            $("#city_content_wrapper").hide();
            $("#city_search_result").hide();
            $("#city_no_search_result").show();
        }
    })
    $("#city_search_clear").on("click",function(){
        $j_input.val("");
        $("#city_content_wrapper").show();
        $("#city_search_result").hide();
        $("#city_no_search_result").hide();
        $("#city_search_clear").hide();
    });
      var swiper = new Swiper('.city_search_hot', {
           slidesPerView: 'auto'
           // slidesPerView: 3.5,
        // paginationClickable: true,
        // centeredSlides:true,
    });
    $(".city_letter_list").on("click","p",function(){
        var _letter = $(this).html();
        $(".city_list_wrap").children().each(function(){
            if(_letter == $(this).attr("data-letter")){
                $('body').scrollTop($(this).offset().top);
            }
        })
    })
</script>
</html>