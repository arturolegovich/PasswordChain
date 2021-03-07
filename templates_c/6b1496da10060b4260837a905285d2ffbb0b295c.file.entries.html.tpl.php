<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:34:48
         compiled from "./templates/entries.html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6789433966044c868af32d6-60617873%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6b1496da10060b4260837a905285d2ffbb0b295c' => 
    array (
      0 => './templates/entries.html.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6789433966044c868af32d6-60617873',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'groupid' => 0,
    'urlHome' => 0,
    'file_ext' => 0,
    'search' => 0,
    'entries' => 0,
    'urlImages' => 0,
    'sort_flag' => 0,
    'sort_col' => 0,
    'class' => 0,
    'name' => 0,
    'value' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c86a6d5309_03459518',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c86a6d5309_03459518')) {function content_6044c86a6d5309_03459518($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include './libs/smarty-3.1.13/plugins/function.cycle.php';
?>﻿

<?php if ($_smarty_tpl->tpl_vars['groupid']->value==-2){?>
    <span class="heading1">Поиск</span>
    <div class="entry">
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" method="post">
            <input type="hidden" name="action" value="search" />
            <input type="hidden" name="groupid" value="-2" />
            <div class="rowe">
                <span class="label">Текст для поиска:</span>
                <span class="formw">
                    <input type="text" name="search" value="<?php if ($_smarty_tpl->tpl_vars['search']->value){?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Поиск" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <span class="clear">&nbsp;</span>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['entries']->value){?>
    <script src="js/entries.js" type="text/javascript"></script>
    <!-- Entries table -->
    <table id="entries" class="entries" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2" align="center" width="1%">Действие&nbsp;</th>
	        <th>
	        	Название
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=title&order=SORT_ASC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_asc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value!="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="title"){?>_n<?php }?>.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=title&order=SORT_DESC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_desc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value=="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="title"){?>_n<?php }?>.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
            <th>
				Логин
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=login&order=SORT_ASC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_asc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value!="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="login"){?>_n<?php }?>.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=login&order=SORT_DESC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_desc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value=="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="login"){?>_n<?php }?>.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
            <th>
				Пароль
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=password&order=SORT_ASC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_asc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value!="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="password"){?>_n<?php }?>.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
&sort=password&order=SORT_DESC#entries">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
s_desc<?php if ($_smarty_tpl->tpl_vars['sort_flag']->value=="SORT_ASC"||$_smarty_tpl->tpl_vars['sort_col']->value!="password"){?>_n<?php }?>.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
        </tr>
    <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['entry'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['name'] = 'entry';
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['entries']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['entry']['total']);
?>
        <?php echo smarty_function_cycle(array('values'=>"odd,even",'assign'=>'class'),$_smarty_tpl);?>

    	<tr class="entry<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
">
    	   <td align="center"><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=edit&entryid=<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['entryid'];?>
" title="Редактирование записей"><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
edit.gif" alt="Edit" width="16" height="16" /></a></td><td align="center"><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?action=delete&entryid=<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['entryid'];?>
&groupid=<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['groupid'];?>
" title="Delete Entry"><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
drop.gif" alt="Delete" width="16" height="16" /></a></td><td><?php if ($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['url']){?><a href="<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['url'];?>
" target="_blank"><?php }?><?php if ($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['icon']){?><img src="<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['site'];?>
" width="16" height="16" />&nbsp;<?php }?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['title'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php if ($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['url']){?></a><?php if ($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['params']){?><form action="<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['url'];?>
" method="post" onSubmit="return createTarget(this.target)" target="entry_<?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['entryid'];?>
"><?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['params']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['value']->key;
?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" /><?php } ?><input name="submit" type="submit" value="Login" /></form><?php }?><?php }?></td></td>
            <td>
				<?php if ($_smarty_tpl->tpl_vars['settings']->value['clipboard']){?><img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['login'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
');return false;" width="16" height="16" /><?php }?>
				<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['login'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
		    <td<?php if ($_smarty_tpl->tpl_vars['settings']->value['pwmask']){?> class="password<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
" OnMouseOver="this.className='passwordshow'" OnMouseOut="this.className='password<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
'"<?php }?>>
				<?php if ($_smarty_tpl->tpl_vars['settings']->value['clipboard']){?><img class="clipboardimg" src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['password'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
');return false;" width="16" height="16" /><?php }?>
				<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['password'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
        </tr>
<?php if ($_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['notes']){?>
        <tr class="entry<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
">
            <td colspan="2">&nbsp;</td>
     	    <td colspan="3"><?php echo $_smarty_tpl->tpl_vars['entries']->value[$_smarty_tpl->getVariable('smarty')->value['section']['entry']['index']]['notes'];?>
</td>
        </tr>
<?php }?>
    <?php endfor; endif; ?></table>
    <!-- END Entries table -->
<?php }else{ ?>
    <p>
        Ничего не найдено.
    </p>
<?php }?>

<?php }} ?>