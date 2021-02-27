<?php
/**
 * Send Http headers.
 *
 * Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package   GoGo_Web_Application
 * @version   $Id: Send_HTTP_Headers.class.php,v 1.11 2006/01/10 00:52:45 gogogadgetscott Exp $
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 */
/**
 * HTTP Headers Class
 *
 * @package    GoGo_Web_Application
 * @subpackage GoGo_Http
 * @version    v1.1.3, 08/15/2004 -- 09/21/2005
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 */
class Send_HTTP_Headers
{ // BEGIN class Send_HTTP_Headers

/**
 * Constructor
 */
function HTTP_Headers($headers = null)
{
    if (is_string($headers)) {
        $this->sendRawHeader($headers);
    } elseif (is_array($headers)) {
        foreach ($headers as $header) {
              if (is_string($header)) {
                $this->sendRawHeader($header);
            }
          }
    }
}

// -----------------------------------------------------------------------------
//                          HTTP Functions.
// -----------------------------------------------------------------------------
    
/**
 * Send a raw HTTP header.
 *
 * @param  string Header string.
 * @param  bool   Whether the header should replace a previous similar 
                  header, or add a second header of the same type.
 * @return bool      Whether header is set correcly.
 */
function sendRawHeader($string = null, $replace = true)
{
    if (isset($string) && !headers_sent()) {
        header($string, $replace);
        return true;
    }
    return false;
}

// -----------------------------------------------------------------------------
//                      Header Field Definitions.
// -----------------------------------------------------------------------------

/**
 * Cache-Control general-header field is used to specify directives that
 * MUST be obeyed by all caching mechanisms along the request/response
 * chain.
 *
 * @param  string Content type string.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function cacheControl($directive = 'no-store, no-cache, must-revalidate', 
    $replace = true)
{
    $this->sendRawHeader('Cache-Control: ' . $directive, $replace);
}


/**
 * Content-Disposition response-header field has been proposed as a means
 * for the origin server to suggest a default filename if the user requests
 * that the content is saved to a file.
 *
 * @param  string Content type string.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function contentDisposition($media_type = 'text/html; charset=ISO-8859-4', 
    $replace = true)
{
    $this->sendRawHeader('Content-Disposition: ' . $media_type, $replace);
}

/**
 * Content-Length entity-header field indicates the size of the entity-body,
 * in decimal number of OCTETs, sent to the recipient or, in the case of the
 * HEAD method, the size of the entity-body that would have been sent had
 * the request been a GET.
 *
 * @param  int    Content length.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function contentLength($length = 0, $replace = true)
{
    $this->sendRawHeader('Content-Length: ' . $length, $replace);
}

/**
 * Set the media type of the entity-body sent to the recipient.
 *
 * Available charset's:
 * - utf-8
 * - ISO-8859-1
 * - ISO-8859-4
 *
 * @param  string Content type string.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function  contentType($media_type = 'text/html', $charset = 'utf-8',
    $replace = true)
{
    if (!empty($charset)) {
        $media_type .= '; charset=' . $charset;
    }
    $this->sendRawHeader('Content-type: ' . $media_type, $replace);
}

/**
 * Set the media encoding type of the entity-body sent to the recipient.
 *
 * @param  string Content encoding type.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return bool      Whether header is set correcly.
 */
function contentEncoding($type = 'gzip', $replace = true)
{
    if (!empty($charset)) {
        $media_type .= '; charset=' . $charset;
    }
    return $this->sendRawHeader('Content-Encoding: ' . $type, $replace);
}

/**
 * Set entity-header field gives the date/time after which the response is
 * considered stale.
 *
 * @param  int    Timestamp.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function expires($date = 0, $replace = true)
{
    $date = gmdate('D,d M Y H:i:s', $date) . ' GMT';
    $this->sendRawHeader('Expires: ' . $date, $replace);
}

/**
 * Set Last-Modified entity-header field indicates the date and time at 
 * which the origin server believes the variant was last modified.
 *
 * @param  int    Timestamp.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function lastModified($date = 0, $replace = true)
{
    if ($date <= 0) {
        $date = time();
    }
    $date = gmdate('D,d M Y H:i:s', $date) . ' GMT';
    $this->sendRawHeader('Last-Modified: ' . $date, $replace);
}

/**
 * Pragma general-header field is used to include implementation- specific
 * directives that might apply to any recipient along the request/response
 * chain.
 *
 * @param  int    Timestamp.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function pragma($type = 'no-cache', $replace = true)
{
    $this->sendRawHeader('Pragma: ' . $type, $replace);
}

/**
 * Set auto-refresh.
 *
 * @param  int    Seconds until refresh.
 * @param  string Url.
 * @return boolean Whether header is set correctly.
 */
function refresh($time = 3600, $url = false)
{
    if ($time == 0) {
        return;
    }
    $string = 'refresh: ' . $time . ';';
    if ($url !== false) {
        $string .=  ' url=' . $url;
    }
    return $this->sendRawHeader($string, true);
}

/**
 * Set location.
 *
 * @param  string Url.
 * @return bool      Whether header is set correcly.
 */
function location($url = false)
{
    if ($url !== false) {
        return $this->sendRawHeader('Location: ' . $url, true);
    }
    return false;
}

/**
 * Pragma general-header field is used to include implementation- specific
 * directives that might apply to any recipient along the request/response
 * chain.
 *
 * @param  int    Timestamp.
 * @param  bool   Whether the header should replace a previous similar 
 *                header, or add a second header of the same type.
 * @return void
 */
function code($code = 200, $replace = true)
{
    static $codestr = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',                      // WebDAV
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',                    // WebDAV
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Request Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',            // WebDAV
        423 => 'Locked',                          // WebDAV
        424 => 'Failed Dependency',               // WebDAV
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        507 => 'Insufficient Storage'             // WebDAV
    );

    $this->sendRawHeader('HTTP/1.0 ' . $code . ' ' . $codestr[$code]
        , $replace);
}

} // END class Send_HTTP_Headers

/**
 * Custom HTTP Headers Class
 *
 * @package    GoGo_Web_Application
 * @subpackage GoGo_Http
 * @version    v1.1.1, 08/15/2004 -- 09/21/2005
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 * @copyright  Copyright (c) 2004-2005. SEG Technology. All rights reserved.
 */
class GoGo_Send_HTTP_Headers extends Send_HTTP_Headers
{  // BEGIN class GoGo_Send_HTTP_Headers

// -----------------------------------------------------------------------------
//                            Content Types.
// -----------------------------------------------------------------------------

/**
 * Send correct header dependent upon file extension.
 *
 * @param  string File extension or file name.
 * @return string Xtype of header sent.
 */
function setContentType($file_extention = 'php')
{
    if (is_array($file_extention)) {
        $file_extention = $file_extention[1];
    }
    $type = $this->typeExtention($file_extention);
    $charset = '';
    switch ($type) {
    // Application.
    case 'exe':
        $xtype = 'application/x-msdownload';
        break;
    case 'pdf':
       $xtype = 'application/pdf';
       break;
    case 'rss':
       $xtype = 'application/xml';
       break;
    case 'xml':
       $xtype = 'application/xml';
       break;
    case 'zip':
        $xtype = 'application/x-zip-compressed';
        break;
    // Audio.
    case 'mp3':
        $xtype = 'audio/mpeg';
        break;
    // Image.
    case 'bmg';
        $xtype = 'image/x-xbitmap';
        break;
    case 'gif';
        $xtype = 'image/gif';
        break;
    case 'jpg';
        $xtype = 'image/jpg';
        break;
    case 'jpeg';
        $xtype = 'image/jpeg';
        break;
    case 'png';
        $xtype = 'image/png';
        break;
    // Text
    case 'css':
        $xtype = 'text/css';
        break;
    case 'txt':
        $xtype = 'text/plain';
        $charset = 'utf-8';
        break;
    default:
        $xtype = 'text/html';
        $charset = 'utf-8';
    }
     $this->contentType($xtype, $charset);
}

/**
 * Set page no cache.
 *
 * @return void
 */
function noCache()
{
    /* 
     * Date in the past
     */
    $this->expires();
    /*
     * Always modified.
     */
    $this->lastModified(); 
    /*
     * HTTP/1.1
     */
    $this->cacheControl();
    $this->cacheControl('post-check=0, pre-check=0', false);
    $this->pragma();
}

/**
 * Set normal html header.
 *
 * @return void
 */
function normalHtml()
{
    $this->expires();
    $this->cacheControl('no-store, no-cache, must-revalidate');
    $this->cacheControl('post-check=0, pre-check=0', false);
    /*
     * IE 6 Fix
     * http://www.phpfreaks.com/tutorials/41/1.php.
     */
    $this->cacheControl('private', false);
    $this->pragma('no-cache');
}

/**
 * Send gzip headers.
 *
 * @return bool    Whether header is set correcly.
 */
function gzip()
{ // BEGIN function gzip
    if ($this->contentEncoding('gzip')) {
        return $this->sendRawHeader('Vary: Accept-Encoding');
    }
    return false;
} // END function gzip

// -----------------------------------------------------------------------------
//                          Helper functions.
// -----------------------------------------------------------------------------

/**
 * Get file type extension by remove any real file extension. 
 * i.g. 'main.css.php' will return 'css'.
 *
 * @param  string File name.
 * @return string File type extension.
 */
function typeExtention($file)
{
    $file = strtolower($file);
    $pos = strrpos($file, '.');
    if ($pos !== false) {
        $type = substr($file, $pos + 1);
        if ($type == 'php' && $pos < strlen($file) - 3) {
            $file = substr($file, 0, $pos);
            return $this->typeExtention($file);
        } else {
            return $type;
        }
    } else {
        return $file;
    }
}

}  // END class GoGo_Send_HTTP_Headers


?>