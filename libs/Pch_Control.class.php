<?php
/**
 * EN: Control class that encapsulates database control to access and process data.
 * RU: Класс для работы с базой данных.
 *
 */
class Pch_Control
{ // BEGIN class Pch_Control

// -----------------------------------------------------------------------------
//                             Variables.
// -----------------------------------------------------------------------------

/**
 * @var    obj Database object.
 * @access private
 */
var $_db;

/**
 * @var    obj Encrypt/Decrypt object.
 * @access private
 */
var $_crypt;

/**
 * @var    string Store algorithm type.
 * @access private
 */
private $_algorithm;

/**
 * @var    string Store hash method.
 * @access private
 */
var $_digestalgo;

/**
 * @var    bool    Store authorization value.
 * @access private
 */
var $_auth;

/**
 * @var    int User id.
 * @access private
 */
var $_userid;

/**
 * @var    string User name.
 * @access private
 */
var $_user;

/**
 * @var    string  Hash of decrypt key.
 * @access private
 */
var $_key;

/**
 * @var    string Log id.
 * @access private
 */
var $_logid;

/**
 * @var    string  Prefix key to make unique to site.
 * @access private
 */
var $_keyPrefix;

/**
 * @var    array  List of entry values to encrypt.
 * @access private
 */
var $_evalues;

/**
 * @var  string User's IP Address
 * @access private
 */
var $_ipAddress;

/**
 * Cached group list.
 *
 * @var    array Array of groups.
 * @access private
 */
var $_groups;

/**
 * Cached user settings.
 *
 * @var    array Array of settings.
 * @access private
 */
var $_settings;

/**
 * Number of entries returned from call to getEntries.
 *
 * @var int.
 * @access private
 */
var $_entriesLstCnt;

/**
 * Debug infomation, print when in debug mode.
 *
 * @var    string
 * @access public
 */
var $debugInfo;

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 *
 * @param  array   Various setting options.
 * @return obj     New instance of Pch_Control class
 * @access private
 */
function __construct($param = array())
{
    /*
     * Initialize database object.
     */
    //$this->_db = new Database_MySQL($param); // old
	$this->_db = new Database_PDO($param);
	

    /*
     * Clear Accessors.
     */
    $this->clearAccessors();
    $this->keyPrefix('');
    $this->debugInfo = '';
    /*
     * Set algorithm.
     */
    if (isset($param['algorithm'])) {
        $this->algorithm($param['algorithm']);
    } else {
        $this->algorithm();
    }
	
    $this->_evalues = array('login', 'password', 'title', 'url', 'notes');
}

/**
 * Destructor
 *
 * @return void
 * @access private
 */
function __destruct()
{
    $this->_db->__destruct();
}

// -----------------------------------------------------------------------------
//                                Properties.
// -----------------------------------------------------------------------------

public function openssl_version()
{
  return $this->_crypt->openssl_version();
}

/**
 * Setup encryption sub-class based on desired algorithm or get current
 * algorithm.
 *
 * @param  string algorithm.
 * @return string algorithm.
 * @access public
 * @todo Setup error handling in recieving (Pch_Main) class.
 */
public function algorithm($value = null)
{
  $this->_crypt = new Pch_Cipher($value);
  return $this->_handleProperties('_algorithm', $value);
}

/**
 * Get authorization value.
 *
 * @return bool
 * @access public
 */
function auth($value = null)
{
    /*
     * Check if user has been authorized or check if used is authorized.
     */
    if ($this->_auth == true) {
        return $this->_auth;
    } else {
        return $this->checkUser();
    }
    
}

/**
 * Get or set logid value.
 *
 * When setting logid, user is set based on log.
 *
 * @param string Used to set current logid.
 * @return string Current logid.
 * @access public
 */
function logid($value = null)
{
    if (!is_null($value)) {
        $userLogs = $this->getLogs(1, false, 1, false, $value);
        if (isset($userLogs[0])) {
            $expire = $this->getSettings('expire');
            $timestamp = time() - $expire;
            /*
             * Check log, ensuring it is not expired and matchs user.
             */
            if ($this->user() != $userLogs[0]['user'] ||
                ($expire > 0 && $userLogs[0]['refreshed'] < $timestamp)) {

                /*
                 * Log is expired or does not match user.
                 */
                $value = null;
            } else {
                $value = $userLogs[0]['logid'];
            }
        } else {
            /*
             * Log is invalid.
             */
            $value = null;
        }
    }
    return $this->_handleProperties('_logid', $value);
}

/**
 * Get or set userid value.
 *
 * @param string User name. If false, current user name is used.
 * @param integer Used to set current userid.
 * @return int false if no such user exists.
 * @access public
 */
function userid($user = null, $userid = null)
{
    if (!is_null($user)) {
        $result = $this->_db->select(PCH_TABLE_USERS,
            "user = '$user'", '', '', 'userid');
        $row = $this->_db->fetchAssoc($result);
        if (isset($row['userid'])) {
            $userid = $row['userid'];
        } else {
            return false;
        }
    } elseif (!is_null($userid)) {
        $this->_userid = $userid;
    } elseif ($this->_userid === false) {
        $user = $this->user();
        if (empty($user)) {
            return false;
        }
        $result = $this->_db->select(PCH_TABLE_USERS,
            "user = '$user'", '', '', 'userid');
        $row = $this->_db->fetchAssoc($result);
        if (isset($row['userid'])) {
            $userid = $row['userid'];
            $this->_userid = $userid;
        } else {
            return false;
        }
    } else {
        $userid = $this->_userid;
    }
    return $userid;
}

/**
 * Get or set user.
 *
 * @return mixed User name or false.
 * @access public
 */
function user($value = null)
{
    if (!is_null($value)) {
        $this->_user = $value;
        $this->_auth = false;
        $this->userid();
    } elseif ($this->_user === false && $this->_userid !== false) {
        $userid = $this->userid();
        if (empty($userid)) {
            return false;
        }
        $result = $this->_db->select(PCH_TABLE_USERS,
            "userid = '" . $this->userid() . "'", '', '', "user");
        $row = $this->_db->fetchAssoc($result);
        if (isset($row['user'])) {
            $this->_user = $row['user'];
        } else {
            return false;
        }
    }
    return $this->_user;
}

/**
 * Get or set key value.
 *
 * @param  string Key, hash of plaintext login password with site wide key
 *                prefix.
 * @return string
 * @access public
 */
function key($value = null)
{
    if (!is_null($value)) {
        $this->_auth = false;
    }
    return $this->_handleProperties('_key', $value);
}

/**
 * Return number of entries returned from call to getEntries.
 *
 * @return integer
 */
function entriesLstCnt($value = null)
{ // BEGIN function entriesLstCnt
    return $this->_handleProperties('_entriesLstCnt', $value);
} // END function entriesLstCnt

/**
 * Get or set user's ip address.
 * 
 * @return string User's IP address.
 * @access public
 */
function ipAddress($value = null)
{ // BEGIN function ipAddress
    if (is_null($value) && !$this->_ipAddress) {
        if (isset($_SERVER)) {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $value = $_SERVER['REMOTE_ADDR'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IPAddress'])) {
                $value = $_SERVER['HTTP_CLIENT_IPAddress'];
            }
        } else {
            if (getenv('REMOTE_ADDR')) {
                $value = getenv('REMOTE_ADDR');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $value = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IPAddress')) {
                $value = getenv('HTTP_CLIENT_IPAddress');
            } else {
                $value = '';
            }

        }
    }
    return $this->_handleProperties('_ipAddress', $value);
} // END function ipAddress

/**
 * Get or set key prefix value.
 *
 * @param  string  New value.
 * @return string
 * @access public
 */
function keyPrefix($value = null)
{ // BEGIN function keyPrefix
    return $this->_handleProperties('_keyPrefix', $value);
} // END function keyPrefix

/**
 * Set debug value.
 *
 * @param  bool New value.
 * @return void
 * @access public
 */
function debug($value = false)
{ // BEGIN function debug
    if (is_null($value)) {
        return $this->_db->debug();
    }
    $this->_db->debug($value);
    $this->_db->suppressError(!$value);
    if ($value) {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors' , 1); 
    } else {
        ini_set('error_reporting', ~E_ALL);
        ini_set('display_errors' , 0);
    }
    return $value;
} // END function debug

/**
 * Clear Accessors.
 *
 * @return void
 */
function clearAccessors()
{
    $this->_auth             = false;
    $this->_logid            = false;
    $this->_userid           = false;
    $this->_user             = false;
    $this->_key              = false;
    $this->_ipAddress        = false;
    $this->_settings         = false;
    $this->_groups           = false;
    $this->_entriesLstCnt    = 0;
    $this->_login_max_tries  = 3;
}

// -----------------------------------------------------------------------------
//                        Base class accessor methods.
// -----------------------------------------------------------------------------

/**
 * Get or set database host value.
 *
 * @param  mixed  New value.
 * @return bool   false
 * @access public
 */
function dbHost($value = null)
{
    return $this->_db->dbHost($value);
}

/**
 * Get or set database name value.
 *
 * @param  mixed  New value.
 * @return bool   false
 * @access public
 */
function dbName($value = null)
{
    return $this->_db->dbName($value);
}

/**
 * Get or set database username value.
 *
 * @param  mixed  New value.
 * @return bool   false
 * @access public
 */
function dbUsername($value = null)
{
    return $this->_db->dbUsername($value);
}

/**
 * Get or set database password value.
 *
 * @param  mixed  New value.
 * @return bool   false
 * @access public
 */
function dbPassword($value = null)
{
    return $this->_db->dbPassword($value);
}

/**
 * Create a new database.
 *
 * @param  string Database name to create.
 * @return bool
 */
function createDatabase($name = '')
{
    return $this->_db->createDatabase($name);
}

/**
 * Create a new database.
 *
 * @param  string Database name to create.
 * @return bool
 */
function deleteDatabase($name = '')
{
    return $this->_db->deleteDatabase($name);
}

/**
 * Check if database exists.
 *
 * @param string   Database name check for.
 * @return bool
 */
function existsDatabase($name = '')
{ // BEGIN function existsDatabase
    return $this->_db->existsDatabase($name);
} // END function existsDatabase

/**
 * Create tables.
 *
 * @param  array   Collections of table schemas.
 * @return bool    Returns true on success or false on failure.
 * @access public
 */
function createTables($tables = array(), $drop = false)
{
    return $this->_db->createTables($tables, $drop);
}

/**
 * Retrieve detailed list of information for a given table.
 *
 * @param  string Table name.
 * @param  string Key name of information to retrieve.
 * @return mixed  Array will be returned if not key is specified, else value
 * of key.
 */
function statusTable($table, $key = '')
{
    return $this->_db->statusTable($table, $key);
}

/**
 * Get array of executed queries/statements.
 *
 * @return arrray Quires and times.
 *                [0] = query; [1] = query time
 * @access public
 */
function queries()
{
    return $this->_db->queries();
}

// -----------------------------------------------------------------------------
//                    General Group Methods.
// -----------------------------------------------------------------------------

/**
 * Add new group for current user.
 *
 * Group id used when saving changes. Group id of 0 will create new.
 *
 * @param  array  groupid, title.
 * @param  string Alternate key used for encryption. 
 * @return int    Int of saved group id on success or bool false.
 * @todo Integrate hierarchy layout.
 */
function addGroup($group, $key = false)
{ // BEGIN function addGroup
    if (!$key) {
        $key = $this->key();
    }
    if (!$key) {
        return false;
    }
    if (is_string($group)) {
        $group = array('title' => $group);
    }
    $data = array(
        'userid'  => $this->userid(),
        'parentid'=> 0,
        'iconid'  => 0,
        'title'   => $group['title']
        );
    /*
     * Encrypt group.
     */
    if ($this->_encryptGroup($data) != true) {
        return false;
    }
    /*
     * Clear internal cache.
     */
    $this->_groups = false;
    /*
     * Preform insert/update.
     */     
    if (!isset($group['groupid']) || $group['groupid'] <= 0) {
        $group['groupid'] = $this->_db->insertArray(PCH_TABLE_GROUPS, $data);
    } else {
        $where = "groupid = '" . $group['groupid'] . "' AND userid = '"
            . $this->userid() . "'";
        $group['groupid'] = $this->_db->updateArray(PCH_TABLE_GROUPS, $data,
            $where);
    }
    return $group['groupid'];
} // END function addGroup

/**
 * Add new groups for current user.
 *
 * @param  array groupid, title.
 * @param  string Alternate key used for encryption. 
 * @return int   Int of saved group id on success or bool false.
 * @todo Integrate hierarchy layout.
 */
function addGroups($groups, $key = false)
{ // BEGIN function addGroups
    if (is_string($groups)) {
        $groups = array('title' => $groups);
    }
    if (isset($groups['title'])) {
        $groups = array($groups);
    }
    $lastID = 0;
    foreach ($groups as $group) {
        $lastID = $this->addGroup($group, $key);
    }
    return $lastID;
} // END function addGroups

/**
 * Delete group(s) and all entries based on groupid.
 *
 * @param  int  Group id to delete. No group can have id of 0. Passing an id e
 *              equal to -1 deletes all groups.
 * @return bool true on success.
 */
function deleteGroups($groupids = false, $group = false)
{ // BEGIN function deleteGroups
    if ($groupids === false) {
        $groupids = $this->getGroupid($group);
    }
    if (is_int($groupids)) {
        $groupids = array($groupids);
    } elseif (!is_array($groupids) || count($groupids) <= 0) {
        return false;
    }
    /*
     * Clear internal cache.
     */
    $this->_groups = false;
    $where = '';
    foreach ($groupids as $key=>$groupid) {
        if ($groupid == -1) {
            $where = 'groupid>=0';
            break;
        }
        if ($key > 0) { $where .= ' OR '; }
        $where .= 'groupid="' . $groupid . '"';
    }
    $entries = $this->getEntries(false, $groupids, false, '', 0, false);
    $entryids = array();
    foreach ($entries as $entry) {
        $entryids[] = $entry['entryid'];
    }
    $this->deleteEntry($entryids);
    $this->_db->delete(PCH_TABLE_GROUPS, $where, false);
    return true;
} // END function deleteGroups

/**
 * Get group name value using id.
 *
 * @param  bool   Group id.
 * @return string Group name. Empty string for invalid group id.
 * @access public
 */
function getGroup($groupid)
{ // BEGIN function getGroup
    if (!isset($this->_groups[$groupid])) {
        $this->getGroups();
    }
    if (!isset($this->_groups[$groupid])) {
        return '';
    }
    return $this->_groups[$groupid];
} // END function getGroup

/**
 * Get group id using name.
 *
 * @param  bool   Group name.
 * @return int    Group id or 0 if no match. 
 * @access public
 */
function getGroupid($group)
{
    if (empty($group)) {
        return 0;
    }
    if ($this->_groups === false) {
        $this->getGroups();
    }
    if (!is_array($this->_groups)) {
        return 0;
    }
    $key = array_search($group, $this->_groups);
    return $key;
}

/**
 * Get all groups.
 *
 * @param  bool  Whether to return a resource or associate array.
 * @return mixed Groups. 
 * @access public
 */
function getGroups($parentid = 0)
{ // BEGIN function getGroups
    if ($this->userid() == false) {
        return false;
    }
    $where = "userid='" . $this->userid() . "'";
//         . ' AND parentid<="' . $parentid . '"',
    $result = $this->_db->select(PCH_TABLE_GROUPS, $where, false, '',
        'groupid, iv, ciphertext');
    /*
     * Initialize variable.
     */
    $groups = array();    
    while ($row = $this->_db->fetchRow($result)) {
        $iv = base64_decode($row[1]);
        if ($this->_decryptGroup($row) == true) {
            $groups[$row[0]] = $row[2];
            unset($row);
        }
    }
    natcasesort($groups);
    /*
     * Cache results.
     */
    $this->_groups = $groups;
    return $groups;
}

/**
 * Create sample groups.
 *
 * @return void
 * @access public
 */
function createSampleGroups()
{ // BEGIN function createSampleGroups
	$groups = array(
        array('title' => 'Fourms'),
        array('title' => 'Research'),
        array('title' => 'News'),
        array('title' => 'Email'),
        array('title' => 'Telephone Numbers'),
        array('title' => 'Shopping'),
        array('title' => 'General'),
        array('title' => 'Software'),
        array('title' => 'Businesses'),
        );
    $this->addGroups($groups);
} // END function createSampleGroups

/**
 * Decrypt a group.
 *
 * @param  array
 * @return bool Whether operation was completed succesful.
 * @access private
 */
function _decryptGroup(&$data, $key = false)
{ // BEGIN function _decryptGroup
    if (!$key) {
        $key = $this->key();
    }
    if (!$key) {
        return false;
    }
    $iv = base64_decode($data[1]);
    $data[2] = $this->_crypt->decrypt($data[2], $iv, $key);
    return true;
} // END function _decryptGroup

/**
 * Encrypt a group.
 *
 * @param  array
 * @param  string Alternate key used for encryption.  
 * @return bool Whether operation was completed succesful.
 * @access private
 */
function _encryptGroup(&$data, $key = false)
{ // BEGIN function _encryptGroup
    if (!$key) {
        $key = $this->key();
    }
    if (!$key) {
        return false;
    }
    $iv = $this->_crypt->makeIv();
    $data['ciphertext'] = $this->_crypt->encrypt($data['title'], $iv, $key);
    unset($data['title']);
    $data['iv'] = base64_encode($iv);
    return true;
} // END function _encryptGroup

// -----------------------------------------------------------------------------
//                    General Entry Methods.
// -----------------------------------------------------------------------------

/**
 * Add new or update entry for current user.
 *
 * @param  array  password, title, url, info; Optional entryid, groupid, iconid
 * @param  string Alternate key used for encryption.
 * @return mixed  Int of saved entry id on success or bool false.
 */
function addEntry($entry, $key = false)
{
    if (!$key) {
        $key = $this->key();
    }
    if (!$key) {
        return false;
    }
    /*
     * Initialize all unset entry values.
     */
    foreach ($this->_evalues as $val) {
        if (!isset($entry[$val])) {
            $entry[$val] = '';
        }
    }
    
    if (!isset($entry['iconid'])) {
        $entry['iconid'] = $this->retrieveIconId($entry['url']);
    }
    $this->_encryptEntry($entry, $key);
    if ($entry == false) {
        return false;
    }
    $data = array(
        'userid'      => $this->userid(),
        'iv'          => $entry['iv'],
        'iconid'      => $entry['iconid'],
        'ciphertext'  => $entry['ciphertext'],
        );
    if (isset($entry['entryid']) && !empty($entry['entryid'])
            && count($this->getEntries($entry['entryid'])) > 0) {

        $this->_db->updateArray(PCH_TABLE_ENTRIES, $data, "entryid = '"
             . $entry['entryid'] . "' AND userid = '" . $this->userid() . "'");
        $data = array('groupid'=> $entry['groupid']);
        $this->_db->updateArray(PCH_TABLE_ENTRIES_TO_GROUPS, $data,
            "entryid = '" . $entry['entryid'] . "'");
    } else {
        if (!isset($entry['entryid'])
                || strlen($entry['entryid']) != PCH_ID_LENGTH) {

            $entry['entryid'] = $this->generateId();
        }
        $data['entryid']  = $entry['entryid'];
        $this->_db->insertArray(PCH_TABLE_ENTRIES, $data);
        $data = array(
            'groupid'=> $entry['groupid'],
            'entryid'=> $entry['entryid']
            );
        $this->_db->insertArray(PCH_TABLE_ENTRIES_TO_GROUPS, $data);
    }
    return $entry['entryid'];
}

/**
 * Delete entry for current user.
 *
 * @param  array[int] Entry id's to delete.
 * @return bool Whether import is excepted.
 */
function deleteEntry($entryids)
{ // BEGIN function deleteEntry
    if (is_string($entryids)) {
        $entryids = array($entryids);
    } elseif (!is_array($entryids) || count($entryids) <= 0) {
        return false;
    }
    $where = '';
    foreach ($entryids as $entryid) {
        $where .= "entryid = '$entryid' OR ";
    }
    $where = substr($where, 0, -4);
    $this->_db->delete(PCH_TABLE_ENTRIES_TO_GROUPS, $where, count($entryids));
    $this->_db->delete(PCH_TABLE_ENTRIES          , $where, count($entryids));
    return true;
} // END function deleteEntry

/**
 * Get entries using group id or just one base on entry id.
 *
 * @param  int    Requested entry ID. Used to retrieve only one entry.
 * @param  int    Requested category ID. When specified retrieves all entries
 *                within category. A value of false for both entryid and groupid
 *                  returns all enties.
 * @param  bool   Whether to parse url.
 * @param  string Column of entry used for sorting returned entries.
 * @return flag   Sorting order flag.
 * @access public
 */
function getEntries($entryid = false, $groupids = false, $parse_url = true,
    $sort_col = 'title', $sort_flag = SORT_ASC, $decypt = true, $search = false)
{ // BEGIN function getEntries
    $this->_entriesLstCnt = 0;
    $from  = PCH_TABLE_ENTRIES_TO_GROUPS . " le," . PCH_TABLE_ENTRIES . " e";
    $where = "e.userid = '" . $this->userid() . "' AND le.entryid = e.entryid";
    $cols = "le.groupid, e.entryid, e.iv, e.ciphertext, e.iconid";
    if (!empty($entryid)) {
        $where .= " AND e.entryid = '$entryid'";
    } elseif ($groupids === false ||
            (isset($groupids[0]) && $groupids[0] == -1)) {

    } elseif (is_int($groupids)) {
        $where .= " AND le.groupid = '$groupids'";
    } elseif (is_array($groupids)) {
        $where .= " AND (";
        foreach ($groupids as $groupid) {
            $where .= "groupid = '$groupid' OR ";
        }
        $where = substr($where, 0, -4);
        $where .= ") ";
    } else {
        return false;  
    }
    $result = $this->_db->select($from, $where, '', '', $cols);
    $entries = array();
    $key = $this->key();
    if (!$key && $decypt) {
        return false;
    }
    while ($entry = $this->_db->fetchAssoc($result)) {
        if ($decypt == false) {
            $entries[] = $entry;
            continue;
        }
        $this->_decryptEntry($entry, $key);
        if ($search) {
            if (!isset($search[1]) || empty($search[1])) {
                return false;
            }
            /*
             * Preform search only if search value is set and decrypt is true.
             */
            $match = false;

            foreach ($search[2] as $serchField) {
                if (isset($entry[$serchField])) {
                    if (stristr($entry[$serchField], $search[1]) !== false) {
                        $match = true;
                        /*
                         * If match any is set, search is complete. Match.
                         */
                        if ($search[0] == false) {
                            break;
                        }
                    } elseif ($search[0] == true) {
                        /*
                         * If match all is set, search is complete. No match.
                         */
                        $match = false;
                        break;
                    }
                }
            }
            if ($match == false) {
                continue;
            }
        }
        $entry['group'] = $this->getGroup($entry['groupid']);
        $entry['icon']  = $this->getIcon($entry['iconid']);
        if (!empty($entry['url']) && $parse_url) {
            /*
             * Preform string replace on url.
             */
            $patterns = array("/%un%?/", "/%pw%?/");
            $replacements = array($entry['login'], $entry['password']);
            $url = preg_replace($patterns, $replacements, $entry['url']);
            /*
             * Url is parsed, will be re-built below.
             */
            $entry['url'] = '';
            $url_parts = @parse_url($url);
            /*
             * Check to url is formatted correctly.
             */
            if (isset($url_parts['scheme']) && isset($url_parts['host'])) {
                $entry['url'] = $url_parts['scheme'] . '://';
                $url_host = $url_parts['host'];
                if (isset($url_parts['port'])) {
                    $url_host .= ':' . $url_parts['port'];
                }
                if (isset($url_parts['user'])) {
                    $entry['url'] .= $url_parts['user'];
                    if (isset($url_parts['pass'])) {
                        $entry['url'] .= ':' . $url_parts['pass'] . '@';
                    }
                }
                $entry['url'] .= $url_host;
                if (isset($url_parts['path'])) {
                    $entry['url'] .= $url_parts['path'];
                }
                if (isset($url_parts['query'])) {
                    /*
                     * Create form to post url data.
                     */
                    parse_str($url_parts['query'], $params);
                    $entry['params'] = $params;
                } else {
                    $entry['params'] = array();
                }
                if (isset($url_parts['fragment'])) {
                    $entry['url'] .= '#' . $url_parts['fragment'];
                }
            }
        }
        $entries[] = $entry;
    }
    /*
     * Sort entries.
     */
    if ($decypt) {
        foreach ($entries as $key => $row) {
           $sort_keys[$key] = $row[$sort_col];
        }
        if (isset($sort_keys)) {
            array_multisort($sort_keys, $sort_flag, $entries);
        }
    }
    $this->_entriesLstCnt = count($entries);
    return $entries;
} // END function getEntries

/**
 * Decrypt an entry.
 *
 * @param  array
 * @return array
 * @access private
 */
function _decryptEntry(&$data, $key = false)
{ // BEGIN function _decryptEntry
    if (!$key) {
        return false;
    }
    $iv = base64_decode($data['iv']);
    $data['plaintext'] = $this->_crypt->decrypt($data['ciphertext'], $iv, $key);
    $plainarray = unserialize($data['plaintext']);
    foreach ($this->_evalues as $evalue) {
        $data[$evalue] = $plainarray[$evalue];
    }
    unset($plainarray);
    unset($data['ciphertext']);
    unset($data['plaintext']);
} // END function _decryptEntry

/**
 * Encrypt an entry.
 *
 * @param  array
 * @return void
 * @access private
 */
function _encryptEntry(&$entry, $key = false)
{ // BEGIN function _encryptEntry
    if (!$key) {
        return false;
    }
    foreach ($this->_evalues as $evalue) {
        $cipherarray[$evalue] = $entry[$evalue];
        unset($entry[$evalue]);
    }
    $entry['plaintext'] = serialize($cipherarray);
    unset($cipherarray);
    $iv = $this->_crypt->makeIv();
    $entry['ciphertext'] = $this->_crypt->encrypt($entry['plaintext'], $iv, $key);
    unset($entry['plaintext']);
    $entry['iv'] = base64_encode($iv);
} // END function _encryptEntry

// -----------------------------------------------------------------------------
//                    General Crypt Methods.
// -----------------------------------------------------------------------------

/**
 * Create test string.
 *
 * @return string Test string.
 * @access private
 */
function _makeTestString()
{
    $length = 12;
    $s = '';
    $randData = $this->_crypt->genRandom($length, 33, 125);
    for ($i = 0; $i < $length; $i++) {
        $s .= chr($randData[$i]);
    }
    return $s . $s;
}

/**
 * Test string.
 *
 * @return bool
 * @access private
 */
function _testString($teststring)
{
    $pos = ceil(strlen($teststring) / 2);
    if (substr($teststring, 0, $pos) === substr($teststring, $pos, $pos)) {
        return true;
    } else {
        return false;
    }
}

// -----------------------------------------------------------------------------
//                           General Icon Methods.
// -----------------------------------------------------------------------------

/**
 * Get icon url from database.
 *
 * @param integer Icon id.
 * @return string Icon url.
 */
function getIcon($iconid)
{ // BEGIN function getIcon
    if ($iconid > 0) {
        $result = $this->_db->select(PCH_TABLE_ICONS, 
            "iconid='$iconid'", '', 1, "icon");
        $row = $this->_db->fetchRow($result);
        if (isset($row[0])) {
            return $row[0];
        }
    }
    return '';
} // END function getIcon

/**
 * Retrieve icon id.
 *
 * @param string $title
 * @param string
 * @return int Icon id.
 */
function retrieveIconId($url)
{ // BEGIN function retrieveIconId
    /*
     * Url is parsed, will be re-built below.
     */
    $baseurl = '';
    $url_parts = @parse_url($url);
    /*
     * Check to url is formatted correctly.
     */
    if (isset($url_parts['scheme']) && isset($url_parts['host'])) {
        $baseurl = $url_parts['scheme'] . '://';
        $url_host = $url_parts['host'];
        if (isset($url_parts['port'])) {
            $url_host .= ':' . $url_parts['port'];
        }
        if (isset($url_parts['user'])) {
            $entry['url'] .= $url_parts['user'];
            if (isset($url_parts['pass'])) {
                $entry['url'] .= ':' . $url_parts['pass'] . '@';
            }
        }
        $baseurl .= $url_host;
        if (isset($url_parts['path'])) {
            $baseurl .= $url_parts['path'];
        }
    }
    $iconid  = 0;
    if (empty($baseurl)) {
        return $iconid;
    }
    require_once PCH_FS_LIBS . 'SB_PageParser.class.php';
    $pp = new SB_PageParser($baseurl);
    $pp->getInformation(array('FAVURL', 'TITLE'));
    $this->debugInfo .= $pp->debugInfo;
    if (isset($pp->info['FAVURL']) && !empty($pp->info['FAVURL'])) {
        $where  = "icon='" . $pp->info['FAVURL'] . "'";
        $result = $this->_db->select(PCH_TABLE_ICONS, $where, '', 1, "iconid");
        $row    = $this->_db->fetchRow($result);
        if (isset($row[0])) {
            /*
             * Icon is already listed in table.
             */
            $iconid = $row[0];
        } else {
            $data['icon'] = $pp->info['FAVURL'];
            if (isset($pp->info['TITLE'])) {
                $data['title'] = $pp->info['TITLE'];
            } else {
                $data['title'] = '';
            }
            $data['title'] = substr($data['title'], 0, 63);
            $iconid = $this->_db->insertArray(PCH_TABLE_ICONS, $data);
        }
    }
    return $iconid;
} // END function retrieveIconId


// -----------------------------------------------------------------------------
//                            Password Functions.
// -----------------------------------------------------------------------------

/**
 * Generate pseudo-random password.
 *
 * @param integer Length of password.
 * @param string  List of valid characters.
 * @return string New password.
 * @access public
 */
function generatePassword($length = 8, $valid = null)
{
    if (is_null($valid)) {
        /*
         * Define default valid characters.
         */
        $valid = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz';
    }
    $randData = $this->_crypt->genRandom($length, 0, strlen($valid) - 1);
    /*
     * Start with a blank password.
     */
    $password = '';
    /*
     * Add random characters to $password until $length is reached.
     */
    for ($i = 0; $i < $length; $i++) {
        /*
         * Pick a random character from the possible ones.
         */
		$password .= substr($valid, $randData[$i], 1);
    }
    /*
     * Done.
     */
    return $password;
}

/**
 * Change master password.
 *
 * A new user is created and data is copied before destroying old user. This
 * is done to prevent unrecoverable corrupt data due to resurce overload.
 *
 * @param string New key.
 * @return bool Whether action is completed successful.
 */
function changePassword($newkey)
{ // BEGIN function changePassword
    /*
     * Ensure key is new and does not match the current one.
     */
    if (empty($newkey) || $this->hashKey($newkey) == $this->key()) {
        return false;
    }
	$tmpControl = clone $this;
    $tmpControl->debug($this->debug(null));
	$tmpControl->clearAccessors();
    $user    = $this->user();
    $tmpUser = $user . '__';
    if (!$tmpControl->addUser($tmpUser, $newkey, $this->getSettings())) {
        return false;
    }
    $tmpControl->user($tmpUser);
    $tmpControl->key($this->hashKey($newkey));
    if (!$tmpControl->auth()) {
        return false;
    }
    /*
     * Transfer old entries to new user.
     */
    $groups = $this->getGroups();
    foreach ($groups as $oldGroupid=>$group) {
        $entries = $this->getEntries(false, $oldGroupid, false);
        $newGroupid = $tmpControl->addGroups($group);
        foreach ($entries as $entry) {
            unset($entry['entryid']);
            $entry['groupid'] = $newGroupid;
            $tmpControl->addEntry($entry);
        }
    }
    $this->deleteUser();
    unset($tmpControl);
    $this->renameUser($tmpUser, $user);
    $this->user($user);
    $this->key($this->hashKey($newkey));
    return true;
} // END function changePassword

/* Hash-function digest (md*) */
function digestalgo($value=null)
{
  return $this->_handleProperties('_digestalgo', $value);
}

/**
 * Single line description of function hashKey.
 *
 * Multi line
 * description
 * of function hashKey.
 *
 * @param string Plaintext key.
 * @return string Key hash.
 * @access public
 */
function hashKey($value)
{ // BEGIN function hashKey
	return $this->_crypt->mdHash($this->keyPrefix() . $value, $this->digestalgo());
} // END function hashKey

// -----------------------------------------------------------------------------
//                              User Functions.
// -----------------------------------------------------------------------------

/**
 * Add new user.
 *
 * @param  string New user name.
 * @param  string New plaintext key (master password).
 * @return bool   true is successful.
 * @access public
 */
function addUser($user, $key, $settings = array())
{ // BEGIN function addUser
    /*
     * Check if user already exist.
     */
    $result = $this->_db->select(PCH_TABLE_USERS, "user = '$user'", '', 1,
        "userid");
    if ($this->_db->numRows($result) != 0) {
        return false;
    }
    $iv         = $this->_crypt->makeIv();
    $key        = $this->hashKey($key);
    $teststring = $this->_crypt->encrypt($this->_makeTestString(), $iv, $key);
    $iv         = base64_encode($iv);
    $userid     = $this->generateId();
    $data = array (
        'userid'    => $userid,
        'user'      => $user,
        'teststring'=> $teststring,
        'iv'        => $iv
        );
    $this->_db->insertArray(PCH_TABLE_USERS, $data);
    $this->user($user);
    $this->key($key);
    /*
     * Login new user while perform sanity check.
     */
    if (!$this->checkUser(false)) {
        /*
         * New user will be unable to login. Likely a cipher issue.
         */
        $this->deleteUser($userid);
        return false;
    }
    /*
     * Strip out userid because a new userid will be assigned.
     */
    if (isset($settings['userid'])) {
        unset($settings['userid']);
    }
    if (!isset($settings['defaultun'])) {
        $settings['defaultun'] = $user;
    }
    $this->setSetting($settings);
    $this->clearAccessors();
    return true;
} // END function addUser

/**
 * Delete user based on user id or user name.
 *
 * @param integer User id to be deleted.
 * @param integer User name to be deleted.
 * @return bool Whether operation is successful.
 */
function deleteUser($userid = false, $user = false)
{ // BEGIN function deleteUser
    if ($userid !== false) {
        $this->userid(null, $userid);
    } elseif ($user !== false) {
        $this->user($user);
    }
    $userid = $this->userid();
    $user   = $this->user();
    if (empty($userid) || empty($user)) {
        return false;
    }
    $groups = $this->getGroups(0, $userid);
    $groupids = array();
    foreach ($groups as $groupid=>$group) {
        $groupids[] = $groupid;
    }
    $this->deleteGroups($groupids);
    $where = "userid = '$userid'";
    $this->_db->delete(PCH_TABLE_SETTINGS, $where);
    $this->_db->delete(PCH_TABLE_USERS, $where);
    $this->clearAccessors();
    return true;
} // END function deleteUser

/**
 * Rename user. This function is independent of any class properties.
 *
 * @param string Old user name.
 * @param string New user name.
 * @return boolean Whether action is a success. 
 */
function renameUser($oldUser, $newUser)
{ // BEGIN function renameUser
	$userid = $this->userid($oldUser);
	if (!$userid) {
	   return false;
	}
	$data['user'] = $newUser;
	$where = "userid = '$userid'";
	$this->_db->updateArray(PCH_TABLE_USERS, $data, $where);
	return true;
} // END function renameUser

/**
 * Check if user is authorized based on key.
 *
 * @return bool   Whether user has been authorized.
 * @access public
 */
function checkUser($log = true)
{
    /*
     * If user is not set prior to call, check cookies.
     * If user is still not set, just clear accessor's.
     */
    if ((!$this->user() && !$this->userid()) || !$this->key()) {
        /*
         * Just in case Pch_Control::_auth is being tampered with.
         */
        $this->clearAccessors();
        return false;
    }
    /*
     * Always clear _auth value.
     */
    $this->_auth = false;
    $timeStamp = time();
    $data = array(
        'user'      => $this->user(),
        'ip'        => $this->ipAddress(),
        'logged'    => $timeStamp,
        'refreshed' => $timeStamp,
        'outcome'   => 0,
        );
    /*
     * Get user info.
     */
    $result = $this->_db->select(PCH_TABLE_USERS,
        "userid = '" . $this->userid() . "'", '', '',
        "teststring, iv");
    if ($this->_db->numRows($result) == 1) {
        $row = $this->_db->fetchAssoc($result);
        if ($this->_testString(
            $this->_crypt->decrypt(
                $row['teststring'],
                base64_decode($row['iv']),
                $this->key())
            )) {

            $this->getSettings();
            $this->_auth = true;
            $data['outcome']    = 1;
        }
    }
    /*
     * Log results.
     */
    if ($log) {
        if ($this->logid()) {
            unset($data['logged']);
            $where = "logid = '" . $this->logid() . "'";
            $logid = $this->_db->updateArray(PCH_TABLE_LOG, $data, $where);
        } else {
            $logid = $this->_db->insertArray(PCH_TABLE_LOG, $data);
            $this->logid($logid);
        }
    }
    if ($this->_auth != true) {
        $this->clearAccessors();
    }
    return $this->_auth;
}

/**
 * Retrieve number of active users.
 *
 * @return integer
 */
function getNumUsers()
{ // BEGIN function getNumUsers
    return $this->_db->count(PCH_TABLE_USERS, 1, 'userid');
} // END function getNumUsers

/**
 * Retrieve data for last login attempts by user name or ip.
 *
 * @param  bool Whether to retrieve only failed attempts.
 * @param  int  Time limit of login attempts to retrieve (minutes).
 * @param  int  Maximum number of login attempts to retrieve.
 * @return array
 */
function getLogs($outcome = -1, $logged = false, $limit = false,
    $refreshed = false, $logid = false)
{ // BEGIN function getLogs
    $user = $this->user();
    if (empty($user)) {
        return array();
    }
    $where = "user = '$user'";
    if ($outcome >= 0) {
        $where .= " AND outcome = '$outcome'";
    }
    if ($logged !== false) {
       $timestamp = time() - $logged;
       $where .= " AND logged >= $timestamp";
    }
    if ($refreshed !== false) {
       $timestamp = time() - $refreshed;
       $where .= " AND refreshed >= $timestamp";
    }
    if ($logid !== false) {
       $where .= " AND logid = $logid";
    }
    $result = $this->_db->select(PCH_TABLE_LOG, $where, "logged DESC", $limit);
    return $this->_db->fetchResults($result);
} // END function getLogs

/**
 * Delete login logs.
 *
 * @param  int  Timestamp of last login attempt to save (seconds).
 * @return void
 */
function deleteLogs($timestamp)
{ // BEGIN function deleteLogs
    $where = "user = '" . $this->user() . "' AND logged <= $timestamp";
    $result = $this->_db->delete(PCH_TABLE_LOG, $where, false);
} // END function deleteLogs

// -----------------------------------------------------------------------------
//                      Web application settings.
// -----------------------------------------------------------------------------

/**
 * Return a settings value.
 * 
 * @param  string Key to the name of requested setting.
 * @return mixed Value of requested key.
 * @access public
 */
function getSettings($key = false)
{
    $userid = $this->userid();
    if (!$userid) return null;
    if ($this->_settings === false) {
        $where = "userid = '$userid'";
        $result = $this->_db->select(PCH_TABLE_SETTINGS, $where);
        $query = $this->_db->fetchAssoc($result);
        if (isset($query['defaultun'])) {
            $query['defaultun'] = $this->_crypt->decrypt(
                $query['defaultun'],
                base64_decode($query['defaultiv']),
                $this->key()
                );
        }
        $this->_settings = $query;
    }
    if ($key !== false) {
        return $this->_settings[$key];
    } else {
        return $this->_settings;
    }
}

/**
 * Set user settings. User must be logged in first to save default user name.
 * 
 * @param  array  Key and value of user settings.
 *                  'pwmask'     => false,
 *                  'clipboard'  => true, 
 *                  'generate'   => true,
 *                  'defaultun'  => 'qwerty',
 *                  'defaultun'  => 'no',
 *                  'expire'     => $expire,
 *                  'refresh'    => 1
 * @return void 
 * @access public
 */
function setSetting($data = array())
{
    if (isset($data['defaultun'])) {
        $iv = $this->_crypt->makeIv();
        $data['defaultiv'] = base64_encode($iv);
        $data['defaultun'] = $this->_crypt->encrypt(
            $data['defaultun'],
            $iv,
            $this->key()
            );
    }
    $old_data = $this->getSettings();
    /*
     *Clear cache.
     */
    $this->_settings = false;
    if (isset($old_data['userid'])) {
        foreach ($old_data as $dataKey=>$dataValue) {
            if (!isset($data[$dataKey])) {
                $data[$dataKey] = $dataValue;
            }
        }
        $where = "userid = '" . $this->userid() . "'";
        $this->_db->updateArray(PCH_TABLE_SETTINGS, $data, $where);
    } else {
        if (!isset($data['userid'])) {
            $data['userid'] = $this->userid();
        }
        $this->_db->insertArray(PCH_TABLE_SETTINGS, $data);
    }
}
    
// -----------------------------------------------------------------------------
//                              Version functions.
// -----------------------------------------------------------------------------

/**
 * Returns true is the current version of PHP is greater that the specified one.
 *
 * @param  sting php version required.
 * @return void Whether running php version is equal or grater then required.
 */
function checkVersion($version = '4.1.0')
{ // BEGIN function checkVersion
    $minversion = intval(str_replace('.', '', $version));
    $curversion = intval(str_replace('.', '', phpversion()));
    return ($curversion >= $minversion);
} // END function checkVersion

// -----------------------------------------------------------------------------
//                              Private methods.
// -----------------------------------------------------------------------------

/**
 * Generate a 32-character unique ID.
 *
 * @param string Id prefix.
 * @return string
 * @access public
 */
function generateId($prefix = null)
{ // BEGIN function generateId
    if (is_null($prefix)) {
        $prefix = $this->_crypt->genRandom();
    }
    $id = md5(uniqid($prefix, true));
    return $id;
} // END function generateId

/**
 * Handle properties and there values.
 *
 * @param  string Property key.
 * @param  mixed  Property value.
 * @return mixed
 */
function _handleProperties($key, $value)
{ // BEGIN function _handleProperties
	if (!is_null($value)) {
		$this->{$key} = $value;
	}
	return $this->{$key};
} // END function _handleProperties

} // END class Pch_Control

?>
