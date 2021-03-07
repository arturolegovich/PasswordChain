<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:34:40
         compiled from "./templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2464180836044c8602ada64-64260687%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0360d049dff10f364dfc53ba2cc3958abf6ee6d' => 
    array (
      0 => './templates/index.tpl',
      1 => 1614587244,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2464180836044c8602ada64-64260687',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'auth' => 0,
    'userLogs' => 0,
    'loginLog' => 0,
    'date_format' => 0,
    'site_name' => 0,
    'app_name' => 0,
    'openssl_ver' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c860e4b4e2_48897647',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c860e4b4e2_48897647')) {function content_6044c860e4b4e2_48897647($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include './libs/smarty-3.1.13/plugins/modifier.date_format.php';
?>﻿

<?php if ($_smarty_tpl->tpl_vars['auth']->value){?>
    <p>
	   Содержимое каждой группы можно просмотреть, нажав на название слева. 
       Для того, чтобы создать новую группу нажмите на ссылку &quot;Группы&quot; в главном меню.
    </p>
    <?php if ($_smarty_tpl->tpl_vars['userLogs']->value){?>
    <hr />
    <span class="heading1">Журнал последних посещений</span>
	<div class="userlogs">
        <ul>
            <?php  $_smarty_tpl->tpl_vars['loginLog'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['loginLog']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['userLogs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['loginLog']->key => $_smarty_tpl->tpl_vars['loginLog']->value){
$_smarty_tpl->tpl_vars['loginLog']->_loop = true;
?>
                <li>
                    <span class="<?php if ($_smarty_tpl->tpl_vars['loginLog']->value['outcome']==1){?>"><?php }else{ ?>error"><?php }?>
                        <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['loginLog']->value['logged'],$_smarty_tpl->tpl_vars['date_format']->value);?>

                        <?php if ($_smarty_tpl->tpl_vars['loginLog']->value['duration']!="N/A"){?>(<?php echo $_smarty_tpl->tpl_vars['loginLog']->value['duration'];?>
)<?php }?>
                        с адреса <?php echo $_smarty_tpl->tpl_vars['loginLog']->value['ip'];?>
 -
                        <?php if ($_smarty_tpl->tpl_vars['loginLog']->value['outcome']==1){?>Удачно<?php }else{ ?>Failed<?php }?>
                    </span>
                </li> 
            <?php } ?>
        </ul>
    </div>
    <?php }?>
<?php }else{ ?>
    <h3>
        Добро пожаловать на <?php echo (($tmp = @$_smarty_tpl->tpl_vars['site_name']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['app_name']->value : $tmp);?>
.
    </h3>
    <p>
        <?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
  - это веб-приложение для хранения логинов и паролей в базе данных в зашифрованном виде.
		<br />
        Шифрование данных осуществляется с помощью блочного алгоритма "Кузнечик" (ГОСТ Р 34.12-2015) и хэш-функции md_gost12_256 (ГОСТ Р 34.11-2012).
		<br />
		В качестве средства криптографической защиты используется <?php echo $_smarty_tpl->tpl_vars['openssl_ver']->value;?>
 и gost-engine.
		<br />
	</p>
    <p>
        Вся информация в базе данных перед сохранением шифруется. 
	Для шифрования (расшифрования) информации используется связка логин+пароль. 
	Если вы забудете логин и/или пароль, то данные восстановить невозможно.
	<br />
	<br />
	Для дальнейшего использования необходимо <b><a href="/user.php">войти</a></b> или <b><a href="/newuser.php">зарегистрироваться</a></b>
    </p>
 <?php }?>
<?php }} ?>