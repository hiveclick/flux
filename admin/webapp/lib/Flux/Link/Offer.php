<?php
namespace Flux\Link;

class Offer extends BasicLink {
	
	private $offer;
	
	/**
	 * Returns the offer_id
	 * @return integer
	 */
	function getOfferId() {
		return parent::getId();
	}
	
	/**
	 * Sets the offer_id
	 * @var integer
	 */
	function setOfferId($arg0) {
		return parent::setId($arg0);
	}
	
	/**
	 * Returns the offer_name
	 * @return string
	 */
	function getOfferName() {
		return parent::getName();
	}
	
	/**
	 * Sets the offer_name
	 * @var string
	 */
	function setOfferName($arg0) {
		return $this->setName($arg0);
	}
	
	/**
	 * Returns the offer
	 * @return \Flux\Offer
	 */
	function getOffer() {
		if (is_null($this->offer)) {
			$this->offer = new \Flux\Offer();
			$this->offer->setId($this->getId());
			$this->offer->query();
		}
		return $this->offer;
	}
}