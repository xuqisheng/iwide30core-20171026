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

<style>
    html, body {
        min-height: 100%;
        min-width: 1480px;
        overflow: auto;
    }
    .layer{position:fixed; top:0; left:0;overflow:hidden; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; display:none}
    .add_hotel_content{background:#f8f8f8; padding:15px; height:100%; float:right; overflow-y:scroll;width:850px;}
    .ftotal_code >*{width:15%; padding-bottom:5px}
    .child_dom{ padding:3px 5px; margin:5px;background:#fff; border:1px solid #3c8dbc;max-width:450px; vertical-align:middle}
    .parent_dom div{display:inline-block}
    .parent_dom .check{margin:2px}
    #coupons_table td{vertical-align:middle}
    /*编辑器BUG*/
    .ke-dialog{top:22%}

    /*j_add*/
    .w_100{width:100%;}
    .flex_grow_35{flex-grow:35 !important;-webkit-flex-grow:35;}
    .skin_img{width:75px;height:90px;}
    .itme_con >div{display:inline-block;}
    .btn_border{border:1px solid #d9dfe9 !important;}
</style>

<!-- 新版本后台 v.2.0.0 -->
<link rel='stylesheet' href='<?php echo base_url(FD_PUBLIC) ?>/css/version2-0-0.css'>
<style>
.icon-arrow-left:after{content: "\e091";}
.icon-arrow-right:after{content: "\e092";}
div[required]>div:first-child:before{content:'*'; color:#f00}
.addimg{margin-right:15px}
#gallery_img_add-queue{ position:absolute;right:0;top:100%;}
.cancel{background:url(<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png) no-repeat; float:left; width:12px; height:12px; background-size:100% 100%; margin:0 5px; cursor:pointer}
.cancel a{ color:transparent;}
/*规格表格，不需要则不添加到页面*/
.spectable .multirow { padding:0}
.spectable .multirow>*{ border-top:1px solid #d9dfe9; padding:5px 0; }
.spectable .multirow>*:first-child{ border-top-color:transparent}
.spectable .multirow span{min-height:25px;line-height:25px; display:block}
.SpecBox .diySpec,.SpecBox .addimg{margin-right:20px}
.SpecBox .input{width:80px}
.flex_aligntop{ padding-top:5px}
#DateSet .input{display:inline-block;}
/*日历弹层*/
#dateTable{ z-index:9999; display:none}
#dateTable >*{ background:#fff; padding:10px; text-align:center; margin: auto; width:600px; max-height:60%; overflow:auto;  position:relative;}
#dateTable .absolute{position:absolute; width:100%; bottom:4px; left:0;}
#dateTable table{margin:10px 0; background:#fff;}
#dateTable td{ height:40px; vertical-align:middle; position:relative}
#dateTable .tdMonth{color:#f00; position:absolute; left:0; top:-5px; z-index:10; width:100%;}
#dateTable .webkitbox{ margin:auto; width:80%;} 
#dateTable .input{margin-left:20px; margin-bottom:5px;}
</style>
<!-- Content Wrapper. Contains page content -->
<?php
    $productIds = $model->m_get('product_ids');
    if( $productIds )
    {
        $productIds = json_decode( $productIds, true );
    }
//    var_dump( $model->m_get('product_ids'), $productIds );die;
?>
<div class="content-wrapper">
    <header class="headtitle"><?php echo $breadcrumb_html; ?></header>
    <!-- <form action="<?php echo $post_url;?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" class="form-horizontal"> -->
    
    <form class="fixed" id="dateTable">
        <div style="margin-top:10%;">
            <div>添加或修改</div>
            <table>
                <thead><tr></tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <div>
            <div class="webkitbox">
                <div class="input"><input id="dateStock"><span>库存</span></div>
                <div class="input"><input id="datePrice"><span>价格|积分</span></div>
            </div>
            <button type="reset" class="button bg_main maright">重置</button>
            <button type="button" class="button bg_main maright" id="SaveDate">保存</button>
        </div>
    </form>
    <section class="content">
        <?php echo form_open( Soma_const_url::inst()->get_url('*/*/edit_post'), array('class'=>'form-horizontal','id'=>'saveInfo','enctype'=>'multipart/form-data' ), array($pk=>$model->m_get($pk), 'inter_id' =>$model->m_get('inter_id') ) ); ?>
        <div class="whitetable">
            <div>
                <span style="border-color:#3f51b5">基本信息</span>
            </div>
            <div class="bd_left list_layout">
                <div required="">
                    <div>公众号</div>
                    <div class="select_input flexgrow">
                        <div class="input"><input placeholder="搜索或下拉选择" value="<?php echo $interIds[$inter_id];?>"></div>
                        <?php if($interIds):?>
                            <div class="silde_layer bd">
                                <?php foreach($interIds as $k=>$v):?>
                                    <div data="<?php echo $k;?>"><?php echo $v;?></div>
                                <?php endforeach;?>
                            </div>
                        <?php endif;?>
                        <input type="hidden" required name="inter_id" value="<?php echo $inter_id;?>">
                    </div>
                </div>
                <div style="display: none;">
                    <div>店铺编号</div>
                    <div class="input flexgrow"><input required="" name="" value="<?php echo $model->m_get('id');?>"></div>
                </div>
                <div required="">
                    <div>店铺名称</div>
                    <div class="input flexgrow"><input required="" name="name" value="<?php echo $model->m_get('name');?>"></div>
                </div>
                <div required="">
                    <div class="flex_aligntop">选择皮肤</div>
                    <div class="itme_con">
                        <?php
                            $themeId = $model->m_get('theme_id');
                            if($themeList):
                        ?>
                            <?php
                                $firstTheme = current( $themeList );
                                foreach($themeList as $k=>$v):
                            ?>
                                <div class="">
                                    <label class="check">
                                        <div><img class="skin_img" src="<?php echo $v['thumbnail'];?>" /></div>
                                        <div>
                                            <input name="theme_id" type="radio"
                                                <?php
                                                    if( ( !$themeId && $v['theme_id']==$firstTheme['theme_id'] )
                                                        || $v['theme_id'] == $themeId )
                                                    {
                                                        echo 'checked';
                                                    }
                                                ?>
                                                   value="<?php echo $v['theme_id'];?>" placeholder="">
                                            <span class="diyradio"><tt></tt></span><?php echo $v['theme_name'];?>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <div>
                    <div class="flex_aligntop">展位编辑</div>
                    <div class="w_100 itme_Booth_list" stle="">
                        <input type="hidden" name="block_arr" class="block_arr" value="">
                        <?php
                            $maxNumBe = 0;
                            if( !$blockArr = $model->m_get( 'block_arr' ) ):?>
                            <div class="flex flexgrow marbtm itme_Booth"  style="padding:4px 0;">
                                <div class="maright">展位</div>
                                <div class="flex_grow_35">
                                    <input type="hidden" name="block_id" class="block_id" value="0">
                                    <div class="input marbtm"><input name="block_content" placeholder="字内容" value=""></div>
                                    <div class="input marbtm"><input name="block_link" placeholder="跳转链接" value=""></div>
                                    <div class="marbtm">
                                        <input type="hidden" name="block_img" class="el_face_img" value="">
                                        <div id="face_img" class="face_img addimgs trim"></div>
                                        <div class="face_img_add marbtm_childe">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else:?>
                            <?php
                                $blockArr = json_decode( $blockArr, true );
                                $maxNumBe = $blockArr ? count( $blockArr ) : 0; 
                                foreach( $blockArr as $k=>$v ):
                            ?>
                            <div class="flex flexgrow marbtm itme_Booth"  style="padding:4px 0;">
                                <div class="maright">展位</div>
                                <div class="flex_grow_35">
                                    <input type="hidden" name="block_id" class="block_id" value="<?php echo $v['block_id'];?>">
                                    <div class="input marbtm"><input name="block_content" placeholder="字内容" value="<?php echo $v['block_content'];?>"></div>
                                    <div class="input marbtm"><input name="block_link" placeholder="跳转链接" value="<?php echo $v['block_link'];?>"></div>
                                    <div class="marbtm">
                                        <input type="hidden" name="block_img" class="el_face_img" value="<?php echo $v['block_img'];?>">
                                        <div id="face_img" class="face_img addimgs trim">
                                                <div class="addimg candelete"><del></del><div><img src="<?php echo $v['block_img'];?>"></div></div>
                                            <?php if( $v['block_img'] ) :?>
                                            <?php endif;?>
                                        </div>
                                        <div class="face_img_add marbtm_childe" style="display:none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        <?php endif;?>
                        <div class="marbtm btn_border button itme_Booth_btn">继续添加展位</div>
                    </div>
                </div>
                <div required="">
                    <div>商品选择</div>
                    <div class="flex flexcenter">
                        <label class="check"><input name="scope" type="radio" <?php if( $model->m_get('scope') == Soma_base::STATUS_TRUE )echo 'checked'?> value="<?php echo Soma_base::STATUS_TRUE;?>" checked=""><span class="diyradio"><tt></tt></span>针对全部上线商品</label>
                        <label class="check"><input name="scope" type="radio" <?php if( $model->m_get('scope') == Soma_base::STATUS_FALSE )echo 'checked'?> value="<?php echo Soma_base::STATUS_FALSE;?>"><span class="diyradio"><tt></tt></span>选择部分商品</label>
                    </div>
                    <span class="button void xs marbtm add_hotel_btn" style="display:none">添加适用商品</span>
                </div>
            </div>
        </div>
        <div class="layer add_hotel" style="display: none;">
    <div class="add_hotel_content">
        <div class="bg_fff bd pad10">
            <label class="check allhotelcheck">
                <input type="checkbox">
                <span class="diyradio">
                    <tt></tt>
                </span>全选所有</label>
        </div>
        <div class="bg_fff bd pad10 martop">
            <div>
                <div class="input">
                    <input type="text" class="searchtable" placeholder="搜索商品">
                </div>
            </div>
            <table id="coupons_table" class="table martop">
                <thead>
                    <tr>
                        <th>分类</th>
                        <th>商品</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if( $catIds ):?>
                        <?php foreach( $catIds as $k=>$v ):?>
                            <tr>
                                <td>
                                    <div class="checkbox hotelcheck">
                                        <label class="check hotelcheck">
                                            <input name="cat_ids[<?php echo $v['cat_id'];?>]" value="<?php echo $v['cat_id'];?>" type="checkbox"
                                                <?php
                                                    if( isset( $productIds[$v['cat_id']] ) && !empty( $productIds[$v['cat_id']] ) )
                                                    {
                                                        echo ' checked="checked" ';
                                                    }
                                                ?>
                                            >
                                            <span class="diyradio">
                                                <tt></tt>
                                            </span><?php echo $v['cat_name'];?></label>
                                    </div>
                                </td>
                                <?php if( $v['items'] ):?>
                                    <td>
                                        <div class="child_dom">
                                            <?php foreach( $v['items'] as $sk=>$sv ):?>
                                                <div class="checkbox codecheck" code="1">
                                                    <label class="check codecheck" code="1">
                                                        <input name="product_ids[<?php echo $v['cat_id'];?>][<?php echo $sv['product_id'];?>]" value="<?php echo $sv['product_id'];?>" type="checkbox"
                                                            <?php
                                                            if( isset( $productIds[$v['cat_id']][$sv['product_id']] ) && !empty( $productIds[$v['cat_id']][$sv['product_id']] ) )
                                                            {
                                                                echo ' checked="checked" ';
                                                            }
                                                            ?>
                                                        >
                                                        <span class="diyradio">
                                                        <tt></tt>
                                                    </span><?php echo $sv['name'];?></label>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </td>
                                <?php endif;?>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </tbody>
            </table>
            <div class="center">
                <ul class="pagination page_concol">
                    <li class="paginate_button active"><a href="#">1</a>
                    </li>
                    <li class="paginate_button"><a href="#">2</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- <div class="bg_fff bd center pad10 layer_success martop"><button class="bg_main button spaced" type="button">保存</button></div> -->
    </div>
</div>

        <div class="bg_fff bd center pad10"><button class="bg_main button spaced" type="button" id="save">保存配置</button></div>
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
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/ckeditor/ckeditor.js"></script>

<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/kindeditor.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.js"></script>
<!--kindEditor-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>

<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css"></script>
<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script src="http://test008.iwide.cn/public/js/areaData.js"></script>
</body>
<script>
var nmub=new Date()*1;
var max_numbe=<?php echo $maxNumBe;?>;
$('.itme_Booth_btn').click(function(){
    nmub++;
    max_numbe++;
    if(max_numbe>=5){return false;}
    var itme_Booth='<div class="flex flexgrow marbtm itme_Booth itme_Booth'+nmub+'"  style="padding:4px 0;"><div class="maright">展位</div><div class="flex_grow_35"><input type="hidden" name="block_id" class="block_id" value="'+nmub+'"><div class="input marbtm"><input  name="block_content" placeholder="字内容" value=""></div><div class="input marbtm"><input name="block_link" placeholder="跳转链接" value=""></div><div class="marbtm"><input type="hidden" required="" name="block_img" class="el_face_img" value=""><div id="face_img" class="face_img addimgs trim"></div><div class="face_img_add'+nmub+' marbtm_childe"></div></div></div>';
    $('.itme_Booth_list').prepend(itme_Booth);
    var face_img_adds='face_img_add'+nmub+'';
    uploadify($('.'+face_img_adds+''));
})
$(".itme_Booth_list").on('click','del',function(){
    max_numbe--;
    $(this).parents(".marbtm").find(".el_face_img").val('');
    $(this).parents(".marbtm").find('.marbtm_childe').show();
    $(this).parents('.candelete').remove();
})
$(".itme_Booth_list .marbtm_childe").each(function(index, el){
    uploadify($(this));
});
function uploadify(obj1){
    obj1.uploadify({//缩略图
        'formData'     : {
            '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
            'timestamp' : '<?php echo time();?>',
            'token'     : '<?php echo md5('unique_salt' . time());?>'
        },
        //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
        'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
        'fileObjName': 'imgFile',
        'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
        'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
        'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
        'fileSizeLimit':'300', //限制文件大小
        'onUploadSuccess' : function(file, data){
            var res = $.parseJSON(data);
            obj1.parent().find('.el_face_img').val(res.url);
            var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
            obj1.parent().find(".face_img").append(dom);
            obj1.hide();
        },   
        'onUploadError': function () {  
            alert('上传失败');  
        }
    });
}
var addimgstr   = '<label class="addimg"><input type="file"></label>';

$(".datepicker").datetimepicker({
    format:"yyyy-mm-dd hh:ii:ss", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",
});
$('.silde_layer>*').click(function(){
    $(this).parent().siblings().find('input').val($(this).html());
    $(this).parent().siblings('input').val($(this).attr('data'));
    if($(this).parent().siblings('input').attr('name')=='hotel_id')
        changeHotel($(this).attr('data'))
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

// function delimg(){  //缩略图
//     $(this).parent().remove();
//     $("#el_face_img").val('');
//     $('#face_img_add').show();
// }
// $('#face_img_add').uploadify({//缩略图
//     'formData'     : {
//         '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
//         'timestamp' : '<?php echo time();?>',
//         'token'     : '<?php echo md5('unique_salt' . time());?>'
//     },
//     //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
//     'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
//     'fileObjName': 'imgFile',
//     'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
//     'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
//     'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
//     'fileSizeLimit':'300', //限制文件大小
//     'onUploadSuccess' : function(file, data) {
//         var res = $.parseJSON(data);
//         $('#el_face_img').val(res.url);
//         var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
//         $("#face_img").append(dom);
//         dom.find('del').get(0).onclick=delimg;
//         $('#face_img_add').hide();
//     },   
//     'onUploadError': function () {  
//         alert('上传失败');  
//     }
// });
// function delgallery(){  //相册
//     $(this).parent().remove();
//     $('#gallery_img_add').show();
//     var thisurl = $(this).parent().find('img').attr('src');
//     $(this).parent().remove();
//     var detail_img = $.parseJSON($("#el_gallery_img").val());
//     for(var k=0;k<detail_img.length;k++){
//         if(detail_img[k] == thisurl){
//             detail_img.splice(k,1);
//         }
//     }
//     detail_img = JSON.stringify(detail_img);
//     $("#el_gallery_img").val(detail_img);
// }
// $('#gallery_img_add').uploadify({//缩略图
//     'formData'     : {
//         '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
//         'timestamp' : '<?php echo time();?>',
//         'token'     : '<?php echo md5('unique_salt' . time());?>'
//     },
//     //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
//     'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
//     'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/add_xs.png",
//     'fileObjName': 'imgFile',
//     'delimg':'<?php echo base_url(FD_PUBLIC) ?>/img/cancel.png',
//     'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
//     'fileSizeLimit':'300', //限制文件大小
//     'onUploadSuccess' : function(file, data) {
//         var res = $.parseJSON(data);
//         var detail_img = [];
//         if($("#el_gallery_img").val()!=''){
//             detail_img = $.parseJSON($("#el_gallery_img").val());
//         }
//         detail_img.push(res.url);
//         if(detail_img.length>=5)
//             $('#gallery_img_add').hide();
//         detail_img = JSON.stringify(detail_img);

//         $('#el_gallery_img').val(detail_img);
//         var dom = $('<div class="addimg candelete"><del></del><div><img src="'+res.url+'"/></div></div>');
//         $("#gallery_img").append(dom);
//         dom.find('del').get(0).onclick=delgallery;
//     },   
//     'onUploadError': function () {  
//         alert('上传失败');  
//     }
// });
// if($('#face_img').find('img').length>0){
//     $("#face_img del").get(0).onclick=delimg;
//     $('#face_img_add').hide();
// }
// if($('#gallery_img').find('img').length>0){
//     $("#gallery_img del").click(delgallery);
// if($('#gallery_img').find('img').length>=5){
//     $('#gallery_img_add').hide();
// }}

/*选择酒店房型*/
$(function(){
	ajaxevent_bind();
	var show_scope=function(){
		var val = $('input[name="scope"]:checked').val();
		if(val == <?php echo Soma_base::STATUS_FALSE;?>){
			$('.add_hotel_btn').show();
		}else{
			$('.add_hotel_btn').hide();
		}		
	}
    $('input[name="scope"]').change(show_scope);
	show_scope();
	$('.add_hotel_btn').click(function(){
		$('.add_hotel').stop().fadeIn();
		$('body').css('overflow','hidden');
	});
});
function layerclose(){
	$('.add_hotel').stop().fadeOut();
	$('body').removeAttr('style');
}
function ajaxevent_bind(){
	$('.add_hotel').bind('click',layerclose);	
	$('.layer_success').click(function(){
		$('.add_hotel').stop().fadeOut();
		$('body').removeAttr('style');
	})
	$('input[type="checkbox"]','.allcodecheck').click(function(){
		var bool =$(this).get(0).checked;
		var tmp = $('.codecheck');
		tmp.each(function() {
         	$(this).find('input').get(0).checked=bool;
        });
	})
	$('input[type="checkbox"]','.codecheck').click(function(){
		var bool   =$(this).get(0).checked;
		var parent =$(this).parents('.codecheck');
		var tmp    =parent.siblings('.allcodecheck');
		if(tmp.length>0){
			parent.siblings('.codecheck').each(function() {
				if( !$(this).find('input').get(0).checked ) 
					bool=$(this).find('input').get(0).checked;
			});
			tmp.find('input').get(0).checked=bool;
		}
	})
	$('input[type="checkbox"]','.allhotelcheck').click(function(){
		var bool   =$(this).get(0).checked;
		$('.add_hotel input').each(function() {
         	$(this).get(0).checked=bool;            
        });
	})
	$('input[type="checkbox"]','.hotelcheck').click(function(){
		var bool;
		$('.hotelcheck').each(function() {
			if( !$(this).find('input').get(0).checked ) 
				bool=$(this).find('input').get(0).checked;
		});
		$('input[type="checkbox"]','.allhotelcheck').get(0).checked=bool;
		bool=$(this).get(0).checked;
		$(this).parents('td').siblings().find('input').each(function(){
			$(this).get(0).checked=bool;
		});
	})
	$('.add_hotel_content').click(function(e){
		e.stopPropagation();
	})
	$('input[type="checkbox"]','.roomcheck').click(function(){
		var bool =$(this).get(0).checked;
		$(this).parents('.roomcheck').siblings().find('input').each(function() {
			$(this).get(0).checked=bool;
        });
	});
	$('input[type="checkbox"]','.total_code .codecheck').click(function(){
		var code = $(this).parents('.codecheck').attr('code');
		var bool = $(this).get(0).checked;
		$('.codecheck[code="'+code+'"]').find('input').each(function() {
            $(this).get(0).checked=bool;
        });
	})
	var tr_length  = $('#coupons_table tbody tr').length;
	if(tr_length>10){
		var page_length= tr_length/10;
		for( var i=10; i<tr_length;i++){
			$('#coupons_table tbody tr').eq(i).hide();
		}
		for (var i=1;i<page_length;i++){
			$('.page_concol').append('<li class="paginate_button"><a href="#">'+(i+1)+'</a></li>');
		}
		$('.paginate_button').click(function(){
			var _index=$(this).index();
			$(this).addClass('active').siblings().removeClass('active');
			$('#coupons_table tbody tr').hide();
			for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
				$('#coupons_table tbody tr').eq(i).show();
			}
		})
	}else{
		$('.page_concol').hide();
	}
	$('.searchtable').bind('input propertychange',function(){
		var val=$(this).val();
		if(val==''){
			if(tr_length>10){
				$('.page_concol').show();
				var _index=$('.paginate_button.active').index();
				for( var i=_index*10; i<(_index+1)*10&&i<tr_length;i++){
					$('#coupons_table tbody tr').eq(i).show();
				}
			}else{
				$('#coupons_table tbody tr').show()
			}
		}
		else{
			$('.page_concol').hide();
			$('.hotelcheck').each(function(index, element) {
                if( $(this).text().indexOf(val)>=0){
					$(this).parents('tr').show();
				}else{
					$(this).parents('tr').hide();
				}
            });
		}
	})
}
$('#save').click(function(e){
var block_arr=[];
    var bool =true;
    if($('.itme_Booth_list .itme_Booth').length!=0){
        $('.itme_Booth_list .itme_Booth').each(function(index,el){
            var item={};
            item.block_content=$(this).find('input[name=block_content]').val();
            item.block_link=$(this).find('input[name=block_link]').val();
            item.block_img=$(this).find('input[name=block_img]').val();
            item.block_id=$(this).find('input[name=block_id]').val();
            block_arr.push(item);
        });
    }

    $('.block_arr').val(JSON.stringify(block_arr));
    console.log(block_arr);
    $('div[required]').each(function(){
        var  _this = $(this);
        _this.removeAttr('style');
        $(this).find('[required]').each(function(index, element) {
            if($(this).val()==''){
                bool = false;
                _this.css('color','#f00');
            }
        });
    });
    if(bool){
		
		if($('input[name=scope]:checked').val()==<?php echo Soma_base::STATUS_FALSE;?>){
			if($('.add_hotel input:checked').length<=0){ alert('请先添加适用商品');return;}
		}
        $('#saveInfo').submit();
    }else{
        alert('带*为必填项');
        return;
    }
});
$('#postImg').click(function(){
    // alert(
    //$('input[name="spec_list"]').val(JSON.stringify(SpecData));
    // console.log($('input[name="spec_list"]').val());return;
    // $('form').submit();
    $('#saveImg').submit();
});
<?php 
//FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
$floder= $model->m_get('inter_id')? $model->m_get('inter_id'): 'inter_id';
$subpath= $floder. '|soma|product_package|order_notice'; //基准路径定位在 /public/media/ 下
$params= array(
    't'=>'images',
    'p'=>$subpath,
    'token'=>'test' //再完善校验机制
);

$subpath_img_detail= $floder. '|soma|product_package|img_detail'; //基准路径定位在 /public/media/ 下
$params_img_detail= array(
    't'=>'images',
    'p'=>$subpath_img_detail,
    'token'=>'test' //再完善校验机制
);
?>
var editor1 ,editor2,editor3,editor4;
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
         editor1 = K.create('#order_notice', {
             cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
             uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
             fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
             allowFileManager : true,
             resizeType : 1,
             items : commonItems,
             afterCreate : function() {
                 setTimeout(function(){
                     $('.ke-container').css('width','600');
                 },1)
             }
         });
         editor3 = K.create('#order_notice_en', {
             cssPath : '<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/kindeditor/plugins/code/prettify.css',
             uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
             fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
             allowFileManager : true,
             resizeType : 1,
             items : commonItems,
             afterCreate : function() {
                 setTimeout(function(){
                     $('.ke-container').css('width','600');
                 },1)
             }
         });

        //图文详情
        editor2 = K.create('#img_detail', {
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
        editor4 = K.create('#img_detail_en', {
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
    var tmp_gallery_img = [];
    $('#gallery_img img').each(function(){
        tmp_gallery_img.push($(this).attr('src'));
    })
    $('#el_gallery_img').val(JSON.stringify(tmp_gallery_img));
    
});

var show_scope=function(){
    var val = $('input[name="scope"]:checked').val();
    if(val == <?php echo Soma_base::STATUS_FALSE;?>){
        $('.add_hotel_btn').show();
    }else{
        $('.add_hotel_btn').hide();
    }       
}

$('input[name="scope"]').change(show_scope);
$('.datepicker2').datepicker({format:'yyyy/mm/dd'});
</script>
</html>
