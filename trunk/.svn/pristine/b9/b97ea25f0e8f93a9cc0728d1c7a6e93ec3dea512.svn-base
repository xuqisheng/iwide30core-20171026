
<?php include 'header.php' ?>

<?php if($comment==0 || !empty($comment_info)) {
    redirect( site_url ( 'hotel/hotel/hotel_comment') . '?id=' . $this->inter_id .'&h='.$order['hotel_id'] );
 } else { ?>

<div class="gradient_bg wrapper">
    <div class="pad_t30">
        <p class="color2 h30 mar_b20"><?php echo $order['first_detail']['roomname'];?></p>
        <p class="color3 h24 mar_b20"><?php echo $order['hname']?></p>
        <p class="h24">
            <span class="color3 mar_r5 inblock">入住</span>
            <span class="color2 mar_r40 inblock"><?php echo date('m/d',strtotime($order['startdate']))?></span>
            <span class="color3 mar_r5 inblock">离店</span>
            <span class="color2 mar_r40 inblock "><?php echo date('m/d',strtotime($order['enddate']))?></span>
            <span class="color3 mar_r20 inblock">共<?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚</span>
        </p>
    </div>
    <div class="layer_bg border_radius mar_t80 pad_lr60 pad_b30 pad_t60">
        <div class="center mar_b80">
            <p class="relative inblock w40 pad_t10 pad_b20 h32 color1">酒店评分<span class="shadow_b"></span></p>
        </div>
        <div>
            <div class="comment_score_rows webkitbox">
                <p class="mar_l30 h28 color1"><?php if(isset($comment_config->facilities_score)){ echo $comment_config->facilities_score;}else{ echo "设施";}?></p>
                <div class="flexgrow h38 color5 star"  id="facilities_score" value='5'>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                </div>
            </div>
            <div class="comment_score_rows webkitbox">
                <p class="mar_l30 h28 color1"><?php if(isset($comment_config->clean_score)){ echo $comment_config->clean_score;}else{ echo "卫生";}?></p>
                <div class="flexgrow h38 color5 star"  id="clean_score" value='5'>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                </div>
            </div>
            <div class="comment_score_rows webkitbox">
                <p class="mar_l30 h28 color1"><?php if(isset($comment_config->service_score)){ echo $comment_config->service_score;}else{ echo "服务";}?></p>
                <div class="flexgrow h38 color5 star" id="service_score" value='5'>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                </div>
            </div>
            <div class="comment_score_rows webkitbox">
                <p class="mar_l30 h28 color1"><?php if(isset($comment_config->net_score)){ echo $comment_config->net_score;}else{ echo "网络";}?></p>
                <div class="flexgrow h38 color5 star" id="net_score" value='5'>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                    <em class="iconfont pulse">&#xE017;</em>
                </div>
            </div>
        </div>
    </div>
    <?php if(isset($comment_config) && !empty($comment_config->sign)){ ?>
    <div class="mar_t80">
        <p class="h24 color3 mar_b40">出游类型</p>
        <div class="condition h30 color1">
            <?php foreach($comment_config->sign as $sign){ ?>
            <div data-type="<?php echo $sign;?>"><p><?php echo $sign;?></p><span class="iconfont main_color1 h48">&#xE031;</span></div>
            <?php }?>
        </div>
    </div>
    <?php }?>
    <div class="mar_t80">
        <p class="h24 color3 mar_b40">发表评论</p>
        <textarea class="comment_publish_text h28 color1" maxlength="200" placeholder="亲~ 住的舒服吗?服务满意吗?"></textarea>
        <p class="h24 txt_r bd_bottom pad_b10"><span class="color1" id="publish_num">0</span><span class="color3">/200</span></p>
    </div>
    <div class="mar_t40">
        <div class="comment_publish_img clearfix addimg">
<!--             <div>
                <img src="/public/hotel/bigger/images/beijing.jpg" alt="">
                <p><em class="iconfont color1 h32">&#xE028;</em></p>
            </div> -->
            <div class="comment_publish_img_border" size="5" id="add_publish_comment">
                <em class="iconfont comment_publish_photo">&#xE030;</em>
                <span class="comment_publish_photo_word h22 mar_t10">添加图片</span>
            </div>
        </div>
    </div>
    <div class="mar_t50 center">
        <div id="look_comment" class="iconfont button h34" style="display: inline-block;width:85%;" onclick="sub_comment()">立即评价</div>
    </div>
    <?php include 'footer.php' ?>

</div>
<section class="whole_eject" style="display:none;" id="comment_success">
    <div class="whole_eject_small bg_282828">
        <!-- <em class="close iconfont color6">&#xE000;</em> -->
        <div class="center">
            <div class="main_color1 h60 mar_t40"><em class="iconfont comment_light_gou">&#xE032;</em></div>
            <p class="color1 mar_t60 h30">评论提交成功</p>
            <p class="color2 mar_t60 h28">感谢您的评价!</p>
            <a class="button spacing h32 mar_t80"  href="<?php echo Hotel_base::inst()->get_url("HOTEL_COMMENT",array('h'=>$order["hotel_id"]));?>">查看评价</a>
        </div>
    </div>
</section>


<?php }?>

</body>
<script>
wx.config({
    debug:false,
    appId:'<?php echo $signPackage["appId"];?>',
    timestamp:<?php echo $signPackage["timestamp"];?>,
    nonceStr:'<?php echo $signPackage["nonceStr"];?>',
    signature:'<?php echo $signPackage["signature"];?>',
    jsApiList: [
        'hideOptionMenu',
        'chooseImage',
        'uploadImage'
    ]
});

var prevend=0;
var _html='';
var images_url = [];
var sign = [];
var limited_upload = 4;


function totest(){
    if ($('#msg').val()==''){
        $.MsgBox.Alert("还未填写评论内容")
        return false;
    }
    if ($('#msg').get(0).value.length<=5){
        $.MsgBox.Alert("评论内容不得少于5个字符");
        return false;
    }

}
function sub_comment(){
    var post_url='';
    if(prevend==1)return false;
    if ($('.comment_publish_text').val()==''){
        $.MsgBox.Alert("还未填写评论内容")
        return false;
    }
    if ($('.comment_publish_text').get(0).value.length<=5){
        $.MsgBox.Alert("评论内容不得少于5个字符")
        return false;
    }

    var ranges=$('#add_publish_comment');
    $.each(ranges,function(i,n){
        var img_url=$(n).find('input[name="img_url[]"]').val();
        if(img_url){
            if(post_url){
                post_url +=','+img_url;
            }else{
                post_url +=img_url;
            }
        }
    });

    prevend=1;
    pageloading('提交中')
    $.post('<?php echo Hotel_base::inst()->get_url("NEW_COMMENT_SUB");?>',{
        content:$('.comment_publish_text').val(),
        facilities_score:$('#facilities_score').attr('value'),
        clean_score:$('#clean_score').attr('value'),
        net_score:$('#net_score').attr('value'),
        service_score:$('#service_score').attr('value'),
        hotel_id:'<?php echo $order["hotel_id"];?>',
        orderid:'<?php if(isset($order["orderid"])){echo $order["orderid"];}?>',
        hotel_name:'<?php if(isset($order["hname"])){echo $order["hname"];}?>',
        room_name:'<?php if(isset($order['first_detail']['roomname'])){echo $order['first_detail']['roomname'];}?>',
        img_url:images_url,
        sign:sign
    },function(data){
        if(data.s==1){
            removeload();
            $("#comment_success").show();;
        }
        else{
            prevend=0;
            removeload();
            $.MsgBox.Alert(data.errmsg);
        }
    },'json');
}
function up_load(file,data,url,target){
    var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
    var fileSize = 0;
    if (isIE && !file.files) {
        var filePath = file.value;
        var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
        var file = fileSystem.GetFile (filePath);
        fileSize = file.Size;
        type = file.Type;
    } else {
        fileSize = file.files[0].size;
        type = file.files[0].type;
    }
    if(!fileSize){return false;}
    var size = fileSize / 1024;
    if(size>3072){
        $.MsgBox.Alert("文件超过3M！请耐心等待");
    }
    if(type.indexOf('image')!=0){
        $.MsgBox.Alert("请上传图片格式的文件");
        return false;
    }
    $.ajaxFileUpload({
        url:url,
        secureuri: false,
        fileElementId:file.id,
        dataType:'json',
        data:data,
        success:function (data) {
            target.html('<img src="'+data+'">');
            if($('.addimg div').length<3){
                // $('.addimg').prepend('<div></div>');
            }
        },
        error:function(err){
            $.MsgBox.Alert("上传失败");
        }
    });
}
function addimg(){
    var _this =$(this);
    var url   = '';
    chooseImage(_this);
}

$('.addimg').on('click','#add_publish_comment',addimg);

$(document).on("click",".comment_publish_img",function(){
    // addimg.splice($(this).attr('data-id'),1);
    // $(this).remove()
})
function chooseImage(obj){

    if(images_url.length >= limited_upload){
        $.MsgBox.Alert('已超过最大图片数');return;
    }

    var upload_num = limited_upload - images_url.length;

    wx.chooseImage({
        count: upload_num, // 默认9
        sizeType: ['original', 'compressed'],// 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'],// 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {

            var localIds = res.localIds;  //1111,22222 格式

            var t_localIds = JSON.stringify(localIds);
            t_localIds=t_localIds.split(',');
            t_localIds=JSON.parse(t_localIds);

            var _html="";

            var images = [];

            images.localId = res.localIds;

            //alert('已选择 ' + res.localIds.length + ' 张图片');

            if (images.localId.length == 0) {
                $.MsgBox.Alert('请先使用 chooseImage 接口选择图片');
                return;
            }

            var i = 0, length = images.localId.length;

            function upload() {
                wx.uploadImage({
                    localId: images.localId[i],
                    success: function (res) {
                        _html =  '<div style="margin-left:10px;" onclick="delete_img(this,event)" class="img_delete" data-id="'+ res.serverId +'"><img value="'+images.localId[i]+'" src="'+images.localId[i]+'"/><p><em class="iconfont color1 h32">&#xE028;</em></p><input name="img_url[]" type="hidden"  value="'+images.localId[i]+'"></div>';
                        $(".addimg").append(_html)
                        i++;
                        images_url.push(res.serverId);
                        if (i < length) {
                            upload();
                        }
                    },
                    fail: function (res) {
                        $.MsgBox.Alert(JSON.stringify(res));
                    }
                });
            }

            upload();


        }
    });

}


var delete_img=function(_this,event){
    var remove_img = $(_this).attr('data-id');
    for (var i = 0; i < images_url.length; i++) {
        if(images_url[i]==remove_img){
            images_url.splice(i,1);
        }
    }
    $(_this).remove();
}

Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};
Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};
    $(".close").on("click",function(){
        $(".whole_eject").hide();
    })
    $(".condition div").on("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    });
    $(".comment_publish_text").on("keyup",function(){
        $("#publish_num").html($(this).val().length);
    })
    var _time;
    $(".star").on("click","em",function(){
        var _this = $(this),
            _index = _this.index();
            clearInterval(_time);
        _this.parent().children().removeClass("pulse").addClass("off");

        var _item = 0;
            _time = setInterval(ani,100)
        function ani(){
            _this.parent().children().eq(_item).addClass("pulse").removeClass("off")
            _item++;
            if(_item > _index){
                _this.parent().attr('value',_item);
                clearInterval(_time);
            } 
        }
    })
</script>
</html>