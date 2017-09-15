<?php include 'header.php'?>
<?php echo referurl('js','submit_order.js',1,$media_path) ?>
<?php echo referurl('js','calendar.js',3,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<?php if (!empty($addit_service)){?>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.core.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.scroller.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.widget.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.util.datetime.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.datetimebase.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.widget.ios.js');?>"></script>
<script src="<?php echo base_url('public/club/calendar/mobiscroll.i18n.zh.js');?>"></script>

<link href="<?php echo base_url('public/club/calendar/mobiscroll.animation.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.widget.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.widget.ios.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.scroller.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('public/club/calendar/mobiscroll.scroller.ios.css');?>" rel="stylesheet" type="text/css">
<?php }?>
<style>
.checkin:after{content:"入住"}
.checkout:after{content:"离店"}
.checkin_time:before{ content:"共"}
.checkin_time:after{ content:"晚"}
.morning:before{content:"次日"}
.dawn:before{content:"凌晨"}
.input_item>*{display:block}
.input_item>*:first-child {min-width: 6rem;}
.input_item .content {position: absolute;left: 6rem; right: 0;top:8px;bottom:8px}
.input_item .invoice_info_area_content{right: 5%}
.df{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;}
.invoice_pull{background: #fff;}
.invoice_pull_box{padding: 10px}
.invoice_pull .invoice_title{text-align: center; line-height: 3}
.invoice_pull .invoice_control{text-align: center;margin-top: 30px}
.invoice_pull .submit_btn_invoice{padding: 10px 30px}
.invoice_pull .submit_btn_invoice + .submit_btn_invoice{margin-left: 20px}
.invoice_pull .list_style >  *:last-child:after{border-top: 1px solid #f0f0f0}
.invoice_header {position: relative;}
.invoice_add{position: absolute;top:0;right: 5px;line-height: 3}
.invoice_list_box dt{font-size: 12px; padding-top: 20px}
.invoice_list_box dt:first-child{padding-top:0}
.invoice_dd {position: relative;padding-left: 2.5rem; padding: 8px 8px 8px 2.5rem}
.invoice_dd .color_main{position: absolute; top: 8px;left: 0.5rem}
.invoice_dd .title{width: 100%;text-overflow:ellipsis; white-space: nowrap; word-break: break-all;overflow:hidden;}
.invoice_dd [checkbox] em{color: #ccc}
.invoice_dd.ischeck [checkbox] em{color:inherit;}
::-webkit-input-placeholder{
	font-size: 12px;
}
.invoice_info_area .invoice_title{display:block;word-break:break-all;overflow:hidden;text-overflow: ellipsis}


</style>
<input type="hidden" id="order_sub_url" name="order_sub_url" value="<?php echo Hotel_base::inst()->get_url("SAVEORDER");?>" />
<input type="hidden" id="startdate" name="startdate" value="<?php echo $startdate;?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo $enddate;?>" />
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel_id;?>" />
<input type="hidden" id="price_codes" name="price_codes" value='<?php echo $price_codes;?>' />
<input type="hidden" id="price_type" name="price_type" value='<?php echo $price_type;?>' />
<input type="hidden" id="prevend" name="prevend" value='0' />
<input type="hidden" id="datas" name="datas" value='<?php echo $source_data;?>' />
<input type="hidden" id="pay_type" name="pay_type" value="<?php echo  empty($pay_ways)?'':$pay_ways[0]->pay_type;?>" />
<header class="pad3">
	<div class="h30"><?php echo $hotel['name']?></div>
    <div class="h24"><?php echo date("m月d日",strtotime($startdate));?><?php if(empty($athour)){?>-<?php echo date("m月d日",strtotime($enddate));?>  共<?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?>晚<?php }?></div>
    <div class="h24 color_888 martop">房型:<?php foreach($room_list as $rl)echo $rl['name'].' ';?>(<?php echo $first_state['price_name'];?>)</div>
	<div class="h24 color_888"><?php if(!empty($first_room['room_info']['imgs']['hotel_room_service']))foreach($first_room['room_info']['imgs']['hotel_room_service'] as $hs)echo $hs['info'].' ';?></div>
</header>

<!-- 预订 -->
<div class="list_style bd" id="booking-base">
	<div class="arrow input_item room_count">
    	<span>房间数</span>
        <select class="num h28" id="roomnum" rid="<?php echo $first_room['room_info']['room_id'];?>">
        <?php for($i=1;$i<=$first_state['least_num'];$i++){?>
        	<option value="<?php echo $i;?>"><?php echo $i;?>间</option>
        	<?php }?>
        </select>
    </div>
	<div class=" input_item room_num" <?php if(empty($hotel_config['ROOM_NO_SELECT'])||$hotel_config['ROOM_NO_SELECT']==0){?>style="display:none;"<?php }?>>
    	<span>房间号</span>
        <span class="num">前台分配(<tt>1</tt>间)</span>
    </div>
	<div class="input_item inner_name">
    	<span>入住人</span>
        <div>
        	<input type="text" id='name' name='name' required class="person" placeholder="请输入姓名" value="<?php echo empty($last_order)?'':$last_order['name']?>" />
        </div>
    </div>

    <div class="input_item">
    	<span>手机号</span>
        <div><input type="tel" id='tel' name='tel' required class="phone" placeholder="请输入手机号" value="<?php echo empty($last_order)?'':$last_order['tel']?>" /></div>
    </div>
<?php if (!empty($customer_condition['fills']['email'])){?>
	<div class="input_item">
    	<span>邮箱</span>
        <div><input type="email" id='email' name='email' required placeholder="请输入邮箱" value="<?php echo empty($last_order['email'])?'':$last_order['email']?>" /></div>
    </div>
<?php }?>
</div>
<?php if (!empty($show_multi_inner)){ ?>
<div id='multi_fill' class="pad3 color_888 h24">随行入住人</div>
<div id="multi_inners" <?php if(empty($customer_condition['show_first'])){?>style='display:none'<?php }?>>
    <div class="list_style bd multi_inner" style="margin-bottom:8px">
    <?php if (!empty($customer_condition['adult']['num'])){for ($i=1;$i<=$customer_condition['adult']['num'];$i++){ ?>
    	<div class="input_item" <?php if($i==1){?>style='display:none'<?php }?>>
        	<span>成人<?php echo $first_state['customer_condition']['adult']['num']>1?$i:''?></span>
            <div>
            	<input type="text" key='extra_formdata' key_type='multi_inner' must='<?php echo $i==1?0:1;?>' name='multi_inners_adult[]' class="person" placeholder="房间1入住成人名" value="" />
            </div>
        </div>
    <?php }}?>
    <?php if (!empty($customer_condition['child']['num'])){for ($i=1;$i<=$customer_condition['child']['num'];$i++){ ?>
    	<div class="input_item">
        	<span>儿童<?php echo $customer_condition['child']['num']>1?$i:''?></span>
            <div>
            	<input type="text" key='extra_formdata' key_type='multi_inner' must='1' name='multi_inners_child[]' class="person" placeholder="房间1入住儿童名" value="" />
            </div>
        </div>
        <?php if (!empty($customer_condition['child']['birthday'])){?>
        <div class="input_item relative">
       		<span>出生日期</span>
            <div>
            	<input type="text" readonly key='extra_formdata' min_date='<?php echo $min_child_birthday;?>' max_date='<?php echo $max_child_birthday;?>' max_date='<?php echo date('Y-m-d',strtotime('-3 year',time()));?>' key_type='multi_inner' must='1' name='multi_inners_child_birthday[]'
            	class="person multi_inners_child_birthday" placeholder="儿童生日" tips="儿童年龄为4~11岁，请重新选择生日" value=""/>
            </div>
        </div>
        <?php }?>
    <?php }}?>
    </div>
</div>
<?php if (!empty($customer_condition['baby']['choose'])){?>
<div class="list_style bd">
    <div class="input_item">
    	<span>是否有婴儿</span>
        <div>
        	<label class="checkradio"><input onchange='toggle_service($(this),"multi_inners_baby_inn")' class="hide" type="radio" name='multi_inners_baby_inn' checked value="0" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 无</label>
        	<label class="checkradio"><input onchange='toggle_service($(this),"multi_inners_baby_inn")' class="hide" type="radio" name='multi_inners_baby_inn' value="1" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 有</label>
        	<input type="hidden" key='extra_formdata' key_type='multi_inner' name='multi_inners_baby_inn_val' value="0" />
        </div>
    </div>
    <div name='multi_inners_baby_inn' field='multi_inners_baby_inn_1'  class="input_item has_baby" style='display: none;'>
    	<span>婴儿人数</span>
        <div>
        	<input type="tel" min=0 max=10 key='extra_formdata' key_type='multi_inner' name='multi_inners_baby_num' value="" placeholder="婴儿人数" />
        </div>
	</div>
</div>
<?php }?>
<?php }?>
<!-- 以上为预订部分 -->
<?php if(!empty($athour)){?>
<!-- 钟点房/时租房 -->
<?php if(!empty($add_time_service['add_times'])){?>
<div class="ui_pull addhour_server_pull bg_fff" style="display:none">
    <div class="pad10 center">请选择加时服务</div>
    <ul class="list_style scroll bd" service_id='<?php echo $add_time_service['service_id']?>' onclick='add_service_info($(this))'>
        <li><tt></tt>不加时</li>
         <?php foreach ($add_time_service['add_times'] as $at){?>
        <li>+<tt><?php echo $at;?></tt>小时<dt><?php echo $at*$add_time_service['service_price'];?></dt>元</li>
         <?php }?>
    </ul>
</div>
 <?php }?>
<?php if(!empty($first_state['time_condition']['book_times'])){?>
<div class="list_style bd martop">
	<div class="arrow input_item">
    	<span>入住时间</span>
        <span class="addhour_date"><?php echo date('H:00',strtotime($first_state['time_condition']['book_times'][0]));?></span>
    </div>
     <?php }?>
     <?php if(!empty($add_time_service['add_times'])){?>
	<div class="arrow input_item">
    	<span>加时服务</span>
        <span class="addhour_server">请选择加时服务</span>
    </div>
    <?php }?>
    <?php if(!empty($first_state['time_condition']['last_time'])){?>
	<div class="arrow input_item">
    	<span>离店时间</span>
        <span class="addhour_leave"><?php echo date('H:00',strtotime($first_state['time_condition']['last_time']));?></span>
    </div>
</div>
<div class="ui_pull addhour_date_pull bg_fff" style="display:none">
    <div class="pad10 center">请选择入住时间</div>
    <ul class="list_style scroll bd">
        <?php foreach ($first_state['time_condition']['book_times'] as $bt){?>
        <li><?php if( date('H',strtotime($bt))==0)echo '24:00';else echo date('H:00',strtotime($bt));?></li>
        <?php }?>
    </ul>
</div>

<script>
	var str_a = $('.addhour_date').html().split(':');
	var str_b = $('.addhour_leave').html().split(':');
	var str_tmp =  $('.addhour_date_pull li').eq($('.addhour_date_pull li').length-1).html().split(':');
	if ( parseInt(str_b[0]) < parseInt(str_a[0]) )  str_b[0]=parseInt(str_b[0])+24;
	var _min = parseInt(str_b[0])-parseInt(str_a[0]);
	var _max = parseInt(str_tmp[0])+_min;
	
	function r(s){
		if ( s>24 ){
			s=s-24;$('.addhour_leave').addClass('morning');
			if( s<6)$('.addhour_leave').addClass('dawn');
		}
		else{$('.addhour_leave').removeClass('dawn morning');}
		if( s<10){s='0'+s;}
		return s+':00';
	}
	function L(str){
	}
	function M(num){
		var b=parseInt($('.addhour_date').html().split(':')[0]);
		return  parseInt(num)+b+_min;
	}
	
	$('.addhour_date_pull li').click(function(){  //选择入住时间
		var b = parseInt($(this).html().split(':')[0]);
		var s = b+_min;
		$('.addhour_server').html('不加时');
		$('.addhour_leave').html(r(s));
		$('.addhour_date').html($(this).html());
		toclose();
	})
	$('.addhour_server_pull li').click(function(){  //选择加时服务
		var s = M($('tt',this).html() || '0');
		if (s >_max){
			if ($('tt',this).html()=='')
				$('.addhour_server').html($(this).html());
			return;
		}
		$('.addhour_leave').html(r(s));
		$('.addhour_server').html($(this).html());
		toclose();
	})
	$('.addhour_date').parent().click(function(){
		toshow($('.addhour_date_pull'));
	})
	$('.addhour_server').parent().click(function(){
		toshow($('.addhour_server_pull'));
		var tmp;
		for ( var i=0; i< $('.addhour_server_pull li').length;i++){
			tmp = $('.addhour_server_pull li').eq(i).find('tt').html();
			if ( tmp == '') tmp=0;else tmp = M(tmp);
			if( tmp >_max) $('.addhour_server_pull li').eq(i).css('color','#999');
			else $('.addhour_server_pull li').eq(i).css('color','#555')
		}
	})
</script>
<!-- 以上为 钟点房/时租房 部分 -->
<?php }}?>
<!-- 续住 -->
<div class="list_style bd" style="display:none">
	<div class="arrow input_item">
    	<span>续住时间</span>
        <span id='checkdate'>
            <span class="checkin" id='checkin'>1月1日</span>
            <span class="checkout" id='checkout'>1月1日</span>
            <span class="checkin_time color_main">1</span>
        </span>
    </div>
	<div class="input_item">
    	<span>续住房型</span>
        <span>高级双人房</span>
    </div>
	<div class="input_item">
    	<span>续住房号</span>
        <span>8899</span>
    </div>
</div>

<!-- 以上为 续住 部分 -->
<div class="list_style bd martop">
<?php if (!isset($total_point)){?>
   <?php if(!empty($member->mem_id)&&!empty($point_consum_rate)&&empty($first_state['bonus_condition']['no_part_bonus'])){?>
   <?php if($bonus_setting==1){?>
	<div class="webkitbox justify" bonus="<?php echo $exchange_max_point;?>" id="bonuspay2">
    	<span id='bonus_tips'>
            可用<?php echo $exchange_max_point;?><?php echo $point_name;?>抵用<?php echo $exchange_max_point*$point_consum_rate;?>元
           <input type="hidden" id='bonus' name='bonus' value='' />
        </span>
		<span class="tmpradio color_E4E4E4"><em class="iconfont">&#x4f;</em></span>
    </div>
       <?php }else{ ?>
	<div class="input_item">
    	<span><?php echo $point_name;?>抵用</span>
        <div style="width:100%;display:inline"><input max="<?php echo $member->bonus;?>" type="tel" id='bonus' name='bonus' value=""
       <?php if (!empty($point_consum_set['max_use'])){ $point_max_use=$point_consum_set['max_use'];?>
    	placeholder="可用<?php echo round($point_consum_set['max_use']);?><?php echo $point_name;?>抵扣<?php echo round($point_consum_set['max_use']*$point_consum_rate); ?>元"
    	<?php }else{ $point_max_use=$member->bonus;?>
        placeholder="可用<?php echo round($member->bonus);?><?php echo $point_name;?>抵扣<?php echo round($member->bonus*$point_consum_rate); ?>元"
        <?php }?> /></div>
    </div>
   <?php    }} else{ if($bonus_setting==1){ ?>
    <div class="webkitbox justify" bonus="<?php echo $exchange_max_point;?>" id="bonuspay2" style="display:none">
    	<span id='bonus_tips'>
            可用<?php echo $exchange_max_point;?><?php echo $point_name;?>抵用<?php echo $exchange_max_point*$point_consum_rate;?>元
           <input type="hidden" id='bonus' name='bonus' value='' />
        </span>
        <span class="tmpradio color_E4E4E4"><em class="iconfont">&#x4f;</em></span>
    </div>
    <?php }else{ ?>
	<div class="input_item" style="display:none">
    	<span><?php echo $point_name;?>抵用</span>
        <div style="width:100%;display:inline"><input type="tel" id='bonus' name='bonus' placeholder="" /></div>
    </div>
    <?php }}?>
    <?php }?>
   	<?php if(empty($first_state['condition']['no_coupon'])&&empty($first_state['coupon_condition']['no_coupon'])) {?>
	<div class="input_item arrow usevote">
    	<span>优惠券</span>
        <?php if(!empty($select_coupon_favour) && $select_coupon_favour!=0){ ?>
        <span id="coupon_i"><?php echo '已选￥'.$select_coupon_favour; ?></span>
        <?php   }else{ ?>
        <span id="coupon_i"><?php echo empty($first_state['coupon_condition']['couprel_info'])?'选择优惠券':$first_state['coupon_condition']['couprel_info']['title']; ?></span>
        <?php }?>
    </div>
    <?php }?>
</div>
<div class="martop pad3 webkitbox input_item bg_fff bd" style="-webkit-box-align:baseline; padding-right:0">
    <span>支付方式</span>
    <div class="pay_list">
    <?php if(!empty($pay_ways)) foreach($pay_ways as $k=>$pw){?>
        <div pay_type='<?php echo $pw->pay_type;?>' pname="<?php echo $pw->pay_name;?>" pfavour="<?php echo $pw->favour;?>" class="pay_way <?php if($k==0){?>ischeck<?php $first_pay_favour=$pw->favour;}?> <?php if (!empty($pw->disable))echo 'disable'?>" delay-policy="<?php echo !empty($bookpolicy_condition['delay_time'][$pw->pay_type])?$bookpolicy_condition['delay_time'][$pw->pay_type]:''; ?>" retain-policy="<?php echo !empty($bookpolicy_condition['retain_time'][$pw->pay_type])?$bookpolicy_condition['retain_time'][$pw->pay_type]:''; ?>">
            <span class="color_main"><em class="iconfont">&#x4f;</em></span>
            <span><?php echo $pw->pay_name;?><?php echo $pw->des;?> <?php if($pw->pay_type=='point') { $valid_point=1; }?></span>
        </div>
        <?php }?>
        <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
        <div id='bonus_pay_way' pay_type='bonus' class="pay_way <?php if($point_exchange['can_exchange']==0){?>disable<?php }?>">
            <span class="color_main"><em class="iconfont">&#x4f;</em></span>
            <span><?php echo $point_name;?>兑换(<?php echo $point_exchange['point_need'];?>/<?php echo $member->bonus;?>)</span>
        </div>
        <?php }?>
        <?php if(empty($valid_point)){?>
           	<div id="point_pay_way" style="display: none" pay_type="point" class="pay_way">
           	<span class="color_main"><em class="iconfont">&#x4f;</em></span><span><?php echo $point_name;?>支付</span>
           	</div>
        <?php }?>
    </div>
</div>
<div class="list_style bd martop" id='consume_code' <?php if($pay_ways[0]->pay_type!='balance'||$banlance_code==0){?>style="display:none"<?php }?>>
	<div class="input_item">
    	<span>消费密码</span>
        <div><input type="password" id='consume_pwd' placeholder="请输入消费密码"/></div>
    </div>
</div>
<?php if (!empty($addit_service['invoice'])){?>
<div class="list_style bd martop" id="invoice_item">
    <div class="input_item">
    	<span>发票</span>
        <div>
        	<label class="checkradio"><input onchange='toggle_service($(this),"invoice")' class="hide" type="radio" name='invoice' checked value="0" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 不需要</label>
        	<label class="checkradio"><input onchange='toggle_service($(this),"invoice")' class="hide" type="radio" name='invoice' value="1" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 需要</label>
        	<input type="hidden" key='extra_formdata' name='invoice_val' key_type='addit_service' value="0" />
        </div>
    </div>
	<div name='invoice' field='invoice_1'  class="input_item arrow invoice_info_area" style='display: none;'>
    	<span>发票信息</span>
        <div class="content invoice_info_area_content">
			<span class="invoice_title"></span>
        	<input type="hidden" class="invoice_info_input" key='extra_formdata' key_type='addit_service'  name='invoice' value="" placeholder="发票信息"/>
        </div>
	</div>
</div>
<?php }?>
<div class="list_style bd martop">
    <div class="input_item" >
    	<span>备注</span>
        <div>
        	<textarea id="custom_remark" name="custom_remark" placeholder="无"></textarea>
        </div>
	</div>
</div>
<?php if (!empty($addit_service)){?>
<div id='addit_service' class="bd martop">
    	<div class="pad3 bg_fff webkitbox" id="slideblock">
        	<span>附加服务</span>
        	<span class="txt_r"><em class="iconfont color_999" style="font-size:5px">&#x49;</em></span>
        </div>
    <?php if (!empty($addit_service['transport'])){?>
    	<div class="pad3 slidechild" style='display:none'>
            <div class="h22"><?php echo $addit_service['transport']['des']?></div>
            <div class="h20 color_999" id='addit_service_transport'>
                <?php echo $addit_service['transport']['tips']?>
        	</div>
      	</div>
        <div class="list_style_2 slidechild" style="background:none;display:none">
      	<?php if (!empty($addit_service['transport']['pickup'])){?>
        	 <div class="input_item show1">
             	<span><?php echo $addit_service['transport']['pickup']['des'];?></span>
             	<div>
                    <label class="checkradio"><input class="hide" type="radio" checked='checked' name='addit_service_pickup' onchange='toggle_service($(this),"addit_service_pickup")'
                    value="none" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 不需要</label>
                 	<?php foreach ($addit_service['transport']['pickup']['ways'] as $n=>$w){?>
                        <label class="checkradio"><input class="hide" type="radio" name='addit_service_pickup' onchange='toggle_service($(this),"addit_service_pickup")'
                        value="<?php echo $n;?>" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> <?php echo $w['name']?></label>
                    <?php }?>
                    <input type="hidden" key='extra_formdata' key_type='addit_service' name="addit_service_pickup_val" value='none'/>
                </div>
             </div>
             <?php if(!empty($addit_service['transport']['pickup']['ways']['train'])){?>
             <div name='addit_service_pickup' field='addit_service_pickup_train' style='display: none' class="input_item h22">
             	<span>火车站名</span>
                <div><input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_trainstation' placeholder="车站名"></div>
             </div>
             <div name='addit_service_pickup' field='addit_service_pickup_train' style='display: none' class="input_item h22">
             	<span>火车班次</span>
                <div><input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_train' placeholder="火车班次"></div>
             </div>
             <div name='addit_service_pickup' field='addit_service_pickup_train' style='display: none' class="input_item h22 arrow relative">
             	<span>到达时间</span>
                <div style="-webkit-box-flex:1">
                	<input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_traintime' placeholder="到达时间" class="addit_service_pickup_time" type="text" readonly/>
				</div>
             </div>
             <?php }?>
             <?php if(!empty($addit_service['transport']['pickup']['ways']['airport'])){?>
             <div name='addit_service_pickup' field='addit_service_pickup_airport' style='display: none' class="input_item h22">
             	<span>机场名</span>
                <div><input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_airport' placeholder="机场名"></div>
             </div>
             <div name='addit_service_pickup' field='addit_service_pickup_airport' style='display: none' class="input_item h22">
             	<span>航班号</span>
                <div><input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_plain' placeholder="航班号"></div>
             </div>
             <div name='addit_service_pickup' field='addit_service_pickup_airport' style='display: none' class="input_item h22 arrow relative">
             	<span>到达时间</span>
                <div style="-webkit-box-flex:1">
                	<input key='extra_formdata' key_type='addit_service' name='addit_service_pickup_plaintime' placeholder="到达时间" class="addit_service_pickup_time" type="text" readonly/>
				</div>
    		</div>
    		<?php }?>
         <?php }?>
         <?php if (!empty($addit_service['transport']['takeoff'])){?>
        	 <div class="input_item show2">
             	<span><?php echo $addit_service['transport']['takeoff']['des'];?></span>
             	<div>
             		<label class="checkradio"><input class="hide" type="radio" checked='checked' name='addit_service_takeoff' onchange='toggle_service($(this),"addit_service_takeoff")'
                    value="none" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 不需要</label>
                 	<?php foreach ($addit_service['transport']['takeoff']['ways'] as $n=>$w){?>
                        <label class="checkradio"><input class="hide" type="radio" name='addit_service_takeoff' onchange='toggle_service($(this),"addit_service_takeoff")'
                        value="<?php echo $n;?>" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> <?php echo $w['name']?></label>
                    <?php }?>
                    <input key='extra_formdata' key_type='addit_service' type="hidden" name="addit_service_takeoff_val" value='none'/>
                </div>
             </div>
             <?php if(!empty($addit_service['transport']['takeoff']['ways']['train'])){?>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_train' style='display: none' class="input_item h22 show2_1">
                 	<span>火车站名</span>
                    <div><input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_trainstation' placeholder="车站名"></div>
                 </div>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_train' style='display: none' class="input_item h22 show2_1">
                 	<span>火车班次</span>
                    <div><input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_train' placeholder="火车班次"></div>
                 </div>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_train' style='display: none' class="input_item h22 arrow relative show2_1">
                 	<span>到达时间</span>
                    <div style="-webkit-box-flex:1">
                    	<input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_traintime' placeholder="到达时间" class="addit_service_takeoff_time" type="text" readonly />
                    </div>
                 </div>
             <?php }?>
             <?php if(!empty($addit_service['transport']['takeoff']['ways']['airport'])){?>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_airport' style='display: none' class="input_item h22 show2_2">
                 	<span>机场名</span>
                    <div><input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_airport' placeholder="机场名"></div>
                 </div>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_airport' style='display: none' class="input_item h22 show2_2">
                 	<span>航班号</span>
                    <div><input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_plain' placeholder="航班号"></div>
                 </div>
                 <div name='addit_service_takeoff' field='addit_service_takeoff_airport' style='display: none' class="input_item h22 arrow relative show2_2">
                 	<span>到达时间</span>
                    <div style="-webkit-box-flex:1">
                    	<input key='extra_formdata' key_type='addit_service' name='addit_service_takeoff_plaintime'  placeholder="到达时间"  class="addit_service_takeoff_time" type="text" readonly/>
					</div>
				 </div>
             <?php }?>
         <?php }?>
     <?php }?>
     <?php if (!empty($addit_service['parking'])){?>
     <div class="input_item">
    	<span><?php echo $addit_service['parking']['des']?></span>
    	<div id='addit_service_parking'>
        	<label class="checkradio"><input onchange='toggle_service($(this),"addit_service_need_parking")' class="hide" type="radio" name='addit_service_need_parking' checked value="0" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 无</label>
        	<label class="checkradio"><input onchange='toggle_service($(this),"addit_service_need_parking")' class="hide"  type="radio" name='addit_service_need_parking' value="1" /><span class="color_main"><em class="iconfont">&#x4f;</em></span> 有</label>
        	<input type='hidden' key='extra_formdata' key_type='addit_service' name='addit_service_need_parking_val' value='0'/>
    	</div>
    	</div>
        <div name='addit_service_need_parking' field='addit_service_need_parking_1' style='display: none' class="input_item need_parking">
        	<span>车牌号码</span>
            <div><input type="text" key='extra_formdata' key_type='addit_service' name='addit_service_car_no' value="" placeholder='车牌号'/></div>
  		</div>
	<?php }?>
    </div>
</div>
<?php }?>
<?php if(!empty($hotel['book_policy'])||!empty($bookpolicy_condition['retain_time'])||!empty($bookpolicy_condition['delay_time'])){?>
<div class="pad3">
	<div class="h30">温馨提示</div>
	<!--预定政策-->
	<div class="color_888 h22">
		<span id="retain-policy" style="<?php echo empty($bookpolicy_condition['retain_time'][$pay_ways[0]->pay_type])?'display: none;':''; ?>">房间保留至<span class="color_main"><?php echo !empty($bookpolicy_condition['retain_time'][$pay_ways[0]->pay_type])?$bookpolicy_condition['retain_time'][$pay_ways[0]->pay_type]:''; ?></span>点！</span>
		<span id="delay-policy" style="<?php echo empty($bookpolicy_condition['delay_time'][$pay_ways[0]->pay_type])?'display: none;':''; ?>">延迟退房时间至<span class="color_main"><?php echo !empty($bookpolicy_condition['delay_time'][$pay_ways[0]->pay_type])?$bookpolicy_condition['delay_time'][$pay_ways[0]->pay_type]:''; ?></span>点</span>
	</div>
	<div class="color_888 h22"><?php echo nl2br($hotel['book_policy']);?></div>
</div>
<?php }?>
<div style="padding-top:15%">
    <div class="foot_fixed">
    	<div class="color_minor" style="padding:0 8px;">
        	合计 <?php if (!isset($total_point)){?>
        	<span id="total_price" class="y h36"><?php echo round($total_price-$first_pay_favour,2);?></span>
        	<?php }else{?>
        	<span id="total_point" class="h36"><?php echo $total_point;?> <?php echo $point_name;?></span>
        	<span id="total_price" class="y h36" style="display: none"><?php echo round($total_price-$first_pay_favour,2);?></span>
        	<?php }?>
        	<?php if (!isset($total_point)){?>
				<?php if($total_oprice>$total_price){?>
	        	<del id="total_oprice" class="y color_888 h22"><?php echo round($total_oprice,2);?></del><?php }?>
        		<span class="price_detail h20 color_888 martop" style=" float:right;">明细 <em class="iconfont" style="font-size:5px">&#x49;</em></span>
        	<?php }?>
        </div>
        <span class="bg_main center pad10 submit_btn">提交订单</span>
    </div>
</div>


<div class="ui_pull chooseroom_pull bg_fff" style="display:none">
    <div>
        <div class="default color_main pad10 bd_bottom">前台分配(<tt>1</tt>间)</div>
        <?php foreach($rooms as $rm){?>
        <div class="scroll" rid="<?php echo $rm['room_info']['room_id'];?>">
            <div class="room_name bg_E4E4E4 center pad10"><?php echo $rm['room_info']['name'];?>(<span><?php echo count($rm['room_info']['number_realtime']);?></span>间)</div>
            <?php foreach($rm['room_info']['number_realtime'] as $rnd){?>
            <div class="roomid pad10 bd_bottom" rno="<?php echo $rnd['num_id'];?>" rnn="<?php echo $rnd['room_no'];?>" style="display:block"><span><?php echo $rnd['room_no'];?></span><?php if(!empty($rnd['des'])){?>(<?php echo $rnd['des'];?>)<?php }?></div>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <div class="sure_btn bottomfixed bg_main center pad10 h32">确定</div>
</div>

<div class="ui_pull price_detail_pull h24" style="display:none" onClick="toclose()">
    <div class="relative" style="width:100%; height:100%;">
        <div class="scroll absolute bg_fff">
            <div class="pad3 ">费用明细</div>
            <div class="list">
                <div class="item webkitbox bd" fangfei><span>房费</span><span class="color_main" id="list_total_price">0</span></div>
                <div detail>
                    <!--div class="item webkitbox"><span>2016-09-18</span><span>无早</span><span>￥188X1</span></div-->
                </div>
                <div class="item webkitbox bd" youhui><span>优惠券</span><span class="color_main" id="list_total_coupon_price">-￥0</span></div>
                <div detail></div>
                <div class="item webkitbox bd_top" youhui><span>使用<?php echo $point_name;?></span><span class="color_main" id="list_total_bonus_price">0</span></div>
                <div class="item webkitbox bd_top" youhui><span>支付优惠</span><span class="color_main" id="list_pay_favour">0</span></div>
            </div>
        </div>
    </div>
</div>

<div class="ui_pull bg_F8F8F8 vote_pull " style="display:none">
    <div tips class="pad3 bg_fff">
        <div class="h30">温馨提示</div>
        <div class="h22">
	        <?php if(!empty($coupon_tips)){ ?><p><?php echo $coupon_tips; ?></p><?php }else{ ?>
				<p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
				<p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
	        <?php } ?>
        </div>
    </div>
    <ul class="votelist scroll bg_F8F8F8" id="votelist"></ul>
    <div footbtn class="bg_main">确定</div>
</div>
<?php if (!empty($addit_service['invoice'])){?>
<div class="ui_pull  invoice_pull" style="display: none">
	<div class="invoice_pull_box">
		<div class="invoice_list">
			<div class="invoice_header">
				<p class="invoice_title">发票选择</p>
				<a href="javascript:;" class="color_main invoice_add">新增</a>
			</div>
			<dl class="invoice_list_box"></dl>
			<div class="invoice_control">
				<a href="javascript:;" class="bg_main center submit_btn_invoice" data-type="11">确定</a>
			</div>
		</div>
		<div class="invoice_form">
			<p class="invoice_title">
				新增发票
			</p>
			<form class="invoice_form-form">
			<div class="invoice_form_box list_style">

				<div class="input_item">
					<span>发票类型</span>
					<div class="df">
						<label class="checkradio"><input type="radio" name="invoice_type" class="invoice_type" value="pp" checked><span class="color_main"><em class="iconfont">O</em></span>普通发票</label>
						<label class="checkradio"><input type="radio" name="invoice_type" class="invoice_type" value="zz" ><span class="color_main"><em class="iconfont">O</em></span>增值税专用发票</label>
					</div>
				</div>
				<div class="input_item">
					<span>单位名称</span>
					<div class="content">
						<input type="text" name="title">
					</div>
				</div>
				<div class="input_item">
					<span>纳税人编号</span>
					<div class="content">
						<input type="text" name="code">
					</div>
				</div>
				<div class="input_item zz">
					<span>注册地址</span>
					<div class="content">
						<input type="text" name="address">
					</div>
				</div>
				<div class="input_item zz">
					<span>注册电话</span>
					<div class="content">
						<input reg="tel" placeholder="固定电话,格式为000-0000000" type="text" name="phonecall">
					</div>
				</div>
				<div class="input_item zz">
					<span>开户银行</span>
					<div class="content">
						<input type="text" name="bank">
					</div>
				</div>
				<div class="input_item zz">
					<span>银行帐号</span>
					<div class="content">
						<input type="text" name="account">
					</div>
				</div>

			</div>
			</form>
			<div class="invoice_control">
				<a href="javascript:;" class="bg_C3C3C3 center submit_btn_invoice" data-type="20">取消</a>
				<a href="javascript:;" class="bg_main center submit_btn_invoice" data-type="21">确定</a>
			</div>
		</div>
	</div>
</div>
<?php }?>
</body>
<script>
var csrf_name='<?php echo $csrf_token;?>';
var csrf_value='<?php echo $csrf_value;?>';
var roomnos={};
var coupons=JSON.parse('<?php echo json_encode($use_coupon);?>');
var select_coupons = JSON.parse('<?php echo json_encode($select_coupons);?>');
var add_services={};
var roomnums=JSON.parse($('#datas').val());
var total_price=<?php echo $total_price;?>;
var real_price=total_price;
var total_oprice=<?php echo $total_oprice;?>;
var total_favour=<?php echo $select_coupon_favour;?>;
var coupon_amount=<?php echo $select_coupon_favour;?>;
var max_room_night_use=0;
var max_order_use=0;
var max_coupon_use=0;
var room_night_use=0;
var order_use=0;
var paytype_counts=0;
var use_flag='';
var banlance_code=<?php echo $banlance_code?>;
var part_bonus_set={};
<?php if (isset($point_consum_set)){?>
part_bonus_set=JSON.parse('<?php echo json_encode($point_consum_set);?>');
<?php }?>
var point_pay_set={};
<?php if (isset($point_pay_set)){?>
point_pay_set=JSON.parse('<?php echo json_encode($point_pay_set);?>');
<?php }?>
var bonus_condition={};
<?php if (isset($first_state['bonus_condition'])){?>
bonus_condition=JSON.parse('<?php echo json_encode($first_state['bonus_condition']);?>');
<?php }?>
var no_part_bonus=<?php echo empty($first_state['bonus_condition']['no_part_bonus'])?0:$first_state['bonus_condition']['no_part_bonus'];?>;
var has_point_pay=<?php echo empty($has_point_pay)?0:1;?>;

var point_consum_rate=<?php echo empty($point_consum_rate)?0:$point_consum_rate;?>;
var point_favour=0;
var point_max_use=<?php echo empty($point_max_use)?0:$point_max_use;?>;
var my_bonus=<?php echo empty($member->bonus)?0:$member->bonus;?>;
var click_check = false;
var bonus_view = <?php echo $bonus_setting;?>;
var point_name='<?php echo empty($point_name)?'积分':$point_name;?>';
var pay_favour=<?php echo empty($first_pay_favour)?0:$first_pay_favour;?>;
total_favour+=pay_favour*1;
var checkindateStr = '<?php echo date("Y-m-d",strtotime($startdate));?>';
var checkoutdateStr = '<?php echo date("Y-m-d",strtotime($enddate));?>';
var todayStr = '<?php echo date("Y-m-d");?>';
var dateOpt= {
	theme:'ios', //设置显示主题
	mode:'scroller', //设置日期选择方式，这里用滚动
	display:'bottom', //设置控件出现方式及样式
	preset : 'date', //日期:年 月 日 时 分
	dateFormat: 'yy-mm-dd', // 日期格式
	dateOrder: 'yymmdd', //面板中日期排列格式
	yearText:'年',
	monthText:'月',
	dayText:'日',
	lang:'zh' //设置控件语言};
};
var timeOpt = {
	preset: 'datetime',
	dateFormat: 'yy-mm-dd',
	hourText: '时',
	minuteText: '分'
}
function addDateTime (){
	var $childBirthdayIpts = $('.multi_inners_child_birthday');
	if($childBirthdayIpts.length){
		$childBirthdayIpts.each(function(){
			var $self = $(this);
			var hasInit = ($self.attr('id') || '').indexOf('mobiscroll') > -1
			if(!hasInit) {
				var _opts = $.extend({}, dateOpt);
				$self.mobiscroll(_opts);
				var min_birthday=$self.attr('min_date').split('-');
				var max_birthday=$self.attr('max_date').split('-');
				$self.mobiscroll('option',{
					minDate: new Date(min_birthday[0], min_birthday[1]-1, min_birthday[2]),
					maxDate: new Date(max_birthday[0], max_birthday[1]-1, max_birthday[2])
				});
			}
		})
	}
}
$(function(){
	//儿童生日



	addDateTime()
	var pickupTime;
	var takeoffTime;
	//接送时间
	var checkindateArr = checkindateStr.split('-');
	var checkoutdateArr = checkoutdateStr.split('-');
	var minDate = new Date(checkindateArr[0], checkindateArr[1]-1, checkindateArr[2], 0, 0, 0);
	var maxDate = new Date(checkoutdateArr[0], checkoutdateArr[1]-1, checkoutdateArr[2], 23, 59, 59);
	var $additServicePickupTime = $('.addit_service_pickup_time');
	if($additServicePickupTime.length){
		var _opts = $.extend({
			minDate: minDate,
			maxDate: maxDate,
			onSelect: function(time){
				pickupTime = time;
				if(takeoffTime && pickupTime > takeoffTime){
					$.MsgBox.Alert('回程时间必须大于接送时间')
				}
			},
			onBeforeShow: function(inst){
				if(takeoffTime){
					var _maxDateArr = takeoffTime.replace(/\s|:/g, '-').split('-');
					var _maxDate = new Date(_maxDateArr[0], _maxDateArr[1]-1, _maxDateArr[2], _maxDateArr[3], _maxDateArr[4],0);
					console.log(_maxDate, 'maxDate')
					$additServicePickupTime.mobiscroll('option',{
						maxDate: _maxDate
					})
				}
			}
		}, dateOpt, timeOpt);
		$additServicePickupTime.mobiscroll(_opts);
	}
	//回程时间
	var $additServiceTakeoffTime = $('.addit_service_takeoff_time');
	if($additServiceTakeoffTime.length){
		var _opts = $.extend({
			minDate: minDate,
			maxDate: maxDate,
			onSelect: function(time){
				takeoffTime = time;
				if(pickupTime && pickupTime > takeoffTime){
					$.MsgBox.Alert('接送时间必须小于回程时间')
				}
			},
			onBeforeShow: function(inst){
				if(pickupTime){
					console.log(pickupTime)
					var _minDateArr = pickupTime.replace(/\s|:/g, '-').split('-');
					var _minDate = new Date(_minDateArr[0], _minDateArr[1]-1, _minDateArr[2], _minDateArr[3], _minDateArr[4],0);
					console.log(_minDate, 'minDate')
					$additServiceTakeoffTime.mobiscroll('option',{
						minDate: _minDate
					})
				}
			}
		}, dateOpt, timeOpt)
		$additServiceTakeoffTime.mobiscroll(_opts);
	}

	//发票
	var $invoicepull = $('.invoice_pull');
	var $invoiceForm = $invoicepull.find('.invoice_form');
	var $invoiceFormForm = $invoicepull.find('.invoice_form-form');
	var $invoiceList = $invoicepull.find('.invoice_list');
	var $invoiceListDl = $invoiceList.find('.invoice_list_box');
	var $invoiceAddBtn = $invoiceList.find('.invoice_add');
	var $zzItems = $invoicepull.find('.zz');
	var $zzIpts = $zzItems.find('input');
	var $invoiceIpts = $invoicepull.find('input');
	var $invoiceType = $('.invoice_type');
	var $invoiceItem = $('#invoice_item');
	var $invoiceItemTitle = $invoiceItem.find('.invoice_title')
	var $invoiceItemIpt = $invoiceItem.find('.invoice_info_input')
	var $invoiceItemArea = $invoiceItem.find('.invoice_info_area');
	$zzItems.hide();
	$zzIpts.prop('disabled', 'disabled')
	//TODO 获取服务器已存发票的接口地址
	var postInvoiceUrl = '<?php echo Hotel_base::inst()->get_url('ASYN_INVOICES');?>';
	//发票弹框事件
	$invoicepull.on('change', '.invoice_type', function(){
		var $self = $(this);
		if($self.val() === 'zz'){
			$zzItems.show();
			$zzIpts.prop('disabled', null);
		}else{
			$zzItems.hide();
			$zzIpts.prop('disabled', 'disabled')
		}
	})
// 	var phoneReg = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/;
	var phoneReg = /^0\d{2,3}-?\d{7,8}$/;
	var invoiceCheck = function(){
		var result = {};
		$invoiceIpts.each(function(idx, ele){
			var $self = $(this);
			if($self.prop('disabled')){
				return true;
			}else{
				var val = $.trim($self.val());
				if (!val){
					var tip = $self.parent().prev('span').text();
					$.MsgBox.Alert('请填写' + tip);
					result.error = true;
					return false;
				}
				if (val){
					var reg = $self.attr('reg');
					var name = $self.prop('name')
					if($self.attr('type')=='radio'){
						$radios=$('input[name="'+name+'"]');
						$.each($radios,function(i,n){
							if($(n).is(":checked")==true){
								val=$(n).val();
								return;
							}
						});
					}
					if(reg === 'tel'){
						if(phoneReg.test(val)){
							result[name] = val;
						}else{
							$.MsgBox.Alert('请填写正确的固定电话')
							result.error = true;
							return false;

						}
					}else{
						result[name]  = val;
					}
				}
			}
		});
		return result;
	}
	var showInvoice = function (data) {
		var zz = data.zz;
		var pp = data.pp;
		var arr = [];
		if(zz){
			arr.push('<dt class="color_C3C3C3">增值税专用发票</dt>')
			$.each(zz, function(key, val){
				arr.push('<dd class="invoice_dd"  data-type="zz" data-code="'+val.invoice_id+'" data-title="'+val.title+'"><span checkbox class="color_main"><em class="iconfont">O</em></span><div class="title">'+val.title+'</div></dd>')
			})
		}
		if(pp){
			arr.push('<dt class="color_C3C3C3">普通发票</dt>')
			$.each(pp, function(key, val){
				arr.push('<dd class="invoice_dd"  data-type="pp" data-code="'+val.invoice_id+'" data-title="'+val.title+'"><span checkbox class="color_main"><em class="iconfont">O</em></span><div class="title">'+val.title+'</div></dd>')
			})
		}
		$invoiceListDl.html(arr.join(''));
	}
	var invoiceFormFrom = 'page';
	$invoicepull.on('click', '.submit_btn_invoice', function(){
		var $self = $(this);
		var type = $self.data('type');
		if(type === 21){
			var result = invoiceCheck();
			if(result.error){
				return false;
			}
			$invoiceItemIpt.val(JSON.stringify(result));
			$invoiceItemTitle.text(result.title);
		}
		if(type === 11){
			var checked = $invoiceListDl.find('dd.ischeck');
			if(checked.length){
				var result = {
					invoice_type:  checked.data('type'),
					invoice_id: checked.data('code')
				}
				$invoiceItemIpt.val(JSON.stringify(result));
				$invoiceItemTitle.text(checked.data('title'));
			}else{
				$invoiceItemIpt.val('');
				$invoiceItemTitle.text('请选择发票信息');
			}
		}
		if(type === 20 && invoiceFormFrom === 'list'){
			$invoiceForm.hide();
			$invoiceList.show();
		}else{
			toclose();
		}
	})
	$invoiceListDl.on('click', 'dd', function(){
		var $self = $(this);
		var hasCheck = $self.hasClass('ischeck');
		if(hasCheck){
			$self.removeClass('ischeck');
		}else{
			$self.addClass('ischeck').siblings('.ischeck').removeClass('ischeck');
		}
	})
	$invoiceAddBtn.click(function(){
		showInvoiceArea();
		invoiceFormFrom = 'list';
	})
	var showInvoiceArea = function(data){
		if(data){
			showInvoice(data);
			$invoiceForm.hide();
			$invoiceList.show();
		}else{
			$invoiceForm.show();
			$invoiceList.hide();
		}
		invoiceFormFrom = 'page';
		toshow($invoicepull);
	}
	//显示发票弹框
	var hasGetInvoiceData = false;
	$invoiceItemArea.click(function(){
		if($(this).hasClass('disable')){
			return false;
		}
		if(hasGetInvoiceData){
			toshow($invoicepull);
		}else{
			hasGetInvoiceData = true;
			$.ajax({
				method: 'post',
				dataType: 'json',
				url: postInvoiceUrl,
				beforeSend: function(){
					pageloading();
				},
				success: function(data){
					if(data && data.s == 1){
						return showInvoiceArea(data.data)
					}
					showInvoiceArea()
				},
				error: function(){
					showInvoiceArea();
				},
				complete: function(){
					removeload();
				}
			})
		}

	})

	$('.price_detail').click(function(){
		if($('.price_detail_pull').is(':hidden')){
            price_detail();
			//立减
			str=0;
			if (pay_favour) str=pay_favour;
			$('#list_pay_favour').html(str);
			toshow($('.price_detail_pull'));
		}
	});
	$('#bonuspay2').click(function(){
		if($('.tmpradio',this).hasClass('color_main')){
			$('#bonus').val('');
		}
		else{
			$('#bonus').val($(this).attr('bonus'));
		}
		var val =$('#bonus').val()?$('#bonus').val()*1:0;
		$('.tmpradio',this).toggleClass('color_main');
		total_favour-=point_favour;
		point_favour=point_consum_rate*val;
		total_favour+=point_favour;
		$('#total_price').html((real_price-total_favour).toFixed(2));
        if(click_check==false){
            click_check = true;
        }else{
            click_check = false;
        }
	})
	$('#bonus').bind('change',function(){
		total_favour-=point_favour;
		if($('#bonus').val()*1>point_max_use){
			$.MsgBox.Alert('最多可用'+point_max_use+point_name);
			if(point_max_use>=my_bonus){
				$('#bonus').val(my_bonus);
			}else{
				$('#bonus').val(point_max_use);
			}
		}
		point_favour=point_consum_rate*$('#bonus').val();
		total_favour+=point_favour;
		var tmp = (real_price-total_favour).toFixed(2);
		$('#total_price').html(tmp>0?tmp:0);
	});

	<?php //if(empty($first_state['coupon_condition']['couprel_info'])){ ?>
	$('.usevote').click(function(){
        point_favour = 0;
        total_favour = pay_favour;
		if($(this).hasClass('disable')){
			return false;
		}
        use_vote();
        toshow($('.vote_pull'));
        var _h=$(window).height()-$('.vote_pull [tips]').outerHeight()-$('.vote_pull [footbtn]').outerHeight();
        $('#votelist').height(_h-10);
	});
	<?php //} ?>
	$('.vote_pull [footbtn]').click(function(){
		if(coupon_amount>0)
            $('#coupon_i').html('已选￥'+coupon_amount.toFixed(2));
		else
			$('#coupon_i').html('选择优惠券');
		toclose();
		getBonusSet();
	});
	$('.room_count .num').change(function(){
        point_favour = 0;
        total_favour = pay_favour;
		var tmpval=$(this).val();
		real_price=total_price*tmpval;
		$('#total_price').html((total_price*tmpval).toFixed(2));
		$('#total_oprice').html(total_oprice*tmpval);
		roomnos={};
		rid=$(this).attr('rid');
		roomnums[rid]=tmpval;
		$('.room_num .num').html('前台分配(<tt>'+tmpval+'</tt>间)');
		$('.default tt').html(tmpval);
		$('.default').trigger('click');

//		$('#coupon_i').html('选择优惠券');
//		total_favour-=coupon_amount;
//		coupon_amount=0;
//		coupons={};
//		$('#total_price').html((real_price-total_favour).toFixed(2));
		getBonusSet();
		getPointpaySet();
        use_vote();
        price_detail();
		 <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
		 	var need_point=<?php echo $point_exchange['point_need'];?>*tmpval;
		 	$('#bonus_pay_way span:last').html(point_name+"兑换("+need_point+"/<?php echo $member->bonus;?>)");
		 	if(need_point><?php echo $member->bonus;?>){
		 		$('#bonus_pay_way').addClass('disable');
// 		 		$('#bonus_pay_way').parent().find('li:first-child').click();
			}else{
				$('#bonus_pay_way').removeClass('disable');
			}
		 <?php }?>

	});
})
function choose_coupon(obj,bool){
	if ( $(obj).hasClass('ischeck')){
		$(obj).removeClass('ischeck');
		if(coupons[$(obj).attr('code')]!=undefined){
			delete(coupons[$(obj).attr('code')]);
			if(getJsonObjLength(coupons)==0)use_flag='';
			coupon_amount-=$(obj).attr('amount')*1;
			total_favour-=$(obj).attr('amount')*1;
			if($(obj).attr('use_num_type')=='room_nights' && bool)
				max_room_night_use++;
			else if($(obj).attr('use_num_type')=='order' && bool)
				max_order_use++;
			else if(!bool)max_coupon_use++;
		}
	}
	else{
		if(bool){
			if(!use_flag)
				use_flag=$(obj).attr('use_num_type');
			if(use_flag!=use_flag)return;
			if($(obj).attr('use_num_type')=='room_nights'){
				if(max_room_night_use>0)
					max_room_night_use--;
				else return;
			}
			else if($(obj).attr('use_num_type')=='order'){
				if(max_order_use>0)
					max_order_use--;
				else return;
			}
		}else{
			if(max_coupon_use>0)
				max_coupon_use--;
			else return;
		}
		$(obj).addClass('ischeck');
		coupons[$(obj).attr('code')]=$(obj).attr('amount');
		coupon_amount+=$(obj).attr('amount')*1;
		total_favour+=$(obj).attr('amount')*1;
	}
	$('#total_price').html((real_price-total_favour).toFixed(2));
}

function getJsonObjLength(jsonObj) {
        var Length = 0;
        for (var item in jsonObj) {
            Length++;
        }
        return Length;
}
function getLocalTime(nS) {
    return new Date(parseInt(nS) * 1000).toLocaleString().substr(0,10)
}
function add_service_info(obj){

}

function getBonusSet(){
	if(no_part_bonus==0){
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_POINT_SET");?>',{
			datas:JSON.stringify(roomnums),
			start:$('#startdate').val(),
			end:$('#enddate').val(),
			h:$('#hotel_id').val(),
			total_price:$('#total_price').html(),
			price_code:$('#price_codes').val(),
			paytype:$('#pay_type').val(),
			point_name:point_name
		},function(data){
			data=JSON.parse(data);
			
			$("#bonus").val('');
			if(data.s==1&&data.hide_bonus==1){
				var ex_money =0;
				$("#bonus").parent().parent().hide();
			}else{
				$("#bonus").parent().parent().show();
				total_favour -= point_favour;
				point_favour = 0;
				$('#total_price').html((real_price - total_favour).toFixed(2));
				if(data.s == 1 && data.consum_rate > 0){
					
					if(part_bonus_set != data.part_set){
						part_bonus_set = {}
						part_bonus_set = data.part_set;
						$("#bonus").parent().parent().show();
						if(bonus_view == 1){
							
							point_consum_rate = data.consum_rate;
							
							if(click_check == true){
								$('#bonuspay2').click();
							}
							
							point_max_use =<?php echo $exchange_max_point;?>;
							
							if(part_bonus_set['max_use'] != undefined){
								if(part_bonus_set['max_use'] < <?php echo $member->bonus;?>){
									point_max_use = part_bonus_set['max_use'];
								}
							}else{
								point_max_use = <?php echo $member->bonus;?>
							}
							
							if((real_price - 1 - total_favour) < 0){
								point_max_use = 0;
							}else if((real_price - 1 - total_favour) < point_max_use * data.consum_rate){
								point_max_use = Math.round((real_price - 1 - total_favour) / data.consum_rate);
							}
							
							if(part_bonus_set['use_rate'] != undefined){
								if((point_max_use % part_bonus_set['use_rate']) != 0){
									point_max_use = point_max_use - (point_max_use % part_bonus_set['use_rate']);
								}
							}
							
							var ex_money = point_max_use * data.consum_rate;
							
							
							$("#bonuspay2").attr('bonus', point_max_use);
							$("#bonus_tips").html("可用" + point_max_use + point_name + "抵用" + ex_money.toFixed(2) + "元" + '<input type="hidden" id="bonus" name="bonus" value=""/>');
							
							
						}else{
							if(part_bonus_set['max_use'] != undefined){
								point_max_use = part_bonus_set['max_use'];
								point_consum_rate = data.consum_rate;
								var ex_money = part_bonus_set['max_use'] * data.consum_rate;
								$("#bonus").attr('placeholder', "可用" + part_bonus_set['max_use'] + point_name + "抵扣" + ex_money.toFixed(2) + "元");
							}else{
								var my_bonus =<?php echo empty($member->bonus) ? 0 : $member->bonus;?>;
								point_max_use = my_bonus;
								point_consum_rate = data.consum_rate;
								var ex_money = my_bonus * data.consum_rate;
								$("#bonus").attr('placeholder', "可用" + my_bonus + point_name + "抵扣" + ex_money.toFixed(2) + "元");
							}
						}
					}
				}else{
					$("#bonus").parent().parent().hide();
					if($("#bonus").val() != '')
						$.MsgBox.Alert(data.errmsg);
				}
				if($('[pay_type="point"]').hasClass('ischeck')){
					$("#bonus").parent().parent().hide();
				}
			}
		});
	}
}
function getPointpaySet(){
	if(has_point_pay==1){
		$.post('<?php echo Hotel_base::inst()->get_url("RETURN_POINTPAY_SET");?>',{
			datas:JSON.stringify(roomnums),
			start:$('#startdate').val(),
			end:$('#enddate').val(),
			h:$('#hotel_id').val(),
			total_price:$('#total_price').html(),
			price_code:$('#price_codes').val(),
			paytype:$('#pay_type').val(),
			point_name:point_name,
			extra_para:<?php echo empty($extra_pointpay_para)?'':$extra_pointpay_para;?>
		},function(data){
			if(data.can_exchange==1){
				$('[pay_type="point"]').removeClass('disable');
				$('[pay_type="point"]').attr('able',1);
				$('[pay_type="point"]').show();
				$('[pay_type="point"] span:last').html($('[pay_type="point"]').attr('pname')+'('+data.des+')');
				if($('[pay_type="point"]').hasClass('ischeck')){
					$("#bonus").val('');
					$("#bonus").parent().parent().hide();
				}
				$('#total_point').html(data.point_need+' '+point_name);
			}else{
				$('[pay_type="point"]').addClass('disable');
				$('[pay_type="point"]').attr('able',0);
// 				$.MsgBox.Alert(data.errmsg);
				if($('[pay_type="point"]').hasClass('ischeck')){
					$('.pay_way').eq(0).trigger('click');
				}
				if(data.point_need!=undefined)
					$('#total_point').html(data.point_need+' '+point_name);
			}
		},'json');
	}
}

$("#bonus").change(function(){
//	$('.pay_way').eq(0).trigger('click');
});
function toggle_service(obj,showid){
	var val = obj.val();
	$('div[name="'+showid+'"]').hide();
	$('div[name="'+showid+'"] input').attr('must','0');
	$('div[name="'+showid+'"] input').attr('key','');
	$('div[field="'+showid+'_'+val+'"]').show();
	$('div[field="'+showid+'_'+val+'"] input').attr('must','1');
	$('div[field="'+showid+'_'+val+'"] input').attr('key','extra_formdata');
	$('input[name="'+showid+'_val"]').val(val);
}
$('#slideblock').click(function(){
	$(this).siblings('.slidechild').slideToggle();
	var _h=$(this).find('em').html();
	if(_h=='&#x34;'||_h=='4')
		$(this).find('em').html('&#x49;');
	else
		$(this).find('em').html('&#x34;');
});
<?php if (!empty($show_multi_inner)){ ?>
	var multi_inner_html=$('#multi_inners').html();
	var timer = null;
	$('#roomnum').on('change',function(){
		var current_count=$('#multi_inners > .multi_inner').length;
		var new_count=$(this).val();
		if(current_count>new_count){
			for(var i=0;i<(current_count-new_count);i++){
				$('#multi_inners > .multi_inner').last().mobiscroll('destroy').remove();
			}
		}else if(current_count<new_count){
			for(var i=0;i<(new_count-current_count);i++){
				$('#multi_inners > .multi_inner').last().after(multi_inner_html);
			}
			clearTimeout(timer);
			timer = setTimeout(function(){
				addDateTime();
			},0)
		}
		<?php if(empty($customer_condition['show_first'])){?>
		new_count==1?$('#multi_inners').hide():$('#multi_inners').show();
		<?php }?>
		$.each($('#multi_inners > .multi_inner'),function(k,n){
			if(k>0){
				$(n).find('.input_item').show();
				$(n).find('input[name="multi_inners_adult[]"]').attr('must','1');
			}
			k++;
			$(n).find('input[name="multi_inners_adult[]"]').attr('placeholder','房间'+k+'入住成人名');
			$(n).find('input[name="multi_inners_child[]"]').attr('placeholder','房间'+k+'入住儿童名');
		});
	});
<?php }else if(!empty($hotel['multiple_inner'])){ ?>
	$('#roomnum').on('change',function(){
		var current_count=$('#booking-base > .inner_name').length;
		var new_count=$(this).val();
		if(current_count>new_count){
			//房间变量少，删除DIV
			for(var i=0;i<(current_count-new_count);i++){
				$('#booking-base > .inner_name').last().remove();
			}
		}else if(current_count<new_count){
			for(var i=0;i<(new_count-current_count);i++){
				$('#booking-base > .inner_name').last().after('<div class="input_item inner_name"><span>入住人'+(current_count+1+i)+'</span><div><input type="text" name="customer[]" required class="person" placeholder="请输入姓名" value="" /></div>');
			}
		}
	});
	$('#roomnum').trigger('change');
<?php }?>

function price_detail(){
    //var num = '<?php if(empty($athour)){echo round(strtotime($enddate)-strtotime($startdate))/86400;}?>';
    //房费
    //if(num==''||num<=0)num = 1;
    //num = parseInt(num);
    var str ='';
    var date= new Date('<?php echo date("Y/m/d",strtotime($startdate));?>');
    var end = new Date('<?php echo date("Y/m/d",strtotime($enddate));?>');
    var tmp_price=JSON.parse('<?php echo json_encode(explode(',',$first_state['allprice']));?>'); //每天的价格
	var extra_info=JSON.parse('<?php echo json_encode($extra_info); ?>');
	var date_tmp='';
	
    for(var i=0;i<tmp_price.length;i++){
        /*str += '<div class="item webkitbox"><span>'
            + date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate()
            + '</span><span>￥'+tmp_price[i]+'×'+$('#roomnum').val()+'</span></div>';*/
        
		str += '<div class="item webkitbox"><span>'
			+ date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate()
			+ '</span>';
		
	    <?php if(!empty($extra_info['breakfast_nums_arr'])){ ?>
		date_tmp=getFullFormatDate(date);
		if(extra_info['breakfast_nums_arr'][date_tmp]!==undefined){
			str += '<span>' + extra_info['breakfast_nums_arr'][date_tmp] + '</span>';
		}
	    <?php }elseif(!empty($bookpolicy_condition['breakfast_nums'])){ ?>
		str+='<span><?php echo $bookpolicy_condition['breakfast_nums']; ?></span>';
	    <?php } ?>
		str+='<span>￥'+tmp_price[i]+'×'+$('#roomnum').val()+'</span></div>';
		
        date=new Date((date/1000+86400)*1000);
    }
    $('[fangfei]').next('[detail]').html(str);
    $('#list_total_price').html('￥'+ (total_price*$('#roomnum').val()).toFixed(2));
    //优惠券
    str='';
    var tmptotal_favour = 0;
    if (coupon_amount>0){
        $('#votelist .ischeck').each(function() {
            tmptotal_favour += $('[rebate]',this).attr('rebate')*1;
            str += '<div class="item webkitbox"><span>'
                + $('[title]',this).text()+'</span><span>'
                + $('[rebate]',this).text()+'</span></div>';
        });
    }else{
        str = '<div class="item webkitbox">无</div>';
    }
    $('#list_total_coupon_price').html('-￥'+tmptotal_favour.toFixed(2));
    $('[youhui]').next('[detail]').html(str);
    //积分
    str=0;
    if ($('#bonus').val()!='') str=$('#bonus').val();
    $('#list_total_bonus_price').html(str+point_name);
}

function getFullFormatDate(date_obj){
	var Year       = date_obj.getFullYear();//ie火狐下都可以
	var Month      = date_obj.getMonth()+1;
	var Day        = date_obj.getDate();
	if(Month<10){
		Month='0'+Month
	}
	if(Day<10){
		Day='0'+Day
	}
	var returnDay=''+Year+Month+Day;
	return returnDay;
	
}

function use_vote(){
	<?php if(!empty($first_state['condition']['no_coupon'])||!empty($first_state['coupon_condition']['no_coupon'])) {?>
	return false;
	<?php }?>
    pageloading();
    $.post('<?php echo Hotel_base::inst()->get_url("RETURN_USABLE_COUPON");?>',{
        datas:JSON.stringify(roomnums),
        start:$('#startdate').val(),
        end:$('#enddate').val(),
        h:$('#hotel_id').val(),
        total:total_price*$('#roomnum').val(),
        price_code:$('#price_codes').val(),
        paytype:$('#pay_type').val(),
        pay_favour:pay_favour
    },function(data){
		temp = '';
    	if(data.hide_coupon==1){
    		$('.usevote').hide();
			coupon_amount = 0;
		}else{
			$('.usevote').show();
			if(data.cards != ''){
				var bool = false;
				if(data.vid == undefined || data.vid == 0){
					bool = true;
				}
				if(bool){
					max_room_night_use = data.count.max_room_night_use;
					max_order_use = data.count.max_order_use;
				}else{
					max_coupon_use = data.count.num;
					if(data.count.effects != undefined && data.count.effects.paytype_counts != undefined){
						paytype_counts = data.count.effects.paytype_counts;
					}
				}
				if(data.selected != undefined){
					coupon_amount = 0;
					coupons = new Object();
					$.each(data.selected, function(k, s){
						coupons[String(s.code)] = s.reduce_cost;
					})
				}
				$.each(data.cards, function(i, n){
					temp += '<li onclick="choose_coupon(this,' + bool + ')"';
					if(coupons[n.code] != undefined){
						coupon_amount = coupon_amount + parseFloat(n.reduce_cost);
						temp += ' class="ischeck"';
						if(n.hotel_use_num_type == 'room_nights' && bool)
							max_room_night_use--;
						else if(n.hotel_use_num_type == 'order' && bool)
							max_order_use--;
						else if(!bool) max_coupon_use--;
					}
					temp += ' code=' + n.code + ' amount="' + n.reduce_cost + '" card_type="' + n.ci_id + '"';
					if(bool) temp += ' max_use_num="' + n.hotel_max_use_num + '" use_num_type="' + n.hotel_use_num_type + '"';
					temp += '><div checkbox class="color_main"><em class="iconfont">&#x4f;</em></div><div class="ui_vote"><p class="bordertop_img"></p><div class="vote_con">';
					temp += '<p rebate=' + n.reduce_cost + ' class="color_main">' + n.reduce_cost + '元</p>';
					temp += '<p title><b>' + n.title + '</b></p>';
					temp += '<p class="color_888">' + n.brand_name;
					if(n.is_wxcard == 1) temp += '--已添加到卡包';
					temp += '</p></div><div class="val_date bd_top">';
					temp += '<p class="color_key"><!--还有4天过期--></p>';
					temp += '<p class="color_888">有效期至';
					if(bool) temp += getLocalTime(n.date_info_end_timestamp);
					else temp += n.valid_date;
					temp += '</p></div></div></li>';
				});
				total_favour = coupon_amount + pay_favour;
				if(coupon_amount != 0){
					$('#coupon_i').html('已选￥' + coupon_amount.toFixed(2));
				}else{
					$('#coupon_i').html('选择优惠券');
				}
			}
			else{
				temp += '<li class="ischeck" ><div class="ui_vote" style="width:90%"><p class="bordertop_img"></p><div class="vote_con"><p class="votename" style="text-align: center;">暂无可用优惠券哦</p></div></div></li>';
			}
		}
        $('#votelist').html(temp);
        getBonusSet();
        $('#total_price').html((real_price-total_favour).toFixed(2));
        removeload();
    },'json');
}

use_vote();
price_detail();
</script>
</html>
