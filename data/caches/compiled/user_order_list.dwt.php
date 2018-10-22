<?php echo $this->fetch('library/user_header.lbi'); ?>
<?php if ($this->_var['show_asynclist']): ?>
<div class="ect-pro-list user-order" style="border-bottom:none;">
    <ul id="J_ItemList">
       <li class="single_item"></li>
       <a href="javascript:;" style="text-align:center" class="get_more"></a>
    </ul>
</div>
<?php else: ?>
	<div class="ect-pro-list user-order" style="border-bottom:none;">
		<ul id="J_ItemList">
		 <?php $_from = $this->_var['orders_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'orders');$this->_foreach['orders_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['orders_list']['total'] > 0):
    foreach ($_from AS $this->_var['orders']):
        $this->_foreach['orders_list']['iteration']++;
?>
			<li>
			<a href="<?php echo url('user/order_detail_wuliu', array('order_id'=>$this->_var['orders']['order_id']));?>"><img src="<?php echo $this->_var['orders']['img']; ?>" class="pull-left" />
			<dl>
			  <dt>
				<h4 class="title"><?php echo $this->_var['lang']['order_number']; ?>：<?php echo $this->_var['orders']['order_sn']; ?></h4>
			  </dt>
			  <dd><?php echo $this->_var['lang']['order_status']; ?>：<?php echo $this->_var['orders']['order_status']; ?></dd>
			  <dd><?php echo $this->_var['lang']['order_total_fee']; ?>：<span class="ect-color"><?php echo $this->_var['orders']['total_fee']; ?></span></dd>
			  <dd><?php echo $this->_var['lang']['order_addtime']; ?>：<?php echo $this->_var['orders']['order_time']; ?></dd>  
			</dl>
			<i class="pull-right fa fa-angle-right"></i> </a> 
			</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>

 <?php echo $this->fetch('library/page.lbi'); ?>
<?php endif; ?>
</div>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript">
<?php $_from = $this->_var['lang']['merge_order_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
if(<?php echo $this->_var['show_asynclist']; ?>){
get_asynclist('index.php?m=default&c=user&a=async_order_list&pay=<?php echo $this->_var['pay']; ?>' , '__TPL__/images/loader.gif');
}
</script> 
</body></html>