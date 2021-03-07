{* phpChain - Smarty Template *}
{* $Id: index.tpl,v 1.9 2006/02/27 04:39:51 gogogadgetscott Exp $ *}
{if $auth}
    <p>
	   Содержимое каждой группы можно просмотреть, нажав на название слева. 
       Для того, чтобы создать новую группу нажмите на ссылку &quot;Группы&quot; в главном меню.
    </p>
    {if $userLogs}
    <hr />
    <span class="heading1">Журнал последних посещений</span>
	<div class="userlogs">
        <ul>
            {foreach name=userLogs from=$userLogs item=loginLog}
                <li>
                    <span class="{if $loginLog.outcome == 1}">{else}error">{/if}
                        {$loginLog.logged|date_format:$date_format}
                        {if $loginLog.duration != "N/A"}({$loginLog.duration}){/if}
                        с адреса {$loginLog.ip} -
                        {if $loginLog.outcome == 1}Удачно{else}Failed{/if}
                    </span>
                </li> 
            {/foreach}
        </ul>
    </div>
    {/if}
{else}
    <h3>
        Добро пожаловать на {$site_name|default:$app_name}.
    </h3>
    <p>
        {$app_name}  - это веб-приложение для хранения логинов и паролей в базе данных в зашифрованном виде.
		<br />
        Шифрование данных осуществляется с помощью блочного алгоритма "Кузнечик" (ГОСТ Р 34.12-2015) и хэш-функции md_gost12_256 (ГОСТ Р 34.11-2012).
		<br />
		В качестве средства криптографической защиты используется {$openssl_ver} и gost-engine.
		<br />
	</p>
    <p>
        Вся информация в базе данных перед сохранением шифруется. 
	Для шифрования (расшифрования) информации используется связка логин+пароль. 
	Если вы забудете логин и/или пароль, то данные восстановить невозможно.
	<br />
	<br />
	Для дальнейшего использования необходимо <b><a href="/user.php">войти</a></b> или <b><a href="/newuser.php">зарегистрироваться</a></b>
    </p>
 {/if}
