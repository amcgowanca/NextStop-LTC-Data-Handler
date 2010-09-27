<?php
/**
 * LondonTransitTime.php
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
 * LondonTransitTime
 *
 * @package nextstop
 * @subpackage nextstop.ltc
 */
class LondonTransitTime {
    /* Member variables */
    const ANTE_MERIDIEM = 'am';
    const POST_MERIDIEM = 'pm';
    
    protected $mTime;
    protected $mMeridiem;
    protected $mName;
    
    /**
     * __construct
     *
     * Ctor.
     * 
     * @access: public
     * @param: string       Contains the time as a string format.
     * @param: string       Contains the meridiem - am or pm. Try and use LondonTransitTime::ANTE_MERIDIEM or LondonTransitTime::POST_MERIDIEM
     * @return: void
     */
    public function __construct($time = '', $meridiem = '', $name = '') {
        $this->mTime = trim($time);
        $this->mMeridiem = trim($meridiem);
        $this->mName = trim($name);
    }
    
    /**
     * GetTime
     *
     * Accessor method for the time as a string.
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function GetTime() {
        return $this->mTime;
    }
    
    /**
     * SetTime
     *
     * Mutator method for the time as a string.
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetTime($val) {
        $this->mTime = $val;
    }
    
    /**
     * GetMeridiem
     *
     * Accessor method for this time's meridiem... am OR pm?
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function GetMeridiem() {
        return $this->mMeridiem;
    }
    
    /**
     * SetMeridiem
     *
     * Mutator method for this time's meridiem...
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetMeridiem($val) {
        $this->mMeridiem = $val;
    }
    
    /**
     * GetName
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function GetName() {
        return $this->mName;
    }
    
    /**
     * SetName
     * 
     * @access: public
     * @param: string
     * @return: void
     */
    public function SetName($val) {
        $this->mName = $val;
    }
    
    /**
     * __toString
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function __toString() {
        return $this->ToString();
    }
    
    /**
     * ToString
     *
     * Creates a string representation of this object - time meridiem
     * 
     * @access: public
     * @param: void
     * @return: string
     */
    public function ToString() {
        return $this->mTime . ' ' . $this->mMeridiem;
    }
}