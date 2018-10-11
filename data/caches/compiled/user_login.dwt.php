<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<body class="b-color-f">
<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">登录</h3>
	<p><a href="<?php echo url('index/index');?>"><i class="iconfont icon-home"></i></a></p>
</header>

<div class="padding-all b-color-f">
	<form name="formLogin" action="<?php echo url('user/login');?>" method="post" class="validforms">
		<div class="ect-bg-colorf">
			<section>
				<ul class="register-list-box">
					<li class="dis-box user-register-box">
						<div class="box-flex reg-left-input"><input placeholder="用户名/手机号" name="username" type="text" id="username" maxlength="20"></div>
					</li>
					<li class="dis-box user-register-box input-text item-password">
						<input class="txt-password" type="password" name="password" autocomplete="off" placeholder="请输入密码" />
						<b class="tp-btn btn-off"></b>
					</li>
					<?php if ($this->_var['enabled_captcha']): ?>
					<li class="dis-box user-register-box">
						<div class="box-flex reg-left-input"><input placeholder="<?php echo $this->_var['lang']['comment_captcha']; ?>" name="captcha" type="text" datatype="*3-15"></div>
						<div class="user-register-list"><img src="<?php echo url('Public/captcha', array('rand'=>$this->_var['rand']));?>" alt="captcha" class="img-yzm pull-right" onClick="this.src='<?php echo url('public/captcha');?>&t='+Math.random()" /></div>
					</li>
					<?php endif; ?>
				</ul>
			</section>
		</div>
		<p class="ect-checkbox ect-padding-tb ect-margin-tb ect-margin-bottom0">
			<input type="checkbox" value="1" name="remember" id="remember" class="l-checkbox" />
			<label for="remember"><?php echo $this->_var['lang']['remember']; ?><i></i></label>
			<?php if ($this->_var['anonymous_buy'] == 1 && $this->_var['step'] == 'flow'): ?>
			<a href="<?php echo url('flow/consignee',array('direct_shopping'=>1));?>" style="float:right;"><?php echo $this->_var['lang']['direct_shopping']; ?></a>
			<?php endif; ?>
		</p>
		<input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
		<div class=" ect-padding-tb">
			<button type="submit" class="btn-submit" value="" /><?php echo $this->_var['lang']['now_landing']; ?></button>
		</div>

	</form>
	<p class="text-right ect-margin-bottom0 n-login-x">
		<a href="<?php echo url('user/get_password_phone');?>"><?php echo $this->_var['lang']['forgot_password']; ?></a>
		<a href="<?php echo url('user/register');?>"><?php echo $this->_var['lang']['free_registered']; ?></a>
	</p>
	<div class="other-login">
		<h4 class="title-hrbg"><span><?php echo $this->_var['lang']['third_login']; ?></span><hr> </h4>
		<ul class="dis-box">
			<li class="box-flex"><a href="<?php echo url('user/third_login',array('type'=>'qq'));?>"><span class="qq"><i class="iconfont icon-qq"></i></span></a></li>
			<li class="box-flex"><a href="<?php echo url('user/third_login',array('type'=>'sina'));?>"><span class="weibo"><i class="iconfont icon-weibo"></i></span></a></li>
			<?php if ($this->_var['is_wechat']): ?>
			<li class="box-flex"><a href="<?php echo url('user/third_login',array('type'=>'weixin'));?>"><span class="weixin"><i class="iconfont icon-weibiaoti1"></i></span></a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>

</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<script>
	$('.btn-off').on('click', function() {
		if ($(this).hasClass('btn-on')) {
			$(this).removeClass('btn-on');
			$(this).prev().attr('type', 'password');
		} else {
			$(this).addClass('btn-on');
			$(this).prev().attr('type', 'text');
		}
	});
</script>
</body>

</html>