<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<div class="con m-b7 new-maxbox">
	<!--<header class="header-max-box">
		<?php if (! $this->_var['subscribe']): ?>
		<div class="ect-header-banner dis-box">
			<div class="box-flex">
				<div class="ect-header-text fl">
					<h4>欢迎进入<span class="color-red">微商城</span></h4>
					<p>关注公众号,享专属服务</p>
				</div>
			</div>
			<a class="btn-submit1 j-ewm-box" href="javascript:;">立即关注</a>
		</div>
		<div class="index-weixin-box">
			<div><img src="__TPL__/statics/img/ewm.png"></div>
			<p class="text-c">长按二维码关注公众微信</p>
		</div>
		<div class="index-bg-box"></div>
		<?php endif; ?>
	</header>-->
	<div class="index-banner swiper-container box position-rel banner-first">
				<div class="index-nav-box">
			<ul class="dis-box">
				<li class="index-left-box"><a href="<?php echo url('category/top_all');?>"><i class="iconfont icon-caidan color-whie"></i></a></li>
				<li class="box-flex n-input-index-box">
					<div class="index-search-box j-search-input" id="j-input-focus"><i class="iconfont icon-sousuo"></i>请输入您搜索的关键词!</div>
				</li>
				<li class="index-right-box"><a href="<?php echo url('user/msg_list');?>"><i class="iconfont icon-xiaoxi1 color-whie n-xiaoxi-size"></i></a></li>
			</ul>
		</div>
		<div class="swiper-wrapper">
			<?php 
$k = array (
  'name' => 'ads',
  'id' => '255',
  'num' => '3',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
		</div>
		
		<div class="swiper-pagination banner-first-pagination"></div>
		
		<div class="linear"></div>
	</div>
	
	<nav class="bg-white ptb-1 index-nav">
		<ul class="box ul-4 text-c bg-white">
			<?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
			<li class="fl">
				<a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew'] == 1): ?>target="_blank" <?php endif; ?>><img src="<?php echo $this->_var['nav']['pic']; ?>" /></a>
				<a class="index-menu-text" href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew'] == 1): ?>target="_blank" <?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

		</ul>
	</nav>
	
	<div class="box  title inx-ms m-top06">
		<div class="dis-box m-top1px b-color-f">
			<div class="box-flex title text-c  pt-1 position-rel index-sale-list">
				<?php 
$k = array (
  'name' => 'ads',
  'id' => '257',
  'num' => '1',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
			</div>
			<div class="box-flex ">
				<ul class="index-discount">
					<li class="">
						<?php 
$k = array (
  'name' => 'ads',
  'id' => '258',
  'num' => '2',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<ul class="index-cate-box m-top06">
		<?php 
$k = array (
  'name' => 'ads',
  'id' => '256',
  'num' => '4',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
	</ul>

	
	<div class="text-c n-cate-box">
		<p class="index-title"><i class="iconfont icon-cainixihuan is-cainixihuan"></i>猜你喜欢</p>
		<p class="index-small-title">实时推荐最适合您的宝贝</p>
	</div>
	<section class="product-list j-product-list n-index-box " data="1">

		<ul class="index-more-list" id="J_ItemList">
			<div class="single_item"></div>
			<a href="javascript:;" style="text-align:center" class="get_more"></a>
		</ul>
	</section>
	<div class="n-footer-box">
		<ul class="n-footer-minbox">
			<li>
				<a href="http://m.ecmoban.com/index.php?m=touch&amp;c=index&amp;a=download">
					<div class="n-footer-img-box"><i class="iconfont icon-qunfengshangjiazhushouapperweimafuben is-ban-fize"></i></div>
					<p class="footer-tit1">客户端</p>
				</a>
			</li>
			<li>
				<a href="index.html">
					<div class="n-footer-img-box"><i class="iconfont icon-shoujiyanzheng is-ban-fize active"></i></div>
					<p class="footer-tit">触屏版</p>
				</a>
			</li>
			<li>
				<a href="http://www.ecmoban.com/?pc">
					<div class="n-footer-img-box"><i class="iconfont icon-pc is-ban-fize jian-top-2"></i></div>
					<p class="footer-tit1">电脑版</p>
				</a>
			</li>
		</ul>
	</div>
</div>

<div class="filter-top" id="scrollUp">
	<i class="iconfont icon-dingbu"></i>
</div>

<footer class="footer-nav dis-box">
				<a href="<?php echo url('index/index');?>" class="box-flex nav-list active">
					<i class="nav-box i-home"></i><span>首页</span>
				</a>
				<a href="<?php echo url('category/top_all');?>" class="box-flex nav-list">
					<i class="nav-box i-cate"></i><span>分类</span>
				</a>
				
			
				
				<a href="<?php echo url('flow/cart');?>" class="box-flex position-rel nav-list">
					<i class="nav-box i-flow"></i><span>购物车</span>
				</a>
				<a href="<?php echo url('user/index');?>" class="box-flex nav-list">
					<i class="nav-box i-user"></i><span>我的</span>
				</a>
		</footer>			
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/new_page_footer.lbi'); ?>
<script type="text/javascript" src="__PUBLIC__/js/jquery.more.js"></script>
<script type="text/javascript">
	get_asynclist("<?php echo url('index/ajax_goods', array('type'=>'best'));?>", '__TPL__/images/loader.gif');
</script>
<script type="text/javascript ">
	$(function($) {		
		var mySwiper = new Swiper('.banner-first', {
			pagination: '.banner-first-pagination',
			loop: false,
			grabCursor: true,
			paginationClickable: true,
			autoplayDisableOnInteraction: false,
			autoplay: 3000
		});
	});
	/*立即关注*/
	if ($(".index-weixin-box").hasClass("index-weixin-box")) {
		$(".index-banner").css({
			"marginTop": "0rem",
			
		})
	} else {
		$(".index-banner").css({
			"marginTop": "0rem",
			
		})
	}
</script>
</body>

</html>