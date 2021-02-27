{* phpChain - Smarty Template *}
{* $Id: entry_form.tpl,v 1.16 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{include file=$file_errors action="Inputting entry."}
{if ! $groups}
        <p>
            Please create a category first from the "settings" link in the menu above. 
        </p>
{elseif $entries ne ""}
{foreach name=entries from=$entries item=entry}
{if $entry.entryid eq ""}
        <span class="heading1">Create new entry</span>
{else}
        <span class="heading1">Edit entry</span>
{/if}
    <div class="entry">
        <form action="{$urlHome}entries{$file_ext}" method="post">
            <input type="hidden" name="action" value="save" />
            <input type="hidden" name="entryid" value="{$entry.entryid}" />
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Save" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="rowe">
                <span class="label">Group:</span>
                <span class="formw">
                    <select name="groupid">
{foreach name=Array from=$groups key=id item=group}
    {if $id eq $entry.groupid}
    {assign var="group" value=$group}
    			        <option selected value="{$id}">{$group}</option>
    {else}
                        <option value="{$id}">{$group}</option>
    {/if}
{/foreach}
                    </select>
                    <!-- <input name="group_title" type="text" id="key" maxlength="255" size="30" value="{$group}" /> -->
                </span>
            </div>
            <div class="rowe">
                <span class="label">Title:</span>
                <span class="formw">
                    <input type="text" name="title" value="{$entry.title|escape:"htmlall"}" />
                </span>
            </div>
            <div class="rowe">
                <span class="label">Login:</span>
                <span class="formw">
                    <input type="text" name="login" size="30" value="{$entry.login|escape:"htmlall"}" />
                </span>
            </div>
            <div class="rowe">
                <span class="label">Password:</span>
                <span class="formw">
                    <input type="text" name="password" size="30" value="{$entry.password|escape:"htmlall"}" />
                </span>
            </div>
            <div class="rowe">
                <span class="label">Address:</span>
                <span class="formw">
                    <textarea name="url" rows="5">{$entry.url|escape:"htmlall"}</textarea>
                </span>
            </div>
            <div class="rowe">
                <span class="label">Notes:</span>
                <span class="formw">
                    <textarea name="notes" rows="8">{$entry.notes|escape:"htmlall"}</textarea>
                </span>
            </div>
            <span class="clear">&nbsp;</span>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Save" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
{/foreach}
{else}
    {* If something goes wrong. *}
    <p>
        Please select an entry to edit or create a new entry.
    </p>
{/if} 

