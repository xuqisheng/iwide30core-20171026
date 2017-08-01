<!DOCTYPE html> <html>
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>分销福利发放</title>
<link href="<?php echo base_url('public/okpay/default/styles/weui.css')?>" rel="stylesheet">
<style type="text/css">
	.hd {padding: 2em 0;}
	.page_title {text-align: center;font-size: 34px;color: #3cc51f;font-weight: 400;margin: 0 15%;}
	.page_desc {text-align: center;color: #888;font-size: 14px;}
</style>
</head>
<body>
<div class="container" id="container"><div class="home">
<?php if($type == 'index'):?>
<div class="hd">
    <h1 class="page_title">授权验证</h1>
    <p class="page_desc">分销福利发放授权验证</p>
</div>
<?php /*
<!--<div class="bd">
    <div class="weui_grids">
        <a href="#/button" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_button"></i>
            </div>
            <p class="weui_grid_label">
                Button
            </p>
        </a>
        <a href="#/cell" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_cell"></i>
            </div>
            <p class="weui_grid_label">
                Cell
            </p>
        </a>
        <a href="#/toast" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_toast"></i>
            </div>
            <p class="weui_grid_label">
                Toast
            </p>
        </a>
        <a href="#/dialog" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_dialog"></i>
            </div>
            <p class="weui_grid_label">
                Dialog
            </p>
        </a>
        <a href="#/progress" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_progress"></i>
            </div>
            <p class="weui_grid_label">
                Progress
            </p>
        </a>
        <a href="#/msg" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_msg"></i>
            </div>
            <p class="weui_grid_label">
                Msg
            </p>
        </a>
        <a href="#/article" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_article"></i>
            </div>
            <p class="weui_grid_label">
                Article
            </p>
        </a>
        <a href="#/actionsheet" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_actionSheet"></i>
            </div>
            <p class="weui_grid_label">
                ActionSheet
            </p>
        </a>
        <a href="#/icons" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_icons"></i>
            </div>
            <p class="weui_grid_label">
                Icons
            </p>
        </a>
        <a href="#/panel" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_panel"></i>
            </div>
            <p class="weui_grid_label">
                Panel
            </p>
        </a>
        <a href="#/tab" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_tab"></i>
            </div>
            <p class="weui_grid_label">
                Tab
            </p>
        </a>
        <a href="#/searchbar" class="weui_grid">
            <div class="weui_grid_icon">
                <i class="icon icon_search_bar"></i>
            </div>
            <p class="weui_grid_label">
                SearchBar
            </p>
        </a>
    </div>
</div>
</div></div>-->
<!--<div class="weui_cells_title">带图标、说明、跳转的列表项</div>-->
*/?>
<?php echo form_open('distribute/dis_v1/s')?>
<input type="hidden" name="t" value="<?php echo $typ?>">
<input type="hidden" name="token" value="<?php echo $token?>">
<button class="weui_btn weui_btn_primary">确认授权</button>
</form>
<?php elseif($type == 'success'):?>
<!-- 操作成功 -->
<div class="weui_msg">
   <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
   <div class="weui_text_area">
       <h2 class="weui_msg_title">操作成功</h2>
       <p class="weui_msg_desc">点击后台的确定按钮可发放福利</p>
   </div>
</div>
<!---->
<?php elseif($type == 'failed'):?>
<!-- 操作失败 -->
<div class="weui_msg">
   <div class="weui_icon_area"><i class="weui_icon_warn weui_icon_msg"></i></div>
   <div class="weui_text_area">
       <h2 class="weui_msg_title">操作失败</h2>
       <p class="weui_msg_desc">请联系管理员了解失败原因</p>
   </div>
</div>
<!---->
<?php else:?>
<!-- 参数错误 -->
<div class="weui_msg">
   <div class="weui_icon_area"><i class="weui_icon_cancel weui_icon_msg"></i></div>
   <div class="weui_text_area">
       <h2 class="weui_msg_title"><?php echo empty($errmsg) ? '参数错误' : $errmsg;?></h2>
       <p class="weui_msg_desc">请联系管理员了解详细信息</p>
   </div>
</div>
<!---->
<?php endif?>
</body>
</html>