<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<style>
<!--
@font-face {font-family: 'iconfont';
    /*src: url('iconfont.eot');  IE9*/
    /*src: url('iconfont.eot?#iefix') format('embedded-opentype'),  IE6-IE8 */
    src: url('<?php echo base_url('public/fonts/iconfont.woff')?>') format('woff'), /* chrome、firefox */
    url('<?php echo base_url('public/fonts/iconfont.ttf')?>') format('truetype') /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
    /*url('iconfont.svg#svgFontName') format('svg');  iOS 4.1- */
}
.iconfont{font-family: "iconfont";
font-style: normal;
font-size: 1.8em;
vertical-align: middle;
display: inline-block;}
-->
@font-face {
  font-family: 'iconfont';
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot');
  src: url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.woff') format('woff'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.ttf') format('truetype'),
  url('<?php echo base_url(FD_PUBLIC) ?>/newfont/iconfont.svg#iconfont') format('svg');
}
.iconfont{
  font-family:"iconfont" !important;
  font-size:16px;font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.over_x{width:100%;overflow-x:auto;}
.bg_fff{background:#fff;}
.bg_3f51b5{background:#3f51b5;}
.bg_ff503f{background:#ff503f;}
.bg_4caf50{background:#4caf50;}
.clearfix:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
.display_none{display:none !important;}
.m_b_20{margin-bottom:20px;}
.float_left{float:left;}
.content-wrapper{color:#7e8e9f;}
.p_0_20{padding:0 20px;}
textarea{border:1px solid #d7e0f1;}
.banner{height:50px;width:100%;line-height:50px;border-bottom:1px solid #d7e0f1;}
.contents{padding:10px 20px 20px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.hotel_star >div:nth-of-type(2) >div,.con_right >div >div{display:inline-block;}
.con_left{width:150px;text-align:center;border-right:1px solid #d7e0f1;display:table-cell;vertical-align:middle;}
.con_right{padding:20px 0 20px 0px;}
.con_right>div{margin-bottom:12px;}
.con_right >div >div:nth-of-type(1){width:115px;height:30px;line-height:30px;text-align:center;}
.input_txt{line-height:30px;}
.input_txt >input{height:30px;line-height:30px;border:1px solid #d7e0f1;width:450px;text-indent:3px;}
.input_txt >select{height:30px;line-height:30px;display:inline-block;border:1px solid #d7e0f1;background:#fff;margin-right:20px;padding:0 8px;}
.input_radio >div{margin-right:10px;}
.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio1.png) no-repeat center left;background-size:22%;width:90px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio2.png) no-repeat center left;background-size:20%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg2.png) no-repeat center left;background-size:15%;}


.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
.uploadify{float: left;}

</style>
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
<div class="over_x">
	<div class="content-wrapper" style="min-width:1050px;">
		<div class="banner bg_fff p_0_20"><?php /*echo $breadcrumb_html; */?>新增二维码</div>
		<div class="contents">
		<?php echo $this->session->show_put_msg(); ?>
			<?php
	echo form_open( EA_const_url::inst()->get_url('*/*/add'), array('class'=>'form-horizontal','onsubmit'=>'return sub();'), array('qrcode_id'=>$this->input->get('ids') ) );
	?>
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_3f51b5"></span>二维码信息</div>
					<div class="con_right">
                        <div class="hottel_name ">
                            <div class="">二维码名称</div>
                            <div class="input_txt"><input type="text" name="qrcode_name" value=""/></div>
                        </div>
							<div class="hottel_name ">
								<div class="">所属店铺</div>
								<div class="input_txt">
									<select style="width:450px;" name="shop_id" id="shop_id" >
                                        <option value="-1">选择</option>
										<?php if(!empty($shop)){?>
											<?php foreach ($shop as $k => $v){?>
												<option value="<?php echo $v['shop_id'];?>"><?php echo $v['shop_name'];?></option>
											<?php }?>	
										<?php }?>
									</select>
								</div>
							</div>
                        <div class="hottel_name" id="qrcode_num">

                        </div>

					</div>
				</div>
            <input type="hidden" name="hotel_id" id="hotel_id" value="">

            <input type="hidden" name="sale_type" id="shop_type" value="">
				<div class="contents_list bg_fff">
					<div class="con_left"><span class="block bg_4caf50"></span>店铺信息</div>
					<div class="con_right">
						<div class="hottel_name ">
							<div class="">所属门店</div>
							<div class="input_txt"><span id="hotel_name"></span></div>
						</div>
						<div class="hottel_name ">
							<div class="">营业时间</div>
							<div class="input_txt"><span id="shop_time"></span></div>
						</div>
                        <div class="hottel_name ">
                            <div class="">售卖方式</div>
                            <div class="input_txt"><span id="sale_type"></span></div>
                        </div>
					</div>
				</div>
				<div class="bg_fff" style="padding:15px;">
					<button type="submit" name="submit" class="fom_btn">生成二维码</button>
				</div>
			<?php echo form_close() ?>
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
        $('#shop_id').change(function(){
            var value  = $(this).val();
            if(value=='-1'){
                return false;
            }
            var url = '<?php echo site_url('ticket/qrcodelist/ajax_get_shop_info')?>';
            $.post(url,{
                'shop_id':value,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(res){
                if(res.errcode == 0){
                    $('#hotel_name').text(res.data.hotel_name);
                    $('#shop_time').text(res.data.shop_time);
                    $('#sale_type').text(res.data.sale_name);
                    $('#shop_type').val(res.data.sale_type);
                    $('#hotel_id').val(res.data.hotel_id);
                    $('#qrcode_num').html('');
                    if(res.data.sale_type == 1){//客房
                        $('#qrcode_num').append('<div>房间号</div> <input type="text" name="type_id" value=""/>');
                    }else if(res.data.sale_type == 2){
                        $('#qrcode_num').append('<div>桌号</div> <input type="text" name="type_id" value=""/>');
                    }
                }else{
                    alert(res.msg);
                }
            },'json');
        });
	});
    function sub(){
        if($("input[name='qrcode_name']").val() == ''){
            alert('名称不能为空');
            return false;
        }
        if($("#shop_id").val() == -1){
            alert('店铺不能为空');
            return false;
        }
        if(typeof $("input[name='type_id']").val() != 'undefined' && $("input[name='type_id']").val()==''){
            alert('号数不能为空');
            return false;
        }

        return true;
    }

</script>