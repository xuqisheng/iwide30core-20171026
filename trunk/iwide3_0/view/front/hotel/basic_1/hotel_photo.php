<?php include 'header.php'?>
<?php echo referurl('js','touchwipe.js',3,$media_path) ?>
<?php echo referurl('css','photo.css',1,$media_path) ?>
<?php if(!empty($first_gallery)){?>
<div class="total_photo big"><span class="cur">1</span>/<span class="all"><?php echo $first_gallery['g_nums']?></span></div>
<?php }?>
<?php if(!empty($cur_gallery)){?>

<style>
.imgbox{position:absolute;width:100%;}
.imgbox > *{ background:url('<?php echo referurl('img','loading.gif',3,$media_path);?>') no-repeat center center; background-size:15px auto; min-height:200px;}

</style>
<div class="relative">
	<ul class="imgbox" gid="<?php echo $cur_gallery[0]['gid'];?>"><li></li></ul>
</div>
<?php }?>

<div class="photo_name big"><?php if(!empty($cur_gallery)){echo $cur_gallery[0]['info'];}?></div>
<ul class="photo_class middle">
	<?php if(!empty($gallery_count)){foreach($gallery_count as $k=>$gc){?>
	<li gid="<?php echo $gc['gid'];?>"><tt><?php echo $gc['gallery_name'];?></tt>(<span><?php echo $gc['g_nums'];?></span>)</li>
	<?php }}?>
</ul>
</body>
<script>

function imagePreview(srcList) {
	if(!srcList) return false;
	if(srcList.indexOf('http://')<0){	
		if(srcList.indexOf('/')==0){	
			srcList = location.protocol+'//'+window.location.host+location.port+srcList;		
		}
	}
	srcListu = [srcList];
	if(typeof(WeixinJSBridge)!='undefined'){
		WeixinJSBridge.invoke('imagePreview', { 
			'current' : srcList,
			'urls' : srcListu
		});
	}
};
var $scroll;
var img_index=0;
var hotel_photo={
	"photo_count"	: 0,
	"pic_name"		: [],
	"pre_url"		: []
}
function set_photo(i){
	$('.photo_name').html(hotel_photo.pic_name[i]);
	$('.imgbox').html('');
	for(var i=0;i<hotel_photo.photo_count;i++){
		$('.imgbox').append('<li>&deg;</li>');
	}
	$('.imgbox li').eq(img_index).html('<img src="'+hotel_photo.pre_url[img_index]+'" >');
}
//gid photo_class.li.attr('gid') ,nums 请求的图片数量, offset 取的位置,从0开始
function get_img(gid,nums,offset){
	pageloading('请稍候',0.3);
	$.get('/index.php/hotel/hotel/get_new_gallery?id=<?php echo $inter_id;?>&h=<?php echo $hotel_id?>',{
		  gid:gid,
		  nums:nums,
		  offset:offset
	},function(data){
		_clear();
		hotel_photo.photo_count=$('.photo_class .cur span').html();
		$.each(data,function(i,n){
			hotel_photo.pic_name[i]=n.info;
			hotel_photo.pre_url[i]=n.image_url;
		});
		set_photo(0);
		$('.page_loading').remove();
	},'json');
}
function _clear(){
	hotel_photo.photo_count=0;
	hotel_photo.pic_name=[];
	hotel_photo.pre_url=[];
}
function reset_img(index,url){
	$('.imgbox img').eq(index+1).attr('src',url);
}
function swipe(dir,_this){
	if((img_index<=0 && !dir)|| (img_index>=hotel_photo.photo_count-1&&dir))return false;
	var direction = dir?1:-1;
	var _w=$(window).width();
	img_index+=direction;
	var tmp_index = -img_index;
	$('.imgbox').stop().animate({'left':tmp_index*_w+'px'},200);
	$('.imgbox li').eq(img_index).html('<img src="'+hotel_photo.pre_url[img_index]+'" >');
	$('.photo_name').html(hotel_photo.pic_name[img_index]);
	$('.total_photo .cur').html(img_index+1);
}
$(function(){
//	$('.imgbox li').css('transform','translateX(-'+$(".imgbox li").width()+'px)');
//	$(".imgbox").click(function() { swipe(false,$(this));})
	$(".imgbox").touchwipe({
		 wipeLeft  : function(_this) { swipe(true,$(_this));},  // 下一张
		 wipeRight : function(_this) { swipe(false,$(_this));}, 
		 min_move_x: 15,
		 min_move_y: 15,
		 preventDefaultEvents: true
	});
	$('.imgbox').click(function(){
		wx.previewImage({
			current: hotel_photo.pre_url[img_index], // 当前显示图片的http链接
			urls: hotel_photo.pre_url // 需要预览的图片http链接列表
		});
	});
	$('.photo_class li').click(function(){
		if ( !$(this).hasClass('cur')){
			img_index=0;
			_clear();
			hotel_photo.photo_count=parseInt($(this).find('span').html());
			get_img($(this).attr('gid'),$(this).find('span').html(),0);
		}
		$(this).addClass('cur').siblings().removeClass('cur');
		$('.all').html($(this).find('span').html());
		$('.total_photo .cur').html(img_index+1);
	});
	$('.photo_class li').eq(0).trigger('click');
})
</script>
</html>
