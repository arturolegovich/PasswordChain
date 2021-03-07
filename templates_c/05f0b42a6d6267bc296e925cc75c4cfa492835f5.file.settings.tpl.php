<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:39:52
         compiled from "./templates/settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6978384116044c9983db552-25723716%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '05f0b42a6d6267bc296e925cc75c4cfa492835f5' => 
    array (
      0 => './templates/settings.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6978384116044c9983db552-25723716',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_errors' => 0,
    'urlHome' => 0,
    'file_ext' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c998bd95b7_60183223',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c998bd95b7_60183223')) {function content_6044c998bd95b7_60183223($_smarty_tpl) {?>﻿

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['file_errors']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('action'=>"saving settings"), 0);?>

	<span class="heading1">Пользовательские настройки</span>
	<div class="settings">
    	<form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
settings<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" method="post" name="settings">
    	    <input type="hidden" name="action" value="settings" />
            <div class="rows">
                <span class="label">Скрытие пароля:</span>
                <span class="formw">
                    <select name="pwmask">
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['pwmask']>=1){?>selected <?php }?>value="1">Включено</option>
                		<option <?php if ($_smarty_tpl->tpl_vars['settings']->value['pwmask']<=0){?>selected <?php }?>value="0">Отключено</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">Кнопка 'Копировать в буфер':</span>
                <span class="formw">
                   	<select name="clipboard">
                       <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['clipboard']>=1){?>selected <?php }?>value="1">Показывать</option>
                	   <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['clipboard']<=0){?>selected <?php }?>value="0">Скрывать</option>
                    </select>
                </span>
            </div>
            <div class="rows">
           	    <span class="label">Длина создаваемого пароля:</span>
                <span class="formw">
                    <select name="generate">
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']<=0){?>selected <?php }?>value="0">Отключить</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==4){?>selected <?php }?>value="4">4 символа</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==6){?>selected <?php }?>value="6">6 символов</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==8){?>selected <?php }?>value="8">8 символов</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==10){?>selected <?php }?>value="10">10 символов</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==16){?>selected <?php }?>value="16">16 символов</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']==32){?>selected <?php }?>value="32">32 символа</option>
                        <option <?php if ($_smarty_tpl->tpl_vars['settings']->value['generate']>=64){?>selected <?php }?>value="64">64 символа</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">Логин по умолчанию:</span>
                <span class="formw">
                    <input type="text" name="defaultun" value="<?php echo $_smarty_tpl->tpl_vars['settings']->value['defaultun'];?>
" />
                </span>
            </div>
            <div class="rows">
                <span class="label">Автовыход:</span>
                <span class="formw">
                    <select name="expire">
                        <option value="0"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']<=0){?> selected<?php }?>>При закрытии</option>
                        <option value="60"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==60){?> selected<?php }?>>1 минута</option>
                        <option value="300"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==300){?> selected<?php }?>>5 минут</option>
                		<option value="900"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==900){?> selected<?php }?>>15 минут</option>
                		<option value="1500"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==1500){?> selected<?php }?>>25 минут</option>
                		<option value="3600"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==3600){?> selected<?php }?>>60 минут</option>
                		<option value="5400"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==5400){?> selected<?php }?>>90 минут</option>
                		<option value="86400"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']==86400){?> selected<?php }?>>1 день</option>
                		<option value="220752000"<?php if ($_smarty_tpl->tpl_vars['settings']->value['expire']>=220752000){?> selected<?php }?>>Никогда</option>
                    </select>
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Сохранить" />
                </span>
            </div>
        </form>
    </div>
    <span class="clear">&nbsp;</span>
<?php }} ?>