
<link href="<?php echo base_url('public/distribute/default/styles/incom.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/my_fans.css')?>" rel="stylesheet">
<link href="<?php echo base_url('public/distribute/default/styles/ui.css')?>" rel="stylesheet">
<title>琅琊榜</title>
</head>
<body>
<div class="headr">
    <div><a<?php if($this->input->get('c') == 'day' && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking')?>?id=<?php echo $inter_id?>&c=day"<?php endif;?>>日收益</a></div>
    <div><a<?php if($this->input->get('c') == 'day' && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=day"<?php endif;?>>日粉丝</a></div>
    <div><a<?php if($this->input->get('c') == 'month' && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking')?>?id=<?php echo $inter_id?>&c=month"<?php endif;?>>月收益</a></div>
    <div><a<?php if($this->input->get('c') == 'month' && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=month"<?php endif;?>>月粉丝</a></div>
    <div><a<?php if((!$this->input->get('c') || $this->input->get('c') == 'all') && $this->uri->segment(3) == 'ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/ranking');?>?id=<?php echo $inter_id?>&c=all"<?php endif;?>>总收益</a></div>
    <div><a<?php if((!$this->input->get('c') || $this->input->get('c') == 'all') && $this->uri->segment(3) == 'fans_ranking'):?> class="col"<?php else:?> href="<?php echo site_url('distribute/dis_v1/fans_ranking')?>?id=<?php echo $inter_id?>&c=all"<?php endif;?>>总粉丝</a></div>
</div>
<div class="f_box">
	<?php if($my_rank):?>
	<div class="fans noe clearfix">
    	<div class="nan_img img_auto_cut"><img class="lazy" src="<?php echo base_url('public/distribute/default/images/header.jpg')?>" data-original="<?php echo $my_rank['headimgurl']?>"/></div>
        <div class="nan_txt">
        	<p class="use"><?php echo $my_rank['nickname']?></p>
        	<p class="con">排名：<font><?php echo $my_rank['rank']?></font></p>
        	<p class="con">粉丝数：<font><?php echo $my_rank['fans_count']?></font></p>
        </div>
    </div><?php endif;?>
	<?php foreach($rankings as $man):?>
	<div class="fans clearfix">
    	<div class="nan_img img_auto_cut"><img class="lazy" src="<?php echo base_url('public/distribute/default/images/header.jpg')?>" data-original="<?php echo $man['headimgurl']?>"/></div>
        <div class="nan_txt">
        	<p class="use"><?php echo $man['name']?></p>
        	<p class="con">粉丝数：<font><?php echo $man['fans_count']?></font></p>
        	<p class="con">排名：<font>第<?php echo $man['rank']?>名</font></p>
        </div>
    </div><?php endforeach;?>
</div>
<script type="text/javascript">
	 $(function() {
      $("img.lazy").lazyload({effect: "fadeIn"});
  });
</script>
</body>
</html>