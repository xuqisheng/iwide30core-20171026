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
  src: url('/public/newfont/iconfont.eot');
  src: url('/public/newfont/iconfont.eot?#iefix') format('embedded-opentype'),
  url('/public/newfont/iconfont.woff') format('woff'),
  url('/public/newfont/iconfont.ttf') format('truetype'),
  url('/public/newfont/iconfont.svg#iconfont') format('svg');
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
.input_radio >div >input+label{font-weight:normal;text-indent:25px;background:url(/public/js/img/radio1.png) no-repeat center left;background-size:13%;width:155px;height:30px;line-height:30px;}
.input_radio >div >input:checked+label{background:url(/public/js/img/radio2.png) no-repeat center left;background-size:13%;}
.block{display:inline-block;height:18px;width:4px;vertical-align: middle;margin-right:5px;}
.introduce{width:450px;height:150px;margin-left:4px;resize:vertical;}
.add_img{width:77px;height:77px;background:url(/public/js/img/214598012363739107.png) no-repeat;background-size:100%;margin-right:20px;float:left;}

.input_checkbox >div >input{display:none;}
.input_checkbox >div >input+label{font-weight:normal;text-indent:25px;background:url(/public/js/img/bg.png) no-repeat center left;background-size:15%;width:110px;height:30px;line-height:30px;}
.input_checkbox >div >input:checked+label{background:url(/public/js/img/bg2.png) no-repeat center left;background-size:15%;}

.fom_btn{background:#ff9900;color:#fff;outline:none;border:0px;padding:6px 25px;border-radius:3px;margin:auto;display:block;}
.add_img_box:hover > .img_close{display:block !important;cursor:pointer;}
#file >input{text-indent:-9999px; height:80px;line-height:60px;width:80px;background-image:url("/public/js/img/upload.png");}
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
    <link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css">
    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>
<div class="over_x">
    <?php echo $this->session->show_put_msg(); ?>
    <div class="content-wrapper" style="min-width: 1050px; min-height: 775px;">
        <div class="banner bg_fff p_0_20">新增随机立减</div>
        <div class="contents">
            <?php if(isset($id)){?>
                <?php echo form_open('okpay/activities/sj_edit?ids='.$id,array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
            <?php }else{?>
                <?php echo form_open('okpay/activities/sj_add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
            <?php }?>
                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
                    <div class="con_right">
                        <div class="address">
                            <div class="">所属酒店</div>
                            <div class="input_txt">
                            	<select class="selectpicker w_450" data-live-search="true" name="hotel_id" id = "el_hotel_id">
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
                            	<select class="w_450" id="el_type_id" name="type_id">
                                    <?php if(!empty($type)){
                                        foreach($type as $tk=>$tv){
                                            ?>
                                            <option value="<?php echo $tv['id']?>" <?php echo isset($posts['type_id'])&&$posts['type_id']==$tv['id']?' selected ':''?>><?php echo $tv['name']?></option>
                                        <?php }}else{?>
                                        <option value="">该酒店没设置场景</option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>活动配置</div>
                    <div class="con_right">
                        <div class="hottel_name ">
                            <div class="">活动名称</div>
                            <div class="input_txt"><input type="text"name="title" placeholder="" value="<?php echo isset($posts['title'])?$posts['title']:''?>"></div>
                        </div>
                        <div class="hotel_star">
                            <div class="">满减方式</div>
                            <div class="input_txt input_radio">
                                随机立减
                            </div>
                            <input type="hidden" name="isfor" value="3"/>
                        </div>
                        <div class="hottel_name ">
                            <div class="">消费起额</div>
                            <div class="input_txt"><input type="text"name="isfor_money" placeholder="" value="<?php echo isset($posts['isfor_money'])?$posts['isfor_money']:'0.01'?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">立减金额1</div>
                            <div class="input_txt">
	                            消费金额
	                            <input type="text" class="w_80" id="con1_min" name="con1[]" placeholder="必填" value="<?php echo isset($config['con1'][0])?$config['con1'][0]:''?>">% -
	                            <input type="text" class="w_80"  id="con1_max" name="con1[]" placeholder="必填" value="<?php echo isset($config['con1'][1])?$config['con1'][1]:''?>">%，概率
	                            <input type="text" class="w_80"  id="con1_rate" name="con1[]" placeholder="必填" value="<?php echo isset($config['con1'][2])?$config['con1'][2]:''?>">%
                        	</div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">立减金额2</div>
                            <div class="input_txt">
	                            消费金额
	                            <input type="text" class="w_80" id="con2_min" name="con2[]" placeholder="选填" value="<?php echo isset($config['con2'][0])?$config['con2'][0]:''?>">% -
	                            <input type="text" class="w_80" id="con2_max" name="con2[]" placeholder="选填" value="<?php echo isset($config['con2'][1])?$config['con2'][1]:''?>">%，概率
	                            <input type="text" class="w_80" id="con2_rate" name="con2[]" placeholder="选填" value="<?php echo isset($config['con2'][2])?$config['con2'][2]:''?>">%
                        	</div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">立减金额3</div>
                            <div class="input_txt">
	                            消费金额
	                            <input type="text" class="w_80" id="con3_min" name="con3[]" placeholder="选填" value="<?php echo isset($config['con3'][0])?$config['con3'][0]:''?>">% -
	                            <input type="text" class="w_80" id="con3_max" name="con3[]" placeholder="选填" value="<?php echo isset($config['con3'][1])?$config['con3'][1]:''?>">%，概率
	                            <input type="text" class="w_80" id="con3_rate" name="con3[]" placeholder="选填" value="<?php echo isset($config['con3'][2])?$config['con3'][2]:''?>">%
                        	</div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">活动时间</div>
                            <div class="input_txt"><span class="t_time"><input name="begin_time" type="text" id="datepicker" class="datepicker moba" value="<?php echo isset($posts['begin_time'])?date('Y-m-d',$posts['begin_time']):''?>"></span>
                            <font>至</font>
                            <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="datepicker moba" value="<?php echo isset($posts['end_time'])?date('Y-m-d',$posts['end_time']):''?>"></span></div>
                        </div>
                    </div>
                </div>

                <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_ff503f"></span>其它</div>
                    <div class="con_right">
                        <div class="hotel_star clearfix b_week">
                            <div class="float_left">不执行日</div>
                            <div class="input_txt input_checkbox p_l_4 w_600">
                                <?php $day = array(1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日')?>

                                <?php foreach($day as $k=>$v){?>
                                    <div>
                                        <input type="checkbox" id="wf<?php echo $k?>" name="no_exec_day[]" <?php echo isset($no_exec_day)&&in_array($k,$no_exec_day)?' checked="checked" ':''?> value="<?php echo $k?>">
                                        <label for="wf<?php echo $k?>"><?php echo $v?></label>
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
                                <input type="text" class="w_180" name="use_count" placeholder="" value="<?php echo isset($gift_limit[1])?$gift_limit[1]:'1'?>">
                                次 此优惠
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">状态</div>
                            <div class="input_txt">
                                <select class="w_450" name="status">
                                    <option value="1" <?php echo isset($posts['status'])&&$posts['status']==1?' selected ':''?>>启用</option>
                                    <option value="0" <?php echo isset($posts['status'])&&$posts['status']==0?' selected ':''?>>不启用</option>
                                </select>
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
    //$(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
    $(document).ready(function(){

        $("#el_hotel_id").bind("change",function(){
            var hotleid = $(this).val();
            if(hotleid == null && hotleid == ""){
                alert("酒店id 缺失，无法加载场景");
                return false;
            }
            $.ajax({
                type: "post",
                url: "<?php echo site_url('okpay/activities/get_type_list')?>?r="+Math.random(),
                dataType:"json",
                data: {"hotelid":$(this).val(),"<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash();?>"},
                success:function(msg){
                    var html = " ";
                    if(msg['status'] == 1){
                        var type_data = eval(msg['data']);
                        for(var d in type_data){
                            html = html+'<option value="'+type_data[d]['id']+'">'+type_data[d]['name']+'</option>';
                        }
                        $("#el_type_id").html(html);
                    }else{
                        html = html + '<option value="">无场景</option>';
                        $("#el_type_id").html(html);
                    }
                },
                error:function(msg){
                }
            });
        });

    });

    function sub(){
        if($("input[name='title']").val() == ''){
            alert('名称不能为空');
            return false;
        }
        if($("input[name='isfor_money']").val() == ''){
            alert('消费起额不能为空');
            return false;
        }
        if($("input[name='begin_time']").val() == '' || $("input[name='end_time']").val() == ''){
            alert('日期不能为空');
            return false;
        }
        if($("#el_type_id").val() == ''){
            alert('场景不能为空');
            return false;
        }
        var con1_min = con1_max = con1_rate = con2_min = con2_max = con2_rate = con3_min = con3_max = con3_rate = '';
        //规则
        if($("#con1_min").val()=='' || $("#con1_max").val()=='' || $('#con1_rate').val()==''){//必填 不能为空
            alert('请完善规则1的内容');
            return false;
        }else if(parseFloat($("#con1_min").val()) >= parseFloat($("#con1_max").val())){
            alert('错误提示：规则1中最大百分比必须需大于最小百分比！');
            return false;
        }else{
            con1_min =  parseFloat($("#con1_min").val());
            con1_max = parseFloat($("#con1_max").val());
            con1_rate = parseFloat($('#con1_rate').val());
        }
        //规则2
        if($("#con2_min").val()=='' && $("#con2_max").val()=='' && $('#con2_rate').val()==''){
            //return true;
        }else if($("#con2_min").val()=='' || $("#con2_max").val()=='' || $('#con2_rate').val()==''){
            alert('请完善规则2的内容');
            return false;
        }else if(parseFloat($("#con2_min").val()) >= parseFloat($("#con2_max").val())){
            alert('错误提示：规则2中最大百分比必须需大于最小百分比！');
            return false;
        }else{
            con2_min =  parseFloat($("#con2_min").val());
            con2_max = parseFloat($("#con2_max").val());
            con2_rate = parseFloat($('#con2_rate').val());
        }
        //规则3
        if($("#con3_min").val()=='' && $("#con3_max").val()=='' && $('#con3_rate').val()==''){
            //return true;
        }else if($("#con3_min").val()=='' || $("#con3_max").val()=='' || $('#con3_rate').val()==''){
            alert('请完善规则3的内容');
            return false;
        }else if(parseFloat($("#con3_min").val()) >= parseFloat($("#con3_max").val())){
            alert('错误提示：规则3中最大百分比必须需大于最小百分比！');
            return false;
        }else{
            con3_min =  parseFloat($("#con3_min").val());
            con3_rate = parseFloat($('#con3_rate').val());
        }
        //比较
        if(con3_min != '' && con2_max != ''){
            if(con3_min <= con2_max){
                alert('错误提示：规则3最小百分比需大于规则2最大百分比');
                return false;
            }
        }
        if(con2_min != '' && con1_max != ''){
            if(con2_min <= con1_max){
                alert('错误提示：规则2最小百分比需大于规则1最大百分比');
                return false;
            }
        }
        if(con3_min != '' && con1_max != ''){
            if(con3_min <= con1_max){
                alert('错误提示：规则3最小百分比需大于规则1最大百分比');
                return false;
            }
        }
        //比例值
        if(con1_rate != '' && con2_rate != '' && con3_rate != ''){
            if(con1_rate+con2_rate+con3_rate != 100){
                alert('错误提示：有效规则总概率需等于100%');
                return false;
            }
        }else if(con1_rate != '' && con2_rate != ''){
            if(con1_rate+con2_rate != 100){
                alert('错误提示：有效规则总概率需等于100%');
                return false;
            }
        }else if(con1_rate != '' && con3_rate != ''){
            if(con1_rate+con2_rate != 100){
                alert('错误提示：有效规则总概率需等于100%');
                return false;
            }
        }else if(con1_rate != 100){
            alert('错误提示：有效规则总概率需等于100%');
            return false;
        }
        return true;
        //$('#tosave').form.submit();
    }
</script>

</body>
</html>
