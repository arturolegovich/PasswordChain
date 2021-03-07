<?php
/**
 * New user.
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
 * @version   $Id: newuser.php,v 1.3 2006/01/15 07:13:34 gogogadgetscott Exp $
 * @link      http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 * @todo Ask user if start groups should be created.
 */
define('PCH_FS_HOME'     , '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
    include PCH_FS_HOME   . 'define.php';
} else {
    die("Cannot find define.php file.\n");
}
/*
 * Log user out when loading setup page.
 */
$pch->deleteCookie();

$newuser  = $pch->reqData('newuser', VR_POST, VR_MIXED, PCH_INVALID_DATA);
$newkey   = $pch->reqData('newkey' , VR_POST, VR_PASSWORD);
$newkey2  = $pch->reqData('newkey2', VR_POST, VR_PASSWORD);
$submit   = $pch->reqData('submit' , VR_POST);
if (!empty($submit)) {
    /*
     * Check login.
     */
    if (!$pch->isLength($newuser, 3, 256)) {
        $errors[] = 'User must be between 3 and 256 characters long.';
    }
    if ($newuser == PCH_INVALID_DATA) {
        $errors[] = 'User contains invalid characters.';
    }
    /*
     * Check key.
     */
    if (!$pch->isLength($newkey, 3, 256)) {
        $errors[] = 'Password must be between 3 and 256 characters long.';
    } elseif ($newkey != $newkey2) {
        $errors[] = 'The passwords you have entered do not match.';
    }
    if (empty($errors)) {
        if ($pch->addUser($newuser, $newkey)) {

            $pch->user($newuser);
            $pch->key($pch->hashKey($newkey));
            $pch->auth();
            $pch->createSampleGroups();
            $pch->clearAccessors();
            
            $msgs[] = 'User successfully created.';
        } else {
            $newuser = '';
            $errors[] = 'User already exists. Please choose another.';
        }
    }
}
if ($newuser == PCH_INVALID_DATA) {
    $newuser = '';
}
$pch->assign('newUser', $newuser);
/*
 * Parse Smarty Header template.
 */
$pch->display('newuser', 'Регистрация');

?>
