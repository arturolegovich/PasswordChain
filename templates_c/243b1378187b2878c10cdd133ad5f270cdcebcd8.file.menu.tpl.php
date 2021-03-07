<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:19
         compiled from "./templates/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17488593536044c26f9d3b23-39651979%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '243b1378187b2878c10cdd133ad5f270cdcebcd8' => 
    array (
      0 => './templates/menu.tpl',
      1 => 1581846100,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17488593536044c26f9d3b23-39651979',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_ext' => 0,
    'site_name' => 0,
    'app_name' => 0,
    'app_url' => 0,
    'app_ver' => 0,
    'date_format' => 0,
    'auth' => 0,
    'urlHome' => 0,
    'urlImages' => 0,
    'user' => 0,
    'hide_login' => 0,
    '_SERVER' => 0,
    'url_ssl' => 0,
    'groupid' => 0,
    'groups' => 0,
    'id' => 0,
    'group' => 0,
    'urlSelf' => 0,
    'theme' => 0,
    'urlTemplate' => 0,
    'themes' => 0,
    'item' => 0,
    'itemcapitalize' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c270698e13_81012844',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c270698e13_81012844')) {function content_6044c270698e13_81012844($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include './libs/smarty-3.1.13/plugins/modifier.date_format.php';
?>﻿

<div id="titlebar" onclick="javascript:document.location='index<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
'"> 
    <span class="menuleft">
        <?php echo (($tmp = @$_smarty_tpl->tpl_vars['site_name']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['app_name']->value : $tmp);?>

        <span class="plain">- Работает на <!--<a href="<?php echo $_smarty_tpl->tpl_vars['app_url']->value;?>
">--><?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['app_ver']->value;?>
<!--</a>--></span>
    </span>
    <a href="javascript:void(0);"><?php echo smarty_modifier_date_format(time(),$_smarty_tpl->tpl_vars['date_format']->value);?>
</a>
</div>

<div id="menubar">
    <span class="menuleft">
    <?php if ($_smarty_tpl->tpl_vars['auth']->value){?>
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
groups<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
groups.gif" alt="Группы" width="16" height="16" />
            Группы
        </a> |
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
settings<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
settings.gif" alt="Настройки" width="16" height="16" />
            Настройки
        </a> |
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
password<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
password.gif" alt="Сменить пароль" width="16" height="16" />
            Сменить пароль
        </a> |
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
user<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logout.gif" alt="Выход" width="16" height="16" />
            Выход
        </a>
    <?php }else{ ?>
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
user<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
lock.gif" alt="Блокировка" width="16" height="16" />
            Вход
        </a> |
        <a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
newuser<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
createlogin.gif" alt="Создание пользователя" width="16" height="16" />
            Регистрация
        </a>
    <?php }?>
    </span>
    <?php if (!empty($_smarty_tpl->tpl_vars['auth']->value)){?>
        Вход выполнен: <b><?php echo $_smarty_tpl->tpl_vars['user']->value;?>
</b>
    <?php }else{ ?>
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
user<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
" method="post">
            <label title="Введите имя пользователя." for="user">
                Пользователь:
                <input name="user" type="<?php if ($_smarty_tpl->tpl_vars['hide_login']->value){?>password<?php }else{ ?>text<?php }?>" id="user" maxlength="255" size="8" value="" />
            </label>
            <label title="Введите пароль." for="key">
                Пароль:
                <input name="key" type="password" id="key" maxlength="255" size="8" value="" />
            </label>
            <input type="submit" value="Войти" />
        </form>
    <?php }?>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['_SERVER']->value['HTTPS'])){?>
<div id="nossl" onclick="javascript:document.location='<?php echo $_smarty_tpl->tpl_vars['url_ssl']->value;?>
'">
    SSL не активно! Соединение не защищено!
    <a href="<?php echo $_smarty_tpl->tpl_vars['url_ssl']->value;?>
">Перейдите по ссылке</a> для установки защищенного соединения.
</div>
<?php }?>

<div id="sidebar">
<?php if ($_smarty_tpl->tpl_vars['auth']->value){?>
    <div id="groupswrap">
<?php echo $_smarty_tpl->getSubTemplate ("newentry.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    <div id="groups">
        <ul>
            <li<?php if ($_smarty_tpl->tpl_vars['groupid']->value==-1){?> id="selected"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=-1#entries">Все</a></li>
<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['group']->key;
?>
            <li<?php if ($_smarty_tpl->tpl_vars['id']->value==$_smarty_tpl->tpl_vars['groupid']->value){?> id="selected"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
#entries"><?php echo $_smarty_tpl->tpl_vars['group']->value;?>
</a></li>
<?php } ?>
            <li<?php if ($_smarty_tpl->tpl_vars['groupid']->value==-2){?> id="selected"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['urlHome']->value;?>
entries<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
?groupid=-2">Поиск</a></li>
        </ul>
        <span class="clear">&nbsp;</span>
    </div>
        <?php echo $_smarty_tpl->getSubTemplate ("newentry.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        </div>
<?php }?>
    <!--
	<div id="sidebox">
        <p>Спешка в любом деле приносит неудачи <i>Геродот (440 гг. до н.э.)</i></p>
	-->
    <?php if (!$_smarty_tpl->tpl_vars['auth']->value){?>
        <!--
        <span class="heading2">Themes</span>
	    <div class="userlogs">
        <form action="<?php echo $_smarty_tpl->tpl_vars['urlSelf']->value;?>
" method="post" name="theme">
            <?php if (isset($_smarty_tpl->tpl_vars['theme']->value)){?><input type="radio" name="theme" value="default" /><img src="<?php echo $_smarty_tpl->tpl_vars['urlTemplate']->value;?>
/img/sample.png" alt="Default theme" width="20" height="20" /> Default<br /><?php }?>
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['themes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
			<?php if (isset($_smarty_tpl->tpl_vars['theme']->value)){?>
				<?php if ($_smarty_tpl->tpl_vars['item']->value!=$_smarty_tpl->tpl_vars['theme']->value){?><input type="radio" name="theme" value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" /><img src="<?php echo $_smarty_tpl->tpl_vars['urlTemplate']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
/img/sample.png" alt="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
 theme" width="20" height="20" /> <?php echo $_smarty_tpl->tpl_vars['itemcapitalize']->value;?>
<br /><?php }?>
			<?php }?>
		<?php } ?>
            <input type="submit" name="submit" value="Switch" />
        </form>
        </div>|
        -->
    <?php }?>
    <!--</div>-->
</div>

<div id="content">
<?php }} ?>