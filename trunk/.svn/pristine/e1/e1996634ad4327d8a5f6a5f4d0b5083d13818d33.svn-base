<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/bookingpublic.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/css/fsy0718.css">
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
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <div class="jfk-pages page__mass">
            <div class="jfk-tab jfk-tab__mass">
              <div class="jfk-tab__title">
                <ul class="jfk-list jfk-list--horizontal">
                  <li data-type="new" class="jfk-list__item jfk-tab__title-item jfk-tab__title-item--selected"><a href="javascript:;">新建群发消息</a></li>
                  <!--<li data-type="send" class="jfk-list__item jfk-tab__title-item"><a href="javascript:;">已发送</a></li>-->
                </ul>
              </div>
              <div class="jfk-tab__body">
                <div class="jfk-tab__body-item jfk-tab__body-item--selected  mass-new" data-type="new">
                  <div class="mass-tip-box">
                    <div class="mass-reminder">
                      <span>为保障用户体验，微信公众平台严禁恶意营销以及诱导分享朋友圈，严禁发布色情低俗、暴力血腥、政治造谣等各类违反法律法规及相关政策规定的信息。一但发现，我们将严厉打击和处理。</span>
                    </div>
                  </div>
                  <div class="mass-new-content">
                    <form class="jfk-form">
                      <div class="mass__target">
                        <div class="mass__filter">
                          <ul class="mass__filter-list">
                            <li class="mass__filter-list-item">
                              <span>群发对象:</span>
                              <select name="sendways">
                                <option value="0">全部用户</option>
                                <option value="1">按标签选择</option>
                              </select>
                              <select name="tag_id" style="display:none;">
                                  <?php foreach ($tags as $tag) {?>
                                    <option value="<?php echo $tag['id'] ;?>"><?php echo $tag['name'] ;?></option>
                                  <?php }?>
                              </select>
                            </li>
                          </ul>
                        </div>
                      </div>
                      </form>
                      <div class="jfk-tab jfk-tab__mass-new">
                        <div class="jfk-tab__title">
                          <ul class="jfk-list jfk-list--horizontal jfk-list__mass-new">
                            <li data-type="news" class="jfk-list__item jfk-tab__title-item jfk-tab__title-item--selected"><a href="javascript:;">图文消息</a></li>
                            <li data-type="text" class="jfk-list__item jfk-tab__title-item"><a href="javascript:;">文字</a></li>
                            <li data-type="image" class="jfk-list__item jfk-tab__title-item"><a href="javascript:;">图片</a></li>
                          </ul>
                        </div>

                        <div class="jfk-tab__body">
                          <div class="jfk-tab__body-item jfk-tab__body-item--selected  jfk-tab__body-item-picword" data-type="news">
                            <div class="jfk-tab__body-item-content">
                              <ul class="jfk-list jfk-list--horizontal jfk-list--horizontal-2 jfk-list__picword">
                                <li class="jfk-list__item">
                                  <a href="javascript:;" class="mass-new__control" data-type="news"><i>从素材库中选择</i></a>
                                </li>
                                <li class="jfk-list__item">
                                  <a href="<?php echo site_url('publics/material/edit_news'); ?>" class="mass-new__control"><i>新建图文消息</i></a>
                                </li>
                              </ul>
                              <div class="mass-new-result-box">
                                <div class="mass-new-result"></div>
                                <a href="javascript:;" data-type="news" class="jfk-button mass-new__delete jfk-button--white">删除</a>
                              </div>

                            </div>
                          </div>
                          <div class="jfk-tab__body-item  jfk-tab__body-item-word" data-type="text">
                            <div class="mass-new-result-box">
                              <textarea name="content" class="required" placeholder="请在这里输入正文" maxlength="600"></textarea>
                            </div>
                          </div>
                          <div class="jfk-tab__body-item d jfk-tab__body-item-pic" data-type="image">
                            <div class="jfk-tab__body-item-content">
                              <ul class="jfk-list jfk-list--horizontal jfk-list--horizontal-2 jfk-list__pic">
                                <li class="jfk-list__item">
                                  <a href="javascript:;" class="mass-new__control" data-type="image"><i>从素材库中选择</i></a>
                                </li>
                                <li class="jfk-list__item">
                                  <a href="javascript:;" class="upload-button-pic jfk-button__uploadify jfk-button__uploadify-noqueue"><i>上传图片</i></a>
                                </li>
                              </ul>
                              <div class="mass-new-result-box">
                                <div class="mass-new-result"></div>
                                <a href="javascript:;" data-type="image" class="jfk-button mass-new__delete jfk-button--white">删除</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-item form-item__control">
                        <a href="javascript:;" data-type="ok" class="jfk-button jfk-button--primary jfk-button__mass">群发</a>
                      </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__picword jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <a class="jfk-layer__close jfk-layer__trigger" data-type="cancel" href="javascript:;" title="关闭"></a>
                <div class="jfk-layer__title">选择素材</div>
                <div class="jfk-layer__content">
                  <!--<div class="jfk-search-component Lbsibx">
    <label class="search-box jfk-search-box">
    <input type="text" class="jfk-search-box__input material-picword-input" placeholder="标题/摘要/作者">
    <i class="jfk-search-box__icon"></i>
    </label>
    <input type="button" value="查询" class="search-box-button">
    </div>-->
                  <div class="material__panel jfk-material-box">
                    <div class="material__head">
                      <h4 class="material__title">图文消息</h4>
                      <div class="material__add-picword-button">
                        <a href="<?php echo site_url('publics/material/edit_news'); ?>" class="jfk-button jfk-button--default jfk-button__add-picword">+新建图文消息</a>
                      </div>
                    </div>
                    <div class="material__body">
                      <ul class="jfk-list jfk-list__material jfk-list--horizontal jfk-list--horizontal-3">
                      </ul>
                      <div class="loading-news Ldn">正在加载中……</div>
                    </div>
                  </div>
                </div>
                <div class="jfk-layer__control">
                  <a href="javascript:;" data-type="cancel" class="jfk-layer__trigger jfk-button jfk-button--simple jfk-button__delete-no">取消</a>
                  <a href="javascript:;" data-type="ok" class="jfk-layer__trigger jfk-button jfk-button--primary jfk-button__delete-ok">确定</a>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__media jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <a href="javascript:;" class="jfk-layer__close jfk-layer__trigger" data-type="cancel" title="关闭"></a>
                <div class="jfk-layer__title">选择图片</div>
                <div class="jfk-layer__content">

                  <div class="title">
                    <a href="javascript:;" class="jfk-button jfk-button--default media-add-pic jfk-button__uploadify jfk-button__uploadify-noqueue">+本地上传</a>
                  </div>
                  <div class="media-list-box jfk-material-box">
                    <div class="jfk-list__media-box ">
                      <ul class="jfk-list jfk-list__media jfk-list--horizontal jfk-list--horizontal-3">
                      </ul>
                      <div class="loading-image Ldn">正在加载中……</div>
                    </div>
                  </div>
                </div>
                <div class="jfk-layer__control">
                  <a href="javascript:;" data-type="cancel" class="jfk-layer__trigger jfk-button jfk-button--simple jfk-button__delete-no">取消</a>
                  <a href="javascript:;" data-type="ok" class="jfk-layer__trigger jfk-button jfk-button--primary jfk-button__delete-ok">确定</a>
                </div>
              </div>
            </div>
          </div>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/js/fsy0718_material.js"></script>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
          <script>
            var hasNewData = <?php if(isset($material)){ echo $material; }else{ echo 'null';}?>;
            var $massContent = $('.mass-new-content');
            var $layerImage = $('.jfk-layer__media');
            var $layerImageList = $layerImage.find('.jfk-list__material');
            var $layerImageBox = $layerImage.find('.material__panel');
            var $layerNews = $('.jfk-layer__picword');
            var $layerNewsList = $layerNews.find('.jfk-list__material');
            var $layerNewsBox = $layerNews.find('.material__panel');
            var $tabMassnew = $('.jfk-tab__mass-new');
            var $tabBodypicwordBox = $('.jfk-tab__body-item-picword .mass-new-result-box');
            var $tabBodyPicwordContent = $tabBodypicwordBox.find('.mass-new-result');
            var $tabBodyPicwordList = $('.jfk-tab__body-item-picword .jfk-list__picword')
            var $tabBodypicBox = $('.jfk-tab__body-item-pic .mass-new-result-box');
            var $tabBodyPicContent = $tabBodypicBox.find('.mass-new-result');
            var $tabBodyPicList = $('.jfk-tab__body-item-pic .jfk-list__pic');
            var $sendwaysSelect = $('select[name=sendways]');
            var materialUrl = "<?php echo site_url('publics/material/ajax_get_materials');?>";
            var sendAllUrl = "<?php echo site_url('publics/send_news/ajax_send');?>";
            var firstOpenPicword = true;
            var firstOpenPic = true;
            var curType = 'news';

            $massContent.on('click', '.mass-new__control', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'news') {
                toggleMassLayer(true, type, firstOpenPicword);
                firstOpenPicword = false;
              }
              if (type === 'image') {
                toggleMassLayer(true, type, firstOpenPic);
                firstOpenPic = false;
              }
            });

            materialGetCallbacks.add(getMaterialCallback);
            //切换tab
            changeTab('.jfk-tab__mass-new', function(type) {
              curType = type;
            })
            var toggleMassLayer = function(isShow, type, isFirst) {
              if (isShow) {
                if (type === 'image') {
                  if (isFirst) {
                    getMaterialData('image', 1, materialUrl);
                    $layerImage.find('.jfk-list__media-box').css('max-height', $(window).height() * 0.8 - 230);
                  }
                  $layerImage.show();

                }
                if (type === 'news') {
                  if (isFirst) {
                    var maxLayerHeight = $(window).height() * .8 - 260;
                    $layerNews.find('.material__body').css('max-height', maxLayerHeight);
                    getMaterialData('news', 1, materialUrl);
                  }
                  $layerNews.show();
                }
              } else {
                if (type === 'image') {
                  $layerImage.hide().find('input[name="media-item"]:checked').prop('checked', false)
                }
                if (type === 'news') {
                  $layerNews.hide().find('input[name="jfk-layer__choice-radio"]:checked').prop('checked', false);
                }
              }
            }
            $layerNews.on('click', '.jfk-layer__trigger', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'ok') {
                var $checkIpt = $layerNews.find('[name="jfk-layer__choice-radio"]:checked');
                var $ele = $checkIpt.parents('.jfk-list__item-box').clone();
                showCheckMass('news', $ele)
              }
              toggleMassLayer(false, 'news');
            });
            //选择图片与取消
            $layerImage.on('click', '.jfk-layer__trigger', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'ok') {
                var $checkedItem = $layerImage.find('input[name="media-item"]:checked');
                if ($checkedItem.length) {
                  var $ele = $checkedItem.parents('.jfk-list__item-box').clone();
                  var mediaId = $checkedItem.val();
                  var mediaUrl = $checkedItem.data('url');
                  showCheckMass('image', $ele);
                }
              }
              toggleMassLayer(false, 'image');
            });
            var showCheckMass = function(type, ele, callback) {
              if (type === 'news') {
                $tabBodypicwordBox.show();
                $tabBodyPicwordContent.html(ele);
                $tabBodyPicwordList.hide();
                callback && callback();
              }
              if (type === 'image') {
                $tabBodypicBox.show();
                $tabBodyPicContent.html(ele);
                $tabBodyPicList.hide();
              }

            };
            //删除已选择的
            $tabMassnew.on('click', '.mass-new__delete', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'news') {
                $tabBodypicwordBox.hide();
                $tabBodyPicwordContent.html('');
                $tabBodyPicwordList.show();
              }
              if (type === 'image') {
                $tabBodypicBox.hide();
                $tabBodyPicContent.html('');
                $tabBodyPicList.show();
              }
            });



            //上传
        $('.upload-button-pic').uploadify({//缩略图
            'formData'     : {
                '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                'timestamp' : Date.now(),
                'token'     : '123'
            },
            //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
            'uploader' : '<?php echo site_url('basic/uploadftp/add_material') ?>',
            'fileObjName': 'imgFile',
            'fileTypeExts':'*.bmp;*.png;*.jpeg;*.jpg;*.gif;',//文件类型
            'fileSizeLimit':'5M', //限制文件大小
            'queueSizeLimit': 1,
            'onUploadSuccess' : function(file, data) {
                var res = $.parseJSON(data);
                if(res.error){
                    return alert(res.message);
                }
                var str = '<div class="jfk-list__item-box"><div class="jfk-media-box__body"><div class="jfk-media-box__thumb"><div class="jfk-img__iframe" id="' + res.media_id + '"></div></div></div><div class="jfk-media-box__control"><div class="jfk-cell__bd">' + res.name + '</div></div><label><input type="radio" data-url="' + res.url + '" name="media-item" value="' + res.media_id + '"/><div class="media-item__status"></div></label></div>';
                showCheckMass('image', str);
                showImg(res.url, res.media_id)
            },   
            'onUploadError': function () {  
                //$layerUpload.show();
                alert('上传失败')
            }
        })

            //群发方法
            var send_all = function (result) {
                $.ajax({
                    url: sendAllUrl,
                    method: 'post',
                    data: {
                        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
                        param: result
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        console.log(data);
                        if(data.code == 0){
                            alert('发送成功');
                        }else{
                            alert('发送失败：'+data.msg);
                        }
                    },
                    error: function () {
                        alert('发送失败');
                    }
                });
            }
            var $form = $('.jfk-form');

            $('.jfk-button__mass').on('click', function() {
              var result = {}
              var data = $form.serializeArray();
              var go = true;
              $.each(data, function(k, d) {
                result[d.name] = d.value;
              });
              var tip;
              var key;
              if (curType === 'news' ) {
                  $ele = $tabMassnew.find('[name="jfk-layer__choice-radio"]');
                  tip = '图文消息'
                  key = 'media_id'
              }
              if(curType === 'image'){
                  $ele = $tabMassnew.find('[name="media-item"]');
                  tip = '图片';
                  key = 'media_id'
              }
              if(curType === 'text'){
                  $ele = $tabMassnew.find('[name="content"]');
                  tip = '文字';
                  key = 'content'
              }
              var val = $.trim($ele.val());
              if(val){
                 result[key] = val;
                 result['type'] = curType;
                 console.log(result);
                 send_all(result);
              }else{
                  alert(tip + '必填')
              }
            })
            $sendwaysSelect.on('change',function(){
                var $that = $(this);
                if($that.val()=='0'){
                    $('select[name="tag_id"]').hide();
                }else{
                    $('select[name="tag_id"]').show();
                }
            });

            //已有的图文消息
            if(hasNewData){
              var item = renderNews(hasNewData);
              showCheckMass('news', '<div class="jfk-list__item-box">' + item.str + '</div>', function(){
                $.each(item.showImgs, function(idx, i){
                  showImg(i.url, i.id)
                })
              });
            }
          </script>

          <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->

        <?php
            /* Footer Block @see footer.php */
            require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'footer.php';
            ?>

          <?php
            /* Right Block @see right.php */
            require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'right.php';
            ?>

  </div>
  <!-- ./wrapper -->

  <?php
            /* Right Block @see right.php */
            require_once VIEWPATH. $tpl .DS .'privilege'. DS. 'commonjs.php';
            ?>

    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
    </script>
</body>

</html>