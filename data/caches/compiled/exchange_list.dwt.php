<?php echo $this->fetch('library/page_header.lbi'); ?>

<style>
.ect-pro-list ul li{background:#fff;}
</style>
<div class="con">
<div style="height:7.2em;"></div>
  <header>
    <nav class="ect-nav ect-bg icon-write">
      <?php echo $this->fetch('library/page_menu.lbi'); ?>
    </nav>
  </header>
<?php echo $this->fetch('library/goods_list_exchange.lbi'); ?>
</div>
</div>
<?php echo $this->fetch('library/new_search.lbi'); ?> 
<?php echo $this->fetch('library/page_footer.lbi'); ?> 
<script type="text/javascript" src="__TPL__/statics/js/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/template.js"></script>
<script type="text/javascript">
var url = '<?php echo url('exchange/asynclist_list', array('page'=>$this->_var['page'],'size'=>$this->_val['size'], 'sort'=>$this->_var['sort'], 'order'=>$this->_var['order']));?>';
var page = 1;
$.post(url, {page: page}, function(data){
	var html = template('j-product', data);
	$('#j-product-box').append(html);
}, 'json');
$(window).scroll(function () {
	var scrollTop = $(this).scrollTop();
	var scrollHeight = $(document).height();
	var windowHeight = $(this).height();
	if (scrollTop + windowHeight == scrollHeight) {
		$.post(url, {page: ++page}, function(data){
			var html = template('j-product', data);
			$('#j-product-box').append(html);
		}, 'json');
	}
});
</script>
</body>
</html>
