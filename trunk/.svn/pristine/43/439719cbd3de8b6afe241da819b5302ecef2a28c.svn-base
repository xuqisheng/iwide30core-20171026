<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('js','imgscroll.js',2,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<style>
	.checkin:after{content:"入住"}
	.checkout:after{content:"离店"}
	.checkin_time:before{ content:"共"}
	.checkin_time:after{ content:"晚"}
</style>

<form id="book_f" method="post" action="<?php echo Hotel_base::inst()->get_url("BOOKROOM");?>">
	<input type="hidden" id="startdate" name="startdate" value="<?php echo date('Y/m/d',strtotime($startdate));?>" />
	<input type="hidden" id="enddate" name="enddate" value="<?php echo date('Y/m/d',strtotime($enddate));?>" />
	<input type="hidden" id="nums" name="nums" value="1" />
	<input type="hidden" id="price_codes" name="price_codes" value="0" />
	<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel['hotel_id']?>" />
	<input type="hidden" id="datas" name="datas" value="" />
	<input type="hidden" id="protrol_code" name="protrol_code" value="" />
	<input type="hidden" id="price_type" name="price_type" value="" />
	<input type="hidden" id="<?php echo $csrf_token;?>" name="<?php echo $csrf_token;?>" value="<?php echo $csrf_value;?>" />
	<input type="hidden" id="more_room" name="" value="" />
</form>
<header class="headers">
	<div class="headerslide">
		<?php if(!empty($hotel['imgs']['hotel_lightbox'])) foreach($hotel['imgs']['hotel_lightbox'] as $hl){?>
			<a class="slideson" <?php if(!empty($gallery_count)){?> href="<?php echo Hotel_base::inst()->get_url("HOTEL_PHOTO",array('h'=>$hotel['hotel_id']));?>"<?php }?>>
				<img src="<?php echo $hl['image_url'];?>" alt="<?php echo $hl['info'];?>" />
			</a>
		<?php }?>
	</div>
	<?php if(!empty($gallery_count)){?>
		<div class="allimg h22">共<?php echo $gallery_count;?>张</div>
	<?php }?>
	<div class="blackbg webkitbox justify">
		<div><?php echo $hotel['name'];?></div>
		<div class="iconfont color_main" like='<?php if(!empty($collect_id)){ echo 'on';}?>' mid="<?php echo $collect_id;?>"><?php if(!empty($collect_id)){ echo '&#x29;';} else{ echo '&#x2a;'; }?></div>
	</div>
</header>
<div class="list_style bd_bottom h24">
	<div onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $hotel['address'];?>')" class="webkitbox justify arrow">
		<span><?php echo $hotel['address'];?></span>
		<span class="color_main" style="min-width:3rem;">地图</span>
	</div>
	<a href="<?php echo Hotel_base::inst()->get_url("HOTEL_DETAIL",array('h'=>$hotel['hotel_id']));?>" class="webkitbox justify">
		<span><?php if(!empty($hotel['imgs']['hotel_service'])) foreach($hotel['imgs']['hotel_service'] as $hs){?>
				<em class="iconfont"><?php echo $hs['image_url'];?></em>
			<?php }?>
        </span>
		<span class="color_main" style="min-width:5rem;">酒店详情</span>
	</a>
	<?php if(!empty($t_t)){ ?>
	<a href="<?php echo Hotel_base::inst()->get_url("HOTEL_COMMENT",array('h'=>$hotel['hotel_id']));?>" class="webkitbox justify">
		<span><?php echo $t_t['comment_count'];?>条评论/<?php echo $t_t['comment_score'];?>分</span>
		<span class="color_main">评论详情</span>
	</a>
	<?php } ?>
</div>

<?php if(isset($middle_ads['ads']) && !empty($middle_ads['ads'])) foreach($middle_ads['ads'] as $fad){ foreach($fad as $fa){?>
	<a href="<?php echo Hotel_base::inst()->get_url($fa['ad_link'],array(),TRUE);?>" class="pad3 bg_fff bd h24 martop color_main" style="display:block"><em class="iconfont"><?php if(!strstr($fa['ad_img'],"http")) echo $fa['ad_img'];?></em><?php echo $fa['ad_title'];?></a>
<?php }}?>

<div class="bg_fff webkitbox justify pad3 martop bd h24" id='checkdate'>
	<span class="checkin" id='checkin'></span>
    <span class="checkout" id='checkout'></span>
    <span class="checkin_time color_main linkblock" id="day"></span>
	<div class="guest txtclip color_main">
		<span>商务旅客</span>
		<em class="iconfont color_888">&#x2d;</em>
	</div>
</div>
<!--- 以上相同部分结束 --->
<!-- 多房价 -->
<div class="box_room_list">
    <div class="room_list" id="room_list" >
	<?php if(!empty($rooms)){$i=0;$cur_label_count=0;$label_output_count=0; foreach($rooms as $r){
		if($cur_label_count==0){
			$cur_label_count=isset($r['room_info']['type_label'])?$r['room_info']['type_label']['counts']:0;
		}
	?>
	<?php if($cur_label_count>0){if($label_output_count==0){?>
		<div class="webkitbox justify bd_bottom pad3" onClick="item_silde_down(this,'room_type_label_<?php echo $r['room_info']['type_label']['id']?>')">
    		<div class="h26 border_left_2"><?php echo $r['room_info']['type_label']['label_name'];?></div>
    		<em class="iconfont color_main <?php if ($i<=0){?> rotate torotate<?php }?>">3</em>
    	</div>
    	<div id='room_type_label_<?php echo $r['room_info']['type_label']['id']?>' <?php if ($i>0){?> style="display:none;"<?php }?>>
    	<?php }$label_output_count++;}?>
		<div class="item" onClick="silde_down(this)">
			<div class="webkitbox justify pad3 bg_fff bd_bottom" rid="<?php echo $r['room_info']['room_id']; ?>">
				<div class="img" onclick="show_room_detail(this,event)"><div class="squareimg"><img class="lazy" src="<?php echo referurl('img','default2.jpg',3,$media_path) ?>"  data-original="<?php echo $r['room_info']['room_img']?>"/></div></div>
				<div class="roomname" onclick="show_room_detail(this,event)">
					<p><?php echo $r['room_info']['name'];?></p>
					<p class="color_999"><?php echo $r['room_info']['sub_des']; ?></p>
					<?php if(!empty($icons_set['coupon_back'])){?><p class="btn_void color_key xs h18">入住返券</p><?php }?>
				</div>
				<?php if(!empty($r['state_info'])){?>
					<div class="showprice color_main">
						<?php if( $r['lowest']>=0){?>
							<tt class="h20">¥</tt><tt class="h36"><?php echo $r['lowest']; ?></tt><tt class="h20 color_999">起</tt>
						<?php }?>
						<em class="iconfont">&#x33;</em>
					</div>
				<?php }else {?>
					<div class="showprice color_999">不可用</div>
				<?php }?>
			</div>
			<?php if(!empty($r['state_info'])&&$r['lowest']<0){?>
				<div class="full_room">
					<span class="topright color_key"></span>
					<span>满</span>
				</div>
			<?php } elseif(!empty($r['top_price'])){?>
				<div class="full_room">
					<span class="topright color_key"></span>
					<span><?php echo current($r['top_price']);?></span>
				</div>
			<?php }?>
			<div class="item_foot"  <?php if(!$cur_label_count&&$i>0){?>style="display:none;"<?php }?>>
				<?php if(!empty($r['show_info']) && empty($tc_id)){ ?>
					<div class="bd_bottom webkitbox h18 viplist" >
						<?php foreach($r['show_info'] as $rsi) {?>
							<div>
								<p><?php echo $rsi['price_name'];?></p>
								<p class="y color_main"><?php echo $rsi['avg_price'];?></p>
								<p><?php echo $rsi['related_des'];?></p>
							</div>
						<?php }?>
					</div>
				<?php  }?>
				<div class="bd_bottom list_style" style="background:none">
					<?php if(!empty($r['state_info'])){ foreach($r['state_info'] as $si){?>
						<div class="webkitbox justify pad3">
							<div style="max-width:50%">
                                <p><?php echo $si['price_name'];?></p>
                                <div class="h24 color_main">
                                    <?php if(isset($si['bookpolicy_condition']['breakfast_nums']) && !empty($si['bookpolicy_condition']['breakfast_nums'])){ ?>
                                        <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle"><?php echo $si['bookpolicy_condition']['breakfast_nums'];?></span>
                                    <?php }?>
                                    <?php if (!empty($si['price_tags'])){foreach ($si['price_tags'] as $tag){?>
                                        <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle"><?php echo $tag;?></span>
                                    <?php }}?>
                                    <?php if(isset($si['useable_coupon_favour']) && !empty($si['useable_coupon_favour'])){ ?>
                                        <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle"><?php echo '券可减'.$si['useable_coupon_favour'].'元';?></span>
                                    <?php }?>
                                    <?php if($si['wxpay_favour_sign']==1){?>
                                        <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle"><?php echo '微信支付减'.$si['bookpolicy_condition']['wxpay_favour'].'元';?></span>
                                    <?php }?>
                                </div>
                                        <div class="h24 color_999"><?php echo $si['des'];?></div>
                            </div>
							<div class="webkitright">
                    	<?php if (isset($si['point_exchange'])){?>
                    		<span class=" color_minor"><?php echo $si['point_exchange']!=-1?$si['avg_point'].'积分':"--";?></span>
	                        <?php if($si['book_status']=='available'){?>
	                        	<?php if ($si['point_exchange']!=-1){?>
	                        		<?php if (isset($member->logined)&&$member->logined==0){if (isset($_SERVER['HTTPS'])&&strtolower($_SERVER['HTTPS'])=='on')$scheme='https://';else $scheme='http://';?>
			                        	<a href="<?php echo site_url('membervip/login').'?id='.$inter_id.'&redir='.urlencode($scheme.$_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI']);?>"><span now class="bg_minor">登录</span></a>
			                        <?php }else{?>
	                        		<?php if ($member->bonus>=$si['total_point']){?>
	                        			<span onClick="pay(this,event)" room_id="<?php echo $r['room_info']['room_id'];?>" price_code="<?php echo $si['price_code'];?>" price_type="<?php echo $si['price_type'];?>">
				                        <?php if (isset($si['condition']['pre_pay'])&&$si['condition']['pre_pay']==1){
										 echo '<span pre class="color_minor"><div class="bg_minor">兑</div><div class="h18">预付</div></span>';
				                        } else{ echo '<span now class="bg_minor">兑</span>';}?></span>
	                        		<?php }else{?>
					                        <span now class="bg_999">积分不足</span>
			                        	<?php }?>
	                        		<?php }?>
	                        	<?php }else{?>
	                        		<span now class="bg_999">无法兑换</span>
	                        	<?php }?>
	                        	<?php }else{?>
	                         		<span now class="bg_999">满</span>
	                        	<?php }?>
                    		<?php }else{?>
								<?php if($si['avg_price']>=0){ ?>
									<div class="color_minor">¥<?php echo $si['avg_price'];?>
										<?php if(!empty($si['is_avg'])):?><p class='color_999 h20'>均价</p><?php endif;?>
									</div>
								<?php } ?>
								<?php if($si['book_status']=='available'){?>
									<span onClick="pay(this,event)" room_id="<?php echo $r['room_info']['room_id'];?>" price_code="<?php echo $si['price_code'];?>" price_type="<?php echo $si['price_type'];?>"><?php
										if (isset($si['condition']['pre_pay'])&&$si['condition']['pre_pay']==1){
											echo '<span pre class="color_minor"><div class="bg_minor">订</div><div class="h18">预付</div></span>';}
										else{ echo '<span now class="bg_minor">订</span>';}?></span>
								<?php } else{?>
									<span now class="bg_999">满</span>
								<?php }?>
                        <?php }?>
							</div>
						</div>
						<?php if (!empty( $si['detail'])){?>
						<div class="pad3 relative color_999 h20">
                            <div style="padding-bottom:10px"><?php echo $si['detail'];?></div>
                        </div>
                        <?php }?>
					<?php }}?>
				</div>
			</div><!--item_foot --->
		</div><!--item --->
        <?php if($cur_label_count&&$cur_label_count==$label_output_count){$cur_label_count=$label_output_count=0;?>
    	</div>
    	<?php }?>
        <?php
	       $i++;
        }}elseif(!empty($room_empty_alert)){
	   ?>
       <div class="center h20" style="padding:30px;">
	   		<?php echo $room_empty_alert;?>
	   </div>
       <?php }?>
</div><!--room_list --->
</div><!--box_room_list --->
<!-- 专题活动无价格提醒 -->
<?php if(!empty($tc_id) && empty($rooms)){?>
	<div class="ui_none">
	    <div>
	    	<p>没有搜索到相关结果~</p>
	        <div class="webkitbox" style="-webkit-box-align:baseline; margin-top:10px">
	        	   <a style="text-align:center; min-width:100px; padding-right:5px;" href="<?php echo preg_replace('/&tc_id=\d*/is','', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" class="color_link">逛逛订房</a>
	        </div>
	    </div>
	</div>
<?php }?>
<!-- 专题活动无价格提醒 -->
<!--  以下为相同部分 -->
<?php if(!empty( $hotel['book_policy'])){?>
	<div class="bg_fff">
		<div class="pad3 bd_bottom">酒店政策</div>
		<div class="pad3 h22"><?php echo $hotel['book_policy'];?></div>
	</div>
<?php }?>

<?php if(isset($foot_ads['ads']) && !empty($foot_ads['ads'])){ ?>
	<div class="h28 pad3 bd_top bg_fff martop"><em class="iconfont">&#X43;</em> <?php if(isset($foot_ads['title']) && !empty($foot_ads['title'])) echo $foot_ads['title'];else echo '推荐';?></div>
<div class="bg_fff" style="padding:0 10px 10px 0">
	<div class="vote_spread bg_fff">
		<?php foreach($foot_ads['ads'] as $fad){ foreach($fad as $fa){?>
			<a href="<?php echo Hotel_base::inst()->get_url($fa['ad_link'],array(),TRUE);?>">
				<div class="squareimg"><img src="<?php echo $fa['ad_img'];?>" info="<?php echo $fa['ad_title'];?>"/></div>
				<div class="h28 txtclip"><?php echo $fa['ad_title'];?></div>
				<div class="h22 txtclip"><?php echo $fa['des'];?></div>
			</a>
		<?php }}?>
	</div>
</div>
	<script>
		$(function(){
			var l= $('.vote_spread>*').length;
			if( l<=1) $('.vote_spread .squareimg').css('padding-bottom','40%');
			if( l<=2) $('.vote_spread>*').css({'-webkit-box-flex':'1','box-flex':'1'});
			if( l>2) $('.vote_spread>*').css('width','45%');
		});
	</script>
<?php }?>

<div class="ui_pull guest_pull" style="display:none">
	<div class="bg_fff pull_box center pad15" style="margin-top:40%">
		<div class="color_main pad3">商务旅客</div>
		<div class="color_main pad3" style="border-bottom:1px solid"><input class="center h22" type="text" placeholder="输入客户协议代码获取协议价"></div>
		<div class="webkitbox center martop">
			<div class="close pad3 color_888"><em class="iconfont h36">&#x27;</em><p class="h20">关闭</p></div>
			<div class="sure pad3 color_main"><em class="iconfont h36">&#x26;</em><p class="h20">确认</p></div>
		</div>
	</div>
</div>

<div class="ui_pull detail_pull" style="display:none">
	<div class="pull_box bg_fff">
		<div class="pull_loading"><p>正在加载</p></div>
		<div title class="color_main center pad3">-</div>
		<div room_img class="pullscroll"></div>
		<div class="scroll pad3 h20">
			<div sever></div>
			<div content></div>
		</div>
		<div class="close color_888 center"><em class="iconfont h36">&#x27;</em></div>
	</div>
</div>
</body>
<script>
    function item_silde_down(_this,slide_id){
        _this=$(_this);
        _this.find('em').toggleClass('rotate').toggleClass('torotate');
        $('#'+slide_id).slideToggle();
    }
	function silde_down(_this){
		_this=$(_this);
		_this.find('.showprice em').toggleClass('rotate').toggleClass('torotate');
		_this.find('.item_foot').stop().slideToggle();
	}
	function DateMinus(start,end){
	　　var _startdate = new Date(start);
	　　var _enddate = new Date(end);
	　　var days = _enddate.getTime() - _startdate.getTime();
	　　var day = parseInt(days / (1000 * 60 * 60 * 24));
	　　return day;
	};
	$("#day").html(DateMinus($("#startdate").val(),$("#enddate").val()));
	var _checkin = $("#startdate").val().split("/"),
		_checkout = $("#enddate").val().split("/");
	$("#checkin").html(_checkin[1]+'月'+_checkin[2]+'日');
	$("#checkout").html(_checkout[1]+'月'+_checkout[2]+'日');
	if($("#more_room").val() != "") { initHtml($("#more_room").val(),false)}
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
			circlesize		 : '4px'
		});
		// $('.room_list .item').eq(0).trigger('click');
		$('.guest').click(function(e){ e.stopPropagation();toshow($('.guest_pull'));})
		$('.close').click(toclose);
		$('.guest_pull .sure').click(function(){
			toclose();
			if($('.guest_pull input').val()==''){
				return false;
			}
			$('#protrol_code').val($('.guest_pull input').val());
			$('.guest').html($('.guest_pull input').val());
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
		$('[like]').click(function(){
			var _like=$(this);
			pageloading('',0.4);
			if(_like.attr('like')=='on'){
				$.get('<?php echo Hotel_base::inst()->get_url("CANCEL_ONE_MARK");?>',{
					mid:_like.attr('mid')
				},function(data){
					if(data==1){
						_like.attr('mid',data);
						_like.attr('like','off');
						$.MsgBox.Alert('已取消收藏');
						_like.html('&#x2a;');
					}
					removeload();
				});
			}
			else{
				$.get('<?php echo Hotel_base::inst()->get_url("ADD_HOTEL_COLLECTION");?>',{
					hid:'<?php echo $hotel['hotel_id'];?>',
					hname:'<?php echo $hotel['name'];?>',
				},function(data){
					if(data>0){
						_like.attr('like','on');
						$.MsgBox.Alert('已收藏');
						_like.html('&#x29;');
					}
					removeload();
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
			preSpDate:<?php echo $pre_sp_date; ?>,
			minSelect:<?php echo $minSelect; ?>,
			maxDays:<?php echo isset($max_book_day)?$max_book_day:90; ?>,
			selectedCallBack:function(data){
				//$('.checkin .week').html(weekNames[data.inDate.getDay()] );
				$('#checkin').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');

				//$('.checkout .week').html(weekNames[data.outDate.getDay()]);
				$('#checkout').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');

				$('.checkin_time').html(data.dateSpan);
				day_rooms($('#startdate').val(),$('#enddate').val());
			}
		});
	})
	function show_room_detail(obj,event){
		$('.pullscroll').html('');
		toshow($('.detail_pull'));
		$('.pull_loading').stop().show();
		var _this=$(obj).parents('[rid]');
		$.post("<?php echo Hotel_base::inst()->get_url("RETURN_ROOM_DETAIL");?>",{
			h:"<?php echo $hotel['hotel_id'];?>",
			r:_this.attr('rid')
		},function(data){
			$('.detail_pull [title]').html(data.name);
			var tmphtml='<div class="headerslide">';
			if(data.imgs!=null&&data.imgs.hotel_room_lightbox!=null){
				$.each(data.imgs.hotel_room_lightbox,function(i,n){
					tmphtml+='<div class="slideson"><img src="'+n.image_url+'" /></div>';
				});
			}
			else  tmphtml+='<div class="slideson"><img src="'+data.room_img+'" /></div>';
			tmphtml+='</div>';
			$('.detail_pull [room_img]').html(tmphtml);
			if(data.imgs!=null&&data.imgs.hotel_room_service!=null){
				temp='';
				$.each(data.imgs.hotel_room_service,function(i,n){
					temp+='<div><em class="iconfont">'+n.image_url+'</em>'+n.info+'</div>';
				});
				$('.detail_pull [sever]').html(temp);
			}
			$('.detail_pull [content]').html(data.book_policy);
			$('.pull_loading').stop().hide();
			$.fn.imgscroll({
				imgrate			 : 640/290,
				partent_div      : 'pullscroll',
				circlesize		 : '4px'
			});
		},'json');

		event.stopPropagation();
	}
	function day_rooms($startdate,$enddate,$price_code){
		pageloading('',0.4);
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_MORE_ROOM");?>',{
			h:'<?php echo $hotel['hotel_id']?>',
			start:$startdate,
			end:$enddate,
            mem_level:<? echo $member->level;?>,
			<?php if(isset($tc_id)) echo 'tc_id:'.$tc_id.',';?>
			protrol_code:$('#protrol_code').val()
		},function(data){
			initHtml(data,true);
			$("#more_room").val(JSON.stringify(data));
		},'json');
	}
	function initHtml(data,bol){
		if(!bol){ data = JSON.parse(data)}
		var temp='';
			var i=0;
			var cur_label_count=0;
			var label_output_count=0;
			$.each(data.rooms,function(i,n){
                if(cur_label_count==0){
					cur_label_count=n.room_info.type_label==undefined?0:n.room_info.type_label.counts;
				}
				if(cur_label_count>0){
					if(label_output_count==0){
						temp+='<div class="webkitbox justify bd_bottom pad3" ';
						temp+=' onClick="item_silde_down(this,'+"'room_type_label_"+n.room_info.type_label.id+"'"+')">';
						temp+=' <div class="h26 border_left_2">'+n.room_info.type_label.label_name+'</div><em class="iconfont color_main ';
                        if(i<=0){
                            temp+='rotate torotate';
                        }
                        temp+=' ">3</em>';
						temp+='</div>';
						temp+='<div id="room_type_label_'+n.room_info.type_label.id+'"';
						if(i>0){
							temp+=' style="display:none;"';
						}
						temp+='>';
					}
					label_output_count++;
				}

				temp+='<div class="item" onClick="silde_down(this)"><div class="webkitbox justify pad3 bg_fff bd_bottom" rid="'+n.room_info.room_id+'"><div class="img" onclick="show_room_detail(this,event)"><div class="squareimg">';
				temp+='<img src="'+n.room_info.room_img+'"/></div></div>';
				temp+='<div class="room_name" onclick="show_room_detail(this,event)"><p>'+n.room_info.name+'</p>';
				temp+='<p class="color_999">'+n.room_info.sub_des+'</p>';
				<?php if(!empty($icons_set['coupon_back'])){?>
				temp+='<p class="btn_void color_key xs h18">入住返券</p>';
				<?php }?>
				temp+='</div>';
				if(n.state_info!=''){
				<?php if(isset($tc_id)){?>
					//计算真实起价
					var lowest = 0;
					$.each(n.state_info,function(sk,sn){
						if(sn.book_status=='available'&&sn.avg_price>=0){
							if(sk==0 || sn.avg_price<lowest){
								lowest = sn.avg_price;
							}
						}
					});
					n.lowest = lowest;
				<?php }?>
					temp+='<div class="showprice color_main">';
					if(parseFloat(n.lowest)>=0)
						temp+='<tt class="h20">¥</tt><tt class="h36">'+n.lowest+'</tt><tt class="h20 color_999">起</tt>';
					temp+='<em class="iconfont';
                    if(cur_label_count||i<=0){
                        temp+=' rotate torotate';
                    }
                    temp+='">&#x33;</em></div>';
				}
				else{
					temp+='<div class="showprice color_999">不可用</div>';
				}
				temp+='</div>';
				if(n.state_info!=''&&n.lowest<0){
					temp+='<div class="full_room"><span class="topright color_key"></span><span>满</span></div>';
				}else if(n.top_price!=undefined&&n.top_price!=''){
					$.each(n.top_price,function(it,nt){
						temp+='<div class="full_room"><span class="topright color_key"></span><span>'+nt+'</span></div>';
						return false;
					});
				}

				temp+='<div class="item_foot"';
				if(!cur_label_count&&i>0){
					temp+=' style="display:none;"';
				}
				temp+='>';
				<?php if(!isset($tc_id)){?>
					if(n.show_info!=''){
						temp+='<div class="bd_bottom webkitbox h18 viplist">';
						$.each(n.show_info,function(nsi,nsv){
							temp+='<div><p>'+nsv.price_name+'</p><p class="y color_main">'+nsv.avg_price+'</p><p>'+nsv.related_des+'</p></div>';
						})
						temp+='</div>';
					}
				<?php }?>
				temp+='<div class="bd_bottom list_style" style="background:none">';
				if(n.state_info!=''){
                    var is_login=true;
					var bonus=0;
					var point_not_enough=0;
					<?php if (isset($member->logined)&&$member->logined==0){?>
						is_login=false;
					<?php }?>
					<?php if (!empty($member->bonus)){?>
						bonus=<?php echo $member->bonus;?>;
					<?php }?>
					$.each(n.state_info,function(sk,sn){
						temp+='<div class="webkitbox justify pad3"><div style="max-width:50%"><p>'+sn.price_name;
						if(sn.price_tags!=undefined){
							$.each(sn.price_tags,function(ptk,ptn){
								temp+=' <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle">'+ptn+'</span>';
							});
						}
						temp+='</p>';
						temp+='<div class="h24 color_main">';
						if(sn.bookpolicy_condition!=undefined&&sn.bookpolicy_condition.breakfast_nums!=undefined&&sn.bookpolicy_condition.breakfast_nums!=''){
                            temp+=' <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle">'+sn.bookpolicy_condition.breakfast_nums+'</span>';
						}
                        if(sn.useable_coupon_favour!=undefined&&sn.useable_coupon_favour!=0){
                            temp+=' <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle">券可减'+sn.useable_coupon_favour+'元</span>';
                        }
                        if(sn.wxpay_favour_sign==1){
                            temp+=' <span class="btn_void xs" style="font-size:8px; color:#7f9fe9; vertical-align:middle">微信支付减'+sn.bookpolicy_condition.wxpay_favour+'元</span>';
                        }
						temp+='</div>';
						temp+='<div class="h24 color_999">'+sn.des+'</div></div><div class="webkitright">';
                        if(sn.point_exchange!=undefined){
                            temp+='<span class=" color_minor">';
                            temp+=sn.point_exchange!=-1?sn.avg_point+'积分':'--';
                            temp+='</span>';
                            if(sn.book_status=='available'){
                            	if(sn.point_exchange!=-1){
                            		if(!is_login){
                                    	temp+='<a href="<?php if (isset($_SERVER['HTTPS'])&&strtolower($_SERVER['HTTPS'])=='on')$scheme='https://';else $scheme='http://'; echo site_url('membervip/login').'?id='.$inter_id.'&redir='.urlencode($scheme.$_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI']);?>"><span now class="bg_minor">登录</span></a>';
                                    }else{
                                        if(bonus>=sn.total_point){
                                            temp+='<span onclick="pay(this,event)" room_id="'+n.room_info.room_id+'"';
                                            temp+=' price_type="'+sn.price_type+'"'
                                            temp+=' price_code="'+sn.price_code+'">';
                                             if(sn.condition&&sn.condition.pre_pay==1)
                                                temp+='<span pre class="color_minor"><div class="bg_minor">兑</div><div class="h18">预付</div></span>';
                                            else
                                                temp+='<span now class="bg_minor">兑</span>';
                                            temp+='</span>';
                                        }else{
                                            temp+='<span now class="bg_999">积分不足</span>';
                                        }
                                    }
                                }else{
                                    temp+='<span now class="bg_999">无法兑换</span>';
                                }
                            }else{
                                temp+='<span now class="bg_999">满</span>';
                            }
                        }else{
							if(parseFloat(sn.avg_price)>=0){
								temp+='<div class="color_minor">¥'+sn.avg_price;
								if(sn.is_avg) temp+='<p class="color_999 h20">均价</p>';
								temp+='</div>';
							}
							if(sn.book_status=='available'){
								temp+='<span onclick="pay(this,event)" room_id="'+n.room_info.room_id+'"';
								temp+=' price_type="'+sn.price_type+'"'
								temp+=' price_code="'+sn.price_code+'">';
								if(sn.condition&&sn.condition.pre_pay==1)
									temp+='<span pre class="color_minor"><div class="bg_minor">订</div><div class="h18">预付</div></span>';
								else
									temp+='<span now class="bg_minor">订</span>';
								temp+='</span>';
							}
							else
								temp+='<span now class="bg_999">满</span>';
                        }
						temp+='</div></div>';
						if(sn.detail!=undefined&&sn.detail){
                            temp+='<div class="pad3 relative color_999 h20">';
	                    	temp+='<div style="padding-bottom:10px">'+sn.detail+'</div>';
                            temp+='</div>';
						}
					});
				}
				temp+='</div></div></div>';

                if(cur_label_count&&cur_label_count==label_output_count){
					cur_label_count=0;
					label_output_count=0;
					temp+='</div>';
				}
				i++;
			});
			if(data.errmsg){
				window.setTimeout(function(){
					$.MsgBox.Alert(data.errmsg);
					$('.guest').html('<span>商务旅客</span><em class="iconfont color_888">&#x2d;</em>');
				},300);
			}else if(temp==''&&data.room_empty_alert!=''){
				// $.MsgBox.Alert(data.room_empty_alert);
				temp='<div class="center h20" style="padding:30px;">'+data.room_empty_alert+'</div>';
			}
            $('#room_list').html(temp);
			removeload();
	}
</script>
</html>
