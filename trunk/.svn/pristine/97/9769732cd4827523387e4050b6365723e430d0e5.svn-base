<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
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

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle">新增/编辑店铺</header>
    <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<?php //$pk= $model->table_primary_key(); ?>
<?php if(isset($id)){?>
<?php echo form_open('roomservice/shop/edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }else{?>
<?php echo form_open('roomservice/shop/add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }?>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">基本信息</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div>酒店</div>
            <div class="select_input flexgrow">

                <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($posts['hotel_id'])&&isset($hotel[$posts['hotel_id']])?$hotel[$posts['hotel_id']]:''?>"></div>
				<?php if(!empty($hotel) && !isset($posts['hotel_id'])){ ?>
                <div class="silde_layer bd" id="el_hotel">
					<?php foreach($hotel as $hk=>$hv){?>
                    <div data="<?php echo $hk?>"><?php echo $hv?></div>
                    <?php }?>
                </div>
				<?php }?>
                <input type="hidden" name="hotel_id" value="<?php echo isset($posts['hotel_id'])?$posts['hotel_id']:''?>">
            </div>
        </div>
        <div>
            <div>店铺名称</div>
            <div class="input flexgrow"><input name="shop_name" value="<?php echo isset($posts['shop_name'])?$posts['shop_name']:''?>"></div>
        </div>
        <div>
            <div>售卖类型</div>
            <div class="flex flexcenter" style="max-width: 800px;!important">
                <?php foreach($sale_type as $sk=>$sv){?>
                <label class="check"><input name="sale_type" type="radio" value="<?php echo $sk?>" <?php if(!empty($posts['sale_type'])&&$posts['sale_type']==$sk ||(!isset($id) && $sk==1)){echo "checked='checked'";}?> onclick="radiochange($(this).val())" /><span class="diyradio"><tt></tt></span><?php echo $sv?></label>
                <?php }?>
                <div class="input" <?php if(empty($posts['sale_type'])||$posts['sale_type']!=3){?>style="display: none;"<?php }?> id="sale_around">
                    <input name="sale_around" placeholder="请输入配送范围" value="<?php echo !empty($posts['sale_around'])?$posts['sale_around']:''?>"><span>KM</span>
                </div>
                <div class="input" <?php if(empty($posts['sale_type'])||$posts['sale_type']!=3){?>style="margin-left: 10px;display: none;"<?php }?> id="sale_dispatching">
                    <input name="sale_dispatching" placeholder="请输入配送起价" value="<?php echo !empty($posts['sale_dispatching'])?$posts['sale_dispatching']:''?>"><span>元</span>
                </div>
            </div>
        </div>
        <div id="shipping_type" <?php if(empty($posts['sale_type'])||$posts['sale_type']!=3){?>style="display: none;"<?php }?>>
            <div>配送方式</div>
            <div class="flex flexcenter">
                <label class="check"><input name="shipping_type" type="radio" value="1" <?php echo !isset($posts['shipping_type'])||$posts['shipping_type']==1?' checked ':''?> /><span class="diyradio"><tt></tt></span>酒店配送</label>
                <label class="check"><input name="shipping_type" type="radio" value="2" <?php echo isset($posts['shipping_type'])&&$posts['shipping_type']==2?' checked ':''?> /><span class="diyradio"><tt></tt></span>达达配送</label>
            </div>
        </div>

        <div id="shipping_cost" <?php if($posts['sale_type'] != 3){?>style="display: none;"<?php }?>>
            <div>配送费</div>
            <div class="input"><input name="shipping_cost" value="<?php echo isset($posts['shipping_cost'])?$posts['shipping_cost']:''?>"><span>元</span></div>
        </div>

        <div id="identify_type" <?php if(isset($posts['sale_type']) && $posts['sale_type']!=1){?>style="margin-left: 10px;display: none;"<?php }?>>
            <div>房号识别</div>
            <div class="flex flexcenter">
                <label class="check"><input name="identify_type" type="radio" value="1" <?php echo !isset($posts['identify_type'])||$posts['identify_type']==1?' checked ':''?> /><span class="diyradio"><tt></tt></span>二维码</label>
                <label class="check"><input name="identify_type" type="radio" value="2" <?php echo isset($posts['identify_type'])&&$posts['identify_type']==2?' checked ':''?> /><span class="diyradio"><tt></tt></span>用户输入</label>
            </div>
        </div>
        <div id="cover_charge">
            <div>服务费</div>
            <div class="input"><input name="cover_charge" value="<?php echo isset($posts['cover_charge'])?$posts['cover_charge']:''?>"><span>%</span></div>
        </div>
    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#4caf50">店铺属性</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div>支付方式</div>
            <div class="flex flexcenter" style="max-width:100%">
                <?php foreach($pay_type as $pk=>$pv){?>
                <label class="check"><input name="pay_type[]" type="checkbox" value="<?php echo $pk?>" <?php echo isset($posts['pay_type'])&&in_array($pk,$posts['pay_type'])?' checked ':''?> /><span class="diyradio"><tt></tt></span><?php echo $pv;?></label>
                <?php }?>
            </div>
        </div>
        <div>
            <div>开店时间</div>
            <div class="input flexgrow"><input placeholder="格式:00:00"  name="start_time" value="<?php echo isset($posts['start_time'])?$posts['start_time']:''?>"></div>
        </div>
        <div>
            <div>打烊时间</div>
            <div class="input flexgrow"><input placeholder="格式:00:00"  name="end_time" value="<?php echo isset($posts['end_time'])?$posts['end_time']:''?>"></div>
        </div>
        <div>
            <div>营业周期</div>
            <div class="flex flexcenter" style="max-width:100%">
				<?php $day = array(1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日')?>
                <?php foreach($day as $k=>$v){?>
                <label class="check"><input name="sale_days[]" type="checkbox" value="<?php echo $k?>" <?php echo isset($posts['sale_days'])&&in_array($k,$posts['sale_days'])?' checked ':''?> /><span class="diyradio"><tt></tt></span><?php echo $v;?></label>
                <?php }?>
            </div>
        </div>
        <div>
            <div>等待时间</div>
            <div class="input"><input name="wait_time" value="<?php echo isset($posts['wait_time'])?$posts['wait_time']:''?>"><span>分钟</span></div>
            <label class="check"><input name="wait_status" type="checkbox" value="1" <?php echo isset($posts['wait_status']) && $posts['wait_status']==1 ?' checked ':''?> /><span class="diyradio"><tt></tt></span>前端不显示</label>
        </div>
        
        <div>
            <div>店铺状态</div>
            <div class="flex flexcenter">
                <label class="check"><input name="status" type="radio" value="1" <?php echo !isset($posts['status'])||$posts['status']==1?' checked ':''?> /><span class="diyradio"><tt></tt></span>启用</label>
                <label class="check"><input name="status" type="radio" value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' checked ':''?> /><span class="diyradio"><tt></tt></span>不启用</label>
            </div>
        </div>
    </div>
</div>

<div class="whitetable">
    <div>
        <span style="border-color:#ebe814">其他设置</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div>模板消息通知</div>
            <div class="select_input flexgrow">
                <div class="input"><input placeholder="搜索或下拉选择" id="search_hotel" value="<?php if (!empty($show_saler_name)){echo $show_saler_name;}else{echo '';}?>"></div>

                <div class="silde_layer bd" id="drowdown">
                    <?php if(!empty($salers)){?>
                    <?php foreach ($salers as $k => $v):?>
                    <div data="<?=$k?>" multi="true" class="<?php echo !empty($posts['msgsaler'])&&(in_array($k,explode(',',$posts['msgsaler'])))?'color_main':''?>"><?=$v?></div>
                    <?php endforeach;?>
                    <?php }?>
                </div>

                <input type="hidden" name="msgsaler" value="<?php echo isset($posts['msgsaler'])?$posts['msgsaler']:''?>">
            </div>
        </div>

        <div>
            <div>优惠类型</div>
            <div class="flex flexcenter">
            <?php foreach($discount_type as $dk=>$dv){?>
            <label onclick="select_discount(<?php echo $dk?>)" class="check"><input name="discount_type" type="radio" value="<?php echo $dk?>" <?php echo isset($posts['discount_type'])&&$posts['discount_type']==$dk?' checked ':''?> <?php if(!isset($id)&&$dk==0){echo 'checked';}?> /><span class="diyradio"><tt></tt></span><?php echo $dv;?></label>
            <?php }?>
               
            </div>
        </div>
        <div id="discount_type"></div>
        
        <div>
            <div>优惠开始时间</div>
            <div class="input flexgrow"><input class="datepicker" name="discount_start_time" value="<?php echo isset($posts['discount_start_time'])&&$posts['discount_start_time']!='0000-00-00 00:00:00'?$posts['discount_start_time']:''?>"></div>
        </div>
        <div>
            <div>优惠结束时间</div>
            <div class="input flexgrow"><input class="datepicker" name="discount_end_time" value="<?php echo isset($posts['discount_end_time'])&&$posts['discount_end_time']!='0000-00-00 00:00:00'?$posts['discount_end_time']:''?>"></div>
        </div>
    </div>
</div>
        <div class="bg_fff bd center pad10">
			<!--<button type="reset" class="bg_key maright button spaced">清空配置</button>-->
        	<button class="bg_main button spaced" type="submit" id="set_btn_save">保存配置</button>
        </div>
                    <!--<div class="form-group  has-feedback">
                        <label for="el_gs_name" class="col-sm-2 control-label">优惠状态</label>
                        <div class="col-sm-8 form-inline">
                            <select name="status" class="form-control">
                                <option value="1" <?php /*echo isset($posts['discount_status'])&&$posts['discount_status']==1?' selected ':''*/?>>启用</option>
                                <option value="0" <?php /*echo isset($posts['discount_status'])&&$posts['discount_status']==0?' selected ':''*/?>>不启用</option>
                            </select>
                        </div>
                    </div>-->
			<?php echo form_close() ?>
    </section>
</div><!-- /.content-wrapper -->
			</div>
            </div>
            </div>
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
</body>
</html>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
function sildeClick(){
	var html='', val = '';
	if($(this).attr('multi')!=undefined&&$(this).attr('multi')=='true'){
		$(this).toggleClass('color_main');
		html = [];
		val  = [];
		$(this).parent().find('.color_main').each(function() {
            html.push($(this).html());
			val.push($(this).attr('data'));
        });
	}else{
		html=$(this).html();
		val =$(this).attr('data');
	}
		$(this).parent().siblings().find('input').val(html.toString());
		$(this).parent().siblings('input').val(val.toString());
}
$('.silde_layer>*').bind('click',sildeClick);

$('.select_input input').bind('input propertychange',function(){
	var _this = $(this).parent().siblings('.silde_layer').find('div');
	var val = $(this).val();
	if(val==''){
		_this.show();
	}else{
		_this.each(function(){
			if($(this).html().indexOf(val)>=0){
				$(this).show()
			}else{
				$(this).hide();
			}
		});
	}
});
$(".datepicker").datetimepicker({
	format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
});

$('#el_hotel div').click(function(){
	$('#drowdown').empty();
	$.post('/index.php/roomservice/shop/get_saler_info',{
		'hotel_id':$(this).attr('data'),
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	},function(data){
		if(data.errcode==0){
			var array=$('input[name=msgsaler]').val();
			if(array!='')
				array= array.split(',');
			for(i in data.res ){
            	var html = '';
                html = $('<div multi="true" data="'+data.res[i].qrcode_id+'">'+data.res[i].name+'</div>');
				for( var j = 0;j<array.length;j++){
					if( data.res[i].qrcode_id ==array[j])
						html.addClass('color_main');
				}
				html.get(0).onclick=sildeClick;
            	$('#drowdown').append(html);
			}
		}
		else{
			alert('通知,'+data.msg);
		}

	},'json')
});
$(document).ready(function(){
	select_discount(<?php echo !empty($posts['discount_type'])?$posts['discount_type']:0?>);
});
    function radiochange(change){
        if(change==3){
            $('#sale_around').show();
            $('#sale_dispatching').show();
            $('#shipping_type').show();

            var shipping_type_val = $('input:radio[name="shipping_type"]:checked').val();
            $('#shipping_cost').show();
        }else{
            $('#sale_around').hide();
            $('#sale_dispatching').hide();
            $('#shipping_type').hide();
            $('#shipping_cost').hide();
        }

        if (change==1)
        {
            $('#identify_type').show();
        }
        else
        {
            $('#identify_type').hide();
        }
    }

    //配送方式
/*
    $('input:radio[name="shipping_type"]').click(function()
    {
        if ( $(this).val() == 2 ||  $(this).val() == 1)
        {
            $('#shipping_cost').show();
        }
        else
        {
            $('#shipping_cost').hide();
        }
    });
*/


    function select_discount(type){
		var html = '';
        if(type == 1 || type == 2){
        html = '<div>优惠规则</div><div class="flex flexcenter">'
			   +'<span class="maright">满</span>'
			   +'<div class="input maright"><input name="config[]" value="<?php echo isset($posts['discount_config'][0])?$posts['discount_config'][0]:''?>"><span>元</span></div>'
			   +'<span class="maright">减</span>'
			   +'<div class="input maright"><input name="config[]"  value="<?php echo isset($posts['discount_config'][1])?$posts['discount_config'][1]:''?>"><span>元</span></div></div>';
        }else if(type == 3){
        html = '<div>优惠规则</div><div class="input"><input name="config[]"  value="<?php echo isset($posts['discount_config'][0])?$posts['discount_config'][0]:''?>"><span>折</span></div>';
			
        }else if(type == 4){
        html = '<div>优惠规则</div><div class="flex flexcenter">'
			   +'<span class="maright">满</span>'
			   +'<div class="input maright"><input name="config[]" style="width:75px;" value="<?php echo isset($posts['discount_config'][0])?$posts['discount_config'][0]:''?>"><span>元</span></div>'
			   +'<span class="maright">减</span>'
			   +'<div class="input maright"><input name="config[]" style="width:75px;" value="<?php echo isset($posts['discount_config'][1])?$posts['discount_config'][1]:''?>"><span>%</span></div>'
			   +'<span class="maright">-</span>'
			   +'<div class="input maright"><input name="config[]" style="width:75px;" value="<?php echo isset($posts['discount_config'][2])?$posts['discount_config'][2]:''?>"><span>%</span></div>'
			   +'</div>';
		}
        $('#discount_type').html(html);
		if(html=='')$('#discount_type').hide();
		else $('#discount_type').show();
    }

	function sub(){
		if($("input[name='hotel_id']").val() == ''){
			alert('酒店不能为空');
			return false;
		}
		if($("input[name='shop_name']").val() == ''){
			alert('店铺名不能为空');
			return false;
		}
        if($("input[name='sale_type']").val() == ''){
            alert('售卖类型不能为空');
            return false;
        }
		if($("input[name='start_time']").val() == '' || $("input[name='end_time']").val() == ''){
			alert('店铺开店时间或者打烊时间不能为空');
			return false;
		}
        if(!check($("input[name='start_time']").val()) || !check($("input[name='end_time']").val())){
            alert('店铺时间格式不对');
            return false;
        }

		return true;
		//$('#tosave').form.submit();
	}
    function  check(b)
    {
        var a = /^(\d{2}):(\d{2})$/;
        if (!a.test(b)) {
            return false
        }
        else
            return true
    }

</script>
