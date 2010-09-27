<?php
/**
 * LondonTransit.php
 *
 * NextStop - London Transit Guide
 * Copyright 2010, Aaron McGowan ( www.amcgowan.ca )
 *
 * @copyright     Copyright 2010, Aaron McGowan (www.amcgowan.ca)
 * @link          http://www.amcgowan.ca/
 * @license       GNU General Public License, http://creativecommons.org/licenses/GPL/2.0/
 */

/**
* @ignore
*/
defined('IN_NEXTSTOP') OR exit;

/**
* EXT
*
* Contains the .php file extension.
*/
defined('EXT') OR define('EXT', '.php');

/**
* _LONDONTRANSIT_PATH
*
* Contains 'this' absolute.
*/
define('_LONDONTRANSIT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');

/* Include/Require additional library package files. */
require_once _LONDONTRANSIT_PATH . 'LondonTransitTime' . EXT;
require_once _LONDONTRANSIT_PATH . 'LondonTransitStop' . EXT;
require_once _LONDONTRANSIT_PATH . 'LondonTransitVehicle' . EXT;

/**
* LondonTransit
*/
class LondonTransit {
    /**
    * CalculateDistance
    *
    * Calculates the distance between two specified co-oridinates (lat,lng) in kilometers.
    * 
    * @access: public static
    * @param:  float        Contains the latitude (in degrees) for the first co-ordinate.
    * @param:  float        Contains the longitude (in degrees) for the first co-ordinate.
    * @param:  float        Contains the latitude (in degrees) for the second co-ordinate.
    * @param:  float        Contains the longitude (in degrees) for the second co-ordinate.
    * @return: float        Returns the distance between first and second co-ordinate in kilometers.
    */
    public static function CalculateDistance($lat1, $lng1, $lat2, $lng2) {
        $pi80 = M_PI / 180;
        
        $lat1 *= $pi80;     $lng1 *= $pi80;
        $lat2 *= $pi80;     $lng2 *= $pi80;
    
        $r = 6372.797;
        
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        $dist = $r * $c;
        return $dist;
    }
    
    /* Member variables */
    const URL_WEBWATCH_DATA = 'http://www.ltconline.ca/webwatch/UpdateWebMap.aspx';
    
    const WEBWATCHDATA_STOPS_MAJOR  = 1;
    const WEBWATCHDATA_STOPS_MINOR  = 3;    
    const WEBWATCHDATA_VEHICLES     = 2;
    
    protected static $mRouteIdentifiers = array();
    protected $mRouteData = array();
    
    /**
    * __construct
    * 
    * @access:  public
    * @param:   void
    * @return:  void
    */
    public function __construct() {
        if( file_exists(_LONDONTRANSIT_PATH . 'data/routes' . EXT) ) {
            $routes = @include _LONDONTRANSIT_PATH . 'data/routes' . EXT;
            if( is_array($routes) ) {
                self::$mRouteIdentifiers = $routes;
            }
        }
    }
    
    /**
    * GetRouteIdentifiers
    * 
    * @access:  public
    * @param:   void
    * @return:  array
    */
    public function &GetRouteIdentifiers() {
        return self::$mRouteIdentifiers;
    }
    
    /**
    * IsRouteNumberValid
    * 
    * @access:  public
    * @param:   int
    * @return:  bool
    */
    public function IsRouteNumberValid($n) {
        return isset(self::$mRouteIdentifiers[$n]);
    }
    
    /**
    * IsRouteAccessible
    * 
    * Tests if a specified route by route number is accessible.
    * 
    * @access: public
    * @param:  int          Contains the route number to test if that route is accessible.
    * @return: bool         Returns true if route is accessible, else returns false.
    */
    public function IsRouteAccessible($n) {
        return in_array($n, self::$mAccessibleRouteIdentifiers);
    }
    
    /**
    * GetClosestStopFromPosForRoute
    *
    * Retrieves the closest stop for a specified route from specified position (lat, lng).
    * 
    * @access: public
    * @param:  int          Contains the route identifier (route number).
    * @param:  float        Contains the latitude value (in degrees) to find the closest from.
    * @param:  float        Contains the longitude value (in degrees) to find the closest from.
    * @return: mixed        Returns an instance of LondonTransitStop of the closest stop on specified else returns false if not found or an error has occurred.
    */
    public function GetClosestStopFromPosForRoute($routeId, $lat, $lng) {
        $stops = $this->GetStopLocations($routeId);
        if( false !== $stops ) {
            $stopIndex = null;
            $shortestDist = 999999;
            foreach( $stops as $i => &$stop ) {
                $d = self::CalculateDistance($lat, $lng, $stop->GetLatitude(), $stop->GetLongitude());
                if( $d < $shortestDist ) {
                    $shortestDist = $d;
                    $stopIndex = $i;
                }
            }
            unset($stop);
            
            if( null !== $stopIndex ) {
                return isset($stops[$stopIndex]) ? $stops[$stopIndex] : false;
            }
        }
        
        return false;
    }
    
    /**
    * GetStopLocations
    *
    * Retrieves all the stop locations (major and minor) from the London Transit Commission's data source.
    * 
    * @access: public
    * @param:  int          Contains the route identifier (route number) in which to retrieve the stops for.
    * @return: mixed        Returns an array of LondonTransitStop objects if data is available, else returns false on failure.
    */
    public function GetStopLocations($routeId, $type = null) {
        $routeId = (int) $routeId;
        $data = $this->RetrieveWebwatchData($routeId);
        
        if( is_array($data) ) {
            $stops = array();
            switch( $type ) {
                case LondonTransitStop::TYPE_MAJOR:
                    if( isset($data[self::WEBWATCHDATA_STOPS_MAJOR]) ) {
                        $this->ParseStopData($data[self::WEBWATCHDATA_STOPS_MAJOR], $stops, LondonTransitStop::TYPE_MAJOR);
                    }
                    break;
                
                case LondonTransitStop::TYPE_MINOR:
                    if( isset($data[self::WEBWATCHDATA_STOPS_MINOR]) ) {
                        $this->ParseStopData($data[self::WEBWATCHDATA_STOPS_MINOR], $stops, LondonTransitStop::TYPE_MINOR);
                    }
                    break;
                
                default:
                    if( isset($data[self::WEBWATCHDATA_STOPS_MAJOR]) ) {
                        $this->ParseStopData($data[self::WEBWATCHDATA_STOPS_MAJOR], $stops, LondonTransitStop::TYPE_MAJOR);
                    }
                    
                    if( isset($data[self::WEBWATCHDATA_STOPS_MINOR]) ) {
                        $this->ParseStopData($data[self::WEBWATCHDATA_STOPS_MINOR], $stops, LondonTransitStop::TYPE_MINOR);
                    }
                    break;
            }
            
            return $stops;
        }
        
        return false;
    }
    
    /**
    * GetVehicleLocations
    *
    * Retrieves all the vehicle locations from the London Transit Commission's data source.
    * 
    * @access: public
    * @param:  int          Contains the route identifier (route number) in which to retrieve vehicle locations for.
    * @return: mixed        Returns an array of LondonTransitVehicle objects if data is available, else returns false on failure.
    */
    public function GetVehicleLocations($routeId) {
        $routeId = (int) $routeId;
        $data = $this->RetrieveWebwatchData($routeId);
        
        if( is_array($data) && isset($data[self::WEBWATCHDATA_VEHICLES]) ) {
            $vehicles = array();
            $data = explode(';', $data[self::WEBWATCHDATA_VEHICLES]);
            
            foreach( $data as $k => &$val ) {
                if( !$val ) {
                    continue;
                }
                
                $pieces = explode('|', $val);
                $pieces[3] = str_replace(array('<br>'), "\n", $pieces[3]);
                $pieces[3] = explode("\n", $pieces[3]);
                $pieces[3] = array_map('strip_tags', $pieces[3]);
                
                if( preg_match('([0-9]+)', $pieces[3][1], $matches) ) {
                    $pieces[3][1] = $matches[0];
                } else {
                    $pieces[3][1] = 0;
                }
                
                $vehicles[] = new LondonTransitVehicle($pieces[3][1], $pieces[0], $pieces[1], $pieces[3][0]);
            }
            
            return $vehicles;
        }
        
        return false;
    }
    
    /**
    * IsDataAvailable
    *
    * Method used for testing if the London Transit Commission's data source is available.
    * This was added due to a couple of reasons:
    *   1. Their unreliability of assuring that their data is available.
    *   2. So that I was not to blame if their data was unavailable.
    * 
    * @access: public
    * @param:  void
    * @return: bool         Returns TRUE if data is available, else returns FALSE.
    */
    public function IsDataAvailable() {
        $curl = curl_init(self::URL_WEBWATCH_DATA);
        if( $curl ) {
            curl_setopt_array($curl, array(
                CURLOPT_MAXREDIRS => 0,
                CURLOPT_NOBODY => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_RETURNTRANSFER => true,
                
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_HEADER => true
            ));
            
            $headers = curl_exec($curl);
            curl_close($curl);
            
            $headers = explode("\n", $headers);
            
            $rtn = false;
            if( preg_match('(HTTP/([0-2].[0-2]) ([0-9]{3}))', $headers[0], $matches) ) {
                if( isset($matches[2]) && 200 == $matches[2] ) {
                    $rtn = true;
                }
            }
            
            return (bool) $rtn;
        }
        
        return false;
    }
    
    /**
    * RetrieveWebwatchData
    *
    * Used for retrieving data from the London Transit Commission's WebWatch data source.
    * 
    * @access: protected
    * @param:  int              Contains the route identifier (route number) in which to retrieve data for.
    * @param:  bool             Contains a boolean value used to determine if data should be re-retrieved
    * @return: mixed            Returns the data retrieved else false on failure.
    */
    protected function RetrieveWebwatchData($routeId, $forceRedownload = false) {
        if( !isset(self::$mRouteIdentifiers[$routeId]) ) {
            return false;
        }
        
        if( !isset($this->mRouteData[$routeId]) || $forceRedownload ) {
            $data = @file_get_contents(self::URL_WEBWATCH_DATA . '?' . http_build_query(array('u' => $routeId, 'timestamp' => time())));
            if( $data ) {
                $this->mRouteData[$routeId] = explode('*', $data);
            }
        }
        
        return $this->mRouteData[$routeId];
    }
    
    /**
    * ParseStopData
    *
    * Parses data retrieved from London Transit Commissions's data source (or specified data) retrieving only the stop
    * information for the specified stop.
    * 
    * @access: protected
    * @param:  string           Contains data to retrieve and parse stop information out of. Passed by reference.
    * @param:  array            Contains an array in which to push new objects onto for each stop parsed out of data.
    * @param:  int              Contains the stop type which is being parsed.
    */
    protected function ParseStopData(&$data, array &$stops, $stopType = LondonTransitStop::TYPE_UNKNOWN) {
        $data = explode(';', $data);
        
        foreach( $data as $k => &$val ) {
            if( !$val ) {
                continue;
            }
            
            $pieces = explode('|', $val);
            $pieces[2] = strtoupper($pieces[2]);
            $pieces[3] = strtoupper($pieces[3]);
            
            $stopNumData = array();
            if( preg_match('([0-9]+)', $pieces[4], $stopNumData) ) {
                $pieces[4] = $stopNumData[0];
            } else {
                $pieces[4] = 0;
            }
            
            $pieces[5] = str_replace(array('<br>'), "\n", $pieces[5]);
            $pieces[5] = explode("\n", $pieces[5]);
            $pieces[5] = array_map('strip_tags', $pieces[5]);
            
            $times = array();
            foreach( $pieces[5] as &$time ) {
                if( !$time ) {
                    continue;
                }
                
                $tData = array();
                if( preg_match('((([0-9]{1,2}):([0-9]{1,2})) (AM|PM) TO ([0-9a-zA-Z \',"]+))', $time, $tData) ) {
                    $times[] = new LondonTransitTime($tData[1], $tData[4], $tData[5]);
                }
            }
            $pieces[5] = &$times;
            
            $stops[] = new LondonTransitStop($stopType, $pieces[0], $pieces[1], $pieces[4], $pieces[2], $pieces[3], $pieces[5]);
        }
    }
}