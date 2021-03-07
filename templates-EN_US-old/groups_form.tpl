{* phpChain - Smarty Template *}
{* $Id: groups_form.tpl,v 1.7 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
    <span class="heading1">{if $action eq "add"}Add{else}Edit{/if} group:</span>
    <div class="entry">
        <form action="{$urlHome}groups{$file_ext}" method="post" name="settings">
            <input type="hidden" name="action" value="save" />
            <input type="hidden" name="groupid" value="{$groupid}" />
            <div class="row">
                <label>Group Title: </label>
                <input name="group_title" type="text" id="key" maxlength="255" size="30" value="{$group_title}" />
            </div>
            <div class="row">
                <label class="col1"></label>
                <span class="button">
                    <input name="submit" type="submit" value="Save" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>