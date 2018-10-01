<!-- $Id: exchange_goods_info.htm 15544 2009-01-09 01:54:28Z zblikai $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,selectzone.js,validator.js')); ?>
<!-- start cards form -->
<div class="tab-div">
<form  action="amount_card.php" method="post" name="theForm" onsubmit="return validate();">
  <table width="90%" id="general-table">
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountlist');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_list']; ?></td>
      <td><input type="text" name="amount_list" size="30" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountlist"><?php echo $this->_var['lang']['notice_amount_list']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountnumber');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_number']; ?></td>
      <td><input type="text" name="amount_number" id="amoun_number" size="30" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountnumber"><?php echo $this->_var['lang']['notice_amount_number']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountpassword');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_password']; ?></td>
      <td><input type="text" name="amount_password" size="30" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountpassword"><?php echo $this->_var['lang']['notice_amount_password']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><?php echo $this->_var['lang']['amount_status']; ?></td>
      <td>
        <input type="radio" name="amount_status" value="0" <?php if ($this->_var['cards']['amount_status'] == 0): ?>checked<?php endif; ?>> <?php echo $this->_var['lang']['down']; ?>
        <input type="radio" name="amount_status" value="1" <?php if ($this->_var['cards']['amount_status'] == 1): ?>checked<?php endif; ?>> <?php echo $this->_var['lang']['up']; ?>
      </td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountcount');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_count']; ?></td>
      <td><input type="text" name="amount_count" size="30" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountcount"><?php echo $this->_var['lang']['notice_amount_count']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeExprydate');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['expry_date']; ?></td>
      <td><input type="date" name="expry_date" size="30" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeExprydate"><?php echo $this->_var['lang']['notice_expry_date']; ?></span></td>
    </tr>
  </table>

  <div class="button-div">
    <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button"  />
    <input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button" />
  </div>
</form>
</div>
<!-- end goods form -->
<script language="JavaScript">


onload = function()
{
  // 开始检查订单
  startCheckOrder();
}

function validate()
{
  var validator = new Validator('theForm');
  validator.isNullOption("amount_list",amount_list);
  validator.isAmountNumber("amount_number",amount_number, true);
  validator.isAmountPassword("amount_password",amount_password,true);
  validator.isNullOption("amount_count",amount_count);
  validator.isNullOption("expry_date",expry_date);

  return validator.passed();
}




</script>
<?php echo $this->fetch('pagefooter.htm'); ?>