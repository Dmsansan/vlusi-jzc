<!-- $Id: exchange_goods_info.htm 15544 2009-01-09 01:54:28Z zblikai $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,selectzone.js,validator.js,jquery-3.3.1.min.js')); ?>
<!-- start cards form -->
<div class="tab-div">
<form  action="order.php" method="post" name="theForm" onsubmit="return validate();">
  <table width="90%" id="general-table">
    <input type='text' name='order_id' value= "<?php echo $this->_var['order']['order_id']; ?>" style="display: none"/>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountlist');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['kuaidi_name']; ?></td>
      <td><input type="text" name="kuaidi_name" size="30" value="<?php echo $this->_var['order']['kuaidi_name']; ?>"  /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountlist"><?php echo $this->_var['lang']['notice_kuaidi_name']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountnumber');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['kuaidi_sn']; ?></td>
      <td><input type="text" name="kuaidi_sn"  size="30" value="<?php echo $this->_var['order']['kuaidi_sn']; ?>" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountnumber"><?php echo $this->_var['lang']['notice_kuaidi_sn']; ?></span></td>
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
  validator.isNullOption("kuaidi_name",kuaidi_name);
  validator.isNullOption("kuaidi_sn",kuaidi_sn);

  return validator.passed();
}


</script>
<?php echo $this->fetch('pagefooter.htm'); ?>