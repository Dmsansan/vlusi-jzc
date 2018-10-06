<!-- $Id: mount_card.htm 15544 2018-09-28 15:54:28Z siss $ -->
<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>
<div class="form-div">
  <form action="javascript:searchArticle()" name="searchForm">
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <?php echo $this->_var['lang']['amount_title']; ?> <input type="text" name="keyword" id="keyword" />
    <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="amount_card.php?act=batch_remove" name="listForm">
<!-- start cat list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
      <a href="javascript:listTable.sort('amount_id'); "><?php echo $this->_var['lang']['amount_id']; ?></a><?php echo $this->_var['sort_amount_id']; ?></th>
    <th><a href="javascript:listTable.sort('amount_list'); "><?php echo $this->_var['lang']['amount_list']; ?></a><?php echo $this->_var['sort_amount_list']; ?></th>
    <th><a href="javascript:listTable.sort('amount_number'); "><?php echo $this->_var['lang']['amount_number']; ?></a><?php echo $this->_var['sort_amount_number']; ?></th>
    <th><a href="javascript:listTable.sort('amount_password'); "><?php echo $this->_var['lang']['amount_password']; ?></a><?php echo $this->_var['sort_amount_password']; ?></th>
    <th><a href="javascript:listTable.sort('status'); "><?php echo $this->_var['lang']['amount_status']; ?></a><?php echo $this->_var['sort_amount_status']; ?></th>
    <th><a href="javascript:listTable.sort('amount_count'); "><?php echo $this->_var['lang']['amount_count']; ?></a><?php echo $this->_var['sort_expry_date']; ?></th>
    <th><a href="javascript:listTable.sort('expry_date'); "><?php echo $this->_var['lang']['expry_date']; ?></a><?php echo $this->_var['sort_amount_count']; ?></th>
     <th><a href="javascript:listTable.sort('is_used'); "><?php echo $this->_var['lang']['is_used']; ?></a><?php echo $this->_var['sort_is_used']; ?></th>
    <th><a href="javascript:listTable.sort('add_date'); "><?php echo $this->_var['lang']['add_date']; ?></a><?php echo $this->_var['sort_add_date']; ?></th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = $this->_var['cards_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td align="center"><span><input name="checkboxes[]" type="checkbox" value="<?php echo $this->_var['list']['amount_id']; ?>"/><?php echo $this->_var['list']['amount_id']; ?></span></td>
    <td class="first-cell" align="center"><span><?php echo htmlspecialchars($this->_var['list']['amount_list']); ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['amount_number']; ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['amount_password']; ?></span></td>
    <td align="center"><span><?php if ($this->_var['list']['amount_status'] == 0): ?><?php echo $this->_var['lang']['down']; ?><?php else: ?><?php echo $this->_var['lang']['up']; ?><?php endif; ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['amount_count']; ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['expry_date']; ?></span></td>
    <td align="center"><span><?php if ($this->_var['list']['use_status'] == 0): ?><?php echo $this->_var['lang']['no']; ?> <?php else: ?><span style="color:red"><?php echo $this->_var['lang']['yes']; ?></span><?php endif; ?></span></td>
    <td align="center"><span><?php echo $this->_var['list']['add_date']; ?></span></td>
    <td align="center" nowrap="true"><span>
      <a href="amount_card.php?act=edit&id=<?php echo $this->_var['list']['amount_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><img src="images/icon_edit.gif" border="0" height="16" width="16" /></a>&nbsp;
      <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['amount_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>"><img src="images/icon_drop.gif" border="0" height="16" width="16"></a></span>
    </td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>
    <td colspan="2"><input type="submit" class="button" id="btnSubmit" value="<?php echo $this->_var['lang']['button_remove']; ?>" disabled="true" /></td>
    <td align="right" nowrap="true" colspan="8"><?php echo $this->fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>
<!-- end cat list -->
<script type="text/javascript" language="JavaScript">
  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  

  onload = function()
  {
    // 开始检查订单
    startCheckOrder();
  }

 /* 搜索文章 */
 function searchArticle()
 {
    listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }
 
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
