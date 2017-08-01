function action(callback) {
    var post_data = JSON.stringify({send_data : {channel_id:channel_id}});
     $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/Private_api/getChannel",
        dataType: "json",
        async:true,
        success: function(e){
            load(e,callback);
        },
        error : function(e){
            $(".percent").html(e.statusText);
            throw new Error(e.statusText);
        }
    });
}
function refreshChannel() {
    var post_data = JSON.stringify({send_data : {channel_id:channel_id}});
     $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/Private_api/refreshChannel",
        dataType: "json",
        async:true,
        success: function(e){
            refresh_Channel(e);
        }
    });
}
function getMsg(id) {
    var post_data = JSON.stringify({send_data : {channel_id:channel_id,last_msg_id:id}});
     $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/private_api/getMsg",
        dataType: "json",
        async:true,
        success: function(e){
            addMSG(e);
        }
    });
}
function sendMsg(e) {
    var post_data = JSON.stringify({send_data : {msg:e,channel_id:channel_id}});
     $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/private_api/sendMsg",
        dataType: "json",
        async:true,
        success: function(e){
            if(e.msg != ""){
                alert(e.msg);
            }
        }
    });
}
function sendGift(a,b) {
    var post_data = JSON.stringify({send_data : {channel_id:channel_id,gift_id:a,send_num:b}});
     $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/private_api/sendGift",
        dataType: "json",
        async:true,
        success: function(e){
            if(e.msg != ""){
                alert(e.msg);
            }else{
                //alert(e.web_data.errmsg);
                total_currency = parseInt(e.web_data.left_mibi);
                $(".left_currency").html(total_currency);
            }
        }
    });
}
function setPackage(num,num2,callback){
    var post_data = JSON.stringify({send_data : {channel_id:channel_id,pid : num,inter_id:num2}});
    $.ajax({
        type: "POST",
        data:post_data,
        url: "http://"+host+"/index.php/zb/soma_api/package_detail",
        dataType: "json",
        async:true,
        success: function(e){
            if(e.msg != ""){
                alert(e.msg);
            }else{
                callback(e);
            }
        }
    });
}