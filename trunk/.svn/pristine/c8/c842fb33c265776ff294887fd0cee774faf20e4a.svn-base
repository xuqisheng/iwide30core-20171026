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
    <body>
        <div class="bg absolute w100 none">
            <div class="title relative w100 left">
                <img class="w100 left" src="<?php echo get_cdn_url('public/soma/live/img/title.png'); ?>">
            </div>
            <div class="urname w100 left relative">
                <?php echo $inter_name; ?>
            </div>
            <div class="score relative w100 left">
                <p class="p1 w100 relative left">12月1日至今，双12累计交易</p>
                <p class="p2 w100 relative left">0</p>
                <p class="p3 w100 relative left">微信酒店排名：<span class="num">第0名</span></p>
            </div>
            <div class="paiming relative w100 left">
                <div class="kuang relative left">
                    <div class="tiao absolute">
                        <img class="w100 left" src="<?php echo get_cdn_url('public/soma/live/img/paiming.png'); ?>">
                    </div>
                    <div class="kuang2 relative left">
                        <div class="ge relative left">
                            <div class="icon left">
                                <img class="iconimg full" src="<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>">
                            </div>
                            <div class="jieshao1 left">
                                <div class="js1 w100">欢乐一家房卷</div>
                                <div class="js2 w100">带双早</div>
                                <div class="js3 w100">已售100</div>
                            </div>
                            <div class="jieshao2 right">
                                <div class="js4 w100 right">较上期</div>
                                <div class="js5 w100 right relative">100%<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div>
                            </div>
                        </div>
                        <div class="ge relative left">
                            <div class="icon left">
                                <img class="iconimg full" src="<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>">
                            </div>
                            <div class="jieshao1 left">
                                <div class="js1 w100">欢乐一家房卷</div>
                                <div class="js2 w100">带双早</div>
                                <div class="js3 w100">已售100</div>
                            </div>
                            <div class="jieshao2 right">
                                <div class="js4 w100 right">较上期</div>
                                <div class="js5 w100 right relative">100%<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div>
                            </div>
                        </div>
                        <div class="ge relative left">
                            <div class="icon left">
                                <img class="iconimg full" src="<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>">
                            </div>
                            <div class="jieshao1 left">
                                <div class="js1 w100">欢乐一家房卷</div>
                                <div class="js2 w100">带双早</div>
                                <div class="js3 w100">已售100</div>
                            </div>
                            <div class="jieshao2 right">
                                <div class="js4 w100 right">较上期</div>
                                <div class="js5 w100 right relative">100%<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div>
                            </div>
                        </div>
                        <div class="ge relative left">
                            <div class="icon left">
                                <img class="iconimg full" src="<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>">
                            </div>
                            <div class="jieshao1 left">
                                <div class="js1 w100">欢乐一家房卷</div>
                                <div class="js2 w100">带双早</div>
                                <div class="js3 w100">已售100</div>
                            </div>
                            <div class="jieshao2 right">
                                <div class="js4 w100 right">较上期</div>
                                <div class="js5 w100 right relative">100%<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div>
                            </div>
                        </div>
                        <div class="ge relative left">
                            <div class="icon left">
                                <img class="iconimg full" src="<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>">
                            </div>
                            <div class="jieshao1 left">
                                <div class="js1 w100">欢乐一家房卷</div>
                                <div class="js2 w100">带双早</div>
                                <div class="js3 w100">已售100</div>
                            </div>
                            <div class="jieshao2 right">
                                <div class="js4 w100 right">较上期</div>
                                <div class="js5 w100 right relative">100%<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jinfangka relative w100 left">by.金房卡</div>
        </div>
        <div class="login absolute w100">
            <img class="w100 left" src="<?php echo get_cdn_url('public/soma/live/img/p1.jpg'); ?>">
            <input type="text" class="account absolute">
            <input type="password" class="pass absolute">
            <div class="btn absolute">
        </div>

        <script src="<?php echo get_cdn_url('public/soma/js/jquery-1.11.1.min.js'); ?>"></script>
        <script src="<?php echo get_cdn_url('public/soma/live/js/Taoja.js'); ?>"></script>

        <script>
            new Orient();
var swi = true;
document.addEventListener("touchmove",function(e){
    if(swi) e.preventDefault();
});
var oldnum = 0;
var btn = document.querySelector(".btn");
var login = document.querySelector(".login");
var p2 = document.querySelector(".p2");
var acc =document.querySelector(".account");
var pass = document.querySelector(".pass");
var thenum = document.querySelector(".num");
btn.addEventListener("touchstart",function(){
    var acc2 = acc.value;
    var pass2 = pass.value;
    if(acc2 == "" || acc2 == undefined){
        alert("请输入您的账号");
    }else if(pass2 == "" || pass2 == undefined){
        alert("请输入您的密码");
    }else{
        $.ajax({  
            type: "POST",
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/login_post', array('id' => $inter_id)); ?>", 
            data: {username:acc2,password:pass2},
            dataType:'json',
            success: function(data)
            {
                var status = data.status;
                if(status == 1 || status == "1"){
                    $("div.urname").html(data.data.inter_name);
                    getinfo();
                    nextpage();
                    storage(acc2,pass2);
                }else{
                    alert(data.message);
                }
            } 
        });
    }
});
function getinfo(){
    $.ajax({  
            type: "POST",
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/ajax_live_data', array('id' => $inter_id)); ?>", 
            data: {},
            dataType:'json',
            success: function(data)
            {
                var rank,sales,product;
                if(data.status == 1 || data.status == "1"){
                    rank = data.data.sales_rank.rank;
                    sales = parseInt(data.data.sales_rank.sales);
                    product = data.data.product_rank;
                    new shownum(oldnum,sales);
                    oldnum = sales;
                    $(thenum).text(rank);
                    var thehtml = "";
                    for(var i = 0 ; i < product.length ; i++){
                        var name = product[i].name.replace('<br>','</div><div class="js2 w100">');
                        var url = product[i].face_img == "" ? "<?php echo get_cdn_url('public/soma/live/img/icon.png'); ?>" : product[i].face_img;
                        var sales_total = product[i].sales_total;
                        var inc_per = product[i].inc_per;
                        thehtml = thehtml + '<div class="ge relative left"><div class="icon left"><img class="iconimg full" src='+url+'></div><div class="jieshao1 left"><div class="js1 w100">'+name+'</div><div class="js3 w100">已售'+sales_total+'</div></div><div class="jieshao2 right"><div class="js4 w100 right">较上期</div><div class="js5 w100 right relative">'+inc_per+'<span class="arrow"><img class="w100" src="<?php echo get_cdn_url('public/soma/live/img/arrow.png'); ?>"></span></div></div></div>';
                    }
                    $(".kuang2").html(thehtml);
                    setTimeout(function(){
                        getinfo()
                    },5000);
                }else{
                    alert(data.message);
                }
            } 
        });
}
function nextpage(){
    ".bg".block();
    swi = false;
    document.body.style.overflow = "auto";
    login.className = "login absolute w100 flipOutX";
    login.addEventListener("webkitAnimationEnd",function(){
        login.className = "login absolute w100 none";
    });
}
function shownum(a,b){
    this.num_old = a;
    this.num_new = b;
    this.add();
}
shownum.prototype.add = function(){
    this.show();
    var cha = this.num_new - this.num_old;
    var z;
    if(cha > 100){
        z = parseInt(cha/10);
    }else{
        z = 5;
    }
    this.num_old = this.num_old + z;
    if(this.num_old <= this.num_new){
        window.requestAnimationFrame(function(){
            this.add();
        }.bind(this));
    }else{
        this.num_old = this.num_new;
        this.show();
    }
}
shownum.prototype.show = function(){
    var str = this.num_old.formatMoney(0,"");
    p2.innerText = str;
}
Number.prototype.formatMoney = function (places, symbol, thousand, decimal) {
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : "$";
    thousand = thousand || ",";
    decimal = decimal || ".";
    var number = this,
        negative = number < 0 ? "-" : "",
        i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};
function nextpage2(){
    ".bg".block();
    swi = false;
    document.body.style.overflow = "auto";
    login.className = "login absolute w100 none";
}
var check = function(){
    $.ajax({  
            type: "POST",
            url: "<?php echo Soma_const_url::inst()->get_url('*/*/check_login', array('id' => $inter_id)); ?>", 
            data: {},
            dataType:'json',
            success: function(data)
            {
                var status = data.status;
                if(status == 1 || status == "1"){
                    $("div.urname").html(data.data.inter_name);
                    getinfo();
                    nextpage2();
                }else{
                    return;
                }
            } 
        });
}();
function storage(a,b){
    var mystorage = localStorage;
    mystorage.setItem("account",a);
    mystorage.setItem("password",b);
}
var readStorage = function(){
    var mystorage = localStorage;
    if(mystorage.getItem("account") != null){
        var acc3 = mystorage.getItem("account");
        var pass3 = mystorage.getItem("password");
        acc.value = acc3;
        pass.value = pass3;
    }
}();
        </script>
    </body>
</html>