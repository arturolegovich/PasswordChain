<?php
/**
 * Validate global data.
 *
 * Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package   GoGo_Web_Application
 * @version   $Id: ValidateRequests.class.php,v 1.19 2006/02/27 04:39:51 gogogadgetscott Exp $
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 */
/**#@+
 * Request source flags.
 */
define('VR_ANY'         , 1000);
define('VR_COOKIE'      , 1001);
define('VR_GET'         , 1002);
define('VR_POST'        , 1003);
define('VR_POST_COOKIE' , 1004);
define('VR_ARGV'        , 1005);
/**#@-*/

/**#@+
 * Validate type flags.
 */
define('VR_NONE'        , 2000);
/**
 * Any ANSI printable characters (/^[^\x-\x1F]+/i).
 */
define('VR_STRING'      , 2001);
define('VR_ALPHA'       , 2002);
define('VR_DIGIT'       , 2003);
define('VR_NUMBER'      , 2004);
define('VR_MIXED'       , 2005);
/**
 * Any of the 95 ANSI printable characters except spaces (/^[\41-\176]+/i).
 */
define('VR_PASSWORD'    , 2006);
define('VR_URL'         , 2007);
define('VR_EMAIL'       , 2008);
/**#@-*/

/**
 * ValidateRequests class. No dependences.
 *
 * @package    GoGo_Web_Application
 * @version    v1.0.3, 05/20/2005 -- 09/26/2005
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 */
class ValidateRequests
{ // BEGIN class ValidateRequests

/**
 * @var    bol Whether magic quotes is turned on.
 * @access private
 */
var $_magic_quotes;

/**
 * @var    string  Value of last request variable retrieved.
 *                 Used to preform additional validating tests.
 * @access private
 */
var $_last_value;

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 * Pass setting options:
 *  $validate = new ValidateRequests($param);
 *
 * @param  array   Various setting options.
 * @return obj     New instance of class.
 * @access private
 */
function __construct($param = array())
{
    if (!is_array($param)) {
        $param = array();
    }
    $this->_magic_quotes = get_magic_quotes_gpc();
    $this->_strip_vars($_COOKIE);
    $this->_strip_vars($_GET);
    $this->_strip_vars($_POST);
    /*
     * Rebuild $_REQUEST.
     */
    unset($_REQUEST);
    $_REQUEST = array_merge($_COOKIE, $_POST, $_GET, $_ENV, $_SERVER);
}

/**
 * Destructor
 *
 * @return void
 * @access private
 */
function __destruct()
{
}

// -----------------------------------------------------------------------------
//                               Main methods.
// -----------------------------------------------------------------------------

/**
 * Get requested data.
 *
 * @param  string Field name to retrieve. Unused when using source directly.
 * @param  mixed  Flas indicating allowable Source of data.
 *                May pass source itself as string.
 * @return mixed  Requested data.
 * @access public
 */
function reqData($fieldName, $source = VR_ANY, $type = VR_NONE, $default = '')
{ // BEGIN function reqData
    $return   = '';
    $typecast = false;
    $sourceType = strtoupper($source);
    switch ($sourceType) {
    case VR_COOKIE:
        if (isset($_COOKIE[$fieldName])) {
           $return = $_COOKIE[$fieldName];
        }
        break;
    case VR_GET:
        if (isset($_GET[$fieldName])) {
            $return = $_GET[$fieldName];
        }
        break;
    case VR_POST:
        if (isset($_POST[$fieldName])) {
           $return = $_POST[$fieldName];
        }
        break;
    case VR_POST_COOKIE:
        if (isset($_POST[$fieldName])) {
            $return = $_POST[$fieldName];
        } elseif (isset($_COOKIE[$fieldName])) {
            $return = $_COOKIE[$fieldName];
        }
        break;
    case VR_ARGV:
        $value = $this->_argv($fieldName);
        if ($value) {
            $return = $value;
        }
        break;
    case VR_ANY:
        if (isset($_REQUEST[$fieldName])) {
            $return = $_REQUEST[$fieldName];
        } else {
            $value = $this->_argv($fieldName);
            if ($value) {
                $return = $value;
            }
        }
        break;
    default:
        $return = $source;
    }
    if ($return !== false && !empty($type)) {
        if (is_array($type)) {
            if (!in_array($return, $type)) {
                $return = '';
            }
        } else {
            switch($type) {
            case VR_STRING:
                $_regex = "[^\x-\x1F]+";
                break;
            case VR_ALPHA:
                $_regex = "[a-z]+";
                break;
            case VR_DIGIT:
                $_regex = "\d+";
                break;
            case VR_NUMBER:
                $_regex = "-*\d+\.*\d*";
                 break;
            case VR_MIXED:
                $_regex = "[a-z0-9\.]+";
                break;
            case VR_PASSWORD:
                $_regex = "[\41-\176]+";
                break;
            case VR_URL:
                $protocols = "(http|https|ftp)";
                $ipv4 = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
                $ipv6 = '([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}|[A-Fa-f0-9]{1,4}::([A-Fa-f0-9]{1,4}:){0,5}[A-Fa-f0-9]{1,4}|([A-Fa-f0-9]{1,4}:){2}:([A-Fa-f0-9]{1,4}:){0,4}[A-Fa-f0-9]{1,4}|([A-Fa-f0-9]{1,4}:){3}:([A-Fa-f0-9]{1,4}:){0,3}[A-Fa-f0-9]{1,4}|([A-Fa-f0-9]{1,4}:){4}:([A-Fa-f0-9]{1,4}:){0,2}[A-Fa-f0-9]{1,4}|([A-Fa-f0-9]{1,4}:){5}:([A-Fa-f0-9]{1,4}:){0,1}[A-Fa-f0-9]{1,4}|([A-Fa-f0-9]{1,4}:){6}:[A-Fa-f0-9]{1,4}';
                $domain = '\S+';
                $_regex = "$protocols:\/\/($ipv4|$ipv6|$domain)(\/\S*)?";
                break;
            case VR_EMAIL:
                $user = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\|\{\}~\']+';
                $doisValid = '(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9]\.?)+';
                $ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
                $ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';
                $_regex = "$user@($doisValid|(\[($ipv4|$ipv6)\]))";
                break;
            case VR_NONE:
            default:
                $_regex = '[\s\S]*';
            }
            if (!preg_match("/^$_regex$/i", $return)) {
               $return = '';
            }
        }
    }
    if ($return == '' && !empty($default)) {
        $return = $default;
    }
    $this->_last_value = $return;
    return $return;
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
function isLength($value = false, $min = 0, $max = 1)
{ // BEGIN function isLength
    if ($value === false) {
        $value = $this->_last_value;
    }
    $length = strlen($value);
    if ($length >= $min && $length <= $max) {
        return true;
    }
    return false;
} // END function isLength

// -----------------------------------------------------------------------------
//                              Private methods.
// -----------------------------------------------------------------------------

/** 
 * Handle slashes when magic quotes option is turned on.
 *
 * @param  mixed  Variables to strip.
 * @access private
 */
function _strip_vars(&$var)
{
    if (is_array($var)) {
        foreach ($var as $key=>$value) {
            $this->_strip_vars($var[$key]);
        }
    } else {
        if ($this->_magic_quotes) {
            $var = stripslashes($var);
        }
    }
}

/**
 * Single line description of function argv.
 *
 * Multi line
 * description
 * of function argv.
 *
 * @param $key
 * @return mixed
 * @access private
 */
function _argv($key)
{ // BEGIN function _argv
    global $argv;
    if(!isset($argv) || !is_array($argv)) {
        return false;
    }
    foreach ($argv as $index=>$cmd) {
        $cmd = strtolower($cmd);
        if ($cmd == "/$key" || $cmd == "-$key" || $cmd == "--$key") {
            if (isset($argv[$index + 1])) {
                return $argv[$index + 1];
            }
        }
    }
    return false;
} // END function _argv

} // END class ValidateRequests

?>