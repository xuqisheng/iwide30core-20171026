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
/*规格表格，不需要则不添加到页面*/
.spectable .multirow { padding:0}
.spectable .multirow>*{ border-top:1px solid #d9dfe9; padding:5px 0; }
.spectable .multirow>*:first-child{ border-top:0}
.spectable .multirow span{min-height:25px;line-height:25px;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<header class="headtitle"><?php echo $breadcrumb_html; ?></header>
    <section class="content">
        <div class="whitetable">
            <div>
                <span style="border-color:#3f51b5">基本信息</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>公众号</div>
                    <div class="input flexgrow"><input  placeholder="搜索或下拉选择"></div>
                </div>
                <div>
                    <div>所属酒店</div>
                    <div class="input flexgrow"><input  placeholder="搜索或下拉选择"></div>
                </div>
                <div>
                    <div>产品类型</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>套票类</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>特权券</label>
                    </div>
                </div>
                <div>
                    <div>商品分类</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>双十二</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>双十一</label>
                    </div>
                </div>
            </div>
        </div>
    
    	
        <div class="whitetable">
            <div>
                <span style="border-color:#ff503f">商品信息</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>商品名称</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div>关键词</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div>缩略图</div>
                    <div class="flexgrow">
                    	<label class="addimg"><input type="file"></label>
                    </div>
                    <div class="layoutfoot">缩略图尺寸：480*480</div>
                </div>
            </div>
        </div>
    
     	<div class="whitetable">
            <div>
                <span style="border-color:#3f92b5">商品规格</span>
            </div>
            <div class="bd_left list_layout">
            	<div>
                	<div class="flex_aligntop">商品规格</div>
                    <div class="flexgrow">
                    	<span class="button void xs">添加规格类型</span>
                        <div class="whiteflex flexcenter pad10 martop">
                        	<div class="input flexgrow maright">
                            <select placeholder="原价">
                            	<option>类型</option><option>人数</option><option>颜色</option><option>尺寸</option><option>其他</option>
                            </select></div>
                       		<label class="check"><input type="checkbox" /><span class="diyradio"><tt></tt></span>添加规格图片</label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex_aligntop">商品配置</div>
                    <div class="flexgrow"  style="max-width:100%">
                    	<div class="table center spectable">
                        	<div>
                                <div>颜色</div>
                                <div>尺寸</div>
                                <div>价格</div>
                                <div>库存</div>
                                <div>SKU</div>
                            </div>
                        	<div>
                                <div>黄色</div>
                                <div class="multirow">
                                    <div><span>1L</span></div>
                                    <div><span>1L</span></div>
                                </div>
                                <div class="multirow">
                                	<div><input class="input xs"></div>
                                    <div><input class="input xs"></div>
                                </div>
                                <div class="multirow">
                                	<div><input class="input xs"></div>
                                    <div><input class="input xs"></div>
                                </div>
                                <div class="multirow">
                                	<div><input class="input xs"></div>
                                    <div><input class="input xs"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>价格</div>
                    <div class="flexgrow flex">
                        <div class="input maright"><input  placeholder=""></div>
                        <div class="input"><input  placeholder="原价"></div>
                    </div>
                </div>
                <div>
                    <div>总库存</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                    <div><label class="check"><input type="checkbox" /><span class="diyradio"><tt></tt></span>页面不显示库存</label></div>
                </div>
                <div>
                    <div>商品家编码</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div>缩略图</div>
                    <div class="flexgrow">
                    	<label class="addimg"><input type="file"></label>
                    </div>
                    <div class="layoutfoot">缩略图尺寸：480*480</div>
                </div>
            </div>
        </div>
    
        <div class="whitetable">
            <div>
                <span style="border-color:#4caf50">商品属性</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>拆分使用</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>商品退款</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>商品邮寄</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持预约</label>
                    </div>
                </div>
                <div>
                    <div>商品转赠</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>到店消费</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>提前预约</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>已售数据</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>开具发票</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>首页展示</div>
                    <div class="flexgrow">
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>不支持</label>
                        <label class="check"><input type="radio" /><span class="diyradio"><tt></tt></span>支持</label>
                    </div>
                </div>
                <div>
                    <div>上架时间</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div class="flex_aligntop">下架时间</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div class="flex_aligntop">失效模式</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div class="flex_aligntop">失效时间</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
            </div>
        </div>    
        <div class="whitetable">
            <div>
                <span style="border-color:#af4cac">商品详情</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>商品内容</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div class="flex_aligntop">购买须知</div>
                    <div class="input flexgrow"><textarea></textarea></div>
                </div>
                <div>
                    <div>图文详情</div>
                    <div class="flexgrow">图文编辑框</div>
                </div>
            </div>
        </div>
    
        <div class="whitetable">
            <div>
                <span style="border-color:#ebe814">其他设置</span>
            </div>
            <div class="bd_left list_layout">
                <div>
                    <div>商品排序</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
                <div>
                    <div>商品状态</div>
                    <div class="input flexgrow"><input  placeholder=""></div>
                </div>
            </div>
        </div>
        <div class="bg_fff bd center pad10"><button class="bg_main button spaced">保存配置</button></div>
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

<!--
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
-->
<script>
<?php 
//FULL Path: .../index.php/basic/browse/mall?t=images&p=a23523967|mall|goods|desc&token=test&CKEditor=el_gs_detail&CKEditorFuncNum=1&langCode=zh-cn
$floder= isset($inter_id)?$inter_id:'';
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
        var editor1 = K.create('textarea[name="order_notice"]', {
            cssPath : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params);?>',
            fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
            allowFileManager : true,
            resizeType : 1,
            items : commonItems,
            afterCreate : function() {
                setTimeout(function(){
                    $('.ke-container').css('width','');
                },1)
            }
        });

        //图文详情
        var editor2 = K.create('textarea[name="img_detail"]', {
            cssPath : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/plugins/code/prettify.css',
            uploadJson : '<?php echo Soma_const_url::inst()->get_url('basic/upload/kind_do_upload', $params_img_detail);?>',
            fileManagerJson : '<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/kindeditor/php/file_manager_json.php',
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






</script>
</body>
</html>
