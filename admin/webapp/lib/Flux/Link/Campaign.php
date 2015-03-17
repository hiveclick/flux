<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Campaign extends CommonForm {
	
	protected $campaign_id;
	protected $campaign_name;
	
	private $campaign;
	
	/**
	 * Returns the campaign_id
	 * @return integer
	 */
	function getCampaignId() {
		if (is_null($this->campaign_id)) {
			$this->campaign_id = '';
		}
		return $this->campaign_id;
	}
	
	/**
	 * Sets the campaign_id
	 * @var integer
	 */
	function setCampaignId($arg0) {
		$this->campaign_id = $arg0;
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