// JavaScript Document
function AlertBox(cfg) {
    this.config = {
        site:'mid',
        top: 0,
        left: $(window).width() / 2,
        title:'提示',
        content: '正在处理',
        windowDom: window,
        setTime: 0,
        hasMask: true,
        hasMaskWhite: false,
        clickDomCancel: false,
        callBack: null,
        ok: null,
        cancel: null,
        hasBtn: false,
        type: 'success',
        btnVal: 'close',
        okVal: '确定',
        cancelVal:'取消',
        quickClose:true,
        time:3000,
        showModel:true,
        showMask:'',
        dourl:null,
        _sthis:null
    };
    $.extend(this.config, cfg);
}

//外层box
AlertBox.prototype.boundingBox = null;
AlertBox.prototype._sthis = null;

//渲染
AlertBox.prototype.show = function(){
    //存在就retrun
    if(AlertBox.prototype.boundingBox) return;
    AlertBox.prototype._sthis=this;
    if(this.config.showModel===true)
        this.config.showMask='<div class="sweet-overlay" tabIndex="-1" onclick="AlertBox.prototype._sthis.close3s()"></div>';

    switch (this.config.type){
        case 'loading':
            this.loading();
            break;
        case 'tip':
            this.show3sMsg();
            this._show3sMsg();
            break;
        case 'info':
            this.showMsg();
            this.showBox();
            break;
        case 'confirm':
            this.showConfirmMsg();
            this.showBox();
            break;
    }

    $('button.okButton').on('click',function(){
        if(typeof AlertBox.prototype._sthis.config.ok === "function"){
            var t=AlertBox.prototype._sthis.config.ok();
            if(t!==false) AlertBox.prototype._sthis.btnremove();
        }else{
            AlertBox.prototype._sthis.btnremove();
        }

    });

    $('button.cancelButton').on('click',function(){
        if(typeof AlertBox.prototype._sthis.config.cancel === "function"){
            var t=AlertBox.prototype._sthis.config.cancel();
            if(t!==false) AlertBox.prototype._sthis.btnremove();
        }else{
            AlertBox.prototype._sthis.btnremove();
        }

    });
    return this;
};

AlertBox.prototype.showSite=function(){
    AlertBox.prototype.boundingBox.appendTo(this.config.windowDom.document.body);
    var as3h=$("#alert_show_3").outerHeight();
    switch (this.config.site){
        case 'top': this.config.top=($(window).height()-as3h)/20;break;
        case 'topmid': this.config.top=($(window).height()-as3h)/4;break;
        case 'mid': this.config.top=($(window).height()-as3h)/2;break;
        case 'bottom': this.config.top=($(window).height()-($(window).height()/20))-as3h;break;
        default:this.config.top=($(window).height()-as3h)/2;break;
    }
    var as3w=$("#alert_show_3").outerWidth();
    this.config.left=($(window).width()-as3w)/2;
    $("#alert_show_3").css('left',this.config.left+'px');
};

AlertBox.prototype.show3sMsg=function(){
    AlertBox.prototype.boundingBox=$('<div id="alert_dialog_show_3s_box" class="show3s-msg"><div id="alert_show_3" class="show-box" style="top:'+this.config.top+'px;left:'+this.config.left+'px;"><p>'+this.config.content+'</p></div></div>');
};

AlertBox.prototype._show3sMsg=function(){
    this.showSite();
    $("#alert_show_3").css('top',this.config.top+'px');
    $("#alert_show_3").animate({opacity:0.65},'800');
    /*计数器*/
    setTimeout('AlertBox.prototype._sthis.close3s()',this.config.time);
};

	//确认
AlertBox.prototype.showMsg=function() {
    AlertBox.prototype.boundingBox = $('<div id="alert_dialog_show_msg_box" class="show-msg">' + this.config.showMask + '<div id="alert_show_3" class="show-box" style="top:' + this.config.top + 'px;"><div class="ttBox"><a class="clsBtn"></a><span class="tt">' + this.config.title + '</span></div><p>' + this.config.content + '</p>' + '<button type="button" name="button" class="okButton" type="button" value="'+this.config.okVal+'">'+this.config.okVal+'</button></div></div>');
}

AlertBox.prototype.showConfirmMsg=function(){
    AlertBox.prototype.boundingBox=$('<div id="alert_dialog_show_confirm_box" class="show-confirm-msg">'+this.config.showMask+'<div id="alert_show_3" class="show-box" style="top:'+this.config.top+'px;"><div class="ttBox"><a class="clsBtn"></a><span class="tt">'+this.config.title+'</span></div><p>'+this.config.content+'</p><button type="button" class="okButton" type="button" value="'+this.config.okVal+'" name="button">'+this.config.okVal+'</button><button type="button" name="button" class="cancelButton" type="button">'+this.config.cancelVal+'</button></div></div>');
};

AlertBox.prototype.showBox=function () {
    this.showSite();
    $("#alert_show_3").animate({opacity:1,top:this.config.top},'300');
};

	//
AlertBox.prototype.loading=function(){
    AlertBox.prototype.boundingBox=$('<div id="alert_dialog_show_loading_box" class="show-loading-box"><div class="sweet-overlay" tabIndex="-1"></div><div id="alert_show_3" class="show-box" style="top:'+this.config.top+'px;left:'+this.config.left+'px;"><div class="spinner"><div class="spinner-container container1"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container2"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container3"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div></div><div class="loading-text">'+this.config.content+'...<div></div></div>');
    this._loading();
};

AlertBox.prototype._loading=function () {
    this.showSite();
    $("#alert_show_3").css('top',this.config.top+'px');
};

AlertBox.prototype.closedLoading=function(){
    AlertBox.prototype._sthis.remove();
};

AlertBox.prototype.remove=function () {
    AlertBox.prototype.boundingBox && AlertBox.prototype.boundingBox.remove();
    AlertBox.prototype.boundingBox = null;
    if(AlertBox.prototype._sthis.config.dourl) window.location.href=AlertBox.prototype._sthis.config.dourl;
};

AlertBox.prototype.animateDestroy=function(){
    $("#alert_show_3").animate({opacity:0,top:0},'300','swing',function () {
        AlertBox.prototype._sthis.remove();
    });
};

//点击取消
AlertBox.prototype.btnremove=function () {
    $("#alert_show_3").animate({opacity:0,top:0},'300','swing',function () {
        AlertBox.prototype.boundingBox && AlertBox.prototype.boundingBox.remove();
        AlertBox.prototype.boundingBox = null;
    });
};

//关闭
AlertBox.prototype.animateClose = function(){
    AlertBox.prototype._sthis.animateDestroy();
};

//点击空白出关闭弹出框
AlertBox.prototype.close3s=function(){
    if(AlertBox.prototype._sthis.config.type!='loading' && AlertBox.prototype._sthis.config.quickClose===true){
        $("#alert_show_3").animate({opacity:0},'300','swing',function () {
            AlertBox.prototype._sthis.remove();
        });
    }
}


