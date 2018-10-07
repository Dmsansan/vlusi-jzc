<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<div class="con m-b7">
	<div class="my-admin-header-box">
		<div class="admin-bg-box">
			<div class="my-user-box com-header">
				<div class="padding-all dis-box">
					<div class="user-head-img-box">
						<a href="<?php echo url('user/profile');?>">
							<?php if ($this->_var['info']['avatar']): ?>
							<img src="<?php echo $this->_var['info']['avatar']; ?>" /> <?php else: ?>
							<img src="__TPL__/images/member-photo-img2.jpeg" /> <?php endif; ?>
						</a>
					</div>
					<div class="user-bg-box"><img src="__TPL__/statics/img/user-1.png"></div>
					<div class="user-bg2-box"><img src="__TPL__/statics/img/user-2.png"></div>
					<div class="box-flex">
						<div class="g-s-i-title-a">
							<a href="javascript:;">
								<h4 class="ellipsis-one user-admin-size"><?php echo $this->_var['info']['name']; ?></h4></a>
							<a href="javascript:;">
								<p class="user-reg-top color-whie f-03"><?php echo $this->_var['rank_name']; ?></p>
							</a>
						</div>
						<?php if (! $this->_var['isbind']): ?>
						<div class="g-s-i-title-a">
							<a href="<?php echo url('user/bind');?>">
								<p class="user-reg-top color-whie f-03">电脑端账号绑定 </p>
							</a>
						</div>
						<?php endif; ?>
					</div>
					<div class="user-right-maxbox">
						<a href="<?php echo url('user/profile');?>">
							<div class="user-right-box">
								<i class="iconfont icon-shezhi is-shezi my-user-right-cont" style="display:block;"></i>
								<p class="my-user-right-cont"><span class="my-t-remark color-whie">设置</span></p>
							</div>
						</a>
						<a href="<?php echo url('user/msg_list');?>">
							<div class="email-box" style="display:none;"></div>
							<i class="iconfont icon-youxiang is-youxiang j-yanjing disabled"></i>
						</a>
					</div>
					</ul>
				</div>
				</a>
			</div>
		</div>
		<div class="my-nav-box my-shoucang-bg">
			<div class="user-nav-box n-g-s-i-title-1 dis-box text-center">
				<a href="<?php echo url('user/collection_list');?>" class="box-flex">
					<h4 class="color-whie ellipsis-one"><?php if ($this->_var['list']['goods_nums'] != ''): ?> <?php echo $this->_var['list']['goods_nums']; ?><?php else: ?>0<?php endif; ?></h4>
					<p class="color-whie t-remark3">收藏</p>
				</a>
				<a href="<?php echo url('user/msg_list');?>" class="box-flex">
					<h4 class="color-whie ellipsis-one"><?php if ($this->_var['list']['msg_list'] != ''): ?> <?php echo $this->_var['list']['msg_list']; ?><?php else: ?>0<?php endif; ?></h4>
					<p class="color-whie t-remark3">消息</p>
				</a>
			</div>
		</div>
	</div>
	<section class="b-color-f my-nav-box m-top10">
		<a href="<?php echo url('user/order_list');?>">
			<div class="dis-box padding-all my-bottom">
				<h3 class="box-flex text-all-span my-u-title-size"><i class="iconfont icon-quanbudingdan is-user-size t-first"></i>我的订单</h3>
				<div class="box-flex t-goods1 text-right onelist-hidden jian-top">全部订单</div>
				<span class="t-jiantou"><i class="iconfont icon-jiantou tf-180 jian-top"></i></span>
			</div>
		</a>
		<ul class="user-money-list g-s-i-title-2 dis-box text-center my-dingdan">
			<a href="<?php echo url('user/not_pay_order_list');?>" class="box-flex">
				<li>
					<h4 class="ellipsis-one"><i class="iconfont icon-daifukuan my-img-size"></i></h4>
					<p class="t-remark3">待付款</p>
					<div class="user-list-num">
						<?php if ($this->_var['list']['not_pays'] != ''): ?><?php echo $this->_var['list']['not_pays']; ?>
						<?php else: ?>0
						<?php endif; ?>
					</div>
				</li>
			</a>
			<a href="<?php echo url('user/not_shoushuo');?>" class="box-flex">
				<li>
					<h4 class="ellipsis-one"><i class="iconfont icon-daifahuo my-img-size"></i></h4>
					<p class="t-remark3">待收货</p>
					<div class="user-list-num">
						<?php if ($this->_var['list']['not_shouhuos'] != ''): ?><?php echo $this->_var['list']['not_shouhuos']; ?>
						<?php else: ?>0
						<?php endif; ?>
					</div>
				</li>
			</a>
			<a href="<?php echo url('user/order_comment');?>" class="box-flex">
				<li>
					<h4 class="ellipsis-one"><i class="iconfont icon-daipingjia my-img-size"></i></h4>
					<p class="t-remark3">待评价</p>
					<div class="user-list-num">
						<?php if ($this->_var['list']['not_comment'] != ''): ?><?php echo $this->_var['list']['not_comment']; ?>
						<?php else: ?>0
						<?php endif; ?>
					</div>
				</li>
			</a>
			<a href="<?php echo url('user/aftermarket_list');?>" class="box-flex">
				<li>
					<h4 class="ellipsis-one"><i class="iconfont icon-p-return my-img-size"></i></h4>
					<p class="t-remark3">退/换货</p>
				</li>
			</a>
		</ul>
	</section>
	<section class="m-top10 my-nav-box b-color-f">
		<a href="<?php echo url('user/account_detail');?>;">
			<div class="dis-box padding-all my-bottom">
				<h3 class="box-flex text-all-span my-u-title-size"><i class="iconfont icon-qianbao is-user-size my-qianbao-color"></i>我的钱包</h3>
				<div class="box-flex t-goods1 text-right onelist-hidden jian-top">资金管理</div>
				<span class="t-jiantou"><i class="iconfont icon-jiantou tf-180 jian-top"></i></span>
			</div>
		</a>
		<ul class="user-money-list g-s-i-title-1 dis-box text-center">
			<div  class="box-flex">
				<li>
					<h4 class="ellipsis-one"><?php if ($this->_var['list']['user_money'] != ''): ?> <?php echo $this->_var['list']['user_money']; ?><?php else: ?>0<?php endif; ?></h4>
					<p class="t-remark3">余额</p>
				</li>
			</div>
			<a href="<?php echo url('user/bonus');?>" class="box-flex">
				<li>
					<h4 class="ellipsis-one"><?php if ($this->_var['list']['bonus'] != ''): ?> <?php echo $this->_var['list']['bonus']; ?><?php else: ?>0<?php endif; ?></h4>
					<p class="t-remark3">红包</p>
				</li>
			</a>
			<div  class="box-flex">
				<li>
					<h4 class="ellipsis-one"><?php if ($this->_var['list']['user_points'] != ''): ?> <?php echo $this->_var['list']['user_points']; ?><?php else: ?>0<?php endif; ?></h4>
					<p class="t-remark3">积分</p>
				</li>
			</div>
		</ul>
	</section>
	<footer class="b-color-f user-fu-box m-top10">
		<div class="box text-c b-color-f dis-box">
			<a href="<?php echo url('user/service');?>" class="box-flex">
				<i class="iconfont icon-kefu"></i>
				<p class="f-03 col-6">客服服务</p>
			</a>
			<!-- <a href="<?php echo url('user/share');?>" class="box-flex">
				<i class="iconfont icon-fenxiang"></i>
				<p class="f-03 col-6">我的分享</p>
			</a> -->
			
			<a href="<?php echo url('store/check_store');?>" class="box-flex">
				<i class="iconfont icon-dianpu"></i>
				<p class="f-03 col-6">我的微店</p>
			</a>
			
		</div>
	</footer>

	<?php if ($this->_var['history']): ?>
	<section class="m-top10 my-nav-box">
		<div class="dis-box padding-all b-color-f">
			<h3 class="box-flex text-all-span my-u-title-size"><i class="iconfont icon-shijian is-user-size my-shijian-color"></i><?php echo $this->_var['lang']['user_history']; ?></h3>
			<span class="jian-top n-shanchutupian del"><i class="iconfont icon-xiao10 is-xiao10 jian-top"></i><span class="q-title jian-top">清空</span>
		</div>
		<section class="goods-shop-pic m-top1px of-hidden padding-all b-color-f">
			<div style="cursor: grab;" class="g-s-p-con product-one-list1 of-hidden scrollbar-none j-g-s-p-con swiper-container-horizontal">
				<div class="swiper-wrapper ">
					<?php $_from = $this->_var['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
					<li class="swiper-slide swiper-slide-active">
						<div class="product-div">
							<a class="product-div-link" href="<?php echo $this->_var['val']['url']; ?>"></a>
							<img class="product-list-img" src="<?php echo $this->_var['val']['goods_thumb']; ?>">
							<div class="product-text m-top06">
								<h4><?php echo $this->_var['val']['goods_name']; ?></h4>
								<p style="display:none"><span class="p-price t-first "><?php echo $this->_var['lang']['sort_price']; ?>：&#165;<?php echo $this->_var['val']['shop_price']; ?></span></p>
							</div>
						</div>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
			</div>
		</section>
	</section>
	<?php endif; ?>
</div>
<footer class="footer-nav dis-box">
	<a href="<?php echo url('index/index');?>" class="box-flex nav-list">
		<i class="nav-box i-home"></i><span>首页</span>
	</a>
	<a href="<?php echo url('category/top_all');?>" class="box-flex nav-list">
		<i class="nav-box i-cate"></i><span>分类</span>
	</a>
	
	<a href="<?php echo url('store/check_store');?>" class="box-flex nav-list ">
		<i class="nav-box i-shop"></i><span>店铺</span>
	</a>
	
	<a href="<?php echo url('flow/cart');?>" class="box-flex position-rel nav-list">
		<i class="nav-box i-flow"></i><span>购物车</span>
	</a>
	<a href="<?php echo url('user/index');?>" class="box-flex nav-list active">
		<i class="nav-box i-user"></i><span>我的</span>
	</a>
</footer>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/new_page_footer.lbi'); ?>
<script>
	/*店铺信息商品滚动*/
	var swiper = new Swiper('.j-g-s-p-con', {
		scrollbarHide: true,
		slidesPerView: 'auto',
		centeredSlides: false,
		grabCursor: true
	});
</script>
</body>

</html>