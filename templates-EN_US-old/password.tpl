{* phpChain - Smarty Template *}
{include file=$file_errors action="updating password"}
{if $action == "complete"}
    <p>
     Password changed!
    </p>
    <p>
     You will now need to login using your new password.
    </p>
{else}
    <p>
        Changing your password requires all data in
        the database under your login to be decrypted and re-encrypted. This
        process can take some time if you have a lot of entries. Do not hit
        stop or close your browser after entering your new password, or data
        loss may occur.
    </p>
    <p>
        Password must be at least 3 characters and no more then 255 characters long.
    </p>
    <div class="user">
    	<form action="{$urlSelf}" method="post">
    	   <input type="hidden" name="action" value="save" />
            <div class="row">
                <span class="label">New Password:</span>
                <span class="formw">
                    <input name="newkey" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <span class="label">Verify new password: </span>
                <span class="formw">
                    <input name="newkey2" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <input name="submit" type="submit" value="Change password" />
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <p>
        <b>Passwords</b> can contain any of the 95 ANSI printable characters
        except spaces (/^[\41-\176]+/i).
    </p>
{/if}
    <span class="clear">&nbsp;</span>