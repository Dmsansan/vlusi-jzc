<div class="con">
  <div class="ect-bg">
    <header class="ect-header ect-margin-tb ect-margin-lr text-center ect-bg icon-write"> <a href="javascript:history.go(-1)" class="pull-left ect-icon ect-icon1 ect-icon-history"></a> <span><?php echo $this->_var['title']; ?></span> <a href="javascript:;" onClick="openMune()" class="pull-right ect-icon ect-icon1 ect-icon-mune"></a></header>
    <nav class="ect-nav ect-nav-list" style="display:none;"> <?php echo $this->fetch('library/page_menu.lbi'); ?> </nav>
  </div>
  <div class="flow-checkout">
    <form action="<?php echo url('flow/done');?>" method="post" name="theForm" id="theForm" onSubmit="return checkOrderForm(this)">
      <script type="text/javascript">
        var flow_no_payment = "<?php echo $this->_var['lang']['flow_no_payment']; ?>";
        var flow_no_shipping = "<?php echo $this->_var['lang']['flow_no_shipping']; ?>";
        </script><a href="<?php echo url('flow/consignee_list');?>">
      <section class="ect-margin-tb ect-padding-lr ect-padding-tb checkout-add">
          <label for="addressId<?php echo $this->_var['con_list']['address_id']; ?>">
          <p class="title"><?php echo htmlspecialchars($this->_var['consignee']['consignee']); ?> <span><?php echo $this->_var['consignee']['mobile']; ?></span></p>
          <p><?php echo $this->_var['consignee']['address']; ?></p>
          <i class="fa fa-angle-right"></i>
          </label>
        </section>
      </a>
      <section class="ect-margin-tb ect-padding-lr checkout-select" id="accordion"> 
        <?php if ($this->_var['total']['real_goods_count'] != 0): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
        <p><b><?php echo $this->_var['lang']['shipping_method']; ?></b><span class="label ect-bg-colory"><?php echo $this->_var['lang']['require_field']; ?></span></p>
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseOne" class="panel-collapse collapse in">
          <ul class="ect-radio">
            <?php $_from = $this->_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS $this->_var['shipping']):
?>
            <li>
              <input name="shipping" type="radio" id="shipping_<?php echo $this->_var['shipping']['shipping_id']; ?>" value="<?php echo $this->_var['shipping']['shipping_id']; ?>"  <?php if ($this->_var['order']['shipping_id'] == $this->_var['shipping']['shipping_id']): ?>checked="true"<?php endif; ?> supportCod="<?php echo $this->_var['shipping']['support_cod']; ?>" insure="<?php echo $this->_var['shipping']['insure']; ?>" onclick="selectShipping(this)">
              <label for="shipping_<?php echo $this->_var['shipping']['shipping_id']; ?>"><?php echo $this->_var['shipping']['shipping_name']; ?> [<?php echo $this->_var['shipping']['format_shipping_fee']; ?>]<i></i></label>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
        </div>
        <?php else: ?>
        <input name="shipping"  type="radio" value = "-1" checked="checked" style="display:none" />
        <?php endif; ?> 
        <?php if ($this->_var['is_exchange_goods'] != 1 || $this->_var['total']['real_goods_count'] != 0): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
        <p><b><?php echo $this->_var['lang']['payment_method']; ?></b><span class="label ect-bg-colory"><?php echo $this->_var['lang']['require_field']; ?></span></p>
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseTwo" class="panel-collapse collapse in">
          <ul class="ect-radio">
            <?php $_from = $this->_var['payment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');if (count($_from)):
    foreach ($_from AS $this->_var['payment']):
?>
            <li>
              <input name="payment" type="radio" id="payment_<?php echo $this->_var['payment']['pay_id']; ?>" value="<?php echo $this->_var['payment']['pay_id']; ?>" <?php if ($this->_var['order']['pay_id'] == $this->_var['payment']['pay_id']): ?>checked<?php endif; ?> isCod="<?php echo $this->_var['payment']['is_cod']; ?>" onclick="selectPayment(this)" <?php if ($this->_var['cod_disabled'] && $this->_var['payment']['is_cod'] == "1"): ?>disabled="true"<?php endif; ?> style="vertical-align:middle">
              <label for="payment_<?php echo $this->_var['payment']['pay_id']; ?>"><?php echo $this->_var['payment']['pay_name']; ?> [<?php echo $this->_var['payment']['format_pay_fee']; ?>]<i></i></label>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
        </div>
        <?php else: ?>
        <input name = "payment" type="radio" value = "-1" checked="checked"  style="display:none"/>
        <?php endif; ?> 
        <?php if ($this->_var['inv_content_list']): ?> 
        <a onclick="isinv()" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo1">
        <p><b><?php echo $this->_var['lang']['invoice']; ?></b></p><input id="invinput" type="hidden"  name="invinput" value="0" />
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseTwo1" class="panel-collapse collapse in" style="overflow:hidden;">
          
		  
          <div id="need_inv">
		  <input name="need_inv" type="checkbox"  class="input" id="ECS_NEEDINV" onclick="changeNeedInv()" value="0"  checked="true" style="display:none"/>
          </div>
          <li> 
            <?php if ($this->_var['inv_type_list']): ?> 
            <span class="pull-left select-span"><?php echo $this->_var['lang']['invoice_type']; ?></span>
            <div class="form-select pull-left"> <i class="fa fa-sort"></i>
              <select name="inv_type" id="ECS_INVTYPE"  onchange="changeNeedInv()" style="border:1px solid #ccc;">
                
         	<?php echo $this->html_options(array('options'=>$this->_var['inv_type_list'],'selected'=>$this->_var['order']['inv_type'])); ?>
          
              </select>
            </div>
          </li>
          <?php endif; ?>
          <li>
            <input name="inv_payee" type="text"  placeholder="<?php echo $this->_var['lang']['please_invoice_title']; ?>" class="input" id="ECS_INVPAYEE" size="20" maxlength="22" value="<?php echo $this->_var['order']['inv_payee']; ?>" onblur="changeNeedInv()" />
          </li>
          <li> <span class="pull-left select-span"><?php echo $this->_var['lang']['invoice_content']; ?></span>
            <div class="form-select pull-left"> <i class="fa fa-sort"></i>
              <select name="inv_content" id="ECS_INVCONTENT"  onchange="changeNeedInv()" style="border:1px solid #ccc;">
                
            
           	 <?php echo $this->html_options(array('values'=>$this->_var['inv_content_list'],'output'=>$this->_var['inv_content_list'],'selected'=>$this->_var['order']['inv_content'])); ?>
          
          
              </select>
            </div>
          </li>
        </div>
        <?php endif; ?>
      </section>
      <?php if ($this->_var['pack_list'] || $this->_var['card_list']): ?>
      <section class="ect-margin-tb ect-padding-lr checkout-select"> 
        <?php if ($this->_var['pack_list']): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
        <p><b><?php echo $this->_var['lang']['goods_package']; ?></b></p>
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseThree" class="panel-collapse collapse in">
          <ul class="ect-radio">
            <li>
              <input  type="radio" id="pack_<?php echo $this->_var['pack']['pack_id']; ?>"  name="pack" value="0" <?php if ($this->_var['order']['pack_id'] == 0): ?>checked="true"<?php endif; ?> onclick="selectPack(this)" >
              <label for="pack_<?php echo $this->_var['pack']['pack_id']; ?>"><?php echo $this->_var['lang']['no_pack']; ?><i></i></label>
            </li>
            <?php $_from = $this->_var['pack_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pack');if (count($_from)):
    foreach ($_from AS $this->_var['pack']):
?>
            <li>
              <input type="radio" class="radio" name="pack" id="pack_<?php echo $this->_var['pack']['pack_id']; ?>" value="<?php echo $this->_var['pack']['pack_id']; ?>" <?php if ($this->_var['order']['pack_id'] == $this->_var['pack']['pack_id']): ?>checked="true"<?php endif; ?> onclick="selectPack(this)" />
              <label for="pack_<?php echo $this->_var['pack']['pack_id']; ?>"><?php echo $this->_var['pack']['pack_name']; ?>[<?php echo $this->_var['pack']['format_pack_fee']; ?>][<?php echo $this->_var['lang']['free_money']; ?>:<?php echo $this->_var['pack']['format_free_money']; ?>]<i></i></label>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
        </div>
        <?php endif; ?> 
        <?php if ($this->_var['card_list']): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
        <p><b><?php echo $this->_var['lang']['goods_card']; ?></b></p>
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseFour" class="panel-collapse collapse in">
          <ul class="ect-radio">
            <li>
              <input name="card" type="radio"  value="0" <?php if ($this->_var['order']['card_id'] == 0): ?>checked="true"<?php endif; ?> onclick="selectCard(this)" id="card_0" />
              <label for="card_0"><?php echo $this->_var['lang']['no_card']; ?><i></i></label>
            </li>
            <?php $_from = $this->_var['card_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'card');if (count($_from)):
    foreach ($_from AS $this->_var['card']):
?>
            <li>
              <input name="card" type="radio" id="card_<?php echo $this->_var['card']['card_id']; ?>" value="<?php echo $this->_var['card']['card_id']; ?>" <?php if ($this->_var['order']['card_id'] == $this->_var['card']['card_id']): ?>checked="true"<?php endif; ?> onclick="selectCard(this)">
              <label for="card_<?php echo $this->_var['card']['card_id']; ?>"><?php echo $this->_var['card']['card_name']; ?>[<?php echo $this->_var['card']['format_card_fee']; ?>][<?php echo $this->_var['lang']['free_money']; ?>:<?php echo $this->_var['card']['format_free_money']; ?>]<i></i></label>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
          <input name="card_message" maxlength="22" type="text" placeholder="<?php echo $this->_var['lang']['bless_note']; ?>">
        </div>
      </section>
      <?php endif; ?> 
      <?php endif; ?>
      <section class="ect-margin-tb ect-padding-lr checkout-select"> 
	  <?php if ($this->_var['allow_use_bonus'] && $this->_var['bonus_list']): ?>
	 	<a data-toggle="collapse" data-parent="#accordion" href="#collapseBonus">
        <p><b><?php echo $this->_var['lang']['use_bonus']; ?></b></p>
        <i class="fa fa-angle-down"></i></a>
        <div id="collapseBonus" class="panel-collapse collapse in">
		  <ul class="ect-radio">
            <li>
              <input  type="radio" id="bonus_<?php echo $this->_var['bonus']['bonus_id']; ?>"  name="bonus" value="0" <?php if ($this->_var['order']['bonus_id'] == 0): ?>checked="true"<?php endif; ?> onclick="changeBonus(this)" >
              <label for="bonus_<?php echo $this->_var['bonus']['bonus_id']; ?>"><?php echo $this->_var['lang']['no_use_bonus']; ?><i></i></label>
            </li>
            <?php $_from = $this->_var['bonus_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'bonus');if (count($_from)):
    foreach ($_from AS $this->_var['bonus']):
?>
            <li>
              <input type="radio" class="radio" name="bonus" id="bonus_<?php echo $this->_var['bonus']['bonus_id']; ?>" value="<?php echo $this->_var['bonus']['bonus_id']; ?>" <?php if ($this->_var['order']['bonus_id'] == $this->_var['bonus']['bonus_id']): ?>checked="true"<?php endif; ?> onclick="changeBonus(this)" />
              <label for="bonus_<?php echo $this->_var['bonus']['bonus_id']; ?>"><?php echo $this->_var['bonus']['type_name']; ?>[<?php echo $this->_var['bonus']['bonus_money_formated']; ?>]<i></i></label>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
        </div>
        <?php endif; ?>
        <?php if ($this->_var['allow_use_surplus']): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour1">
        <p><b> <?php echo $this->_var['lang']['use_surplus']; ?> </b></p>
        <i class="fa fa-angle-down"></i> </a>
        <div id="collapseFour1" class="panel-collapse collapse in"> <?php echo $this->_var['lang']['your_surplus']; ?><?php echo empty($this->_var['your_surplus']) ? '0' : $this->_var['your_surplus']; ?><br />
          <span id="ECS_SURPLUS_NOTICE"></span>
          
        </div>
        <?php endif; ?> 
        
        <?php if ($this->_var['allow_use_integral']): ?> 
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive1">
        <p><b> <?php echo $this->_var['lang']['use_integral']; ?> </b></p>
        <i class="fa fa-angle-down"></i> </a>
        <div id="collapseFive1" class="panel-collapse collapse in"> <?php echo $this->_var['lang']['can_use_integral']; ?>:<?php echo empty($this->_var['your_integral']) ? '0' : $this->_var['your_integral']; ?> <?php echo $this->_var['points_name']; ?>，<?php echo $this->_var['lang']['noworder_can_integral']; ?><?php echo $this->_var['order_max_integral']; ?>  <?php echo $this->_var['points_name']; ?>.<br />
          <span id="ECS_INTEGRAL_NOTICE" class="notice"></span>
          <input name="integral" type="text" class="input" id="ECS_INTEGRAL" onblur="changeIntegral(this.value)" value="<?php echo empty($this->_var['order']['integral']) ? '0' : $this->_var['order']['integral']; ?>" size="10" />
        </div>
        <?php endif; ?> 
        
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
        <p><b> <?php echo $this->_var['lang']['order_postscript']; ?> </b></p>
        <i class="fa fa-angle-down"></i> </a>
        <div id="collapseFive" class="panel-collapse collapse in">
          <input name="postscript" type="text" class="fuyan" maxlength="22" placeholder="<?php echo $this->_var['lang']['please_order_postscript']; ?>">
        </div>
      </section>
      <section class="ect-margin-tb ect-margin-bottom0 ect-padding-lr checkout-select checkout-pro-list">
        <p><b><?php echo $this->_var['lang']['goods_list']; ?></b><span class="label ect-bg-colory"><?php if ($this->_var['allow_edit_cart']): ?><a href="<?php echo url('flow/index');?>" class="ect-colorf"><?php echo $this->_var['lang']['modify']; ?></a><?php endif; ?></span></p>
        <ul>
          <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
          <li>
            <dl>
              <dt class="pull-left"> 
                <?php if ($this->_var['goods']['goods_id'] > 0 && $this->_var['goods']['extension_code'] == 'package_buy'): ?> 
                <a href="javascript:void(0)" onClick="setSuitShow(<?php echo $this->_var['goods']['goods_id']; ?>)" ><?php echo $this->_var['goods']['goods_name']; ?><span style="color:#FF0000;">（<?php echo $this->_var['lang']['remark_package']; ?>）</span></a> 
                <?php else: ?> 
                <a href="<?php echo url('goods/index',array('id'=>$this->_var['goods']['goods_id']));?>" target="_blank" ><?php echo $this->_var['goods']['goods_name']; ?></a> 
                <?php if ($this->_var['goods']['parent_id'] > 0): ?> 
                <span style="color:#FF0000">（<?php echo $this->_var['lang']['accessories']; ?>）</span> 
                <?php elseif ($this->_var['goods']['is_gift']): ?> 
                <span style="color:#FF0000">（<?php echo $this->_var['lang']['largess']; ?>）</span> 
                <?php endif; ?> 
                <?php endif; ?> 
                <?php if ($this->_var['goods']['is_shipping']): ?>(<span style="color:#FF0000"><?php echo $this->_var['lang']['free_goods']; ?></span>)<?php endif; ?> 
              </dt>
              <dd class="pull-left list-num">x <?php echo $this->_var['goods']['goods_number']; ?></dd>
              <dd class="pull-right"><?php echo $this->_var['goods']['formated_subtotal']; ?></dd>
            </dl>
          </li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php echo $this->fetch('library/order_total.lbi'); ?> </section>
      <div class="ect-padding-lr ect-padding-tb ect-margin-tb">
        <input type="submit" name="submit" value="<?php echo $this->_var['lang']['order_submit']; ?>" class="btn btn-info ect-btn-info ect-colorf ect-bg"/>
        <input type="hidden" name="step" value="done" />
      </div>
    </form>
  </div>
</div>