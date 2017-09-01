<?php include 'header.php'?>
<?php echo referurl('js','submit_order.js?v='.time(),1,$media_path) ?>
<?php echo referurl('js','calendar_wuye.js?v='.time(),3,$media_path) ?>
<?php echo referurl('js','date.js',1,$media_path) ?>
<?php echo referurl('css','calendar.css',2,$media_path) ?>
<link href="<?php echo base_url("public/hotel/su8/styles/submit_order.css");?>" rel="stylesheet">
<style>
    .checkin .date:after{content:"入住"}
    .checkout .date:after{content:"离店"}
    .checkin_time:before{ content:"共"}
    .checkin_time:after{ content:"晚"}
    .n:before{content:""}
    .morning:before{content:"次日"}
    .dawn:before{content:"凌晨"}
</style>
<input type="hidden" id="startdate" name="startdate" value="<?php echo date('Y/m/d',strtotime($startdate));?>" />
<input type="hidden" id="enddate" name="enddate" value="<?php echo date('Y/m/d',strtotime($enddate));?>" />
<input type="hidden" id="hotel_id" name="hotel_id" value="<?php echo $hotel_id;?>" />
<input type="hidden" id="price_codes" name="price_codes" value='<?php echo $price_codes;?>' />
<input type="hidden" id="price_type" name="price_type" value='<?php echo $price_type;?>' />
<input type="hidden" id="prevend" name="prevend" value='0' />
<input type="hidden" id="datas" name="datas" value='<?php echo $source_data;?>' />
<input type="hidden" id="pay_type" name="pay_type" value="<?php echo  empty($pay_ways)?'':$pay_ways[0]->pay_type;?>" />
<header class="order_intro">
    <div class="hotelname"><?php echo $hotel['name']?></div>
    <div class="datetime"><?php echo date("m月d日",strtotime($startdate));?><?php if(empty($athour)){?>-<?php echo date("m月d日",strtotime($enddate));?>  共<?php echo round(strtotime($enddate)-strtotime($startdate))/86400;?>晚<?php }?></div>
    <div class="room_type">房型:<?php foreach($room_list as $rl)echo $rl['name'].' ';?>(<?php echo $first_state['price_name'];?>)</div>
    <div class="sever"><?php if(!empty($first_room['room_info']['imgs']['hotel_room_service']))foreach($first_room['room_info']['imgs']['hotel_room_service'] as $hs)echo $hs['info'].' ';?></div>
</header>

<!-- 预订 -->
<div class="form_list">
    <div class="item ui_btn_block room_count" >
        <span>房间数</span>
        <select class="num big" rid="<?php echo $first_room['room_info']['room_id'];?>">
            <?php for($i=1;$i<=$first_state['least_num'];$i++){?>
                <option value="<?php echo $i;?>"><?php echo $i;?>间</option>
            <?php }?>
        </select>
    </div>
    <div class="item ui_btn_block room_num" <?php if(empty($hotel_config['ROOM_NO_SELECT'])||$hotel_config['ROOM_NO_SELECT']==0){?>style="display:none;"<?php }?>>
        <span>房间号</span>
        <span class="num">前台分配(<tt>1</tt>间)</span>
    </div>
    <div class="item">
        <span>入住人</span>
        <input type="text" id='name' name='name' required class="person" placeholder="请输入姓名" value="<?php echo empty($last_order)?'':$last_order['name']?>" />
    </div>
    <div class="item">
        <span>手机号</span>
        <input type="tel" id='tel' name='tel' required class="phone" placeholder="请输入手机号" value="<?php echo empty($last_order)?'':$last_order['tel']?>" />
    </div>
    <div class="item ui_btn_block" id="keeping">
        <span>保留到</span>
        <span class="keeping" id="must_checkin_time">18:00</span>
    </div>
</div>
<!-- 以上为预订部分 -->
<?php if(!empty($athour)){?>
    <!-- 钟点房/时租房 -->
    <div class="form_list">
        <?php if(!empty($first_state['condition']['book_times'])){?>
            <div class="item ui_btn_block">
                <span>入住时间</span>
                <span class="addhour_date"><?php echo date('H:00',strtotime($first_state['condition']['book_times'][0]));?></span>
            </div>
        <?php }?>
        <?php if(!empty($add_time_service['add_times'])){?>
            <div class="item ui_btn_block">
                <span>加时服务</span>
                <span class="addhour_server">请选择加时服务</span>
            </div>
        <?php }?>
        <?php if(!empty($first_state['condition']['last_time'])){?>
            <div class="item">
                <span>离店时间</span>
                <span class="addhour_leave"><?php echo date('H:00',strtotime($first_state['condition']['last_time']));?></span>
            </div>
        <?php }?>
    </div>
    <?php if(!empty($first_state['condition']['book_times'])){?>
        <div class="ui_pull addhour_date_pull" style="display:none">
            <div class="pulltitle">请选择入住时间</div>
            <ul>
                <?php foreach ($first_state['condition']['book_times'] as $bt){?>
                    <li><?php if( date('H',strtotime($bt))==0)echo '24:00';else echo date('H:00',strtotime($bt));?></li>
                <?php }?>
        </div>
        </div>
    <?php }?>
    <?php if(!empty($add_time_service['add_times'])){?>
        <div class="ui_pull addhour_server_pull" style="display:none">
            <div class="pulltitle">请选择加时服务</div>
            <ul service_id='<?php echo $add_time_service['service_id']?>' onclick='add_service_info($(this))'>
                <li><tt></tt>不加时</li>
                <?php foreach ($add_time_service['add_times'] as $at){?>
                    <li>+<tt><?php echo $at;?></tt>小时<dt><?php echo $at*$add_time_service['service_price'];?></dt>元</li>
                <?php }?>
            </ul>
        </div>
    <?php }?>
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
<?php }?>
<!-- 续住 -->
<div class="form_list" style="display: none">
    <div class="item ui_btn_block ">
        <span>续住时间</span>
        <div class="checkdate" id='checkdate'>
            <span class="checkin" id='checkin'><span class="date">1月1日</span></span>
            <span class="checkout" id='checkout'><span class="date">1月1日</span></span>
            <span class="checkin_time ui_color">1</span>
        </div>
    </div>
    <div class="item">
        <span>续住房型</span>
        <span>高级双人房</span>
    </div>
    <div class="item ui_btn_block">
        <span>续住房号</span>
        <span>8899</span>
    </div>
</div>
<!--div class="form_list" style="padding-left:0">
	<div class="item manysever h4" style="padding:0">
    	<div class="room_img"><img src="http://ihotels.iwide.cn/public/hotel/uploads/a429262687/img/8.jpg"/></div>
    	<p style="padding-top:5%">附加服务</p>
        <em class="float_r iconfont ui_color_gray">&#x34;</em>
        <p class="ui_price ui_color h3 float_r">0</p>
        <p class="ui_color_gray">如有需要请选择</p>
        <ul class="sever_list" style="display:none">
        	<li><span>早餐</span><span class="ui_color"><tt>50</tt>元/份</span>
            	<div class="u_d"><span class="down">-</span><input type="tel" value="0" readonly><span class="up">+</span></div>
            </li>
        	<li><span>早餐</span><span class="ui_color"><tt>50</tt>元/份</span>
            	<div class="u_d"><span class="down">-</span><input type="tel" value="0" readonly><span class="up">+</span></div>
            </li>
        	<li><span>早餐</span><span class="ui_color"><tt>50</tt>元/份</span>
            	<div class="u_d"><span class="down">-</span><input type="tel" value="0" readonly><span class="up">+</span></div>
            </li>
        </ul>
    </div>
</div>
<!-- 以上为 续住 部分 -->
<div class="form_list">
    <div class="item" style="display: none">
        <span>连住优惠</span>
        <span class="pay_way ischeck"><em class="ui_ico ui_ico13"></em><span>连住3天抵减300元</span></span>
    </div>
    <?php if(!empty($member->mem_id)){?>
    	<?php if (!empty($point_consum_rate)){?>
        <div class="item">
            <span>积分抵用</span>
            <input max="<?php echo $member->bonus;?>" type="tel" id='bonus' name='bonus' placeholder="共<?php echo $member->bonus;?>积分，最多可抵<?php echo $member->bonus*$point_consum_rate; ?>元)" />
        </div>
        <?php }?>
    	<?php if (!empty($banlance_part_pay)){?>
        <div class="item" style="display:none">
            <span>余额抵用</span>
            <input max="<?php echo $member->balance;?>" <?php if ($member->balance<=0){echo 'disabled';}?> type="tel" id='balance_part' name='balance_part' value="<?php echo $member->balance;?>" />
        </div>
        <?php }?>
    <?php } ?>
    <?php if(empty($first_state['condition']['no_coupon']) ) {?>
        <div class="item ui_btn_block usevote <?php if(!empty($first_room['no_coupon'])&&$first_room['no_coupon']==1){echo "gray";}?>" abled='<?php if (!empty($first_room['no_coupon']))echo 0;else echo 1;?>'>
            <span>优惠券</span>
			<span id="coupon_i">
		        <?php if(empty($first_room['no_coupon'])||$first_room['no_coupon']==0){echo empty($first_state['coupon_condition']['couprel_info'])?'选择优惠券':$first_state['coupon_condition']['couprel_info']['title'];;}else{echo "此房间不可用优惠券";}	?></span>
        </div>
    <?php }?>
</div>
<div class="form_list">
    <div class="item pay_list" style="padding-bottom:0">
        <span>支付方式</span>
        <ul style="margin-top:3%">
		<?php if(!empty($pay_ways)) foreach($pay_ways as $k=>$pw){?>
                <li pay_type='<?php echo $pw->pay_type;?>' abled='1' class="pay_way <?php if($pw->pay_type=='balance') { if($member->balance==0)echo "gray origin";  }?> <?php if($k==0){?>ischeck<?php }?>">
                    <em class="ui_ico ui_ico13"></em>
                    <span><?php echo $pw->pay_name;?><?php if($pw->pay_type=='balance') { ?>(<?php echo $member->balance;?>元)<?php }?></span></li>
            <?php }?>
            <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
                <li class=<?php if($point_exchange['can_exchange']==0){?>'pay_way gray origin' abled='0'<?php }else{?>"pay_way"  abled='1'<?php }?> id='bonus_pay_way' pay_type='bonus'>
                    <em class="ui_ico ui_ico13"></em>
                    <span>积分兑换(<?php echo $point_exchange['point_need'];?>/<?php echo $member->bonus;?>)</span></li>
            <?php }?>
        </ul>
    </div>
</div>
<?php if(!empty($hotel['book_policy'])){?>
<div class="notic">
    <div class="title">温馨提示</div>
    <div class="content">
        <p><?php echo nl2br($hotel['book_policy']);?></p>
    </div>
</div>
<?php }?>

<div style="padding-top:15%">
    <div class="footfixed">
        <div class="total">合计 
        	<span class="ui_color"><em id="total_price" class="ui_price"><?php echo $total_price;?></em></span>
            <?php if($total_oprice>$total_price){?> 
            <del id="total_oprice" class="ui_price"><?php echo $total_oprice;?></del><?php }?>
        	<span class="price_detail float_r" style="font-size:12px;">明细 <em class="iconfont">&#x49;</em></span>
        </div>
        <span class="submit_btn">提交订单</span>
    </div>
</div>


<div class="ui_pull chooseroom_pull" style="display:none">
    <div class="list">
        <div class="default ischoose">前台分配(<tt>1</tt>间)</div>
        <?php foreach($rooms as $rm){?>
            <div class="item" rid="<?php echo $rm['room_info']['room_id'];?>">
                <div class="room_name"><?php echo $rm['room_info']['name'];?>(<span><?php echo count($rm['room_info']['number_realtime']);?></span>间)</div>
                <?php foreach($rm['room_info']['number_realtime'] as $rnd){?>
                    <div class="roomid" rno="<?php echo $rnd['num_id'];?>" rnn="<?php echo $rnd['room_no'];?>"><span><?php echo $rnd['room_no'];?></span><?php if(!empty($rnd['des'])){?>(<?php echo $rnd['des'];?>)<?php }?></div>
                <?php }?>
            </div>
        <?php }?>
    </div>
    <div class="sure_btn">确定</div>
</div>
<div class="ui_pull vote_pull" style="display:none" title='优惠券'>
    <div class="list">
        <div class="notic">
            <div class="title">温馨提示</div>
            <div class="content">
	            <?php if(!empty($coupon_tips)){ ?><p><?php echo $coupon_tips; ?></p><?php }else{ ?>
					<p>1.原则上每个间夜仅可使用 1 张住房抵用券，特殊注明可叠加使用多张券的房型除外</p>
					<p>2.抵用券不找零、不兑换，使用后不可取消，请谨慎使用</p>
	            <?php } ?>
            </div>
        </div>
        <ul class="votelist" id="votelist"></ul>
    </div>
    <div class="footbtn" style="border-top-color:#fff !important;">确定</div>
</div>

<div class="ui_pull from_bottom keeping_pull" title='保留时间' style="display:none" onClick="toclose()">
    <div class="relative" style="width:100%; height:100%;">
        <div class="box">
            <div class="h3 padding">最晚到店</div>
            <div class="h5 padding" style="padding-top:0; text-align:left">请确保在所选时间之前到达，否则订单可能被酒店取消。14：00前到店可能需要等待入住。</div>
            <div class="list scroll_list">
                <div class="item ischeck"><span>18:00</span></div>
                <div class="item"><span>19:00</span></div>
                <div class="item"><span>20:00</span></div>
                <div class="item"><span>21:00</span></div>
                <div class="item"><span>22:00</span></div>
                <div class="item"><span>23:00</span></div>
                <div class="item"><span class="morning">00:00</span></div>
                <div class="item"><span class="morning">01:00</span></div>
                <div class="item"><span class="morning">02:00</span></div>
                <div class="item"><span class="morning">03:00</span></div>
                <div class="item"><span class="morning">04:00</span></div>
                <div class="item"><span class="morning">05:00</span></div>
                <div class="item"><span class="morning">06:00</span></div>
            </div>
        </div>
    </div>
</div>
<?php /* ob_clean();print_r($first_state['extra_info']['daily_info']);exit; */ ?>
<div class="ui_pull from_bottom price_detail_pull" title='费用明细' style="display:none" onClick="toclose()">
    <div class="relative" style="width:100%; height:100%;">
        <div class="box scroll_list">
            <div class="h3 padding">费用明细</div>
            <div class="list">
                <div class="item item_head"><span>房费</span><span class="ui_color" id="list_total_price">￥<?php echo $total_price;?></span></div>
                <?php
				if(isset($first_state) && isset($first_state['extra_info']['daily_info'])  ){
					$daily_info = (array)$first_state['extra_info']['daily_info'];
                	foreach( $daily_info as $daily_price ){
                ?>
                <div class="item"><span><?php echo $daily_price['RoomDay'];?></span><span><?php echo $daily_price['BreakfastName'] ;?></span><span class="ui_color">￥<?php echo $daily_price['Price'];?>X<span class="list_room_num">1</span></span></div>
                <?php }} ?>              
               <!--  <div class="item item_head"><span>优惠劵</span><span class="ui_color" id="list_total_coupon_price">-￥105</span></div>
                <div class="item"><span>2016-09-18</span><span>优惠劵</span><span class="ui_color">-￥105</span></div>
            -->
            </div>
        </div>
    </div>
</div>

<div class="ui_pull top_tips h5" style="display:none">
	<em class="iconfont h4">&#x4d;</em>由于您到店时间较晚或预订房间数较多，速8需要您预付房费，金额为<span id="tips_total_price"><?php echo $total_price;?></span>元，订单确认后如需取消订单，请在入住前一天18:00前取消，否则将扣除首晚房费。
</div>
<div class="ui_pull tips_box" id="over_tips" style="display:none">
	<div class="box">
        <div class="h2 ui_color" style=" text-align:center;">温馨提示</div>
        <div class="h4">由于您到店时间较晚或预订房间数较多，请您通过拨打4001840018订房。</div>
        <div class="btn_list">
        	<a class="ui_btn mbg" href="tel:4001840018">拨打电话</a>
            <div class="ui_btn ui_color" onClick="$('#over_tips').stop().hide();" style="background:#fff;border:1px solid #d40f20;">返回订单</div>
        </div>
    </div>
</div>
<div class="ui_pull tips_box" id="over_coupon" style="display:none">
	<div class="box">
        <div class="h2 ui_color" style=" text-align:center;">温馨提示</div>
        <div class="h4">由于该房型优惠券使用限制，你所选的<span class="tmp_coupon">0</span>元券只能抵减此订单<span class="tmp_coupon">0</span>元房费</div>
        <div class="btn_list">
            <div class="ui_btn mbg" onClick="$('#over_coupon').hide();">确定</div>
        </div>
    </div>
</div>
</body>
<script>
var rule={};
rule.Maxcoupon = 99999;
rule.MinCheckinTime = '';
rule.MaxCheckinTime = '';
rule.MaxRoomCount = 99999;
rule.CanPrepay =true;
rule.over_room_num = false;//已经超房间数标记
rule.in_time_limit = false;//已经在范围标记
rule.can_submit = true;   //能否提交订单
rule.Balance = 0;  //余额
rule.Daylength = (Date.parse($('#enddate').val()) - Date.parse($('#startdate').val()))/86400000;
rule.Cancoupon = true; //是否能使用优惠券;
rule.Dayrule =[  //每日优惠券数量限制
	{
		"RoomDay": "2016-06-28",
		"CurQuantity": 10,
		"RoomState": 1
	}
]

if (rule.Dayrule.length <=0){
	rule.Cancoupon = false;
	if( !$('.usevote').hasClass('gray')){
		$('.usevote').addClass('gray');
		$('#coupon_i').html('此房间不可用优惠券');
	}
}
	rule.Maxcoupon = <?php if(isset($first_state['extra_info']['coupon_limit'][0]['Amount'])){ echo intval($first_state['extra_info']['coupon_limit'][0]['Amount']);}else{echo 0;}?>;  // 优惠券限额
<?php if (!empty($first_state['extra_info']['guaran']) && $first_state['extra_info']['guaran']['stime'] != "" && $first_state['extra_info']['guaran']['etime'] != ""){?>

	rule.MinCheckinTime = new Date('<?php echo date("Y/m/d",(strtotime($daily_info[0]['RoomDay'])))." ".$first_state['extra_info']['guaran']['stime'];?>'); //min保留时间
	rule.MaxCheckinTime = new Date('<?php 
if($first_state['extra_info']['guaran']['stime'] >= $first_state['extra_info']['guaran']['etime']){
	echo date("Y/m/d",(strtotime($daily_info[0]['RoomDay'])+86401))." ".$first_state['extra_info']['guaran']['etime'];
}else{
	echo date("Y/m/d",strtotime($daily_info[0]['RoomDay']))." ".$first_state['extra_info']['guaran']['etime'];
}
?>'); //max保留时间
	rule.MaxRoomCount = <?php echo intval( $first_state['extra_info']['guaran']['minnum']);?>; // 最大房间数
	rule.CanPrepay = <?php echo $first_state['extra_info']['guaran']['pre_pay']?'true':'false';?>;  //true 表示支持预付

<?php }?>
////////////////////////初始化结束////////////////////////////

	var tips_time;
    <?php if(!empty($member->mem_id)){?>
    	<?php if (!empty($banlance_part_pay)){?>
    rule.Balance =<?php echo $member->balance;?>;
    <?php }} ?>
	function is_maxcoupon(num){
		num=num*1;
		rule.Maxcoupon=rule.Maxcoupon*1;
		if(num>rule.Maxcoupon){
			$('#over_coupon').stop().show();
			$('#over_coupon .tmp_coupon').eq(0).html(num);
			$('#over_coupon .tmp_coupon').eq(1).html(rule.Maxcoupon);
			return rule.Maxcoupon;
		}
		else
			return num;
	}
	function show_tips(){
		if(rule.CanPrepay){
			rule.can_submit = true;
			if ($('.pay_way[pay_type="daofu"]').hasClass('ischeck'))
				$('.pay_way[pay_type="weixin"]').trigger('click');
			$('.pay_way[pay_type="daofu"]').removeClass('ischeck').addClass('gray');
			if( $('.pay_list .gray').length ==$('.pay_list .pay_way').length ){
				$('.submit_btn').addClass('gray');
				rule.can_submit = false;
				$('#over_tips').stop().show();
			}else{
				var _this = $('.top_tips');
				_this.slideDown();
				window.clearTimeout(tips_time);
				tips_time = window.setTimeout(function(){
					if (!_this.is(":hidden")) _this.slideUp();
				},5000);
			}
		}
		else{
			rule.can_submit = false;
			$('.pay_way').addClass('gray');
			$('.submit_btn').addClass('gray');
			$('#over_tips').stop().show();
		}
	}
	function is_Maxtime(_this){    //是否超过最低保留时间
		var _val = _this.find('span').html().split(':')[0]*1;
		
		if ( _this.find('span').hasClass('morning')&&rule.MaxCheckinTime.getHours()>=_val ){
			_val =24 +_val;
		}
		if ( rule.MinCheckinTime!= '' ){
			if (_val>rule.MinCheckinTime.getHours() && _val<=rule.MaxCheckinTime.getHours()+24){
				rule.in_time_limit = true;
				show_tips();
			}else{
				rule.in_time_limit = false;
				if( rule.over_room_num == false){
					button_able();
				}					
			}
		}
	}
	function button_able(){
		rule.can_submit = true;
		$('.pay_way').removeClass('gray');
		$('.pay_way.origin').addClass('gray');
		$('.submit_btn').removeClass('gray');
	}
	function Balance_pay(){ //余额+微信支付;
		if(rule.Balance>0 && rule.Balance<real_price){
			var _p = '现金券支付('+rule.Balance+'元) + 微信支付('+(real_price-rule.Balance-total_favour).toFixed(2)+'元)';
			$('span','.pay_way[pay_type="balance"]').html(_p);
		}else{
			$('span','.pay_way[pay_type="balance"]').html('现金券支付('+rule.Balance+'元)');
		}
	}
	function keeping_item_click(_this){
		_this.addClass('ischeck').siblings().removeClass('ischeck');
		is_Maxtime(_this);
		var _html = '';
		if ( _this.find('span').hasClass('morning') ){
			_html+="次日";
		}
		_html += _this.find('span').text();
		$('.keeping').html(_html);
	}
    var csrf_name='<?php echo $csrf_token;?>';
    var csrf_value='<?php echo $csrf_value;?>';
    var roomnos={};
    var coupons={};
    var add_services={};
    var roomnums=JSON.parse($('#datas').val());
    var total_price=<?php echo $total_price;?>;  //总价
    var real_price=total_price;
    var total_oprice=<?php echo $total_oprice;?>;
    var total_favour=0;
    var coupon_amount=0;
    var max_room_night_use=0;
    var max_order_use=0;
    var room_night_use=0;
    var order_use=0;
    var use_flag='';
    $(function(){
		if(rule.Balance>0) $('.pay_way[pay_type="balance"]').trigger('click');
		if(rule.MaxRoomCount < 2){
			show_tips();
			rule.over_room_num = true;
		}
		Balance_pay();
		var TmpToday = new Date();
		var StartDay = new Date($('#startdate').val());
		if ( TmpToday.getDate()>=StartDay.getDate()&&TmpToday.getMonth()==StartDay.getMonth()){
			var CanselectHour =function(){
				return parseInt($('.keeping_pull .item').eq(0).text().split(':')[0]);
			}
			if (TmpToday.getHours() >=6){
				while ( TmpToday.getHours() >= CanselectHour()&& CanselectHour()>6)
					$('.keeping_pull .item').eq(0).remove();
				keeping_item_click($('.keeping_pull .item').eq(0));
			}
			else{
				while ( TmpToday.getHours() <= CanselectHour()&& CanselectHour()>6)
					$('.keeping_pull .item').eq(0).remove();
				while ( TmpToday.getHours() >= CanselectHour())
					$('.keeping_pull .item').eq(0).remove();
				keeping_item_click($('.keeping_pull .item').eq(0));
			}
			
		}else{
			is_Maxtime($('.keeping_pull .item').eq(0));
		}
		$('.price_detail').click(function(){
			if($('.price_detail_pull').is(':hidden')){
				toshow($('.price_detail_pull'));
				var _h =$(window).height()-$('.footfixed').height();
				$('.price_detail_pull').height(_h);
			}
			else{
				toclose();
			}
		});
		$('#keeping').click(function(){
			toshow($('.keeping_pull'));
			var _h=0;
			for ( var i=0; i<$('.keeping_pull .item').length&&i<6;i++)
				_h +=$('.keeping_pull .item').eq(i).outerHeight()*1;
			$('.keeping_pull .scroll_list').css('max-height',_h+'px');
		});
		$('.keeping_pull .item').click(function(){
			keeping_item_click($(this));
		});
	    <?php if(empty($first_state['coupon_condition']['couprel_info'])){ ?>
        $('.usevote').click(function(){
            if($(this).attr('abled')==0 || $(this).hasClass('gray') || !rule.Dayrule){
                return false;
            }
            pageloading('请稍候',0.5);
            $.post('<?php echo base_url('/index.php/hotel/hotel/return_usable_coupon?id=').$inter_id;?>',{
                datas:JSON.stringify(roomnums),
                start:$('#startdate').val(),
                end:$('#enddate').val(),
                h:$('#hotel_id').val(),
                total:1,
                price_code:$('#price_codes').val()
            },function(data){
                temp='';
                if(data.cards!=''){
					
/* 原来的优惠券使用规则*/
                    max_room_night_use=data.count.max_room_night_use;
                    max_order_use=data.count.max_order_use;

                    $.each(data.cards,function(i,n){
                        temp+='<li onclick="choose_coupon(this)" ';
                        if(coupons[n.code]!=undefined){
                            temp+=' class="ischeck"';
                            if(n.hotel_use_num_type=='room_nights')
                                max_room_night_use--;
                            else if(n.hotel_use_num_type=='order')
                                max_order_use--;
                        }
                        temp+=' code='+n.code+' amount="'+n.reduce_cost+'" max_use_num="'+n.hotel_max_use_num+'" card_type="'+n.ci_id+'" use_num_type="'+n.hotel_use_num_type+'"><div class="checkbox"><em class="ui_ico ui_ico13"></em></div><div class="ui_vote"><p class="bordertop_img"></p><div class="vote_con">';
                        temp+='<p class="ui_price ui_color">'+n.reduce_cost+'元</p>';
                        temp+='<p><b>'+n.title+'</b></p>';
                        temp+='<p class="ui_gray">'+n.brand_name;
                        if(n.is_wxcard==1)temp+='--已添加到卡包';
                        temp+='</p></div><div class="val_date">';
                        temp+='<p class="ui_red">还有'+ calcDate(getLocalDateTime(n.date_info_end_timestamp)) +'天过期</p>';
						temp+='<p class="ui_gray">有效期至'+getLocalTime(n.date_info_end_timestamp)+'</p></div></div></li>'
                        x = n.date_info_end_timestamp + ',';
                    });
                }
                else{
                   temp+='<div style="padding-top:15%;font-size:13px" onclick="toclose()"><em class="iconfont" style="color:#c3c3c3">&#x4a;</em><p style="padding-top:2%;">没有更多可用劵了<span class="ui_color">点击返回</span></p></div>';
                }
                $('#votelist').html(temp);
                $('.page_loading').remove();
            	toshow($('.vote_pull'));
            },'json');
        });
        <?php } ?>
        $('.vote_pull .footbtn').click(function(){
            if(coupon_amount>0)
                $('#coupon_i').html('已优惠￥'+coupon_amount);
            else
                $('#coupon_i').html('选择优惠券');
            toclose();
        });
        $('.room_count .num').change(function(){
            var tmpval=$(this).val();
            real_price=total_price*tmpval;

            //清单列表的处理
            $('.list_room_num').each(function(){
				$(this).html(tmpval);
            });
           
            //$('#total_price').html(real_price.toFixed(2)); // 总价
            $('#total_oprice').html(total_oprice*tmpval);
            $('#tips_total_price').html(real_price);
            $('#list_total_price').html(real_price);
			
            if (tmpval>=rule.MaxRoomCount){
            	rule.over_room_num = true;
            	show_tips();
            }else{
            	rule.over_room_num = false;
            	if(rule.in_time_limit == false){
            		button_able();
            	}
            }
            roomnos={};
            rid=$(this).attr('rid');
            roomnums[rid]=tmpval;
            $('.room_num .num').html('前台分配(<tt>'+tmpval+'</tt>间)');
            $('.default tt').html(tmpval);
            $('.default').trigger('click');
            if(!$('.usevote').hasClass('gray'))$('#coupon_i').html('选择优惠券');
            total_favour-=coupon_amount;
            coupon_amount=0;
            coupons={};
            $('#total_price').html((real_price-total_favour).toFixed(2));
			Balance_pay(); //余额+微信支付;
            <?php if(!empty($point_exchange)&&isset($point_exchange['can_exchange'])&&!empty($member)){?>
            var need_point=<?php echo $point_exchange['point_need'];?>*tmpval;
            $('#bonus_pay_way').find('span').html("积分兑换("+need_point+"/<?php echo $member->bonus;?>)");
            if(need_point><?php echo $member->bonus;?>){
                $('#bonus_pay_way').css('color','gray');
                $('#bonus_pay_way').attr('abled','0');
                $('#bonus_pay_way').parent().find('li:first-child').click();
            }else{
                $('#bonus_pay_way').removeAttr('style');
                $('#bonus_pay_way').attr('abled','1');
            }
            <?php }?>
        });
    })
    function choose_coupon(obj){
        if ( $(obj).hasClass('ischeck')){
            $(obj).removeClass('ischeck');
            if(coupons[$(obj).attr('code')]!=undefined){
                delete(coupons[$(obj).attr('code')]);
                if(getJsonObjLength(coupons)==0)use_flag='';
				if($(obj).attr('amount')*1>rule.Maxcoupon){
					total_favour-=rule.Maxcoupon;
					coupon_amount-=rule.Maxcoupon;
				}
				else{
					total_favour-=$(obj).attr('amount')*1;
               		coupon_amount-=$(obj).attr('amount')*1;
				}
/* 原来的优惠券使用规则*/
                if($(obj).attr('use_num_type')=='room_nights')
                    max_room_night_use++;
                else if($(obj).attr('use_num_type')=='order')
                    max_order_use++;

            }
        }
        else{
            if(!use_flag)
                use_flag=$(obj).attr('use_num_type');
            if(use_flag!=use_flag)return;
/* 原来的优惠券使用规则*/
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

            $(obj).addClass('ischeck');
            coupon_amount+=is_maxcoupon($(obj).attr('amount'));
			if($(obj).attr('amount')*1>rule.Maxcoupon){
				coupons[$(obj).attr('code')]=rule.Maxcoupon;
				total_favour+=rule.Maxcoupon;
			}
			else{
				coupons[$(obj).attr('code')]=$(obj).attr('amount');
            	total_favour+=$(obj).attr('amount')*1;
			}
			console.log(total_favour);
        }
        $('#total_price').html((real_price-total_favour).toFixed(2));
		if ( $('.pay_way[pay_type="balance"]').hasClass('origin')==false){
			if ( total_favour>0){
				$('.pay_way[pay_type="weixin"]').trigger('click');
				$('.pay_way[pay_type="balance"]').addClass('gray');
			}
			else{
				$('.pay_way[pay_type="balance"]').removeClass('gray');
			}
		}
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
    function getLocalDateTime(nS) {
        return new Date(parseInt(nS) * 1000)
    }
    function add_service_info(obj){

    }

    var silde_down=function(_this){
        _this=_this.find('.iconfont');
        _this.toggleClass('rotate').toggleClass('torotate');
        _this.siblings('.sever_list').stop().slideToggle();
    }
    $('.manysever').click(function(e){
        silde_down($(this));
    })
    $('.manysever').trigger('click');

    var tosum = function (){
        var _price,_count,_sum=0;
        for( var i=0; i<$('.manysever li').length;i++){
            _price= parseInt($('.manysever li').eq(i).find('tt').html());
            _count= parseInt($('.manysever li').eq(i).find('input').val());
            _sum=_sum+_price*_count;
        }
        $('.manysever .ui_price').html(_sum);
        _sum+=parseInt($('#total_price').html());
        $('#total_price').html(_sum);
    }
    $('.down').on('touchstart',function(e){
        e.stopPropagation()
        e.preventDefault();
        var tmpval = parseInt($(this).siblings('input').val());
        if( tmpval <=0 ){
            tosum();
            return;
        }
        $(this).siblings('input').val(tmpval-1);
        tosum();
    })

    $('.up').on('touchstart',function(e){
        e.stopPropagation()
        e.preventDefault();
        var tmpval = parseInt($(this).siblings('input').val());
        //if( tmpval >=10 ){ alert('库存不足');return; }// 库存
        $(this).siblings('input').val(tmpval+1);
        tosum();
    })
    
    //var daily_info = JSON.Stringify('<?php //json_encode( $first_state['extra_info']['daily_info'] );?>');

    function calcDate(targetDate){
        //var targetDate = new Date(targetDate);
        var nowDate=new Date();
        var diff = targetDate - nowDate;
        return Math.round(diff/(24*60*60*1000));
    }
</script>

</html>
