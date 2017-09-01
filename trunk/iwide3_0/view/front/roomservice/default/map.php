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
    <title>地图</title>
    <?php echo referurl('css','global.css',1,$media_path) ?>
    <?php echo referurl('css','service.css',1,$media_path) ?>
    <?php echo referurl('js','jquery.js',1,$media_path) ?>
    <?php echo referurl('js','ui_control.js',1,$media_path) ?>
    <?php echo referurl('js','alert.js',1,$media_path) ?>
    <script type='text/javascript'>
        var _vds = _vds || [];
        window._vds = _vds;
        (function(){
            _vds.push(['setAccountId', '9035a905d6d239a4']);
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
<page class="page h24">
    <header>
        <div class="pad10">
            <div class="searchbox" style="padding-left:10px"><em class="iconfont color_999">&#X0A15;</em><input id="keyword" placeholder="搜索关键字" class="h24"></div>
        </div>
    </header>
    <section class="scroll flexgrow h26" id="AddressList">
    </section>
    <footer></footer>
</page>
</body>
<script src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js "></script>
<script>
    var key = '6RABZ-LJEKJ-PCNFY-FMHHT-HN2LZ-VCFI6';
    var user = {};
    var city = '';
    function filladdress(data,iscur){
        if(iscur==undefined)iscur=false;
        var str = '<div class="flex bg_fff pad10">'
            + '<em class="iconfont h36 color_main">&#XA8;</em>'
            + '<div class="marleft"><div class="h26">';
        if(iscur) str +='<span class="color_main">[当前] </span>';
        str+= data.name;
        str+= '</div><div class="h20 color_C3C3C3">'
        if(data.address)str+= data.address;
        else str+=' 定位失败，请手动搜索地址 '
        str+= '</div></div></div>';
        var TmpObj = $(str);
        $('#AddressList').append(TmpObj);
        if(data.address){
            TmpObj.click(function(){
                try{
                    user= $.parseJSON($.getsession('user'));
                    //console.log(typeof(user));
                    if(user==''||typeof(user)=='string')user={};
                }catch(e){
                    user={};
                }
                user.select_addr = data.name;
                user.address = data.address;
                user.latLng = data.latLng;
                $.setsession('user',JSON.stringify(user));
                window.location.href=document.referrer;
            })
        }
    }
    $(function(){
        var searchService, markers = [];
        var geolocal = new qq.maps.Geolocation(key, 'myapp');
        //设置搜索的范围和关键字等属性
        $('#keyword').change(function() {
            var keyword = $(this).val();
            var url = 'http://apis.map.qq.com/ws/place/v1/suggestion';
            //url+= '?key='+key+'&keyword='+keyword;
            pageloading();
            $.ajax({
                type:"get",
                dataType:'JSONP',
                data:{
                    region:city,
                    keyword:keyword,
                    key:key,
                    output:'jsonp'
                },
                jsonp:"callback",
                jsonpCallback:"QQmap",
                url:url,
                success:function(json){
                    /*json对象转为文本 var aToStr=JSON.stringify(a);*/
                    //var toStr = JSON.stringify(json);
                    console.log(json);
                    $('#AddressList').html('');
                    $.each(json.data,function(m,n){
                        filladdress({
                            name:n.title,
                            address:n.address,
                            latLng:{
                                lat:n.location.lat,
                                lng:n.location.lng
                            }
                        },false);
                    });
                    if(json.data.length<=0)
                        $('#AddressList').html('<div class="pad15 h20 bg_fff">无结果</div>');
                    //console.log(toStr);
                },
                error : function(err){console.log(err);$('#AddressList').html('<div class="pad15 h20 bg_fff">无结果</div>');},
                complete: function(){removeload();}

            })
        })
        geolocal.getLocation(function(data){
            removeload();
            $('#AddressList').html('');
            city = data.city;
            var address = data.district?data.district:'';
            address = city + address;
            filladdress({
                name:data.addr,
                address:address,
                latLng:{
                    lat:data.lat,
                    lng:data.lng
                }
            },true);
        },function(){
            removeload();
            $.MsgBox.Alert('网络超时，请稍后再试');
        },{timeout:30000})
    });
</script>
</html>
