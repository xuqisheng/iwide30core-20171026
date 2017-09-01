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
</head>
<style>
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

.banner{height:50px;width:100%;line-height:50px;border-bottom:1px solid #d7e0f1;padding-right:0px;}
.banner > span{padding:0px 5px;margin-left:5px;border-radius:3px;font-size:11px;}
.news{position:relative;cursor:pointer;background:#fe8f00;height:100%;padding:0px 12px;color:#fff;}
.news_radius{padding:0 2px;border-radius:3px;background:#fff;color:#fe8f00;text-align:center;font-size:8px;margin-left:8px;}
.display_flex{display:flex;display:-webkit-flex;justify-content:top;align-items:center;-webkit-align-items:center;}
.display_flex >div{-webkit-flex:1;flex:1;cursor:pointer;}
.j_toshow{width:320px;min-height:100%;position:absolute;top:50px;right:-330px;box-shadow:-5px 0px 15px rgba(0,0,0,0.1);-webkit-box-shadow:-5px 0px 15px rgba(0,0,0,0.1);}
.toshow_con{padding:12px;}
.t_con_list{margin-bottom:12px;height:170px;}
.close_btn{cursor:pointer;}
.toshow_con_titl{background:#f0f3f6;font-size:13px;padding:10px;border-bottom:1px solid #d7e0f1;}
.toshow_con_list{padding:10px;font-size:11px;height:114px;overflow:hidden;}
.toshow_con_list >a{display:block;margin-bottom:5px;}
.toshow_con_list >a:last-child{margin-bottom:0px;}
.toshow_titl_txt{position:relative;}
.radius_txt{position:absolute;top:0px;left:105%;border-radius:3px;text-align:center;padding:0px 3px;font-size:12px;}
select,input,.moba{height:30px;line-height:30px;border:1px solid #d7e0f1;text-indent:3px;}

.contents{padding:10px 0px 20px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont >div{margin-bottom:10px;cursor:pointer;}
.head_cont >div:last-child{margin-bottom:0px;}
.head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> *{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> divlast-child{margin-right:0px;}
.j_head >div{display:inline-block;}
.j_head >div:nth-of-type(1){width:307px;}
.j_head >div:nth-of-type(2){width:526px;}
.j_head >div:nth-of-type(3){width:255px;}
.j_head >div >span:nth-of-type(1){display:inline-block;width:60px;text-align:center;}
.classification{height:30px;line-height:30px;}
.classification >div{width:70px;display:inline-block;text-align:center;height:30px;}
.classification .add_active{border-bottom:3px solid #ff9900;}
.fomr_term{height:30px;line-height:30px;}
.classification >div,.all_open_order{cursor:pointer;}
.template >div{text-align:center;}
.template >div:nth-of-type(1){-webkit-flex:3.7;flex:3.7;text-align:left;padding-left:10px;}
.template >div:nth-of-type(2){-webkit-flex:1.2;flex:1.2;}
.template >div:nth-of-type(7){-webkit-flex:1.3;flex:1.3;}
.template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
.template_span{display:inline-block;margin-top:2px;}
.template_btn{padding:1px 8px;border-radius:3px;}
.temp_con >div >span{line-height:1.7;}
.room{width:52px;display:inline-block;}
.con_list > div:nth-child(odd){background:#fafcfb;}
.con_list{display:none;}
.page{padding-bottom: 1%;}
.page a{padding:6px 12px;margin-left:-1px;line-height:1.42857143;color:#337ab7;text-decoration:none;background-color:#fff;border:1px solid #ddd;font-size:15px;}
.current{color:#fff !important;cursor:default;background-color:#337ab7 !important;}
</style>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;">
            <div class="banner bg_fff p_0_20">
                <!-- <div class="f_r news"><i class="iconfont" style="font-size:22px;vertical-align:middle;margin-right:5px;">&#xe623;</i>消息<span class="news_radius bg_ff0000">8</span></div> -->
                <?php echo $action_name;?>
            </div>
            <div class="contents">
				<form class="form" method='get' action='<?php echo $search_url;?>'>
                <input type="hidden" name="s" value="<?php echo $istatus;?>" />
				<div class="head_cont contents_list bg_fff">
					<div class="j_head">
						<div>
							<span>酒店名称</span>
							<select class="w_200" name="hotel">
								<option value="-1">酒店名称</option>
                            		<?php foreach ($allhotels as $kh => $vh) {?>
                                	<option value="<?php echo $vh['hotel_id'];?>" <?php if($vh['hotel_id']==$hotel) echo 'selected';?>><?php echo $vh['name'];?></option>
                                	<?php }?>
							</select>
						</div>
						<div>
							<span>时间筛选</span>
							<select class="w_90" name="timetype">
								<?php foreach ($time_type as $kt => $vt) {?>
                            	<option value="<?php echo $kt;?>" <?php if($kt==$timetype) echo 'selected';?>><?php echo $vt;?></option>
                               	<?php }?>
							</select>
							<span class="t_time"><input name="start_t" type="text" id="datepicker" class="datepicker moba" value="<?php echo $start_t;?>"></span>
                            <font>至</font>
                            <span class="t_time"><input name="end_t" type="text" id="datepicker2" class="datepicker moba" value="<?php echo $end_t;?>"></span>
						</div>
						<div>
							<span>关键字</span>
							<span><input style="width: 170px;"type="text" name="number" value="<?php echo $number;?>" placeholder="客户姓名/手机号/订单号"/></span>
						</div>
					</div>
					<div class="j_head">
						<div>
							<span>支付方式</span>
							<span>
								<select  class="w_200" name="paytype">
                                	<option value="-1">全部</option>
									<?php foreach ($pay_type as $kp => $vp) {?>
                                	<option value="<?php echo $kp;?>" <?php if($kp==$paytype) echo 'selected';?>><?php echo $vp;?></option>
                                    <?php }?>
                                </select>
							</span>
						</div>
						<div>
							<span>支付状态</span>
							<span>
								<select class="w_90" name="paystatus">
                                	<option value="-1">全部</option>
                                	<?php foreach ($pay_status as $ks => $vs) {?>
                                	<option value="<?php echo $ks;?>" <?php if($ks==$paystatus) echo 'selected';?>><?php echo $vs;?></option>
                                    <?php }?>
                                </select>
							</span>
						</div>
						<div>
							<span>订单状态</span>
							<span>
								<select name="orderstatus">
                                	<option value="-1">全部</option>
                                	<?php foreach ($order_status as $ko => $vo) {?>
                                	<option value="<?php echo $ko;?>" <?php if($ko==$orderstatus) echo 'selected';?>><?php echo $vo;?></option>
                                	<?php }?>
                                </select>
							</span>
						</div>
					</div>
					<div  class="h_btn_list" style="">
						<button class="actives" onclick="submit(this.form)">筛选</button>
                        <!--
						<div>批量导出</div>
						<div>显示设置</div>
                        -->
					</div>
				</div>
				</form>
				<div class="contents_list bg_fff" style="font-size:13px;">
					<div class="p_r_30 classification b_b_1">
						<span class="f_r all_open_order">展开订单</span>
						<?php foreach ($show_status as $k => $v) {?>
						<div class="<?php if($istatus==$k){echo 'add_active';}?>"><a href="<?php echo site_url('hotel/orders_ticket/index?s='.$k);?>"><?php echo $v['des'];?></a></div>
						<?php }?>
					</div>
					<div class="bg_f8f9fb display_flex fomr_term template">
					<?php foreach ($list_fields as $field): ?>
						<div><?php echo $field;?></div>
					<?php endforeach ?>
						<div>操作</div>
					</div>
				</div>
				<?php if(!empty($lists)){ foreach($lists as $k => $list){?>
				<div class="border_1 m_t_10 bg_fff">
					<div class="bg_f8f9fb fomr_term p_0_30_0_10 b_b_1">
						<span class="f_r"><a href="<?php echo site_url('hotel/orders_ticket/edit?ids='.$list['id'].'&h='.$list['hotel_id']);?>">订单详情</a></span>
						<div>订单号：<?php echo $list['orderid'];?></div>
					</div>
					<div class="display_flex temp_con template p_t_10 p_b_10">
						<div class="clearfix">
							<img class="template_img" src="<?php echo $list['order_details'][0]['r_room_img'];?>">
							<span class="template_span"><?php echo $list['hname_rname'];?></span><br>
							<span>下单时间：<?php echo date('Y.m.d',$list['order_time']);?></span>
						</div>
						<div>
							<span><?php echo $list['price'];?></span><br>
							<span><?php echo $list['roomnums'];?>张</span>
						</div>
						<!--缺省-->
						<div>
							<span>0.00/0</span><br>
							<span>0.00</span>
						</div>
						<!--end-->
						<div>
							<span><?php echo $list['name'];?></span><br>
							<span><?php echo $list['tel'];?></span>
						</div>
						<div>
							<span><?php echo date('Y.m.d',strtotime($list['startdate']));?></span><br>
							<!-- <span></span> -->
						</div>
						<div>
							<span><?php echo $list['paytype'];?></span><br>
							<span class="color_ff9900"><?php echo $list['is_paid'];?></span>
						</div>
						<div>
							<span><?php echo $list['status'];?></span><br>
							<?php if(empty($list['no_status'])){?> 							
							<?php if(!empty($list['opt_status'])){foreach ($list['opt_status'] as $k => $val) {?>
							<span class="bg_<?php echo $val['bg_color'];?> color_fff template_btn" sid="<?php echo $k;?>" oid="<?php echo $list['orderid'];?>" onclick="change_status(this)"><?php echo $val['text'];?></span>
							<?php }}?>
							<?php }else {?><span>--&nbsp;</span><?php }?>
						</div>
						<div>
							<span class="color_72afd2"><a href="<?php echo site_url('hotel/orders_ticket/edit?ids='.$list['id'].'&h='.$list['hotel_id']).'&print=1';?>">打印订单</a></span><br>
							<span class="color_72afd2 open_order">展开订单</span>
						</div>
					</div>
					<div class="con_list">
					<?php if(!empty($list['order_details'])){ foreach ($list['order_details'] as $kod => $vod) {?>
						<div  class="display_flex temp_con template p_t_10 p_b_10 b_t_1">
							<div class="clearfix">
								<span class="room">票<?php echo $kod+1;?></span>
								<span><?php echo $vod['roomname'];?>-<?php echo $vod['price_code_name'];?></span>
							</div>
							<div>
								<span><?php echo $vod['iprice'];?></span>
							</div>
							<div>
								<span><?php echo $list['coupon_used'];?></span>
							</div>
							<div>
								<span>&nbsp;</span>
							</div>
							<div>
								<span>&nbsp;</span>
							</div>
							<div>
								<span>&nbsp;</span>
							</div>
							<div>
							<span class=""><?php echo $vod['istatus'];?></span>
							<?php if(empty($vod['no_item_status'])&&empty($vod['no_item_status'])){?>
								<?php if(!empty($vod['item_opt_status'])){foreach ($vod['item_opt_status'] as $k => $val) {?>
								<span class="bg_<?php echo $val['bg_color'];?> color_fff border_1 template_btn" sid="<?php echo $k;?>" oid="<?php echo $list['orderid'];?>" iid="<?php echo $vod['id'];?>" onclick="change_status(this)"><?php echo $val['text'];?></span>
								<?php }}?>
							<?php }else {?><span>-</span><?php }?>
							</div>
							<div>
								<span>&nbsp;</span>
							</div>
						</div>
					<?php }}?>
					</div>
				</div>
				<?php }}?>
			</div>
			<div class="page"><?php echo $pagination;?></div>
        </div>
    </div>
    <!-- <div class="j_toshow bg_fff">
        <div class="banner bg_fff p_0_20">消息中心（8未读）<i class="iconfont f_r close_btn" style="font-size:24px;">&#xe635;</i></div>
        <div class="toshow_con">
            <div class="border_1 t_con_list">
                <div class="toshow_con_titl">
                    <a class="f_r mores" href="">更多</a>
                    <span class="toshow_titl_txt">订单消息<div class="radius_txt bg_ff0000 color_fff">6</div></span>
                </div>
                <div class="toshow_con_list">
                    <a href="">[待确认]您有一条新的订房订单需要确认哦！</a>
                    <a href="">[待确认]您有一条新的订房订单需要确认哦！</a>
                    <a href="">[待确认]您有一条新的订房订单需要确认哦！</a>
                    <a href="">[待确认]您有一条新的订房订单需要确认哦！</a>
                    <a href="">[待确认]您有一条新的订房订单需要确认哦！</a>
                </div>
            </div>
            <div class="border_1 t_con_list">
                <div class="toshow_con_titl">
                    <a class="f_r mores" href="">更多</a>
                    <span class="toshow_titl_txt">用户评价<div class="radius_txt bg_ff0000 color_fff">1</div></span>
                </div>
                <div class="toshow_con_list">
                    <a href="">[买家评价]还好吧，挺干净的，也挺安静。</a>
                </div>
            </div>
            <div class="border_1 t_con_list">
                <div class="toshow_con_titl">
                    <a class="f_r mores" href="">更多</a>
                    <span class="toshow_titl_txt">全员分销<div class="radius_txt bg_ff0000 color_fff">2</div></span>
                </div>
                <div class="toshow_con_list">
                    <a href="">[待审核]有新申请分销员等待您审核哦！</a>
                    <a href="">[待审核]有新申请分销员等待您审核哦！</a>
                </div>
            </div>
            <div class="border_1 t_con_list">
                <div class="toshow_con_titl">
                    <a class="f_r mores" href="">更多</a>
                    <span class="toshow_titl_txt">社群客<div class="radius_txt bg_ff0000 color_fff">2</div></span>
                </div>
                <div class="toshow_con_list">
                    <a href="">[待审核]有新申请分销员等待您审核哦！</a>
                    <a href="">[待审核]有新申请分销员等待您审核哦！</a>
                </div>
            </div>
        </div>
    </div> -->
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
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/layDate.js"></script>
<!--日历调用结束-->
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
<script>
$(function(){
	$('.classification >div').click(function(){
		$(this).addClass('add_active').siblings().removeClass('add_active');
	})
	$('.open_order').click(function(){
		$(this).parent().parent().next().slideToggle();
	})
	$('.all_open_order').click(function(){
		$('.con_list').slideToggle();
	})
	$('.news').click(function(){
    	$('.j_toshow').animate({"right":"0px"},800);
	});
	$('.close_btn').click(function(){
	    $('.j_toshow').animate({"right":"-330px"},800);
	});
	<!--日历调用-->
	// $('.datepicker').datepicker({
	// 	dateFormat: "yymmdd"
	// });
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

function setout(obj){
	setTimeout(function(){
		obj.fadeOut();	
	},2000)	
}
var orderid='';
function show_detail(obj){
	$('#status_detail').html('');
	$('#myModalLabel').html('单号：');
	var temp='';
	orderid='';
	$.get('<?php echo site_url('hotel/orders/order_status')?>',{
		oid:$(obj).attr('oid'),
		hotel:$(obj).attr('h')
	},function(data){
		orderid=data.order.orderid;
		if(data.after!=''){
			temp+='<select id="after_status">';
			$.each(data.after,function(i,n){
				if(i!=4)
					temp+='<option value="'+i+'">'+n+'</option>';		
			});
			temp+='</select>';
		}else{
			temp+=data.order.status_des;
			orderid='';
		}
		$('#status_detail').html(temp);
		$('#myModalLabel').append(data.order.orderid);
	},'json');
}
function slidesub(id){
	chose="[name='"+'weborder'+id+"']";
	if($(chose).css("display")=='table-row'){
		$(chose).css("display",'none');
	}
	else{
		$(chose).css("display",'table-row');
	}
}

$('#grid-btn-set').click(function(){
	var str = '<input type="hidden" name="<?php echo $this->security->get_csrf_token_name ();?>" value="<?php echo $this->security->get_csrf_hash ();?>" style="display:none;">';
	$.getJSON('<?php echo site_url("hotel/orders/get_cofigs?ctyp=ORDERS_STATUS_HOTEL")?>',function(data){
		$.each(data,function(k,v){
			str += '<div class="checkbox"><label><input type="checkbox" name="' + k + '"';
			if(v.must == 1){
				str += ' disabled checked ';
			}else if(v.choose == 1){
				str += ' checked ';
			}
			str += '>' + v.name + '</label></div>';
		});
		$('#setting_form').html(str);
	});

});
$('#set_btn_save').click(function(){
	$.post('<?php echo site_url("hotel/orders/save_cofigs?ctyp=ORDERS_STATUS_HOTEL")?>',$("#setting_form").serialize(),function(data){
		if(data == 'success'){
			window.location.reload();
		}else{
			alert('保存失败');
		}
	});
});

<!--改变订单状态-->
function change_status(obj){
	var sid = $(obj).attr('sid');
	var orderid = $(obj).attr('oid');
	var item_id = '';
	if($(obj).attr('iid')){
		item_id = $(obj).attr('iid')
	}
	if(orderid){
		$.get('<?php echo site_url('hotel/orders_ticket/update_order_status');?>',{
			oid:orderid,
			status:sid,
			item_id:item_id
		},function(data){
			if(data==1){
				alert('修改成功');
				location.reload();
			}else{
				alert('修改失败');
			}
		});
	}
}
</script>
</body>
</html>