<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/images/laydate12.css">
<link type="text/css" rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/need/laydate.css">
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
.w_450{width:450px !important;border:1px solid #d7e0f1 !important;}
.w_80{width:80px;}
.w_180{width:180px !important;}
.bg_fff{background:#fff;}
.bg_3f51b5{background:#3f51b5;}
.bg_ff503f{background:#ff503f;}
.bg_4caf50{background:#4caf50;}
.clearfix:after{content: "" ;display:block;height:0;clear:both;visibility:hidden;}
.display_none{display:none !important;}
.relative{position:relative;}
.absolute{position:absolute;}
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
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio1.png) no-repeat center left;background-size:13%;width:155px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/radio2.png) no-repeat center left;background-size:13%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(<?php echo base_url(FD_PUBLIC) ?>/js/img/bg2.png) no-repeat center left;background-size:15%;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
#file >input{text-indent:-9999px; height:80px;line-height:60px;width:80px;background-image:url("<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png");}
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
.drow_list{ display:none; position:absolute;width:100%; top:100%; left:0; background:#fff; border:1px solid #e4e4e4; padding:0; max-height:300px; overflow:auto; z-index:999}
.drow_list li{ height:35px; padding-left:15px; line-height:35px; list-style:none; cursor:pointer}
.drow_list li:hover{ background:#f1f1f1}
.drow_list li.cur{background:#ff9900; color:#fff}
#drowdown:hover .drow_list{display:block}
</style>

    <?php echo $this->session->show_put_msg(); ?>
    <?php $pk= $model->table_primary_key(); ?>
<div class="over_x">
    <div class="content-wrapper" style="min-width: 1050px; min-height: 775px;">
        <div class="banner bg_fff p_0_20">编辑场景or新增场景</div>
        <div class="contents">
            <?php
            echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data'), array($pk=>$model->m_get($pk) ) ); ?>

            <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
                    <div class="con_right">
                        <div class="hottel_name ">
                            <div class="">场景名称</div>
                            <div class="input_txt"><input type="text" name="name" value="<?php if(!empty($model->m_get('name'))) echo $model->m_get('name')?>"></div>
                        </div>
                        <div class="address">
                            <div class="">所属酒店</div>
                            <div class="input_txt">
                                <select class="selectpicker w_450" data-live-search="true" id="el_hotel" name="hotel_id">
                                    <?php if(!empty($hotel_info)){
                                        foreach($hotel_info as $k=>$v){
                                    ?>
                                 <option value="<?php echo $k?>" <?php if($model->m_get('hotel_id')==$k)
                                     echo " selected='selected' "?>><?php echo $v?></option>
                                    <?}}?>
                                </select>
                            </div>
                        </div>
                        <div class="hotel_star">
                            <div class="">显示不优惠金额</div>
                            <div class="input_txt input_radio">
                                <div>
                                    <input type="radio" id="star_16" name="no_sale" checked="checked" <?php if(!empty($model->m_get('no_sale')&&$model->m_get('no_sale')==0)) echo 'checked="checked"'?> value="0">
                                    <label for="star_16">不显示</label>
                                </div>
                                <div>
                                    <input class="btn_number" type="radio" id="star_17" <?php if(!empty($model->m_get('no_sale')&&$model->m_get('no_sale')==1)) echo 'checked="checked"'?> name="no_sale" value="1">
                                    <label for="star_17">显示</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
                <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
                <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
                <!--<div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>优惠券使用</div>
                    <div class="con_right">
                        <div class="hotel_star">
                            <div class="">优惠券抵减</div>
                            <div class="input_txt input_radio">
                                <div>
                                    <input type="radio" id="star_6" checked="checked" <?php /*echo $model->m_get('coupon_status')==0?'checked':''*/?> name="coupon_status"  value="0" onclick="check_status(0)">
                                    <label for="star_6">不启用</label>
                                </div>
                                <div>
                                    <input type="radio" id="star_7" <?php /*echo $model->m_get('coupon_status')==1?'checked':''*/?> name="coupon_status" value="1" onclick="check_status(1)">
                                    <label for="star_7">启用</label>
                                </div>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">可用券</div>
                            <div class="input_txt">
                                <select id="coupon_ids" class="selectpicker show-tick form-control w_450" multiple data-live-search="false">
                                    <?php
/*                                    if(!empty($coupon)){
                                        foreach($coupon as $key=>$val){*/?>
                                            <option value="<?php /*echo $val['card_id' ]; */?>" ><?php	/*echo $val['title']; */?></option>
                                        <?php /*}} */?>
                                </select>
                                <input type="hidden" name="coupon_ids" id="coupon_val" value="">
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">可用数量</div>
                            <div class="input_txt relative">
                                <input type="text" name="coupon_use_count" value="<?php /*echo !empty($model->m_get('coupon_use_count'))?$model->m_get('coupon_use_count'):''*/?>" id="coupon_use_count">
                                <span class="absolute" style="top:1px;right:8px;">张/单</span>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>其它</div>
                    <div class="con_right">
                        <div class="hottel_name ">
                            <div class="">按钮名称</div>
                            <div class="input_txt"><input type="text" name="store_name" value="<?php echo !empty($model->m_get('store_name'))?$model->m_get('store_name'):''?>"></div>
                        </div>

                        <div class="hottel_name ">
                            <div class="">跳转链接</div>
                            <div class="input_txt">
                                <input type="text" name="store_url" value="<?php echo !empty($model->m_get('store_url'))?$model->m_get('store_url'):''?>">
                            </div>
                        </div>
                        <div class="hotel_star">
                            <div class="">场景状态</div>
                            <div class="input_txt input_radio">
                                <div>
                                    <input class="" type="radio" id="star_2" name="status"  value="0" <?php echo $model->m_get('status')==0?'checked':''?>>
                                    <label for="star_2">不启用</label>
                                </div>
                                <div>
                                    <input type="radio" id="star_3" name="status" value="1"  <?php echo $model->m_get('status')==1?'checked':''?>>
                                    <label for="star_3">启用</label>                                    
                                </div>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">模板消息通知</div>
                            <div class="input_txt w_450" style="position:relative" id="drowdown">
                                <input placeholder="选择" class="input" type="text"  id="search_hotel" value="<?php if (!empty($show_saler_name)){echo $show_saler_name;}else{echo '';}?>">
                                <div class="silde_layer drow_list">
                                    <?php if(!empty($salers)){?>
                                        <?php foreach ($salers as $k => $v):?>
                                            <li data="<?php echo $k;?>"  multi="true"  <?php echo !empty($msgsaler)&&(in_array($k,explode(',',$msgsaler)))?'class="cur"':''?>><?=$v?></li>

                                        <?php endforeach;?>
                                    <?php }?>
                                </div>
                                <input type="hidden" id="hidd_msgsaler" name="msgsaler" value="<?php echo isset($msgsaler) &&!empty($msgsaler)?$msgsaler:''?>">

                            </div>
                            </div>
                        <div class="hottel_name ">
                            <div class="">分组</div>
                                <div class="input_txt">
                                    <select class="selectpicker w_450" data-live-search="true"  name="group_id">
                                        <?php if(!empty($groups)){
                                            foreach($groups as $k=>$v){
                                                ?>
                                                <option value="<?php echo $k?>" <?php if($model->m_get('group_id')==$k)
                                                    echo " selected='selected' "?>><?php echo $v?></option>
                                            <?}}?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg_fff" style="padding:15px;">
                    <button class="fom_btn" type="submit">保存</button>
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
    $(function(){
        var coupon_status = '<?php echo empty($model->m_get('coupon_status'))?0:$model->m_get('coupon_status')?>';
        //check_status(coupon_status);
        $('#coupon_ids').change(function(){
            $('#coupon_val').val($(this).val());
        });
        $('#coupon_ids').selectpicker({
            'selectedText': 'cat'
        });
        var arr = '<?php echo !empty($model->m_get('coupon_ids'))?$model->m_get('coupon_ids'):''?>';
        if(arr != ''){
            var str=arr.split(',');
            $('#coupon_ids').selectpicker('val', str);
        }
    });
    function check_status(status){
        if(status == 0){
            $('#coupon_use_count').attr('disabled',true);
            $('#coupon_ids').attr('disabled',true);
        }else if(status == 1){
            $('#coupon_use_count').attr('disabled',false);
            $('#coupon_ids').attr('disabled',false);
        }
    }
    $('#search_hotel').bind('input propertychange',function(){
        var val=$(this).val();
        if(val!=''|| val!=undefined){
            for(var i=0;i<$('.drow_list li').length;i++){
                if ( $('.drow_list li').eq(i).html().indexOf(val)>=0)
                    $('.drow_list li').eq(i).show();
                else
                    $('.drow_list li').eq(i).hide();
            }
        }else{
            $('.drow_list li').show();
        }
    });
    function select_click(obj){
        $('#search_hotel').val($(obj).text());
        $("input[name='msgsaler']").val($(obj).val());
        $(obj).addClass('cur').siblings().removeClass('cur');
    }
    function sildeClick(){
        var html='', val = '';
        if($(this).attr('multi')!=undefined&&$(this).attr('multi')=='true'){
            $(this).toggleClass('cur');
            html = [];
            val  = [];
            $(this).parent().find('.cur').each(function() {
                html.push($(this).html());
                val.push($(this).attr('data'));
            });
        }else{
            html=$(this).html();
            val =$(this).attr('data');
        }console.log(html);console.log(val);
        $('#search_hotel').val(html.toString());
        $('#hidd_msgsaler').val(val.toString());
    }
    $('.silde_layer>*').bind('click',sildeClick);
    $("body").on('change','#el_hotel',function(){
        $.post('/index.php/okpay/types/get_saler_info',{
            'hotel_id':$('#el_hotel').val(),
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },function(data){
            $('.drow_list').empty();
            if(data.errcode==0){
                for(i in data.res ){
                    var html = '';
                    html = $('<li multi="true" data="'+data.res[i].qrcode_id+'">'+data.res[i].name+'</li>');
                    html.get(0).onclick=sildeClick;
                    $('.drow_list').append(html);
                }
            }
            else{
                alert('通知,'+data.msg);
            }

        },'json')
    });
</script>

</body>
</html>
