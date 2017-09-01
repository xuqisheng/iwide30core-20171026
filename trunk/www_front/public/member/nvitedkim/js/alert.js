(function () {
    $.MsgBox = {
        Alert: function (title, msg, callback) {
            GenerateHtml("alert", title, msg);
            btnOk(callback);
            btnNo();
        },
        Confirm: function (title, msg, callback) {
            GenerateHtml("confirm", title, msg); 
            btnOk(callback);
            btnNo();
        }
    }
    var GenerateHtml = function (type, title, msg) {
		$("#mb_box,#mb_con").remove();
        var _html = "";
		if(msg==undefined){
			msg=title;
			title='';
		}
        _html += '<div id="mb_box"></div><div id="mb_con">';
		_html += '<div id="mb_title">' + title + '</div>';
        _html += '<div id="mb_msg" class="h12">' + msg + '</div><div id="mb_btnbox">';
        if (type == "alert") {
            _html += '<span id="mb_btn_ok" class="color_fff btn bg_main"><p>确定</p><span>';
        }
        if (type == "confirm") {
            _html += '<span id="mb_btn_no" class="btn"><p>取消</p></span>';
            _html += '<span id="mb_btn_ok" class="color_fff btn bg_main"><p>确认</p></span>';
        }
        _html += '</div></div>';
        $("body").append(_html);
        GenerateCss();
    }
    var GenerateCss = function () {
		var tmp='<style>';
        tmp+="#mb_box{width:100%;height:100%;z-index:99999;position:fixed;background:#000;top:0;left:0;opacity:0.6}";
        tmp+="#mb_con{z-index:999999;width:80%;position:fixed;background:#fff;text-align:center;padding-top:5%;border-radius:0.5em}";
        tmp+="#mb_title{ padding-top: 7%;text-align:center;}";
        tmp+="#mb_msg{ padding:0 5% 5% 5%; display:inline-block;text-align:center;line-height: 1.5; border-bottom:1px solid #e4e4e4;}";
        tmp+="#mb_btnbox{ margin: 5% 0; text-align: center;}";
        tmp+="#mb_btnbox>*{ margin:2%; width:30%;}";
        tmp+="#mb_btn_no{border:1px solid #e4e4e4;}";
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
    var btnNo = function () {
        $("#mb_btn_no,#mb_ico").click(function () {
            $("#mb_box,#mb_con").remove();
        });
    }
})();