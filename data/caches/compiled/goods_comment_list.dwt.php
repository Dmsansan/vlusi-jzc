<?php echo $this->fetch('library/new_page_header.lbi'); ?>

<style>
	/*page 分页样式 - 数字*/
	
	.ect-page {
		padding: 0.6em;
		margin: 0;
		overflow: hidden;
	}
	
	.ect-page ul li {
		float: left;
	}
	
	.ect-page select {
		background: #FFF;
	}
	
	.ect-page li a,
	.ect-page li a {
		background: #FFF;
		border-radius: 5px !important;
		padding: 0.4em 0.4em;
		font-size: 1.1em;
		border: 1px solid #e3e3e3;
		display: block;
	}
	
	.ect-page .form-select select {
		padding: 0.9em 5em;
	}
	
	.ect-page .form-select i.fa {
		margin-top: -0.6em;
	}
	
	.form-select i.fa {
		display: block;
		display: inline-block;
		position: absolute;
		top: 50%;
		margin-top: -0.65em;
		right: 0.6em;
		z-index: 1;
		color: #aaa;
	}
	
	select {
		-webkit-appearance: none;
		border: 0;
		color: #555;
		padding-left: 0;
		border: 1px solid #e3e3e3;
		border-radius: 5px;
		padding: 0.2em 0.4em;
		padding-right: 1.2em;
		font-size: 1.1em;
	}
	
	.form-select {
		position: relative;
		overflow: hidden;
		height: auto;
		text-align: center;
		margin-top: 0.1em;
		height: 2.5em;
	}
	
	.form-select select {
		position: relative;
	}
	
	.ect-page .form-select select {
		padding: 0.4em 3em;
		position: relative;
	}
	
	.pager li {
		display: inline;
	}
	
	.pager {
		margin: 20px 0;
		text-align: center;
		list-style: none;
	}
	
	.n-nav-box {}
	
	.goods-evaluation-page .tab-title-1 ul li {
		padding: 1.2rem 0;
		padding-bottom: .8rem;
		font-size: 1.5rem;
		text-align: center;
	}
	
	.goods-evaluation-page .tab-title-1 ul li span {
		color: #777;
	}
	
	.daifukan-ts {
		text-align: center;
		color: #555;
		font-size: 1.7rem;
		margin-top: 2rem;
	}
</style>
<div class="con comment-con">
	<div class="n-nav-box">
		<div class="ect-bg">
			<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
				<h3 class="box-flex"><?php echo $this->_var['title']; ?></h3>
				<p><i class="iconfont icon-pailie j-nav-box"></i></p>
			</header>
			<div class="j-nav-content">
				<ul class="dis-box new-footer-box">
					<li class="box-flex">
						<a href="<?php echo url('index/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/home.png"></i><span>首页</span></a>
					</li>
					<li class="box-flex">
						<a href="<?php echo url('category/top_all');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/cate.png"></i><span>分类</span></a>
						<li class="box-flex"><a href="javascript:;" class="nav-cont j-search-input"><i class="nav-box"><img src="__TPL__/statics/img/search.png"></i><span>搜索</span></a></li>
						<li class="box-flex"><a href="<?php echo url('flow/cart');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/flow.png"></i><span>购物车</span></a></li>
						<li class="box-flex"><a href="<?php echo url('user/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/user.png"></i><span>用户中心</span></a></li>
				</ul>
			</div>
		</div>
		<div class="goods-evaluation-page of-hidden ect-tab j-ect-tab">
			<div class="hd j-tab-title tab-title-1 b-color-f of-hidden">
				<ul class="dis-box">
					<li class="box-flex <?php if ($this->_var['type'] == 1): ?>active<?php endif; ?>"><a href="<?php echo url('goods/comment_list',array('type'=>1,'id'=>$this->_var['id'],'page'=>1));?>"><span><?php echo $this->_var['lang']['all_comment']; ?></span></a><em class="dis-block m-top04"><?php echo $this->_var['comments_info']['count']; ?></em></li>
					<li class="box-flex <?php if ($this->_var['type'] == 2): ?>active<?php endif; ?>"><a href="<?php echo url('goods/comment_list',array('type'=>2,'id'=>$this->_var['id'],'page'=>1));?>"><span><?php echo $this->_var['lang']['favorable_comment']; ?></span></a><em class="dis-block m-top04"><?php echo $this->_var['comments_info']['favorable_count']; ?></em></li>
					<li class="box-flex <?php if ($this->_var['type'] == 3): ?>active<?php endif; ?>"><a href="<?php echo url('goods/comment_list',array('type'=>3,'id'=>$this->_var['id'],'page'=>1));?>"><span><?php echo $this->_var['lang']['medium_comment']; ?></span></a><em class="dis-block m-top04"><?php echo $this->_var['comments_info']['medium_count']; ?></em></li>
					<li class="box-flex <?php if ($this->_var['type'] == 4): ?>active<?php endif; ?>"><a href="<?php echo url('goods/comment_list',array('type'=>4,'id'=>$this->_var['id'],'page'=>1));?>"><span><?php echo $this->_var['lang']['bad_comment']; ?></span></a><em class="dis-block m-top04"><?php echo $this->_var['comments_info']['bad_count']; ?></em></li>
				</ul>
			</div>
			<div id="j-tab-con" class="b-color-f  m-top06">
				<div id="content"></div>
				<div class="swiper-wrapper">

					<section class="swiper-slide of-hidden">
						<?php if ($this->_var['comment_list']): ?>
						<?php $_from = $this->_var['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['com'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['com']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['com']['iteration']++;
?>
						<div class="evaluation-list padding-all">
							<header class="of-hidden ">
								<p class="fl">
									<span class="grade-star g-star-<?php echo $this->_var['list']['comment_rank']; ?> fl"></span>
									<em class="t-remark fl"><?php if ($this->_var['list']['user_name']): ?><?php echo $this->_var['list']['user_name']; ?><?php else: ?><?php echo $this->_var['lang']['anonymous']; ?><?php endif; ?></em>
								</p>
								<p class="fr t-remark"><?php echo $this->_var['list']['add_time']; ?></p>
							</header>
							<p class="clear m-top10 t-goods1"><?php echo $this->_var['list']['content']; ?></p>
							<?php if ($this->_var['comment']['re_content']): ?>
							<p>
								<font class="f1"><?php echo $this->_var['lang']['admin_username']; ?></font><?php echo $this->_var['comment']['re_content']; ?></p>
							<?php endif; ?>
							
							
							<?php if ($this->_var['list']['reply']): ?>
							<?php $_from = $this->_var['list']['reply']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'reply');if (count($_from)):
    foreach ($_from AS $this->_var['reply']):
?>
							<p class="clear m-top10 t-goods1">
								<font class="f1"><?php echo $this->_var['reply']['user_name']; ?> 回复：</font><?php echo $this->_var['reply']['content']; ?>
							</p>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							<?php endif; ?>
							<p style="display:none;" class="clear m-top08 t-remark">颜色分类：70cm、5144蓝色</p>
						</div>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						<?php endif; ?>
					</section>
				</div>
			</div>

		</div>

	</div>
	<?php echo $this->fetch('library/page.lbi'); ?>
</div>

<script src="__PUBLIC__/bootstrap/js/bootstrap.min.js"></script>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/new_page_footer.lbi'); ?>
<script>
	/*点击下拉菜单*/
	function openMune() {
		if ($(".ect-nav").is(":visible")) {
			$(".ect-nav").hide();
		} else {
			$(".ect-nav").show();
		}
	}
	/*倒计时*/
	var goods_id = 3;
	var goodsattr_style = 1;
	var gmt_end_time = 0;
	var day = "天";
	var hour = "小时";
	var minute = "分钟";
	var second = "秒";
	var end = "结束";
	var goodsId = 3;
	var now_time = 1453767623;
	var use_how_oos = 1;
	onload = function() {
		changePrice(2);
		fixpng();
		try {
			onload_leftTime();
		} catch (e) {}
	}

	function back_goods_number() {
		var goods_number = document.getElementById('goods_number').value;
		document.getElementById('back_number').value = goods_number;
	}
</script>

</div>
</body>

</html>