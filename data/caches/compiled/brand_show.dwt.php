<?php echo $this->fetch('library/page_header.lbi'); ?>
<link rel="stylesheet" href="__TPL__/css/brand.css">
<div class="con">
    <?php if ($this->_var['list']): ?>
    <div class="brand-main">
        <div class="brand-today">
            <div class="brand-today-img">
                <?php $_from = $this->_var['list']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'li');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['li']):
        $this->_foreach['list']['iteration']++;
?>
                <div class="top fr">

                    <div class="">
                        <a href="<?php echo $this->_var['li']['url']; ?>"> <img src="<?php echo $this->_var['li']['brand_logo']; ?>" alt="" /></a>
                    </div>
                    <span><?php echo $this->_var['li']['brand_desc']; ?></span>
                </div>
                <div class="brand-today-img-content fl ">
                    <div id="focus" class="focus goods-focus ect-padding-lr ect-margin-tb">
                        <div class="hd">
                            <ul>
                            </ul>
                        </div>
                        <div class="bd">
                            <ul id="Gallery">
                                <?php $_from = $this->_var['li']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'l');$this->_foreach['li'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['li']['total'] > 0):
    foreach ($_from AS $this->_var['l']):
        $this->_foreach['li']['iteration']++;
?>
                                <li><a href="<?php echo $this->_var['l']['url']; ?>"><img src="<?php echo $this->_var['l']['goods_thumb']; ?>" alt="" /></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
        <?php $_from = $this->_var['list']['center']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'li');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['li']):
        $this->_foreach['list']['iteration']++;
?>
        <div class="brand-today-img">
            <a href="<?php echo url('brand/goods_list',array('id'=>$this->_var['li']['brand_id']));?>">
            <div class="brand-today-img-text fl">
                <p>今日推荐</p><span>Recommendationof day</span>
            </div>
            <div class="brand-today-img-content fr">
                <img src="<?php echo $this->_var['li']['brand_banner']; ?>" alt="" />
            </div>
            <div class="brand-today-img-logo fl">
                <img src="<?php echo $this->_var['li']['brand_logo']; ?>" alt="" />
            </div>
            </a>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <div class="brand-lattice">
            <ul>
                <?php $_from = $this->_var['list']['list1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'li');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['li']):
        $this->_foreach['list']['iteration']++;
?>
                <li>
                    <a href="<?php echo url('brand/goods_list',array('id'=>$this->_var['li']['brand_id']));?>">
                    <img src="<?php echo $this->_var['li']['goods']['0']['goods_thumb']; ?>" alt="" class="brand-lattice-bg" />
                    <img src="<?php echo $this->_var['li']['brand_logo']; ?>" alt="" class="brand-lattice-logo" />
                    <span><?php echo $this->_var['li']['brand_name']; ?></span>
                    </a>
			    </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>

        <div class="brand-list">
            <ul>
                <?php $_from = $this->_var['list']['list2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'br');$this->_foreach['brand'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['brand']['total'] > 0):
    foreach ($_from AS $this->_var['br']):
        $this->_foreach['brand']['iteration']++;
?>
                <li><a href="<?php echo $this->_var['br']['url']; ?>"><img src="<?php echo $this->_var['br']['brand_logo']; ?>" alt="" /></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                <li><a href="<?php echo url('brand/nav');?>"><img src="__TPL__/images/brand-list-img2.gif" alt="" /></a></li>
            </ul>
        </div>

    </div>
</div>
    <?php endif; ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body></html>
<script type="text/javascript" src="__TPL__/js/TouchSlide.1.1.js"></script>
<script type="text/javascript">
$(function(){
    /*banner滚动图片*/
    TouchSlide({
      slideCell : "#focus",
      titCell : ".hd ul", // 开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
      mainCell : ".bd ul",
      effect : "leftLoop",
      autoPlay : true, // 自动播放
      autoPage : true, // 自动分页
      delayTime: 200, // 毫秒；切换效果持续时间（执行一次效果用多少毫秒）
      interTime: 2500, // 毫秒；自动运行间隔（隔多少毫秒后执行下一个效果）
      switchLoad : "_src" // 切换加载，真实图片路径为"_src"
    });
});
</script>
<style>
.ect-padding-lr{margin:0 !important;padding:0 !important;}
.ect-padding-lr img{width:100%;height:auto;margin:0 !important;padding:0 !important;}
.brand-main{margin:0;}
</style>