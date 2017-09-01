<script src="<?php echo base_url('public/mall/multi/script/jquery.touchwipe.min.js')?>"></script>
<script src="<?php echo base_url('public/mall/multi/script/command.js')?>"></script>
<link href="<?php echo base_url('public/mall/multi/style/ui.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mail.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/mailstatus.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/choosebtn.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/mall/multi/style/shopcar.css')?>" rel="stylesheet">
<title>我的购物车</title>
</head>
<style>
.content{ background:#fff;border-bottom:1px solid #e4e4e4;}
.content .item .itemimg{ width:3.2rem; height:3.2rem;}
.content .item .ui_price{font-size:0.8rem; margin-top:2%; display:inline-block;}
</style>
<body><form id="spsform" action="<?php echo site_url('mall/wap/cconfirm/')?>?id=<?php echo $inter_id?>&t=<?php echo $topic['identity'] ?>&saler=<?php echo $saler?>&f=<?php echo $fans_id?>" method="post">
	<input type="hidden" name="id" value="<?php echo $inter_id ?>" />
	<input type="hidden" name="t" value="<?php echo $topic['identity'] ?>" />
	<input type="hidden" name="saler" value="<?php echo $saler?>" />
	<input type="hidden" name="f" value="<?php echo $fans_id?>" />
	<input type="hidden" name="sps" id="sps" value='' />
<div class="content">
<?php $amount=0; foreach ($products as $product):?>
	<div class="item">
    	<div class="wipe">
            <div class="choosebtn" rel_id="<?php echo $product['gs_id']?>"><i></i></div>
            <div class="itemimg img_auto_cut"><img src="<?php echo $product['gs_logo']?>" /></div>
            <div class="hotelname txtclip"><?php echo $product['gs_name']?></div>
            <div class="desc gray"><?php echo $product['gs_desc']?></div>
            <div class="addcount">
                <div class="down"><img src="<?php echo base_url('public/mall/multi/images/ico/down.png')?>" /></div>
                <div class="num"><input type="tel" readonly value="<?php echo $product['nums']?>" name="rec_num" rel_id="<?php echo $product['gs_id']?>"></div>
                <div class="add"><img src="<?php echo base_url('public/mall/multi/images/ico/add.png')?>" /></div>
            </div>
            <span class="ui_price color"><?php echo $product['gs_wx_price']?></span>
        </div>
        <div class="delete" del_id="<?php echo $product['gs_id']?>">删除</div>
    </div>
	
	<?php $amount += $product['gs_wx_price']*$product['nums']; endforeach;?>
</div>

<div class="footbtn">
    <div class="float_r bg_orange"><input type="submit" class="bg_orange paybtn" value="结算"></div>
	<div class="chooseall">
    	<div class="choosebtn"><i></i></div><span>全选</span> 
    </div>
    <div class="color">合计<span class="ui_price total"><!-- <?php echo $amount?> -->0.00</span></div>
</div></form>
</body>
<script>
$('#spsform').on('submit',function(){
    var sps = {};
    $.each($('div.ischoose'),function(k,v){
        var cur = $(v);
        sps[cur.attr('rel_id')] = $('input[name=rec_num][rel_id='+cur.attr('rel_id')+']').val();
    });
    $('#sps').val(JSON.stringify(sps));
    return true;
});
$('.delete').on('click',function(){
	var r=confirm('确定删除该商品？');
	if( !r ) return false;
	var id= $(this).attr('del_id');
	$.getJSON("<?php echo site_url('mall/wap/del_from_cart') ?>?pid="+id,function(datas){
		if(datas.errmsg == 'ok'){
			$('div[del_id='+id+']').parent().remove();
		} else {
			alert('添加失败');
		}
	});

})
</script>
</html>
