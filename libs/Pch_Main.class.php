<?php
/**
 * Main class. 
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
 * @package    phpChain
 * @version    $Id: Pch_Main.class.php,v 1.56 2006/01/31 01:50:13 gogogadgetscott Exp $
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
/**
 * Main class.
 *
 * @package    phpChain
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 */
class Pch_Main extends Pch_Control
{  // BEGIN class Pch_Main

// -----------------------------------------------------------------------------
//                             Object Variables.
// -----------------------------------------------------------------------------

/**
 * @var    bool Whether to display descriptive error messages.
 * @access private
 */
var $_debug;

/**
 * @var    obj Timer object.
 * @access private
 */
var $_timer;

/**
 * @var    obj Header object.
 * @access private
 */
var $_header;

/**
 * @var    obj Smarty object.
 * @access private
 */
var $_smarty;

/**
 * @var    obj Validate object.
 * @access private
 */
var $_validate;

/**
 * @var    string  Template theme, analogous with folder name.
 * @access private
 */
var $_theme;

/**
 * @var    string  Determines how debug data is displayed.
 * @access private
 */
var $_debug_style;

/**
 * @var    string  DataBase Driver for SQL.
 * @access private
 */
var $_dbDriver;

/**
 * @var    string  DataBase File for sqlite.
 * @access private
 */
var $_dbFile;

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Class Constructor. 
 *
 * These automatically get set with each new instance. 
 *
 * @return obj     New instance of class.
 * @access private
 */
public function __construct($param = array())
{
    /*
     * Initialize Pch_Control class.
     */
    parent::__construct($param);
    /*
     * Initialize timer object.
     */
    $this->_timer = new Benchmark_Timer();
    $this->_timer->start();
    /*
     * Initialize HTTP header object. 
     */
    $this->_header = new GoGo_Send_HTTP_Headers();
    /*
     * Initialize Smarty object.
     */
    $this->_smarty = new Smarty();
    /*
     * Setup paths.
     */
    $this->_smarty->cache_dir   = PCH_FS_CACHE;
    /*
     * Samrty properties.
     */
    $this->_smarty->caching       = false;
    $this->_smarty->compile_check = true;
    /*
     * Define Smarty variables.
     */
    $this->assign('app_name'  , 'PasswordChain');
    $this->assign('app_ver'   , PCH_VERSION);
	$this->assign('openssl_ver'   , $this->openssl_version());
    $this->assign('app_url'   , 'https://phpchain.ru/');
    $this->assign('date_now'  , gmdate('D, d M Y H:i:s') . ' GMT');
    $this->assign('file_ext'  , '');
    /*
     * Define Smarty template files.
     */
    $this->assign('file_errors', 'errors.tpl');
    $this->assign('groups_form', 'groups_form.tpl');
    $this->_smarty->debug_tpl = PCH_FS_HOME . 'templates' . DIRECTORY_SEPARATOR
        . 'debug.tpl';
    /*
     * Assign default Smarty variables.
     */
    $this->assign('auth'       , false);
    $this->assign('login'      , '');
    $this->assign('user'       , '');
    $this->assign('errors'     , array());
    $this->assign('msgs'       , array());
    $this->assign('site_name'  , 'phpChain');
    $this->assign('hide_login' , true);
    $this->assign('date_format', '%a, %d %b %Y %I:%M %p');
    /*
     * Initialize validate object. Used to retrieve user input.
     */
    $this->_validate = new ValidateRequests();
    /*
     * Handle errors.
     */
    $PCH_errors = Array (
        1 => 'Unable to delete. Remove entries from category first'
        );
    $error = $this->reqData('error');
    if (!empty($error)) {
        $error = $PCH_errors[$error];
    } else {
        $error = '';
    }
}

// -----------------------------------------------------------------------------
//                        Accessor methods.
// -----------------------------------------------------------------------------


/**
 * Set debug value.
 *
 * @param  mixed  New value.
 * @return void
 * @access public
 */
public function debug($value = false, $style = 'popup')
{
    if (is_null($value)) {
        return $this->_db->debug();
    }
    $this->_debug       = $value;
    $this->_debug_style = $style;
    $this->_db->debug($value);
    $this->_db->suppressError(!$value);
    $this->assign('debug', $value);
    if ($value) {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors' , 1); 
    } else {
         ini_set('error_reporting', !E_ALL);
         ini_set('display_errors' , 0);
    }
}

/**
 * Get or set application theme and smarty paths.
 *
 * @param  string Theme name.
 * @return string Theme name.
 */
public function theme($theme = null)
{
    if (!is_null($theme)) {
        /*
         * Check that path is valid.
         */
        if (empty($theme)
                || !is_dir(PCH_FS_TEMPLATES . $theme)) {

            $theme = null;
        }

        /*
         * Save theme path for using in display method.
         */
        if (!is_null($theme)) {
            $this->_theme = $theme;
            $urlTheme = PCH_WS_TEMPLATES . $this->_theme . '/';
            $this->assign('theme'         , $theme);
            $this->assign('urlTheme'      , $urlTheme);
            $this->assign('urlThemeImages', $urlTheme . 'img/');
            $this->_smarty->compile_dir .= $this->_theme . DIRECTORY_SEPARATOR;
        }

        /*
         * Set Smarty template default directory.
         */
        $this->_smarty->template_dir = PCH_FS_TEMPLATES;
        $this->_smarty->compile_dir  = PCH_FS_TEMPLATES_C;
        $this->_smarty->debug_tpl    = implode(", ",$this->_smarty->template_dir) . 'debug.tpl';
        $this->assign('urlSelf'      , $this->urlSelf(false, false, false));
        $this->assign('urlHome'      , PCH_WS_HOME);
        $this->assign('urlTemplate'  , PCH_WS_TEMPLATES);
        $this->assign('urlImages'    , PCH_WS_IMAGES);
        $this->assign('urlJavaScript', PCH_WS_JS);
    }
    return $this->_theme;
}

// -----------------------------------------------------------------------------
//                        Base class accessor methods.
// -----------------------------------------------------------------------------

/**
 * Assigns values to template variables.
 *
 * @param array|string $tpl_var the template variable name(s)
 * @param mixed $value the value to assign
 */
public function assign($tpl_var, $value = null)
{ // BEGIN function assign
    $this->_smarty->assign($tpl_var, $value);
} // END function assign


/**
 * Executes & displays the template results.
 *
 * @param  string $resource_name
 * @param  string Document name.
 * @param  bool   Whether is display header and footer.
 * @return void
 * @access public
 */
public function display($resource_name, $doc_name = '', $show_templete = true, 
    $xtype = 'text/html')
{
    global $action, $errors, $msgs;
    if (!$this->_debug) {
        $this->_smarty->loadfilter('output', 'trimwhitespace');
    }
    $this->setMarker('Enter Pch_Main::display');
    $resource_name = $resource_name . '.tpl';
    if($this->auth()) {
        $this->assign('groups'  , $this->getGroups());
        $this->assign('settings', $this->getSettings());
    }
    $this->assign('errors'  , $errors);
    $this->assign('msgs'    , $msgs);
    $this->assign('doc_name', $doc_name);
    $this->assign('action'  , $action);
    
    /*
     * Check if page should use special sidebar css.
     */
    $sidebar = $this->reqData('sidebar', VR_ANY, VR_DIGIT, false);
    if ($sidebar) {
        setcookie('sidebar', $sidebar);
    }
    $this->assign('sidebar', $sidebar);

    if ($this->auth()) {
        $this->_header->noCache();
        
        /*
         * Auto-refresh page after cookie expires.
         */
        $urlSelf = $this->urlSelf();
        $this->assign('redirect', $urlSelf);
        $expire = $this->getSettings('expire');
        if ($expire > 0) {
            $this->_header->refresh(($this->getSettings('expire') + 15),
                $urlSelf);
        }
    }
    if ($xtype != false) {
        $this->_header->contentType($xtype);
    }
    
    /*
     * Parse smarty templates. All headers and cookies are now sent.
     * No content many be outputted before this point.
     */
    $this->setMarker('-Start smarty parse');
    $source = '';
    if ($show_templete) {
        $source .= $this->_fetch('header.tpl');
        $this->setMarker('-Completed parse header');
        $source .= $this->_fetch('menu.tpl');
        $this->setMarker('-Completed parse menu bar');
    }
    $source .= $this->_fetch($resource_name);
    $this->setMarker('-Completed parse main content');
    if ($show_templete) {
        $source .= $this->_fetch('footer.tpl');
    }
    $this->setMarker('-Completed parse footer');
    if ($this->_debug && $show_templete) {
        $this->setMarker('-Start debug process');
        $this->assign('_smarty_debug_output', $this->_debug_style);
        $this->assign('_debug_queries' , $this->queries());
        $this->assign('profile'        , $this->_timer->getProfiling(1000));
        $this->assign('debugInfo'      , $this->debugInfo);
        $source .= $this->_fetch('debug.tpl');
    }
    if(extension_loaded('zlib')
        && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
        && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {

        if ($this->_header->gzip()) {    
            $source = gzencode($source, 4);
        }
    }
    $this->_header->contentLength(strlen($source));
    echo $source;
}

/**
 * Fetch template results.
 *
 * @param  string Template resource name.
 * @return string Template results.
 * @access private
 */
private function _fetch($resource_name)
{
    if (!empty($this->_theme)) {
        $tmp_path = $this->_theme . DIRECTORY_SEPARATOR . $resource_name;
        if (file_exists($this->_smarty->template_dir . $tmp_path)) {
            $resource_name = $tmp_path;
        }
    }
    return $this->_smarty->fetch($resource_name);
}

/**
 * Get requested data.
 *
 * @param  string Allowable Source of data.
 * @return mixed  Requested data.
 * @access public
 */
public function reqData($fieldname, $source = VR_ANY, $type = VR_NONE, $default = '')
{ // BEGIN function reqData
    return $this->_validate->reqData($fieldname, $source, $type, $default);
} // END function reqData

/**
 * Test if a value is a given range.
 *
 * @param  string Value being tested.
 * @param  float  Minimum value.
 * @param  float  Maximum value.
 * @return bool   
 * @access public
 */
public function isLength($value = '', $min = 0, $max = 1)
{ // BEGIN function isLength
    return $this->_validate->isLength($value, $min, $max);
} // END function isLength

/**
 * Set timer class marker.
 *
 * @param  string Name of the marker to be set.
 * @access public
 */
public function setMarker($name = '')
{ // BEGIN function setMarker
    $this->_timer->setMarker($name);
} // END function setMarker

/**
 * Prints profile information.
 *
 * @access public
 */
public function displayProfile()
{ // BEGIN function displayProfile
    $this->_timer->display();
} // END function displayProfile

/**
 * Return duration in determined time unit.
 *
 * @param integer Start time.
 * @param integer End time.
 * @param string Units
 * @return string
 * @access public
 */
public function getDuration($start, $end, $units = false)
{ // BEGIN function getDuration
    return $this->_timer->getDuration($start, $end, $units);
} // END function getDuration

// -----------------------------------------------------------------------------
//                              General methods.
// -----------------------------------------------------------------------------

/**
 * Header redirect.
 *
 * @param  string URL
 * @return void
 * @access public
 * @todo echo html redirect w/ href link in the case that the header fails.
 */
public function redirect($url = '')
{ // BEGIN function redirect
    if (empty($url)) {
        $url = PCH_WS_HOME . 'index.php';
    }
    $this->_header->location($url);
    exit;
} // END function redirect

/**
 * Retrieve current url w/ parameters.
 *
 * @param array  Additional parameters where key is name.
 * @param bool   Whether just a directory should be returned, striping
 *               parameters and file name. This overrides any other arguments.
 * @param string An alternate host.
 * @param string Whether to force ssl url.
 * @return string Current url w/ parameters.
 */
public function urlSelf($newVars = false, $strip = false, $host = '',
    $fource_ssl = false)
{
    $urlSelf = '';
    /*
     * Setup site paths and names.
     * Try REQUEST_URI first to allow the use of Apache's mod_rewrite and 
     * MultiViews.
     */
    $sev_var = array('REQUEST_URI', 'SCRIPT_NAME', 'PHP_SELF');
    foreach ($sev_var as $key) {
        if (empty($urlSelf)) {
            if (isset($_SERVER[$key])) {
                $urlSelf = $_SERVER[$key];
            } else {
                $urlSelf = getenv($key);
            }
        } else {
            break;
        }
    }
    if (empty($urlSelf)) {
        $urlSelf = getenv('PATH_INFO');
    }
    if (empty($urlSelf)) {
        $urlSelf = './';
    }
    $urlSelf = strip_tags($urlSelf);  
    if ($host !== false) {
        if (empty($host) && isset($_SERVER['HTTP_HOST'])) {
            $urlSelf = $_SERVER['HTTP_HOST'] . $urlSelf;
        } else {
            $urlSelf = $host . $urlSelf;
        }
        if ((defined('PCH_WS_SSL') && PCH_WS_SSL) || $fource_ssl) {
            $urlSelf = 'https://' . $urlSelf;
        } else {
            $urlSelf = 'http://' . $urlSelf;
        }
    }
    if ($strip == true) {
        $qstart = strrpos($urlSelf, '/');
        $urlSelf = substr($urlSelf, 0, $qstart + 1);
        return $urlSelf;
    }
    $qstart = strpos($urlSelf, '?');
    if ($qstart === false) {
        $beginning = $urlSelf;
        $ending = (isset($_SERVER['QUERY_STRING']))
            ? $_SERVER['QUERY_STRING']
            : '';
    } else {
        $beginning = substr($urlSelf, 0, $qstart);
        $ending    = substr($urlSelf, $qstart + 1);
    }
    if (is_array($newVars)) {
        $vals = array();
        parse_str($ending, $vals);
        foreach ($newVars as $varKey=>$varValue) {
            $vals[$varKey] = $varValue;
        }
        $ending = '';
        $count  = 0;
        foreach($vals as $varKey=>$varValue) {
            if ($count > 0) {
                $ending .= '&';
            } else {
                $count++;
            }
           $ending .= "$varKey=" . urlencode($varValue);
        }
    }
    $urlSelf = $beginning;
    if (!empty($ending)) {
        $urlSelf .= '?' . $ending;
    }
    /*
     * Check if url has an extra slash, if so get ride of it. This cause 
     * relative link to fail.
     */
    if (strlen($urlSelf) > 7 && strpos($urlSelf, '//', 7) !== false) {
        $urlSelf = str_replace("//", "/", $urlSelf);
        $this->redirect($urlSelf);
    }
    return $urlSelf;
}

/**
 * Check database setup.
 *
 * @return bool true is database is setup correctly.
 */
public function checkSetup()
{ // BEGIN function checkSetup
    /**
     * @todo Investigate fast method to determine if db is setup for app.
     */
    if ($this->statusTable(PCH_TABLE_USERS)) {
        return true;
    } else {
        if (strpos($this->urlSelf(), 'setup') === false) {
           $this->deleteCookie();
           $this->redirect(PCH_WS_HOME . 'setup.php');
        }
        return false;
    }
} // END function checkSetup


// -----------------------------------------------------------------------------
//                                  Cookies.
// -----------------------------------------------------------------------------

/**
 * Get user data from cookies.
 *
 * @return void
 * @access public
 */
public function getCookies()
{
    $data = $this->_validate->reqData(PCH_COOKIE_DATA, VR_COOKIE, VR_MIXED);
    $data = explode('.', $data);
    if (count($data) != 3) {
        return;
    }
    $this->userid(null, $this->_validate->reqData(false, $data[0], VR_STRING));

    /*
     * Key must be set before logid due to the need to decrypt settings.
     */
    $this->key($this->_validate->reqData(false, $data[1], VR_PASSWORD));

    $this->logid($this->_validate->reqData(false, $data[2], VR_MIXED));

    /*
     * Check log was valid and not expired.
     */
    $logid = $this->logid();
    if (empty($logid)) {
        $this->deleteCookie();
        return;
    }

}

/**
 * Set cookie.
 *
 * @return Whether cookie are set.
 * @access public
 */
public function setCookies()
{    
    if (!$this->auth()) {
        $this->deleteCookie();
        return false;
    }
    if (!headers_sent()) {
        /*
         * Set expire time based on settings.
         */
        $exire = $this->getSettings('expire');
        if ($exire <= 0) {
            $exp_time = null;
        } else {
            $exp_time = time() + $exire;
        }
        /*
         * This will ensure the cookie is restricted to current path. Only when
         * debugging is the cookie available within the entire domain. This is
         * for auto-login applications.
         */
        if ($this->_debug) {
            $path = '/';
        } else {
            $path = '';
        }
        $data = $this->userid() . '.' . $this->key() . '.' . $this->logid();
        setcookie(PCH_COOKIE_DATA, $data, $exp_time, $path, '',
            PCH_WS_SSL);
        return true;
    }
    return false;
}

/**
 * Delete cookie.
 *
 * Un-set cookies
 * Hack to deal with brain dead browsers that don't unset properly.
 * Make sure cookie is full of garbage instead of the key.
 *
 * @return void
 * @access public
 */
public function deleteCookie()
{
    $exp_time = time() - 3600;
    if (!headers_sent()) {
        setcookie(PCH_COOKIE_DATA, md5($exp_time), $exp_time);
    }
    /*
     * Clear Accessors.
     */
    $this->clearAccessors();
    $this->assign('auth', false);
    $this->assign('user', '');
}

/**
 * Get or set application DataBase SQL Driver.
 *
 * @param  string dbDriver name.
 * @return string dbDriver name.
 */

public function dbDriver($dbDriver='mysql')
{
    if (!is_null($dbDriver)) 
            $this->_dbDriver = $dbDriver;
    return $this->_dbDriver;
}

/**
 * Get or set application DataBase File for sqlite.
 *
 * @param  string dbFile path/name.
 * @return string dbFile path/name.
 */

public function dbFile($dbFile=null)
{
    if (!is_null($dbFile)) 
            $this->_dbFile = $dbFile;
    return $this->_dbFile;
}

}  // END class Pch_Main



?>
