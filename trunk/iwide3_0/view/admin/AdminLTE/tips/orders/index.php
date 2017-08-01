<!-- DataTables -->
<link rel="stylesheet"
      href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/pagination.css">
</head>
<style>
    

</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">显示设置</h4>
            </div>
            <div class="modal-body">
                <div id='cfg_items'>
                    <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>

                    </form></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="wrapper">

    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>
    <style>
        .l_content{background-color: #ffffff;margin-top: 30px;padding: 30px 20px 0 20px;}
        .l_button_yellow{background-color: #ff9900;display: inline-block;color: white;padding: 7px 20px;border-radius: 5px;cursor: pointer;border:1px solid #ff9900;}
        .l_select{width: auto; padding-left: 5px;display: inline-block; text-indent: 0px; border: 1px solid #d9dfe9; color: #7e8e9f; outline: none;}
        .l_content_rows{display: inline-block; margin-left: 20px;position: relative;}
        .l_screen_button{color: #2d87e2;cursor: pointer;margin-right: 10px;}
        .search_ico{color: #d9dfe9; position: absolute; right: 7px; top: 10px; font-size: 16px; cursor: pointer;}
        input::-webkit-input-placeholder, textarea::-webkit-input-placeholder {color: #d9dfe9; } input:-moz-placeholder, textarea:-moz-placeholder {color: #d9dfe9; } input::-moz-placeholder, textarea::-moz-placeholder {color: #d9dfe9; } input:-ms-input-placeholder, textarea:-ms-input-placeholder {color: #d9dfe9; }
        .l_content_list{width: 100%;text-align: center;border:1px solid #d7e0f1;margin-top: 10px;margin-bottom: 20px;}
        .l_content_list th{text-align: center;background-color: #f8f9fb;height: 50px;}
        .l_content_list tr{border-top: 1px solid #e7ecf7;height: 60px;}
        .l_content_list tr:nth-child(2n){background-color: #fafcfb;}
        .l_content_record{padding-bottom: 40px;}
        .l_record_word{float: left;line-height: 33px;}
        .floatr{float: right;}
        .l_content_rows input{height: 36px;}
        .t_time input{width: 100px;}
        #datepicker{margin-left: 5px;}
        #datepicker2{margin-right: 10px;}
        .serch_v{width: 190px;}
    </style>

    <div style="color:#92a0ae;">
        <div class="over_x">
            <div class="content-wrapper" style="min-width:1130px; ">

                <div class="banner bg_fff p_0_20">
                    <?php echo '打赏记录'; ?>
                </div>
                <div  class="l_content clearfix">
                    <div class="l_content_topper">
                        <div class="l_content_rows" style="margin:0px;"><span class="l_button_yellow" id="Exportreport">导出报表</span></div>
                        <div class="floatr">
                            <div class="l_content_rows">
                               <select class="form-control l_select" id="j_state">
                                    <option value="">发送状态</option>
                                    <option value="1">未发送</option>
                                    <option value="2">已发送</option>
                                </select> 
                            </div>
                            <div class="l_content_rows">
                                <select class="form-control l_select" id="j_hotel">
                                    <option value="0">所有门店</option>
                                    <?php if(!empty($hotels)){
                                    foreach($hotels as $k=>$v){
                                        ?>
                                        <option value="<?php echo $k?>"><?php echo $v?></option>
                                    <?php
                                    }}
                                    ?>
                                </select>
                            </div>                        
                            <div class="l_content_rows">
                                <span>打赏时间</span>
                                <span class="t_time"><input name="begin_time" type="text" id="datepicker" class="moba" value=""></span>
                                <span>至</span>
                                <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="moba" value=""></span>
                                <span class="l_screen_button" id="j_search">筛选</span>
                            </div>
                            <div class="l_content_rows">
                                <input name="begin_time" id="j_wd" type="text" placeholder="输入关键字搜索" class="moba l_select serch_v" value="">
                                <span class="fa fa-search search_ico" id="j_search_ico"></span>
                            </div>
                        </div>
                    </div>
                    <table class="l_content_list">
                        <thead>
                            <th width="12%">打赏用户</th>
                            <th width="12%">打赏时间</th>
                            <th width="9%">员工姓名</th>
                            <th width="9%">分销号</th>
                            <th width="12%">所属门店</th>
                            <th width="8%">打赏金额</th>
                            <th width="8%">用户评分</th>
                            <th width="10%">发放状态</th>
                        </thead>
                        <tbody id="list_content">
                        </tbody>
                    </table>
                    <div class="l_content_record clearfix">
                        <p class="l_record_word">当前共筛选到<span id="total"></span>条 / 共<span id="page"></span>页数据</p>
                        <div id="page_choose">
                            <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                <div class="pagination">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    /* Footer Block @see footer.php */
    require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
    ?>

    <?php
    /* Right Block @see right.php */
    require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
    ?>



    <?php
    /* Right Block @see right.php */
    require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
    ?>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/pagination.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<script>
;!function(){
  laydate({
     elem: '#datepicker'
  })
  laydate({
     elem: '#datepicker2'
  })
}();
function getData(number,wd,pagebol){
    /**
     * 获取数据函数
     *
     * @param    {string}    send_status 发送状态
     * @param    {string}    hotel_id    酒店ID
     * @param    {datatime}  start_time  打赏时间开始
     * @param    {datatime}  end_time    打赏时间结束
     * @param    {string}    wd  关键字
     * @param    {number}    cur_page    页数
     * @param    {booleans}  pagebol     重设页数
     * @returns  void
     *
     * @date     2017-04-24
     * @author   Chung
     */

    var _data = 
    {
        send_status : $("#j_state").val(),
        hotel_id    : $("#j_hotel").val(),
        start_time  : $("#datepicker").val(),
        end_time    : $("#datepicker2").val(),
        cur_page    : number,
        wd  : wd
    };


    $.ajax({
        type:"GET",
        url:"get_order_index_data",
        data:_data,
        dataType:"json",
        success:function(data){
            var _json = data['data']['result_data'];

            var _html = "";
            for(var item in _json){

                _html += "<tr>";
                //***内容***
                _html += "<td>" + _json[item]["pay_name"] + "</td>"//打赏用户
                _html += "<td>" + _json[item]["pay_time"] + "</td>"//打赏时间
                _html += "<td>" + _json[item]["saler_name"] + "</td>"//员工姓名
                _html += "<td>" + _json[item]["saler"] + "</td>"//分销号
                _html += "<td>" + _json[item]["hotel_name"] + "</td>"//所属门店
                _html += "<td>" + _json[item]["pay_money"] + "</td>"//打赏金额
                _html += "<td>" + _json[item]["score"] + "</td>"//用户评分
                _html += "<td>" + _json[item]["send_status"] + "</td>"//发放状态
                //***内容end***
                _html += "</tr>"
            }
            var _total= data['data']['total_count'];//总数据数
                _page = data['data']['total_page'];//总页数

            $("#page").html(_page)
            $("#total").html(_total);
            if (pagebol){
                $('.pagination').pagination({
                    pageCount:_page,
                    coping:true,
                    endPage:_page,
                    prevContent:'上一页',
                    nextContent:'下一页'
                });
            }
            
            $("#list_content").html(_html);
        },
        error:function(data){

        }
    }) 
}
getData(1,"",true);
$("#j_search").on("click",function(){
    getData(1,$("#j_wd").val(),true);
});
$("#j_wd").on("keypress",function(){
    if(event.keyCode == "13")      
    {  
        getData(1,$("#j_wd").val(),true);
    }  
});
$("#j_search_ico").on("click",function(){
    getData(1,$("#j_wd").val(),true);
});

$(document).on("click",".page_click",function(){
    getData($(this).attr("data-page"),$("#j_wd").val());

});
$(document).on("click",".prev",function(){
    getData($("#page_choose .active").attr("data-page")); 
});
$(document).on("click",".next",function(){
    getData($("#page_choose .active").attr("data-page")); 
});

$("#Exportreport").on("click",function(){
    //导出报表
     window.location.href="<?php echo site_url('tips/orders/extdata')?>?send_status="+$("#j_state").val()+"&hotel_id="+$("#j_hotel").val()+'&start_time='+$("#datepicker").val()+'&end_time='+ $("#datepicker2").val()+'&wd='+$("#j_wd").val();
})
</script>
</body>
</html>
