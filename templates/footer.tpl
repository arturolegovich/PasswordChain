{* phpChain - Smarty Template *}
{* $Id: footer.tpl,v 1.9 2006/01/31 01:50:13 gogogadgetscott Exp $ *}
</div>
<div id="footer">
    <span class="menuleft">
        {$app_name} version {$app_ver},
        Copyright &copy; 2005-2006.<br /> SEG Technology. All rights reserved.<br />
        <!-- {$app_name} comes with ABSOLUTELY NO WARRANTY; for details
        <a href="gpl{$file_ext}#SEC3a">click here</a>.<br />
        This is free software, and you are welcome
        to redistribute it under certain conditions;<br />
        <a href="gpl{$file_ext}#SEC3">click here</a>
        for details. -->
    </span>
    {*
        If user is logged in hide logo to abided by "Conditional Use"
        polices (MySQL: http://www.mysql.com/company/legal/trademark.html)
    *}
    {if ! $auth}
    <a href="http://smarty.php.net/" target="_blank">
        <img src="{$urlImages}logos/smarty_icon.gif" alt="Smarty Template Engine" width="88" height="31" />
    </a>
    <a href="http://www.php.net/" target="_blank">
        <img src="{$urlImages}logos/powered_php_03.gif" alt="Powered by PHP" width="77" height="33" />
    </a>
    <a href="http://www.mysql.com/" target="_blank">
        <img src="{$urlImages}logos/powered-by-mysql-63x32.gif" alt="Powered by MySQL" width="63" height="32" />
    </a>
    <a href="http://sourceforge.net/">
        <img src="{$urlImages}logos/sflogo.png" alt="SourceForge.net Logo" width="88" height="31" />
    </a>
    <a href="http://sourceforge.net/donate/index.php?group_id=137582">
        <img src="{$urlImages}logos/project-support.jpg" alt="Support This Project" width="88" height="32" />
    </a>
    <a href="http://www.pspad.com/" title="editor PSPad - freeware editor">
        <img src="{$urlImages}logos/pspad_6.gif" alt="editor PSPad" width="88" height="31" />
    </a>
    <a href="http://gogogadgetscott.info/computers/scripts/phpob">
        <img src="{$urlImages}logos/phpOBrowser.gif" alt="SourceForge.net Logo" width="89" height="43" />
    </a>
    {/if}
</div>

{if !debug}
</body>
</html>
{/if}