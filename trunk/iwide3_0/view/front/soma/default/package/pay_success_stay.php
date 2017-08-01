<body>
<style>
body,html{background-color:#fff!important;}
.top_fixed{position: fixed;top: 0; left: 0; width: 100%;line-height: 30px;background: #bfbfbf; text-align: center;}
.top_fixed em{padding-left: 8px;}
.ui_success > div{padding-bottom: 25px;}
.ui_success .btn{padding: 6px 27px;}
.ui_success .btn + .btn{margin-left: 15px;}
.ui_success .tip{padding-bottom: 44px;}
.advs{width: 90.625%; margin: 0 auto 16px auto;position: relative;padding-bottom: 27.5862%;}
.advs_cont{position: absolute;top: 0;right: 0;bottom:0;left: 0;}
.advs_cont img{width: 100%;height: 100%;}
.gap{background-color: #e5e5e5;width: 100%;height: 8px;}
.ui_pull {display: none;}
.ui_pull .box{height: 100%;display: -webkit-box;display: flex;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center}
.ui_pull .cont{background:#fff; border-radius: 4px;padding:15px 37px 37px;display:inline-block;width: 300px;}
.ui_pull .cont .qrcode{width: 160px; height: 160px;margin: 13px auto 17px auto;}
.ui_pull .qrcode img{width: 100%; height: 100%;}
.ui_pull .pull_close{text-align: right;line-height: 1;color: #bfbfbf;margin-right: -10px;}
.ui_pull .pull_close em{padding: 0 10px;}
.ui_pull .tip{text-align: center;line-height: 2.14;}
</style>

<div class="top_fixed h24 color_fff"><span>关注公众号“<?php echo $info['name']; ?>”，随时查看订单信息<em class="iconfont">&#xe61b;</em></span></div>
<div class="ui_success">
  <div>
    <?php if($this->inter_id == 'a490782373'): ?>
      <p class="tip color_0bbc09 h34">恭喜你申请成功</p>
    <?php else: ?>
      <p class="tip color_0bbc09 h34">恭喜你购买成功</p>
    <?php endif; ?>
    <p class="control">
        <?php if($this->inter_id == 'a490782373'): ?>
          <a href="<?php echo $product_detail_link; ?>" class="btn btn_main">再次申请</a>
        <?php else: ?>
          <a href="<?php echo $product_detail_link; ?>" class="btn btn_main">再次购买</a>
        <?php endif; ?>
        <a href="<?php echo $order_detail_link; ?>" class="btn btn_main">查看订单</a>
    </p>
  </div>
</div>
<!--<div class="advs">-->
<!--  <div class="advs_cont">-->
<!--    <img src="#" alt="广告位"/>-->
<!--  </div>-->
<!--</div>-->
<div class="gap"></div>
<!-- 推荐位  -->
 <?php echo isset($block) ? $block: '';?>
<!-- 推荐位  -->
<!-- 弹层-->
<div class="ui_pull">
  <div class="box">
  <div class="cont">
    <div class="pull_close">
      <em>X</em>
    </div>
    <div class="qrcode">
      <img src="<?php echo $qr_code; ?>" alt="二维码"/>
    </div>
    <p id='qrcode-tip' class="tip"></p>
  </div>
  </div>
</div>
<input id="subscribe_status" type="hidden" value="<?php echo $subscribe_status; ?>" >
</body>
<script>

  $(function(){
    var $pull = $('.ui_pull');
    $pull.on('click', '.pull_close', function() {
      $pull.hide();
    })
    $('.top_fixed').click(function(){
      $pull.show();
    })

      var subscribe_status = $('#subscribe_status').val();
      if (subscribe_status) {
          $('#qrcode-tip').html('长按识别进入公众号<br/><span class="h30">随时查看订单</span>');
      } else {
          $('#qrcode-tip').html('你还未关注公众号，<br/>先长按识别关注公众号吧');
          $pull.show();
      }

  })
</script>
</html>
