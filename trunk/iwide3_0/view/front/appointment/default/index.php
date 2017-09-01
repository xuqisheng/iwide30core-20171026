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
<title>首页</title>

    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('css','global.css',1,$media_path) ?>

    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>

</head>
<body>
<div class="pageloading"></div>
<page class="page">
    <header></header>
    <section class="mainboxs">
        <ul class="scroll salelist">


        </ul>
	</section>
    <section class="floatlayer">
    	<a href="<?php echo site_url('appointment/booking/my_booking?type=booking&id='.$inter_id)?>" class="squareimg"><img src="<?php echo base_url('public/appointment/default/images/mine.png')?>"></a>
    </section>
    <footer></footer>
</page>
</body>
<script>
$('.btn_main').click(function(){
	$.setsession('booktype',$(this).attr('booktype'));
	$.setsession('shopname',$(this).parents('li').find('[shopname]').html());
	window.location.href=$(this).attr('href');
})
</script>
<script type="text/javascript">
    $(function()
    {
        var page_count = 0;        //总页数
        var page_num = 1;         //单页
        var page_show = 15;      //每页显示10条数据

        function get_data()
        {
            $.ajax({
                dataType:'json',
                type:'post',
                data:{
                    'page_num':page_num,
                    'page_show':page_show,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                url:'<?php echo site_url('appointment/booking/dining_room_list')?>',
                success:function(rs)
                {
                    var list = '';
                    page_count = Math.ceil(rs.data.page.page_total);

                    if (rs.status == 1)
                    {
                        for (var i = 0; i < rs.data.list.length; i++)
                        {
                            var title = rs.data.list[i].book_style == 3 ? '今日预约' : '正在排队';
                            list += '<li>'
                             +'<div class="img"><div class="squareimg"><img src="'+rs.data.list[i].shop_image+'"></div></div>'
                             + '<div class="flexgrow">'
                             + '<div class="h30" shopname>'+rs.data.list[i].shop_name+'</div>'
                             + '<div class="h20 color_999 pad multiclip">'+rs.data.list[i].shop_profiles+'</div>'
                             + '<div class="flex flexjustify h26">'
                             + ' <div>'+title+': <span class="color_minor">'+rs.data.list[i].count+rs.data.list[i].unit+'</span></div>'
                             + '<a class="btn_main select_type h26" href="'+rs.data.list[i].url+'" booktype="alldate">预约订座</a>'
                             + '</div>'
                             + '</div>'
                             + '</li>'
                        }
                    }
                    else
                    {

                    }
                    $('.salelist').append(list);
                }
            })
        }//end

        get_data();
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
                    get_data();

                }
                else if(page_num == page_count)
                {
                    // $('.tate_title').find('ol>li').eq(0).find('.rompt').html("歇息下吧，暂无商品了！");
                }
            }
        })

    })
</script>
</html>
