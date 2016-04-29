<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class ReportAdCampaign extends MongoForm {
	
	protected $ad_campaign;
	protected $report_date;
	protected $impression_count;
	protected $click_count;
	protected $cost;
	protected $average_cost;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('report_ad_campaign');
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
	 * Returns the impression_count
	 * @return integer
	 */
	function getImpressionCount() {
		if (is_null($this->impression_count)) {
			$this->impression_count = 0;
		}
		return $this->impression_count;
	}
	
	/**
	 * Sets the impression_count
	 * @var integer
	 */
	function setImpressionCount($arg0) {
		$this->impression_count = (int)$arg0;
		$this->addModifiedColumn("impression_count");
		return $this;
	}
	
	/**
	 * Returns the cost
	 * @return float
	 */
	function getCost() {
		if (is_null($this->cost)) {
			$this->cost = 0.00;
		}
		return $this->cost;
	}
	
	/**
	 * Sets the cost
	 * @var float
	 */
	function setCost($arg0) {
		$this->cost = (float)$arg0;
		$this->addModifiedColumn("cost");
		return $this;
	}
	
	/**
	 * Returns the average_cost
	 * @return float
	 */
	function getAverageCost() {
		if (is_null($this->average_cost)) {
			$this->average_cost = 0.00;
		}
		return $this->average_cost;
	}
	
	/**
	 * Sets the average_cost
	 * @var float
	 */
	function setAverageCost($arg0) {
		$this->average_cost = (float)$arg0;
		$this->addModifiedColumn("average_cost");
		return $this;
	}
	
	/**
	 * Returns the client
	 * @return \Flux\Link\AdCampaign
	 */
	function getAdCampaign() {
		if (is_null($this->ad_campaign)) {
			$this->ad_campaign = new \Flux\Link\AdCampaign();
		}
		return $this->ad_campaign;
	}
	
	/**
	 * Sets the ad_campaign
	 * @var \Flux\Link\AdCampaign
	 */
	function setAdCampaign($arg0) {
		if (is_array($arg0)) {
			$this->ad_campaign = new \Flux\Link\AdCampaign();
			$this->ad_campaign->populate($arg0);
			if (\MongoId::isValid($this->ad_campaign->getId()) && $this->ad_campaign->getName() == '') {
				$this->ad_campaign->setName($this->ad_campaign->getAdCampaign()->getName());
			}
		} else if (is_string($arg0)) {
			$this->ad_campaign = new \Flux\Link\AdCampaign();
			$this->ad_campaign->setId($arg0);
			if (\MongoId::isValid($this->ad_campaign->getId()) && $this->ad_campaign->getName() == '') {
				$this->ad_campaign->setName($this->ad_campaign->getAdCampaign()->getName());
			}
		} else if ($arg0 instanceof \MongoId) {
			$this->ad_campaign = new \Flux\Link\AdCampaign();
			$this->ad_campaign->setId($arg0);
			if (\MongoId::isValid($this->ad_campaign->getId()) && $this->ad_campaign->getName() == '') {
				$this->ad_campaign->setName($this->ad_campaign->getAdCampaign()->getName());
			}
		}
		$this->addModifiedColumn("ad_campaign");
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
		$report_client->getCollection()->ensureIndex(array('report_date' => 1, 'ad_campaign._id' => 1), array('background' => true));
		return true;
	}
	
}