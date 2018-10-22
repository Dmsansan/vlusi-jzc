<?php echo $this->fetch('library/user_header.lbi'); ?>
<section class="user-order-detail" style="border-top:none;">
  <div class="user-order">
    <p><?php echo $this->_var['lang']['order_status']; ?>：<span><?php echo $this->_var['order']['order_status']; ?> <?php echo $this->_var['order']['pay_status']; ?> <?php echo $this->_var['order']['shipping_status']; ?></span></p>
    <p><?php echo $this->_var['lang']['order_number']; ?>：<span><?php echo $this->_var['order']['order_sn']; ?></span></p>
    <p><?php echo $this->_var['lang']['order_money']; ?>：<span><?php echo $this->_var['order']['formated_total_fee']; ?></span></p>
    <p><?php echo $this->_var['lang']['order_addtime']; ?>：<span><?php echo $this->_var['order']['formated_add_time']; ?></span></p>
    <?php if ($this->_var['order']['invoice_no']): ?><p><?php echo $this->_var['lang']['consignment']; ?>：<span><?php echo $this->_var['order']['invoice_no']; ?></span></p><?php endif; ?>
    <?php if ($this->_var['order']['to_buyer']): ?>
    <p> <?php echo $this->_var['lang']['detail_to_buyer']; ?>：<?php echo $this->_var['order']['to_buyer']; ?> </p>
    <?php endif; ?>
    <?php if ($this->_var['virtual_card']): ?>
    <p><?php echo $this->_var['lang']['virtual_card_info']; ?>：<br>
      <?php $_from = $this->_var['virtual_card']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vgoods');if (count($_from)):
    foreach ($_from AS $this->_var['vgoods']):
?> 
      <?php $_from = $this->_var['vgoods']['info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'card');if (count($_from)):
    foreach ($_from AS $this->_var['card']):
?>
    <hr style="border:none;border-top:1px #CCCCCC dashed; margin:5px 0" />
    <?php if ($this->_var['card']['card_sn']): ?><?php echo $this->_var['lang']['card_sn']; ?>:<span style="color:red;"><?php echo $this->_var['card']['card_sn']; ?></span><br>
    <?php endif; ?> 
    <?php if ($this->_var['card']['card_password']): ?><?php echo $this->_var['lang']['card_password']; ?>:<span style="color:red;"><?php echo $this->_var['card']['card_password']; ?></span><br>
    <?php endif; ?> 
    <?php if ($this->_var['card']['end_date']): ?><?php echo $this->_var['lang']['end_date']; ?>:<?php echo $this->_var['card']['end_date']; ?><br>
    <?php endif; ?> 
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </p>
    <?php endif; ?>
    <p style="display:none;"><a href="<?php echo url('user/msg_list', array('order_id'=>$this->_var['order']['order_id']));?>">[<?php echo $this->_var['lang']['business_message']; ?>]</a></p>
  </div>
  <?php if ($this->_var['order']['order_amount'] > 0): ?>
	<section class="ect-padding-tb ect-margin-tb ect-margin-bottom0"><?php echo $this->_var['order']['pay_online']; ?></section>
  <?php endif; ?>
  <div class="ect-pro-list">
    <ul>
      <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
      <li><a href="<?php echo url('goods/index', array('id'=>$this->_var['goods']['goods_id']));?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></a>
        <dl>
          <dt>
            <h4 class="title"><a href="<?php echo url('goods/index', array('id'=>$this->_var['goods']['goods_id']));?>"><?php echo $this->_var['goods']['goods_name']; ?><?php echo $this->_var['goods']['goods_attr']; ?></a></h4>
          </dt>
           <dd class="dd-price"><b><?php echo $this->_var['goods']['goods_attr']; ?></b></dd>
          <dd class="dd-price"><b><?php echo $this->_var['lang']['ws_subtotal']; ?>：<span class="ect-colory"><?php echo $this->_var['goods']['subtotal']; ?></span> <?php echo $this->_var['lang']['number_to']; ?>：<?php echo $this->_var['goods']['goods_number']; ?></b></dd>
          <dd class="dd-price raise-order-box">
            <?php if ($this->_var['goods']['aftermarket'] == 1): ?>
          <b class="service">
              <a href="<?php echo url('user/aftermarket_done',array('rec_id'=>$this->_var['goods']['rec_id'],'order_id'=>$this->_var['order']['order_id']));?>" >查看申请记录</a>
              <a href="<?php echo url('user/aftermarket_detail',array('ret_id'=>$this->_var['goods']['ret_id']));?>" >退换货详情</a>
          </b>
          <?php else: ?>
              <?php if ($this->_var['goods']['service_apply']): ?>
              <b class="service"><a href="<?php echo url('user/aftermarket',array('rec_id'=>$this->_var['goods']['rec_id'],'order_id'=>$this->_var['order']['order_id']));?>" >申请售后</a></b>
              <?php endif; ?>
          <?php endif; ?>
          
          </dd>

         
        </dl>
      </li>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
  </div>
</section>

<div class="two-btn ect-padding-tb ect-padding-lr ect-margin-tb text-center"> 
  <?php echo $this->_var['order']['handler']; ?>
    <?php if ($this->_var['order']['order_tracking']): ?>
    <a href="<?php echo url('order_tracking', array('order_id'=>$this->_var['order']['order_id']));?>" type="button" class="btn btn-info ect-btn-info ect-colorf ect-bg"><?php echo $this->_var['lang']['order_tracking']; ?></a>
    <?php endif; ?>
</div>
<section class="order-detail-info ect-margin-tb" style="margin-bottom:0;">
  <ul>
    <li><?php echo $this->_var['lang']['consignee_name']; ?>：<b><?php echo $this->_var['order']['consignee']; ?></b></li>
    <li><?php echo $this->_var['lang']['mobile']; ?>：<b><?php echo $this->_var['order']['mobile']; ?></b></li>
    <?php if ($this->_var['order']['exist_real_goods']): ?>
    <li><?php echo $this->_var['lang']['detailed_address']; ?>：<b><?php echo $this->_var['order']['address']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['exist_real_goods'] && 0): ?>
    <li><?php echo $this->_var['lang']['deliver_goods_time']; ?>：<b><?php echo $this->_var['order']['best_time']; ?></b></li>
    <?php endif; ?>
    <li class="text-right">
      <p class="ect-margin-tb"><?php echo $this->_var['lang']['goods_all_price']; ?>：<b class="ect-colory"> 
        <?php if ($this->_var['order']['extension_code'] == "group_buy"): ?><?php echo $this->_var['lang']['gb_deposit']; ?><?php endif; ?><?php echo $this->_var['order']['formated_goods_amount']; ?></b>
         - <?php echo $this->_var['lang']['discount']; ?>：<b class="ect-colory"><?php echo $this->_var['order']['formated_discount']; ?> </b><br>
      	 <?php if ($this->_var['order']['tax'] > 0): ?> 
      + <?php echo $this->_var['lang']['tax']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_tax']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['shipping_fee'] > 0): ?> 
      + <?php echo $this->_var['lang']['shipping_fee']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_shipping_fee']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['insure_fee'] > 0): ?> 
      + <?php echo $this->_var['lang']['insure_fee']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_insure_fee']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['pay_fee'] > 0): ?> 
      + <?php echo $this->_var['lang']['pay_fee']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_pay_fee']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['pack_fee'] > 0): ?> 
      + <?php echo $this->_var['lang']['pack_fee']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_pack_fee']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['card_fee'] > 0): ?> 
      + <?php echo $this->_var['lang']['card_fee']; ?>:<b class="ect-colory"><?php echo $this->_var['order']['formated_card_fee']; ?></b>
      <?php endif; ?> 
      </p>
      <p class="ect-margin-tb">
      	 <?php if ($this->_var['order']['money_paid'] > 0): ?> 
      - <?php echo $this->_var['lang']['order_money_paid']; ?>: <b class="ect-colory"><?php echo $this->_var['order']['formated_money_paid']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['surplus'] > 0): ?> 
      - <?php echo $this->_var['lang']['use_surplus']; ?>: <b class="ect-colory"><?php echo $this->_var['order']['formated_surplus']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['integral_money'] > 0): ?> 
      - <?php echo $this->_var['lang']['use_integral']; ?>: <b class="ect-colory"><?php echo $this->_var['order']['formated_integral_money']; ?></b><br>
      <?php endif; ?> 
      <?php if ($this->_var['order']['bonus'] > 0): ?> 
      - <?php echo $this->_var['lang']['use_bonus']; ?>: <b class="ect-colory"><?php echo $this->_var['order']['formated_bonus']; ?></b><br>
      <?php endif; ?> 
      </p>
      <p class="ect-margin-tb"><?php echo $this->_var['lang']['order_amount']; ?>：<b class="ect-colory"><?php echo $this->_var['order']['formated_order_amount']; ?></b><br><?php if ($this->_var['order']['extension_code'] == "group_buy"): ?> 
      <?php echo $this->_var['lang']['notice_gb_order_amount']; ?><?php endif; ?> </p>
    </li>
   
  </ul>
</section>
 <?php if ($this->_var['allow_edit_surplus']): ?>
    <form action="<?php echo url('user/edit_surplus');?>" method="post" name="formFee" id="formFee">
    <section class="order-detail-info ect-margin-tb" style="border-top:none; margin-top:0;">
        <ul><li> <b class="pull-left"> <?php echo $this->_var['lang']['use_more_surplus']; ?>：</b><span><input name="surplus" class="ect-padding-lr" type="text" size="8" value="0" /></span>
         <p class="ect-margin-tb"><?php echo $this->_var['max_surplus']; ?></p>
         </li></ul>
         </section>
          <section class="ect-padding-lr ect-padding-tb ect-margin-tb ect-margin-bottom0">
         <input type="submit" name="Submit" class="btn btn-info ect-btn-info ect-colorf ect-bg" value="<?php echo $this->_var['lang']['button_submit']; ?><?php echo $this->_var['lang']['use_surplus']; ?>" />
         <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>" />
         </section>
    </form>
    <?php endif; ?>
<section class="order-detail-info ect-margin-tb  ect-margin-bottom0">
  <ul>
    <li><?php echo $this->_var['lang']['select_payment']; ?>：<b><?php echo $this->_var['order']['pay_name']; ?></b></li>
    <li><?php echo $this->_var['lang']['order_amount']; ?>：<b class="ect-colory"><?php echo $this->_var['order']['formated_order_amount']; ?></b></li>
    	<?php if ($this->_var['pay_desc']): ?>
	<li><?php echo $this->_var['pay_desc']; ?></li>
	<?php endif; ?>
    <?php if ($this->_var['payment_list']): ?> 
    <li>
    <form name="payment" method="post" action="<?php echo url('user/edit_payment');?>">
      <?php echo $this->_var['lang']['change_payment']; ?>: <br/>
      <select name="pay_id" style="margin:6px 0;float:left">
        <?php $_from = $this->_var['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');if (count($_from)):
    foreach ($_from AS $this->_var['payment']):
?>
        <option value="<?php echo $this->_var['payment']['pay_id']; ?>"> <?php echo $this->_var['payment']['pay_name']; ?>(<?php echo $this->_var['lang']['pay_fee']; ?>:<?php echo $this->_var['payment']['format_pay_fee']; ?>) </option>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </select>
  
      <input type="hidden" name="order_id" value="<?php echo $this->_var['order']['order_id']; ?>" />
      <input type="submit" name="Submit" style="float:right;width:30%" class="btn btn-info ect-btn-info ect-colorf ect-bg" value="<?php echo $this->_var['lang']['button_submit']; ?>" />
    </form>  
    </li>
    <?php endif; ?>
  </ul>
</section>  
<section class="order-detail-info ect-margin-tb ect-margin-bottom0 user-bottom0">
  <ul style="margin-bottom: 2rem;">
   <?php if ($this->_var['order']['shipping_id'] > 0): ?>
    <li><?php echo $this->_var['lang']['shipping']; ?>：<b><?php echo $this->_var['order']['shipping_name']; ?></b></li>
    <?php endif; ?>
    <li><?php echo $this->_var['lang']['payment']; ?>：<b><?php echo $this->_var['order']['pay_name']; ?></b></li>
    <?php if ($this->_var['order']['insure_fee'] > 0): ?> 
    <?php endif; ?> 
    <?php if ($this->_var['order']['pack_name']): ?>
    <li><?php echo $this->_var['lang']['use_pack']; ?>：<b><?php echo $this->_var['order']['pack_name']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['card_name']): ?>
    <li><?php echo $this->_var['lang']['use_card']; ?>：<b><?php echo $this->_var['order']['card_name']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['card_message']): ?>
    <li><?php echo $this->_var['lang']['bless_note']; ?>：<b><?php echo $this->_var['order']['card_message']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['surplus'] > 0): ?> 
    <?php endif; ?> 
    <?php if ($this->_var['order']['integral'] > 0): ?>
    <li><?php echo $this->_var['lang']['use_integral']; ?>：<b><?php echo $this->_var['order']['integral']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['bonus'] > 0): ?> 
    <?php endif; ?> 
    <?php if ($this->_var['order']['inv_payee'] && $this->_var['order']['inv_content']): ?>
    <li><?php echo $this->_var['lang']['invoice_title']; ?>：<b><?php echo $this->_var['order']['inv_payee']; ?></b></li>
    <li><?php echo $this->_var['lang']['invoice_content']; ?>：<b><?php echo $this->_var['order']['inv_content']; ?></b></li>
    <?php endif; ?> 
    <?php if ($this->_var['order']['postscript']): ?>
    <li><?php echo $this->_var['lang']['order_postscript']; ?>：<b><?php echo $this->_var['order']['postscript']; ?></b></li>
    <?php endif; ?>
    <li><?php echo $this->_var['lang']['booking_process']; ?>：<b><?php echo $this->_var['order']['how_oos_name']; ?></b></li>
  </ul>
</section>
</div>
<?php echo $this->fetch('library/new_search.lbi'); ?> <?php echo $this->fetch('library/page_footer.lbi'); ?>