<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/AdminLTE/plugins/new/new.css">
<style>

</style>
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
<div class="over_x">
  <div class="content-wrapper" style="min-width:1050px;">
    <div class="banner bg_fff p_0_20"><?php echo $breadcrumb_html; ?></div>
    <div class="contents">
      <from>
        <input id="key" name="key" type="hidden" value="<?php if(isset($row['id'])) echo $row['id'];?>">

        <div class="contents_list bg_fff">
          <div class="con_left"><span class="block bg_ff503f"></span>添加图片</div>
          <div class="con_right">
            <div class="hotel_star">
              <div class="">图片来源</div>
              <div class="input_txt input_radio">
                <div>
                  <input name="imgtype" type="radio" id="star_5" checked>
                  <label for="star_5">本地上传</label>
                </div>
                <div>
                  <input name="imgtype" type="radio" id="star_4">
                  <label for="star_4">网络图片</label>
                  <span class="in_url input_txt" style="display:none;"><input type="text" name="imgurl" id="el_intro_img" placeholder="url" value="<?php if(isset($row['id'])) echo $row['image_url'];?>"/></span>
                </div>
              </div>
            </div>
            <div class="jingwei carousel clearfix">
              <div class="float_left">轮播图片</div>
              <div class="input_txt file_img_list" style="padding-left:4px;">
                <?php if(isset($row['image_url'])){?>
                  <div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="<?php echo $row['image_url'];?>"/>
                    <div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div>
                  </div>
                <?php }?>
                <label id="file" class="add_img"><input class="display_none file_img" type="file" /></label>
              </div>
            </div>
            <?php if($t==0){?>
            <div class="jingwei">
              <div class="">跳转链接</div>
              <div class="input_txt"><input type="text" id="imglink" value="<?php if(isset($row['link'])) echo $row['link'];?>"/></div>
            </div>
            <?php }?>
            <?php if($t>0){?>
            <div class="jingwei">
              <div class="">关联酒店</div>
              <div class="input_txt">
                <select id="hotel" name="hotel" onchange="get_rooms(this)" style="width:450px;">
               <?php foreach ($hotels as $hotel):?><option <?php if(isset($row['id']) && $row['hotel_id']==$hotel['hotel_id']) echo 'selected';?> value="<?=$hotel['hotel_id']?>"><?=$hotel['name']?></option><?php endforeach;?>
               </select>
               <?php if(count($hotels)>10){?>
                <div style='margin-top: 5px;'>
                <input type="text" name="qhs" id="qhs" placeholder="快捷查询">
                  <input type="button" onclick='quick_search()' value='查询' />
                  <input type="button" onclick='go_hotel("next")' value='下一个' />
                  <input type="button" onclick='go_hotel("prev")' value='上一个' />
                  <span id='search_tip' style='color:red'></span>
               </div>
               <?php }?>
              </div>
            </div>
            <?php }?>
            <?php if($t>1){?>
            <div class="jingwei">
              <div class="">关联房型</div>
              <div class="input_txt">
                <select id="room_id" name="room_id" style="width:450px;">
                <?php if(!empty($room_list)){ foreach($room_list as $rl){ ?>
                <option value='<?php echo $rl['room_id'];?>' <?php if(isset($row['id']) && $row['room_id']==$rl['room_id']) echo 'selected';?>><?php echo $rl['name'];?></option>
                <?php }}?>
                </select>
              </div>
            </div>
            <?php }?>
            <div class="address">
              <div class="">图片描述</div>
              <div class="input_txt"><input type="text" id="imgdescribe" value="<?php if(isset($row['id'])) echo $row['info'];?>"/></div>
            </div>
            <div class="jingwei">
              <div class="">图片排序</div>
              <div class="input_txt"><input type="text" id="imgsort" value="<?php if(isset($row['id'])) echo $row['sort'];?>"/></div>
            </div>
          </div>
        </div>
        <div class="bg_fff center" style="padding:15px;">
          <button class="fom_btn">保存</button>
        </div>
      </from>
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
</body>
</html>
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify_html5/jquery.uploadify.js"></script>
<script type="text/javascript">
var room_id=0;
var hotel_id=$('#hotel').val();
var t = '<?php echo $t;?>';
var submitting = false;
function get_rooms(obj){
  if(t!=2){
    return;
  }
  hotel_id = $(obj).val();
  fill_rooms(hotel_id);
}
function fill_rooms(hotel_id){
  hotel_id=hotel_id;
  var _html = '';
  $('#room_id').html(_html);
  $.getJSON('<?php echo site_url('hotel/focus/room_types')?>',{'hid':hotel_id},function(datas){
    $.each(datas,function(k,v){
      _html += '<option value="' + v.room_id +'" ';
      _html+= '>' + v.name+ '</option>';
    });
    $('#room_id').html(_html);
  },'json');
}
var search_index=0;
var hid='';
function quick_search() {
  var hk=$('#qhs').val();
  if(hk){
    $('#search_tip').html('');
    options=$('#hotel option');
    search_index=0;
    $.each(options,function(i,n){
      $(n).css('color','#555');
      $(n).removeAttr('be_search');
      if(n.innerHTML.indexOf(hk)>-1){
        search_index++;
        $(n).css('color','red');
        $(n).attr('be_search',search_index);
        if(search_index==1){
          n.selected=true;
          hid=n.value;
        }
      }
    });
    if(search_index==0){
      $('#search_tip').html('无结果');
    }
  }
};
function go_hotel(direction){
  selected_option=$('#hotel').find('option:selected');
  selected_option=selected_option[0];
  now_index=$(selected_option).attr('be_search');
  if(now_index){
    search_index=now_index;
  }
  if(search_index){
    if(direction=='next'){
      search_index++;
    }else{
      search_index--;
    }
  }
  if(search_index){
    option=$('#hotel>option[be_search="'+search_index+'"]');
    if(option[0]!=undefined){
      option=option[0];
      option.selected=true;
      hid=option.value;
    }
  }
}
  <?php $timestamp = time();?>
  $(function() {

    $('#file').uploadify({
      'formData'     : {
        '<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
        'timestamp' : '<?php echo $timestamp;?>',
        'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
      },
      'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
      //'uploader' : '<?php echo site_url("basic/upload/hotel_upload") ?>',
      'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
      'fileObjName': 'imgFile',
      'buttonImage':"<?php echo base_url(FD_PUBLIC) ?>/js/img/upload.png",
      'fileTypeExts':'*.jpg;*.jpeg;*.gif;*.png',//文件类型
      'height':77,
      'width':77,
        'onUploadSuccess' : function(file, data, response) {
          var res = $.parseJSON(data);
            $('#el_intro_img').val(res.url);
            $('.add_img_box').remove();
               $(".file_img_list").prepend($('<div class="add_img_box" style="float:left;width:77px;height:77px;border:1px solid #d7e0f1;position:relative;margin-right:20px;"><img style="width:77px;height:77px;overflow:hidden;" src="'+res.url+'"/><div class="img_close" style="position:absolute;right:-11px;top:-9px;width:20px;height:20px;background:rgba(0,0,0,0.5);border-radius:50%;text-align:center;color:#fff;line-height:19px;display:none;"><i class="iconfont">&#xe635;</i></div></div>'));
               $('.add_img_box').delegate('.img_close','click',function(){
                  $(this).parent().remove();
                  $("#el_intro_img").val('');
                })
          }
    });
  $('.add_img_box').delegate('.img_close','click',function(){
    $(this).parent().remove();
    $("#el_intro_img").val('');
  })



    $('.input_radio >div >input').change(function(){
      if($('.input_radio >div:nth-of-type(1) >input:checked').val()){
        $('.carousel').show();
      }else{
        $('.carousel').hide();
      }
      if($('.input_radio >div:nth-of-type(2) >input:checked').val()){
        $('.in_url').show();
      }else{
        $('.in_url').hide();
      }
    })


    $(".fom_btn").click(function(){
      if(submitting){
        return;
      }
      submitting = true;
      var imgurl = $('#el_intro_img').val();
      var describe   = $('#imgdescribe').val();
      var sort   = $('#imgsort').val();
      var link   = $('#imglink').val();
      var key = $('#key').val();
      if(imgurl == '' || imgurl == undefined){
        alert('请填写图片路径');
        submitting =false;
        return;
      }
      var locationurl = "<?php echo site_url('hotel/focus/index')?>";
      if(t>0){
        hotel_id = $('#hotel').val();
        if(hotel_id == 0 || hotel_id == undefined || hotel_id == ''){
          alert('请选择酒店');
          submitting = false;
          return;
        }
        locationurl = "<?php echo site_url('hotel/focus/hotel_focus')?>"+'?hid='+hotel_id;
      }
      if(t>1){
        room_id = $('#room_id').val();
        if(room_id == 0 || room_id == undefined || room_id == ''){
          alert('请选择房型');
          submitting = false;
          return;
        }
        locationurl = "<?php echo site_url('hotel/focus/room_focus')?>"+'?hid='+hotel_id+'&rid='+room_id;
      }
      if(key>0){
        if(confirm('保存修改？')){
            $.getJSON("<?php echo site_url('hotel/focus/update_focus')?>",{'key':key,'link':link,'imgurl':imgurl,'sort':sort,'info':describe<?php if ($t>0):?>,'hotel_id':hotel_id<?php endif;?><?php if ($t>1):?>,'room_id':room_id<?php endif;?>,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>','t':t},function(datas){
                if(datas.errmsg == 'ok'){
                    alert('保存成功');
                    window.location.replace(locationurl);
                }else{
                    alert('保存失败，请重试');
                }
            });
        }
      }else{
        $.post("<?php echo site_url('hotel/focus/save').'?t='?>"+t,{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','imgurl':imgurl,'sort':sort,'link':link,'describe':describe<?php if ($t>0):?>,'hotel_id':hotel_id<?php endif;?><?php if ($t>1):?>,'room_id':room_id<?php endif;?>,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
          if(datas.errmsg == 'ok'){
            alert('保存成功');
            window.location.replace(locationurl);
          }else{
            alert('保存失败');
          }
        },'json');
      }
      submitting = false;
    });
  });
</script>