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
	<header class="headtitle">新增/编辑达达配置</header>
    <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<?php //$pk= $model->table_primary_key(); ?>
<?php if(isset($id)){?>
<?php echo form_open('ticket/dada/edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }else{?>
<?php echo form_open('ticket/dada/add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
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
            <div>dada Appkey</div>
            <div class="input flexgrow"><input name="app_key" value="<?php echo isset($posts['app_key'])?$posts['app_key']:''?>"></div>
        </div>
        <div>
            <div>dada AppSecret</div>
            <div class="input flexgrow"><input name="app_secret" value="<?php echo isset($posts['app_secret'])?$posts['app_secret']:''?>"></div>
        </div>
        <div>
            <div>dada商户id</div>
            <div class="input flexgrow"><input name="source_id" value="<?php echo isset($posts['source_id'])?$posts['source_id']:''?>"></div>
        </div>
        <div>
            <div>dada门店编号</div>
            <div class="input flexgrow"><input name="shop_no" value="<?php echo isset($posts['shop_no'])?$posts['shop_no']:''?>"></div>
        </div>

        <div>
            <div>订单所在城市编码</div>
            <div class="select_input flexgrow">

                <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($posts['city_code'])&&isset($dadacity[$posts['city_code']])?$dadacity[$posts['city_code']]:''?>"></div>
                <?php if(!empty($dadacity)){ ?>
                    <div class="silde_layer bd" >
                        <?php foreach($dadacity as $hk=>$hv){?>
                            <div data="<?php echo $hk?>"><?php echo $hv?></div>
                        <?php }?>
                    </div>
                <?php }?>
                <input type="hidden" name="city_code" value="<?php echo isset($posts['city_code'])?$posts['city_code']:''?>">
            </div>
        </div>
        <div>
            <div>下单后取货间隔</div>
            <div class="input flexgrow"><input placeholder="订单被接单后,间隔多少分钟后才来取货" name="expected_fetch_time" value="<?php echo isset($posts['expected_fetch_time'])?$posts['expected_fetch_time']:''?>">/分钟</div>
        </div>
        <div>
            <div>是否需要垫付</div>
            <div class="flex flexcenter">
                <label class="check"><input name="is_prepay" type="radio" checked value="1" <?php echo isset($posts['is_prepay'])&&$posts['is_prepay']==1?' checked ':''?> /><span class="diyradio"><tt></tt></span>需要</label>
                <label class="check"><input name="is_prepay" type="radio" value="2" <?php echo isset($posts['is_prepay'])&&$posts['is_prepay']==2?' checked ':''?> /><span class="diyradio"><tt></tt></span>不需要</label>
            </div>
        </div>
        <div>
            <div>状态</div>
            <div class="flex flexcenter">
                <label class="check"><input name="status" type="radio" checked value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' checked ':''?> /><span class="diyradio"><tt></tt></span>正常</label>
                <label class="check"><input name="status" type="radio" value="2" <?php echo isset($posts['status'])&&$posts['status']==2?' checked ':''?> /><span class="diyradio"><tt></tt></span>停用</label>
            </div>
        </div>
    </div>
</div>
        <div class="bg_fff bd center pad10">
        	<button class="bg_main button spaced" type="submit" id="set_btn_save">保存配置</button>
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

/*$('#el_hotel div').click(function(){
	$('#drowdown').empty();
	$.post('/index.php/ticket/shop/get_saler_info',{
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
});*/

	function sub(){
		if($("input[name='hotel_id']").val() == ''){
			alert('酒店不能为空');
			return false;
		}
		if($("input[name='app_key']").val() == ''){
			alert('app_key不能为空');
			return false;
		}
        if($("input[name='app_secret']").val() == ''){
            alert('app_secret不能为空');
            return false;
        }
        if($("input[name='source_id']").val() == ''){
            alert('source_id不能为空');
            return false;
        }
        if($("input[name='shop_no']").val() == ''){
            alert('shop_no不能为空');
            return false;
        }
		return true;
		//$('#tosave').form.submit();
	}


</script>
