<body>

<?php 
    $bg  = get_cdn_url('public/soma/images/exchange/bg.jpg');
    $bg2 = get_cdn_url('public/soma/images/exchange/bg2.jpg');

    if($theme 
        && $theme->m_get('bg_img') != null 
        && $theme->m_get('bg_img') != '') {
        $bg = $theme->m_get('bg_img');
    }

    if($theme 
        && $theme->m_get('btn_img') != null 
        && $theme->m_get('btn_img') != '') {
        $bg2 = $theme->m_get('btn_img');
    }
?>

<style>
body,html,.box{width:100%;height:100%;}
.box{background:no-repeat;background-size:cover;position:absolute;display: block!important;}
.conter{position: relative;
    margin-top: 15%;
    margin-left: 6.25%;
    width: 87.5%;
    background: #fff;
    border-radius: 1em;
    overflow: hidden;
    text-align: center;
    padding-bottom: 8px;}
.squareimg{ padding-bottom:50%;}
.conter input{border:1px solid #e4e4e4;width:90%; padding:12px}
.btn_main{ width:50%; border-radius:8px; padding:10px}
.box{background-image:url("<?php echo $bg; ?>") }  /* 页面背景图 */
._histroy{ position: relative;margin-top: 10%;width: 100%;z-index: 9;}
._histroy > *{display:inline-block; border-radius:50%; background:rgba(3,152,255,0.8); color:#fff;width:60px; height:60px;}
._histroy span{ padding-top:12px; display:block}
</style>
<div class="box">
	<div class="conter">
    	<div class="squareimg overflow"><img src="<?php echo $bg2; ?>"></div> <!-- 输入框内Banner 图 -->
        <p class="pad3 h30"><?php echo ($theme && $theme->m_get('page_content') && $theme->m_get('page_content') != '') ? $theme->m_get('page_content') : '请输入您的验证码'; ?></p>
        <p><input class="h34 center" type="text" id="in_code"/></p>
        <div class="btn_main martop disable" id="btn">兑换</div>
    </div>
    <div class="center _histroy">
    	<a href="<?php echo $record_url;?>"><span>兑换<br />记录</span></a>
    </div>
</div>
<script>
$(function(){
	var url = "<?php echo Soma_const_url::inst()->get_url('*/package'); ?>";
	$('#in_code').bind('input propertychange',function(){
        if($(this).val()=='') $('#btn').addClass('disable');
        else $('#btn').removeClass('disable');
	});
	function gourl(){ window.location.href=url;	}
	function Reload(){window.location.reload();}
	$('#btn').click(function(){
        if($('#btn').hasClass('disable')) return;
		var code = $('#in_code').val();
		pageloading();
		$.ajax({
            url:"<?php echo Soma_const_url::inst()->get_url('*/*/scaner_exchange',array('id'=>$inter_id));?>",
			async:true,
            type:"post",
            dataType:"json",
            data:{ code:code },
            error:function(data){
				$.MsgBox.Confirm('服务器开小差，请刷新后重试',Reload,Reload,'取消','好的');
            },
            success:function(data){
                if(data.status == <?php echo Soma_base::STATUS_TRUE;?>) {
                	url = data.data.url;
					$.MsgBox.Confirm('恭喜您已通过验证',gourl,Reload,'立即使用','稍后再说');
                } else {
					url = "<?php echo Soma_const_url::inst()->get_url('*/package'); ?>";
                	$.MsgBox.Confirm(data.message,gourl,'','进入商城','重新输入');
                }
            },
			complete:function(){
				removeload();
			}
        });
	})
})
</script>
</body>
</html>
