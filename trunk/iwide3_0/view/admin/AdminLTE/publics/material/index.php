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
          <div class="jfk-pages page__material">
            <div class="jfk-tab jfk-tab__material">
              <div class="jfk-tab__title">
                <ul class="jfk-list jfk-list--horizontal">
                  <li data-type="news" class="jfk-list__item jfk-tab__title-item jfk-tab__title-item--selected"><a href="javascript:;">图文消息</a></li>
                  <li data-type="image" class="jfk-list__item jfk-tab__title-item"><a href="javascript:;">图片</a></li>
                </ul>
              </div>
              <div class="jfk-tab__body">
                <div class="jfk-tab__body-item jfk-tab__body-item--selected  material-picword" data-type="news">
                  <!-- <div class="material-picword-search-box jfk-search-component">
    <label class="search-box jfk-search-box">
    <input type="text" class="jfk-search-box__input material-picword-input" placeholder="标题/摘要/作者" />
    <i class="jfk-search-box__icon"></i>
    </label>
    <input type="button" value="查询" class="search-box-button" />
    </div> -->
                  <div class="material__panel">
                    <div class="material__head">
                      <h4 class="material__title">图文消息</h4>
                      <div class="material__add-picword-button">
                        <a href="<?php echo site_url('publics/material/edit_news'); ?>" class="jfk-button jfk-button--default jfk-button__add-picword">+新建图文消息</a>
                      </div>
                    </div>
                    <div class="material__body jfk-material-box">
                      <ul class="jfk-list jfk-list__material jfk-list--horizontal jfk-list--horizontal-3">

                      </ul>
                      <div class="loading-news">正在加载中……</div>
                    </div>
                  </div>
                </div>
                <div class="jfk-tab__body-item material-pic" data-type="image">
                  <div class="material__panel">
                    <div class="material__head">
                      <div class="material__left Lfll">
                        <span class="jfk-checkbox">
        <label>
        <input type="checkbox" name="check-all" class="jfk-checkbox__input jfk-checkbox__check-all"/>
        <i class="jfk-checkbox__placeholder"></i>
        <i class="jfk-checkbox__label">全选</i>
        </label>
        </span>
                        <a href="javascript:;" data-type="delete_all" class="jfk-button jfk-button--simple jfk-button__delete-all pic-control-button">删除</a>
                      </div>
                      <div class="material__right Lflr">
                        <span class="tip">大小不超过5M</span>
                        <a href="javascript:;" class="jfk-button jfk-button--default jfk-button__add-pic">+本地上传</a>
                      </div>
                    </div>
                    <div class="material__body jfk-material-box">
                      <ul class="jfk-list jfk-list__material jfk-list--horizontal jfk-list--horizontal-4">
                        <!--(li.jfk-list__item.jfk-media-box.jfk-media-box__material>.jfk-list__item-box>(.jfk-media-box__body>.jfk-media-box__thumb>img.jfk-media-box__thumb-img[src="./img/2.jpg"])+(.jfk-media-box__control.jfk-cell>(.jfk-cell__bd>label.jfk-checkbox>input.jfk-checkbox__input[type="checkbox"]+i.jfk-checkbox__placeholder+i.jfk-checkbox__label{$$$.jpg})+.jfk-cell_hd>a[title="删除" href="javascript:;"]))*5-->
                      </ul>
                      <div class="loading-image">正在加载中……</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__delete jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <a class="jfk-layer__close jfk-layer__trigger" data-type="cancel" href="javascript:;" title="关闭"></a>
                <div class="jfk-layer__title"></div>
                <div class="jfk-layer__content">确认删除该项？</div>
                <div class="jfk-layer__control">
                  
                  <a href="javascript:;" data-type="cancel" class="jfk-layer__trigger jfk-button jfk-button--simple jfk-button__delete-no">取消</a>
                  <a href="javascript:;" data-type="ok" class="jfk-layer__trigger jfk-button jfk-button--primary jfk-button__delete-ok">确定</a>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__upload-status jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <a class="jfk-layer__close jfk-layer__trigger" data-type="cancel" href="javascript:;" title="关闭">X</a>
                <div class="jfk-layer__title">温馨提示</div>
                <div class="jfk-layer__content">上传失败</div>
                <div class="jfk-layer__control">
                  <a href="javascript:;" data-type="cancel" class="jfk-layer__trigger jfk-button jfk-button--primary jfk-button__delete-ok">确定</a>
                </div>
              </div>
            </div>
          </div>
          <div class="jfk-layer jfk-layer__delete-status jfk-layer--default">
            <div class="jfk-layer__mask"></div>
            <div class="jfk-layer__box">
              <div class="jfk-layer__wrap">
                <div class="jfk-layer__title">温馨提示</div>
                <div class="jfk-layer__content">删除中，请勿操作</div>
              </div>
            </div>
          </div>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
          <script src="<?php echo base_url(FD_PUBLIC) ?>/js/fsy0718_material.js"></script>
          <script>
            var materialUrl = "<?php echo site_url('publics/material/ajax_get_materials');?>";
            var editMaterialUrl = "<?php echo site_url('publics/material/edit_news?media_id=');?>";
            var firstOpenImage = true;
            var renderNewsCustom = function(data) {
              var items = renderNews(data, true);
              var str = items.str + '<div class="jfk-media-box__control"><a href="'+(editMaterialUrl + items.mediaId)+'" class="material-list-editor jfk-media-box__control-item-edit jfk-media-box__control-item" title="编辑"></a><a href="javascript:;" data-value="'+items.mediaId+'" class="material-list-delete jfk-media-box__control-item jfk-media-box__control-item-delete" title="删除"></a></div>';
              items.str = str;
              return items;
            }
            var renderImageCustom = function(data) {
              var str = '<div class="jfk-media-box__body"><div class="jfk-media-box__thumb"><div class="jfk-img__iframe" id="' + data.media_id + '"></div></div></div><div class="jfk-media-box__control jfk-cell"><div class="jfk-cell__bd"><label class="jfk-checkbox"><input type="checkbox" value="' + data.media_id + '" class="jfk-checkbox__input"><i class="jfk-checkbox__placeholder"></i><i class="jfk-checkbox__label">' + data.name + '</i></label></div><div class="jfk-cell__hd"><a class="pic-control-button jfk-media-box__control-item-delete" data-type="delete" data-value="'+data.media_id+'" href="javascript:;" title="删除"></a></div></div>';
              return {
                str: str,
                mediaId: data.media_id,
                showImgs: {
                  url: data.url,
                  id: data.media_id
                }
              }
            }
            materialGetCallbacks.add(getMaterialCallback);

            changeTab('.jfk-tab__material', function(type) {
              if (type === 'image' && firstOpenImage) {
                getMaterialData('image', 1, materialUrl);
                firstOpenImage = false;
              }
            })

            getMaterialData('news', 1, materialUrl);
            var resetPage = false;
            var $checkAllInput = $('.jfk-checkbox__check-all');
            var $tabPic = $('.material-pic');
            var $tabPicword = $('.material-picword');
            var $tabPicList = $tabPic.find('.jfk-list__material');
            $tabPic.on('change', 'input[type="checkbox"]', function (e) {
                var $that = $(this);
                var isChecked = $that.prop('checked');
                var $checkboxList = $tabPic.find('.jfk-list__material input[type="checkbox"]')
                if ($that.hasClass('jfk-checkbox__check-all')) {

                    if ($checkboxList) {
                        $checkboxList.prop('checked', isChecked);
                    }
                } else {
                    if (!isChecked) {
                        $checkAllInput.prop('checked', false);
                    } else if ($checkboxList.filter(":checked").length === $checkboxList.length) {
                        $checkAllInput.prop('checked', true);
                    }
                }
            });
            var deleteStr;
            var deleteType;
            $tabPic.on('click', '.pic-control-button', function (e) {
                var $that = $(this);
                var type = $that.data('type');
                deleteType = 'image';
                if (type) {
                    if (type === 'delete_all') {
                        var checkedList = $tabPic.find('.jfk-list__material input[type="checkbox"]:checked');
                        if (checkedList.length) {
                            deleteStr = 'all';
                            $layerDelete.show();
                        }
                    }
                    if (type === 'delete') {
                        deleteStr = $that.data('value');
                        $layerDelete.show();
                    }
                }
            });
            $tabPicword.on('click', '.material-list-delete', function(e){
                deleteStr = $(this).data('value');
                deleteType = 'news';
                $layerDelete.show();
            })
            //删除素材
            function ajax_del_material(ids, type) {
              $.ajax({
                  url: "<?php echo site_url('publics/material/ajax_del_material');?>",
                  method: 'get',
                  data: {
                      media_ids: ids
                  },
                  beforeSend: function(){
                      $layerDeleteStatus.show();
                  },
                  success: function(datas){
                    deleteStr = null;
                    deleteType = null;
                    resetPage = true;
                    getMaterialData(type, 1, materialUrl);
                    resetPage = false;
                  },
                  complete: function(){
                      $layerDeleteStatus.hide();
                  }
              })
            }
            var $layerDelete = $('.jfk-layer__delete');
            var $layerUpload = $('.jfk-layer__upload-status');
            var $layerDeleteStatus = $('.jfk-layer__delete-status');
            $layerDelete.on('click', '.jfk-layer__trigger', function() {
              var type = $(this).data('type');
              if (type === 'cancel') {
                $layerDelete.hide();
              }
              if (type === 'ok') {
                  var ids = [];
                  if(deleteStr === 'all'){
                    $('.jfk-list__material input[type="checkbox"]:checked').each(function() {
                    ids.push($(this).val());
                    });
                  }else{
                      ids.push(deleteStr);
                  }
                
                console.log(ids);
                ajax_del_material(ids, deleteType);
                $layerDelete.hide();
              }
            });
            $layerUpload.on('click', '.jfk-layer__trigger', function() {
              var type = $(this).data('type');
              if (type === 'cancel') {
                $layerUpload.hide();
              }
            })
            $('.jfk-button__add-pic').uploadify({ //缩略图
              'formData': {
                '<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash();?>',
                'timestamp': Date.now(),
                'token': '123'
              },
              //'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
              'uploader': '<?php echo site_url('basic/uploadftp/add_material ') ?>',
              'fileObjName': 'imgFile',
              'fileTypeExts': '*.bmp;*.png;*.jpeg;*.jpg;*.gif;', //文件类型
              'fileSizeLimit': '5M', //限制文件大小
              'queueSizeLimit': 1,
              'onUploadSuccess': function(file, data) {
                // var res = $.parseJSON(data);
                // $tabPicList.append('<li class="jfk-list__item jfk-media-box jfk-media-box__material"><div class="jfk-list__item-box"><div class="jfk-media-box__body"><div id='+ res.media_id +' class="jfk-media-box__thumb"></div></div><div class="jfk-media-box__control jfk-cell"><div class="jfk-cell__bd"><label class="jfk-checkbox"><input type="checkbox" class="jfk-checkbox__input"><i class="jfk-checkbox__placeholder"></i><i class="jfk-checkbox__label">'+res.name+'</i></label></div><div class="jfk-cell_hd"><a class="pic-control-button" data-type="delete" href="javascript:;" title="删除"></a></div></div></div></li>');
                // showImg(res.url, res.media_id);
                getMaterialData('image', 1, materialUrl);
              },
              'onUploadError': function() {
                $layerUpload.show();
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