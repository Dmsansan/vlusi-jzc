<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>
<!-- 订单列表 -->
<form method="post" action="order.php?act=operate" name="listForm" onsubmit="return check()">
  <div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" /><a href="javascript:listTable.sort('order_sn', 'DESC'); "><?php echo $this->_var['lang']['service_sn']; ?></a><?php echo $this->_var['sort_order_sn']; ?>
    </th>
    <th><a href="#"><?php echo $this->_var['lang']['service_type_name']; ?></a><?php echo $this->_var['sort_order_time']; ?></th>
    <th><a href="#"><?php echo $this->_var['lang']['apply_time']; ?></a><?php echo $this->_var['sort_order_time']; ?></th>
    <th><a href="#"><?php echo $this->_var['lang']['should_returns']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>
    <th><a href="#"><?php echo $this->_var['lang']['real_returns']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>
    <th><a href="#"><?php echo $this->_var['lang']['return_nums']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>
    <!--<th><a href="#"><?php echo $this->_var['lang']['consignee']; ?></a><?php echo $this->_var['sort_consignee']; ?></th>-->
    <!--<th><a href="#">签收时间</a><?php echo $this->_var['sort_total_fee']; ?></th>-->
    <th><a href="#"><?php echo $this->_var['lang']['all_status']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>
    <th><a href="#"><?php echo $this->_var['lang']['buyer']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>
    <!--<th><a href="#"><?php echo $this->_var['lang']['is_check']; ?></a><?php echo $this->_var['sort_total_fee']; ?></th>-->
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = $this->_var['order_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('okey', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['okey'] => $this->_var['order']):
?>
  <tr>
    <td valign="top" nowrap="nowrap"><input type="checkbox" name="checkboxes" value="<?php echo $this->_var['order']['order_sn']; ?>" /><a href="aftermarket.php?act=aftermarket_info&ret_id=<?php echo $this->_var['order']['ret_id']; ?>&rec_id=<?php echo $this->_var['order']['rec_id']; ?>" id="order_<?php echo $this->_var['okey']; ?>"><?php echo $this->_var['order']['service_sn']; ?><?php if ($this->_var['order']['extension_code'] == "group_buy"): ?><br /><div align="center"><?php echo $this->_var['lang']['group_buy']; ?></div><?php elseif ($this->_var['order']['extension_code'] == "exchange_goods"): ?><br /><div align="center"><?php echo $this->_var['lang']['exchange_goods']; ?></div><?php endif; ?></a></td>
    
    <td><?php echo $this->_var['order']['service_type']; ?></td>
    <td><?php echo $this->_var['order']['add_time']; ?></td>
    <td align="left" valign="top"><a href="mailto:<?php echo $this->_var['order']['email']; ?>"><?php echo $this->_var['order']['should_return']; ?></a></td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo $this->_var['order']['actual_return']; ?></td>
    <td align="right" valign="top" nowrap="nowrap"><?php echo $this->_var['order']['back_num']; ?></td>
    <!--<td align="right" valign="top" nowrap="nowrap"><?php echo htmlspecialchars($this->_var['order']['consignee']); ?></a><?php if ($this->_var['order']['tel']): ?> [TEL: <?php echo htmlspecialchars($this->_var['order']['tel']); ?>]<?php endif; ?> <br /><?php echo htmlspecialchars($this->_var['order']['address']); ?></td>-->
    <!-- <td align="right" valign="top" nowrap="nowrap"><?php echo $this->_var['order']['sign_time']; ?></td>-->
    <td align="center" valign="top" nowrap="nowrap">
        <?php echo $this->_var['lang']['rf'][$this->_var['order']['return_status']]; ?>,<?php echo $this->_var['lang']['ff'][$this->_var['order']['refund_status']]; ?>
    </td> 
    <td align="center" valign="top" nowrap="nowrap"><?php echo $this->_var['order']['apply_user']; ?></td>
    <!--<td align="center" valign="top" nowrap="nowrap"><img src="images/<?php if ($this->_var['order']['is_check']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'check_service', <?php echo $this->_var['order']['ret_id']; ?>)" /></td>-->
    <td align="center" valign="top"  nowrap="nowrap">
     <a href="aftermarket.php?act=aftermarket_info&ret_id=<?php echo $this->_var['order']['ret_id']; ?>&rec_id=<?php echo $this->_var['order']['rec_id']; ?>"><?php echo $this->_var['lang']['detail']; ?></a>
     <?php if ($this->_var['order']['can_remove']): ?>
     <br /><a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['order']['order_id']; ?>, remove_confirm, 'remove_order')"><?php echo $this->_var['lang']['remove']; ?></a>
     <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>

<!-- 分页 -->
<table id="page-table" cellspacing="0">
  <tr>
    <td align="right" nowrap="true">
    <?php echo $this->fetch('page.htm'); ?>
    </td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
  </div>
  <div>
    <input name="confirm" type="submit" id="btnSubmit" value="<?php echo $this->_var['lang']['op_confirm']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="invalid" type="submit" id="btnSubmit1" value="<?php echo $this->_var['lang']['op_invalid']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="cancel" type="submit" id="btnSubmit2" value="<?php echo $this->_var['lang']['op_cancel']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="remove" type="submit" id="btnSubmit3" value="<?php echo $this->_var['lang']['remove']; ?>" class="button" disabled="true" onclick="this.form.target = '_self'" />
    <input name="batch" type="hidden" value="1" />
    <input name="order_id" type="hidden" value="" />
  </div>
</form>
<script language="JavaScript">
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
		listTable.query = "query";
    }

    /**
     * 搜索订单
     */
    function searchOrder()
    {
        listTable.filter['order_sn'] = Utils.trim(document.forms['searchForm'].elements['order_sn'].value);
        listTable.filter['consignee'] = Utils.trim(document.forms['searchForm'].elements['consignee'].value);
        listTable.filter['composite_status'] = document.forms['searchForm'].elements['status'].value;
        listTable.filter['page'] = 1;
        listTable.loadList();
    }

    function check()
    {
      var snArray = new Array();
      var eles = document.forms['listForm'].elements;
      for (var i=0; i<eles.length; i++)
      {
        if (eles[i].tagName == 'INPUT' && eles[i].type == 'checkbox' && eles[i].checked && eles[i].value != 'on')
        {
          snArray.push(eles[i].value);
        }
      }
      if (snArray.length == 0)
      {
        return false;
      }
      else
      {
        eles['order_id'].value = snArray.toString();
        return true;
      }
    }
    /**
     * 显示订单商品及缩图
     */
    var show_goods_layer = 'order_goods_layer';
    var goods_hash_table = new Object;
    var timer = new Object;

    /**
     * 绑定订单号事件
     *
     * @return void
     */
    function bind_order_event()
    {
        var order_seq = 0;
        while(true)
        {
            var order_sn = Utils.$('order_'+order_seq);
            if (order_sn)
            {
                order_sn.onmouseover = function(e)
                {
                    try
                    {
                        window.clearTimeout(timer);
                    }
                    catch(e)
                    {
                    }
                    var order_id = Utils.request(this.href, 'order_id');
                    show_order_goods(e, order_id, show_goods_layer);
                }
                order_sn.onmouseout = function(e)
                {
                    hide_order_goods(show_goods_layer)
                }
                order_seq++;
            }
            else
            {
                break;
            }
        }
    }
    listTable.listCallback = function(result, txt) 
    {
        if (result.error > 0) 
        {
            alert(result.message);
        }
        else 
        {
            try 
            {
                document.getElementById('listDiv').innerHTML = result.content;
                bind_order_event();
                if (typeof result.filter == "object") 
                {
                    listTable.filter = result.filter;
                }
                listTable.pageCount = result.page_count;
            }
            catch(e)
            {
                alert(e.message);
            }
        }
    }
    /**
     * 浏览器兼容式绑定Onload事件
     *
     */
    if (Browser.isIE)
    {
        window.attachEvent("onload", bind_order_event);
    }
    else
    {
        window.addEventListener("load", bind_order_event, false);
    }

    /**
     * 建立订单商品显示层
     *
     * @return void
     */
    function create_goods_layer(id)
    {
        if (!Utils.$(id))
        {
            var n_div = document.createElement('DIV');
            n_div.id = id;
            n_div.className = 'order-goods';
            document.body.appendChild(n_div);
            Utils.$(id).onmouseover = function()
            {
                window.clearTimeout(window.timer);
            }
            Utils.$(id).onmouseout = function()
            {
                hide_order_goods(id);
            }
        }
        else
        {
            Utils.$(id).style.display = '';
        }
    }

    /**
     * 显示订单商品数据
     *
     * @return void
     */
    function show_order_goods(e, order_id, layer_id)
    {
        create_goods_layer(layer_id);
        $layer_id = Utils.$(layer_id);
        $layer_id.style.top = (Utils.y(e) + 12) + 'px';
        $layer_id.style.left = (Utils.x(e) + 12) + 'px';
        if (typeof(goods_hash_table[order_id]) == 'object')
        {
            response_goods_info(goods_hash_table[order_id]);
        }
        else
        {
            $layer_id.innerHTML = loading;
            Ajax.call('order.php?is_ajax=1&act=get_goods_info&order_id='+order_id, '', response_goods_info , 'POST', 'JSON');
        }
    }

    /**
     * 隐藏订单商品
     *
     * @return void
     */
    function hide_order_goods(layer_id)
    {
        $layer_id = Utils.$(layer_id);
        window.timer = window.setTimeout('$layer_id.style.display = "none"', 500);
    }

    /**
     * 处理订单商品的Callback
     *
     * @return void
     */
    function response_goods_info(result)
    {
        if (result.error > 0)
        {
            alert(result.message);
            hide_order_goods(show_goods_layer);
            return;
        }
        if (typeof(goods_hash_table[result.content[0].order_id]) == 'undefined')
        {
            goods_hash_table[result.content[0].order_id] = result;
        }
        Utils.$(show_goods_layer).innerHTML = result.content[0].str;
    }
</script>


<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>