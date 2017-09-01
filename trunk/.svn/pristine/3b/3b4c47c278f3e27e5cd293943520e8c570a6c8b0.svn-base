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
<title>我的预约</title>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
<body>
<div class="pageloading"></div>
<page class="page h24">
	<header>
    	<div class="center bg_fff flex flexjustify tablayer color_main">
        	<a href="<?php echo site_url('appointment/booking/my_booking?id='.$inter_id.'&type=booking')?>" <?php if($type=='booking'){?>class="iscur"<?php }?> type="booking"><tt>预约中</tt></a>
        	<a href="<?php echo site_url('appointment/booking/my_booking?id='.$inter_id.'&type=finish')?>" <?php if($type=='finish'){?>class="iscur"<?php }?> type="finish"><tt>已用餐</tt></a>
        	<a href="<?php echo site_url('appointment/booking/my_booking?id='.$inter_id.'&type=cancel')?>" <?php if($type=='cancel'){?>class="iscur"<?php }?> type="cancel"><tt>已取消</tt></a>
        	<a href="<?php echo site_url('appointment/booking/my_booking?id='.$inter_id.'&type=all')?>" <?php if($type=='all'){?>class="iscur"<?php }?> type="all"><tt>全部</tt></a>
        </div>
    </header>
    <section class="scroll flexgrow orders" style="padding-bottom:10px">
        <div style="padding:30px 0;display: none;text-align: center;" class="center color_999 h22 no-data">暂无订单结果</div>
    </section>
    <footer></footer>
</page>
</body>


<script type="text/javascript">
    var type = "<?php echo $type;?>";
    $(function()
    {
        var page_count = 0;        //总页数
        var page_num = 1;         //单页
        var page_show = 15;      //每页显示10条数据
        function get_data(type)
        {
            $.ajax({
                dataType:'json',
                type:'post',
                data:
                {
                    'type':type,
                    'page_num':page_num,
                    'page_show':page_show,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                url:'<?php echo site_url('appointment/booking/booking_list')?>',
                success:function(rs)
                {
                    var list = '';
                    if (rs.status == 1)
                    {
                        page_count = Math.ceil(rs.data.page.page_total);
                        var data = rs.data.list;
                        var list = '';
                        for (var i = 0; i < rs.data.list.length; i++)
                        {
                            list += '<div class="list_style_2 martop flexlist" orderid="'+ data[i].order_id+'">';
                            list += '<div class="flex flexjustify h24">';
                            if (data[i].book_type == 2 && data[i].book_op_status == 0)
                            {
                                list += '<div class="color_666">还需等待<span class="color_minor">'+data[i].count + data[i].unit+'</span></div>';
                                list += '<div>预计等待 -- 分钟</div>';
                            }
                            else
                            {
								var _txt = data[i].book_op_status == 2 ? "取消时间：":"用餐时间：";
								    _txt +=data[i].book_op_status == 2 ? data[i].offer_op_time: data[i].book_datetime;
                                list += '<div>'+ _txt +'</div>';
                                if (type == 'all')
                                {
                                    list += '<div class="color_main">'+ data[i].status_name+'</div>';
                                }
                            }

                            list += '</div>';
                            list += '<div class="flex">';
                            list += '<div class="img"><div class="squareimg"><img src="'+ data[i].shop_image+'"></div></div>';
                            list += '<div class="flexgrow">';
                            list += '<span class="h24">'+ data[i].shop_name+'</span>';
                            list += '<div class="h20 color_999">'+ data[i].book_number+'人</div>';
                            list += '</div>';
                            list += '<div>';
                            list += '<div class="h30 color_minor">'+ (data[i].offer_name ? data[i].offer_name : '--')+'</div>';
                            list += '<div class="h20 color_999 center">'+ data[i].desk_name+'</div>';
                            list += '</div>';
                            list += '</div>';
                            list += '<div class="flex">';
                            list += '<div class="h20 color_999">'+ data[i].book_add_time+'</div>';
                            if (data[i].book_op_status == 0 && data[i].book_type == 1)
                            {
                                list += '<div class="btnlayer flexgrow">';
                                list += ' <div class="btn_void h22 color_999" onClick="cancel_order(this)">取消</div>';
                                list += '</div>';
                            }

                            list += '</div>';
                            list += '</div>';

                        }
                    }
                    else
                    {
                        $('.no-data').show();
                    }
                    $('.orders').append(list);
                }
            })
        }//end

        get_data(type);
        /*
         # 滚动加载数据
         */
        $(window).on('scroll',function()
        {
            if ($(this).scrollTop() >= $(document).height() - $(window).height())
            {
                if (page_count>0 && (page_num < page_count))
                {
                    page_num++;
                    get_data(type);
                }
                else if(page_num == page_count)
                {
                   // $('.no-data').show();
                }
            }
        })
    })

    /*$('.tablayer>*').click(function(){alert($(this).attr('type'));
     $(this).addClass('iscur').siblings().removeClass('iscur');
     pageloading();
     //getData();
     });*/
    function isnone(str)
    {
        str = str?str:'暂无订单结果';
        str = '<div style="padding:30px 0" class="center color_999 h22">'+str+'</div>';
        $('.orders').html(str);
    }


    function cancel_order(obj)
    {
        var order_id = $(obj).parents('[orderid]').attr('orderid');
        $.ajax({
            dataType:'json',
            type:'post',
            data:
            {
                'order_id':order_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            url:'<?php echo site_url('appointment/booking/cancel_order')?>',
            success:function(rs)
            {
                if (rs.status == 1)
                {
                    $.MsgBox.Alert('取消成功',function()
                    {
                        if (type == 'booking')
                        {
                            $(obj).parent().parent().parent().hide(100);
                        }
                        else
                        {
                            window.location.reload()
                        }
                    });
                    $('#mb_btn_no').remove();
                }
                else
                {
                    $.MsgBox.Alert(rs.msg);
                }
            }
        })
    }//end

    //getData();
</script>
</html>
 