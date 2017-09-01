<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',3,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php echo referurl('css','room_list.css',1,$media_path) ?>
<style>
.checkin .date:after{content:"入住"}
.checkout .date:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.ui_price.qi:after{ content:"起";}
</style>

<style>
/* 要求修改部分 
.room_list .room_img{ height:8.5em; overflow:hidden}
.room_list_many .item_right{ top:0; padding-top:1.5%;}
.room_list_many .showprice .ui_price{padding-right:0; display:block;}
.showprice p{ background:#f99e12; color:#fff; padding:0.2em 2%; display:inline-block; border-radius:0.3em;}
.showprice p span{ font-size:2em; vertical-align:middle}
.showprice p .iconfont{ font-size:1em; vertical-align:middle}
/* end */
</style>
<form id="book_f" method="post" action="bookroom">
<input type="hidden" id="startdate" name="startdate" value="<?php echo date('Y/m/d',strtotime($startdate));?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo date('Y/m/d',strtotime($enddate));?>" />
<input type="hidden" id="nums" name="nums" value="1" />
<input type="hidden" id="price_codes" name="price_codes" value="0" />
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel['hotel_id']?>" />
<input type="hidden" id="datas" name="datas" value="" />
<input type="hidden" id="protrol_code" name="protrol_code" value="" />
<input type="hidden" id="price_type" name="price_type" value="" />
<input type="hidden" id="<?php echo $csrf_token;?>" name="<?php echo $csrf_token;?>" value="<?php echo $csrf_value;?>" />
</form>
<header class="headers"> 
    <div class="headerslide">
    	<?php if(!empty($hotel['imgs']['hotel_lightbox'])) foreach($hotel['imgs']['hotel_lightbox'] as $hl){?>
    	<a class="slideson ui_img_auto_cut" <?php if(!empty($gallery_count)){?> href="hotel_photo?id=<?php echo $inter_id;?>&h=<?php echo $hotel['hotel_id']?>"<?php }?>>
    		<img src="<?php echo $hl['image_url'];?>" alt=<?php echo $hl['info'];?> />
         </a>
    	<?php }?>
    </div>
    <?php if(!empty($gallery_count)){?>
    <div class="allimg">共<?php echo $gallery_count;?>张</div>
    <?php }?>
    <div class="blackbg"></div>
    <div class="hotelname txtclip"><?php echo $hotel['name'];?></div>
    <div class="addlike <?php if(!empty($collect_id)){?>islike<?php }?>" mid="<?php echo $collect_id;?>"><span><?php if(!empty($collect_id)){?>已收藏<?php } else{?>收藏<?php }?></span></div>
</header>
<div class="inrto_list">
	<a href='javascript:void(0)' onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $hotel['address'];?>')" class="ui_btn_block"><span class="txtclip"><?php echo $hotel['address'];?></span><span class="ui_color">地图</span></a>
	<a href="hotel_detail?id=<?php echo $inter_id;?>&h=<?php echo $hotel['hotel_id'];?>" class="ui_btn_block sever">
		<?php if(!empty($hotel['imgs']['hotel_service'])) foreach($hotel['imgs']['hotel_service'] as $hs){?>
            <em class="iconfont"><?php echo $hs['image_url'];?></em>
        <?php }?>
        <em class="iconfont">&nbsp;</em>
        <span class="ui_color">酒店详情</span>
    </a>
	<a href="hotel_comment?id=<?php echo $inter_id;?>&h=<?php echo $hotel['hotel_id'];?>" class="ui_btn_block"><span><?php echo $t_t['comment_count'];?>条评论/<?php echo $t_t['comment_score'];?>分</span><span class="ui_color">评论详情</span></a>
</div>

<div class="list_head">
    <div class="ui_btn_block checkdate" id='checkdate'>
        <span class="checkin" id='checkin'><span class="date"><?php echo date("m月d日",strtotime($startdate));?></span></span>&nbsp;&nbsp;
        <span class="checkout" id='checkout'><span class="date"><?php echo date("m月d日",strtotime($enddate));?></span></span>
        <span class="checkin_time ui_color"><?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?></span>
    </div>
    <div class="guest ui_color txtclip">
        <span>商务旅客</span> 
        <em class="iconfont">&#x2d;</em>
    </div>
</div>
<!--- 以上相同部分结束 --->
<!-- 多房价 -->
<div class="room_list room_list_many" id="room_list">
<?php if(!empty($rooms)) foreach($rooms as $r){?>
	<div class="item" onClick="silde_down(this)">
        <div class="item_left" rid="<?php echo $r['room_info']['room_id']; ?>">
    		<div class="room_img" onclick="show_room_detail(this,event)"><img class="lazy" src="<?php echo referurl('img','default2.jpg',3,$media_path) ?>"  data-original="<?php echo $r['room_info']['room_img']?>"/></div>
        	<div class="room_name" onclick="show_room_detail(this,event)"><?php echo $r['room_info']['name'];?></div>
            <div class="sever" onclick="show_room_detail(this,event)">
           <!-- <?php if(!empty($r['room_info']['imgs']['hotel_room_service'])){foreach($r['room_info']['imgs']['hotel_room_service'] as $rs){echo $rs['info'].' '; }}?> --> 
            <?php echo $r['room_info']['sub_des']; ?>&nbsp;
            </div>
            <?php if(!empty($icons_set['coupon_back'])){?><div class="backvote">入住返券</div><?php }?>
        </div>
        
        <?php if(!empty($r['top_price'])){?>
        	<div class="full_room ag_price"><span class="topright"></span><span><?php echo current($r['top_price']);?></span></div>
        <?php }?>
        <?php if(!empty($r['state_info'])){?>
        <div class="full_room" style="display:none"><span class="topright"></span><span>满</span></div>        
       <!--- <div class="isfull item_right showprice">  <!-- 满房需要加isfull 变灰色字体-->
        <div class="item_right showprice">
        	<div class="ui_price"><b><?php echo $r['lowest']; ?></b><tt class="ui_color_gray">起</tt></div>
            <em class="iconfont ui_color">&#x33;</em>
        </div>
        <?php }else {?>
        <div class="item_right" >
        	<div class="no_price ui_color_gray">不可用</div>
        </div>
        <?php }?>
        <div class="item_foot" style="display:none;">
        	<?php if(!empty($r['show_info'])){ ?>
        	<div class="viplist" >
        	<?php foreach($r['show_info'] as $rsi) {?>
                <div class="vip">
                    <p><tt><?php echo $rsi['price_name'];?></tt></p>
                    <p><span class="ui_price"><?php echo $rsi['avg_price'];?></span></p>
                    <p><tt><?php echo $rsi['related_des'];?></tt></p>
                </div>
               <?php }?>
            </div>
               <?php  }?>
            <div class="room_book">
            <?php if(!empty($r['state_info'])){ foreach($r['state_info'] as $si){?>
            	<div class="pay_way">
                	<div class="pay_name"><?php echo $si['price_name'];?></div>
                    <div class="sever"><?php echo $si['des'];?>&nbsp;</div>
                    <div class="item_right">
                        <div class="ui_price"><b><?php echo $si['avg_price'];?></b></div>
                        <?php if($si['book_status']=='available'){?>
                        <span onClick="pay(this,event)"
                        <?php if (isset($si['condition']['pre_pay'])&&$si['condition']['pre_pay']==1) echo 'class="pre_pay"';else echo 'class="now_pay"'; ?> 
                        room_id="<?php echo $r['room_info']['room_id'];?>" price_code="<?php echo $si['price_code'];?>" price_type="<?php echo $si['price_type'];?>"><p>订</p>
                        <?php if (isset($si['condition']['pre_pay'])&&$si['condition']['pre_pay']==1) echo '<p>预付</p>'; ?>
                        </span>
                        <?php } else{?>
                        <span class="no_pay"><p>满</p></span>
                        <?php }?>
                    </div>
                </div> 
            <?php }}?>
            </div>
            <!--room_book --->
        </div><!--item_foot --->
    </div><!--item --->
   <?php } ?>
</div><!--room_list --->

<!--  以下为相同部分 -->
<?php if(!empty( $hotel['book_policy'])){?>
<div class="hotel_rule">
	<div class="title">酒店政策</div>
	<div class="content">
    	<?php echo $hotel['book_policy'];?>
    </div>
</div>
<?php }?>
<?php if(!empty($foot_ads)){ ?>
 <div class="vote_spread">
	<div class="title"><?php echo $foot_ads['title']?></div>
	<?php foreach($foot_ads['ads'] as $fad){ foreach($fad as $fa){?>
	<a href="<?php echo $fa['ad_link'];?>" class="item" style="height:16em;margin-bottom: 3%;">
	<img src="<?php echo $fa['ad_img'];?>" info="<?php echo $fa['ad_title'];?>"/></a>
	<?php }}?>
</div>  
<?php }?>


<div class="ui_pull guest_pull" style=" display:none;">
    <div class="pull_box">
        <div class="ui_color pull_title">商务旅客</div>
        <ul>
            <li><input type="text" placeholder="输入客户协议代码获取协议价"></li>
        </ul>
        <div class="close"><em class="iconfont">&#x27;</em><p>取消</p></div>
        <div class="sure"><em class="iconfont ui_color">&#x26;</em><p>确认</p></div>
    </div>
</div>
<div class="ui_pull detail_pull" style=" display:none;">
	<div class="pull_box">
    	<div class="pull_loading"><p>正在加载</p></div>
        <div class="ui_color pull_title"></div>
        <div class="room_img pullscroll"></div>
        <div class="scroll_content">
            <div class="sever" id="detail_service"></div>
            <div class="content"></div>
        </div>
    	<div class="close"><em class="iconfont">&#x27;</em><p>关闭</p></div>
    </div>
</div>
</body>
<script>
var silde_down=function(_this){
	_this=$(_this).find('.item_right');
	_this.find('em').toggleClass('rotate').toggleClass('torotate');
	_this.siblings('.item_foot').stop().slideToggle();
}
$('#startdate').val("<?php echo date('Y/m/d',strtotime($startdate));?>");
$('#enddate').val("<?php echo date('Y/m/d',strtotime($enddate));?>");
var pay=function(_this,event){
	room_id=$(_this).attr('room_id');
	if(room_id!=undefined){
		rooms={};
		price_codes={};
		price_type={};
		rooms[room_id] = $('#nums').val();
		price_codes[room_id] = $(_this).attr('price_code');
		price_type[$(_this).attr('price_type')] = 1;
		$('#datas').val(JSON.stringify(rooms));
		$('#price_type').val(JSON.stringify(price_type));
		$('#price_codes').val(JSON.stringify(price_codes));
		$('#book_f').submit();
	}
	event.stopPropagation();
}
$(function(){
	$.fn.imgscroll({
		imgrate			 : 640/290, 
	    partent_div      : 'headers',
	});
	$('.room_list .item').eq(0).trigger('click');
	$('.guest').click(function(){toshow($('.guest_pull'));})
	$('.close').click(toclose);
	$('.guest_pull .sure').click(function(){
		toclose();
		if($('.guest_pull input').val()==''){
			return false;
		}		
		$('#protrol_code').val($('.guest_pull input').val());
		$('.guest').html('<span>'+$('.guest_pull input').val()+'</span>');
		day_rooms($('#startdate').val(),$('#enddate').val());
	});
	var placeholder='';
	$('.guest_pull input').focus(function(){
		placeholder=$(this).attr('placeholder');
		if($(this).val()=='')	$(this).attr('placeholder','');
	})
	$('.guest_pull input').blur(function(){
		$(this).attr('placeholder',placeholder);
	})
	$('.addlike').click(function(){
		var _like=$(this);
		if(_like.hasClass('islike')){
			$.get('/index.php/hotel/hotel/cancel_one_mark?id=<?php echo $inter_id?>',{
				mid:_like.attr('mid')
			},function(data){
				if(data==1){
					_like.removeClass('islike');
					_like.find('span').html('收藏');
				}	
			});
		}
		else{
			$.get('/index.php/hotel/hotel/add_hotel_collection?id=<?php echo $inter_id?>',{
					hid:'<?php echo $hotel['hotel_id'];?>',
					hname:'<?php echo $hotel['name'];?>',
				},function(data){
				if(data>0){
					_like.addClass('islike');
					_like.attr('mid',data);
					_like.find('span').html('已收藏');
				}	
			});
		}
	});	
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	var today=new Date();
	var morrow=new Date((today/1000+86400)*1000);
	var r;
	$('#checkdate').cusCalendar({
		_parent			:'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
		selectedCallBack:function(data){
			$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
			day_rooms($('#startdate').val(),$('#enddate').val());
		}
	});
})
function show_room_detail(obj,event){ 
	$('.pullscroll').html('');
	toshow($('.detail_pull'));
	$('.pull_loading').stop().show();
	var _this=$(obj).parents('.item_left');
	$.post("<?php echo site_url('hotel/hotel/return_room_detail').'?id='.$inter_id; ?>",{
		h:"<?php echo $hotel['hotel_id'];?>",
		r:_this.attr('rid')
	},function(data){
		$('.detail_pull .pull_title').html(data.name);
		var tmphtml='<div class="headerslide">';
		if(data.imgs!=null&&data.imgs.hotel_room_lightbox!=null){
			$.each(data.imgs.hotel_room_lightbox,function(i,n){
				tmphtml+='<span class="slideson ui_img_auto_cut"><img src="'+n.image_url+'" /></span>';
			});
		}
		else  tmphtml+='<span class="slideson ui_img_auto_cut" title=""><img src="'+data.room_img+'" /></span>';
		tmphtml+='</div>';
		$('.detail_pull .room_img').html(tmphtml);
		if(data.imgs!=null&&data.imgs.hotel_room_service!=null){
			temp='';
			$.each(data.imgs.hotel_room_service,function(i,n){
				temp+='<div><em class="iconfont">'+n.image_url+'</em><span>'+n.info+'</span></div>';
			});
			$('#detail_service').html(temp);
		}
		$('.detail_pull .content').html('<p>'+data.book_policy+'</p>');
		$('.pull_loading').stop().hide();
		$.fn.imgscroll({
			imgrate			 : 640/290, 
			partent_div      : 'pullscroll',
		});
	},'json');

	event.stopPropagation();
}
function day_rooms($startdate,$enddate,$price_code){
	$.post('/index.php/hotel/hotel/return_more_room?id=<?php echo $inter_id;?>',{
		h:'<?php echo $hotel['hotel_id']?>',
		start:$startdate,
		end:$enddate,
		protrol_code:$('#protrol_code').val()
	},function(data){
			var temp='';
			$.each(data.rooms,function(i,n){
				temp+='<div class="item" onClick="silde_down(this)"><div class="item_left" rid="'+n.room_info.room_id+'"><div class="room_img"  onclick="show_room_detail(this,event)">';
				temp+='<img src="'+n.room_info.room_img+'"/></div>';
				temp+='<div class="room_name" onclick="show_room_detail(this,event)">'+n.room_info.name+'</div>';
				temp+='<div class="sever" onclick="show_room_detail(this,event)">';
// 				if(n.room_info.imgs!=undefined&&n.room_info.imgs.hotel_room_service!=undefined){
// 					$.each(n.room_info.imgs.hotel_room_service,function(rsi,rsn){
// 						temp+=rsn.info+' ';
// 					});
// 				}
				temp+=n.room_info.sub_des;
				temp+='</div>';
				<?php if(!empty($icons_set['coupon_back'])){?>
				temp+='<div class="backvote">入住返券</div>';
				<?php }?>
				temp+='</div>';

				if(n.top_price!=undefined&&n.top_price!=''){
					$.each(n.top_price,function(it,nt){
						temp+='<div class="full_room ag_price"><span class="topright"></span><span>'+nt+'</span></div>';
						return false;
					});
				}
				
				if(n.state_info!=''){
					temp+='<div class="item_right showprice">';
					temp+='<div class="ui_price"><b>'+n.lowest+'</b><tt class="ui_color_gray">起</tt></div><em class="iconfont ui_color">&#x33;</em></div>';
				}
				else{
					temp+='<div class="item_right" ><div class="no_price ui_color_gray">不可用</div></div>';
				}
				temp+='<div class="item_foot" style="display:none;">';
				if(n.show_info!=''){
					temp+='<div class="viplist">';
					$.each(n.show_info,function(nsi,nsv){
						temp+='<div class="vip"><p><tt>'+nsv.price_name+'</tt></p><p><span class="ui_price">'+nsv.avg_price+'</span></p><p><tt>'+nsv.related_des+'</tt></p></div>';
					})
					temp+='</div>';
				}
				temp+='<div class="room_book">';
				if(n.state_info!=''){
					$.each(n.state_info,function(sk,sn){
						temp+='<div class="pay_way"><div class="pay_name">'+sn.price_name+'</div>';
						temp+='<div class="sever">'+sn.des+'&nbsp;</div>';
						temp+='<div class="item_right"><div class="ui_price"><b>'+sn.avg_price+'</b></div>';
						if(sn.book_status=='available'){
							temp+='<span ';
							if(sn.condition&&sn.condition.pre_pay==1)
								temp+='class="pre_pay"';
							else 
								temp+='class="now_pay"';
							temp+=' onclick="pay(this,event)" room_id="'+n.room_info.room_id+'"';
							temp+=' price_type="'+sn.price_type+'"' 
							temp+=' price_code="'+sn.price_code+'"><p>订</p>';
							if(sn.condition&&sn.condition.pre_pay==1)
								temp+='<p>预付</p>';
							temp+='</span>';
						}
						else
							temp+=' <span class="no_pay"><p>满</p></span>';
						temp+='</div></div>';
					});
				}
				temp+='</div></div></div>';
			});
			$('#room_list').html(temp);
			if(data.errmsg){
				window.setTimeout(function(){
					$.MsgBox.Alert('错误',data.errmsg);
					$('.guest').html('<span>商务旅客</span><em class="iconfont">&#x2d;</em>');
				},300);
			}
		},'json');
}
</script>
</html>
