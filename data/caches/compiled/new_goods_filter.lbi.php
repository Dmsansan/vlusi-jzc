<div id="j-filter-div" class="j-filter-div filter-div ts-5 c-filter-div">
	<div class="mask-filter-div"></div>
	<section class="close-filter-div j-close-filter-div">
		<div class="close-f-btn">
			<i class="iconfont icon-fanhui"></i>
			<span>关闭</span>
		</div>
	</section>
	<section class="con-filter-div">
		<form class="hold-height" method="post" id="form" action="<?php echo url('category/index');?>">
		<input type="hidden" name="id" class="cat" value="<?php echo $this->_var['id']; ?>" />
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="price-range padding-all m-top08">
					<label><?php echo $this->_var['lang']['sort_price']; ?></label>
					<div class="price-slider">
						<div id="slider-range" class="slider"></div>
						<div class="slider-info">
							<span id="slider-range-amount"></span>
						</div>
					</div>
					<input type="hidden" id="min" name="price_min" value="<?php echo $this->_var['price_min']; ?>" />
					<input type="hidden" id="max" name="price_max" value="<?php echo $this->_var['price_max']; ?>" />
				</div>
				<div class="select-two">
					<a class="select-title padding-all j-menu-select">
						<label class="fl"><?php echo $this->_var['lang']['brand']; ?></label>
						<span class="fr t-jiantou j-t-jiantou jian001" id="j-t-jiantou"><em class="fl">
						<?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');$this->_foreach['brand'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brand']['total'] > 0):
    foreach ($_from AS $this->_var['brand']):
        $this->_foreach['brand']['iteration']++;
?> 
						<?php if ($this->_var['brand']['brand_id'] == $this->_var['brand_id']): ?> 
						<?php echo $this->_var['brand']['brand_name']; ?>
						<?php endif; ?> 
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</em><i class="iconfont icon-jiantou tf-180 ts-2"></i></span>
					</a>
					<ul class="j-sub-menu padding-all j-get-one" data-istrue="true">
						<?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');$this->_foreach['brands'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brands']['total'] > 0):
    foreach ($_from AS $this->_var['brand']):
        $this->_foreach['brands']['iteration']++;
?>
						<li class="ect-select" data-attr="<?php echo $this->_var['brand']['brand_id']; ?>">
							<label class="ts-1"><?php echo htmlspecialchars($this->_var['brand']['brand_name']); ?><i class="fr iconfont icon-gou ts-1"></i></label>
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						<input type="hidden" name="brand" value="<?php echo $this->_var['brand_id']; ?>" />
					</ul>
					<?php $_from = $this->_var['filter_attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'filter');$this->_foreach['filter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['filter']['total'] > 0):
    foreach ($_from AS $this->_var['filter']):
        $this->_foreach['filter']['iteration']++;
?>
					<a class="select-title padding-all j-menu-select" id="filter_attr_25544">
						<label class="fl"><?php echo $this->_var['filter']['filter_attr_name']; ?></label>
						<span class="fr t-jiantou j-t-jiantou"><em class="fl">
						<?php $_from = $this->_var['filter']['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');$this->_foreach['attr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['attr']['total'] > 0):
    foreach ($_from AS $this->_var['attr']):
        $this->_foreach['attr']['iteration']++;
?> 
						<?php if ($this->_var['attr']['selected']): ?> 
						<?php echo $this->_var['attr']['attr_value']; ?> 
						<?php endif; ?> 
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</em><i class="iconfont icon-jiantou tf-180 ts-2"></i></span>
					</a>
					<ul class="j-sub-menu padding-all j-get-one j-sub-menu-attr">
						<?php $_from = $this->_var['filter']['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');$this->_foreach['attr'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['attr']['total'] > 0):
    foreach ($_from AS $this->_var['attr']):
        $this->_foreach['attr']['iteration']++;
?>
						<li class="ect-select <?php if ($this->_var['attr']['selected']): ?>active<?php endif; ?>" data-attr="<?php echo $this->_var['attr']['attr_value']; ?>">
							<label class="ts-1 <?php if ($this->_var['attr']['selected']): ?>active<?php endif; ?> " id="brand_<?php echo $this->_var['attr']['attr_id']; ?>" value="<?php echo $this->_var['attr']['attr_id']; ?>"><?php echo htmlspecialchars($this->_var['attr']['attr_value']); ?><i class="fr iconfont icon-gou ts-1"></i></label>
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>						
					</ul>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<input type="hidden" id="filter_attr" name="filter_attr" value="<?php echo $this->_var['filter_attr']; ?>" />
				</div>
				<div class="ect-button-more dis-box padding-all">
					<a class="box-flex btn-reset j-filter-reset" onclick="javascript:clear_filter();">清空选项</a>
					<button type="submit" class="box-flex btn-submit">确定</button>
				</div>
			</div>
		</div>
		</form>
	</section>
</div>

<script type="text/javascript">
function clear_filter(){
 $(".touchweb-com_listType .range").text("全部");
	$(".touchweb-com_listType input").each(function() {
		if($(this).attr('class') != 'cat'){ 
			$(this).val("");
		}
	});
	$(".ui-slider-handle").css("left","0");
	$(".ui-slider-range").css({left:0,width:0});
	$("#slider-range-amount").html(0);
	$("#min ,#max").val(0);
	$('input[name="brand"]').val(0);
	$("#filter_attr").val(0);
}


</script>