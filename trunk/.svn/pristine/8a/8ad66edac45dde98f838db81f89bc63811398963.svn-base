<script type="text/javascript">
var selected= [];
var rand_code = "<?=$rand_code?>";
var setfucid,setfucid2;
$(document).ready(function() {
    //查询优惠券信息
    $(document).on("click",".secarch_btn",function () {
        clean_tip();
        var val = $(".secarch_input").val();
        if(!val || val.length < 6){
            var con_txt=[
                '录入用户手机号、会员号或12位优惠券码！',
                '必须输入超过5个字符的内容！'
            ];
            if(val){
                var j_con=con_txt[1];
            }else{
                var j_con=con_txt[0];
            }
            alertmsg(j_con);
            return false;
        }

        $("#coupons_table_wrapper").remove();
        var j_form_html = $(".j_form-div").html();
        $('#coupons_table').show();
        DataTable_ajax(url_ajax,columnSet,grid_sort,'coupons_table',val,'你录入的信息无法找到对应的优惠券信息');
        $(".j_form-div").show();
        $(".original_con").hide();
        $("#coupons_table_wrapper").after(j_form_html);
    });

    $("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append(buttons);

    //获取列表信息，设置DataTable
    function DataTable_ajax(url,column,sort,rowid,val,srecord_text) {
        $('#'+rowid).DataTable({
            "aLengthMenu": [20,30,50,100,200],
            "iDisplayLength": 20,
            "bProcessing": true,
            "paging": true,
            "lengthChange": true,
            "ordering": true,
            "order": sort,
            "info": true,
            "autoWidth": false,
            "language": {
                "sSearch": "搜索",
                "lengthMenu": "每页显示 _MENU_ 条记录",
                "zeroRecords": "找不到任何记录. ",
                "info": "当前显示第_PAGE_ / _PAGES_页，记录从 _START_ 到 _END_ ，共 _TOTAL_ 条",
                "infoEmpty": "",
                "infoFiltered": "(从 _MAX_ 条记录中过滤)",
                "paginate": {
                    "sNext": "下一页",
                    "sPrevious": "上一页",
                },
                "sSearchPlaceholder":'请输入关键字...',
                "sProcessing":'加载中...',
                "sLoadingRecords":'正在加载...',
                "sZeroRecords" : srecord_text,
            },
            "oClasses":{
                "sFilterInput":'form-control input-sm search-wd'
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": 'POST',
                "url": url,
                "data": {
                    <?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>',
                    searchval:val
                }
            },
            "rowCallback": function(row, data ) {
                if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
                    $(row).addClass('bg-gray');
                }
            },
            "columns": column,
            //"data": dataSet,
            "searching": true,
        });
    }

    $("#coupons_table2_wrapper").remove();
    var j_form_html = $(".j_form-div2").html();
    $('#coupons_table2').show();
    DataTable_ajax(url_ajax2,columnSet2,grid_sort2,'coupons_table2','','暂无核销员');
    $(".j_form-div2").show();
    $("#coupons_table2_wrapper").after(j_form_html);

    var Jobj,Jobj2,myobj,myflag = 1;

    //核销前置准备
    $(document).on("click",".write_off_btn",function (e){
        myobj = this;
        var code = $(this).data("code");
        var url = $(this).data("url");
        var code = $(this).data("code");
        var mid = $(this).data("mid");
        $(".yes_btn").attr("data-url",url);
        $(".yes_btn").attr("data-code",code);
        $(".yes_btn").attr("data-mid",mid);
        Jobj = $(this).parent().parent().find('td').eq(7);
        Jobj2 = $(this).parent().parent().find('td').eq(1);
        $("#write_alert1_code").html('券码：' + code);
        $('.write_alert1').show().siblings().hide();
        $('.p_fixed').fadeIn();
    });

    //设置无效前置准备
    $(document).on("click",".write_no_btn",function (e){
        myobj = this;
        myflag = 2;
        var code = $(this).data("code");
        var url = $(this).data("url");
        var code = $(this).data("code");
        var mid = $(this).data("mid");
        $(".invalid_btn").attr("data-url",url);
        $(".invalid_btn").attr("data-code",code);
        $(".invalid_btn").attr("data-mid",mid);
        Jobj = $(this).parent().parent().find('td').eq(7);
        Jobj2 = $(this).parent().parent().find('td').eq(1);
        $("#write_alert2_code").html('券码：' + code);
        $(".write_alert2_remark").val('');
        $('.write_alert2').show().siblings().hide();
        $('.p_fixed').fadeIn();
    });

    //提交核销和设置无效的请求
    $(document).on("click",".yes_btn,.invalid_btn",function (e) {
        var url = $(this).attr("data-url");
        var code = $(this).attr("data-code");
        var mid = $(this).attr("data-mid");
        var remark = $(".write_alert2_remark").val();
        if(myflag == 2 && !remark){
            alert("请填写核销备注");
            return false;
        }

        $('.write_alert1').hide().siblings().hide();
        $(".load_msg").html("正在请求...");
        $('.j_load_ico').show();
        $('.p_fixed').fadeIn();

        var datas = {code:code,mid:mid,remark:remark};
        $.get(url,datas,function (data){
            if(data.status == 1){
                clean_tip();
                $(myobj).siblings().remove();
                $(myobj).remove();
                if(data.usetime) Jobj2.html(data.usetime);
                Jobj.html(data.text);
            }else{
                $('.j_load_ico').hide();
                alertmsg(data.msg);
            }
            $('.j_load_ico').hide();
        });
    });

    //按下回车键，提交查询信息
    $(".secarch_input").keyup(function (enent) {
        if(event.keyCode == 13){
            clean_tip();
            $(".secarch_btn").trigger("click");
        }
    });

    //按下Esc键，关闭所有信息框
    $(document).keyup(function (event) {
        if(event.keyCode == 27){
            clean_tip();
        }
    });

     //提交授权动作
     $(document).on("click","#auth-content",function (e) {
         $('.alert2').hide();
         var url = "<?=EA_const_url::inst()->get_url('*/membercardevent/applyauth')?>";
         var auth = $(this).data("auth"),datas = {auth:auth,fc:rand_code};
         $.ajax({
             url:url,
             type:'GET',
             data:datas,
             dataType:'json',
             timeout:15000,
             beforeSend:function (XMLHttpRequest) {
                 $(".load_msg").html("授权中...");
                 $('.j_load_ico').show();
                 $('.p_fixed').fadeIn();
             },
             success: function (result) {
                 if(result.status == 1){
                     clean_tip();
                     $("#coupons_table2_wrapper").remove();
                     var j_form_html = $(".j_form-div2").html();
                     $('#coupons_table2').show();
                     DataTable_ajax(url_ajax2,columnSet2,grid_sort2,'coupons_table2','','暂无核销员');
                     $(".j_form-div2").show();
                     $("#coupons_table2_wrapper").after(j_form_html);
                 }else if(result.status != 1){
                     $('.j_load_ico').hide();
                     alertmsg(result.msg);
                 }
             },
             error: function () {
                 clean_tip();
             }
         });
     });

     //获取二维码的随机数code
     $(document).on("click",".add_writeoff",function (e) {
         var url = "<?=EA_const_url::inst()->get_url('*/membercardevent/getqrcode')?>";
         $.ajax({
             url:url,
             type:'GET',
             data:{},
             dataType:'json',
             timeout:15000,
             beforeSend:function (XMLHttpRequest) {
                 $(".load_msg").html("加载中...");
                 $('.j_load_ico').show();
                 $('.p_fixed').fadeIn();
                 $(".scanauth-ok").html('请需要添加核销权限的员工用微信扫码上方二维码');
             },
             success: function (result) {
                 if(result.status == 1){
                     var qrurl = "<?=EA_const_url::inst()->get_url('*/tool/scanqr',array('str'=>"http://{$public['domain']}/membervip/auth/scan?id={$public['inter_id']}"));?>&fc=" + result.key;
                     $(".qr_er_ma_img").attr("src",qrurl);
                     rand_code = result.code;
                     //设置一个定时器，获得定时器的ID
                     sp_clearInterval(); //设置前先清除事务
                     setfucid = setInterval(check_scanqr,3500);
                     setfucid2 = setInterval(check_applyauth,3500);
                 }
             },
             error: function () {},
             complete:function (XMLHttpRequest, textStatus) {
                 setTimeout(function () {
                     $('.alert1').show().siblings().hide();
                     $('.p_fixed').fadeIn();
                 },500);
             }
         });
     });

    $(document).on("click",".invalidauth",function (e) {
        var aid = $(this).data("aid"),
            parentobj = $(this).parent().parent().find('td').eq(1),
            name = parentobj.html();
        $(".cancel_auth_btn").attr("aid",aid);
        $("#write_alert3_name").html('核销员：' + name);
        $('.write_alert3').show().siblings().hide();
        $('.p_fixed').fadeIn();
    });

     //取消授权
    $(document).on("click",".cancel_auth_btn",function (e) {
        var url = "<?=EA_const_url::inst()->get_url('*/membercardevent/invalidauth')?>";
        var aid = $(this).attr("aid"),datas = {id:aid};
        $.ajax({
            url:url,
            type:'GET',
            data:datas,
            dataType:'json',
            timeout:15000,
            beforeSend:function (XMLHttpRequest) {
                $('.write_alert3').hide().siblings().hide();
                $(".load_msg").html("授权中...");
                $('.j_load_ico').show();
                $('.p_fixed').fadeIn();
            },
            success: function (result) {
                if(result.status == 1){
                    clean_tip();
                    $("#coupons_table2_wrapper").remove();
                    var j_form_html = $(".j_form-div2").html();
                    $('#coupons_table2').show();
                    DataTable_ajax(url_ajax2,columnSet2,grid_sort2,'coupons_table2','','暂无核销员');
                    $(".j_form-div2").show();
                    $("#coupons_table2_wrapper").after(j_form_html);
                }else if(result.status != 1){
                    $('.j_load_ico').hide();
                    alertmsg(result.msg);
                }
            },
            error: function () {
                clean_tip();
            }
        });
    });

    $('.p_fixed').on('click','.close_btn,.cancel_btn',function(){
        clean_tip();
    });

    $('.next_step_btn').click(function(){
        $('.alert1').hide();
        $('.alert2').show();
    });

    //关闭所有信息框
    function clean_tip() {
        $('.alert1').hide().siblings().hide();
        $('.alert2').hide().siblings().hide();
        $('.write_alert1').hide().siblings().hide();
        $('.write_alert2').hide().siblings().hide();
        $('.search_alert1').remove();
        $('.j_load_ico').hide();
        $('.p_fixed').fadeOut();
        sp_clearInterval();
    }

});

//监测用户扫码动作
function check_scanqr() {
    var url = "<?=EA_const_url::inst()->get_url('*/membercardevent/check_scanqr')?>";
    var datas = {fc:rand_code};
    $.get(url,datas,function (data){
        if(data.status == 1){
            $(".scanauth-ok").html('<strong>'+data.msg+'</strong>');
            if(setfucid){
                clearInterval(setfucid); //取消 setInterval() 函数设定的定时执行操作
                setfucid = null;
            }
        }
    });
}

//监测用户是否已经提交授权申请
function check_applyauth() {
    var url = "<?=EA_const_url::inst()->get_url('*/membercardevent/check_applyauth')?>";
    var datas = {fc:rand_code};
    $.get(url,datas,function (results){
        if(results.status == 1){
            $('.alert1').hide();
            $('.alert2').show();
            $(".er_ma_fans").find('img').attr("src",results.headimgurl);
            $("#fans-name").html(results.name);
            $("#auth-content").attr("data-auth",results.auth)
            if(setfucid2){
                clearInterval(setfucid2); //取消 setInterval() 函数设定的定时执行操作
                setfucid2 = null;
            }
        }else if(results.err == 4008){
            clean_tip();
            alertmsg(results.msg);
            return false;
        }
    });
}

//清除定时事务
function sp_clearInterval() {
    if(setfucid){
        clearInterval(setfucid);
        setfucid = null;
    }
    if(setfucid2){
        clearInterval(setfucid2);
        setfucid2 = null;
    }
}

//提示框
function alertmsg(msg) {
    var str='<div class="search_alert1 center bg_fff radius_3 relative padding_t_20 width_380 margin_auto"><div class="absolute close_btn"><i class="iconfonts font_16">&#xe60a;</i></div><div class="font_14 padding_t_30 margin_bottom_10 width_260 margin_auto">'+msg+'</div><div class="margin_40_0 flex centers"><div class="batch_setting_btn pointer padding_10_0 radius_3 font_weight font_16 color_fff bg_b69b69 width_150 center margin_right_15 cancel_btn">好 的</div></div></div>';
    $('.p_fixed >div').append(str);
    $('.p_fixed').fadeIn();
}
</script>