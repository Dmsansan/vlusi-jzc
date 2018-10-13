<?php exit;?>a:3:{s:8:"template";a:5:{i:0;s:73:"D:/phpStudy/PHPTutorial/WWW/vlusi_jzc/themes/default/category_top_all.dwt";i:1;s:80:"D:/phpStudy/PHPTutorial/WWW/vlusi_jzc/themes/default/library/new_page_header.lbi";i:2;s:81:"D:/phpStudy/PHPTutorial/WWW/vlusi_jzc/themes/default/library/new_search_small.lbi";i:3;s:75:"D:/phpStudy/PHPTutorial/WWW/vlusi_jzc/themes/default/library/new_search.lbi";i:4;s:80:"D:/phpStudy/PHPTutorial/WWW/vlusi_jzc/themes/default/library/new_page_footer.lbi";}s:7:"expires";i:1539339617;s:8:"maketime";i:1539336017;}<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta charset="utf-8">
<title>所有分类</title>
<link rel="stylesheet" href="/vlusi_jzc/themes/default/statics/css/ectouch.css" />
<script type="text/javascript" >var tpl = '/vlusi_jzc/themes/default';</script>
</head>
<body style="max-width:640px;">
<div id="loading"><img src="/vlusi_jzc/themes/default/statics/img/loading.gif" /></div>
<script type="text/javascript" >var tpl = '/vlusi_jzc/themes/default';</script><div class="con" >
	<div class="category-top">
	<header>
		<section class="search">
			<div class="text-all  text-all-back j-text-all">
				<div class="input-text n-input-text i-search-input">
					<a class="a-search-input j-search-input" href="javascript:void(0)"></a>
					<i class="iconfont icon-sousuo"></i>
					<input type="text" placeholder="请输入您搜索的关键词!" />
					<i class="iconfont icon-guanbi is-null j-is-null"></i>
				</div>
			</div>
		</section>
	</header>
</div>	<aside>
		<div class="menu-left scrollbar-none" id="sidebar">
			<ul>
								<li  class="active"><a href="/vlusi_jzc/index.php?m=default&c=category&a=index&id=1&u=0">代金卡</a></li>
							</ul>
		</div>
	</aside>
		<section class="menu-right padding-all j-content">
			</section>
	</div>
<footer class="footer-nav dis-box">
				<a href="/vlusi_jzc/index.php?m=default&c=index&a=index&u=0" class="box-flex nav-list">
					<i class="nav-box i-home"></i><span>首页</span>
				</a>
				<a href="/vlusi_jzc/index.php?m=default&c=category&a=top_all&u=0" class="box-flex nav-list active">
					<i class="nav-box i-cate"></i><span>分类</span>
				</a>
				
				<a href="/vlusi_jzc/index.php?m=default&c=store&a=check_store&u=0" class="box-flex nav-list ">
					<i class="nav-box i-shop"></i><span>店铺</span>
				</a>
				
				<a href="/vlusi_jzc/index.php?m=default&c=flow&a=cart&u=0" class="box-flex position-rel nav-list">
					<i class="nav-box i-flow"></i><span>购物车</span>
				</a>
				<a href="/vlusi_jzc/index.php?m=default&c=user&a=index&u=0" class="box-flex nav-list">
					<i class="nav-box i-user"></i><span>我的</span>
				</a>
		</footer>	
<div class="search-div ts-3">
	<section class="search">
		<form action="/vlusi_jzc/index.php?m=default&c=category&a=index&u=0" method="post">
		<div class="text-all dis-box j-text-all">
			<a class="a-icon-back j-close-search" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou is-left-font"></i></a>
			<div class="box-flex input-text">
				<input class="j-input-text" type="text" name="name" placeholder="请输入搜索关键词！" id="newinput" autofocus="autofocus"/ >
				<i class="iconfont icon-guanbi2 is-null j-is-null"></i>
			</div>
			<button type="submit" class="btn-submit">搜索</button>
		</div>
		</form>
	</section>
	<section class="search-con">
		<div class="swiper-scroll history-search">
			<div class="swiper-wrapper">
			<div class="swiper-slide">
		<p>
			<label class="fl">热门搜索</label>
		</p>
		<ul class="hot-search a-text-more">
					</ul>
		<p class="hos-search">
			<label class="fl">最近搜索</label>
			<span class="fr" onclick="javascript:clearHistroy();"><i class="iconfont icon-xiao10 is-xiao10 jian-top fr"></i></span>
		</p>
		
			<ul class="hot-search a-text-more a-text-one" id="search_histroy">
							</ul>
			</div>
			</div>
			<div class="swiper-scrollbar"></div>
		</div>
	</section>
	<footer class="close-search j-close-search">
		点击关闭
	</footer>
</div>
<script type="text/javascript">
//设置cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function clearHistroy(){
	setCookie('ECS[keywords]', '', -1);
	document.getElementById("search_histroy").style.visibility = "hidden";
}
</script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/swiper.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/ectouch.js"></script>
<script type="text/javascript" src="/vlusi_jzc/data/assets/js/jquery.json.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/themes/default/statics/js/jquery.lazyload.min.js"></script>
<script type="text/javascript" src="/vlusi_jzc/data/assets/js/common.js"></script>
<script type="text/javascript">
	$(function($){
		$('#sidebar ul li').click(function(){
			$(this).addClass('active').siblings('li').removeClass('active');
			var index = $(this).index();
			$('.j-content').eq(index).show().siblings('.j-content').hide();
			$(window).scrollTop(0);
		})
	})
	
	function redirect_list(id){
		localData.remove('cat_'+ id +'_page');
		localData.remove('cat_'+ id +'_page_min');
		localData.remove('cat_'+ id +'_page_max');
		window.location.href = 'index.php?c=category&id=' + id;
	}
</script>
</body>
</html>