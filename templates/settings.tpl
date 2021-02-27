{* phpChain - Smarty Template *}
{* $Id: settings.tpl,v 1.17 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
    {include file=$file_errors action="saving settings"}
	<span class="heading1">User Settings</span>
	<div class="settings">
    	<form action="{$urlHome}settings{$file_ext}" method="post" name="settings">
    	    <input type="hidden" name="action" value="settings" />
            <div class="rows">
                <span class="label">Password Masking:</span>
                <span class="formw">
                    <select name="pwmask">
                        <option {if $settings.pwmask >= 1}selected {/if}value="1">Enable</option>
                		<option {if $settings.pwmask <= 0}selected {/if}value="0">Disable</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">'Copy To Clipboard' Button:</span>
                <span class="formw">
                   	<select name="clipboard">
                       <option {if $settings.clipboard >= 1}selected {/if}value="1">Enable</option>
                	   <option {if $settings.clipboard <= 0}selected {/if}value="0">Disable</option>
                    </select>
                </span>
            </div>
            <div class="rows">
           	    <span class="label">Generate password length:</span>
                <span class="formw">
                    <select name="generate">
                        <option {if $settings.generate <= 0}selected {/if}value="0">Disable</option>
                        <option {if $settings.generate == 4}selected {/if}value="4">4</option>
                        <option {if $settings.generate == 6}selected {/if}value="6">6</option>
                        <option {if $settings.generate == 8}selected {/if}value="8">8</option>
                        <option {if $settings.generate == 10}selected {/if}value="10">10</option>
                        <option {if $settings.generate == 16}selected {/if}value="16">16</option>
                        <option {if $settings.generate == 32}selected {/if}value="32">32</option>
                        <option {if $settings.generate >= 64}selected {/if}value="64">64</option>
                    </select>
                </span>
            </div>
            <div class="rows">
                <span class="label">Default Login:</span>
                <span class="formw">
                    <input type="text" name="defaultun" value="{$settings.defaultun}" />
                </span>
            </div>
            <div class="rows">
                <span class="label">Auto logout time (min):</span>
                <span class="formw">
                    <select name="expire">
                        <option value="0"{if $settings.expire <= 0} selected{/if}>On Close</option>
                        <option value="60"{if $settings.expire == 60} selected{/if}>1 min</option>
                        <option value="300"{if $settings.expire == 300} selected{/if}>5 min</option>
                		<option value="900"{if $settings.expire == 900} selected{/if}>15 min</option>
                		<option value="1500"{if $settings.expire == 1500} selected{/if}>25 min</option>
                		<option value="3600"{if $settings.expire == 3600} selected{/if}>60 min</option>
                		<option value="5400"{if $settings.expire == 5400} selected{/if}>90 min</option>
                		<option value="86400"{if $settings.expire == 86400} selected{/if}>1 day</option>
                		<option value="220752000"{if $settings.expire >= 220752000} selected{/if}>Never</option>
                    </select>
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Save" />
                </span>
            </div>
        </form>
    </div>
    <span class="clear">&nbsp;</span>
