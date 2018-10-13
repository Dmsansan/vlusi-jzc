<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<div class="con m-b7 new-maxbox">

	<div class="index-banner swiper-container box position-rel banner-first">
				<div class="index-nav-box">
			<ul class="dis-box">
				
				<li class="box-flex n-input-index-box">
					<div class="index-search-box j-search-input" id="j-input-focus"><i class="iconfont icon-sousuo"></i>请输入您搜索的关键词!</div>
				</li>
			
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

	

	<section class="product-list j-product-list n-index-box " data="1">

		<ul class="index-more-list" id="J_ItemList">
			<div class="single_item"></div>
			<a href="javascript:;" style="text-align:center" class="get_more"></a>
		</ul>
	</section>

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