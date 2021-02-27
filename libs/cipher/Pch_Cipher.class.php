<?php
/**
 * Base class implementing Cipher interface enryption.
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
 * @version    $Id: Pch_Cipher.class.php,v 1.10 2006/01/10 08:24:45 gogogadgetscott Exp $
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */
/**
 * Base class implementing the Cipher interface enryption.
 *
 * @package phpChain
 * @subpackage Crypt
 * @author  Scott Greenberg <me@gogogadgetscott.coScott
 * @todo Setup error handling.
 */
class Pch_Cipher
{ // BEGIN class Pch_Cipher

// -----------------------------------------------------------------------------
//                             Variables.
// -----------------------------------------------------------------------------

/**
 * Size of the IV belonging to a specific cipher/mode combination.
 *
 * @var    int
 * @access private
 */
var $_iv_size;

/**
 *  Block cipher mode.
 *
 * @var    string
 * @access private
 */
var $_mode;

/**
 *  Cipher.
 *
 * @var    string
 * @access private
 */
var $_cipher;

/**
 * Whether to use mcrypt funcations.
 *
 * @var    bool
 * @access private
 */
var $_use_mcrypt;

/**
 * Horde object used when mcrypt is not available.
 *
 * @var object
 * @access private
 */
var $_h;

// -----------------------------------------------------------------------------
//                      Constructor/Destructor.
// -----------------------------------------------------------------------------

/**
 * Constructor.
 *
 * @return obj     New instance of Pch_Cipher class
 * @access private
 */
function Pch_Cipher($crypt = 'blowfish')
{
    $this->_use_mcrypt = extension_loaded('mcrypt') ? true: false;
    $this->_mode = 'cbc';
    $this->_cipher = strtolower($crypt);
    if ($this->_use_mcrypt) {
        $this->_iv_size = mcrypt_get_iv_size($this->_cipher, $this->_mode);
    } else {
        switch ($this->_cipher) {
        case 'blowfish':
            $this->_iv_size = 8;
            break;
        default:
            $this->_iv_size = 0;
            break;
        }
    }
}

/**
 * Destructor
 *
 * @return void
 * @access private
 */
function _Pch_Cipher()
{
}

/**
 * Seeds random number generator.
 *
 * @param integer
 * @return void
 * @access public
 */
function seed($value = null)
{ // BEGIN function seed
    if (is_null($value)) {
        $value = (double) microtime() * 1000000;
    }
    mt_srand($value);
    srand($value);
} // END function seed

/**
 * Create an initialization vector (IV) from a random source
 *
 * @return string IV.
 * @access public
 */
function makeIv()
{
    if ($this->_use_mcrypt) {
        $iv = mcrypt_create_iv($this->_iv_size, MCRYPT_RAND);
    } else {
        $randMax = mt_getrandmax();
        $iv = "\0\0\0\0\0\0\0\0";
        $randData = $this->genRandom($this->_iv_size);
        for ($i = 0; $i < $this->_iv_size; $i++) {
            $iv[$i] = chr(255.0 * $randData[$i] / $randMax);
        }
    }
    return $iv;
}

/**
 * Encrypt data.
 *
 * @param  string Data.
 * @param  string IV.
 * @param  string Key.
 * @return string Base64 encrypted data.
 * @access public
 * @abstract
 */
function encrypt($data, $iv, $key)
{
    if (!$data || !$iv || !$key) return '';
    if (!$this->_use_mcrypt) {
        $this->_init_horde();
        $this->_h->setIV($iv);
        $this->_h->setKey($key);
        $data = $this->_h->encrypt($data);
        unset($this->_h);
    } else {
        $data = @mcrypt_encrypt($this->_cipher, $key, $data,
            $this->_mode, $iv);
    }
    return base64_encode($data);
}

/**
 * Decrypt data. Please note that iv must be base64 decoded before passing.
 *
 * @param  string base64 encoded data.
 * @param  string base64 decoded iv.
 * @param  string base64 encoded key.
 * @return string Decrypted data.
 * @access private
 */
function decrypt($data, $iv, $key)
{
    if (!$data || !$iv || !$key) return '';
    $data = base64_decode($data);
    if (!$this->_use_mcrypt) {
        $this->_init_horde();
        $this->_h->setIV($iv);
        $this->_h->setKey($key);
        $data = $this->_h->decrypt($data);
        unset($this->_h);
    } else {
        $data = @mcrypt_decrypt($this->_cipher, $key, $data,
            $this->_mode, $iv);
    }
    return trim($data);
}

// -----------------------------------------------------------------------------
//                                RNG Methods.
// -----------------------------------------------------------------------------

/**
 * Generate a random value.
 *
 * Currently this method is only configured for mt_rand.
 *
 * @param integer Minimum value.
 * @param integer Maximum value.
 * @return array List of random values.
 * @todo Implement URL cache.
 */
function genRandom($num = 1, $min = 0, $max = 1000000000)
{ // BEGIN function genRandom
    if ($num < 1) {
        $num = 1;
    }
    $randUrl = 'http://www.random.org/cgi-bin/randnum?num=' . $num
        . '&min=' . $min . '&max=' . $max . '&col=1';
    $randUrlMax = 10 ^ 8;
    $randUrlMin = -1 * $randUrlMax;
    $data = '';
    $altMethod = false;
    if ($altMethod && $file = @fopen("/dev/urandom", "r")) {
         $data .= bin2hex(fread($file, 32));
         fclose($file);
    } elseif ($altMethod && $file = @fopen($randUrl, 'r')) {
        if ($min > $randUrlMin) { $min = $randUrlMin; }
        if ($max > $randUrlMax) { $max = $randUrlMax; }
        while (!feof($file)) {
              $data .= fread($file, 2048);
        }
        fclose($file);
        $data = trim($data);
        $data = explode("\n", $data, $num);
        foreach ($data as $key=>$value) {
            $data[$key] = (int)$value;
        }
    } else {
        for ($i = 0; $i <= $num; $i++) {
            $data[] = mt_rand($min, $max);
        }
    }
    if ($num == 1) {
        $data = $data[0];
    }
    return $data;
} // END function genRandom

// -----------------------------------------------------------------------------
//                              Private Methods.
// -----------------------------------------------------------------------------

/**
 * Initializes the Horde Blowfish object
 *
 * @param string cipher.
 * @access private
 */
function _init_horde()
{
    if (isset($this->_b)) return;
    $file  = PCH_FS_HORDE . 'Cipher.php';
    if (is_file($file)) {
        require_once $file;
    } else {
        exit("Cannot find Horde libs.\n");
    }
    $this->_h =& Horde_Cipher::factory($this->_cipher);
    $this->_h->setBlockMode($this->_mode);
}

} // END class Pch_Cipher

?>
