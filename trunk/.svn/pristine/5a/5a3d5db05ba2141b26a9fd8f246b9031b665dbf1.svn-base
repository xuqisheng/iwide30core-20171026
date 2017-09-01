<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
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

    <div class="content-wrapper">
        <header class="headtitle">新增商品分组</header>
        
        <section class="content">
		<?php echo $this->session->show_put_msg(); ?>
			<?php
            if(isset($id)){
                echo form_open( EA_const_url::inst()->get_url('*/*/edit?ids='.$id), array('class'=>'form-horizontal','onsubmit'=>'return sub();'), array('ids'=>$this->input->get('ids') ) );
            }else{
                echo form_open( EA_const_url::inst()->get_url('*/*/add'), array('class'=>'form-horizontal','onsubmit'=>'return sub();'), array('ids'=>$this->input->get('ids') ) );
            }
	?>
        <div class="whitetable">
            <div>
                <span style="border-color:#3f51b5">分组信息</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>分组名称</div>
                    <div class="input flexgrow"><input name="group_name" value="<?php echo isset($posts['group_name'])?$posts['group_name']:''?>"></div>
                </div>
                <div>
                    <div>所属店铺</div>
                    <div class="select_input flexgrow">
                    	<div class="input"><input placeholder="搜索或下拉选择" value="<?php echo isset($shop_info['shop_name'])?$shop_info['shop_name']:''?>"></div>
						<?php if(!empty($shop)){?>
                        	<div class="silde_layer bd">
                            <?php foreach ($shop as $k => $v){?>
                            	<div data="<?php echo $v['shop_id'];?>" idd="<?php echo $v['hotel_id']?>"><?php echo $v['shop_name'];?></div>
                            <?php }?>	
                            </div>
                        <?php }?>
                        <input type="hidden" name="shop_id" value="<?php echo isset($posts['shop_id'])?$posts['shop_id']:''?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="whitetable">
            <div>
                <span style="border-color:#ff503f">其他信息</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>分组排序</div>
                    <div class="input flexgrow"><input name="sort_order" value="<?php echo isset($posts['sort_order'])?$posts['sort_order']:''?>"></div>
                </div>
            </div>
        </div>
        <input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo isset($posts['hotel_id'])?$posts['hotel_id']:''?>">
        <div class="bg_fff bd center pad10"><button class="bg_main button spaced" type="submit">保存配置</button></div>
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script type="text/javascript">
	<?php $timestamp = time();?>
$(function() {
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
	$('.silde_layer>*').click(function(){
		$(this).parent().siblings().find('input').val($(this).html());
		$(this).parent().siblings('input').val($(this).attr('data'));
        $('#hotel_id').val($(this).attr('idd'));
	})

});
    function sub(){
        if($("input[name='group_name']").val() == ''){
            alert('名称不能为空');
            return false;
        }
        if($("input[name='shop_id']").val() == ''){
            alert('店铺不能为空');
            return false;
        }
        if($("input[name='hotel_id']").val() == ''){
            alert('酒店不能为空');
            return false;
        }
        return true;
    }

</script>