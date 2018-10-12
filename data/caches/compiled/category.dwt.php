<?php echo $this->fetch('library/new_page_header.lbi'); ?>
	<style>
            a.a-sequence {width: 3.5rem;height: 0rem;line-height: 3rem;margin-left:0.5rem;}
            a.s-filter {line-height: 3rem;padding-left: 0rem;}
   </style>
		<div class="con">
			<div class="category">
				<section class="search">
					<div class="text-all dis-box j-text-all text-all-back">
						<a class="a-icon-back j-close-search" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou is-left-font"></i></a>
						<div class="box-flex input-text n-input-text i-search-input">
							<a class="a-search-input j-search-input" href="javascript:void(0)"></a>
							<i class="iconfont icon-sousuo"></i>
							<input class="j-input-text" type="text" placeholder="请输入您搜索的关键词!">
							<i class="iconfont icon-guanbi1 is-null j-is-null"></i>
						</div>
						<a class="a-sequence j-a-sequence"><i class="iconfont icon-pailie" data="1"></i></a>
					</div>
				</section>
				<?php echo $this->fetch('library/new_goods_list.lbi'); ?>
			</div>
		</div>
	
<div class="filter-top" id="scrollUp">
	<i class="iconfont icon-dingbu"></i>
</div>

		<?php echo $this->fetch('library/new_goods_filter.lbi'); ?>
		<?php echo $this->fetch('library/new_search.lbi'); ?>
		<div class="div-messages"></div>
		<?php echo $this->fetch('library/new_page_footer.lbi'); ?>

		<script type="text/javascript" src="__TPL__/statics/js/echo.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/js/template.js"></script>
<script type="text/javascript">
$('#hide-div').delay(1500).hide(0);
	var sliders = function() {
		// 筛选价格区间 js
		$("#slider-range").slider({
			range: true,
			min: 0,
			max: 1000,
			step: 100,
			values: [0, 300],
			slide: function(event, ui) {
				$("#slider-range-amount").text(ui.values[0] + " ~ " + ui.values[1]);
				$('input[name=price_min]').val(ui.values[0]);
				$('input[name=price_max]').val(ui.values[1]);
			}
		});
		var price_min = $('input[name=price_min]').val();
		var price_max = $('input[name=price_max]').val();
		$("#slider-range").slider({values: [price_min, price_max]});
		$("#slider-range-amount").text(price_min + " ~ " + price_max);
	}();

	var url = '<?php echo url('category/async_list', array('id'=>$this->_var['id'], 'type'=>$this->_var['type'], 'brand'=>$this->_var['brand_id'], 'price_min'=>$this->_var['price_min'], 'price_max'=>$this->_var['price_max'], 'filter_attr'=>$this->_var['filter_attr'], 'page'=>$this->_var['page'], 'sort'=>$this->_var['sort'], 'order'=>$this->_var['order'], 'keywords'=>$this->_var['keywords']));?>';
	var total = 0;
	var page = localData.get('cat_<?php echo $this->_var['id']; ?>_page');
	page = (page === null) ? 1:parseInt(page);
	// first request
	get_data(page);
	localData.set('cat_<?php echo $this->_var['id']; ?>_page', page);
	if(page == 1){
		localData.set('cat_<?php echo $this->_var['id']; ?>_page_min', 1);
		localData.set('cat_<?php echo $this->_var['id']; ?>_page_max', 1);
	}
	if(page > 1){
		localData.set('cat_<?php echo $this->_var['id']; ?>_page_min', page);
		localData.set('cat_<?php echo $this->_var['id']; ?>_page_max', page);
	}
	var minPage = localData.get('cat_<?php echo $this->_var['id']; ?>_page_max');
	var maxPage = localData.get('cat_<?php echo $this->_var['id']; ?>_page_max');
	minPage = (minPage === null) ? 1:parseInt(minPage);
	maxPage = (maxPage === null) ? 1:parseInt(maxPage);

	$(window).scroll(function () {
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		// 下拉
		if (scrollTop + windowHeight == scrollHeight) {
			if(page >= maxPage){
				$.ajax({
					type : "post",
					url : url,
					data : 'page=' + (page + 1),
					dataType: 'json',
					async : false,
					success : function(data){
						var html = template('j-product', data);
						$('#j-product-box').append(html);
						//rug
						if(!isEmpty(data.list)){
							page++;
							localData.set('cat_<?php echo $this->_var['id']; ?>_page', page);
							if(page > maxPage){
								localData.set('cat_<?php echo $this->_var['id']; ?>_page_max', page);
							}
						}
					}
				});
			}
		}
		// 上拉
		if(scrollTop == 0 && minPage > 1){
			$.ajax({
				type : "post",
				url : url,
				data : 'page=' + (page - 1),
				dataType: 'json',
				async : false,
				success : function(data){
					var html = template('j-product', data);
					$('#j-product-box').prepend(html);
					page--;
					localData.set('cat_<?php echo $this->_var['id']; ?>_page', page);
					if(page < minPage){
						localData.set('cat_<?php echo $this->_var['id']; ?>_page_min', page);
					}
				}
			});
		}
	});

	function get_data(page){
		$.ajax({
			type : "post",
			url : url,
			data : 'page=' + page,
			dataType: 'json',
			async : false,
			success : function(data){
				var html = template('j-product', data);
				$('#j-product-box').append(html);
			}
		});
	}

	/*
   * 检测对象是否是空对象(不包含任何可读属性)。
   * 方法既检测对象本身的属性，也检测从原型继承的属性(因此没有使hasOwnProperty)。
   */
  function isEmpty(obj) {
      for (var name in obj) {
          return false;
      }
      return true;
  }
  echo.init();
  $(function(){
  	$('.ect-select').click(function(){
  		var data = $(this).attr('data-attr');
  		$(this).siblings('input[type="hidden"]').val(data);
  	})
  })
  //属性
  $(function() {
      $('.j-sub-menu-attr li').click(function() {
  		$(this).addClass('active').siblings('li').removeClass('active');
  		var i = 0;
  		var new_attr = new Array();
  		$(".j-sub-menu-attr li").each(function(){
  			if ($(this).hasClass('active')) {
  				 new_attr[i] = $(this).find('label').attr('value');
  				 i = i + 1;
  			 }
  		});
  		var new_attr_str = new_attr.join('.');
          //属性参数具体值
          $('input[name=filter_attr]').val(new_attr_str);
      })
  })
</script>

	</body>
</html>
