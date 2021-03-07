<?php /* Smarty version Smarty-3.1.13, created on 2021-03-07 16:09:17
         compiled from "./templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15019061196044c26d8c4368-16855666%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97c13ae6868bbc459509c9f1b968154acd23eecc' => 
    array (
      0 => './templates/header.tpl',
      1 => 1537611135,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15019061196044c26d8c4368-16855666',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_name' => 0,
    'app_name' => 0,
    'doc_name' => 0,
    'urlImages' => 0,
    'urlTemplate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_6044c26f4f9a21_31592779',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6044c26f4f9a21_31592779')) {function content_6044c26f4f9a21_31592779($_smarty_tpl) {?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="yandex-verification" content="e8fc629938fb2f2a" />
    <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['site_name']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['app_name']->value : $tmp);?>
 - <?php echo (($tmp = @$_smarty_tpl->tpl_vars['doc_name']->value)===null||$tmp==='' ? "Page" : $tmp);?>
</title>
	<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['urlImages']->value;?>
favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['urlTemplate']->value;?>
style.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['urlTemplate']->value;?>
printlayout.css" media="print" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['urlTemplate']->value;?>
column.css" media="screen" />
</head>
<body>

<?php }} ?>