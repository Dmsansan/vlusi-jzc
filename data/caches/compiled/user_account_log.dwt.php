<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<header class="dis-box header-menu b-color color-whie"  style="background-color:#4f743b"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">申请记录</h3>
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
<ul class="n-user-acc-log">
		<?php if ($this->_var['account_log']): ?>
	<?php $_from = $this->_var['account_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
	<li class="dis-shop-list p-r padding-all m-top10 b-color-f  ">
		<div class="dis-box">
			<div class="box-flex">
				<h5 class="f-05 col-7"><?php echo $this->_var['item']['add_time']; ?></h5>
				<h6 class="f-05 col-7  m-top04"><?php echo $this->_var['item']['type']; ?></h6>
			</div>
			<div class="box-flex">
				<p class="f-04 color-red text-right"><?php echo $this->_var['item']['pay_status']; ?></p>
				<h6 class="f-05 col-3 text-right m-top04"><?php echo $this->_var['item']['amount']; ?></h6>

			</div>
			
		</div>
		<p class="f-03 m-top02"><span class="col-7 f-05"><?php echo $this->_var['lang']['admin_notic']; ?> : </span><span class="col-3"><?php if ($this->_var['item']['admin_note']): ?><?php echo $this->_var['item']['admin_note']; ?><?php else: ?>N/A<?php endif; ?></span></p>
		<p class="f-03 m-top04"><span class="col-7 f-05"><?php echo $this->_var['lang']['process_notic']; ?> : </span><span class="col-3"><?php echo $this->_var['item']['user_note']; ?></span></p>
		<div class="n-but-box n-acc-log">
			<?php echo $this->_var['item']['handle']; ?>
			<?php if (( $this->_var['item']['is_paid'] == 0 && $this->_var['item']['process_type'] == 1 ) || $this->_var['item']['handle']): ?>
			<a href="<?php echo url('user/cancel',array('id'=>$this->_var['item']['id']));?>" onclick="if (!confirm('<?php echo $this->_var['lang']['confirm_remove_account']; ?>')) return false;"><?php echo $this->_var['lang']['is_cancel']; ?></a>
              <?php endif; ?>			
		</div>
		
				
	</li>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php else: ?>
	<div class="no-div-message flow-no-cart">
			<i class="iconfont icon-biaoqingleiben"></i>
			<p>亲，此处没有内容～！</p>
		</div>
<?php endif; ?>
</ul>
  <?php echo $this->fetch('library/page.lbi'); ?>

<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>