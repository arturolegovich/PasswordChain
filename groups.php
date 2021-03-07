<?php
/**
 * Group management page.
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
 * @version    $Id: groups.php,v 1.7 2006/01/13 06:42:14 gogogadgetscott Exp $
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 *
 */
define('PCH_FS_HOME'     , '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
  include PCH_FS_HOME   . 'define.php';
} else {
  die("Cannot find define.php file.\n");
}
$page = 'Управление группами';
if (!$pch->auth()) {
	$pch->redirect();
}
/*
 * Turn off php maximum execution time limit for script.
 * Required for imports runs.
 */
@set_time_limit(0);
switch ($action) {
case 'import';
    require PCH_FS_LIBS . 'FileUploader.class.php';
    require PCH_FS_LIBS . 'Pch_Import.class.php';

    $importRate  = 45;

	/*
     * Create a new instance of the class.
     */
	$import = new Pch_Import();

	/*
     * Check if make 2 or greater pass.
     */
    $uploadedFile = base64_decode(
        $pch->reqData('uploadedFile', VR_ANY, VR_STRING, ''));
    $importPass = intval($pch->reqData('importPass', VR_ANY, VR_NUMBER, 0));

    if (empty($uploadedFile)) {
        $importPass = 0;
    	/*
         * UPLOAD the file.
         */
    	if ($import->upload('importfile', 'text/xml', '.xml')) {
    		$import->save_file(PCH_FS_CACHE, 2);
    	}
    	if ($import->error) {
    		$errors[] = $import->error;
	    } else {
        	/*
             * Successful upload.
             */
            $uploadedFile = $import->file_path();
        }
    }

    if (!empty($uploadedFile)) {
        if ($import->import($uploadedFile)) {

            $importStart = $importPass * $importRate;
	        $entries = array_slice($import->entries, $importStart,
                $importRate);
            foreach ($entries as $count=>$entry) {
                $entry['groupid'] = $pch->getGroupid($entry['group']);
                if ($entry['groupid'] == 0) {
                    $entry['groupid'] = $pch->addGroup($entry['group']);
                }
                $entry['entryid'] = $pch->addEntry($entry);
            }
            if ($importStart + $importRate < $import->numResults) {
                $params = array(
                    'action'       => 'import',
                    'uploadedFile' => base64_encode($uploadedFile),
                    'importPass'   => $importPass + 1,
                    );
                $msgs[] = count($entries) . ' entries imported. '
                    . 'Pass complete <a href="'
                    . $pch->urlSelf($params)
                    . '">click here</a> to continue.';
            } else {
                $import->deleteUpload($uploadedFile);
        		$msgs[] = $import->numResults . ' total entries imported.'
                    . ' Process is complete.';
            }
		} else {
		    $errors[] = 'Import failed, data file is corrupt.';
        }
	}
    /*
     * Display messages/errors.
     */
    $pch->display('import', 'Import');
    break;
case 'save':
    $group['groupid'] = $groupid;
	$group['title']   = $pch->reqData('group_title', VR_POST, VR_STRING);
	$pch->addGroup($group);
    $pch->redirect($pch->urlSelf());
	break;
case 'delete':
    $check  = $pch->reqData('check');
	if ($check == 1) {
		$pch->deleteGroups($groupid);
        $pch->redirect($_SERVER['PHP_SELF']);
    }
    /* 
     * Get delete conformation.
     */
    $pch->assign('groupid' , $groupid);
    /*
     * Parse Smarty template.
     */
    $pch->display('entry_delete');
    break;
case 'edit':
	if ($groupid == 0) {
		$group_title = '';
	} else {
		$group_title = $pch->getGroup($groupid);
	}
	$pch->assign('group_title', $group_title);
    /*
     * Parse Smarty Header template.
     */
    $pch->display('groups_form');
    break;
default:
    /*
     * Parse Smarty Header template.
     */
    $pch->display('groups', $page);
    break;
}

?>
