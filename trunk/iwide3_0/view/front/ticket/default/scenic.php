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
        <ul class="scroll">
            <?php
                if ($list)
                {
                    foreach ($list as $key=>$value)
                    {
            ?>
                <li class="scenic_rows">
                <div class="scenic_img"><div class="squareimg"><img src="<?php echo $value['shop_image']?>"></div></div>
                <div class="flexgrow scenic_flexgrow">
                    <div class="h30 scenic_title" shopname><?php echo $value['shop_name']?></div>
                    <div class="h20 color_999 pad multiclip scenic_word"><?php echo $value['shop_profiles']?></div>
                    <div class="flex flexjustify h26">
                        <div>景区星际: <span class="color_minor"><?php echo $value['spots_level']?></span></div>
                        <a class="btn_main select_type h26" href="<?php echo site_url('ticket/ticket/index?id='.$this->inter_id.'&shop_id='.$value['shop_id'].'&hotel_id='.$value['hotel_id']);?>" booktype="alldate">马上买票</a>
                       </div>
                    </div>
                </li>

            <?php
                    }
                }
            ?>
        </ul>
	</section>
    <footer></footer>
    <a href="<?php echo site_url('/ticket/ticket/orderlist?id='.$this->inter_id)?>"> <img class="scenic_mine" src="<?php echo base_url('public/ticket/default/images/mine.png')?>"></a>
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
                url:'<?php echo site_url('ticket/ticket/scenic_list')?>',
                success:function(rs)
                {
                    var list = '';
                    page_count = Math.ceil(rs.data.page.page_total);

                    if (rs.status == 1)
                    {
                        for (var i = 0; i < rs.data.list.length; i++)
                        {
                            list += '<li class="scenic_rows">'
                             + '<div class="scenic_img"><div class="squareimg"><img src="'+rs.data.list[i].shop_image+'"></div></div>'
                             + '<div class="flexgrow scenic_flexgrow">'
                             + '<div class="h30 scenic_title" shopname>'+rs.data.list[i].shop_name+'</div>'
                             + '<div class="h20 color_999 pad multiclip scenic_word">'+rs.data.list[i].shop_profiles+'</div>'
                             + '<div class="flex flexjustify h26">'
                             + ' <div>景区星际: <span class="color_minor">AAAA</span></div>'
                             + '<a class="btn_main select_type h26" href="'+rs.data.list[i].url+'" booktype="alldate">马上买票</a>'
                             + '</div>'
                             + '</div>'
                             + '</li>'
                        }
                    }
                    else
                    {

                    }
                    $('.scroll').append(list);
                }
            })
        }//end

        //get_data();
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
                    //get_data();

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
