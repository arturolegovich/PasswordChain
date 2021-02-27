<?php
/**
 * Login page.
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
 * @version    $Id: user.php,v 1.2 2006/01/13 06:42:14 gogogadgetscott Exp $
 * @link       http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
define('PCH_FS_HOME'     , '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
  include PCH_FS_HOME   . 'define.php';
} else {
  die("Cannot find define.php file.\n");
}
if ($pch->auth()) {
    /*
     * Logout.
     */
    $pch->deleteCookie();
    $pch->redirect();
}
$user = $pch->reqData('user' , VR_POST, VR_MIXED, PCH_INVALID_DATA);
$key  = $pch->reqData('key'  , VR_POST, VR_PASSWORD);

if ($user == PCH_INVALID_DATA) {

} elseif (empty($user)) {
    $errors[] = 'Please enter user.';
} elseif (empty($key)) {
    $errors[] = 'Please enter password.';
} else {
    $pch->user($user);
    $pch->key($pch->hashKey($key));
	/*
     * Check for failed login attempts within time limit.
     */
    $count = count($pch->getLogs(0, PCH_TIME_LIMIT));
    if ($count > PCH_MAX_TRIES) {
        $errors[] = 'Too many failed login attempts. Please wait '
            . PCH_TIME_LIMIT / 60 . ' or more minutes before next login.';
    } else {
        if ($pch->checkUser()) {
            $pch->setCookies();
            $pch->redirect();
		} else {
			$errors[] = 'Incorrect user and/or password.';
		}
	}
}
if ($user == PCH_INVALID_DATA) {
    $user = '';
}
/*
 * Parse Smarty Header template.
 */
$pch->display('user', 'User');

?>
