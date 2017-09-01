<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
</head>
    <style>
        @font-face {
            font-family: 'iconfont';
            src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot');
            src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.woff') format('woff'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.ttf') format('truetype'),
            url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.svg#iconfont') format('svg');
        }
        .iconfont{
            font-family:"iconfont" !important;
            font-size:16px;font-style:normal;
            -webkit-font-smoothing: antialiased;
            -webkit-text-stroke-width: 0.2px;
            -moz-osx-font-smoothing: grayscale;
        }
        .over_x{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;-webkit-overflow-scrolling:touch;overflow-scrolling:touch;}
        .clearfix:after{content:"" ;display:block;height:0;clear:both;visibility:hidden;}
        .bg_fff{background:#fff;}
        .color_fff{color:#fff;}
        .bg_ff0000{background:#ff0000;}
        .bg_f8f9fb{background:#f8f9fb;}
        .bg_ff9900{background:#ff9900;}
        .bg_f8f9fb{background:#f8f9fb;}
        .bg_fe6464{background:#fe6464;}
        .bg_eee{background:#EEEEEE}
        .color_72afd2{color:#72afd2;}
        .color_ff9900{color:#ff9900;}
        .color_F99E12{color:#F99E12;}
        a{color:#92a0ae;}

        .border_1{border:1px solid #d7e0f1;}
        .f_r{float:right;}
        .p_0_20{padding:0 20px;}
        .w_90{width:90px;}
        .w_200{width:200px;}
        .p_r_30{padding-right:30px;}
        .m_t_10{margin-top:10px;}
        .p_0_30_0_10{padding:0 30px 0 10px;}
        .b_b_1{border-bottom:1px solid #d7e0f1;}
        .b_t_1{border-top:1px solid #d7e0f1;}
        .p_t_10{padding-top:10px;}
        .p_b_10{padding-bottom:10px;}

        .contents{padding:10px 0px 20px 20px;}
        .contents_list{display:table;width:100%;border-bottom:1px solid #d7e0f1;margin-bottom:10px;}
        .head_cont{padding:20px 0 20px 10px;}
        .head_cont >div{margin-bottom:10px;cursor:pointer;}
        .head_cont >div:last-child{margin-bottom:0px;}
        .j_head >div{display:inline-block;}
        .j_head >div:nth-of-type(1){width:307px;}
        .j_head >div:nth-of-type(2){width:526px;}
        .j_head >div:nth-of-type(3){width:255px;}
        .j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
        .head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
        .h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
        .h_btn_list> div:last-child{margin-right:0px;}
        .classification{height:30px;line-height:30px;border-top:1px solid #d7e0f1;border-right:1px solid #d7e0f1;border-left:1px solid #d7e0f1;width:300px;}
        .classification >div{text-align:center;height:30px;border-right:1px solid #d7e0f1;}
        .classification >div:last-child{border-right:none;}
        .classification .add_active{background:#ecf0f5;border-bottom:1px solid #ecf0f5;}
        .fomr_term{height:30px;line-height:30px;}
        .classification >div,.all_open_order{cursor:pointer;}
        .all_open_order{margin-right:10px;margin-top:5px;}
        .template >div{text-align:center;}
        .template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
        .template_span{display:inline-block;margin-top:2px;}
        .template_btn{padding:1px 8px;border-radius:3px;}
        .form_con,.form_title{height:30px;line-height:30px;}
        .form_con >td,.form_title >th{text-align:center;font-weight:normal;}
        .form_thead >tr,.containers >tr{padding:8px 0;}
        .form_title >th:nth-of-type(2),.form_con >td:nth-of-type(2){flex:1.5;}
        .form_title >th:nth-of-type(7),.form_con >td:nth-of-type(7){flex:2.9;}
        .form_title >th:nth-of-type(6),.form_con >td:nth-of-type(6){flex:2.9;}
        .containers >tr:nth-child(even){background:#F8F8F8 !important;}
        .containers >tr:nth-child(odd){background:#fff !important;}
        .form_con >td{padding-right:20px !important;}
        .box-body{padding:0px;overflow:hidden;}
        #coupons_table_length{display:none;}


        .drow_list{display:none;position:absolute;width:100%;top:100%;left:0;background:#fff;border:1px solid #e4e4e4;padding:0;overflow:auto;z-index:999}
        .drow_list li{height:35px;padding-left:15px;line-height:35px;list-style:none; cursor:pointer}
        .drow_list li:hover{background:#f1f1f1}
        .drow_list li.cur{background:#ff9900;color:#fff}
        #drowdown:hover .drow_list{display:block}
		.blue{color:#2d87e2}
		.red{color:#e22e3b}
		.pointer{cursor:pointer}
		.pointer:hover{text-decoration:underline}
		
		.dialog{display:flex; display:-webkit-flex; display:-moz-flex; display:box; align-items:center; text-align:center}
		.dialog{justify-content:center;position:fixed; width:100%;height:100%; top:0;left:0; z-index:1111; background:rgba(0,0,0,0.5);}
		.dialog .box{background:#fff; border-radius:5px; width:300px;min-height:300px; max-height:80%; padding:10px 0 0 10px;flex-flow:column;}
		.dialog .box>*{margin-bottom:10px}
		.dialog .btn{width:45%}
		.dialog textarea,.dialog input,.dialog select{width:200px; text-indent:0; outline:none; padding:0 5px}
		.dialog textarea{vertical-align:text-top; resize:none; height:auto;line-height:1.5;}

        #pages
        {
            height: 40px;
            text-align: right;
            font-family: \u5b8b\u4f53,Arial;
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }

        #pages span {
            float: left;
            display: inline;
            margin: 1px 4px;
            display: block;
            border-radius: 3px;

        }

        #pages span.span {
            color: #666;
            padding: 6px;
            background-color: #FFFFFF;
        }
        #pages span.nolink {
            color: #666;
            border: 1px solid #e3e3e3;
            padding: 6px;
            background-color: #FFFFFF;
        }
        #pages span.current {
            color: #fff;
            background: #ffac59;
            border: 1px solid #e6e6e6;
            padding: 11px 13px;
            font-size: 14px;
            display: inline;
        }

        #pages a {
            float: left;
            display: inline;
            padding: 11px 13px;
            border: 1px solid #e6e6e6;
            border-right: none;
            background: #f6f6f6;
            color: #666666;
            font-family: \u5b8b\u4f53,Arial;
            font-size: 14px;
            cursor: pointer;

        }
		.display_flex{display:flex;display:-webkit-flex;display:box;display:-webkit-box;justify-content:top;align-items:center;-webkit-align-items:center;}
        .display_flex >th,.display_flex >td,.display_flex >div{-webkit-flex:1;flex:1;-webkit-box-flex:1;box-flex:1;cursor:pointer;}
    </style>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

    <?php
    /* 顶部导航 */
    echo $block_top;
    ?>

    <?php
    /* 左栏菜单 */
    echo $block_left;
    ?>

<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">预约列表</div>
            <div class="contents_list" style="font-size:13px;margin-top:20px">
                <div class="classification display_flex bg_fff">
                    <div class="<?php echo $param['type']=='0' ? 'add_active':'';?>"><a href="<?php echo site_url('appointment/book_orders/index?type=0');?>">预约中</a></div>
                    <div class="<?php echo $param['type']=='1' ? 'add_active':'';?>"><a href="<?php echo site_url('appointment/book_orders/index?type=1');?>">已用餐</a></div>
                    <div class="<?php echo $param['type']=='2' ? 'add_active':'';?>"><a href="<?php echo site_url('appointment/book_orders/index?type=2');?>">已取消</a></div>
                    <div class="<?php echo $param['type']=='99' ? 'add_active':'';?>"><a href="<?php echo site_url('appointment/book_orders/index?type=99');?>">所有预约</a></div>
                </div>
            </div>
            <div class="contents">
                <form class="form" method='get' id="" action='<?php echo site_url('appointment/book_orders/index')?>'>
                    <div style="text-align:right;">
                        <span>店铺</span>
                        <span>
                            <select  class="w_90" name="dining_room_id">
                                <option value="0">全部</option>
                                <?php
                                if (!empty($shop))
                                {
                                foreach ($shop as $value) {
                                    ?>
                                    <option <?php echo $param['dining_room_id'] ==$value['dining_room_id']?'selected':''; ?> value="<?php echo $value['dining_room_id']?>"><?php echo $value['shop_name']?></option>
                                    <?php
                                }}
                                ?>
                            </select>
                        </span>
                        <span>用餐时间</span>
    
                        <span class="t_time"><input name="start_time"  data-date-format="yyyy-mm-dd" class="datepicker moba" value="<?php echo $param['start_time'] ? addslashes($param['start_time']) : date('Y-m-d');?>"></span>
                        <font>至</font>
                        <span class="t_time"><input name="end_time"  data-date-format="yyyy-mm-dd" class="datepicker moba" value="<?php echo addslashes($param['end_time']);?>"></span>
                        <span><input style="width: 170px;" type="text" name="wd" value="<?php echo addslashes($param['wd']);?>" placeholder="关键字"/></span>
                        <input type="hidden" name="type" value="<?php echo $param['type']?>"/>
                        <input type="submit" class="btn btn-sm bg-green" value="搜索"/>
                    	<div class="btn btn-sm bg-yellow" style="float:left" id="Add">新增预约</div>
                    </div>
                </form>
                <div class="dialog" id="Add_layer" style="display:none">
                    <div class="box">
                        <div>新增预订</div>
                        <div>预订时间 <input name="book_datetime" placeholder="预订时间" type="text" class="timepicker moba" value=""></div>
                        <div>顾客姓名 <input name="book_name" placeholder="顾客姓名" type="text" class="moba" value=""></div>
                        <div>顾客电话 <input name="book_phone" placeholder="顾客电话" type="text" class="moba" value=""></div>
                        <div>预订人数 <input name="book_number" placeholder="预订人数" type="text" class="moba" value=""></div>
                        <div>预订店铺 <select class="moba" id= "dining_room_id" name="dining_room_id">
                                <?php
                                if (!empty($shop)) {
                                    foreach ($shop as $value) {
                                        ?>
                                        <option value="<?php echo $value['dining_room_id'];?>"><?php echo $value['shop_name'];?></option>
                                        <?php
                                    }
                                }
                                ?>

                        </select></div>
                        <div>备注事项 <textarea name="book_info"  class="moba" rows="4"></textarea></div>
                        <div>
                            <div class="btn btn-sm bg_eee" onClick="$('#Add_layer').hide();">取消</div>
                            <div class="btn btn-sm bg-yellow" id="subNew">提交</div>
                        </div>
                    </div>  
                </div>
                
                <div class="box-body">
                    <table id="coupons_table" class="table-striped table-condensed dataTable no-footer" style="width:100%;">
                        <thead class="bg_f8f9fb form_thead">
                        	<tr class="bg_f8f9fb form_title">
                                <th>预订时间</th>
                                <th>预约用户</th>
                                <th>用户电话</th>
                                <th>预订人数</th>
                                <th>预订店铺</th>
                                <th>提交时间</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            <?php
                                if (!empty($list))
                                {
                            ?>
                             <tbody class="containers dataTables_wrapper form-inline dt-bootstrap">
                            <?php
                                foreach ($list as $key => $value)
                                {
                            ?>
                             <tr class="form_con" style="line-height:1.4">
                                <td><?php echo $value['book_date'].'<br>'.$value['book_time'];?></td>
                                <td><?php echo $value['book_name'];?></td>
                                <td><?php echo $value['book_phone'];?></td>
                                <td><?php echo $value['book_number'];?></td>
                                <td><?php echo $value['shop_name'];?></td>
                                <td><?php echo $value['add_date'].'<br>'.$value['add_time'];?></td>
                                <td><?php echo $value['book_info'] ? $value['book_info'] : '--';?></td>
                                <td>
                                    <?php
                                    if ($value['book_op_status'] == 0) {
                                        ?>
                                        <span class="blue pointer" onClick="change_staus('<?php echo $value['order_id'];?>','on')">用餐</span>
                                        <span class="red pointer" onClick="change_staus('<?php echo $value['order_id'];?>','off')">取消</span>
                                        <?php
                                    }else{
                                    ?>
                                    <span><?php echo $op_status[$value['book_op_status'].$value['book_op_type']]?></span>
                                        <?php
                                    }
                                    ?>
                                 </td>
                            </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                        <?php
                            }
                        ?>
                        </table>

                        <div class="row">
                            <div class="col-sm-5">
                                <!--                                <div class="dataTables_info" id="data-grid_info" role="status" aria-live="polite">共1条</div>-->
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data-grid_paginate">
                                    <ul class="Pagination"><?php echo $pagehtml;?></ul>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.content -->
</div><!-- /.content-wrapper -->
    <?php
    /* Footer Block @see footer.php */
    require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
    ?>

    <?php
    /* Right Block @see right.php */
    require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
    ?>

</div><!-- ./wrapper -->

<?php
/* Right Block @see right.php */
require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
?>

<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<!-- page script -->
<script>



    function change_staus(id,status){
        if(!confirm("确定更改")){
            return false;
        }
        $.post('<?php echo site_url('/appointment/book_orders/change_status')?>',{
            'order_id':id,
            'status':status,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(res){
            if(res.status == 1){
                alert(res.msg);
                window.location.reload();
            }else{
                alert(res.msg);
            }
        },'json');
    }
	
	$(".datepicker").datepicker({
		language: "zh-CN"
	});
	$(".timepicker").datetimepicker({
		format:"yyyy-mm-dd hh:ii", language: "zh-CN",clearBtn: false,todayBtn: true,orientation: "auto left",
	});
	$('#Add').click(function(){$('#Add_layer').show();})
	$('#Add_layer').click(function(){$('#Add_layer').hide()});
	$('#Add_layer .box').click(function(e){e.stopPropagation()});

	$('#subNew').click(function()
    {
		var _this = $(this);
		for(var i=0;i<$('#Add_layer input').length;i++){
			if($.trim($('#Add_layer input').eq(i).val())==''){
				alert('请先填写'+$('#Add_layer input').eq(i).attr('placeholder'));
				return;	
			}
		}
		_this.html('<i class="fa-spinner fa"></i>');

        var data = {};
        data.book_datetime = $('input[name="book_datetime"]').val();
        data.book_number = $('input[name="book_number"]').val();
        data.book_name = $('input[name="book_name"]').val();
        data.book_phone = $('input[name="book_phone"]').val();
        data.book_info = $('textarea[name="book_info"]').val();
        data.dining_room_id = $('#dining_room_id').val();
        data.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
		$.ajax({
			dataType:"json",
			type:'post',
			url:"<?php echo site_url('/appointment/book_orders/save_order')?>",
			data:data,
			success: function(res)
            {
                alert(res.msg);
                if (res.status == 1)
                {
                    $('#Add_layer').hide();
                    window.location.reload();
                }
			},
			complete: function()
            {
				_this.html('提交');				
			}
		})
	})


</script>
</body>
</html>
