<?php
namespace Flux\Base;

use Mojavi\Form\MongoForm;

class SplitPosition extends MongoForm {

	protected $fulfillment;
	protected $split;

	protected $daily_cap_count;
	protected $cap;
	protected $revenue;

	protected $offers;
	protected $verticals;
	protected $domain_groups;
	protected $data_fields;
	protected $position;

	/**
	 * Constructs new user
	 * @return void
	 */
	function __construct() {
		$this->setCollectionName('split_position');
		$this->setDbName('admin');
	}

	/**
	 * Returns the cap
	 * @return integer
	 */
	function getCap() {
		if (is_null($this->cap)) {
			$this->cap = 0;
		}
		return $this->cap;
	}

	/**
	 * Sets the cap
	 * @var integer
	 */
	function setCap($arg0) {
		$this->cap = (int)$arg0;
		$this->addModifiedColumn('cap');
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
		$this->revenue = (float)$arg0;
		$this->addModifiedColumn('revenue');
		return $this;
	}

	/**
	 * Returns the position
	 * @return integer
	 */
	function getPosition() {
		if (is_null($this->position)) {
			$this->position = 0;
		}
		return $this->position;
	}

	/**
	 * Sets the position
	 * @var integer
	 */
	function setPosition($arg0) {
		$this->position = (int)$arg0;
		$this->addModifiedColumn('position');
		return $this;
	}
	
	/**
	 * Returns the daily_cap_count
	 * @return integer
	 */
	function getDailyCapCount() {
		if (is_null($this->daily_cap_count)) {
			$this->daily_cap_count = 0;
		}
		return $this->daily_cap_count;
	}
	
	/**
	 * Sets the daily_cap_count
	 * @var integer
	 */
	function setDailyCapCount($arg0) {
		$this->daily_cap_count = $arg0;
		$this->addModifiedColumn('daily_cap_count');
		return $this;
	}
	
	/**
	 * Sets the daily_cap_count
	 * @var integer
	 */
	function incrementDailyCapCount() {
		$this->daily_cap_count++;
		$this->addModifiedColumn('daily_cap_count');
		return $this;
	}
	
    /**
	 * Returns the offers
	 * @return integer
	 */
	function getOffers() {
		if (is_null($this->offers)) {
			$this->offers = array();
		}
		return $this->offers;
	}

	/**
	 * Sets the offers
	 * @var integer
	 */
	function setOffers($arg0) {
		if (is_array($arg0)) {
			$this->offers = $arg0;
			array_walk($this->offers, function(&$val, $key) { 
				$offer = new \Flux\Link\Offer();
				$offer->setOfferId($val);
				if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == '') {
					$offer->setOfferName($offer->getOffer()->getName());
				}
				$val = $offer; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->offers = array();
			} else if (is_string($arg0)) {
				$this->offers = implode(',', $arg0);
				array_walk($this->offers, function(&$val, $key) { 
					$offer = new \Flux\Link\Offer();
					$offer->setOfferId($val);
					if (\MongoId::isValid($offer->getOfferId()) && $offer->getOfferName() == '') {
						$offer->setOfferName($offer->getOffer()->getName());
					}
					$val = $offer; 
				});
			}
		}
		$this->addModifiedColumn('offers');
		return $this;
	}

	/**
	 * Returns the domain_groups
	 * @return integer
	 */
	function getDomainGroups() {
		if (is_null($this->domain_groups)) {
			$this->domain_groups = array();
		}
		return $this->domain_groups;
	}

	/**
	 * Sets the domain_groups
	 * @var integer
	 */
	function setDomainGroups($arg0) {
		if (is_array($arg0)) {
			$this->domain_groups = $arg0;
			array_walk($this->domain_groups, function(&$val, $key) { 
				$domain_group = new \Flux\Link\DomainGroup();
				$domain_group->setDomainGroupId($val);
				if (\MongoId::isValid($domain_group->getDomainGroupId()) && $domain_group->getDomainGroupName() == '') {
					$domain_group->setDomainGroupName($domain_group->getDomainGroup()->getName());
				}
				$val = $domain_group; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->domain_groups = array();
			} else if (is_string($arg0)) {
				$this->domain_groups = implode(',', $arg0);
				array_walk($this->domain_groups, function(&$val, $key) { 
					$domain_group = new \Flux\Link\DomainGroup();
					$domain_group->setDomainGroupId($val);
					if (\MongoId::isValid($domain_group->getDomainGroupId()) && $domain_group->getDomainGroupName() == '') {
						$domain_group->setDomainGroupName($domain_group->getDomainGroup()->getName());
					}
					$val = $domain_group; 
				});
			}
		}
		$this->addModifiedColumn('domain_groups');
		return $this;
	}

	/**
	 * Returns the verticals
	 * @return integer
	 */
	function getVerticals() {
		if (is_null($this->verticals)) {
			$this->verticals = array();
		}
		return $this->verticals;
	}

	/**
	 * Sets the verticals
	 * @var integer
	 */
	function setVerticals($arg0) {
		if (is_array($arg0)) {
			$this->verticals = $arg0;
			array_walk($this->verticals, function(&$val, $key) { 
				$vertical = new \Flux\Link\Vertical();
				$vertical->setVerticalId($val);
				if (\MongoId::isValid($vertical->getVerticalId()) && $vertical->getVerticalName() == '') {
					$vertical->setVerticalName($vertical->getVertical()->getName());
				}
				$val = $vertical; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->verticals = array();
			} else if (is_string($arg0)) {
				$this->verticals = implode(',', $arg0);
				array_walk($this->verticals, function(&$val, $key) { 
					$vertical = new \Flux\Link\Vertical();
					$vertical->setVerticalId($val);
					if (\MongoId::isValid($vertical->getVerticalId()) && $vertical->getVerticalName() == '') {
						$vertical->setVerticalName($vertical->getVertical()->getName());
					}
					$val = $vertical; 
				});
			}
		}
		$this->addModifiedColumn('verticals');
		return $this;
	}

	/**
	 * Returns the data_fields
	 * @return integer
	 */
	function getDataFields() {
		if (is_null($this->data_fields)) {
			$this->data_fields = array();
		}
		return $this->data_fields;
	}

	/**
	 * Sets the data_fields
	 * @var integer
	 */
	function setDataFields($arg0) {
		if (is_array($arg0)) {
			$this->data_fields = $arg0;
			array_walk($this->data_fields, function(&$val, $key) { 
				$data_field = new \Flux\Link\DataField();
				$data_field->setDataFieldId($val);
				if (\MongoId::isValid($data_field->getDataFieldId()) && $data_field->getDataFieldName() == '') {
					$data_field->setDataFieldName($data_field->getDataField()->getName());
				}
				$val = $data_field; 
			});
		} else if (is_string($arg0)) {
			if (trim($arg0) == '') {
				$this->data_fields = array();
			} else if (is_int($arg0)) {
				$this->data_fields = array((int)$arg0);
				array_walk($this->data_fields, function(&$val, $key) { 
					$data_field = new \Flux\Link\DataField();
					$data_field->setDataFieldId($val);
					if (\MongoId::isValid($data_field->getDataFieldId()) && $data_field->getDataFieldName() == '') {
						$data_field->setDataFieldName($data_field->getDataField()->getName());
					}
					$val = $data_field; 
				});
			} else if (is_string($arg0)) {
				$this->data_fields = implode(',', $arg0);
				array_walk($this->data_fields, function(&$val, $key) { 
					$data_field = new \Flux\Link\DataField();
					$data_field->setDataFieldId($val);
					if (\MongoId::isValid($data_field->getDataFieldId()) && $data_field->getDataFieldName() == '') {
						$data_field->setDataFieldName($data_field->getDataField()->getName());
					}
					$val = $data_field; 
				});
			}
		}
		$this->addModifiedColumn('data_fields');
		return $this;
	}
	
    /**
	 * Returns the fulfillment
	 * @return \Flux\Link\Fulfillment
	 */
	function getFulfillment() {
		if (is_null($this->fulfillment)) {
			$this->fulfillment = new \Flux\Link\Fulfillment();
		}
		return $this->fulfillment;
	}
	
	/**
	 * Sets the fulfillment
	 * @var integer|array
	 */
	function setFulfillment($arg0) {
		if (is_array($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->populate($arg0);
			if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == "") {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if (is_string($arg0)) {
			$fulfillment = $this->getFulfillment();
			$fulfillment->setFulfillmentId($arg0);
			if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == "") {
				$fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
			}
			$this->fulfillment = $fulfillment;
		} else if ($arg0 instanceof \MongoId) {
		    $fulfillment = $this->getFulfillment();
		    $fulfillment->setFulfillmentId($arg0);
		    if (\MongoId::isValid($fulfillment->getFulfillmentId()) && $fulfillment->getFulfillmentName() == "") {
		        $fulfillment->setFulfillmentName($fulfillment->getFulfillment()->getName());
		    }
		    $this->fulfillment = $fulfillment;
		}
		$this->addModifiedColumn('fulfillment');
		return $this;
	}
	
	/**
	 * Returns the split
	 * @return \Flux\Link\Split
	 */
	function getSplit() {
		if (is_null($this->split)) {
			$this->split = new \Flux\Link\Split();
		}
		return $this->split;
	}
	
	/**
	 * Sets the split
	 * @var integer|array
	 */
	function setSplit($arg0) {
		if (is_array($arg0)) {
			$split = $this->getSplit();
			$split->populate($arg0);
			if (\MongoId::isValid($split->getSplitId()) && $split->getSplitName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		} else if (is_string($arg0)) {
			$split = $this->getSplit();
			$split->setSplitId($arg0);
			if (\MongoId::isValid($split->getSplitId()) && $split->getSplitName() == "") {
				$split->setSplitName($split->getSplit()->getName());
			}
			$this->split = $split;
		} else if ($arg0 instanceof \MongoId) {
		    $split = $this->getSplit();
		    $split->setSplitId($arg0);
		    if (\MongoId::isValid($split->getSplitId()) && $split->getSplitName() == "") {
		        $split->setSplitName($split->getSplit()->getName());
		    }
		    $this->split = $split;
		}
		$this->addModifiedColumn('split');
		return $this;
	}
}
