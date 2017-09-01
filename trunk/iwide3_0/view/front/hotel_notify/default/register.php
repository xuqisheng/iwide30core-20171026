<?php include 'header.php'?>
<style>
body,html{background:#ffffff; color:#555}input,select{width:85%; font-size:14px}.circle,.wait>*{border-radius:50%;}
.steps .circle,.wait{ width:3.4rem; height:3.4rem; line-height:3.4rem; font-size:1.5rem; font-family:arial; display:inline-block; }
.not .circle{background:#efefef}
.steps >* {position:relative}
.steps hr{ position:absolute; width:40%; left:-20%; top:1.8rem;}
.form{width:80%; margin:7% auto; overflow:hidden; border:1px solid #efefef; padding-left:2.2rem; box-shadow:-5px 8px 25px rgba(180,180,180,0.1)}
.form > *{padding:12px 0;}
.form .iconfont:first-child{ margin-left:-1.4rem}
.wait{background:#272636; line-height:3rem}
.wait>*{background:#fff; display:inline-block; width:7px; height:7px; line-height:0; margin:0 2px}
</style>
<body>
<div class="page" style="display:none">
    <div class="steps webkitbox center pad15 h20">
        <div class="">
            <p class="circle bg_main">1</p>
            <p class="martop">登记信息</p>
        </div>
        <div class="not">
            <hr>
            <p class="circle bg_main">2</p>
            <p class="martop">后台审核</p>
        </div>
        <div class="not">
            <hr>
            <p class="circle bg_main">3</p>
            <p class="martop">审核结果</p>
        </div>
    </div>
    <div class="form bdradius">
        <div class="bd_bottom">
            <em class="iconfont">&#x38;</em>
            <input name="hname" placeholder="输入姓名">
        </div>
        <div>
            <em class="iconfont">&#x41;</em>
            <select name="hid">
            <?php foreach ($hotels as $h) {?>
                <option value="<?php echo $h['hotel_id'];?>"><?php echo $h['name'];?></option>
            <?php }?>
            </select>
            <em class="iconfont">&#x2B;</em>
        </div>
    </div>
    <div class="foot_btn" style="margin-top:50px"><button class="btn_void color_main bdradius disable">提交</button></div>
</div>
<div class="page center h20" style="display:none">
	<div class="wait circle color_fff" style="margin-top:50px"><span></span><span></span><span></span></div>
	<div class="martop">等待审核</div>
</div>
<?php if(isset($status)){?>
<div class="page center h20" style="display:none">
	<div style="margin-top:50px"><img style="width:4rem;" src="<?php echo base_url('public/hotel_notify/default/images/success.png');?>"></div>
	<div class="martop">已绑定：<?php echo $hotelname;?></div>
</div>
<?php }?>
</body>
<script>
var curstep = 1; ///设置状态
	$('input').bind('input propertychange',function(){
		if($.trim($(this).val())!='') $('button').removeClass('disable');
		else  $('button').addClass('disable');
	})
var sub = true;
	$('button').click(function(){
		if($(this).hasClass('disable'))return;
        var hname = $('input[name=hname]').val();
        var hid = $('select[name=hid]').val();
        if(sub){
            sub = false;
            if(toAjax(hname,hid)){           
            }else{
                sub=true;
            }
        }else{
            alert('正在提交数据');
        }
	})
    function toAjax(hotel_name,hotel_id){
        var ret = true;
        $.ajax({
            url:"<?php echo site_url('hotel_notify/hotel_notify/toRegister');?>"+"?name="+hotel_name+"&hotel_id="+hotel_id,
            dataType: "json",
            success:function(data){
                if(data.errmsg=='ok'){
                    alert('提交成功');
                    curstep=2;
                    showpage();
                }else{
                    alert(data.errmsg);
                    ret = false;
                }
            },
            error:function(){
                alert('提交失败');
                ret = false;
            }
        });
        return ret;
    }
	function showpage(){$('.page').eq(curstep-1).show().siblings('.page').hide();}
    <?php if(isset($status)&&$hotelname!=''){?>
        <?php if($status=='complete'){?>
        curstep=3;
        <?php }else{?>
        curstep=2;
        <?php }?>
    <?php }?>
    showpage();
</script>
</html>
