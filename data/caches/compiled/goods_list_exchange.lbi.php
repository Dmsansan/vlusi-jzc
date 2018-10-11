<form method="GET" class="sort" name="listform">
  <div class="ect-wrapper text-center" >
    <div><a class="<?php if ($this->_var['sort'] == 'goods_id' && $this->_var['order'] == 'DESC'): ?>ect-colory<?php endif; ?>" href="<?php echo url('exchange/index',array('id'=>$this->_var['id'],'display'=>$this->_var['display'],'brand'=>$this->_var['brand_id'],'price_min'=>$this->_var['price_min'],'price_max'=>$this->_var['price_max'],'filter_attr'=>$this->_var['filter_attr'], 'sort'=>'goods_id', 'order'=> 'DESC'));?>"><?php echo $this->_var['lang']['sort_default']; ?></a> <a class="<?php if ($this->_var['sort'] == 'sales_volume' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'sales_volume' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php else: ?><?php endif; ?>" href="<?php echo url('exchange/index',array('id'=>$this->_var['id'],'display'=>$this->_var['display'],'brand'=>$this->_var['brand_id'],'price_min'=>$this->_var['price_min'],'price_max'=>$this->_var['price_max'],'filter_attr'=>$this->_var['filter_attr'], 'sort'=>'sales_volume', 'order'=> ($this->_var['sort']=='sales_volume' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>"><?php echo $this->_var['lang']['exchange_num']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> <a class="<?php if ($this->_var['sort'] == 'click_count' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'click_count' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php else: ?><?php endif; ?>" href="<?php echo url('exchange/index',array('id'=>$this->_var['id'],'display'=>$this->_var['display'],'brand'=>$this->_var['brand_id'],'price_min'=>$this->_var['price_min'],'price_max'=>$this->_var['price_max'],'filter_attr'=>$this->_var['filter_attr'], 'sort'=>'click_count', 'order'=> ($this->_var['sort']=='click_count' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>"><?php echo $this->_var['lang']['sort_popularity']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> <a class="<?php if ($this->_var['sort'] == 'exchange_integral' && $this->_var['order'] == 'DESC'): ?>select ect-colory<?php elseif ($this->_var['sort'] == 'exchange_integral' && $this->_var['order'] == 'ASC'): ?>ect-colory<?php else: ?><?php endif; ?>" href="<?php echo url('exchange/index',array('id'=>$this->_var['id'],'display'=>$this->_var['display'],'brand'=>$this->_var['brand_id'],'price_min'=>$this->_var['price_min'],'price_max'=>$this->_var['price_max'],'filter_attr'=>$this->_var['filter_attr'], 'sort'=>'exchange_integral', 'order'=> ($this->_var['sort']=='exchange_integral' && $this->_var['order']=='ASC')?'DESC':'ASC'));?>"><?php echo $this->_var['lang']['exchange_integral']; ?> <i class="glyphicon glyphicon-arrow-up"></i></a> </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->_var['id']; ?>" />
  <input type="hidden" name="display" value="<?php echo $this->_var['pager']['display']; ?>" id="display" />
  <input type="hidden" name="brand" value="<?php echo $this->_var['brand_id']; ?>" />
  <input type="hidden" name="price_min" value="<?php echo $this->_var['price_min']; ?>" />
  <input type="hidden" name="price_max" value="<?php echo $this->_var['price_max']; ?>" />
  <input type="hidden" name="filter_attr" value="<?php echo $this->_var['filter_attr']; ?>" />
  <input type="hidden" name="page" value="<?php echo $this->_var['page']; ?>" />
  <input type="hidden" name="sort" value="<?php echo $this->_var['sort']; ?>" />
  <input type="hidden" name="order" value="<?php echo $this->_var['order']; ?>" />
  <input type="hidden" name="keywords" value="<?php echo $this->_var['keywords']; ?>" />
</form>
<?php if ($this->_var['show_asynclist']): ?>
<div class="ect-margin-tb ect-pro-list ect-margin-bottom0 ect-border-bottom0">
  <ul id="J_ItemList">
    <li class="single_item"></li>
    <a href="javascript:;" class="get_more"></a>
  </ul>
</div>
<?php else: ?>
<div class="ect-margin-tb ect-pro-list ect-margin-bottom0 ect-border-bottom0">
  <ul id="j-product-box">
  <script id="j-product" type="text/html">
	{{each list as groupbuy i}}
    <li class="single_item"> <a href="{{groupbuy.url}}"><img src="{{groupbuy.goods_thumb}}<?php echo $this->_var['groupbuy']['goods_thumb']; ?>" alt="{{groupbuy.goods_name}}"></a>
      <dl>
        <dt>
          <h4 class="title"><a href="{{groupbuy.url}}">{{groupbuy.goods_name}}</a></h4>
        </dt>
        <dd class="dd-price"><span class="pull-left"><strong><?php echo $this->_var['lang']['price']; ?>：<b class="ect-colory">{{groupbuy.exchange_integral}} 积分</b></strong><small class="ect-margin-lr"><del><?php echo $this->_var['groupbuy']['market_price']; ?></del></small></span><span class="ect-pro-price"> <i class="label zk">兑</i> </span></dd>
        <dd class="dd-num"><span class="pull-left"><i class="fa fa-eye"></i> {{groupbuy.click_count}}<?php echo $this->_var['lang']['scan_num']; ?></span><span class="pull-right"><?php echo $this->_var['lang']['sort_sales']; ?>：{{groupbuy.sales_count}}<?php echo $this->_var['lang']['piece']; ?></span> </dd>
        <dd style="display:none"> {{groupbuy.spare_price}}</dd>
      </dl>
    </li>
	{{/each}}
	</script>
  </ul>
</div>
<?php endif; ?> 