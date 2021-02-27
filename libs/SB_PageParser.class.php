<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2004  Gunnar Wrobel <sitebar@gunnarwrobel.de>               *
 *  Copyright (C) 2004-2005  Ondrej Brablc <http://brablc.com/mailto?o>       *
 *                                                                            *
 *  This program is free software; you can redistribute it and/or modify      *
 *  it under the terms of the GNU General Public License as published by      *
 *  the Free Software Foundation; either version 2 of the License, or         *
 *  (at your option) any later version.                                       *
 *                                                                            *
 *  This program is distributed in the hope that it will be useful,           *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
 *  GNU General Public License for more details.                              *
 *                                                                            *
 *  You should have received a copy of the GNU General Public License         *
 *  along with this program; if not, write to the Free Software               *
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA *
 ******************************************************************************/

/**
 * Page Parser.
 *
 * Information implemented
 *      HEAD    : The HTML Header of the page
 *      CHARSET : The character set of the page
 *      TITLE   : The title of the page
 *      FAVURL  : The Url of the Favicon
 *                (Presence of this url does not denote
 *                 that there really is a favicon)
 *      FAVICON : The Favicon of the page
 *      ! If you wish to retrieve the favicon !
 *      ! from a normal webpage, do not       !
 *      ! specify 'FAVICON' directly. Instead !
 *      ! only retrieve 'FAVURL'. It will be  !
 *      ! read from the header of the webpage !
 *      ! and will automatically retrieve and !
 *      ! check the favicon.                  !
 *
 * Information not yet implemented
 *      MD5     : The MD5 hash of the page (might
 *                also use MODIFIED here, which would
 *                signify that the page was modified)
 *
 * @package    SiteBar
 * @version    $Id: SB_PageParser.class.php,v 1.4 2006/01/13 06:42:16 gogogadgetscott Exp $
 * @copyright  Copyright (c) 2005-2006. SEG Technology. All rights reserved.
 */

/*
 * HTTP style errors
 */
define ('PP_OK',                        299); // OK
define ('PP_ERR',                       400); // Common error
define ('PP_ERR__FAVICON_NOT_COMPLETE', 401); // Error
define ('PP_ERR__FAVICON_TOO_BIG',      501); // Fatal error
define ('PP_ERR__FAVICON_WRONG_SIZE',   502); // Fatal error
define ('PP_ERR__FAVICON_WRONG_FORMAT', 503); // Fatal error

/**
 * HTTP Steam
 *
 * This class is repackaged for use with phpChain.
 * It does not contain calls to SB_ErrorHandler.
 *
 * @package    SiteBar
 * @author     Scott Greenberg <me@gogogadgetscott.com>
 */
class SB_HTTPStream
{
    // Handle for the connection to the
    // page
    var $connection = null;

    /*
     * Timeout for the connection.
     * @access private
     */
    var $timeout;

    // Host
    var $host;

    // Port
    var $port;

    function SB_HTTPStream($timeout=5)
    {
        $this->timeout = $timeout;
    }

    function setAddress($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    // Connect to the server
    function connect()
    {
        if ($this->connection)
        {
            $this->close();
            $this->connection = null;
        }

        if (!isset($this->host) || !isset($this->port))
        {
            return false;
        }

        $errno = 0;
        $errstr = '';

        $this->connection = @fsockopen($this->host, $this->port,
                                      $errno, $errstr, $this->timeout);

        if (!$this->connection)
        {
            return false;
        }

        $this->setTimeOut();

        return true;
    }

    function setTimeOut($timeout=null)
    {
        socket_set_timeout($this->connection, $timeout===null?$this->timeout:$timeout);
    }

    function getMetaData()
    {
        socket_get_status($this->connection);
    }

    function put($data)
    {
        return fputs($this->connection, $data);
    }

    function get($size=4096)
    {
        $data = fgets($this->connection, $size);
        return $data;
    }

    function read($size=4096)
    {
        $data = fread($this->connection, $size);
        return $data;
    }

    function flush()
    {
        while ($this->connection && $this->getMetaData() && $this->get());
    }

    function close()
    {
        if ($this->connection)
        {
            fclose($this->connection);
        }
        $this->connection = null;
    }
}

/**
 * HTTP Steam for PHP 4.3.0
 *
 * @package    SiteBar
 */
class SB_HTTPStream430 extends SB_HTTPStream
{
    function SB_HTTPStream430($timeout=5)
    {
        $this->SB_HTTPStream($timeout);
    }

    function setTimeOut($timeout=null)
    {
        stream_set_timeout($this->connection, $timeout===null?$this->timeout:$timeout);
    }

    function getMetaData()
    {
        stream_get_meta_data($this->connection);
    }
}

/**
 * Page Parser
 *
 * @package    SiteBar
 */
class SB_PageParser
{
    // HTTP stream
    var $http = null;

    // Redirection counting
    var $redirects = 0;
    var $maxRedirects = 5;

    var $https2http = false;
    var $failedFor = array();
    var $isDead = false;

    // Max download size
    var $maxBytes;

    // The url information in different
    // formats
    var $url;
    var $parsed;
    var $base;
    var $path;

    /*
     * Timeout for the connection.
     * @access private
     */
    var $timeout;

    // The page information
    var $info = array();

    // Information error messages
    var $errorCode = array();

    var $debugInfo = '';

    function SB_PageParser($url)
    {
        $this->maxBytes = 20000;
        $this->timeout = 2;
        
        /* This is the timeout while reading or writing data
         * The function name changed in PHP 4.3
         */
        if (version_compare(phpversion(), '4.3.0', '>='))
        {
            $this->http = new SB_HTTPStream430($this->timeout);
        }
        else
        {
            $this->http = new SB_HTTPStream($this->timeout);
        }

        // Set the necessary path information
        // so that url can be accessed by various
        // functions in different formats
        // calls $this->http->setAddress()
        if (!$this->setUrlInformation($url))
        {

            return;
        }
    }

    function getInformation($info=null)
    {
        if (!$this->retrieveHTTPHeaders())
        {
            $this->isDead = true;
            return;
        }

        // We wanted just validation
        if (!$info)
        {
            return;
        }

        foreach ($info as $value)
        {
            $this->errorCode[$value] = PP_ERR;
            $this->info[$value] = null;
        }

        // Get the page head so that we can get more information
        $this->retrieveHEADTag();

        foreach ($info as $value)
        {
            $this->errorCode[$value] = 0;
            $execute = 'parseHEADTagFor' . $value;
            if (method_exists($this, $execute))
            {
                $this->$execute();
            }
        }

        // Verify icon on FAVURL
        if ( in_array('FAVURL', $info) )
        {
            // Not found in HEAD, try default location
            if (!isset($this->info['FAVURL']))
            {
                $defLoc = $this->base . '/favicon.ico';
                $this->info['FAVURL'] = $defLoc;
            }

            $this->setUrlInformation($this->info['FAVURL']);

            if (!$this->connect())
            {
                unset($this->info['FAVURL']);
                $this->errorCode['FAVURL'] = PP_ERR;
            }
            else
            {
                $ico = '';
                $this->errorCode['FAVICON'] = $this->retrieveFAVICON($ico);

                if ($this->errorCode['FAVICON']<=PP_OK)
                {
                    
                }
                else
                {
                    unset($this->info['FAVURL']);
                    $this->errorCode['FAVURL'] = PP_ERR;
                }
            }
        }
    }

    function connect()
    {
        $addr = $this->http->host.':'.$this->http->port;
        $this->debugInfo .= "-- $addr --\n";
        if (isset($this->failedFor[$addr]))
        {
            return false;
        }

        if (!$this->http->connect())
        {
            $this->failedFor[$addr]=true;
            return false;
        }
        return true;
    }

    function putCommonHeaders($close = true)
    {
        static $agent = null;
        if ($agent === null)
        {
            // $agent = "SiteBar/" . str_replace(' ', '', SB_CURRENT_RELEASE) . ' (Bookmark Server; http://sitebar.org/)';
            $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) phpChain/" . PCH_VERSION;
        }

        $this->http->put( "User-Agent: $agent\r\n");

        // We cannot use Keep-Alive if we do not want to complicate the communication a lot.
        // It is not guaranteed that the connection would be kept and we do usually only 3
        // hits to one site. Keep-Alive could speed it up, but probably not very dramatically.
        // If someone wants to go this way, then he must count with other problems. He must
        // ensure that he flushes the stream, reads only what he should (some sites do not
        // sent Content-length!).
        $this->http->put( "Connection: Close\r\n");

//         $this->http->put( "Referer: " . SB_Page::baseurl() . "/\r\n");
        if ($close)
        {
            $this->http->put( "\r\n");
        }
    }

    /* This retrieves the HTTP HEAD
     * The function is only intended to be called by the
     * constructor.
     * The function has been adapted from the PEAR HTTP module.
     */
    function retrieveHTTPHeaders($request = 'HEAD')
    {
        if (!$this->connect() )
        {
            return false;
        }
        $this->debugInfo .= $request . ' ' . $this->path . " HTTP/1.1\n";
        $this->http->put( $request . ' ' . $this->path . " HTTP/1.1\r\n");
        $this->http->put( "Host: " . $this->parsed['host'] . "\r\n");
        $this->putCommonHeaders();

        $this->info['HEADERS'] = $this->readHTTPHeaders();

        $status = $this->http->getMetaData();
        if ($status['timed_out'] && $request == 'HEAD')
        {
            // Timeout. Might signify unsupported HEAD. Try a GET.
            $this->retrieveHTTPHeaders('GET');
        }
        else if ($status['timed_out'])
        {
        }
        else if (!isset($this->info['HEADERS']['response_code']))
        {
        }
        else if ($this->info['HEADERS']['response_code'] == 405
                 && $request == 'HEAD')
        {
            $this->retrieveHTTPHeaders('GET');
        }
        else if ($this->info['HEADERS']['response_code'] > 399)
        {
        }
        else if ($this->info['HEADERS']['response_code'] > 299)
        {
            if ($this->redirects > $this->maxRedirects)
            {
            }
            else
            {
                $redirect = @parse_url($this->info['HEADERS']['location']);
                $url = $this->mergeRedirect($this->parsed, $redirect);

                $this->setUrlInformation($url);
                $this->redirects++;
                unset($this->info['HEADERS']);

                $this->retrieveHTTPHeaders();
            }
        }

        return true;
    }

    // Retrieve the HTML HEAD portion of the page
    function retrieveHEADTag()
    {
        if (!$this->connect())
        {
            return;
        }

        $this->http->put( "GET " . $this->path . " HTTP/1.0\r\n");
        $this->http->put( "Host: " . $this->parsed['host'] . "\r\n");
        $this->http->put( "Accept: text/html\r\n");
        $this->putCommonHeaders();

        $this->info['HEADERS'] = $this->readHTTPHeaders();

        $head = '';
        $found = false;

        while ($line = $this->http->get())
        {
            $this->debugInfo .= "$line";
            $head .= $line;
            $end = strpos( strtolower($head), "</head>");

            if ($end !== false)
            {
                $found = true;
                $head = substr($head, 0, $end);
                break;
            }

            $pos = strlen($head);

            // Read too far into the page without finding </head>
            if ($pos > $this->maxBytes)
            {
                $head = null;
                break;
            }
        }

        if (!$found)
        {
            $head = null;
        }

        $this->info['HEAD'] = $head;
        $this->http->close();

        return true;
    }

    function parseHEADTagForFAVURL()
    {
        if (isset($this->info['HEAD'])) {
            preg_match_all ("/<([^<]*)>/", $this->info['HEAD'], $tags);
        }
        // If there are any tags
        if (isset($tags) && count ($tags[1]) )
        {
            foreach ($tags[1] as $tag)
            {
                /* identify favicon references
                 * The common name for rel is usually "shortcut icon"
                 * but FireFox also accepts only "icon". So I shortened
                 * the match here.
                 */
                if (   preg_match('/link/i', $tag)
                    && preg_match('/rel=([\'"])[\w\s]*icon\1/i', $tag))
                {
                    if (preg_match ('/href=([\'"])(.+?)\1/i', $tag, $found))
                    {
                        $favUrl = @parse_url($found[2]);
                        $this->info['FAVURL'] = $this->mergeRedirect($this->parsed, $favUrl);
                        return;
                    }
                }
            }
        }

        $this->errorCode['FAVURL'] = PP_ERR;
    }

    function parseHEADTagForCHARSET()
    {
        //parse HTTP HEAD
        if (isset($this->info['HEADERS']['content-type']))
        {
            preg_match ('/charset=([^\s";]*)/', $this->info['HEADERS']['content-type'], $found);
            if (count($found))
            {
                $this->info['CHARSET'] = $found[1];
                return;
            }
        }
        
        if (isset($this->info['HEAD'])) {
            preg_match_all ("/(<meta.*?>)/i", $this->info['HEAD'], $tags);
        }
        // If there are any tags
        if (isset($tags) && count ($tags[1]))
        {
            foreach ($tags[1] as $tag)
            {
                // identify meta charset references
                if (preg_match('/charset=([^\s";]*)/i', $tag, $found))
                {
                    $this->info['CHARSET'] = $found[1];
                    return;
                }
            }
        }

        $this->errorCode['CHARSET'] = PP_ERR;
    }

    function parseHEADTagForTITLE()
    {
        // identify title
        if (isset($this->info['HEAD']) &&
            preg_match('#<title[^>]*>\s*(.*)\s*</title>#i', $this->info['HEAD'],
            $found)) {

            $this->info['TITLE'] = $found[1];
            return;
        }

        $this->errorCode['TITLE'] = PP_ERR;
    }

    function retrieveFAVICON(&$ico)
    {
        
        if (!$this->connect())
        {
            return PP_ERR;
        }

        $this->http->put( "GET " . $this->path . " HTTP/1.0\r\n");
        $this->http->put( "Host: " . $this->parsed['host'] . "\r\n");
        $this->http->put( "Accept: image/*\r\n"); /* PSPad Hack */
        $this->putCommonHeaders();

        $head = $this->readHTTPHeaders();

        if (!isset($head['response_code']) || $head['response_code'] > 299)
        {
            $this->http->close();
            return PP_ERR;
        }

        $ico = '';
        $data = '';

        $this->http->close();

        return PP_OK;
    }

    // Parses the HTTP HEAD on the given connection
    function readHTTPHeaders()
    {
        $head = array();

        $response = rtrim($this->http->get());

        if (preg_match("|^HTTP/[^\s]*\s(\d*)|", $response, $status))  /* PSPad Hack */
        {
            $head['response_code'] = $status[1];
        }
        $head['response'] = $response;
        
        $this->debugInfo .= $head['response'] . "\n";
        while ($line = $this->http->get())
        {
            $this->debugInfo .= "$line";
            if (!trim($line))
            {
                break;
            }
            if (($pos = strpos($line, ':')) !== false)
            {
                // HTTP Headers are case insensitive
                /*
                 * HTTP/1.0 - HTTP/1.1
                 * 4.2  Message Headers
                 *
                 *  "Each header field consists
                 *  of a name followed immediately by a colon (":"), a single
                 *  space (SP) character, and the field value.
                 *  Field names are case-insensitive."
                 *
                 * Example:
                 *  Location: http://en.wikipedia.org/wiki/Main_Page
                 *
                 * UPDATED: SG 01/12/2006
                 */
                $header = strtolower(substr($line, 0, $pos));
                // $value  = strtolower(trim(substr($line, $pos + 1)));
                $value  = trim(substr($line, $pos + 1));

                $head[$header] = $value;
            }
        }

        return $head;
    }

    // Parses the url
    function setUrlInformation($url)
    {
        // This may fail but it is not important here
        // Only the connection counts
        $this->parsed = @parse_url($url);

        if (!isset($this->parsed['host']))
        {
            return FALSE;
        }

        if (!isset($this->parsed['port']))
        {
            $this->parsed['port'] = 80;
        }

        if (!isset($this->parsed['path']))
        {
            $this->parsed['path'] = '/';
        }

        if (!isset($this->parsed['scheme']))
        {
            $this->parsed['scheme'] = 'http';
        }

        if ($this->parsed['scheme'] == 'https' && !$this->https2http)
        {
            $this->parsed['scheme'] = 'http';
            $url = str_replace('https://','http://', $url);
            $this->https2http = true;
        }

        if ($this->parsed['scheme'] != 'http')
        {
            return false;
        }

        if (array_key_exists ('query', $this->parsed))
        {
            $this->path = $this->parsed['path'] . '?' . $this->parsed['query'];
        }
        else
        {
            $this->path = $this->parsed['path'];
        }

        $this->base = $this->getUrlBase($this->parsed);

        $this->http->setAddress($this->parsed['host'],$this->parsed['port']);

        $this->url = $url;
        return true;
    }

    //
    function getUrlBase($parsed)
    {
        $uri = '';
        if (array_key_exists ('scheme', $parsed))
        {
            $uri .=   $parsed['scheme']
                    ? $parsed['scheme'] . ':' . ((strtolower($parsed['scheme']) == 'mailto')
                      ? ''
                      : '//')
                    : '';
        }
        if (array_key_exists ('user', $parsed))
        {
            $uri .=   $parsed['user']
                    ? $parsed['user'] . ($parsed['pass']
                      ? ':' . $parsed['pass']
                      : '') . '@'
                    : '';
        }
        if (array_key_exists ('host', $parsed))
        {
            $uri .=   $parsed['host']
                    ? $parsed['host']
                    : '';
        }
        if (array_key_exists ('port', $parsed))
        {
            if ($parsed['scheme']!='http' || $parsed['port']!=80)
            {
                $uri .=   $parsed['port']
                        ? ':' . $parsed['port']
                        : '';
            }
        }
        return $uri;
    }

    // merge two parsed urls to one new, redirected url string
    function mergeRedirect($base, $redirect)
    {
        $oldpath = '';

        if (isset($base['path']))
        {
            if ($base['path']{strlen($base['path'])-1} == '/')
            {
                $oldpath = $base['path'];
            }
            else
            {
                // For servers hosted on Windows
                $oldpath = str_replace('\\','/',dirname($base['path']));
            }
        }

        $new = array_merge($base, $redirect);

        if (isset($new['path']) && strlen($new['path']))
        {
            if ($new['path']{0} == '/')
            {
                return $this->getUrlBase($new) . $new['path'];
            }
            else
            {
                if( substr($oldpath, -1) == '/')
                {
                    return $this->getUrlBase($new) . $oldpath . $new['path'];
                }
                else
                {
                    return $this->getUrlBase($new) . $oldpath . '/' . $new['path'];
                }
            }
        }
        else
        {
            return $this->getUrlBase($new);
        }
    }
 

    // Check the binary format of a file for BMP or ICO format.
    function faviconCheck ($ico)
    {
        return true;
    }

}
?>
