{* phpChain - Smarty Template *}
{include file=$file_errors action="updating password"}
{if $action == "complete"}
    <p>
     Пароль изменён!
    </p>
    <p>
     После смены пароля необходимо выполнить вход с использованием нового пароля.
    </p>
{else}
    <p>
		Изменение пароля повлечёт за собой расшифровку всех записей вашего аккаунта в базе данных 
		с последующим шифрованием всех записей с использованием нового пароля.
        Этот процесс может занять некоторое время в зависимости от количества записей. Не завершайте работу браузера
		и не закрывайте его, пока процесс не будет завершен, а иначе можно потерять данные.
    </p>
    <p>
        Пароль должен состоять не менее чем из 3 символов и не более чем из 255 символов.
    </p>
    <div class="user">
    	<form action="{$urlSelf}" method="post">
    	   <input type="hidden" name="action" value="save" />
            <div class="row">
                <span class="label">Новый пароль:</span>
                <span class="formw">
                    <input name="newkey" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <span class="label">Проверка нового пароля: </span>
                <span class="formw">
                    <input name="newkey2" type="password" maxlength="255" size="30" value="" />
                </span>
            </div> 
            <div class="row">
                <input name="submit" type="submit" value="Сменить пароль" />
            </div>
            <span class="clear">&nbsp;</span>
        </form>
    </div>
    <p>
        <b>Пароли</b> должны состоять из любых печатных символов ANSI кроме пробелов (/^[\41-\176]+/i).
    </p>
{/if}
    <span class="clear">&nbsp;</span>