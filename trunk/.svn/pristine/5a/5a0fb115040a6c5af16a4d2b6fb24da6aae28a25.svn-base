<?php 
    // 是否显示¥符号
    $show_y_flag = true;
    if($package['type'] == $packageModel::PRODUCT_TYPE_POINT)
    {
        $show_y_flag = false;
    }
?>
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
			font-family: Heiti SC;
			overflow: auto;
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
		div,p{
			line-height: none;
		}
		body,
		page {
			font-family: HeiTi SC;
			background-color: #f3f4f8;
		}
		
		.gn_title {
			padding: 10px;
			font-size: 14px;
			margin-top: 10px;
		}
		
		.gn_price {
			border-bottom: 1px solid #e4e4e4;
			margin: 0px 10px;
			font-size: 17px;
			padding-bottom: 10px;
		}
		
		.price {
			font-size: 21px;
			display: inline-block;
		}
		
		.gn_price_old {
			font-size: 12px;
			margin-left: 10px;
			color: gray;
			font-weight: 100;
		}
		
		.like {
			top: -17px;
			right: 10%;
		}
		
		.likeimg {
			width: 35px;
			height: 35px;
		}
		
		.peo_type {
			font-size: 13px;
			color: #999;
		}
		
		.gou {
			width: 13px;
			height: 13px;
			margin: 10px 8px;
		}
		
		.gouk {
			float: left;
		}
		
		.peo {
			height: 33px;
			line-height: 33px;
			margin-right: 30px;
		}
		
		.promis {
			background-color: white;
		}
		
		.dg_title_log,
		.peo {
			line-height: 33px;
		}
		
		.zz {
			height: 33px;
			margin-left: 10px;
			color: #555;
			font-size: 13px;
			line-height: 33px;
		}
		
		.zanzhu,
		.dinggou,
		.goodsinfo,
		.picinfo,
		.position {
			background-color: white;
			margin-top: 0.5rem;
		}
		
		.goodsname {
			background-color: white;
		}
		
		.dg_title_logo_img {
			width: 18px;
			height: 13px;
			margin-top: 10px;
		}
		
		.dg_title_logo_img2 {
			width: 13px;
			height: 13px;
			margin-top: 10px;
		}
		
		.dg_title_logo_img3 {
			width: 12px;
    		margin-top: 8px;
		}
		
		.dg_title {
			height: 33px;
			line-height: 33px;
			margin: 0rem 0.5rem;
			border-bottom: 1px solid #e4e4e4;
		}
		
		.dg_title_name {
			color: #555;
			margin-left: 16px;
			font-size: 13px;
			line-height: 33px;
		}
		
		.dg_body {
			color: #555;
			padding-bottom: 10px;
		}
		
		.padall5 {
			padding: 10px 20px;
		}
		
		.goindex,
		.dingdan {
			width: 20%;
			text-align: center;
			height: 100%;
		}
		
		.dg_body_img {
			width: 100%;
			height: 200px;
		}
		
		.miaosha {
			width: 60%;
			height: 45px;
			text-align: center;
			line-height: 45px;
			color: white;
			background-color: black;
		}
		
		.bottom {
			height: 45px;
			line-height: 45px;
			bottom: 0px;
			text-align: center;
			background-color: white;
			margin: 0px;
			border: 0px;
			left: 0px;
		}
		
		.dg_body_info {
		    font-size: 12px;
		    margin: 10px;
		    /* margin-left: 20px; */
		}
		
		.time_img {
			width: 12px;
			height: 12px;
			padding: 0px 5px;
		}
		
		.gi_info_body,
		.gi_info_title {
			font-size: 12px;
		}
		
		.gi_info_title {
			color: #999;
		}
		
		.djs {
			top: 0px;
			right: 0px;
			background-color: rgba(0, 0, 0, 0.5);
			padding: 3px;
		}
		
		.left_time {
			font-size: 10px;
			color: white;
		}
		
		.wzk {
font-size: 12px;
height: 20px;
line-height: 20px;
/* bottom: 6px; */
position: relative;
text-align: center;
		}
		
		.imgimg {
			width: 18px;
			height: 18px;
		}
		
		.imgk {
			text-align: center;
position: relative;
/* vertical-align: middle; */
width: 100%;
height: 25px;
line-height: 35px;
		}
		
		.peo:last-child {
			margin-right: 0px;
		}
		*{
			box-sizing: content-box !important;
		}
		.page{
			height: 100%;
			display: flex;
			align-items: stretch;
			flex-flow: column;
		}
		.flexrow {
			flex-flow: column;
			justify-content: space-between;
		}
		.flexgrow {
			flex-grow: 1;
		}
		.bg_main{
			background-color:#000;
		}
		.Ldn{
			display: none
		}
	</style>

<body class="myweb">
<?php //var_dump($groupons); exit; ?>
<?php 
	if(isset($killsec)) {
		// 存在秒杀 禁止拼团
		unset($groupons);
	}
?>
	<div class="main w100">
		<div class="photo w100 left relative">
			<img src="<?php echo $package['face_img']; ?>" class="w100 left" style="height:375px;"></img>
			<?php if(isset($killsec)): ?>
				<div class="absolute djs">
					<div class="time left">
						<img class="time_img" src="<?php echo get_cdn_url('public/soma/v4/time.png');?>" />
					</div>
					<div class="left_time left">1</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="goodsname w100 relative left">
			<div class="gn_title"><?php echo $package['name']; ?></div>

			<?php // if(isset($killsec) && !$finish_killsec 
				// && $killsec['killsec_time'] < date('Y-m-d H:i:s',time())): ?>
			<?php if(isset($killsec)): ?>
				<?php // 存在秒杀即显示秒杀价，不管是否开始 ?>
				<div class="gn_price">秒杀价
               		<div class="price"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $killsec['killsec_price']; ?></div>
               		<s class="gn_price_old"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $package['price_package']; ?></s>
            	</div>
            <?php elseif(!empty($groupons) && !$is_expire): ?>
				<div class="gn_price"><?php echo $groupons[0]['group_count'];?>人团
               		<div class="price"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $groupons[0]['group_price']; ?></div>
               		<s class="gn_price_old"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $package['price_package']; ?></s>
            	</div>
            <?php else: ?>
				<div class="gn_price">惊喜价
               		<div class="price"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $package['price_package']; ?></div>
               		<s class="gn_price_old"><?php if($show_y_flag): ?>¥<?php endif; ?><?php echo $package['price_market']; ?></s>
            	</div>
            <?php endif; ?>
			

			<div class="like absolute">
				<img class="likeimg" src="<?php echo get_cdn_url('public/soma/v4/like.png');?>"></img>
			</div>
		</div>
	
		<?php
		    // 无属性
		    $no_attr_flag = true;
		    if($package['can_refund'] != $packageModel::CAN_REFUND_STATUS_FAIL && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE && $package['type'] != $packageModel::PRODUCT_TYPE_POINT) {
		        $no_attr_flag = false;
		    }		

		    if($package['can_gift'] == $packageModel::CAN_T) {
		        $no_attr_flag = false;
		    }		

		    if($package['can_mail'] == $packageModel::CAN_T) {
		        $no_attr_flag = false;
		    }		

		    if($package['can_pickup'] == $packageModel::CAN_T) {
		        $no_attr_flag = false;
		    }		

		    if($package['can_invoice'] == $packageModel::CAN_T) {
		        $no_attr_flag = false;
		    }		

		    if($package['can_split_use'] == $packageModel::CAN_T) {
		        $no_attr_flag = false;
		    }
		?>

		<div class="promis w100 relative left <?php if($no_attr_flag): ?>Ldn<?php endif; ?>">
			
			<?php if($package['can_refund'] != $packageModel::CAN_REFUND_STATUS_FAIL && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE && $package['type'] != $packageModel::PRODUCT_TYPE_POINT): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
						<span class="peo_type"><?php if($package['can_refund'] == $packageModel::CAN_REFUND_STATUS_SEVEN): ?>7天退款<?php else: ?>随时退款<?php endif; ?></span>
				</div>
			<?php endif; ?>

			<?php if($package['can_gift'] == $packageModel::CAN_T): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
					<span class="peo_type">赠送朋友</span>
				</div>
			<?php endif; ?>

			<?php if($package['can_mail'] == $packageModel::CAN_T): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
					<span class="peo_type">邮寄到家</span>
				</div>
			<?php endif; ?>

			<?php if($package['can_pickup'] == $packageModel::CAN_T): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
					<span class="peo_type">到店自提</span>
				</div>
			<?php endif; ?>

			<?php if($package['can_invoice'] == $packageModel::CAN_T): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
					<span class="peo_type">开具发票</span>
				</div>
			<?php endif; ?>

			<?php if($package['can_split_use'] == $packageModel::CAN_T): ?>
				<div class="peo left">
					<div class="gouk">
						<img class="gou" src="<?php echo get_cdn_url('public/soma/v4/gou.png'); ?>"></img>
					</div>
					<span class="peo_type">分时可用</span>
				</div>
			<?php endif; ?>

		</div>
		<div class="zanzhu w100 relative left">
			<div class="zz">本商品由<?php echo $public['name']; ?>提供</div>
		</div>
		<?php if( isset( $isTicket ) && !$isTicket ):?>
        <div class="zanzhu w100 relative left select_type" <?php if(!$spec_product): ?> style="display:none" <?php endif; ?>>
            <div class="webkitbox input_item linkblock zz">
				<?php
                    $spec_compose = json_decode($psp_summary['spec_compose'], true);
                ?>
                <div>请选择  <?php echo implode(' ', $spec_compose['spec_type']); ?></div>
                <div class="color_888 h24 result"></div>
            </div>
        </div>
	    <?php else: ?>
	    	<div class="whiteblock select_type" <?php if(!$spec_product): ?> style="display:none" <?php endif; ?>>
	    		<a href="<?php echo Soma_const_url::inst()->get_url('*/*/ticket_select_time',array('id'=>$inter_id,'pid'=>$_GET['pid'],'tkid'=>$ticketId)); ?>">
		            <div class="webkitbox input_item linkblock">
		                <div>请选择门票时间</div>
		                <div class="color_888 h24 result"></div>
		            </div>
	            </a>
	        </div>
	    <?php endif; ?>
        <?php if($spec_product): ?>
        <div class="ui_pull color_666 page" style="display:none" onClick="toclose();" id="spec_page">
            <div class="flexgrow" style="min-height:40%"></div>
            <div class="bg_fff">
                <div class="flex bd_bottom bg_fff pad10">
                    <div class="specimg"><div class="squareimg"><img src="<?php  if( $package['face_img'] )echo $package['face_img'];else echo base_url('public/soma/images/default2.jpg');?>" /></div></div>
                    <div class="pad10 flexgrow">
        
                        <?php 
                            $low_price = $psp_setting[$package['product_id']][0]['spec_price'];
                            $high_setting = end($psp_setting[$package['product_id']]);
                            $high_price = $high_setting['spec_price'];
                        ?>
        
                        <div class="y color_main specprice"><?php echo $low_price . '~' . $high_price; ?></div>
                        <div class="h22 result"></div>
                    </div>
                    <div class="iconfont h34">&#xe612;</div>
                </div>
            </div>
            <div class="bg_fff _w flexgrow scroll">
                <div class="list_style_1 flexgrow speclist">
                    <div class="webkitbox justify pad10 hide">
                        <span>购买数量</span>
                        <span>
                            <div class="num_control bd webkitbox" style="float:right">
                                <div class="down_num bd_left">-</div>
                                <div class="result_num bd_left"><input id="selece_num" value="1" type="tel" min="1" max="9"></div>
                                <div class="up_num bd_lr">+</div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <footer class="flex bg_fff bd_top">
                <div class="flexgrow pad10 center color_999">取消</div>
                <div class="flexgrow sure_btn btn_main pad10 disable">确认</div>
            </footer>
        </div>
        <?php endif; ?>
		<div class="dinggou w100 relative left">
			<div class="dg_title">
				<div class="dg_title_logo left">
					<img class="dg_title_logo_img" src="<?php echo get_cdn_url('public/soma/v4/dg.png'); ?>"></img>
				</div>
				<div class="dg_title_name left">订购需知</div>
			</div>
			<div class="dg_body left">
				<?php if(!empty($groupons)): ?>
					<div class="dg_body_info">拼团购买超时，人数不足自动退款</div>
				<?php elseif(!empty($auto_rule)): ?>
					<?php foreach($auto_rule as $k=>$v): ?>
						<div class="dg_body_info"><?php echo $v['name']; ?></div>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if(isset($package['order_notice'])  && !empty($package['order_notice'])): ?>
					<div class="dg_body_info"><?php echo $package['order_notice']; ?></div>
				<?php endif; ?>
			</div>
		</div>

		<?php
		$content = unserialize($package['compose']);
		// 商品内容为空时隐藏
		$flag = false;
		if(is_array($content)) {
		    foreach($content as $k=>$v) { 
		        if(empty($v['content'])) continue;
		        $flag = true;
		    }
		}
		?>

		<?php if(!empty($content) && $flag): ?>
			<div class="goodsinfo w100 relative left">
				<div class="dg_title">
					<div class="dg_title_logo left">
						<img class="dg_title_logo_img2" src="<?php echo get_cdn_url('public/soma/v4/neirong.png'); ?>"></img>
					</div>
					<div class="dg_title_name left">商品内容</div>
				</div>
				<div class="dg_body left w100 relative left">
					<div class="gi_info_title w100 left">
						<div class="padall5 left">名称</div>
						<div class="padall5 right">数量</div>
					</div>
					<?php if(is_array($content)): ?>
						<?php foreach($content as $k=>$v): ?>
							<?php if(empty($v['content'])) { continue; } ?>
							<div class="gi_info_body w100 left">
								<div class="padall5 left"><?php echo $v['content']; ?></div>
								<div class="padall5 right"><?php echo $v['num']; ?></div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="picinfo w100 relative left">
			<div class="dg_title">
				<div class="dg_title_logo left">
					<img class="dg_title_logo_img2" src="<?php echo get_cdn_url('public/soma/v4/neirong.png'); ?>"></img>
				</div>
				<div class="dg_title_name left">图文详情</div>
			</div>
			<div class="dg_body left w100 relative left">
				 <?php echo $package['img_detail']; ?>
			</div>
		</div>
		<div class="position w100 relative left">
			<div class="dg_title left" style="border:0.5px;">
				<div class="dg_title_logo left">
					<img class="dg_title_logo_img3" src="<?php echo get_cdn_url('public/soma/v4/location.png'); ?>"></img>
				</div>
				<div class="dg_title_name left">地址:</div>
			</div>
			<div class="left dz_name" style="font-size: 13px;line-height:33px;"><?php echo $package['hotel_address'];?></div>
		</div>
<div class="relative left w100" style="height:54px;"></div>
	</div>
	<div class="bottom w100 fixed none">
		<div data-url="../index/index" class="goindex relative left" onclick="go_to_index()">
			<div class="imgk left">
				<img class="imgimg" src="<?php echo get_cdn_url('public/soma/v4/home.png'); ?>"></img>
			</div>
			<div class="wzk w100 center left">首页</div>
		</div>
		<div class="dingdan relative left" data-url="../../default/my_order/my_order" onclick="go_to_order()">
			<div class="imgk left">
				<img class="imgimg" src="<?php echo get_cdn_url('public/soma/v4/dan.png'); ?>" style="width:17px;"></img>
			</div>
			<div class="wzk w100 center left">订单</div>
		</div>
		<?php if(isset($killsec) && !$finish_killsec 
			&& $killsec['killsec_time'] <= date('Y-m-d H:i:s',time())): ?>
			<div class="miaosha left" onclick="killsec_pay()">立即秒杀</div>
		<?php elseif(isset($killsec) && !$finish_killsec
			&& $killsec['killsec_time'] > date('Y-m-d H:i:s',time())
			&& isset($killsec['is_subscribe']) && $killsec['is_subscribe']==Soma_base::STATUS_TRUE): ?>
			<div class="miaosha left" onclick="killsec_notice()">设置提醒</div>
		<?php elseif(isset($killsec) && isset($finish_killsec) && $finish_killsec ): ?>
			<div class="miaosha left">已售罄</div>
		<?php elseif(!empty($groupons) && !$is_expire): ?>
			<div class="miaosha left" onclick="groupon_pay()">开团购买</div>
		<?php else: ?>
			<?php if( isset( $isTicket ) && !$isTicket ):?>
				<div class="miaosha left select_type" del_onclick="default_pay()" href="<?php echo Soma_const_url::inst ()->get_package_pay(array('pid'=>$_GET['pid'],'id'=>$inter_id));?>";>立即购买</div>
			<?php else: ?>
				<div class="miaosha left select_type" del_onclick="default_pay()" href="<?php echo Soma_const_url::inst()->get_url('*/*/ticket_select_time',array('id'=>$inter_id,'pid'=>$_GET['pid'],'tkid'=>$ticketId,'bType'=>$bType)); ?>";>立即购买</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<script>
	var bottom = document.querySelector(".bottom");
		var windowwidth = window.innerWidth;
		var windowheight = window.innerHeight;
		var main = document.querySelector(".main");
		var fontwidth = $(".dg_title_name").width();
		var dzwidth = windowwidth - 15 - 12 - 16 - fontwidth;
		$(".dz_name").css("width",dzwidth+"px");
		bottom.className = "bottom w100 fixed";
		var startFlag = false;
		<?php if(!$finish_killsec 
			&& $killsec['killsec_time'] < date('Y-m-d H:i:s',time())): ?>
			// 秒杀进行中
			startFlag = true;
		<?php endif; ?>

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
				startFlag = false;
			} else if (kaishi <= 0) {
				wenzi = "距结束还有" + djs("dd天hh小时mm分ss秒", jieshu);
				startFlag = true;
			} else {
				wenzi = "距开始还有" + djs("dd天hh小时mm分ss秒", kaishi);
				startFlag = false;
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
			var hours = Math.floor(leave1 / (3600 * 1000));
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

		<?php if(isset($killsec)): ?>
			// 秒杀倒计时
			var start_time = <?php echo strtotime($killsec['killsec_time']) * 1000; ?>;
			var end_time = <?php echo strtotime($killsec['end_time']) * 1000; ?>;
			new getdjs(start_time, end_time, document.querySelector(".djs"));
		<?php endif; ?>

		function go_to_index() {
			window.location = "<?php echo Soma_const_url::inst()->get_pacakge_home_page(array('id'=>$inter_id)); ?>";
		}

		function go_to_order() {
			window.location = "<?php echo Soma_const_url::inst()->get_soma_ucenter(array('id'=>$inter_id)); ?>";
		}

		<?php if(isset($killsec)): ?>
		
		function killsec_pay() {
			get_in_line();
		}

		function killsec_notice() {
			get_in_line();
		}

		<?php endif; ?>

		function default_pay() {
			window.location = "<?php echo Soma_const_url::inst ()->get_package_pay(array('pid'=>$_GET['pid'],'id'=>$inter_id));?>";
		}

		<?php if(!empty($groupons) && !$is_expire ): ?>
		<?php $g_act_id = $groupons[0]['act_id']; ?>
		function groupon_pay() {
			window.location = "<?php echo Soma_const_url::inst ()->get_groupon_first_pay(array('act_id'=>$g_act_id,'id'=>$inter_id));?>";
		}
		<?php endif; ?>


<?php /**有秒杀**/ if( isset($killsec) &&  !empty($killsec)){ ?>
var subscribe_lock= false;
function get_in_line(){
	if(!startFlag){
		var tmptime= new Date('<?php echo date('Y/m/d H:i:s',strtotime($killsec['killsec_time']));?>');
		var tmpnow = new Date();
		if ( tmptime.getTime()-tmpnow.getTime() < 15*60*1000 ){ // 小于30分钟
			$.MsgBox.Confirm( '秒杀尚未开始,敬请等待' );
			
		}else{
<?php if( isset($killsec['is_subscribe']) && $killsec['is_subscribe']==Soma_base::STATUS_TRUE ): ?>
            if( subscribe_lock== true){
            	$.MsgBox.Confirm( '您已成功订阅！' );
            
            } else {
            	$.MsgBox.Confirm('活动尚未开始，你可以订阅提醒，活动开始前10分钟将提醒您', function(){
            		//window.location.href='';//rightEvent;
            		pageloading('数据发送中，请稍后', 0.2);
            		$.ajax({
            		url:'<?php echo Soma_const_url::inst()->get_url('*/killsec/subscribe_killsec_notice_ajax', array('id'=>$inter_id )); ?>',
            			type: 'POST',
            			dataType:'JSON',
            			data:{
            			act_id :'<?php echo $killsec['act_id']; ?>',
            			},
            			success:function(json){
            				$('.pageloading').remove();
            				subscribe_lock= true;
            				if( json.status == 1 ){
            					$.MsgBox.Confirm( json.message );								
            				} else if( json.status == 2 ){
            					$.MsgBox.Confirm( json.message );
            				}
            			}
            		});
            	},function(){
            		//window.location.href='';//leftEvent;					
            	},'立即订阅', '稍候再说');
            }
<?php else: ?>
            $.MsgBox.Confirm( '秒杀尚未开始,敬请等待' );
<?php endif; ?>
		}
		return false;
	}

	pageloading('排队中，请稍后',0.2);
	$.ajax({
		async: true,
		url:'<?php echo Soma_const_url::inst()->get_url('*/killsec/get_killsec_token_ajax', array('id'=>$inter_id )); ?>',
		type: 'POST',
		dataType:'JSON',
		data:{
			act_id :'<?php echo $killsec['act_id']; ?>',
		},
		success:function(json){
			$('.pageloading').remove();
			if( json.status == 1 ){
				var token= json.data.token;
				var instance_id= json.data.instance_id;
				//$.MsgBox.Confirm('', json.message,function(){
				location.href='<?php echo Soma_const_url::inst()->get_url('*/killsec/package_pay',
					array('id'=>$inter_id, 'pid'=>$_GET['pid'], 'act_id'=>$killsec['act_id'], )); ?>&instance_id='+ instance_id+ '&token='+ token;
				//} );
			} else if( json.status == 2 ){
				$.MsgBox.Confirm( json.message );
			}
		},
		error: function() {
			//失败
			$('.pageloading').remove();
			$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
				//if(e.status == 302||e.status == 307) {
					window.location.reload();
				//}		
			},function(){
					window.location.reload();
			});
			return false;
		},
	　　complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
	　　　　if(status=='timeout'){//超时,status还有success,error等值的情况
				$('.pageloading').remove();
				$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
					//if(e.status == 302||e.status == 307) {
						window.location.reload();
					//}		
				},function(){
						window.location.reload();
				});
	　　　　}
	　　},
		timeout: function() {
			$('.pageloading').remove();
					//失败
			$.MsgBox.Confirm('哎呦喂，被挤爆了，请稍后重试！',function(){
				//if(e.status == 302||e.status == 307) {
					window.location.reload();
				//}		
			},function(){
					window.location.reload();
			});
		}
	})
}
<?php } ?>
var specdata = '',specid = '', _url = '',setting_id='',stock = 0,price = '-';
$('.select_type').click(function(){
	if(specdata==''){
		pageloading();
		$.ajax({
			type: 'GET',
			url: '<?php echo Soma_const_url::inst()->get_url("*/*/ajax_product_spec"); ?>',
			data:{
				id:'<?php echo $this->inter_id; ?>',
				pid:<?php echo $package['product_id']; ?>
			},
			dataType:'JSON',
			error: function(){
				$.MsgBox.Alert('网络开小差了，请刷新重试');
				$('#mb_btn_no').remove();
			},
			success: function(data){
				if(data.status==1){
				  if(!jQuery.isEmptyObject(data.data.data)&&data.data.spec_type!=undefined){
					specdata = data.data;
					for(var i=specdata.spec_type.length-1;i>=0;i--){
						var html = $('<div class="flex flexrow"><div>商品'+specdata.spec_type[i]+'</div><div class="specbtn"></div></div>');
						for(var j=0;j<specdata.spec_name[i].length;j++){
							var specbtn = $('<span class="bg_F8F8F8" specid="'+specdata.spec_name_id[i][j]+'">'+specdata.spec_name[i][j]+'</span>');
							specbtn.get(0).onclick=function(e){
								e.stopPropagation();
                                stock = '-',price = '-',specid = '',_url='',setting_id='';
								var  text = '确认' ;
								$(this).addClass('bg_main').siblings().removeClass('bg_main');
								$('#spec_page .sure_btn').addClass('disable');
								$('.speclist .bg_main').each(function() {
									specid += $(this).attr('specid');
                                });
								for(var i =0;i< specdata.spec_id.length;i++){
									if( specid==specdata.spec_id[i].toString()){
										setting_id = specdata.setting_id[i];
									}
								}
								if( specdata.data[setting_id]!=undefined){
                                    _url = $('.fixed .select_type').attr('href')+'&psp_sid='+specdata.data[setting_id].setting_id;
									stock = Number(specdata.data[setting_id].stock);
									price = Number(specdata.data[setting_id].specprice);
									var _html = ':'
									for(var d = 0;d<specdata.data[setting_id].spec_name.length;d++){
										_html+='"'+specdata.data[setting_id].spec_name[d]+'"';
									}
									$('.result').html(_html);
									if( stock<=0){
										text= '库存不足';
									}else{
										$('#spec_page .sure_btn').removeClass('disable');
									}
								}
								//console.log(stock)
								$('.specprice').html(price);
								$('.select_type span').html(price);
								$('#spec_page .sure_btn').html(text);
                        
							}
							html.find('.specbtn').append(specbtn);
						}
						$('.speclist').prepend(html);
					  }
					  $('.select_type').show();
					  toshow($('#spec_page'));
					}else{
						 window.location.href = $('.fixed .select_type').attr('href');
					}
				}
				else{ $.MsgBox.Alert('似乎出了点问题，请稍后重试');}
			},
			complete: function(data){
				removeload();
			}
		});
	}else{
		if($(this).attr('href')!=undefined&&_url!=''&&stock>0) window.location.href = _url;
		else toshow($('#spec_page'));
	}
})
$('#spec_page .speclist').click(function(e){
	e.stopPropagation();
})
$('#spec_page .sure_btn').click(function(e){
    e.stopPropagation();
	if($(this).hasClass('disable'))return;
	if(specdata.data[setting_id]!=undefined&&_url!=''&&stock>0) window.location.href = _url;
});
	</script>
</body>

</html>
