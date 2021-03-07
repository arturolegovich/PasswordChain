<?php
/**
 * Entries.
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
 * @version   $Id: entries.php,v 1.34 2006/02/27 04:39:51 gogogadgetscott Exp $
 * @link      http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
define('PCH_FS_HOME', '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
  include PCH_FS_HOME   . 'define.php';
} else {
  die("Cannot find define.php file.\n");
}
if (!$pch->auth()) {
	$pch->redirect();
}
switch($action) {
case 'redirect':
    if ($entryid != 0) {
        $entries = $pch->getEntries($entryid);
        if (!$entries) {
            $pch->redirect();
        }
        $pch->assign('entries', $entries);
    }
    $pch->display('redirect', 'Auto-Login');
    break;
case 'delete':
    $check  = $pch->reqData('check');
	if ($check == 1) {
        $pch->deleteEntry($entryid);
        $pch->redirect('entries.php?groupid=' . $groupid);
	}
	
    /*
     * Parse Smarty template before getting user check.
     */
    $pch->display('entry_delete', 'Delete Entry');	
    break;
case 'edit':
    if (strlen($entryid) == PCH_ID_LENGTH) {
    	/*
         * Get existing data and decrypt it first.
         */
    	$entries = $pch->getEntries($entryid, false, false);
    	if (!$entries) {
    		unset($entries);
    	}
    } else {
        $entries[0]['groupid'] = $groupid;
        $entries[0]['title']   = '';
        $entries[0]['url']     = '';
        $entries[0]['login']   = $pch->getSettings('defaultun');
        $generate = (int) $pch->getSettings('generate');
        if ($generate > 0) {
            $entries[0]['password'] = $pch->generatePassword($generate);
        } else {
            $entries[0]['password'] = '';
        }
        $entries[0]['notes'] = '';
    } 
    if (isset($entries) && $entries !== false) {
        $pch->assign('entries', $entries);
        
        /*
         * Pull current group ID from first entry.
         */
        $pch->assign('groupid', $entries[0]['groupid']);
    } else {
        $pch->assign('entries', '');
        $pch->assign('error', 'No permission to edit this entry!');
    }
    
    /*
     * Parse Smarty template.
     */
    $pch->display('entry_form', 'Edit Entires');
    break;
case 'save':
    $entry['entryid']      = $entryid;
    $entry['groupid']      = $groupid;
    $entry['title']        = $pch->reqData('title'   , VR_POST, VR_STRING);
    $entry['url']          = $pch->reqData('url'     , VR_POST, VR_URL);
    $entry['login']        = $pch->reqData('login'   , VR_POST, VR_STRING);
    $entry['password']     = $pch->reqData('password', VR_POST, VR_STRING);
    $entry['notes']        = $pch->reqData('notes'   , VR_POST, VR_NONE);
    if (!empty($entry['url']) 
        && strpos($entry['url'], 'http://')  === false
        && strpos($entry['url'], 'https://') === false) {

        $entry['url'] = "http://" . $entry['url'];
    }
    
    /*
     * Encrypt login and pass using key.
     */
     
    $pch->addEntry($entry);
    /*
     * Clear entry id and server group entries.
     */
    $entryid = false;    
default: // view
    /*
     * Initialize search.
     */
    $search = $pch->reqData('search', VR_POST, VR_STRING, '');
    /*
     * Check that a category is selected.
     */
    if ($groupid == -1) {
        $groupid = false;
	} elseif ($groupid == -2) {
	    $groupid = false;
        $pch->assign('search', $search);
	    $search = array(false, $search,
            array('title', 'url', 'login', 'password', 'notes'));
	} elseif ($groupid <= 0) {
        $pch->redirect();
    }
    
    /*
     * Get requested view.
     */
	$valid_data = array('html', 'xml');
    $output = $pch->reqData('output', VR_ANY, $valid_data, $valid_data[0]);
    
    /*
     * Get requested sorting column.
     */
	$valid_data = array('title', 'login', 'password', 'site', 'url', 'info');
    $sort_col = $pch->reqData('sort', VR_ANY, $valid_data, $valid_data[0]);
    $pch->assign('sort_col', $sort_col);
    
    /*
     * Get requested sort order.
     */
	$valid_data = array('SORT_ASC', 'SORT_DESC');
    $sort_flag = constant($pch->reqData('order', VR_ANY, $valid_data,
		$valid_data[0]));
    if ($sort_flag == SORT_ASC) {
		$pch->assign('sort_flag', 'SORT_ASC');
	} else {
		$pch->assign('sort_flag', 'SORT_DESC');
	}

    /*
     * Get entries.
     */
    $pch->assign('entries', $pch->getEntries($entryid, $groupid, 
		true, $sort_col, $sort_flag, true, $search));
	$pch->assign('entries_count', $pch->entriesLstCnt());

    /*
     * Parse Smarty template.
     */
    if ($output == 'xml') {
		$pch->display('entries.' . $output, 'Output Entries', false,
            'application/xml');
	} else {
    	$pch->display('entries.' . $output, 'Просмотр записей');
    }
    break;
}

?>
