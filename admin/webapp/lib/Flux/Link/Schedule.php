<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

/**
 * Stores scheduling information
 * @author Mark Hobson
 */
class Schedule extends CommonForm {

	protected $days;
	protected $start_hour;
	protected $end_hour;
	protected $all_day;
	
	/**
	 * Returns the days
	 * @return array
	 */
	function getDays() {
		if (is_null($this->days)) {
			$this->days = array();
		}
		return $this->days;
	}
	
	/**
	 * Sets the days
	 * @var array
	 */
	function setDays($arg0) {
		if (is_array($arg0)) {
			$this->days = $arg0;
		}
		return $this;
	}
	
	/**
	 * Returns the start_hour
	 * @return integer
	 */
	function getStartHour() {
		if (is_null($this->start_hour)) {
			$this->start_hour = 0;
		}
		return $this->start_hour;
	}
	
	/**
	 * Sets the start_hour
	 * @var integer
	 */
	function setStartHour($arg0) {
		$this->start_hour = $arg0;
		return $this;
	}
	
	/**
	 * Returns the end_hour
	 * @return integer
	 */
	function getEndHour() {
		if (is_null($this->end_hour)) {
			$this->end_hour = 23;
		}
		return $this->end_hour;
	}
	
	/**
	 * Sets the end_hour
	 * @var integer
	 */
	function setEndHour($arg0) {
		$this->end_hour = $arg0;
		return $this;
	}
	
	/**
	 * Returns the all_day
	 * @return boolean
	 */
	function getAllDay() {
		if (is_null($this->all_day)) {
			$this->all_day = false;
		}
		return $this->all_day;
	}
	
	/**
	 * Sets the all_day
	 * @var boolean
	 */
	function setAllDay($arg0) {
		$this->all_day = $arg0;
		return $this;
	}
	
	/**
	 * Returns true if the current time falls within the schedule
	 * @return boolean
	 */
	function isValid($current_time = null) {
		if (is_null($current_time)) {
			$current_time = strtotime('now');
		}
		// If there are no days selected, assume all day/every day
		if (empty($this->getDays())) { return true; }
		
		// See if the day is available
		if (in_array(date('w', $current_time), $this->getDays())) {
			if ($this->getAllDay()) { return true; }
			if ((date('G', $current_time) >= $this->getStartHour()) && (date('G', $current_time) <= $this->getEndHour())) {
				return true;
			}
		}
		return false;
	}
}