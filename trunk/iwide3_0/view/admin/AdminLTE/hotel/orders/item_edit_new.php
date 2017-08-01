<!-- DataTables -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
</head>
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
textarea,select,input,.moba{height:30px;line-height:30px;border:1px solid #d7e0f1;text-indent:3px;}
.display_table{display:table;width:100%;}
.display_table >div{vertical-align:middle;display:table-cell;width:23%;}
.contents{padding:10px 0px 20px 20px;}
.order_details{padding:1% 0 3% 3%;}
.order_details>div{float:left;margin-right:5%;}
.order_details>div:last-child{margin-right:0%;}
.order_d_l{width:325px;}
.order_l_con > div{margin-bottom:7px;}
.order_l_con > div >span:first-child{display:inline-block;width:80px;text-align:right;margin-right:6%;}
.order_r_con .d_t_con >.redius{position:relative;}
.order_r_con .d_t_con >.redius:after{position:absolute;content:"";width:30px;height:1px;background:#d7e0f1;top:50%;right:74px;}
.order_r_con .d_t_con:first-child >.redius:after{content:"";width:0px;height:0px;}
.order_r_con >.active{color:#ff9900;}
.order_r_con >.active >p:nth-of-type(2){color:#999;}
.order_r_con >.active >.redius{background:#ff9900;}
.order_r_con >.active >.redius:after{background:#ff9900;}
.order_r_con{text-align:center;margin-bottom:40px;}
.states{margin-bottom:18px;}
.h_btn_list> div{display:inline-block;width:100px;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;cursor:pointer;}
.h_btn_list> div:last-child{margin-right:0px;}
.h_btn_list{margin-bottom:33px;}
.order_d_r{border-left:1px solid #d7e0f1;padding-left:13px;}
.redius{width:26px;color:#fff;height:26px;border-radius:50%;text-align:center;line-height:26px;background:#ededed;margin:5px auto;}
.d_t_con{margin-right:2.5%;}
.d_t_con:last-child{margin-right:0;}
.d_t_con >p{margin-bottom:5px}
.remarks{margin-bottom:52px;}

.h_btn_lists .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}

.h_btn_lists> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_lists> div:last-child{margin-right:0px;}

.confirms,.cancel{cursor:pointer;}
.modify_btn{padding-top:2%;width:325px;margin-left:3%;}
.modify_btn >div{border:1px solid #92a0ae;padding:3px 10px;border-radius:5px;margin-right:0px;width:50px;}
.informations{width:1100px;padding:2% 0 2% 3%;}
.informations >div{width:210px;float:left;}
.informations >div span:nth-of-type(2){color:#252525;}
.disabled_input{background:#fff;border:0px;}
.modify_b2,.modify_b{cursor:pointer;}
.modify_b2{display:none;}
</style>
<?php echo form_open( site_url('hotel/orders/item_edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'editstatus_form' ), array('orderid'=>$list['orderid'],'item_id'=>$list['id'],'ajax'=>1) ); ?>
<div class="fixed_box bg_fff">
    <div class="tile">订单处理</div>
    <div class="f_b_con">当前订单状态修改为：已入住</div>
    <input type="hidden" name="istatus" id='istatus'>
    <div class="h_btn_lists clearfix" style="">
        <div class="actives confirms">确认</div>
        <div class="cancel f_r">取消</div>
    </div>
</div>
<?php echo form_close() ?>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;" >
            <div class="banner bg_fff p_0_20">
                <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
                <?php echo form_open( site_url('hotel/orders/item_edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data','id'=>'edit_form' ), array('orderid'=>$list['orderid'],'item_id'=>$list['id'],'ajax'=>1) ); ?>
                <div class="bg_fff m_b_4 border_1">
                    <?php if(!empty($can_edit)){?>
                        <div class="modify_btn clearfix">
                            <div class="f_r modify_b">修改</div>
                            <div class="f_r modify_b2">保存</div>
                        </div>
                    <?php }?>
                    <div class="order_details clearfix">
                			<div class="order_d_l">
                				<div class="order_l_con m_b_8">
                					<div>
                						<span>订单号</span>
                						<span><?php echo $list['orderid']?></span>
                					</div>
                					<div>
                						<span>下单时间</span>
                						<span><?php echo date('Y-m-d H:i:s',$list['order_time'])?></span>
                					</div>
                					<div>
                						<span>支付状态</span>
                						<span class="color_ff9900"><?php echo $list['pay_name']."--".$list['status_des'];?></span>
                					</div>
                					<div>
                						<span>实付金额</span>
                						<span><input type="text" name="new_price" value="<?php echo $list['iprice']?>" disabled class="disabled_input" /></span>
                					</div>
                                    <div>
                                        <span>房号</span>
                                        <span><input type="text" name="mt_room_id" value="<?php echo $list['mt_room_id']?>" disabled class="disabled_input" /></span>
                                    </div>
                					<!-- <div>
                						<span>优惠券金额</span>
                						<span>3<?php echo $list['coupon_favour']?></span>
                					</div>
                					<div>
                						<span>积分数量</span>
                						<span>600</span>
                					</div> -->
                				</div>

                				<div class="order_l_con">
                					<div>
                						<span>入住酒店</span>
                						<span><?php echo $list['hotelname']?></span>
                					</div>
                					<div>
                						<span>入住房型</span>
                						<span><?php echo $list['roomname']?></span>
                					</div>
                					<div>
                						<span>入住人</span>
                						<span><?php echo $list['customer']!=''?$list['customer']:$list['name'];?></span>
                					</div>
                					<div>
                						<span>联系方式</span>
                						<span><?php echo $list['tel']?></span>
                					</div>
                					<div>
                						<span>入住日期</span>
                						<span><input name='startdate' class="datepicker moba disabled_input" disabled id="datepicker" type="text" value="<?php echo $list['f_startdate']?>"  /></span>
                					</div>
                					<div>
                						<span>离店日期</span>
                						<span><input name='enddate' class="datepicker moba disabled_input" disabled id="datepicker2" type="text" value="<?php echo $list['f_enddate']?>"  /></span>
                					</div>
                					<!-- <div>
                						<span>房间数</span>
                						<span>5</span>
                					</div> -->
                				</div>
                			</div>
                        
            			<div class="order_d_r">
            				<div class="display_table order_r_con">
            					<div class="d_t_con active">
            						<div class="redius">1</div>
            						<p>用户下单</p>
            						<p><?php echo date('Y-m-d H:i:s',$list['order_time'])?></p>
            					</div>
            					<div class="d_t_con <?php if(!empty($list['save_1'])) echo 'active'?>">
            						<div class="redius">2</div>
            						<p class="hotel_state">酒店确认</p>
            						<p>
                                    <?php if(!empty($list['save_1'])){?>
                                    <?php echo $list['save_1'];?>
                                    <?php }else{?>
                                        &nbsp;
                                    <?php }?>
                                    </p>
            					</div>
            					<div class="d_t_con <?php if(!empty($list['save_2'])) echo 'active'?>">
            						<div class="redius">3</div>
            						<p>用户入住</p>
            						<p><?php if(!empty($list['save_2'])){?>
                                    <?php echo $list['save_2'];?>
                                    <?php } else{ ?>
                                        &nbsp;
                                    <?php }?></p>
            					</div>
            					<div class="d_t_con <?php if(!empty($list['save_3'])) echo 'active'?>">
            						<div class="redius">4</div>
            						<p>用户离店</p>
            						<p><?php if(!empty($list['save_3'])){?>
                                    <?php echo $list['save_3'];?>
                                    <?php } else{ ?>
                                        &nbsp;
                                    <?php }?></p>
            					</div>
            				</div>
                            <div style="padding-left:27px;">
                				<div class="states"><span class="m_r_10 w_88">
                                    <i class="iconfont color_ff9900" style="margin-right:4px;">&#xe64d;</i>订单状态:</span>
                                    <span class="state_con"><?php echo $list['status_des'];?></span>
                                </div>

                                <?php if(!empty($status)){?>
                				<div  class="h_btn_list even_btn" style="">
                                    <?php foreach($status as $code=>$des){?>
                                        <?php if (strpos($des,'取消')>0) {?>
                                            <div class="bg_7e8e9f color_fff border_none template_btn" value='<?php echo $code;?>'><?php echo $des;?></div>
                                        <?php }else{?>
                                            <div class="actives bg_ff9900 color_fff border_none template_btn" value='<?php echo $code;?>'><?php echo $des;?></div>
                                        <?php }?>
                                    <?php }?>
                                </div>
                                <?php }?>

                				<!-- <div class="remarks"><span class="m_r_10 w_88"><i class="iconfont color_ff9900"  style="margin-right:4px;">&#xe64d;</i>用户备注:</span>我要无烟房</div>
                                <div class="remarks clearfix">
                                    <span class="m_r_10 w_88" style="float:left;">前台备注:</span>
                                    <textarea style="width:300px;height:120px;"></textarea>
                                </div> -->
                				<div>
                					<p>温馨提醒:</p>
                					<p>a.如果无法接单,请及时与买家联系并说明情况后进行退款</p>
                				</div>
                            </div>
            			</div>
            		</div>
            	</div>
                <?php echo form_close() ?>
            	<div class="bg_fff border_1" style="display: none;">
            		<div class="clearfix order_l_con informations">
                        <div>
                            <span>会员ID:</span>
                            <span>18819188370</span>
                        </div>   
                        <div>
                            <span>性别:</span>
                            <span>男</span>
                        </div>
                        <div>
                            <span>年龄段:</span>
                            <span>90后</span>
                        </div> 
                        <div>
                            <span>会员等级:</span>
                            <span>黑钻会员</span>
                        </div> 
                        <div>
                            <span>客户关系:</span>
                            <span>活跃用户</span>
                        </div>  
                        <div>
                            <span>注册时间:</span>
                            <span>2016.01.06</span>
                        </div>   
                        <div>
                            <span>账户余额:</span>
                            <span>755</span>
                        </div>
                        <div>
                            <span>账户积分:</span>
                            <span>7000</span>
                        </div> 
                        <div>
                            <span>优惠券:</span>
                            <span>43</span>
                        </div> 
                        <div>
                            <span>消费订单:</span>
                            <span  class="color_ff9900">6</span>
                        </div> 
                        <div>
                            <span>消费总额:</span>
                            <span>103287</span>
                        </div>   
                        <div>
                            <span>消费能力:</span>
                            <span>300-400</span>
                        </div>
                        <div>
                            <span>常在地点:</span>
                            <span>广州</span>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
<!--日历调用结束-->
<script>
;!function(){
	laydate({
	   elem: '#datepicker',
        min: "<?php echo $begin_min;?>", //设定最小日期为当前日期
        max: "<?php echo $begin_max;?>" //最大日期
	})
	laydate({
	   elem: '#datepicker2',
        min: "<?php echo $end_min;?>", //设定最小日期为当前日期
        max: "<?php echo $end_max;?>" //最大日期
	})
}();
</script>
<script>
$(function(){
    var bool=true;
    var btn_s=$("<div class='actives bg_ff9900 color_fff border_none template_btn3'>离店</div>");
    
    $('.template_btn').click(function(){
        if(bool){
            var _this=$(this);
            bool=false;
            $('.fixed_box').fadeIn();
            $("#istatus").val(_this.attr('value'));
            $('.f_b_con').html('当前订单状态修改为：<b>'+ _this.html().replace('操作','')+'</b>');
            $('.confirms').click(function(){
                $.post('<?php echo site_url('hotel/orders/item_edit_post')?>',$("#editstatus_form").serialize(),function(data){
                    if(data == 'false'){
                        alert('修改失败');
                        return false;
                    }
                    window.location.reload();     
                });
            });
        }
    });
    
    $('.cancel').click(function(){
        bool=true;
        $('.fixed_box').fadeOut();
    });

    $('.modify_b').click(function(){
        $('.modify_b').hide();
        $('.modify_b2').show();
        $('input').removeAttr('disabled');
        $('input').removeClass('disabled_input');
    })
    $('.modify_b2').click(function(){
        $.post('<?php echo site_url('hotel/orders/item_edit_post')?>',$("#edit_form").serialize(),function(data){
            if(data == 'false'){
                alert('修改失败');
                return false;
            }
            $('.modify_b').show();
            $('.modify_b2').hide();
            $('.order_l_con input').attr('disabled','disabled');
            $('.order_l_con input').addClass('disabled_input');            
        });

    })
})
</script>
</body>
</html>
