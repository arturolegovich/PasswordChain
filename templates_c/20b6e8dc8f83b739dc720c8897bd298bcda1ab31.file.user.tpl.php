<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:35:23
         compiled from "./templates/user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11550487526044c88bb94c04-37605347%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '20b6e8dc8f83b739dc720c8897bd298bcda1ab31' => 
    array (
      0 => './templates/user.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11550487526044c88bb94c04-37605347',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_errors' => 0,
    'urlHome' => 0,
    'file_ext' => 0,
    'hide_login' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c88be9dee4_13212403',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c88be9dee4_13212403')) {function content_6044c88be9dee4_13212403($_smarty_tpl) {?>﻿

<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['file_errors']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('action'=>"performing login"), 0);?>

    <p>
        Логин и пароль 
        <strong title="Uppercase and lowercase letters are treated differently.">
        чувствительны к регистру</strong>.
    </p>
    <div class="user">
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
user<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" method="post">
            <div class="row">
                <span class="label">Пользователь:</span>
                <span class="formw">
                    <input name="user" type="<?php if ($_smarty_tpl->tpl_vars['hide_login']->value){?>password<?php }else{ ?>text<?php }?>" id="user" maxlength="255" size="30" value="<?php echo $_smarty_tpl->tpl_vars['user']->value;?>
" />
                </span>
            </div>
            <div class="row">
                <span class="label">Пароль:</span>
                <span class="formw">
                    <input name="key" type="password" id="key" maxlength="255" size="30" value="" />
                </span>
            </div>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Вход" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
<?php }} ?>