<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:24
         compiled from "./templates/password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5070506796044c274ab7753-69324656%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '68976757a0667110129aa206bb4ea12e08727072' => 
    array (
      0 => './templates/password.tpl',
      1 => 1537115228,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5070506796044c274ab7753-69324656',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'file_errors' => 0,
    'action' => 0,
    'urlSelf' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c274bed6c5_41491763',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c274bed6c5_41491763')) {function content_6044c274bed6c5_41491763($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['file_errors']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('action'=>"updating password"), 0);?>

<?php if ($_smarty_tpl->tpl_vars['action']->value=="complete"){?>
    <p>
     Пароль изменён!
    </p>
    <p>
     После смены пароля необходимо выполнить вход с использованием нового пароля.
    </p>
<?php }else{ ?>
    <p>
		Изменение пароля повлечёт за собой расшифровку всех записей вашего аккаунта в базе данных 
		с последующим шифрованием всех записей с использованием нового пароля.
        Этот процесс может занять некоторое время в зависимости от количества записей. Не завершайте работу браузера
		и не закрывайте его, пока процесс не будет завершен, а иначе можно потерять данные.
    </p>
    <p>
        Пароль должен состоять не менее чем из 3 символов и не более чем из 255 символов.
    </p>
    <div class="user">
    	<form action="<?php echo $_smarty_tpl->tpl_vars['urlSelf']->value;?>
" method="post">
    	   <input type="hidden" name="action" value="save" />
            <div class="row">
                <span class="label">Новый пароль:</span>
                <span class="formw">
                    <input name="newkey" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <span class="label">Проверка нового пароля: </span>
                <span class="formw">
                    <input name="newkey2" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <input name="submit" type="submit" value="Сменить пароль" />
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <p>
        <b>Пароли</b> должны состоять из любых печатных символов ANSI кроме пробелов (/^[\41-\176]+/i).
    </p>
<?php }?>
    <span class="clear">&nbsp;</span><?php }} ?>