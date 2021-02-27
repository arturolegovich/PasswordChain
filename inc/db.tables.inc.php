<?php
/**
 * MySQL Database table structure.
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
 * @version   $Id: db.tables.inc.php,v 1.23 2006/01/13 06:42:16 gogogadgetscott Exp $
 * @link      http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2005-2006. SEG Technology. All rights reserved.
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
    . sch_add_field('iv'        , 'VARCHAR(16) NOT NULL')
    . sch_add_primary('userid'  , true)
    . ') TYPE=MyISAM'
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
    . ') TYPE=MyISAM'
    . ' COMMENT = \'Login history.\'';

$table_name = PCH_TABLE_SETTINGS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('pwmask'    , 'TINYINT(1) NOT NULL DEFAULT 1')
    . sch_add_field('clipboard' , 'TINYINT(1) NOT NULL DEFAULT 1')
    . sch_add_field('generate'  , 'SMALLINT NOT NULL DEFAULT 8')
    . sch_add_field('defaultiv' , 'VARCHAR(16) NOT NULL')
    . sch_add_field('defaultun' , 'TEXT')
    . sch_add_field('expire'    , 'INT UNSIGNED NOT NULL DEFAULT 300')
    . sch_add_primary('userid'  , true)
    . ') TYPE=MyISAM'
    . ' COMMENT = \'User settings.\'';

$table_name = PCH_TABLE_ICONS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('iconid'   , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('title'    , 'VARCHAR(64) NOT NULL')
    . sch_add_field('icon'     , 'TEXT')
    . sch_add_primary('iconid', true)
    . ') TYPE=MyISAM'
    . ' COMMENT = \'Group and entry icons.\'';

$table_name = PCH_TABLE_GROUPS;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('groupid'   , 'MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT')
    . sch_add_field('iv'        , 'VARCHAR(16) NOT NULL')
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('parentid'  , 'MEDIUMINT UNSIGNED NOT NULL')
    . sch_add_field('iconid'    , 'MEDIUMINT UNSIGNED NOT NULL')
    //. sch_add_field('title'     , 'VARCHAR(64) NOT NULL')
    . sch_add_field('ciphertext', 'TEXT')
    . sch_add_primary('groupid')
    . sch_add_key('userid', '', false, true)
    . ') TYPE=MyISAM'
    . ' COMMENT = \'Entry groups.\'';
   
$table_name = PCH_TABLE_ENTRIES;
$db_tables[$table_name]['table_name'] = $table_name;
$db_tables[$table_name]['structure']  =
      sch_add_table($db_tables[$table_name]['table_name'])
    . sch_add_field('entryid'   , 'VARCHAR(32) NOT NULL')
    . sch_add_field('iv'        , 'VARCHAR(16) NOT NULL')
    . sch_add_field('userid'    , 'VARCHAR(32) NOT NULL')
    . sch_add_field('iconid'    , 'MEDIUMINT UNSIGNED NOT NULL')
    . sch_add_field('ciphertext', 'TEXT')
    . sch_add_primary('entryid')
    . sch_add_key  ('userid', '', false, true)
    . ') TYPE=MyISAM'
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
    . ') TYPE=MyISAM'
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
    $renderer =& new Text_Highlighter_Renderer_HTML($options);
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
