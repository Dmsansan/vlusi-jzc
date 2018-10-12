<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<script type="text/javascript">
	{
		foreach from = $lang.profile_js item = item key = key
	}
	var {
		$key
	} = "<?php echo $this->_var['item']; ?>"; {
		/foreach}
</script>
<body class="b-color-f">
<header class="dis-box header-menu b-color color-whie" style="background-color:#4f743b"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">修改密码</h3>
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
<form name="formPassword" action="<?php echo url('user/edit_password');?>" method="post" onSubmit="return editPassword()">
	<section class="user-center user-forget-tel margin-lr">
		<div class="text-all dis-box j-text-all" name="userpassworddiv">
			<div class="input-text input-check  box-flex">
				<input class="j-input-text" name="old_password" type="password" placeholder="<?php echo $this->_var['lang']['old_password']; ?>">
				<i class="iconfont icon-guanbi2 is-null j-is-null"></i>
			</div>
		</div>
		<div class="text-all dis-box j-text-all" name="userpassworddiv">
			<div class="input-text input-check  box-flex">
				<input class="j-input-text" id="new_password" name="new_password" type="password" placeholder="<?php echo $this->_var['lang']['new_password']; ?>">
				<i class="iconfont icon-guanbi2 is-null j-is-null"></i>
			</div>
		</div>
		<div class="text-all dis-box j-text-all" name="userpassworddiv">
			<div class="input-text input-check  box-flex">
				<input class="j-input-text" id="comfirm_password" name="comfirm_password" type="password" placeholder="<?php echo $this->_var['lang']['confirm_password']; ?>">
				<i class="iconfont icon-guanbi2 is-null j-is-null"></i>
			</div>
		</div>
		<input name="act" type="hidden" value="edit_password" />
		<button type="submit" class="btn-submit" style="background-color:#4f743b"><?php echo $this->_var['lang']['confirm_edit']; ?></button>
	</section>
</form>
</div>

</div>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>

</html>