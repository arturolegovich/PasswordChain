<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:20
         compiled from "./templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2247174056044c270f0c7a5-15941624%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a4f6f0d327fc7bc3ea86f63906a1bf934ca50c7' => 
    array (
      0 => './templates/footer.tpl',
      1 => 1615061704,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2247174056044c270f0c7a5-15941624',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'app_name' => 0,
    'app_ver' => 0,
    'file_ext' => 0,
    'auth' => 0,
    'urlImages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c27116abd3_25512470',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c27116abd3_25512470')) {function content_6044c27116abd3_25512470($_smarty_tpl) {?>﻿

</div>
<div id="footer">
    <!--<span class="menuleft">
        <?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
 версия <?php echo $_smarty_tpl->tpl_vars['app_ver']->value;?>
,
        авторские права &copy; 2005-2006.<br /> SEG Technology. Все права защищены.<br />-->
        <!-- <?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
 выпускается без каких либо гарантий; для получения более подробной информации
        <a href="gpl<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
#SEC3a">перейдите по этой ссылке</a>.<br />
        Это беплатное программное обеспечение, и вы можете распространять его на определенных условиях;<br />
        <a href="gpl<?php echo $_smarty_tpl->tpl_vars['file_ext']->value;?>
#SEC3">Перейти по ссылке</a>
        for details.
    </span>-->
    
    <?php if (!$_smarty_tpl->tpl_vars['auth']->value){?>
    <!--<a href="https://money.yandex.ru/to/410015769312157" title="Помощь проекту">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/project-support.jpg" alt="Поддержка проекта" width="88" height="32" />
    </a>-->
    <a href="https://www.smarty.net/" target="_blank">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/smarty_icon.gif" alt="Движок шаблона Smarty" width="88" height="31" />
    </a>
    <a href="http://www.php.net/" target="_blank">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/powered_php_03.gif" alt="Работает на PHP" width="77" height="33" />
    </a>
    <a href="https://mariadb.com/" target="_blank">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/powered-by-mariadb-126x32.png" alt="Работает на MariaDB" width="126" height="32" />
    </a>
    <a href="http://sourceforge.net/">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/sflogo.png" alt="Логотип SourceForge.net" width="88" height="31" />
    </a>
    <!--<a href="http://www.pspad.com/" title="editor PSPad - беспалтный редактор">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/pspad_6.gif" alt="редактор PSPad" width="88" height="31" />
    </a>-->
    <!--<a href="http://gogogadgetscott.info/computers/scripts/phpob">
        <img src="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
logos/phpOBrowser.gif" alt="Логотип SourceForge.net" width="89" height="43" />
    </a>-->
    <?php }?>
</div>

<?php if (!'debug'){?>
</body>
</html>
<?php }?><?php }} ?>