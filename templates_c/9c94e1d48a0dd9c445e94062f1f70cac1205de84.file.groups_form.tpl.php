<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:20
         compiled from "./templates/groups_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18939670196044c270de0623-46135914%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c94e1d48a0dd9c445e94062f1f70cac1205de84' => 
    array (
      0 => './templates/groups_form.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18939670196044c270de0623-46135914',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action' => 0,
    'urlHome' => 0,
    'file_ext' => 0,
    'groupid' => 0,
    'group_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c270ec9955_34858908',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c270ec9955_34858908')) {function content_6044c270ec9955_34858908($_smarty_tpl) {?>﻿

    <span class="heading1"><?php if ($_smarty_tpl->tpl_vars['action']->value=="add"){?>Добавление<?php }else{ ?>Редактирование<?php }?> группы:</span>
    <div class="entry">
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" method="post" name="settings">
            <input type="hidden" name="action" value="save" />
            <input type="hidden" name="groupid" value="<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
" />
            <div class="row">
                <label>Название группы: </label>
				
                <input name="group_title" type="text" id="key" maxlength="255" size="30" value="<?php if (isset($_smarty_tpl->tpl_vars['group_title']->value)){?><?php echo $_smarty_tpl->tpl_vars['group_title']->value;?>
<?php }?>" />
			</div>
            <div class="row">
                <label class="col1"></label>
                <span class="button">
                    <input name="submit" type="submit" value="Сохранить" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div><?php }} ?>