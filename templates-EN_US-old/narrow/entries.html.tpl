{* phpChain - Smarty Template *}
{* $Id: entries.html.tpl,v 1.2 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{if $entries}
    <script src="js/entries.js" type="text/javascript"></script>
    <!-- Entries table -->
    <table id="entries" class="entries" cellpadding="0" cellspacing="0">
        <tr><th>Action&nbsp;</th></tr>
	    <tr>
            <th>
	        	Title
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=title&order=SORT_ASC#entries">
                    <img src="{$urlImages}s_asc{if $sort_flag!="SORT_ASC" | $sort_col!="title"}_n{/if}.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=title&order=SORT_DESC#entries">
                    <img src="{$urlImages}s_desc{if $sort_flag=="SORT_ASC" || $sort_col!="title"}_n{/if}.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
        </tr>
        <tr>
            <th>
				Login
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=login&order=SORT_ASC#entries">
                    <img src="{$urlImages}s_asc{if $sort_flag!="SORT_ASC" || $sort_col!="login"}_n{/if}.png" alt="Sort Ascending" width="11" height="9" />
                </a>
                <a href="{$urlHome}entries{$file_ext}?groupid={$groupid}&sort=login&order=SORT_DESC#entries">
                    <img src="{$urlImages}s_desc{if $sort_flag=="SORT_ASC" || $sort_col!="login"}_n{/if}.png" alt="Sort Descending" width="11" height="9" />
                </a>
			</th>
        </tr>
        <tr>
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
    </table>
    {section name=entry loop=$entries}
        {cycle values="odd,even" assign=class}
    <table class="entries entry{$class}" cellpadding="0" cellspacing="0">
    	{strip}<tr>
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
        </tr>
        <tr>
            <td colspan="2">
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
        </tr>{/strip}
        <tr>
            <td colspan="2">
				{if $settings.clipboard}<img src="{$urlImages}clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('{$entries[entry].login|escape:"htmlall"}');return false;" width="16" height="16" />{/if}
				{$entries[entry].login|escape:"htmlall"}</td>
        </tr>
        <tr>
		    <td colspan="2"{if $settings.pwmask} class="password{$class}" OnMouseOver="this.className='passwordshow'" OnMouseOut="this.className='password{$class}'"{/if}>
				{if $settings.clipboard}<img class="clipboardimg" src="{$urlImages}clipboard.gif" alt="Copy to clipboard" onClick="copy_clip('{$entries[entry].password|escape:"htmlall"}');return false;" width="16" height="16" />{/if}
				{$entries[entry].password|escape:"htmlall"}</td>
        </tr>
{if $entries[entry].notes}
        <tr>
            <td>{$entries[entry].notes}</td>
        </tr>
{/if}
    </table>
    {/section}
    <!-- END Entries table -->
{else}
    <p>
        No data found.
    </p>
{/if}

