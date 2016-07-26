<?php
namespace Flux;

class ReportLead extends Base\ReportLead {
	
	private $date_range;
	private $start_date;
	private $end_date;
	
	private $client_id_array;
	private $offer_id_array;
	private $disposition_array;
	
	protected $day_of_year;
	
	/**
	 * Sets the report_date
	 * @var \MongoDate
	 */
	function setReportDate($arg0) {
		parent::setReportDate($arg0);
		$this->setStartDate(date('m/01/Y', $this->getReportDate()->sec));
		$this->setEndDate(date('m/t/Y', $this->getReportDate()->sec));
		return $this;
	}
	
	/**
	 * Returns the date_range
	 * @return integer
	 */
	function getDateRange() {
		if (is_null($this->date_range)) {
			$this->date_range = \Flux\Report\BaseReport::DATE_RANGE_TODAY;
		}
		return $this->date_range;
	}
	
	/**
	 * Sets the date_range
	 * @var integer
	 */
	function setDateRange($arg0) {
		$this->date_range = $arg0;
		$this->addModifiedColumn("date_range");
		return $this;
	}
	
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
	 * Returns the offer_id_array
	 * @return array
	 */
	function getOfferIdArray() {
		if (is_null($this->offer_id_array)) {
			$this->offer_id_array = array();
		}
		return $this->offer_id_array;
	}
	
	/**
	 * Sets the offer_id_array
	 * @var array
	 */
	function setOfferIdArray($arg0) {
		if (is_array($arg0)) {
			$this->offer_id_array = $arg0;
			array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0) || is_int($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->offer_id_array = explode(",", $arg0);
			} else {
				$this->offer_id_array = array($arg0);
			}
			array_walk($this->offer_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		}
		$this->addModifiedColumn("offer_id_array");
	}
	
	/**
	 * Returns the client_id_array
	 * @return array
	 */
	function getClientIdArray() {
		if (is_null($this->client_id_array)) {
			$this->client_id_array = array();
		}
		return $this->client_id_array;
	}
	
	/**
	 * Sets the client_id_array
	 * @var array
	 */
	function setClientIdArray($arg0) {
		if (is_array($arg0)) {
			$this->client_id_array = $arg0;
			array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if (is_string($arg0) || is_int($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->client_id_array = explode(",", $arg0);
			} else {
				$this->client_id_array = array($arg0);
			}
			array_walk($this->client_id_array, function(&$val) { if (\MongoId::isValid($val) && !($val instanceof \MongoId)) { $val = new \MongoId($val); }});
		} else if ($arg0 instanceof \MongoId) {
			$this->client_id_array = array($arg0);
		}
		$this->addModifiedColumn("client_id_array");
		return $this;
	}
	
	/**
	 * Returns the disposition_array
	 * @return array
	 */
	function getDispositionArray() {
		if (is_null($this->disposition_array)) {
			$this->disposition_array = array();
		}
		return $this->disposition_array;
	}
	
	/**
	 * Sets the disposition_array
	 * @var array
	 */
	function setDispositionArray($arg0) {
		if (is_array($arg0)) {
			$this->disposition_array = $arg0;
			array_walk($this->disposition_array, function(&$value) { $value = (int)$value; });
		} else if (is_string($arg0) || is_int($arg0)) {
			if (strpos($arg0, ',') !== false) {
				$this->disposition_array = explode(",", $arg0);
			} else {
				$this->disposition_array = array($arg0);
			}
			array_walk($this->disposition_array, function(&$value) { $value = (int)$value; });
		}
		$this->addModifiedColumn("disposition_array");
		return $this;
	}
	
	/**
	 * Queries entries by filters
	 */
	function queryByLead() {
		$criteria = array();
		if (\MongoId::isValid($this->getLead()->getLeadId())) {
			$criteria['lead._id'] = $this->getLead()->getLeadId();
		} else if (\MongoId::isValid($this->getId())) {
			$criteria['lead._id'] = $this->getId();
		}
		return parent::query($criteria, false);
	}
   
	/**
	 * Queries entries by filters
	 */
	function queryAll(array $criteria = array(), array $fields = array(), $hydrate = true, $timeout = 30000) {
		if (count($this->getDispositionArray()) > 0) {
			$criteria['disposition'] = array('$in' => $this->getDispositionArray());
		}
		if (trim($this->getKeywords()) != '') {
			if (\MongoId::isValid($this->getKeywords())) {
				$criteria['lead._id'] = $this->getKeywords();
			} else {
				$criteria['$or'] = array(
					array('lead.name' => new \MongoRegex('/' . $this->getKeywords() . '/i')), 
					array('lead.email' => new \MongoRegex('/' . $this->getKeywords() . '/i'))
				);
			}
		}
		if (count($this->getClientIdArray()) > 0) {
			$criteria['client._id'] = array('$in' => $this->getClientIdArray());
		}
		if (count($this->getOfferIdArray()) > 0) {
			$criteria['lead.offer._id'] = array('$in' => $this->getOfferIdArray());
		}
		if ($this->getDateRange() == \Flux\Report\BaseReport::DATE_RANGE_CUSTOM) {
			if ($this->getStartDate() != '' && $this->getEndDate() != '') {
				$criteria['report_date'] = array('$gte' => new \MongoDate(strtotime($this->getStartDate())), '$lte' => new \MongoDate(strtotime($this->getEndDate())));
			}
		} else {
			$base_report = new \Flux\Report\BaseReport();
			$base_report->setDateRange($this->getDateRange());
			$base_report->massageDates();
			$criteria['report_date'] = array('$gte' => new \MongoDate(strtotime($base_report->getStartTime())), '$lte' => new \MongoDate(strtotime($base_report->getEndTime())));
		}
		
		return parent::queryAll($criteria, $fields, $hydrate, $timeout);
	}
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$report_lead = new self();
		$report_lead->getCollection()->ensureIndex(array('lead._id' => 1, 'report_date' => 1), array('unique' => true, 'background' => true));
		return true;
	}
	
}