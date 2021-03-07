{* phpChain - Smarty Template *}
{* $Id: setup.tpl,v 1.5 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{include file=$file_errors action="Installing $app_name"}
{if $action eq "complete"}
    <p>
	   Congratulations! {$app_name} is setup correctly. For security reasons, please 
       delete this file and it's template (setup.php & setup.tpl).
    </p>
    <p>
        <b><a href="{$urlHome}setup{$file_ext}?action=delete">Click here</a></b> to delete setup files.
    </p>
{elseif $action eq "preexist"}
    <p>
        It has been determined that {$app_name} is already installed correctly.
        You may reset all data by entering you database connection information below.
        Before doing so please be aware of the following points:
        <ul>
        <li>
        All users, groups, entries, and <b>ALL DATA</b> will be lost <b>FOREVER</b>.
        This is non-reveable (no undo).
        </li>
        <li>
        Please use extreme caution when using this step.
        </li>
        <li>
        Only {$app_name} tables will be overwritten, any other tables listed in 
        the same database will not be altered.
        </li>
        </ul>
    </p>
    <span class="heading1">Database Connection information</span>
    <div class="user">
        <form action="{$urlHome}setup{$file_ext}" method="post">
            <input type="hidden" name="action" value="reset" />
            <div class="row">
                <span class="label">Username:</span>
                <span class="formw">
                    <input name="dbusername" type="text" id="login" maxlength="255" size="30" value="" />
                </span>
            </div>
            <div class="row">
                <span class="label">Password:</span>
                <span class="formw">
                    <input name="dbpassword" type="password" id="key" maxlength="255" size="30" value="" />
                </span>
            </div>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Login" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
{elseif $action eq "delete"}
    <p>
        Unable to remove one or more of the following files.  Please delete these
        files manully.
        <ol>
            <li>setup.php</li>
            <li>setup.tpl</li>
        </ol>
    </p>
{else}
	<h3>
        Welcome to {$app_name} setup.
    </h3>
    <p>
        {$app_name} is a secure database for storing important passwords. Data is stored 
        encrypted using the Blowfish algorithm for security.
    </p>
    <p>
        In order for this system to be secure, your password is not stored in the 
        database. Not only that, but only your password may be used to decrypt the 
        passwords you have stored. Consequently, if you forget your password, <b>all 
        your data is unrecoverable</b>. So, while this system exists to help you recall 
        passwords, try and remember the one to get into this site.
    </p>
    {foreach from=$custid item=curr_id}
    
    {/foreach}
    {if not $errors}
    <p>
        <b><a href="{$urlHome}setup{$file_ext}?action=setup">Click here</a></b> to begin setup.
    </p>
    {/if}
{/if}
