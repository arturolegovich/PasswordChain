{* phpChain - Smarty Template *}
{* $Id: entry_delete.tpl,v 1.8 2005/12/29 16:59:57 gogogadgetscott Exp $ *}
    <p>
        This is un-reversible. Are you sure you know
        what you are doing?
    </p>
    <p>
        <a href="{$urlSelf}?action=delete&check=1&entryid={$entryid}&groupid={$groupid}">
            <b>YES</b>
        </a> |
        <a href="{$urlSelf}?groupid={$groupid}">
            <b>NO</b>
        </a>
    </p>

