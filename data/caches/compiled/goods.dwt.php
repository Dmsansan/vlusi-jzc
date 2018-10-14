<?php echo $this->fetch('library/new_page_header.lbi'); ?>


<div class="con">
	
	<div class="goods" style="margin-bottom: 0rem;">
		<div class="ect-bg">
					<header class="dis-box header-menu-1 b-color color-whie goods-fixed ts-3">
					<a class="" href="javascript:history.go(-1)">
						<div class="goods-left-jiat"><i class="iconfont icon-jiantou is-con"></i></div>
					</a>
					<div class="box-flex">
						<ul class="dis-box goods-header-nav-box">
							<li class="box-flex"><a class="hover" href="javascript:;"><label>商品</label></a></li>
							<li class="box-flex"><a href="#n-goods"><label>详情</label></a></li>
							<li class="box-flex"><a href="<?php echo url('goods/comment_list',array('id'=>$this->_var['goods']['goods_id']));?>"><label>评价</label></a></li>
						</ul>
					</div>
					<a class="" href="javascript:;">
						<div class="goods-left-jiat"><i class="iconfont icon-gengduo j-nav-box is-con"></i></div>
					</a>
					<a class="" href="javascript:;">
						<div class="goods-left-jiat"><i class="iconfont icon-gengduo j-nav-box is-con"></i></div>
					</a>
					<div class="goods-nav ts-3">
						<ul class="goods-nav-box">
						    <a href="<?php echo url('index/index');?>">
								<li><i class="iconfont icon-home j-nav-box"></i>首页</li>
							</a>
							<a href="<?php echo url('user/msg_list');?>">
								<li><i class="iconfont icon-xiaoxi1 j-nav-box"></i>消息</li>
							</a>
							<a href="<?php echo url('user/index');?>">
								<li><i class="iconfont icon-geren j-nav-box"></i>用户中心</li>
							</a>
							<a href="<?php echo url('user/order_list');?>">
								<li style="border:none"><i class="iconfont icon-quanbudingdan j-nav-box"></i>全部订单</li>
							</a>
						</ul>
					</div>
				</header>			
		</div>
		
		<div class="goods-photo n-j-show-goods-img">
			<div class="hd">
				<ul>
				</ul>
			</div>
			<span class="goods-num" id="goods-num"><span id="g-active-num"></span>/<span id="g-all-num"></span></span>
			<ul class="swiper-wrapper">
				<?php if ($this->_var['pictures']): ?>
				<?php $_from = $this->_var['pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'picture');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['picture']):
        $this->_foreach['no']['iteration']++;
?>
				<li class="swiper-slide tb-lr-center"><div class="of-hidden" id="img-photo-box"><img id="img-photo-box" src="<?php echo $this->_var['picture']['img_url']; ?>" alt="<?php echo $this->_var['picture']['img_desc']; ?>" /></div></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<?php else: ?>
				<li class="swiper-slide tb-lr-center"><div class="of-hidden" id="img-photo-box"><img id="img-photo-box" src="<?php echo $this->_var['goods']['goods_img']; ?>" alt="<?php echo $this->_var['goods']['goods_name']; ?>" /></div></li>
				<?php endif; ?>
			</ul>
			<div class="swiper-pagination"></div>
		</div>
		
		<div class="goods-info">
			
			<section class="goods-title b-color-f padding-all ">
				<div class="dis-box">
					<h3 class="box-flex"><?php echo $this->_var['goods']['goods_style_name']; ?></h3>
					
					<span class="heart j-heart <?php if ($this->_var['sc'] == 1): ?>active<?php endif; ?>" onClick="collect(<?php echo $this->_var['goods']['goods_id']; ?>)" id='ECS_COLLECT'><i class="ts-2"></i><em class="ts-2"><?php echo $this->_var['lang']['btn_collect']; ?></em></span>
				</div>
			</section>

			<section class="goods-price padding-all b-color-f">
				<p class="p-price">
					<span class="t-first">
						<?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?>
						<?php echo $this->_var['goods']['promote_price']; ?>
						<?php else: ?>
						<?php echo $this->_var['goods']['shop_price_formated']; ?>
						<?php endif; ?>				
					</span>
					<em class="em-promotion">积分可抵现</em>
				</p>
				<?php if (goods.is__promote && $this->_var['goods']['gmt_end_time']): ?>
				
				<?php else: ?>
				<p class="p-market"style="color:red;">
					会员等级价格 <?php echo $this->_var['goods']['rank_price_formated']; ?>
				</p>
				<?php endif; ?>
				<?php if ($this->_var['cfg']['SHOW_MARKETPRICE']): ?>
				<p class="p-market">
					市场价 <del><?php echo $this->_var['goods']['market_price']; ?></del>
				</p>
				<?php endif; ?>
				<p class=" dis-box g-p-tthree m-top04">
					<span class="box-flex text-left"><?php echo $this->_var['lang']['sort_sales']; ?>：<?php echo $this->_var['sales_count']; ?></span>
					<span class="box-flex text-right">库存: <?php echo $this->_var['goods']['goods_number']; ?></span>
				</p>
			</section>
			<?php if ($this->_var['promotion']): ?>
			<section class="ect-margin-tb ect-margin-bottom0 ect-padding-tb goods-promotion ect-padding-lr n-activity-list b-color-f">
				<h5><?php echo $this->_var['lang']['activity']; ?></h5>
				<p class="ect-border-top ect-margin-tb">
					<?php $_from = $this->_var['promotion']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
					<?php if ($this->_var['item']['type'] == "snatch"): ?>
					<a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang']['snatch']; ?>"><i class="label tbqb"><?php echo $this->_var['lang']['snatch_act']; ?></i> [<?php echo $this->_var['lang']['snatch']; ?>]<i class="pull-right fa fa-angle-right"></i></a>
					<?php elseif ($this->_var['item']['type'] == "group_buy"): ?>
					<a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang']['group_buy']; ?>"><i class="label tuan"><?php echo $this->_var['lang']['group_buy_act']; ?></i> [<?php echo $this->_var['lang']['group_buy']; ?>]<?php echo $this->_var['item']['time']; ?><i class="pull-right fa fa-angle-right"></i></a>
					<?php elseif ($this->_var['item']['type'] == "auction"): ?>
					<a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang']['auction']; ?>"><i class="label pm"><?php echo $this->_var['lang']['auction_act']; ?></i> [<?php echo $this->_var['lang']['auction']; ?>]<i class="pull-right fa fa-angle-right"></i></a>
					<?php elseif ($this->_var['item']['type'] == "favourable"): ?>
					<a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?> <?php echo $this->_var['item']['act_name']; ?><?php echo $this->_var['item']['time']; ?>">
						<?php if ($this->_var['item']['act_type'] == 0): ?>
						<i class="label mz"><?php echo $this->_var['lang']['favourable_mz']; ?></i>
						<?php elseif ($this->_var['item']['act_type'] == 1): ?>
						<i class="label mj"><?php echo $this->_var['lang']['favourable_mj']; ?></i>
						<?php elseif ($this->_var['item']['act_type'] == 2): ?>
						<i class="label zk"><?php echo $this->_var['lang']['favourable_zk']; ?></i>
						<?php endif; ?><?php echo $this->_var['item']['act_name']; ?><?php echo $this->_var['item']['time']; ?> <i class="pull-right fa fa-angle-right"></i></a>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</p>
			</section>
			<?php endif; ?>
			
			<section class="padding-all b-color-f1 goods-service">
				<div class="dis-box">
					<div class="box-flex">
						<div class="dis-box">
							<p class="box-flex t-goods2">本服务由<?php echo $this->_var['shop_name']; ?>提供售后服务与产品支持</p>
							<i class="iconfont icon-102 goods-min-icon"></i>							
						</div>
						<div class="dis-box m-top08 g-r-rule goods-service-list">
							<p class="box-flex t-remark3">
								<em class="fl em-promotion"><i class="iconfont icon-weibiaoti11"></i></em><span class="fl">正品保证</span></p>
							<p class="box-flex t-remark3">
								<em class="fl em-promotion"><i class="iconfont icon-daifukuan"></i></em><span class="fl">货到付款</span></p>
							<p class="box-flex t-remark3">
								<em class="fl em-promotion"><i class="iconfont icon-7tianwuliyoutuihuo"></i></em><span class="fl">7天退货</span></p>
							<p class="box-flex t-remark3">
								<em class="fl em-promotion"><i class="iconfont icon-taobaojisutuikuan"></i></em><span class="fl">极速达</span></p>
						</div>
					</div>
				</div>
			</section>
			
			<?php if ($this->_var['volume_price_list']): ?>
			<section class="goods-num-box b-color-f text-c f-05 padding-all">
				<ul class=" dis-box goods-box-bor">
					<li class="box-flex goods-bor goods-b-n">优惠数量</li>
					<li class="box-flex goods-bor goods-b-n">优惠价格</li>
				</ul>
				<?php $_from = $this->_var['volume_price_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'volume');if (count($_from)):
    foreach ($_from AS $this->_var['volume']):
?>
				<ul class="dis-box">
					<li class="box-flex goods-bor"><?php echo $this->_var['volume']['number']; ?></li>
					<li class="box-flex goods-bor"><?php echo $this->_var['volume']['price']; ?></li>
				</ul>				
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</section>			
			<?php endif; ?>
			
			<?php echo $this->fetch('library/goods_fittings.lbi'); ?>
			<form action="javascript:addToCart(<?php echo $this->_var['goods']['goods_id']; ?>)" method="post" name="ECS_FORMBUY" id="ECS_FORMBUY">
				<section class="goods-more-a">
					<!--  <a class="ect-padding-lr ect-padding-tb" href="<?php echo url('goods/info',array('id'=>$this->_var['goods']['goods_id']));?>"><span class="Text"><?php echo $this->_var['lang']['goods_brief']; ?></span> <span class="pull-right"><i class="fa fa-chevron-right"></i></span></a> 
      <a class="ect-padding-lr ect-padding-tb" href="<?php echo url('goods/comment_list',array('id'=>$this->_var['goods']['goods_id']));?>"><span class="Text"><?php echo $this->_var['lang']['goods_comment']; ?></span> <span class="pull-right"><span class="ect-color"><?php echo $this->_var['comments']['count']; ?></span><?php echo $this->_var['lang']['comment_num']; ?> <span class="ect-color"><?php echo $this->_var['comments']['favorable']; ?>%</span><?php echo $this->_var['lang']['favorable_comment']; ?> <i class="fa fa-chevron-right"></i></span></a>  -->
				</section>
				
				<section class="m-top10 padding-all b-color-f goods-attr j-goods-attr j-show-div">
					<div class="dis-box">
						<label class="t-remark g-t-temark">已选</label>
						<div class="box-flex t-goods1 ">请选择</div>
						<span class="t-jiantou"><i class="iconfont icon-jiantou tf-180"></i></span>
					</div>
					
					<div class="mask-filter-div"></div>
					<div class="show-goods-attr j-filter-show-div ts-3 b-color-1">
						<section class="s-g-attr-title b-color-1  product-list-small">
							<div class="product-div">
								<img src="<?php echo $this->_var['goods']['goods_img']; ?>" alt="<?php echo $this->_var['goods']['goods_name']; ?>" class="product-list-img" />
								<div class="product-text n-right-box">
									<div class="dis-box">
										<h4 class="box-flex"><?php echo $this->_var['goods']['goods_style_name']; ?></h4>
										<i class="iconfont icon-guanbi show-div-guanbi"></i>
									</div>
									<p><span class="p-price t-first" id="ECS_GOODS_AMOUNT">
										<?php if ($this->_var['goods']['is_promote'] && $this->_var['goods']['gmt_end_time']): ?>
										<?php echo $this->_var['goods']['promote_price']; ?>
										<?php else: ?>
										<?php echo $this->_var['goods']['shop_price_formated']; ?>
										<?php endif; ?></span>
									</p>
									<p class="dis-box p-t-remark"><span class="box-flex">库存:<?php echo $this->_var['goods']['goods_number']; ?></span></p>
								</div>
							</div>
						</section>
						<section class="s-g-attr-con swiper-scroll b-color-f padding-all m-top1px">
							<div class="swiper-wrapper">
								<div class="swiper-slide">
									<?php $_from = $this->_var['specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('spec_key', 'spec');$this->_foreach['spec'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['spec']['total'] > 0):
    foreach ($_from AS $this->_var['spec_key'] => $this->_var['spec']):
        $this->_foreach['spec']['iteration']++;
?>
									<h4 class="t-remark"><?php echo $this->_var['spec']['name']; ?>：</h4>
									<ul class="select-one  <?php if ($this->_var['spec']['attr_type'] == 1): ?>j-get-one<?php else: ?>j-get-more<?php endif; ?> m-top10">
										<?php $_from = $this->_var['spec']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['value']):
?>
										<li class="ect-select dis-flex fl">
											<input type="<?php if ($this->_var['spec']['attr_type'] == 1): ?>radio<?php else: ?>checkbox<?php endif; ?>" name="spec_<?php echo $this->_var['spec_key']; ?>" value="<?php echo $this->_var['value']['id']; ?>" id="spec_value_<?php echo $this->_var['value']['id']; ?>" <?php if ($this->_var['key'] == 0): ?>checked<?php endif; ?> onclick="changePrice()" />
											<label class="ts-1 <?php if ($this->_var['key'] == 0): ?>active<?php endif; ?>" for="spec_value_<?php echo $this->_var['value']['id']; ?>"><?php echo $this->_var['value']['label']; ?></label>
										</li>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									</ul>
									<input type="hidden" name="spec_list" value="<?php echo $this->_var['key']; ?>" />
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

									<h4 class="t-remark">数量</h4>
									<div class="div-num dis-box m-top08">
										<a class="num-less" onClick="changePrice('1')"></a>
										<input class="box-flex" type="text" value="1" name="number" id="goods_number" autocomplete="off" />
										<a class="num-plus" onClick="changePrice('3')"></a>
									</div>
								</div>
							</div>
							<div class="swiper-scrollbar"></div>
						</section>
						<section class="ect-button-more dis-box padding-all">
							<a class="btn-cart box-flex show-div-guanbi n-btn-box"  type="button" onClick="addToCart(<?php echo $this->_var['goods']['goods_id']; ?>);">加入购物车</a>
							<a class="btn-submit box-flex" type="button" onClick="addToCart_quick(<?php echo $this->_var['goods']['goods_id']; ?>);">立即购买</a>
						</section>
			</form>
			</div>
			
			</section>
			
			<section class="m-top10 goods-evaluation">
				<a href="<?php echo url('goods/comment_list',array('id'=>$this->_var['goods']['goods_id']));?>">
					<div class="dis-box padding-all b-color-f  g-evaluation-title">
						<label class="t-remark g-t-temark">用户评价</label>
						<div class="box-flex t-goods1">好评率 <em class="t-first"><?php echo $this->_var['comments']['favorable']; ?>%</em></div>
						<div class="t-goods1"><em class="t-first"><?php echo $this->_var['goods']['comment_count']; ?></em><span class="t-jiantou"><?php echo $this->_var['comments']['count']; ?>人评论<i class="iconfont icon-jiantou tf-180"></i></span></div>
					</div>
				</a>
				<div class="m-top1px b-color-f g-evaluation-con">
					<?php $_from = $this->_var['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');$this->_foreach['com'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['com']['total'] > 0):
    foreach ($_from AS $this->_var['comment']):
        $this->_foreach['com']['iteration']++;
?>
					<?php if (($this->_foreach['com']['iteration'] - 1) < 5): ?>
					<div class="of-hidden evaluation-list padding-all-1 n-list-pl">
						<div class="of-hidden">
							<p class="fl"><span class="grade-star g-star-<?php echo $this->_var['comment']['comment_rank']; ?> fl"></span><em class="t-remark fl"><?php if ($this->_var['comment']['user_name']): ?><?php echo htmlspecialchars($this->_var['comment']['user_name']); ?><?php else: ?><?php echo $this->_var['lang']['anonymous']; ?><?php endif; ?></em></p>
							<p class="fr t-remark"><?php echo $this->_var['comment']['add_time']; ?></p>
						</div>
						<p class="clear m-top06 t-goods1"><?php echo $this->_var['comment']['content']; ?></p>
						<?php if ($this->_var['comment']['re_content']): ?>
						<p>
							<font class="f1"><?php echo $this->_var['lang']['admin_username']; ?></font><?php echo $this->_var['comment']['re_content']; ?></p>
						<?php endif; ?>
						<p style="display:none;" class="clear m-top08 t-remark">颜色分类：70cm、5144蓝色</p>
					</div>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					<!--<div class="of-hidden evaluation-list">
					<div class="of-hidden ">
						<p class="fl"><span class="grade-star g-star-5 fl"></span><em class="t-remark fl">s****y</em></p>
						<p class="fr t-remark">2015-08-09 10:46:43</p>
					</div>
					<p class="clear m-top10 t-goods1">很好看，大小刚刚好 很好看</p>
					<p class="clear m-top08 t-remark">颜色分类：70cm、5144蓝色</p>
					<div class="ect-button-more m-top10 dis-box">
						<a class="box-flex btn-default">有图评价</a>
						<a href="<?php echo url('goods/comment_list',array('id'=>$this->_var['goods']['goods_id']));?>" class="box-flex btn-default">全部评价</a>
					</div>
				</div>-->
				</div>
			</section>
			<!-- <section class="padding-all text-center t-remark2 xiangqing-1 ">
			<a href="<?php echo url('goods/info',array('id'=>$this->_var['goods']['goods_id']));?>" class="j-goodsinfo-div">点击查看商品详情</a>
		</section> -->

		</div>
		
		<div class="mask-filter-div"></div>
		<div class="goods-scoll-bg"></div>
		
		<script type="text/javascript">
			function showDiv() {
				document.getElementById('popDiv').style.display = 'block';
				document.getElementById('hidDiv').style.display = 'block';
				document.getElementById('cartNum').innerHTML = document.getElementById('goods_number').value;
				document.getElementById('cartPrice').innerHTML = document.getElementById('ECS_GOODS_AMOUNT').innerHTML;
			}

			function closeDiv() {
				document.getElementById('popDiv').style.display = 'none';
				document.getElementById('hidDiv').style.display = 'none';
			}
		</script>
		<div class="tipMask" id="hidDiv" style="display:none"></div>

		

	</div>
	<?php echo $this->fetch('library/new_page_footer_nav.lbi'); ?>

	<?php echo $this->fetch('library/new_page_footer.lbi'); ?>
</div>
<div class="n-goods-bg"></div>
<?php echo $this->fetch('goods_info.dwt'); ?>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<script type="text/javascript" src="__TPL__/js/lefttime.js"></script>
<script type="text/javascript" src="__TPL__/statics/js/layer.js"></script>
<script type="text/javascript">	
	/*倒计时*/
	var goods_id = <?php echo $this->_var['goods_id']; ?>;
	var goodsattr_style = 1;
	var gmt_end_time = 0;
	var day = "天";
	var hour = "小时";
	var minute = "分钟";
	var second = "秒";
	var end = "结束";
	var goodsId = <?php echo $this->_var['goods_id']; ?>;
	var now_time = <?php echo $this->_var['now_time']; ?>;
	var use_how_oos = <?php echo C('use_how_oos');?>;
$(function() {
  changePrice(2);
  //fixpng();
  try {onload_leftTime();}
  catch (e) {}
});

	function back_goods_number() {
		var goods_number = document.getElementById('goods_number').value;
		document.getElementById('back_number').value = goods_number;
	}
	/**
	 * 点选可选属性或改变数量时修改商品价格的函数
	 */
	function changePrice(type) {
		var qty = document.forms['ECS_FORMBUY'].elements['number'].value;
		//var qty = 0;

		if (type == 1) {
			qty--;
		}
		if (type == 3) {
			qty++;
		}
		if (qty <= 0) {
			qty = 1;
		}
		if (!/^[0-9]*$/.test(qty)) {
			qty = document.getElementById('back_number').value;
		}
		document.getElementById('goods_number').value = qty;
		var attr = getSelectedAttributes(document.forms['ECS_FORMBUY']);
		$.get('<?php echo url("goods/price");?>', {
			'id': goodsId,
			'attr': attr,
			'number': qty
		}, function(data) {
			changePriceResponse(data);
		}, 'json');
	}
	/**
	 * 接收返回的信息
	 */
	function changePriceResponse(res) {
		if (res.err_msg.length > 0) {
			alert(res.err_msg);
		} else {
			if (document.getElementById('ECS_GOODS_AMOUNT'))
				document.getElementById('ECS_GOODS_AMOUNT').innerHTML = res.result;
		}
	}

	
</script>
<script>
	$(function($) {
	
		var handler = function(e) { //禁止浏览器默认行为
			e.preventDefault();
		};


		/*弹出层方式*/
		$(".j-show-div").click(function() {
			document.addEventListener("touchmove", handler, false);
			$(".j-filter-show-div").addClass("show");
			$(".mask-filter-div").addClass("show");
		});
		/*关闭弹出层*/
		$(".mask-filter-div,.show-div-guanbi").click(function() {
			document.removeEventListener("touchmove", handler, false);
			$(".j-filter-show-div").removeClass("show");
			$(".mask-filter-div").removeClass("show");
			return false;
		});
		/*商品详情相册切换*/
		var swiper = new Swiper('.goods-photo', {
			paginationClickable: true,
			onInit: function(swiper) {
				document.getElementById("g-active-num").innerHTML = swiper.activeIndex + 1;
				document.getElementById("g-all-num").innerHTML = swiper.slides.length;
			},
			onSlideChangeStart: function(swiper) {
				document.getElementById("g-active-num").innerHTML = swiper.activeIndex + 1;
			}
		});
	});
</script>

		<script>
			$(function() {
					/*头部导航*/
					
	$(".j-nav-box").click(function() {
		$(".j-nav-content").toggleClass("active");
	});

/*导航弹框*/	
$(".icon-gengduo").click(function() {
					$(".goods-nav").toggleClass("active");
				});	
				
				
				$('.goods-header-nav-box li').click(function() {
					for (var i = 0; i < $('.goods-header-nav-box li').size(); i++) {
						if (this == $('.goods-header-nav-box li').get(i)) {
							$('.goods-header-nav-box li').eq(i).children('a').addClass('hover');
						} else {
							$('.goods-header-nav-box li').eq(i).children('a').removeClass('hover');
						}
					}
				})
			});
		</script>
		<script type="text/javascript">
var btn_buy = "确定";
var is_cancel = "取消";
var select_spe = "请选择商品属性";
var select_base = '请选择套餐基本件';
var select_shop = '请选择套餐商品';
var data_not_complete = '数据格式不完整';
var understock = '库存不足，请选择其他商品';

$(function(){
	//组合套餐tab切换
	var _tab = $('#cn_b h2');
	var _con = $('#cn_h blockquote');
	var _index = 0;
	_con.eq(0).show().siblings('blockquote').hide();
	_tab.css('cursor','pointer');
	_tab.click(function(){
		_index = _tab.index(this);
		_tab.eq(_index).removeClass('h2bg').siblings('h2').addClass('h2bg');
		_con.eq(_index).show().siblings('blockquote').hide();
	})
	//选择基本件
	$('.m_goods_body').click(function(){
		if($(this).prop('checked')){
			ec_group_addToCart($(this).attr('item'), <?php echo $this->_var['goods']['goods_id']; ?>); //基本件(组,主件)
		}else{
			ec_group_delInCart($(this).attr('item'), <?php echo $this->_var['goods']['goods_id']; ?>); //删除基本件(组,主件)
			display_Price($(this).attr('item'),$(this).attr('item').charAt($(this).attr('item').length-1));
		}
	})
	//变更选购的配件
	$('.m_goods_list').click(function(){
		//是否选择主件
		if(!$(this).parents('table').find('.m_goods_body').prop('checked')){
			alert(select_base);
			return false;
		}
		if($(this).prop('checked')){
			ec_group_addToCart($(this).attr('item'), $(this).val(),<?php echo $this->_var['goods']['goods_id']; ?>); //新增配件(组,配件,主件)
		}else{
			ec_group_delInCart($(this).attr('item'), $(this).val(),<?php echo $this->_var['goods']['goods_id']; ?>); //删除基本件(组,配件,主件)
			display_Price($(this).attr('item'),$(this).attr('item').charAt($(this).attr('item').length-1));
		}
	})
	//可以购买套餐的最大数量
	$(".combo_stock").keyup(function(){
		var group = $(this).parents('form').attr('name');
		getMaxStock(group);//根据套餐获取该套餐允许购买的最大数
	});
})

//允许购买套餐的最大数量
function getMaxStock(group){
	var obj = $('input[name="'+group+'_number"]').val();
	var original = parseInt(Number(obj.val()));
	var stock = $("."+group).eq(0).attr('stock');
	//是否是数字
	if(isNaN(original)){
		original = 1;
		obj.val(original);
	}
	
	//更新
	original = (original < 1)?1:original;
	stock = (stock < 1)?1:stock;
	if(original > stock){
		obj.val(stock);
	}
}

//统计套餐价格
function display_Price(_item,indexTab){
	var _size = $('.'+_item).size();
	var _amount_shop_price = 0;
	var _amount_spare_price = 0;
	indexTab = indexTab - 1;
	for(i=0; i<_size; i++){
		obj = $('.'+_item).eq(i);
		if(obj.prop('checked')){
			_amount_shop_price += parseFloat(obj.attr('data')); //原件合计
			_amount_spare_price += parseFloat(obj.attr('spare')); //优惠合计
		}
	}
	var tip_spare = $('.tip_spare:eq('+indexTab+')');//节省文本
	if(_amount_spare_price > 0){//省钱显示提示信息
		tip_spare.show();
		tip_spare.children('strong').text(_amount_spare_price);
	}else{
		tip_spare.hide();
	}
	//显示总价
	$('.combo_price:eq('+indexTab+')').text(_amount_shop_price);
}

//处理添加商品到购物车
function ec_group_addToCart(group,goodsId,parentId){
  var goods        = new Object();
  var spec_arr     = new Array();
  var fittings_arr = new Array();
  var number       = 1;
  var quick		   = 0;
  var group_item   = (typeof(parentId) == "undefined") ? goodsId : parseInt(parentId);

  goods.quick    = quick;
  goods.spec     = spec_arr;
  goods.goods_id = goodsId;
  goods.number   = number;
  goods.parent   = (typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
  goods.group = group + '_' + group_item;//组名
  
  	$.post('<?php echo url("flow/add_to_cart_combo");?>', {goods : $.toJSON(goods)}, function(data) {
		ec_group_addToCartResponse(data);
	}, 'json');  
	
}

//处理添加商品到购物车的反馈信息
function ec_group_addToCartResponse(result)
{
  if (result.error > 0)
  {
    // 如果需要缺货登记，跳转
    if (result.error == 2)
    {
      alert(understock);
	  cancel_checkboxed(result.group, result.goods_id);//取消checkbox
    }
    // 没选规格，弹出属性选择框
    else if (result.error == 6)
    {
      ec_group_openSpeDiv(result.message, result.group, result.goods_id, result.parent);
    }
    else
    {
      alert(result.message);
	  cancel_checkboxed(result.group, result.goods_id);//取消checkbox
    }
  }
  else
  {
	//处理Ajax数据
	var group = result.group.substr(0,result.group.lastIndexOf("_"));
	$("."+group).each(function(index){
		if($("."+group).eq(index).val()==result.goods_id){
			//主件显示价格、配件显示基本件+属性价
			var goods_price = (result.parent > 0) ? (parseFloat(result.fittings_price)+parseFloat(result.spec_price)):result.goods_price;
			$("."+group).eq(index).attr('data',goods_price);//赋值到文本框data参数
			$("."+group).eq(index).attr('stock',result.stock);//赋值到文本框stock参数
			$('.'+group+'_display').eq(index).text(goods_price);//前台显示
		}
	});
	//getMaxStock(group);//根据套餐获取该套餐允许购买的最大数
	display_Price(group,group.charAt(group.length-1));//显示套餐价格
  }
}

//处理删除购物车中的商品
function ec_group_delInCart(group,goodsId,parentId){
  var goods        = new Object();
  var group_item   = (typeof(parentId) == "undefined") ? goodsId : parseInt(parentId);

  goods.goods_id = goodsId;
  goods.parent   = (typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
  goods.group = group + '_' + group_item;//组名

  	$.post('<?php echo url("flow/del_in_cart_combo");?>', {goods : $.toJSON(goods)}, function(data) {
		ec_group_delInCartResponse(data);
	}, 'json');  
}

//处理删除购物车中的商品的反馈信息
function ec_group_delInCartResponse(result)
{
	var group = result.group;
	if (result.error > 0){
		alert(data_not_complete);
	}else if(result.parent == 0){
		$('.'+group).attr("checked",false);
	}
	display_Price(group,group.charAt(group.length-1));//显示套餐价格
}

//生成属性选择层
function ec_group_openSpeDiv(message, group, goods_id, parent) 
{
  var _id = "speDiv";
  var m = "mask";
  if (docEle(_id)) document.removeChild(docEle(_id));
  if (docEle(m)) document.removeChild(docEle(m));
  //计算上卷元素值
  var scrollPos; 
  if (typeof window.pageYOffset != 'undefined') 
  { 
    scrollPos = window.pageYOffset; 
  } 
  else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') 
  { 
    scrollPos = document.documentElement.scrollTop; 
  } 
  else if (typeof document.body != 'undefined') 
  { 
    scrollPos = document.body.scrollTop; 
  }

  var i = 0;
  var sel_obj = document.getElementsByTagName('select');
  while (sel_obj[i])
  {
    sel_obj[i].style.visibility = "hidden";
    i++;
  }

  // 新激活图层
  var newDiv = document.createElement("div");
  newDiv.id = _id;
  newDiv.style.position = "fixed";
  newDiv.style.zIndex = "10000";
  //newDiv.style.width = "26rem";
  //newDiv.style.height = "28rem";
  //newDiv.style.top = (parseInt(scrollPos + 200)) + "px";
  newDiv.style.bottom = "0"; 
  newDiv.style.left = "0"; 
  newDiv.style.right = "0"; 
  //newDiv.style.marginLeft = "-13rem";
  newDiv.style.overflow = "hidden"; 
  newDiv.style.background = "#FFF";
  //newDiv.style.border = "3px solid #59B0FF";
  newDiv.style.padding = "1.3rem 1.3rem 0rem 1.3rem";
  newDiv.style.boxSizing = "border-box";

  //生成层内内容
  newDiv.innerHTML = '<div class="dis-box"><h4 class="n-goods-tit box-flex">' + select_spe + "</h4><a href='javascript:ec_group_cancel_div(\"" + group + "\"," + goods_id + ")' class='box-flex text-right'><i class='iconfont icon-guanbi show-div-guanbi'></i></a></div>";

  for (var spec = 0; spec < message.length; spec++)
  {
      newDiv.innerHTML += '<h6 class="n-goods-cont-tit">' +  message[spec]['name'] + '</h6>';

      if (message[spec]['attr_type'] == 1)
      {
        for (var val_arr = 0; val_arr < message[spec]['values'].length; val_arr++)
        {
          if (val_arr == 0)
          {
            newDiv.innerHTML += "<div class='n-goods-list-cont'><input  type='radio' name='spec_" + message[spec]['attr_id'] + "' value='" + message[spec]['values'][val_arr]['id'] + "' id='spec_value_" + message[spec]['values'][val_arr]['id'] + "' checked /><label>" + message[spec]['values'][val_arr]['label'] + '</label><em>(' + message[spec]['values'][val_arr]['format_price'] + ')</em></div>';
          }
          else
          {
            newDiv.innerHTML += "<div class='n-goods-list-cont'><input  type='radio' name='spec_" + message[spec]['attr_id'] + "' value='" + message[spec]['values'][val_arr]['id'] + "' id='spec_value_" + message[spec]['values'][val_arr]['id'] + "' /><label>" + message[spec]['values'][val_arr]['label'] + '</label><em> (' + message[spec]['values'][val_arr]['format_price'] + ')</em></div>';      
          }
        } 
        newDiv.innerHTML += "<input type='hidden' name='spec_list' value='" + val_arr + "' />";
      }
      else
      {
        for (var val_arr = 0; val_arr < message[spec]['values'].length; val_arr++)
        {
          newDiv.innerHTML += "<div class='n-goods-list-cont'><input  type='checkbox' name='spec_" + message[spec]['attr_id'] + "' value='" + message[spec]['values'][val_arr]['id'] + "' id='spec_value_" + message[spec]['values'][val_arr]['id'] + "' /><label>" + message[spec]['values'][val_arr]['label'] + '</label><em> (' + message[spec]['values'][val_arr]['format_price'] + ')</em></div>';     
        }
        newDiv.innerHTML += "<input type='hidden' name='spec_list' value='" + val_arr + "' />";
      }
  }
  newDiv.innerHTML += "<br /><div class='dis-box  b-color-f n-goods-btn'><a class='f6 btn-submit2 box-flex' href='javascript:ec_group_cancel_div(\"" + group + "\"," + goods_id + ")' class='f6' >" + is_cancel + "</a><a href='javascript:ec_group_submit_div(\"" + group + "\"," + goods_id + "," + parent + ")' class='f6 btn-submit box-flex' >" + btn_buy + "</a></div>";
  document.body.appendChild(newDiv);


  // mask图层
  var newMask = document.createElement("div");
  newMask.id = m;
  newMask.style.position = "absolute";
  newMask.style.zIndex = "9999";
  newMask.style.width = document.body.scrollWidth + "px";
  newMask.style.height = document.body.scrollHeight + "px";
  newMask.style.top = "0px";
  newMask.style.left = "0px";
  newMask.style.background = "#000";
  newMask.style.filter = "alpha(opacity=30)";
  newMask.style.opacity = "0.40";
  document.body.appendChild(newMask);
} 

//获取选择属性后，再次提交到购物车
function ec_group_submit_div(group, goods_id, parentId) 
{
  var goods        = new Object();
  var spec_arr     = new Array();
  var fittings_arr = new Array();
  var number       = 1;
  var input_arr      = document.getElementById('speDiv').getElementsByTagName('input'); //by mike
  var quick		   = 1;

  var spec_arr = new Array();
  var j = 0;

  for (i = 0; i < input_arr.length; i ++ )
  {
    var prefix = input_arr[i].name.substr(0, 5);

    if (prefix == 'spec_' && (
      ((input_arr[i].type == 'radio' || input_arr[i].type == 'checkbox') && input_arr[i].checked)))
    {
      spec_arr[j] = input_arr[i].value;
      j++ ;
    }
  }

  goods.quick    = quick;
  goods.spec     = spec_arr;
  goods.goods_id = goods_id;
  goods.number   = number;
  goods.parent   = (typeof(parentId) == "undefined") ? 0 : parseInt(parentId);
  goods.group    = group;//组名

	$.post('<?php echo url("flow/add_to_cart_combo");?>', {goods : $.toJSON(goods)}, function(data) {
		ec_group_addToCartResponse(data);
	}, 'json');  
  document.body.removeChild(docEle('speDiv'));
  document.body.removeChild(docEle('mask'));

  var i = 0;
  var sel_obj = document.getElementsByTagName('select');
  while (sel_obj[i])
  {
    sel_obj[i].style.visibility = "";
    i++;
  }

}

//关闭mask和新图层的同时取消选择
function ec_group_cancel_div(group, goods_id){
  document.body.removeChild(docEle('speDiv'));
  document.body.removeChild(docEle('mask'));

  var i = 0;
  var sel_obj = document.getElementsByTagName('select');
  while (sel_obj[i])
  {
    sel_obj[i].style.visibility = "";
    i++;
  }
  cancel_checkboxed(group, goods_id);//取消checkbox
}

/*
*套餐提交到购物车 by mike
*/
function addMultiToCart(group,goodsId){
	var goods     = new Object();
	var number    = $('input[name="'+group+'_number"]').val();
	
	goods.group = group;
	goods.goods_id = goodsId;
	goods.number = (number < 1) ? 1:number;
	
	//判断是否勾选套餐
	if(!$("."+group).is(':checked')){
		alert(select_shop);
		return false;	
	}
	
	
	$.post('<?php echo url("flow/add_to_cart_group");?>', {goods : $.toJSON(goods)}, function(data) {
		addMultiToCartResponse(data);
	}, 'json');  
}

//回调
function addMultiToCartResponse(result){
	if(result.error > 0){
		alert(result.message);
	}else{
		window.location.href = "<?php echo url('flow/cart');?>";
	}
}

//取消选项
function cancel_checkboxed(group, goods_id){
	//取消选择
	group = group.substr(0,group.lastIndexOf("_"));
	$("."+group).each(function(index){
		if($("."+group).eq(index).val()==goods_id){
			$("."+group).eq(index).attr("checked",false);			  
		}
	});
}

/*
//sleep
function sleep(d){
	for(var t = Date.now();Date.now() - t <= d;);
}
*/
</script>

</body>

</html>