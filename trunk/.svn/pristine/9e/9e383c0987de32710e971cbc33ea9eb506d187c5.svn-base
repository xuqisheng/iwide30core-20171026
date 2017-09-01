try{
String.prototype.checklength = function(){
    var char = this.replace(/[^\x00-\xff]/g, 'xx');
    return char.length;
}
String.prototype.cutstr = function(len,suffix){
    if(!suffix) suffix = "";
    if(len <= 0) return "";
    if(this.checklength == 0) return "";
    var templen=0;  
    for(var i=0;i<this.length;i++){  
        if(this.charCodeAt(i)>255){  
            templen+=2;  
        }else{  
            templen++  
        }
        if(templen > len){
            break;  
        }
    }  
    if(templen >len){  
        this.becut = this.substring(0,i)+suffix; 
        return this.becut;
    }  
    return this; 
}
String.prototype.insecret = function(){
    var str1 ="";
    for(var i = 0; i < this.length ; i ++ ){
        var lz = this[i].charCodeAt(0);
        lz = lz <= 255? "00"+lz.toString(16) : lz <= 4095 ? "0" + lz.toString(16) : lz.toString(16);
        str1 = str1 +"\\u"+ lz;
    }
    return str1;
}
String.prototype.unsecret = function(){
    var str1 = this;
    str1 = str1.replace(/(%5C)/g,"\\");
    str1 = eval("'" + str1 + "'");
    return str1;
}
String.prototype.phone = function(){
    var partten = /^1[3,5,8]\d{9}$/
    return partten.test(this) ? true : false;
}
String.prototype.js = function(){
    var src = this;
    var oscript = document.createElement("script");
    oscript.type = "text/javascript";
    oscript.src = this;
    document.body.appendChild(oscript);
}
String.prototype.css = function(){
    var oHead = document.getElementsByTagName('HEAD').item(0);
    var src = this;
    var oscript = document.createElement("link");
    oscript.type = "text/css";
    oscript.rel = "stylesheet";
    oscript.href = this;
    oHead.appendChild(oscript);
}
/*-------------字符串操作-----------------*/
/*---------------用法------------------*/
/*
var fc = new Formcheck(str); str为需要操作的字符串
fc.checklength();
fc.cutstr(5,"..",function); 5为保留的位数，结尾加上..,function结束时回调, 返回新的字符串
fc.insecret(); 加密str
fc.unsecret(); 解密str
*/
var Tao = function(objname){
    this.objname = objname;
    this.load();
    this.result;
}
Tao.prototype.load = function(){
    var getstr = new XMLHttpRequest();
    getstr.onloadend = function(){
        this.json = JSON.parse(getstr.response);
        var getfile = new XMLHttpRequest();
        getfile.onprogress = function(e){
            this.onprogress(e);
        }.bind(this)
        getfile.onloadend = function(){
            this.abuffer = getfile.response;
            this.decode();
        }.bind(this)
        getfile.open("GET",this.objname + ".png",true);
        getfile.send();
        getfile.responseType = "arraybuffer";
    }.bind(this)
    getstr.open("GET",this.objname + ".txt",true);
    getstr.send();
    getstr.responseType = "text";
}
Tao.prototype.decode = function(){
    var kaishi = 0;
    var shuzu2 = {};
    this.source;
    var shuzu = {};
    this.url = [];
    for(var i = 0 ; i<this.json.name.length ; i++){
        var thelength = parseInt(this.json.size[i]);
        var theDV = new DataView(this.abuffer,kaishi,thelength);
        var theabuffer = this.abuffer.slice(kaishi,thelength);
        var theblob = new Blob([theDV],{ "type" : this.json.type[i] });
        kaishi = kaishi + thelength;
        var Url = window.URL.createObjectURL(theblob);
        this.url.push(Url);
        shuzu[this.json.name[i].replace(".","_")] = Url;
        shuzu2[this.json.name[i].replace(".","_")] = theabuffer;
    }
    this.result = shuzu;
    this.source = shuzu2;
    this.loaded(this.result,this.source,this.url);
}
Tao.prototype.onprogress = function(){}
Tao.prototype.loaded = function(){}
/*--------------预加载-----------------*/
/*---------------用法------------------*/
/*
function xxx(json){
    var tao = new Tao("wf2.tao",json);   变量名+整合数据包的json
    tao.onprogress = function(e){
        console.log(e); e为当前已加载的数据量
    }
    tao.loaded = function(){
        console.log(this.result);返回一个素材名+obj地址的json
    }
}
*/


function Orient(callback){
    this.callback = callback || "";
    this.obj = document.createElement('div');
    document.body.appendChild(this.obj);
    this.obj.className = "mod-orient-layer none";
    this.obj.id = "orientLayer";
    this.obj.innerHTML = '<div class="mod-orient-layer__content"><i class="icon mod-orient-layer__icon-orient"></i><div class="mod-orient-layer__desc">为了更好的体验，请锁定屏幕旋转后浏览</div></div>';
    this.styles = document.createElement('style');
    document.body.appendChild(this.styles);
    this.styles.innerHTML = '@-webkit-keyframes rotation{10%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}50%{transform:rotate(0);-webkit-transform:rotate(0)}60%{transform:rotate(0);-webkit-transform:rotate(0)}90%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}100%{transform:rotate(90deg);-webkit-transform:rotate(90deg)}}.mod-orient-layer{display:none;position:fixed;height:100%;width:100%;left:0;top:0;background:#000;z-index:9997}.mod-orient-layer__content{position:absolute;width:100%;top:45%;margin-top:-75px;text-align:center}.mod-orient-layer__icon-orient{display:inline-block;width:67px;height:109px;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIYAAADaCAMAAABU68ovAAAAXVBMVEUAAAD29vb////x8fH////////x8fH5+fn29vby8vL////5+fn39/f6+vr////x8fH////////+/v7////09PT////x8fH39/f////////////////////x8fH///+WLTLGAAAAHXRSTlMAIpML+gb4ZhHWn1c2gvHBvq1uKJcC6k8b187lQ9yhhboAAAQYSURBVHja7d3blpowFIDhTUIAOchZDkre/zE7ycySrbUUpsRN2/1fzO18KzEqxEVgTiZNfgmmtxRc8iaR8HNe8x4BtjQePKayYCIoyBSgvNNE1AkNSHqZyLqk97EgUCCHBzZ5mkg7ScvIJuIyOyXBRFxgpqWZyGsAZLB1KjsJi8nutHU4JCRbFRH8tmirI9k8Jx2sqNs8K/m0LQkrktO2crgcgXGB4AiTEsB0hJfo9MGgX7CGcYiYwQxmMOOvZwRhBG8tCoMXjBDeXvWCEcHbi14wgCBmMIMZzGAGM5jxETNwzMAxA8cMHDNwzMAxA8cMHDNwzMAxA8cMHDNwzMAxY6E2rUQxnH2tz9cirlJFwFBJedaPnUv0M7++egPDE8iAJcIDmxwH5wwv9vUviw2kLbVO3TJU5uul/EyB0FoLp4x60PdGUd3qPurrWyjGGTc05u+1dcgI7/+tCCPARWGhH7o5Y7RCf+bH9ctXLp6v2BVDxfqz0oPXeSVaNtINo/1SXDv4dck8IIkbhtC2ol+iouEonTBCbYvVMnXOjxww6s/RFrBUpXHh/gw1rHj5d/qhYn9Gpk2FWh6xRBRX5Oj3Znh2Sq49/L6+y8pB26q9GbE2dbA2mVbx6I+7MfBglLCttm73ZQi7AD3iL4HqjFYJHSPRppqaUaJ3ATpGa+ckpGak2hRRMyqjGMkvl+xyFeSMwjAqcsZgGDdyhl0oNTnDN4yenJGZFGxNChP5/Y3efh6SM2rDOJMzboYxkDMqwyjIGcIw6F+io2FU1IxIm1JqRmgXSkvNKNCXeTpGrU0JNSO2c6LIGPgCS8AuDHz9ta0SXWDtxoDRH+MqlbC2Dt2G2JFRadtQZt2qq/orGowdGb2euxYiqWEpVWhTBnszoNAPdStuQwxqf0aocdWKW4Z+DfszIh8pxJqbuCE4YAC+4bm0evtipjpgJHeFnyyt1Ku2xa0bhjxr27p75rECNwyI9ZwvXkHq+7aTaMEV44YYy/spfgjgjNHaWW+GeUhGEX7tLlVinIFDDSgnOwhi1V6bU0b6tVS9eAERe863g4dRrtiHdc6o+nn5vtyVVgR79Cqt4uL6gfHPQyGqtP2vf7HADGbcYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JiBYwaOGThm4JjhtOM+J/AgT008yDMkN/dPP9hzS8zAMQN3OEYeekp5YU7KOKXwVXqiY+QS7smcinGKABWdiBgpPJTSMHJ4KidhhPBUSMLw4CmPhKHgKUXCkHsygum71ftNSgCX6bsl8FQyfbcL5EdYsDk0R3j7aiA5wpt5AjKg/2gLJEBD/0Hf2OOf/vRrj6z/7GtP4B3nMKyjHA12kIPSjnJs3FEO0TvKkYJHOWCR+rjJH0Vn6fI5PjNbAAAAAElFTkSuQmCC);transform:rotate(90deg);-webkit-transform:rotate(90deg);-webkit-animation:rotation infinite 1.5s ease-in-out;animation:rotation infinite 1.5s ease-in-out;-webkit-background-size:67px;background-size:67px}.mod-orient-layer__desc{margin-top:20px;font-size:15px;color:#fff}.mod-orient-layer__desc{margin-top:20px;font-size:15px;color:#fff}';
    var ori = "onorientationchange" in window ? "orientationchange" : "resize";
    this.orientNotice();
    window.addEventListener(ori,function(){
        setTimeout(function(){
        this.orientNotice();
        }.bind(this),200);
    }.bind(this)); 
}
Orient.prototype.orientNotice = function(){
    var orient = this.checkDirect(); 
    if (orient == "portrait") { 
        this.obj.style.display = "none";
    } else { 
        this.obj.style.display = "block";
    }
}
Orient.prototype.checkDirect = function(){
    if (document.documentElement.clientHeight >= document.documentElement.clientWidth) {
        return "portrait"; 
    } else { 
        return "landscape"; 
    } 
}
/*-------------转屏提示-----------------*/
/*---------------用法------------------*/
/*new Orient(); */

function Count(num){
    this.gogogo = true;
    this.num = num;
    this.thedate = new Date();
    this.action = this.thedate.getTime(); 
    this.go();
}
Count.prototype.go = function(){
    this.thedate = new Date();
    this.now = this.thedate.getTime();
    this.cha = (this.now - this.action)/1000;
    this.onplay(this.cha);
    if(this.cha > this.num || !this.gogogo){
        return;
    }
    window.requestAnimationFrame(function(){this.go()}.bind(this));
}
Count.prototype.pause = function(){
    this.gogogo = false;   
}
Count.prototype.play = function(){
    this.gogogo = true;   
}
Count.prototype.onplay = function(e){}
/*-------------倒计时-----------------*/
/*---------------用法------------------*/
/*
var count = new Count(50,function); num为倒计时初始数值，function为数到0的回调 
count.pause();暂停
count.play();继续
*/

/*function index(str){
    var windowwidth = window.innerWidth;
    var windowheight = window.innerHeight;
    var scaleX = windowwidth/640;
    var scaleY = windowheight/1008;
    if(str != undefined){
        var obj = document.getElementById(str) || document.getElementsByClassName(str)[0];
        obj.style.transform = "scaleX("+ scaleX +") scaleY("+scaleY+")";
        obj.style.webkitTransform = "scaleX("+ scaleX +") scaleY("+scaleY+")";
    }
    var styles = document.createElement('style');
    document.body.appendChild(styles);
    styles.innerHTML = 'p{margin:0;font-family:HeiTi SC}.w100{width:100%}.absolute{position:absolute}body{width:100%;height:100%;position:absolute;margin:0;overflow:hidden;font-size:14px;font-family:Heiti SC}.right{float:right}.full{width:100%;height:100%}.maxscreen{width:100%}.left{float:left}.coverpage{width:100%;height:100%;background-color:#000;opacity:.8}.fixed{position:fixed}.none{display:none}.relative{position:relative}.left{float:left}.fullbg{background-size:100% 100%}.main{transform-origin:top left;-webkit-transform-origin:top left;-o-transform-origin:top left;-moz-transform-origin:top left;}';
}
function indexgo(){
    $('.main').one("webkitTransitionEnd",function(){
         document.styleSheets[1].insertRule('.main::after{display:none;}',4);
    });
    document.styleSheets[1].insertRule('.main::after{background-color:transparent;}',3);   
}*/
/*-------------框架初始化-----------------*/
/*---------------用法------------------*/
/*
    index('main');
*/
Array.prototype.preload = function(callback){
    this.js = 0;
    this.callback = callback;
    this.loop();
    for (var i=0;i<this.length;i++){
        var result = this[i].indexOf(".mp3");
        if(result != -1){
            var bg = new Audio();
            bg.oncanplaythrough = function(){
                bg.pause();
                this.js = this.js + 100/this.length; 
            }.bind(this)
            bg.src = this[i];
            bg.load();
            bg.playbackRate=0.01;
            bg.play();
        }else{
            var sucai = new Image();
            sucai.onload = function(){
                this.js = this.js + 100/this.length; 
            }.bind(this)
            sucai.src = this[i];
        }
    }
}
Array.prototype.loop = function(){
    if(this.js < 99){
        this.percent();
        this.raf = window.requestAnimationFrame(function(){
            this.loop();
        }.bind(this));
    }else{
        this.percent();
        this.callback();   
    }
}
Array.prototype.percent = function(){}
Array.prototype.choice = function(e){
    this.select = this[e];
    return this.select;
}
Array.prototype.remove = function(val) { var index = this.indexOf(val); if (index > -1) { this.splice(index, 1); } };
String.prototype.assign = function(e){
	var objlist = document.querySelectorAll(e);
	for(var i = 0; i <= objlist ; i++){
		var obj = objlist[i];
		if(obj.nodeName == "img" || obj.nodeName == "IMG"){
	        obj.src = this;  
	    }else{
	    	obj.style.backgroundImage = "url("+this+")";
	    }
	}
}
String.prototype.none = function(){
	var objlist = document.querySelectorAll(this);
	for(var i = 0; i < objlist.length ; i++){
		var obj = objlist[i];
		if (!hasClass(obj, "none")) obj.className += " " + "none"; 
	} 
}
String.prototype.block = function(){
    var objlist = document.querySelectorAll(this);
	for(var i = 0; i < objlist.length ; i++){
		var obj = objlist[i];
		if (hasClass(obj, "none")) { 
			var reg = new RegExp('(\\s|^)' + 'none' + '(\\s|$)');  
        	obj.className = obj.className.replace(reg, ' ');  
        }
	}  
}
function hasClass(obj, cls) {  
    return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));  
}  
function Touch(obj){
    var ele = obj ? obj : document;
    var eventstart = "ontouchstart" in window ? "touchstart" : "mousedown";
    var eventend = "ontouchstart" in window ? "touchend" : "mouseup";
    var eventmove= "ontouchstart" in window ? "touchmove" : "mousemove";
    this.beforex=0,this.afterx=0;
    this.beforey=0,this.aftery=0;
    this.chax = 0,this.chay = 0;
    ele.addEventListener(eventstart,function(e){
        this.tstart(e);
    }.bind(this));
    ele.addEventListener("touchmove",function(e){
        this.tmove(e);
    }.bind(this));
    ele.addEventListener(eventend,function(e){
        this.tend(e);
    }.bind(this));
}
Touch.prototype.tmove = function(e){
    this.afterx = "ontouchstart" in window ? e.targetTouches[0].clientX : e.x;
    this.aftery = "ontouchstart" in window ? e.targetTouches[0].clientY : e.y;
    this.chax = this.afterx - this.beforex;
    this.chay = this.aftery - this.beforey;
    this.beforex = this.afterx;
    this.beforey = this.aftery;
    var cha = {
        x : this.chax,
        y : this.chay,
        clientX : this.afterx,
        clientY : this.aftery,
        moveX : this.chax,
        moveY : this.chay
    }
    this.move(cha);
}
Touch.prototype.tstart = function(e){
    this.beforex=0,this.afterx=0;
    this.beforey=0,this.aftery=0;
    this.checkx = 0,this.checky = 0;
    this.beforex = this.afterx = "ontouchstart" in window ? e.targetTouches[0].clientX : e.x;
    this.beforey = this.aftery = "ontouchstart" in window ? e.targetTouches[0].clientY : e.y;
    this.checkx = this.beforex;
    this.checky = this.beforey;
    var cha = {
        clientX : this.beforex,
        clientY : this.beforey
    }
    this.start(cha);
}
Touch.prototype.tend = function(e){
    this.afterx = "ontouchstart" in window ? this.afterx : e.x;
    this.aftery = "ontouchstart" in window ? this.aftery : e.y;
    this.chax = this.afterx - this.checkx;
    this.chay = this.aftery - this.checky;
    var cha = {
        x : this.chax,
        y : this.chay,
        clientX : this.afterx,
        clientY : this.aftery,
        moveX : this.chax,
        moveY : this.chay
    }
    this.end(cha);
    this.beforex = this.afterx = 0;
    this.beforey = this.aftery = 0;
    this.checkx = this.checky = 0;
}
Touch.prototype.move = function(e){}
Touch.prototype.end = function(e){}
Touch.prototype.start = function(e){}

function Shake(){
    this.last_update = 0;
    this.range = 3000;
    this.x = this.y = this.z = this.last_x = this.last_y =  this.last_z = 0;
    window.addEventListener("devicemotion",function(e){
         this.dm(e);
    }.bind(this));
}
Shake.prototype.dm = function(e){
    var acceleration = e.accelerationIncludingGravity;  
    var curTime = new Date().getTime();  
    if ((curTime - this.last_update) > 100) {  
        var diffTime = curTime - this.last_update;  
        this.last_update = curTime;  
        this.x = acceleration.x;  
        this.y = acceleration.y;  
        this.z = acceleration.z;  
        var speed = Math.abs(this.x + this.y + this.z - this.last_x - this.last_y - this.last_z) / diffTime * 10000;
        this.end(speed);
        this.last_x = this.x;  
        this.last_y = this.y;  
        this.last_z = this.z;  
    } 
}
Shake.prototype.end = function(){}



function TaoVideo(arr,num,arr2){
    this.arr2 = arr2 ? arr2.slice(0) : [];
    this.arr3 = arr2 ? arr2.slice(0) : [];
    this.frame = num ? num : 16;
    this.switch = false;
    this.duration = arr.length;
    this.currentTime = 0;
    this.render = new Image();
    this.arr = arr;
    this.movie();
}
TaoVideo.prototype.movie = function(){
    if(this.switch){
        if(this.arr2.indexOf(this.currentTime) > -1){
            this.switch = false;
            this.arr2.remove(this.currentTime);
            this.stop(this.currentTime);
        }else{
            this.render.onload = function(){
                this.result(this.render);
            }.bind(this)
            this.render.src = this.arr[this.currentTime];
            this.currentTime++;
            if(this.currentTime >= this.duration){
                this.currentTime = 0;
                this.switch = false;
                this.arr2 = this.arr3.slice(0);
                this.stop(this.currentTime);
            }
        }
    }
    if(this.frame <= 16){
        window.requestAnimationFrame(function(){
            this.movie();
        }.bind(this));
    }else{
        setTimeout(function(){
            this.movie();
        }.bind(this),this.frame);
    }
}
TaoVideo.prototype.result = function(){};
TaoVideo.prototype.play = function(){
    this.switch = true;
}
TaoVideo.prototype.pause = function(){
    this.switch = false;
}
TaoVideo.prototype.stop = function(){}
/*    
taovideo = new TaoVideo(tao.url,100,[49]);
taovideo.result = function(e){
    cans1.drawImage(e,0,0,374,666);
}
taovideo.stop = function(e){
    if(e == 49){
        p3();
    }
    if(e == this.duration){
        p5();
    }
}
taovideo.play()  taovideo.pause()
*/
function TaoAudio(ab){
    this.ab = ab;
    this.loop = false;
    this.audioCtx = new (window.AudioContext || window.webkitAudioContext);
    this.audioCtx.suspend();
    this.source = this.audioCtx.createBufferSource();
    this.source.connect(this.audioCtx.destination);
}
TaoAudio.prototype.load = function(){
    this.audioCtx.decodeAudioData(this.ab,function(buffer){
        this.source.buffer = buffer;
        this.source.loop = this.loop;
        this.source.start(0);
    }.bind(this));  
}
TaoAudio.prototype.play = function(){
    this.source.loop = this.loop;
    this.audioCtx.resume();   
}
TaoAudio.prototype.pause = function(){
    this.audioCtx.suspend();   
}

function BGMControl(obj,tof){
    this.switch = tof ? tof : true;
    this.btn = document.createElement("div");
    this.btn.className = "mbtn absolute";
    document.body.appendChild(this.btn);
    this.img = document.createElement("img");
    this.img.className = "w100 left";
    this.btn.appendChild(this.img);
    this.obj = obj;
    this.go ="data:image/png;base64,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";
    this.stop="data:image/png;base64,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";
    this.set();
}
BGMControl.prototype.set = function(){
    var eventstart = "ontouchstart" in window ? "touchstart" : "mousedown";
    this.img.src = this.switch ? this.go : this.stop;
    this.btn.addEventListener(eventstart,function(){
        this.control();
    }.bind(this));
}
BGMControl.prototype.control = function(){
    if(this.switch){
        this.obj.pause();
        this.img.src = this.stop;
        this.switch = false;
    }else{
        this.obj.play();
        this.img.src = this.go;
        this.switch = true;  
    }
}
}catch(e){
    alert(e.name + ":" + e.message);   
}