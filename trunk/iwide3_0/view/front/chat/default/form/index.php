<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/chat/public/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/public/chat/public/lib/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/public/chat/public/css/main.css">
	<style type="text/css">
	html,body{width:100%; height:100%; font-size:14px}
	</style>
    <script src="/public/chat/public/lib/jquery-1.11.1.min.js" type="text/javascript"></script>

        <script src="/public/chat/public/lib/jQuery-Knob/js/jquery.knob.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {$(".knob").knob();});
    </script>
    <link rel="stylesheet" type="text/css" href="/public/chat/public/stylesheets/theme.css">
    <link rel="stylesheet" type="text/css" href="/public/chat/public/stylesheets/premium.css">
</head>
<body class=" theme-blue">
    <script type="text/javascript">
        $(function() {
            var match = document.cookie.match(new RegExp('color=([^;]+)'));
            if(match) var color = match[1];
            if(color) {
                $('body').removeClass(function (index, css) {
                    return (css.match (/\btheme-\S+/g) || []).join(' ')
                })
                $('body').addClass('theme-' + color);
            }

            $('[data-popover="true"]').popover({html: true});
            
        });
    </script>
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .navbar-default .navbar-brand, .navbar-default .navbar-brand:hover { 
            color: #fff;
        }
    </style>

    <script type="text/javascript">
        $(function() {
            var uls = $('.sidebar-nav > ul > *').clone();
            uls.addClass('visible-xs');
            $('#main-menu').append(uls.clone());
        });
    </script>
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <a class="" style="cursor:pointer"><span class="navbar-brand"><span class="fa fa-paper-plane"></span> iwide</span></a></div>
      </div>
    </div>
    

    <div class="sidebar-nav">
    <ul>
        <li><a href="#" data-target=".legal-menu" class="nav-header collapsed" data-toggle="collapse"><i class="fa fa-fw fa-legal"></i> 数据图表<i class="fa fa-collapse"></i></a></li>
        <li>
		  <ul class="legal-menu nav nav-list collapse">
			<li><a href="/index.php/chat/superform/suform" target="mainshow"><span class="fa fa-caret-right"></span> 万能表单</a></li>
			<li><a href="/index.php/chat/superform/formcount" target="mainshow"><span class="fa fa-caret-right"></span> 表单统计</a></li>
			<li><a href="/index.php/chat/formmember/logout"><span class="fa fa-caret-right"></span> 退出登录</a></li>
          </ul>
		</li>
    </ul>
    </div>

    <div class="content"><iframe src="/index.php/chat/formadmin/main" width="100%" height="100%" id="mainshow" name="mainshow" frameborder="0"></iframe></div>
    <script src="/public/chat/public/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">
        $("[rel=tooltip]").tooltip();
        $(function() {
            $('.demo-cancel-click').click(function(){return false;});
        });
		$('.content').css('height',$("html").height()-65);
		$(window).resize(function(){
			$('.content').css('height',$("html").height()-65);
		});
		$('.collapsed').click();
		$('.nav-list li').click(function(){
			$('.nav-list li').css('border-left','');
			$(this).css('border-left','4px solid #8989a6');
			$('.nav-list li a').css('background','');
			$(this).find('a').css({'background':'#d2d2dd'});
		});
    </script>
</body></html>