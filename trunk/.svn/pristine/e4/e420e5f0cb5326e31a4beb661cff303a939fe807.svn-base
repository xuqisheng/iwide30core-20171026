<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/images/laydate12.css">
<link type="text/css" rel="stylesheet" href="http://test008.iwide.cn/public/AdminLTE/plugins/datatables/need/laydate.css">
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
<style>
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
.w_450{width:450px !important;border:1px solid #d7e0f1;}
.w_80{width:80px !important;}
.w_180{width:180px !important;}
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
.input_txt{height:30px;line-height:30px;}
.input_txt >input{height:30px;line-height:30px;border:1px solid #d7e0f1;width:450px;text-indent:3px;}
.input_txt >select{height:30px;line-height:30px;display:inline-block;border:1px solid #d7e0f1;background:#fff;margin-right:20px;padding:0 8px;}
.input_radio >div{margin-right:28px;}
.input_radio >div >input{display:none;}
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(http://test008.iwide.cn/public/js/img/radio1.png) no-repeat center left;background-size:13%;width:155px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(http://test008.iwide.cn/public/js/img/radio2.png) no-repeat center left;background-size:13%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(http://test008.iwide.cn/public/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(http://test008.iwide.cn/public/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(http://test008.iwide.cn/public/js/img/bg2.png) no-repeat center left;background-size:15%;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
#file >input{text-indent:-9999px; height:80px;line-height:60px;width:80px;background-image:url("http://test008.iwide.cn/public/js/img/upload.png");}
.f_l{float:left;}
.block_list{margin-left:4px;}
.block_list>div{margin-bottom:10px;}
.block_list>div:last-chlid{margin-bottom:0px;}
.clearfix:after{content:" ";display:block;clear:both;height:0;}
.btn_number+label{width:70px !important;background-size:30% !important;}
.btn_number:checked+label{background-size:30% !important;}
.btn_number+label+div{display:none}
.btn_number:checked+label+div{display:inline-block;}
.w_450 .dropdown-toggle{background:#fff;border:0px;}
</style>
<div class="over_x">
    <div class="content-wrapper" style="min-width: 1050px; min-height: 775px;">
        <div class="banner bg_fff p_0_20">新增礼包规则or编辑礼包规则
</div>
        <div class="contents">
            <?php if(isset($id)){?>
                <?php echo form_open('okpay/package/edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
            <?php }else{?>
                <?php echo form_open('okpay/package/add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
            <?php }?>
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
                    <div class="con_right">
                        <div class="address">
                            <div class="">所属酒店</div>
                            <div class="input_txt">
                            	<select class="selectpicker w_450" name="hotel_id" data-live-search="true">
                                    <?php if(!empty($hotel)){
                                        foreach($hotel as $hk=>$hv){
                                            ?>
                                            <option value="<?php echo $hk?>" <?php echo isset($posts['hotel_id'])&&$posts['hotel_id']==$hk?' selected ':''?>><?php echo $hv?></option>
                                        <?php }}?>
                                </select>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">所属场景</div>
                            <div class="input_txt">
                            	<select class="w_450" name="type_id">
                                    <?php if(!empty($type)){
                                        foreach($type as $tk=>$tv){
                                            ?>
                                            <option value="<?php echo $tv['id']?>" <?php echo isset($posts['type_id'])&&$posts['type_id']==$tv['id']?' selected ':''?>><?php echo $tv['name']?></option>
                                        <?php }}else{?>
                                        <option value="">该酒店没设置场景</option>
                                    <?php }?>
                                  <option>所属场景3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <link rel="stylesheet" href="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
                <script src="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
                <script src="http://mp.iwide.cn/public/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>礼包配置</div>
                    <div class="con_right">
                        <div class="hottel_name ">
                            <div class="">礼包名称</div>
                            <div class="input_txt">
                            	<select name="package_id" class="w_450">
                                    <?php if(!empty($package)){
                                        foreach($package as $hk=>$hv){
                                            ?>
                                            <option value="<?php echo $hv['package_id']?>" <?php echo isset($posts['package_id'])&&$posts['package_id']==$hv['package_id']?' selected ':''?>><?php echo $hv['name']?></option>
                                        <?php }}else{?>
                                        <option value="-1">无礼包信息</option>
                                    <?php }?>
	                            </select>
	                        </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">条件金额</div>
                            <div class="input_txt"><input type="text" name="start_money" placeholder="条件金额" value="<?php echo isset($posts['start_money'])?$posts['start_money']:''?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">赠送份数</div>
                            <div class="input_txt"><input type="text" name="count" placeholder="赠送份数" value="<?php echo isset($posts['count'])?$posts['count']:'1'?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">活动时间</div>
                            <div class="input_txt">
                                <span class="t_time">
                                    <input name="start_time" type="text" id="datepicker"
                                           class="datepicker moba" value="<?php echo isset($posts['start_time'])?$posts['start_time']:''?>">
                                </span>
                                <font>至</font>
                                <span class="t_time">
                                    <input name="end_time" type="text" id="datepicker2"
                                           class="datepicker moba" value="<?php echo isset($posts['end_time'])?$posts['end_time']:''?>">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>其它</div>
                    <div class="con_right">
                        <div class="hotel_star clearfix b_week">
                            <div class="float_left">不执行日</div>
                            <div class="input_txt input_checkbox p_l_4 w_600">
                                <?php $day = array(1=>'星期一',2=>'星期二',3=>'星期三',4=>'星期四',5=>'星期五',6=>'星期六',7=>'星期日')?>
                                <?php foreach($day as $k=>$v){?>
                                <div>
                                    <input type="checkbox" id="week_<?php echo $k;?>" name="no_exec_day[]" value="<?php echo $k?>"  <?php echo isset($no_exec_day)&&in_array($k,$no_exec_day)?' checked="checked" ':''?>>
                                    <label for="week_<?php echo $k;?>"><?php echo $v;?></label>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="jingwei">
                            <div class="">同步状态</div>
                            <div class="input_txt">
	                            每人
	                            <select name="date" class="w_80">
                                    <option value="d" <?php echo  isset($gift_limit[0])&&$gift_limit[0]=='d'?' selected ':''?> >每天</option>
                                    <option value="m" <?php echo  isset($gift_limit[0])&&$gift_limit[0]=='m'?' selected ':''?>>每月</option>
                                    <option value="y" <?php echo  isset($gift_limit[0])&&$gift_limit[0]=='y'?' selected ':''?>>每年</option>
	                            </select>
	                            可享受
	                            <input type="text" class="w_180" name="use_count" placeholder="" value="<?php echo !empty($gift_limit[1]) ? $gift_limit[1] : '1'?>">
	                            次 此优惠
                        	</div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">状态</div>
                            <div class="input_txt">
                            	<select name="status" class="w_450">
                                    <option value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' selected ':''?>>启用</option>
                                    <option value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' selected ':''?>>不启用</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg_fff" style="padding:15px;">
                    <button id="set_btn_save" type="submit" class="fom_btn">保存</button>
                    <input name="submit" type="hidden" value="submit">
                </div>
            </form>
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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>

<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->

<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/areaData.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/layDate.js"></script>
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
<script type="text/javascript">
        $(function() {
            $('.star_77,.star_66').change(function(){
                if($('.star_77:checked').val()){
                    $('.b_week').show();
                    $('.b_s_data').hide();
                }else{
                    $('.b_week').hide();
                    $('.b_s_data').show();
                }
            });
         $("#file >input").change(function(e){
             var file = this.files[0];
             var imageType = /image.*/;
             if(file.type.match(imageType)){
                 var reader = new FileReader();
                 reader.onload=function(){
                    $(".file_img_list").append($('<div"><div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+reader.result+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div><div class="add_img_box" style="float:left;width:77px;height:77px;position:relative;margin-right:20px;"><div style="border-radius:50%;overflow:hidden;"><img style="width:77px;height:77px;overflow:hidden;" src="'+reader.result+'"/></div></div></div>'));
                    $('#file').hide();
                    $('.add_img_box').delegate('.img_close','click',function(){
                        $(this).parent().parent().remove();
                        $('#file').show();
                        $("#file >input").val('');
                    })
                 }
                reader.readAsDataURL(file);
            }
        });

        //  $('.add_img_box').delegate('.img_close','click',function(){
        //     $(this).parent().parent().remove();
        //     $('#file').show();
        //     $("#file >input").val('');
        // })
        //图片上传排版end
    });
</script>
<script>
$(function () {
    var commonItems = [
        'undo', 'redo', '|','cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat',  '|',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'image', 'multiimage',
        'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
        'anchor', 'link', 'unlink'
    ];

    KindEditor.ready(function(K) {
        //订购须知
        // var editor1 = K.create('textarea[name="order_notice"]', {
        //     cssPath : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
        //     uploadJson : 'http://mp.iwide.cn/index.php/basic/upload/kind_do_upload?t=images&p=inter_id|soma|product_package|order_notice&token=test',
        //     fileManagerJson : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/php/file_manager_json.php',
        //     allowFileManager : true,
        //     resizeType : 1,
        //     items : commonItems,
        //     afterCreate : function() {
        //         setTimeout(function(){
        //             $('.ke-container').css('width','');
        //         },1)
        //     }
        // });

        //图文详情
        var editor2 = K.create('textarea[name="img_detail"]', {
            cssPath : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : 'http://mp.iwide.cn/index.php/basic/upload/kind_do_upload?t=images&p=inter_id|soma|product_package|img_detail&token=test',
            fileManagerJson : 'http://mp.iwide.cn/public/AdminLTE/plugins/kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            resizeType : 1,
            items : commonItems,
            afterCreate : function() {
                setTimeout(function(){
                    $('.ke-container').css('width','');
                    $('.ke-edit').height(300);
                    $('.ke-edit-iframe').height(300);
                },1)
            }
        });
        prettyPrint();
    });

});


</script>
</body>
</html>
