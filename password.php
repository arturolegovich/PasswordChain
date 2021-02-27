<?php
/**
 * Change master password.
 *
 * History:
 *
 * @package    phpChain
 * @version    v2.0.1, 07/15/2003 -- 04/08/2005
 * @link       http://gogogadgetscott.info/computers/passwordchain/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 *             James Gurney <james@globalmegacorp.org>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
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
 */
$page = 'password';
define('PCH_FS_HOME'     , '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
  include PCH_FS_HOME   . 'define.php';
} else {
  die("Cannot find define.php file.\n");
}
if (!$pch->auth()) {
    $pch->redirect();
}
$newkey   = $pch->reqData('newkey'  , VR_POST, VR_PASSWORD);
$newkey2  = $pch->reqData('newkey2' , VR_POST, VR_PASSWORD);
$complete = $pch->reqData('complete');
if (empty($complete)) {
    $complete = 0;
}
if (!empty($newkey) || !empty($newkey2)) {
    if (!$pch->isLength($newkey, 3, 255)) {
        $errors[] = 'Password must between 3 and 255 characters long.';
    } elseif ($newkey != $newkey2) {
        $errors[] = 'The passwords you have entered do not match.';
    } elseif ($action == 'save') {
    	$result = $pch->changePassword($newkey);
    	if ($result) {
    	    $pch->deleteCookie();
    	    $action = 'complete';
        } else {
            $errors[] = 'The password you enter should be different from current one.';
        }
    }
}
/*
 * Parse Smarty Header template.
 */
$pch->display('password', 'Change Password');

?>
