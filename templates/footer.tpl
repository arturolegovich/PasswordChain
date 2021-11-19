{* phpChain - Smarty Template *}
{* $Id: footer.tpl,v 1.9 2006/01/31 01:50:13 gogogadgetscott Exp $ *}
</div>
<div id="footer">
    <!--<span class="menuleft">
        {$app_name} версия {$app_ver},
        авторские права &copy; 2005-2006.<br /> SEG Technology. Все права защищены.<br />-->
        <!-- {$app_name} выпускается без каких либо гарантий; для получения более подробной информации
        <a href="gpl{$file_ext}#SEC3a">перейдите по этой ссылке</a>.<br />
        Это беплатное программное обеспечение, и вы можете распространять его на определенных условиях;<br />
        <a href="gpl{$file_ext}#SEC3">Перейти по ссылке</a>
        for details.
    </span>-->
    {*
        If user is logged in hide logo to abided by "Conditional Use"
        polices (MySQL: http://www.mysql.com/company/legal/trademark.html)
    *}
    {if ! $auth}
    <a href="https://www.smarty.net/" target="_blank">
        <img src="{$urlImages}logos/smarty_icon.gif" alt="Движок шаблона Smarty" width="88" height="32" />
    </a>
    <a href="http://www.php.net/" target="_blank">
        <img src="{$urlImages}logos/powered_php_03.gif" alt="Работает на PHP" width="77" height="32" />
    </a>
    <a href="https://mariadb.com/" target="_blank">
        <img src="{$urlImages}logos/powered-by-mariadb-126x32.png" alt="Работает на MariaDB" width="126" height="32" />
    </a>
    <a href="https://github.com/">
        <img src="{$urlImages}logos/github.png" alt="Логотип GitHub.com" width="88" height="32" />
    </a>
    {/if}
</div>

{if !debug}
</body>
</html>
{/if}