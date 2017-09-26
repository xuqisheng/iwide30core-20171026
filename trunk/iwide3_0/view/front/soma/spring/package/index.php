<body>
<script src="<?php echo get_cdn_url('public/soma/scripts/jquery.touchwipe.min.js');?>"></script>
<div class="pageloading"><p class="isload">正在加载</p></div>
<script>
    var package_obj= {
		'appId': '<?php echo $wx_config["appId"]?>',
        'timestamp': <?php echo $wx_config["timestamp"]?>,
        'nonceStr': '<?php echo $wx_config["nonceStr"]?>',
        'signature': '<?php echo $wx_config["signature"]?>'
    }
    /*下列字符不能删除，用作替换之用*/
    //[<sign_update_code>]
    wx.config({
        debug: false,
        appId: package_obj.appId,
        timestamp: package_obj.timestamp,
        nonceStr: package_obj.nonceStr,
        signature: package_obj.signature, 
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
    });
    wx.ready(function(){

        <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

        <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

        <?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo $js_share_config["title"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {},
            cancel: function () {}
        });
        <?php endif; ?>

        // wx.getLocation({
        //     success: function (res) {
        //         get_package_nearby(res.latitude,res.longitude);
        //     },
        //     cancel: function (res) {
        //         $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置');
        //     }
        // });
    });
</script>
<?php if( $multi_hotel || $multi_city ): ?>
    <?php if( $is_show_navigation == Soma_base::STATUS_TRUE ):?>
        <div class="tmp" style="padding-top:39px"></div>
        <div class="tab_menus bg_fff to_fixed border_bottom">
            <div class="cur hot menu_item">热销</div>
            <div class="menu_item" id="nearby" is_true="">附近</div>
            <div class="s_select">定位</div>
        </div>
		<script>
        $(function(){var _top=$('.tab_menus').offset().top;});
        </script>
    <?php endif;?>
<?php endif; ?>
    <header class="headers border_top" >
        <div class="headerslide"><?php
            if(!empty($advs)){  foreach($advs as $k => $v){ ?>
                <a class="slideson ui_img_auto_cut" href="<?php
                if( $v->product_id ) echo $advs_url.$v->product_id; else echo $v->link;?>">
                    <img src="<?php echo $v->logo;?>" />
                    <div class="bn_title"><p class="txtclip"><?php echo $v->name;?></p></div>
                </a>
            <?php } } ?>
        </div>
    </header>

    <div class="step_style j_class_list bg_fcf">
        <?php foreach($categories as $k=>$v){?>
            <a href="<?php echo Soma_const_url::inst()->get_category(array('catid'=>$v['cat_id'],'id'=>$inter_id));?>" class="item">
                <img src="<?php echo $v['cat_img'];?>" data-original="<?php echo $v['cat_img'];?>" />
                <p class="c_b5b l_h_15"><?php echo $v['cat_name'];?></p>
            </a>
        <?php } ?>
    </div>

    <div class="tp_list" id="tp_list">
        <?php foreach($products as $k=>$v){?>
    	<a href="<?php echo Soma_const_url::inst()->get_package_detail(array('pid'=>$v['product_id'],'id'=>$inter_id) );?>" class="item bg_fff">
            <div class="img">
                <img s="<?php echo get_cdn_url('public/soma/images/default2.jpg'); ?>" src="<?php echo $v['face_img'];?>" />

                <?php if(isset($v['killsec'])){ //有秒杀 ?>
                    <div class="j_label color_main f_s_12">秒杀</div>
                <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                    <div class="j_label color_main f_s_12">拼团</div>
                <?php } elseif(isset($v['auto_rule'])){ //有活动 ?>
                    <div class="j_label color_main f_s_12">满减</div>
                <?php } ?>

                <?php if($v['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?> 
                    <div class="j_label color_main f_s_12">会员</div>
                <?php endif; ?>
                <?php if($v['type'] == $packageModel::PRODUCT_TYPE_POINT): ?> 
                    <div class="j_label color_main f_s_12">积分</div>
                <?php endif; ?>

            </div>
            <p class="h24 color_888"><?php echo $v['name'];?></p>

            <?php if(isset($v['killsec'])){ //有秒杀 ?>
                <p class="item_foot">秒杀价<em>|</em><span class="color_main y"><?php echo $v['killsec']['killsec_price'];?></span></p>
            <?php } elseif(isset($v['groupon'])){ //有拼团 ?>
                <p class="item_foot"><?php echo $v['groupon']['group_count'];?>人团<em>|</em><span class="color_main y"><?php echo $v['groupon']['group_price'];?></span></p>
            <?php } else{ ?>
                
                <?php if($v['type'] == $packageModel::PRODUCT_TYPE_BALANCE): ?>
                    <p class="item_foot"><?php echo $show_name;  ?>价<em>|</em><span class="color_main y"><?php echo $v['price_package']?></span></p>
                <?php elseif($v['type'] == $packageModel::PRODUCT_TYPE_POINT): ?>
                    <p class="item_foot">积分价<em>|</em><span class="color_main y"><?php echo $v['price_package']?></span></p>
                <?php else: ?>
                    <p class="item_foot">惊喜价<em>|</em><span class="color_main y"><?php echo $v['price_package']?></span></p>
                <?php endif; ?>

            <?php } ?>
                
        </a>
        <?php } ?>
    </div>


    <div class="tp_list hide"  id="nearbyBox">
        <div style="text-align: center;padding-top:10%;">努力加载中...</div>
    </div>


    <div class="ui_pull" id="search_pull" style="display:none">
        <div class="search_pull scroll">
            <div class="cur_local bg_fff border" style="display: none"><em class="iconfont">&#xe606;</em>当前城市: <span id="userLocate">定位中..</span></div>
            <div class="city_list bg_fff border">
                <ul>
                    <?php foreach($cities as $city){ ?>
                        <li class="border city-list" ref="<?php echo Soma_const_url::inst()->get_url('*/*/category_list/',array('city'=>$city,'id'=>$this->inter_id));?>"><?php echo $city;?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

<style>body,html{ background:#fff !important; }</style>

</body>
<script> 
	$.fn.imgscroll({
		imgrate : 640/345,
		circlesize  : '7px'
	});

    var productLink = '<?php echo Soma_const_url::inst ()->get_package_detail(array('id'=>$inter_id));?>';
    function get_package_nearby(lat,lng){
        $.ajax({
            dataType: 'json',
            type : "POST",  //提交方式
            url : "<?php echo Soma_const_url::inst()->get_package_nearby_ajax();?>",//路径
            data : {
                lat: lat, //测试：23.136202
                lng: lng //测试：113.3291
            },//数据，这里使用的是Json格式进行传输
            success : function(data) {//返回数据根据结果进行相应的处理
                fillProductContent(data,lat,lng); //数据填充
            }
        });
    }

    function getJsonObjLength(jsonObj) {
        var Length = 0;
        for (var item in jsonObj) {
            Length++;
        }
        return Length;
    }

    function fillProductContent(data,lat,lng){
        var str='';
        var location;
        for(var item in data){
            if(data[item].distance == null){
                location = data[item].hotel_name;
            }else{
                <?php //location = data[item].distance;  /*取消php计算*/?>
                location = getDistance(lat,lng,data[item].latitude,data[item].longitude);
                if(parseInt(location) > 1000){
                    location = (parseInt(location)/1000).toFixed(1) + "km";
                }else{
                    location = parseInt(location) + "m";
                }
            }

            if(data[item].killsec != undefined){
                str +=
                '<a href="' + productLink + '&pid=' + data[item].product_id + '" class="item bg_fff">' +
                    '<div class="img">' +
                    '<img src="' +data[item].face_img + '">' +
                    '<div class="j_label color_main f_s_12">秒杀</div>'+
                    '</div>' +
                    '<p class="h3 color_888">' + data[item].name + '</p>' +
                    '<p class="item_foot">秒杀价<em>|</em><span class="color_main y">' + data[item].killsec.killsec_price +'</span></p>' +
                '</a>';
            }else if(data[item].groupon != undefined){
                str +=
                    '<a href="' + productLink + '&pid=' + data[item].product_id + '" class="item bg_fff">' +
                        '<div class="img">' +
                        '<img src="' +data[item].face_img + '">' +
                        '<div class="j_label color_main f_s_12">拼团</div>'+
                        '</div>' +
                        '<p class="h3 color_888">' + data[item].name + '</p>' +
                        '<p class="item_foot">' + data[item].groupon.group_count +'人团<em>|</em><span class="color_main y">' + data[item].groupon.group_price +'</span></p>' +
                        '</a>';
            }else if(data[item].auto_rule != undefined){
                str +=
                    '<a href="' + productLink + '&pid=' + data[item].product_id + '" class="item bg_fff">' +
                        '<div class="img">' +
                        '<img src="' +data[item].face_img + '">' +
                        '<div class="j_label color_main f_s_12">满减</div>'+
                        '</div>' +
                        '<p class="h3 color_888">' + data[item].name + '</p>' +
                        '<p class="item_foot">低于<em>|</em><span class="color_main y">' + data[item].price_package +'</span></p>' +
                        '</a>';
            }else{
                str +=
                    '<a href="' + productLink + '&pid=' + data[item].product_id + '" class="item bg_fff">' +
                        '<div class="img">' +
                        '<img src="' +data[item].face_img + '">' +
                        '</div>' +
                        '<p class="h3 color_888">' + data[item].name + '</p>' +
                        '<p class="item_foot">惊喜价<em>|</em><span class="color_main y">' + data[item].price_package +'</span></p>' +
                        '</a>';
            }
        }
        $('#nearbyBox').html(str);
    }

    $(function(){
        $('#search_pull').click(toclose);
        $('.search_pull').click(function(e){e.stopPropagation();});
        $('.s_select').click(function(){
            toshow($('#search_pull'));
        })
        $('.s_input').click(function(){
            $('input',this).focus();
        })
        $('#search_pull li').click(function(){
            console.log($(this).attr('ref'));
//            alert('abc');
//            $('.s_select').html($(this).html());
            location.href = $(this).attr('ref');
            //toclose();
        })
        $('.cur_local').click(function(){
            $('.s_select').html($('span',this).html());
            toclose();
        })
        $('.tab_menus .menu_item').click(function(){
            if($(this).hasClass('cur')) return;
            $(this).addClass('cur').siblings().removeClass('cur');
            $('.tp_list').toggleClass('hide');
        })
		$('.img').each(function(index, element) {
            $(this).height($(this).width());
        });
        
        $('#nearby').click(function(){
            var obj = $(this);
            var is_true = obj.attr('is_true');
            if( is_true != <?php echo Soma_base::STATUS_TRUE;?> ){
             wx.getLocation({
                     success: function (res) {
                         obj.attr('is_true',<?php echo Soma_base::STATUS_TRUE;?>);
                         get_package_nearby(res.latitude,res.longitude);
                     },
                     cancel: function (res) {
                          $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置',null,null,'好的','取消');
                     }
                });
            }
        })
    })

    //距离计算

    function toRad(d) {  return d * Math.PI / 180; }
    function getDistance(lat1, lng1, lat2, lng2) { //#lat为纬度, lng为经度, 一定不要弄错
        var dis = 0;
        var radLat1 = toRad(lat1);
        var radLat2 = toRad(lat2);
        var deltaLat = radLat1 - radLat2;
        var deltaLng = toRad(lng1) - toRad(lng2);
        var dis = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(deltaLat / 2), 2) + Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(deltaLng / 2), 2)));
        return dis * 6378137;
    }
    function countdownTime(Time){
        var endTime=new Date(Time);
        var nowTime=new Date();
        var s_time=endTime-nowTime;
        var end_date=parseInt((s_time/1000)/86400);
        var end_hour=parseInt((s_time/1000)%86400/3600);
        var end_minute=parseInt((s_time/1000)%3600/60);
        var end_second=parseInt((s_time/1000)%60);
        return {
            j_date : end_date,
            j_hour : end_hour,
            j_minute : end_minute,
            j_second : end_second
        }
    }

    var x ;
    function fillText(j_Obj,oTime){
        var timeObj = countdownTime(oTime);
        j_Obj.find('.j_dat').html('倒计时：'+timeObj.j_date+'天');
        j_Obj.find('.j_time').html(timeObj.j_hour+'小时'+timeObj.j_minute+'分');
        j_Obj.time=setInterval(function(){
            timeObj = countdownTime(oTime);
            x = timeObj;
            if( parseInt(timeObj.j_date) <= 0 && parseInt(timeObj.j_hour) <=0 && parseInt(timeObj.j_minute) <=0 && parseInt(timeObj.j_second) <=0){
                $(j_Obj).html('秒杀进行中');
                clearInterval(j_Obj.time);
            }
            if( parseInt(timeObj.j_date) > 0){
                j_Obj.find('.j_dat').html('倒计时：'+timeObj.j_date+'天');
            }else{
                j_Obj.find('.j_dat').html('倒计时：');
            }
            if(parseInt(timeObj.j_date) <= 0 && parseInt(timeObj.j_hour) <= 0){
                j_Obj.find('.j_time').html(timeObj.j_minute+'分' + timeObj.j_second + '秒');
            }else{
                j_Obj.find('.j_time').html(timeObj.j_hour+'小时'+timeObj.j_minute+'分');
            }
        },1000)
    }
    var $j_dowTime=$('.j_dowTime');
    if($j_dowTime.length>0){
        for(var i=0;i<$j_dowTime.length;i++){
            var $j_dt_clas=$j_dowTime.eq(i);
            var time_txt=$j_dowTime.eq(i).attr('killsec-time');
            fillText($j_dt_clas,time_txt);
        }
    }

    //异步查询分销员号
    function get_saler(){
        var saler = "<?php echo $this->input->get('saler');?>";
        var url = "<?php echo Soma_const_url::inst()->get_url('*/package/get_saler_id_by_ajax',array( 'id'=> $this->inter_id) );?>";
        $.ajax({
            url: url,
            type: 'post',
            data: {saler:saler},
            dataType: 'json',
            success:function( json ){
                if( json.status == 1 ){
                    if(json.jump_url== 1){
                    	window.location="<?php 
                        	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                        	    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                        	echo "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    	?>&saler="+ json.sid;
                    }
                    if(json.show_button== 1){
                    	//alert( json.sid + json.name );
                        $("#distribute_id").html(json.sid);
                        $("#distribute_name").html(json.name);
                        $("#distribute_url").attr('href',json.url);
                        $(".distribute_btn").show();
                    }
                }
            }
        });
    }
    // get_saler();
var hideload = function(){
	$('.ui_loadmore').remove();	
}
var showload =function(str){
	hideload();
	if(str==undefined)
	var tmp = "<div class='center ui_loadmore' style='padding:20px;'><em class='ui_loading'></em></div>";
	else
	var tmp = "<div class='center ui_loadmore color_888 h20' style='padding:20px;'>"+str+"</div>";
	$('body').append(tmp);
}  
var  startX,startY,isend=false,isload=false,pageIndex=0;
$(document).bind('touchstart',function(e){
    startX = e.originalEvent.changedTouches[0].pageX,
    startY = e.originalEvent.changedTouches[0].pageY;
});
$(document).on('touchmove',function(e){
    endX = e.originalEvent.changedTouches[0].pageX,
    endY = e.originalEvent.changedTouches[0].pageY;
    //获取滑动距离
    distanceX = endX-startX;
    distanceY = endY-startY;
    //判断滑动方向
	if(distanceY<0&&($(document).height()-$(window).height())*0.4<=$(document).scrollTop()){
		if (isend){
			showload('客官！到底啦~');
			return;
		}
		if (!isload){
			e.preventDefault();
			isload  = true;
			$.ajax({
				dataType: 'json',
				type: 'POST',
				url: '<?php echo Soma_const_url::inst()->get_url('*/package/ajax_get_product_list',array( 'id'=> $this->inter_id) );?>',
				data: {
					p: pageIndex
				},
				success: function(data){
					//console.log(data);
					if(data.status!=undefined&&data.status==1){
						var str = '';
						for(var n in  data.data){
							str +='<a href="'+productLink+'&pid='+data.data[n].product_id+'" class="item bg_fff">';
							str +='<div class="img"><img src="'+data.data[n].face_img+'"/>';
							if(data.data[n].killsec!=undefined)//有秒杀 
							str +='<div class="j_label color_main h24">秒杀</div>';
							else if(data.data[n].groupon!=undefined)//有拼团 
							str +='<div class="j_label color_main h24">拼团</div>';
							else if(data.data[n].auto_rule!=undefined)//有活动
							str +='<div class="j_label color_main h24">满减</div>';
							if(data.data[n].type== '<?php echo $packageModel::PRODUCT_TYPE_BALANCE ?>')
							str +='<div class="j_label color_main h24">会员</div>';
							if(data.data[n].type== '<?php echo $packageModel::PRODUCT_TYPE_POINT ?>')
							str +='<div class="j_label color_main h24">积分</div>';
							str +='</div><p class="h24 color_888">'+data.data[n].name+'</p>';
							str +='<p class="item_foot">'
							if(data.data[n].killsec!=undefined)//有秒杀 
							str +='秒杀价<em>|</em><span class="color_main y">'+data.data[n].killsec.killsec_price+'</span>';
							else if(data.data[n].groupon!=undefined)//有拼团 
							str +=data.data[n].groupon.group_count+'人团<em>|</em><span class="color_main y">'+data.data[n].groupon.group_price+'</span>'
							else{
								if(data.data[n].type== '<?php echo $packageModel::PRODUCT_TYPE_BALANCE ?>')
								str +='<?php echo $show_name;  ?>价';
								else if(data.data[n].type== '<?php echo $packageModel::PRODUCT_TYPE_POINT ?>')
								str +='积分价';
								else
								str +='惊喜价';
								str +='<em>|</em><span class="color_main y">'+data.data[n].price_package+'</span>';
							}               
							str +='</p></a>';
						}
						$('#tp_list').append(str);
						pageIndex++;
					}else{
						isend = true;
					}
				},
				complete: function(data){
					hideload();
					isload=false;
				}
			});
		}
		else{
			showload();
		}
	}
})
</script>
</html>
