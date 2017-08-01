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
<style>
		p {
			margin: 0;
			font-family: HeiTi SC
		}
		*{
			    box-sizing: content-box !important;
		}
		.w100 {
			width: 100%
		}
		
		.absolute {
			position: absolute
		}
		
		body {
			width: 100%;
			height: 100%;
			position: absolute;
			margin: 0;
			font-size: 14px;
			font-family: Heiti SC
		}
		
		.right {
			float: right
		}
		
		.full {
			width: 100%;
			height: 100%
		}
		
		.maxscreen {
			width: 100%
		}
		
		.left {
			float: left
		}
		
		.coverpage,
		.coverpage2 {
			width: 100%;
			height: 100%;
			background-color: #000;
			opacity: .8
		}
		
		.fixed {
			position: fixed
		}
		
		.none {
			display: none
		}
		
		.relative {
			position: relative
		}
		
		.left {
			float: left
		}
		
		.fullbg {
			background-size: 100% 100%
		}
		
		.main {
			transform-origin: top left;
			-webkit-transform-origin: top left;
			-o-transform-origin: top left;
			-moz-transform-origin: top left;
		}
		
		.full {
			height: 100%;
		}
		
		body,
		page {
			font-family: HeiTi SC;
			background-color: #f3f4f8;
		}
		
		.big_tu,
		.small_tu {
			margin: 2.5%;
			background-color: white;
		}
		
		.big_tu_kuang {
			padding: 2.5%;
		}
		
		.big_tu_img {
			width: 100%;
		}
		
		.big_tu_name {
			text-align: center;
			font-size: 1.2rem;
			font-weight: bold;
		}
		
		.xiahuaxian {
			display: inline-block;
			font-size: 1.1rem;
			font-weight: bold;
			padding: 0.4rem 0.8rem;
			border-bottom: 1px solid black;
		}
		
		.big_tu_price {
			text-align: center;
			margin-top: 0.5rem;
		}
		
		.big_tu_info {
			text-align: center;
			color: #888;
			padding: 1.1rem 1rem;
			    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
		}
		
		.small_tu_kuang {
			width: 35%;
			overflow: hidden;
			padding: 2%;
		}
		
		.small_tu_kuang2 {
			width: 60%;
		}
		
		.small_tu_name {
			text-align: center;
			padding: 0.5rem;
			font-size: 1rem;
			font-weight: bold;
			margin-top: 1rem;
		}
		
		.small_tu_info {
			font-size: 0.6rem;
			text-align: center;
			padding: 0.5rem;
			color: #888;
			overflow: hidden;
    text-overflow: ellipsis;
			white-space: nowrap;
		}
		
		.small_tu_price {
			font-size: 1rem;
			font-weight: bold;
			padding: 1.5rem 0rem;
			width: 45%;
			text-align: center;
		}
		
		.small_tu_btn {
			width: 45%;
			text-align: center;
			font-size: 1rem;
			margin: 1rem 0rem;
		}
		
		.btn {
			padding: 0.5rem 0.5rem;
			display: inline-block;
			border-radius: 5px;
			background-color: black;
			color: white;
		}
		
		.small_tu {
			width: 95%;
		}
		.djs {
			top: 0px;
			right: 0px;
			background-color: rgba(0, 0, 0, 0.5);
			margin: 2.5%;
			padding: 3px;
		}
		.small_tu .djs{
			min-width: 112px;
			margin: 5.5%;
			transform-origin: right top;
			transform: scale(0.9);
		}
		.left_time {
			font-size: 10px;
			color: white;
		}
		.time_img {
			width: 12px;
			height: 12px;
			padding: 0px 5px;
		}
	</style>

<body>
	<?php //var_dump($products);exit; ?>
	<?php $cnt = 0; ?>
	<?php foreach($products as $k=>$v): ?>
		<?php
            // 是否显示¥符号
            $show_y_flag = true;
            if($v['type'] == $packageModel::PRODUCT_TYPE_POINT)
            {
                $show_y_flag = false;
            }
        ?>
		<?php $cnt ++; if($cnt > 2) { break; } ?>
		<div class="big_tu" onclick="go_to_detail(<?php echo $v['product_id']; ?>)">
			<div class="big_tu_kuang relative">
					<img class="big_tu_img" src="<?php echo $v['face_img']; ?>">
				<?php if(isset($v['killsec'])): ?>
					<div class="absolute djs" data-action="<?php echo strtotime($v['killsec']['killsec_time']) * 1000 + 60000 ; ?>" data-end="<?php echo strtotime($v['killsec']['end_time']) * 1000 ; ?>">
						<div class="time left">
							<img class="time_img" src="<?php echo get_cdn_url('public/soma/v4/time.png');?>" />
						</div>
						<div class="left_time left">1</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="big_tu_name">
				<?php echo $v['name']; ?>
			</div>
			<div class="big_tu_price">
				<?php //if(isset($v['killsec'])
					// && $v['killsec']['killsec_time'] < date('Y-m-d H:i:s', time())): ?>
				<?php if(isset($v['killsec'])): ?>
					<?php // 只要有秒杀都显示秒杀价,存在秒杀一律禁止拼团 ?>
					<?php unset($v['groupon']); ?>
					<div class="xiahuaxian"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['killsec']['killsec_price']; ?></div>
				<?php elseif(isset($v['groupon'])): ?>
					<div class="xiahuaxian"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['groupon']['group_price']; ?></div>
				<?php else: ?>
					<div class="xiahuaxian"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['price_package']; ?></div>
				<?php endif; ?>
			</div>
			<div class="big_tu_info">
				摘要：<?php echo $v['keyword']; ?>
			</div>
		</div>
	<?php endforeach; ?>

	<?php $cnt = 0; ?>
	<?php foreach($products as $k=>$v): ?>
		<?php
            // 是否显示¥符号
            $show_y_flag = true;
            if($v['type'] == $packageModel::PRODUCT_TYPE_POINT)
            {
                $show_y_flag = false;
            }
        ?>
		<?php $cnt ++; if($cnt <= 2) { continue; } ?>
		<div class="small_tu left">
			<div class="small_tu_kuang left relative">
				<img class="small_tu_img w100" src="<?php echo $v['face_img']; ?>" />
				<?php if(isset($v['killsec'])): ?>
					<div class="absolute djs" data-action="<?php echo strtotime($v['killsec']['killsec_time']) * 1000 + 60000; ?>" data-end="<?php echo strtotime($v['killsec']['end_time']) * 1000 ; ?>">
						<div class="time left">
							<img class="time_img" src="<?php echo get_cdn_url('public/soma/v4/time.png');?>" />
						</div>
						<div class="left_time left">1</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="small_tu_kuang2 left">
				<div class="small_tu_name"><?php echo $v['name']; ?></div>
				<div class="small_tu_info">摘要：<?php echo $v['keyword']; ?></div>
				<div class="small_tu_price_and_btn">
					<?php //if(isset($v['killsec']) && $v['killsec']['killsec_time'] < date('Y-m-d H:i:s', time())): ?>
					<?php if(isset($v['killsec'])): ?>
						<?php // 只要有秒杀都显示秒杀价,存在秒杀一律禁止拼团 ?>
						<?php unset($v['groupon']); ?>
						<div class="small_tu_price left"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['killsec']['killsec_price']; ?></div>
					<?php elseif(isset($v['groupon'])): ?>
						<div class="small_tu_price left"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['groupon']['group_price']; ?></div>
					<?php else: ?>
						<div class="small_tu_price left"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $v['price_package']; ?></div>
					<?php endif; ?>
					<div class="small_tu_btn left">
						<?php // if(isset($v['killsec']) && $v['killsec']['killsec_time'] < date('Y-m-d H:i:s', time())): ?>
						<?php if(isset($v['killsec'])): ?>
							<?php // 存在秒杀都显示去秒杀，不管是否开始 ?>
							<div class="btn" onclick="go_to_detail(<?php echo $v['product_id']; ?>)">去秒杀</div>
						<?php elseif(isset($v['groupon'])): ?>
							<div class="btn" onclick="go_to_detail(<?php echo $v['product_id']; ?>)">去拼团</div>
						<?php else: ?>
							<div class="btn" onclick="go_to_detail(<?php echo $v['product_id']; ?>)">去看看</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

</body>
	<script>
		function getdjs(num1, num2,obj) {
			this.wenzi = "";
			this.num1 = num1;
			this.num2 = num2;
			this.obj = obj;
			this.interval();
		}
		getdjs.prototype.interval = function(){
			var nowtime = (new Date()).getTime();
			var kaishi = this.num1 - nowtime;
			var jieshu = this.num2 - nowtime;
			if (jieshu <= 0) {
				wenzi = "活动已结束";
			} else if (kaishi <= 0) {
				wenzi = "距结束:" + djs("hh:mm:ss", jieshu);
			} else {
				wenzi = "距开始:" + djs("hh:mm:ss", kaishi);
			}
			var target = this.obj.children[1];
			target.innerHTML = wenzi;
			setTimeout(function(){
				this.interval();
			}.bind(this),1000);
		}
		var djs = function(fmt, ts) {
			var days = Math.floor(ts / (24 * 3600 * 1000));
			var leave1 = ts % (24 * 3600 * 1000);
			var hours = Math.floor(ts / (3600 * 1000));
			var leave2 = leave1 % (3600 * 1000) //计算小时数后剩余的毫秒数
			var minutes = Math.floor(leave2 / (60 * 1000))
				//计算相差秒数
			var leave3 = leave2 % (60 * 1000) //计算分钟数后剩余的毫秒数
			var seconds = Math.round(leave3 / 1000)
			var o = {
				"d+": days, //日 
				"h+": hours, //小时 
				"m+": minutes, //分 
				"s+": seconds, //秒  
			};
			if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (dateoj.getFullYear() + "").substr(4 - RegExp.$1.length));
			for (var k in o)
				if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
			return fmt;
		}
		var djs_arr = document.querySelectorAll(".djs");
		var curindex =0;
		for(var i = 0 ; i < djs_arr.length ; i++){
			var action = djs_arr[i].dataset.action;
			var end = djs_arr[i].dataset.end;
			if(action == undefined || action == ""){
				djs_arr[i].className = "absolute djs none";
			}else{
				new getdjs(action,end,djs_arr[i]);
			}
			curindex = i;
		}

		function go_to_detail(pid) {
			var url = "<?php echo Soma_const_url::inst()->get_package_detail(array('id'=>$inter_id));?>";
			window.location = url + '&pid=' + pid;
		}

var hideload = function(){
	$('.ui_loadmore').remove();	
}
var showload =function(str){
	hideload();
	if(str==undefined)
	var tmp = "<div class='center ui_loadmore' style='padding:20px; clear:both'><em class='ui_loading'></em></div>";
	else
	var tmp = "<div class='center ui_loadmore color_888 h20' style='padding:20px; clear:both'>"+str+"</div>";
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
						var curTimes =new Date().getTime();
						var killtime,endtime;
						var show_y_flag = true;
						for(var n in  data.data){
							show_y_flag = true;
                            if(data.data[n].type == <?php echo $packageModel::PRODUCT_TYPE_POINT;?>)
                            {
                                show_y_flag = false;
                            }
							str +='<div class="small_tu left">';
							str +='<div class="small_tu_kuang left relative">';
							str +='<img class="small_tu_img w100" src="'+data.data[n].face_img+'">';
							if (data.data[n].killsec!=undefined){
								killtime = new Date(Date.parse(data.data[n].killsec.killsec_time.replace(/-/g,"/")));
								endtime = new Date(Date.parse(data.data[n].killsec.end_time.replace(/-/g,"/")))
							str +='<div class="absolute djs" data-action="'+(killtime.getTime()+60000)+'" data-end="'+endtime.getTime()+'">';
							str +='<div class="time left"><img class="time_img" src="<?php echo get_cdn_url('public/soma/v4/time.png');?>" /></div>';
							str +='<div class="left_time left">1</div></div>';
							}
							str +='</div>';
							str +='<div class="small_tu_kuang2 left">';
							str +='<div class="small_tu_name">'+data.data[n].name+'</div>';
							str +='<div class="small_tu_info">摘要：'+ data.data[n].keyword+'</div>';
							str +='<div class="small_tu_price_and_btn">';
							//if(data.data[n].killsec!=undefined&& killtime.getTime()< curTimes )
							if(data.data[n].killsec!=undefined)
							{
								data.data[n].groupon=undefined;
								str +='<div class="small_tu_price left">';
								if(show_y_flag)
								{
									str +='¥';
								}
								str +=data.data[n].killsec.killsec_price+'</div>';
							}
							else if(data.data[n].groupon!=undefined)
							{
								str +='<div class="small_tu_price left">';
								if(show_y_flag)
								{
									str +='¥';
								}
								str +=data.data[n].groupon.group_price+'</div>';
							}
							else
							{
								str +='<div class="small_tu_price left">';
								if(show_y_flag)
								{
									str +='¥';
								}
								str +=data.data[n].price_package+'</div>';
							}
							str +='<div class="small_tu_btn left"><div class="btn" onclick="go_to_detail('+data.data[n].product_id+')">';
							if(data.data[n].killsec!=undefined)
							str +='去秒杀';
							else if(data.data[n].groupon!=undefined)
							str +='去拼团';
							else
							str +='去看看';
							str +='</div></div></div></div></div>';
						}
						$('body').append(str);
						var _djs_arr = document.querySelectorAll(".djs");
						for(var i = curindex ; i < _djs_arr.length ; i++){
							var action = _djs_arr[i].dataset.action;
							var end = _djs_arr[i].dataset.end;
							if(action == undefined || action == ""){
								_djs_arr[i].className = "absolute djs none";
							}else{
								new getdjs(action,end,_djs_arr[i]);
							}
							curindex = i;
						}
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
