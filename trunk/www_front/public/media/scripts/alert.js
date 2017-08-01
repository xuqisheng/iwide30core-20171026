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
        var _html = "";
		if(msg==undefined){
			msg=title;
			title='';
		}
        _html += '<div id="mb_box"></div><div id="mb_con">';
		_html += '<div id="mb_title" class="ui_color">' + title + '</div>';
        _html += '<div id="mb_msg">' + msg + '</div><div id="mb_btnbox">';
        if (type == "alert") {
            _html += '<span id="mb_btn_ok" class="ui_color"><em class="iconfont">&#x26;</em><p>确定</p><span>';
        }
        if (type == "confirm") {
            _html += '<span id="mb_btn_no"><em class="iconfont">&#x27;</em><p>取消</p></span>';
            _html += '<span id="mb_btn_ok" class="ui_color"><em class="iconfont">&#x26;</em><p>确认</p></span>';
        }
        _html += '</div></div>';
		if ( $('#mb_box').get(0) != undefined )
            $("#mb_box,#mb_con").remove();
        $("body").append(_html);
        GenerateCss();
    }
    var GenerateCss = function () {
		var tmp='<style>';
        tmp+="#mb_box{ width: 100%; height: 100%; z-index: 99999; position: fixed; background:#000; top: 0; left: 0; opacity: 0.6}";
        tmp+="#mb_con{ z-index: 999999; width: 85%; position: fixed;background: #fff;font-size: 2.8em; border-radius:0.5em}";
        tmp+="#mb_title{ padding-top: 7%;text-align:center; font-size:1.1em;}";
        tmp+="#mb_msg{ padding: 5%;text-align:center;line-height: 1.5;}";
        tmp+="#mb_btnbox{ margin-bottom: 5%; text-align: center }";
        tmp+="#mb_btnbox .iconfont{font-size:2em}";
        tmp+="#mb_btn_ok,#mb_btn_no{display:inline-block; width:45%;}";
		$('#mb_box').append(tmp);
        var _widht = document.documentElement.clientWidth;  //屏幕宽
        var _height = document.documentElement.clientHeight; //屏幕高
        var boxWidth = $("#mb_con").width();
        var boxHeight = $("#mb_con").height();
        //让提示框居中
        $("#mb_con").css({ top: (_height - boxHeight) / 2 + "px", left: (_widht - boxWidth) / 2 + "px" });
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