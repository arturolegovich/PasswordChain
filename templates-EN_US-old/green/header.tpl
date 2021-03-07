{* phpChain - Smarty Template *}
{* $Id: header.tpl,v 1.2 2006/01/13 06:42:16 gogogadgetscott Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>
        {$site_name|default:$app_name} - {$doc_name|default:"Page"}
    </title>
	<link rel="shortcut icon" href="{$urlImages}favicon.ico" />
    <link rel="stylesheet" type="text/css" href="{$urlTemplate}style.css" media="all" />
    <link rel="stylesheet" type="text/css" href="{$urlTemplate}printlayout.css" media="print" />
    <link rel="stylesheet" type="text/css" href="{$urlTheme}column.css" media="screen" />
</head>
<body>
