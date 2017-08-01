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
<style>
.w_340{width:340px !important;float: left;}
.banner > span{padding:0px 5px;margin-left:5px;border-radius:3px;font-size:11px;}
select,input,.moba{height:30px;line-height:30px;border:1px solid #d7e0f1;text-indent:3px;}
.contents{padding:10px 0px 20px 20px;}
.contents_list{display:table;width:100%;border-bottom:1px solid #d7e0f1;margin-bottom:10px;}
.head_cont{padding:20px 0 20px 10px;}
.head_cont .actives{background:#ff9900;color:#fff;border:1px solid #ff9900 !important;}
.h_btn_list> div{display:inline-block;width:100px;border:1px solid #d7e0f1;text-align:center;padding:6px 0px;border-radius:5px;margin-right:8px;}
.h_btn_list> div:last-child{margin-right:0px;}
.classification .add_active{border-bottom:1px solid #ecf0f5;}
.all_open_order{margin-right:10px;margin-top:5px;}
.img_box >div{width:24.222%;float:left;margin-right:1%;margin-bottom:1%}
.img_box >div:nth-of-type(4n){margin-right:0px;}
.img_con:hover .img_btn{display:block;}
.img_con >img{width:100%;height:auto;}
.img_txt{width:100%;bottom:0px;left:0px;padding:1px 2px;background:rgba(0,0,0,0.8);}
.img_txt p{margin-bottom:0px;}
.img_btn{right:0px;top:0px;padding-right:3px;display:none;}
</style>
<div style="color:#92a0ae;">
    <div class="over_x">
        <div class="content-wrapper" style="min-width:1130px;">
            <div class="banner bg_fff p_0_20">
                <?php echo $breadcrumb_html; ?>
            </div>
            <div class="contents">
        <div class="head_cont contents_list bg_fff" style="<?php if($t==0)echo 'display:none;';?>">
          <div class="j_head">
            <div class="holets w_340" style="<?php if($t==0)echo 'display:none;';?>">
              <span>所属酒店</span>
               <select id="hotel" name="hotel" onchange="get_rooms(this)" class="w_200">
               <?php foreach ($datas['hotels'] as $hotel_id=>$hotel):?><option value="<?=$hotel_id?>"><?=$hotel?></option><?php endforeach;?>
               </select>
               <?php if(count($datas['hotels'])>10){?>
                <div style='margin-top: 5px;'>
                <input type="text" name="qhs" id="qhs" placeholder="快捷查询">
                  <input type="button" onclick='quick_search()' value='查询' />
                  <input type="button" onclick='go_hotel("next")' value='下一个' />
                  <input type="button" onclick='go_hotel("prev")' value='上一个' />
                  <span id='search_tip' style='color:red'></span>
               </div>
               <?php }?>
            </div>
            <div class="rooms w_307"  style="display:none;">
              <span>房型名称</span>
               <select id="room_id" name="room_id" class="w_200">
                <?php if(!empty($datas['rooms'])){ foreach($datas['rooms'] as $rl){ ?>
                <option value='<?php echo $rl['room_id'];?>'><?php echo $rl['name'];?></option>
                <?php }}?>
                </select>
            </div>
          </div>
          <div  class="h_btn_list" style="display: table-row;">
            <div id="filter" class="actives">筛选</div>
          </div>
        </div>
        <div class="contents_list" style="font-size:13px;">
          <a class="f_r all_open_order color_72afd2" id="slide_add" href="<?php echo site_url('hotel/focus/slide_edit')?>">新增图片</a>
          <div class="classification bg_fff">
            <div class="add_active">集团图片</div>
          </div>
        </div>
        <div class="img_box clearfix">
          <?php if(!empty($datas['focus'])){ foreach($datas['focus'] as $vv){ ?>
          <div class="relative img_con">
            <div class="absolute img_btn">
              <a class="edit_btn" href="<?php echo site_url('hotel/focus/slide_edit')."?key=$vv[id]&t=$t"?>">
                编辑
              </a>
              <a class="delete_btn" iid="<?php echo $vv['id'];?>" href="javascript:;">
                删除
              </a>
            </div>
            <img src="<?php echo $vv['image_url'];?>">
            <div class="absolute img_txt">
              <span class="f_r">
                排序:<?php echo $vv['sort'];?>
              </span>
              <p>
                <?php echo $vv['info'];?>
              </p>
            </div>
          </div>
          <?php }}?>
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
<script>
function getQueryString(name) { 
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
  var r = window.location.search.substr(1).match(reg); 
  if (r != null) return unescape(r[2]); return null; 
} 
var room_id=0;
var hotel_id=0;
var t = '<?php echo $t;?>';
var hid =0;
var rid =0;
function get_rooms(obj){
  hotel_id = $(obj).val();
  fill_rooms(hotel_id);
}
function fill_rooms(hotel_id){
  if(t!=2){
    return;
  }
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
          hotel_id=n.value;
          fill_rooms(n.value);
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
      hotel_id=option.value;
      fill_rooms(option.value);
    }
  }
}

function show_img () {
  if(t>0){
    hid = $('#hotel').val();
  }
  if(t==2){
    rid = $('#room_id').val();
    if(rid == 0){
      alert('请选择房型！');return;
    }
  }
  $.getJSON('<?php echo site_url('hotel/focus/ajax_get_slide')?>',{'t':t,'hid':hid,'rid':rid},function(datas){
    var html = '';
    if(t==0){
      var data = datas.data; 
    }else{
      var data = datas.data.focus; 
    }
    $.each(data,function(k,v){
      html += '<div class="relative img_con"><div class="absolute img_btn"><a class="edit_btn" href="'+'<?php echo site_url('hotel/focus/slide_edit').'?key=';?>'+v.id+'&t='+t+'">编辑</a><a class="delete_btn" iid="'+v.id+'"href="javascript:;">删除</a></div>';
      html +='<img src="'+ v.image_url +'" /><div class="absolute img_txt"><span class="f_r">排序:'+v.sort+'</span><p>'+v.info+'</p></div></div>';
    });
    if(data.length==0){
      html = '暂无图片！';
    }
    $('.img_box').html(html);
    $('.delete_btn').click(function(){
      var _this = $(this);
      if(confirm('删除后将不可恢复，确定要删除吗？')){
        $.getJSON("<?php echo site_url('hotel/focus/del')?>",{'t':t,'key':$(this).attr('iid'),'hotel_id':hid,'room_id':rid,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
          if(datas.errmsg == 'ok'){
            alert('删除成功');
            _this.parents('.img_con').remove();
          }else{
            alert('删除失败，请重试');
          }
        });
      }
    });
  },'json');
}

function init_select(){
  if(t==0){
    $('.holets,.rooms').hide();
    $('.add_active').html('集团图片');
  }else if(t==1){
    $('.holets').show();
    $('.rooms').hide();
    $('.add_active').html('酒店图片');
  }else if(t==2){
    $('.holets,.rooms').show();
    $('.add_active').html('房型图片');
  }
  $('#slide_add').attr('href','<?php echo site_url('hotel/focus/slide_edit').'?t='?>'+t);
}
init_select();
$(function(){
  hid = getQueryString('hid');
  rid = getQueryString('rid');
  if(hid){
    $("#hotel").find("option[value="+hid+"]").attr("selected",true); 
  }
  if(rid){
    $("#room_id").find("option[value="+rid+"]").attr("selected",true); 
  }
  hid = $('#hotel').val();
  rid = $('#room_id').val();
  $('.delete_btn').click(function(){
    var _this = $(this);
    if(confirm('删除后将不可恢复，确定要删除吗？')){
      $.getJSON("<?php echo site_url('hotel/focus/del')?>",{'t':t,'key':$(this).attr('iid'),'hotel_id':hid,'room_id':rid,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
        if(datas.errmsg == 'ok'){
          alert('删除成功');
          _this.parents('.img_con').remove();
        }else{
          alert('删除失败，请重试');
        }
      });
    }
  })
  $('.classification >div').click(function(){
    $(this).addClass('add_active').siblings().removeClass('add_active');
  })

  $('.close_btn').click(function(){
      $('.j_toshow').animate({"right":"-330px"},800);
  });

  $('#filter').click(function(){
    show_img();
  });
})
</script>
</body>
</html>
