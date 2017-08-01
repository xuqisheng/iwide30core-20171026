<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">   
    <title>修改支付密码</title>
	<script>
         (function() {
        var scale = 1.0;
        if (window.devicePixelRatio >= 2) {
            scale *= 0.5;
        }
        var text = '<meta name="viewport" content="initial-scale=' + scale + ', maximum-scale=' + scale +', minimum-scale=' + scale + ', width=device-width, user-scalable=no" />';
        document.write(text);
    })();
    </script>
    <script>
        var iWidth = document.documentElement.clientWidth;
        document.getElementsByTagName('html')[0].style.fontSize = iWidth / 40 + 'px';
    </script>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/style/weui.min.css");?>"/>
    <link rel="stylesheet" href="<?php echo base_url("public/member/version4.0/weui/dist/example/example.css");?>"/>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/zepto.min.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/weui/dist/example/example.js");?>"></script>
    <script src="<?php echo base_url("public/member/version4.0/js/login.js");?>"></script>
</head>
<style>
.body{background:#efefef;}
.j_con{margin:1.8rem 1.25rem;background:#fff;border-radius:3.5px;border:1px solid #dedede;padding:0 8.5px;}
.pay_title{color:#ff6804;border-bottom:1px solid #dedede;padding:1.9rem 1rem 1.5rem 1rem;font-size:1.5rem;}
.j_btn{color:#fff;background:#ff6600;margin:6.25rem 2rem;font-size:1.9rem;}
.j_form{margin:7.25rem 1rem;}
.in_row{margin-top:0.9rem;}
.txt_code{width:9.75rem;font-size:1.5rem;display:inline-block;}
.txt_in{width:24rem;border:1px solid #d6d6d6;display:inline-block;}
.txt_in input{width:100%;line-height:2.625rem;height:2.625rem;outline:none;font-size:1.5rem;
-webkit-tap-highlight-color:rgba(0,0,0);tap-highlight-color:rgba(0,0,0);border:none;text-indent:0.3rem;}
.weui_dialog_ft >a{font-size:1.8rem;padding:3% 0;}
</style>
<body class="body" ontouchstart>
    <div class="vip_content j_con" >
        <!--FROM DATA END-->
        <div class="weui_cells_title pay_title">设置储值支付密码:</div>
        <form class="j_form" style="margin:7.25rem 0;" id="SavePayPassword" action="<?php echo base_url("index.php/membervip/balance/save_changepwd");?>" method="post" >
           <div class="in_row">
              <p class="txt_code" style="width:11.25rem;">原支付密码:</p>
              <p class="txt_in"><input class="pay_code" type="password" value="" name="oldpassword" ></p>
           </div>
           <div class="in_row">
              <p class="txt_code" style="width:11.25rem;">新支付密码:</p>
              <p class="txt_in"><input class="pay_co1" type="password" value="" name="newpassword" ></p>
           </div>
           <div class="in_row">
              <p class="txt_code" style="width:11.25rem;">确认密码:</p>
              <p class="txt_in"><input class="pay_co2" type="password" value="" name="confirm_pwd" ></p>
           </div>
		   <a href="#" class="weui_btn j_btn">提交</a>
        </form>
    </div>
    <!--BEGIN START-->
    <!--Loading END-->
    <!--dialog start -->
    <div class="weui_dialog_alert j_flexd" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title" style="font-size:1.5rem;">操作提示</strong></div>
            <div class="weui_dialog_bd wrong" style="font-size:1.5rem;">XXX</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--dialog end -->
    <script type="text/javascript">
    window.onload=function(){
      /* 提交信息 START */
        var form = $("#SavePayPassword");
        var postUrl = form.attr("action");
        form.submit(function(){
            $.post( postUrl ,
            form.serialize(),
            function(result,status){
                if(result.err>1){
                    $('.weui_dialog_bd').html(result.msg);
                    $('#dialog2').attr('style','');
                }else{
                    $('.weui_dialog_bd').html(result.msg);
                    $('#dialog2').attr('style','');
                    if(result.jump_url){
                        window.location.href=result.jump_url;
                    }else{
                        window.location.href="<?php echo base_url('index.php/membervip/center');?>";
                    }
                }
            },'json');
            return false;
        });
      /* 提交信息 END */

      $('.j_btn').click(function(){
        var pay_code=$(".pay_code").val();
        var code=$(".pay_co1").val();
        var code2=$(".pay_co2").val();
        if(pay_code==""||code==""||code2==""){
          $('.weui_dialog_bd').html('密码不能为空');
          $('#dialog2').attr( 'style' , '' );
        }else if(code!==code2){
          $('.weui_dialog_bd').html('确认密码不一致');
          $('#dialog2').attr( 'style' , '' );
        }else{
          form.submit();
        }
      })
      var w_height=$(window).height();
      var weui_navbar_height=$('.weui_navbar').height()
      var weui_tab_bd_height=w_height-weui_navbar_height;
      $('.weui_tab_bd').css('height',weui_tab_bd_height+'px');
    }
    </script>
</body>
</html>
