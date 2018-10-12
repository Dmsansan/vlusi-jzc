<style>
	blockquote {
		display: block;
		-webkit-margin-before: 1em;
		-webkit-margin-after: 1em;
		-webkit-margin-start: 1.3rem;
		-webkit-margin-end: 1.3rem;
	}
	
	.n-list-box {
		color: #1CBB7F;
		font-size: 1.5rem;
		padding: 10px 0;
		text-align: center;
	}
	
	.n-taocan-box {
		border-bottom: 1px dashed #eee;
		padding-bottom: 1rem;
	}
</style>

<?php if ($this->_var['fittings']): ?>
<div class="box" style="margin: 1.5rem auto;background-color: #FFFFFF;">
	<div class="box_1">
		<h3 style="padding:0;">
      <div id="cn_b" class="history clearfix dis-box"> 
        <?php $_from = $this->_var['fittings_tab_index']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'tab_item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['tab_item']):
?> 
        <?php if ($this->_var['key'] == 1): ?>
        <h2  class="box-flex n-list-box">套餐<?php echo $this->_var['comboTab'][$this->_var['key']]; ?></h2>
        <?php else: ?>
        <h2  class="box-flex n-list-box h2bg">套餐<?php echo $this->_var['comboTab'][$this->_var['key']]; ?></h2>
        <?php endif; ?> 
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
      </div>
    </h3>
		<div id="cn_v" class="boxCenterList"></div>
		<div id="cn_h">
			<?php $_from = $this->_var['fittings_tab_index']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'tab_item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['tab_item']):
?>
			<blockquote class="am-left">
				<form name="m_goods_<?php echo $this->_var['key']; ?>" method="post" action="" onSubmit="return false;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;">
						<tr class="n-taocan-box">
							<td width="10%"><input class="m_goods_body m_goods_<?php echo $this->_var['key']; ?>" item="m_goods_<?php echo $this->_var['key']; ?>" type="checkbox" value="<?php echo $this->_var['goods']['goods_id']; ?>" data="<?php echo $this->_var['goods']['shop_price']; ?>" spare="0" stock="0" /></td>
							<td align="center" valign="top" width="40%">
								<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="100" /></a>
							</td>
							<td width="50%"><?php echo sub_str($this->_var['goods']['goods_name'],28); ?><br>
								<font class="f1">￥<strong class="m_goods_<?php echo $this->_var['key']; ?>_display"><?php echo $this->_var['goods']['shop_price']; ?></strong>元</font><br />套餐基本件</td>
						</tr>
						<tr>
							<td align="center" width="5px" height="7px" colspan="3"><img src="__TPL__/images/plus_4.gif" /></td>
						</tr>
						<?php $_from = $this->_var['fittings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_list');if (count($_from)):
    foreach ($_from AS $this->_var['goods_list']):
?>
						<?php if ($this->_var['goods_list']['group_id'] == $this->_var['key']): ?>
						<tr>
							<td><input class="m_goods_list m_goods_<?php echo $this->_var['key']; ?>" item="m_goods_<?php echo $this->_var['key']; ?>" type="checkbox" value="<?php echo $this->_var['goods_list']['goods_id']; ?>" data="<?php echo $this->_var['goods_list']['fittings_price_ori']; ?>" spare="<?php echo $this->_var['goods_list']['spare_price_ori']; ?>" stock="0" /></td>
							<td align="center" valign="top">
								<a href="<?php echo $this->_var['goods_list']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods_list']['goods_name']); ?>"><img src="<?php echo $this->_var['goods_list']['goods_thumb']; ?>" width="100" /></a>
							</td>
							<td><?php echo sub_str($this->_var['goods_list']['goods_name'],28); ?><br>
								<font class="f1">￥<strong class="m_goods_<?php echo $this->_var['key']; ?>_display"><?php echo $this->_var['goods_list']['fittings_price_ori']; ?></strong>元</font><br />搭配省<?php echo $this->_var['goods_list']['spare_price']; ?></td>
							<tr>
								<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</table>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:14px;">
						<tr>
							<td align="right" class="submit_<?php echo $this->_var['key']; ?>">
								<!-- <span class="tip_spare">搭配买共省<strong>0.00</strong>元</span> -->套餐价：￥<span class="combo_price">0.00</span>元 × <input type="text" class="combo_stock" name="m_goods_<?php echo $this->_var['key']; ?>_number" value="1" size="5" style="text-align:center;border:1px solid #1CBB7F;border-radius: 4px;background:#F6F6F9;    font-family: initial;    font-size: 1em;" />
							</td>
						</tr>
						<tr>
							<td align="right" style="padding-top:10px;">
								<a class="btn-submit box-flex" href="javascript:addMultiToCart('m_goods_<?php echo $this->_var['key']; ?>', '<?php echo $this->_var['goods']['goods_id']; ?>')">立即购买</a>
							</td>
						</tr>
					</table>
				</form>
			</blockquote>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<script type="text/javascript">
				//reg("cn");
			</script>
		</div>
	</div>
</div>
<div class="blank"></div>
<?php endif; ?>

<style type="text/css">
	.tip_spare {
		display: none
	}
	
	.h2bg {
		background: #eee;
		color: #777;
	}
</style>