<!-- DataTables -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="modal fade" id="setModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">显示设置</h4>
      </div>
      <div class="modal-body">
        <div id='cfg_items'>
        <?php echo form_open('distribute/distri_report/save_cofigs','id="setting_form"')?>
          
        </form></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="set_btn_save">保存</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="wrapper">

<?php 
/* 顶部导航 */
echo $block_top;
?>

<?php 
/* 左栏菜单 */
echo $block_left;
?>
<div class="fixed_box bg_fff">
    <div class="tile">模板消息确认</div>
    <div class="f_b_con">确认要将当前模板消息删除吗？</div>
    <div class="h_btn_lists clearfix" style="">
        <div class="actives confirms">确认</div>
        <div class="cancel f_r">取消</div>
    </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;">
          <div class="banner bg_fff p_0_20">
              <?php echo $breadcrumb_html; ?>
          </div>
          <div class="contents">
              <div class="head_cont contents_list bg_fff border_1">
                <div class="j_head">
                  <?php if(empty($no_add)){?>
                  <div  class="h_btn_list" style="">
                    <a class="actives new_mb" href="<?php echo site_url('hotel/tmmsg/edit'); ?>">新建模板消息</a>
                  </div>
                  <?php }?>
                  <div class="f_r" style="display: none;">
                    <span class="relative">
                      <input class="w_220" type="text" placeholder="输入关键字搜索"/>
                      <span class="absolute h_30 search_btn"><i class="iconfont">&#xe6d0;</i></span>
                    </span>
                  </div>
                </div>
              </div>
              <!-- <div class="border_1 bg_fff refresh" style="text-align:center;padding:50px 0px;">还没有模版消息,点击请刷新</div>  --><!-- 没有消息时显示 -->
              <div class="news_list">
              <?php $num = 1;?>
                <?php if(!empty($list)){ foreach($list as $k => $lt){ ?>
                  <div class="news_con border_1 m_t_10" temp_id = "<?php echo $k;?>">
                    <div class="n_title bg_f8f9fb p_10_27 b_b_1">
                      <div>序号：<?php echo $num++;?></div>
                      <div>创建时间：<?php echo date('Y.m.d H:i:s',$lt['edit_time']);?></div>
                      <div>
                        <a class="color_2d87e2" href="<?php echo site_url('hotel/tmmsg/edit').'?tid='.$lt['temp_type'];?>">编辑</a>
                        <a class="delete_btn">删除</a>
                      </div>
                    </div>
                    <div class="bg_fff con_lsit">
                      <div class="row_con">
                        <div>模版名称：<?php echo $lt['des'];?></div>
                        <div>模版ID：<?php echo $lt['temp_id'];?></div>
                      </div>
                      <div class="row_con">
                        <div>引流页面：<?php echo $lt['url_type'];?></div>
                        <div>状态：<?php echo $lt['status'];?></div>
                      </div>
                    </div>
                  </div>
                <?php }}?>
              </div>
          </div>
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

<script>
$(function(){

  $('.delete_btn').click(function(){
      var _this=$(this);
          $('.fixed_box').fadeIn();
          $('.confirms').click(function(){
            window.location.href = "<?php echo site_url('hotel/tmmsg/delete').'?tid=';?>" + _this.parents(".news_con").attr('temp_id');
              // $('.fixed_box').fadeOut();
              // _this.parents(".news_con").attr('temp_id');
          });
  });
  $('.cancel').click(function(){
      $('.fixed_box').fadeOut();
  });

})

</script>
</body>
</html>
