<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
	<meta name="Maker" content="Taoja" tel="13544425200">
	<meta name="format-detection" content="telephone=no">
	<title>直播</title>
	<link type="text/css" href="/public/zb/css/tao.css" rel="stylesheet">
	<style type="text/css">
	html{
		font-size: 12px;
	}
		.goods_title{
			height: 30px;
			background-color: #bfbfbf;
			text-align: center;
			color: white;
			line-height: 30px;
			position: relative;
		}
		.goods_title::after{
			    content: "";
    width: 6px;
    height: 12px;
    background-image: url(/public/zb/img/arrow.png);
    background-size: 100% 100%;
    position: absolute;
    top: 9px;
    margin-left: 5px;
		}
		.goods_icon2{
			width: 20%;
			margin: auto;
			margin-top: 45px;
		}
		.goods_say{
			font-size: 1.4rem;
			text-align: center;
			color: #0bbc09;
			font-weight: bold;
			margin-top: 25px;
		}
		.goods_gift{
			margin-top: 15px;
			font-size: 1rem;
			text-align: center;
			color: black;
			font-weight: bold;
			letter-spacing: 1px;
		}
		.goods_gift>span{
			font-size: 1.41rem;
			font-weight: bold;
		}
		.goods_btns>ib{
			width: 9.58rem;
			height: 2.91rem;
			text-align: center;
			line-height: 2.91rem;
			border-radius: 3px;
		}
		.goods_btns{
			padding: 0px 32px;
			margin-top: 77px;
			justify-content: space-around;
		}
		.back{
			border:#ff9900 1px solid;
			color: #ff9900;
			font-size: 14px;
		}
		.check{
			border:#ff9900 1px solid;
			color: white;
			background-color: #ff9900;
			font-size: 14px;
		}
		.tk{
			position: fixed;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.5);
			top: 0px;
			left: 0px;
			display: flex;
			align-items: center;
		}
		.tk>div:nth-child(2){
			    width: 81.25%;
    margin: 82.5px auto;
    text-align: center;
    background-color: white;
    border-radius: 5px;
    position: relative;
		}
		.tk>div:nth-child(2)>ib{
			    width: 62%;
    margin-top: 12%;
		}
		.goods_ewm_info1{
			    font-size: 14px;
    letter-spacing: 2px;
    margin-top: 22px;
		}
		.goods_ewm_info2{
			font-size: 14px;
    		letter-spacing: 2px;
    		margin-top: 5px;
    		padding-bottom: 30px;
		}
		.leave_tk{
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0px;
			left: 0px;
		}
		@media screen and (min-width:375px){
            html{
                font-size: 14px;
            }
        }
        @media screen and (min-width:414px){
            html{
                font-size: 15px;
            }
        }
	</style>
</head>

<body class="full" style="background-color: white;">
	<div class="goods_title">该商品由<?php echo $name?>提供，点击关注</div>
	<div>
		<div class="goods_icon2">
			<img class="w100" src="/public/zb/img/success.png">
		</div>
		<div class="goods_say">恭喜你购买成功</div>
		<div class="goods_gift">获得<span><?php echo $mibi_change_num;?></span>个虾币</div>
		<flex class="goods_btns">
			<ib class="back">返回直播</ib>
			<ib class="check">查看订单</ib>
		</flex>
	</div>
	<div class="tk none fadeIn">
		<div class="leave_tk"></div>	
		<div>
			<ib>
				<img class="w100" src="/index.php/zb/zb/showQrcode?id=<?php echo $inter_id;?>">
			</ib>
			<div class="goods_ewm_info1">长按识别关注公众号</div>
			<div class="goods_ewm_info2">查看订单</div>
		</div>
	</div>
	<script src="/public/zb/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		$(".leave_tk").on("click",function() {
			$(".tk").removeClass("fadeIn").addClass("fadeOut");
		});
		$(".goods_title").on("click",function(){
			$(".tk").removeClass("none");
		});
		$(".check").on("click",function(){
			location.href = "<?php echo $order_url;?>";
		});
		$(document).on("animationEnd webkitAnimationEnd",".fadeOut",function(){
			$(this).addClass("none fadeIn").removeClass("fadeOut");
		});
		$(".back").on("click",function(){
			location.href = "/index.php/zb/zb/?channel_id=<?php echo $buy_goods_from_channel_id;?>";
		});
	</script>
</body>

</html>
