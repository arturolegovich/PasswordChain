<?php
// EN: Web-app Settings.
// RU: Настройки веб-приложения.

// EN: Protection of the script from direct execution.
// RU: Защита скрипта от прямого исполнения.
if (strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false
	|| !is_object($pch)) {
    header('location: ../index.php');
    exit;
}

/*
 * These are site wide configuration options that effect the entire application
 * regardless of user.
 */
// -----------------------------------------------------------------------------

/*
 * Enter PDO Database Driver (sqlite, pgsql, mysql).
 Not yet working with pdo, only php_mysqli. Need update code for php_pdo.
 */
$pch->dbDriver('mysql');
/*
 * Enter sqlite filename (if using sqlite)
 */
$pch->dbFile('/opt/var/phpchain.sqlite3');
/*
 * Enter MySQL Database Host.
 */
$pch->dbHost('localhost');
/*
 * Enter MySQL Database Username here.
 */
$pch->dbUsername('root');
/*
 * Enter MySQL Database Password here.
 */
$pch->dbPassword('');
/*
 * Enter MySQL Database Name here.
 */
$pch->dbName('phpchain');

// -----------------------------------------------------------------------------

/*
 * Site name for title for documents. Shown in both browser title and page 
 * header.
 */
$pch->assign('site_name', 'PasswordChain');
/*
 * Set default theme.
 */
$pch->theme('');
/*
 * Whether a SSL connection is required to login.
 * Value: true or false
 */
define('PCH_USE_SSL', false);
/*
 * Time limit until failed login attempts are discarded. Seconds.
 */
define('PCH_TIME_LIMIT', 10 * 60);
/*
 * Maximum number of login attempts allowed within time limit.
 */
define('PCH_MAX_TRIES', 3);
/*
 * Prefix of database table names. Use to resolve name conflict when sharing a
 * single database.
 */
define('PCH_TABLE_PREFIX' , 'pch_');
/*
 * SSL host domain. Uncomment to set ssl domain.  If not set, current host
 * is used.  DO NOT include tailing slash.
 * Used on servers where a shared certificate maybe in use like on a
 * shared host.
 */
#define('PCH_WSS_DOMAIN', '192.168.1.1');
/*
 * Un-comment when content negotiation is not available. If page-not-found
 * errors are appearing it is likely this should be un-commented and
 * set to '.php'. 
 */
$pch->assign('file_ext', '.php');
/*
 * Whether username login field should be hidden with '*'. This is enable to
 * prevent exposure of key in the case when user fails to advance to 
 * password field before entering password.  Which would result in key begin
 * exposed in plain text on screen.
 * true or false
 */
$pch->assign('hide_login', true);
/*
 * Date format used. 
 * @link http://smarty.php.net/manual/en/language.modifier.date.format.php
 *       date_format (Smarty online manual)
 * @link http://www.opengroup.org/onlinepubs/007908799/xsh/strftime.html
 * Sample value options: 
 * %a, %d %b %Y %T       -> Wed, 31 Aug 2005 23:31:20
 * %a, %d %b %Y %I:%M %p -> Wed, 31 Aug 2005 11:29 PM
 * %D %I:%M %p           -> 08/31/05 11:32 PM
 */
$pch->assign('date_format', '%a, %d %B %I:%M %p');
/*
 * Desired algorithm used for encryption.
 * Once database is setup this cannot be changed. Do so will prevent data from 
 * being accessed and decrypted.
 * Value options: bf-cbc, grasshopper-cbc
 */
$pch->algorithm('grasshopper-cbc');

/*
 * Desired hash-algorithm (method digest) used with algorithm option for encryption.
 * Once database is setup this cannot be changed. Do so will prevent data from 
 * being accessed and decrypted.
 * Value options: md5, md_gost12_256
 * Default value: md5
 */
$pch->digestalgo('md_gost12_256');

// -----------------------------------------------------------------------------

/*
 * Debugging mode.  When set to true will create a popup with parse & log 
 * information. Set the value to false when running live server. Please note,
 * no password information will be displayed.
 * 
 * Second parameter for ::debug determines the display style.
 * Currently supported modes are 'html' and 'popup'.
 */
$pch->debug(false, 'html');

// -----------------------------------------------------------------------------

?>
