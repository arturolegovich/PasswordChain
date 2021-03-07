{* phpChain - Smarty Template *}
{if $errors}

    <div class="error">
        <p>
            The following error{if $errors > 1}s{/if} occurred while {$action}:
        </p>
        <ul>
        {foreach from=$errors item=error}
            <li>
                {$error}
            </li>
        {/foreach}
        </ul>
    </div>
{/if}
{if $msgs}

    <div class="msgs">
        <p>
            The following message{if $msgs > 1}s{/if} where returned while {$action}:
        </p>
        <ul>
{foreach from=$msgs item=msg}
            <li>
                {$msg}
            </li>
{/foreach}
        </ul>
    </div>
{/if}
