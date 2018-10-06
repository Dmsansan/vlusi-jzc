<!-- $Id: exchange_goods_info.htm 15544 2009-01-09 01:54:28Z zblikai $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,selectzone.js,validator.js,jquery-3.3.1.min.js')); ?>
<!-- start cards form -->
<div class="tab-div">
<form  action="amount_card.php" method="post" name="theForm" onsubmit="return validate();">
  <table width="90%" id="general-table">
    <input type='text' name='amount_id' value= "<?php echo $this->_var['cards']['amount_id']; ?>" style="display: none"/>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountlist');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_list']; ?></td>
      <td><input type="text" name="amount_list" size="30" value="<?php echo $this->_var['cards']['amount_list']; ?>" <?php if ($this->_var['cards']['amount_list']): ?> readonly="readonly"<?php endif; ?> /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountlist"><?php echo $this->_var['lang']['notice_amount_list']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountnumber');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_number']; ?></td>
      <td><input type="text" name="amount_number" id="amount_number" size="30" value="<?php echo $this->_var['cards']['amount_number']; ?>" <?php if ($this->_var['cards']['amount_number']): ?> readonly="readonly"<?php endif; ?>/><?php if (! $this->_var['cards']['amount_number']): ?><a href="javascript:autoCreateNumOrPassword('amount_number')"><?php echo $this->_var['lang']['autoCreateNum']; ?></a><?php endif; ?><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountnumber"><?php echo $this->_var['lang']['notice_amount_number']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeAmountpassword');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['amount_password']; ?></td>
      <td><input type="text" name="amount_password" id="amount_password" size="30" value="<?php echo $this->_var['cards']['amount_password']; ?>" <?php if ($this->_var['cards']['amount_password']): ?> readonly="readonly"<?php endif; ?>/><?php if (! $this->_var['cards']['amount_password']): ?><a href="javascript:autoCreateNumOrPassword('amount_password')"><?php echo $this->_var['lang']['autoCreatePass']; ?></a><?php endif; ?><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountpassword"><?php echo $this->_var['lang']['notice_amount_password']; ?></span></td>
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
      <td> <?php $_from = $this->_var['card_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <input type="radio" name="amount_count" value="<?php echo $this->_var['list']['id']; ?>" <?php if ($this->_var['cards']['type_id'] == $this->_var['list']['id']): ?>checked<?php endif; ?>> <?php echo $this->_var['list']['card_count']; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
       <?php if (! $this->_var['card_count']): ?><a href="<?php echo $this->_var['card_type_add_url']; ?>"><?php echo $this->_var['lang']['add_card_type_first']; ?></a><?php endif; ?>
        <span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeAmountcount"><?php echo $this->_var['lang']['notice_amount_count']; ?></span></td>
    </tr>
    <tr>
      <td align="right" class="label"><a href="javascript:showNotice('noticeExprydate');" title="<?php echo $this->_var['lang']['form_notice']; ?>"><img src="images/notice.gif" width="16" height="16" border="0" alt="<?php echo $this->_var['lang']['form_notice']; ?>"></a><?php echo $this->_var['lang']['expry_date']; ?></td>
      <td><input type="date" name="expry_date" size="30" value="<?php echo $this->_var['cards']['expry_date']; ?>" /><span class="notice-span" <?php if ($this->_var['help_open']): ?>style="display:block" <?php else: ?> style="display:none" <?php endif; ?> id="noticeExprydate"><?php echo $this->_var['lang']['notice_expry_date']; ?></span></td>
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

/**
*自动生成代金卡号函数
**/
function autoCreateNumOrPassword(obj){
  var amount_number = "JZC";
  amount_number = amount_number+new Date().valueOf()+RndNum(4);
  var str = '';
  for(var i=0;i<4;i++){
    if(i<3){
      str +=randomWord(false,4,5)+"-";
    }else{
      str +=randomWord(false,4,5);
    }  
  }
  if(obj === "amount_number"){
     document.getElementById(obj).value = amount_number;
  }else{
    document.getElementById(obj).value = str;
  }
 
}

//产生随机数函数
function RndNum(n){
    var rnd="";
    for(var i=0;i<n;i++)
        rnd+=Math.floor(Math.random()*10);
    return rnd;
}

/*
** randomWord 产生任意长度随机字母数字组合
** randomFlag-是否任意长度 min-任意长度最小位[固定位数] max-任意长度最大位
** xuanfeng 2014-08-28
*/
 
function randomWord(randomFlag, min, max){
    var str = "",
        range = min,
        arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
 
    // 随机产生
    if(randomFlag){
        range = Math.round(Math.random() * (max-min)) + min;
    }
    for(var i=0; i<range; i++){
        pos = Math.round(Math.random() * (arr.length-1));
        str += arr[pos];
    }
    return str;
}
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>