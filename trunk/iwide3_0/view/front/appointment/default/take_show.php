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
<title>排队取号</title>
<?php echo referurl('css','service.css',1,$media_path) ?>
<?php echo referurl('css','global.css',1,$media_path) ?>
<?php echo referurl('js','jquery.js',1,$media_path) ?>
<?php echo referurl('js','ui_control.js',1,$media_path) ?>
<?php echo referurl('js','alert.js',1,$media_path) ?>
<?php echo referurl('js','timepicker.js',1,$media_path) ?>
<?php include 'wxheader.php'?>
</head>
<body>
<div class="pageloading"></div>
<page class="page">
	<header>
        <?php
        if ($dining_room['book_style'] == 1) {
        ?>
    	<div class="center padding" style="background:#f5e8d7">
        	<a class="color_555 h22" href="<?php echo site_url('/appointment/booking/offer_show?id='.$this->inter_id.'&dining_room_id='.$dining_room['dining_room_id'])?>">您当前选择的是立即取号，如需要预约其他时间请<span class="color_link">轻点此处</span></a>
        </div>
            <?php
        }
        ?>
        <!--div class="center bg_fff flex tablayer color_main" style="justify-content:space-around;">
        	<a href="" class="iscur"><tt>排队取号</tt></a>
        	<a href=""><tt>预约订座</tt></a>
        </div-->
    </header>
    <section class="scroll flexgrow h26">
    	<div class="bg_fff">

        	<div class="list_style_2 martop h22 center waitList bd_bottom">
                <?php
                if (!empty($desk_type)) {

                    ?>
                    <div class="flex">
                        <div>排除类型</div>
                        <div>前方等待</div>
                        <div>预估时间</div>
                    </div>
                    <?php
                    foreach ($desk_type as $value)
                    {
                    ?>
                        <div class="flex">
                            <div><?php echo $value['name'];?>
                                <div class="h20 color_999">
                                    <?php
                                    echo $value['min_num'] == $value['max_num'] ? $value['min_num'] : $value['min_num'] .'-'. $value['max_num']
                                    ?>
                                    人</div>
                            </div>
                            <div class="color_minor"><span class="h34"><?php echo $value['wait_num'] ? $value['wait_num'] : '--';?></span><?php echo $dining_room['unit'];?></div>
                            <div>
                                <?php echo $value['wait_min'] ? '--':'--';?>分钟
                                </div>
                        </div>

                        <?php
                    }
                }
                ?>
            </div>
            <div class="padding h20 color_999">
            	<div>&sdot;<?php echo $dining_room['give_info']?></div>
            </div>
    	</div>
    	<div class="flex bg_fff pad10 martop">
        	<div class="shrink">电话：</div>
            <div><?php echo $dining_room['shop_tel'];?></div>
        </div>
    	<div class="flex bg_fff pad10 bd_top linkblock"  onclick="tonavigate(<?php echo $hotel['latitude'];?>,<?php echo $hotel['longitude'];?>,'<?php echo $hotel['name'];?>','<?php echo $dining_room['shop_address'];?>')">
        	<div class="shrink">地址：</div>
            <div><?php echo $dining_room['shop_address'];?></div>
        </div>
    	<div class="flex bg_fff pad10 bd_top">
        	<div class="shrink">营业时间：</div>
            <div><?php echo $dining_room['open_time'];?></div>
        </div>
    </section>
    <section class="ui_pull dialog flex" style="display:none" onClick="toclose()" id="num_layer">
    	<div class="box scroll center h22 flex">
        	<div class="shrink">请选择人数</div>
            <div class="select_num flex flexwrap scroll" fillnum='20' max='<?php echo $dining_room['toplimit'];?>'></div>
            <div class="flex _w flexjustify center">
            	<div class="btn bg_ddd">取消</div>
                <button class="btn bg_main" onClick="go_href(event,this)">确定 </button>
            </div>
        </div>    	
    </section>
    <footer>
        <div class="pad10 bg_main center" <?php if ($dining_room['is_take']==0){?> disable="yes" <?php }else{?> onClick="show_num();"<?php }?>>立即取号</div>
    </footer>
</page>
</body>
<script>
function show_num(){
	toshow($('#num_layer'));
	if($('#num_layer .select_num').html()==''){
		var fillnum = Number($('#num_layer .select_num').attr('fillnum'));
		var $max	= Number($('#num_layer .select_num').attr('max'));
		for(var i= 1 ;i<=fillnum;i++){
			var dom = $('<div>'+i+'</div>');
			if(i>$max){ dom.addClass('disable');}
			$('#num_layer .select_num').append(dom);
			dom.get(0).onclick=function(e){
				e.stopPropagation();
				if($(this).hasClass('disable'))return;
				else{$(this).addClass('iscur').siblings().removeClass('iscur');}
			}
		}
	}
}

function save_order(book_number,dining_room_id,obj)
{
    $.ajax({
        dataType:'json',
        type:'post',
        data:
        {
            'book_number':book_number,
            'dining_room_id':dining_room_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        url:'<?php echo site_url('appointment/booking/save_take_num')?>',
        beforeSend: function(){
            $(obj).attr("disabled", true);
        },
        success:function(rs)
        {
            if (rs.status == 1)
            {
                $.MsgBox.Alert(rs.msg,function()
                {
                    window.location.href = rs.data.url;
                });
                $('#mb_btn_no').remove();
            }
            else
            {
                if (rs.status == 400)
                {
					$.MsgBox.Confirm('您已取号',function(){
						window.location.href=rs.data.url;
					},null,'查看','关闭');
                   // $.MsgBox.Alert(rs.msg);
                }
                else
                {
                    $.MsgBox.Alert(rs.msg);
                }
            }
            $(obj).removeAttr('disabled');
        }

    })
}//end

function go_href(e,obj)
{

	e.stopPropagation();
	if($('#num_layer .select_num .iscur').length>0)
    {
        var book_number = $('#num_layer .select_num .iscur').html();

        var dining_room_id = "<?php echo $dining_room['dining_room_id']?>";

        save_order(book_number,dining_room_id,obj);
	}
}
</script>
</html>
