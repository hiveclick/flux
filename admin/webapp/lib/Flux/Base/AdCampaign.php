<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class AdCampaign extends MongoForm {
	
	protected $campaign_id;
	protected $name;
	protected $status;
	protected $serving_status;
	protected $start_date;
	protected $end_date;
	protected $budget;
	protected $conversion_optimizer_eligibility;
	protected $ad_serving_optimization_status;
	protected $frequency_cap;
	protected $settings;
	protected $advertising_channel_type;
	protected $advertising_channel_sub_type;
	protected $network_setting;
	protected $labels;
	protected $bidding_strategy_configuration;
	protected $forward_compatibility_map;
	protected $tracking_url_template;
	protected $url_custom_parameters;
	
	protected $daily_cost;
	protected $daily_clicks;
	protected $mtd_cost;
	protected $mtd_clicks;
	protected $daily_average_cost;
	
	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('ad_campaign');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the campaign_id
	 * @return integer
	 */
	function getCampaignId() {
		if (is_null($this->campaign_id)) {
			$this->campaign_id = 0;
		}
		return $this->campaign_id;
	}
	
	/**
	 * Sets the campaign_id
	 * @var integer
	 */
	function setCampaignId($arg0) {
		$this->campaign_id = (int)$arg0;
		$this->addModifiedColumn("campaign_id");
		return $this;
	}
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		if (is_null($this->name)) {
			$this->name = "";
		}
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @var string
	 */
	function setName($arg0) {
		$this->name = $arg0;
		$this->addModifiedColumn("name");
		return $this;
	}
	
	/**
	 * Returns the status
	 * @return string
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = "";
		}
		return $this->status;
	}
	
	/**
	 * Sets the status
	 * @var string
	 */
	function setStatus($arg0) {
		$this->status = $arg0;
		$this->addModifiedColumn("status");
		return $this;
	}
	
	/**
	 * Returns the start_date
	 * @return \MongoDate
	 */
	function getStartDate() {
		if (is_null($this->start_date)) {
			$this->start_date = new \MongoDate();
		}
		return $this->start_date;
	}
	
	/**
	 * Sets the start_date
	 * @var \MongoDate
	 */
	function setStartDate($arg0) {
		if (is_string($arg0)) {
			$this->start_date = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->start_date = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->start_date = $arg0;
		}
		$this->addModifiedColumn("start_date");
		return $this;
	}
	
	/**
	 * Returns the end_date
	 * @return \MongoDate
	 */
	function getEndDate() {
		if (is_null($this->end_date)) {
			$this->end_date = new \MongoDate();
		}
		return $this->end_date;
	}
	
	/**
	 * Sets the end_date
	 * @var \MongoDate
	 */
	function setEndDate($arg0) {
		if (is_string($arg0)) {
			$this->end_date = new \MongoDate(strtotime($arg0));
		} else if (is_int($arg0)) {
			$this->end_date = new \MongoDate($arg0);
		} else if ($arg0 instanceof \MongoDate) {
			$this->end_date = $arg0;
		}
		$this->addModifiedColumn("end_date");
		return $this;
	}
	
	/**
	 * Returns the daily_cost
	 * @return float
	 */
	function getDailyCost() {
		if (is_null($this->daily_cost)) {
			$this->daily_cost = 0.00;
		}
		return $this->daily_cost;
	}
	
	/**
	 * Sets the daily_cost
	 * @var float
	 */
	function setDailyCost($arg0) {
		$this->daily_cost = (float)$arg0;
		$this->addModifiedColumn("daily_cost");
		return $this;
	}
	
	/**
	 * Returns the daily_clicks
	 * @return integer
	 */
	function getDailyClicks() {
		if (is_null($this->daily_clicks)) {
			$this->daily_clicks = 0;
		}
		return $this->daily_clicks;
	}
	
	/**
	 * Sets the daily_clicks
	 * @var integer
	 */
	function setDailyClicks($arg0) {
		$this->daily_clicks = (int)$arg0;
		$this->addModifiedColumn("daily_clicks");
		return $this;
	}
	
	/**
	 * Returns the daily_average_cost
	 * @return float
	 */
	function getDailyAverageCost() {
		if (is_null($this->daily_average_cost)) {
			$this->daily_average_cost = 0.00;
		}
		return $this->daily_average_cost;
	}
	
	/**
	 * Sets the daily_average_cost
	 * @var float
	 */
	function setDailyAverageCost($arg0) {
		$this->daily_average_cost = (float)$arg0;
		$this->addModifiedColumn("daily_average_cost");
		return $this;
	}
	
	/**
	 * Returns the mtd_cost
	 * @return float
	 */
	function getMtdCost() {
		if (is_null($this->mtd_cost)) {
			$this->mtd_cost = 0.00;
		}
		return $this->mtd_cost;
	}
	
	/**
	 * Sets the mtd_cost
	 * @var float
	 */
	function setMtdCost($arg0) {
		$this->mtd_cost = (float)$arg0;
		$this->addModifiedColumn("mtd_cost");
		return $this;
	}
	
	/**
	 * Returns the mtd_clicks
	 * @return integer
	 */
	function getMtdClicks() {
		if (is_null($this->mtd_clicks)) {
			$this->mtd_clicks = 0;
		}
		return $this->mtd_clicks;
	}
	
	/**
	 * Sets the mtd_clicks
	 * @var integer
	 */
	function setMtdClicks($arg0) {
		$this->mtd_clicks = (int)$arg0;
		$this->addModifiedColumn("mtd_clicks");
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
		$ad_campaign = new self();
		$ad_campaign->getCollection()->ensureIndex(array('campaign_id' => 1), array('unique' => true, 'background' => true));
		return true;
	}
	
}