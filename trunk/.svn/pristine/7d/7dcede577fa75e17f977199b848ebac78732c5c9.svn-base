<?php 
/** 单个菜单格式
<li class="header">功能菜单</li>
<li class="[active] treeview [text-yellow]">
    <a href="#">
        <i class="fa fa-dashboard"></i> <span>概览</span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li [class="active"]><a href="index.html"><a href="#" >
            <i class="fa [fa-circle-o]"></i> Dashboard v1
            [<small class="label pull-right bg-green">12</small>] 
        </a></li>
        <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
    </ul>
</li>
 */

$switch= FALSE;
$default_icon= ' fa-circle-o ';
$submenu_icon= '<i class="fa fa-angle-left pull-right"></i>';
$css_active= ' active ';

if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' )
    $index_prefix= 'http://mp.iwide.cn/index.php/';
else if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='testing' )
    $index_prefix= 'http://30.iwide.cn/index.php/';
//else if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='development' )
//    $index_prefix= 'http://ihotels.iwide.cn/index.php/';
else
    $index_prefix= site_url(). '/';

if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ) 
    $mookcake_prefix= 'http://mk2016.mp.iwide.cn/index.php/';
else if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='testing' ) 
    $mookcake_prefix= 'http://mk.iwide.cn/index.php/';
//else if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='development' ) 
//    $mookcake_prefix= 'http://mk.iwide.cn/index.php/';
else
    $mookcake_prefix= 'http://ma.iwide.cn/index.php/';

$server_path_info= isset($_SERVER['PATH_INFO'])? $_SERVER['PATH_INFO']: isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']: false;
?>
<!-- Left side column. contains the logo and sidebar -->
<style>
.sidebar-menu .fa-angle-left:before {
    content: "\f0d9";
}
.foot_nickname{
	position:absolute; left:0; bottom:0; z-index:1200; cursor:pointer;
}
.foot_nickname>*{padding:15px; text-align:center; width:200px; color:#fff; background:#51759a; display:block}
.foot_nickname a{display:none}
.foot_nickname:hover a{display:block; color:#fff;}
.foot_nickname:hover span{display:none}
</style>
<div class="foot_nickname">
	<span><?php echo $profile['nickname']; ?></span>
	<a href="<?php echo EA_const_url::inst()->get_logout_admin() ?>"><i class="fa fa-sign-out"></i> 退出</a>    
</div>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
       <!-- <div class="user-panel">
            <div class="image">
                <img style="border:1px solid #d7e0f1;" src="<?php echo base_url(FD_PUBLIC). '/'. $tpl ?>/dist/img/iwide_logo.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info hide">
                <p><?php echo $profile['nickname']; ?></p>
                <a href="<?php echo EA_const_url::inst()->get_profile_admin() ?>"><i class="fa fa-circle text-success"></i> </a>
            </div>
        </div>-->
    
        <!-- search form -->
       <!-- <form action="/index.html" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="寻找功能">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>-->
        <!-- /.search form -->
    
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!--<li class="header text-center"> 功能菜单 </li>-->
			<li class="treeview">
                <a href="<?php echo EA_const_url::inst()->get_default_admin(); ?>" >今日概览</a>
			</li>
			<!--<li class="treeview">
                <a href="<?php echo EA_const_url::inst()->get_default_admin(); ?>" ><?php echo EA_const_url::inst()->get_default_tab(); ?></a>
			</li>--><?php 
if(count($menu)>0 ): 
//print_r($menu);die;
    foreach ($menu as $k1=> $L1 ): 
    
?>
            <li class="treeview">
                <a href="<?php echo $L1['name']=='mooncake'? $mookcake_prefix: $index_prefix; echo $L1['href']; //echo $index_prefix. $L1['href'] ?>" >
                   <!-- <i class="fa <?php echo isset($L1['icon'])? $L1['icon']: $default_icon; ?>"></i> -->
                    <span><?php echo $L1['label'] ?></span>
                    <?php if(false) echo '<small class="label pull-right bg-green">new</small>'; ?><?php 


/* //有2级菜单判断开始   */
        
        if(isset($L1['child'])&& count($L1['child'])>0 ):
?>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu"><?php 
            foreach ($L1['child'] as $k2=> $L2 ):
                $referer= isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']: 'dashboard';
?>
                    <li <?php 
if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
    if( two_string_match($server_path_info, $L2['href'] ) ){
        echo 'class="'. $css_active. '" id="L2_active_item1"';
        $switch= TRUE;
    } else if( $switch==FALSE && two_string_match($referer, $L2['href'] ) ) {
        echo 'class="'. $css_active. '" id="L2_active_item2"';
        $switch= TRUE;
    }
} else {
    if( $L1['name']!='mooncake' && //该判断用于排除某些域名判断
        two_string_match($server_path_info, $L2['href'] ) ){
        echo 'class="'. $css_active. '" id="L2_active_item1"';
        $switch= TRUE;
    } else if(  $L1['name']!='mooncake' && //该判断用于排除某些域名判断
        $switch==FALSE && two_string_match($referer, $L2['href'] ) ) {
        echo 'class="'. $css_active. '" id="L2_active_item2"'; 
        $switch= TRUE;
    }
}
                        ?> >
                        <a href="<?php echo $L1['name']=='mooncake'? $mookcake_prefix: $index_prefix; echo $L2['href']; //echo $index_prefix. $L2['href'] ?>">
                            <!--<i class="fa <?php echo isset($L2['icon'])? $L2['icon']: $default_icon; ?>"></i>--> 
                            <span><?php echo $L2['label'] ?></span>
                            <?php if(false) echo '<small class="label pull-right bg-green">new</small>'; ?><?php


    /* //有3级菜单判断开始   */
        if(isset($L2['child'])&& count($L2['child'])>0 ):
?>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu"><?php 
            foreach ($L2['child'] as $k3=> $L3 ):
                $referer= isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']: 'dashboard';
?>
                    <li <?php 
if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
    //月饼说照常显示active
    if( two_string_match($server_path_info, $L3['href'] ) ){
        echo 'class="'. $css_active. '" id="L3_active_item1"';
        $switch= TRUE;
    } else if( $switch==FALSE && two_string_match($referer, $L3['href'] ) ) {
        echo 'class="'. $css_active. '" id="L3_active_item2"';
        $switch= TRUE;
    }
} else {
    //套票关闭显示月饼菜单active
    if(  $L1['name']!='mooncake' && //该判断用于排除某些域名判断
        two_string_match($server_path_info, $L3['href'] ) ){
        echo 'class="'. $css_active. '" id="L3_active_item1"';
        $switch= TRUE;
    } else if(  $L1['name']!='mooncake' && //该判断用于排除某些域名判断
        $switch==FALSE && two_string_match($referer, $L3['href'] ) ) {
        echo 'class="'. $css_active. '" id="L3_active_item2"'; 
        $switch= TRUE;
    }
}
                        ?> >
                        <a href="<?php echo $L1['name']=='mooncake'? $mookcake_prefix: $index_prefix; echo $L3['href']; //echo $index_prefix. $L3['href'] ?>">
                            <!--<i class="fa <?php echo isset($L3['icon'])? $L3['icon']: $default_icon; ?>"></i>--> 
                            <span><?php echo $L3['label'] ?></span>
                            <?php if(false) echo '<small class="label pull-right bg-green">new</small>'; ?>
                        </a>
                    </li><?php
            endforeach;
?>
                </ul><?php
    /* //有3级菜单判断结束   */



    /* //无3级菜单判断开始   */
            //没有下级菜单的情况
            else: echo '</a>'; endif;

    /* //无3级菜单判断结束   */
    ?>
                    </li><?php
            endforeach;
?>
                </ul><?php

/* //有2级菜单判断结束   */



/* //无2级菜单判断开始   */
        //没有下级菜单的情况
        else: echo '</a>'; endif;

/* //无2级菜单判断结束   */
?>
            </li><?php 
    endforeach; 
endif; ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>