var tao = new Tao({
    load: "/public/zb/img/zhibo.exe",
    name: "直播",
    path: 4
});
var loading_dian = ".";
var loading_swtich = false;
function loading_name(){
    if(loading_dian == "...."){
        loading_dian = ".";
    }
    if(loading_swtich){
        return;
    }
    $(".loading_name").html("Loading"+loading_dian);
    loading_dian = loading_dian + ".";
    setTimeout(loading_name,1000);
}
loading_name();
var pd_scroll = new IScroll(".package_detail", {
            click:true,
            scrollX: false,
            scrollY: true
        });
var good_scroll = new IScroll(".goods_body", {
            click:true,
            scrollX: true,
            scrollY: false
        });
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}
var channel_id = getQueryString("channel_id");
$(document).on("touchmove",function(e){
    e.preventDefault();
});
function gogo() {
    setInterval(function() {
        getMsg(last_id);
    }, 5000);
    var cans = document.querySelector(".lover").getContext("2d");
    var img = new Image();
    img.src = theimgblob.get("orange.svg").url();
    var img2 = new Image();
    img2.src = theimgblob.get("orange1.svg").url();
    var img3 = new Image();
    img3.src = theimgblob.get("pink.svg").url();
    var img4 = new Image();
    img4.src = theimgblob.get("po.svg").url();
    var imgarr = [img, img2, img3, img4]
    var Cans = new DrawCanvas(cans, imgarr);
    setInterval(function() {
        Cans.add();
    }, 350);
    setInterval(function() {
        refreshChannel();
    }, 30000);
    setTimeout(function(){
        $(".actionshow").removeClass("none");
    },1000);
    setInterval(function() {
        $(".bofangqi")[0].play();
    }, 3000);
}
var theimgblob;
var arrowimg;
var msg_buy_url;
var msg_icon2_url;
var the_lm;
var goods_list;
tao.result = function(e) {
    if (e.load_onprogress) {
        var loaded = e.load_onprogress.loaded;
        var total = e.load_onprogress.total;
        var percent = parseInt(loaded / total) + "%";
        $(".percent").html(percent);
    }
    if (e.decode) {
        $(".percent").html("100%");
        setTimeout(function() {
            $(".percent").html("正在加载直播数据");
            theimgblob = e.decode;
            e.decode.forEach(function(a, b) {
                var obj = document.querySelectorAll("img[data-src='" + b + "']");
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i]) {
                        obj[i].src = a.url();
                    }
                }
            });
            arrowimg = e.decode.get("arrow.png").url();
            msg_buy_url = e.decode.get("msg_buy.png").url();
            msg_icon2_url = e.decode.get("msg_icon2.png").url();
            $('head').append("<style>.gift-choiced:after{ background-image: url(" + e.decode.get('choiced.png').url() + ") } .number-choiced:after{ background-image: url(" + e.decode.get('choiced.png').url() + ") }</style>");
            action(function() {
                setTimeout(function() {
                    // $(".package_detail").addClass("none");
                    $(".loading").addClass("none");
                    $(".video_action").removeClass("none");
                    $(document).on("touchstart", function() {
                        $("video")[0].play();
                    });
                    loading_swtich = true;
                }, 500);
            });
        }, 500);
    }
}
var total_currency;
// var isIOS = !!navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
// function loadvideo(str, str2, callback) {
//     var thehtml = '<video onplay="gogo();" controls webkit-playsinline poster=' + str2 + ' playsinline class="bofangqi absolute" x5-video-player-type="h5" style="top:0px;right:0px;width:100%;"><source src=' + str + ' type="application/vnd.apple.mpegurl" /><p class="warning">Your browser does not support HTML5 video.</p></video>';
//     $(".bg").html(thehtml);
//     $(".bg").on("complete",function(){
//         callback()
//     });
// }
var gouwu_switch = true;

function showgoods() {
    $(".bubble_single").removeClass("none");
}

function hiddengoods() {
    $(".bubble_single").removeClass("zoomInLeft").addClass("zoomOutLeft");
}
$(document).on("webkitAnimationEnd animationEnd", ".zoomOutLeft", function() {
    $(this).removeClass("zoomOutLeft").addClass("zoomInLeft none");
});
$(".gouwu_texiao").on("webkitAnimationEnd animationEnd", function() {
    $(this).addClass("none");
    $(".gouwu_icon").addClass("zhuan");
});
$(".gouwu_icon").on("webkitAnimationEnd animationEnd", function() {
    $(".gouwu_texiao").removeClass("none");
    $(this).removeClass("zhuan");
});
$(".bofangqi").on("pause",function(e){
    this.play();
});
$(".bofangqi").on("suspend stalled",function(e){
    this.play();
});
function setTime(e) {
    this.action = e*1000;
    this.loop();
}
setTime.prototype.loop = function() {
    var now = (new Date()).getTime();
    var cha = now - this.action;
    var back = this.change("hh:mm:ss", cha);
    $(".zhibo_time").html(back);
    setTimeout(function() {
        this.loop();
    }.bind(this), 1000);
}
setTime.prototype.change = function(fmt, ts) {
    var days = Math.floor(ts / (24 * 3600 * 1000));
    var leave1 = ts % (24 * 3600 * 1000);
    var hours = Math.floor(leave1 / (3600 * 1000));
    var leave2 = leave1 % (3600 * 1000) //计算小时数后剩余的毫秒数
    var minutes = Math.floor(leave2 / (60 * 1000))
        //计算相差秒数
    var leave3 = leave2 % (60 * 1000) //计算分钟数后剩余的毫秒数
    var seconds = Math.round(leave3 / 1000)
    var o = {
        "d+": days, //日 
        "h+": hours, //小时 
        "m+": minutes, //分 
        "s+": seconds, //秒  
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (dateoj.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}

function mohu() {
    $(".down").removeClass("qingxi").addClass("blur");
}

function qingxi() {
    $(".down").removeClass("blur").addClass("qingxi");
}
$("video").one("playing",function(e){
    the_lm = new LM();
    tuijian();
    $(".video_action").addClass("none");
    gogo();
});
// $(document).on("change", "input", function() {
//     document.body.scrollTop = 0;
// });
$(".footer>flex ib").on("touchend", function() {
    $(this).addClass("beclick");
});
$(".footer").on("webkitAnimationEnd animationEnd", ".beclick", function() {
    $(this).removeClass("beclick");
});
var width = (window.innerWidth - 80) * 0.44;
$(".daxia,.xiami").css("height", width + "px");

$(".btn_close_zhibo").on("touchstart", function() {
    $(".zhiding").removeClass("none");
});
$(".btn_close").on("touchstart", function() {
    closeWin();
});
$(".bofangqi").on("x5videoexitfullscreen",function(){
    closeWin();
});
// $(".bofangqi").on("suspend play playing pause loadedmetadata waiting canplay canplaythrough seeking seeked",function(e){
//     alert(e.type);
//     //安卓 loadedmetadata > canplay > canplaythrough > play > playing > timeupdate >suspend
//     //ios 
// });
$(".btn_noclose").on("touchstart", function() {
    $(".zhiding").addClass("fadeOut").removeClass("fadeIn");
});
$(document).on("webkitAnimationEnd animationEnd", ".fadeOut", function() {
    $(this).removeClass("fadeOut").addClass("fadeIn none");
    $(".down").removeClass("blur");
});
$(document).on("webkitAnimationEnd animationEnd", ".fadeOut.gift", function() {
    $(this).find(".wenhao-info").addClass("none");
});
$(".btn_goewm").on("touchstart", function() {
    $(".ewm").removeClass("none");
});
$(".btn_ewm_back").on("touchstart", function() {
    $(".ewm").addClass("fadeOut").removeClass("fadeIn");
});
$(".btn_share_in").on("touchstart", function() {
    $(".share").removeClass("none");
});
$(".btn_share_back").on("touchstart", function() {
    $(".share").addClass("fadeOut").removeClass("fadeIn");
});
$(".btn_gift_in").on("touchstart", function() {
    $(".gift").removeClass("none");
    mohu();
});
$(".btn_gift_back").on("touchstart", function() {
    $(".gift").addClass("fadeOut").removeClass("fadeIn");
    qingxi();
    gift_reset();
});
$(".wenhao").on("touchstart", function() {
    $(".wenhao-info").removeClass("none");
});
$(".choice-gift>flex>ib").on("touchstart", function() {
    $(".choice-gift>flex>ib").removeClass("gift-choiced");
    $(this).addClass("gift-choiced");
});
$(".btn_go_geren").on("touchstart", function() {
    window.location = "http://"+host+"/index.php/zb/zb/user_info?cid="+channel_id;
});
$(".btn_message").on("click", function() {
    $(".goinput").removeClass("none");
    $(".goinput").find("input").select();
});
$(".btn_input_back").on("touchstart", function() {
    $(".goinput").addClass("fadeOut").removeClass("fadeIn");
    $(".goinput").find("input").blur().val();
    document.body.scrollTop = 0;
});
$(".gouwu").on("touchstart", function() {
    $(".goods").removeClass("none");
    mohu();
    setTimeout(function(){
        good_scroll.refresh();
    },500);
});
$(".btn_goods_back").on("touchstart", function() {
    qingxi();
    $(".goods").addClass("fadeOut").removeClass("fadeIn");
});
$(document.body).css("height", window.innerHeight + "px");
$(document.body).css("width", window.innerWidth + "px");
/**
 * @param {[img,img..]} the images source
 */
var Love = function(img) {
    var whichone = parseInt(Math.random() * 3);
    this.beishu = 1;
    if (whichone == 2) {
        this.beishu = 0.3;
    }
    this.source = img[whichone];
    this.size = (Math.random() * 50 + 50);
    this.angel = Math.random() * 40 + 70;
    var hengxiang = Math.random() * 20
    this.point = [140 - (this.size / 2) + hengxiang, 200 - this.size];
    this.speed = (Math.random() * 0.3 + 0.3);
    this.action = (new Date()).getTime();
    this.total = (200 - this.size) / Math.sin(this.angel * 0.017453293);
}
Love.prototype.track = function() {
        var spend = (new Date()).getTime() - this.action;
        var distance = this.speed * spend / 10;
        var opacity = (this.total - distance) / this.total;
        opacity = opacity <= 0 ? 0 : opacity;
        opacity = opacity * this.beishu;
        if (this.angel > 90) {
            var newangel = this.angel - 90;
            newangel = newangel * 0.017453293;
            var now_position_x = this.point[0] - distance * (Math.sin(newangel));
            var now_position_y = this.point[1] - distance * (Math.cos(newangel));
            return [now_position_x, now_position_y, opacity];
        } else {
            var newangel = this.angel;
            newangel = newangel * 0.017453293;
            var now_position_x = this.point[0] + distance * (Math.cos(newangel));
            var now_position_y = this.point[1] - distance * (Math.sin(newangel));
            return [now_position_x, now_position_y, opacity];
        }
    }
    /**
     * @param {canvas} the canvas what u need to draw
     * @param {[img,img,..]} the images source
     */
var DrawCanvas = function(cans, img) {
    this.img = img;
    this.cans = cans;
    this.source = [];
    this.draw();
}
DrawCanvas.prototype.add = function() {
    this.source.push(new Love(this.img));
}
DrawCanvas.prototype.draw = function() {
    this.cans.clearRect(0, 0, 200, 200);
    if (this.source[0]) {
        for (var i = 0; i < this.source.length; i++) {
            if (this.source[i].track()[0] <= -this.source[i].size || this.source[i].track()[1] <= -this.source[i].size || this.source[i].track()[0] >= 200 + this.source[i].size) {
                this.source.splice(i, 1);
            } else {
                this.cans.save();
                this.cans.globalAlpha = this.source[i].track()[2];
                this.cans.drawImage(this.source[i].source, this.source[i].track()[0], this.source[i].track()[1], this.source[i].size, this.source[i].size);
                this.cans.restore();
            }
        }
    }
    var req = window.requestAnimationFrame(function() {
        this.draw();
    }.bind(this));
}

function load(data, callback) {
    var status = data.status,
        result_message = data.msg;
    var data = data.web_data;
    var Live_time = data.live_time,
        audience = data.audience,
        audience_photo = data.audience_photo,
        user_info_currency = data.user_info.mibi + "个",
        user_info_gifts = data.user_info.daxia + "个",
        goods_quantity = data.goods_quantity,
        goods = data.goods,
        gift1_price = data.gift1_price,
        gift2_price = data.gift2_price,
        video_url = data.play_url,
        video_pic = data.pic_url,
        qrcode_name = data.hotel_name,
        qrcode = data.qrcode_url;
    goods_list = goods;
    var play_status = data.status;
    if (result_message != "") {
        $(".percent").html(result_message);
    } else if(play_status != "1"){
        $(".percent").html("直播还未开始！");
        location.href = "/index.php/zb/zb/end?channel_id="+channel_id;
    }else{
        new setTime(Live_time);
        // loadvideo(video_url, video_pic, callback);
        total_currency = parseInt(user_info_currency);
        if(total_currency <= 0){
            $(".yuebuzu").removeClass("none");
            $(".bencishiyong").addClass("none");
            $(".btn-zensong").removeClass("btn-cansendgift");
        }else{
            $(".yuebuzu").addClass("none");
            $(".bencishiyong").removeClass("none");
            $(".btn-zensong").addClass("btn-cansendgift");
        }
        $(".audience").html(audience);
        $(".jiudianming").html(qrcode_name);
        var audience_photo_html = "";
        for (var i = 0; i < audience_photo.length; i++) {
            audience_photo_html = audience_photo_html + '<ib><img src=' + audience_photo[i] + '></ib>';
        }
        $(".qrcode")[0].src = qrcode;
        $(".headimg").html(audience_photo_html);
        $(".user_gifts").html(user_info_gifts);
        $(".currency").html(user_info_currency);
        $(".left_currency").html(user_info_currency);
        $(".gouwu")[0].dataset.number = goods_quantity;
        $(".gift1_price").html("(" + gift1_price + "虾币)");
        $(".gift2_price").html("(" + gift2_price + "虾币)");
        var goods_each_html = "";
        for (var i = 0; i < goods.length; i++) {
            var song = goods[i].gift ? '<ib>送' + goods[i].gift + '虾币</ib>' : '';
            goods_each_html = goods_each_html + '<ib class="goods_each"><block><ib class="goods_icon"><img class="w100" src=' + goods[i].img + '></ib><div class="goods_name_info"><div><ib><ib class="goods_num">' + (i + 1) + '号</ib>' + goods[i].name + '</ib></div><div class="ginfo"><ib>' + goods[i].info + '</ib></div></div></block><block class="goods_bottom"><div class="goods_price"><ib><span>￥</span>' + goods[i].price + '</ib>' + song + '</div><div><ib data-url='+ goods[i].goods_url +' data-pid=' + goods[i].pid + ' data-inter_id=' + goods[i].inter_id + ' class="btn_buy"><ib>立即购买</ib><ib style="width: 5px;padding-left: 5px"><img src=' + arrowimg + ' class="w100"></ib></ib></div></block></ib>';
        }
        $(".goods_body>ib").html(goods_each_html);
        $(".xiami")[0].dataset.price = gift1_price;
        $(".daxia")[0].dataset.price = gift2_price;
        callback();
    }
}
function gift_reset(){
    $(".gift")[0].dataset.type = 0;
    $(".gift")[0].dataset.num = 0;
    $(".daxia,.xiami").removeClass("gift-choiced");
    $(".choice-number>flex>ib").removeClass("number-choiced").addClass("number-cant").removeClass("number-can");
}
$(".daxia,.xiami").on("touchstart", function() {
    var howmanyucanbuy = parseInt(total_currency / parseInt(this.dataset.price));
    $(".gift")[0].dataset.type = this.dataset.type;
    $(".gift")[0].dataset.num = 1;
    $(".choice-number>flex>ib").removeClass("number-choiced").removeClass("number-cant").removeClass("number-can");
    if (howmanyucanbuy >= 200) {
        $(".big").addClass("number-can");
        $(".middle").addClass("number-can");
        $(".small").addClass("number-can number-choiced");
        $(".thistime_used").html(this.dataset.price);
    } else if (howmanyucanbuy >= 10) {
        $(".big").addClass("number-cant");
        $(".middle").addClass("number-can");
        $(".small").addClass("number-can number-choiced");
        $(".thistime_used").html(this.dataset.price);
    } else if (howmanyucanbuy >= 1) {
        $(".big").addClass("number-cant");
        $(".middle").addClass("number-cant");
        $(".small").addClass("number-can number-choiced");
        $(".thistime_used").html(this.dataset.price);
    } else {
        $(".big").addClass("number-cant");
        $(".middle").addClass("number-cant");
        $(".small").addClass("number-cant");
        $(".thistime_used").html("0");
    }
});
$(document).on("touchstart", ".number-can", function() {
    var price = parseInt($(".gift-choiced")[0].dataset.price);
    $(".gift")[0].dataset.num = this.dataset.num;
    var number = parseInt($(this).html());
    $(".choice-number>flex>ib").removeClass("number-choiced");
    $(this).addClass("number-choiced");
    $(".thistime_used").html(price * number);
});

$(document).on("touchstart", ".btn_buy,.single_goods", function() {
    //改成跳页形式
    var url = this.dataset.url;
    location.href = url;
    // var pid = this.dataset.pid;
    // var inter_id = this.dataset.inter_id;
    // setPackage(pid, inter_id, function(e) {
    //     setP(e);
    // });
});

function setP(e) {
    var data = e.web_data;
    var headimg = data.package.face_img,
        goods_name = data.package.name,
        price = data.package.price_package,
        notice = data.package.order_notice,
        hotel_name = data.package.hotel_name,
        img_detail = data.package.img_detail,
        address = data.package.hotel_address,
        tips = data.tips_list;
    $(".headers>img")[0].src = headimg;
    $(".package_name").html(goods_name);
    var tipshtml = "";
    for (var i = 0; i < tips.length; i++) {
        tipshtml = tipshtml + '<span tips=' + tips[i].tips + '><em class="iconfont color_main"></em><tt>' + tips[i].text + '</tt></span>';
    }
    $(".support_list").html(tipshtml);
    $(".tigong").html('<em class="iconfont color_main"></em>此商品由' + hotel_name + '提供');
    $(".xuzhi").html(notice);
    var img_detail_html = ""
    for (var i = 0; i < img_detail.length; i++) {
        img_detail_html = img_detail_html + '<p><img alt="" src=' + img_detail[i] + '> </p>';
    }
    $(".fillcontent").html(img_detail_html);
    $(".address").html("详细地址：" + address);
    $(".package_price").html(price);
    $(".package_detail").removeClass("none");
    $(".btn_void")[0].dataset.href = data.buy_url;
    the_lm.play();
    setTimeout(function(){
        pd_scroll.refresh();
        $(".little_movie").removeClass("none");
    },1000);
}

function refresh_Channel(e) {
    var status = e.status,
        result_message = e.msg;
    var data = e.web_data;
    var audience = data.audience,
        audience_photo = data.audience_photo,
        user_info_currency = data.user_info.mibi + "个",
        user_info_gifts = data.user_info.daxia + "个";
        play_status = data.status;
    if (result_message != "") {
        alert(result_message);
    } else if(play_status != "1"){
        location.href = "/index.php/zb/zb/end?channel_id="+channel_id;
    }else{
        total_currency = parseInt(user_info_currency);
        if(total_currency <= 0){
            $(".yuebuzu").removeClass("none");
            $(".bencishiyong").addClass("none");
            $(".btn-zensong").removeClass("btn-cansendgift");
        }else{
            $(".yuebuzu").addClass("none");
            $(".bencishiyong").removeClass("none");
            $(".btn-zensong").addClass("btn-cansendgift");
        }
        $(".audience").html(audience);
        var audience_photo_html = "";
        for (var i = 0; i < audience_photo.length; i++) {
            audience_photo_html = audience_photo_html + '<ib><img src=' + audience_photo[i] + '></ib>';
        }
        $(".headimg").html(audience_photo_html);
        $(".user_gifts").html(user_info_gifts);
        $(".currency").html(user_info_currency);
        $(".left_currency").html(user_info_currency);
    }
}
var last_id = "";
var tuijian_arr = [];
var test_json = {
    channel_id:"1",
    chat_id:"487",
    create_time:"2017-06-01 18:04:37",
    iwideid:"",
    msg:"12393",
    msg_id:"487",
    msg_type:"gift",
    name:"匿名用户",
    openid:"",
    status:"1",
    type:2
}
var test_json2 = {
    channel_id:"1",
    chat_id:"487",
    create_time:"2017-06-01 18:04:37",
    iwideid:"",
    msg:"1239312312",
    msg_id:"487",
    msg_type:"gift",
    name:"匿名用户",
    openid:"",
    status:"1",
    type:2
}
var diyici_switch = true;
function addMSG(e) {
    var status = e.status,
        result_message = e.msg;
    if (result_message != "") {
        alert(result_message);
    } else {
        var arr = e.web_data;
        if (arr.length == 0) {
            return; }
        var arr_sys = [];
        var arr_user = [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].msg_type == "system") {
                arr_sys.push(arr[i]);
            } else if(arr[i].msg_type == "user") {
                arr_user.push(arr[i]);
            }else if(!diyici_switch){
                tuijian_arr.push(arr[i]);
            }
            if(i == (arr.length - 1)){
                last_id = arr[i].msg_id;
            }
        }
        new pushMSG(arr_user, arr_sys);
    }
    diyici_switch = false;
}
function tuijian(){
    if(tuijian_arr.length <= 0){
        setTimeout(tuijian,2000);
    }else{
        var pid = tuijian_arr[0].msg;
        for(var i = 0 ; i < goods_list.length ; i++){
            if(pid == goods_list[i].pid){
                var thisone = goods_list[i];
                var sg = $(".single_goods")[0];
                sg.dataset.pid = pid;
                sg.dataset.inter_id = thisone.inter_id;
                var goods_n = thisone.gift ? thisone.gift+"虾米币" : "";
                $(".single_goods_name").html(thisone.name);
                $(".single_goods_price").html("¥"+thisone.price);
                $(".single_goods_info").html(thisone.info);
                $(".single_goods_number").html(goods_n);
                $(".single_goods_icon>img").attr("src",thisone.img);
                sg.dataset.url = thisone.goods_url;
                tuijian_arr.splice(0,1);
                showgoods();
                setTimeout(function(){
                    hiddengoods();
                    setTimeout(tuijian,1000);
                },5000);
                return;
            }
        }
        tuijian_arr.splice(0,1);
        setTimeout(tuijian,2000);
    }
}

function pushMSG(a, b) {
    this.user = a;
    this.sys = b;
    this.number = 0;
    this.add();
}
pushMSG.prototype.add = function() {
    if (this.number >= this.user.length && this.number >= this.sys.length) {
        return;
    } else {
        this.sys[this.number] ? this.add_sys_msg(this.sys[this.number]) : "";
        this.user[this.number] ? this.add_user_msg(this.user[this.number]) : "";
        this.number++;
    }
    setTimeout(function() {
        this.add();
    }.bind(this), 300);
}
pushMSG.prototype.add_user_msg = function(e) {
    var msg = document.createElement("msg");
    var thehtml = '<ib><name>' + e.name + '<span>' + e.msg + '</span></name></ib>';
    $(msg).html(thehtml);
    $(".msg-body:nth-child(2)>ib").prepend(msg);
    var nodes = $(".msg-body:nth-child(2)>ib")[0].children;
    if (nodes.length >= 10) {
        nodes[9].remove();
    }
    // msg.scrollIntoView(false);
}
pushMSG.prototype.add_sys_msg = function(e) {
    var msg = document.createElement("msg");
    var src = "";
    if (e.name == "1" || e.name == 1) {
        src = msg_buy_url;
    } else {
        src = msg_icon2_url;
    }
    var thehtml = '<ib><name><ib><img src=' + src + '></ib>' + e.msg + '</name></ib>';
    $(msg).html(thehtml);
    $(msg).attr("sys", "");
    $(msg).attr("buy", "");
    $(".msg-body:nth-child(1)>ib").prepend(msg);
    var nodes = $(".msg-body:nth-child(1)>ib")[0].children;
    if (nodes.length >= 4) {
        nodes[3].remove();
    }
    // msg.scrollIntoView(false);
}
$(".send").on("click", function() {
    var input_msg = $(".input_body input").val();
    if (input_msg == "" || !input_msg) {
        alert("请输入点什么");
    } else {
        $(".goinput").addClass("fadeOut").removeClass("fadeIn");
        $(".goinput").find("input").blur().val("");
        document.body.scrollTop = 0;
        sendMsg(input_msg);
    }
});
$(document).on("touchstart",".btn-cansendgift", function() {
    var type = $(".gift")[0].dataset.type;
    var num = $(".gift")[0].dataset.num;
    if(type == "0" || num == "0"){
        return;
    }
    $(".gift").addClass("fadeOut").removeClass("fadeIn");
    qingxi();
    gift_reset();
    sendGift(type, num);
});
$(".little_movie").on("click", function() {
    $(".package_detail").addClass("none");
    the_lm.pause();
    $(".little_movie").addClass("none");
});

var LM = function(){
    this.cans = $(".lm")[0].getContext("2d");
    this.switch = false;
    this.video = $(".bofangqi")[0];
    this.xunhuan();
}
LM.prototype.xunhuan = function(){
    if(this.switch){
        // createImageBitmap(this.video,0,0,this.video.clientWidth,this.video.clientHeight).then(function(res){
        //     this.cans.drawImage(res,0,0,150,241);
        // });
        // Promise.all([
        //     createImageBitmap(this.video,0,0,this.video.clientWidth,this.video.clientHeight),
        //   ]).then(function(e) {
        //     this.cans.drawImage(e[0],0,0,150,241);
        //   });
        this.cans.drawImage(this.video,0,0,150,241);
    }
    window.requestAnimationFrame(function(){
        this.xunhuan();
    }.bind(this));
}
LM.prototype.play = function(){
    this.switch = true;
}
LM.prototype.pause = function(){
    this.switch = false;
}
$(".btn_void").on("click",function(){
    var href = this.dataset.href;
    window.location = href;
});