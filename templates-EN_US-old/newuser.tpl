{* phpChain - Smarty Template *}
{* $Id: newuser.tpl,v 1.3 2006/01/15 07:18:02 gogogadgetscott Exp $ *}
    <h3>
        Welcome New user to {$app_name}!!!
    </h3>
{include file=$file_errors action="creating a new user"}
    <p>
        Password must be at least 3 characters and no more then 255 characters long.
    </p>
    <div class="user">
    	<form action="{$urlSelf}" method="post">
    	   <div class="row">
                <span class="label">New User:</span>
                <span class="formw">
                    <input name="newuser" type="text" maxlength="255" size="20" value="{$newUser}" />
                </span>
            </div>
            <div class="row">
                <span class="label">Password:</span>
                <span class="formw">
                    <input name="newkey" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <span class="label">Verify password:</span>
                <span class="formw">
                    <input name="newkey2" type="password" maxlength="255" size="30" value="" />
                </span>
            </div>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Create login" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <p>
        <b>User</b> can contain uppercase letters, lowercase letters, numbers,
        and periods (/^[a-z0-9\.]+/i).
    </p>
    <p>
        <b>Passwords</b> can contain any of the 95 ANSI printable characters
        except spaces (/^[\41-\176]+/i).
    </p>