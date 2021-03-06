<?php
/**
 * MySQL Database connection.
 */
class Database_PDO
{  // BEGIN class Database_PDO

// -----------------------------------------------------------------------------
//                             Object Variables.
// -----------------------------------------------------------------------------

/**#@+
 * @var boolean Whether to display descriptive error messages.
 * @access private
 */
var $_debug;

/**
 * @var bol Whether to suppress all error messages.
 */
var $_suppressError;

/**
 * @var string Last error message.
 */
var $_error;

/**
 * Which class to use for error objects.
 *
 * @var string
 */
var $_errorClass;

/**
 * @var int General id.
 */
var $_id;

/**
 * @var int Debug message.
 */
var $_msg;

/**
 * @var string Host of PDO database, usual 'localhost'.

 */
var $_dbHost;

/**
 * @var string PDO username.
 */
var $_dbUsername;

/**
 * @var string PDO password.
 */
var $_dbPassword;

/**
 * @var string PDO database name.
 */
var $_dbName;

/**
 * @var string Currently selected PDO database name.
 */
var $_dbSelectedName;

/**
 * @var int PDO connect link identifier.
 */
var $_dbResource = NULL;

/**
 * @var string PDO session tables; name and structure.
 */
var $_dbTables;

/**
 * @var boolean Whether using Improved PDO Extension or not.
 */
#var $_useMysqli;

/**
 * @var boolean Whether to create a persistent connections.
 */
var $_persistent;

/**
 * Time unit convertor. 1=s, 1000=ms, 1000000=microsecond
 *
 * @var integer
 */
var $_timeUnit;

/**
 * @var float Slow database query value. Same unit as set by time_unit.
 */
var $_longQueryTime;

/**
 * @var string SQL comment notation
 */
var $_commentMarker;

/**
 * @var string SQL comment notation
 */
var $_crlf;

/**
 * @var boolean Whether class instance is a source.  For database
 *              Synchronization.
 */
var $_isSource;

/**
 * @var array Store database query information. Time and stack are only set
 *               when debug is set to true.
 *               [0] = query
 *               [1] = query time
 *               [2] = stack frame
 *               [3] = Error data
 */
var $_queries;

/**
 * @var resource  Store last result.
 */
var $_lastResult;

/**
 * @var float Elapsed time of last query. Value multiplied by time_unit.
 */
var $_queryTime;

/**
 * @var int Number of executed queries/statements.
 */
var $_numQueries;

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

/**#@-*/

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 * Pass setting options:
 *  $sess = new Database_PDO($param);
 *
 * @param  array   Various database options.
 * @return obj     New instance of class.
 * @access private
 */
function __construct($param = array())
{
    if (!is_array($param)) {
       $param = array();
    }
    $this->_debug          = isset($param['debug'])         ? $param['debug']         : false;
    $this->_suppressError  = isset($param['suppressError']) ? $param['suppressError'] : false;
    $this->_persistent     = isset($param['persistent'])    ? $param['persistent']    : false;
	$this->_dbDriver       = isset($param['dbDriver'])      ? $param['dbDriver']      : 'mysql';
	$this->_dbFile         = isset($param['dbFile'])        ? $param['dbFile']        : '';
    $this->_dbHost         = isset($param['dbHost'])        ? $param['dbHost']        : 'localhost';
    $this->_dbUsername     = isset($param['dbUsername'])    ? $param['dbUsername']    : '';
    $this->_dbPassword     = isset($param['dbPassword'])    ? $param['dbPassword']    : '';
    $this->_dbName         = isset($param['dbName'])        ? $param['dbName']        : '';
    $this->_dbTables       = isset($param['dbTables'])      ? $param['dbTables']      : array();
    $this->_dbSelectedName = '';

    /*
     * Setup queries logging.
     */
    $this->clearQueries();
 
    /*
     * Optionally supply a database resource link. This class will NOT
     * attempt to connect to MySQL and use the link instead.
     */
    if (isset($param['dbResource'])) {
        $this->_dbResource = is_resource($param['dbResource']) ? $param['dbResource'] : NULL;
    } else {
        $this->_dbResource = null;
    }
    $this->_timeUnit      = isset($param['timeUnit'])      ? $param['timeUnit']      : 1000;
    $this->_longQueryTime = isset($param['longQueryTime']) ? $param['longQueryTime'] : 0.1;
    $this->_longQueryTime = $this->_longQueryTime * $this->_timeUnit;

    $this->_commentMarker = isset($param['commentMarker']) ? $param['commentMarker'] : '-- ';
    $this->_crlf          = isset($param['crlf'])          ? $param['crlf']          : "\n";
    $this->_isSource      = isset($param['isSource'])      ? $param['isSource']      : true;

    /*
     * Connect to database.
     */
    if (!empty($this->_dbUsername)) {
        $this->_connect();
    }

    /*
     * Select database.
     */
    if (!empty($this->_dbName)) {
        $this->selectDatabase();
    }
}

/**
 * Class Destructor
 *
 * @return void
 * @access private
 */
function __destruct()
{
    $msg = 'Destructor called, class=' . get_class($this) . "\n";
    if ($this->_debug) {
        error_log($msg);
    }
    $this->closeDatabase();
}


// -----------------------------------------------------------------------------
//                         Public Properties.
// -----------------------------------------------------------------------------

/**
 * Get or set current MySQL resource link.
 *
 * @return resource Returns a MySQL resource link identifier.
 * @access public
 */
public function dbResource($value = null)
{
    return $this->_handleProperties('_dbResource', $value);
}

/**
 * Get last SQL query as string.
 *
 * @return string Last SQL query.
 * @access public
 */
public function lastQuery()
{
    return $this->_queries[$this->_numQueries][0];
}

/**
 * Get or set current number of executed queries/statements.
 *
 * @return int
 * @access public
 */
public function numQueries($value = null)
{
    return $this->_handleProperties('_numQueries', $value);
}

/**
 * Get array of executed queries/statements.
 *
 * @return arrray Quires and times.
 *                [0] = query; [1] = query time
 * @access public
 */
public function queries($value = null)
{
    return $this->_handleProperties('_queries', $value);
}

/**
 * Get or set database host value.
 *
 * @param  string  New value.
 * @return void
 * @access public
 */
public function dbHost($value = null)
{
    return $this->_handleProperties('_dbHost', $value);
}

/**
 * Get or set database Name value.
 *
 * @param  string  New value.
 * @return void
 * @access public
 */
public function dbName($value = null)
{
    return $this->_handleProperties('_dbName', $value);
}

/**
 * Get or set database username value.
 *
 * @param  string  New value.
 * @return void
 * @access public
 */
public function dbUsername($value = null)
{
    return $this->_handleProperties('_dbUsername', $value);
}

/**
 * Get or set database password value.
 *
 * @param  string  New value.
 * @return void
 * @access public
 */
public function dbPassword($value = null)
{
    return $this->_handleProperties('_dbPassword', $value);
}

/**
 * Get or set debug value.
 *
 * @return boolean false
 * @access public
 */
public function debug($value = null)
{ // BEGIN function debug
    return $this->_handleProperties('_debug', $value);
} // END function debug

/**
 * Get or set suppress errors value.
 *
 * @param  mixed  New value.
 * @return void
 * @access public
 */
public function suppressError($value)
{
    $this->_suppressError = $value;
}
/**
 * Clear querey logs.
 *
 * @return void
 */
public function clearQueries()
{ // BEGIN function clearQueries
    $this->_queries    = array();
    $this->_queryTime  = 0;
    $this->_numQueries = 0;
} // END function clearQueries

// -----------------------------------------------------------------------------
//                         General Database Methods.
// -----------------------------------------------------------------------------

/**
 * Connects to sever.
 *
 * @return boolean True on good connection and select of database, false
                   on errors.
 * @access private
 */
function _connect()
{
    /**
     * Loads the mysql extensions if it is not loaded yet
     */
    if (!@function_exists('mysql_connect')
            && !@function_exists('mysqli_connect')) {

        if (defined('PHP_OS') && stristr(PHP_OS, 'win')) {
            $suffix = 'dll';
        } else {
            $suffix = 'so';
        }
        if (!@dl('mysql.' . $suffix)) {

            $this->_error('MySQL connection from PHP is not configured '
                . 'correctly.');
        }
    }

    /*
     * Check whether Improved MySQL Extension is in use.
     */
    if (@extension_loaded('mysqli')) {
        $this->_useMysqli = true;
    } else {
        $this->_useMysqli = false;
    }

    if (empty($this->_dbUsername)) {
        $this->_error = 'No database username in use.';
        $this->_handleErrors(true);
        return false;
    }

    /*
     * When no resource link initially supplied, connect to database.
     */
    if (!is_resource($this->_dbResource)) {
        /*
         * Connect to MySQL server.
         */
        if ($this->_useMysqli) {
            $connect = 'mysqli';
        } else {
            $connect = 'mysql';
        }
        $connect .= '_';
        if ($this->_persistent) {
            $connect .= 'p';
        }
        $connect .= 'connect';
        $this->_dbResource = $connect(
                $this->_dbHost,
                $this->_dbUsername,
                $this->_dbPassword);
    }

    if (empty($this->_dbResource)) {
        $this->_error = 'Access denied to database.';
        $this->_handleErrors(true);
        return false;
    }
    return true;
}

/**
 * Selects the database.
 *
 * @return bool    true on select of database, false on errors.
 * @access public
 */
protected function selectDatabase($name = '')
{
    /*
     * When no resource link initially supplied, connect to database.
     */
    if (!is_resource($this->_dbResource)) {
        /*
         * Connect to MySQL server.
         */
        $this->_connect();
    }
    if (empty($name)) {
        $name = $this->_dbName;
    }

    /*
     * Select database.
     */
    if ($this->_useMysqli) {
        $mysql_db = @mysqli_select_db($this->_dbResource, $name);
    } else {
        $mysql_db = @mysql_select_db($name, $this->_dbResource);
    }
    if (!$mysql_db) {
        $this->_dbSelectedName = '';
        $this->_error('Failed selecting database (' . $name . ').');
        return false;
    }
    $this->_dbSelectedName = $name;
    return true;
}

/**
 * Close connection.
 *
 * @return bool    Returns true on success or false on failure.
 * @access public
 */
function closeDatabase()
{
    if (!is_resource($this->_dbResource)) {
        return false;
    }
    if ($this->_useMysqli) {
        $return = @mysqli_close($this->_dbResource);
    } else {
        $return = @mysql_close($this->_dbResource);
    }

    if ($return) {
        $this->_dbResource = NULL;
        return true;
    } else {
        $this->_error('Failed closing database connection.');
        return false;
    }
}

/**
 * Create a new database.
 *
 * @param  string Database name to create.
 * @return bool
 */
function createDatabase($name = '')
{
    if (empty($name)) {
        $name = $this->_dbName;
    }
    $sql = 'CREATE DATABASE ' . $name;
    return $this->query($sql, '', false);
}

/**
 * Create a new database.
 *
 * @param  string Database name to create.
 * @return bool
 */
function deleteDatabase($name = '')
{
    if (empty($name)) {
        $name = $this->_dbName;
    }
    $sql = 'DROP DATABASE ' . $name;
    return $this->query($sql, '', false);
}

/**
 * Check if database exists.
 *
 * @param string   Database name check for.
 * @return bool
 */
function existsDatabase($name = '')
{ // BEGIN function existsDatabase
    if (empty($name)) {
        $name = $this->_dbName;
    }
    $sql = "SHOW DATABASES LIKE '$name'";
    $result = $this->query($sql, '', false);
    $query  = $this->fetchArray($result);
    return (bool) $query;
} // END function existsDatabase


// -----------------------------------------------------------------------------
//                                  Queries.
// -----------------------------------------------------------------------------

/**
 * Query.
 *
 * @param  string   Query string.
 * @param  int      Query key.
 * @param  int      Force method to select database prier to running query.
 * @return mixed    Returns a resource identifier or error message.
 */
function query($query = '', $key = '', $force_select = true)
{
    /*
     * Clear last result.
     */
    $this->_lastResult = null;
    /*
     * Set query to object.
     */
    if (empty($query) && !empty($key)) {
        /*
         * Use saved query.
         */
        $query = $this->_queries[$key][0];
    } else {
        /*
         * Increment count.
         */
        $this->_numQueries += 1;
        /*
         * Save query.
         */
        $this->_queries[$this->_numQueries][0] = $query;
    }
    /*
     * Check connections is made, else connect.
     */
    if (!is_resource($this->_dbResource) || empty($this->_dbSelectedName)) {
        if ($force_select) {
            $this->selectDatabase();
        } else {
            $this->_connect();
        }
    }

    /*
     * Current system UNIX timestamp with microseconds.
     */
    $time_start = explode(' ', microtime());

    /*
     * Run query.
     */
    if ($this->_useMysqli) {
        $this->_lastResult = @mysqli_query($this->_dbResource, $query);
    } else {
        $this->_lastResult = @mysql_query($query, $this->_dbResource);
    }
    $time_end = explode(' ', microtime());

    /*
     * Set last query time to object.
     */
    $precision = 6 - round(log10($this->_timeUnit));
    $this->_queryTime = number_format(
        ($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0]))
        * $this->_timeUnit, $precision);

    /*
     * Log data.
     */
    if ($this->_queryTime > $this->_longQueryTime) {
        $this->_slow_query($query, $this->_queryTime);
    }
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($this->_debug == true) {
        $this->_queries[$this->_numQueries][1] = $this->_queryTime;
        $this->_queries[$this->_numQueries][3] = $errno;
        if (function_exists('debug_backtrace')) {
            $stackTrace = debug_backtrace();
            $stackFrame = '';
            $thisClass  = get_class($this);
            /*
             * Will try return whole stack for more accurate debug.
             */
            foreach ($stackTrace as $frame) {
                if (!isset($frame['class']) || $frame['class'] !== $thisClass) {
                    $stackFrame .= basename($frame['file']) . '('
                        . $frame['line'] . '):';
                    if (isset($frame['class'])) {
                        $stackFrame .= $frame['class'];
                    }
                    if (isset($frame['function'])) {
                        $stackFrame .= '::' . $frame['function'];
                    }
                    break;
                }
            }
            $this->_queries[$this->_numQueries][2] = $stackFrame;
        }
    }

    if ($errno != 0) {
        /*
         * Error, stop running query and return error
         */
        return $this->_error($query);
    } else {
        /*
         * Return last resource identifier.
         */
        return $this->_lastResult;
    }
}

/**
 * Run multiple Query.
 *
 * @param  string   Query.
 * @return resource Returns a resource identifier or error message.
 */
function multiQuery($query)
{
    $queries = array();
    $this->splitSql($queries, $query);
    foreach ($queries as $query) {
        if (isset($query['empty']) && !$query['empty']) {
            $return = $this->query($query['query']);
            if ($this->_useMysqli) {
                $errno = mysqli_errno($this->_dbResource);
            } else {
                $errno = mysql_errno($this->_dbResource);
            }
            if ($errno != 0) {
                /*
                 * Error, stop running query and return error
                 */
                return $this->_error($query['query']);
            }
        }
    }
    /*
     * Return last resource identifier.
     */
    return $return;
}

/**
 * Fetch_assoc.
 *
 * Used to return an associative array representing the next row in the
 * result set for the result represented by the result parameter, where each
 * key in the array represents the name of one of the result set's columns.
 *
 * @param  object   Result.
 * @return resource Associative array that corresponds to the fetched row or
 *                  NULL if there are no more rows.
 */
function fetchAssoc($result = null)
{
    if (is_null($result)) {
        $result = $this->_lastResult;
    }
    if (is_object($result) || is_resource($result)) {
        if ($this->_useMysqli) {
            return mysqli_fetch_assoc($result);
        } else {
            return mysql_fetch_assoc($result);
        }
    }
}

/**
 * Fetch array.
 *
 * Used to return an associative array representing the next row in the
 * result set for the result represented by the result parameter, where each
 * key in the array represents the name of one of the result set's columns.
 *
 * @param  object Result.
 * @return mixed  Array that corresponds to the fetched row or NULL if there
 *                are no more rows for the result-set represented by the
 *                result parameter.
 */
function fetchArray($result = null)
{
    if (is_null($result)) {
        $result = $this->_lastResult;
    }
    if (is_object($result) || is_resource($result)) {
        if ($this->_useMysqli) {
            return mysqli_fetch_array($result);
        } else {
            return mysql_fetch_array($result);
        }
    }
}

/**
 * Fetch row.
 *
 * Fetches one row of data from the result associated with the  specified
 * result identifier. The row is returned as an array. Each result column is
 * stored in an array offset, starting at offset 0.
 *
 * @param  object Result.
 * @return mixed  An array that corresponds to the fetched row, or false if
 *                there are no more rows.
 */
function fetchRow($result = null)
{
    if (is_null($result)) {
        $result = $this->_lastResult;
    }
    if (is_object($result) || is_resource($result)) {
        if ($this->_useMysqli) {
            return mysqli_fetch_row($result);
        } else {
            return mysql_fetch_row($result);
        }
    }
}

/**
 * Result.
 *
 * Returns contents of one cell from a MySQL result set. Field argument can
 * be the field's offset, or the field's name, or the field's table dot
 * field name (tablename.fieldname).
 *
 * @param  object Result.
 * @return mixed  An array that corresponds to the fetched row, or false if
 *                there are no more rows.
 */
function result($result, $row)
{
    if (is_object($result) || is_resource($result)) {
        if (!$this->_useMysqli) {
            return mysql_result($result);
        }
    }
}

/**
 * Fetch results.
 *
 * Returns contents of all cells from a MySQL result set.
 *
 * @param  object Result.
 * @return array  Result in as an associate array.
 * @access public
 */
function fetchResults($result = null)
{
    $query = array();
    if (is_null($result)) {
        $result = $this->_lastResult;
    }
    while ($row = $this->fetchAssoc($result)) {
        $query[] = $row;
    }
    return $query;
}

/**
 * Num_rows.
 *
 * Gets the number of rows in a result .
 *
 * @param  object Result.
 * @return mixed  Returns number of rows in the result set.
 */
function numRows($result)
{
    if ($this->_useMysqli) {
        return mysqli_num_rows($result);
    } else {
        return mysql_num_rows($result);
    }
}

/**
 * Select.
 *
 * @param  string   Table name.
 * @param  string   Select clause.
 * @param  string   Order of query.
 * @param  int      Query limit.
 * @return resource Returns a resource identifier or false if the query
 * was not executed correctly. For other type of SQL statements,
 * mysql_query() returns true on success and false on error.
 */
function select($table, $where = false, $order = false, $limit = false,
    $columns = '*')
{
    $select = "SELECT $columns FROM $table";
    if ($where != false) {
        $select .= " WHERE $where";
    }
    if ($order != false) {
        $select .= " ORDER BY $order";
    }
    if ($limit != false) {
        $select .= " LIMIT $limit";
    }
    return $this->query($select);
}

/**
 * Insert
 *
 * @param  string Table name.
 * @param  string Rows to insert.
 * @param  string Value of rows.
 * @return int    Returns the ID generated for an AUTO_INCREMENT column.
 */
function insert($table, $rows, $values)
{
    $insert = "INSERT INTO $table ($rows) VALUES ($values)";
    $this->query($insert);
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($errno != 0) {
        $this->_error($insert);
    }
    if ($this->_useMysqli) {
        return mysqli_insert_id($this->_dbResource);
    } else {
        return mysql_insert_id($this->_dbResource);
    }
}

/**
 * Insert from array. Automatically escapes values.
 *
 * @param  string Table name.
 * @param  string Array of rows to insert with value of rows.
 * @return int    Returns the ID generated for an AUTO_INCREMENT column
 *
 * Usage:
 * <code>
 * $array = array(
 *      'row1' => 'value1',
 *      'row2' => 'value2'
 *      );
 * database::insertArray(TABLE, $array);
 * </code>
 */
function insertArray($table, $array)
{
    $rows = '';
    $values = '';
    foreach ($array as $key=>$value) {
        $rows .= "$key, ";
        $values .= $this->translateValue($value) . ', ';
    }
    $rows   = substr($rows  , 0, strlen($rows)   - 2);
    $values = substr($values, 0, strlen($values) - 2);
    return $this->insert($table, $rows, $values);
}

/**
 * Replace.
 *
 * Replace works exactly like Insert, except that if an old record in the
 * table has the same value as a new record for a PRIMARY KEY or a UNIQUE
 * index, the old record is deleted before the new record is inserted.
 *
 * @param  string Table name.
 * @param  string Rows to insert.
 * @param  string Value of rows.
 * @return int    Returns the ID generated for an AUTO_INCREMENT column
 */
function replace($table, $rows, $values)
{
    $replace = "REPLACE INTO $table ($rows) VALUES ($values)";
    $this->query($replace);
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($errno != 0) {
        $this->_error($replace);
    }
    if ($this->_useMysqli) {
        return mysqli_insert_id($this->_dbResource);
    } else {
        return mysql_insert_id($this->_dbResource);
    }
}

/**
 * Replace from array. Automatically escapes values.
 *
 * @param  string Table name.
 * @param  string Array of rows to replace with value of rows.
 * @return int    Returns the ID generated for an AUTO_INCREMENT column
 *
 * Usage:
 * <code>
 * $array = array(
 *      'row1' => 'value1',
 *      'row2' => 'value2'
 *      );
 * database::replaceArray($table, $array);
 * </code>
 */
function replaceArray($table, $array)
{
    $rows   = '';
    $values = '';
    foreach ($array as $key=>$value) {
        $rows   .= "$key, ";
        $values .= $this->translateValue($value) . ', ';
    }
    $rows   = substr($rows  , 0, strlen($rows)   - 2);
    $values = substr($values, 0, strlen($values) - 2);
    return $this->replace($table, $rows, $values);
}

/**
 * Update.
 *
 * @param  string Table name.
 * @param  int    Rows to update.
 * @param  string Select clause.
 * @param  int    Query limit.
 * @return int    Query return message and any error.
 */
function update($table, $set, $where, $limit = '1')
{
    $query = "UPDATE $table SET $set WHERE $where LIMIT $limit";
    $this->query($query);
    $msg = '';
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($this->_useMysqli) {
        $errdes = mysqli_error($this->_dbResource);
    } else {
        $errdes = mysql_error($this->_dbResource);
    }
    $msg = $errno . '::' . $errdes;
    return $msg;
}

/**
 * Update from array. Automatically escapes values.
 *
 * Usage:
 * <code>
 * $array = array(
 *      'row1' => 'value1',
 *      'row2' => 'value2'
 *      );
 * database::inserta(TABLE, $array);
 * </code>
 *
 * @param  string Table name.
 * @param  string Array of rows to insert with value of rows.
 * @param  string Select clause.
 * @param  int    Query limit.
 * @return int    Query return message and any error.
 */
function updateArray($table, $array, $where, $limit = '1')
{
    $set = '';
    foreach ($array as $key=>$value) {
        $set .= "$key = " . $this->translateValue($value) . ', ';
    }
    $set = substr($set, 0, strlen($set) - 2);
    $set = str_replace('null', null, $set);
    return $this->update($table, $set, $where, $limit);
}

/**
 * Delete
 *
 * @param  string Table name.
 * @param  string Select clause.
 * @param  int    Query limit.
 * @return string Query message.
 */
function delete($table, $where, $limit = '1')
{
    $delete = "DELETE FROM $table";
    if ($where != '') {
        $delete .= " WHERE $where";
    }
    if ($limit != false) {
        $delete .= " LIMIT $limit";
    }
    $this->query($delete);
    $msg = '';
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($this->_useMysqli) {
        $errdes = mysqli_error($this->_dbResource);
    } else {
        $errdes = mysql_error($this->_dbResource);
    }
    $msg = $errno . '::' . $errdes;
    return "Query: $delete - " . $msg;
}

/**
 * Records slow_queries.
 *
 * Designed to by overloaded.
 *
 * @param  time
 * @param  float  Query.
 * @return string HTML comment with slow query information.
 */
function _slow_query($query = '', $time = 0)
{
    if (empty($query)) {
        $query = $this->_query_last;
    }
    if ($time == 0) {
        $time = $this->_queryTime;
    }
    return "<!-- SQL Query: ${time}s - $query //-->\n";
}

// -----------------------------------------------------------------------------
//                     Tables and table information.
// -----------------------------------------------------------------------------

/**
 * Create table
 *
 * @param  string Table name.
 * @return mixed
 */
function createTable($table, $fields, $auto_inc = '1')
{
    $create = "CREATE TABLE $table";
    $create .= " ($fields)";
    if (!empty($auto_inc)) {
        $create .= " TYPE=MyISAM AUTO_INCREMENT=$auto_inc";
    }
    $create .= ';';
    $this->query($create);
    $msg = '';
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($this->_useMysqli) {
        $errdes = mysqli_error($this->_dbResource);
    } else {
        $errdes = mysql_error($this->_dbResource);
    }
    $msg = $errno . '::' . $errdes;
    return "Query: $create - " . $msg;
}


/**
 * Create tables.
 *
 * @param  array   Collections of table schemas.
 * @return bool    Returns true on success or false on failure.
 * @access public
 */
function createTables($tables = array(), $drop = false)
{
    if (empty($tables)) {
        $tables = $this->_dbTables;
    }

    /*
     * Create all tables as specified by above array, _tables.
     */
    foreach ($tables as $key=>$table) {
        if ($key != '_dbName') {
            if ($drop) {
                /*
                 * Remove any pre-existing table.
                 */
                $this->query('DROP TABLE IF EXISTS ' . $table['table_name']);
            }
            $return = $this->query($table['structure']);
            if (!$return) {
                return false;
            }
        }
    }
    return true;
}

/**
 * Delete table
 *
 * @param  string Table name.
 * @param  bool   Whether to check if table exists.
 * @return string Query string and and error code plus error massage.
 */
function deleteTables($table, $if_exists = true)
{
    $querystr = 'DROP TABLE';
    if ($if_exists) {
        $querystr .= ' IF EXISTS';
    }
    $querystr .= " $table";
    $this->query($querystr);
    $msg = '';
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    if ($this->_useMysqli) {
        $errdes = mysqli_error($this->_dbResource);
    } else {
        $errdes = mysql_error($this->_dbResource);
    }
    $msg = $errno . '::' . $errdes;
    return "Query: $querystr - " . $msg;
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
    $status = "SHOW TABLE STATUS LIKE '$table'";
    $result = $this->query($status);
    $query  = $this->fetchAssoc($result);
    if (!empty($key) && isset($query[$key])) {
        return $query[$key];
    } else {
        return $query;
    }
}

/**
 * Retrieve tables columns.
 *
 * @param  string Table name.
 * @param  string Field name to check for. Use '%' before and/or
 *                after name for wildcard option.
 * @return array  Collection of columns.
 */
function tableColumns($table, $field = '')
{
    $querystr = "SHOW COLUMNS FROM $table";
    if (!empty($field)) {
        $querystr .= " LIKE '$field'";
    }
    return $this->fetchAssoc($this->query($querystr));
}

/**
 * Checks if a given table has a specified field.
 *
 * @param  string Table name.
 * @param  string Field name to check for. Use '%' before and/or
 *                after name for wild-card option.
 * @return bool   Field name ot
 *         string false is not found.
 */
function tableFieldValid($table, $field = '')
{
    $result = $this->tableColumns($table, $field);
    if (isset($result['Field'])) {
        return $result['Field'];
    } else {
        return false;
    }
}

/**
 * Retrieve all possible values for an ENUM column.
 *
 * @param  string           Table name.
 * @param  string           Field name to check for. Use '%' before and/or
 *                          after name for wildcard option.
 * @return  array    Collection of options or
 *          boolean  false in case of an error.
 */
function tableGetEnum($table, $field = '')
{
    $result = $this->tableColumns($table, $field);
    if (isset($result['Type'])) {
        $type_def = $result['Type'];

        /*
         * Search for correct column type, 'enum'.
         */
        $type_test = strpos($type_def, 'enum');
        $open  = strpos($type_def, '(');
        $close = strrpos($type_def, ')');
        if ($type_test === false || !$open || !$close) {
            return false;
        }
        $options = substr($type_def, $open + 2, $close - $open - 3);
        $options = explode('\',\'', $options);
        return $options;
    } else {
        return false;
    }
}

/**
 * Outputs tables structure to sql string.
 *
 * @param  string Table name.
 * @param  bool
 * @param  bool   Not in use.
 * @return string SQL string of data.
 */
function tableExportStructure($table, $if_not_exists = true,
    $auto_inc = false, $old_ver_comaptable = true)
{
    $dump = $this->_crlf . $this->_commentMarker
          . '--------------------------------------------------------'
          . $this->_crlf . $this->_crlf . $this->_commentMarker
          . $this->_crlf . $this->_commentMarker
          . 'Table structure for table \'' . $table . '\'' . $this->_crlf
          .  $this->_commentMarker . $this->_crlf;
    if ($if_not_exists) {
        $dump .= 'DROP TABLE IF EXISTS ' . $table . ';' . $this->_crlf;
    }
    $result = $this->query("SHOW CREATE TABLE $table;");
    $query = $this->fetchAssoc($result);
    $structure = $query['Create Table'];
    if ($old_ver_comaptable) {
        $_areplace = array(
            array("/DEFAULT.CHARSET=\w*/", ''),
            array("/ENGINE=/", 'TYPE=')
            );
        foreach ($_areplace as $value) {
            $structure = preg_replace($value[0], $value[1], $structure);
        }
    }
    // $dump .= str_replace('', '', $structure);
    $dump .= $structure;
    /*
    if (strpos($structure, 'auto_increment') !== false) {
        $dump .= AUTO_INCREMENT=27696
    }
    */
    $dump .= ';' . $this->_crlf;
    return $dump;
}

/**
 * Outputs tables data to sql string.
 *
 * @param  string Table name.
 * @return string SQL string of data.
 */
function tableExportData($table)
{
    $dump = '';
    $schema_insert = "INSERT INTO $table VALUES (";
    $fields_cnt = count($this->tableColumns($table));
    $result = $this->select($table);
    while ($row = $this->fetch_row($result)) {
        foreach ($row as $value) {
            $values[] = $this->translateValue($value);
        }
        /*
         * Extended inserts case
         */
        $dump .= $schema_insert . implode(', ', $values) . ');'
              . $this->_crlf;
        unset($values);
    }
    return $dump;
}

/**
 * Retrieve an array containing all tables names.
 *
 * @return array Collection of databases table names.
 */
function listTable()
{
    $result = $this->query('SHOW TABLES');
    $return = array();
    while ($row = $this->fetch_row($result)) {
        if (isset($row[0])) {
            $return[] = $row[0];
        }
    }
    return $return;
}

// -----------------------------------------------------------------------------
//                                  Counts.
// -----------------------------------------------------------------------------

/**
 * Returns the number of matching rows in a table.
 *
 * Always use '*' for columns. MySQL server can count rows faster if a
 * column is not specified, because it does not have to examine values in
 * each row it counts.
 *
 * @param  string Table name.
 * @param  string Select clause.
 * @param  string Columns to count. Default is all columns, '*'.
 * @return int    Count.
 */
function count($table, $where = 1, $columns = "*")
{
    $result = $this->select($table, $where, '', '', "count($columns)");
    $query  = $this->fetchRow($result);
    return (int) $query[0];
}

// -----------------------------------------------------------------------------
//                             Backup Database.
// -----------------------------------------------------------------------------

/**
 * Backup Database.
 *
 * Will backup database by producing a file named
 * $db-$year$month$day-$hour$min.gz under $DOCUMENT_ROOT/$backupdir.
 */
function backup($backupdir)
{
    /*
     * Compute day, month, year, hour and min.
     */
    $today = getdate();
    $day = $today['mday'];
    if ($day < 10) {
            $day = "0$day";
    }
    $month = $today['mon'];
    if ($month < 10) {
            $month = "0$month";
    }
    $year = $today['year'];
    $hour = $today['hours'];
    $min = $today['minutes'];
    $sec = '00';

    /*
     * Execute mysqldump command.
     * It will produce a file named $db-$year$month$day-$hour$min.gz
     * under $DOCUMENT_ROOT/$backupdir
     */
    $backup_file = sprintf(
        '%s%s-%s%s%s-%s%s.sql.gz',
        $backupdir,
        $this->_dbName,
        $month,
        $day,
        $year,
        $hour,
        $min);
    $cmd = sprintf(
        'mysqldump --opt -h %s -u %s -p%s %s | gzip > %s',
        $this->_dbHost,
        $this->_dbUsername,
        $this->_dbPassword,
        $this->_dbName,
        $backup_file);
    $result = @system($cmd, $retval);
    return $result . $retval . '<br />Database backup file: '
        . $backup_file;
}

// -----------------------------------------------------------------------------
//                       Miscellaneous methods.
// -----------------------------------------------------------------------------

/**
 * Escapes special characters in a string for use in a SQL statement.
 *
 * @param string Unescaped string.
 * @return string
 * @access public
 */
function escapeString($value)
{ // BEGIN function escapeString
    /*
     * When no resource link initially supplied, connect to database.
     */
    if (!is_resource($this->_dbResource)) {
        /*
         * Connect to MySQL server.
         */
        $this->_connect();
    }
    if ($this->_useMysqli) {
        return mysqli_real_escape_string($this->_dbResource, $value);
    } else {
        return mysql_real_escape_string($value, $this->_dbResource);
    }
} // END function escapeString

/**
 * Translate a php type into a readable sql statement.
 *
 * @param  mixed  Field value.
 * @return string
 */
function translateValue($value)
{
    $search = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
    $replace = array("\0", "\n", "\r", "\Z");
    // NULL
    if (!isset($value) || is_null($value)) {
        return "NULL";

    // a number
    } elseif (is_numeric($value)) {
        return $value;

    // empty string
    } elseif (empty($value)) {
        return "''";

    // something else -> treat as a string
    } else {
        return "'"
             . str_replace($search, $replace, addslashes($value))
             . "'";
    }
}

/**
 * Removes comment lines and splits up large sql files into individual queries
 *
 * Delivered from PMA_splitFileSql
 *
 * @param   array    sql commands
 * @param   string   sql commands
 * @return  boolean  always true
 */
function splitSql(&$ret, $sql)
{
    $sql          = trim($sql);
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = false;
    $nothing      = true;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        /*
         * We are in a string, check for not escaped end of strings except
         * for back-quotes that can't be escaped.
         */
        if ($in_string) {
            for (;;) {
                $i = strpos($sql, $string_start, $i);

                /*
                 * No end of string found -> add the current substring to
                 * the returned array.
                 */
                if (!$i) {
                    $ret[] = $sql;
                    return true;
                }

                /*
                 * Back-quotes or no back-slashes before quotes: it's indeed
                 * the end of the string -> exit the loop.
                 */
                else if ($string_start == '' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = false;
                    break;
                }

                /*
                 * One or more Backslashes before the presumed end of
                 * string...
                 */
                else {
                    /*
                     * ... first checks for escaped backslashes
                     */
                    $j                     = 2;
                    $escaped_backslash     = false;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = false;
                        break;
                    } else {
                        $i++;
                    }
                }
            }
        }
        // lets skip comments (/*, -- and #)
        else if (($char == '-' && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql[$i + 1] == '*')) {
            $i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
            // didn't we hit end of string?
            if ($i === false) {
                break;
            }
            if ($char == '/') $i++;
        }
        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
            $nothing    = true;
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return true;
            }
        }
        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '')) {
            $in_string    = true;
            $nothing      = false;
            $string_start = $char;
        }
        elseif ($nothing) {
            $nothing = false;
        }
        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        }
    }
    // add any rest to the returned array
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
        $ret[] = array('query' => $sql, 'empty' => $nothing);
    }
    return true;
}

/**
 * Return a new instance of class while copying properties.
 *
 * @return object
 * @access public
 */
function copyProperties()
{ // BEGIN function copyProperties
    $db = new Database_MySQL();
    $db->debug($this->debug(null));
    $db->dbHost($this->dbHost());
    $db->dbUsername($this->dbUsername());
    $db->dbPassword($this->dbPassword());
    $db->dbName($this->dbName());
    $db->dbResource($this->dbResource());
    return $db;
} // END function copyProperties

function dbDriver($value = null)
{
    return $this->_handleProperties('_dbDriver', $value);
}

function dbFile($value = null)
{
    return $this->_handleProperties('_dbFile', $value);
}

// -----------------------------------------------------------------------------
//                          Error Handling.
// -----------------------------------------------------------------------------

/**
 * Display information about the most recent query.
 *
 * @param  Additional error message.
 * @param  bool    Flags whether to stop script.
 * @return void
 * @access private
 */
function _error($msg = '', $_stop = false)
{
    static $resolve_mode = false;
    if (empty($this->_dbResource)) {
        $this->_error = $msg . ' :: Data source connection failed.';
        $this->_handleErrors(true);
    }
    if ($this->_useMysqli) {
        $errno = mysqli_errno($this->_dbResource);
    } else {
        $errno = mysql_errno($this->_dbResource);
    }
    $override = '_error_' . $errno;
    if (method_exists($this, $override)) {
        if (callUser_func(array(&$this, $override), $msg, $_stop)) {
            return;
        }
    }
    switch ($errno) {
    case 1046: // No database selected.
        if (!$resolve_mode) {
            $resolve_mode = true;
            $this->selectDatabase();
            return;
        }
        break;
    case 1049: // Unknown database.
        break;
    case 1107: // Can't create database.
        break;
    case 1146: // Table does not exist.
        $this->_error = 'Database tables are not setup correctly.';
        break;
    }
    if ($this->_useMysqli) {
        $errdes = mysqli_error($this->_dbResource);
    } else {
        $errdes = mysql_error($this->_dbResource);
    }
    $msg .= '::' . $errno . '::' . $errdes;
    $this->_error = $msg;
    $this->_handleErrors($_stop);
    $resolve_mode = false;
}

/**
 * Display error messages and ends script if flag is set to stop.
 *
 * @param  bool    Flags whether to stop script.
 * @return void
 * @access private
 */
function _handleErrors($_stop = true)
{
    if (!$this->_suppressError) {
        if ($this->_debug) {
            echo $this->_error . PHP_EOL;
        } else {
            echo 'Data source connection issues have stopped this package.';
        }
    }
    if ($_stop == true) {
        exit;
    }
}

// -----------------------------------------------------------------------------
//                              Private methods.
// -----------------------------------------------------------------------------

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

}  // END class Database_MySQL

/**
 * History:
 *  1.4.4
 *   - Cleaned up Constructor.
 *   - Added 'escapeString' method.
 *   - Automatically escapes array values before executing.
 *  1.4.3
 *   - Added copyProperties method.
 *   - Cache last result.
 *   - 'fetch_row' to 'fetchRow'
 *   - 'fetch_results' to 'fetchResults'
 *   - 'table_columns' to 'tableColumns'
 *   - 'list_table'    to 'listTable'
 *  1.4.2
 *   - Added persistent connection option.
 *  1.4.0
 *   - Standardize property names.
 *  1.3.6
 *   - Standardize method names.
 *     - '_db_select'      to 'selectDatabase'
 *     - 'database_close'  to 'closeDatabase'
 *     - 'database_create' to 'createDatabase'
 *     - 'database_delete' to 'deleteDatabase'
 *     - 'database_exists' to 'existsDatabase'
 *     - 'table_create'    to 'createTable'
 *     - 'tables_create'   to 'createTables'
 *     - 'table_delete'    to 'delete_table'
 *     - 'table_status'    to 'statusTable'
 *     - 'table_list'      to 'list_table'
 *     - '_db_connect'     to '_connect'.
 *   - Added 'existsDatabase' method.
 *   - Allow for custom error handle based on error number.
 *   - Added 'query' parameter which turns of force database select. Default
 *     is still a force select situation.
 *   - Renamed 'close' to 'closeDatabase'.
 *   - Type cast to int in count method.
 *  1.3.5
 *   - Check if $param['_dbName'] is set before assigning in constructor.
 *   - Script is terminated if a query is made before a connection.
 *   - Suppress php error when calling close method.
 *   - Added set accessor methods
 *   - Prevent a connection when _dbName or dbUsername is not set.
 *   - Revised '_createTables' to handle new table array.
 *   - No longer automatically create tables in '_error'. A table error no
 *     longer kills script.
 *   - Added method 'createDatabase'.
 *   - Added suppress all errors & selected database name accessor.
 *   - Count number of executed queries/statements.
 *   - Allow query to be specified as a key of a prior query.
 *  1.3.4
 *   - Added fetch_results method.
 *  1.3.3
 *   - Fixed ill call to query in '_createTables'.
 *   - Update 'count'.
 *  1.3.2
 *   - Added fetch_row function to handle Improved MySQL Extension.
 *  1.3.1
 *   - Fixed return bug in 'fetch_assoc'.
 *   - Fixed return bug in 'num_rows'.
 *  1.3.0
 *   - Update to handle Improved MySQL Extension.
 *   - Fixed a typo in '_db_select' where '_connect' was called and not the
 *     correct '_db_connect'.
 *   - Fixed a typo in 'query' where 'connect' was called and not the correct
 *     '_db_connect'.
 *   - Added method 'fetch_assoc' to handle differences in mysql extensions.
 *   - Added method 'num_rows' to handle differences in mysql extensions.
 *  1.2.2
 *   - Added getLastQuery as a  public accessor function.
 *   - Check if db_resource is set before check if valid. Prevents undefined
 *      notice.
 *   - Updated tableExportStructure to remove new MySQL table structure
 *     information.
 *  1.2.1
 *   - Fixed tableExportStructure to acuminate new db properties.
 *  1.2.0
 *   - Converted method to private and added '_' as name prefix.
 *   - Setup new error handling.
 *  1.1.6
 *   - Added translateValue, list_table function.
 *   - Updated close function to clear link value.
 *  1.1.5
 *   - Query function now test database connection before running query. Will
 *     connect if need be.
 *   - Added insertArray function, updateArray tableGetEnum, table_columns,
 *     tableExportData,
 *     function.
 *   - Allow for IF EXISTS in deleteTables function.
 *  1.1.4
 *   - Hides any error raised from system call when backing up database.
 *  1.1.3
 *   - Added statusTable function.
 *   - Added tableFieldValid function.
 *  1.1.2
 *   - Create connection information as objects properties.
 *  1.1.1
 *   - Added query timer with slow_query function.
 *   - Optimized count function by only selecting 'id'.
 *   - Added columns to select function as optional parameter.
 *  1.1.0
 *   - Removed custom database functions and table decorations
 *  1.0.1
 *   - Moved database connection information to separate file
 */

?>
