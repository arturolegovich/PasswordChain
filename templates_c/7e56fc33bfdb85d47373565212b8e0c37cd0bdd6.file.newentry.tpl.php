<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:20
         compiled from "./templates/newentry.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12689659176044c2706d0159-10921231%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7e56fc33bfdb85d47373565212b8e0c37cd0bdd6' => 
    array (
      0 => './templates/newentry.tpl',
      1 => 1614597384,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12689659176044c2706d0159-10921231',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_ext' => 0,
    'groupid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c2707809f6_67043145',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c2707809f6_67043145')) {function content_6044c2707809f6_67043145($_smarty_tpl) {?>﻿

    <a href="entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=edit&entryid=0&groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
" class="button" title="Добавление новой записи.">Добавить запись</a><?php }} ?>