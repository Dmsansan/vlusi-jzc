<?php echo $this->fetch('library/new_page_header.lbi'); ?>

<div class="con">

	<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
		<h3 class="box-flex">用户充值</h3>
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
	<section>
		<form action="<?php echo url('user/act_account_amount');?>" method="post" name="theForm">
			<div class="user-recharge b-color-f m-top10">
				<div class="m-top1px margin-lr">
					<div class="text-all dis-box j-text-all">
						<label class="t-remark"><?php echo $this->_var['lang']['amount_number']; ?> </label>
						<div class="box-flex input-text">
							<input name="card_number" placeholder="<?php echo $this->_var['lang']['amount_number']; ?>" type="text" class="j-input-text" />
						</div>
					</div>
				</div>
				<div class="m-top1px margin-lr">
                					<div class="text-all dis-box j-text-all">
                						<label class="t-remark"><?php echo $this->_var['lang']['amount_password']; ?> </label>
                						<div class="box-flex input-text">
                							<input name="card_password" placeholder="<?php echo $this->_var['lang']['amount_password']; ?>" type="text" class="j-input-text" />
                						</div>
                                    </div>
                </div>
			</div>
			<div class="padding-all">
				<input type="hidden" name="user_name" value="<?php echo $this->_var['user_name']; ?>" />
				<button type="submit" name="submit" class="btn-submit" value="" /><?php echo $this->_var['lang']['submit_request']; ?></button>
			</div>
		</form>
	</section>
</div>

<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript" src="__PUBLIC__/script/region.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/shopping_flow.js"></script>
<script type="text/javascript">
	region.isAdmin = false; {
		foreach from = $lang.flow_js item = item key = key
	}
	var {
		$key
	} = "<?php echo $this->_var['item']; ?>"; {
		/foreach} {
		literal
	}
	onload = function() {
			if (!document.all) {
				document.forms['theForm'].reset();
			}
		} {
			/literal}
</script>
<script type="text/javascript">
	$(function($) {});
</script>
</body>

</html>