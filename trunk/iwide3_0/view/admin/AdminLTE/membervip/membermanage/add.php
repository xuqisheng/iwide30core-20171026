<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link href="<?php echo base_url(FD_PUBLIC) ?>/js/art_Dialog/skins/default.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<script type="text/javascript">
    //全局变量
    var GV = {
        DIMAUB: "<?php echo base_url();?>",
        JS_ROOT: "<?php echo FD_PUBLIC ?>/js/",
    };
</script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/wind.js"></script>
</head>
<style>
    html, body{min-width: 100%;}
.weborder {
  background: #FFFFFF !important;
  display: none;
}

.morder {
  background: #FAFAFA !important;
}

.a_like {
  cursor: pointer;
  color: #72afd2;
}

.page {
  text-align: right;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 2em;
}

.page a {
  padding: 10px;
}

.current {
  color: #000000;
}
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
@font-face {
  font-family: 'iconfont';
  src: url('<?php echo base_url(FD_PUBLIC);?>/newfont/iconfont.eot');
  src: url('<?php echo base_url(FD_PUBLIC);?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('<?php echo base_url(FD_PUBLIC);?>/newfont/iconfont.woff') format('woff'),
  url('<?php echo base_url(FD_PUBLIC);?>/newfont/iconfont.ttf') format('truetype'),
  url('<?php echo base_url(FD_PUBLIC);?>/newfont/iconfont.svg#iconfont') format('svg');
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
.bg_e8eaee{background:#e8eaee;}
.bg_fe6464{background:#fe6464;}
.color_72afd2{color:#72afd2;}
.color_ff9900{color:#ff9900;}
.color_F99E12{color:#F99E12;}
.color_A4C4ED{color:#A4C4ED;}
.color_E9858A{color:#E9858A;}
.color_B3CEF0{color:#B3CEF0;}
.color_FFAF28{color:#FFAF28;}
.color_E6757A{color:#E6757A;}
a{color:#92a0ae;}

.border_1{border:1px solid #d7e0f1;}
.f_r{float:right;}
.p_0_20{padding:0 20px;}
.w_90{width:90px;}
.w_130{width:130px;}
.w_200{width:200px;}
.p_r_30{padding-right:30px;}
.m_t_10{margin-top:10px;}
.p_0_30_0_10{padding:0 30px 0 10px;}
.b_b_1{border-bottom:1px solid #d7e0f1;}
.b_t_1{border-top:1px solid #d7e0f1;}
.p_t_10{padding-top:10px;}
.p_b_10{padding-bottom:10px;}


.containers >div:nth-child(odd){background:#F8F8F8 !important;}
.containers >div:nth-child(even){background:#fff !important;}
.table{display:table;margin-bottom:0px;}
.table >div{display: table-cell;width:137px;}
.contents{padding:10px 0px 85px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont >div{margin-bottom:10px;cursor:pointer;}
.head_cont >div:last-child{margin-bottom:0px;}
.h_btn_list .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> div:last-child{margin-right:0px;}
.f_r_con >div,.j_head >div{display:inline-block;}
.f_r_con >div{margin-right:10px;}
.classification{height:50px;line-height:50px;margin-bottom:18px;}
.classification >div{width:98px;display:inline-block;text-align:center;height:50px;}
.classification .add_active{border-bottom:3px solid #ff9900;}
.fomr_term{height:30px;line-height:30px;}
.classification >div,.all_open_order{cursor:pointer;}
.template >div{text-align:center;}
.template_img{float:left;width:60px;height:60px;overflow:hidden;vertical-align:middle;margin-right:2%;}
.template_span{display:block;line-height:1.3 !important;overflow:hidden;text-overflow:ellipsis;display:-webkit-box; -webkit-box-orient:vertical;-webkit-line-clamp:2; }
.template_btn{padding:1px 8px;border-radius:3px;}
.temp_con{border-bottom:1px solid #d7e0f1;}
.temp_con:last-child{border-bottom:none;}
.temp_con >div >span{line-height:1.7;}
.room{width:52px;display:inline-block;}
.con_list > div:nth-child(odd){background:#fafcfb;}
.con_list{display:none;}
.frames_c{padding:2px 8px;margin-right:10px;border-radius:3px;}
.t_money{font-size:13px;}
.detailed_con >div{float:left;padding:20px;width:50%;}
.detailed_con >div:first-child{border-right:1px solid #d7e0f1;}
.de_first div span:first-child{width:105px;display:inline-block;text-align:right;}
.no_record{text-align:center;padding:35px 0;}

.drow_list{display:none;position:absolute;width:100%;top:100%;left:0;background:#fff;border:1px solid #e4e4e4;padding:0;overflow:auto;z-index:999}
.drow_list li{height:35px;padding-left:15px;line-height:35px;list-style:none; cursor:pointer}
.drow_list li:hover{background:#f1f1f1}
.drow_list li.cur{background:#ff9900;color:#fff}
#drowdown:hover .drow_list{display:block}
.fixed_box{position:fixed;top:36%;left:48%;z-index:9999;border:1px solid #d7e0f1;border-radius:5px;padding:1% 2%;display:none;}
.tile{font-size:15px;text-align:center;margin-bottom:4%;}
.f_b_con{font-size:13px;text-align:center;margin-bottom:8%;}
.pointer,.delivery,.confirms,.cancel{cursor:pointer;}
.pagination{margin-top:0px;margin-bottom:0px;}
.btn_list_r span{margin-right:10px;}
.btn_list_r span:last-child{margin-right:0px;}

.pages {
  float: right;
  margin: 20px 0;
}
.pages #Pagination {
  float: left;
  overflow: hidden;
}
.pages #Pagination .pagination {
  height: 40px;
  text-align: right;
  font-family: \u5b8b\u4f53,Arial;
}
.pages #Pagination .pagination a,
.pages #Pagination .pagination span {
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
.pages #Pagination .pagination .current {
  background: #ffac59;
  color: #fff;
}
.pages #Pagination .pagination .prev,
.pages #Pagination .pagination .next {
  float: left;
  padding: 11px 13px;
  border: 1px solid #e6e6e6;
  background: #f6f6f6;
  color: #666666;
  cursor: pointer;
}
.pages #Pagination .pagination .prev i,
.pages #Pagination .pagination .next i {
  display: inline-block;
  width: 4px;
  height: 11px;
  margin-right: 5px;
  background: url(icon.fw.png) no-repeat;
}
.pages #Pagination .pagination .prev {
  border-right: none;
}
.pages #Pagination .pagination .prev i {
  background-position: -144px -1px;
  *background-position: -144px -4px;
}
.pages #Pagination .pagination .next i {
  background-position: -156px -1px;
  *background-position: -156px -4px;
}
.pages #Pagination .pagination .pagination-break {
  padding: 11px 5px;
  border: none;
  border-left: 1px solid #e6e6e6;
  background: none;
  cursor: default;
}

</style>
<div class="fixed_box bg_fff">
  <div class="tile"></div>
  <div class="f_b_con">
    你是否要删除这条信息 ？
  </div>
  <div class="f_b_con">
    <span></span>
    <span></span>
  </div>
  <div class="h_btn_list clearfix" style="">
    <div class="actives confirms">确认</div>
    <div class="cancel f_r">取消</div>
  </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1050px;">
            <div class="banner bg_fff p_0_20">会员详细</div>
            <div class="contents">
                <?=form_open(EA_const_url::inst()->get_url('*/*/unbind'), array('class'=>'form-inline form-unbind'))?>
                <input type="hidden" name="member_info_id" value="<?=!empty($member_info_id)?$member_info_id:''?>">
                <input type="hidden" name="openid" value="<?=!empty($open_id)?$open_id:''?>">
                <div class="border_1 bg_fff detailed_con clearfix">
                    <div class="de_first">
                        <div>
                            <span>会员ID：</span>
                            <span><?=!empty($member_info_id)?$member_info_id:''?></span>
                        </div>
                        <div>
                            <span>会员昵称：</span>
                            <span><?=!empty($nickname)?$nickname:''?></span>
                        </div>
                        <div>
                            <span>会员等级：</span>
                            <span><?=!empty($lvl_name)?$lvl_name:''?></span>
                        </div>
                        <div>
                            <span>pms等级代码：</span>
                            <span><?=!empty($lvl_pms_code)?$lvl_pms_code:''?></span>
                        </div>
                        <div>
                            <span>会员积分：</span>
                            <span><?=!empty($credit)?$credit:''?></span>
                        </div>
                        <div>
                            <span>会员储值：</span>
                            <span><?=!empty($balance)?$balance:''?></span>
                        </div>
                        <div>
                            <span>是否有效：</span>
                            <?php if(!empty($is_active) && $is_active=='t'):?>
                            <span>是</span>
                            <?php else:?>
                            <span>否</span>
                            <?php endif;?>
                        </div>
                        <div>
                            <span>是否登陆：</span>
                            <?php if(!empty($is_login) && $is_login=='t'):?>
                                <span id="is_login">是</span>
                            <?php else:?>
                                <span id="is_login">否</span>
                            <?php endif;?>
                        </div>
                        <div>
                            <span></span>
                            <span></span>
                        </div>
                        <div>
                            <span>真实姓名：</span>
                            <span><?=!empty($name)?$name:''?></span>
                        </div>
                        <div>
                            <span>手机号码：</span>
                            <span><?=!empty($cellphone)?$cellphone:''?></span>
                        </div>
                        <div>
                            <span>身份证号：</span>
                            <span><?=!empty($id_card_no)?$id_card_no:''?></span>
                        </div>
                        <div>
                            <span>会员生日：</span>
                            <span><?=!empty($birth)?date('Y-m-d',$birth):''?></span>
                        </div>
                        <div>
                            <span>会员邮箱：</span>
                            <span><?=!empty($email)?$email:''?></span>
                        </div>
                        <div>
                            <span>注册时间：</span>
                            <span><?=!empty($createtime)?date('Y-m-d',$createtime):''?></span>
                        </div>
                    </div>
                    <div>
                        <div>升级记录</div>
                        <?php if(!empty($lvllog)):?>
                            <?php foreach ($lvllog as $key => $value):?>
                                <div>
                                <?php echo $value['last_update_time']; ?>升级到: <?php echo $value['lvl_name']; ?> (备注：<?php echo $value['note']; ?>)</div>
                            <?php endforeach;?>
                        <?php else:?>
                            <div class="no_record">暂无记录</div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="box-footer box-bind">
                    <?php if($member_mode == 1 OR $is_login == 'f'):?>
                        <button type="submit" disabled class="btn btn-disabled"><i class="fa fa-save">解绑</i></button>
                    <?php else:?>
                        <button type="submit" class="btn btn-primary unbind"><i class="fa fa-save">解绑</i></button>
                    <?php endif;?>
                </div>
                <?=form_close()?>
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
<!--日历调用开始-->
<!-- <script src="<?php echo base_url(FD_PUBLIC);?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/layDate.js"></script>
<script>
;!function(){
  laydate({
     elem: '#datepicker'
  })
  laydate({
     elem: '#datepicker2'
  })
}();
</script>
<!--日历调用结束-->
    <script>
        $(function(){
            <?php if($member_mode == 2 && $is_login == 't'):?>
            Wind.use("ajaxForm","artDialog", function (){
                $(document).on('click', '.unbind', function (e){
                    e.preventDefault();
                    var _this = this, ok_url = "<?php echo EA_const_url::inst()->get_url('*/*/');?>", btn = $(this);
                    var form = $('.form-unbind'), form_url = form.attr("action");
                    //ie处理placeholder提交问题
                    if ($.support.msie) {
                        form.find('[placeholder]').each(function () {
                            var input = $(this);
                            if (input.val() == input.attr('placeholder')) {
                                input.val('');
                            }
                        });
                    }

                    form.ajaxSubmit({
                        url: form_url,
                        dataType: 'json',
                        beforeSubmit: function (arr, $form, options) {
                            var text = btn.find("i").text();
                            btn.prop('disabled', true).addClass('disabled').find("i").text(text + '中...');
                        },
                        success: function (data) {
                            if (data.status == 1) {
                                btn.parent().append("<span style='color: #00b723;'>" + data.message + "</span>");
                                setTimeout(function () {
                                    btn.parent().find('span').fadeOut('normal', function () {
                                        btn.parent().find('span').remove();
                                    });
                                }, 3000);
                                btn.removeClass("btn-primary");
                                btn.addClass("btn-disabled");
                                $("#is_login").text('否');
                            } else {
                                btn.parent().append("<span style='color: #ff0040;'>" + data.message + "</span>");
                                setTimeout(function () {
                                    btn.parent().find('span').fadeOut('normal', function () {
                                        btn.parent().find('span').remove();
                                    });
                                }, 3000);
                                btn.prop('disabled', false);
                            }
                        },
                        complete: function () {
                            var text = btn.find("i").text();
                            btn.removeClass('disabled').find("i").text(text.replace('中...', ''));
                        },
                        error: function () {
                            btn.parent().append("<span style='color: #ff0040;'>请求异常,请刷新页面试试!</span>");
                            setTimeout(function () {
                                btn.parent().find('span').fadeOut('normal', function () {
                                    btn.parent().find('span').remove();
                                });
                            }, 3000);
                            btn.prop('disabled', false);
                        }
                    });
                });
            });
            <?php endif;?>

            var This;
            $('.del_btn').click(function(){
                This=$(this);
                $('.fixed_box').show();
            })
            $('.confirms').click(function(){
                This.parent().parent().remove();
                $('.fixed_box').hide();
            })
            $('.cancel').click(function(){
                $('.fixed_box').hide();
            })
            var bool=true;
            var obj=null;
            $('.drow_list li').click(function(){
                $('#search_hotel').val($(this).text());
                $('#search_hotel_h').val($(this).val());
                $(this).addClass('cur').siblings().removeClass('cur');
            });
            $('.classification >div').click(function(){
                $(this).addClass('add_active').siblings().removeClass('add_active');
            })
            $('.open_order').click(function(){
                $(this).parent().parent().next().slideToggle();
            })
            $('.all_open_order').click(function(){
                $('.con_list').slideToggle();
            })
            var tips=$('#tips');
            $('.btn_o').click(function(){
                //console.log( decodeURIComponent($(".form").serialize(),true));
                start=$('.t_time').find('input[name="start_t"]').val().replace(/-/g,'');
                end=$('.t_time').find('input[name="end_t"]').val().replace(/-/g,'');
                if(start!=''&&start!=undefined){
                    if(isNaN(start)){
                        tips.html('开始日期错误');
                        setout(tips);
                        return false;
                    }
                    if(end!=''&&end!=undefined){
                        if(isNaN(end)||end<start){
                            tips.html('结束日期错误或大于开始日期');
                            setout(tips);
                            return false;
                        }
                    }
                }
            })
        })
    </script>
</body>
</html>
