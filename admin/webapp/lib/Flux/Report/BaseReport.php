<?php
namespace Flux\Report;

use \Mojavi\Form\MongoForm;

class BaseReport extends MongoForm {

	const DATE_RANGE_LAST_30_DAYS = 1;
	const DATE_RANGE_LAST_7_DAYS = 2;
	const DATE_RANGE_THIS_WEEK = 3;
	const DATE_RANGE_YESTERDAY = 4;
	const DATE_RANGE_TODAY = 5;
	const DATE_RANGE_LAST_48_HOURS = 6;
	const DATE_RANGE_LAST_24_HOURS = 7;
	const DATE_RANGE_LAST_12_HOURS = 8;
	const DATE_RANGE_LAST_4_HOURS = 9;
	const DATE_RANGE_LAST_1_HOURS = 10;
	const DATE_RANGE_LAST_10_MIN = 11;
	const DATE_RANGE_CUSTOM = 12;

	const EVENT_WINDOW_EXCLUSIVE_ANY = 1;
	const EVENT_WINDOW_EXCLUSIVE_EXCPLICIT = 2;
	const EVENT_WINDOW_ALL = 3;

	protected $date_range;
	protected $start_time;
	protected $end_time;
	protected $tz_modifier;
	protected $start_time_tz;
	protected $end_time_tz;
	protected $event_window;

	protected $offer_id = array();
	protected $campaign_id = array();
	protected $event_id = array();
	protected $group_id = array();
	protected $column_id = array();

	/**
	 * Returns the date_range
	 * @return integer
	 */
	function getDateRange() {
		if (is_null($this->date_range)) {
			$this->date_range = self::DATE_RANGE_TODAY;
		}
		return $this->date_range;
	}

	/**
	 * Sets the date_range
	 * @var integer
	 */
	function setDateRange($arg0) {
		$this->date_range = (int)$arg0;
		return $this;
	}

	/**
	 * Returns the start_time
	 * @return string
	 */
	function getStartTime() {
		if (is_null($this->start_time)) {
			$this->start_time = "";
		}
		return $this->start_time;
	}

	/**
	 * Sets the start_time
	 * @var string
	 */
	function setStartTime($arg0) {
		$this->start_time = $arg0;
		return $this;
	}

	/**
	 * Returns the end_time
	 * @return string
	 */
	function getEndTime() {
		if (is_null($this->end_time)) {
			$this->end_time = "";
		}
		return $this->end_time;
	}

	/**
	 * Sets the end_time
	 * @var string
	 */
	function setEndTime($arg0) {
		$this->end_time = $arg0;
		return $this;
	}

	/**
	 * Returns the tz_modifier
	 * @return integer
	 */
	function getTzModifier() {
		if (is_null($this->tz_modifier)) {
			$this->tz_modifier = date_default_timezone_get();
		}
		return $this->tz_modifier;
	}

	/**
	 * Sets the tz_modifier
	 * @var integer
	 */
	function setTzModifier($arg0) {
		$this->tz_modifier = $arg0;
		return $this;
	}

	/**
	 * Returns the offer_id
	 * @return array
	 */
	function getOfferId() {
		if (is_null($this->offer_id)) {
			$this->offer_id = array();
		}
		return $this->offer_id;
	}

	/**
	 * Sets the offer_id
	 * @var array
	 */
	function setOfferId($arg0) {
		$this->offer_id = $arg0;
		return $this;
	}

	/**
	 * Returns the campaign_id
	 * @return array
	 */
	function getCampaignId() {
		if (is_null($this->campaign_id)) {
			$this->campaign_id = array();
		}
		return $this->campaign_id;
	}

	/**
	 * Sets the campaign_id
	 * @var array
	 */
	function setCampaignId($arg0) {
		$this->campaign_id = $arg0;
		return $this;
	}

	/**
	 * Returns the event_id
	 * @return array
	 */
	function getEventId() {
		if (is_null($this->event_id)) {
			$this->event_id = array();
		}
		return $this->event_id;
	}

	/**
	 * Sets the event_id
	 * @var array
	 */
	function setEventId($arg0) {
		$this->event_id = $arg0;
		return $this;
	}

	/**
	 * Returns the group_id
	 * @return array
	 */
	function getGroupId() {
		if (is_null($this->group_id)) {
			$this->group_id = array();
		}
		return $this->group_id;
	}

	/**
	 * Sets the group_id
	 * @var array
	 */
	function setGroupId($arg0) {
		$this->group_id = $arg0;
		return $this;
	}

	/**
	 * Returns the column_id
	 * @return array
	 */
	function getColumnId() {
		if (is_null($this->column_id)) {
			$this->column_id = array();
		}
		return $this->column_id;
	}

	/**
	 * Sets the column_id
	 * @var array
	 */
	function setColumnId($arg0) {
		$this->column_id = $arg0;
		return $this;
	}

	/**
	 * Converts the data to an array
	 * @return multitype:NULL string multitype:NULL  \DateTime
	 */
	public function dataToArray() {
		$data = array();
		$data['date_range'] = $this->date_range;
		$data['start_time'] = $this->start_time;
		$data['end_time'] = $this->end_time;
		$data['tz_modifier'] = $this->tz_modifier;
		$data['offer_id'] = $this->offer_id;
		$data['campaign_id'] = $this->campaign_id;
		$data['event_id'] = $this->event_id;
		$data['group_id'] = $this->group_id;
		$data['column_id'] = $this->column_id;
		return $data;
	}
	/*
	public static function createSetNotation($notation_array) {
		$ritit = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($notation_array));
		$result = array();
		foreach ($ritit as $leafValue) {
			$keys = array();
			foreach (range(0, $ritit->getDepth()) as $depth) {
				$keys[] = $ritit->getSubIterator($depth)->key();
			}
			$result[ join('.', $keys) ] = $leafValue;
		}
		return $result;
	}
	*/

	/**
	 * Sets the event window
	 * @param unknown $value
	 */
	public function setEventWindow($value) {
		$this->event_window = (int) $value;
	}

	/**
	 * Adds an offer_id to the internal offer_id array
	 * @param integer $offer_id
	 */
	public function addOfferId($offer_id) {
		$tmp_array = $this->getOfferId();
		$tmp_array[] = (int)$offer_id;
		$this->setOfferId($tmp_array);
	}

	/**
	 * Adds a campaign_id to the internal campaign_id array
	 * @param unknown $campaign_id
	 */
	public function addCampaignId($campaign_id) {
		$tmp_array = $this->getCampaignId();
		$tmp_array[] = (int)$campaign_id;
		$this->setCampaignId($tmp_array);
	}

	/**
	 * Adds an event_id to the internal event_id array
	 * @param unknown $event_id_array
	 */
	public function addEventId($event_id) {
		$tmp_array = $this->getEventId();
		$tmp_array[] = (int)$event_id;
		$this->setEventId($tmp_array);
	}

	/**
	 * Adds a column_id to the internal column_id array
	 * @param unknown $column_id_array
	 */
	public function addColumnId($column_id) {
		$tmp_array = $this->getColumnId();
		$tmp_array[] = (int)$column_id;
		$this->setColumnId($tmp_array);
	}

	/**
	 * Returns an array of date formats
	 * @return multitype:string
	 */
	static public function retrieveDateRanges()
	{
		return array(
			self::DATE_RANGE_LAST_30_DAYS => 'Last 30 Days',
			self::DATE_RANGE_LAST_7_DAYS => 'Last 7 Days',
			self::DATE_RANGE_THIS_WEEK => 'This Week',
			self::DATE_RANGE_YESTERDAY => 'Yesterday',
			self::DATE_RANGE_TODAY => 'Today',
			self::DATE_RANGE_LAST_48_HOURS => 'Last 48 Hours',
			self::DATE_RANGE_LAST_24_HOURS => 'Last 24 Hours',
			self::DATE_RANGE_LAST_12_HOURS => 'Last 12 Hours',
			self::DATE_RANGE_LAST_4_HOURS => 'Last 4 Hours',
			self::DATE_RANGE_LAST_1_HOURS => 'Last Hour',
			self::DATE_RANGE_LAST_10_MIN => 'Last 10 Minutes',
			self::DATE_RANGE_CUSTOM => 'Custom'
		);
	}

	/**
	 * Returns an array of event windows
	 * @return multitype:string
	 */
	static public function getEventWindows() {
		return array(
			self::EVENT_WINDOW_EXCLUSIVE_ANY => 'Selected events + siblings within report date',
			self::EVENT_WINDOW_EXCLUSIVE_EXCPLICIT => 'Selected events only',
			self::EVENT_WINDOW_ALL => 'Selected events + all siblings'
		);
	}

	/**
	 * Returns the group name from the id field
	 * @param integer $group_id
	 * @return string
	 */
	static public function getGroupNameFromId($group_id)
	{
		$groups = self::getGroups();
		return $groups[$group_id];
	}

	/**
	 * Massages the start and end date based on the date range filter
	 * @return void
	 */
	public function massageDates() {
		$timezone_string = date_default_timezone_get();
		if(strlen($this->tz_modifier) > 0) {
			$timezone_string = $this->tz_modifier;
		}
		$timezone = new \DateTimeZone($timezone_string);
		if(isset($this->date_range)){
			switch($this->date_range) {
				case self::DATE_RANGE_LAST_30_DAYS:
					$start_time = new \DateTime('30 days ago', $timezone);
					$this->start_time = $start_time->setTime(0,0,0);
					$end_time = new \DateTime('now', $timezone);
					$this->end_time = $end_time->setTime(23,59,59);
					break;
				case self::DATE_RANGE_LAST_7_DAYS:
					$start_time = new \DateTime('7 days ago', $timezone);
					$this->start_time = $start_time->setTime(0,0,0);
					$end_time = new \DateTime('now', $timezone);
					$this->end_time = $end_time->setTime(23,59,59);
					break;
				case self::DATE_RANGE_THIS_WEEK:
					$this->start_time = new \DateTime('last monday midnight', $timezone);
					$end_time = new \DateTime('next sunday midnight', $timezone);
					$this->end_time = $end_time->setTime(23,59,59);
					break;
				case self::DATE_RANGE_YESTERDAY:
					$this->start_time = new \DateTime('yesterday', $timezone);
					$end_time = new \DateTime('yesterday', $timezone);
					$this->end_time = $end_time->setTime(23,59,59);
					break;
				case self::DATE_RANGE_TODAY:
					$this->start_time = new \DateTime('today', $timezone);
					$end_time = new \DateTime('today', $timezone);
					$this->end_time = $end_time->setTime(23,59,59);
					break;
				case self::DATE_RANGE_LAST_48_HOURS:
					$this->start_time = new \DateTime('-48 hours', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_LAST_24_HOURS:
					$this->start_time = new \DateTime('-24 hours', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_LAST_12_HOURS:
					$this->start_time = new \DateTime('-12 hours', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_LAST_4_HOURS:
					$this->start_time = new \DateTime('-4 hours', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_LAST_1_HOURS:
					$this->start_time = new \DateTime('-1 hours', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_LAST_10_MIN:
					$this->start_time = new \DateTime('-10 minutes', $timezone);
					$this->end_time = new \DateTime('now', $timezone);
					break;
				case self::DATE_RANGE_CUSTOM:
				    $end_time = new \DateTime($this->getEndTime());
				    $this->end_time = $end_time->setTime(23,59,59);
			}
		}

		if(! is_a($this->start_time, 'DateTime')) {
			$this->start_time = new \DateTime($this->start_time, $timezone);
		}
		if(! is_a($this->end_time, 'DateTime')) {
			$this->end_time = new \DateTime($this->end_time, $timezone);
		}

		$this->start_time_tz = $this->start_time;
		$this->end_time_tz = $this->end_time;
		$this->start_time = $this->start_time->format(MO_DEFAULT_DATETIME_FORMAT_PHP);
		$this->end_time = $this->end_time->format(MO_DEFAULT_DATETIME_FORMAT_PHP);
	}
}