<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <meta name="Maker" content="Taoja" tel="13544425200">
    <meta name="format-detection" content="telephone=no">
    <title>直播已结束</title>
    <style>
    html {
        font-size: 17px;
        font-family: 微软雅黑;
    }
    
    body {
        position: absolute;
        overflow: hidden;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        margin: 0px;
        font-size: 1rem;
    }
    input{
      font-size: 1rem;
    }
    .bg {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
    }
    
    .bg>img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        -o-object-fit: cover;
    }
    .mu{
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: RGBA(0,0,0,0.7);
    }
    .end{
        position: absolute;
        width: 100%;
        height: 100%;
    }
    .end>div{
        margin:0px 4%;
    }
    .headimg_body>div{
        width: 3.352rem;
        border-radius: 100%;
        overflow: hidden;
        margin:auto;
        margin-top: 4.12rem;
    }
    .headimg_body>div>img{
        width: 100%;
    }
    .user_id{
        text-align: center;
        color: white;
        letter-spacing: 2px;
        margin-top: 0.8rem;
        font-size: 0.88rem;
    }
    .sayend{
        text-align: center;
        color: white;
        letter-spacing: 4px;
        font-size: 1.7rem;
        margin-top: 6rem;
    }
    line{
        display: block;
        color: rgba(255,255,255,0.9);
        font-size: 0.94rem;
        text-align: center;
        letter-spacing: 2px;
        position: relative;
    }
    line:before{
        content: "";
        width: 4.26rem;
        position: absolute;
        border-bottom: 1px solid RGBA(255,255,255,0.2);
        left: 1.47rem;
        top: 50%;
    }
    line:after{
        content: "";
        width: 4.26rem;
        position: absolute;
        border-bottom: 1px solid RGBA(255,255,255,0.2);
        right: 1.47rem;
        top: 50%;
    }
    .l_1{
        margin-top: 5.0rem;
    }
    .l_2{
        margin-top: 2.7rem;
    }
    flex {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
[between] {
    justify-content: space-between;
}
[around] {
    justify-content: space-around;
}
ib{
    display: inline-block;
    vertical-align: middle;
}
.cj{
    text-align: center;
    margin-top: 1.9rem;
}
.cj>ib{
    width: 5rem;
}
.cj>ib>div:nth-child(1){
    color: #fea251;
    font-size: 1.117rem;
}
.cj>ib>div:nth-child(2){
    margin-top: 0.2rem;
    color: RGBA(255,255,255,0.7);
    font-size: 0.7rem;
}
.share{
    width: 11.76rem;
    margin:auto;
    margin-top: 1.17rem;
}
.share img{
    width: 2.29rem;
}
.btn_body {
        height: 2.647rem;
        border-radius: 2.94rem;
        background: -webkit-linear-gradient(bottom left, #fe7e59, #ff4f62, #ff5fb4);
        background: -o-linear-gradient(bottom left, #fe7e59, #ff4f62, #ff5fb4);
        background: linear-gradient(bottom left, #fe7e59, #ff4f62, #ff5fb4);
        color: white;
        line-height: 2.647rem;
        margin-top: 4.5rem;
            text-align: center;
    }
    
    .btn_body img {
        height: 24.4%;
    }
    
    .btn_body>ib {
        margin-left: 11px;
    }
    
    ib {
        display: inline-block;
    }
    .close img{
        width: 0.94rem;
    }
    .close{
        position: absolute;
    right: 0px;
    top: 1.76rem;
    }
    @media screen and (max-width:320px){
    html{
          font-size: 14.5px;
      }
  }
  @media screen and (min-width:414px){
      html{
          font-size: 18.7px;
      }
  }
    </style>
</head>

<body>
    <div class="bg">
        <img src="<?php echo $head_img;?>" id="img_output">
    </div>
    <div class="mu"></div>
    <div class="end">
        <div>
            <div class="sayend" >直播尚未开始</div>
            <line class="l_1">本场直播成就</line>
            <flex around class="cj">
                <ib>
                    <div id="gift_2"><?php echo $daxia;?></div>
                    <div>大虾</div>
                </ib>
                <ib>
                    <div id="gift_1"><?php echo $xiami;?></div>
                    <div>虾米</div>
                </ib>
              
                <!-- <ib>
                    <div>24223</div>
                    <div>最高观看</div>
                </ib> -->
            </flex>
            <!-- <line class="l_2">分享回放至</line>
            <flex around class="share">
                <ib><img src="img/wx.png" id="weixin_btn"></ib>
                <ib><img src="img/pyq.png" id="weixin_s_btn"></ib>
                <ib><img src="img/sin.png"></ib>
            </flex> -->
            <div class="btn_body close_btn" onclick="gotoUrl('<?php echo $replay_url;?>')">看回放
                <ib><img src="/public/zb/img/arrow.png"></ib>
            </div>
        </div>
        <!-- <div class="close close_btn_X" ><img src="img/x.png"></div> -->
    </div>
</body>

<script>
function gotoUrl(url){
	location.href=url;
}

</script>

</html>
