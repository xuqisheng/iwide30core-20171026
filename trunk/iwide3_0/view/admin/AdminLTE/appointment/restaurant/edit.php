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
<style>
div[required]>div:first-child:before{content:'*'; color:#f00}
.candelete del{
    z-index: 6;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle">编辑店铺</header>
    <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<?php //$pk= $model->table_primary_key(); ?>
<?php if(isset($id)){?>
<?php echo form_open('appointment/restaurant/edit?ids='.$id,array('id'=>'tosave','enctype'=>'multipart/form-data'))?>
<?php }else{?>
<?php echo form_open('appointment/restaurant/add',array('id'=>'tosave','enctype'=>'multipart/form-data' ))?>
<?php }?>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">基本信息</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>关联店铺</div>
            <div class="flex flexcenter">
                <label class="check"><input name="shop_status" type="radio" value="1" <?php echo  $rest['shop_status'] == 1 ? 'checked' :'';?>/><span class="diyradio"><tt></tt></span>是</label>
                <label class="check"><input name="shop_status" type="radio" value="2" <?php echo  $rest['shop_status'] == 2 ? 'checked' :'';?>/><span class="diyradio"><tt></tt></span>否</label>
            </div>
        </div>
        <div id="el_connect_shop" required>
            <div>店铺名称</div>
            <div class="select_input flexgrow">
                <div class="input flexgrow">
                <select name="shop_id" class="hotel_id_select">
                    <option value="0">请选择店铺</option>
                    <?php
                        if (!empty($shops))
                        {
                            foreach ($shops as $val)
                            {
                    ?>
                        <option data="<?php echo $val['hotel_id'];?>" value="<?php echo $val['shop_id'];?>"><?php echo $val['shop_name'];?></option>
                    <?php
                            }
                        }
                    ?>
                </select></div>
            	<ul class="input flexgrow">
                    <input name="shop_name" value="<?php echo $rest['shop_name'];?>">
                </ul>
            </div>
        </div>
        <div id="hotel_id" style="display: none">
            <div>关联酒店</div>
            <div class="select_input flexgrow">
                <div class="input flexgrow">
                    <select name="hotel_id" class="hotel_id_select">
                        <option value="0">请选择酒店</option>
                        <?php
                        if (!empty($hotels))
                        {
                            foreach ($hotels as $key=>$val)
                            {
                                ?>
                                <option data="<?php echo $key;?>" value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php
                            }
                        }
                        ?>
                    </select></div>
            </div>
        </div>
        <div required>
            <div>预约方式</div>
            <div class="flex flexcenter">
                <label class="check"><input name="book_style" type="radio" value="1" <?php echo  $rest['book_style'] == 1 ? 'checked' :'';?>/><span class="diyradio"><tt></tt></span>取号+预订</label>
                <label class="check"><input name="book_style" type="radio" value="2" <?php echo  $rest['book_style'] == 2 ? 'checked' :'';?>/><span class="diyradio"><tt></tt></span>仅取号</label>
                <label class="check"><input name="book_style" type="radio" value="3" <?php echo  $rest['book_style'] == 3 ? 'checked' :'';?>/><span class="diyradio"><tt></tt></span>仅预订</label>
            </div>
        </div>
    </div>
</div>

<div class="whitetable">
    <div>
        <span style="border-color:#393">营业时段</span>
    </div>
    <div class="bd_left list_layout">
        <div style="padding-bottom:0" required>
            <div>营业时段</div>
            <div class="flexgrow" style="max-width:100%">
                <span class="button void xs" id="addRange">添加时段</span> 
            </div>
        	<div class="layoutfoot" style="color:inherit;display:block" id="RangeTable" limit='8'>
            	<input type="hidden" required name="time_range" value="" />
                <div class="diytable center martop">
                    <div class="thead">
                    	<div>名称</div>
                    	<div>时间范围</div>
<!--                    	<div>等待时长</div>-->
                        <div>操作</div>
                    </div>
                    <?php
                    if(!empty($opentime))
                    {
                    foreach($opentime as $item)
                    {
                    ?>
                    <div class="tr">
                        <div style="display: none"><input class="input xs" name="opentime_id" onChange="" value="<?php echo $item['opentime_id'];?>" /></div>
                        <div><input class="input xs" name="name" onChange="" value="<?php echo  $item['name'];?>" /></div>
                        <div><input class="input xs timepicker" name="start_time" onChange="" value="<?php echo  $item['start_time'];?>" /> -
                            <input class="input xs timepicker" name="end_time" onChange="" value="<?php echo  $item['end_time'];?>" /></div>
<!--                        <div><input class="input xs" name="wait_time" onChange="" value="--><?php //echo  $item['wait_time'];?><!--" /> 分钟/桌</div>-->
                        <div class="candelete"><!--del style="position:static"></del>--></div>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#3f92b5">类型/库存</span>
    </div>
    <div class="bd_left list_layout">
        <div style="padding-bottom:0" required>
            <div>类型管理</div>
            <div class="flexgrow" style="max-width:100%">
                <span class="button void xs" id="addType">添加桌型</span> 
            </div>
        	<div class="layoutfoot" style="color:inherit; display:block" id="TypeTable" limit='8'>
            	<input type="hidden" name="desk_list" required value="" />
                <div class="diytable center martop">
                    <div class="thead">
                    	<div>名称</div>
                    	<div>容纳人数</div>
                    	<div>等待时长</div>
                    	<div>时段库存</div>
                        <div>操作</div>
                    </div>
                    <?php
                        if(!empty($desk_type))
                        {
                            foreach($desk_type as $item)
                            {
                    ?>
                        <div class="tr">
                            <div style="display: none"><input class="input xs" name="desk_type_id" onChange="" value="<?php echo $item['desk_type_id'];?>" /></div>
                            <div><input class="input xs" name="name" onChange="" value="<?php echo $item['name'];?>" /></div>
                            <div><input class="input xs" name="min_num" onChange="" value="<?php echo $item['min_num'];?>" /> -
                                <input class="input xs" name="max_num" onChange="" value="<?php echo $item['max_num'];?>" /></div>
                            <div><input class="input xs" name="wait_time" onChange="" value="<?php echo $item['wait_time'];?>" /> 分钟/桌</div>
                            <div><input class="input xs" name="stock" onChange="" value="<?php echo $item['stock'];?>" /></div>
                            <div class="candelete"><!--<del style="position:static"></del>--></div>
                        </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#666">通用配置</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>类型单位</div>
            <div class="input flexgrow"><input name="unit" required placeholder="如：桌、台" value="<?php echo $rest['unit'];?>"></div>
        </div>
        <div required>
            <div>人数上限</div>
            <div class="input flexgrow"><input name="toplimit" required placeholder="输入最大可预约/预订容纳人数" value="<?php echo $rest['toplimit'];?>"><span>人</span></div>
        </div>
    </div>
</div>
<div class="whitetable" id="only_getnum">
    <div>
        <span style="border-color:#af4cac">取号配置</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>取号限制</div>
            <div class="flex flexcenter" style="max-width:100%;">
            	<div>营业前&nbsp;</div>
                <div class="input"><input name="start_time" required value="<?php echo $rest['start_time'];?>" style="width:80px"></div>
                <div>&nbsp;分钟可取号，结业前&nbsp;</div>
                <div class="input"><input name="end_time" required value="<?php echo $rest['end_time'];?>" style="width:80px"></div>
                <div>&nbsp;分钟关闭取号</div>
            </div>
        </div>
        <div>
            <div class="flex_aligntop">取号备注</div>
            <div class="flexgrow input"><textarea name="give_info" maxlength="120"><?php echo $rest['give_info'];?></textarea></div>
        </div>
    </div>
</div>

<div class="whitetable" id="only_booked">
    <div>
        <span style="border-color:#F96">预订配置</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>预订限制</div>
            <div class="flex flexcenter" style="max-width:100%;">
            	<div>可提前&nbsp;</div>
                <div class="input"><input required name="book_day" placeholder="" value="<?php echo $rest['book_day'];?>" style="width:80px"><span>天</span></div>
                <div>&nbsp;预订</div>
            </div>
        </div>
        <!----------- 暂不开发
        <div>
            <div>即时确认</div>
            <div class="flex flexcenter">
                <label class="check"><input name="" type="radio" value="" checked/><span class="diyradio"><tt></tt></span>是</label>
                <label class="check"><input name="" type="radio" value="" /><span class="diyradio"><tt></tt></span>否</label>
            </div>
        </div>
        <div>
            <div>预订押金</div>
            <div class="input flexgrow"><input name="" placeholder="" value=""><span>元</span></div>
        </div>--------- 暂不开发-->
    </div>
</div>

<div class="whitetable">
    <div>
        <span style="border-color:#933">其他信息</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>店铺电话</div>
            <div class="input flexgrow"><input required name="shop_tel" value="<?php echo $rest['shop_tel'];?>"></div>
        </div>
        <div required>
            <div>店铺地址</div>
            <div class="input flexgrow"><input required name="shop_address" value="<?php echo $rest['shop_address'];?>"></div>
        </div>
        <div>
            <div>模板消息通知</div>
            <div class="select_input flexgrow">
                <div class="input"><input placeholder="搜索或下拉选择" id="search_hotel" value="<?php if (!empty($show_saler_name)){echo $show_saler_name;}else{echo '';}?>"></div>

                <div class="silde_layer bd" id="drowdown">
                    <?php if(!empty($salers)){?>
                        <?php foreach ($salers as $k => $v):?>
                            <div data="<?=$k?>" multi="true" class="<?php echo !empty($rest['msgsaler'])&&(in_array($k,explode(',',$rest['msgsaler'])))?'color_main':''?>"><?=$v?></div>
                        <?php endforeach;?>
                    <?php }?>
                </div>

                <input type="hidden" name="msgsaler" value="<?php echo isset($rest['msgsaler'])?$rest['msgsaler']:''?>">
            </div>
        </div>
        <!--
        <div required>
            <div>营业时间</div>
            <div class="flexgrow flex">
                <div class="input maright "><input required class="timepicker" name="start_open_time" value="<?php echo $rest['start_open_time'];?>"></div>
                <span>-</span>
                <div class="input"><input required class="timepicker" name="end_open_time" value="<?php echo $rest['end_open_time'];?>"></div>
            </div>
        </div>
        -->
        <div>
            <div class="flex_aligntop">店铺简介</div>
            <div class="flexgrow input"><textarea name="shop_profiles" maxlength=""><?php echo $rest['shop_profiles'];?></textarea></div>
        </div>
    </div>
</div><div class="whitetable">
    <div>
        <span style="border-color:#ebe814">店铺图片</span>
    </div>
    <div class="bd_left list_layout">
        <div required>
            <div>缩略图</div>
            <div>
                <input type="hidden" required name="shop_image" id="el_shop_img" value='<?php echo $rest['shop_image'];?>'>
                <div id="shop_img" class="addimgs trim">
                    <div class="addimg candelete"><del></del><div><img src="<?php echo $rest['shop_image'];?>"/></div></div>
                </div>
                <div id="shop_img_add"></div>
            </div>
            <div class="layoutfoot">图片大小必须 &lt; 300KB;</div>
        </div>
    </div>
</div>
        <div class="bg_fff bd center pad10">
            <input type="hidden" name="dosubmit" value="1">
            <input type="hidden" name="dining_room_id" value="<?php echo $rest['dining_room_id'];?>">
            <input type="hidden" name="shop_info_id" value="<?php echo $rest['shop_info_id'];?>">
        	<button class="bg_main button spaced" type="button" id="set_btn_save">保存配置</button>
        </div>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script>

    //消息通知店员
    $('.hotel_id_select').change(function(){
        $('#drowdown').empty();
        $('#search_hotel').val('');
        //alert($(this).find('option:selected').attr('data'));
        $.post('/appointment/restaurant/get_saler_info',{
            'hotel_id':$(this).find('option:selected').attr('data'),
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




var _html_1 = '<div class="tr">'
			+ '<div><input class="input xs" name="name" value="" /></div>'
			+ '<div><input class="input xs timepicker" name="start_time" value="" /> - <input class="input xs timepicker" name="end_time" value="" /></div>'
//			+ '<div><input class="input xs" name="wait_time" value="" /> 分钟/桌</div>'
			+ '<div class="candelete"><del style="position:static"></del></div>'
			+ '</div>';
var _html_2 = '<div class="tr">'
			+ '<div><input class="input xs" name="name" value="" /></div>'
			+ '<div><input class="input xs" name="min_num" value="" /> - <input class="input xs" name="max_num" value="" /></div>'
			+ '<div><input class="input xs" name="wait_time" value="" /> 分钟/桌</div>'
			+ '<div><input class="input xs" name="stock" value="" /></div>'
			+ '<div class="candelete"><del style="position:static"></del></div>'
			+ '</div>';

function fill_table(html,parent){
	var dom = $(html);
	var limit= Number(parent.attr('limit'));
	if( parent.find('.tr').length>=limit){ return;}
	parent.find('.diytable').append(dom);
	parent.show();
	dom.find('input').attr('required',true);
	dom.find('del').get(0).onclick=function(){
		$(this).parents('.tr').remove();
		if( parent.find('.tr').length<=0) parent.hide();
	}
	dom.find('.timepicker').datetimepicker({
        language:  'zh-CN',
        format: 'hh:ii',
        startView:1,
        autoclose: true
    });
}
$('.timepicker').datetimepicker({
	language:  'zh-CN',
	format: 'hh:ii',
	startView:1,
	autoclose: true
});
$('#addRange').click(function(){
	fill_table(_html_1,$('#RangeTable'));
})
$('#addType').click(function(){
	fill_table(_html_2,$('#TypeTable'));
})

function delimg(){  //缩略图
	$(this).parent().remove();
	$("#el_shop_img").val('');
	$('#shop_img_add').show();
}
$('#shop_img_add').uploadify({//缩略图
	'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
		'timestamp' : '<?php echo time();?>',
        'token'     : '<?php echo md5('unique_salt' . time());?>'
	},
	'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
	'fileObjName': 'imgFile',
	'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
	'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
	'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
	'fileSizeLimit':'300', //限制文件大小
	'onUploadSuccess' : function(file, data) {
		var res = $.parseJSON(data);
		$('#el_shop_img').val(res.url);
		var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
		$("#shop_img").append(dom);
		dom.find('del').get(0).onclick=delimg;
		$('#shop_img_add').hide();
	},   
	'onUploadError': function () {  
		alert('上传失败');  
	}
});
if($('#shop_img').find('img').length>0){
	$("#shop_img del").get(0).onclick=delimg;
	$('#shop_img_add').hide();
}
function show_book_type(){
	var val = $('input[name="book_style"]:checked').val();
	$('#only_booked').show();
	$('#only_getnum').show();
	if(val == 2){
		$('#only_booked').hide();
	}else if(val==3){
		$('#only_getnum').hide();
	}
}
$('input[name="book_style"]').change(show_book_type);
show_book_type();
function show_connect_shop(){
	var val = $('input[name="shop_status"]:checked').val();
	if(val == 2){
		$('#el_connect_shop select').parent().hide();
		$('#el_connect_shop input').parent().show();
		$('#hotel_id').show();
		$('#el_connect_shop input').attr('required',true);
	}else{
		$('#el_connect_shop select').parent().show();
		$('#el_connect_shop input').parent().hide();
		$('#hotel_id').hide();
		$('#el_connect_shop input').removeAttr('required');
	}	
}
$('input[name="shop_status"]').change(show_connect_shop);
show_connect_shop();
function save_input(table){
	var array = [];
	table.find('.tr').each(function(){
		var json = {}
		$(this).find('input').each(function(index, element) {
           json[$(this).attr('name')] = $(this).val();
        });
		array.push(json);
	});
	if(table.find('.tr').length>0)
	table.find('input[type=hidden]').val(JSON.stringify(array));
}
$('#set_btn_save').click(function(){
	var bool =true;
	save_input($('#RangeTable'));
	save_input($('#TypeTable'));
	console.log($('input[name=time_range]').val())
	$('div[required]').each(function(){
		var  _this = $(this);
		_this.removeAttr('style');
		$(this).find('[required]').each(function(index, element) {
			if($(this).val()==''){
				console.log($(this).attr('name'));
				bool = false;
				_this.css('color','#f00');
			}
        });
	});
	if(bool){
		$('form').submit();
	}else{
		alert('带*为必填项');
	}
})

$('select[name="shop_id"]').val(<?php echo $rest['shop_id'];?>);
$('select[name="hotel_id"]').val(<?php echo $rest['hotel_id'];?>);
</script>
