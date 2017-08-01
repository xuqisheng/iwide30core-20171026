<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<?php
	/* 顶部导航 */
	echo $block_top;
	?>

	<?php
	/* 左栏菜单 */
	echo $block_left;
	?>

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>

<style>

.red{ color:#f00}
#numberInput{margin-left:10px;width:350px; background:#f8f8f8}
.InputBox{ border-radius:5px; overflow:hidden; background:#fff;}
.InputBox input{width:100%;  padding:15px 10px; box-sizing:content-box; border-bottom:1px solid #d9dfe9}
.InputBox input:last-child{border:0}
.InputBox input,.keyBoard>*,#numberInput .btn,.b{ font-size:24px; font-family:arial;}
.keyBoard{ margin-top:10px; justify-content:space-between;}
.keyBoard>*{border:1px solid #d9dfe9; padding:12px 0; width:30%;background:#fff; text-align:center; border-radius:4px; margin-bottom:10px; cursor:pointer}
.keyBoard>*:hover{background:#f8f8f8}
#numberInput .btn{display:block; margin-top:10px;} 

#NumberList .tablayer,.overdue{overflow:auto; max-height:600px}
.tabMenus{ border-bottom:2px solid #ff9900;}
.tabMenus > *{display:inline-block; min-width:50px; padding:5px 2px;text-align:center; margin-left:10px;cursor:pointer}
.tabMenus > .iscur,.tabMenus > *:hover{border-top:4px solid #ff9900}
.tabMenus > .iscur .b{color:#ff9900}
.tablayer .item{align-items:center; justify-content:space-between; padding:10px}
.tablayer .item:nth-child(odd){background:#f8f8f8}

.shop_select{position:absolute; bottom:0; width:100%; cursor:pointer}
.fixed_box{width:240px; margin:15% auto; background:#fff; border-radius:4px; overflow:hidden}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle">取号管理</header>
    <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<?php //$pk= $model->table_primary_key(); ?>
<?php if(isset($id)){?>
<?php echo form_open('restaurant/edit?ids='.$id,array('id'=>'tosave','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }else{?>
<?php echo form_open('restaurant/shop/add',array('id'=>'tosave','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }?>
<div class="flex">
    <div class="bg_fff bd flexgrow relative" id="NumberList">
        <div class="shop_select pad10" style="background:#fce4bd" onClick="$('#select_shop').show();">
        	<span style="color:#666">当前店铺：<?php echo $dining_room['shop_name']?></span>
            <span style="color:#77a5d9">点击切换</span>
        </div>
        <div class="tabMenus">
            <?php
            if ($desk_type){
            foreach ($desk_type as $item){
            ?>
            <div data='<?php echo $item['desk_type_id'];?>' max='<?php echo $item['max_num'];?>'>
                <div class="b"><?php echo $item['num'];?></div>
                <div><?php echo $item['min_num'] == $item['max_num'] ? $item['max_num'] : $item['min_num'] .'-'.$item['max_num'];?>人</div>
            </div>
            <?php
            }} ?>
            <div data='0' id="histroy">
                <div class="b"><?php echo $history;?></div>
                <div>历史</div>
            </div>
        </div>

        <div id="tablayers">
            <div class="tablayer offer-data">


            </div>
        </div>
    </div>
    <div class="bd pad10" id="numberInput">
        <div class="bd InputBox">
        	<input name="phone" placeholder="输入预留手机号(非必填) ">
            <input name="count" placeholder="输入用餐人数">
        </div>
        <div class="keyBoard flex flexwrap"></div>
        <div class="btn btn-sm bg-orange" data="1" id="getNum">取号</div>
        <div class="btn btn-sm bg-orange" data="2" id="cutNum">插队取号</div>
    </div>

</div>
			<?php echo form_close() ?>
<div class="fixed center" id="select_shop" style="z-index:9999999; display:none" onClick="$(this).hide()">
	<div class="fixed_box pad10" onClick="event.stopPropagation();">
    	<div style="margin-bottom:10px">店铺切换</div>
        <div style="margin-bottom:10px" class="input">
        	<select class="dining_room_id" name="dining_room_id">
                <?php
                if (!empty($shop)) {
                    foreach ($shop as $item)
                    {
                    ?>
                    <option value="<?php echo $item['dining_room_id'];?>" <?php $dining_room['dining_room_id'] == $item['dining_room_id'] ? 'selected':''?>>
                        <?php echo $item['shop_name'];?></option>
                    <?php
                }}
                ?>
            </select>
        </div>
        <button class="bg_main btn btn-block location_btn" style="color:#fff;">切换</button>
    </div>
</div>
    </section>
</div><!-- /.content-wrapper -->
			</div>
            </div>
            </div>
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
?>
<?php
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
?>
</div><!-- ./wrapper -->
<?php
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>
</body>
<script>

    $('.location_btn').click(function() {
        var dmr_id = $('.dining_room_id').val();
            window.location.href = "<?php echo site_url('/appointment/offer_orders/index?dining_room_id=')?>"+dmr_id;
    });
function showtab(index)
{
	//$('.tabMenus>*').eq(index).addClass('iscur').siblings().removeClass('iscur');
	//$('#tablayers').find('.tablayer').eq(index).show().siblings().hide();
    //alert(index);
}
function addSound(txt){
	txt = encodeURI(txt+',请您用餐了');
	var html = '<audio class="call"><source src="http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=2&text='+txt+'" type="audio/mpeg">'
			 + '<embed height="0" width="0" src="http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=2&text='+txt+'"></audio>';
	audio = $(html);
	$('body').append(audio);
	audio.get(0).play();
}
function callNum(that){  //叫号
	if($('.call').length>0){
		$('.call').get(0).pause();
		$('.call').remove();
	}
	addSound($(that).parents('.item').attr('title'));
}
function useNum(id,obj)
{ //用号
    var itemid = $('.offer-data .item ').eq($(obj).parent().index() +3).attr('itemid');

    $.ajax({
        dataType:'json',
        type:'post',
        data:{
            'order_id':id,
            'type':1,//使用
            'itemid':itemid,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/offer_orders/change_status')?>',
        success:function(rs)
        {
            if (rs.status == 1)
            {
                var total_type = parseInt($('.tabMenus').find('.iscur .b').html());
                total_type = total_type >0 ? total_type - 1 : total_type;
                $('.tabMenus').find('.iscur .b').html(total_type);

                var history_num = parseInt($('.tabMenus').find('#histroy .b').html());
                history_num = history_num + 1;
                $('.tabMenus').find('#histroy .b').html(history_num);

                var parent = $(obj).parents('.item');
                parent.find('.btn').remove();
                parent.append('<span class="red">已使用</span>');
                parent.siblings('.overdue').append(parent);
                $(obj).parents('.item').remove();

            }
            else
            {
                alert(rs.msg);
            }
        }
    })
}


/**
var wait_time = 1000; // 测试时使用1秒增加一次
window.setInterval(function(){  //等号时间
	$('.tablayer>.item [wait]').each(function() {
		var wait = Number($(this).attr('wait'))+1;
		$(this).html('等待'+wait+'分钟');
		$(this).attr('wait',wait);
	});
},wait_time);

 **/
function passNum(id,obj)
{  //过号
	if(!confirm('该操作不可恢复，是否继续')){return;}

    $.ajax({
        dataType:'json',
        type:'post',
        data:{
            'order_id':id,
            'type':2,//过号
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/offer_orders/change_status')?>',
        success:function(rs)
        {
            if (rs.status == 1)
            {
                var total_type = parseInt($('.tabMenus').find('.iscur .b').html());
                total_type = total_type >0 ? total_type - 1 : total_type;
                $('.tabMenus').find('.iscur .b').html(total_type);

                var history_num = parseInt($('.tabMenus').find('#histroy .b').html());
                history_num = history_num + 1;
                $('.tabMenus').find('#histroy .b').html(history_num);

                var parent = $(obj).parents('.item');
                parent.find('.btn').remove();
                parent.append('<span class="red">已过号</span>');
                parent.siblings('.overdue').append(parent);
                $(obj).parents('.item').remove();

            }
            else
            {
                alert(rs.msg);
            }
        }
    })
}


function getNum(offer_type,phone,count)
{ //取号

    $.ajax({
        dataType:'json',
        type:'post',
        data:
        {
            'dining_room_id':"<?php echo $dining_room['dining_room_id'];?>",
            'book_number':count,
            'book_phone':phone,
            'offer_type':offer_type,//过号
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/offer_orders/save_take_num')?>',
        success:function(rs)
        {
            if (rs.status == 1)
            {
                //alert(rs.msg);
                $('.tabMenus>div').eq(rs.data.click_key).trigger('click');

            }
            else
            {
                alert(rs.msg);
            }
        }
    })




	//$('#numberInput input').val('');
}






/** 屏蔽选项卡切换效果**/
$('.tabMenus>*').click(function()
{
    var obj_data ={};
    obj_data.desk_type_id = $(this).attr('data');
    obj_data.dining_room_id = "<?php echo $dining_room['dining_room_id'];?>";
    $('.offer-data').html('');
    $('.tabMenus>*').eq($(this).index()).addClass('iscur').siblings().removeClass('iscur');
    get_data(obj_data,this);
});

/** 获取类型数据**/
function get_data(obj,dom)
{
    $.ajax({
        dataType:'json',
        type:'post',
        data:{
            'dining_room_id':obj.dining_room_id,
            'desk_type_id':obj.desk_type_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/offer_orders/load_type_data')?>',
        success:function(rs)
        {
            var list = '';
            if (rs.status == 1)
            {
                if (obj.desk_type_id == 0)
                {
                    for (var i = 0; i < rs.data.list.length; i++)
                    {
                        list +='<div class="item flex" itemid="" title="'+rs.data.list[i].desk_name+rs.data.list[i].offer_name+'">'
                            +'<span class="b">'+rs.data.list[i].offer_name+'</span>'
                            +'<span>'+rs.data.list[i].book_number+'人</span>'
                            +'<span wait="0">等待--分钟</span>'
                            +'<span class="red"">'+(rs.data.list[i].book_op_status == 1? '已使用':'已过号')+'</span>'
                            +'</div>'
                    }
                }
                else
                {
                    for (var i = 0; i < rs.data.list.length; i++)
                    {
                        list +='<div class="item flex" itemid="'+rs.data.list[i].order_id+'" title="'+rs.data.list[i].desk_name+rs.data.list[i].offer_name+'">'
                            +'<span class="b">'+rs.data.list[i].offer_name+'</span>'
                            +'<span>'+rs.data.list[i].book_number+'人</span>'
                            +'<span wait="0">等待--分钟</span>'
                            +'<span class="btn bg-green" onClick="callNum(this)">叫号</span>'
                            +'<span class="btn bg-orange" onClick="useNum('+rs.data.list[i].order_id+',this)">使用</span>'
                            +'<span class="btn bg-red" onClick="passNum('+rs.data.list[i].order_id+',this)">过号</span>'
                            +'</div>'
                    }
                }
            }
            else
            {

            }

            list += '<div class="overdue"></div>';
            $(dom).find('.b').html(rs.data.list.length);
            $('.offer-data').html(list);
        }
    })
}//end


$('.tabMenus *').eq(0).trigger('click');

$('#histroy').click(function(){$('#tablayers').find('.tablayer').show()});

$('#numberInput .btn').click(function()
{


	var phone = $('input[name=phone]').val();
	var count = Number($('input[name=count]').val());
    var offer_type = $(this).attr('data');

    if(count == '')
    {
        alert('请输入用餐人数');
        return false;
    }
	getNum(offer_type,phone,count);
});

;(function(){
	var curInput = $('#numberInput input').eq(0);
	$('#numberInput input').blur(function(){
		curInput = $(this);
	})
	var numClick = function(){
		var val = String(curInput.val());
		curInput.val(val+$(this).html());
	}
	for(var i=1;i<10;i++){
		var dom=$('<div>'+i+'</div>');
		$('#numberInput .keyBoard').append(dom);
		dom.get(0).onclick=numClick;
	}
	var clear=$('<div class="red">清空</div>');
	$('#numberInput .keyBoard').append(clear);
	clear.get(0).onclick=function(){
		$('#numberInput input').val('');
	};
	var dom=$('<div>0</div>');
	$('#numberInput .keyBoard').append(dom);
	dom.get(0).onclick=numClick;
	var del=$('<div class="red">回删</div>');
	$('#numberInput .keyBoard').append(del);
	del.get(0).onclick=function(){
		var val = curInput.val();
		val = val.substr(0, val.length - 1); 
		curInput.val(val);
	};
	$('#numberInput').on('selectstart',function(){return false;});
})();
</script>
</html>
