
<?php echo $this->fetch('library/new_page_header.lbi'); ?>
<header class="dis-box header-menu b-color color-whie"><a class="" href="javascript:history.go(-1)"><i class="iconfont icon-jiantou"></i></a>
	<h3 class="box-flex">消息中心</h3>
	<p><i class="iconfont icon-pailie j-nav-box"></i></p>
</header>
<div class="j-nav-content">
	<ul class="dis-box new-footer-box">
		<li class="box-flex">
			<a href="<?php echo url('index/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/home.png"></i><span>首页</span></a>
		</li>
		<li class="box-flex">
			<a href="<?php echo url('category/top_all');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/cate.png"></i><span>分类</span></a>
			<li class="box-flex"><a href="javascript:;" class="nav-cont j-search-input"><i class="nav-box"><img src="__TPL__/statics/img/search.png"></i><span>搜索</span></a></li>
			<li class="box-flex"><a href="<?php echo url('flow/cart');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/flow.png"></i><span>购物车</span></a></li>
			<li class="box-flex"><a href="<?php echo url('user/index');?>" class="nav-cont"><i class="nav-box"><img src="__TPL__/statics/img/user.png"></i><span>用户中心</span></a></li>
	</ul>
</div>
<?php if ($this->_var['message_list']): ?>
    <div class="user-account-detail" >
      <ul>
        <?php $_from = $this->_var['message_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'msg');$this->_foreach['message_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['message_list']['total'] > 0):
    foreach ($_from AS $this->_var['msg']):
        $this->_foreach['message_list']['iteration']++;
?>
        <li class="single_item">       
        	<div class="dis-box new-msg-title">
        		<div class="box-flex">
        			<h4><?php echo $this->_var['msg']['msg_type']; ?>:</h4>
        			<span><?php echo $this->_var['msg']['msg_time']; ?></span>
        		</div>
        		<div class="box-flex">
        			<a onclick="if (!confirm('<?php echo $this->_var['lang']['confirm_remove_msg']; ?>')) return false;" href="<?php echo $this->_var['msg']['url']; ?>" style="float: right; outline: none;"><i class="iconfont icon-xiao10 fr"></i></a>
        		</div>
            </div>
            <h5> <?php echo $this->_var['msg']['msg_title']; ?></h5>
            <p> <?php echo $this->_var['msg']['msg_content']; ?> </p>
            <?php if ($this->_var['msg']['re_msg_content']): ?>
            <table>
            	
              <tr>
                <td><div class="msg-jiantou tf-45"></div> <label><?php echo $this->_var['lang']['shopman_reply']; ?><em>(<?php echo $this->_var['msg']['re_msg_time']; ?>)</em></label>
                  <?php echo $this->_var['msg']['re_msg_content']; ?> </td>
              </tr>
            </table>
            <?php endif; ?>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </ul>
    </div>
<?php echo $this->fetch('library/page.lbi'); ?>

<?php else: ?>
<div class="user-account-detail" >
	<div class="user-account-message">
		目前还没有消息
	</div>
  <ul class="ect-bg-colorf" id="J_ItemList">
    
    <a href="javascript:;" style="text-align:center" class="get_more"></a>
  </ul>
</div>
<?php endif; ?>

</div>
<?php echo $this->fetch('library/new_search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?> 
<?php if (empty ( $this->_var['order_id'] )): ?>
<script type="text/javascript">
get_asynclist('<?php echo url("user/msg_list");?>' , '__TPL__/images/loader.gif');
</script>
<?php endif; ?>
</body></html>