<?php
namespace Flux\Link;

use Mojavi\Form\CommonForm;

class Lead extends CommonForm {
	
	protected $lead_id;
	protected $lead_name;
	protected $offer;
	protected $client;
	protected $campaign;
	protected $name;
	protected $email;
	protected $phone;
	protected $lead_created;
	
	private $lead;
	
	/**
	 * Returns the lead_id
	 * @return integer
	 */
	function getLeadId() {
		if (is_null($this->lead_id)) {
			$this->lead_id = '';
		}
		return $this->lead_id;
	}
	
	/**
	 * Sets the lead_id
	 * @var integer
	 */
	function setLeadId($arg0) {
		$this->lead_id = $arg0;
		// Try to set the other fields
		if (trim($this->getLead()->getValue('fn') . ' ' . $this->getLead()->getValue('ln')) != '') {
            $this->setLeadName($this->getLead()->getValue('fn') . ' ' . $this->getLead()->getValue('ln'));
		} else {
		    $this->setLeadName($this->getLead()->getValue('name'));
		}
		$this->setEmail($this->getLead()->getValue('em'));
		$this->setPhone($this->getLead()->getValue('ph'));
		if ($this->getLead()->getId() instanceof \MongoId) {
			$this->setLeadCreated($this->getLead()->getId()->getTimestamp());
		}
		$this->setOffer($this->getLead()->getTracking()->getOffer()->getOfferId());
		$this->setClient($this->getLead()->getTracking()->getClient()->getClientId());
		$this->setCampaign($this->getLead()->getTracking()->getCampaign()->getCampaignId());
		return $this;
	}
	
	/**
	 * Returns the lead_name
	 * @return string
	 */
	function getLeadName() {
		if (is_null($this->lead_name)) {
			$this->lead_name = "";
		}
		return $this->lead_name;
	}
	
	/**
	 * Sets the lead_name
	 * @var string
	 */
	function setLeadName($arg0) {
		$this->lead_name = $arg0;
		return $this;
	}
	
	/**
	 * Returns the email
	 * @return string
	 */
	function getEmail() {
		if (is_null($this->email)) {
			$this->email = "";
		}
		return $this->email;
	}
	
	/**
	 * Sets the email
	 * @var string
	 */
	function setEmail($arg0) {
		$this->email = $arg0;
		return $this;
	}
	
	/**
	 * Returns the phone
	 * @return string
	 */
	function getPhone() {
		if (is_null($this->phone)) {
			$this->phone = "";
		}
		return $this->phone;
	}
	
	/**
	 * Sets the phone
	 * @var string
	 */
	function setPhone($arg0) {
		$this->phone = $arg0;
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
	 * Sets the offer
	 * @var \Flux\Link\Offer
	 */
	function setOffer($arg0) {
		if (is_array($arg0)) {
			$this->offer = $this->getOffer();
			$this->offer->populate($arg0);
			if ($this->offer->getOfferId() > 0 && $this->offer->getOfferName() == '') {
				$this->offer->setOfferName($this->offer->getOffer()->getName());
			}
		} else if (is_string($arg0) || is_int($arg0)) {
			$this->offer = $this->getOffer();
			$this->offer->setOfferId($arg0);
			if ($this->offer->getOfferId() > 0 && $this->offer->getOfferName() == '') {
				$this->offer->setOfferName($this->offer->getOffer()->getName());
			}
		}
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
			$this->client = $this->getClient();
			$this->client->populate($arg0);
			if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
				$this->client->setClientName($this->client->getClient()->getName());
			}
		} else if (is_string($arg0) || is_int($arg0)) {
			$this->client = $this->getClient();
			$this->client->setClientId($arg0);
			if ($this->client->getClientId() > 0 && $this->client->getClientName() == '') {
				$this->client->setClientName($this->client->getClient()->getName());
			}
		}
		return $this;
	}
	
	/**
	 * Returns the campaign
	 * @return \Flux\Link\Campaign
	 */
	function getCampaign() {
		if (is_null($this->campaign)) {
			$this->campaign = new \Flux\Link\Campaign();
		}
		return $this->campaign;
	}
	
	/**
	 * Sets the campaign
	 * @var \Flux\Link\Campaign
	 */
	function setCampaign($arg0) {
		if (is_array($arg0)) {
			$this->campaign = $this->getCampaign();
			$this->campaign->populate($arg0);
			if (\MongoId::isValid($this->campaign->getCampaignId()) && $this->campaign->getCampaignName() == '') {
				$this->campaign->setCampaignName((string)$this->campaign->getCampaignId());
			}
			if (\MongoId::isValid($this->campaign->getCampaignId()) && $this->campaign->getCampaignDescription() == '') {
			    $this->campaign->setCampaignDescription((string)$this->campaign->getCampaign()->getDescription());
			}
		} else if (is_string($arg0)) {
			$this->campaign = $this->getCampaign();
			$this->campaign->setCampaignId($arg0);
			if (\MongoId::isValid($this->campaign->getCampaignId()) && $this->campaign->getCampaignName() == '') {
				$this->campaign->setCampaignName((string)$this->campaign->getCampaignId());
			}
			if (\MongoId::isValid($this->campaign->getCampaignId()) && $this->campaign->getCampaignDescription() == '') {
			    $this->campaign->setCampaignDescription((string)$this->campaign->getCampaign()->getDescription());
			}
		}
		return $this;
	}
	
	/**
	 * Returns the lead_created
	 * @return MongoDate
	 */
	function getLeadCreated() {
		if (is_null($this->lead_created)) {
			$this->lead_created = new \MongoDate();
		}
		return $this->lead_created;
	}
	
	/**
	 * Sets the lead_created
	 * @var MongoDate
	 */
	function setLeadCreated($arg0) {
		$this->lead_created = $arg0;
		return $this;
	}
	
	/**
	 * Returns the lead
	 * @return \Flux\Lead
	 */
	function getLead() {
		if (is_null($this->lead)) {
			$this->lead = new \Flux\Lead();
			$this->lead->setId($this->getLeadId());
			$this->lead->query();
		}
		return $this->lead;
	}
}