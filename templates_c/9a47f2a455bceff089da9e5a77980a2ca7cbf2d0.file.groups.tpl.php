<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:20
         compiled from "./templates/groups.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19864886566044c2708579c6-12469884%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a47f2a455bceff089da9e5a77980a2ca7cbf2d0' => 
    array (
      0 => './templates/groups.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19864886566044c2708579c6-12469884',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_errors' => 0,
    'groups_form' => 0,
    'urlHome' => 0,
    'file_ext' => 0,
    'urlImages' => 0,
    'groups' => 0,
    'class' => 0,
    'id' => 0,
    'group' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c270b9cd15_82203859',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c270b9cd15_82203859')) {function content_6044c270b9cd15_82203859($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include './libs/smarty-3.1.13/plugins/function.cycle.php';
?>﻿

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['file_errors']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('action'=>"saving groups"), 0);?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['groups_form']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('action'=>"add"), 0);?>

    <hr />
    <table class="center">
        <tr>
    	   <th width="65%">Группы</th>
    	   <th width="15%">Редактирование</th>
    	   <th width="15%">Удаление</th>
    	   <th width="15%">Экспорт</th>
    	</tr>
        <tr class="entryeven">
            <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=-1">Все</a></td>
            <td>&nbsp;</td>
    	    <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=delete&groupid=-1" title="Удалить группу"
                ><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
drop.gif" alt="Удаление" width="16" height="16"
                /></a></td>
    	    <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?output=xml&groupid=-1" title="Экспорт группы"
                ><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
xxml.gif" alt="Экспорт" width="16" height="16"
                /></a></td>
        </tr>
        <?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['group']->key;
?>
    	<?php echo smarty_function_cycle(array('values'=>"odd,even",'assign'=>'class'),$_smarty_tpl);?>

        <tr class="entry<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
">
            <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['group']->value;?>
</a></td>
            <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=edit&groupid=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" title="Редактировать группу"
                ><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
edit.gif" alt="Редактирование" width="16" height="16"
                /></a></td>
    	    <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=delete&groupid=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" title="Удалить группу"
                ><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
drop.gif" alt="Удаление" width="16" height="16"
                /></a></td>
    	    <td><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?output=xml&groupid=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" title="Экспорт группу"
                ><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
xxml.gif" alt="Экспорт" width="16" height="16"
                /></a></td>
        </tr>
    	<?php } ?>
    </table>
	<hr />
    <span class="heading1">Импорт записей:</span>
	<div class="settings">
	    <span class="clear">&nbsp;</span>
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" enctype="multipart/form-data" method="post" name="filename">
            <input type="hidden" name="action" value="import" />
            XML файл:&nbsp;
            <input class="text_area" name="importfile" type="file" />
            <span class="clear">&nbsp;</span>
            <input class="button" type="submit" value="Загрузить файл" />
        </form>
    </div>

<?php }} ?>