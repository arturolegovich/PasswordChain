{* phpChain - Smarty Template *}
{* $Id: *}
    {include file=$file_errors action="saving groups"}
    {include file=$groups_form action="add"}
    <hr />
    <table class="center">
        <tr>
    	   <th width="65%">Группы</th>
    	   <th width="15%">Редактирование</th>
    	   <th width="15%">Удаление</th>
    	   <th width="15%">Экспорт</th>
    	</tr>
        <tr class="entryeven">
            <td><a href="{$urlHome}entries{$file_ext}?groupid=-1">Все</a></td>
            <td>&nbsp;</td>
    	    <td><a href="{$urlHome}groups{$file_ext}?action=delete&groupid=-1" title="Удалить группу"
                ><img src="{$urlImages}drop.gif" alt="Удаление" width="16" height="16"
                /></a></td>
    	    <td><a href="{$urlHome}entries{$file_ext}?output=xml&groupid=-1" title="Экспорт группы"
                ><img src="{$urlImages}xxml.gif" alt="Экспорт" width="16" height="16"
                /></a></td>
        </tr>
        {foreach name=Array from=$groups key=id item=group}
    	{cycle values="odd,even" assign=class}
        <tr class="entry{$class}">
            <td><a href="{$urlHome}entries{$file_ext}?groupid={$id}">{$group}</a></td>
            <td><a href="{$urlHome}groups{$file_ext}?action=edit&groupid={$id}" title="Редактировать группу"
                ><img src="{$urlImages}edit.gif" alt="Редактирование" width="16" height="16"
                /></a></td>
    	    <td><a href="{$urlHome}groups{$file_ext}?action=delete&groupid={$id}" title="Удалить группу"
                ><img src="{$urlImages}drop.gif" alt="Удаление" width="16" height="16"
                /></a></td>
    	    <td><a href="{$urlHome}entries{$file_ext}?output=xml&groupid={$id}" title="Экспорт группу"
                ><img src="{$urlImages}xxml.gif" alt="Экспорт" width="16" height="16"
                /></a></td>
        </tr>
    	{/foreach}
    </table>
	<hr />
    <span class="heading1">Импорт записей:</span>
	<div class="settings">
	    <span class="clear">&nbsp;</span>
        <form action="{$urlHome}groups{$file_ext}" enctype="multipart/form-data" method="post" name="filename">
            <input type="hidden" name="action" value="import" />
            XML файл:&nbsp;
            <input class="text_area" name="importfile" type="file" />
            <span class="clear">&nbsp;</span>
            <input class="button" type="submit" value="Загрузить файл" />
        </form>
    </div>

