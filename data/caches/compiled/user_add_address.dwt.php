<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">新增收货地址</h3>
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
<form action="<?php echo url('user/add_address',array('token'=>$this->_var['token']));?>" method="post" name="theForm" onsubmit="return checkConsignee(this)">
	
		<section class="flow-consignee ect-bg-colorf s-user-top onclik-admin">
		<ul>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['lang']['consignee_name']; ?>：</h3>
				<div class="box-flex t-goods1 n-pro-name  onelist-hidden"> <input name="consignee" maxlength="10" placeholder="<?php echo $this->_var['lang']['consignee_name']; ?><?php echo $this->_var['lang']['require_field']; ?>" type="text" class="inputBg" value="<?php echo htmlspecialchars($this->_var['consignee']['consignee']); ?>" /></div>
			</li>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size"><?php echo $this->_var['lang']['mobile']; ?>：</h3>
				<div class="box-flex t-goods1 onelist-hidden n-pro-name"> <input maxlength="11" placeholder="<?php echo $this->_var['lang']['mobile']; ?><?php echo $this->_var['lang']['require_field']; ?>" name="mobile" type="text" class="inputBg_touch" value="<?php echo htmlspecialchars($this->_var['consignee']['mobile']); ?>" /></div>
			</li>
			<li style="display:none">
				<div class="input-text"><b class="pull-left"><?php echo $this->_var['lang']['city_district']; ?>：</b><span>
			        <select class="n-edit-box" name="country" id="selCountries_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 1, 'selProvinces_<?php echo $this->_var['sn']; ?>')">
			          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['0']; ?></option>
			          <?php $_from = $this->_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'country');if (count($_from)):
    foreach ($_from AS $this->_var['country']):
?>
			          <option value="<?php echo $this->_var['country']['region_id']; ?>"<?php if ($this->_var['country']['region_id'] == '1'): ?> selected<?php endif; ?>><?php echo $this->_var['country']['region_name']; ?></option>
			          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			        </select>
			        </span>
				</div>
			</li>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size">省/直辖市：</h3>
				<i class="iconfont icon-more n-addr-edit"></i>
				<select class="n-edit-box box-flex" name="province" id="selProvinces_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 2, 'selCities_<?php echo $this->_var['sn']; ?>')">			   
		          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['1']; ?></option>
		          <?php $_from = $this->_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'province');if (count($_from)):
    foreach ($_from AS $this->_var['province']):
?>
		          <option value="<?php echo $this->_var['province']['region_id']; ?>" <?php if ($this->_var['consignee']['province'] == $this->_var['province']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['province']['region_name']; ?></option>
		          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		 
		       </select>
			</li>
			<li class="dis-box s-xian-box s-user-top-1">
				<h3 class="profile-left-name text-all-span my-u-title-size">城市：</h3>
					<i class="iconfont icon-more n-addr-edit"></i>
				<select class="n-edit-box box-flex" name="city" id="selCities_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 3, 'selDistricts_<?php echo $this->_var['sn']; ?>')">
          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['2']; ?></option>
          <?php $_from = $this->_var['city_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'city');if (count($_from)):
    foreach ($_from AS $this->_var['city']):
?>
          <option value="<?php echo $this->_var['city']['region_id']; ?>" <?php if ($this->_var['consignee']['city'] == $this->_var['city']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['city']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </select>
				</div>
			</li>
			<li class="dis-box s-xian-box s-user-top-1" id="selDistricts_<?php echo $this->_var['sn']; ?>_box" <?php if (! $this->_var['district_list'][$this->_var['sn']]): ?>style="" <?php endif; ?>>
					<h3 class="profile-left-name text-all-span my-u-title-size">区/县：</h3>
						<i class="iconfont icon-more n-addr-edit"></i>
			        <select class="n-edit-box box-flex" name="district" id="selDistricts_<?php echo $this->_var['sn']; ?>">
			        	
			          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['3']; ?></option>
			          <?php $_from = $this->_var['district_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');if (count($_from)):
    foreach ($_from AS $this->_var['district']):
?>
			          <option value="<?php echo $this->_var['district']['region_id']; ?>" <?php if ($this->_var['consignee']['district'] == $this->_var['district']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['district']['region_name']; ?></option>
			          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			        </select>
				</div>
			</li>
			<li class="input-text  s-user-top-1"><b class="pull-left"><?php echo $this->_var['lang']['detailed_address']; ?>：</b>
				<textarea name="address" placeholder="<?php echo $this->_var['lang']['detailed_address']; ?><?php echo $this->_var['lang']['require_field']; ?>" type="text"><?php echo htmlspecialchars($this->_var['consignee']['address']); ?></textarea>
			</li>
		</ul>

  <div class="two-btn ect-padding-tb   text-center">
    <input type="submit" name="submit"  class="btn-submit"  value="<?php echo $this->_var['lang']['add_address']; ?>"/>
    <input name="address_id" type="hidden" value="<?php echo $this->_var['consignee']['address_id']; ?>" />
  </div>

  
  
  	</section>
</form>

</div>
<?php echo $this->fetch('library/new_search.lbi'); ?> <?php echo $this->fetch('library/page_footer.lbi'); ?> 
<script type="text/javascript" src="__PUBLIC__/script/region.js"></script> 
<script type="text/javascript" src="__PUBLIC__/js/shopping_flow.js"></script> 
<script type="text/javascript">
	region.isAdmin = false;
	<?php $_from = $this->_var['lang']['flow_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	
	onload = function() {
	      if (!document.all)
	      {
	        document.forms['theForm'].reset();
	      }
	}
	
</script>
</body></html>