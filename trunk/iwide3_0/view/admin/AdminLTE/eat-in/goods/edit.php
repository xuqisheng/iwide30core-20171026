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
	div[required]>div:first-child:before{content:'*'; color:#f00}
	.addimg{margin-right:15px}
	/*规格表格，不需要则不添加到页面*/
	.spectable .multirow { padding:0}
	.spectable .multirow>*{ border-top:1px solid #d9dfe9; padding:5px 0; }
	.spectable .multirow>*:first-child{ border-top-color:transparent}
	.spectable .multirow span{min-height:25px;line-height:25px; display:block}
	.SpecBox .diySpec,.SpecBox .addimg{margin-right:20px}
	.SpecBox .input{width:80px}
	.flex_aligntop{ padding-top:5px}
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <header class="headtitle"><?php echo $breadcrumb_html; ?></header>
<!--        <form action="<?php /*echo site_url('eat-in/goods/add')*/?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="form-horizontal">
-->
        <?php if(isset($ids)){?>
        <?php echo form_open( site_url('eat-in/goods/edit?ids='.$ids), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array() ); ?>
        <?php }else{?>
        <?php echo form_open( site_url('eat-in/goods/add'), array('class'=>'form-horizontal','enctype'=>'multipart/form-data' ), array() ); ?>
<?php }?>
        <section class="content">
            <?php echo $this->session->show_put_msg(); ?>

            <div class="whitetable">
                <div>
                    <span style="border-color:#3f51b5">商品信息</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>商品名称</div>
                        <div class="input flexgrow"><input name="goods_name" value="<?php echo isset($posts['goods_name'])?$posts['goods_name']:''?>"></div>
                    </div>
                    <div>
                        <div>所属店铺<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo isset($posts['hotel_id'])?$posts['hotel_id']:''?>"/></div>
                        <div class="input flexgrow">
                            <select name="shop_id" id="shop_id">
                                <option value="">选择店铺</option>
                                <?php if(!empty($shops)):?>
                                <?php foreach($shops as $k=>$v):?>
                                        <option value="<?php echo $v['shop_id'];?>" iid="<?php echo $v['hotel_id']?>" <?php if(isset($posts['shop_id'])&&$posts['shop_id']==$v['shop_id']){?>selected="selected"<?php }?>><?php echo $v['shop_name'];?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>展位推荐</div>
                        <div class="flexgrow">

                                    <label class="check">
                                        <input name="is_recommend" type="radio" value="1" <?php if(isset($posts['is_recommend'])&&$posts['is_recommend'])echo 'checked';?> />
                                        <span class="diyradio"><tt></tt></span>
                                        是
                                    </label>
                            <label class="check">
                                <input name="is_recommend" type="radio" value="0" <?php if(isset($posts['is_recommend'])&&$posts['is_recommend']==0 || !isset($ids))echo 'checked';?> />
                                <span class="diyradio"><tt></tt></span>
                               否(默认)
                            </label>

                        </div>
                    </div>
                    <div>
                        <div>商品分组</div>
                        <div class="input flexgrow">
                                <select name="group_id" id="group_id">
                                    <option value="">选择分组</option>
                                    <?php if(!empty($group)):?>
                                    <?php foreach($group as $k=>$v):?>
                                        <option value="<?php echo $v['group_id'];?>" <?php if(isset($posts['group_id'])&&$posts['group_id']==$v['group_id']){?>selected="selected"<?php }?>><?php echo $v['group_name'];?></option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="whitetable">
                <div>
                    <span style="border-color:#3f92b5">商品规格</span>
                </div>
                <div class="bd_left list_layout">
                    <div style="padding-bottom:0" id="SpecControls">
                        <div class="flex_aligntop">商品规格</div>
                        <div class="flexgrow" style="max-width:100%">
                            <span class="button void xs marbtm" id="addSpec">添加规格类型</span>
                            <!--- 添加规格模版DIV -->
                            <div class="whiteblock pad10 SpecBox candelete hide" id="model" limit='1'><!-- limit 最多添加3种规格 -->
                                <del></del>
                                <div class="flex flexcenter">
                                    <div class="input maright">
                                        <select class="selectSpec">
                                            <option value="1">颜色</option><option value="2">尺寸</option><option value="3">限购人数</option><option value="4">类型</option><option value="5">其他</option>                                        </select></div>
                                    <label class="check hide" style="display: none;"><input type="checkbox" /><span class="diyradio"><tt></tt></span>添加规格图片</label>
                                </div>
                                <div class="flex flexcenter martop diySpecs">
                                    <span class="textlink addNewSpec"><i class="fa fa-plus"></i> 添加规格</span>
                                </div>
                                <div class="martop trim addimgs"></div>
                            </div>
                            <!-- end -->
                        </div>
                    </div>
                    <div id="SpecSet" style="display:none">
                        <div class="flex_aligntop">商品配置</div>
                        <div class="flexgrow"  style="max-width:100%">
                            <div class="diytable center spectable">
                                <div class="thead"></div>
                                <div class="tbody"></div>
                            </div>
                        </div>
                    </div>
                    <!--div>
                        <div>价格</div>
                        <div class="flexgrow flex">
                            <div class="input"><input name="shop_price" placeholder="" id="el_price_package" value="<?php echo isset($posts['shop_price'])?$posts['shop_price']:''?>"></div>

                        </div>
                    </div-->
                    <div>
                        <div>总库存</div>
                        <div class="input flexgrow"><input readonly name="stock" placeholder="" value=""></div>
                        <div class="hide"><label class="check"><input type="radio"  name="is_show_stock" <?php if(isset($posts['is_show_stock'])&&$posts['is_show_stock']==1)echo 'checked';?> /><span class="diyradio"><tt></tt></span>页面不显示库存</label></div>
                    </div>
                    <div>
                        <div>商品家编码</div>
                        <div class="input flexgrow"><input name="goods_sn" placeholder="" value="<?php echo isset($posts['goods_sn'])?$posts['goods_sn']:''?>"></div>
                    </div>
                </div>
            </div>

            <div class="whitetable">
                <div>
                    <span style="border-color:#ff503f">商品信息</span>
                </div>
                <div class="bd_left list_layout">

                    <div>
                        <div>缩略图</div>
                        <div>
                            <input type="hidden" name="goods_img" id="el_intro_img_d" value='<?php if(!empty($posts['goods_img'])) echo $posts['goods_img'];?>'>
                            <div id="intro_img" class="addimgs trim">
                                <?php if(!empty($posts['goods_img'])) $detail_img = json_decode($posts['goods_img'],true);?>
                                <?php if(!empty($detail_img)){foreach($detail_img as $img){?>
                                <div class="addimg candelete"><del></del><div><img src="<?php echo $img;?>"/></div></div>
                                <?php }}?>
                            </div>
                            <div id="file_d"></div>
                        </div>
                        <div class="layoutfoot">缩略图尺寸：480*480</div>
                    </div>
                    <div>
                        <div class="flex_aligntop">图文详情</div>
                        <div class="flexgrow"><textarea name="goods_desc" id="goods_desc"><?php echo isset($posts['goods_desc'])?$posts['goods_desc']:''?></textarea></div>
                    </div>
                </div>
            </div>

            <div class="whitetable">
                <div>
                    <span style="border-color:#4caf50">其他</span>
                </div>
                <div class="bd_left list_layout">
                    <div>
                        <div>店铺优惠</div>
                        <div class="flexgrow">

                            <label class="check">
                                <input name="is_discount" type="radio" value="1" <?php if(isset($posts['is_discount'])&&$posts['is_discount']  || !isset($ids))echo 'checked';?> />
                                <span class="diyradio"><tt></tt></span>
                                参与
                            </label>
                            <label class="check">
                                <input name="is_discount" type="radio" value="2" <?php if(isset($posts['is_discount'])&&$posts['is_discount']==2)echo 'checked';?> />
                                <span class="diyradio"><tt></tt></span>
                                不参与
                            </label>

                        </div>
                    </div>
                    <div>
                        <div>商品排序</div>
                        <div class="input flexgrow"><input name="sort_order" value="<?php echo isset($posts['sort_order'])?$posts['sort_order']:''?>"></div>
                    </div>
                    <!--
                    <div>
                        <div>开售时间</div>
                    <label class="check">
                        <input name="sale_now" type="radio" value="1" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==1)echo 'checked';?> />
                        <span class="diyradio"><tt></tt></span>
                        立即开售
                        </label>
                        <label class="check">
                        <input name="sale_now" type="radio" value="2" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==2)echo 'checked';?> />
                        <span class="diyradio"><tt></tt></span>
                        定时开售

                    </label>
                        <div class="input"><input class="datepicker" name="sale_time" value="<?php if(isset($posts['sale_time']))echo (strtotime($posts['sale_time']))?$posts['sale_time']:'';?>"></div>
                        </div>
                        -->
                    <div>
                        <div>开售时间</div>
                        <label class="check">
                            <input name="sale_now" onclick="radiochange($(this).val())" type="radio" value="1" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==1 || empty($posts['sale_now']))echo 'checked';?> />
                            <span class="diyradio"><tt></tt></span>
                            立即开售
                        </label>
                        <label class="check">
                            <input name="sale_now" onclick="radiochange($(this).val())" type="radio" value="2" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==2)echo 'checked';?> />
                            <span class="diyradio"><tt></tt></span>
                            定时开售

                        </label>
                        <div class="input" id="sale_now_show" style="display: <?php echo !empty( $posts['sale_now']) && $posts['sale_now'] == 2 ? 'block':'none';?>">
                            <input class="input xs timepicker" name="sale_start_time"
                                   value="<?php if(isset($posts['sale_start_time']))echo $posts['sale_start_time']?date('H:i',strtotime($posts['sale_start_time'])):'';?>">
                            --
                            <input class="input xs timepicker" name="sale_end_time"
                                   value="<?php if(isset($posts['sale_end_time']))echo$posts['sale_end_time'] ?date('H:i',strtotime($posts['sale_end_time'])):'';?>">
                        </div>
                        <label class="check">
                            <input name="sale_now" onclick="radiochange($(this).val())" type="radio" value="3" <?php if(isset($posts['sale_now'])&&$posts['sale_now']==3 || empty($posts['sale_now']))echo 'checked';?> />
                            <span class="diyradio"><tt></tt></span>
                            不开售
                        </label>
                    </div>

                </div>
            </div>
            <input type="hidden" name="spec_list" />
        	<input type="hidden" name="delete_spec" />
            <div class="bg_fff bd center pad10"><button class="bg_main button spaced" type="button" id="save">保存配置</button></div>
        </section>
        <?php echo form_close() ?>
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
<!--
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>
-->
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->

</body>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<!--<script src="<?php /*echo base_url(FD_PUBLIC) */?>/js/areaData.js"></script>
--><script>
    <?php
    //FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
    $floder= $this->session->get_admin_inter_id ()?$this->session->get_admin_inter_id (): 'kindeditor';
    $subpath= $floder. '|roomservice|goods|deatil'; //基准路径定位在 /public/media/ 下
    $params= array(
        't'=>'images',
        'p'=>$subpath,
        'token'=>'test' //再完善校验机制
    );

    ?>
    <?php $timestamp = time();?>
    var editor_kd;
    $(function() {

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


            //图文详情
            editor_kd = K.create('#goods_desc', {
                cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
                uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
                fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
                allowFileManager : true,
                resizeType : 1,
                items : commonItems,
                afterCreate : function() {
                    setTimeout(function(){
                        $('.ke-container').css('width','600');
                        $('.ke-edit').height(300);
                        $('.ke-edit-iframe').height(300);
                    },1)
                }
            });

            prettyPrint()
        });



        //change
        $('#shop_id').change(function(){
            var value  = $(this).val();
            if(value==''){
                return false;
            }
            $('#hotel_id').val($(this).children('option:selected').attr('iid'));
            var url = '<?php echo site_url('eat-in/goods_group/ajax_get_group_info')?>';
            $.post(url,{
                'shop_id':value,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },function(res){
                if(res.errcode == 0){
                    var html = '';
                    $('#group_id').html('');
                    for(var i in res.data){
                        html += '<option value="'+res.data[i].group_id+'">'+res.data[i].group_name+'</option>';
                    }
                    $('#group_id').append(html);
                }else{
                    alert(res.msg);
                }
            },'json');
        });
        //上传图片
		function delimg(){

			var thisurl = $(this).parent().find('img').attr('src');
			$(this).parent().remove();
			var detail_img = $.parseJSON($("#el_intro_img_d").val());
			for(var k=0;k<detail_img.length;k++){
				if(detail_img[k] == thisurl || detail_img[k] == null){
					detail_img.splice(k,1);
				}
			}
			detail_img = JSON.stringify(detail_img);
        
            if (detail_img == '[]')
            {
                detail_img = '';
            }
            $("#el_intro_img_d").val(detail_img);
			$('#file_d').show();
		}
        $('#file_d').uploadify({
            'formData'     : {
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                'timestamp' : '<?php echo $timestamp;?>',
                'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
            },
            //'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
            'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
			'fileObjName': 'imgFile',
			'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
			'fileSizeLimit':'300', //限制文件大小
            'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
            'onUploadSuccess' : function(file, data) {
                var res = $.parseJSON(data);
                if($("#el_intro_img_d").val()==''){
                    var detail_img = new Array();
                }else{
                    var detail_img = $.parseJSON($("#el_intro_img_d").val());
                }
                detail_img.push(res.url);
                detail_img = JSON.stringify(detail_img);

                $('#el_intro_img_d').val(detail_img);
				var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
                $("#intro_img").append(dom);
                dom.delegate('del','click',delimg);
				$('#file_d').hide();
            },   
			'onUploadError': function () {  
				alert('上传失败');  
			}
        });
<?php if(!empty($detail_img)){?>
$('#file_d').hide();
<?php }?>
        $('.addimg').delegate('del','click',delimg);
    });
//初始化规格表;
var addimgstr	= '<label class="addimg"><input type="file"></label>';
var inputstr	= '<input class="input xs" tmpval="" title="点击修改">';
var setid		= <?php echo $auto_increment_id;?>;
var del_sn		= [];
var del_spec	= [];
var first		= true;
var setid_specid= {};
/*
JSON 数据示例
颜色-黄色 id:1, 尺寸-M id:1,类型-运动 id:5;  specid:115
颜色-黄色 id:1, 尺寸-M id:1,类型-热血 id:3;  specid:113
*/
<?php if( empty($posts['spec_list']) ):?>

var SpecData = {
	spec_type:[],
	spec_type_id:[],
	setting_id:[],
	spec_id:[],
	spec_name:[],
	spec_name_id:[],
/*	spec_type:['颜色','尺寸'],
	spec_type_id:['1','2'],   //修改
	setting_id:[11873,11874,11875,11876],
	spec_id:['11','12','21','22'],
	spec_name:[['1','2'],['1','2']],  //修改
	spec_name_id:[['1','2'],['1','2']],  //修改
	sepc_images:[],  //规格图片*/
	spec_static_name:['价格','库存','SKU'],
	spec_static_en:['specprice','stock','sku'],
	data:{
//		'11873':{setting_id:11873,sku:"",spec_id:"11",spec_name:[1,1],spec_name_id:[1,1],specprice:"24",stock:""},
//		'11874':{setting_id:11874,sku:"",spec_id:"12",spec_name:[1,2],spec_name_id:[1,2],specprice:"24",stock:""},
//		'11875':{setting_id:11875,sku:"",spec_id:"21",spec_name:[2,1],spec_name_id:[2,1],specprice:"24",stock:""},
//		'11876':{setting_id:11876,sku:"",spec_id:"22",spec_name:[2,2],spec_name_id:[2,2],specprice:"24",stock:""},
//		'111':{
			
//			specid:'111', 		/*规格ID，和外层的spec_id数组对应*/
//			spec_name:['黄色','M','运动'],  //修改
//			spec_name_id:['1','1','1'],	   //修改
//			
//			specprice:500,		/*规格价格*/
//			stock:99,			/*库存*/
//			sku:'青春',			/*sku*/
//		},
	}
};
<?php else:?>
var SpecData = <?php echo $posts['spec_list'];?>;
(function(){
	if(SpecData.data.length<=0){
		SpecData.data={};
	}
	if(SpecData.spec_name.length>0){
		var limit = $('#model').attr('limit')!=undefined ?$('#model').attr('limit'):3;
		if(SpecData.spec_type.length==limit){
			$('#addSpec').hide();
		}
		for(var i =0;i <SpecData.spec_type.length;i++){
			var clone = $('#model').clone();
			clone.removeClass('hide').removeAttr('id');
			$("#model").before(clone);
			$('.selectSpec',clone).val(SpecData.spec_type_id[i]);
			$('.selectSpec',clone).bind('change',selectChange);
			$('.addNewSpec',clone).bind('click',addNewSpec);
			$('del',clone).on('click',SpecBoxDel);
			for(var j = 0;j<SpecData.spec_name[i].length;j++){
				var diySpec =  $('<span class="diySpec candelete">'+inputstr+'<del title="删除"></del></span>');
				diySpec.find('input').val(SpecData.spec_name[i][j]);
				diySpec.find('input').attr('tmpval',SpecData.spec_name[i][j]);
				diySpec.find('input').get(0).onblur=Diyspec;
				diySpec.find('del').get(0).onclick=deldiySpec;
				$('.addNewSpec',clone).before(diySpec);
				if(SpecData.spec_name[i][j].length>=8)$('.addNewSpec',clone).hide();
			}
		}
	}
}())
<?php endif;?>
function isNull(){if(!jQuery.isEmptyObject(SpecData)&&SpecData.spec_type_id.length>0&&SpecData.spec_name_id.length>0)return false;else return true;}
function NewArray(array,index){  //将一个多维数组里的元素两两相乘合并为一个新数组
	array = array.concat();
	if(index==undefined)index=0;
	if(index+1>=array.length){return array=array[index];}
	//console.log(array);
	var sum = [],i = 0,a=array[index].length,b=array[index+1].length;
	for(var m=0;m<a;m++)
		for(var n=0;n<b;n++)
		sum[i++]=array[index][m].toString()+array[index+1][n].toString();
	array.splice(index,2,sum);
	return NewArray(array,index);
}
function getRow(array,index,getCount){
	if(index==undefined)index=0;
	if(getCount==undefined)getCount=false;
	if(getCount){
		if(index-1<0)return array[index].length;
		return array[index].length*getRow(array,index-1,getCount);
	}else{
		if(index+1>=array.length)return array[index].length;
		return array[index].length*getRow(array,index+1);
	}
}
function showSpecTable(){
	//console.log(SpecData);
	if(isNull()){	$('#SpecSet').hide();	return;	}
	$('#SpecSet').show();
	var _this = $('#SpecSet'), layernum = SpecData.spec_type_id.length , str = '';
	for(var i = 0;i<layernum;i++)str += '<div>'+SpecData.spec_type[i]+'</div>';
	for(var i = 0;i<SpecData.spec_static_name.length;i++)str+='<div>'+SpecData.spec_static_name[i]+'</div>';
	$('.thead',_this).html(str);
	//填充表格列
	str = '';
	for(var i=0;i<SpecData.spec_name.length;i++){
		var row = getRow(SpecData.spec_name,i)/SpecData.spec_name[i].length;
		var count=getRow(SpecData.spec_name,i,true);
		var css = 'height:'+ row*36+'px;line-height:'+ row*36+'px;padding:0';
		str+='<div class="multirow">';
		for(var m=0;m<count/SpecData.spec_name[i].length;m++)
			for( var j= 0;j<SpecData.spec_name[i].length;j++)
			str+='<div style="'+css+'"><span style="'+css+'">'+SpecData.spec_name[i][j]+'</span></div>';
		str+='</div>';
	}
	var data = SpecData.spec_id;
	for(var i = 0;i<SpecData.spec_static_en.length;i++){
		str+='<div class="multirow">';
		for(var j=0;j<data.length;j++){
			var id = SpecData.setting_id[j];
			var en =SpecData.spec_static_en[i];
			str+='<div><input class="input xs '+en+'" onChange="alterVal(\''+en+'\',this)" value="';
				if(SpecData.data[id]!=undefined) str+=SpecData.data[id][en];
			str+='" /></div>';
		}
		str+='</div>';
	}
	$('.tbody',_this).html(str);
}
function alterVal(en,_this){ //修改静态值 （价格/sKu/库存)
	var index  = $(_this).parent().index();
	var specid = SpecData.spec_id[index];
	for(var i=0;i<index;i++){
		if(SpecData.setting_id[i]==undefined)
			SpecData.setting_id[i]='';
	}
	if(SpecData.setting_id[index]==undefined||SpecData.setting_id[index]==''){
		SpecData.setting_id[index] = setid;setid++;
	}
	var id = SpecData.setting_id[index];
	var  array = specid.length>1?specid.split(""):[specid];
	//console.log(array,id);
	if(SpecData.data[id]==undefined){
		SpecData.data[id]={}
		SpecData.data[id]['spec_name']		=[];
		SpecData.data[id]['spec_name_id']	=[];
		SpecData.data[id]['spec_id']		=specid;
		SpecData.data[id]['setting_id']		=id;
		for(var j = 0;j<SpecData.spec_static_en.length;j++){
			SpecData.data[id][SpecData.spec_static_en[j]]='';
		}
		for(var i=0;i<array.length;i++){
			//console.log(array);
			SpecData.data[id].spec_name[i]=SpecData.spec_name[i][array[i]-1];
			SpecData.data[id].spec_name_id[i]=SpecData.spec_name_id[i][array[i]-1];
		}
	}
	SpecData.data[id][en]=$(_this).val();
}
function selectChange(){showNext();}
function deldiySpec(){
	event.stopPropagation();
	if( window.confirm('是否删除？')){
		var i	= $(this).parents('.SpecBox').index()-1,exist=false;
		var val	= $(this).siblings('input').val();
		$(this).parent().siblings('.addNewSpec').show();
		if($(this).parents('.SpecBox').find('.diySpec').length<=1) clearval();
		$(this).parent().remove();
		if(SpecData.spec_id.length>0){
			var array = SpecData.setting_id.concat();
			for(var m=array.length-1;m>=0;m--){
				if(array[m]!=''&&SpecData.data[array[m]]!=undefined){
					if(val==SpecData.data[array[m]].spec_name[i]){
						del_spec.push(array[m]);
						delete SpecData.data[array[m]];
					}
				}
			}
			var spec_id= SpecData.spec_name_id.concat();
			spec_id.splice(i,1,[$(this).index()+1]);
			spec_id= NewArray(spec_id);
			var delid = [];
			for(var j = 0;j<spec_id.length;j++){
				for(var k=SpecData.spec_id.length-1;k>=0;k--){
					if(spec_id[j]==SpecData.spec_id[k]&&SpecData.setting_id[k]!=undefined){
						delid.push(k);
					}
				}
			}
			for(var j=delid.length-1;j>=0;j--)SpecData.setting_id.splice(delid[j],1);
		}
		showNext();
		$.each(SpecData.data,function(m,n){
			for(var k=0;k<n.spec_name.length;k++){
				for(var l=0;l<SpecData.spec_name[k].length;l++){
					if( n.spec_name[k]==SpecData.spec_name[k][l]){
						n.spec_name_id[k]=SpecData.spec_name_id[k][l];
						n.spec_id=n.spec_name_id.join('');
						break;
					}
				}
			}
		})
	}
} 
function alterName(a,b,val){
	a = Number(a);
	$.each(SpecData.data,function(i,n){
		if(n.spec_name_id[a]==b+1){
		console.log(n.spec_name_id[a],b)
			n.spec_name[a]=val;
		}
	});
}
function Diyspec(){
	var val		= $(this).val();
	var tmpval	= $(this).attr('tmpval');
	var exist	= false;
	if( val !=''){
		var others	= $(this).parent().siblings().find('input');
		for( var i	= 0;i< others.length; i++){
			if( others.eq(i).val() == val){
				exist = true;
				break;
			}
		}
		if(exist){
			alert('已存在的规格');
			$(this).val(tmpval);
		}else if(val!=tmpval){
			var i = $(this).parents('.SpecBox').index()-1, j = 0 ;
			/*if(del_sn[i]==undefined)del_sn[i]=[];
			while(j<del_sn[i].length){
				if( del_sn[i][j] == val){
					del_sn[i].splice(j,1);
					break;
				}
				j++;
			}*/
			$(this).attr('tmpval',val);
			//console.log(i,$(this).parent().index())
			alterName(i,$(this).parent().index(),val);
			showNext();
		}
	}
	if( $(this).val()=='')	$(this).parent().remove();
}
function addNewSpec(){
	var parent = $(this).parents('.SpecBox');
	var add= $('<span class="diySpec candelete">'+inputstr+'<del title="删除"></del></span>');
	if($(this).siblings('.diySpec').length>=7){
		$(this).hide();
	}
	$(this).before(add);
	add.find('input').get(0).onblur=Diyspec;
	add.find('del').get(0).onclick=deldiySpec;
	add.find('input').focus();
}
function clearval(){
	for(var i=0;i<SpecData.setting_id.length;i++)
		del_spec.push(SpecData.setting_id[i]);
	SpecData.setting_id=[];
	SpecData.spec_name=[];
	SpecData.spec_name_id=[];
	SpecData.data={};
	SpecData.spec_type=[];
	SpecData.spec_type_id=[];
	SpecData.spec_id=[];
	SpecData.setting_id=[];
}
function SpecBoxDel(){
	event.stopPropagation();
	if( window.confirm('将清空数据表，是否删除?')){
		clearval();
		$(this).parent().remove();
		$('#addSpec').show();
		showNext();
	}
}
$('#addSpec').click(function(e){
	var limit = $('#model').attr('limit')!=undefined ?$('#model').attr('limit'):3;
	if($('.SpecBox').length==limit) $(this).hide();
	var clone = $('#model').clone();
	clone.removeClass('hide').removeAttr('id');
	$("#model").before(clone);
	$('.selectSpec',clone).bind('change',selectChange);
	$('.addNewSpec',clone).bind('click',addNewSpec);
	$('del',clone).on('click',SpecBoxDel);
});
function showNext(){
	var _parent = $('#SpecControls');
	if($('.SpecBox',_parent).length<=1){ showSpecTable();return;}
	var index = 0;
	if($('.SpecBox',_parent).length-1!=SpecData.spec_name.length){
		clearval();
	}/*
	if(SpecData.spec_id.length>0){
		var array = SpecData.setting_id.concat();
		//console.log(array);
	  for(var i = 0;i<del_sn.length;i++){
		if(del_sn[i]==undefined)del_sn[i]=[];
		for(var j=0;j<del_sn[i].length;j++)
			for(var m=array.length-1;m>=0;m--){
				if(SpecData.data[array[m]]!=undefined){
					if(del_sn[i][j]==SpecData.data[array[m]].spec_name[i]){
						del_spec.push(array[m]);
						SpecData.setting_id.splice($.inArray(array[m],SpecData.setting_id),1);
						delete SpecData.data[array[m]];
					}
				}
			}
	  }
	}*/
	var tmp = SpecData.spec_id.concat();
	SpecData.spec_type=[];
	SpecData.spec_type_id=[];
	SpecData.spec_name=[];
	SpecData.spec_name_id=[];
	for(var i = 0;i<$('.SpecBox',_parent).length-1;i++){
		var _this = $('.SpecBox',_parent).eq(i);
		for(var j=0;j<$('.diySpec',_this).length;j++){
			SpecData.spec_type[index]		=_this.find('option:selected').text();
			SpecData.spec_type_id[index]	=_this.find('.selectSpec').val();
			if( j==0 ){
				SpecData.spec_name[index]	=[];
				SpecData.spec_name_id[index]=[];
			}
			SpecData.spec_name[index][j]	= $('.diySpec',_this).eq(j).find('input').val();
			SpecData.spec_name_id[index][j]	= j+1;
		}
		if($('.diySpec',_this).length>0)index++;
	}
	if(SpecData.spec_name_id.length>0)SpecData.spec_id = NewArray(SpecData.spec_name_id);
	if( SpecData.setting_id.length>0){
		 var i=tmp.length-1;j=SpecData.spec_id.length-1;
		 //console.log(tmp,SpecData.spec_id)
		 if(tmp.length<SpecData.spec_id.length){
			 while(i>=0){
				if(tmp[i]==SpecData.spec_id[j]) {i--;}
				else{
					//console.log(i);
					SpecData.setting_id.splice(i+1,0,'');
				}
				j--;
			 }
		 }/*else if(tmp.length>SpecData.spec_id.length){
			 while(i<SpecData.spec_id.length){
				if(tmp[j]==SpecData.spec_id[i]) {i++;}
				else{
					console.log('b')
					SpecData.setting_id.splice(i,1);
				}
				j++;
			 }
		 }*/
	}
	showSpecTable();
}
showSpecTable();
//规格JS----end 
	var sumStock = 0;
	function autoStock(){
		sumStock = 0;
		$('.stock').each(function() {
			var val = $(this).val();
			if(val!='')sumStock+=val*1;
		});
		$('input[name="stock"]').val(sumStock);
	}
	$('.spectable').on('change','.stock',autoStock);
	autoStock();
    $(".timepicker").datetimepicker({
        format:"hh:ii", language: "zh-CN",startView:1, autoclose: true,
    });
    $('.silde_layer>*').click(function(){
        $(this).parent().siblings().find('input').val($(this).html());
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
    $('#save').click(function(){
        if($("input[name='goods_name']").val() == ''){
            alert('名字不能为空');
            return false;
        }
        if($("input[name='stock']").val() == '' || $("input[name='stock']").val() == 0){
            alert('库存有误');
            return false;
        }
		if(SpecData.spec_name.length<=0){alert('请先添加规格');return;}
        $('input[name="spec_list"]').val(JSON.stringify(SpecData));
		$('input[name="delete_spec"]').val(JSON.stringify(del_spec));
        // console.log($('input[name="spec_list"]').val());return;
        editor_kd.sync();
        $('form').submit();
    })

    //定时售卖
    function radiochange(change)
    {
        if (change==2)
        {
            $('#sale_now_show').show();
        }
        else
        {
            $('#sale_now_show').hide();
        }
    }
</script>
</html>
