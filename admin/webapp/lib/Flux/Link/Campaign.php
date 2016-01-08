<?php
namespace Flux\Link;

class Campaign extends BasicLink {
	
	protected $campaign_description;
	
	private $campaign;
	
	/**
	 * Returns the campaign_id
	 * @return integer
	 */
	function getCampaignId() {
		return parent::getId();
	}
	
	/**
	 * Sets the campaign_id
	 * @var integer
	 */
	function setCampaignId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the campaign_name
	 * @return string
	 */
	function getCampaignName() {
		return parent::getName();
	}
	
	/**
	 * Sets the campaign_name
	 * @var string
	 */
	function setCampaignName($arg0) {
		return parent::setName($arg0);
	}
	
	/**
	 * Returns the campaign_description
	 * @return string
	 */
	function getCampaignDescription() {
		if (is_null($this->campaign_description)) {
			$this->campaign_description = "";
		}
		return $this->campaign_description;
	}
	
	/**
	 * Sets the campaign_description
	 * @var string
	 */
	function setCampaignDescription($arg0) {
		$this->campaign_description = $arg0;
		$this->addModifiedColumn("campaign_description");
		return $this;
	}
	
	/**
	 * Returns the campaign
	 * @return \Flux\Campaign
	 */
	function getCampaign() {
		if (is_null($this->campaign)) {
			$this->campaign = new \Flux\Campaign();
			if (\MongoId::isValid($this->getId())) {
				$this->campaign->setId($this->getId());
				$this->campaign->query();
			}
		}
		return $this->campaign;
	}
}