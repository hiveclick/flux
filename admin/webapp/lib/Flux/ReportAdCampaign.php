<?php
namespace Flux;

class ReportAdCampaign extends Base\ReportAdCampaign {
	
	private $start_date;
	private $end_date;
	
	protected $day_of_year;
	
	/**
	 * Returns the start_date
	 * @return string
	 */
	function getStartDate() {
		if (is_null($this->start_date)) {
			$this->start_date = "";
		}
		return $this->start_date;
	}
	
	/**
	 * Sets the start_date
	 * @var string
	 */
	function setStartDate($arg0) {
		$this->start_date = $arg0;
		$this->addModifiedColumn("start_date");
		return $this;
	}
	
	/**
	 * Returns the end_date
	 * @return string
	 */
	function getEndDate() {
		if (is_null($this->end_date)) {
			$this->end_date = "";
		}
		return $this->end_date;
	}
	
	/**
	 * Sets the end_date
	 * @var string
	 */
	function setEndDate($arg0) {
		$this->end_date = $arg0;
		$this->addModifiedColumn("end_date");
		return $this;
	}
	
	/**
	 * Returns the day_of_year
	 * @return string
	 */
	function getDayOfYear() {
		return date('z', $this->getReportDate()->sec);
	}
	
	
	/**
	 * Queries entries by filters
	 */
	function queryAll(array $criteria = array(), $hydrate = false, $fields = array()) {
		if ($this->getStartDate() != '' && $this->getEndDate() != '') {
			$criteria['report_date'] = array('$gte' => new \MongoDate(strtotime($this->getStartDate())), '$lte' => new \MongoDate(strtotime($this->getEndDate())));
		}
		return parent::queryAll($criteria, $hydrate, $fields);
	}	
}