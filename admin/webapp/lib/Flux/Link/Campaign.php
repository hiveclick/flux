<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Campaign extends CommonForm {
	
	protected $campaign_id;
	protected $campaign_name;
	protected $campaign_description;
	
	private $campaign;
	
	/**
	 * Returns the campaign_id
	 * @return integer
	 */
	function getCampaignId() {
		if (is_null($this->campaign_id)) {
			$this->campaign_id = null;
		}
		return $this->campaign_id;
	}
	
	/**
	 * Sets the campaign_id
	 * @var integer
	 */
	function setCampaignId($arg0) {
	    if (is_string($arg0) && \MongoId::isValid($arg0)) {
	        $this->campaign_id = new \MongoId($arg0);
	    } else if ($arg0 instanceof \MongoId) {
	        $this->campaign_id = $arg0;
	    }
		return $this;
	}
	
	/**
	 * Returns the campaign_name
	 * @return string
	 */
	function getCampaignName() {
		if (is_null($this->campaign_name)) {
			$this->campaign_name = "";
		}
		return $this->campaign_name;
	}
	
	/**
	 * Sets the campaign_name
	 * @var string
	 */
	function setCampaignName($arg0) {
		$this->campaign_name = $arg0;
		return $this;
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
			$this->campaign->setId($this->getCampaignId());
			$this->campaign->query();
		}
		return $this->campaign;
	}
}