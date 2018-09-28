<?php echo $this->fetch('library/page_header.lbi'); ?>
<div class="con">
  <div style="height:7.2em;"></div>
  <header>
    <nav class="ect-nav ect-bg icon-write"> <?php echo $this->fetch('library/page_menu.lbi'); ?> </nav>
  </header>
  <div class="ect-wrapper text-center">
    <div> 
    <a class="<?php if ($this->_var['sort'] == 'goods_id' && $this->_var['order'] == 'DESC'): ?>ect-colory<?php endif; ?>" href="<?php echo url('groupbuy/index', array('id'=>$this->_var['id'],'page'=>$this->_var['page']['page'],'sort'=>'goods_id','order'=>'DESC'));?>"><?php echo $this->_var['lang']['sort_default']; ?></a> 
    <a class="<?php if ($this->_var['sort'] == 'sales_count' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'sales_count' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php endif; ?>" href="<?php echo url('groupbuy/index', array('id'=>$this->_var['id'],'page'=>$this->_var['page']['page'],'sort'=>'sales_count', 'order'=> ($this->_var['sort']=='sales_count' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>"><?php echo $this->_var['lang']['sort_sales']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> 
    <a class="<?php if ($this->_var['sort'] == 'click_num' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'click_num' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php else: ?><?php endif; ?>" href="<?php echo url('groupbuy/index', array('id'=>$this->_var['id'],'page'=>$this->_var['page']['page'],'sort'=>'click_num', 'order'=> ($this->_var['sort']=='click_num' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>"><?php echo $this->_var['lang']['sort_popularity']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> 
    <a class="<?php if ($this->_var['sort'] == 'cur_price' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'cur_price' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php else: ?><?php endif; ?>" href="<?php echo url('groupbuy/index', array('id'=>$this->_var['id'],'page'=>$this->_var['page']['page'],'sort'=>'cur_price', 'order'=> ($this->_var['sort']=='cur_price' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>" class="xl"><?php echo $this->_var['lang']['sort_price']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> </div>
  </div>
  <?php if ($this->_var['show_asynclist']): ?>
  <div class="ect-margin-tb ect-pro-list ect-margin-bottom0 ect-border-bottom0">
    <ul id="J_ItemList">
      <li class="single_item"></li>
      <a href="javascript:;" class="get_more"></a>
    </ul>
  </div>
  <?php else: ?>
  <div class="ect-margin-tb ect-pro-list ect-margin-bottom0 ect-border-bottom0">
    <ul id="J_ItemList">
    <?php $_from = $this->_var['gb_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'groupbuy');$this->_foreach['gb_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gb_list']['total'] > 0):
    foreach ($_from AS $this->_var['groupbuy']):
        $this->_foreach['gb_list']['iteration']++;
?>
      <li class="single_item"> <a href="<?php echo $this->_var['groupbuy']['url']; ?>"><img src="<?php echo $this->_var['groupbuy']['goods_thumb']; ?>" alt="<?php echo $this->_var['groupbuy']['goods_name']; ?>"></a>
        <dl>
          <dt>
            <h4 class="title"><a href="<?php echo $this->_var['groupbuy']['url']; ?>"><?php echo $this->_var['groupbuy']['goods_name']; ?></a></h4>
          </dt>
          <dd class="dd-price"><span class="pull-left"><strong><?php echo $this->_var['lang']['price']; ?>：<b class="ect-colory"><?php echo $this->_var['groupbuy']['cur_price']; ?></b></strong><small class="ect-margin-lr"><del><?php echo $this->_var['groupbuy']['market_price']; ?></del></small></span><span class="ect-pro-price"> <i class="label zk"><?php echo $this->_var['groupbuy']['spare_discount']; ?><?php echo $this->_var['lang']['favourable_zk']; ?></i> </span></dd>
          <dd class="dd-num"><span class="pull-left"><i class="fa fa-eye"></i> <?php echo $this->_var['groupbuy']['click_num']; ?><?php echo $this->_var['lang']['scan_num']; ?></span><span class="pull-right"><?php echo $this->_var['lang']['sort_sales']; ?>：<?php echo $this->_var['groupbuy']['sales_count']; ?><?php echo $this->_var['lang']['piece']; ?></span> </dd>
          <dd style="display:none"> <?php echo $this->_var['groupbuy']['spare_price']; ?></dd>
        </dl>
      </li>
       <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    </ul>
  </div>
  <?php echo $this->fetch('library/page.lbi'); ?>
  <?php endif; ?> 
</div>
<?php echo $this->fetch('library/new_search.lbi'); ?> <?php echo $this->fetch('library/page_footer.lbi'); ?> 
<script type="text/javascript">
if(<?php echo $this->_var['show_asynclist']; ?>){
	get_asynclist("<?php echo url('groupbuy/asynclist', array('page'=>$this->_var['page'], 'sort'=>$this->_var['sort'], 'order'=>$this->_var['order']));?>" , '__TPL__/images/loader.gif');
}
</script>
</body></html>