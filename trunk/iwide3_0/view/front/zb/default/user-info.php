<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no">
	<title>个人中心</title>
	<style>
  @font-face{
      font-family: 'icon';
      src : url('/public/zb/font/iconfont.ttf') format('truetype'),
            url('/public/zb/font/iconfont.woff') format('woff');
    }
        html{
            font-size: 12px;
            font-family: PingFang SC Light, 微软雅黑;
        }
		.full {
			height: 100%;
		}
		
		block {
			display: block;
			width: 100%;
		}
		
		ib {
			display: inline-block;
			vertical-align: middle;
		}
		
		.none {
			display: none;
		}
		
		flex {
			display: flex;
			align-items: center;
			flex-wrap: wrap;
		}
		
		.center {
			text-align: center;
		}
		
		[between] {
			justify-content: space-between;
		}
		
		[nowrap] {
			flex-wrap: nowrap;
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
			overflow: auto;
			font-size: 1.1rem;
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
		
		body {
			background-color: #f9f9f9;
      font-family: icon;
		}
		
		.headimg {
			width: 17%;
			border-radius: 100%;
			    box-shadow: 0px 0px 10px RGBA(0,0,0,0.1);
		}
		
		.head {
			text-align: center;
			background-color: white;
			position: relative;
		}
		
		.headbg {
			position: absolute !important;
			top: 0px;
			overflow: hidden;
			opacity: 0.1;
		}
		
		.headimg_body {
			padding-top: 27px;
		}
		
		.username_body {
			margin-top: 15px;
			font-size: 1.25rem;
		}
        .head>*{
            position: relative;
        }
		.userinfo>ib {
			width: 50%;
		}
		
		.userinfo {
			margin-top: 25px;
			padding-bottom: 15px;
		}
		
		.userinfo>ib>block:nth-child(1)>ib {
			font-size: 1.8rem;
			font-family: PingFangSC-Regular, sans-serif;
		}
		
		.userinfo>ib>block:nth-child(2)>ib {
			font-size: 1rem;
		}
		
		.userinfo>ib>block:nth-child(2) {
			margin-top: 4px;
		}
		
		
		.info {
			background-color: white;
			margin-top: 15px;
			padding-top: 1px;
			padding-bottom: 40px;
		}
		
		.info>flex,
		.info>div {
			margin-left: 18px;
			margin-top: 20px;
		}
		
		.info>div>ib>img {
			width: 18px;
		}
		
		.zhibo_each {
			width: 154px;
			margin-right: 10px;
			position: relative;
			    box-shadow: 0px 0px 10px RGBA(199,199,199,0.8);
			    border-radius: 7px;
            font-size: 16px;
                display: inline-flex;
    flex-wrap: wrap;
    height: 92px;
    overflow: hidden;
    align-items: center;
		}
		.zhibo_each>img{
      width: 100%;
      height: 100%;
      object-fit: cover;
      -o-object-fit:cover;
    }
		.zhibo_name {
			font-size: 1.2rem;
			color: white;
			bottom: 0.6rem;
			left: 0.6rem;
			transform: scale(0.8);
			transform-origin: left bottom;
			-webkit-transform: scale(0.8);
			-webkit-transform-origin: left bottom;
		}
		
		.zhibo_num {
			font-size: 1.2rem;
			color: white;
			bottom: 0.6rem;
			right: 0.6rem;
			transform: scale(0.8);
			transform-origin: right bottom;
			-webkit-transform: scale(0.8);
			-webkit-transform-origin: right bottom;
		}
		
		.zhibo_btn {
			position: absolute;
			left: 50%;
			width: 2.2rem;
			margin-left: -15px;
			top: 50%;
			margin-top: -15px;
      font-family: icon;
		}
		
		.zhibo_ing {
			position: absolute;
			right: 0.6rem;
			top: 0.6rem;
			width: 32%;
		}
		
		.goods_body {
			flex-wrap: nowrap;
			overflow-x: auto;
			overflow-y: hidden;
			align-items: stretch;
		}
		
		.goods_each {
			max-width: 160px;
			min-width: 160px;
			padding: 20px;
			margin-right: 10px;
			border-radius: 5px;
			border: 1px solid #eee;
			display: inline-flex;
			flex-wrap: wrap;
			align-items: stretch;
		}
		
		.goods_each block:nth-child(1) {
			text-align: center;
		}
		
.goods_name_info {
    color: black;
    font-size: 1.25rem;
    margin-top: 20px;
    text-align: left;
}
		
		.ginfo {
			color: #a1a1a1;
			margin-top: 5px;
			font-size: 1.1rem;
		}
		
		.goods_each flex {
			margin-top: 20px;
		}
		
		.goods_each flex ib:nth-child(1) {
			color: black;
			font-size: 1.4em;
		}
		
		.goods_each flex ib:nth-child(2) {
			font-size: 0.8em;
			background-color: #cb4f3e;
			color: white;
			padding: 1px 3px;
			border-radius: 3px;
		}
		
		.goods_icon {
			width: 5rem;
			height: 5rem;
			border-radius: 100%;
			display: inline-flex;
			justify-content: center;
			overflow: hidden;
		}
		
		.btn_buy {
			color: #fd6a5c;
			border: 1px solid #fd6a5c;
			padding: 5px 15px;
			border-radius: 30px;
            display:inline-flex;
            align-items: center;
            justify-content: center;
		}
		.btn_buy2{
			    color: #fd6a5c;
    border: 1px solid #fd6a5c;
    border-radius: 30px;
    font-size: 0.91rem;
            display:inline-flex;
            align-items: center;
            justify-content: center;
            width: 6rem;
            height: 2rem;
            line-height: 2rem;
		}
		.zhibo_body {
			overflow-x: auto;
			overflow-y: hidden;
			padding: 10px 0px;
		}
		
		.goods_bottom {
			text-align: center;
			align-self: flex-end;
			margin-top: 20px;
		}
		
		.goods_price {
			text-align: left;
			color: black;
			font-size: 1.5rem;
			margin-bottom: 20px;
			font-family: PingFangSC-Regular, sans-serif;
		}
		.goods_price span{
			font-size: 14px;
		}
		.single_img{
			    width: 100%;
		}
		.single_img_body{
			display: inline-flex;
    justify-content: center;
    width: 3.9rem;
    height: 3.9rem;
    border-radius: 100%;
    overflow: hidden;
		}
		.goods_single{
			    padding: 0px 2.5rem;
		}
		.ginfo_single {
			color: #a1a1a1;
			font-size: 0.91rem;
		}
		.userinfo ib block:nth-child(1){
	color:#fea251;
}
		.zhibo_each:nth-child(1),.goods_each:nth-child(1){
			margin-left: 20px;
		}
        .zhibo_body>ib{
            display: inline-flex;
            align-items: stretch;
        }
        .goods_body>ib{
            display: inline-flex;
            align-items: stretch;
        }
        .headbg_muban{
        	position:absolute;
        	width: 100%;
        	height: 100%;
    background: -webkit-linear-gradient(left bottom, #fff, RGBA(255,255,255,0));
    background: linear-gradient(left bottom, #fff, RGBA(255,255,255,0));
        }
        .goods_single_name{
        	max-width: 8.75rem;
        	overflow: hidden;
        	white-space: nowrap;
        	text-overflow: ellipsis;
        	font-size: 1.08rem;
        }
        .zhezhao{
        	width: 100%;
        	height: 100%;
        	background-color: rgba(0,0,0,0.5);
        	border-radius: 7px;
        	left: 0;
    top: 0;
        }
        .gomingxi{
          font-size: 1.16rem;
        }
        .gomingxi>ib:nth-child(1){
          color: #373736;
        }
        .gomingxi>ib:nth-child(2){
          color: #fda557;
          font-size: 1.4rem;
        }
        .userinfo::after{
              content: "";
    position: absolute;
    width: 1.25rem;
    height: 1px;
    background-color: #eee;
    top: -10px;
    left: 50%;
    margin-left: -0.5125rem;
        }
        @media screen and (min-width:375px){
    html{
        font-size: 14px;
    }
            .zhibo_each{
                width: 182px;
                height: 108px;
            }
}
@media screen and (min-width:414px){
    html{
        font-size: 15px;
    }
    .goods_single_name{
        	max-width: 11rem;
        }
    .zhibo_each{
                width: 202px;
                height: 120px;
            }
}
	</style>
</head>

<body>
	<div>
	<div class="head">
		<div class="headbg full">
			<img src="<?php echo $info['headimgurl'];?>" class="w100 left">
		</div>
		<div class="headbg_muban"></div>
		<div class="headimg_body">
			<ib>
				<img class="headimg" src="<?php echo $info['headimgurl'];?>">
			</ib>
		</div>
		<div class="username_body">
			<ib><?php echo $userinfo['nickname'];?></ib>
		</div>
		<flex between nowrap class="userinfo">
			<block class="gomingxi">
        <ib>
          虾米币
        </ib>
				<ib>
					<?php echo $userinfo['mibi'];?>
				</ib>
			</ib>
		</flex>
	</div>
	<div class="info">
		<div>
			<ib><img src="/public/zb/img/kanguo.png"></ib>
			<ib>看过的直播</ib>
		</div>
		<div style="margin-top: 10px;margin-left: 0px;">
			<flex nowrap class="zhibo_body">
                <ib>
                <?php foreach($channel as $data){?>
				<ib class="zhibo_each" data-url="<?php echo $data['url']?>">
				    <?php if($data['head_img']  == ""){?>
					<img src="/public/zb/img/zhibo.png" class="w100">
					<?php }else{
					?>
					<img src="<?php echo $data['head_img'];?>" class="w100">
					<?php }?>
					<ib class="zhezhao absolute"></ib>
					<ib class="absolute zhibo_name"><?php $data['channel_title']?></ib>
					<ib class="absolute zhibo_num" style="display:none">1.2万</ib>
					<ib class="zhibo_btn">
            <img src="/public/zb/img/action.png" class="w100">
          </ib>
					<?php if($data['status']  == 1){?>
					<ib class="zhibo_ing">
						<img src="/public/zb/img/zhiboing.png" class="w100">
					</ib>
					<?php }?>
				</ib>
				<?php }?>
				<!-- <ib class="zhibo_each">
					<img src="/public/zb/img/zhibo.png" class="w100">
					<ib class="absolute zhibo_name">一个萝卜的直播</ib>
					<ib class="absolute zhibo_num">1.2万</ib>
					<ib class="zhibo_btn">
						<img src="/public/zb/img/action.png" class="w100">
					</ib>
					<ib class="zhibo_ing none">
						<img src="/public/zb/img/zhiboing.png" class="w100">
					</ib>
				</ib>
				<ib class="zhibo_each">
					<img src="/public/zb/img/zhibo.png" class="w100">
					<ib class="absolute zhibo_name">一个萝卜的直播</ib>
					<ib class="absolute zhibo_num">1.2万</ib>
					<ib class="zhibo_btn">
						<img src="/public/zb/img/action.png" class="w100">
					</ib>
					<ib class="zhibo_ing none">
						<img src="/public/zb/img/zhiboing.png" class="w100">
					</ib>
				</ib> -->
                </ib>
			</flex>
		</div>
		<div style="margin-top: 15px;">
			<ib><img src="/public/zb/img/tuijian.png"></ib>
			<ib>推荐的商品</ib>
		</div>
		<div style="margin-left: 0px;">
		    <?php if(count($goods) > 1){?>
			<flex class="goods_body">
                <ib>
                <?php foreach($goods as $data){?>
				<ib class="goods_each">
					<block>
						<ib class="goods_icon"><img class="w100" src="<?php echo $data['img'];?>"></ib>
                        <div class="goods_name_info">
                            <div>
                                <ib><?php echo $data['name'];?></ib>
                            </div>
                            <div class="ginfo">
                                <ib>随时可用，可退款</ib>
                            </div>
                        </div>
					</block>
					<block class="goods_bottom">
						<div class="goods_price">
							<ib><span>¥</span><?php echo $data['price'];?></ib>
						</div>
						<div>
						 
							<ib class="btn_buy" onclick="gotourl('<?php echo $data['buy_url']?>')">
								<ib>立即购买</ib>
								<ib style="margin-left: 5px;"><img src="/public/zb/img/arrow2.png" style="width: 5px;"></ib>
							</ib>
						
						</div>
					</block>
				</ib>
				<?php }?>
				
                </ib>
			</flex>
			<?php }?>
			 <?php if(count($goods) == 1){?>
			  <?php foreach($goods as $data){?>
			<flex class="goods_single" between>
				<ib>
					<ib class="single_img_body">
						<img src="<?php echo $data['img'];?>" class="single_img">
					</ib>
					<ib style="margin-left: 0.56rem;">
						<block class="goods_single_name"><?php echo $data['name'];?></block>
						<block class="ginfo_single">随时可用，可退款</block>
					</ib>
				</ib>
				<ib>
					<ib class="btn_buy2" onclick="gotourl('<?php echo $data['buy_url']?>')">
								<ib>立即购买</ib>
								<ib style="margin-left: 5px;"><img src="/public/zb/img/arrow2.png" style="width: 5px;"></ib>
							</ib>
				</ib>
			</flex>
			<?php 
                }   
            }?>
		</div>
	</div>
	</div>
	<script src="/public/zb/js/jquery-1.7.2.min.js"></script>
	<script src="/public/zb/js/Taoja_new.min.js"></script>
    <script src="/public/zb/js/iscroll.js"></script>
    <script src="/public/zb/package.json"></script>
    <script>
        $(".gomingxi").on("touchstart",function(){
            window.location = "http://"+host+"/index.php/zb/zb/mingxi"; 
        });
        var zhibo_scroll = new IScroll(".zhibo_body", {
            scrollX: true,
            scrollY: false,
            click:true
        });
        <?php if(count($goods) > 1){?>
        var goods_scroll = new IScroll(".goods_body", {
            scrollX: true,
            scrollY: false,
            click:true
        });
        <?php }?>
        var body_scroll = new IScroll("body", {
            scrollX: false,
            scrollY: true,
            click:true
        });
        setInterval(function(){
        	zhibo_scroll.refresh();
        	goods_scroll.refresh();
        	body_scroll.refresh();
        },500);
        $(document).on("click",".zhibo_each,.goods_each",function(){
        	var url = this.dataset.url;
        	if(url){
        		window.location = url;
        	}
        });

        function gotourl(url){

        	location.href=url;

        }
    </script>
</body>

</html>
