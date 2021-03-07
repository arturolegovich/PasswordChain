{* phpChain - Smarty Template *}
{* $Id: debug.tpl,v 1.16 2006/01/13 06:42:16 gogogadgetscott Exp $ *}
{if $assign_debug_info}
	{$assign_debug_info}
{/if}
{if isset($_smarty_debug_output) and $_smarty_debug_output eq "html"}
<link rel="stylesheet" type="text/css" href="{$urlTemplate}debug.css" media="all" />
<table class="debug">
    <tr>
        <th colspan="3">
            {$app_name} Консоль отладки
        </th>
    </tr>
    <tr>
        <td class="dsection" colspan="3">Запросы к базе данных:</td>
    </tr>
    <tr class="dsection2">
        <th>Запрос</th>
        <th>Стек</th>
        <th>Время (мс)</th>
    </tr>
    {foreach name=queries from=$_debug_queries item=queries}
    <tr class="drow{if $smarty.foreach.queries.index is even}even{else}odd{/if}">
        <td class="dname">
            {$smarty.foreach.queries.iteration|string_format:"%2s"}) {$queries[0]|wordwrap:50}
            {if $queries[3]}
            <span class="error">Error: {$queries[3]}</span>
            {/if}
        </td>
        <td class="dvalue">{$queries[2]}</td>
        <td class="dtime">{$queries[1]}</td>
    </tr>
    {foreachelse}
    <tr>
        <td class="dnone" colspan="3">Нет запросов к базе данных.</td>
    </tr>
    {/foreach}
</table>
<table class="debug">
    <tr>
        <td class="dsection" colspan="3">Профиль/таймер информация:</td>
    </tr>
    <tr class="dsection2">
        <th>Курсор</th>
        <th>Время (мс)</th>
        <th>Всего (%)</th>
    </tr>
    {foreach name=marker from=$profile item=marker}
    <tr class="drow{if $smarty.foreach.marker.index is even}even{else}odd{/if}">
        <td class="dname">
            {$smarty.foreach.marker.iteration|string_format:"%2s"}) {$marker.name}
        </td>
        <td class="dtime">{$marker.diff}</td>
        <td class="dtime">{$marker.per}</td>
    </tr>
    {foreachelse}
    <tr>
        <td class="dnone" colspan="2">Профиль не создан.</td>
    </tr>
    {/foreach}
</table>	
<table class="debug">
    <tr>
        <td class="dsection" colspan="2">Назначенные переменные шаблона:</td>
    </tr>
    {counter assign="count" start=0 print=0}
	{section name=vars loop=$_debug_keys}
		{if $_debug_keys[vars] != "_debug_queries"
            && $_debug_keys[vars] != "profile"
            && $_debug_keys[vars] != "entries"
            && $_debug_keys[vars] != "groups"
            }
        {counter}
        <tr class="drow{if $count is even}even{else}odd{/if}">
            <td class="dname">
                {ldelim}
                    ${$_debug_keys[vars]}
                {rdelim}
            </td>
            <td class="dvalue">
                {$_debug_vals[vars]|@debug_print_var:0:72}
            </td>
        </tr>
        {/if}
	{sectionelse}
	    <tr>
            <td class="dnone" colspan="2">Нет назначенных переменных шаблона.</td>
        </tr>
	{/section}
</table>
<table class="debug">
    <tr>
        <td class="dsection" colspan="2">Отладочная информация:</td>
    </tr>
    <tr class="droweven">
        <td class="dvalue">
            <pre>{$debugInfo}</pre>
        </td>
    </tr>
</table>
{else}
<script type="text/javascript">
var title = 'Console';
if( self.name == '' )
    self.name = Math.random()*4;
    title += '_' + self.name;

_smarty_console = window.open("",title.value,"width=680,height=600,resizable,scrollbars=yes");
if (_smarty_console) {ldelim}
_smarty_console.document.write("<html><head><title>{$app_name} Debug Console_"+self.name+"</title></head><body>");
_smarty_console.document.write("<table border=0 width=100%>");

_smarty_console.document.write('<link rel="stylesheet" type="text/css" href="{$urlTemplate}debug.css" media="all" />\n'
+'<table class="debug">\n'
+'    <tr>\n'
+'        <th colspan="3">\n'
+'            {$app_name} Консоль отладки\n'
+'        </th>\n'
+'    </tr>\n'
+'    <tr>\n'
+'        <td class="dsection" colspan="3">Запросы к базе данных:</td>\n'
+'    </tr>\n'
+'    <tr class="dsection2">\n'
+'        <th>Query</th>\n'
+'        <th>Stack Frame</th>\n'
+'        <th>Time (ms)</th>\n'
+'    </tr>\n');
      {foreach name=queries from=$_debug_queries item=queries}
_smarty_console.document.write('    <tr class="drow{if $smarty.foreach.queries.index is even}even{else}odd{/if}">\n'
+'        <td class="dname">\n'
+'            {$smarty.foreach.queries.iteration|string_format:"%2s"}) {$queries[0]|escape:"javascript"}\n'
+'        </td>\n'
+'        <td class="dvalue">{$queries[2]}</td>\n'
+'        <td class="dtime">{$queries[1]}</td>\n'
+'    </tr>\n');
      {foreachelse}
_smarty_console.document.write('    <tr>\n'
+'        <td class="dnone" colspan="3">Нет запросов к базе данных.</td>\n'
+'    </tr>\n');
      {/foreach}
_smarty_console.document.write('</table>\n'
+'<table class="debug">\n'
+'    <tr>\n'
+'        <td class="dsection" colspan="3">Профиль/таймер информация:</td>\n'
+'    </tr>\n'
+'    <tr class="dsection2">\n'
+'        <th>Marker</th>\n'
+'        <th>Time (ms)</th>\n'
+'        <th>Total (%)</th>\n'
+'    </tr>\n');
      {foreach name=marker from=$profile item=marker}
_smarty_console.document.write('    <tr class="drow{if $smarty.foreach.marker.index is even}even{else}odd{/if}">\n'
+'        <td class="dname">\n'
+'            {$smarty.foreach.marker.iteration|string_format:"%2s"}) {$marker.name}\n'
+'        </td>\n'
+'        <td class="dtime">{$marker.diff}</td>\n'
+'        <td class="dtime">{$marker.per}</td>\n'
+'    </tr>\n');
      {foreachelse}
_smarty_console.document.write('    <tr>\n'
+'        <td class="dnone" colspan="2">Профиль не создан.</td>\n'
+'    </tr>\n');
      {/foreach}
_smarty_console.document.write('</table>\t\n'
+'<table class="debug">\n'
+'    <tr>\n'
+'        <td class="dsection" colspan="2">Назначенные переменные шаблона:</td>\n'
+'    </tr>\n');
{counter assign="count" start=0 print=0}
{section name=vars loop=$_debug_keys}
    {if $_debug_keys[vars] != "_debug_queries"
        && $_debug_keys[vars] != "profile"
        && $_debug_keys[vars] != "entries"
        && $_debug_keys[vars] != "groups"
        }
        {counter}
_smarty_console.document.write('        <tr class="drow{if $count is even}even{else}odd{/if}">\n'
+'            <td class="dname">\n'
+'                {ldelim}\n'
+'                    ${$_debug_keys[vars]}\n'
+'                {rdelim}\n'
+'            </td>\n'
+'            <td class="dvalue">\n'
+'                {$_debug_vals[vars]|@debug_print_var}\n'
+'            </td>\n'
+'        </tr>\n');
    {/if}
  {sectionelse}
_smarty_console.document.write('\t    <tr>\n'
+'            <td class="dnone" colspan="2">Нет назначенных переменных шаблона.</td>\n'
+'        </tr>\n');
  {/section}
_smarty_console.document.write('</table>');

_smarty_console.document.write("</body></html>");
_smarty_console.document.close();

{rdelim}
</script>
{/if}
</body>
</html>