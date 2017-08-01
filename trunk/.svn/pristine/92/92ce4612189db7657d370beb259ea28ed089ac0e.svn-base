
<body class="">
<form action="<?php echo $post_url;?>" method="post" class="hotel_select_time__fsy">
    <div class="pageloading"><p class="isload">正在加载</p></div>
    <div class="bg_fff bd_bottom">
        <div class="bd_bottom title_con h26">
            <div>入住劵码：<?php echo $code;?></div>
            <div>预约房型：<?php echo $hotel_name ? $hotel_name : $item['hotel_name'];?>   <?php echo $room_name;?></div>
        </div>
        <div class="h26 explain">
            <div><span class="bg_fff"></span>有房</div>
            <div><span class="bg_fb4947"></span>满房</div>
            <!-- <div><span class="bg_4bc1f8"></span>不可定</div> -->
        </div>
    </div>
    <div class="calendar bd martop">
        <table>
            <thead class="bg_fff theads">
                <tr>
                    <th>日</th>
                    <th>一</th>
                    <th>二</th>
                    <th>三</th>
                    <th>四</th>
                    <th>五</th>
                    <th>六</th>
                </tr>
            </thead>
            <tbody id="oTbody">
                <tr id="titl_btn" class="titl_btn">
                    <td class="se_datas bd_bottom relative" colspan="7">
                        <i class="iconfont left_btn reduce">&#xe679;</i>
                        <span>2016年</span>
                        <span>
                            <select id="s_months">
                                <option value="1">1月</option>
                                <option value="2">2月</option>
                                <option value="3">3月</option>
                                <option value="4">4月</option>
                                <option value="5">5月</option>
                                <option value="6">6月</option>
                                <option value="7">7月</option>
                                <option value="8">8月</option>
                                <option value="9">9月</option>
                                <option value="10">10月</option>
                                <option value="11">11月</option>
                                <option value="12">12月</option>
                            </select>
                        </span>
                        <i class="iconfont right_btn add">&#xe6a3;</i>
                    </td>
                </tr>
           
            </tbody>
        </table>
    </div>
    <div class="bd_bottom list_style">
        <div class="input_item">
            <span>入住人</span>
            <span><input type="text" name="post_name" id="editName" value="<?php echo isset( $customer_info['name'] ) ? $customer_info['name'] : '';?>"></span>
        </div>
        <div class="input_item">
            <span>入住人电话</span>
            <span><input type="text" name="post_phone" id="editPhone" value="<?php echo isset( $customer_info['mobile'] ) ? $customer_info['mobile'] : '';?>"></span>
        </div>
    </div>
    <div>
        <div class="book_btn bg_main disable" id="bookingButton">预订</div>
    </div>
    <div class="bd list_style">
        <div class="input_item">
            <span>购买人</span>
            <span><?php echo isset( $customer_info['name'] ) ? $customer_info['name'] : '';?></span>
        </div>
        <div class="input_item">
            <span>购买人电话</span>
            <span><?php echo isset( $customer_info['mobile'] ) ? $customer_info['mobile'] : '';?></span>
        </div>
        <div class="input_item">
            <span>订单号</span>
            <span><?php echo $item['order_id'];?></span>
        </div>
        <div class="input_item">
            <span>有效日期</span>
            <span><?php echo $item['expiration_date'];?></span>
        </div>
    </div>
    <div class="ui_pull" style="display:none">
        <div class="f_con bg_fff">
            <div class="spot bg_main"></div>
            <div class="f_txt bd_bottom h24">
                <div class="center h28 c_000">入住确认</div>
                <div class="f_txt_box color_3e">
                    <div> 
                        <span>入住时间:</span>
                        <span class="in_time">2016-12-5</span>
                    </div>
                    <div> 
                        <span>离店时间:</span>
                        <span class="out_time">2016-12-6</span>
                    </div>
                    <div> 
                        <span>入住酒店:</span>
                        <span><?php echo $item['hotel_name'];?></span>
                    </div>
                    <div> 
                        <span>入住房型:</span>
                        <span><?php echo $room_name;?></span>
                    </div>
                </div>
            </div>
            <div class="f_txt h24">
                <div class="f_txt_box">
                    <div>   
                        <span>入住人:</span>
                        <span id="inName"><?php echo isset( $customer_info['name'] ) ? $customer_info['name'] : '';?></span>
                    </div>
                    <div> 
                        <span>联系电话:</span>
                        <span id="inPhone"><?php echo isset( $customer_info['mobile'] ) ? $customer_info['mobile'] : '';?></span>
                    </div>
                    <div class="prompt"><i class="iconfont color_main">&#xe64d;</i>提交后,将不能退款,不可取消 是否确认</div>
                </div>
            </div>
            <div class="btn_lists center">
                <div class="cancel color_3e">暂不预定</div>
                <div class="bg_main" id="PostSubmit">确定预定</div>
            </div>
            <input type="hidden" name="post_order_id" value="<?php echo isset( $order_id ) ? $order_id : '';?>">
            <input type="hidden" name="post_hotel_id" value="<?php echo isset( $hotel_id ) ? $hotel_id : '';?>">
            <input type="hidden" name="post_room_id" value="<?php echo isset( $room_id ) ? $room_id : '';?>">
            <input type="hidden" name="post_price_code" value="<?php echo isset( $price_code ) ? $price_code : '';?>">
            <input type="hidden" name="post_code" value="<?php echo isset( $code ) ? $code : '';?>">
            <input type="hidden" name="post_start" id="PostStartTime" value="">
            <input type="hidden" name="post_end" id="PostEndTime" value="">
            <input type="hidden" name="post_room_name" value="<?php echo isset( $room_name ) ? $room_name : '';?>">
            <input type="hidden" name="post_code_name" value="<?php echo isset( $code_name ) ? $code_name : '';?>">
            <input type="hidden" name="aiid" value="<?php echo isset( $aiid ) ? $aiid : '';?>">
            <input type="hidden" name="aiidi" value="<?php echo isset( $aiidi ) ? $aiidi : '';?>">
            <input type="hidden" name="post_num" value="1">
        </div>
        <div class="close center"><i class="iconfont c_fff">&#xe612;</i></div>
    </div>
</form>
<script>
$(function(){
    //日历开始
    var json1={
		val:'',
		data:[
		// {'time':'2016-12-6','state':'满房'},
		// {'time':'2016-12-9','state':'不可定'},
		// {'time':'2016-12-16','state':'满房'},
		// {'time':'2016-12-20','state':'不可定'},
		// {'time':'2016-12-22','state':'不可定'},
		<?php if( $rooms_un_can_booking ):?>
			<?php foreach( $rooms_un_can_booking as $k=>$v ):?>
				<?php if( $v['can_booking'] == $api_model::CAN_BOOKING_TRUE ): ?>
					// {'time':'<?php //echo $k;?>','state':'可定'},
				<?php elseif( $v['can_booking'] == $api_model::CAN_BOOKING_FALSE ): ?>
					{'time':'<?php echo $k;?>','state':'不可定'},
				<?php elseif( $v['can_booking'] == $api_model::CAN_BOOKING_FULL ): ?>
					{'time':'<?php echo $k;?>','state':'满房'},
				<?php else: ?>
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
		]
	}
    var new_time=new Date();
    var yy=new_time.getFullYear();
    var mm=new_time.getMonth()+1;
    var numbers=yy;

    new_dada(yy,mm);
    $('.reduce').click(function(){
        clear_date();
        // new_dada(numbers=numbers-1,mm);
        // pageloading();
        ajax(numbers=numbers-1,mm);
    })
    $('.add').click(function(){
        clear_date();
		ajax(numbers=numbers+1,mm);
    })
    $('.bottom_btn').click(function(){
        scelet_month();
        $('.bom_select').show();
        $('select').trigger('change');
    })
    $('select').change(function(event){
		$('.bom_select').hide();
		var num=parseInt($(this).val());
		clear_date();
        ajax(numbers,num);
	});
	function ajax(yy,mm){
        // alert( yy );return;
		// $.ajax('',function(){
		// 	json1.data=[];
		// 	// removeloading();
		// })
        pageloading();
        var url = "<?php echo $get_booking_time_url;?>";
        var hid = "<?php echo $hotel_id;?>";
        var rmid = "<?php echo $room_id;?>";
        var cdid = "<?php echo $price_code;?>";
        var oid = "<?php echo $order_id;?>";
        $.ajax({
            url: url,
            type: "post",
            dataType: 'json',
            data: {hid:hid,rmid:rmid,cdid:cdid,oid:oid,year:yy,month:mm},
            success:function(json){
                removeload();
                if( json.status == <?php echo Soma_base::STATUS_TRUE;?> ){
                    var str = '';
                    var full = "<?php echo $api_model::CAN_BOOKING_FULL;?>";
                    var booking = "<?php echo $api_model::CAN_BOOKING_TRUE;?>";
                    var unbooking = "<?php echo $api_model::CAN_BOOKING_FALSE;?>";
                    var array = [];
                    var i=0;
                    for(var key in json.data.data){
                        if( json.data.data[key]['can_booking'] == full ){
                            str = '{"time":"'+key+'","state":"满房"}';
                        }else if( json.data.data[key]['can_booking'] == booking ){

                        }else if( json.data.data[key]['can_booking'] == unbooking ){
                            str = '{"time":"'+key+'","state":"不可定"}';
                        }
                        array[i++]=JSON.parse(str)
                    }
                    // json1.val = mm;
                    json1.data = array;
                    new_dada(yy,mm);
                }else{
                    window.location.href = json.data.url;
                }
            }
        });
	}
    function new_dada(y,m){
        var oTbody=document.getElementById('oTbody');
        var titl_btn=document.getElementById('titl_btn');
        var oSpan=titl_btn.getElementsByTagName('span');
        var s_months=document.getElementById('s_months');
        var new_date=new Date();
        y=y||new_date.getFullYear();
        m=m||new_date.getMonth()+1;
        oSpan[0].innerHTML=y+"年 ";
        s_months.value=m;
        
        var set_data=new Date(y,m-1);
        var week=set_data.getDay();  //星期几
		//console.log(week);
        var day_num=new Date(y,m,0).getDate();  //多少天
        for(var i=0;i<6;i++){
            var oTr=document.createElement('tr');
            for(var h=0;h<7;h++){
                var oTd=document.createElement('td');
                var odiv=document.createElement('div');
                oTd.appendChild(odiv);
                oTr.appendChild(oTd);
            }
            oTbody.appendChild(oTr);
        }
        var oDvi=oTbody.getElementsByTagName('div');
        switch(set_data.getDay()){
                case 1:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+1].innerHTML=i+1;
                        }
                break;
                case 2:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+2].innerHTML=i+1;
                        }
                break;
                case 3:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+3].innerHTML=i+1;
                        }
                break;
                case 4:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+4].innerHTML=i+1;
                        }
                break;
                case 5:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+5].innerHTML=i+1;
                        }
                break;
                case 6:
                    for(var i=0;i<day_num;i++){
                            oDvi[i+6].innerHTML=i+1;
                        }
                break;
                case 0:
                    for(var i=0;i<day_num;i++){
                            oDvi[i].innerHTML=i+1;
                        }
                break;
            }
            for(var i=0;i<oDvi.length;i++){
                if(json1.data.length){
                    for(var j=0;j<json1.data.length;j++){
                       if(oDvi[i].innerHTML==json1.data[j].time.split('-')[2]){
                        if(json1.data[j].state=='满房'){
                            oDvi[i].className='full';
                        }else if(json1.data[j].state=='不可定'){
                            oDvi[i].className='no_holet';
                        }
                       }
                    };
                }
				if ( json1.val!==''&&y == json1.val.getFullYear() && m ==json1.val.getMonth()+1&&oDvi[i].innerHTML==json1.val.getDate()){
					oDvi[i].className='hook';
				}
            }

    }
	$('#oTbody').on('click','td',function(){		
		var a = new Date($('.se_datas span:nth-of-type(1)').html().replace('年','/')+(parseInt($('#s_months').val()))+'/'+$(this).find('div').html());
		var b = new Date();
		b.setHours(0,0,0,0);
		if(a.getTime()>=b.getTime()&&!$(this).find('div').hasClass('full')&&!$(this).find('div').hasClass('no_holet')){
			$(this).find('div').addClass('hook').parent().siblings('td').find('div').removeClass('hook').parents('tr').siblings('tr').find('div').removeClass('hook');
			$('.book_btn').removeClass('disable')
            var year=parseInt($('.se_datas span:nth-of-type(1)').html());
            var month=$('#s_months').val()-1;
            var data;
            for(var i=0;i<$('#oTbody').find('div').length;i++){
                if($('#oTbody').find('div').eq(i).hasClass('hook')){
                    data=$('#oTbody').find('div').eq(i).html();
                }
            }
            var year=parseInt($('.se_datas span:nth-of-type(1)').html());
            var month=$('#s_months').val()-1;
            var data;
            for(var i=0;i<$('#oTbody').find('div').length;i++){
                if($('#oTbody').find('div').eq(i).hasClass('hook')){
                    data=$('#oTbody').find('div').eq(i).html();
                }
            }
			json1.val = new Date(year,month,data);
		}
	})
    function clear_date(){
        $('#oTbody tr').not(":first").remove();
    }
    //日历结束
    $('.book_btn').click(function(){
        if($('.book_btn').hasClass('disable'))return;
		
		toshow($('.ui_pull'));
		var time1=json1.val.getTime();
		var time2=json1.val.getTime()+86400000;
		$('.in_time').html(format_data(time1));
		$('.out_time').html(format_data(time2));
    })
    function format_data(tme){
        var time=new Date(tme);
        var yy=time.getFullYear();
        var mm=time.getMonth()+1;
        var dd=time.getDate();
        return yy+'-'+mm+'-'+dd;
    }
    $('.close i,.cancel').click(toclose)

    $('#s_months').change(function(){
        console.log( $(this).val() );
    });

    $("#PostSubmit").one('click',function(){
        $('#PostStartTime').val($('.in_time').html());
        $('#PostEndTime').val($('.out_time').html());
		console.log('form submit')
		$('form').submit();
    });

    $('#bookingButton').click(function(){
        $('#inName').html( $('#editName').val() );
        $('#inPhone').html( $('#editPhone').val() );
    });
})
</script>
</body>
</html>
