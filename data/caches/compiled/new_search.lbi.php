<div class="search-div ts-3">
	<section class="search">
		<form action="<?php echo url('category/index');?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>" method="post">
		<div class="text-all dis-box j-text-all">
			<a class="a-icon-back j-close-search" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou is-left-font"></i></a>
			<div class="box-flex input-text">
				<input class="j-input-text" type="text" name="name" placeholder="<?php echo $this->_var['lang']['no_keywords']; ?>" id="newinput" autofocus="autofocus"/ >
				<i class="iconfont icon-guanbi2 is-null j-is-null"></i>
			</div>
			<button type="submit" class="btn-submit">搜索</button>
		</div>
		</form>
	</section>
	<section class="search-con">
		<div class="swiper-scroll history-search">
			<div class="swiper-wrapper">
			<div class="swiper-slide">
		<p>
			<label class="fl"><?php echo $this->_var['lang']['hot_search']; ?></label>
		</p>
		<ul class="hot-search a-text-more">
			<?php $_from = $this->_var['hot_search_keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'keyword');if (count($_from)):
    foreach ($_from AS $this->_var['keyword']):
?>
			<li class="w-3"><a href="<?php echo url('category/index', array('keywords'=>$this->_var[keyword]));?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>"><span><?php echo $this->_var['keyword']; ?></span></a></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<p class="hos-search">
			<label class="fl">最近搜索</label>
			<span class="fr" onclick="javascript:clearHistroy();"><i class="iconfont icon-xiao10 is-xiao10 jian-top fr"></i></span>
		</p>
		
			<ul class="hot-search a-text-more a-text-one" id="search_histroy">
				<?php $_from = $this->_var['search_histroy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'keyword');if (count($_from)):
    foreach ($_from AS $this->_var['keyword']):
?>
				<li><a href="<?php echo url('category/index', array('keywords'=>$this->_var[keyword]));?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>"><span><?php echo $this->_var['keyword']; ?></span></a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</ul>
			</div>
			</div>
			<div class="swiper-scrollbar"></div>
		</div>
	</section>
	<footer class="close-search j-close-search">
		点击关闭
	</footer>
</div>

<script type="text/javascript">
//设置cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function clearHistroy(){
	setCookie('ECS[keywords]', '', -1);
	document.getElementById("search_histroy").style.visibility = "hidden";
}
</script>