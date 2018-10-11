<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<div class="con" >
	<?php echo $this->fetch('library/new_search_small.lbi'); ?>
	<aside>
		<div class="menu-left scrollbar-none" id="sidebar">
			<ul>
				<?php $_from = $this->_var['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');$this->_foreach['vo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['vo']['total'] > 0):
    foreach ($_from AS $this->_var['vo']):
        $this->_foreach['vo']['iteration']++;
?>
				<li <?php if (($this->_foreach['vo']['iteration'] - 1) == 0): ?> class="active"<?php endif; ?>><?php if (count ( $this->_var['vo']['cat_id'] ) > 0): ?><?php echo $this->_var['vo']['name']; ?><?php else: ?><a href="<?php echo $this->_var['vo']['url']; ?>"><?php echo $this->_var['vo']['name']; ?></a><?php endif; ?></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
		</div>
	</aside>
	<?php $_from = $this->_var['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');$this->_foreach['val'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['val']['total'] > 0):
    foreach ($_from AS $this->_var['val']):
        $this->_foreach['val']['iteration']++;
?>
	<section class="menu-right padding-all j-content"<?php if (($this->_foreach['val']['iteration'] - 1) != 0): ?> style="display:none"<?php endif; ?>>
		<?php $_from = $this->_var['val']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');$this->_foreach['category'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['category']['total'] > 0):
    foreach ($_from AS $this->_var['vo']):
        $this->_foreach['category']['iteration']++;
?>
			<?php if (count ( $this->_var['vo']['cat_id'] ) > 0): ?>
				<h5><?php echo $this->_var['vo']['name']; ?></h5>
				<ul>
				<?php $_from = $this->_var['vo']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
					<li class="w-3"><a href="javascript:void(0);" onclick='javascript:redirect_list(<?php echo $this->_var['v']['id']; ?>);'></a>
					<img src="<?php echo $this->_var['v']['img']; ?>" /><span><?php echo $this->_var['v']['name']; ?></span></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			<?php else: ?>
				<?php if ($this->_foreach['category']['iteration'] == 1): ?>
				<h5><?php echo $this->_var['val']['name']; ?></h5>
				<ul>
				<?php $_from = $this->_var['val']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'v');if (count($_from)):
    foreach ($_from AS $this->_var['v']):
?>
					<li class="w-3"><a href="javascript:void(0);" onclick='javascript:redirect_list(<?php echo $this->_var['v']['id']; ?>);'></a>
					<img src="<?php echo $this->_var['v']['img']; ?>" /><span><?php echo $this->_var['v']['name']; ?></span></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</section>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<footer class="footer-nav dis-box">
				<a href="<?php echo url('index/index');?>" class="box-flex nav-list">
					<i class="nav-box i-home"></i><span>首页</span>
				</a>
				<a href="<?php echo url('category/top_all');?>" class="box-flex nav-list active">
					<i class="nav-box i-cate"></i><span>分类</span>
				</a>
				
				<a href="<?php echo url('store/check_store');?>" class="box-flex nav-list ">
					<i class="nav-box i-shop"></i><span>店铺</span>
				</a>
				
				<a href="<?php echo url('flow/cart');?>" class="box-flex position-rel nav-list">
					<i class="nav-box i-flow"></i><span>购物车</span>
				</a>
				<a href="<?php echo url('user/index');?>" class="box-flex nav-list">
					<i class="nav-box i-user"></i><span>我的</span>
				</a>
		</footer>	
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/new_page_footer.lbi'); ?>
<script type="text/javascript">
	$(function($){
		$('#sidebar ul li').click(function(){
			$(this).addClass('active').siblings('li').removeClass('active');
			var index = $(this).index();
			$('.j-content').eq(index).show().siblings('.j-content').hide();
			$(window).scrollTop(0);
		})
	})
	
	function redirect_list(id){
		localData.remove('cat_'+ id +'_page');
		localData.remove('cat_'+ id +'_page_min');
		localData.remove('cat_'+ id +'_page_max');
		window.location.href = 'index.php?c=category&id=' + id;
	}
</script>
</body>
</html>