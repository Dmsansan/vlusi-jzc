<div class="ect-bg-colorf flow-consignee s-user-top">
  <ul>
    <li>
      <div class="input-text"><b class="pull-left"><?php echo $this->_var['lang']['consignee_name']; ?>：</b><span style="top: -0.5rem;">
        <input name="consignee" maxlength="10" type="text" class="inputBg" id="consignee_<?php echo $this->_var['sn']; ?>" placeholder="<?php echo $this->_var['lang']['consignee_name']; ?><?php echo $this->_var['lang']['require_field']; ?>" value="<?php echo htmlspecialchars($this->_var['consignee']['consignee']); ?>">
        </span></div>
    </li>
    <li>
      <div class="input-text"><b class="pull-left"><?php echo $this->_var['lang']['mobile']; ?>：</b><span style="top: -0.5rem;">
        <input name="mobile" maxlength="11" type="text" class="inputBg"  id="mobile_<?php echo $this->_var['sn']; ?>" placeholder="<?php echo $this->_var['lang']['mobile']; ?><?php echo $this->_var['lang']['require_field']; ?>" value="<?php echo htmlspecialchars($this->_var['consignee']['mobile']); ?>">
        </span></div>
    </li>
    <?php if ($this->_var['real_goods_count'] > 0): ?> 
    
    <li style="display:none">
        <div class="input-text"><b class="pull-left"><?php echo $this->_var['lang']['city_district']; ?>：</b><span>
        <select name="country" id="selCountries_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 1, 'selProvinces_<?php echo $this->_var['sn']; ?>')">
          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['0']; ?></option>
          <?php $_from = $this->_var['country_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'country');if (count($_from)):
    foreach ($_from AS $this->_var['country']):
?>
          <option value="<?php echo $this->_var['country']['region_id']; ?>"<?php if ($this->_var['country']['region_id'] == '1'): ?> selected<?php endif; ?>><?php echo $this->_var['country']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </select>
      </div>
    </li>
    <li>
        <div class="input-text"><b class="pull-left">省/直辖市：</b><span style="top: -0.5rem;">
        <select name="province" id="selProvinces_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 2, 'selCities_<?php echo $this->_var['sn']; ?>')">
          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['1']; ?></option>
          <?php $_from = $this->_var['province_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'province');if (count($_from)):
    foreach ($_from AS $this->_var['province']):
?>
          <option value="<?php echo $this->_var['province']['region_id']; ?>" <?php if ($this->_var['consignee']['province'] == $this->_var['province']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['province']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </select>
      </div>
    </li>
    <li>
        <div class="input-text"><b class="pull-left">城市：</b><span style="top: -0.5rem;">
        <select name="city" id="selCities_<?php echo $this->_var['sn']; ?>" onchange="region.changed(this, 3, 'selDistricts_<?php echo $this->_var['sn']; ?>')">
          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['2']; ?></option>
          <?php $_from = $this->_var['city_list'][$this->_var['sn']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'city');if (count($_from)):
    foreach ($_from AS $this->_var['city']):
?>
          <option value="<?php echo $this->_var['city']['region_id']; ?>" <?php if ($this->_var['consignee']['city'] == $this->_var['city']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['city']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </select>
      </div>
    </li>
    <li id="selDistricts_<?php echo $this->_var['sn']; ?>_box" <?php if (! $this->_var['district_list'][$this->_var['sn']]): ?>style="display:none"<?php endif; ?>>
        <div class="input-text"><b class="pull-left">区/县：</b><span style="top: -0.5rem;">
        <select name="district" id="selDistricts_<?php echo $this->_var['sn']; ?>">
          <option value="0"><?php echo $this->_var['lang']['please_select']; ?><?php echo $this->_var['name_of_region']['3']; ?></option>
          <?php $_from = $this->_var['district_list'][$this->_var['sn']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'district');if (count($_from)):
    foreach ($_from AS $this->_var['district']):
?>
          <option value="<?php echo $this->_var['district']['region_id']; ?>" <?php if ($this->_var['consignee']['district'] == $this->_var['district']['region_id']): ?>selected<?php endif; ?>><?php echo $this->_var['district']['region_name']; ?></option>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </select
          >
      </div>
    </li>
    <?php endif; ?> 
    <?php if ($this->_var['real_goods_count'] > 0): ?> 
    
    <li class="input-text"><b class="pull-left"><?php echo $this->_var['lang']['detailed_address']; ?>：</b>
      <textarea name="address" placeholder="<?php echo $this->_var['lang']['detailed_address']; ?><?php echo $this->_var['lang']['require_field']; ?>"  id="address_<?php echo $this->_var['sn']; ?>"><?php echo htmlspecialchars($this->_var['consignee']['address']); ?></textarea>
    </li>
    <?php endif; ?>
  </ul>
</div>
<div class="two-btn ect-padding-tb ect-padding-lr ect-margin-tb text-center"> 
  <?php if ($_SESSION['user_id'] > 0 && $this->_var['consignee']['address_id'] > 0): ?> 
   
  <a  class="btn btn-info11 n-but-bor"  onclick="if (confirm('<?php echo $this->_var['lang']['drop_consignee_confirm']; ?>')) location.href='<?php echo url('flow/drop_consignee',array('id'=>$this->_var['consignee']['address_id']));?>'" ><?php echo $this->_var['lang']['drop']; ?></a> 
  <?php endif; ?>
  <button type="submit" class="btn btn-submit" style="background-color:red" name="Submit"><?php echo $this->_var['lang']['shipping_address']; ?></button>
</div>
<input type="hidden" name="step" value="consignee" />
<input type="hidden" name="act" value="checkout" />
<input name="address_id" type="hidden" value="<?php echo $this->_var['consignee']['address_id']; ?>" />
