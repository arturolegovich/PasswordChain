{* phpChain - Smarty Template *}
{* $Id: settings.tpl,v 1.17 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
    {include file=$file_errors action="saving settings"}
	<span class="heading1">Пользовательские настройки</span>
	<div class="settings">
    	<form action="{$urlHome}settings{$file_ext}" method="post" name="settings">
    	    <input type="hidden" name="action" value="settings" />
            <div class="rows">
                <span class="label">Скрытие пароля:</span>
                <span class="formw">
                    <select name="pwmask">
                        <option {if $settings.pwmask >= 1}selected {/if}value="1">Включено</option>
                		<option {if $settings.pwmask <= 0}selected {/if}value="0">Отключено</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">Кнопка 'Копировать в буфер':</span>
                <span class="formw">
                   	<select name="clipboard">
                       <option {if $settings.clipboard >= 1}selected {/if}value="1">Показывать</option>
                	   <option {if $settings.clipboard <= 0}selected {/if}value="0">Скрывать</option>
                    </select>
                </span>
            </div>
            <div class="rows">
           	    <span class="label">Длина создаваемого пароля:</span>
                <span class="formw">
                    <select name="generate">
                        <option {if $settings.generate <= 0}selected {/if}value="0">Отключить</option>
                        <option {if $settings.generate == 4}selected {/if}value="4">4 символа</option>
                        <option {if $settings.generate == 6}selected {/if}value="6">6 символов</option>
                        <option {if $settings.generate == 8}selected {/if}value="8">8 символов</option>
                        <option {if $settings.generate == 10}selected {/if}value="10">10 символов</option>
                        <option {if $settings.generate == 16}selected {/if}value="16">16 символов</option>
                        <option {if $settings.generate == 32}selected {/if}value="32">32 символа</option>
                        <option {if $settings.generate >= 64}selected {/if}value="64">64 символа</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">Логин по умолчанию:</span>
                <span class="formw">
                    <input type="text" name="defaultun" value="{$settings.defaultun}" />
                </span>
            </div>
            <div class="rows">
                <span class="label">Автовыход:</span>
                <span class="formw">
                    <select name="expire">
                        <option value="0"{if $settings.expire <= 0} selected{/if}>При закрытии</option>
                        <option value="60"{if $settings.expire == 60} selected{/if}>1 минута</option>
                        <option value="300"{if $settings.expire == 300} selected{/if}>5 минут</option>
                		<option value="900"{if $settings.expire == 900} selected{/if}>15 минут</option>
                		<option value="1500"{if $settings.expire == 1500} selected{/if}>25 минут</option>
                		<option value="3600"{if $settings.expire == 3600} selected{/if}>60 минут</option>
                		<option value="5400"{if $settings.expire == 5400} selected{/if}>90 минут</option>
                		<option value="86400"{if $settings.expire == 86400} selected{/if}>1 день</option>
                		<option value="220752000"{if $settings.expire >= 220752000} selected{/if}>Никогда</option>
                    </select>
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Сохранить" />
                </span>
            </div>
        </form>
    </div>
    <span class="clear">&nbsp;</span>
