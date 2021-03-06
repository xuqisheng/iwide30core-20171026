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
/*气泡*/
.show_bubble{position:relative; cursor:help}
._bubble{ position:absolute; top:50%; left:-22px;text-align:justify; color:#fff; font-size:0; display:none}
._bubble tt{border:10px solid transparent; border-bottom-color:rgba(0,0,0,0.6); display:inline-block; margin-left:20px}
._bubble div{ border-radius:5px; overflow:hidden;background:rgba(0,0,0,0.6); padding:12px; width:280px; font-size:12px; line-height:1.4;}
.show_bubble:hover ._bubble,.show_bubble:focus ._bubble{display:block}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle">新增/编辑打印机</header>
    <section class="content">
<?php echo $this->session->show_put_msg(); ?>
<?php //$pk= $model->table_primary_key(); ?>
<?php if(isset($id)){?>
<?php echo form_open('eat-in/printer/edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }else{?>
<?php echo form_open('eat-in/printer/add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
<?php }?>
<div class="whitetable">
    <div>
        <span style="border-color:#3f51b5">基本信息</span>
    </div>
    <div class="bd_left list_layout">
        <div>
            <div>打印来源</div>
            <?php if(!isset($id)){?>
            <div class="flex flexcenter">
               <label class="check"><input name="source" onclick="change_source()" type="radio" value="1" <?php if(isset($s)&&$s==1)echo 'checked'?> /><span class="diyradio"><tt></tt></span>快乐送</label>
                <label class="check"><input name="source" onclick="change_source()" type="radio" value="2" <?php if(isset($s)&&$s==2)echo 'checked'?> /><span class="diyradio"><tt></tt></span>快乐付</label>
                <label class="check"><input name="source" onclick="change_source()" type="radio" value="3" <?php if(isset($s)&&$s==3)echo 'checked'?> /><span class="diyradio"><tt></tt></span>现场取号</label>
            </div>
            <?php }else{?>
            <div class="flex flexcenter">
                <?php if(isset($posts['source'])&&$posts['source']==1){?>
                <label class="check"> <input name="source"  type="hidden" value="1"/>快乐送</label>
                <?php }else if(isset($posts['source'])&&$posts['source']==2){?>
                <label class="check"><input name="source"  type="hidden" value="2"/>快乐付</label>
                <?php }else if(isset($posts['source'])&&$posts['source']==3){?>
                <label class="check"> <input name="source"  type="hidden" value="3"/>现场取号</label>
                <?php } ?>
            </div>
            <?php }?>
        </div>
        <div>
                <div>打印机名称</div>
            <div class="input flexgrow"><input name="name" value="<?php echo isset($posts['name'])?$posts['name']:''?>"></div>
        </div>
    </div>
</div>
<div class="whitetable">
    <div>
        <span style="border-color:#4caf50">打印机绑定</span>
    </div>
    <div class="bd_left list_layout">

        <div>
            <div>机身编号</div>
            <div class="input flexgrow"><input name="printer_no" value="<?php echo isset($posts['printer_no'])?$posts['printer_no']:''?>"></div>
        </div>
        <div>
            <div>机身秘钥</div>
            <div class="input flexgrow"><input name="printer_key" value="<?php echo isset($posts['printer_key'])?$posts['printer_key']:''?>"></div>
        </div>
    </div>
</div>
<?php if(isset($s)&& $s==1){?>
        <div class="whitetable">
            <div>
                <span style="border-color:#ebe814">快乐送</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>所属店铺</div>
                    <div class="select_input flexgrow">
                        <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($posts['shop_id'])&&isset($shops[$posts['shop_id']]['shop_name'])?$shops[$posts['shop_id']]['shop_name']:''?>"></div>
                        <?php if(!empty($shops)){ ?>
                            <div class="silde_layer bd" id="el_hotel">
                                <?php foreach($shops as $hk=>$hv){?>
                                    <div data="<?php echo $hv['shop_id']?>"><?php echo $hv['shop_name']?></div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <input type="hidden" name="shop_id" value="<?php echo isset($posts['shop_id'])?$posts['shop_id']:''?>">
                    </div>
                </div>
                <div>
                    <div>打印方式
                        <i class="fa fa-question-circle show_bubble" style="font-size:16px">
                            <div class="_bubble">
                                <tt></tt>
                                <div>
                                    新订单提醒：<br>
                                    1.微信支付订单且用户已支付时自动打印<br>
                                    2.线下支付订单提交时(即待确认时）自动打印<br><br>

<!--                                    接单自动打印：<br>-->
<!--                                    1.线下支付订单确认接单后自动打印<br>-->
<!--                                    2.微信支付订单确认接单后自动打印<br><br>-->

                                    后台手工打印：<br>
                                    1.后台操作打印订单时触发打印该订单<br><br>

                                    催单提醒：<br>
                                    1.用户催单时自动打印
                                </div>
                            </div>
                        </i>
                    </div>
                    <div class="flex flexcenter">
                        <?php foreach($type as $sk=>$sv){?>
                            <label class="check"  style="min-width:100px!important;"><input name="type[]" type="checkbox" value="<?php echo $sk?>" <?php if(!empty($posts['type'])&&in_array($sk,$posts['type']) ){echo "checked='checked'";}?> /><span class="diyradio"><tt></tt></span><?php echo $sv?></label>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <?php }else if(isset($s)&& $s==2){?>
        <div class="whitetable">
            <div>
                <span style="border-color:#ebe814">快乐付</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>所属场景</div>
                    <div class="select_input flexgrow">
                        <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($posts['shop_id'])&&isset($okpay_types[$posts['shop_id']]['name'])?$okpay_types[$posts['shop_id']]['name']:''?>"></div>
                        <?php if(!empty($okpay_types)){ ?>
                            <div class="silde_layer bd" id="el_hotel">
                                <?php foreach($okpay_types as $hk=>$hv){?>
                                    <div data="<?php echo $hv['id']?>"><?php echo $hv['name']?></div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <input type="hidden" name="shop_id" value="<?php echo isset($posts['shop_id'])?$posts['shop_id']:''?>">
                    </div>
                </div>
                <div>
                    <div>打印方式
                            <div class="_bubble">

                            </div>
                        </i>
                    </div>
                    <div class="flex flexcenter" style="width:600px;">
                        <?php foreach($okpay_print_type as $sk=>$sv){?>
                            <label class="check"><input name="type[]" type="checkbox" value="<?php echo $sk?>" <?php if(!empty($posts['type'])&&in_array($sk,$posts['type']) ){echo "checked='checked'";}?> /><span class="diyradio"><tt></tt></span><?php echo $sv?></label>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <?php } if(isset($s)&& $s==3) { ?>
             <div class="whitetable">
        <div>
            <span style="border-color:#ebe814">现场取号</span>
        </div>
        <div class="bd_left list_layout">
            <div>
                <div>所属店铺</div>
                <div class="select_input flexgrow">
                    <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($posts['shop_id'])&&isset($shops[$posts['shop_id']]['shop_name'])?$shops[$posts['shop_id']]['shop_name']:''?>"></div>
                    <?php if(!empty($shops)){ ?>
                        <div class="silde_layer bd" id="el_hotel">
                            <?php foreach($shops as $hk=>$hv){?>
                                <div data="<?php echo $hv['shop_id']?>"><?php echo $hv['shop_name']?></div>
                            <?php }?>
                        </div>
                    <?php }?>
                    <input type="hidden" name="shop_id" value="<?php echo isset($posts['shop_id'])?$posts['shop_id']:''?>">
                </div>
            </div>
            <div>
                <div>打印方式
                    <i class="fa fa-question-circle show_bubble" style="font-size:16px">
                        <div class="_bubble">
                            <tt></tt>
                            <div>
                                后台取号：<br>

                            </div>
                        </div>
                    </i>
                </div>
                <div class="flex flexcenter">
                    <?php foreach($offer_print_type as $sk=>$sv){?>
                        <label class="check"  style="min-width:100px!important;"><input name="type[]" type="checkbox" value="<?php echo $sk?>" checked='checked' /><span class="diyradio"><tt></tt></span><?php echo $sv?></label>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

        <?php
        }
        ?>
        <div class="bg_fff bd center pad10">
			<!--<button type="reset" class="bg_key maright button spaced">清空配置</button>-->
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
    function change_source(){
        var s = $("input[name=source]:checked").val();
        location.href = '<?php echo site_url('eat-in/printer/add?s=')?>' + s;
    }
function select_click(obj){
	$('#search_hotel').val($(obj).text());
	$("input[name='msgsaler']").val($(obj).val());
}

$('.silde_layer>*').click(function(){
	$(this).parent().siblings().find('input').val($(this).html());
	$(this).parent().siblings('input').val($(this).attr('data'));
})
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

	function sub(){
		if($("input[name='shop_id']").val() == ''){
			alert('店铺不能为空');
			return false;
		}
		if($("input[name='name']").val() == ''){
			alert('名称不能为空');
			return false;
		}
        if($("input[name='type']").val() == ''){
            alert('类型不能为空');
            return false;
        }

		return true;
		//$('#tosave').form.submit();
	}


</script>
