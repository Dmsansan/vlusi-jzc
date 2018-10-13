
<div class="ect-padding-tb text-right" id="ECS_ORDERTOTAL">
  <?php if ($_SESSION['user_id'] > 0 && ( C ( 'use_integral' ) || C ( 'use_bonus' ) )): ?> 
  <?php echo $this->_var['lang']['complete_acquisition']; ?> 
  <?php if (C ( 'use_integral' )): ?>
  <b class="ect-colory"><?php echo $this->_var['total']['will_get_integral']; ?></b>
  <?php echo $this->_var['points_name']; ?> 
  <?php endif; ?> 
  <?php if (( 'use_integral' ) && C ( 'use_bonus' )): ?>，<?php echo $this->_var['lang']['with_price']; ?> <?php endif; ?> 
  <?php if (C ( 'use_bonus' )): ?>
  <b class="ect-colory"><?php echo $this->_var['total']['will_get_bonus']; ?></b>
  <?php echo $this->_var['lang']['de']; ?><?php echo $this->_var['lang']['bonus']; ?>。 
  <?php endif; ?> <br/>
  <?php endif; ?> 
  <?php echo $this->_var['lang']['goods_all_price']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['goods_price_formated']; ?></b>
  <br />
  <?php if ($this->_var['total']['discount'] > 0): ?> 
  - <?php echo $this->_var['lang']['discount']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['discount_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['tax'] > 0): ?> 
  + <?php echo $this->_var['lang']['tax']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['tax_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['shipping_fee'] > 0): ?> 
  + <?php echo $this->_var['lang']['shipping_fee']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['shipping_fee_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['shipping_insure'] > 0): ?> 
  + <?php echo $this->_var['lang']['insure_fee']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['shipping_insure_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['pay_fee'] > 0): ?> 
  + <?php echo $this->_var['lang']['pay_fee']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['pay_fee_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['pack_fee'] > 0): ?> 
  + <?php echo $this->_var['lang']['pack_fee']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['pack_fee_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['card_fee'] > 0): ?> 
  + <?php echo $this->_var['lang']['card_fee']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['card_fee_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['surplus'] > 0 || $this->_var['total']['integral'] > 0 || $this->_var['total']['bonus'] > 0): ?> 
  <?php if ($this->_var['total']['surplus'] > 0): ?> 
  - <?php echo $this->_var['lang']['use_surplus']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['surplus_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['integral'] > 0): ?> 
  - <?php echo $this->_var['lang']['use_integral']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['integral_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php if ($this->_var['total']['bonus'] > 0): ?> 
  - <?php echo $this->_var['lang']['use_bonus']; ?>:
  <b class="ect-colory"><?php echo $this->_var['total']['bonus_formated']; ?></b>
  <br />
  <?php endif; ?> 
  <?php endif; ?> 
  
  <?php echo $this->_var['lang']['total_fee']; ?>: <b class="ect-colory"><?php echo $this->_var['total']['amount_formated']; ?></b><br />
  <?php if ($this->_var['is_group_buy']): ?>
  <?php echo $this->_var['lang']['notice_gb_order_amount']; ?><?php endif; ?> 
  <?php if ($this->_var['total']['exchange_integral']): ?> 
  <?php echo $this->_var['lang']['notice_eg_integral']; ?><b class="ect-colory"><?php echo $this->_var['total']['exchange_integral']; ?></b><br />
  <?php endif; ?> 
</div>
