<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
</head>
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
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 1271px;">
<div class="banner bg_fff p_0_20">
    <?php echo $breadcrumb_html; ?>
</div>
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>消息提醒设置</h1>
</section> -->
<!-- Main content -->

<section class="content">
<div class="row">
<style>
.input-group{margin-bottom:16px}
.switch{padding:2px 6px; background:#fafafa; font-size:20px;}
.checkbox label{ margin-right:8px; padding-right:8px; border-right:1px solid #e4e4e4;}
.box-body .col-xs-12{border-bottom:1px dashed #f1f1f1; padding:15px 0 0 0;}
.table td{ position:relative}
.table .diyselect{ position:absolute; width:100%; height:150px; background:#fff;z-index:999; top:100%; left:0; border:1px solid #e4e4e4; overflow-x:hidden; overflow-y:scroll}
.table .diyselect>*{ display:block; padding:2px 8px; overflow:hidden;}
.table .diyselect>p:hover{background:#69F; color:#fff}
.fa-edit:before{content: "";}
.fa-edit:after{content: "\f044"; margin-left:4px}
</style>
<div class="col-xs-12">
    <div class="box box-primary">
        <div class="box-body">
        	<!--  开发暂缓   
        	<div class="col-xs-12"><div class="form-group col-xs-4">
                <label>短信提醒 </label>
                <div class="input_list">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                          <button type="button" class="btn" ><i class="fa fa-toggle-off text-green"></i></button>
                        </span>
                        <input type="tel" class="form-control">
                        <input type="hidden" name="" value="">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger btn-flat" title="删除"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <button type="button" class="btn btn-success btn-flat" title="添加短信提醒"><i class="fa fa-plus"></i></button>
                </div>
            </div></div>
            
            <div class="col-xs-12"><div class="form-group col-xs-4">
                <label>传真提醒 </label>
                <div class="input_list">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                          <button type="button" class="btn" ><i class="fa fa-toggle-off text-green"></i></button>
                        </span>
                        <input type="tel" class="form-control">
                        <input type="hidden" name="" value="">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger btn-flat" title="删除"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <button type="button" class="btn btn-success btn-flat" title="添加短信提醒"><i class="fa fa-plus"></i></button>
                </div>
            </div></div>
            
            <div class="col-xs-12"><div class="form-group col-xs-4">
                <label>邮件提醒 </label>
                <div class="input_list">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                          <button type="button" class="btn" ><i class="fa fa-toggle-off text-green"></i></button>
                        </span>
                        <input type="tel" class="form-control">
                        <input type="hidden" name="" value="">
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-danger btn-flat" title="删除"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <button type="button" class="btn btn-success btn-flat" title="添加短信提醒"><i class="fa fa-plus"></i></button>
                </div>
            </div></div>
             -- end  ---><!---->
             <form action="<?php echo site_url('notify/notify/target');?>" method="get">
            <div class="col-xs-12"><div class="form-group col-xs-4">
                <label>弹窗提醒 </label>
                <button type="button" class="btn switch" ><i class="fa fa-toggle-off text-green <?php if($admin_config['is_popup']==1){ echo 'fa-toggle-on';}?>"></i></button> 
                <input type="hidden" value="<?php if($admin_config['is_popup']==1){ echo 'on';}else{
                	echo 'off';}?>" name="is_popup">
            </div></div>
            
            <div class="col-xs-12"><div class="form-group col-xs-4">
                <label>声音提醒 </label>
                <button type="button" class="btn switch" ><i class="fa fa-toggle-off text-green <?php if($admin_config['is_voice']==1){ echo 'fa-toggle-on';}?>"></i></button> 
                <input type="hidden" value="<?php if($admin_config['is_voice']==1){ echo 'on';}else{
                	echo 'off';}?>" name="is_voice">
            </div></div>
            
            <div class="col-xs-12"><div class="form-group col-xs-8">
                <label>微信提醒 </label> 
                <!-- <button type="button" class="btn switch" ><i class="fa fa-toggle-off text-green <?php if($admin_config['is_weixin']==1){ echo 'fa-toggle-on';}?>"></i></button> -->
                <input type="hidden" value="<?php if($admin_config['is_weixin']==1){ echo 'on';}else{
                	echo 'off';}?>" name="is_weixin">
                	<span style="margin-left:20px"> 
                	<!-- <button type="button" class="btn btn-primary btn-xs" onclick="window.location.href='<?php echo site_url("notify/notify/notify_wx");?>'">去设置</button> -->
					<a style="color:#009900;font-size:13px;text-decoration:underline;" href="<?php echo site_url('notify/notify/notify_wx');?>">去设置>></a> 
                	</span>
                <!-- <span style="margin-left:20px">
                   <button type="button" class="btn btn-primary btn-xs" id="filldata">查看成员</button>
                   <button type="button" class="btn btn-success btn-xs" id="qrapply">扫码登记</button>
                   <img id="qrimg" src="<?php echo site_url('notify/notify/apply_qr_code');?>" style="display:none;">
                </span>
                <div style="margin-top:20px;display:none" id="member">
                	<div style="padding:5px 15px; background:#f1f1f1;">成员明细</div>
                	<div id="membertips" style="display:none"><i class="fa fa-spinner text-green"></i>正在加载</div>
                	<div id="memberhandle" style="padding:5px 15px; background:#CFF1AD; margin-bottom:10px;display:none">批量操作
                        <span style="margin-left:20px">
                           <button type="button" class="btn btn-danger btn-xs" id="pass">全部审核通过</button>
                           <button type="button" class="btn btn-default btn-xs" id="unbind">全部解除绑定</button>
                        </span>
                    </div>
                	<table id="membertable" class="table table-bordered table-striped"> --> <!-- ajax填充数据 --><!-- </table>
                </div> -->
            </div></div>
            
            <!-- <div class="form-group col-xs-10" style="padding-top:20px">
              <label>提醒内容</label>
              <div class="checkbox">   
               	<?php foreach ($check_config as $kn => $vn) {?>
               	<label><input <?php echo $vn['label'];?> type="checkbox" name="<?php echo $vn['name'];?>" value="<?php echo $kn;?>" <?php if(in_array($kn, explode(',',$admin_config['wx_notify']))){ echo 'checked';}?> > <?php echo $vn['text'];?></label> 
               	<?php }?>          
              </div>
            </div> -->
            
            <div class="form-group col-xs-8">
               <button type="button" class="btn btn-info btn-flat" onclick="submit()">保存</button>
            </div>
            </form>
        </div>
    </div>
</div> 
</div>
</section>


<!-- 弹窗样式 -->
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

<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
function hotel_list(dom){
	$('.diyselect').remove();
	var html = '<div class="diyselect"><input placeholder="输入酒店名或关键字">';
	<?php foreach ($hotels as $kh => $vh) {?>
		html += '<p data-id="<?php echo $vh['hotel_id'];?>"><?php echo $vh['name'];?></p>';
	<?php }?>
	html += '</div>';
	dom.after(html);
	$('.diyselect input').bind('input properchange',function(){
		var val = $(this).val()
		if( val!='')
			$('.diyselect p').each(function(index, element) {
				if ($(this).html().indexOf(val)>=0) $(this).show();
				else $(this).hide();
			});
		else $('.diyselect p').show();
	})
	$('.diyselect p').click(function(){
		//修改绑定酒店
		var hid = $(this).attr('data-id');
		var rid = dom.attr('data-id');
		var hname = $(this).html();
		$('.diyselect').remove();
		$.ajax({
			url:"<?php echo site_url('notify/notify/ajax_edit_hotel').'?hid=';?>"+hid+'&rid='+rid,
			success:function(m){
				if(m == 'ok'){
					dom.html(hname);
					//dom.parents('tr'); 
				}else{
					alert('操作失败');
				}
			},
			error:function(){
				alert('操作失败');
			}
		});
	})
}
function toPermit(rid,element){
	var ret = false;
	var tmparry = ['解除绑定','审核通过'];
	var per;
	if(element.html()==tmparry[0]){
		per = 2;
	}else{
		per = 1;
	}
	$.ajax({
		url:"<?php echo site_url('notify/notify/ajax_to_permit').'?per=';?>"+per+'&rid='+rid,
		success:function(m){
			if(m=='ok'){
				ret = true;
				if(element.html()==tmparry[0]){
					element.html(tmparry[1])
					element.addClass('text-green');
					element.parents('tr').find('.text-default').html('未绑定').addClass('text-info');
				}else{
					element.html(tmparry[0])
					element.removeClass('text-green');
					element.parents('tr').find('.text-default').html('已绑定').removeClass('text-info');
				};
			}else{
				alert('操作失败');
			}
		},
		error:function(){
			alert('操作失败');
		}
	});
	return ret;
}
//per 1.全部通过，2.全部解绑
function toAllPermit(per){
	$.ajax({
		url:"<?php echo site_url('notify/notify/ajax_to_permit').'?per=';?>"+per+'&rid=all',
		success:function(m){
			if(m=='ok'){
				if(per==1){
					alert('已通过所有审核');
					$('#membertable .text-danger').html('解除绑定').removeClass('text-green');
					$('#membertable .text-default').html('已绑定').removeClass('text-info');
				}else if(per==2){
					alert('已解除所有绑定');
					$('#membertable .text-danger').html('审核通过').addClass('text-green');
					$('#membertable .text-default').html('未绑定').addClass('text-info');
				}
			}else{
				alert('操作失败');
			}
		},
		error:function(){
			alert('操作失败');
		}
	});
}
var isfirst=false;
	$('#filldata').click(function () {
		$('#membertips').show(); 
		$('#member').show();
		$('#membertable').html('');
		var html = '<thead><tr><th>姓名</th><th>酒店名称</th><th>状态</th><th>操作</th></tr></thead>';
		$.getJSON("<?php echo site_url('notify/notify/ajax_get_reg');?>",function(regs){	
				html +='<tbody>';
				$.each(regs,function(index,content){
					if(content.status==1){
						html +='<tr><td>'+content.name+'</td><td><span hname class="fa fa-edit" data-id='+content.id+'>'+content.hname+'</span></td><td><span class="text-default">已绑定</span></td><td><span handle class="text-danger" data-id='+content.id+'>解除绑定</span></td></tr>';
					}else if(content.status==2){
						html +='<tr><td>'+content.name+'</td><td><span hname class="fa fa-edit" data-id='+content.id+'>'+content.hname+'</span></td><td><span class="text-default text-info">未绑定</span></td><td><span handle class="text-danger text-green" data-id='+content.id+'>审核通过</span></td></tr>';
					}
				});
				html +='</tbody>';
			
			$('#membertable').html(html);
			if(!isfirst){
				$('#membertable').DataTable({
				  "aaSorting": [[2,"desc"]],//默认排序
				  "paging": true,
				  "lengthChange": true,
				  "searching": true,
				  "info": false,
				  "autoWidth": false,
				  "oLanguage":{
					  "oPaginate":{ "sFirst": "页首","sPrevious": "上一页","sNext": "下一页","sLast": "页尾"},
					  "sLengthMenu": "每页显示 _MENU_ 条数据",
					  "sEmptyTable": "暂无相关数据"
				  },
				  "bRetrieve": true
				});
				$('#membertable').on('click','[handle]',function(){
					toPermit($(this).attr('data-id'),$(this));
				});
				$('#membertable').on('click','[hname]',function(){
					hotel_list($(this));
				});
			}
			$('#memberhandle').show(); //批量操作按钮
			$('#membertips').hide();
		});
	});
	$('#pass').click(function(){
		if(window.confirm('是否将通过所有审核? 请确认当前操作!')){
			toAllPermit(1);
		}
	})
	
	$('#unbind').click(function(){
		if(window.confirm('是否将解除所有绑定? 请确认当前操作!')){
			toAllPermit(2);
		}
	})


	$('.switch').click(function(){
		var value = '';
		if( $('i',this).hasClass('fa-toggle-on')){
			$('i',this).removeClass('fa-toggle-on');
			value = 'off';
		}
		else{
			$('i',this).addClass('fa-toggle-on');
			value = 'on';
		}
		$(this).siblings('input').val(value);
	})
	$('.checkbox [checkall]').click(function(){
		var checked=$(this).get(0).checked;
		$(this).parent().siblings().each(function(index, element) {
           $('input',this).get(0).checked=checked;
        });
	})
	$('.checkbox [single]').click(function(){
		var checked= $(this).get(0).checked;
		if(checked){
			$(this).parent().siblings().each(function(index, element) {
				var single =$('input',this).attr('single');
				var bool = $('input',this).get(0).checked ; 
				if(single!=undefined&&!bool)checked=bool;
			});
		}
		$('input[checkall]').get(0).checked=checked;
	})
	$('#qrapply').click(function(){
		if($('#qrimg').css('display')=='none'){
			$('#qrimg').css('display','block');
		}else{
			$('#qrimg').css('display','none');
		}
	});
</script>
</body>
</html>
