<?php
namespace Flux\Link;

class AdCampaign extends BasicLink {
		
	private $ad_campaign;
	
	/**
	 * Returns the campaign_id
	 * @return integer
	 */
	function getAdCampaignId() {
		return parent::getId();
	}
	
	/**
	 * Sets the campaign_id
	 * @var integer
	 */
	function setAdCampaignId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the campaign_name
	 * @return string
	 */
	function getAdCampaignName() {
		return parent::getName();
	}
	
	/**
	 * Sets the campaign_name
	 * @var string
	 */
	function setAdCampaignName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the ad_campaign
	 * @return \Flux\AdCampaign
	 */
	function getAdCampaign() {
		if (is_null($this->ad_campaign)) {
			$this->ad_campaign = new \Flux\AdCampaign();
			if (\MongoId::isValid($this->getId())) {
				$this->ad_campaign->setId($this->getId());
				$this->ad_campaign->query();
			}
		}
		return $this->ad_campaign;
	}
}