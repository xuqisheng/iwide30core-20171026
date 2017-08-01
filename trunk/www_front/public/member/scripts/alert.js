(function () {
    $.MsgBox = {
        Alert: function ( msg,callback,cancel) {
            GenerateHtml("alert", msg);
            btnOk(callback);
            btnNo(cancel);
        },
        Confirm: function (msg, callback,cancel,str1,str2) {
			if(str1==undefined)str1='好的';
			if(str2==undefined)str2='取消';
            GenerateHtml("confirm",msg,str1,str2); 
            btnOk(callback);
            btnNo(cancel);
        }
    }
    var GenerateHtml = function (type, msg,str1,str2) {
		$("#mb_box,#mb_con").remove();
        var _html = "";
//		if(msg==undefined){
//			msg=title;
//			title='提示';
//		}
        _html += '<div id="mb_box" class="h30"><div id="mb_con">';
		_html += '<!--div id="mb_title" class="color_main h36"> + title + </div-->';
        _html += '<div id="mb_msg" class="bd_bottom">' + msg + '</div><div class="webkitbox" id="mb_btnbox">';
        if (type == "alert") {
            _html += '<div id="mb_btn_no" class="color_888 bd_right">取消</div>';
            _html += '<div id="mb_btn_ok" class="color_main">好的</div>';
        }
        if (type == "confirm") {
            _html += '<div id="mb_btn_no" class="color_888 bd_right">'+str2+'</div>';
            _html += '<div id="mb_btn_ok" class="color_main">'+str1+'</div>';
        }
        _html += '</div></div></div>';
        $("body").append(_html);
        GenerateCss();
    }
    var GenerateCss = function () {
		var tmp='<style>';
        tmp+="#mb_box{width:100%;height:100%;z-index:99999;position:fixed;background:rgba(0,0,0,0.4);top:0;left:0;}";
        tmp+="#mb_con{z-index:999999;width:70%;position:fixed;background:rgba(255,255,255,0.9);text-align:center;border-radius:10px;}";
        tmp+="#mb_title{ padding-top:15px;text-align:center;}";
        tmp+="#mb_msg{ padding:35px;text-align:center;line-height: 1.2;}";
        tmp+="#mb_btnbox{text-align: center;}";
        tmp+="#mb_btnbox>*{ padding:10px;}";
		$('body').before(tmp);
        var _widht = document.documentElement.clientWidth;  //屏幕宽
        var _height = document.documentElement.clientHeight; //屏幕高
        var boxWidth = $("#mb_con").width();
        var boxHeight = $("#mb_con").height();
        //让提示框居中
        $("#mb_con").css({ top: (_height - boxHeight) / 2.5 + "px", left: (_widht - boxWidth) / 2 + "px" });
    }
    //确定按钮事件
    var btnOk = function (callback) {
        $("#mb_btn_ok").click(function () {
            $("#mb_box,#mb_con").remove();
            if (typeof (callback) == 'function') {
                callback();
            }
        });
    }
    //取消按钮事件
    var btnNo = function (callback) {
        $("#mb_btn_no").click(function () {
            $("#mb_box,#mb_con").remove();
            if (typeof (callback) == 'function') {
                callback();
            }
        });
    }
})();