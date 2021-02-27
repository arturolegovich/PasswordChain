{* phpChain - Smarty Template *}
{* $Id: menu.tpl,v 1.10 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
<div id="titlebar" onclick="javascript:document.location='index{$file_ext}'"> 
    <span class="menuleft">
        {$site_name|default:$app_name}
        <span class="plain">- Powered by <a href="{$app_url}">{$app_name} {$app_ver}</a></span>
    </span>
    <a href="javascript:void(0);">{$smarty.now|date_format:$date_format}</a>
</div>

<div id="menubar">
    <span class="menuleft">
    {if $auth}
        <a href="{$urlHome}groups{$file_ext}">
            <img src="{$urlImages}groups.gif" alt="Groups" width="16" height="16" />
            Groups
        </a> |
        <a href="{$urlHome}settings{$file_ext}">
            <img src="{$urlImages}settings.gif" alt="Settings" width="16" height="16" />
            Settings
        </a> |
        <a href="{$urlHome}password{$file_ext}">
            <img src="{$urlImages}password.gif" alt="Password" width="16" height="16" />
            Password
        </a> |
        <a href="{$urlHome}user{$file_ext}">
            <img src="{$urlImages}logout.gif" alt="Logout" width="16" height="16" />
            Logout
        </a>
    {else}
        <a href="{$urlHome}user{$file_ext}">
            <img src="{$urlImages}lock.gif" alt="Lock" width="16" height="16" />
            Login
        </a> |
        <a href="{$urlHome}newuser{$file_ext}">
            <img src="{$urlImages}createlogin.gif" alt="Create User" width="16" height="16" />
            Create user
        </a>
    {/if}
    </span>
    {if $auth}
        Logged in as: {$user}
    {else}
        <form action="{$urlHome}user{$file_ext}" method="post">
            <label title="Enter login name." for="user">
                User:
                <input name="user" type="{if $hide_login}password{else}text{/if}" id="user" maxlength="255" size="8" value="" />
            </label>
            <label title="Enter password or key." for="key">
                Password:
                <input name="key" type="password" id="key" maxlength="255" size="8" value="" />
            </label>
            <input type="submit" value="Login" />
        </form>
    {/if}
</div>

{if $url_ssl}
<div id="nossl" onclick="javascript:document.location='{$url_ssl}'">
    SSL not enabled! Connection is not secure!
    <a href="{$url_ssl}">Click here</a> for secure version.
</div>
{/if}

<div id="sidebar">
{if $auth}
    <div id="groupswrap">
{include file="newentry.tpl"}
    <div id="groups">
        <ul>
            <li{if $groupid eq -1} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid=-1#entries">All</a></li>
{foreach name=Array from=$groups key=id item=group}
            <li{if $id eq $groupid} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid={$id}#entries">{$group}</a></li>
{/foreach}
            <li{if $groupid eq -2} id="selected"{/if}><a href="{$urlHome}entries{$file_ext}?groupid=-2">Search</a></li>
        </ul>
        <span class="clear">&nbsp;</span>
    </div>
        {include file="newentry.tpl"}
        </div>
{/if}
    <div id="sidebox">
        <p>Haste in every business brings failures. <i>Herodotus (440 B.C.E)</i></p>
    {if !$auth}
        <!--
         <span class="heading2">Themes</span>
	    <div class="userlogs">
        <form action="{$urlSelf}" method="post" name="theme">
            {if $theme != ""}<input type="radio" name="theme" value="default" /><img src="{$urlTemplate}/img/sample.png" alt="Default theme" width="20" height="20" /> Default<br />{/if}
        {foreach from=$themes item=item}
            {if $item != $theme}<input type="radio" name="theme" value="{$item}" /><img src="{$urlTemplate}/{$item}/img/sample.png" alt="{$item} theme" width="20" height="20" /> {$itemcapitalize}<br />{/if}
        {/foreach}
            <input type="submit" name="submit" value="Switch" />
        </form>
        </div>|
        -->
    {/if}
    </div>
</div>

<div id="content">
