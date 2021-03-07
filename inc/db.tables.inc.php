<?php
/**
 * MySQL Database table structure.
 */

/* 
 * Define the database table prefix names used in the package.
 */
if(!defined('PCH_TABLE_PREFIX')) {
    define('PCH_TABLE_PREFIX', 'pch_');
}
/*
 * PW tables constants.
 * Do NOT change constant names as they are used throughout package.
 */
define('PCH_TABLE_USERS'            , PCH_TABLE_PREFIX . 'users');
define('PCH_TABLE_LOG'              , PCH_TABLE_PREFIX . 'userlogs');
define('PCH_TABLE_SETTINGS'         , PCH_TABLE_PREFIX . 'settings');
define('PCH_TABLE_ICONS'            , PCH_TABLE_PREFIX . 'icons');
define('PCH_TABLE_GROUPS'           , PCH_TABLE_PREFIX . 'groups');
define('PCH_TABLE_ENTRIES'          , PCH_TABLE_PREFIX . 'entries');
define('PCH_TABLE_ENTRIES_TO_GROUPS', PCH_TABLE_PREFIX . 'entries_to_groups');
define('PCH_TABLE_ENTRIES_TO_FIELDS', PCH_TABLE_PREFIX . 'entries_to_fields');
/*
 * Do NOT change any field names as they are used throughout package.
 */
$db_tables = array();

$table_name = PCH_TABLE_USERS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('user'      , 'VARCHAR(45) NOT NULL')
    . sch_add_field('teststring', 'TEXT')
    . sch_add_field('iv'        , 'VARCHAR(256) NOT NULL')
    . sch_add_primary('userid'  , true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Application authorized users and settings.\'';

$table_name = PCH_TABLE_LOG;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('logid'     , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('user'      , 'VARCHAR(45) NOT NULL')
    . sch_add_field('ip'        , 'VARCHAR(15) NOT NULL')
    . sch_add_field('logged'    , 'INT(10) unsigned NOT NULL')
    . sch_add_field('refreshed' , 'INT(10) unsigned NOT NULL')
    . sch_add_field('outcome'   , 'TINYINT UNSIGNED NOT NULL')
    . sch_add_primary('logid')
    . sch_add_key  ('outcome'   , 'user, outcome', false, true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Login history.\'';

$table_name = PCH_TABLE_SETTINGS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('pwmask'    , 'TINYINT(1) NOT NULL DEFAULT 1')
    . sch_add_field('clipboard' , 'TINYINT(1) NOT NULL DEFAULT 1')
    . sch_add_field('generate'  , 'SMALLINT NOT NULL DEFAULT 10')
    . sch_add_field('defaultiv' , 'VARCHAR(256) NOT NULL')
    . sch_add_field('defaultun' , 'TEXT')
    . sch_add_field('expire'    , 'INT UNSIGNED NOT NULL DEFAULT 1800')
    . sch_add_primary('userid'  , true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'User settings.\'';

$table_name = PCH_TABLE_ICONS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('iconid'   , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('title'    , 'VARCHAR(64) NOT NULL')
    . sch_add_field('icon'     , 'TEXT')
    . sch_add_primary('iconid', true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Group and entry icons.\'';

$table_name = PCH_TABLE_GROUPS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('groupid'   , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('iv'        , 'VARCHAR(256) NOT NULL')
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('parentid'  , 'MEDIUMINT UNSIGNED NOT NULL')
    . sch_add_field('iconid'    , 'MEDIUMINT UNSIGNED NOT NULL')
    //. sch_add_field('title'     , 'VARCHAR(64) NOT NULL')
    . sch_add_field('ciphertext', 'TEXT')
    . sch_add_primary('groupid')
    . sch_add_key('userid', '', false, true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Entry groups.\'';
   
$table_name = PCH_TABLE_ENTRIES;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('entryid'   , 'VARCHAR(32) NOT NULL')
    . sch_add_field('iv'        , 'VARCHAR(256) NOT NULL')
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('iconid'    , 'MEDIUMINT UNSIGNED NOT NULL')
    . sch_add_field('ciphertext', 'TEXT')
    . sch_add_primary('entryid')
    . sch_add_key  ('userid', '', false, true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Entries.\'';

$table_name = PCH_TABLE_ENTRIES_TO_GROUPS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('linkid'    , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('groupid'   , 'MEDIUMINT UNSIGNED NOT NULL')
    . sch_add_field('entryid'   , 'VARCHAR(32) NOT NULL')
    . sch_add_primary('linkid')
    . sch_add_key  ('groupid')
    . sch_add_key  ('entryid', '', false, true)
    . ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
    . ' COMMENT = \'Relation table for many-to-many entries/categories.\'';

/*
 * Display MySQL table structure when file is call on its own.
 */
if (!defined('DBTABLEINCLUDE')) {


$page_body = "\n--\n-- phpChain database schematic\n--\n\n\n";
foreach ($db_tables as $key=>$table) {
    if ($key != 'db_name') {
        $page_body .=
              "--\n-- Table structure for table `$table[table_name]`\n"
            . "-- PHP constant " . sch_get_consant($table['table_name'])
            . "\n--\n\n\n"
            . "DROP TABLE IF EXISTS `$table[table_name]`;\n"
            . $table['structure'] . ";\n\n\n";
    }
}


@include 'Text/Highlighter.php';
if (class_exists('Text_Highlighter')) {
    echo '<link rel="stylesheet" href="../dev/hilight.css" />';
    include 'Text/Highlighter/Renderer/Html.php';
    $options = array(
         'numbers' => HL_NUMBERS_TABLE,
         'tabsize' => 4,
     );
    $renderer = new Text_Highlighter_Renderer_HTML($options);
    $renderer->_defClass = '';
    $highlighter =& Text_Highlighter::factory('mysql');
    $highlighter->setRenderer($renderer);
    echo $highlighter->highlight($page_body);
} else {
    highlight_string($page_body);
}

} // End if, !defined('DBTABLEINCLUDE')

/**
 * Start table structure.
 *
 * @param  string Table name.
 * @return string Formatted sql statement.
 * @access public
 */
function sch_add_table($name)
{
    $sql = "CREATE TABLE `" . $name . "` (\n";
    return $sql;
}

/**
 * Format field into valid sql.
 *
 * @param  string Field name.
 * @param  string Field type.
 * @return string Formatted sql statement.
 * @access public
 */
function sch_add_field($field, $structure, $last_entry = false)
{
    $sql = "\t" . $field . ' ' . $structure;
    if (!$last_entry) {
        $sql .= ',';
    }
    $sql .= "\n";
    return $sql;
}

/**
 * Format key into valid sql.
 *
 * @param  string Key name.
 * @param  string Key value.
 * @param  bool
 * @return string Formatted sql statement.
 * @access public
 */
function sch_add_key($key, $value = '', $fulltext = false, $last_entry = false)
{
    if (empty($value)) {
        $value = $key;
    }
    $sql = "\t";
    if ($fulltext) {
        $sql .= 'FULLTEXT ';
    }
    $sql .= 'KEY ' . $key . ' (' . $value . ')';
    if (!$last_entry) {
        $sql .= ',';
    }
    $sql .= "\n";
    return $sql;
}

/**
 * Format key into valid sql.
 *
 * @param  string Key name.
 * @param  string Key value.
 * @param  bool
 * @return string Formatted sql statement.
 * @access public
 */
function sch_add_primary($key, $last_entry = false)
{
    $sql = "\t" . 'PRIMARY KEY (' . $key . ')';
    if (!$last_entry) {
        $sql .= ',';
    }
    $sql .= "\n";
    return $sql;
}

/**
 * Convert constant value into string name.
 *
 * @param  mixed  Constant value.
 * @return string Constant name.
 * @access public
 */
function sch_get_consant($value)
{
    $constants = get_defined_constants();
    $name = array_search($value, $constants, true);     
    return $name;
}

?>
