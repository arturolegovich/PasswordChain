<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:20
         compiled from "./templates/errors.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6317199866044c270bbe565-49544556%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '313899b697f7268c531f9c04fcca6147384ff0c1' => 
    array (
      0 => './templates/errors.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6317199866044c270bbe565-49544556',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'errors' => 0,
    'action' => 0,
    'error' => 0,
    'msgs' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c270db4ac3_42156483',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c270db4ac3_42156483')) {function content_6044c270db4ac3_42156483($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['errors']->value){?>

    <div class="error">
        <p>
            The following error<?php if ($_smarty_tpl->tpl_vars['errors']->value>1){?>s<?php }?> occurred while <?php echo $_smarty_tpl->tpl_vars['action']->value;?>
:
        </p>
        <ul>
        <?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['errors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value){
$_smarty_tpl->tpl_vars['error']->_loop = true;
?>
            <li>
                <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

            </li>
        <?php } ?>
        </ul>
    </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['msgs']->value){?>

    <div class="msgs">
        <p>
            The following message<?php if ($_smarty_tpl->tpl_vars['msgs']->value>1){?>s<?php }?> where returned while <?php echo $_smarty_tpl->tpl_vars['action']->value;?>
:
        </p>
        <ul>
<?php  $_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['msgs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['msg']->key => $_smarty_tpl->tpl_vars['msg']->value){
$_smarty_tpl->tpl_vars['msg']->_loop = true;
?>
            <li>
                <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

            </li>
<?php } ?>
        </ul>
    </div>
<?php }?>
<?php }} ?>