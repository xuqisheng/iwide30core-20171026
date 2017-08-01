
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes" >
<meta name="apple-touch-fullscreen" c ontent="yes">
<meta name="format-detection" content="telephone=no,email=no">
<meta name="ML-Config" content="fullscreen=yes,preventMove=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=320,user-scalable=0">
    <title><?php echo $shop['shop_name']?></title>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>

    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <?php echo referurl('js','iscroll.js',1,$media_path) ?>
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '8807010cf1c72c17']);
            (function() {
                var vds = document.createElement('script');
                vds.type='text/javascript';
                vds.async = true;
                vds.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'dn-growing.qbox.me/vds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(vds, s);
            })();
        })();
    </script>
    <script type='text/javascript' src='https://assets.growingio.com/sdk/wx/vds-wx-plugin.js'></script>
</head>
<body>
<div class="pageloading"></div>
<page class="page">
    <header>
        <div class="title_main_bg" style="padding:10px 16px;">
            <div class="searchbox center"><em class="iconfont color_999">&#X0A15;</em><input placeholder="请输入商品、品牌名称进行搜索" class="h24"></div>
        </div>
    </header>
    <section class="bd_top flex bd_color_d3 mainboxs">
        <ul class="list_style_2 h20 bd_right scroll color_main title_main_bg tabmenus">
            <?php if(!empty($group)){
                if(!empty($is_recommend)){?>
            <li class="iscur" group_id="is_re"><div><span>热门推荐</span></div></li>
            <?php }
				$_tmpindex = 0;
                foreach($group as $gk=>$gv){
					$_tmpindex++;
            ?>
            <li <?php echo empty($is_recommend)&&$_tmpindex==1?'class="iscur"':''?> group_id="<?php echo $gv['group_id']?>">
            	<div><span><?php echo $gv['group_name']?></span></div></li>
            <?php }}?>
        </ul>
        <ul class="list_style_2 flexgrow bg_fff scroll salelist"></ul>
        <div class="tmplayer" style="display:none"><img src=""></div>
        <div class="search_result" onClick="$(this).hide();"><ul class="list_style_2 flexgrow bg_fff scroll" id="search_result"></ul></div>
	</section>
    <section class="ui_pull flexlayer" id="speclayer" style="display:none" onClick="toclose()">
    	<div class="bg_fff layerbox node relative">
        	<div class="scroll" style="height:90%">
                <div class="squareimg overflow"><img src=""></div>
                <div class="h28 martop" name></div>
                <!--<div class="h18 color_999 martop">1盒/L</div>-->
                <div class="h20 martop_big" spec></div>
            </div>
            <div class="flex absolute _w bg_fff" style="bottom:0; padding:8px; left:0">
                <div class="color_minor h18">¥<span class="h28" price></span>
                <span class="stock color_999">还剩5份</span></div>
                <div>
                    <div class="num_control" goods_id="" style="float:right">
                        <div class="down_num color_555 iconfont">&#xA4;</div>
                        <div class="result_num"><input readonly value="0" type="tel" min="0" max="1"></div>
                        <div class="up_num iconfont color_main">&#x0A13;</div>
                    	<span class="h22 color_key" id="saleout" style="display:none">售罄</span>
                    </div>
                 </div>
            </div>
        </div>
    </section>
    <section class="ui_pull flexlayerBtm" id="shoplayer" style="display:none;" onClick="toclose()">
    	<div class="bg_fff layerbox">
        	<div class="title_main_bg pad10 flex flexjustify color_555">
            	<div class="h24">购物车</div>
                <div class="h20" id="clearCart"><em class="iconfont h24">&#XA9;</em> 清空</div>
            </div>
            <div class="h24 list_style" id="pre_orderlist"></div>
        </div>
        <div style="height:44px"></div>
    </section>
    <section class="ui_pull flexlayer" id="contentclayer" style="display:none;" onClick="toclose()">
        <div class="bg_fff layerbox node h20">
            <p class="h28 contentclayer-title">- 商品详情 -</p>
            <div class="contentclayer-content" id="iscroll">
				<div class="contentclayer-content-wrapper">
					
				</div>
                <!---内容-->
            </div>
        </div>
    </section>
    <section id="btm_tips" style="bottom:45px; display:none" min="<?php echo ($shop['sale_type'] ==3) ? $shop['sale_dispatching'] : 0;?>"><!--还差200元才能配送哦--></section>
    <footer class="bottomfixed layer1">
        <div class="shopcart "><em class="iconfont bg_main">&#XA5;</em><div class="_num bg_minor" style="display:none">0</div></div>
        <div class="flex flexgrow">
            <div class="y" id="total">0</div>
            <a href="<?php echo site_url('roomservice/roomservice/orderlist?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id)?>" class="center color_fff"><em class="iconfont">&#X0A16;</em><p class="h20">订单</p></a>
        </div>
        <?php if(isset($shop['shop_status']) && $shop['shop_status']){?>
        <div href="<?php echo site_url('roomservice/roomservice/checkout?id='.$inter_id.'&hotel_id='.$hotel_id.'&shop_id='.$shop_id.'&type_id='.$type_id)?>" class="btn_main disable" id="topay">去结算</div>
        <?php }else{?>
        <div  class="btn_main disable">商家休息</div>
        <?php }?>
    </footer>
</page>
</body>
<script>
var defaultimg= '<?php echo base_url('public/roomservice/default/images/default2.jpg');?>';
var GroupData = {};
var shopcart  = $.getsession('shopcart');
var curspec_list;

function isover(price){
	var m = $('#btm_tips').attr('min')? Number($('#btm_tips').attr('min')):0;
    money = m-price;
	$('#btm_tips').html('还差'+(money.toFixed(2))+'元才能配送哦');
	if( price < m){
		$('#btm_tips').show();
		return false;
	}
	else{
		$('#btm_tips').hide();
		return true;
	}
}
function length(jsonObj) { var Length = 0;for (var i in jsonObj) Length++;  return Length;}
function animate(bool){
	var _this = $('.shopcart ._num');
	var _this2= $('.shopcart em');
	if(_this.text()>=1){
		_this.show();
		if(bool){
			_this2.addClass('scale');
			window.setTimeout(function(){_this2.removeClass('scale');},100);
		}
	}
	else{
		_this.hide();
		_this2.removeClass('scale');
	}
}
function showcart(){
	$('#pre_orderlist').html('');
	$.each(shopcart,function(i,n){
		$.each(n,function(j,m){
			var html = $('<div><div class="flexgrow">'+m.goods_name+' '+m.spec_name+'</div>'
				+ '<div class=" color_minor h18">¥<span class="h24">'+m.price+'</span></div>'
				//+ '<div class="stock color_999 h18">还剩'+m.stock+'份</div>'
				+ '<div spec_name="'+m.spec_name+'" spec_id="'+m.spec_id+'" setting_id="'+m.setting_id+'" group_id="'+m.group_id+'" goods_id="'+m.goods_id+'"'
				+ ' class="num_control"><div class="down_num color_555 iconfont">&#xA4;</div>'
				+ '<div class="result_num"><input readonly value="'+m.count+'" type="tel" min="0" max="'+m.stock+'"></div>'
				+ '<div class="up_num iconfont color_main">&#x0A13;</div></div></div>');
			html.find('.down_num').bind('touchstart',removecart);
			html.find('.up_num').bind('touchstart',addcart);
			$('#pre_orderlist').append(html);
		});
	});
	if($('#pre_orderlist').html()==''&&!$('#shoplayer').is(':hidden'))$('#shoplayer').hide();
}
function savecart(_this){
	var parent    = $(_this).parent();
	var price     = $(_this).parents('.node').find('[price]').html();
	var goods_id  = parent.attr('goods_id');
	var group_id  = parent.attr('group_id');
	var spec_id   = parent.attr('spec_id');
	var setting_id= parent.attr('setting_id');
	var count     = parent.find('input').val();
	var spec_name = parent.attr('spec_name');
	var stock     = parent.find('input').attr('max');
	if(shopcart[goods_id]==undefined)shopcart[goods_id]={};
	if(shopcart[goods_id][setting_id]) shopcart[goods_id][setting_id].count=count;
	else{
		var tmp = GroupData[group_id][goods_id];
		var spec_list = $.parseJSON(tmp.spec_list);
		shopcart[goods_id][setting_id]={
			count:count,
			goods_id:goods_id,
			group_id:group_id,
			spec_id:spec_id,
			setting_id:setting_id,
			admin_setting_id:spec_list.data[setting_id].admin_setting_id,
			spec_name:spec_name,
			price:price,
			stock:stock,
			goods_name:tmp.goods_name,
			goods_img:tmp.goods_img,
		}
	}
	if(count<=0) delete shopcart[goods_id][setting_id];
	if(length(shopcart[goods_id])<=0) delete shopcart[goods_id];
	$.setsession('shopcart',JSON.stringify(shopcart));
	showcart();
	sumprice();
}
function sameval(_this){
	var group_id = _this.parent().attr('group_id');
	var goods_id = _this.parent().attr('goods_id');
	var spec_id = _this.parent().attr('spec_id');
	var val = _this.siblings('.result_num').find('input').val();
	$('.salelist .node').each(function() {
       if(group_id== $(this).attr('group_id')){
		   $(this).find('.num_control').each(function() {
            if(goods_id==$(this).attr('goods_id')&&spec_id==$(this).attr('spec_id')){
				$('.result_num input',this).val(val);
				if(val==0){$('.down_num',this).hide();$('.result_num',this).hide();}
				else{$('.down_num',this).show();$('.result_num',this).show();}
			}
        });
	   }
    });
}
function addcart(){
	if(up_num(event,this)){
		$('.shopcart ._num').html($('.shopcart ._num').text()*1+1);
		animate();
		savecart(this);
		sameval($(this));
		var clone = $('.tmplayer').eq(0).clone();
		var x = $('.shopcart ._num').offset().left;
		var y = $('.shopcart ._num').offset().top;
		var _x = $(this).offset().left;
		var _y = $(this).offset().top-$('.salelist').scrollTop();
		clone.find('img').attr('src',$(this).parents('.node').find('img').attr('src'));
		$('body').append(clone);
		clone.css({top:_y,left:_x}).show('fast');
		var a=0,b=0;
		var diy = window.setInterval(function(){
			b=a*(y-_y)/(_x-x)
			clone.css({
				top:_y+b,
				left:_x-a
			});
			a+=5;
			if( _y+b>=y){
				animate(true);
				clone.remove();
				window.clearInterval(diy);
			}
		},1);
	}
}
function removecart(){
	if(down_num(event,this)){
		$('.shopcart ._num').html($('.shopcart ._num').text()*1-1);
		animate();
		sameval($(this));
		savecart(this);
	}
}
function fill_list(dom,n,type){
	var shop_price, spec_list, specid,setid;
	var html = '<li class="node" group_id="'+n.group_id+'"><div class="img"><div class="squareimg"><img src="';
	if(n.goods_img) html+=n.goods_img;
	else html+=defaultimg;
    html+='" /></div>' +

        '</div>';
	html+='<div class="flexgrow"><div class="h24 goods_name" name>'+n.goods_name+'</div><div class="h18">';
	if( n.spec_list){
		spec_list = $.parseJSON(n.spec_list);
		$.each(spec_list.data,function(j,m){
			setid = j;
			specid= m.spec_id;
			if(shop_price) shop_price=Math.min(shop_price,m.specprice);
			else shop_price = m.specprice; 
			if(shopcart[n.goods_id]){
				if(n.sale_status!=1||n.stock<=0)  delete shopcart[n.goods_id];
			}
		});
		if(length(spec_list.data)>1)html+='<span class="color_link">多规格</span>';
		else html+='<span class="color_link">'+spec_list.spec_name[0][0]+'</span>';
	}else{
		html+='<span class="color_555">&nbsp;</span>';
	}
	html+='</div><div>';
	var stock_html = '<span class="stock color_999">还剩'+n.stock+'份</span>';
    if (n.btn_status == 1)
    {
        var btn_status = 'select_type';
        var up_num = 'up_num';
    }
    else
    {
        var btn_status = 'disable';
        var up_num = 'disable';
    }
	if( n.sale_status==1&&Number(n.stock)>0){
		if( n.spec_list && length(spec_list.data)>1 )
			html+='<div group_id="'+n.group_id+'" goods_id="'+n.goods_id+'" class="btn_main xsbtn h20 '+btn_status+'" style="float:right; margin-top:5px">选规格</div>';
		else{
			var val = 0;
			var display = "display:none";
			if(shopcart[n.goods_id]){
				var val = shopcart[n.goods_id][setid]? shopcart[n.goods_id][setid].count:0;
				if(val >=1&&val<=Number(n.stock)) display='';
			}
			html+='<div group_id="'+n.group_id+'" goods_id="'+n.goods_id+'" setting_id="'+setid+'" spec_name="'+spec_list.spec_name[0][0]+'" spec_id="'+specid+'" class="num_control" style="float:right">'
				 +'<div class="down_num color_555 iconfont" style="'+display+'">&#XA4;</div>'
				 +'<div class="result_num" style="'+display+'"><input readonly value="'+val+'" type="tel" min="0" max="'+n.stock+'"></div>'
				 +'<div class=" '+up_num+' iconfont  color_main">&#X0A13;</div></div>';
		}
	}else{
		html+='<div class="h20 color_999" style="float:right; margin-top:5px">售罄</div>';
		stock_html='';
	}
	if(shop_price)html+='<div class=" color_minor h18">¥<span class="h24" price>'+shop_price+'</span> '+stock_html+'</div></div></div>' +
        '' + '<div class="goods_desc" style="display: none">'+n.goods_desc +'</div>' +
        '</li>';
	html = $(html);
	if(type!=undefined)dom.append(html);
	else dom.after(html);
	html.find('.down_num').bind('touchstart',removecart);
	html.find('.up_num').bind('touchstart',addcart);

}
//点击分组请求商品信息
function get_goods(group_id){
	if(group_id==undefined){group_id = '';}
	$.post('<?php echo site_url('roomservice/roomservice/get_goods');?>',{
		'inter_id':'<?php echo $inter_id?>',
		'hotel_id':'<?php echo $hotel_id?>',
		'shop_id':'<?php echo $shop_id?>',
		'group_id':group_id,
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	},function(data){
		removeload();
		if(data.errcode == 0){
			var clone=$('<li class="bg_F8F8F8 h20" style="color:#989898;padding:4px 8px" group_title=""></li>');
			$.each(data.data,function(i,n){
				if(GroupData[n.group_id]==undefined)GroupData[n.group_id]={};
				if(group_id==''){
					var dom=clone.clone();
					dom.attr('group_title',n.group_id);
					dom.html(n.group_name)
					$('.salelist').append(dom);					
					$.each(n.goods_info,function(j,k){
						fill_list(dom,k);
						//console.log($.parseJSON(k.spec_list))
						GroupData[n.group_id][k.goods_id]=k;
					});					
				}else{
					if(i==0){
						clone.attr('group_title',group_id);
						clone.html($('.tabmenus [group_id="'+group_id+'"]').text());
						$('.salelist').prepend(clone);
					}
					fill_list(clone,n);
					GroupData[n.group_id][n.goods_id]=n;
				}
			});
		}else{
			$.MsgBox.Alert(data.msg);
		}
		$(".salelist li:last").css("padding-bottom","44px");
	},'json');
}
function sumprice(){
	var count= 0;
	var sum  = 0;
	$.each(shopcart,function(i,j){
		$.each(j,function(m,n){
			count	+= Number(n.count);
			sum		+= Number(n.price)*Number(n.count);
		});
	})
	sum = sum.toFixed(2);
	$('#total').html(sum);
	var bool = isover(sum);
	$('.shopcart ._num').html(count);
	if(count>0&&bool) $('#topay').removeClass('disable');
	else $('#topay').addClass('disable');
}
$(function(){
	if(shopcart!=''){
		shopcart = $.parseJSON(shopcart);
		sumprice();
		animate();
	}
	else shopcart = {};
	$('.shopcart').click(function(){
		if($('._num',this).is(':hidden')) return;
		$('.ui_pull').hide();
		toshow($('#shoplayer'));
		showcart();
	});
	$('.tabmenus li').click(function(){
		$(this).addClass('iscur').siblings().removeClass('iscur');
		var gid = $(this).attr('group_id');
		$('.salelist [group_title]').each(function(){
			if(gid==$(this).attr('group_title')){
				var _top= $(this).offset().top+$('.salelist').scrollTop()-$('header').height();
				$('.salelist').scrollTop(_top);
			}
		})
		
	});
	$('.salelist').on('touchstart',function(){ 
		var count = $('[group_title]').length;
		for(var i= 0;i<count;i++){
			var _this =$('[group_title]').eq(i)
			var gid =_this.attr('group_title');
			if((_this.offset().top<=$('header').height()&&i<count-1)){
				if($('[group_title]').eq(i+1).offset().top>0) 
				$('.tabmenus li').eq(i).addClass('iscur').siblings().removeClass('iscur');
			}
			if((_this.offset().top>=$('header').height()&&i>=1)){
				if($('[group_title]').eq(i-1).offset().top<=0) 
				$('.tabmenus li').eq(i).addClass('iscur').siblings().removeClass('iscur');
			}
			if($('.salelist').scrollTop()<=$('header').height())
				$('.tabmenus li').eq(0).addClass('iscur').siblings().removeClass('iscur');
		}
	})
	if($('.tabmenus [group_id="is_re"]').length>0){
		pageloading();
		var group_id = $('.tabmenus .iscur').attr('group_id');
		get_goods('is_re');
		get_goods();	
	}else{
		pageloading();
		get_goods();		
	}
	$('.mainboxs').on('click','.select_type',function(e){
		e.stopPropagation();
		var parent   = $(this).parents('li');
		var goods_id  = $(this).attr('goods_id');
		var group_id  = $(this).attr('group_id');
		curspec_list = $.parseJSON(GroupData[group_id][goods_id].spec_list);
		$('#speclayer .squareimg img').attr('src',parent.find('.img img').attr('src'));
		$('#speclayer [name]').html(parent.find('[name]').html());
		$('#speclayer [spec]').html('');
		$.each( curspec_list.data,function(i,n){
			var btn = $('<div class="btn_void xsbtn maright" specid="'+n.spec_id+'">'+n.spec_name.toString()+'</div>');
			$('#speclayer [spec]').append(btn);
			function btnclick(){
				btn.addClass('bg_main').siblings().removeClass('bg_main');
				$('#speclayer [price]').html( n.specprice);
				$('#speclayer .num_control input').attr('max',n.stock?n.stock:0);
				$('#speclayer .stock').html(n.stock?'还剩'+n.stock+'份':'售罄');
				$('#speclayer .num_control div').show();
				if( Number($('#speclayer .num_control input').attr('max'))<=0 ){
					$('#speclayer .num_control div').hide();
					$('#saleout').show();
				}else{
					$('#saleout').hide();
					$('#speclayer .num_control').attr('goods_id',goods_id);
					$('#speclayer .num_control').attr('group_id',group_id);
					$('#speclayer .num_control').attr('spec_id',n.spec_id);
					$('#speclayer .num_control').attr('setting_id',n.setting_id);
					$('#speclayer .num_control').attr('spec_name',n.spec_name.toString());
					$('#speclayer .num_control .down_num').hide();
					$('#speclayer .num_control .result_num').hide();
					$('#speclayer .num_control input').val(0);
					if(shopcart[goods_id]){
						var val = shopcart[goods_id][i]? shopcart[goods_id][i].count:0;
						if(val >=1&&val<=Number(n.stock)){
							$('#speclayer .num_control div').show();
							$('#speclayer .num_control input').val(val);
						}
					}
				}
			}
			btn.get(0).onclick=btnclick;
			if(btn.index()<=0)btnclick();
		});
		toshow($('#speclayer'));
		$('#speclayer .scroll').height($('#speclayer .layerbox').height()-$('#speclayer .absolute').height()-10);
	});
	$('#speclayer .down_num').bind('touchstart',removecart);
	$('#speclayer .up_num').bind('touchstart',addcart);
	$('.layerbox').click(function(e){e.stopPropagation();});
	$('#topay').click(function(){
		if($(this).hasClass('disable')) return;
		window.location.href=$(this).attr('href');
	});
	$("#clearCart").click(function(){
		$.MsgBox.Confirm('是否清空购物车？',function(){
			toclose();
			$('.shopcart ._num').html(0).hide();
			for(var i in shopcart) delete shopcart[i];
			$.setsession('shopcart','');
			$('#pre_orderlist').html('');
			$('.num_control').find('input').val(0);
			$('.num_control .down_num').hide();
			$('.num_control .result_num').hide();
			sumprice();			
		});
	})
	$('.searchbox input').blur(function(){
		var key = $(this).val();
		if( key=='') return;
		$('#search_result').html('');
		$.each(GroupData,function(i,j){
			$.each(j,function(m,n){
				if( n.goods_name.indexOf(key) >=0){
					fill_list($('#search_result'),n,'append');
				}
			});
		});
		$('.search_result').show();
		if( $('#search_result').children().length<=0){
			$('#search_result').html('<div class="center h22 color_999 pad15">没有搜索到相关结果</div>');
		}
	})
});

//弹窗内容goods_desc
$('.mainboxs').on('click','.img,.goods_name',function(){

    var goods_desc = $(this).parents('li.node').find('.goods_desc').html();
    if (goods_desc != 'null')
    {
        $('#contentclayer .contentclayer-content-wrapper').html(goods_desc);
    }
    else
    {
        $('#contentclayer .contentclayer-content-wrapper').html('');
    }

    toshow($('#contentclayer'));
    loadsc();
})

function loadsc () {
	new IScroll('#iscroll', { mouseWheel: true });
}
</script>
</html>
