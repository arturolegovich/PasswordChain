{* phpChain - Smarty Template *}
{* $Id: groups_form.tpl,v 1.7 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
    <span class="heading1">{if $action eq "add"}Добавление{else}Редактирование{/if} группы:</span>
    <div class="entry">
        <form action="{$urlHome}groups{$file_ext}" method="post" name="settings">
            <input type="hidden" name="action" value="save" />
            <input type="hidden" name="groupid" value="{$groupid}" />
            <div class="row">
                <label>Название группы: </label>
				
                <input name="group_title" type="text" id="key" maxlength="255" size="30" value="{if isset($group_title)}{$group_title}{/if}" />
			</div>
            <div class="row">
                <label class="col1"></label>
                <span class="button">
                    <input name="submit" type="submit" value="Сохранить" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>