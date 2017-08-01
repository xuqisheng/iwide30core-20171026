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
.w_80{width:80px;}
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
    <?php echo $this->session->show_put_msg(); ?>
    <?php $pk= $model->table_primary_key(); ?>
<div class="over_x">
    <div class="content-wrapper" style="min-width: 1050px; min-height: 775px;">
        <div class="banner bg_fff p_0_20"><?php echo ( $this->input->post($pk) ) ? '编辑': '新增'; ?>活动</div>
        <div class="contents">
            <?php
            echo form_open( EA_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal'), array($pk=>$model->m_get($pk) ) ); ?>

            <div class="contents_list bg_fff">
                    <div class="con_left"><span class="block bg_3f51b5"></span>基本信息</div>
                    <div class="con_right">
                        <div class="address">
                            <div class="">所属酒店</div>
                            <div class="input_txt">
                            	<select class="selectpicker w_450" data-live-search="true" name="hotel_id" id="el_hotel_id">
                                  <option value="-1">--请选择--</option>
                                    <?php if(!empty($hotels)){
                                        foreach($hotels as $k=>$v){
                                    ?>
                                  <option value="<?php echo $v['hotel_id']?>" <?php if($model->m_get('hotel_id')==$v['hotel_id'])
                                      echo " selected='selected' "?>><?php echo $v['name']?></option>
                                    <?php }}?>
                                </select>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">所属场景</div>
                            <div class="input_txt">
                            	<select class="w_450" name="type_id" id="el_type_id">
                                  <option>--请选择--</option>
                                    <?php
                                    if(!empty($typelist)){
                                    foreach($typelist as $key=>$val){
                                        ?>
                                        <option value="<?php echo $key; ?>" <?php if($model->m_get('type_id')==$key)
                                            echo " selected='selected' "?>><?php	echo $val; ?></option>
                                        <?php
                                    }}
                                    ?>
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
                            <div class="input_txt"><input type="text" name="title" value="<?php echo !empty($model->m_get('title'))?$model->m_get('title'):''?>"></div>
                        </div>
                        <div class="hotel_star">
                            <div class="">满减方式</div>
                            <div class="input_txt input_radio">
                                <div>
                                    <input type="radio" id="star_26" name="isfor" value="1" <?php echo !empty($model->m_get('isfor'))&&$model->m_get('isfor')==1?'checked':''?>>
                                    <label for="star_26">每满减</label>
                                </div>
                                <div>
                                    <input class="" type="radio" id="star_37" name="isfor" value="2" <?php echo !empty($model->m_get('isfor'))&&$model->m_get('isfor')==2?'checked':''?>>
                                    <label for="star_37">单满减</label>
                                </div>
                            </div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">满减金额</div>
                            <div class="input_txt"><input type="text" name="isfor_money" placeholder="满减金额" value="<?php echo !empty($model->m_get('isfor_money'))?$model->m_get('isfor_money'):''?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">减免金额</div>
                            <div class="input_txt"><input type="text" name="discount_amount" placeholder="减免金额" value="<?php echo !empty($model->m_get('discount_amount'))?$model->m_get('discount_amount'):''?>"></div>
                        </div>
                        <div class="hottel_name ">
                            <div class="">活动时间</div>
                            <div class="input_txt"><span class="t_time"><input name="begin_time" type="text" id="datepicker" class="datepicker moba" value="<?php echo !empty($model->m_get('begin_time'))?date('Y-m-d',$model->m_get('begin_time')):''?>"></span>
                            <font>至</font>
                            <span class="t_time"><input name="end_time" type="text" id="datepicker2" class="datepicker moba" value="<?php echo !empty($model->m_get('begin_time'))?date('Y-m-d',$model->m_get('end_time')):''?>"></span></div>
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
                                <?php $no_exec_day = $model->m_get('no_exec_day');
                                $no_exec_day = !empty($no_exec_day)?explode(',',$no_exec_day):array();
                                ?>
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
                            <?php $gift_limit = $model->m_get('gift_limit');
                            $gift_limit = !empty($gift_limit)?explode('|',$gift_limit):array();
                            ?>
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
                                  <option value="0" <?php echo !empty($model->m_get('status')&&$model->m_get('status')==1?'checked':'')?>>未启用</option>
                                  <option value="1" <?php echo !empty($model->m_get('status')&&$model->m_get('status')==0?'checked':'')?>>已启用</option>
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
    $(document).ready(function(){

        $("#el_hotel_id").bind("change",function(){
            var hotleid = $(this).val();
            if(hotleid == null || hotleid == "" || hotleid == "-1"){
                $("#el_type_id").html('');
               // alert("酒店id 缺失，无法加载场景");
                return false;
            }
            $.ajax({
                type: "post",
                url: "<?php echo site_url('okpay/activities/get_type_list')?>?r="+Math.random(),
                dataType:"json",
                data: {"hotelid":$(this).val(),"<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash();?>"},
                success:function(msg){
                    var html = '';
                    if(msg['status'] == 1){
                        var type_data = eval(msg['data']);
                        for(var d in type_data){
                            //html = '<option value="">--请选择--</option>';
                            html= html+'<option value="'+type_data[d]['id']+'">'+type_data[d]['name']+'</option>';
                        }
                        $("#el_type_id").html(html);
                    }else{
                        alert(msg['message']);
                        $("#el_type_id").html(html);
                    }
                },
                error:function(msg){
                }
            });
        });

    });
</script>

</body>
</html>
