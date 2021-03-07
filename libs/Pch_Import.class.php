<?php
/**
 * Entry import class. Dervided from FileUploader class.
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
 * @version    $Id: Pch_Import.class.php,v 1.6 2006/01/13 06:42:16 gogogadgetscott Exp $
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005. SEG Technology. All rights reserved.
 */
/**
 * Entry import class. Dervided from FileUploader class.
 *
 * @package    phpChain
 * @subpackage FileUploader
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 */
class Pch_Import extends FileUploader
{ // BEGIN class Pch_Import

// -----------------------------------------------------------------------------
//                             Object Variables.
// -----------------------------------------------------------------------------

/**
 * Element data.
 *
 * @var    string
 * @access private
 */
var $elData;

/**
 * Element atribute.
 *
 * @var    string
 * @access private
 */
var $Atribute;

/**
 * Uploaded data.
 *
 * @var    int
 * @access private
 */
var $numResults;

/**
 * Imported entries
 *
 * @var    array
 * @access private
 */
var $entries;

/**
 * Current entry.
 *
 * @var    array
 * @access private
 */
var $entry;

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 *
 * @param  array   Various setting options.
 * @return obj     New instance of Pch_Import class
 * @access private
 */
function __construct($param = array())
{

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
//                            Accessor functions.
// -----------------------------------------------------------------------------


// -----------------------------------------------------------------------------
//                              General method.
// -----------------------------------------------------------------------------

/**
 * Parse imported file.
 *
 * @return bool Whether import is successful
 */
function import($uploadedFile = FALSE)
{ // BEGIN function import
    if ($uploadedFile == FALSE) {
        $uploadedFile = $this->file_path();
    }
    $data = @file_get_contents($uploadedFile);
    if (empty($data)) {
        return FALSE;
    }
    $this->entries = array();
    $this->entry   = array();
    /*
     * Parse xml feed data.
     */
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    /*
     * Set the handlers.
     */
    xml_set_object($parser, $this);
    xml_set_element_handler($parser, 'start_element', 'stop_element');
    xml_set_character_data_handler($parser, 'char_data');
    $err = xml_parse($parser, $data);
    /*
     * Free the parser resource.
     */
    xml_parser_free($parser);
    if (!$err) {
        return FALSE;
    }
    return TRUE;
} // END function import

function start_element($parser, $sTag, $arAttr)
{ // BEGIN function start_element
    /*
     * Start with empty data string.
     */
    $this->elData = '';
    /*
     * Put each attribute into the Data array.
     */
    foreach ($arAttr as $Key=> $Val) {
        $this->Atribute["$sTag:$Key"] = trim($Val);
    }
} // END function start_element

function char_data($parser, $sTag)
{ // BEGIN function char_data
      $this->elData .= $sTag;
} // END function char_data
    
function stop_element($parser, $sTag)
{ // BEGIN function stop_element
    $this->elData = html_entity_decode($this->elData, ENT_QUOTES);
    $this->elData = str_replace("\r", "", $this->elData);
    switch (strtolower($sTag)) {
    case 'group':
        $this->entry['group']    = $this->elData;
        break;
    case 'title':
        $this->entry['title']    = $this->elData;
        break;
    case 'username':
        $this->entry['login']    = $this->elData;
        break;
    case 'url':
        $this->entry['url']      = $this->elData;
        break;
    case 'password':
        $this->entry['password'] = $this->elData;
        break;
    case 'notes':
        $this->entry['notes']    = $this->elData;
        break;
    case 'image':
        $this->entry['image']    = $this->elData;
        break;
    case 'uuid':
        $this->entry['entryid']  = $this->elData;
        break;
    case 'pwentry';
       $this->entries[] = $this->entry;
       $this->entry = array(); 
       /*
        * Increase counter.
        */
       $this->numResults++;
       break;
    }
} // END function stop_element

/**
 * Delete uploaded file.
 *
 * @return void
 */
function deleteUpload($uploadedFile = FALSE)
{ // BEGIN function deleteUpload
    if ($uploadedFile == FALSE) {
        $uploadedFile = $this->file_path();
    }
    @unlink($uploadedFile);
} // END function deleteUpload


} // END class Pch_Import

?>
