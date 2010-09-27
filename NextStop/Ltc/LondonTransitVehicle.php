<?php
/**
 * LondonTransitVehicel.php
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
 * LondonTransitVehicle
 *
 * @package nextstop
 * @subpackage nextstop.ltc
 */
class LondonTransitVehicle {
    /* Member variables */
    protected $mVehicleNumber;
    protected $mLatitude;
    protected $mLongitude;
    protected $mDirection;
    
    /**
     * __construct
     *
     * Ctor.
     * 
     * @access: public
     * @param: int          Contains the vehicle number.
     * @param: float        Contains the position's latitude value.
     * @param: float        Contains the position's longitude value.
     * @param: string       Contains the direction of this vehicle.
     * @return: void
     */
    public function __construct($vNumber = 0, $lat = 0, $lng = 0, $direction = 'Unknown') {
        $this->mVehicleNumber = (int) $vNumber;
        $this->mLatitude = (float) $lat;
        $this->mLongitude = (float) $lng;
        $this->mDirection = ucwords(trim($direction));
    }
    
    /**
     * GetNumber
     *
     * Accessor method for the vehicle number.
     * 
     * @access: public
     * @param: void
     * @return: int
     */
    public function GetNumber() {
        return $this->mVehicleNumber;
    }
    
    /**
     * SetNumber
     *
     * Mutator method for the vehicle number.
     * 
     * @access: public
     * @param: int
     * @return: void
     */
    public function SetNumber($vNumber) {
        $this->mVehicleNumber = (int) $vNumber;
    }
    
    /**
     * GetLatitude
     *
     * Accessor method for this vehicle's position's latitude.
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
     * Mutator method for this vehicle's position's latitude.
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
     * Accessor method for this vehicle's position's longitude.
     * 
     * @access: public
     * @param: void
     * @return: float
     */
    public function GetLongitude() {
        return $this->mLongitude;
    }
    
    /**
     * SetLongitude
     *
     * Mutator method for this vehicle's position's longitude.
     * 
     * @access: public
     * @param: float
     * @return: void
     */
    public function SetLongitude($lng) {
        $this->mLongitude = (float) $lng;
    }
    
    /**
     * GetDirection
     *
     * Accessor method for the direction property.
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
     * Mutator method for setting the direction property of this vehicle object.
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetDirection($dir) {
        $this->mDirection = $dir;
    }
}