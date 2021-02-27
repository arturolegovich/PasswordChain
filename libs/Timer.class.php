<?php
//
// +------------------------------------------------------------------------+
// | PEAR :: Benchmark                                                      |
// +------------------------------------------------------------------------+
// | Copyright (c) 2001-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>. |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//

define('CONV_MS_TS', 100);
define('CONV_MS_S' , CONV_MS_TS * 10);
define('CONV_MS_M' , CONV_MS_S  * 60);
define('CONV_MS_H' , CONV_MS_M  * 60);

define('CONV_S_TS', 1 / 10);
define('CONV_S_S' , 1);
define('CONV_S_M' , CONV_S_S  * 60);
define('CONV_S_H' , CONV_S_M  * 60);

/**
 * Provides timing and profiling information.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @author    Ludovico Magnocavallo <ludo@sumatrasolutions.com>
 * @author    Scott Greenberg <me@gogogadgetscott.com>
 * @copyright Copyright &copy; 2002-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.php.net/license/3_0.txt The PHP License, Version 3.0
 * @category  Benchmarking
 * @package   Benchmark
 * @version   $Id: Timer.class.php,v 1.8 2006/01/10 08:24:45 gogogadgetscott Exp $
 */
class Benchmark_Timer
{ // BEGIN class Benchmark_Timer

/**
 * Contains the markers.
 *
 * @var    array
 * @access private
 */
var $markers = array();

/**
 * Auto-start and stop timer.
 *
 * @var    boolean
 * @access private
 */
var $auto = false;

/**
 * Max marker name length for non-html output.
 *
 * @var    integer
 * @access private
 */
var $maxStringLength = 0;

/**
 * Constructor.
 *
 * @param  boolean $auto
 * @access public
 */
function Benchmark_Timer($auto = false)
{
    $this->auto = $auto;

    if ($this->auto) {
        $this->start();
    }

}

/**
 * Destructor.
 *
 * @access private
 */
function _Benchmark_Timer()
{
    if ($this->auto) {
        $this->stop();
        $this->display();
    }
}

/**
 * Set "Start" marker.
 *
 * @see    setMarker(), stop()
 * @access public
 */
function start()
{
    $this->setMarker('Start');
}

/**
 * Set "Stop" marker.
 *
 * @see    setMarker(), start()
 * @access public
 */
function stop()
{
    $this->setMarker('Stop');
}

/**
 * Set marker.
 *
 * @param  string  $name Name of the marker to be set.
 * @see    start(), stop()
 * @access public
 */
function setMarker($name)
{
    $i = 1;
    while (empty($name) || array_key_exists($name, $this->markers)) {
         $name .= $i;
         $i++;
     }
    $this->markers[$name] = $this->_getMicrotime();
}

/**
 * Returns the time elapsed betweens two markers.
 *
 * @param  string  $start        start marker, defaults to "Start"
 * @param  string  $end          end marker, defaults to "Stop"
 * @return double  $time_elapsed time elapsed between $start and $end
 * @access public
 */
function timeElapsed($start = 'Start', $end = 'Stop')
{
    if ($end == 'Stop' && !isset($this->markers['Stop'])) {
        $this->markers['Stop'] = $this->_getMicrotime();
    }

    if (extension_loaded('bcmath')) {
        return bcsub($this->markers[$end], $this->markers[$start], 6);
    } else {
        return $this->markers[$end] - $this->markers[$start];
    }
}

/**
 * Return duration in determined time unit.
 *
 * @param integer Start time (seconds).
 * @param integer End time (seconds).
 * @param string Input time units.
 * @return string
 * @access public
 */
function getDuration($start, $end, $inputUnits = CONV_S_S)
{ // BEGIN function getDuration

    $h  = 0;
    $m  = 0;
    $s  = 0;
    $ts = 0;
    $diff = $end - $start;
    $diff *= CONV_S_S;
    if (($diff / CONV_S_H) >= 1) {
        $h = floor($diff / CONV_S_H);
        $diff -= ($h * CONV_S_H);
        return "$h hours";
    }
    if (($diff / CONV_S_M) >= 1) {
        $m = floor($diff / CONV_S_M);
        $diff -= ($m * CONV_S_M);
        return "$m minutes";
    }
    if (($diff / CONV_S_S) >= 1) {
        $s = floor($diff / CONV_S_S);
        $diff -= ($s * CONV_S_S);
        return "$s seconds";
    }
    if (($diff / CONV_S_TS) >= 1) {
        $ts = floor($diff / CONV_S_TS);
        $diff -= ($ts * CONV_S_TS);
        return "$ts tera-seconds";
    }
    return "N/A";
} // END function getDuration

/**
 * Returns profiling information.
 *
 * $profiling[x]['name']  = name of marker x
 * $profiling[x]['time']  = time index of marker x
 * $profiling[x]['diff']  = execution time from marker x-1 to this marker x
 * $profiling[x]['total'] = total execution time up to marker x
 * $profiling[x]['per']   = percent of total
 *
 * @param  int   Time unit convertor. 1=s, 1000=ms, 1000000=microsecond 
 * @return array
 * @access public
 */
function getProfiling($time_unit = 1)
{
    $i = $total = $temp = 0;
    $result   = array();
    $maxtotal = $this->TimeElapsed();
    $precision = 6 - round(log10($time_unit));
    foreach ($this->markers as $marker => $time) {
        if (extension_loaded('bcmath')) {
            $diff  = bcsub($time, $temp, 6);
            $total = bcadd($total, $diff, 6);
        } else {
            $diff  = $time - $temp;
            $total = $total + $diff;
        }

        $result[$i]['name']  = $marker;
        $result[$i]['time']  = $time;
        $result[$i]['diff']  = number_format($diff * $time_unit, $precision);
        $result[$i]['total'] = $total;
        $perc = $diff * 100 / $maxtotal;
        $result[$i]['per']   = number_format($perc, 2, '.', '');

        $this->maxStringLength = (strlen($marker) > $this->maxStringLength ? strlen($marker) + 1 : $this->maxStringLength);

        $temp = $time;
        $i++;
    }
    $result[$i]['name']  = 'Total';
    $result[$i]['time']  = '';
    $result[$i]['diff']  = number_format($maxtotal * $time_unit, $precision);
    $result[$i]['total'] = $maxtotal;
    $result[$i]['per']   = '100.00';
    
    $result[0]['diff'] = number_format(0, $precision);
    $result[0]['per']  = '0.00';
    $this->maxStringLength = (strlen('total') > $this->maxStringLength ? strlen('total') : $this->maxStringLength);
    $this->maxStringLength += 4;

    return $result;
}

/**
 * Return formatted profiling information.
 *
 * @return string
 * @see    getProfiling()
 * @access public
 */
function getOutput()
{
    if (function_exists('version_compare') &&
        version_compare(phpversion(), '4.1', 'ge'))
    {
        $http = isset($_SERVER['SERVER_PROTOCOL']);
    } else {
        global $HTTP_SERVER_VARS;
        $http = isset($HTTP_SERVER_VARS['SERVER_PROTOCOL']);
    }
    $total  = $this->TimeElapsed();
    $result = $this->getProfiling();
    $dashes = '';
    if ($http) {
        $out = '<table border="1">' . "\n"
         . '<tr><td>Marker</td><td align="center"><b>Time Index</b></td>'
         . '<td align="center"><b>Execution time</b></td><td align="center">'
         . '<b>Percent Total (%)</b></td></tr>' . "\n";
    } else {
        $dashes = $out = str_pad("\n", ($this->maxStringLength + 52), '-', STR_PAD_LEFT);
        $out .= str_pad('Marker', $this->maxStringLength)
            . str_pad("Time Index", 22)
            . str_pad("Execution time", 22)
            . "Percent Total (%)\n"
            . $dashes;
    }
    foreach ($result as $k => $v) {
    	$perc = (($v['diff'] * 100) / $total);
        if ($http) {
        	$class = (fmod($k, 2) == 0) ? 'even' : 'odd';
            $out .= "<tr class=\"$class\"><td><b>" . $v['name']
				. "</b></td><td>" . $v['time'] . '</td><td>' . $v['diff']
				. "</td><td align=\"right\">" . number_format($perc, 2, '.', '')
				. "%</td></tr>\n";
        } else {
            $out .= str_pad($v['name'], $this->maxStringLength, ' ')
            	. str_pad($v['time'], 22)
				. str_pad($v['diff'], 22)
            	. str_pad($perc . "%\n", 8, ' ', STR_PAD_LEFT);
        }

        $out .= $dashes;
    }
    $out .= '</table>' . "\n";
    return $out;
}

/**
 * Prints the information returned by getOutput().
 *
 * @see    getOutput()
 * @access public
 */
function display()
{
    echo $this->getOutput();
}

/**
 * Wrapper for microtime().
 *
 * @return float
 * @access private
 * @since  1.3.0
 */
function _getMicrotime()
{
    $microtime = explode(' ', microtime());
    return $microtime[1] . substr($microtime[0], 1);
}

} // END class Benchmark_Timer

?>