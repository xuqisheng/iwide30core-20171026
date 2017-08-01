<!-- DataTables -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/js/demo.js"></script>
<script type="text/javascript">

//display the top level menu item.
$('#L2_active_item1').parent().parent().addClass('active');
$('#L3_active_item1').parent().parent().addClass('active');
$('#L3_active_item1').parent().parent().parent().parent().addClass('active');
if( document.getElementById('L2_active_item1')==null && document.getElementById('L3_active_item1')==null ){
	$('#L2_active_item2').parent().parent().addClass('active');
	$('#L3_active_item2').parent().parent().addClass('active');
	$('#L3_active_item2').parent().parent().parent().parent().addClass('active');
}

//TODO 加载页面顶部的通知公告信息、最新业务通知
var tips={};
var new_tips=0;
var checked=0;
var t=Date.parse(new Date())+'';
var option = {
	tips :'您有新的订单',  //提示文字
	left :'暂不处理',   // 左按钮文字 默认为'取消'
	right:'前往查看'  // 右按钮文字 默认为'确定'
}
var tit = document.title;
var next_data = [];
var next_data_n = 0;
t =t.substr(0,10);
if(getCookie('starttime')){
	var sleepnums = 60-(t-getCookie('starttime'))%60;
	setTimeout('secondStep()',sleepnums*1000);
}else{
	secondStep();
}
function secondStep() {
	check_new_order();
	setInterval('check_new_order()',60000);
}
function getCookie(objName){//获取指定名称的cookie的值 
	var arrStr = document.cookie.split('; '); 
	for(var i = 0;i < arrStr.length;i ++){ 
		var temp = arrStr[i].split('='); 
		if(temp[0] == objName) return unescape(temp[1]); 
	}
}

<?php if(  $this->session->flashdata('is_arreared') ){ ?>
//检查是否欠费
var arrear_option = {
	tips :'<?php echo  $this->session->flashdata('arrear_tips'); ?>',  //提示文字
	left :'好的',   // 左按钮文字 默认为'取消'
	right:'甜甜是逗比'  // 右按钮文字 默认为'确定'
}
diylayer(arrear_option,'',10,0);
<?php }?>
function notice(new_tips,tips){
	$('#top_alter_order> ul li.header ').html('');
	if(new_tips>0){
		$('#top_alter_order> a ').append('<span class="label label-danger">'+new_tips+'</span>');
		$('#top_alter_order').attr('data-placement','left').attr('data-content','您有 '+new_tips+' 条提醒信息').popover('show');
		if(tips['room']!=undefined){
			$('#top_alter_order> ul li.header ').append("<a href='javascript:to_check(1);'>您有新的"+tips['room']+"条订单！</a>");
		}
		//$('#top_alter_order').attr('title', '您有新的订单！').attr('data-placement','left').tooltip('show');
		$('<audio id="top_alter_order_ling"><source src="<?php echo base_url( FD_PUBLIC. '/'. $tpl. '/media/notify_cn.mp3'); ?>" type="audio/mpeg" ><source src="<?php echo base_url( FD_PUBLIC. '/'. $tpl. '/media/notify.mp3'); ?>" type="audio/mpeg"><source src="<?php echo base_url( FD_PUBLIC. '/'. $tpl. '/media/notify.wav'); ?>" type="audio/wav"></audio>').appendTo('body');
		$('#top_alter_order_ling')[0].play();
		$('#top_alter_order> ul li.footer ').hide();
	} else {
		$('#top_alter_order> a span').remove();
		$('#top_alter_order> ul li.header ').html('您暂时没有提醒信息');
		$('#top_alter_order> ul li.footer ').hide();
		$('#top_alter_order> ul li ul ').html('');
		$('#top_alter_order').popover('hide');
	}
}
function to_check(type){
	checked=0;
	t=Date.parse(new Date())+'';
	t=t.substr(0,10);
	tips={};
	new_tips=0;
	$('#top_alter_order> a span').remove();
	$('#top_alter_order> ul li.header ').html('您暂时没有提醒信息');
	$('#top_alter_order> ul li.footer ').hide();
	$('#top_alter_order> ul li ul ').html('');
	$('#top_alter_order').popover('hide');
	$('#top_alter_order').attr('data-placement','left').attr('data-content','您暂时没有提醒信息').popover('hide');
	if(type==1){
		link="<?php echo site_url('hotel/orders/index')?>";
		window.open(link);    
	}
}

function reset_nums(r){
	$.getJSON('<?php echo site_url("notify/notify/ajax_query_order")?>',{
			r:r,
		},function(m){
			if(m=1){

			}
		},'json');
}

function check_new_order(){
	if(checked==0){
		if(!getCookie('starttime')){
			document.cookie='starttime='+t+'; path=/';
		}
		$.getJSON('<?php echo site_url("notify/notify/ajax_query_order")?>',{
			t:t
		},function(m){
			var data = {};
			next_data_n = 0;
			if(m.checkout.total>0){
				data = m.checkout;
				next_data[1] = m.order;
                next_data[2] = m.roomseriver_new_order;
                next_data[3] = m.roomseriver_order_reminder;
			}else if(m.order.total>0){
				data = m.order;
                next_data[1] = m.roomseriver_new_order;
                next_data[2] = m.roomseriver_order_reminder;
			}
            else if(m.roomseriver_new_order.total>0)
            {//快乐送新订单
                data = m.roomseriver_new_order;
                next_data[1] = m.eatin_new_order;
            }
            else if(m.eatin_new_order.total>0)
            {//快乐送新订单
                data = m.eatin_new_order;
                next_data[1] = m.takeaway_new_order;
            }
            else if(m.takeaway_new_order.total>0)
            {//快乐送新订单
                data = m.takeaway_new_order;
                next_data[1] = m.ticket_new_order;
            }
            else if(m.ticket_new_order.total>0)
            {//快乐送新订单
                data = m.ticket_new_order;
                next_data[1] = m.roomseriver_order_reminder;
            }
            else if(m.roomseriver_order_reminder.total>0)
            {
                data = m.roomseriver_order_reminder;
                next_data[1] = m.eatin_order_reminder;
            }
            else if(m.eatin_order_reminder.total>0)
            {
                data = m.eatin_order_reminder;
                next_data[1] = m.takeaway_order_reminder;
            }
            else if(m.takeaway_order_reminder.total>0)
            {
                data = m.takeaway_order_reminder;
                next_data[1] = m.ticket_order_reminder;
            }else if(m.ticket_order_reminder.total>0){//快乐送催单
                data = m.ticket_order_reminder;
                next_data[1] = m.soma_order;
            }else{
            	data = m.soma_order;//商城新订单
            }
			if(data.status==1&&data.total>0&&data.is_voice==1){
				tips['room']=data.total;
				new_tips+=1;
				checked=1;
				notice(new_tips,tips);
			}else{
				tips['room']=0;
				notice(new_tips,tips);
			}
			if(data.status==1&&data.total>0&&data.is_popup==1){
				if(data.type == 'checkout'){
					document.title = '【您有一个新的退房申请！！】';
					option.tips = '<span style="color:blue;">您有新的退房申请</span>';
					diylayer(option,"<?php echo site_url('hotel/checkout/index');?>",0,2,1);
				}else if(data.type == 'order'){
					document.title = '【您有一个新订单！！】';
                    option.tips = '<span style="color:blue;">您有一个新订单</span>';
					diylayer(option,"<?php echo site_url('hotel/orders/index');?>",0,1,1);
				}else if(data.type == 'roomseriver_new_order'){
                    document.title = '【您有一个新的快乐送订单！！】';
                    option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
                    diylayer(option,"<?php echo site_url('roomservice/orders/index');?>",0,3,1);
                }else if(data.type == 'eatin_new_order'){
                    document.title = '【您有一个新的快乐送订单！！】';
                    option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
                    diylayer(option,"<?php echo site_url('eat-in/orders/index');?>",0,33,1);
                }
                else if(data.type == 'takeaway_new_order'){
                    document.title = '【您有一个新的快乐送订单！！】';
                    option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
                    diylayer(option,"<?php echo site_url('take-away/orders/index');?>",0,333,1);
                }
                else if(data.type == 'ticket_new_order'){
                    document.title = '【您有一个新的快乐送订单！！】';
                    option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
                    diylayer(option,"<?php echo site_url('ticket/orders/index');?>",0,3333,1);
                }
                else if(data.type == 'roomseriver_order_reminder'){
                    document.title = '【您有一个快乐送催单！！】';
                    option.tips = '<span style="color:#23527c;">催单啦！您有一个新的快乐送催单</span>';
                    diylayer(option,"<?php echo site_url('roomservice/orders/index');?>",0,4,1);
                }
                else if(data.type == 'eatin_order_reminder'){
                    document.title = '【您有一个快乐送催单！！】';
                    option.tips = '<span style="color:#23527c;">催单啦！您有一个新的快乐送催单</span>';
                    diylayer(option,"<?php echo site_url('eat-in/orders/index');?>",0,4,1);
                }
                else if(data.type == 'takeaway_order_reminder'){
                    document.title = '【您有一个快乐送催单！！】';
                    option.tips = '<span style="color:#23527c;">催单啦！您有一个新的快乐送催单</span>';
                    diylayer(option,"<?php echo site_url('take-away/orders/index');?>",0,4,1);
                }
                else if(data.type == 'ticket_order_reminder'){
                    document.title = '【您有一个快乐送催单！！】';
                    option.tips = '<span style="color:#23527c;">催单啦！您有一个新的快乐送催单</span>';
                    diylayer(option,"<?php echo site_url('ticket/orders/index');?>",0,4,1);
                }
                else if(data.type == 'soma_order'){
                    document.title = '【您有一个商城新订单！！】';
                    option.tips = '<span style="color:blue;">您有一个新的商城订单</span>';
                    diylayer(option,"<?php echo site_url('soma/sales_order/order_list');?>",0,5555,1);
                }
			}
		},'json');
	}
}

// 触发下一个弹窗提醒
function next_alert(data,t_index){
	if(data != '' && data.status==1&&data.total>0&&data.is_popup==1){
		setTimeout(function(){
		if(data.type == 'checkout'){
			document.title = '【您有一个新的退房申请！！】';
			option.tips = '<span style="color:blue;">您有新的退房申请</span>';
			diylayer(option,"<?php echo site_url('hotel/checkout/index');?>",0,2,t_index+1);
		}else if(data.type == 'order'){
            document.title = '【您有一个新订单！！】';
            option.tips = '您有新的订单';
            diylayer(option,"<?php echo site_url('hotel/orders/index');?>",0,1,t_index+1);
        }
        else if(data.type == 'roomseriver_new_order'){
            document.title = '【您有一个新的快乐送订单！！】';
            option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
            diylayer(option,"<?php echo site_url('roomservice/orders/index');?>",0,3,t_index+1);
        }else if(data.type == 'eatin_new_order'){
            document.title = '【您有一个新的快乐送订单！！】';
            option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
            diylayer(option,"<?php echo site_url('eat-in/orders/index');?>",0,33,t_index+1);
        }else if(data.type == 'takeaway_new_order'){
            document.title = '【您有一个新的快乐送订单！！】';
            option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
            diylayer(option,"<?php echo site_url('take-away/orders/index');?>",0,333,t_index+1);
        }
        else if(data.type == 'ticket_new_order'){
            document.title = '【您有一个新的快乐送订单！！】';
            option.tips = '<span style="color:#ff9900;">您有一个新的快乐送订单</span>';
            diylayer(option,"<?php echo site_url('ticket/orders/index');?>",0,3333,t_index+1);
        }

        else if(data.type == 'roomseriver_order_reminder'){
            document.title = '【您有一个快乐送催单！！】';
            option.tips = '<span style="color:#23527c;">您有一个快乐送催单</span>';
            diylayer(option,"<?php echo site_url('roomservice/orders/index');?>",0,4,t_index+1);
        }
        else if(data.type == 'eatin_order_reminder'){
            document.title = '【您有一个快乐送催单！！】';
            option.tips = '<span style="color:#23527c;">您有一个快乐送催单</span>';
            diylayer(option,"<?php echo site_url('eat-in/orders/index');?>",0,44,t_index+1);
        }
        else if(data.type == 'takeaway_order_reminder'){
            document.title = '【您有一个快乐送催单！！】';
            option.tips = '<span style="color:#23527c;">您有一个快乐送催单</span>';
            diylayer(option,"<?php echo site_url('take-away/orders/index');?>",0,444,t_index+1);
        }
        else if(data.type == 'ticket_order_reminder'){
            document.title = '【您有一个快乐送催单！！】';
            option.tips = '<span style="color:#23527c;">您有一个快乐送催单</span>';
            diylayer(option,"<?php echo site_url('ticket/orders/index');?>",0,4444,t_index+1);
        }
        else if(data.type == 'soma_order'){
            document.title = '【您有一个新的商城订单！！】';
            option.tips = '<span style="color:blue;">您有一个新的商城订单</span>';
            diylayer(option,"<?php echo site_url('soma/sales_order/order_list');?>",0,5555,t_index+1);
        }
		next_data_n = 1;
		},
		500);
	}
}


/*
// diylayer(option,handle)方法使用明细;
//参数  - 使用方法1
option = {
	tips :'您有新的订单',  //提示文字
	left :'暂不处理',   // 左按钮文字 默认为'取消'
	right:'前往查看'  // 右按钮文字 默认为'确定'
}

//调用
diylayer(option,function(){console.log('回调')});

//参数 - 使用方法2
option = {
	tips :'您有新的订单',  //提示文字
	left :'暂不处理',   // 左按钮文字 默认为'取消'
	right:'前往查看'  // 右按钮文字 默认为'确定'
}
//调用
diylayer(option,'http://www.baidu.com');
*/

function diylayer(option,handle,opt_delay_time,t_type,t_index){
	var dom='';
	var attr='';
	if( option.tips =='' || option.tips ==undefined ) option.tips='提示';
	if( option.left =='' || option.left ==undefined ) option.left='取消';
	if( option.right =='' || option.right ==undefined ) option.right='确定';
	if( handle==''|| handle==undefined||typeof(handle)=='function'){ dom = 'div';}
	else{dom='a';attr='href="'+ handle+'"';}
	var _h = $(window).height();
	var html='<style>.diylayer{position:fixed;z-index:999999;width:100%;height:100%;min-height:'+_h+'px;background:rgba(0,0,0,0.5);top:0;left:0;text-align:center}'
			+'.alertbox{margin:'+(_h-150)/2+'px auto;width:500px;padding:20px;background:rgba(255,255,255,0.9);border-radius:7px;overflow:hidden}'
			+'.alertbox .btn{margin:0 15px}</style>';
	html+='<div class="diylayer"><div class="alertbox"><div style="padding:20px;" id="opt_tips">'+option.tips+'</div>';
	html+='<div id="diylayer_opt" ';
	if(opt_delay_time>0){
		html+=' style="display:none" ';
	}
	html+=' >';
	if(option.left!='甜甜是逗比')
		html+='<div class="btn btn-default">'+option.left+'</div>';
	if(option.right!='甜甜是逗比')
		html+='<'+dom+' class="btn btn-success" '+attr+' target="_blank">'+option.right+'</'+dom+'>';
	html+='</div>';
	html+='</div></div>';
	$('body').append(html);
	if(opt_delay_time>0){
		var i=1;
		var tips=$('#opt_tips').html();
		var opt_timeout=function(){
				$('#opt_tips').html(tips+" <br />("+i+' s)');
				if(i==opt_delay_time+1){
					$('#diylayer_opt').show();
					$('#opt_tips').html(tips);
					return false;
				}
				setTimeout(opt_timeout,1000);
				i++;
			}
		opt_timeout();
	}
    var this_data = '';
    if(typeof next_data[t_index] != 'undefined'){
        this_data = next_data[t_index];
    }
	$('.alertbox .btn').click(function(){reset_nums(t_type);to_check(0);$('.diylayer').remove();next_alert(this_data,t_index);document.title=tit});
	if(typeof(handle)=='function') $('.alertbox .btn-success').click(handle);
}
</script>
