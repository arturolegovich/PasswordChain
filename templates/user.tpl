{* phpChain - Smarty Template *}
{* $Id: user.tpl,v 1.2 2006/01/13 06:42:16 gogogadgetscott Exp $ *}
{include file=$file_errors action="performing login"}
    <p>
        User and password are
        <strong title="Uppercase and lowercase letters are treated differently.">
        case sensitive</strong>.
    </p>
    <div class="user">
        <form action="{$urlHome}user{$file_ext}" method="post">
            <div class="row">
                <span class="label">User:</span>
                <span class="formw">
                    <input name="user" type="{if $hide_login}password{else}text{/if}" id="user" maxlength="255" size="30" value="{$user}" />
                </span>
            </div>
            <div class="row">
                <span class="label">Password:</span>
                <span class="formw">
                    <input name="key" type="password" id="key" maxlength="255" size="30" value="" />
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
