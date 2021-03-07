<?php
/**
 * Home document.
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
 * @version    $Id: index.php,v 1.16 2006/01/10 08:24:43 gogogadgetscott Exp $
 * @link       http://phpchain.sourceforge.net/
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
define('PCH_FS_HOME', '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
    include PCH_FS_HOME   . 'define.php';
} else {
    die("Cannot find define.php file.\n");
}
if ($pch->auth()) {
    $userLogs = $pch->getLogs();
    $count = count($userLogs);
    if ($count > (PCH_MAX_TRIES + 1)) {
        $pch->deleteLogs($userLogs[(PCH_MAX_TRIES + 1)]['logged']);
    }
    $userLogs = array_slice($userLogs, 1, (PCH_MAX_TRIES));
    $count = count($userLogs);
    for ($i = 0; $i < $count; $i++) {
        $address = @gethostbyaddr($userLogs[$i]['ip']);
        if (!empty($address)) {
            $userLogs[$i]['ip'] = $address . ' (' . $userLogs[$i]['ip'] . ')';
        }
        

        $userLogs[$i]['duration'] = $pch->getDuration(
            $userLogs[$i]['logged'], $userLogs[$i]['refreshed']);
    }
    $pch->assign('userLogs', $userLogs);
}
/*
 * Parse Smarty templates.
 */
$pch->display('index', 'Главная страница');

?>
