<?php
/**
 * Twofish algorithm class implementing the Cipher interface enryption.
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
 * @version    $Id: Pch_Cipher_Twofish.class.php,v 1.5 2006/01/03 07:33:49 gogogadgetscott Exp $
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
/**
 * Twofish algorithm class implementing the Cipher interface enryption.
 *
 * @package phpChain
 * @subpackage Crypt
 * @author  Scott Greenberg <me@gogogadgetscott.com>
 */
class Pch_Cipher_Twofish extends Pch_Cipher
{ // BEGIN class Pch_Cipher_Twofish

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 *
 * @return obj     New instance of Pch_Cipher_Twofish class
 * @access private
 * @todo Setup error handling in recieving (Pch_Main) class.
 */
function Pch_Cipher_Twofish()
{
    $this->_iv_size = 16;
    $this->_use_mcrypt = extension_loaded('mcrypt') ? true: false;
    if (!$this->_use_mcrypt) {
        exit('MCyrpt extention is not load.');
    }
}

/**
 * Destructor
 *
 * @return void
 * @access private
 */
function _Pch_Cipher_Twofish()
{
}

// -----------------------------------------------------------------------------
//                    General Methods.
// -----------------------------------------------------------------------------

/**
 * Encrypt data.
 *
 * @param  string Data.
 * @param  string IV.
 * @param  string Key.
 * @return string Base64 encrypted data.
 * @access public
 */
function encrypt($data, $iv, $key)
{
    return $this->_encrypt(MCRYPT_TWOFISH, $data, $iv, $key);
}

/**
 * Decrypt data.
 *
 * @param  string base64 encoded data.
 * @param  string base64 decoded iv.
 * @param  string base64 encoded key.
 * @return string Decrypted data.
 * @access private
 */
function decrypt($data, $iv, $key)
{
    return $this->_decrypt(MCRYPT_TWOFISH, $data, $iv, $key);
}

} // END class Pch_Cipher_Twofish

?>
