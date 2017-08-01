<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/plugins/datatables/dataTables.bootstrap.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/html5shiv.min.js"></script>
    <script src="<?php echo base_url(FD_PUBLIC) ?>/js/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo base_url(FD_PUBLIC) ?>/datepicker/css/bootstrap-datepicker.min.css">
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script src="<?php echo base_url(FD_PUBLIC) ?>/datepicker/js/bootstrap-datepicker.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php
if(isset($_GET['t']) && !empty($_GET['t'])){
    $tt=$_GET['t'];
}else{

    $tt='';
}
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
        <section class="content-header">
          <h1><?php echo $breadcrumb_array['action']; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb"><?php echo $breadcrumb_html; ?></ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <?php echo $this->session->show_put_msg(); ?>
              <!--
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <style>
                .img-row{border-bottom:1px solid #ddd;vertical-align:middle;padding:5px;margin:10px;}
                .img-row input{margin-top: 10%;}
                .col-img img{width: 100%;max-width: 200px;max-height: 100px;}
                </style>
                <div class="box-body">
                <form><?php if(isset($datas['hotels'])):?>
                  <div class="row">
                  	<div class="col-md-4 col-img">
                  		<label>选择酒店</label><select class="form-control" id="hotels">
                  			<?php foreach ($datas['hotels'] as $key=>$val):?><option value="<?php echo $key?>"<?php if($key == $this->input->get('hid')):echo ' selected';endif;?>><?php echo $val?></option><?php endforeach;?>
                  		</select>
                        <?php if(count($datas['hotels'])>10){?>
						     <div style='margin-top: 5px;'>
						    	<input type="text" name="qhs" id="qhs" placeholder="快捷查询">
						 	  	<input type="button" onclick='quick_search()' value='查询' />
						 	  	<input type="button" onclick='go_hotel("next")' value='下一个' />
						 	  	<input type="button" onclick='go_hotel("prev")' value='上一个' />
						 	  	<input type="button" onclick='to_search()' value='确定搜索' />
						 	  	<span id='search_tip' style='color:red'></span>
						    </div>
                         <?php }?>
                  	</div>
                  </div><?php endif;?>
                  <?php if(isset($datas['rooms'])):?>
                  <div class="row">
                    <div class="col-md-4 col-img">
                      <label>选择房型</label><select class="form-control" id="rooms">
                        <?php foreach ($datas['rooms'] as $room):?><option value="<?php echo $room['room_id']?>"<?php if($room['room_id'] == $this->input->get('rid')):echo ' selected';endif;?>><?php echo $room['name']?></option><?php endforeach;?>
                      </select>
                    </div>
                  </div><?php endif;?>
                  <div class="form-group">
                  	<label>图片链接</label>
                  	<input type="text" name="imgurl" id="imgurl" class="form-control" /><input type="file" id="upfiles" />
                  </div>
                  <div class="form-group">
                  	<label>图片描述</label>
                  	<input type="text" size=2 name="imgdescribe" id="imgdescribe" class="form-control" />
                  </div>
                  <?php if(empty($tt)){ ?>
                    <div class="form-group">
                        <label>链接地址</label>
                        <input type="text" size=2 name="imglink" id="imglink" class="form-control" />
                    </div>
                   <?php } ?>
                  <div class="form-group">
                  	<label>图片排序</label>
                  	<input type="text" size=2 name="imgsort" id="imgsort" class="form-control" />
                  </div>
                  <button class="btn btn-primary" id="btn_save" type="button">保存</button>
                  </form>
                  <div class="row">&nbsp;</div>
                  <div class="row">
                  	<div class="col-md-3 col-img">图片</div>
                  	<div class="col-md-3">图片描述</div>
                      <?php if(empty($tt)){ ?>
                      <div class="col-md-2">链接地址</div>
                      <?php } ?>
                  	<div class="col-md-2">排序</div>
                  	<div class="col-md-2">操作</div>
                  </div>
                  <?php if (isset($datas['focus'])): foreach ($datas['focus'] as $item):?>
                  <div class="row img-row">
                  	<div class="col-md-3 col-img"><img src="<?php echo $item->image_url?>" id="imgsurl" value="<?php echo $item->image_url;?>" alt="<?php echo $item->info?>" /></div>
                  	<div class="col-md-3"><input type="text" name="img_alt" id="info" value="<?php echo $item->info?>" class="form-control" /></div>
                  <?php if(empty($tt)){ ?>
                      <div class="col-md-2"><input type="text"  id="link" value="<?php echo $item->link?>" class="form-control" /></div>
                  <?php } ?>
                  	<div class="col-md-2"><input type="text" size=2 id="sort" name="img_sort" value="<?php echo $item->sort?>" class="form-control" /></div>
                  	<div class="col-md-1"><i class="fa fa-fw fa-remove" title="删除" iid="<?php echo $item->id?>"></i></div>
                      <div class="col-md-1"><i class="fa fa-fw fa-update" title="修改" iid="<?php echo $item->id?>"  sort="<?php echo $item->sort;?>" link="<?php echo $item->link;?>" imgsurl="<?php echo $item->image_url;?>" info="<?php echo $item->info;?>">修改</i></div>
                  </div><?php endforeach; else: foreach ($datas as $item):?>
                  <div class="row img-row" id="item<?php echo $item['id']?>">
                  	<div class="col-md-3 col-img" ><img src="<?php echo $item['image_url']?>" id="imgsurl1" value="<?php echo $item['image_url']?>" alt="<?php echo $item['info']?>" /></div>
                  	<div class="col-md-3"><input type="text" name="img_alt"  id="info" value="<?php echo $item['info']?>" class="form-control" /></div>

                     <?php if(empty($tt)){ ?>
                         <div class="col-md-2"><input type="text"  id="link"  value="<?php echo $item['link']?>" class="form-control" /></div>
                     <?php } ?>
                    <div class="col-md-2"><input type="text" size=2 name="img_sort"  id="sort" value="<?php echo $item['sort']?>" class="form-control" /></div>
                  	<div class="col-md-2"><i class="fa fa-fw fa-remove" title="删除" iid="<?php echo $item['id']?>"></i></div>
                      <div class="col-md-2"><i class="fa fa-fw fa-update" title="修改" iid="<?php echo $item['id']?>" sort="<?php echo $item['sort']?>" link="<?php if(!empty($item['link'])){ echo $item['link'];}else{ echo "";}?>" imgsurl="<?php echo $item['image_url']?>" info="<?php echo $item['info']?>">修改</i></div>
                  </div><?php endforeach;endif;?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
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
<script src="<?php echo base_url(FD_PUBLIC) ?>/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
var search_index=0;
function quick_search() {
	var hk=$('#qhs').val();
	if(hk){
		$('#search_tip').html('');
		options=$('#hotels option');
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
				}
			}
		});
		if(search_index==0){
			$('#search_tip').html('无结果');
		}
	}
};
function go_hotel(direction){
	selected_option=$('#hotels').find('option:selected');
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
		option=$('#hotels>option[be_search="'+search_index+'"]');
		if(option[0]!=undefined){
			option=option[0];
			option.selected=true;
		}
	}
}
function to_search(){
	selected_option=$('#hotels').find('option:selected');
	selected_option=selected_option[0];
	hid=selected_option.value;
	window.location.href = "<?php echo site_url('hotel/focus')?><?php echo "/".$this->uri->segment(3);if(!empty($this->input->get('t'))):echo '?t='.$this->input->get('t').'&';else:echo '?';endif; ?>hid="+hid;
}
	<?php $timestamp = time();?>
	$(function() {
		$('#hotels').change(function(){
            <?php if(isset($datas['rooms'])){?>
			    window.location.href = "<?php echo site_url('hotel/focus')?><?php echo "/".$this->uri->segment(3); if(!empty($this->input->get('t'))):echo '?t='.$this->input->get('t').'&';else:echo '?';endif; ?>hid="+$(this).val();
            <?php  }else{ ?>
                window.location.href = "<?php echo site_url('hotel/focus/hotel_focus')?><?php if(!empty($this->input->get('t'))):echo '?t='.$this->input->get('t').'&';else:echo '?';endif; ?>hid="+$(this).val();
            <?php };?>
		});
    $('#rooms').change(function(){
      window.location.href = "<?php echo site_url('hotel/focus')?><?php echo "/".$this->uri->segment(3);if(!empty($this->input->get('t'))):echo '?t='.$this->input->get('t').'&';if(!empty($this->input->get('hid'))):echo '&hid='.$this->input->get('hid').'&';endif;else:echo '?';endif; ?>rid="+$(this).val();
    });
		$('#upfiles').uploadify({
			'formData'     : {
				'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>',
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '<?php echo base_url(FD_PUBLIC) ?>/uploadify/uploadify.swf',
			'uploader' : '<?php echo site_url('basic/uploadftp/do_upload') ?>',
			'file_post_name': 'imgFile',
		    'onUploadSuccess' : function(file, data, response) {
			    var res = $.parseJSON(data);
        		$('#imgurl').val(res.url);
        	}
		});
		$("#btn_save").click(function(){
			var imgurl = $('#imgurl').val();
			var describe   = $('#imgdescribe').val();
            var sort   = $('#imgsort').val();
            var link   = $('#imglink').val();
			if(imgurl == '' || imgurl == undefined){
				alert('请填写图片路径');
                return;
			}
//            describe == undefined && (describe = '');
//            link == undefined && (link = '');
//			sort == undefined && (sort = 0);
			$.post("<?php echo site_url('hotel/focus/save')?>?t=<?php echo $this->input->get('t')?>",{'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>','imgurl':imgurl,'sort':sort,'link':link,'describe':describe<?php if (isset($datas['hotel_id'])):?>,'hotel_id':<?php echo $datas['hotel_id']; endif;?><?php if (isset($datas['room_id'])):?>,'room_id':<?php echo $datas['room_id']; endif;?>,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
				if(datas.errmsg == 'ok'){
					alert('保存成功');
					window.location.replace(location.href);
				}else{
					alert('保存失败');
				}
			},'json');
		});
		$('.img-row .fa-remove').click(function(){
			if(confirm('删除后将不可恢复，确定要删除吗？')){
				$.getJSON("<?php echo site_url('hotel/focus/del')?>?t=<?php echo $this->input->get('t')?>",{'key':$(this).attr('iid')<?php if (isset($datas['hotel_id'])):?>,'hotel_id':<?php echo $datas['hotel_id']; endif;?><?php if (isset($datas['room_id'])):?>,'room_id':<?php echo $datas['room_id']; endif;?>,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
					if(datas.errmsg == 'ok'){
						alert('删除成功');
						window.location.replace(location.href);
					}else{
						alert('删除失败，请重试');
					}
				});
			}
		});

        $('.img-row .fa-update').click(function(){
//            alert($(this).parent().parent().find('#imgsurl1').val());return;
            if(confirm('保存修改？')){
                $.getJSON("<?php echo site_url('hotel/focus/update')?>?t=<?php echo $this->input->get('t')?>",{'key':$(this).attr('iid'),'link':$(this).parent().parent().find('#link').val(),'sort':$(this).parent().parent().find('#sort').val(),'info':$(this).parent().parent().find('#info').val()<?php if (isset($datas['hotel_id'])):?>,'hotel_id':<?php echo $datas['hotel_id']; endif;?><?php if (isset($datas['room_id'])):?>,'room_id':<?php echo $datas['room_id']; endif;?>,'inter_id':'<?php echo isset($datas['inter_id']) ? $datas['inter_id'] : $inter_id; ?>'},function(datas){
                    if(datas.errmsg == 'ok'){
                        alert('保存成功');
//                        window.location.reload();
                    }else{
                        alert('保存失败，请重试');
                    }
                });
            }
        });
	});
$(document).ready(function() {

});
</script>
</body>
</html>
