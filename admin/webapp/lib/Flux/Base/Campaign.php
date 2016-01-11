<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class Campaign extends MongoForm {

	const CAMPAIGN_STATUS_ACTIVE = 1;
	const CAMPAIGN_STATUS_INACTIVE = 2;
	const CAMPAIGN_STATUS_DELETED = 3;

	protected $description;
	protected $status;
	protected $offer;
	protected $client;
	protected $traffic_source;
	protected $redirect_link;
	protected $whitelist_ips;
	protected $tracking_pixel;
	
	protected $flow_rules;
	
	protected $payout;
	protected $daily_clicks;
	protected $daily_conversions;
	
	private $offer_id_array;
	private $client_id_array;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('campaign');
		$this->setDbName('admin');
		$this->setIdType(self::ID_TYPE_MONGO);
	}
	
	/**
	 * Returns the key
	 * @return string
	 */
	function getKey() {
		if (parent::getId() instanceof \MongoId) {
			return parent::getId()->__toString();
		}
		return parent::getId();
	}
	
	/**
	 * Sets the key
	 * @var string
	 */
	function setKey($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the redirect_link
	 * @return string
	 */
	function getRedirectUrl() {
		if (count($this->getFlowRules()) > 0) {
			// Figure out what url to use based on the rules
			$total_clicks = 0;
			foreach ($this->getFlowRules() as $flow_rule) {
				$total_clicks += $flow_rule->getDailyClickCount();
			}
			// Iterate through the rules and find the one that we want to use
			foreach ($this->getFlowRules() as $key => $flow_rule) {
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: Checking rule #" . $key . ": " . $flow_rule->getName());
				if ($flow_rule->getCap() > 0 && $flow_rule->getDailyClickCount() > $flow_rule->getCap()) {
					// We've hit our cap, so continue
					\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: Checking rule #" . $key . ": " . $flow_rule->getName() . ' - CAP HIT (' . $flow_rule->getDailyClickCount() . ' > ' . $flow_rule->getCap() . ')');
					continue;
				}
				// Only check percentages if this isn't the last rule (rules could be capped)
				if ($key < (count($this->getFlowRules()) - 1)) {
					if ((($flow_rule->getDailyClickCount() / $total_clicks) * 100) > $flow_rule->getPercent()) {
						\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: Checking rule #" . $key . ": " . $flow_rule->getName() . ' - PERCENT HIT (' . (($flow_rule->getDailyClickCount() / $total_clicks) * 100) . ' > ' . $flow_rule->getPercent() . '%)');
						continue;
					}
				}
				
				\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: Checking rule #" . $key . ": " . $flow_rule->getName() . ' - ALL CHECKS PASSED, FORWARDING TO ' . $flow_rule->getLandingPage());
				// We are below the percentage, so use this rule
				$this->update(array('_id' => $this->getId()), array(
					'$inc' => array(
						'flow_rules.' . $key . '.daily_click_count' => 1
					)	
				));
				
				return $flow_rule->getLandingPage() . '?_id=#_id#&s4=' . $flow_rule->getS4() . '&s5=' . $flow_rule->getS5();
			}
			
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: No Rules Matched, using default landing page, FORWARDING TO " . $this->getRedirectLink());
			return $this->getRedirectLink() . '?_id=#_id#';
		} else {
			\Mojavi\Logging\LoggerManager::error(__METHOD__ . " :: No Rules Defined, using default landing page, FORWARDING TO " . $this->getRedirectLink());
			return $this->getRedirectLink() . '?_id=#_id#';
		}
	}
	
	/**
	 * Sets the redirect_link
	 * @var string
	 */
	function setRedirectUrl($arg0) {
		return $this->setRedirectLink($arg0);
	}
	
	/**
	 * Returns the payout
	 * @return float
	 */
	function getPayout() {
		if (is_null($this->payout)) {
			$this->payout = 0.00;
		}
		return $this->payout;
	}
	
	/**
	 * Sets the payout
	 * @var float
	 */
	function setPayout($arg0) {
		$this->payout = floatval($arg0);
		$this->addModifiedColumn("payout");
		return $this;
	}
	
	/**
	 * Returns the redirect_link
	 * @return string
	 */
	function getRedirectLink() {
		if (is_null($this->redirect_link)) {
			$this->redirect_link = "";
		}
		return $this->redirect_link;
	}
	
	/**
	 * Sets the redirect_link
	 * @var string
	 */
	function setRedirectLink($arg0) {
		$this->redirect_link = $arg0;
		$this->addModifiedColumn("redirect_link");
		return $this;
	}
	
	/**
	 * Returns the description
	 * @return string
	 */
	function getDescription() {
		if (is_null($this->description)) {
			$this->description = "";
		}
		return $this->description;
	}
	
	/**
	 * Sets the description
	 * @var string
	 */
	function setDescription($arg0) {
		$this->description = $arg0;
		$this->addModifiedColumn("description");
		return $this;
	}

	/**
	 * Returns the status
	 * @return integer
	 */
	function getStatus() {
		if (is_null($this->status)) {
			$this->status = self::CAMPAIGN_STATUS_ACTIVE;
		}
		return $this->status;
	}

	/**
	 * Sets the status
	 * @var integer
	 */
	function setStatus($arg0) {
		$this->status = (int)$arg0;
		$this->addModifiedColumn('status');
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
		$this->daily_clicks = $arg0;
		$this->addModifiedColumn("daily_clicks");
		return $this;
	}
	
	/**
	 * Returns the daily_conversions
	 * @return integer
	 */
	function getDailyConversions() {
		if (is_null($this->daily_conversions)) {
			$this->daily_conversions = 0;
		}
		return $this->daily_conversions;
	}
	
	/**
	 * Sets the daily_conversions
	 * @var integer
	 */
	function setDailyConversions($arg0) {
		$this->daily_conversions = $arg0;
		$this->addModifiedColumn("daily_conversions");
		return $this;
	}
	
	/**
	 * Returns the this
	 * @return \Flux\Link\Client
	 */
	function getClient() {
		if (is_null($this->client)) {
			$this->client = new \Flux\Link\Client();
		}
		return $this->client;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setClient($arg0) {
		if (is_array($arg0)) {
			$client = $this->getClient();
			$client->populate($arg0);
			if (\MongoId::isValid($client->getId()) && $client->getName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if (is_string($arg0)) {
			$client = $this->getClient();
			$client->setClientId($arg0);
			if (\MongoId::isValid($client->getId()) && $client->getName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		} else if ($arg0 instanceof \MongoId) {
			$client = $this->getClient();
			$client->setClientId($arg0);
			if (\MongoId::isValid($client->getId()) && $client->getName() == "") {
				$client->setClientName($client->getClient()->getName());
			}
			$this->client = $client;
		}
		$this->addModifiedColumn('client');
		return $this;
	}
	
	/**
	 * Returns the traffic_source
	 * @return \Flux\Link\TrafficSource
	 */
	function getTrafficSource() {
		if (is_null($this->traffic_source)) {
			$this->traffic_source = new \Flux\Link\TrafficSource();
		}
		return $this->traffic_source;
	}
	
	/**
	 * Sets the traffic_source
	 * @var \Flux\Link\TrafficSource
	 */
	function setTrafficSource($arg0) {
		if (is_array($arg0)) {
			$traffic_source = $this->getTrafficSource();
			$traffic_source->populate($arg0);
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getName() == "") {
				$traffic_source->setTrafficSourceName($traffic_source->getTrafficSource()->getName());
			}
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getTrafficSourceIcon() == "") {
				$traffic_source->setTrafficSourceIcon($traffic_source->getTrafficSource()->getIcon());
			}
			$this->traffic_source = $traffic_source;
		} else if (is_string($arg0)) {
			$traffic_source = $this->getTrafficSource();
			$traffic_source->setTrafficSourceId($arg0);
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getName() == "") {
				$traffic_source->setTrafficSourceName($traffic_source->getTrafficSource()->getName());
			}
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getTrafficSourceIcon() == "") {
				$traffic_source->setTrafficSourceIcon($traffic_source->getTrafficSource()->getIcon());
			}
			$this->traffic_source = $traffic_source;
		} else if ($arg0 instanceof \MongoId) {
			$traffic_source = $this->getTrafficSource();
			$traffic_source->setTrafficSourceId($arg0);
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getName() == "") {
				$traffic_source->setTrafficSourceName($traffic_source->getTrafficSource()->getName());
			}
			if (\MongoId::isValid($traffic_source->getId()) && $traffic_source->getTrafficSourceIcon() == "") {
				$traffic_source->setTrafficSourceIcon($traffic_source->getTrafficSource()->getIcon());
			}
			$this->traffic_source = $traffic_source;
		}
		$this->addModifiedColumn('traffic_source');
		return $this;
	}
	
	/**
	 * Returns the offer
	 * @return \Flux\Link\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Flux\Link\Offer();
		}
		return $this->offer;
	}
	
	/**
	 * Sets the this
	 * @var integer|array
	 */
	function setOffer($arg0) {
		if (is_array($arg0)) {
			$offer = $this->getOffer();
			$offer->populate($arg0);
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
			$this->offer = $offer;
		} else if (is_string($arg0)) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
			$this->offer = $offer;
		} else if ($arg0 instanceof \MongoId) {
			$offer = $this->getOffer();
			$offer->setOfferId($arg0);
			if (\MongoId::isValid($offer->getId()) && $offer->getName() == "") {
				$offer->setOfferName($offer->getOffer()->getName());
				\Mojavi\Logging\LoggerManager::debug(__METHOD__ . " :: Populating name from object");
			}
			$this->offer = $offer;
		}
		$this->addModifiedColumn('offer');
		return $this;
	}
	
	/**
	 * Returns the whitelist_ips
	 * @return array
	 */
	function getWhitelistIps() {
		if (is_null($this->whitelist_ips)) {
			$this->whitelist_ips = array();
		}
		return $this->whitelist_ips;
	}
	
	/**
	 * Sets the whitelist_ips
	 * @var array
	 */
	function setWhitelistIps($arg0) {
		if (is_array($arg0)) {
			$this->whitelist_ips = $arg0;
		} else if (is_string($arg0)) {
			if (strpos(",", $arg0) !== false) {
				$this->whitelist_ips = explode(",", $arg0);
			} else {
				$this->whitelist_ips = array($arg0);
			}
		}
		array_walk($this->whitelist_ips, function(&$value) { $value = trim($value); });
		$this->addModifiedColumn("whitelist_ips");
		return $this;
	}
	
	/**
	 * Returns the tracking_pixel
	 * @return string
	 */
	function getTrackingPixel() {
		if (is_null($this->tracking_pixel)) {
			$this->tracking_pixel = "";
		}
		return $this->tracking_pixel;
	}
	
	/**
	 * Sets the tracking_pixel
	 * @var string
	 */
	function setTrackingPixel($arg0) {
		$this->tracking_pixel = $arg0;
		$this->addModifiedColumn("tracking_pixel");
		return $this;
	}
	
	/**
	 * Returns the flow_rules
	 * @return array
	 */
	function getFlowRules() {
		if (is_null($this->flow_rules)) {
			$this->flow_rules = array();
		}
		return $this->flow_rules;
	}
	
	/**
	 * Sets the flow_rules
	 * @var array
	 */
	function setFlowRules($arg0) {
		if (is_array($arg0)) {
			$this->flow_rules = array();
			foreach ($arg0 as $key => $value) {
				$rule = new \Flux\Link\FlowRule();
				$rule->populate($value);
				$this->flow_rules[] = $rule;				
			}
		}
		$this->addModifiedColumn("flow_rules");
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
		$campaign = new self();
		$campaign->getCollection()->ensureIndex(array('offer._id' => 1, 'client._id' => 1, "traffic_source._id" => 1), array('background' => true));
		return true;
	}
}
