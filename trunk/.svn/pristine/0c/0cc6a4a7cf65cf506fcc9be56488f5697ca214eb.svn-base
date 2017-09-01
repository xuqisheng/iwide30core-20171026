<?php include 'header.php'?>
<?php echo referurl('js','ajaxfileupload.js',2,$media_path) ?>
<?php if($comment==0 || !empty($comment_info)) {
    redirect( site_url ( 'hotel/hotel/hotel_comment') . '?id=' . $this->inter_id .'&h='.$order['hotel_id'] );
?>
<!--<div class="ui_none"><div>您还不能评论</div></div>
<div class="foot_btn">
	<a class="btn_main bdradius" href='orderdetail?id=<?php /*echo $inter_id;*/?>&oid=<?php /*echo $order['id'];*/?>'>返回订单</a>
</div>-->

<?php } else {?>
<?php if(empty($comment_info)) {?>
<div class="list_style">
    <?php if(isset($first_room)){ ?>
        <div>
            <div class="pointimg">
                <div class="squareimg"><img src="<?php echo $first_room['room_img'];?>" /></div>
            </div>
            <div class="color_999">
                <div class="color_000"><?php echo $order['hname']?></div>
                <div><?php echo date('m月d日',strtotime($order['startdate'])).'-'.date('m月d日',strtotime($order['enddate']));?>  共<?php echo round(strtotime($order['enddate'])-strtotime($order['startdate']))/86400;?>晚</div>
                <div><?php echo $order['first_detail']['roomname'];?></div>
            </div>
        </div>
    <?php }  ?>
    <div class="comment" style="display:block">
        <textarea placeholder="亲～服务满意吗？留下个脚印吧～" id="msg" maxlength="100" rows="3" oninput="changerow(this)"></textarea>
        <div class="addimg">
            <div></div>
<!--        	<div>-->
<!--                <img style="width:100%;"  src="http://img3.imgtn.bdimg.com/it/u=117562581,3194768070&fm=21&gp=0.jpg" />-->
<!--                <input name="img_url[]" type="hidden"  value="http://img3.imgtn.bdimg.com/it/u=117562581,3194768070&fm=21&gp=0.jpg">-->
<!--            </div>-->
        </div>
    </div>
    <div class="topoint" style="display:block">
        <div>酒店评分</div>
        <ul class="h24"> 
            <li>设施
                <span class="iconfont color_main h36">
                    <tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt>
                    <input id="facilities_score" type="hidden" value='5'>
                </span>
            </li>
            <li>卫生
                <span class="iconfont color_main h36">
                    <tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt>
                    <input id="clean_score" type="hidden" value='5'>
                </span>
            </li>
            <li>服务
                <span class="iconfont color_main h36">
                    <tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt>
                    <input id="service_score" type="hidden" value='5'>
                </span>
            </li>
            <li>网络
                <span class="iconfont color_main h36">
                    <tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt><tt>&#X23;</tt>
                    <input id="net_score" type="hidden" value='5'>
                </span>
            </li>
        </ul>
    </div>
</div>



<!--header class="order_intro">
    
	<div class="sever"><?php if(!empty($first_room['imgs']['hotel_room_service'])) foreach($first_room['imgs']['hotel_room_service'] as $hs){ ?><?php echo $hs['info']; ?>&nbsp;<?php }?></div>
</header-->

<div class="foot_btn">
	<button type="submit" class="btn_main bdradius footbtn disable" onclick="sub_comment()">提交评价</button>
</div>
<?php } else {?>
<div class="comment">
	<p><?php echo $comment_info['content'];?></p>
</div>

<div class="topoint">
	<span class="big color_main_gray float">酒店评分&nbsp;</span>
    <ul> 
    	<?php for($i=0;$i<$comment_info['score'];$i++) {?>
    	<li><em class="ui_star ui_star1"></em></li>
    	<?php }?>
    </ul>
</div>
<div class="foot_btn">
	<a href='index?id=<?php echo $inter_id;?>&h=<?php echo $order['hotel_id'];?>' class="btn_main bdradius">再订一单</a>
</div>
<?php } }?>

<input type="file" accept="image/*" id="upload" name="UploadForm[file]" style="display:none"/>
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
function totest(){
	if ($('#msg').val()==''){
		$('.footbtn').html('还未填写评论内容').addClass('disable');
		return false;
	}
	if ($('#msg').get(0).value.length<=5){
		$('.footbtn').html('评论内容不得少于5个字符').addClass('disable');
		return false;
	}
	$('.footbtn').html('提交评价');
	$('.footbtn').removeClass('disable');
}
function sub_comment(){
//    alert(images_url);
    var post_url='';
    if(prevend==1)return false;
	if ($('#msg').val()==''){
		$('.footbtn').html('还未填写评论内容').addClass('disable');
		return false;
	}
	if ($('#msg').get(0).value.length<=5){
		$('.footbtn').html('评论内容不得少于5个字符').addClass('disable');
		return false;
	}

    var ranges=$('.addimg div');
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
	$('.footbtn').html('提交中');
	$.post('<?php echo site_url("hotel/hotel/new_comment_sub").'?id='.$inter_id; ?>',{
		content:$('#msg').val(),
        facilities_score:$('#facilities_score').val(),
        clean_score:$('#clean_score').val(),
        net_score:$('#net_score').val(),
        service_score:$('#service_score').val(),
		hotel_id:'<?php echo $order["hotel_id"];?>',
		orderid:'<?php if(isset($order["orderid"])){echo $order["orderid"];}?>',
		hotel_name:'<?php if(isset($order["hname"])){echo $order["hname"];}?>',
		room_name:'<?php if(isset($order['first_detail']['roomname'])){echo $order['first_detail']['roomname'];}?>',
        img_url:images_url
	},function(data){
		if(data.s==1){
//			$.MsgBox.Alert(data.errmsg);
            $.MsgBox.Alert('提交成功');
//		    location.reload();
            location.href="<?php echo base_url('/index.php/hotel/hotel/hotel_comment?id=').$inter_id.'&h='.$order["hotel_id"];?>";


		}
		else{
			prevend=0;
			$('.footbtn').html(data.errmsg);
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
				$('.addimg').append('<div></div>');
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
$(function(){
	$('.addimg').on('click','div',addimg);
	$('#msg').blur(totest);
	$('.topoint tt').click(function(){
		$(this).siblings().html('&#x22;');
		for ( var i=0; i<=$(this).index(); i++){
			$(this).parent().find('tt').eq(i).html('&#x23;');
		}
		$(this).siblings('input').val($(this).index()+1);
	})
})

function chooseImage(obj){
    wx.chooseImage({
        count: 4, // 默认9
        sizeType: ['original', 'compressed'],// 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'],// 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {

            var localIds = res.localIds;  //1111,22222 格式

//            $("#upload_list .img_list").remove();

            var _html="";

            for(var i in localIds){
                var photoSrc=localIds[i];
                _html = _html + '<div><img style="width:100%;"  value="'+photoSrc+'" src="'+photoSrc+'"/><input name="img_url[]" type="hidden"  value="'+photoSrc+'"></div>';
            }

            $(".addimg").append(_html);

            var images = [];

            images.localId = res.localIds;

            //alert('已选择 ' + res.localIds.length + ' 张图片');

            if (images.localId.length == 0) {
                alert('请先使用 chooseImage 接口选择图片');
                return;
            }

            var i = 0, length = images.localId.length;

//            images.serverId = [];

            function upload() {
                wx.uploadImage({
                    localId: images.localId[i],
                    success: function (res) {
                        i++;
//                        alert(images_url);
//                        alert(res.serverId);
//                        images_url = res.serverId;
                        images_url.push(res.serverId);
                        if (i < length) {
                            upload();
                        }
                    },
                    fail: function (res) {
                        alert(JSON.stringify(res));
                    }
                });
            }

            upload();


        }
    });

}

</script>
</html>
