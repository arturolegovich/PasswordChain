<?php
/**
 * Defines all global variables & contents, setup requested document,
 * includes required documents, and initializes required classes.
 *
 * This file is part of phpChain.
 *
 * phpChain is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * phpChain is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with phpChain; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package   phpChain
 * @version   $Id: define.php,v 1.71 2006/02/27 04:45:09 gogogadgetscott Exp $
 * @link      http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 * @todo Update docs.
 * @todo Create 2nd template.
 * @todo Allow new group creation on entry page.
 * @todo If add entry without any groups redirect to groups or ...
 * @todo Prevent loading insecure images when using ssl. Maybe cache images.
 * @todo Add popup description for settings.
 * @todo Prevent favicon lookup when changing passwords.
 * @todo Add user interface to delete user.
 */
 
/*
 * Package version.
 */
define('PCH_VERSION', '2.1.2 (OpenSSL)');

/*
 * Check that script is not called directly.
 */
if (isset($_SERVER['SCRIPT_NAME']) &&
    strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false) {

    header('location: ./');
    exit;
}

/*
 * Define file server paths.
 */
if (!defined('PCH_FS_HOME')) {
    define('PCH_FS_HOME', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
define('PCH_FS_INCLUDES'   , PCH_FS_HOME . 'inc'        . DIRECTORY_SEPARATOR);
define('PCH_FS_CONFIGS'    , PCH_FS_HOME . 'config'     . DIRECTORY_SEPARATOR);
define('PCH_FS_CACHE'      , PCH_FS_HOME . 'cache'      . DIRECTORY_SEPARATOR);
define('PCH_FS_TEMPLATES'  , PCH_FS_HOME . 'templates'  . DIRECTORY_SEPARATOR);
define('PCH_FS_TEMPLATES_C', PCH_FS_HOME . 'templates_c'. DIRECTORY_SEPARATOR);
define('PCH_FS_LIBS'       , PCH_FS_HOME . 'libs'       . DIRECTORY_SEPARATOR);
define('PCH_FS_CIPHER'     , PCH_FS_LIBS . 'cipher'     . DIRECTORY_SEPARATOR);
//define('PCH_FS_HORDE'      , PCH_FS_LIBS . 'Horde'      . DIRECTORY_SEPARATOR);
define('SMARTY_DIR'        , PCH_FS_LIBS . 'smarty-3.1.13'     . DIRECTORY_SEPARATOR);
//define('SMARTY_DIR'        , '/smarty-3.1.13'	. DIRECTORY_SEPARATOR);

/*
 * Miscellaneous constants.
 */
define('DBTABLEINCLUDE'  , true);
define('PCH_COOKIE_DATA' , 'PchData');
define('PCH_THEME'       , 'PchTheme');
define('PCH_VERSION_REQ' , '7.0.0');
define('PCH_INVALID_DATA', '-invalid-');
define('PCH_ID_LENGTH'   , 32);

/*
 * Include SQL Database connection information.
 */

$includes_files = array(
    //array(PCH_FS_LIBS      . 'Database_MySQL.class.php',
    //    'Cannot find database file.'),
    array(PCH_FS_LIBS      . 'Database_PDO.class.php',
        'Cannot find PDO database file.'),	
    array(PCH_FS_LIBS      . 'ValidateRequests.class.php',
        'Cannot find Validate Requests file.'),
    array(PCH_FS_LIBS      . 'Pch_Control.class.php',
        'Cannot find crypt class.'),
    array(PCH_FS_LIBS      . 'Pch_Main.class.php',
        'Cannot find main class.'),
    array(PCH_FS_LIBS      . 'Send_HTTP_Headers.class.php',
        'Cannot find header class.'),
    array(PCH_FS_LIBS      . 'Timer.class.php',
        'Cannot find timer class.'),
    array(PCH_FS_CIPHER    . 'Pch_Cipher.class.php',
        'Cannot find cipher base class.'),
    array(SMARTY_DIR       . 'Smarty.class.php',
        'Cannot find Smarty.'),
    );
foreach ($includes_files as $value) {
    if (is_file($value[0])) {
        require_once $value[0];
    } else {
        exit("$value[1]\n");
    }
}

/*
 * Initialize objects and message arrays.
 */
$pch    = new Pch_Main();

$errors = array();
$msgs   = array();
$pch->setMarker('Loaded library files');

/*
 * Check for SSL use.
 */
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    define('PCH_WS_SSL', true);
} else {
	/**
	 * @ignore
	 */
    define('PCH_WS_SSL', false);
}

/*
 * Define web server paths.
 */
if (!defined('PCH_WS_HOME')) {
    define('PCH_WS_HOME', $pch->urlSelf(false, true));
}
define('PCH_WS_TEMPLATES' , PCH_WS_HOME 	 . 'templates/');
define('PCH_WS_IMAGES'    , PCH_WS_TEMPLATES . 'img/');
define('PCH_WS_JS'        , PCH_WS_HOME      . 'js/');

/*
 * Set default theme.
 */
$pch->theme('');

/*
 * Get action.
 */
$action = $pch->reqData('action', VR_ANY, VR_STRING);

/* 
 * Import configuration file.
 */
@include PCH_FS_CONFIGS . 'config.php';
$pch->setMarker('Loaded configuration file');

/*
 * Assign default constants if not set in configuration file.
 */
if (!defined('PCH_TIME_LIMIT')) {
    define('PCH_TIME_LIMIT', 10 * 60);
}
if (!defined('PCH_MAX_TRIES')) {
    define('PCH_MAX_TRIES', 3);
}
if (!defined('PCH_TABLE_PREFIX')) {
    define('PCH_TABLE_PREFIX' , 'pch_');
}

/*
 * Handle themes.
 */
$themes = array();
if ($dh = opendir(PCH_FS_TEMPLATES)) {
    while (($file = readdir($dh)) !== false) {
        $fullpath = PCH_FS_TEMPLATES . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullpath) && substr($file, 0, 1) !== '.'
                && $file != 'img' && $file != 'CVS') {

            /*
             * Found a folder.
             */
            $themes[] = $file;
        }
    }
    closedir($dh);
}

$pch->assign('themes', $themes);


$theme = $pch->reqData('theme', VR_POST, VR_ALPHA, PCH_INVALID_DATA);
if ($theme != PCH_INVALID_DATA) {
    $pch->theme($theme);
    setcookie(PCH_THEME, $pch->theme());
} else {
    $theme = $pch->reqData(PCH_THEME, VR_COOKIE, VR_ALPHA, PCH_INVALID_DATA);
    if ($theme != PCH_INVALID_DATA) {
        $pch->theme($theme);
    }
}

/*
 * Load tables information.
 */
$includes_files = array(
    array(PCH_FS_INCLUDES  . 'db.tables.inc.php', 
        'Cannot find database table information file.')
    );
foreach ($includes_files as $value) {
    if (is_file($value[0])) {
        include $value[0];
    } else {
        exit($value[1]);
    }
}
$pch->setMarker('Loaded database tables');
$pch->checkSetup();
$pch->setMarker('Complete setup check');
if (PCH_WS_SSL == false) {
    /*
     * Set ssl domain address. PCH_WSS_DOMAIN may be defined in configuration
	 * file.
     */
    if (defined('PCH_WSS_DOMAIN')) {
         $host = PCH_WSS_DOMAIN;
	} else {
         $host = '';
    }
    $url_ssl = $pch->urlSelf(false, true, $host, true);
    $pch->assign('url_ssl', $url_ssl);
    if (defined('PCH_USE_SSL') && PCH_USE_SSL) {
        $pch->redirect($url_ssl);
    }
}

/*
 * Get started with authorization process.
 */
$pch->setMarker('Get cookies');
$pch->getCookies();
$pch->setMarker('Done w/ cookies');
if ($pch->auth()) {
    /*
     * Reset cookie expiration time.
     */
    $pch->setCookies();
    
    /*
     * Get some passed data.
     */
    $groupid = intval($pch->reqData('groupid', VR_ANY, VR_NUMBER, 0));
    $entryid = $pch->reqData('entryid', VR_ANY, VR_STRING, 0);
    
    /*
     * Define Smarty variables.
     */
    $pch->assign('auth'   , true);
    $pch->assign('user'   , $pch->user());
    $pch->assign('groupid', $groupid);
    $pch->assign('entryid', $entryid);
}

$pch->setMarker('Completed define');

?>
