<div class="con">
		<div id="n-goods" class="goods-info of-hidden ect-tab b-color-f j-goods-info j-ect-tab ts-3" style="margin-bottom: 6.4rem;margin-top:0.2rem;">
			<div class="hd j-tab-title tab-title b-color-f of-hidden">
				<ul class="dis-box">
					<li class="box-flex active"><?php echo $this->_var['lang']['goods_brief']; ?></li>
					<li class="box-flex">规格参数</li>
				</ul>
			</div>
			<div id="j-tab-con" class="b-color-f m-top1px tab-con ">
				<div class="swiper-wrapper">
					<section class="swiper-slide ">
						<div class="padding-all">
						<?php if ($this->_var['goods']['goods_desc']): ?>
							<?php echo $this->_var['goods']['goods_desc']; ?>
							<?php else: ?>
							<div class="no-div-message">
			<i class="iconfont icon-biaoqingleiben"></i>
			<p>亲，此处没有内容～！</p>
		</div>
							<?php endif; ?>
						
						</div>
					</section>
					<section class="swiper-slide goods-info-attr">
					<?php if ($this->_var['properties']): ?>
							<ul class="t-remark">
							<?php $_from = $this->_var['properties']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'property_group');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['property_group']):
?>
								<?php $_from = $this->_var['property_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'property');if (count($_from)):
    foreach ($_from AS $this->_var['property']):
?>
									<li class="of-hidden"><span class="fl">[<?php echo htmlspecialchars($this->_var['property']['name']); ?>]</span><span class="fr"><?php echo $this->_var['property']['value']; ?></span></li>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
							<?php else: ?>
							<div class="no-div-message">
			<i class="iconfont icon-biaoqingleiben"></i>
			<p>亲，此处没有内容～！</p>
		</div>
							<?php endif; ?>
						
					</section>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			/*切换*/
			var tabsSwiper = new Swiper('#j-tab-con', {
				speed: 100,
				noSwiping: true,
				autoHeight: true,
				onSlideChangeStart: function() {
					$(".j-tab-title .active").removeClass('active')
					$(".j-tab-title li").eq(tabsSwiper.activeIndex).addClass('active')
				}
			})
			$(".j-tab-title li").on('touchstart mousedown', function(e) {
				e.preventDefault()
				$(".j-tab-title .active").removeClass('active')
				$(this).addClass('active')
				tabsSwiper.slideTo($(this).index())
			})
			$(".j-tab-title li").click(function(e) {
				e.preventDefault()
			})
			
		</script>
	</body>

</html>