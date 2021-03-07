{* phpChain - Smarty Template *}
{* $Id: entries.html.tpl,v 1.9 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{if $groupid eq -2}
    <span class="heading1">Search</span>
    <div class="entry">
        <form action="{$urlHome}entries{$file_ext}" method="post">
            <input type="hidden" name="action" value="search" />
            <input type="hidden" name="groupid" value="-2" />
            <div class="rowe">
                <span class="label">Search for this text:</span>
                <span class="formw">
                    <input type="text" name="search" value="{if $search}{$search|escape:"htmlall"}{/if}" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Search" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <span class="clear">&nbsp;</span>
{/if}
{if $entries}
    <script src="js/entries.js" type="text/javascript"></script>
    <!-- Entries table -->
    <table id="entries" class="entries" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2" align="center" width="1%">Action&nbsp;</th>
	        <th>
	        	Title
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=title&order=SORT_ASC#entries">
                    <img src="{$urlImages}s_asc{if $sort_flag!="SORT_ASC" || $sort_col!="title"}_n{/if}.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=title&order=SORT_DESC#entries">
                    <img src="{$urlImages}s_desc{if $sort_flag=="SORT_ASC" || $sort_col!="title"}_n{/if}.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
            <th>
				Login
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=login&order=SORT_ASC#entries">
                    <img src="{$urlImages}s_asc{if $sort_flag!="SORT_ASC" || $sort_col!="login"}_n{/if}.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=login&order=SORT_DESC#entries">
                    <img src="{$urlImages}s_desc{if $sort_flag=="SORT_ASC" || $sort_col!="login"}_n{/if}.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
            <th>
				Password
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=password&order=SORT_ASC#entries">
                    <img src="{$urlImages}s_asc{if $sort_flag!="SORT_ASC" || $sort_col!="password"}_n{/if}.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=password&order=SORT_DESC#entries">
                    <img src="{$urlImages}s_desc{if $sort_flag=="SORT_ASC" || $sort_col!="password"}_n{/if}.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
        </tr>
    {section name=entry loop=$entries}
        {cycle values="odd,even" assign=class}
    	<tr class="entry{$class}">
    	   {strip}
            <td align="center">
    	        <a href="{$urlHome}entries{$file_ext}?action=edit&entryid={$entries[entry].entryid}" title="Edit Entry">
                    <img src="{$urlImages}edit.gif" alt="Edit" width="16" height="16" />
                </a>
    	    </td>
    	    <td align="center">
    	        <a href="{$urlHome}entries{$file_ext}?action=delete&entryid={$entries[entry].entryid}&groupid={$entries[entry].groupid}" title="Delete Entry">
                    <img src="{$urlImages}drop.gif" alt="Delete" width="16" height="16" />
                </a>
    	    </td>
            <td>
            {if $entries[entry].url}
                <a href="{$entries[entry].url}" target="_blank">
            {/if}
            {if $entries[entry].icon}
                <img src="{$entries[entry].icon}" alt="{$entries[entry].site}" width="16" height="16" />&nbsp;
            {/if}
            {$entries[entry].title|escape:"htmlall"}
            {if $entries[entry].url}
                </a>
                {if $entries[entry].params}
                <form action="{$entries[entry].url}" method="post" onSubmit="return createTarget(this.target)" target="entry_{$entries[entry].entryid}">
                {foreach from=$entries[entry].params key=name item=value}
                    <input type="hidden" name="{$name}" value="{$value}" />
                {/foreach}
                    <input name="submit" type="submit" value="Login" />
                </form>
                {/if}
            {/if}
    	    </td>
            </td>
            {/strip}
            <td>
				{if $settings.clipboard}<img src="{$urlImages}clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('{$entries[entry].login|escape:"htmlall"}');return false;" width="16" height="16" />{/if}
				{$entries[entry].login|escape:"htmlall"}</td>
		    <td{if $settings.pwmask} class="password{$class}" OnMouseOver="this.className='passwordshow'" OnMouseOut="this.className='password{$class}'"{/if}>
				{if $settings.clipboard}<img class="clipboardimg" src="{$urlImages}clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('{$entries[entry].password|escape:"htmlall"}');return false;" width="16" height="16" />{/if}
				{$entries[entry].password|escape:"htmlall"}</td>
        </tr>
{if $entries[entry].notes}
        <tr class="entry{$class}">
            <td colspan="2">&nbsp;</td>
     	    <td colspan="3">{$entries[entry].notes}</td>
        </tr>
{/if}
    {/section}</table>
    <!-- END Entries table -->
{else}
    <p>
        No data found.
    </p>
{/if}

