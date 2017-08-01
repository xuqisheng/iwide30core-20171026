<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/tinycolor.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.js"></script>
<style type="text/css">
    .input_checkbox>div >span{margin-right: 20px;}
    .input_checkbox >div >input:checked+label{background-size: 15px;}
    .input_checkbox >div >input+label{width: 170px;background-size: 15px;}
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
        <div class="banner bg_fff p_0_20">自定义样式</div>
        <div class="contents">
            <?php echo form_open( site_url('hotel/skins/edit_theme'), array('id'=>'code_form','class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array() ); ?>
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本配置</div>
                    <div class="con_right relative">
                        <div class="jingwei">
                            <div class="">主体颜色</div>
                            <div class="input_txt relative"><input  id="theme_color" class="c_block form-control cp-preventtouchkeyboardonshow"   type="text" name="theme_color" id="theme_color" 
                             value="<?php if (isset($skin_set['overall_style']['theme_color']))echo $skin_set['overall_style']['theme_color'];?>"/></div>
                            <script>
                                $("#theme_color").ColorPickerSliders({color:"#FF9900",size: "sm", placement: "bottom", hsvpanel: true, previewformat:"hex"});
                            </script>
                        </div>
                        <div class="jingwei">
                            <div class="">字体大小</div>
                            <div class="input_txt relative"><input type="text" name="fontx" id="fontx" value="<?php if (isset($skin_set['overall_style']['fontx']))echo $skin_set['overall_style']['fontx'];?>"/><span class="absolute" style="right:10px">px</span></div>
                        </div>
                        <div class="hotel_star clearfix">
                            <div class="float_left">默认设置</div>
                            <div class="input_txt input_checkbox" style="padding-left:4px;">
                                <div>
                                    <input type="checkbox" id="wf" name='default_color' value='1'>
                                    <label for="wf">使用默认颜色(#FF9900)</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="con_room" name='default_fx' value='1'>
                                    <label for="con_room">使用默认字体(14px)</label>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($skin_decoras)){?>
                         <div class="hotel_star clearfix">
                            <div class="float_left">皮肤装饰</div>
                            <div class="input_txt input_checkbox">
                                <div>
                                     <span><input type='radio' name='skin_decora' onclick='change_decora($(this))' value='' def_color="" 
                                     <?php if (empty($skin_set['overall_style']['decora']))echo 'checked';?>  /> 无</span> 
                                     <?php foreach ($skin_decoras as $s){?>
                                        <span><input
                                        <?php if (isset($skin_set['overall_style']['decora'])&&$skin_set['overall_style']['decora']==$s['code'])echo 'checked';?>
                                         type='radio' name='skin_decora' onclick='change_decora($(this))' value='<?php echo $s['code']?>' def_color="<?php echo empty($s['color'])?'':$s['color'];?>" /> <?php echo $s['name']."($s[tips])";?></span>
                                     <?php }?>
                                     <!-- <br>
                                        <label>
                                         <?php $i=1;foreach ($skin_decoras as $s){?>
                                            <?php echo $i.':'.$s['tips'];$i++;?>
                                        <?php }?>
                                        </label> -->
                                 </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            <div class="bg_fff border_1 btns_list" style="padding:15px;text-align:center;">
                <button type="button" onclick='sub();' class="fom_btn">保存</button>
                 <label id='tips' style='color:red;'></label>
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
<script>
var submit_tag=0;
function sub(){
    var check=true;
    if(submit_tag==0){
        submit_tag=1;
        $('#tips').html('提交中');
        if(!check){
            submit_tag=0;
            return false;
        }
        $.post('<?php echo site_url('hotel/skins/edit_theme')?>',$('#code_form').serialize(),function(data){
            $('#tips').html(data.message);
            submit_tag=0;
        },'json');
    }else{
        $('#tips').html('提交中，请勿重复提交');
    }
}
</script>