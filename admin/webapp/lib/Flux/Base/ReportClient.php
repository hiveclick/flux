<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class ReportClient extends MongoForm {
	
	protected $client;
	protected $report_date;
	protected $click_count;
	protected $conversion_count;
	protected $revenue;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('report_client');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the report_date
	 * @return \MongoDate
	 */
	function getReportDate() {
		if (is_null($this->report_date)) {
			$this->report_date = new \MongoDate();
		}
		return $this->report_date;
	}
	
	/**
	 * Sets the report_date
	 * @var \MongoDate
	 */
	function setReportDate($arg0) {
		if ($arg0 instanceof \MongoDate) {
			$this->report_date = $arg0;
		} else if (is_string($arg0)) {
			$this->report_date = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->report_date = new \MongoDate($arg0);
		}
		$this->addModifiedColumn('report_date');
		return $this;
	}
	
	/**
	 * Returns the click_count
	 * @return integer
	 */
	function getClickCount() {
		if (is_null($this->click_count)) {
			$this->click_count = 0;
		}
		return $this->click_count;
	}
	
	/**
	 * Sets the click_count
	 * @var integer
	 */
	function setClickCount($arg0) {
		$this->click_count = (int)$arg0;
		$this->addModifiedColumn("click_count");
		return $this;
	}
	
	/**
	 * Returns the conversion_count
	 * @return integer
	 */
	function getConversionCount() {
		if (is_null($this->conversion_count)) {
			$this->conversion_count = 0;
		}
		return $this->conversion_count;
	}
	
	/**
	 * Sets the conversion_count
	 * @var integer
	 */
	function setConversionCount($arg0) {
		$this->conversion_count = (int)$arg0;
		$this->addModifiedColumn("conversion_count");
		return $this;
	}
	
	/**
	 * Returns the revenue
	 * @return float
	 */
	function getRevenue() {
		if (is_null($this->revenue)) {
			$this->revenue = 0.00;
		}
		return $this->revenue;
	}
	
	/**
	 * Sets the revenue
	 * @var float
	 */
	function setRevenue($arg0) {
		$this->revenue = floatval($arg0);
		$this->addModifiedColumn("revenue");
		return $this;
	}
	
	/**
	 * Returns the client
	 * @return \Flux\Link\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Link\Client();
		}
		return $this->client;
	}
	
	/**
	 * Sets the client
	 * @var \Flux\Link\Client
	 */
	function setClient($arg0) {
		if (is_array($arg0)) {
			$this->client = new \Flux\Link\Client();
			$this->client->populate($arg0);
			if (\MongoId::isValid($this->client->getId()) && $this->client->getName() == '') {
				$this->client->setClientName($this->client->getClient()->getName());
			}
		} else if (is_string($arg0)) {
			$this->client = new \Flux\Link\Client();
			$this->client->setClientId($arg0);
			if (\MongoId::isValid($this->client->getId()) && $this->client->getName() == '') {
				$this->client->setClientName($this->client->getClient()->getName());
			}
		} else if ($arg0 instanceof \MongoId) {
			$this->client = new \Flux\Link\Client();
			$this->client->setClientId($arg0);
			if (\MongoId::isValid($this->client->getId()) && $this->client->getName() == '') {
				$this->client->setClientName($this->client->getClient()->getName());
			}
		}
		$this->addModifiedColumn("client");
		return $this;
	}
	
	// +------------------------------------------------------------------------+
	// | HELPER METHODS															|
	// +------------------------------------------------------------------------+
	
	/**
	 * Ensures that the mongo indexes are set (should be called once)
	 * @return boolean
	 */
	public static function ensureIndexes() {
		$report_client = new self();
		$report_client->getCollection()->ensureIndex(array('report_date' => 1, 'client._id' => 1), array('background' => true));
		return true;
	}
}