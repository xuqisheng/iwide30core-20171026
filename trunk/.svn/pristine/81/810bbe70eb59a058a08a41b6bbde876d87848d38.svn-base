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
          <div class="jfk-pages page__material-add">
            <div class="material-add__box">
              <div class="material-add__right">
                <h5 class="material-add__right-title">图文列表</h5>
                <div class="material-add__right-content">
                  <div class="jfk-media-box">
                    <div class="jfk-media-box__thumb article-item article-item--selected" data-id="0">
                      <div class="mask mask--default"></div>
                      <p class="jfk-media-box__thumb-desc article-item__title">标题</p>
                      <!--<div class="cell-control">
    <a href="javascript:;" class="control-order control-order-up">上</a>
    <a href="javascript:;" class="control-order control-order-down">下</a>
    <a href="javascript:;" class="control-delete"></a>
    </div>-->
                    </div>
                    <div class="jfk-media-box__desc">
                      <div class="jfk-cells">
                      </div>
                    </div>
                  </div>
                  <div class="material-add__right-control">
                    <a href="javascript:;" id="material-add-multiple" title="添加图文消息"></a>
                  </div>
                </div>
              </div>
              <div class="material-add__left">
                <div class="material-add__left-content">
                  <form class="material-add__form jfk-form" id="material-add__form">
                    <div class="jfk-form__item">
                      <input type="text" name="title" class="required" placeholder="请在这里输入标题" />
                    </div>
                    <div class="jfk-form__item">
                      <input type="text" name="author" class="required" placeholder="请输入作者">
                    </div>
                    <div class="jfk-form__item material-add__article">
                      <div class="ueditor-box">
                        <div class="ueditor" name="content" id="jfk-ueditor"></div>
                      </div>
                      <a href="javascript:;" class="material-add__pic jfk-button__uploadify jfk-button__uploadify-noqueue Ldn"></a>
                    </div>
                    <div class="jfk-form__item">
                      <label class="jfk-checkbox">
                        <input type="checkbox" name="content_source_url_key" class="material-content-source-url" value="1" />
                        <i class="jfk-checkbox__placeholder"></i>
                        <i class="jfk-checkbox__label">原文链接</i>
                      </label>
                      <p class="material-content-source-url-box Ldn">
                        <input type="text" name="content_source_url" disabled/>
                      </p>
                    </div>
                    <div class="material-content-style jfk-form__item">
                      <h5 class="style-title">发布样式编辑</h5>

                      <p class="cover-title">封面<i class="tip">（大图片建议尺寸：900像素*500像素）</i></p>
                      <div>
                        <a href="javascript:;" id="open-media-layer" class="jfk-button jfk-button--white">从图片库选择</a>
                        <p class="cover-error error">请选择封面</p>
                      </div>
                      <input type="hidden" name="thumb_url" class="thumb-media-url" />
                      <input type="checkbox" name="thumb_media_id" class="thumb-media-id">
                      <div class="cover-result">
                        <div class="mask"></div>
                        <div class="image"></div>
                        <a href="javascript:;" class="control"></a>
                      </div>
                    </div>
                    <div class="jfk-form__item">
                      <p class="summary-title">摘要<i class="tip">（选填，如果不填写会默认抓取正文前54个字）</i></p>
                      <div class="material-summary">
                        <textarea name="digest" maxlength="54"></textarea>
                        <span class="material-summary-total"><i class="total">0</i>/54</span>
                      </div>
                    </div>
                    <div class="jfk-form__item jfk-form__control">
                      <a href="javascrit:;" data-type="add" class="jfk-button jfk-button--primary">保存</a>
                      <a href="javascript:;" data-type="mass" class="jfk-button jfk-button--white">保存并群发</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__delete jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <div class="jfk-layer__title"></div>
                <div class="jfk-layer__content">确认删除此篇图文</div>
                <div class="jfk-layer__control">
                  <a href="javascript:;" data-type="ok" class="jfk-layer__trigger jfk-button jfk-button--primary jfk-button__delete-ok">确定</a>
                  <a href="javascript:;" data-type="cancel" class="jfk-layer__trigger jfk-button jfk-button--simple jfk-button__delete-no">取消</a>
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
                    <div class="jfk-list__media-box">
                      <ul class="jfk-list jfk-list__media jfk-list--horizontal jfk-list--horizontal-3">
                      </ul>
                      <div class="loading-image">正在加载中……</div>
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
          <script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/js/fsy0718_material.js"></script>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor_f/ueditor.config.js"></script>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/js/ueditor_f/ueditor.all.js"></script>
          <script>
          //是否为编辑状态
            var hasNewData = <?php if(isset($material)){ echo $material; }else{ echo 'null';}?>;
            var $layerMedia = $('.jfk-layer__media');
            var $picwordFirst = $('.material-add__right .jfk-media-box__thumb');
            var $picwordBox = $('.material-add__right-content')
            var $layerDelete = $('.jfk-layer__delete');

            var $layerMediaBox = $layerMedia.find('.media-list-box');
            var $layerMediaList = $layerMedia.find('.jfk-list__media');
            var $summaryTotal = $('.material-summary-total .total');
            var $thumbMediaId = $('.thumb-media-id');
            var $thumbMediaUrl = $('.thumb-media-url');
            var $thumbMediaImage = $('.cover-result .image');
            var $picwordCells = $('.material-add__right .jfk-cells');
            var $layerMediaNoContent = $layerMedia.find('.media-no-content');
            var $addPicwordForm = $('#material-add__form');
            var $sourceUrlBox = $('.material-content-source-url-box');
            var $sourceUrlCheckbox = $('.material-content-source-url');
            var $sourceUrl = $('input[name="content_source_url"]');
            var $messageBox = $('.material-content-message-box');
            var $messageInput = $(".material-content-message-item");
            var $articleContent = $('.material-add__article')
            var $title = $('input[name="name"]');
            var $author = $('input[name="author"]')
            var $content = $('textarea[name="content"]');
            var $currMediaListItem = $picwordFirst;
            var $coverError = $('.cover-error');
            var materialUrl = "<?php echo site_url('publics/material/ajax_get_materials');?>";
            var $materialRightControl = $('.material-add__right-control');
            var $uploadImg = $('.material-add__pic');
            $('textarea[name=digest]').keyup(function() {
              $summaryTotal.text($(this).val().length);
            })
            //多图文列表模板
            var getPicwordTpl = function(idx) {
              return '<div data-id="' + idx + '" class="jfk-cell article-item article-item--selected"><div class="jfk-cell__bd article-item__title">标题</div><div class="jfk-cell__hd"><div class="mask mask--default"></div></div><div class="cell-control"><a href="javascript:;" class="control-delete"></a></div></div>';
            }
            var showExistNewsByData = function(data){
              var showImgs = [];
              var strArr = ['<div class="jfk-media-box"><div class="jfk-media-box__thumb article-item" data-id="0"><div class="mask"><div class="jfk-img__iframe" id="'+data[0].thumb_media_id+'"></div></div><p class="jfk-media-box__thumb-desc article-item__title">' + data[0].title + '</p></div><div class="jfk-media-box__desc"><div class="jfk-cells">'];
              showImgs.push({
                url: data[0].thumb_url,
                id: data[0].thumb_media_id
              })
              var i = 1;
              var len = data.length;
              while(i < len){
                strArr.push('<div data-id="' + i + '" class="jfk-cell article-item"><div class="jfk-cell__bd article-item__title">' + data[i].title + '</div><div class="jfk-cell__hd"><div class="mask"><div class="jfk-img__iframe" id="' + data[i].thumb_media_id + '"></div></div></div></div>');
                showImgs.push({
                  url: data[i].thumb_url,
                  id: data[i].thumb_media_id
                });
                i++;
              }
                strArr.push('</div>');
                $('.material-add__right-content').html(strArr.join(''));
                $.each(showImgs, function(idx, prop){
                  showImg(prop.url, prop.id)
                })
            }
            var toggleMediaLayer = function(isShow, isFirst) {
              if (isFirst) {
                getMaterialData('image', 1, materialUrl);
                $layerMedia.find('.jfk-list__media-box').css('max-height', $(window).height() * 0.8 - 230)
              }
              $layerMedia[isShow ? 'show' : 'hide']();
              if (!isShow) {
                $layerMedia.find('input[name="media-item"]:checked').prop('checked', false)
              }
            }
            materialGetCallbacks.add(getMaterialCallback);

            var mediaNeverShow = true;
            //从图片库中选择图片
            $('#open-media-layer').click(function(e) {
              toggleMediaLayer(true, mediaNeverShow)
              mediaNeverShow = false;
            });

            $layerMedia.find('.media-add-pic').uploadify({ //缩略图
              'formData': {
                '<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash();?>',
                'timestamp': Date.now(),
                'token': '123'
              },
              //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
              'uploader': '<?php echo site_url('basic/uploadftp/add_material') ?>',
              'fileObjName': 'imgFile',
              'fileTypeExts': '*.bmp;*.png;*.jpeg;*.jpg;*.gif;', //文件类型
              'fileSizeLimit': '5M', //限制文件大小
              'queueSizeLimit': 1,
              'onUploadSuccess': function(file, data) {
                var res = $.parseJSON(data);
                if (res.error) {
                  return alert(res.message);
                }
                mediaNeverShow = true;
                var str = '<div class="jfk-list__item-box"><div class="jfk-media-box__body"><div class="jfk-media-box__thumb"><div class="jfk-img__iframe" id="' + res.media_id + '"></div></div></div><div class="jfk-media-box__control"><div class="jfk-cell__bd">' + res.name + '</div></div></div>'
                var $ele = $(str).appendTo($layerMediaList.find('.jfk-list__item:last')).find('.jfk-img__iframe');
                showImg(res.url, res.media_id);
                toggleThumbMediaResult(res.media_id, res.url, $ele, true);
                toggleMediaLayer(false);

              },
              'onUploadError': function() {
                //$layerUpload.show();
                alert('上传失败')
              }
            });
            //从图片库中选择图片完成
            function toggleThumbMediaResult (mediaId, mediaUrl, ele, showMediaListItem,callback) {
                var $mediaListItemImage = $currMediaListItem.find('.mask');
                if (mediaId) {
                  $thumbMediaId.val(mediaId).prop('checked', true);
                  $thumbMediaUrl.val(mediaUrl);
                  $thumbMediaImage.html(ele).removeClass('mask--default');
                  if (showMediaListItem) {
                    $mediaListItemImage.html($thumbMediaImage.children().clone()).removeClass('mask--default');
                  }
                  $coverError.hide();
                  callback && callback(mediaUrl, mediaId);

                } else {
                  $thumbMediaImage.html('');
                  $thumbMediaUrl.val('')
                  $thumbMediaId.val('').prop('checked', false)
                    //手动删除，如果不是手动删除，这个值为undefine
                  if (mediaId === false) {
                    $mediaListItemImage.html('').addClass('mask--default')
                  }
                }
              }
              //选择图片与取消
            $layerMedia.on('click', '.jfk-layer__trigger', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'ok') {
                var $checkedItem = $layerMedia.find('input[name="media-item"]:checked');
                if ($checkedItem.length) {
                  var $ele = $checkedItem.parents('.jfk-list__item-box').find('.jfk-img__iframe').clone();
                  var mediaId = $checkedItem.val();
                  var mediaUrl = $checkedItem.data('url');
                  toggleThumbMediaResult(mediaId, mediaUrl, $ele, true);
                }
              }
              toggleMediaLayer(false);
            });
            //删除已经选择的图片
            $('.cover-result .control').on('click', function() {
                toggleThumbMediaResult(false);
              })
            function selectPicwordById (id, ele) {
                var $that = ele || $picwordBox.find('.article-item[data-id="' + id + '"]')
                $picwordBox.find('.article-item--selected').removeClass('article-item--selected');
                $that.addClass('article-item--selected');
                getFormData();
                resetAddPicwordForm();
                selectPicword(id);
                $currMediaListItem = $that;
              }
              //选择图文
            function selectPicword (idx) {
              articlesMap.curIdx = idx;
              var data = articlesMap.articles[idx];
              showPicwordForm(data);
            }
            var articlesMap;
            if(hasNewData){
              //编辑
              articlesMap = {
                mediaId: hasNewData.media_id,
                curIdx: 0,
                order: [],
                articles: {}
              }
              var items = hasNewData.content.news_item;
              if(items){
                articlesMap.lastIdx = items.length - 1;
                $.each(items, function(idx,item){
                  articlesMap.order.push(idx);
                  articlesMap.articles[idx] = item;

                });
                showExistNewsByData(items);
                selectPicword(0);
                $currMediaListItem = $('.material-add__right .jfk-media-box__thumb')
              }

            }else{
              //图文列表数据缓存
              articlesMap = {
                order: [0],
                lastIdx: 0,
                articles: {},
                curIdx: 0
              };
            }


            //添加新的图文列表
            var addArticle = function(idx) {
              articlesMap.curIdx = idx;
            }

            //添加新的图文
            $materialRightControl.on('click', function() {
                $picwordBox.find('.article-item--selected').removeClass('article-item--selected');
                addPicword();
              })
              //删除多图文
            $picwordBox.on('click', '.cell-control .control-delete', function() {
              var $that = $(this);
              //删除
              var $par = $that.parents('.jfk-cell');
              //thumb算一个
              var idx = $par.data('id');
              $layerDelete.show();
              $layerDelete.data('idx', idx);
            });
            //选择图文视图
            $picwordBox.on('click', '.article-item', function() {
              var $that = $(this);
              var id = $that.data('id');
              if (id !== undefined) {
                selectPicwordById(id, $that)
              }
            });
            //删除图文
            var removeArticle = function(idx) {
                var index = articlesMap.order.indexOf(idx);
                if (index !== -1) {
                  articlesMap.order.splice(index, 1);
                  delete articlesMap.articles[idx]
                  if(articlesMap.order.length < 8){
                    $materialRightControl.show();
                  }
                }
              }
              //添加图文视图
            var addPicword = function() {
                articlesMap.lastIdx++;
                articlesMap.order.push(articlesMap.lastIdx);
                var tpl = getPicwordTpl(articlesMap.lastIdx);
                $currMediaListItem = $(tpl).appendTo($picwordCells);
                getFormData();
                //无排序，可以不用考虑第一个的操作按钮
                //togglePicwordFirstControl(true);
                resetAddPicwordForm();
                addArticle(articlesMap.lastIdx);
                if(articlesMap.order.length >= 8){
                  $materialRightControl.hide();
                }
              }
              //删除图文视图
            var removePicword = function(idx) {
              resetAddPicwordForm();
              var $ele = $('.jfk-cell[data-id="' + idx + '"]');
              var $prev;
              if ($ele.hasClass('article-item--selected')) {
                $prev = $ele.prev('.jfk-cell');
                var idx = 0;
                if ($prev.length) {
                  $prev.addClass('article-item--selected');
                  idx = +$prev.data('id');
                } else {
                  $prev = $picwordFirst;
                }
              }
              $currMediaListItem = $prev;
              $ele.remove();
              $layerDelete.data('idx', null);
              selectPicword(idx)
            }


            //删除弹框
            $layerDelete.on('click', '.jfk-layer__trigger', function() {
              var $that = $(this);
              var type = $that.data('type');
              if (type === 'ok') {
                var idx = $layerDelete.data('idx');
                removePicword(idx);
                removeArticle(idx);

              }
              $layerDelete.hide();
            });
            //显示原文链接和留言
            function toggleSourceAndMessage (isShow, type) {
                if (type === 1) {
                  $sourceUrlBox[isShow ? 'removeClass' : 'addClass']('Ldn');
                  $sourceUrl.prop('disabled', isShow ? false : true);
                }
                if (type === 2) {
                  $messageBox[isShow ? 'removeClass' : 'addClass']('Ldn');
                  $messageInput.prop('disabled', isShow ? false : true);
                  if (isShow) {
                    $messageInput.eq(0).prop('checked', true);
                  }
                }
              }
              //change
            $addPicwordForm.on('change', 'input[type="checkbox"]', function() {
              var $that = $(this);
              var isChecked = $that.prop('checked');
              //原文链接
              if ($that.hasClass('material-content-source-url')) {
                toggleSourceAndMessage(isChecked, 1)
              }
              if ($that.hasClass('material-content-message')) {
                toggleSourceAndMessage(isChecked, 2)
              }
            })

            //重置form
            function resetAddPicwordForm () {
              $addPicwordForm[0].reset();
              toggleSourceAndMessage(false, 1);
              toggleSourceAndMessage(false, 2);
              $summaryTotal.text('0');
              if(editor){
                editor.execCommand('cleardoc')
              }
              $coverError.hide();
              $('.error').removeClass('error');
            }

            //填充form
            function showPicwordForm (data) {
                $.each(data, function(key, val) {
                  if (val !== '') {
                    if (key === 'title' || key === 'author'  || key === 'digest') {
                      $('[name="' + key + '"]').val(val);
                    }
                    if(key === 'content' && editor){
                      editor.setContent(val);
                    }
                    if (key === 'content_source_url') {
                      $sourceUrlCheckbox.prop('checked', true);
                      $sourceUrl.val(data.content_source_url);
                      toggleSourceAndMessage(true, 1);
                    }
                    if (key === 'thumb_media_id' && data.thumb_url) {
                      //debugger;
                      var $ele = $picwordBox.find('.article-item[data-id="' + articlesMap.curIdx + '"]').find('.mask .jfk-img__iframe').clone();
                      var mediaId = data.thumb_media_id;
                      var callback;
                      //可能不存在这个元素
                      if(!$ele.length){
                        mediaId = data.thumb_media_id + parseInt(Math.random() * 1000);
                        $ele = $('<div class="jfk-img__iframe" id="'+mediaId+'"></div>');
                        callback = function(url,id){
                          showImg(url,id);
                        }
                      }
                      toggleThumbMediaResult(mediaId, data.thumb_url, $ele, false, callback);
                    }
                  }
                })
              }
              //从当前form中获取数据
            function getFormData () {
                var data = $addPicwordForm.serializeArray();
                var curIdx = articlesMap.curIdx;
                var target = {};
                $.each(data, function(idx, d) {
                  target[d.name] = d.value;
                });
                if (!target.content_source_url_key) {
                  delete target.content_source_url
                }
                articlesMap.articles[curIdx] = target;
                return target;
              }
              //显示错误
            function showFormError (errors) {
                $.each(errors, function(idx, name) {
                    console.log(name);
                  if (name !== 'content') {
                    $('input[name="' + name + '"]').addClass('error');
                  }
                  if (name === 'content') {
                    $articleContent.addClass('error');
                  }
                  if( name === 'thumb_media_id'){
                      $coverError.show();
                  }
                })
              }
              //隐藏错误
            function hideFormError (keys) {
                if (keys !== 'content') {
                  $('input.error[name="' + keys + '"]').removeClass('error');
                }
                if (keys === 'content') {
                  $articleContent.removeClass('error');
                }
              }

            var editorId = 'appmsg_editor';
            var editor = UE.getEditor('jfk-ueditor',{
                id: editorId,
                wordCount: false,
                autoHeightEnabled: false,
                elementPathEnabled: false,
                initialFrameHeight: 200
            });
            var uploadArticleImg = function(){

            }
            editor.addListener('ready', function(){
              //默认有内容
              if(articlesMap.mediaId){
                editor.setContent(articlesMap.articles[articlesMap.curIdx].content)
              }
              //上传图片
              $uploadImg.show().uploadify({ //缩略图
                  'formData': {
                    '<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash();?>',
                    'timestamp': Date.now(),
                    'token': '123'
                  },
                  //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
                  'uploader': '<?php echo site_url('basic/uploadftp/uploadimg') ?>',
                  'fileObjName': 'imgFile',
                  'fileTypeExts': '*.jpg;*.png', //文件类型
                  'fileSizeLimit': '5M', //限制文件大小
                  'queueSizeLimit': 1,
                  'onUploadSuccess': function(file, data) {
                    var res = $.parseJSON(data);
                    if(res.error){
                      alert(res.message);
                    }else{
                      editor.execCommand('insertimage',{
                        src: res.url,
                        width: 'auto',
                        height: 'auto'
                      })
                    }
                  },
                  'onUploadError': function() {
                    //$layerUpload.show();
                    alert('上传失败')
                  }
                })
            });
            editor.addListener('contentChange', function(){
              if(editor.getContent()){
                $articleContent.removeClass('error');
              }
            })

              //提交图文数据
            var saveArticlesData = function(article, callback, type) {
                var data = {
                    '<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash();?>',
                    articles: article
                };
                if(articlesMap.mediaId){
                  data.media_id = articlesMap.mediaId;
                }
                $.ajax({
                  url: '/index.php/publics/material/update_news',
                  method: 'post',
                  data: data,
                  success: function(data) {
                    data = $.parseJSON(data);
                    callback(null, data, type);
                  },
                  error: function() {
                    callback(true, null, type);
                  }
                })
              }
              //保存后页面显示
            var renderArticles = function(err, data, type) {
                if (!err) {
                  if (data.code == 1) { //保存失败
                    alert(data.msg);
                    return;
                  }
                  if (type=='mass') {
                    window.location.href = "<?php echo site_url('publics/send_news/send').'?media_id=';?>" + data.media_id;//跳转到群发页面
                  } else {
                    alert('保存成功');
                  }
                }
              }
              //检测错误
            var testDataInvalid = function(data) {
              var errors = [];
              if (!data.title) {
                errors.push('title');
              }
              if (!data.author) {
                errors.push('author');
              }
              if (!data.content) {
                errors.push('content');
              }
              if (data.content_source_url_key && !data.content_source_url) {
                errors.push('content_source_url');
              }
              if(!data.thumb_media_id){
                errors.push('thumb_media_id');
              }
              return errors;
            }

            //保存
            $addPicwordForm.on('click', '.jfk-form__control>a', function() {
              var $that = $(this);
              var type = $that.data('type')
              var datas = articlesMap.articles;
              //防止添加后直接点击保存
              getFormData();
              var go = true;
              $.each(datas, function(id, data) {
                var errors = testDataInvalid(data);
                if (errors.length) {
                  selectPicwordById(id);
                  showFormError(errors);
                  go = false;
                  return false;
                }

              });
              if (!go) {
                return;
              }
              var _articles = []
              $.each(articlesMap.order, function(idx, id) {
                _articles.push(articlesMap.articles[id]);
              });
              saveArticlesData(_articles, renderArticles, type);

            })
                    //标题变更
        var changePicwordListTitle = function(title){
            var curIdx = articlesMap.curIdx;
            var $ele = $currMediaListItem.find('.article-item__title')
            $ele.text(title);
        }
        $('input[type="text"][name="title"]').on('input', function(){
            var val = $.trim($(this).val()) || '标题';
            changePicwordListTitle(val);
        });

            //隐藏错误
            $addPicwordForm.on('blur', '.required', function() {
              var $that = $(this);
              var name = $that.prop('name');
              var val = $.trim($that.val());
              if (val) {
                hideFormError(name);
              }
            })
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