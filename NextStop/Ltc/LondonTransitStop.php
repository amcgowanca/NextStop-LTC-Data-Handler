<?php
/**
 * LondonTransitStop.php
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
 * LondonTransitStop
 *
 * @package: nextstop
 * @subpackage: nextstop.ltc
 */
class LondonTransitStop {
    /* Member variables */
    const TYPE_UNKNOWN = 0;
    const TYPE_MAJOR = 1;
    const TYPE_MINOR = 2;
    
    protected $mStopType;
    
    protected $mLatitude;
    protected $mLongitude;
    
    protected $mStopNumber;
    protected $mStopName;
    protected $mDirection;
    
    protected $mTimes;
    protected $mTimesCount;
    
    /**
     * __construct
     *
     * Ctor.
     * 
     * @access: public
     * @param: int      Contains the stop type. Use LondonTransitStop::TYPE_UNKNOWN, LondonTransitStop::TYPE_MAJOR or LondonTransitStop::TYPE_MINOR
     * @param: float    Contains the stop position's latitude.
     * @param: float    Contains the stop position's longitude.
     * @param: int      Contains the stop number or stop id.
     * @param: string   Contains the name of the stop.
     * @param: string   Contains a string indicating the direction of the stop.
     * @param: array    Contains an array of next arrival times.
     * @return:
     */
    public function __construct($type = 0, $lat = 0, $lng = 0, $stopNum = 0, $stopName = '', $dir = '', array $times = null) {
        $this->mType = (int) $type;
        
        $this->mLatitude = (float) $lat;
        $this->mLongitude = (float) $lng;
        
        $this->mStopNumber = (int) $stopNum;
        $this->mStopName = trim($stopName);
        $this->mDirection = trim(strtoupper($dir));
        
        $this->mTimes = $times;
        $this->mTimesCount = count($this->mTimes);
    }
    
    /**
     * GetLatitude
     *
     * Accessor method for this stop's position's latitude.
     * 
     * @access: public
     * @param: void
     * @return: float
     */
    public function GetLatitude() {
        return $this->mLatitude;
    }
    
    /**
     * SetLatitude
     *
     * Mutator method for this stop's position's latitude.
     * 
     * @access: public
     * @param: float
     * @return: void
     */
    public function SetLatitude($lat) {
        $this->mLatitude = (float) $lat;
    }
    
    /**
     * GetLongitude
     *
     * Accessor method for this stop's position's longitude.
     * 
     * @access: public
     * @param: void
     * @return: float
     */
    public function GetLongitude() {
        return $this->mLongitude;
    }
    
    /**
     * SetLatitude
     *
     * Mutator method for this stop's position's longitude.
     * 
     * @access: public
     * @param: float
     * @return: void
     */
    public function SetLongitude($lng) {
        $this->mLongitude = (float) $lng;
    }
    
    /**
     * GetNumber
     *
     * Accessor method for this stop's number and or 'id'.
     * 
     * @access: public
     * @param: void
     * @return: int
     */
    public function GetNumber() {
        return $this->mStopNumber;
    }
    
    /**
     * SetNumber
     *
     * Mutator method for this stop's number and or 'id'.
     * 
     * @access: public
     * @param: int
     * @return: void
     */
    public function SetNumber($n) {
        $this->mStopNumber = (int) $n;
    }
    
    /**
     * GetName
     *
     * Accessor method for this stop's name.
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function GetName() {
        return $this->mStopName;
    }
    
    /**
     * SetName
     *
     * Mutator method for this stop's name.
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetName($name) {
        $this->mStopName = trim($name);
    }
    
    /**
     * GetDirection
     *
     * Accessor method for the direction of this stop.
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function GetDirection() {
        return $this->mDirection;
    }
    
    /**
     * SetDirection
     *
     * Mutator method for the direction of this stop.
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetDirection($dir) {
        $this->mDirection = strtoupper(trim($dir));
    }
    
    /**
     * GetTimes
     *
     * Accessor method for the next arrival times. Returns an array of LondonTransitTime objects.
     * 
     * @access: public
     * @param: void
     * @return: array   Returned by reference.
     */
    public function &GetTimes() {
        return $this->mTimes;
    }
    
    /**
     * SetTimes
     *
     * Mutator method for the array of next arrival times.
     * 
     * @access: public
     * @param: array
     * @return: void
     */
    public function SetTimes(array $times) {
        $this->mTimes = $times;
        $this->mTimesCount = count($times);
    }
    
    /**
     * GetTimesCount
     *
     * Accessor method for the number of next arrival times for this stop.
     * 
     * @access: public
     * @param: void
     * @return: int
     */
    public function GetTimesCount() {
        return $this->mTimesCount;
    }
    
    /**
     * GetType
     *
     * Accessor method for this stop's type property.
     * 
     * @access: public
     * @param: void
     * @return: int
     */
    public function GetType() {
        return $this->mType;
    }
    
    /**
     * SetType
     *
     * Mutator method for this stop's type property.
     * 
     * @access: public
     * @param: int
     * @return: void
     */
    public function SetType($type) {
        $this->mType = $type;
    }
}