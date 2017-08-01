<script>
    var package_obj= {
        'appId': '<?php echo $wx_config["appId"]?>',
        'timestamp': <?php echo $wx_config["timestamp"]?>,
        'nonceStr': '<?php echo $wx_config["nonceStr"]?>',
        'signature': '<?php echo $wx_config["signature"]?>'
    }
    /*下列字符不能删除，用作替换之用*/
    //[<sign_update_code>]
    wx.config({
        debug: false,
        appId: package_obj.appId,
        timestamp: package_obj.timestamp,
        nonceStr: package_obj.nonceStr,
        signature: package_obj.signature,
        jsApiList: [<?php echo $js_api_list; ?>,'getLocation']
    });
    wx.ready(function(){

        <?php if( $js_menu_hide ): ?>wx.hideMenuItems({ menuList: [<?php echo $js_menu_hide; ?>] });<?php endif; ?>

        <?php if( $js_menu_show ): ?>wx.showMenuItems({ menuList: [<?php echo $js_menu_show; ?>] });<?php endif; ?>

        <?php if( $js_share_config ): ?>
        wx.onMenuShareTimeline({
            title: '<?php echo $js_share_config["title"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            success: function () {},
            cancel: function () {}
        });
        wx.onMenuShareAppMessage({
            title: '<?php echo $js_share_config["title"]?>',
            desc: '<?php echo $js_share_config["desc"]?>',
            link: '<?php echo $js_share_config["link"]?>',
            imgUrl: '<?php echo $js_share_config["imgUrl"]?>',
            //type: '', //music|video|link(default)
            //dataUrl: '', //use in music|video
            success: function () {},
            cancel: function () {}
        });
        <?php endif; ?>

        // wx.getLocation({
        //     success: function (res) {
        //         get_package_nearby(res.latitude,res.longitude);
        //     },
        //     cancel: function (res) {
        //         $.MsgBox.Confirm('为了更好的体验，请先授权获取地理位置');
        //     }
        // });
    });
</script>
<script src="<?php echo get_cdn_url('public/soma/tickets/mobiscroll/mobiscroll.2.13.2.js'). config_item('css_debug');?>"></script>
<link href="<?php echo get_cdn_url('public/soma/tickets/mobiscroll/mobiscroll.css'). config_item('css_debug');?>" rel="stylesheet" />
<style>
.color_6e6e6e{color:#6e6e6e;}
.bg_f8f9fb{background:#92a0ae;}
</style>
<body class="bg_fafafa">
<div class="pageloading"><p class="isload">正在加载</p></div>
<?php if( $productDetail ):?>
<div class="header bg_fff p_3 m_b_10">
    <div class="min_img f_l head_img"><img src="<?php echo $productDetail['face_img'];?>" /></div>
    <div class="contents clearfix">
        <div class="cont_price f_r m_t_22"><span class="cont_price_ico">¥</span><span class="cont_price_numb"><?php echo $productDetail['price_package'];?></span></div>
        <p class="cont_title text_ellipsis"><?php echo $productDetail['name'];?></p>
        <p class="cont_txt select_data"></p>
    </div>
</div>
<?php endif;?>
<div class="calendar border_bottom border_top m_t_3 m_b_10">
    <table>
        <thead class="bg_fff theads f_s_13">
            <tr>
                <th>日</th>
                <th>一</th>
                <th>二</th>
                <th>三</th>
                <th>四</th>
                <th>五</th>
                <th>六</th>
            </tr>
        </thead>
        <tbody id="oTbody">
            <tr id="titl_btn" class="titl_btn">
                <td class="se_datas border_bottom relative" colspan="7">
                    <i class="iconfont left_btn reduce">&#xe8b5;</i>
                       <!--  <span>2017年</span>
                        <span id="s_months">10月</span> -->
                        <input name="test" id="slide" class="data_txt demo-test-date demo-test-datetime demo-test-time demo-test-credit" value="2017年10月" />
                    <i class="iconfont right_btn add">&#xe88e;</i>
                </td>
            </tr>
       
        </tbody>
    </table>
</div>
<div style="height:56px;"></div>
<div class="bg_fff border_top color_555 floor f_s_8 fixed floor_btn">   
    <div class="d_flex_r menu_bar">
    </div>
    <div class="d_flex_r floor_btn_list f_s_14">
        <div class="btn_confirm bg_f8f9fb"><a class="color_fff" href="javascript:;">立即支付</a></div>
    </div>
</div>
<script>

    $(function(){
        //日历开始
        //console.log(new Date().getDate());
        var josn2=[
            /*{
                'data':'2017/3',
                'month':[
                    {'time':'2017-3-1','money':'¥62','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-2','money':'¥6232','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-3','money':'¥6452','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-4','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-5','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-6','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-7','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-8','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-9','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-10','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-11','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-12','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-13','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-14','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-15','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-18','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-19','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-20','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-21','money':'¥892','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-22','money':'632','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-23','money':'¥892','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-26','money':'¥682','stock':'0','psp_sid':'162'},
                    {'time':'2017-3-27','money':'682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-28','money':'¥682','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-29','money':'¥6452','stock':'0','psp_sid':'162'},
                    {'time':'2017-3-30','money':'¥132','stock':'123','psp_sid':'162'},
                    {'time':'2017-3-31','money':'¥62','stock':'123','psp_sid':'162'}
                ]
            },*/
            <?php if( $settingData ):?>
                <?php foreach( $settingData as $k=>$v ):?>
                    {'data':'<?php echo $v['data'];?>','month':[
                        <?php foreach($v['month'] as $sk=>$sv): ?>
                            {'time':'<?php echo $sv['time'];?>','money':'¥<?php echo $sv['money'];?>','stock':'<?php echo $sv['stock'];?>','psp_sid':'<?php echo $sv['psp_sid'];?>'},
                        <?php endforeach;?>
                    ]},
                <?php endforeach;?>
            <?php endif;?>
        ]
        var json1={data:[]}

        $("#slide").mobiscroll().date({
            theme: "android-holo light",
            lang: "zh",
            cancelText: null,
            dateFormat: 'yy/mm', //返回结果格式化为年月格式
            startYear:2017, //开始年份
            endYear:2020,//结束年份
            onBeforeShow: function (inst) { inst.settings.wheels[0].length>2?inst.settings.wheels[0].pop():null; }, //弹掉“日”滚轮
            headerText: function (valueText) { //自定义弹出框头部格式
                array = valueText.split('/');
                return array[0] + "年" + array[1] + "月";
            },
            onSelect:function(valueText,inst){
                array = valueText.split('/');
                $('.data_txt').val(array[0] + "年 " + array[1] + "月");
                clear_date();
                json1.data.length=0;
                for(var i=0;i<josn2.length;i++){
                    var y_d=josn2[i].data.split('/');
                    if(y_d[0]==array[0]&&y_d[1]==ay[1]){
                        json1.data.push(josn2[i].month);
                    }
                }
                new_dada(array[0],array[1])
            }
        });
        var new_time=new Date();
        var yy=new_time.getFullYear();
        var mmm=new_time.getMonth()+1;
        var numbers=yy;
        var hh;

        for(var i=0;i<josn2.length;i++){
            var y_d=josn2[i].data.split('/');
            if(y_d[0]==yy&&y_d[1]==mmm){
                json1.data.push(josn2[i].month);
            }
        }
        new_dada(yy,mmm);
        $('.reduce').click(function(){
            clear_date();
            json1.data.length=0;
            var data_arr=$('.data_txt').val().replace('年','').replace('月','').split(' ');
            hh=parseInt(data_arr[1])-1
            if(hh<1){
                hh=12;
                data_arr[0]=parseInt(data_arr[0])-1;
            }
            for(var i=0;i<josn2.length;i++){
                var y_d=josn2[i].data.split('/');
                if(y_d[0]==data_arr[0]&&y_d[1]==hh){
                    json1.data.push(josn2[i].month);
                }
            }
            new_dada(data_arr[0],hh);
        })
        $('.add').click(function(){
            clear_date();
            json1.data.length=0;
            var data_arr=$('.data_txt').val().replace('年','').replace('月','').split(' ');
            hh=parseInt(data_arr[1])+1
            if(hh>12){
                hh=1;
                data_arr[0]=parseInt(data_arr[0])+1;
            }
            for(var i=0;i<josn2.length;i++){
                var y_d=josn2[i].data.split('/');
                if(y_d[0]==data_arr[0]&&y_d[1]==hh){
                    json1.data.push(josn2[i].month);
                }
            }
            new_dada(data_arr[0],hh);
        })



        function new_dada(y,m){
            var oTbody=document.getElementById('oTbody');
            var titl_btn=document.getElementById('titl_btn');
            var oSpan=titl_btn.getElementsByTagName('span');
            var o_Spans=oTbody.getElementsByTagName('span');
            var new_date=new Date();
            y=y||new_date.getFullYear();
            m=m||new_date.getMonth()+1
            // oSpan[0].innerText=y+"年 ";
            //oSpan[1].innerText=m+"月";
            $('.data_txt').val(''+y+'年 '+m+'月');

            var set_data=new Date(y,m-1);
            var week=set_data.getDay();  //星期几
            var day_num=new Date(y,m,0).getDate();  //多少天
            var rows=week+day_num<35?rows=5:rows=6;
            for(var i=0;i<rows;i++){
                var oTr=document.createElement('tr');
                for(var h=0;h<7;h++){
                    var oTd=document.createElement('td');
                    var odiv=document.createElement('div');
                    var ospan=document.createElement('span');
                    ospan.className='moneys color_ff9900';
                    ospan.innerHTML='&nbsp'
                    oTd.appendChild(odiv);
                    oTd.appendChild(ospan);
                    oTr.appendChild(oTd);
                }
                oTbody.appendChild(oTr);
            }
            var oDvi=oTbody.getElementsByTagName('div');
            switch(set_data.getDay()){
                case 1:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+1].innerHTML=i+1;
                    }
                    break;
                case 2:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+2].innerHTML=i+1;
                    }
                    break;
                case 3:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+3].innerHTML=i+1;
                    }
                    break;
                case 4:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+4].innerHTML=i+1;
                    }
                    break;
                case 5:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+5].innerHTML=i+1;
                    }
                    break;
                case 6:
                    for(var i=0;i<day_num;i++){
                        oDvi[i+6].innerHTML=i+1;
                    }
                    break;
                case 0:
                    for(var i=0;i<day_num;i++){
                        oDvi[i].innerHTML=i+1;
                    }
                    break;
            }
            for(var i=0;i<o_Spans.length;i++){
                if(json1.data.length!=0&&json1.data!=''){
                    for(var j=0;j<json1.data[0].length;j++){
                        if(oDvi[i].innerHTML==json1.data[0][j].time.split('-')[2]){
                            if(json1.data[0][j].stock<=0){
                                addClass(o_Spans[i],'color_6e6e6e');
                            }
                            o_Spans[i].innerHTML=json1.data[0][j].money;
                            o_Spans[i].setAttribute('psp_sid',json1.data[0][j].psp_sid);
                        }
                    };
                }
            }
            yy=new_date.getFullYear();
            mm=new_date.getMonth()+1;
            dd=new_date.getDate();
            var data_arr=$('.data_txt').val().replace('年','').replace('月','').split(' ');
            if(''+yy+toZoo(mm)>data_arr[0]*1+toZoo(data_arr[1])){
                for(var i=0;i<oDvi.length;i++){
                    addClass(oDvi[i],'color_6e6e6e');
                    addClass(o_Spans[i],'color_6e6e6e');
                }
            }
            if((''+yy+toZoo(mm))*1==data_arr[0]*1+toZoo(data_arr[1])){
                for(var i=week;i<dd+week-1;i++){
                    addClass(oDvi[i],'color_6e6e6e');
                    addClass(o_Spans[i],'color_6e6e6e');
                }
            }

            td_click();
        }
        function addClass(obj,cName){
            if (obj.className){
                var classArr=obj.className.split(' ');
                for(var i=0;i<classArr.length;i++){
                    if(classArr[i]==cName){
                        return;
                    }
                }
                obj.className+=' ' + cName;
            }else{
                obj.className=cName;
            }
        }
        td_click();
        var d_id='';
        function td_click(){
            $('#oTbody td').on('click',function(){
                var arr_mon=$('.data_txt').val().replace('年','').replace('月','').split(' ');
                var select_datas=toZoo(arr_mon[0])+toZoo(arr_mon[1])+toZoo($(this).find('div').html());
                var new_datas=new Date().getFullYear().toString()+toZoo((new Date().getMonth()+1).toString())+toZoo(new Date().getDate().toString());
                if(!$(this).hasClass('se_datas')){
                    if($(this).find('span').html()!='\&nbsp;'&&select_datas>=new_datas&&!$(this).find('span').hasClass('color_6e6e6e')){
                        $(this).addClass('hook').siblings('td').removeClass('hook').parent().siblings('tr').find('td').removeClass('hook');

                        $('.book_btn').removeClass('bg_e7e7e7').addClass('bg_ff9900');

                        $('.select_data').html('已选“'+arr_mon[1]+'月'+$(this).find("div").html()+'日“');

                        var str=$(this).find('span').html().replace('¥','');

                        $('.cont_price_numb').html(str);
                        $('.btn_confirm').removeClass('bg_f8f9fb').addClass('bg_ff9900');
                        console.log($(this).find('span').attr('psp_sid'));
                        d_id=$(this).find('span').attr('psp_sid');
                    }
                }
            })
        }
        $('.btn_confirm').click(function(){

             <?php $gift = isset($_REQUEST['gift']) ? $_REQUEST['gift'] : '' ?>;

            if($(this).hasClass('bg_ff9900')){
                if(d_id!=''){
                    var psp_sid = d_id;
                    var url = "<?php echo Soma_const_url::inst()->get_url('*/*/package_pay/', array(
                                                                                        'id'=>$interId,
                                                                                        'bsn'=>$bsn,
                                                                                        'pid'=>$productId,
                                                                                        'bType'=>$bType,
                                                                                        'gift' => $gift
                                                                                        )
                                                                                    );
                                                                                ?>";
//                    console.log(url+'&psp_sid='+psp_sid);return false;
                    window.location.href = url+'&psp_sid='+psp_sid;
                    /*$.ajax({
                         type: "GET",
                         url: url,
                         data: psp_sid,
                         //dataType: "json",
                         success: function(data) {
                             // $('').empty();   //清空resText里面的所有内容
                             window.location.href = url+'&psp_sid='+psp_sid;
                         }
                    });*/
                }
            }
        })
        function clear_date(){
            $('#oTbody tr').not(":first").remove();
        }
        function toZoo(str){
            return str>=10?str:'0'+str;
        }
    })
</script>
</body>
</html>
