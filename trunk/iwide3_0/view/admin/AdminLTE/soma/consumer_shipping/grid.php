<!-- DataTables -->
<link rel="stylesheet"
  href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/images/laydate12.css">
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/huang.css">
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
<style>

.table{display:table;margin-bottom:0px;}
.table >div{display: table-cell;width:137px;}
.contents{padding:10px 0px 85px 20px;}
.contents_list{display:table;width:100%;border:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont >div{margin-bottom:10px;cursor:pointer;}
.head_cont >div:last-child{margin-bottom:0px;}
.h_btn_list .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> div:last-child{margin-right:0px;}
.f_r_con >div,.j_head >div{display:inline-block;}
.f_r_con >div{margin-right:20px;}
.classification_n{height:50px;line-height:50px;margin-bottom:18px;}
.classification_n >div{width:98px;display:inline-block;text-align:center;height:50px;}
.classification_n .add_active{border-bottom:3px solid #ff9900;}
.fomr_term{height:30px;line-height:30px;}
.classification_n >div,.all_open_order{cursor:pointer;}
.template >div{text-align:center;}
.template >div:nth-of-type(1){width:250px;text-align:left;padding-left:10px;}
.template_img{float:left;width:50px;height:50px;overflow:hidden;vertical-align:middle;margin-right:2%;}
.template_span{display:inline-block;margin-top:2px;}
.template_btn{padding:1px 8px;border-radius:3px;}
.temp_con >div >span{line-height:1.7;}
.room{width:52px;display:inline-block;}
.con_list > div:nth-child(odd){background:#fafcfb;}
.con_list{display:none;}


</style>

<?php
  $filter = $this->input->get(null, true);
  $base_page = array('page_num' => 1, 'page_size' => 20);
?>

<div class="fixed_box bg_fff">
  <div class="tile">商品发货</div>
  <div class="f_b_con">
    <span>快递公司</span>
    <span>
      <select class="w_200 express">
        <option value="顺风快递">顺风快递</option>
        <option value="优速快寄">优速快寄</option>
      </select>
    </span>
  </div>
  <div class="f_b_con">
    <span>快递单号</span>
    <span><input class="w_200 order_number" type="text" value=""></span>
  </div>
  <div class="h_btn_list clearfix" style="">
    <div class="actives confirms">确认</div>
    <div class="cancel f_r">取消</div>
  </div>
</div>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;">
            <div class="banner bg_fff p_0_20">邮寄申请管理</div>
            <div class="contents">
              <?php echo $this->session->show_put_msg(); ?>
              <!-- <div class="p_r_30 classification_n b_b_1 bg_fff">
                <?php foreach($model->get_status_label() as $k => $v): ?>
                  <?php $params = array_merge($base_page, array('filter[status]' => $k)); ?>
                  <?php $s_filter = $this->input->get('filter', true); ?>
                  <div class="<?php if($k==$s_filter['status']) { echo 'add_active'; } ?>"><a href="<?php echo Soma_const_url::inst()->get_url('*/*/*', $params); ?>"><?php echo $v ?></a></div>
                <?php endforeach; ?>
                <div class="<?php if(count($s_filter) <= 0) { echo 'add_active'; } ?>"><a href="<?php echo Soma_const_url::inst()->get_url('*/*/*', $base_page); ?>">全部</a></div>
              </div> -->

              <form action="<?php echo Soma_const_url::inst()->get_url('*/*/*'); ?>" class="form" method='get' id="this_form" >

                <div class="head_cont contents_list bg_fff">
                  <div class="j_head">
                        <div>
                            <span>邮寄ID</span>
                            <span><input class="w_200" type="text" name="filter[shipping_id]" value="<?php echo isset($filter['filter']['shipping_id']) ? $filter['filter']['shipping_id'] : '' ; ?>"></span>
                        </div>
                        <div>
                            <span>订单号</span>
                            <span><input class="w_200" type="text" name="filter[order_id]" value="<?php echo isset($filter['filter']['order_id']) ? $filter['filter']['order_id'] : '' ; ?>"></span>
                        </div>
                        <!-- <div>
                            <span>配送商</span>
                            <span><input class="w_200" type="text"></span>
                        </div> -->
                        <div>
                            <span>快递单号</span>
                            <span><input class="w_200" type="text" name="filter[tracking_no]" value="<?php echo isset($filter['filter']['tracking_no']) ? $filter['filter']['tracking_no'] : '' ; ?>"></span>
                        </div>
                        <div>
                            <span>联系人</span>
                            <span><input class="w_200" type="text" name="filter[contacts]" value="<?php echo isset($filter['contacts']) ? $filter['contacts'] : '' ; ?>"></span>
                        </div>
                        <div>
                            <span>联系电话</span>
                            <span><input class="w_200" type="text" name="filter[phone]" value="<?php echo isset($filter['filter']['phone']) ? $filter['filter']['phone'] : '' ; ?>"></span>
                        </div>
                        <div>
                          <span>申请时间</span>
                          <span class="t_time"><input name="start" type="text" id="datepicker" class="datepicker moba" value="<?php echo isset($filter['start']) ? $filter['start'] : ''; ?>"></span>
                          <font>至</font>
                          <span class="t_time"><input name="end" type="text" id="datepicker2" class="datepicker moba" value="<?php echo isset($filter['end']) ? $filter['end'] : ''; ?>"></span>
                        </div>
                        <?php if(isset($s_filter['status'])): ?>
                          <input name="filter[status]" type="text" style="display: none;" value="<?php echo $s_filter['status']; ?>">
                        <?php endif; ?>
                        <input name="page_num" type="text" style="display: none;" value="<?php echo $base_page['page_num']; ?>">
                        <input name="page_size" type="text" style="display: none;" value="<?php echo $base_page['page_size']; ?>">
                  </div>
                  <div class="h_btn_list" style="">
                      <div class="actives" id='export_btn'>导出</div>
                      <div class="" id='batch_mail_btn'>批量邮寄</div>
                      <div id="search_btn" class="pointer">筛选</div>
                  </div>
                </div>
              </form>
              <div class="" style="font-size:13px;">
                <div class="bg_f8f9fb table fomr_term template">
                  <div>酒店&商品</div>
                  <div>份数</div>
                  <div>客户信息</div>
                  <div>用户地址</div>
                  <div>邮寄状态</div>
                  <div>邮寄时间&快递单号</div>
                </div>
              </div>

              <?php //var_dump($result['data']);exit; ?>
              <?php $status_label = $model->get_status_label(); ?>
              <?php foreach($result['data'] as $row): ?>
                <?php 
                  $order = $row['order_info'];
                  $shipping = $row['ori_info'];
                ?>
                <div class="border_1 m_t_10 bg_fff">
                  <div class="bg_f8f9fb fomr_term p_0_30_0_10 b_b_1">
                    <a href="<?php echo Soma_const_url::inst()->get_url('*/*/edit', array('ids' => $row['DT_RowId'])); ?>" class="f_r color_F99E12">订单详情</a>
                    <div>订单号：<?php echo $order['order_id']; ?></div>
                  </div>
                  <div class="table temp_con template p_t_10 p_b_10">
                    <div class="clearfix">
                      <img class="template_img" src="<?php echo $order['items'][0]['face_img']; ?>">
                      <span class="template_span"><?php echo $order['inter_name']; ?></span><br>
                      <span><?php echo $order['items'][0]['name']; ?></span>
                    </div>
                    <div>
                      <!-- <span><?php echo $order['real_grand_total']; ?></span><br> -->
                      <span><?php echo $shipping['qty']; ?>份</span>
                    </div>
                    <div>
                      <span><?php echo $shipping['contacts']; ?></span><br>
                      <span><?php echo $shipping['phone']; ?></span>
                    </div>
                    <div>
                      <span><?php echo $shipping['address']; ?></span><br>
                      <span></span>
                    </div>
                    <div>
                      <span><?php echo $status_label[ $shipping['status'] ]; ?></span><br>
                      <!-- <span class="color_ff9900 delivery">发货</span> -->
                    </div>
                    <div class="f_times">
                      <span><?php echo $shipping['post_time']; ?></span><br>
                      <span><?php echo $shipping['tracking_no']; ?></span><br>
                      <!--
                      <?php $mail_time_arr = explode(' ', $row[5])?>
                      <span><?php echo isset($mail_time_arr[0]) ? $mail_time_arr[0] : '--'; ?></span><br>
                      <span><?php echo isset($mail_time_arr[1]) ? $mail_time_arr[1] : '&nbsp;'; ?></span>
                      -->
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>

              <!-- 分页代码开始 -->
                
                <div class="pages">
                <div id="Pagination">
                  <div class="pagination">
                    <?php
                      // 分页代码需要复制页面开始代码中的 $filter与 $base_page代码
                      
                      // 每页显示5条数据
                      $page_size = $base_page['page_size'];
                      // 总页数
                      $total_page = ceil($result['total'] / $page_size);
                      // 当前页，从get参数获取
                      $current_page = isset($filter['page_num']) ? intval($filter['page_num']) : $base_page['page_num'];
                      if($current_page <= 0) { $current_page = $base_page['page_num']; }

                      // 当前链接
                      $params = $filter;
                      unset($params['page_num']);
                      unset($params['page_size']);
                      unset($params['filter']);

                      if(isset($filter['filter'])
                        && is_array($filter['filter'])) {
                        foreach ($filter['filter'] as $k => $v) {
                          $key = 'filter[' . $k . ']';
                          $params[$key] = $v;
                        }
                      }

                      $current_url = Soma_const_url::inst()->get_url('*/*/*', $params);
                      if(strpos($current_url, "?") === false) {
                        $current_url .= '?';
                      } else {
                        $current_url .= '&';
                      }

                      // 第一页链接
                      $first_page = $current_url . 'page_num=' . 1 . '&page_size=' . $page_size;

                      $pre_page = $pre_two_page = $nxt_page = $nxt_two_page = null;
                      // 上两页链接
                      if(($current_page - 1) > 1) {
                        $pre_two_page = $current_url . 'page_num=' . ($current_page - 2) . '&page_size=' . $page_size;
                      }
                      // 上一页链接
                      if($current_page > 1) {
                        $pre_page = $current_url . 'page_num=' . ($current_page - 1) . '&page_size=' . $page_size;
                      }
                      // 下一页链接
                      if($current_page < $total_page) {
                        $nxt_page = $current_url . 'page_num=' . ($current_page + 1) . '&page_size=' . $page_size;
                      }
                      // 下两页页链接
                      if(($current_page + 1) < $total_page) {
                        $nxt_two_page = $current_url . 'page_num=' . ($current_page + 2) . '&page_size=' . $page_size;
                      }
                      // 最后一页链接
                      $last_page = $current_url . 'page_num=' . $total_page . '&page_size=' . $page_size;

                    ?>
                    
                    <?php if($current_page > 1): ?>
                      <a href="<?php echo $pre_page; ?>">&lt;</a>
                    <?php endif; ?>
                    <?php if($current_page > 3): ?>
                      <a href="<?php echo $first_page; ?>">1</a>
                      <a>...</a>
                    <?php endif; ?>
                    <?php if($pre_two_page != null): ?>
                      <a href="<?php echo $pre_two_page; ?>"><?php echo intval($current_page - 2); ?></a>
                    <?php endif; ?>
                    <?php if($pre_page != null): ?>
                      <a href="<?php echo $pre_page; ?>"><?php echo intval($current_page - 1); ?></a>
                    <?php endif; ?>
                    <a class="number current" href="#"><?php echo intval($current_page); ?></a>
                    <?php if($nxt_page != null): ?>
                      <a href="<?php echo $nxt_page; ?>"><?php echo intval($current_page + 1); ?></a>
                    <?php endif; ?>
                    <?php if($nxt_two_page != null): ?>
                      <a href="<?php echo $nxt_two_page; ?>"><?php echo intval($current_page + 2); ?></a>
                    <?php endif; ?>
                    <?php if(($current_page + 2) < $total_page): ?>
                      <a>...</a>
                      <a href="<?php echo $last_page; ?>"><?php echo intval($total_page); ?></a>
                    <?php endif; ?>
                    <?php if($current_page < $total_page): ?>
                      <a href="<?php echo $nxt_page; ?>">&gt;</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- 分页代码结束 -->
            </div>
        </div>
    </div>
</div>
<?php
/* Footer Block @see footer.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'footer.php';
?>

<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'right.php';
?>



<?php
/* Right Block @see right.php */
require_once VIEWPATH . $tpl . DS . 'privilege' . DS . 'commonjs.php';
?>
<!--日历调用开始-->
<!-- <script src="<?php echo base_url(FD_PUBLIC);?>/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<script src="<?php echo base_url(FD_PUBLIC).'/'.$tpl;?>/plugins/datatables/layDate.js"></script>
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
<!--日历调用结束-->
<script>

$('#export_btn').click(function(){

    <?php 
      $exp_filter = $this->input->get(null, true);
      unset($exp_filter['page_num']);
      unset($exp_filter['page_size']);
      if(isset($exp_filter['filter'])) {
        foreach ($exp_filter['filter'] as $key => $value) {
          $exp_filter[$key] = $value;
        }
        unset($exp_filter['filter']);
      }
    ?>
    window.location = "<?php echo Soma_const_url::inst()->get_url('*/*/export_list', $exp_filter); ?>";

});

$('#batch_mail_btn').click(function(){
  window.location = "<?php echo Soma_const_url::inst()->get_url('*/*/batch'); ?>";
});

$('#search_btn').click(function(){
  $('#this_form').submit();
});

$(function(){
  var $this;
  $('.delivery').click(function(){
    $this=$(this)
    if($this.html()=='发货'){
      $('.fixed_box').show();
    }
  })
  $('.confirms').click(function(){
      if($('.order_number').val()!=''){
        $('.fixed_box').hide();
        var timess=new Date();
        var y=timess.getFullYear();
        var m=timess.getMonth()+1;
        var d=timess.getDate();
        var h=timess.getHours();
        var mu=timess.getMinutes();
        var s=timess.getSeconds();
        var str=$(this).parent().parent().find('.express').val()+'('+$(this).parent().parent().find('.order_number').val()+')';
        $this.parent().find('span:first-child').html(str);
        $this.html('已发货');
        $this.parent().parent().find('.f_times span:nth-of-type(1)').html(y+'.'+m+'.'+d);
        $this.parent().parent().find('.f_times span:nth-of-type(2)').html(h+':'+mu+':'+s);
      }else{
        alert('请填写快递单号');
      }
  })
  $('.cancel').click(function(){
      $('.fixed_box').hide();
  })

  var bool=true;
  var obj=null;
    $('.drow_list li').click(function(){
        $('#search_hotel').val($(this).text());
        $('#search_hotel_h').val($(this).val());
        $(this).addClass('cur').siblings().removeClass('cur');
    });
  $('.classification_n >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })
  $('.open_order').click(function(){
    $(this).parent().parent().next().slideToggle();
  })
  $('.all_open_order').click(function(){
    $('.con_list').slideToggle();
  })
  var tips=$('#tips');
  $('.btn_o').click(function(){
    //console.log( decodeURIComponent($(".form").serialize(),true));
    start=$('.t_time').find('input[name="start_t"]').val().replace(/-/g,'');
    end=$('.t_time').find('input[name="end_t"]').val().replace(/-/g,'');
    if(start!=''&&start!=undefined){
      if(isNaN(start)){
        tips.html('开始日期错误');
        setout(tips);
        return false;
      }
      if(end!=''&&end!=undefined){
        if(isNaN(end)||end<start){
          tips.html('结束日期错误或大于开始日期');
          setout(tips);
          return false;
        }
      }
    }
  })
})
</script>
</body>
</html>
