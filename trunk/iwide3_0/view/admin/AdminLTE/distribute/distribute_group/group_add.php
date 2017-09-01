<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datepicker/datepicker3.css">
<!--[if lt IE 9]>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
</head>
<style>
.list{ float:left; width:200px;overflow:auto; height:200px; margin-right:15px; padding:10px;}
.list li{margin-bottom:5px; white-space:nowrap; }
</style>
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
            <h1><?php echo isset($breadcrumb_array['action'])? $breadcrumb_array['action']: ''; ?>
                <small></small>
            </h1>
            <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>-->
        <!-- Main content -->
        <section class="content">

            <?php echo $this->session->show_put_msg(); ?>
            <?php //$pk= $model->table_primary_key(); ?>
            <!-- Horizontal Form -->
               <!-- <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $type == 1? '手动': '自动'; ?>分组信息</h3>
                </div>-->
                    <?php echo form_open('distribute/distribute_group/group_add',array('id'=>'tosave','class'=>'form-horizontal','enctype'=>'multipart/form-data','onsubmit'=>'return sub();' ))?>
			<div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">基本信息</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>分组名称</div>
                        <div class="input flexgrow"><input type="text" name="group_name" value="<?php echo isset($posts['group_name'])?$posts['group_name']:''?>"></div>
                    </div>
                    <div>
                        <div>开始时间</div>
                        <div class="input flexgrow"><input class="form_datetime" data-date-format="yyyy-mm-dd" name="begin_time" aria-controls="data-grid" value="<?php echo isset($posts['start_time'])?date('Y-m-d',$posts['start_time']):''?>"></div>
                    </div>
                    <div>
                        <div>结束时间</div>
                        <div class="input flexgrow"><input class="form_datetime" data-date-format="yyyy-mm-dd" name="end_time" placeholder="" aria-controls="data-grid" value="<?php echo isset($posts['end_time'])?date('Y-m-d',$posts['end_time']):''?>"></div>
                    </div>
            	</div>
            </div>
                    <?php if($type == 2){?>
			<div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">核定配置</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>核定周期</div>
                        <div class="input flexgrow">
                            <select name="check_date">
                                <option value="1" <?php echo isset($posts['check_date'])&&$posts['check_date']==1?' selected ':''?>>周</option>
                                <option value="2" <?php echo isset($posts['check_date'])&&$posts['check_date']==2?' selected ':''?>>月</option>
                                </select>
                        </div>
                    </div>
                    <div>
                        <div>核定来源</div>
                        <div class="input flexgrow">
                            <select name="source" id="select_source">
                                <option value="-1" >选择</option>
                                <option value="1" <?php echo isset($posts['source'])&&$posts['source']==1?' selected ':''?>>订房</option>
                                <option value="2" <?php echo isset($posts['source'])&&$posts['source']==2?' selected ':''?>>商城</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>核定方式</div>
                        <div class="input flexgrow">
                            <select name="check_type" id="select_check_type">
                              <option value="1" <?php echo isset($posts['check_type'])&&$posts['check_type']==1?' selected ':''?>>间夜</option>
                                <option value="2" <?php echo isset($posts['check_type'])&&$posts['check_type']==2?' selected ':''?>>订单数</option>
                                <option value="3" <?php echo isset($posts['check_type'])&&$posts['check_type']==3?' selected ':''?>>交易额</option>
                            </select>

                        </div>
                    </div>
                    <div>
                        <div>核定数量</div>
                        <div class="input flexgrow"><input type="text" name="check_count" value="<?php echo isset($posts['check_count'])?$posts['check_count']:0?>"></div>
                    </div>
            	</div>
            </div>
             <?php }?>
			<div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">适用对象</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div class="flex_aligntop">适用对象</div>
                        <div class="flexgrow" style="max-width:100%">
                            <ul class="list bd" id="list_1">
                                <li class="checkall"><label class="check"><input value='' type="checkbox" ><span class="diyradio"><tt></tt></span>所有酒店</label></li>
                                <?php foreach($hotel as $key=>$name) {?>
                                <li class="checksingle">
                                    <label class="check"><input value='<?php echo $name['hotel_id'];?>' <?php echo isset($group_hotel)&&in_array($name['hotel_id'],$group_hotel)?' checked ':''?> name="hotel[]"  type="checkbox" ><span class="diyradio"><tt></tt></span><?php echo $name['name'];?></label>
                                </li>
                                <?php } ?>
                            </ul>
                            <ul class="list bd" id="list_2">
                               <li class="checkall"><label class="check"><input value='' type="checkbox"><span class="diyradio"><tt></tt></span>全选</label></li>
                                <?php foreach($department as $key=>$name) {
                                        if(empty($name['master_dept']))continue;
                                    ?>
                                <li class="checksingle"><label class="check"><input value='<?php echo $name['master_dept'];?>' <?php echo isset($group_department)&&in_array($name['master_dept'],$group_department)?' checked ':''?>  name="department[]" title='<?php echo $name['master_dept'];?>' type="checkbox" ><span class="diyradio"><tt></tt></span><?php echo $name['master_dept'];?></label>
                                </li>
                                <?php } ?>
                            </ul>
                    <?php if($type == 1){?>
                            <ul class="list bd" id="list_3">
                               <li class="checkall"><label class="check"><input value='' type="checkbox"><span class="diyradio"><tt></tt></span>全选</label></li>
                                <?php
                                    if(isset($dept_staff)){
                                    foreach($dept_staff as $key=>$name) {
                                    if(empty($name['name']))continue;
                                    ?>
                                <li class="checksingle"><label class="check"><input value='<?php echo $name['qrcode_id'];?>' <?php echo isset($sd_member_ids)&&in_array($name['qrcode_id'],$sd_member_ids)?' checked ':''?>  name="dept_staff[]" title='<?php echo $name['name'];?>' type="checkbox" ><span class="diyradio"><tt></tt></span><?php echo $name['qrcode_id'].$name['name'];?></label>
                                </li>
                                <?php } ?>
                            </ul>
                    <?php }}?>
                        </div>
                    </div>
                </div>
            </div>
            <input name="type" type="hidden" value="<?php echo isset($type)?$type:1?>">
            <input name="ids" type="hidden" value="<?php echo isset($group_id)?$group_id:''?>">
            <input name="submit" type="hidden" value="submit">
            <!-- /.box-body -->
            <div class="bg_fff bd center pad10">
            <button class="bg_minor button maright" type="reset">清空</button>
            <button class="bg_main button maright" type="submit" id="set_btn_save">保存</button>            
            </div>
            <!-- /.box-footer -->
            <?php echo form_close() ?>
            <!-- /.box -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

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

    //ajax获取部门接口
    function get_hotel_department(hotel_id)
    {
        $.ajax({
            dataType:'json',
            type:'post',
            data:{
                'hotel_id':hotel_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            url:'<?php echo site_url('/distribute/distribute_group/hotel_department')?>',
            success:function(rs)
            {
                if (rs.status == 1)
                {
                    if (rs.data.length >0)
                    {
                        $('#list_2').html(radio);
                        for(var i=0;i<rs.data.length;i++)
                        {
                            if ((rs.data[i].master_dept != null) && (rs.data[i].master_dept !=''))
                            {
                                $('#list_2').append(getradio(rs.data[i].master_dept,rs.data[i].master_dept));
                            }

                        }
                    }
                }
            }
        })
    }

    //ajax 获取酒店职工接口
    function get_hotel_staff()
    {

    }

function saveinput(){
	
}
function checkall(that){
	var bool = $('input',that).get(0).checked;
	$(that).parent().find('input').each(function(){
		$(this).get(0).checked=bool;
	});
}
function checksingle(that){
	var bool = $('input',that).get(0).checked;
	if(bool){
		$(that).siblings(".checksingle").each(function(){
			if( $('input',this).get(0).checked ==false){
				bool =false;
			}
		});
	}
	$(that).parents('ul').find('.checkall input').eq(0).checked = bool;
}
var radio = $('<li class="checkall"><label class="check"><input type="checkbox"><span class="diyradio"><tt></tt></span>全选</label></li>');
radio.get(0).onclick=function(){checkall(this);}

$('.checksingle').click(function(){checksingle(this)});
$('.checkall').click(function(){checkall(this)});

function getradio(str,val){
	var r = $('<li class="checksingle"><label class="check"><input value="'+val+'" type="checkbox"><span class="diyradio"><tt></tt></span>'+str+'</label></li>');
	r.get(0).onclick=function(){checksingle(this)}
	return r;
}


$('#list_1').on('click','.checksingle',function(){

    var val = $('input',this).val();
    var hotel_id = new Array();
    $(this).parent().find('.checksingle input:checked').each(function(){
        hotel_id.push( $(this).val());
    });
    hotel_id = hotel_id.toString();
    //get_hotel_department(hotel_id);
});



$('#list_2').on('click','.checksingle',function(){

	
});
    $(function(){
        $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
        $('#select_source').change(function(){
            var val = $('#select_source').val();
            if(val == 2){//商城不需要间夜
                $("#select_check_type").empty();
                $("#select_check_type").append("<option value='2'>订单数</option>");
                $("#select_check_type").append("<option value='3'>交易额</option>");
                //$("#select_check_type option[value='1']").remove();
            }else{
                $("#select_check_type").empty();
                $("#select_check_type").append("<option value='1'>间夜</option>");
                $("#select_check_type").append("<option value='2'>订单数</option>");
                $("#select_check_type").append("<option value='3'>交易额</option>");
            }
        });


    });
    function sub(){
        if($("input[name='group_name']").val() == ''){
            alert('分组名不能为空');
            return false;
        }
        if($("input[name='begin_time']").val() == '' || $("input[name='end_time']").val() == ''){
            alert('日期不能为空');
            return false;
        }
        if($('#select_source').val() < 0 && $("input[name='type']").val()==2){
            alert('请选择核定来源为空');
            return false;
        }
        if(($("input[name='check_count']").val() == 0 || $("input[name='check_count']").val() == '') && $("input[name='type']").val() == 2){
            alert('核定数量不能为空');
            return false;
        }

        //$('#tosave').form.submit();
    }


</script>
