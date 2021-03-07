<?php
/**
 * Settings page.
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
 * @version   $Id: settings.php,v 1.28 2006/01/13 06:42:14 gogogadgetscott Exp $
 * @link      http://phpchain.sourceforge.net/
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 * @todo Preform check for import requirements. See ./moodle/lib/compatible.php
 */
define('PCH_FS_HOME'     , '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
    include PCH_FS_HOME   . 'define.php';
} else {
    die("Cannot find define.php file.\n");
}
$page = 'Настройки';
if (!$pch->auth()) {
	$pch->redirect();
}
switch ($action) {
case 'settings':
    $settings['pwmask']    = $pch->reqData('pwmask'   , VR_POST, VR_DIGIT , 1);
    $settings['clipboard'] = $pch->reqData('clipboard', VR_POST, VR_DIGIT , 1);
    $settings['generate']  = $pch->reqData('generate' , VR_POST, VR_DIGIT , 1);
    $settings['defaultun'] = $pch->reqData('defaultun', VR_POST, VR_STRING, '');
    $settings['expire']    = $pch->reqData('expire'   , VR_POST, VR_DIGIT , 300);
    $pch->setSetting($settings);
    $pch->setCookies();
    $msgs[] = 'Settings saved.';
default:
    /*
     * Parse Smarty Header template.
     */
    $pch->display('settings', $page);
    break;
}

?>
