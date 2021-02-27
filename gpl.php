<?php
/**
 * GPL.
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
define('PCH_FS_HOME', '.' . DIRECTORY_SEPARATOR);
if (is_file(PCH_FS_HOME . 'define.php')) {
  include PCH_FS_HOME   . 'define.php';
} else {
  die("Cannot find define.php file.\n");
}
/*
 * Parse Smarty template.
 */
$pch->display('gpl', 'GNU General Public License');


?>
