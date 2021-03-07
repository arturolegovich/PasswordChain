{* phpChain - Smarty Template *}
{* $Id: entry_delete.tpl,v 1.8 2005/12/29 16:59:57 gogogadgetscott Exp $ *}
    <p>
        Эту операцию невозможно будет отменить. Вы подтверждаете удаление записи?
    </p>
    <p>
        <a href="{$urlSelf}?action=delete&check=1&entryid={$entryid}&groupid={$groupid}">
            <b>ДА</b>
        </a> |
        <a href="{$urlSelf}?groupid={$groupid}">
            <b>НЕТ</b>
        </a>
    </p>

