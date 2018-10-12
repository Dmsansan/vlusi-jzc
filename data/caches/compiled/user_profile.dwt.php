<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<header class="dis-box header-menu" style="background-color:#4f743b"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">个人资料</h3>
</header>


<form name="formEdit" action="<?php echo url('user/profile');?>" method="post" onSubmit="return userEdit()">
	<section class="flow-consignee ect-bg-colorf s-user-top onclik-admin">
		<ul>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['lang']['username']; ?>：</h3>
				<div class="box-flex t-goods1  onelist-hidden"> <input name="email" type="text" readonly="readonly" placeholder="<?php echo $this->_var['info']['username']; ?>" value="<?php echo $this->_var['info']['username']; ?>"></div>
			</li>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['lang']['email']; ?>：</h3>
				<div class="box-flex t-goods1 onelist-hidden "> <input name="email" type="text" placeholder="<?php echo $this->_var['lang']['no_emaill']; ?>" value="<?php echo $this->_var['profile']['email']; ?>"></div>
			</li>
			<?php $_from = $this->_var['extend_info_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'field');if (count($_from)):
    foreach ($_from AS $this->_var['field']):
?>
			<?php if ($this->_var['field']['id'] == 6): ?>
			<li>
				<div class="form-select"> <i class="fa fa-sort"></i>
					<select name="sel_question">
            <option value='0'><?php echo $this->_var['lang']['sel_question']; ?></option>            
            <?php echo $this->html_options(array('options'=>$this->_var['passwd_questions'],'selected'=>$this->_var['profile']['passwd_question'])); ?>          
          </select>
				</div>
			</li>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['lang']['passwd_answer']; ?>:</h3>
				<div class="box-flex t-goods1  onelist-hidden"> <input placeholder="<?php echo $this->_var['lang']['passwd_answer']; ?>" name="passwd_answer" type="text" value="<?php echo $this->_var['profile']['passwd_answer']; ?>" /></div>
			</li>
			<?php else: ?>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['field']['reg_field_name']; ?>:</h3>
				<div class="box-flex t-goods1  onelist-hidden"> <input name="extend_field<?php echo $this->_var['field']['id']; ?>" type="text" value="<?php echo $this->_var['field']['content']; ?>" placeholder="<?php echo $this->_var['field']['reg_field_name']; ?>"></div>
			</li>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</section>
	<input name="act" type="hidden" value="profile" />
	<div class=" ect-padding-tb padding-all">
		<button name="submit" type="submit" class="btn-submit" style="background-color:#4f743b"  value="<?php echo $this->_var['lang']['confirm_edit']; ?>"><?php echo $this->_var['lang']['confirm_edit']; ?></button>
	</div>
</form>

<section class="b-color-f my-nav-box m-top10">

	<a href="<?php echo url('user/address_list');?>">
		<div class="s-user-top">
			<div class="dis-box s-xian-box s-user-top-1">
				<h3 class="box-flex text-all-span my-u-title-size">收货地址</h3>
				<span class="t-jiantou"><i class="iconfont icon-jiantou tf-180 jian-top"></i></span>
			</div>
		</div>
	</a>
	<?php if ($this->_var['is_not_wechat']): ?>
	<a href="<?php echo url('user/logout');?>">
		<div class="s-user-top">
			<div class="dis-box s-user-top-1">
				<h3 class="box-flex text-all-span my-u-title-size">退出</h3>
				<span class="t-jiantou"><i class="iconfont icon-jiantou tf-180 jian-top"></i></span>
			</div>
		</div>
	</a>
	<?php endif; ?>
</section>
</div>

<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>

</html>