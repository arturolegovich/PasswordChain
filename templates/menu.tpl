{* phpChain - Smarty Template *}
{* $Id: menu.tpl,v 1.10 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
<div id="titlebar" onclick="javascript:document.location='index{$file_ext}'"> 
    <span class="menuleft">
        {$site_name|default:$app_name}
        <span class="plain">- Работает на <!--<a href="{$app_url}">-->{$app_name} {$app_ver}<!--</a>--></span>
    </span>
    <a href="javascript:void(0);">{$smarty.now|date_format:$date_format}</a>
</div>

<div id="menubar">
    <span class="menuleft">
    {if $auth}
        <a href="{$urlHome}groups{$file_ext}">
            <img src="{$urlImages}groups.gif" alt="Группы" width="16" height="16" />
            Группы
        </a> |
        <a href="{$urlHome}settings{$file_ext}">
            <img src="{$urlImages}settings.gif" alt="Настройки" width="16" height="16" />
            Настройки
        </a> |
        <a href="{$urlHome}password{$file_ext}">
            <img src="{$urlImages}password.gif" alt="Сменить пароль" width="16" height="16" />
            Сменить пароль
        </a> |
        <a href="{$urlHome}user{$file_ext}">
            <img src="{$urlImages}logout.gif" alt="Выход" width="16" height="16" />
            Выход
        </a>
    {else}
        <a href="{$urlHome}user{$file_ext}">
            <img src="{$urlImages}lock.gif" alt="Блокировка" width="16" height="16" />
            Вход
        </a> |
        <a href="{$urlHome}newuser{$file_ext}">
            <img src="{$urlImages}createlogin.gif" alt="Создание пользователя" width="16" height="16" />
            Регистрация
        </a>
    {/if}
    </span>
    {if !empty($auth)}
        Вход выполнен: <b>{$user}</b>
    {else}
        <form action="{$urlHome}user{$file_ext}" method="post">
            <label title="Введите имя пользователя." for="user">
                Пользователь:
                <input name="user" type="{if $hide_login}password{else}text{/if}" id="user" maxlength="255" size="8" value="" />
            </label>
            <label title="Введите пароль." for="key">
                Пароль:
                <input name="key" type="password" id="key" maxlength="255" size="8" value="" />
            </label>
            <input type="submit" value="Войти" />
        </form>
    {/if}
</div>

{if isset($_SERVER['HTTPS'])}
<div id="nossl" onclick="javascript:document.location='{$url_ssl}'">
    SSL не активно! Соединение не защищено!
    <a href="{$url_ssl}">Перейдите по ссылке</a> для установки защищенного соединения.
</div>
{/if}

<div id="sidebar">
{if $auth}
    <div id="groupswrap">
{include file="newentry.tpl"}
    <div id="groups">
        <ul>
            <li{if $groupid eq -1} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid=-1#entries">Все</a></li>
{foreach name=Array from=$groups key=id item=group}
            <li{if $id eq $groupid} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid={$id}#entries">{$group}</a></li>
{/foreach}
            <li{if $groupid eq -2} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid=-2">Поиск</a></li>
        </ul>
        <span class="clear">&nbsp;</span>
    </div>
        {include file="newentry.tpl"}
        </div>
{/if}
    <!--
	<div id="sidebox">
        <p>Спешка в любом деле приносит неудачи <i>Геродот (440 гг. до н.э.)</i></p>
	-->
    {if !$auth}
        <!--
        <span class="heading2">Themes</span>
	    <div class="userlogs">
        <form action="{$urlSelf}" method="post" name="theme">
            {if isset($theme)}<input type="radio" name="theme" value="default" /><img src="{$urlTemplate}/img/sample.png" alt="Default theme" width="20" height="20" /> Default<br />{/if}
        {foreach from=$themes item=item}
			{if isset($theme)}
				{if $item != $theme}<input type="radio" name="theme" value="{$item}" /><img src="{$urlTemplate}/{$item}/img/sample.png" alt="{$item} theme" width="20" height="20" /> {$itemcapitalize}<br />{/if}
			{/if}
		{/foreach}
            <input type="submit" name="submit" value="Switch" />
        </form>
        </div>|
        -->
    {/if}
    <!--</div>-->
</div>

<div id="content">
