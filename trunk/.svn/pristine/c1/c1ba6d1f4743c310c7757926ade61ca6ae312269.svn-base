<script type="text/javascript">
var selected= [];
var datas = {
    <?php echo config_item('csrf_token_name') ?>: '<?php echo $this->security->get_csrf_hash() ?>',
};
$(document).ready(function() {
    $(".formdate").jeDate({
        skinCell:"jedateblue",                      //日期风格样式，默认蓝色
        format:"YYYY-MM-DD",               //日期格式
        minDate:"1900-01-01 00:00:00",              //最小日期
        maxDate:"2099-12-31 23:59:59",              //最大日期
        language:{                                  //多语言设置
            name  : "cn",
            month : ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
            weeks : [ "日", "一", "二", "三", "四", "五", "六" ],
            times : ["小时","分钟","秒数"],
            clear : "清空",
            today : "今天",
            yes   : "确定",
            close : "关闭"
        },
        choosefun: function(){
            return;
        }
    });


    //查询优惠券信息
    $(document).on("click", ".secarch_btn", function () {
        var keywords = $("input[name=keywords]").val(),
            useoff_sttime = $("input[name=useoff_sttime]").val(),
            useoff_edtime = $("input[name=useoff_edtime]").val(),
            coupon_code = $("input[name=coupon_code]").val(),
            status = $("select[name=status]").val();

        if(useoff_sttime && useoff_edtime){
            var sd = new Date(useoff_sttime+' 00:00:00'),st=sd.getTime()/1000; //开始时间的时间戳
            var ed = new Date(useoff_edtime+' 24:00:00'),et=ed.getTime()/1000; //结束时间的时间戳
            var cd = et-st
            if(cd<=0){
                alert('结束时间不能小于开始时间');
                return false;
            }
        }

        var postdatas = datas;
        postdatas.keywords = keywords;
        postdatas.useoff_sttime = useoff_sttime;
        postdatas.useoff_edtime = useoff_edtime;
        postdatas.coupon_code = coupon_code;
        postdatas.status = status;

        $("#coupons_table_wrapper").remove();
        var j_form_html = $(".j_form-div").html();
        $('#coupons_table').show();
        DataTable_ajax(url_ajax, columnSet, grid_sort, 'coupons_table', postdatas, '你录入的信息无法找到对应的优惠券信息');
        $(".j_form-div").show();
        $("#coupons_table_wrapper").after(j_form_html);
    });

    Wind.use("artDialog",function (){
        $(document).on("click",".explore_btn",function (e) {
            var onthis = this,url_action=$(this).data('action');
            var keywords = $("input[name=keywords]").val(),
                useoff_sttime = $("input[name=useoff_sttime]").val(),
                useoff_edtime = $("input[name=useoff_edtime]").val(),
                coupon_code = $("input[name=coupon_code]").val(),
                status = $("select[name=status]").val();

            if(useoff_sttime && useoff_edtime){
                var sd = new Date(useoff_sttime+' 00:00:00'),st=sd.getTime()/1000; //开始时间的时间戳
                var ed = new Date(useoff_edtime+' 24:00:00'),et=ed.getTime()/1000; //结束时间的时间戳
                var cd = et-st
                if(cd<=0){
                    alert('结束时间不能小于开始时间');
                    return false;
                }
            }

            var postdatas = datas;
            postdatas.keywords = keywords;
            postdatas.useoff_sttime = useoff_sttime;
            postdatas.useoff_edtime = useoff_edtime;
            postdatas.coupon_code = coupon_code;
            postdatas.status = status;

            art.dialog({
                title:'信息提示',
                width:'27%',
                left:'30%',
                content:'确定要导出报表？',
                ok:function (){

                    art.dialog({content:'正在导出...',lock:true,opacity:0.1,background:'#FFF',ok:false,cancel:true,cancelVal:'关闭',time:6});

                    window.location= url_action+'?keywords='+keywords+'&useoff_sttime='+useoff_sttime+'&useoff_edtime='+useoff_edtime+'&coupon_code='+coupon_code+'&status='+status+'&tp=8';
                },
                close:function () {
                },
                cancel:true,
                follow:onthis,
                okVal:'导出',
                cancelVal:'取消'
            });
        });
    });


    $("#data-grid_length").children().append('&nbsp;&nbsp;&nbsp;').append(buttons);

    //获取列表信息，设置DataTable
    function DataTable_ajax(url, column, sort, rowid, postdatas, srecord_text) {
        $('#' + rowid).DataTable({
            "aLengthMenu": [20, 30, 50, 100, 200],
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
                "sSearchPlaceholder": '请输入关键字...',
                "sProcessing": '加载中...',
                "sLoadingRecords": '正在加载...',
                "sZeroRecords": srecord_text,
            },
            "oClasses": {
                "sFilterInput": 'form-control input-sm search-wd'
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": 'POST',
                "url": url,
                "data": postdatas
            },
            "rowCallback":function (row, data) {
                if ($.inArray(data.DT_RowId, selected) !== -1) {
                    $(row).addClass('bg-gray');
                }
            },
            "columns":column,
            "searching":true,
        });
    }

    $("#coupons_table_wrapper").remove();
    var j_form_html = $(".j_form-div").html();
    $('#coupons_table').show();
    DataTable_ajax(url_ajax, columnSet, grid_sort, 'coupons_table',datas, '你录入的信息无法找到对应的优惠券信息');
    $(".j_form-div").show();
    $("#coupons_table_wrapper").after(j_form_html);

    //按下回车键，提交查询信息
    $(".secarch_input input,.secarch_input select").keyup(function (enent) {
        if(event.keyCode == 13){
            $(".secarch_btn").trigger("click");
        }
    });
});
</script>