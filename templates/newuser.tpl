{* phpChain - Smarty Template *}
{* $Id: newuser.tpl,v 1.3 2006/01/15 07:18:02 gogogadgetscott Exp $ *}
    <h3>
        Приветствуем нового пользователя {$app_name}!!!
    </h3>
{include file=$file_errors action="creating a new user"}
    <p>
        Пароль должен быть не менее 3 символов и не более 255 символов.
    </p>
    <div class="user">
    	<form action="{$urlSelf}" method="post">
    	   <div class="row">
                <span class="label">Новый логин:</span>
                <span class="formw">
                    <input name="newuser" type="text" maxlength="255" size="20" value="{$newUser}" />
                </span>
            </div>
            <div class="row">
                <span class="label">Пароль:</span>
                <span class="formw">
                    <input name="newkey" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <span class="label">Подтверждение пароля:</span>
                <span class="formw">
                    <input name="newkey2" type="password" maxlength="255" size="30" value="" />
                </span>
            </div>
            <div class="row">
                <span class="formw">
                    <input name="submit" type="submit" value="Создать пользователя" />
                </span>
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <p>
        <b>Логин</b> может состоять из заглавных букв, строчных букв, цифр
         и специальных символов (/^[a-z0-9\.]+/i).
    </p>
    <p>
        <b>Пароль</b> должен состоять из любых печатных символов ANSI кроме пробелов (/^[\41-\176]+/i).
    </p>