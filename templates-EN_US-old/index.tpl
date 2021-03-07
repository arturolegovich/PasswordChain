{* phpChain - Smarty Template *}
{* $Id: index.tpl,v 1.9 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{if $auth}
    <p>
	   The contents of each Category can be viewed by clicking the name on
       the left. If you have no categories, you will need to create some
       from the &quot;settings&quot; link in the menu above.
    </p>
    {if $userLogs}
    <hr />
    <span class="heading1">Last login Log</span>
	<div class="userlogs">
        <ul>
            {foreach name=userLogs from=$userLogs item=loginLog}
                <li>
                    <span class="{if $loginLog.outcome == 1}">{else}error">{/if}
                        {$loginLog.logged|date_format:$date_format}
                        {if $loginLog.duration != "N/A"}({$loginLog.duration}){/if}
                        from {$loginLog.ip} -
                        {if $loginLog.outcome == 1}Successful{else}Failed{/if}
                    </span>
                </li> 
            {/foreach}
        </ul>
    </div>
    {/if}
{else}
    <h3>
        Welcome to {$site_name|default:$app_name}.
    </h3>
    <p>
        {$app_name} is a online, secure database management system for storing 
        important data.
        Data is stored encrypted using the Blowfish algorithm for security.
		You may login, or create a login from the links in the menu above.
    </p>
    <p>
        In order for this system to be secure, your password is not stored in 
        the database. Not only that, but only your password may be used to 
        decrypt the passwords you have stored. Consequently, if you forget 
        your password, <b>all your data is unrecoverable</b>. So, while this 
        system exists to help you recall passwords, try and remember the one 
        to get into this site.
    </p>
{/if}
