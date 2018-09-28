<?php echo $this->fetch('library/new_page_header.lbi'); ?>

<body class="b-color-f">
	<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
		<h3 class="box-flex">注册</h3>
		<p><a href="<?php echo url('index/index');?>"><i class="iconfont icon-home"></i></a></p>
	</header>
	<div class="con padding-all">
		<div class="user-register">
			<?php if ($this->_var['shop_reg_closed'] == 1): ?>
			<p class="text-center" style="font-size: x-large;"><?php echo $this->_var['lang']['shop_register_closed']; ?></p>
			<?php else: ?>
			<div class="tab-content">
				<?php if ($this->_var['enabled_sms_signin'] == 1): ?>
				<div class="tab-pane active">
					<form action="<?php echo url('user/register');?>" method="post" name="formUser" onsubmit="return register2();" class="validforms">
						<input type="hidden" name="flag" id="flag" value="register" />
						<div class="ect-bg-colorf">
							<ul class="register-list-box">
								<li class="dis-box user-register-box">
									<div class="box-flex reg-left-input"><input placeholder="请输入验证码" name="captcha" type="text" id="captcha" maxlength="4" style="text-transform: uppercase;"></div>
									<div class="user-register-list"><img class="pull-right" src="<?php echo url('public/captcha', array('rand'=>$this->_var['rand']));?>" alt="captcha" onClick="this.src='<?php echo url('public/captcha');?>&t='+Math.random()" /></div>
								</li>
								<li class="dis-box user-register-box">
									<div class="box-flex reg-left-input"><input placeholder="请输入手机号" name="mobile" type="text" id="mobile_phone" maxlength="11"></div>
									<a class="user-register-list2" id="zphone" name="sendsms" onClick="sendSms();" type="botton">获取短信验证码</a>
								</li>
								<li class="dis-box user-register-box">
									<div class="box-flex reg-left-input"><input placeholder="请输入短信验证码" name="mobile_code" type="text" id="mobile_code" maxlength="6"></div>
								</li>
								<li class="dis-box user-register-box input-text item-password">
									<input class="txt-password" type="password" name="password" id="password" autocomplete="off" placeholder="请输入密码" />
									<b class="tp-btn btn-off"></b>
								</li>
							</ul>
						</div>
						<p class="ect-checkbox ect-padding-tb ect-margin-tb ect-margin-bottom0 ect-padding-lr">
							<input id="agreement" name="agreement" type="checkbox" value="1" checked="checked">
							<label for="agreement"><?php echo $this->_var['lang']['agreement']; ?><i></i></label>
						</p>
						<div class="ect-padding-lr ect-padding-tb">
							<input name="act" type="hidden" value="act_register" />
							<input name="enabled_sms" type="hidden" value="1" />
							<input type="hidden" name="sms_code" value="<?php echo $this->_var['sms_code']; ?>" id="sms_code" />
							<input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
							<button name="Submit" type="submit" class="btn-submit"><?php echo $this->_var['lang']['register']; ?></button>
						</div>
					</form>
				</div>
				<script type="text/javascript" src="__PUBLIC__/js/sms.js"></script>
				<?php else: ?>
				<div class="tab-pane active">
					<form action="<?php echo url('user/register');?>" method="post" name="formUser" class="validforms">
						<input type="hidden" name="flag" id="flag" value="register" />
						<div class="ect-bg-colorf">
							<ul class="register-list-box">
								<li class="dis-box user-register-box">
									<div class="box-flex reg-left-input"><input placeholder="<?php echo $this->_var['lang']['no_username']; ?>" name="username" type="text" id="username" datatype="s3-15" nullmsg="请输入用户名"></div>
								</li>
								<li class="dis-box user-register-box input-text item-password">
									<input class="reg-left-input" type="password" name="password" placeholder="请输入密码" datatype="*" nullmsg="请输入密码" />
									<b class="tp-btn btn-off"></b>
								</li>
								<li class="dis-box user-register-box">
									<input class="box-flex reg-left-input" type="text" name="email" placeholder="请输入电子邮箱" datatype="e" nullmsg="请输入电子邮箱" />
								</li>
								<?php if ($this->_var['enabled_captcha']): ?>
								<li class="dis-box user-register-box">
									<div class="box-flex reg-left-input"><input placeholder="请输入验证码" name="captcha" type="text" id="captcha" maxlength="4"></div>
									<div class="user-register-list"><img class="pull-right" src="<?php echo url('public/captcha', array('rand'=>$this->_var['rand']));?>" alt="captcha" onClick="this.src='<?php echo url('public/captcha');?>&t='+Math.random()" /></div>
								</li>
								<?php endif; ?>
							</ul>
						</div>
						<p class="ect-checkbox ect-padding-tb ect-margin-tb ect-margin-bottom0">
							<input id="agreement" name="agreement" type="checkbox" value="1" checked="checked">
							<label for="agreement"><?php echo $this->_var['lang']['agreement']; ?><i></i></label>
						</p>
						<div class="ect-padding-tb">
							<input name="act" type="hidden" value="act_register" />
							<input name="enabled_sms" type="hidden" value="0" />
							<input type="hidden" name="back_act" value="<?php echo $this->_var['back_act']; ?>" />
							<button  name="Submit" type="submit" class="btn-submit"><?php echo $this->_var['lang']['register']; ?></button>
							
						</div>
					</form>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
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