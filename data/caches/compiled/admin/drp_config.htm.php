<?php if ($this->_var['full_page']): ?>
<!-- $Id: users_list.htm 15617 2009-02-18 05:18:00Z sunxiaodong $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<form method="POST" action="" name="listForm">

<!-- start users list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellpadding="4" cellspacing="1">
    <?php $_from = $this->_var['info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'li');if (count($_from)):
    foreach ($_from AS $this->_var['li']):
?>
    <tr>
        <td style="width:20%;"><?php echo $this->_var['li']['name']; ?></td>
        <td style="width:40%;">
            <?php if ($this->_var['li']['type'] == 'textarea'): ?>
                <textarea cols="40" rows="5" name="data[<?php echo $this->_var['li']['keyword']; ?>]"><?php echo $this->_var['li']['value']; ?></textarea>

            <?php elseif ($this->_var['li']['type'] == 'text'): ?>
                <input type="text" size="40" name="data[<?php echo $this->_var['li']['keyword']; ?>]" value="<?php echo $this->_var['li']['value']; ?>">
            <?php elseif ($this->_var['li']['type'] == 'radio'): ?>
                <?php $_from = $this->_var['li']['centent']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'c');if (count($_from)):
    foreach ($_from AS $this->_var['c']):
?>
                    <input type="radio" name="data[<?php echo $this->_var['li']['keyword']; ?>]" value="<?php echo $this->_var['c']; ?>" <?php if ($this->_var['li']['value'] == $this->_var['c']): ?>checked<?php endif; ?> ><?php echo $this->_var['c']; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>

        </td>
        <td><span><?php echo $this->_var['li']['remarks']; ?></span></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr>
        <td ></td>
        <td><input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" class="button" /></td>
        <td></td>
    </tr>

</table>
<?php if ($this->_var['full_page']): ?>
</div>
<!-- end users list -->
</form>

<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>