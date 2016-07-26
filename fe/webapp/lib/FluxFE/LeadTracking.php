<?php
namespace FluxFE;

class LeadTracking extends \Flux\LeadTracking {
	
	private $_offer;
	private $_client;
	private $_campaign;
	
	/**
	 * Returns the offer details
	 * @return \Flux\Offer
	 */
	function getOffer() {
		if (is_null($this->_offer)) {
			$this->_offer = new \FluxFE\Offer();
			if (!\MongoId::isValid($this->getOfferId())) {
				$this->findDefaultCampaign();
			}
			$this->_offer->setId($this->getOfferId());
			$this->_offer->query();
		}
		return $this->_offer;
	}
	
	/**
	 * Returns the client details
	 * @return \Flux\Client
	 */
	function getClient() {
		if (is_null($this->_client)) {
			$this->_client = new \FluxFE\Client();
			if (\MongoId::isValid($this->getClientId())) {
				$this->findDefaultCampaign();
			}
			$this->_client->setId($this->getClientId());
			$this->_client->query();
		}
		return $this->_client;
	}
	
	/**
	 * Returns the campaign details
	 * @return \Flux\Campaign
	 */
	function getCampaign() {
		if (is_null($this->_campaign)) {
			if ($this->getCampaignId() == '') {
				$this->findDefaultCampaign();
			}
			if (\MongoId::isValid($this->getCampaignId())) {
				$this->_campaign = new \FluxFE\Campaign();
				$this->_campaign->setId($this->getCampaignId());
				$this->_campaign->query();
			}
		}
		return $this->_campaign;
	}
	
	/**
	 * Sets the _campaign_id
	 * @var string
	 */
	function setCampaignId($arg0) {
		$this->_campaign_id = $arg0;
		
		$this->_campaign = new \FluxFE\Campaign();
		$this->_campaign->setId($arg0);
		$this->_campaign->query();

		$this->setClientId($this->_campaign->getClientId());
		$this->setOfferId($this->_campaign->getOfferId());
		
		$this->addModifiedColumn("_campaign_id");
		return $this;
	}
	
	
	
}